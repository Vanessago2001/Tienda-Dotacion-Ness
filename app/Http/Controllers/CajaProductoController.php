<?php

namespace App\Http\Controllers;

use App\Models\CajaAperturaCierre;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Venta;
use App\Models\VentaDetalle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CajaProductoController extends Controller
{
    public function index()
    {
        $clientes = Customer::orderBy('name')->get();

        $productos = Product::where('stock', '>', 0)
            ->orderBy('name')
            ->get();

        $ventasHoy = Venta::with(['cliente', 'detalles.product', 'factura'])
            ->whereDate('fecha', now()->toDateString())
            ->latest()
            ->get();

        $totalVentasHoy = Venta::whereDate('fecha', now()->toDateString())
            ->where('estado', 'pagada')
            ->sum('total');

        $totalEfectivoHoy = Venta::whereDate('fecha', now()->toDateString())
            ->where('estado', 'pagada')
            ->where('metodo_pago', 'efectivo')
            ->sum('total');

        $cantidadVentasHoy = Venta::whereDate('fecha', now()->toDateString())
            ->where('estado', 'pagada')
            ->count();

        $cajaAbierta = CajaAperturaCierre::where('estado', 'abierta')->exists();

        return view('caja_productos.index', compact(
            'clientes',
            'productos',
            'ventasHoy',
            'totalVentasHoy',
            'totalEfectivoHoy',
            'cantidadVentasHoy',
            'cajaAbierta'
        ));
    }

    public function store(Request $request)
    {
        $cajaAbierta = CajaAperturaCierre::where('estado', 'abierta')->exists();

        if (!$cajaAbierta) {
            return redirect()->route('caja.productos.index')
                ->withErrors(['caja' => 'No puedes registrar ventas porque no hay una caja abierta.']);
        }

        $request->validate([
            'cliente_id' => ['nullable', 'exists:customers,id'],
            'nombre_cliente' => ['nullable', 'string', 'max:255'],
            'documento_cliente' => [
                'nullable', 
                'string', 
                'max:50',
                function ($attribute, $value, $fail) use ($request) {
                    if (!$request->cliente_id && \App\Models\Customer::where('document', $value)->exists()) {
                        $fail('El documento "'.$value.'" ya está registrado. Por favor, seleccione el cliente de la lista desplegable en lugar de ingresarlo como nuevo.');
                    }
                }
            ],
            'metodo_pago' => ['required', 'in:efectivo,transferencia,tarjeta,nequi,daviplata'],
            'dinero_recibido' => ['nullable', 'numeric', 'min:0'],
            'productos' => ['required', 'array', 'min:1'],
            'productos.*.product_id' => ['required', 'exists:products,id'],
            'productos.*.cantidad' => ['required', 'integer', 'min:1'],
        ]);

        DB::transaction(function () use ($request) {
            $clienteId = $request->cliente_id;

            if (!$clienteId && $request->filled('documento_cliente')) {
                $cliente = Customer::firstOrCreate(
                    ['document' => $request->documento_cliente],
                    [
                        'name' => $request->filled('nombre_cliente') ? $request->nombre_cliente : 'Sin nombre',
                        'document_type' => 'CC',
                        'email' => 'caja_' . uniqid() . '@tienda.com',
                        'phone' => '0000000000',
                        'address' => 'Desconocida',
                        'quota' => 0,
                        'photo' => 'https://via.placeholder.com/150'
                    ]
                );
                $clienteId = $cliente->id;
            } elseif (!$clienteId && $request->filled('nombre_cliente')) {
                $cliente = Customer::firstOrCreate(
                    ['name' => $request->nombre_cliente],
                    [
                        'document' => rand(1000000, 9999999),
                        'document_type' => 'CC',
                        'email' => 'caja_' . uniqid() . '@tienda.com',
                        'phone' => '0000000000',
                        'address' => 'Desconocida',
                        'quota' => 0,
                        'photo' => 'https://via.placeholder.com/150'
                    ]
                );
                $clienteId = $cliente->id;
            }

            $items = collect($request->productos);
            $subtotalVenta = 0;
            $detallesCalculados = [];

            foreach ($items as $item) {
                $producto = Product::lockForUpdate()->findOrFail($item['product_id']);
                $cantidad = (int) $item['cantidad'];

                if ($producto->stock < $cantidad) {
                    throw ValidationException::withMessages([
                        'productos' => "No hay stock suficiente para el producto: {$producto->name}. Stock disponible: {$producto->stock}",
                    ]);
                }

                $precioUnitario = (float) $producto->price;
                $subtotalProducto = $precioUnitario * $cantidad;
                $subtotalVenta += $subtotalProducto;

                $detallesCalculados[] = [
                    'producto' => $producto,
                    'cantidad' => $cantidad,
                    'precio_unitario' => $precioUnitario,
                    'subtotal' => $subtotalProducto,
                ];
            }

            $totalVenta = $subtotalVenta;
            $dineroRecibido = (float) ($request->dinero_recibido ?? 0);
            $cambio = 0;

            if ($request->metodo_pago === 'efectivo') {
                if ($dineroRecibido < $totalVenta) {
                    throw ValidationException::withMessages([
                        'dinero_recibido' => 'El dinero recibido no puede ser menor al total de la venta.',
                    ]);
                }

                $cambio = $dineroRecibido - $totalVenta;
            }

            if ($request->metodo_pago !== 'efectivo') {
                $dineroRecibido = $totalVenta;
                $cambio = 0;
            }

            $venta = Venta::create([
                'cliente_id' => $clienteId,
                'user_id' => auth()->id(),
                'numero_venta' => 'V-' . now()->format('YmdHis'),
                'metodo_pago' => $request->metodo_pago,
                'subtotal' => $subtotalVenta,
                'total' => $totalVenta,
                'dinero_recibido' => $dineroRecibido,
                'cambio' => $cambio,
                'estado' => 'pagada',
                'fecha' => now()->toDateString(),
            ]);

            foreach ($detallesCalculados as $detalle) {
                VentaDetalle::create([
                    'venta_id' => $venta->id,
                    'product_id' => $detalle['producto']->id,
                    'cantidad' => $detalle['cantidad'],
                    'precio_unitario' => $detalle['precio_unitario'],
                    'subtotal' => $detalle['subtotal'],
                ]);

                // Descontar stock del producto y registrar el movimiento de inventario
                $productoModel = $detalle['producto'];
                $stockAnterior = (int) $productoModel->stock;
                $productoModel->decrement('stock', $detalle['cantidad']);
                $stockNuevo = $stockAnterior - $detalle['cantidad'];

                \App\Models\MovimientoInventario::registrar(
                    $productoModel->id,
                    'salida',
                    $detalle['cantidad'],
                    $stockAnterior,
                    $stockNuevo,
                    'Venta ' . $venta->numero_venta
                );
            }

            // Registrar Movimiento de Caja
            if ($totalVenta > 0) {
                \App\Models\MovimientoCaja::create([
                    'tipo' => 'entrada',
                    'concepto' => 'Venta POS ' . $venta->numero_venta,
                    'categoria' => 'otro',
                    'valor' => $totalVenta,
                    'metodo_pago' => $request->metodo_pago,
                    'descripcion' => 'Ingreso por venta POS. Venta: ' . $venta->numero_venta,
                    'fecha' => now()->toDateString(),
                    'estado' => 'activo',
                ]);
            }
        });

        return redirect()
            ->route('caja.productos.index')
            ->with('success', 'Venta registrada correctamente.');
    }

    public function destroyCliente(Customer $cliente)
    {
        $cliente->delete();
        return redirect()
            ->route('caja.productos.index')
            ->with('success', 'Cliente eliminado correctamente.');
    }
}

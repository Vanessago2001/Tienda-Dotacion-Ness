<?php

namespace App\Http\Controllers;

use App\Models\Factura;
use App\Models\FacturaDetalle;
use App\Models\NotaCredito;
use App\Models\NotaCreditoDetalle;
use App\Models\Product;
use App\Models\Venta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FacturaController extends Controller
{
    public function imprimir(Factura $factura)
    {
        $factura->load([
            'cliente',
            'venta',
            'detalles.producto'
        ]);

        return view('facturas.imprimir', compact('factura'));
    }

    public function index(Request $request)
    {
        $query = Factura::with(['cliente', 'venta', 'detalles.producto', 'notasCredito']);

        if ($request->filled('numero')) {
            $query->where('numero_factura', 'like', '%' . $request->numero . '%');
        }

        if ($request->filled('fecha')) {
            $query->whereDate('fecha_emision', $request->fecha);
        }

        if ($request->filled('cliente')) {
            $query->whereHas('cliente', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->cliente . '%');
            });
        }

        $facturas = $query->latest()->get();

        return view('facturas.index', compact('facturas'));
    }

    public function generarDesdeVenta(Venta $venta)
    {
        $venta->load(['cliente', 'detalles.producto']);

        $facturaExistente = Factura::where('venta_id', $venta->id)->first();

        if ($facturaExistente) {
            return redirect()
                ->route('facturas.imprimir', $facturaExistente->id);
        }

        $facturaCreada = null;

        DB::transaction(function () use ($venta, &$facturaCreada) {

            $factura = Factura::create([
                'venta_id' => $venta->id,
                'cliente_id' => $venta->cliente_id,
                'numero_factura' => $this->generarNumeroFactura(),
                'subtotal' => $venta->subtotal,
                'total' => $venta->total,
                'estado' => 'emitida',
                'fecha_emision' => now()->toDateString(),
            ]);

            foreach ($venta->detalles as $detalle) {

                FacturaDetalle::create([
                    'factura_id' => $factura->id,
                    'producto_id' => $detalle->product_id,
                    'cantidad' => $detalle->cantidad,
                    'precio_unitario' => $detalle->precio_unitario,
                    'subtotal' => $detalle->subtotal,
                ]);
            }

            $facturaCreada = $factura;
        });

        return redirect()
            ->route('facturas.imprimir', $facturaCreada->id);
    }

    public function show(Factura $factura)
    {
        $factura->load([
            'cliente',
            'venta',
            'detalles.producto',
            'notasCredito'
        ]);

        return view('facturas.show', compact('factura'));
    }

    public function edit(Factura $factura)
    {
        $factura->load([
            'cliente',
            'detalles.producto'
        ]);

        return view('facturas.edit', compact('factura'));
    }

    public function update(Request $request, Factura $factura)
    {
        $request->validate([
            'fecha_emision' => 'required|date',
        ]);

        $factura->update([
            'fecha_emision' => $request->fecha_emision,
        ]);

        return redirect()
            ->route('facturas.index')
            ->with('success', 'Factura actualizada correctamente.');
    }

    public function anular(Request $request, Factura $factura)
    {
        $request->validate([
            'motivo_anulacion' => ['required', 'string', 'max:500'],
        ]);

        if ($factura->estado === 'anulada') {
            return redirect()
                ->route('facturas.index')
                ->with('error', 'Esta factura ya fue anulada.');
        }

        $factura->load('detalles.producto');

        DB::transaction(function () use ($factura, $request) {

            $notaCredito = NotaCredito::create([
                'factura_id' => $factura->id,
                'numero_nota_credito' => $this->generarNumeroNotaCredito(),
                'subtotal' => $factura->subtotal,
                'total' => $factura->total,
                'motivo' => $request->motivo_anulacion,
                'fecha' => now()->toDateString(),
            ]);

            foreach ($factura->detalles as $detalle) {

                NotaCreditoDetalle::create([
                    'nota_credito_id' => $notaCredito->id,
                    'producto_id' => $detalle->producto_id,
                    'cantidad' => $detalle->cantidad,
                    'precio_unitario' => $detalle->precio_unitario,
                    'subtotal' => $detalle->subtotal,
                ]);

                // Reintegrar stock y registrar el movimiento de inventario (entrada)
                $producto = Product::lockForUpdate()->find($detalle->producto_id);
                if ($producto) {
                    $stockAnterior = (int) $producto->stock;
                    $producto->increment('stock', $detalle->cantidad);
                    $stockNuevo = $stockAnterior + $detalle->cantidad;

                    \App\Models\MovimientoInventario::registrar(
                        $producto->id,
                        'entrada',
                        $detalle->cantidad,
                        $stockAnterior,
                        $stockNuevo,
                        'Anulación factura ' . $factura->numero_factura
                    );
                }
            }

            $factura->update([
                'estado' => 'anulada',
                'fecha_anulacion' => now()->toDateString(),
                'motivo_anulacion' => $request->motivo_anulacion,
                'anulada_por' => auth()->id(),
            ]);

            $factura->venta()->update([
                'estado' => 'anulada',
            ]);
        });

        \App\Models\Auditoria::registrar('anular', 'Facturas',
            "Anuló la factura {$factura->numero_factura}. Motivo: {$request->motivo_anulacion}");

        return redirect()
            ->route('facturas.index')
            ->with('success', 'Factura anulada, nota crédito generada e inventario reintegrado correctamente.');
    }

    public function notasCredito()
    {
        $notasCredito = NotaCredito::with([
            'factura.cliente',
            'detalles.producto'
        ])
        ->latest()
        ->get();

        return view('notas_credito.index', compact('notasCredito'));
    }

    private function generarNumeroFactura(): string
    {
        $ultimaFactura = Factura::latest('id')->first();
        $siguiente = $ultimaFactura ? $ultimaFactura->id + 1 : 1;

        return 'FAC-' . str_pad($siguiente, 6, '0', STR_PAD_LEFT);
    }

    private function generarNumeroNotaCredito(): string
    {
        $ultimaNota = NotaCredito::latest('id')->first();
        $siguiente = $ultimaNota ? $ultimaNota->id + 1 : 1;

        return 'NC-' . str_pad($siguiente, 6, '0', STR_PAD_LEFT);
    }
}
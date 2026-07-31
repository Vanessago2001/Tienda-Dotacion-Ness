<?php

namespace App\Http\Controllers;

use App\Models\Auditoria;
use App\Models\MovimientoInventario;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class InventarioController extends Controller
{
    /**
     * Historial de movimientos de inventario con filtros.
     */
    public function index(Request $request)
    {
        $base = MovimientoInventario::with(['product', 'usuario']);

        if ($request->filled('product_id')) {
            $base->where('product_id', $request->product_id);
        }
        if ($request->filled('tipo')) {
            $base->where('tipo', $request->tipo);
        }
        if ($request->filled('desde')) {
            $base->whereDate('fecha', '>=', $request->desde);
        }
        if ($request->filled('hasta')) {
            $base->whereDate('fecha', '<=', $request->hasta);
        }

        $movimientos = $base->latest('id')->paginate(20)->withQueryString();
        $productos = Product::orderBy('name')->get();

        return view('inventario.index', compact('movimientos', 'productos'));
    }

    /**
     * Formulario para registrar un movimiento manual.
     */
    public function create()
    {
        $productos = Product::orderBy('name')->get();

        return view('inventario.create', compact('productos'));
    }

    /**
     * Aplica el movimiento al stock y lo registra.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'tipo'       => ['required', 'in:entrada,salida,ajuste'],
            'cantidad'   => ['required', 'integer', 'min:0'],
            'motivo'     => ['nullable', 'string', 'max:255'],
        ]);

        DB::transaction(function () use ($data) {
            $producto = Product::lockForUpdate()->findOrFail($data['product_id']);
            $anterior = (int) $producto->stock;
            $cantidad = (int) $data['cantidad'];

            switch ($data['tipo']) {
                case 'entrada':
                    if ($cantidad < 1) {
                        throw ValidationException::withMessages(['cantidad' => 'La cantidad debe ser mayor a 0.']);
                    }
                    $nuevo = $anterior + $cantidad;
                    $magnitud = $cantidad;
                    break;

                case 'salida':
                    if ($cantidad < 1) {
                        throw ValidationException::withMessages(['cantidad' => 'La cantidad debe ser mayor a 0.']);
                    }
                    if ($cantidad > $anterior) {
                        throw ValidationException::withMessages(['cantidad' => "No hay stock suficiente. Stock actual: {$anterior}."]);
                    }
                    $nuevo = $anterior - $cantidad;
                    $magnitud = $cantidad;
                    break;

                default: // ajuste: la cantidad es el nuevo stock absoluto
                    $nuevo = $cantidad;
                    $magnitud = abs($nuevo - $anterior);
                    break;
            }

            $producto->stock = $nuevo;
            $producto->save();

            MovimientoInventario::registrar(
                $producto->id,
                $data['tipo'],
                $magnitud,
                $anterior,
                $nuevo,
                $data['motivo'] ?? null
            );

            Auditoria::registrar('movimiento', 'Inventario',
                ucfirst($data['tipo']) . " de {$magnitud} en {$producto->name} (stock {$anterior} → {$nuevo})");
        });

        return redirect()->route('inventario.index')
            ->with('success', 'Movimiento de inventario registrado correctamente.');
    }
}

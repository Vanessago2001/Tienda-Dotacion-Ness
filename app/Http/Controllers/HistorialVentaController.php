<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use Illuminate\Http\Request;

class HistorialVentaController extends Controller
{
    /**
     * Historial completo de ventas con filtros.
     */
    public function index(Request $request)
    {
        $base = Venta::with(['cliente', 'usuario', 'factura']);

        // Filtros
        if ($request->filled('desde')) {
            $base->whereDate('fecha', '>=', $request->desde);
        }

        if ($request->filled('hasta')) {
            $base->whereDate('fecha', '<=', $request->hasta);
        }

        if ($request->filled('estado')) {
            $base->where('estado', $request->estado);
        }

        if ($request->filled('metodo_pago')) {
            $base->where('metodo_pago', $request->metodo_pago);
        }

        if ($request->filled('cliente')) {
            $base->whereHas('cliente', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->cliente . '%');
            });
        }

        // Totales del resultado filtrado (antes de paginar)
        $totalVendido = (clone $base)->where('estado', 'pagada')->sum('total');
        $cantidadVentas = (clone $base)->count();

        $ventas = $base->latest('fecha')->latest('id')
            ->paginate(20)
            ->withQueryString();

        return view('historial_ventas.index', compact(
            'ventas',
            'totalVendido',
            'cantidadVentas'
        ));
    }

    /**
     * Detalle de una venta.
     */
    public function show(Venta $venta)
    {
        $venta->load(['cliente', 'usuario', 'factura', 'detalles.product']);

        return view('historial_ventas.show', compact('venta'));
    }
}

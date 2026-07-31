<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use Illuminate\Http\Request;

class ReporteController extends Controller
{
    /**
     * Informe de ventas (administrador). Filtra por rango de fechas.
     */
    public function ventas(Request $request)
    {
        $desde = $request->input('desde', now()->startOfMonth()->toDateString());
        $hasta = $request->input('hasta', now()->toDateString());

        $base = Venta::where('estado', 'pagada')
            ->whereDate('fecha', '>=', $desde)
            ->whereDate('fecha', '<=', $hasta);

        $resumen = [
            'cantidad'      => (clone $base)->count(),
            'total'         => (clone $base)->sum('total'),
            'efectivo'      => (clone $base)->where('metodo_pago', 'efectivo')->sum('total'),
            'transferencia' => (clone $base)->where('metodo_pago', 'transferencia')->sum('total'),
            'tarjeta'       => (clone $base)->where('metodo_pago', 'tarjeta')->sum('total'),
            'nequi'         => (clone $base)->where('metodo_pago', 'nequi')->sum('total'),
            'daviplata'     => (clone $base)->where('metodo_pago', 'daviplata')->sum('total'),
        ];

        // Ventas por cajero
        $porCajero = (clone $base)
            ->with('usuario')
            ->selectRaw('user_id, COUNT(*) as cantidad, SUM(total) as total')
            ->groupBy('user_id')
            ->get();

        return view('reportes.ventas', compact('resumen', 'porCajero', 'desde', 'hasta'));
    }

    /**
     * Informe de la caja del día (cajero): solo las ventas del usuario logueado, hoy.
     */
    public function cajaDia(Request $request)
    {
        $hoy = now()->toDateString();
        $userId = auth()->id();

        $base = Venta::where('estado', 'pagada')
            ->where('user_id', $userId)
            ->whereDate('fecha', $hoy);

        $resumen = [
            'cantidad'      => (clone $base)->count(),
            'total'         => (clone $base)->sum('total'),
            'efectivo'      => (clone $base)->where('metodo_pago', 'efectivo')->sum('total'),
            'transferencia' => (clone $base)->where('metodo_pago', 'transferencia')->sum('total'),
            'tarjeta'       => (clone $base)->where('metodo_pago', 'tarjeta')->sum('total'),
            'nequi'         => (clone $base)->where('metodo_pago', 'nequi')->sum('total'),
            'daviplata'     => (clone $base)->where('metodo_pago', 'daviplata')->sum('total'),
        ];

        $ventas = (clone $base)->with('cliente')->latest('id')->get();

        return view('reportes.caja_dia', compact('resumen', 'ventas', 'hoy'));
    }
}

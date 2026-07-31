<?php

namespace App\Http\Controllers;

use App\Models\CajaAperturaCierre;
use App\Models\MovimientoCaja;
use App\Models\Venta;
use Illuminate\Http\Request;

class CajaAperturaCierreController extends Controller
{
    public function index()
    {
        $fechaHoy = now()->toDateString();

        $cajaAbierta = CajaAperturaCierre::where('estado', 'abierta')
            ->latest()
            ->first();

        $historialCajas = CajaAperturaCierre::latest()->take(20)->get();

        $resumen = $this->calcularResumenCaja($fechaHoy);

        return view('apertura_cierre_caja.index', compact('cajaAbierta', 'historialCajas', 'resumen'));
    }

    public function abrir(Request $request)
    {
        $request->validate([
            'saldo_inicial' => ['required', 'numeric', 'min:0'],
            'observacion_apertura' => ['nullable', 'string', 'max:500'],
        ]);

        $existeCajaAbierta = CajaAperturaCierre::where('estado', 'abierta')->exists();

        if ($existeCajaAbierta) {
            return redirect()->route('apertura-cierre-caja.index')
                ->with('error', 'Ya existe una caja abierta. Debes cerrarla antes de abrir otra.');
        }

        CajaAperturaCierre::create([
            'fecha' => now()->toDateString(),
            'saldo_inicial' => $request->saldo_inicial,
            'estado' => 'abierta',
            'fecha_apertura' => now(),
            'observacion_apertura' => $request->observacion_apertura,
        ]);

        return redirect()->route('caja.productos.index')
            ->with('success', 'Caja abierta correctamente.');
    }

    public function cerrar(Request $request, CajaAperturaCierre $caja)
    {
        $request->validate([
            'dinero_contado' => ['required', 'numeric', 'min:0'],
            'observacion_cierre' => ['nullable', 'string', 'max:500'],
        ]);

        if ($caja->estado === 'cerrada') {
            return redirect()->route('apertura-cierre-caja.index')
                ->with('error', 'Esta caja ya se encuentra cerrada.');
        }

        $resumen = $this->calcularResumenCaja($caja->fecha->toDateString());

        $saldoEsperado = $caja->saldo_inicial
            + $resumen['ventas_efectivo']
            + $resumen['entradas_adicionales']
            - $resumen['salidas_gastos'];

        $dineroContado = (float) $request->dinero_contado;
        $diferencia = $dineroContado - $saldoEsperado;

        $caja->update([
            'ventas_efectivo' => $resumen['ventas_efectivo'],
            'ventas_transferencia' => $resumen['ventas_transferencia'],
            'ventas_tarjeta' => $resumen['ventas_tarjeta'],
            'ventas_nequi' => $resumen['ventas_nequi'],
            'ventas_daviplata' => $resumen['ventas_daviplata'],
            'total_ventas' => $resumen['total_ventas'],
            'entradas_adicionales' => $resumen['entradas_adicionales'],
            'salidas_gastos' => $resumen['salidas_gastos'],
            'saldo_esperado' => $saldoEsperado,
            'dinero_contado' => $dineroContado,
            'diferencia' => $diferencia,
            'estado' => 'cerrada',
            'fecha_cierre' => now(),
            'observacion_cierre' => $request->observacion_cierre,
        ]);

        return redirect()->route('apertura-cierre-caja.index')
            ->with('success', 'Caja cerrada correctamente.');
    }

    private function calcularResumenCaja(string $fecha): array
    {
        $ventasEfectivo = Venta::whereDate('fecha', $fecha)
            ->where('estado', 'pagada')
            ->where('metodo_pago', 'efectivo')
            ->sum('total');

        $ventasTransferencia = Venta::whereDate('fecha', $fecha)
            ->where('estado', 'pagada')
            ->where('metodo_pago', 'transferencia')
            ->sum('total');

        $ventasTarjeta = Venta::whereDate('fecha', $fecha)
            ->where('estado', 'pagada')
            ->where('metodo_pago', 'tarjeta')
            ->sum('total');

        $ventasNequi = Venta::whereDate('fecha', $fecha)
            ->where('estado', 'pagada')
            ->where('metodo_pago', 'nequi')
            ->sum('total');

        $ventasDaviplata = Venta::whereDate('fecha', $fecha)
            ->where('estado', 'pagada')
            ->where('metodo_pago', 'daviplata')
            ->sum('total');

        $totalVentas = $ventasEfectivo + $ventasTransferencia + $ventasTarjeta + $ventasNequi + $ventasDaviplata;

        $entradasAdicionales = MovimientoCaja::whereDate('fecha', $fecha)
            ->where('tipo', 'entrada')
            ->where('estado', 'activo')
            ->where('concepto', 'not like', 'Venta POS%')
            ->sum('valor');

        $salidasGastos = MovimientoCaja::whereDate('fecha', $fecha)
            ->where('tipo', 'salida')
            ->where('estado', 'activo')
            ->sum('valor');

        return [
            'ventas_efectivo' => $ventasEfectivo,
            'ventas_transferencia' => $ventasTransferencia,
            'ventas_tarjeta' => $ventasTarjeta,
            'ventas_nequi' => $ventasNequi,
            'ventas_daviplata' => $ventasDaviplata,
            'total_ventas' => $totalVentas,
            'entradas_adicionales' => $entradasAdicionales,
            'salidas_gastos' => $salidasGastos,
        ];
    }
}

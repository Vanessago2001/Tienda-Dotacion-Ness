<?php

namespace App\Http\Controllers;

use App\Models\MovimientoCaja;
use App\Models\Venta;
use Illuminate\Http\Request;

class MovimientoCajaController extends Controller
{
    public function index()
    {
        $fechaHoy = now()->toDateString();

        $movimientos = MovimientoCaja::whereDate('fecha', $fechaHoy)
            ->where('estado', 'activo')
            ->latest()
            ->get();

        $entradasHoy = MovimientoCaja::whereDate('fecha', $fechaHoy)
            ->where('tipo', 'entrada')
            ->where('estado', 'activo')
            ->where('concepto', 'not like', 'Venta POS%')
            ->sum('valor');

        $salidasHoy = MovimientoCaja::whereDate('fecha', $fechaHoy)
            ->where('tipo', 'salida')
            ->where('estado', 'activo')
            ->sum('valor');

        // Check if Venta model actually exists. The user mentioned it might not.
        // Assuming Venta exists based on previous migrations output, but just in case, use try/catch or class_exists.
        // Actually, the user says "Temporalmente puedes reemplazarlo por: $ventasHoy = 0;" if it fails.
        // I will use class_exists to be safe and smart.
        if (class_exists(Venta::class)) {
            $ventasHoy = Venta::whereDate('fecha', $fechaHoy)
                ->where('estado', 'pagada')
                ->sum('total');
        } else {
            $ventasHoy = 0;
        }

        $saldoCaja = $ventasHoy + $entradasHoy - $salidasHoy;

        return view('movimientos_caja.index', compact(
            'movimientos',
            'entradasHoy',
            'salidasHoy',
            'ventasHoy',
            'saldoCaja'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tipo' => ['required', 'in:entrada,salida'],
            'concepto' => ['required', 'string', 'max:255'],
            'categoria' => [
                'required',
                'in:aporte,prestamo,ajuste_caja,arriendo,servicios,transporte,compra_insumos,nomina,mantenimiento,otro'
            ],
            'valor' => ['required', 'numeric', 'min:1'],
            'metodo_pago' => ['nullable', 'string', 'max:100'],
            'descripcion' => ['nullable', 'string'],
        ]);

        MovimientoCaja::create([
            'tipo' => $request->tipo,
            'concepto' => $request->concepto,
            'categoria' => $request->categoria,
            'valor' => $request->valor,
            'metodo_pago' => $request->metodo_pago,
            'descripcion' => $request->descripcion,
            'fecha' => now()->toDateString(),
            'estado' => 'activo',
        ]);

        return redirect()
            ->route('movimientos-caja.index')
            ->with('success', 'Movimiento registrado correctamente.');
    }

    public function anular(MovimientoCaja $movimiento)
    {
        $movimiento->update([
            'estado' => 'anulado',
        ]);

        return redirect()
            ->route('movimientos-caja.index')
            ->with('success', 'Movimiento anulado correctamente.');
    }
    public function edit(MovimientoCaja $movimiento)
    {
        return view('movimientos_caja.edit', compact('movimiento'));
    }

    public function update(Request $request, MovimientoCaja $movimiento)
    {
        $request->validate([
            'tipo' => ['required', 'in:entrada,salida'],
            'concepto' => ['required', 'string', 'max:255'],
            'categoria' => [
                'required',
                'in:aporte,prestamo,ajuste_caja,arriendo,servicios,transporte,compra_insumos,nomina,mantenimiento,otro'
            ],
            'valor' => ['required', 'numeric', 'min:1'],
            'metodo_pago' => ['nullable', 'string', 'max:100'],
            'descripcion' => ['nullable', 'string'],
        ]);

        $movimiento->update([
            'tipo' => $request->tipo,
            'concepto' => $request->concepto,
            'categoria' => $request->categoria,
            'valor' => $request->valor,
            'metodo_pago' => $request->metodo_pago,
            'descripcion' => $request->descripcion,
        ]);

        return redirect()
            ->route('movimientos-caja.index')
            ->with('success', 'Movimiento actualizado correctamente.');
    }

    public function destroy(MovimientoCaja $movimiento)
    {
        $movimiento->delete();

        return redirect()
            ->route('movimientos-caja.index')
            ->with('success', 'Movimiento eliminado correctamente.');
    }
}

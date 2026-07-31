@extends('layouts.panel')

@section('title', 'Informe de Ventas')
@section('titulo', '📊 Informe de Ventas')

@section('nav')
    <a href="{{ route('historial-ventas.index') }}" class="btn-modulo">Historial</a>
@endsection

@section('content')

<div class="header-card">
    <h1>Informe de ventas</h1>
    <p>Resumen de ventas pagadas por rango de fechas, discriminado por método de pago y por cajero.</p>
</div>

<form method="GET" action="{{ route('reportes.ventas') }}" class="filtros">
    <div>
        <label>Desde</label>
        <input type="date" name="desde" value="{{ $desde }}">
    </div>
    <div>
        <label>Hasta</label>
        <input type="date" name="hasta" value="{{ $hasta }}">
    </div>
    <div class="acciones">
        <button type="submit" class="btn btn-primary">Generar</button>
    </div>
</form>

<div class="stats">
    <div class="stat-card"><h3>Ventas</h3><p>{{ $resumen['cantidad'] }}</p></div>
    <div class="stat-card"><h3>Total vendido</h3><p>${{ number_format($resumen['total'], 0, ',', '.') }}</p></div>
</div>

<h3 style="margin:20px 0 10px; color:#0f172a;">Por método de pago</h3>
<table class="table-modern">
    <thead>
        <tr><th>Método</th><th>Total</th></tr>
    </thead>
    <tbody>
        <tr><td>Efectivo</td><td>${{ number_format($resumen['efectivo'], 0, ',', '.') }}</td></tr>
        <tr><td>Transferencia</td><td>${{ number_format($resumen['transferencia'], 0, ',', '.') }}</td></tr>
        <tr><td>Tarjeta</td><td>${{ number_format($resumen['tarjeta'], 0, ',', '.') }}</td></tr>
        <tr><td>Nequi</td><td>${{ number_format($resumen['nequi'], 0, ',', '.') }}</td></tr>
        <tr><td>Daviplata</td><td>${{ number_format($resumen['daviplata'], 0, ',', '.') }}</td></tr>
        <tr style="background:#f0fdfa;">
            <td><strong>TOTAL</strong></td>
            <td><strong style="color:#0f766e;">${{ number_format($resumen['total'], 0, ',', '.') }}</strong></td>
        </tr>
    </tbody>
</table>

<h3 style="margin:24px 0 10px; color:#0f172a;">Por cajero</h3>
@if($porCajero->count())
<table class="table-modern">
    <thead>
        <tr><th>Cajero</th><th>N° ventas</th><th>Total</th></tr>
    </thead>
    <tbody>
        @foreach($porCajero as $fila)
            <tr>
                <td>{{ $fila->usuario?->name ?? '—' }}</td>
                <td>{{ $fila->cantidad }}</td>
                <td style="color:#0f766e; font-weight:bold;">${{ number_format($fila->total, 0, ',', '.') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
@else
<div class="empty"><p>No hay ventas en el rango seleccionado.</p></div>
@endif

@endsection

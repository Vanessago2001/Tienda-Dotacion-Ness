@extends('layouts.panel')

@section('title', 'Informe de Caja del Día')
@section('titulo', '🧾 Caja del Día')

@section('nav')
    <a href="{{ route('caja.productos.index') }}" class="btn-modulo">Caja</a>
@endsection

@section('content')

<div class="header-card">
    <h1>Informe de caja del día</h1>
    <p>Resumen de tus ventas de hoy ({{ \Illuminate\Support\Carbon::parse($hoy)->format('d/m/Y') }}), cajero: <strong>{{ auth()->user()->name }}</strong>.</p>
</div>

<div class="stats">
    <div class="stat-card"><h3>Ventas de hoy</h3><p>{{ $resumen['cantidad'] }}</p></div>
    <div class="stat-card"><h3>Total del día</h3><p>${{ number_format($resumen['total'], 0, ',', '.') }}</p></div>
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

<h3 style="margin:24px 0 10px; color:#0f172a;">Ventas de hoy</h3>
@if($ventas->count())
<table class="table-modern">
    <thead>
        <tr><th>N° Venta</th><th>Cliente</th><th>Método</th><th>Total</th></tr>
    </thead>
    <tbody>
        @foreach($ventas as $v)
            <tr>
                <td><strong>{{ $v->numero_venta }}</strong></td>
                <td>{{ $v->cliente?->name ?? 'Sin cliente' }}</td>
                <td style="text-transform:capitalize;">{{ $v->metodo_pago }}</td>
                <td style="color:#0f766e; font-weight:bold;">${{ number_format($v->total, 0, ',', '.') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
@else
<div class="empty"><p>No has registrado ventas hoy.</p></div>
@endif

@endsection

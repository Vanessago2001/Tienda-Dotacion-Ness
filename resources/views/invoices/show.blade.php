@extends('invoices.layout')
@section('content')
    <h1>Factura #{{ $invoice->id }}</h1>
    <div style="background: #fff; border: 1px solid #e2e8f0; padding: 20px; border-radius: 8px;">
        <p><strong>Cliente:</strong> {{ $invoice->customer }}</p>
        <p><strong>Fecha:</strong> {{ $invoice->date->format('d/m/Y H:i') }}</p>
        <p><strong>Estado:</strong> {{ $invoice->state }}</p>
        <hr>
        <p><strong>Descripción:</strong><br>{{ $invoice->description }}</p>
        <p><strong>Cantidad:</strong> {{ $invoice->amount }}</p>
        <p><strong>Precio Unitario:</strong> ${{ number_format($invoice->price, 0) }}</p>
        <div class="total-box">
            Total Facturado: ${{ number_format($invoice->amount * $invoice->price, 0) }}
        </div>
    </div>
    <div style="margin-top: 20px;">
        <a href="{{ route('invoices.index') }}">Volver al listado</a> | 
        <a href="{{ route('invoices.edit', $invoice) }}">Editar factura</a>
    </div>
@endsection
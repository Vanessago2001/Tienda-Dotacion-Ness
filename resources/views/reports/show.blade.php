@extends('reports.layout')
@section('content')
    <h1>Detalle del Reporte #{{ $report->id }}</h1>
    <div style="line-height: 2;">
        <p><strong>Tipo:</strong> {{ $report->type }}</p>
        <p><strong>Fecha:</strong> {{ $report->date->format('d/m/Y h:i A') }}</p>
        <p><strong>Estado:</strong> {{ $report->state }}</p>
        <p><strong>Factura Relacionada:</strong> {{ $report->invoice }}</p>
        <p><strong>Responsable (Cajero):</strong> {{ $report->cashier }}</p>
        <p><strong>Creado el:</strong> {{ $report->created_at }}</p>
    </div>
    <hr>
    <a href="{{ route('reports.edit', $report) }}">Editar</a> | 
    <a href="{{ route('reports.index') }}">Volver</a>
@endsection
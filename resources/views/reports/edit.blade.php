@extends('reports.layout')
@section('content')
    <h1>Editar Reporte #{{ $report->id }}</h1>
    <form action="{{ route('reports.update', $report) }}" method="POST">
        @csrf @method('PUT')
        
        <label>Tipo de Reporte</label>
        <input type="text" name="type" value="{{ old('type', $report->type) }}">

        <label>Fecha y Hora</label>
        <input type="datetime-local" name="date" value="{{ old('date', $report->date->format('Y-m-d\TH:i')) }}">

        <label>Estado</label>
        <select name="state">
            <option value="Pendiente" {{ $report->state == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
            <option value="Completado" {{ $report->state == 'Completado' ? 'selected' : '' }}>Completado</option>
            <option value="Cancelado" {{ $report->state == 'Cancelado' ? 'selected' : '' }}>Cancelado</option>
        </select>

        <label>Número de Factura</label>
        <input type="text" name="invoice" value="{{ old('invoice', $report->invoice) }}">

        <label>Nombre del Cajero</label>
        <input type="text" name="cashier" value="{{ old('cashier', $report->cashier) }}">

        <button type="submit">Actualizar Reporte</button>
    </form>
@endsection
@extends('reports.layout')
@section('content')
    <h1>Generar Nuevo Reporte</h1>
    <form action="{{ route('reports.store') }}" method="POST">
        @csrf
        <label>Tipo de Reporte</label>
        <input type="text" name="type" placeholder="Ej: Venta Diaria, Inventario">

        <label>Fecha y Hora</label>
        <input type="datetime-local" name="date">

        <label>Estado</label>
        <select name="state">
            <option value="Pendiente">Pendiente</option>
            <option value="Completado">Completado</option>
            <option value="Cancelado">Cancelado</option>
        </select>

        <label>Número de Factura</label>
        <input type="text" name="invoice" placeholder="FAC-001">

        <label>Nombre del Cajero</label>
        <input type="text" name="cashier">

        <button type="submit">Guardar Reporte</button>
    </form>
@endsection
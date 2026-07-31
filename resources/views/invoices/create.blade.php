@extends('invoices.layout')
@section('content')
    <h1>Crear Nueva Factura</h1>
    <form action="{{ route('invoices.store') }}" method="POST">
        @csrf
        <label>Nombre del Cliente</label>
        <input type="text" name="customer" placeholder="Nombre completo o Razón Social">

        <label>Fecha de Emisión</label>
        <input type="datetime-local" name="date">

        <label>Descripción del Servicio/Producto</label>
        <textarea name="description" rows="3"></textarea>

        <div style="display: flex; gap: 20px;">
            <div style="flex: 1;">
                <label>Cantidad (Amount)</label>
                <input type="number" name="amount" value="1">
            </div>
            <div style="flex: 1;">
                <label>Precio Unitario</label>
                <input type="number" name="price" value="0">
            </div>
        </div>

        <label>Estado de la Factura</label>
        <select name="state">
            <option value="Pendiente">Pendiente de Pago</option>
            <option value="Pagada">Pagada</option>
            <option value="Anulada">Anulada</option>
        </select>

        <button type="submit" style="margin-top: 25px;">Guardar Factura</button>
    </form>
@endsection
@extends('invoices.layout')
@section('content')
    <h1>Editar Factura #{{ $invoice->id }}</h1>
    <form action="{{ route('invoices.update', $invoice) }}" method="POST">
        @csrf @method('PUT')
        
        <label>Cliente</label>
        <input type="text" name="customer" value="{{ old('customer', $invoice->customer) }}">

        <label>Fecha</label>
        <input type="datetime-local" name="date" value="{{ old('date', $invoice->date->format('Y-m-d\TH:i')) }}">

        <label>Descripción</label>
        <textarea name="description" rows="3">{{ old('description', $invoice->description) }}</textarea>

        <div style="display: flex; gap: 20px;">
            <div style="flex: 1;">
                <label>Cantidad</label>
                <input type="number" name="amount" value="{{ old('amount', $invoice->amount) }}">
            </div>
            <div style="flex: 1;">
                <label>Precio</label>
                <input type="number" name="price" value="{{ old('price', $invoice->price) }}">
            </div>
        </div>

        <label>Estado</label>
        <select name="state">
            <option value="Pendiente" {{ $invoice->state == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
            <option value="Pagada" {{ $invoice->state == 'Pagada' ? 'selected' : '' }}>Pagada</option>
            <option value="Anulada" {{ $invoice->state == 'Anulada' ? 'selected' : '' }}>Anulada</option>
        </select>

        <button type="submit" style="margin-top: 25px; background: #2f855a;">Actualizar Factura</button>
    </form>
@endsection
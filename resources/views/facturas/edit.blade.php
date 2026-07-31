@extends('invoices.layout')

@section('title', 'Editar Factura')

@section('content')
<div style="background: rgba(255,255,255,.90); backdrop-filter: blur(12px); border-radius: 24px; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,.06); max-width: 600px; margin: 0 auto;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid #e2e8f0; padding-bottom: 20px;">
        <div>
            <h1 style="color: #1e293b; font-size: 28px; margin: 0;">Editar Factura {{ $factura->numero_factura }}</h1>
            <p style="color: #64748b; margin-top: 5px;">Modificar información de emisión</p>
        </div>
    </div>

    <form action="{{ route('facturas.update', $factura->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: bold; color: #475569; margin-bottom: 8px;">Cliente</label>
            <input type="text" value="{{ $factura->cliente?->name ?? 'Sin cliente' }}" disabled style="width: 100%; padding: 12px; border-radius: 12px; border: 1px solid #e2e8f0; background: #f1f5f9; color: #64748b;">
            <small style="color: #94a3b8; display: block; margin-top: 5px;">El cliente no puede modificarse en una factura emitida.</small>
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: bold; color: #475569; margin-bottom: 8px;">Fecha de Emisión</label>
            <input type="date" name="fecha_emision" value="{{ old('fecha_emision', $factura->fecha_emision->format('Y-m-d')) }}" required style="width: 100%; padding: 12px; border-radius: 12px; border: 1px solid #ddd6fe;">
            @error('fecha_emision')
                <span style="color: #ef4444; font-size: 13px; margin-top: 5px; display: block;">{{ $message }}</span>
            @enderror
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: bold; color: #475569; margin-bottom: 8px;">Total Factura</label>
            <input type="text" value="${{ number_format($factura->total, 0, ',', '.') }}" disabled style="width: 100%; padding: 12px; border-radius: 12px; border: 1px solid #e2e8f0; background: #f1f5f9; color: #64748b;">
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 15px; margin-top: 30px;">
            <a href="{{ route('facturas.index') }}" style="background: #e2e8f0; color: #475569; padding: 12px 24px; border-radius: 12px; font-weight: bold; text-decoration: none;">Cancelar</a>
            <button type="submit" style="background: linear-gradient(135deg, #14b8a6, #2dd4bf); color: white; padding: 12px 24px; border-radius: 12px; border: none; font-weight: bold; cursor: pointer;">Guardar Cambios</button>
        </div>
    </form>
</div>
@endsection

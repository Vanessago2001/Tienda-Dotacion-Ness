@extends('suppliers.layout')

@section('title', 'Detalle del Proveedor')

@section('content')
    <h1>Ficha de Proveedor: {{ $supplier->company }}</h1>

    <div style="display: flex; gap: 30px; align-items: start; line-height: 1.8;">
        <div style="flex: 1; max-width: 250px;">
            <img src="{{ $supplier->photo }}" alt="Logo {{ $supplier->company }}" style="width: 100%; border-radius: 10px; border: 1px solid #ddd; box-shadow: 2px 2px 5px rgba(0,0,0,0.1);">
        </div>

        <div style="flex: 2;">
            <h3>Información de Contacto</h3>
            <p><strong>Nombre del Asesor:</strong> {{ $supplier->name }}</p>
            <p><strong>Email Personal:</strong> {{ $supplier->email }}</p>
            <p><strong>Teléfono Personal:</strong> {{ $supplier->phone }}</p>

            <hr>

            <h3>Datos Corporativos</h3>
            <p><strong>Empresa:</strong> {{ $supplier->company }}</p>
            <p><strong>Email Corporativo:</strong> {{ $supplier->company_email }}</p>
            <p><strong>Teléfono Oficina:</strong> {{ $supplier->company_phone }}</p>
            <p><strong>Producto Principal:</strong> <span style="background: #e9ecef; padding: 2px 8px; border-radius: 4px;">{{ $supplier->product }}</span></p>
        </div>
    </div>

    <div style="margin-top: 30px; border-top: 1px solid #eee; padding-top: 20px;">
        <a href="{{ route('suppliers.edit', $supplier) }}" style="text-decoration: none; background: #ffc107; color: #000; padding: 10px 15px; border-radius: 5px; font-weight: bold;">Editar Datos</a>
        <a href="{{ route('suppliers.index') }}" style="margin-left: 15px; text-decoration: none; color: #666;">Volver al listado</a>
    </div>
@endsection
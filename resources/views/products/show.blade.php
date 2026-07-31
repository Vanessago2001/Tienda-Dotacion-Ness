@extends('products.layout')

@section('title', 'Detalle del Producto')

@section('content')
    <h1>Información de: {{ $product->name }}</h1>

    <div style="display: flex; gap: 30px; align-items: start; line-height: 1.8;">
        <div style="flex: 1; max-width: 300px;">
            <img src="{{ $product->photo_url }}" alt="{{ $product->name }}" style="width: 100%; border-radius: 8px; border: 1px solid #ddd;">
        </div>

        <div style="flex: 2;">
            <p><strong>ID del sistema:</strong> {{ $product->id }}</p>
            <p><strong>Proveedor:</strong> {{ $product->supplier }}</p>
            <p><strong>Categoría:</strong> {{ $product->category }}</p>
            <p><strong>Precio de Venta:</strong> <span style="color: green; font-weight: bold;">${{ number_format($product->price, 0) }}</span></p>
            <p><strong>Costo de Adquisición:</strong> ${{ number_format($product->cost, 0) }}</p>
            <p><strong>Existencias (Stock):</strong> {{ $product->stock }} {{ $product->unit_of_measurement }}</p>
            <p><strong>Última actualización:</strong> {{ $product->updated_at->format('d/m/Y H:i') }}</p>
        </div>
    </div>

    <hr style="margin-top: 20px; border: 0; border-top: 1px solid #eee;">
    
    <div style="margin-top: 20px;">
        <a href="{{ route('products.edit', $product) }}" style="text-decoration: none; background: #ffc107; color: #000; padding: 10px 15px; border-radius: 5px;">Editar Producto</a>
        <a href="{{ route('products.index') }}" style="margin-left: 10px; text-decoration: none; color: #666;">Volver al inventario</a>
    </div>
@endsection
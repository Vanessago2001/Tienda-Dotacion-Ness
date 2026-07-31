@extends('products.layout')

@section('title', 'Editar Producto')

@section('content')
    <h1>Editar Producto: {{ $product->name }}</h1>

    @if($errors->any())
        <div class="error">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <label>Nombre del Producto</label>
        <input type="text" name="name" value="{{ old('name', $product->name) }}">

        <label>Código de barras</label>
        <input type="text" name="barcode" value="{{ old('barcode', $product->barcode) }}">

        <label>Proveedor</label>
        <input type="text" name="supplier" value="{{ old('supplier', $product->supplier) }}">

        <div style="display: flex; gap: 15px;">
            <div style="flex: 1;">
                <label>Precio Venta</label>
                <input type="number" name="price" value="{{ old('price', $product->price) }}">
            </div>
            <div style="flex: 1;">
                <label>Costo</label>
                <input type="number" name="cost" value="{{ old('cost', $product->cost) }}">
            </div>
        </div>

        <div style="display: flex; gap: 15px;">
            <div style="flex: 1;">
                <label>Stock Actual</label>
                <input type="number" name="stock" value="{{ old('stock', $product->stock) }}">
            </div>
            <div style="flex: 1;">
                <label>Unidad de Medida</label>
                <input type="text" name="unit_of_measurement" value="{{ old('unit_of_measurement', $product->unit_of_measurement) }}">
            </div>
        </div>

        <label>Categoría</label>
        <input type="text" name="category" value="{{ old('category', $product->category) }}">

        <label>Foto del Producto</label>
        <div style="margin-bottom: 8px;">
            <span style="font-size: 13px; color: #666;">Foto actual:</span><br>
            <img src="{{ $product->photo_url }}" alt="{{ $product->name }}"
                 style="width:120px; height:120px; object-fit:cover; border-radius:10px; border:1px solid #ddd;">
        </div>
        <input type="file" name="photo" id="photo" accept="image/*" onchange="previsualizarFoto(event)">
        <small style="color:#666;">Deja este campo vacío si no quieres cambiar la foto.</small>
        <img id="previewFoto" src="#" alt="Nueva foto"
             style="display:none; margin-top:10px; width:120px; height:120px; object-fit:cover; border-radius:10px; border:2px solid #14b8a6;">

        <div style="margin-top: 15px;">
            <button type="submit">Actualizar Producto</button>
            <a href="{{ route('products.index') }}" style="margin-left: 15px; text-decoration: none; color: #666;">Cancelar</a>
        </div>
    </form>

    <script>
        function previsualizarFoto(event) {
            const input = event.target;
            const preview = document.getElementById('previewFoto');
            if (input.files && input.files[0]) {
                preview.src = URL.createObjectURL(input.files[0]);
                preview.style.display = 'block';
            } else {
                preview.style.display = 'none';
            }
        }
    </script>
@endsection
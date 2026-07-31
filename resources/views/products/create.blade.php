@extends('products.layout')
@section('title', 'Nuevo Producto')
@section('content')
    <h1>Registrar Producto</h1>
    @if($errors->any())
        <div class="error">
            <ul>@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
        </div>
    @endif

    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <label>Nombre del Producto</label>
        <input type="text" name="name" value="{{ old('name') }}">
        <label>Código de barras</label>
        
        <input
        type="text"
        name="barcode"
        id="barcode"
        placeholder="Escanee el código">

        <label>Proveedor</label>
        <input type="text" name="supplier" value="{{ old('supplier') }}">

        <div style="display: flex; gap: 10px;">
            <div style="flex: 1;">
                <label>Precio Venta</label>
                <input type="number" name="price" value="{{ old('price') }}">
            </div>
            <div style="flex: 1;">
                <label>Costo</label>
                <input type="number" name="cost" value="{{ old('cost') }}">
            </div>
        </div>

        <label>Stock Inicial</label>
        <input type="number" name="stock" value="{{ old('stock') }}">

        <label>Categoría</label>
        <input type="text" name="category" value="{{ old('category') }}">

        <label>Unidad de Medida</label>
        <input type="text" name="unit_of_measurement" placeholder="Ej: Unidad, Kg, Paquete" value="{{ old('unit_of_measurement') }}">

        <label>Foto del Producto</label>
        <input type="file" name="photo" id="photo" accept="image/*" onchange="previsualizarFoto(event)">
        <img id="previewFoto" src="#" alt="Vista previa"
             style="display:none; margin-top:10px; width:120px; height:120px; object-fit:cover; border-radius:10px; border:1px solid #ddd;">

        <button type="submit">Guardar Producto</button>
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
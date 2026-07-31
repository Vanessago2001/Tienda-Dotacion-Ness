@extends('companies.layout')
@section('content')
    <h1>Nueva Empresa</h1>
    <form action="{{ route('companies.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <label>Nombre de la Empresa</label>
        <input type="text" name="name" value="{{ old('name') }}">

        <label>NIT</label>
        <input type="number" name="nit" value="{{ old('nit') }}">

        <label>Dirección</label>
        <input type="text" name="address" value="{{ old('address') }}">

        <label>Teléfono</label>
        <input type="number" name="phone" value="{{ old('phone') }}">

        <label>Correo Electrónico</label>
        <input type="email" name="email" value="{{ old('email') }}">

        <label>Ciudad</label>
        <input type="text" name="city" value="{{ old('city') }}">

        <label>Logo de la Empresa</label>
        <input type="file" name="logo" accept="image/*" onchange="previsualizarLogo(event)">
        <img id="previewLogo" src="#" alt="Vista previa"
             style="display:none; margin-top:10px; width:120px; height:120px; object-fit:contain; border:1px solid #ddd; border-radius:8px; padding:6px;">

        <button type="submit" style="margin-top: 20px;">Guardar Empresa</button>
    </form>

    <script>
        function previsualizarLogo(event) {
            const input = event.target;
            const preview = document.getElementById('previewLogo');
            if (input.files && input.files[0]) {
                preview.src = URL.createObjectURL(input.files[0]);
                preview.style.display = 'block';
            } else {
                preview.style.display = 'none';
            }
        }
    </script>
@endsection
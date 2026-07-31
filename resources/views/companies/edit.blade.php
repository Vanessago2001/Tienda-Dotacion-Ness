@extends('companies.layout')
@section('content')
    <h1>Editar Empresa: {{ $company->name }}</h1>
    <form action="{{ route('companies.update', $company) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')
        
        <label>Nombre</label>
        <input type="text" name="name" value="{{ old('name', $company->name) }}">

        <label>NIT</label>
        <input type="number" name="nit" value="{{ old('nit', $company->nit) }}">

        <label>Dirección</label>
        <input type="text" name="address" value="{{ old('address', $company->address) }}">

        <label>Teléfono</label>
        <input type="number" name="phone" value="{{ old('phone', $company->phone) }}">

        <label>Correo Electrónico</label>
        <input type="email" name="email" value="{{ old('email', $company->email) }}">

        <label>Ciudad</label>
        <input type="text" name="city" value="{{ old('city', $company->city) }}">

        <label>Logo de la Empresa</label>
        <div style="margin: 8px 0;">
            <span style="font-size: 13px; color: #666;">Logo actual:</span><br>
            <img src="{{ $company->logo_url }}" alt="{{ $company->name }}"
                 style="width:120px; height:120px; object-fit:contain; border:1px solid #ddd; border-radius:8px; padding:6px;">
        </div>
        <input type="file" name="logo" accept="image/*" onchange="previsualizarLogo(event)">
        <small style="color:#666;">Deja este campo vacío si no quieres cambiar el logo.</small>
        <img id="previewLogo" src="#" alt="Nuevo logo"
             style="display:none; margin-top:10px; width:120px; height:120px; object-fit:contain; border:2px solid #14b8a6; border-radius:8px; padding:6px;">

        <button type="submit" style="margin-top: 20px; background: #27ae60;">Actualizar Empresa</button>
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
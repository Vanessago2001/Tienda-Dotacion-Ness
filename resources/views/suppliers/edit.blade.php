@extends('suppliers.layout')

@section('title', 'Editar Proveedor')

@section('content')
    <h1>Actualizar Proveedor: {{ $supplier->company }}</h1>

    @if($errors->any())
        <div class="error">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('suppliers.update', $supplier) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <fieldset style="border: 1px solid #ddd; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            <legend style="padding: 0 10px; font-weight: bold;">Datos del Contacto</legend>
            
            <label>Nombre Completo</label>
            <input type="text" name="name" value="{{ old('name', $supplier->name) }}">

            <label>Email Personal</label>
            <input type="email" name="email" value="{{ old('email', $supplier->email) }}">

            <label>Teléfono Personal</label>
            <input type="number" name="phone" value="{{ old('phone', $supplier->phone) }}">
        </fieldset>

        <fieldset style="border: 1px solid #ddd; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            <legend style="padding: 0 10px; font-weight: bold;">Datos de la Empresa</legend>

            <label>Nombre de la Empresa</label>
            <input type="text" name="company" value="{{ old('company', $supplier->company) }}">

            <label>Email Corporativo</label>
            <input type="email" name="company_email" value="{{ old('company_email', $supplier->company_email) }}">

            <label>Teléfono Corporativo</label>
            <input type="number" name="company_phone" value="{{ old('company_phone', $supplier->company_phone) }}">

            <label>Producto Suministrado</label>
            <input type="text" name="product" value="{{ old('product', $supplier->product) }}">

            <label>Foto/Logo</label>
            @if($supplier->photo)
                <img src="{{ Str::startsWith($supplier->photo, ['http://', 'https://']) ? $supplier->photo : asset('storage/' . $supplier->photo) }}" alt="Logo actual" style="display:block; width:120px; height:120px; object-fit:cover; border-radius:10px; border:1px solid #ddd; margin-bottom:10px;">
            @endif
            <input type="file" name="photo" accept="image/*">
        </fieldset>

        <div style="margin-top: 10px;">
            <button type="submit" style="background: #198754; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;">Guardar Cambios</button>
            <a href="{{ route('suppliers.index') }}" style="margin-left: 15px; text-decoration: none; color: #666;">Cancelar</a>
        </div>
    </form>
@endsection
@extends('customers.layout')
@section('content')
    <h1>Editar Cliente: {{ $customer->name }}</h1>
<form action="{{ route('customers.update', $customer) }}" method="POST" enctype="multipart/form-data">        @csrf @method('PUT')
        
        <label>Tipo de Documento</label>
<select name="document_type">
    <option value="CC" {{ old('document_type', $customer->document_type) == 'CC' ? 'selected' : '' }}>
        Cédula de Ciudadanía
    </option>

    <option value="TI" {{ old('document_type', $customer->document_type) == 'TI' ? 'selected' : '' }}>
        Tarjeta de Identidad
    </option>

    <option value="CE" {{ old('document_type', $customer->document_type) == 'CE' ? 'selected' : '' }}>
        Cédula de Extranjería
    </option>

    <option value="NIT" {{ old('document_type', $customer->document_type) == 'NIT' ? 'selected' : '' }}>
        NIT
    </option>
</select>
<input type="text" name="name" value="{{ old('name', $customer->name) }}">

        <label>Número de Documento</label>
        <input type="number" name="document" value="{{ old('document', $customer->document) }}">

        <label>Correo Electrónico</label>
<input
    type="email"
    name="email"
    value="{{ old('email', $customer->email) }}"
>

<label>Teléfono</label>
<input
    type="number"
    name="phone"
    value="{{ old('phone', $customer->phone) }}"
>
        <label>Dirección</label>
        <input type="text" name="address" value="{{ old('address', $customer->address) }}">

        <label>Cupo de Crédito</label>
        <input type="number" name="quota" value="{{ old('quota', $customer->quota) }}">

@if($customer->photo)
    <div style="margin-bottom:10px;">
        <img src="{{ asset('storage/' . $customer->photo) }}"
             width="120"
             style="border-radius:10px;">
    </div>
@endif

<label>Foto de Perfil</label>
<input type="file" name="photo" accept="image/*">


        <button type="submit" style="margin-top: 20px; background: #198754;">Actualizar Datos</button>
        <a href="{{ route('customers.index') }}" style="margin-left: 15px; text-decoration: none; color: #666;">Cancelar</a>
    </form>
@endsection
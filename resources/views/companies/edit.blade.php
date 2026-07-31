@extends('companies.layout')
@section('content')
    <h1>Editar Empresa: {{ $company->name }}</h1>
    <form action="{{ route('companies.update', $company) }}" method="POST">
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

        <label>URL del Logo</label>
        <input type="text" name="logo" value="{{ old('logo', $company->logo) }}">

        <button type="submit" style="margin-top: 20px; background: #27ae60;">Actualizar Empresa</button>
    </form>
@endsection
@extends('companies.layout')
@section('content')
    <h1>Nueva Empresa</h1>
    <form action="{{ route('companies.store') }}" method="POST">
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

        <label>URL del Logo</label>
        <input type="text" name="logo" value="{{ old('logo') }}">

        <button type="submit" style="margin-top: 20px;">Guardar Empresa</button>
    </form>
@endsection
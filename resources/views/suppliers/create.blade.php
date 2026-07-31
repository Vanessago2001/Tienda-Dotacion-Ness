@extends('suppliers.layout')
@section('title', 'Registrar Proveedor')
@section('content')
    <h1>Nuevo Proveedor</h1>
    <form action="{{ route('suppliers.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <label>Nombre del Contacto</label>
        <input type="text" name="name" value="{{ old('name') }}">
        
        <label>Email Personal</label>
        <input type="email" name="email" value="{{ old('email') }}">

        <label>Nombre de la Empresa</label>
        <input type="text" name="company" value="{{ old('company') }}">

        <label>Email de la Empresa</label>
        <input type="email" name="company_email" value="{{ old('company_email') }}">

        <label>Teléfono Empresa</label>
        <input type="number" name="company_phone" value="{{ old('company_phone') }}">

        <label>Producto que suministra</label>
        <input type="text" name="product" value="{{ old('product') }}">

        <label>Foto/Logo</label>
        <input type="file" name="photo" accept="image/*">

        <button type="submit">Guardar Proveedor</button>
    </form>
@endsection
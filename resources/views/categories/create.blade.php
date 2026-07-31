@extends('categories.layout')
@section('content')
    <h1>Crear Categoría</h1>
    <form action="{{ route('categories.store') }}" method="POST">
        @csrf
        <label>Nombre</label>
        <input type="text" name="name" placeholder="Ej: Electrónica">

        <label>Estado</label>
        <select name="state">
            <option value="Activo">Activo</option>
            <option value="Inactivo">Inactivo</option>
        </select>

        <button type="submit">Guardar</button>
    </form>
@endsection
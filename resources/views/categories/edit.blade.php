@extends('categories.layout')
@section('content')
    <h1>Editar Categoría</h1>
    <form action="{{ route('categories.update', $category) }}" method="POST">
        @csrf @method('PUT')
        <label>Nombre</label>
        <input type="text" name="name" value="{{ $category->name }}">

        <label>Estado</label>
        <select name="state">
            <option value="Activo" {{ $category->state == 'Activo' ? 'selected' : '' }}>Activo</option>
            <option value="Inactivo" {{ $category->state == 'Inactivo' ? 'selected' : '' }}>Inactivo</option>
        </select>

        <button type="submit">Actualizar</button>
    </form>
@endsection
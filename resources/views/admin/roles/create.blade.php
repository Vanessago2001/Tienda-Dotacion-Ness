@extends('layouts.panel')

@section('title', 'Nuevo rol')
@section('titulo', '🏷️ Nuevo rol')

@section('nav')
    <a href="{{ route('roles.index') }}" class="btn-modulo">Roles</a>
@endsection

@section('content')

<div class="header-card">
    <h1>Crear rol</h1>
    <p>El identificador (slug) se genera automáticamente a partir del nombre.</p>
</div>

<form method="POST" action="{{ route('roles.store') }}" class="form-card">
    @csrf

    <div class="campo">
        <label>Nombre del rol</label>
        <input type="text" name="name" value="{{ old('name') }}" required placeholder="Ej: Supervisor">
    </div>

    <div class="campo">
        <label>Descripción (opcional)</label>
        <input type="text" name="description" value="{{ old('description') }}">
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Crear rol</button>
        <a href="{{ route('roles.index') }}" class="btn btn-secondary">Cancelar</a>
    </div>
</form>

@endsection

@extends('layouts.panel')

@section('title', 'Editar rol')
@section('titulo', '🏷️ Editar rol')

@section('nav')
    <a href="{{ route('roles.index') }}" class="btn-modulo">Roles</a>
@endsection

@section('content')

<div class="header-card">
    <h1>Editar: {{ $role->name }}</h1>
    @if($role->is_system)
        <p>Este es un rol del sistema: puedes cambiar su nombre y descripción, pero no su identificador ni eliminarlo.</p>
    @endif
</div>

<form method="POST" action="{{ route('roles.update', $role) }}" class="form-card">
    @csrf
    @method('PUT')

    <div class="campo">
        <label>Nombre del rol</label>
        <input type="text" name="name" value="{{ old('name', $role->name) }}" required>
    </div>

    <div class="campo">
        <label>Identificador (slug)</label>
        <input type="text" value="{{ $role->slug }}" disabled style="background:#f1f5f9;">
    </div>

    <div class="campo">
        <label>Descripción (opcional)</label>
        <input type="text" name="description" value="{{ old('description', $role->description) }}">
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Guardar cambios</button>
        <a href="{{ route('roles.index') }}" class="btn btn-secondary">Cancelar</a>
    </div>
</form>

@endsection

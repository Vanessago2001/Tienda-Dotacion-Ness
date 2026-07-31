@extends('layouts.panel')

@section('title', 'Editar usuario')
@section('titulo', '👥 Editar usuario')

@section('nav')
    <a href="{{ route('usuarios.index') }}" class="btn-modulo">Usuarios</a>
@endsection

@section('content')

<div class="header-card">
    <h1>Editar: {{ $usuario->name }}</h1>
    <p>Deja la contraseña vacía si no quieres cambiarla.</p>
</div>

<form method="POST" action="{{ route('usuarios.update', $usuario) }}" class="form-card">
    @csrf
    @method('PUT')

    <div class="campo">
        <label>Nombre</label>
        <input type="text" name="name" value="{{ old('name', $usuario->name) }}" required>
    </div>

    <div class="campo">
        <label>Correo</label>
        <input type="email" name="email" value="{{ old('email', $usuario->email) }}" required>
    </div>

    <div class="campo">
        <label>Rol</label>
        <select name="role" required>
            @foreach($roles as $role)
                <option value="{{ $role->slug }}" @selected(old('role', $usuario->role)===$role->slug)>{{ $role->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="campo">
        <label>Nueva contraseña (opcional)</label>
        <input type="password" name="password">
    </div>

    <div class="campo">
        <label>Confirmar nueva contraseña</label>
        <input type="password" name="password_confirmation">
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Guardar cambios</button>
        <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">Cancelar</a>
    </div>
</form>

@endsection

@extends('layouts.panel')

@section('title', 'Nuevo usuario')
@section('titulo', '👥 Nuevo usuario')

@section('nav')
    <a href="{{ route('usuarios.index') }}" class="btn-modulo">Usuarios</a>
@endsection

@section('content')

<div class="header-card">
    <h1>Crear usuario</h1>
    <p>Completa los datos. El usuario podrá iniciar sesión con su correo y contraseña.</p>
</div>

<form method="POST" action="{{ route('usuarios.store') }}" class="form-card">
    @csrf

    <div class="campo">
        <label>Nombre</label>
        <input type="text" name="name" value="{{ old('name') }}" required>
    </div>

    <div class="campo">
        <label>Correo</label>
        <input type="email" name="email" value="{{ old('email') }}" required>
    </div>

    <div class="campo">
        <label>Rol</label>
        <select name="role" required>
            <option value="">Selecciona un rol</option>
            @foreach($roles as $role)
                <option value="{{ $role->slug }}" @selected(old('role')===$role->slug)>{{ $role->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="campo">
        <label>Contraseña</label>
        <input type="password" name="password" required>
    </div>

    <div class="campo">
        <label>Confirmar contraseña</label>
        <input type="password" name="password_confirmation" required>
    </div>

    <div class="campo">
        <label><input type="checkbox" name="active" value="1" checked style="width:auto; margin-right:8px;">Usuario activo</label>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Crear usuario</button>
        <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">Cancelar</a>
    </div>
</form>

@endsection

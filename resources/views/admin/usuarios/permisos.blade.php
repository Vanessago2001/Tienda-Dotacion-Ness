@extends('layouts.panel')

@section('title', 'Permisos de ' . $usuario->name)
@section('titulo', '🔐 Permisos')

@section('nav')
    <a href="{{ route('usuarios.index') }}" class="btn-modulo">Usuarios</a>
@endsection

@section('content')

<style>
    .perm-grid{ display:grid; grid-template-columns:repeat(auto-fill,minmax(300px,1fr)); gap:16px; margin:20px 0; }
    .perm-item{ display:flex; gap:12px; align-items:flex-start; border:1px solid #e2e8f0; border-radius:14px; padding:16px; transition:.2s; cursor:pointer; background:#fff; }
    .perm-item:hover{ border-color:#14b8a6; background:#f0fdfa; }
    .perm-item input{ width:20px; height:20px; margin-top:2px; accent-color:#14b8a6; cursor:pointer; }
    .perm-item .perm-name{ font-weight:700; color:#0f172a; }
    .perm-item .perm-desc{ font-size:13px; color:#64748b; margin-top:3px; }
    .helper-bar{ display:flex; gap:10px; margin-bottom:6px; }
    .helper-bar button{ background:#e0f2fe; color:#0369a1; border:none; padding:6px 14px; border-radius:10px; font-weight:600; cursor:pointer; font-size:13px; }
</style>

<div class="header-card">
    <h1>Permisos de {{ $usuario->name }}</h1>
    <p>Rol: <strong>{{ $usuario->role }}</strong> · {{ $usuario->email }}</p>
</div>

<form action="{{ route('usuarios.permisos.update', $usuario) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="helper-bar">
        <button type="button" onclick="marcarTodos(true)">Seleccionar todos</button>
        <button type="button" onclick="marcarTodos(false)">Quitar todos</button>
    </div>

    <div class="perm-grid">
        @foreach($permisos as $permiso)
            <label class="perm-item">
                <input type="checkbox" name="permisos[]" value="{{ $permiso->id }}"
                    {{ in_array($permiso->id, $asignados) ? 'checked' : '' }}>
                <span>
                    <span class="perm-name">{{ $permiso->name }}</span>
                    <span class="perm-desc">{{ $permiso->description }}</span>
                </span>
            </label>
        @endforeach
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Guardar permisos</button>
        <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">Cancelar</a>
    </div>
</form>

<script>
    function marcarTodos(estado){
        document.querySelectorAll('input[name="permisos[]"]').forEach(chk => chk.checked = estado);
    }
</script>

@endsection

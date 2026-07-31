@extends('layouts.panel')

@section('title', 'Roles')
@section('titulo', '🏷️ Roles')

@section('nav')
    <a href="{{ route('usuarios.index') }}" class="btn-modulo">Usuarios</a>
    <a href="{{ route('roles.create') }}" class="btn-nuevo">+ Nuevo rol</a>
@endsection

@section('content')

<div class="header-card">
    <h1>Roles del sistema</h1>
    <p>Los roles son etiquetas para clasificar usuarios. Los permisos se asignan por usuario en el módulo Usuarios.</p>
</div>

<table class="table-modern">
    <thead>
        <tr>
            <th>Rol</th>
            <th>Slug</th>
            <th>Descripción</th>
            <th>Usuarios</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach($roles as $role)
            <tr>
                <td>
                    <strong>{{ $role->name }}</strong>
                    @if($role->is_system) <span class="badge badge-amber">Sistema</span> @endif
                </td>
                <td><code>{{ $role->slug }}</code></td>
                <td>{{ $role->description }}</td>
                <td><span class="badge badge-gray">{{ $role->users_count }}</span></td>
                <td>
                    <div class="acciones-fila">
                        <a href="{{ route('roles.edit', $role) }}" class="btn btn-edit">Editar</a>
                        @unless($role->is_system)
                            <form method="POST" action="{{ route('roles.destroy', $role) }}"
                                  onsubmit="return confirm('¿Eliminar el rol {{ $role->name }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-delete">Eliminar</button>
                            </form>
                        @endunless
                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

@endsection

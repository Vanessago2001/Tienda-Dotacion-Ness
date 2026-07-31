@extends('layouts.panel')

@section('title', 'Usuarios')
@section('titulo', '👥 Usuarios')

@section('nav')
    <a href="{{ route('roles.index') }}" class="btn-modulo">Roles</a>
    <a href="{{ route('usuarios.create') }}" class="btn-nuevo">+ Nuevo usuario</a>
@endsection

@section('content')

<div class="header-card">
    <h1>Usuarios del sistema</h1>
    <p>Crea, edita, activa/inactiva usuarios y administra sus permisos. El administrador siempre tiene todos los permisos.</p>
</div>

<table class="table-modern">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Correo</th>
            <th>Rol</th>
            <th>Estado</th>
            <th>Permisos</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach($users as $user)
            <tr>
                <td><strong>{{ $user->name }}</strong></td>
                <td>{{ $user->email }}</td>
                <td><span class="badge {{ $user->isAdmin() ? 'badge-amber' : 'badge-blue' }}" style="text-transform:capitalize;">{{ $user->role }}</span></td>
                <td>
                    @if($user->active)
                        <span class="badge badge-green">Activo</span>
                    @else
                        <span class="badge badge-red">Inactivo</span>
                    @endif
                </td>
                <td>
                    @if($user->isAdmin())
                        <span class="badge badge-gray">Todos</span>
                    @else
                        <span class="badge badge-gray">{{ $user->permissions_count }}</span>
                    @endif
                </td>
                <td>
                    <div class="acciones-fila">
                        <a href="{{ route('usuarios.edit', $user) }}" class="btn btn-edit">Editar</a>

                        @unless($user->isAdmin())
                            <a href="{{ route('usuarios.permisos.edit', $user) }}" class="btn btn-perm">Permisos</a>
                        @endunless

                        @if($user->id !== auth()->id())
                            <form method="POST" action="{{ route('usuarios.estado', $user) }}">
                                @csrf @method('PUT')
                                <button type="submit" class="btn {{ $user->active ? 'btn-off' : 'btn-on' }}">
                                    {{ $user->active ? 'Inactivar' : 'Activar' }}
                                </button>
                            </form>

                            <form method="POST" action="{{ route('usuarios.destroy', $user) }}"
                                  onsubmit="return confirm('¿Eliminar al usuario {{ $user->name }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-delete">Eliminar</button>
                            </form>
                        @endif
                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

@endsection

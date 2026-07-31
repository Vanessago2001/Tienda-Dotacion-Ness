@extends('layouts.panel')

@section('title', 'Auditoría')
@section('titulo', '🕵️ Auditoría')

@section('content')

<div class="header-card">
    <h1>Auditoría del sistema</h1>
    <p>Registro de acciones importantes: accesos, gestión de usuarios, permisos, anulaciones y movimientos de inventario.</p>
</div>

<form method="GET" action="{{ route('auditoria.index') }}" class="filtros">
    <div>
        <label>Módulo</label>
        <select name="modulo">
            <option value="">Todos</option>
            @foreach($modulos as $m)
                <option value="{{ $m }}" @selected(request('modulo')===$m)>{{ $m }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label>Acción</label>
        <select name="accion">
            <option value="">Todas</option>
            @foreach($acciones as $a)
                <option value="{{ $a }}" @selected(request('accion')===$a)>{{ ucfirst($a) }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label>Desde</label>
        <input type="date" name="desde" value="{{ request('desde') }}">
    </div>
    <div>
        <label>Hasta</label>
        <input type="date" name="hasta" value="{{ request('hasta') }}">
    </div>
    <div class="acciones">
        <button type="submit" class="btn btn-primary">Filtrar</button>
        <a href="{{ route('auditoria.index') }}" class="btn btn-secondary">Limpiar</a>
    </div>
</form>

@if($auditorias->count())
<table class="table-modern">
    <thead>
        <tr>
            <th>Fecha y hora</th>
            <th>Usuario</th>
            <th>Módulo</th>
            <th>Acción</th>
            <th>Descripción</th>
            <th>IP</th>
        </tr>
    </thead>
    <tbody>
        @foreach($auditorias as $a)
            <tr>
                <td>{{ $a->created_at->format('d/m/Y H:i') }}</td>
                <td>{{ $a->usuario?->name ?? 'Sistema' }}</td>
                <td><span class="badge badge-blue">{{ $a->modulo }}</span></td>
                <td><span class="badge badge-gray" style="text-transform:capitalize;">{{ $a->accion }}</span></td>
                <td>{{ $a->descripcion }}</td>
                <td style="color:#94a3b8;">{{ $a->ip }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

@if($auditorias->hasPages())
<div class="pagination">
    @if($auditorias->onFirstPage())
        <span class="page disabled">« Anterior</span>
    @else
        <a class="page" href="{{ $auditorias->previousPageUrl() }}">« Anterior</a>
    @endif
    <span class="page-info">Página {{ $auditorias->currentPage() }} de {{ $auditorias->lastPage() }}</span>
    @if($auditorias->hasMorePages())
        <a class="page" href="{{ $auditorias->nextPageUrl() }}">Siguiente »</a>
    @else
        <span class="page disabled">Siguiente »</span>
    @endif
</div>
@endif

@else
<div class="empty">
    <h3>Sin registros</h3>
    <p>Las acciones del sistema aparecerán aquí.</p>
</div>
@endif

@endsection

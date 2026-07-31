@extends('layouts.panel')

@section('title', 'Movimientos de Inventario')
@section('titulo', '📦 Inventario')

@section('nav')
    <a href="{{ route('products.index') }}" class="btn-modulo">Productos</a>
    <a href="{{ route('inventario.create') }}" class="btn-nuevo">+ Nuevo movimiento</a>
@endsection

@section('content')

<div class="header-card">
    <h1>Movimientos de inventario</h1>
    <p>Historial de entradas, salidas y ajustes de stock. Las ventas y anulaciones generan movimientos automáticamente.</p>
</div>

<form method="GET" action="{{ route('inventario.index') }}" class="filtros">
    <div>
        <label>Producto</label>
        <select name="product_id">
            <option value="">Todos</option>
            @foreach($productos as $p)
                <option value="{{ $p->id }}" @selected(request('product_id')==$p->id)>{{ $p->name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label>Tipo</label>
        <select name="tipo">
            <option value="">Todos</option>
            @foreach(['entrada','salida','ajuste'] as $t)
                <option value="{{ $t }}" @selected(request('tipo')===$t)>{{ ucfirst($t) }}</option>
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
        <a href="{{ route('inventario.index') }}" class="btn btn-secondary">Limpiar</a>
    </div>
</form>

@if($movimientos->count())
<table class="table-modern">
    <thead>
        <tr>
            <th>Fecha</th>
            <th>Producto</th>
            <th>Tipo</th>
            <th>Cantidad</th>
            <th>Stock (antes → después)</th>
            <th>Motivo</th>
            <th>Usuario</th>
        </tr>
    </thead>
    <tbody>
        @foreach($movimientos as $m)
            <tr>
                <td>{{ \Illuminate\Support\Carbon::parse($m->fecha)->format('d/m/Y') }}</td>
                <td><strong>{{ $m->product?->name ?? 'Producto eliminado' }}</strong></td>
                <td>
                    @php $clase = ['entrada'=>'badge-green','salida'=>'badge-red','ajuste'=>'badge-blue'][$m->tipo]; @endphp
                    <span class="badge {{ $clase }}">{{ ucfirst($m->tipo) }}</span>
                </td>
                <td><strong>{{ $m->cantidad }}</strong></td>
                <td>{{ $m->stock_anterior }} → {{ $m->stock_nuevo }}</td>
                <td>{{ $m->motivo ?? '—' }}</td>
                <td>{{ $m->usuario?->name ?? '—' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

@if($movimientos->hasPages())
<div class="pagination">
    @if($movimientos->onFirstPage())
        <span class="page disabled">« Anterior</span>
    @else
        <a class="page" href="{{ $movimientos->previousPageUrl() }}">« Anterior</a>
    @endif
    <span class="page-info">Página {{ $movimientos->currentPage() }} de {{ $movimientos->lastPage() }}</span>
    @if($movimientos->hasMorePages())
        <a class="page" href="{{ $movimientos->nextPageUrl() }}">Siguiente »</a>
    @else
        <span class="page disabled">Siguiente »</span>
    @endif
</div>
@endif

@else
<div class="empty">
    <h3>No hay movimientos</h3>
    <p>Registra un movimiento o realiza ventas para ver el historial.</p>
</div>
@endif

@endsection

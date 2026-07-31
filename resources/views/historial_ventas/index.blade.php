@extends('historial_ventas.layout')

@section('title', 'Historial de Ventas')

@section('content')

<style>
    .header-card{ margin-bottom:20px; }
    .header-card h1{ margin:0; color:#1e293b; font-size:30px; font-weight:700; }
    .header-card p{ margin-top:8px; color:#64748b; }

    .stats{ display:flex; gap:16px; flex-wrap:wrap; margin-bottom:20px; }
    .stat-card{
        flex:1; min-width:200px; background:#f0fdfa; border:1px solid #ccfbf1;
        border-radius:16px; padding:18px;
    }
    .stat-card h3{ font-size:13px; color:#0f766e; margin-bottom:6px; text-transform:uppercase; }
    .stat-card p{ font-size:26px; font-weight:700; color:#0f172a; }

    .filtros{
        display:grid; grid-template-columns:repeat(auto-fit,minmax(150px,1fr));
        gap:12px; align-items:end; margin-bottom:22px;
        background:#f8fafc; padding:16px; border-radius:16px; border:1px solid #e2e8f0;
    }
    .filtros label{ font-size:12px; font-weight:600; color:#475569; display:block; margin-bottom:4px; }
    .filtros input, .filtros select{
        width:100%; padding:10px; border:1px solid #cbd5e1; border-radius:10px;
    }
    .filtros .acciones{ display:flex; gap:8px; }
    .btn-filtrar{
        background:linear-gradient(135deg,#14b8a6,#2dd4bf); color:#fff; border:none;
        padding:10px 18px; border-radius:10px; font-weight:700; cursor:pointer;
    }
    .btn-limpiar{
        background:#f1f5f9; color:#475569; text-decoration:none;
        padding:10px 18px; border-radius:10px; font-weight:700; display:inline-block;
    }

    .table-modern{ width:100%; border-collapse:collapse; }
    .table-modern thead{ background:#ecfeff; }
    .table-modern th{
        padding:14px; text-align:left; color:#0f766e; font-weight:700;
        border-bottom:1px solid #ccfbf1; font-size:14px;
    }
    .table-modern td{ padding:12px 14px; border-bottom:1px solid #f1f5f9; font-size:14px; }
    .table-modern tbody tr:hover{ background:#f0fdfa; transition:.3s; }

    .badge{ padding:5px 12px; border-radius:20px; font-size:12px; font-weight:700; }
    .badge-pagada{ background:#dcfce7; color:#166534; }
    .badge-anulada{ background:#fee2e2; color:#991b1b; }
    .badge-metodo{ background:#e0f2fe; color:#0369a1; text-transform:capitalize; }

    .btn{
        text-decoration:none; padding:7px 12px; border-radius:9px; font-size:13px;
        font-weight:600; display:inline-block; border:none; cursor:pointer;
    }
    .btn-ver{ background:#e6fbf8; color:#0d3f3c; }
    .btn-print{ background:#ccfbf1; color:#0f766e; }
    .acciones-fila{ display:flex; gap:6px; flex-wrap:wrap; }

    .pagination{ display:flex; gap:12px; align-items:center; justify-content:center; margin-top:22px; }
    .pagination .page{
        text-decoration:none; padding:8px 16px; border-radius:10px; font-weight:600;
        background:#ccfbf1; color:#0f766e;
    }
    .pagination .disabled{ background:#f1f5f9; color:#94a3b8; }
    .pagination .page-info{ color:#475569; font-weight:600; }

    .empty{ text-align:center; padding:40px; color:#94a3b8; }
</style>

<div class="header-card">
    <h1>Historial de Ventas</h1>
    <p>Consulta todas las ventas registradas y filtra por fecha, estado, método de pago o cliente.</p>
</div>

<div class="stats">
    <div class="stat-card">
        <h3>Ventas encontradas</h3>
        <p>{{ $cantidadVentas }}</p>
    </div>
    <div class="stat-card">
        <h3>Total vendido (pagadas)</h3>
        <p>${{ number_format($totalVendido, 0, ',', '.') }}</p>
    </div>
</div>

<form method="GET" action="{{ route('historial-ventas.index') }}" class="filtros">
    <div>
        <label>Desde</label>
        <input type="date" name="desde" value="{{ request('desde') }}">
    </div>
    <div>
        <label>Hasta</label>
        <input type="date" name="hasta" value="{{ request('hasta') }}">
    </div>
    <div>
        <label>Estado</label>
        <select name="estado">
            <option value="">Todos</option>
            <option value="pagada"  @selected(request('estado')==='pagada')>Pagada</option>
            <option value="anulada" @selected(request('estado')==='anulada')>Anulada</option>
        </select>
    </div>
    <div>
        <label>Método de pago</label>
        <select name="metodo_pago">
            <option value="">Todos</option>
            @foreach(['efectivo','transferencia','tarjeta','nequi','daviplata'] as $m)
                <option value="{{ $m }}" @selected(request('metodo_pago')===$m)>{{ ucfirst($m) }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label>Cliente</label>
        <input type="text" name="cliente" value="{{ request('cliente') }}" placeholder="Nombre del cliente">
    </div>
    <div class="acciones">
        <button type="submit" class="btn-filtrar">Filtrar</button>
        <a href="{{ route('historial-ventas.index') }}" class="btn-limpiar">Limpiar</a>
    </div>
</form>

@if($ventas->count())
<table class="table-modern">
    <thead>
        <tr>
            <th>Fecha</th>
            <th>N° Venta</th>
            <th>Cliente</th>
            <th>Cajero</th>
            <th>Método</th>
            <th>Total</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach($ventas as $venta)
            <tr>
                <td>{{ \Illuminate\Support\Carbon::parse($venta->fecha)->format('d/m/Y') }}</td>
                <td><strong>{{ $venta->numero_venta }}</strong></td>
                <td>{{ $venta->cliente?->name ?? 'Sin cliente' }}</td>
                <td>{{ $venta->usuario?->name ?? '—' }}</td>
                <td><span class="badge badge-metodo">{{ $venta->metodo_pago }}</span></td>
                <td style="color:#0f766e; font-weight:bold;">${{ number_format($venta->total, 0, ',', '.') }}</td>
                <td>
                    <span class="badge {{ $venta->estado === 'pagada' ? 'badge-pagada' : 'badge-anulada' }}">
                        {{ ucfirst($venta->estado) }}
                    </span>
                </td>
                <td>
                    <div class="acciones-fila">
                        <a href="{{ route('historial-ventas.show', $venta) }}" class="btn btn-ver">Ver</a>

                        @if($venta->factura)
                            <a href="{{ route('facturas.imprimir', $venta->factura) }}" class="btn btn-print" target="_blank">Imprimir</a>
                        @else
                            <form method="POST" action="{{ route('facturas.generar', $venta) }}">
                                @csrf
                                <button type="submit" class="btn btn-print">Generar factura</button>
                            </form>
                        @endif
                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

@if($ventas->hasPages())
<div class="pagination">
    @if($ventas->onFirstPage())
        <span class="page disabled">« Anterior</span>
    @else
        <a class="page" href="{{ $ventas->previousPageUrl() }}">« Anterior</a>
    @endif

    <span class="page-info">Página {{ $ventas->currentPage() }} de {{ $ventas->lastPage() }}</span>

    @if($ventas->hasMorePages())
        <a class="page" href="{{ $ventas->nextPageUrl() }}">Siguiente »</a>
    @else
        <span class="page disabled">Siguiente »</span>
    @endif
</div>
@endif

@else
<div class="empty">
    <h3>No se encontraron ventas</h3>
    <p>Ajusta los filtros o registra ventas desde la caja.</p>
</div>
@endif

@endsection

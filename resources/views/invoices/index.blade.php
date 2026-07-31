@extends('invoices.layout')

@section('title', 'Listado de Facturas')

@section('content')

<style>

    .header-card{
        background: rgba(255,255,255,.90);
        backdrop-filter: blur(12px);
        border-radius:24px;
        padding:25px;
        margin-bottom:25px;
        box-shadow:0 10px 30px rgba(0,0,0,.06);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
    }

    .header-card h1{
        margin:0;
        color:#1e293b;
        font-size:32px;
        font-weight:700;
    }

    .header-card p{
        margin-top:8px;
        color:#64748b;
    }

    .table-card{
        background:white;
        border-radius:24px;
        overflow:hidden;
        box-shadow:0 10px 30px rgba(0,0,0,.06);
    }

    .table-modern{
        width:100%;
        border-collapse:collapse;
    }

    .table-modern thead{
        background:#faf5ff;
    }

    .table-modern th{
        padding:18px;
        text-align:left;
        color:#6b21a8;
        font-weight:700;
        border-bottom:1px solid #e9d5ff;
    }

    .table-modern td{
        padding:16px;
        border-bottom:1px solid #f1f5f9;
        vertical-align: top;
    }

    .table-modern tbody tr:hover{
        background:#faf5ff;
        transition:.3s;
    }

    .badge{
        padding:6px 12px;
        border-radius:20px;
        font-size:12px;
        font-weight:600;
    }

    .pagada{
        background:#dcfce7;
        color:#166534;
    }

    .cancelada{
        background:#fee2e2;
        color:#991b1b;
    }

    .actions{
        display:flex;
        flex-direction: column;
        gap:8px;
    }

    .btn{
        text-decoration:none;
        padding:8px 14px;
        border-radius:10px;
        font-size:13px;
        font-weight:600;
        transition:.3s;
        border:none;
        cursor:pointer;
        display: inline-block;
        text-align: center;
    }

    .btn:hover{
        transform:translateY(-2px);
    }

    .btn-delete{
        background:#fee2e2;
        color:#dc2626;
    }

    .btn-notas{
        background:#1e293b;
        color:white;
        padding:10px 20px;
        border-radius:12px;
    }

    .price{
        color:#0f766e;
        font-weight:700;
    }

    .empty-card{
        background:white;
        padding:40px;
        border-radius:24px;
        text-align:center;
        box-shadow:0 10px 30px rgba(0,0,0,.06);
    }

    /* Buscador */
    .search-card {
        background: rgba(255,255,255,.90);
        backdrop-filter: blur(12px);
        padding: 20px;
        border-radius: 20px;
        margin-bottom: 25px;
        box-shadow: 0 5px 20px rgba(0,0,0,.04);
        border: 1px solid #f1f5f9;
    }

    .search-form {
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
        align-items: flex-end;
    }

    .search-group {
        flex: 1;
        min-width: 200px;
    }

    .search-label {
        font-size: 13px;
        font-weight: bold;
        color: #475569;
        display: block;
        margin-bottom: 6px;
    }

    .search-input {
        width: 100%;
        padding: 10px 14px;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        box-sizing: border-box;
        transition: 0.3s;
        background: #f8fafc;
        margin: 0; /* Override default input margin */
    }

    .search-input:focus {
        border-color: #14b8a6;
        background: white;
        outline: none;
        box-shadow: 0 0 0 3px rgba(255,255,255,.1);
    }

    .btn-search {
        background: #3b82f6;
        color: white;
        padding: 10px 20px;
        border-radius: 12px;
        border: none;
        font-weight: bold;
        cursor: pointer;
        transition: 0.3s;
        height: 42px; /* Match input height */
    }

    .btn-search:hover {
        background: #2563eb;
    }

    .btn-clear {
        background: #f1f5f9;
        color: #64748b;
        padding: 10px 20px;
        border-radius: 12px;
        text-decoration: none;
        font-weight: bold;
        transition: 0.3s;
        height: 42px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .btn-clear:hover {
        background: #e2e8f0;
        color: #475569;
    }

</style>

<div class="header-card">
    <div>
        <h1>🧾 Gestión de Facturación</h1>
        <p>Consulta, administra y controla todas las facturas registradas.</p>
    </div>
    <a href="{{ route('notas-credito.index') }}" class="btn btn-notas">
        Ver Notas de Crédito
    </a>
</div>

<div class="search-card">
    <form method="GET" action="{{ route('facturas.index') }}" class="search-form">
        <div class="search-group">
            <label class="search-label">Número de Factura</label>
            <input type="text" name="numero" value="{{ request('numero') }}" placeholder="Ej: FAC-000001" class="search-input">
        </div>
        <div class="search-group">
            <label class="search-label">Fecha de Emisión</label>
            <input type="date" name="fecha" value="{{ request('fecha') }}" class="search-input">
        </div>
        <div class="search-group">
            <label class="search-label">Nombre del Cliente</label>
            <input type="text" name="cliente" value="{{ request('cliente') }}" placeholder="Buscar por cliente..." class="search-input">
        </div>
        <div style="display: flex; gap: 8px;">
            <button type="submit" class="btn-search">Buscar</button>
            <a href="{{ route('facturas.index') }}" class="btn-clear">Limpiar</a>
        </div>
    </form>
</div>

@if($facturas->count())

<div class="table-card">
    <table class="table-modern">
        <thead>
            <tr>
                <th>Número</th>
                <th>Cliente</th>
                <th>Fecha</th>
                <th>Estado</th>
                <th>Total</th>
                <th>Productos</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($facturas as $factura)
            <tr>
                <td>
                    <strong>{{ $factura->numero_factura }}</strong>
                </td>
                <td>
                    {{ $factura->cliente?->name ?? 'Sin cliente' }}
                </td>
                <td>
                    {{ $factura->fecha_emision->format('d/m/Y') }}
                </td>
                <td>
                    @if($factura->estado == 'emitida')
                        <span class="badge pagada">Emitida</span>
                    @else
                        <span class="badge cancelada">Anulada</span>
                    @endif
                </td>
                <td class="price">
                    ${{ number_format($factura->total, 0, ',', '.') }}
                </td>
                <td>
                    @foreach($factura->detalles as $detalle)
                        <div style="font-size: 13px; margin-bottom: 4px; padding: 4px 8px; background: #f8fafc; border-radius: 6px;">
                            <strong>{{ $detalle->producto?->name }}</strong> x{{ $detalle->cantidad }} <br>
                            <span style="color: #059669; font-weight: bold;">${{ number_format($detalle->subtotal, 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                </td>
                <td>
                    <div class="actions">
                        <a href="{{ route('facturas.show', $factura->id) }}" class="btn" style="background:#e0e7ff; color:#4338ca; padding: 6px 12px; margin-bottom: 4px;">Ver</a>
                        @if($factura->estado === 'emitida')
                            @if(Auth::user()->role === 'admin')
                                <a href="{{ route('facturas.edit', $factura->id) }}" class="btn" style="background:#e6fffb; color:#0f766e; padding: 6px 12px; margin-bottom: 4px;">Editar</a>
                                <form 
                                    action="{{ route('facturas.anular', $factura->id) }}"
                                    method="POST"
                                    onsubmit="return confirm('¿Seguro que deseas anular esta factura? Se generará una nota crédito y se reintegrará el inventario.')"
                                >
                                    @csrf
                                    @method('PUT')
                                    <textarea name="motivo_anulacion" rows="2" placeholder="Motivo de anulación" required style="width: 100%; min-width: 150px; padding: 8px; border-radius: 8px; border: 1px solid #ddd; margin-bottom: 8px; font-size: 12px; resize: vertical;"></textarea>
                                    <button type="submit" class="btn btn-delete" style="width: 100%;">Anular factura</button>
                                </form>
                            @else
                                <span style="color: #64748b; font-size: 13px; padding: 6px; background: #f1f5f9; border-radius: 8px; display: inline-block; text-align: center; margin-top: 4px;">Sin permisos para anular</span>
                            @endif

                        @else
                            <div style="font-size: 12px; background: #f8fafc; padding: 8px; border-radius: 8px; border: 1px solid #e2e8f0;">
                                <strong style="color: #475569;">Nota crédito:</strong><br>
                                <span style="color: #64748b; font-weight: bold;">{{ $factura->notasCredito->first()?->numero_nota_credito }}</span>
                            </div>
                        @endif
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@else

<div class="empty-card">
    <h3>No hay facturas registradas</h3>
    <p style="margin-top:10px;color:#64748b;">
        Ajusta tus filtros de búsqueda o genera facturas desde la Caja POS.
    </p>
</div>

@endif

@endsection
@extends('historial_ventas.layout')

@section('title', 'Venta ' . $venta->numero_venta)

@section('content')

<style>
    .top{ display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px; margin-bottom:20px; }
    .top h1{ margin:0; color:#1e293b; font-size:26px; font-weight:700; }

    .badge{ padding:6px 14px; border-radius:20px; font-size:13px; font-weight:700; }
    .badge-pagada{ background:#dcfce7; color:#166534; }
    .badge-anulada{ background:#fee2e2; color:#991b1b; }

    .info-grid{
        display:grid; grid-template-columns:repeat(auto-fit,minmax(200px,1fr));
        gap:14px; margin-bottom:24px;
    }
    .info-item{ background:#f8fafc; border:1px solid #e2e8f0; border-radius:14px; padding:14px; }
    .info-item span{ display:block; font-size:12px; color:#64748b; text-transform:uppercase; margin-bottom:4px; }
    .info-item strong{ font-size:16px; color:#0f172a; }

    .table-modern{ width:100%; border-collapse:collapse; margin-bottom:24px; }
    .table-modern thead{ background:#ecfeff; }
    .table-modern th{ padding:12px 14px; text-align:left; color:#0f766e; font-weight:700; border-bottom:1px solid #ccfbf1; }
    .table-modern td{ padding:12px 14px; border-bottom:1px solid #f1f5f9; }

    .prod-foto{ width:45px; height:45px; object-fit:cover; border-radius:8px; border:1px solid #ddd; }

    .totales{ text-align:right; font-size:16px; }
    .totales .total{ font-size:22px; font-weight:700; color:#0f766e; }

    .factura-box{ background:#f0fdfa; border:1px solid #ccfbf1; border-radius:16px; padding:20px; margin-top:10px; }
    .factura-box h3{ color:#0f766e; margin-bottom:10px; }

    .btn{ text-decoration:none; padding:9px 16px; border-radius:10px; font-weight:600; display:inline-block; border:none; cursor:pointer; }
    .btn-print{ background:#ccfbf1; color:#0f766e; }
    .btn-back{ background:#f1f5f9; color:#475569; }
    .btn-anular{ background:#dc2626; color:#fff; margin-top:8px; }

    textarea{ width:100%; padding:10px; border:1px solid #cbd5e1; border-radius:10px; margin-top:8px; }
</style>

<div class="top">
    <h1>Venta {{ $venta->numero_venta }}</h1>
    <span class="badge {{ $venta->estado === 'pagada' ? 'badge-pagada' : 'badge-anulada' }}">
        {{ ucfirst($venta->estado) }}
    </span>
</div>

<div class="info-grid">
    <div class="info-item"><span>Fecha</span><strong>{{ \Illuminate\Support\Carbon::parse($venta->fecha)->format('d/m/Y') }}</strong></div>
    <div class="info-item"><span>Cliente</span><strong>{{ $venta->cliente?->name ?? 'Sin cliente' }}</strong></div>
    <div class="info-item"><span>Cajero</span><strong>{{ $venta->usuario?->name ?? '—' }}</strong></div>
    <div class="info-item"><span>Método de pago</span><strong style="text-transform:capitalize;">{{ $venta->metodo_pago }}</strong></div>
    <div class="info-item"><span>Dinero recibido</span><strong>${{ number_format($venta->dinero_recibido, 0, ',', '.') }}</strong></div>
    <div class="info-item"><span>Cambio</span><strong>${{ number_format($venta->cambio, 0, ',', '.') }}</strong></div>
</div>

<table class="table-modern">
    <thead>
        <tr>
            <th>Foto</th>
            <th>Producto</th>
            <th>Cantidad</th>
            <th>Precio unit.</th>
            <th>Subtotal</th>
        </tr>
    </thead>
    <tbody>
        @foreach($venta->detalles as $detalle)
            <tr>
                <td><img src="{{ $detalle->product?->photo_url }}" alt="{{ $detalle->product?->name }}" class="prod-foto"></td>
                <td><strong>{{ $detalle->product?->name ?? 'Producto eliminado' }}</strong></td>
                <td>{{ $detalle->cantidad }}</td>
                <td>${{ number_format($detalle->precio_unitario, 0, ',', '.') }}</td>
                <td style="color:#0f766e; font-weight:bold;">${{ number_format($detalle->subtotal, 0, ',', '.') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<div class="totales">
    <p>Subtotal: ${{ number_format($venta->subtotal, 0, ',', '.') }}</p>
    <p class="total">Total: ${{ number_format($venta->total, 0, ',', '.') }}</p>
</div>

<div class="factura-box">
    <h3>Factura</h3>

    @if($venta->factura)
        <p>
            N° <strong>{{ $venta->factura->numero_factura }}</strong> ·
            Estado: <strong>{{ ucfirst($venta->factura->estado) }}</strong>
        </p>

        @if($venta->factura->estado === 'anulada')
            <p style="color:#991b1b; margin-top:6px;">
                Anulada el {{ \Illuminate\Support\Carbon::parse($venta->factura->fecha_anulacion)->format('d/m/Y') }}
                @if($venta->factura->anuladaPor) por <strong>{{ $venta->factura->anuladaPor->name }}</strong>@endif.
                <br>Motivo: {{ $venta->factura->motivo_anulacion }}
            </p>
        @endif
        <div style="margin-top:10px; display:flex; gap:8px; flex-wrap:wrap;">
            <a href="{{ route('facturas.imprimir', $venta->factura) }}" class="btn btn-print" target="_blank">Imprimir factura</a>
        </div>

        @can('anular-facturas')
            @if($venta->factura->estado === 'emitida')
                <form method="POST" action="{{ route('facturas.anular', $venta->factura) }}"
                      onsubmit="return confirm('¿Seguro que deseas anular esta factura? Se generará una nota crédito y se reintegrará el inventario.')"
                      style="margin-top:14px; max-width:420px;">
                    @csrf
                    @method('PUT')
                    <textarea name="motivo_anulacion" rows="2" placeholder="Motivo de anulación" required></textarea>
                    <button type="submit" class="btn btn-anular">Anular factura</button>
                </form>
            @endif
        @endcan
    @else
        <p style="color:#64748b;">Esta venta aún no tiene factura generada.</p>
        <form method="POST" action="{{ route('facturas.generar', $venta) }}" style="margin-top:10px;">
            @csrf
            <button type="submit" class="btn btn-print">Generar factura</button>
        </form>
    @endif
</div>

<div style="margin-top:24px;">
    <a href="{{ route('historial-ventas.index') }}" class="btn btn-back">← Volver al historial</a>
</div>

@endsection

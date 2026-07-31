<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Facturas</title>
    <style>
        body { font-family: Arial, sans-serif; background: linear-gradient(135deg, #0d3f3c 0%, #1c7a74 45%, #40E0D0 100%); padding: 20px; margin: 0; color: #0f172a; }
        .panel { background: rgba(255,255,255,.90); padding: 20px; border-radius: 14px; box-shadow: 0 10px 30px rgba(0,0,0,0.06); }
        .alert-success { background: #dcfce7; border: 1px solid #86efac; color: #166534; padding: 12px; border-radius: 8px; margin-bottom: 15px; }
        .alert-error { background: #fee2e2; border: 1px solid #fca5a5; color: #991b1b; padding: 12px; border-radius: 8px; margin-bottom: 15px; }
        table { width: 100%; border-collapse: collapse; margin-top: 14px; }
        th, td { padding: 10px; border-bottom: 1px solid #e5e7eb; text-align: left; font-size: 14px; vertical-align: top; }
        th { background: #f9fafb; }
        .estado-emitida { color: #15803d; font-weight: bold; }
        .estado-anulada { color: #b91c1c; font-weight: bold; }
        textarea { width: 100%; padding: 8px; border-radius: 8px; border: 1px solid #ddd; box-sizing: border-box; }
        button { padding: 8px 12px; border: none; border-radius: 8px; font-weight: bold; cursor: pointer; }
        .btn-anular { background: #dc2626; color: white; margin-top: 6px; }
    </style>
</head>
<body>

<x-header-navbar 
    title="Gestión de Facturas" 
    :navLinks="[
        ['label' => 'Facturas', 'url' => route('facturas.index')],
    ]"
    primaryAction="Ir a Caja POS"
    :primaryActionUrl="route('caja.productos.index')"
/>

@if(session('success'))
    <div class="alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="alert-error">{{ session('error') }}</div>
@endif

<div class="panel">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h2 style="margin: 0;">Listado de facturas</h2>
    </div>

    <div style="margin-top: 20px; margin-bottom: 20px; background: #f8fafc; padding: 15px; border-radius: 12px; border: 1px solid #e2e8f0;">
        <form method="GET" action="{{ route('facturas.index') }}" style="display: flex; gap: 15px; flex-wrap: wrap; align-items: flex-end;">
            <div style="flex: 1; min-width: 200px;">
                <label style="font-size: 13px; font-weight: bold; color: #0f172a; display: block; margin-bottom: 5px;">Número de Factura</label>
                <input type="text" name="numero" value="{{ request('numero') }}" placeholder="Ej: FAC-000001" style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #cbd5e1; box-sizing: border-box;">
            </div>
            <div style="flex: 1; min-width: 200px;">
                <label style="font-size: 13px; font-weight: bold; color: #0f172a; display: block; margin-bottom: 5px;">Fecha de Emisión</label>
                <input type="date" name="fecha" value="{{ request('fecha') }}" style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #cbd5e1; box-sizing: border-box;">
            </div>
            <div style="flex: 1; min-width: 200px;">
                <label style="font-size: 13px; font-weight: bold; color: #0f172a; display: block; margin-bottom: 5px;">Nombre del Cliente</label>
                <input type="text" name="cliente" value="{{ request('cliente') }}" placeholder="Buscar cliente..." style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #cbd5e1; box-sizing: border-box;">
            </div>
            <div>
                <button type="submit" style="background: #3b82f6; color: white; padding: 10px 20px; border-radius: 8px; border: none; font-weight: bold; cursor: pointer;">Buscar</button>
                <a href="{{ route('facturas.index') }}" style="background: #f8fafc; color: #0f172a; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: bold; display: inline-block; margin-left: 8px; border: 1px solid #dbe4ee;">Limpiar</a>
            </div>
        </form>
    </div>

    <table>
        <thead>
            <tr>
                <th>Número</th>
                <th>Cliente</th>
                <th>Fecha</th>
                <th>Total</th>
                <th>Estado</th>
                <th>Productos</th>
                <th>Acción</th>
            </tr>
        </thead>

        <tbody>
            @forelse($facturas as $factura)
                <tr>
                    <td><strong>{{ $factura->numero_factura }}</strong></td>
                    <td>{{ $factura->cliente?->name ?? 'Sin cliente' }}</td>
                    <td>{{ $factura->fecha_emision->format('d/m/Y') }}</td>
                    <td>${{ number_format($factura->total, 0, ',', '.') }}</td>
                    <td>

    {{-- Botón Imprimir --}}
    <a
        href="{{ route('facturas.imprimir', $factura->id) }}"
        target="_blank"
        style="display:inline-block; background:#2563eb; color:white; padding:8px 12px; border-radius:8px; text-decoration:none; margin-bottom:6px;"
    >
        Imprimir
    </a>

    @if($factura->estado === 'emitida')
        @if(auth()->user()->can('anular-facturas'))
            <form 
                method="POST" 
                action="{{ route('facturas.anular', $factura->id) }}"
                onsubmit="return confirm('¿Seguro que deseas anular esta factura? Se generará una nota crédito y se reintegrará el inventario.')"
            >
                @csrf
                @method('PUT')
                <textarea name="motivo_anulacion" rows="2" placeholder="Motivo de anulación" required></textarea>
                <button type="submit" class="btn-anular">Anular factura</button>
            </form>
        @else
            <span style="color: #475569; font-size: 13px;">Sin permisos para anular</span>
        @endif
    @else
        <strong>Nota crédito generada</strong><br>
        <span style="color: #475569;">{{ $factura->notasCredito->first()?->numero_nota_credito }}</span>
    @endif

</td>
                    <td>
                        @if($factura->estado === 'emitida')
                            <span class="estado-emitida">Emitida</span>
                        @else
                            <span class="estado-anulada">Anulada</span>
                        @endif
                    </td>
                    <td>
                        @foreach($factura->detalles as $detalle)
                            <div style="margin-bottom: 4px;">
                                <span style="font-weight: bold;">{{ $detalle->producto?->name }}</span>
                                <span style="color: #666;">x {{ $detalle->cantidad }}</span>
                                <span style="color: #059669; font-weight: bold;">- ${{ number_format($detalle->subtotal, 0, ',', '.') }}</span>
                            </div>
                        @endforeach
                    </td>
                    <td>
                        @if($factura->estado === 'emitida')
                            @if(auth()->user()->can('anular-facturas'))
                                <form 
                                    method="POST" 
                                    action="{{ route('facturas.anular', $factura->id) }}"
                                    onsubmit="return confirm('¿Seguro que deseas anular esta factura? Se generará una nota crédito y se reintegrará el inventario.')"
                                >
                                    @csrf
                                    @method('PUT')
                                    <textarea name="motivo_anulacion" rows="2" placeholder="Motivo de anulación" required></textarea>
                                    <button type="submit" class="btn-anular">Anular factura</button>
                                </form>
                            @else
                                <span style="color: #475569; font-size: 13px;">Sin permisos para anular</span>
                            @endif
                        @else
                            <strong>Nota crédito generada</strong><br>
                            <span style="color: #475569;">{{ $factura->notasCredito->first()?->numero_nota_credito }}</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; color: #94a3b8; padding: 20px;">No hay facturas registradas.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

</body>
</html>

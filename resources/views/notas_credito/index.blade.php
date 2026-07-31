<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Notas Crédito</title>
    <style>
        body { font-family: Arial, sans-serif; background: linear-gradient(135deg, #0d3f3c 0%, #1c7a74 45%, #40E0D0 100%); padding: 20px; margin: 0; color: #0f172a; }
        .panel { background: rgba(255,255,255,.90); padding: 20px; border-radius: 14px; box-shadow: 0 10px 30px rgba(0,0,0,0.06); }
        table { width: 100%; border-collapse: collapse; margin-top: 14px; }
        th, td { padding: 10px; border-bottom: 1px solid #e5e7eb; text-align: left; font-size: 14px; vertical-align: top; }
        th { background: #f9fafb; }
    </style>
</head>
<body>

<x-header-navbar 
    title="Gestión de Facturas" 
    :navLinks="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Facturas', 'url' => route('facturas.index')],
    ]"
    primaryAction="Ir a Caja POS"
    :primaryActionUrl="route('caja.productos.index')"
/>

<div class="panel">
    <table>
        <thead>
            <tr>
                <th>Número nota crédito</th>
                <th>Factura anulada</th>
                <th>Cliente</th>
                <th>Fecha</th>
                <th>Total</th>
                <th>Motivo</th>
                <th>Productos reintegrados</th>
            </tr>
        </thead>

        <tbody>
            @forelse($notasCredito as $nota)
                <tr>
                    <td><strong>{{ $nota->numero_nota_credito }}</strong></td>
                    <td>{{ $nota->factura?->numero_factura }}</td>
                    <td>{{ $nota->factura?->cliente?->name ?? 'Sin cliente' }}</td>
                    <td>{{ $nota->fecha->format('d/m/Y') }}</td>
                    <td style="color: #dc2626; font-weight: bold;">${{ number_format($nota->total, 0, ',', '.') }}</td>
                    <td>{{ $nota->motivo }}</td>
                    <td>
                        @foreach($nota->detalles as $detalle)
                            <div style="margin-bottom: 4px;">
                                <span style="font-weight: bold;">{{ $detalle->producto?->name }}</span> 
                                <span style="color: #666;">x {{ $detalle->cantidad }}</span>
                            </div>
                        @endforeach
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; color: #94a3b8; padding: 20px;">No hay notas crédito registradas.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

</body>
</html>

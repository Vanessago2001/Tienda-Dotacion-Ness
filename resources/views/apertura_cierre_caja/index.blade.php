<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Apertura y Cierre de Caja</title>
    <link rel="icon" href="{{ asset('images/logo_happy_store.png') }}">
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: linear-gradient(135deg, #0d3f3c 0%, #1c7a74 45%, #40E0D0 100%); margin: 0; padding: 30px; min-height: 100vh; color: #0f172a; }
        h1 { margin-bottom: 8px; color: #0d3f3c; font-size: 32px; }
        .subtitle { color: #0f766e; margin-bottom: 20px; }
        .dashboard { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 20px; }
        .card, .panel { background: rgba(255,255,255,0.92); padding: 20px; border-radius: 20px; box-shadow: 0 10px 24px rgba(0,0,0,0.06); }
        .card h3 { margin: 0; font-size: 15px; color: #0f766e; }
        .card p { margin: 10px 0 0; font-size: 24px; font-weight: bold; color: #0d3f3c; }
        .contenedor { display: grid; grid-template-columns: 35% 65%; gap: 20px; }
        label { display: block; margin-top: 12px; font-weight: bold; font-size: 14px; color: #0f766e; }
        input, textarea { width: 100%; padding: 10px; margin-top: 6px; border: 1px solid #8ee7dd; border-radius: 10px; box-sizing: border-box; background: #f7fffe; }
        input:focus, textarea:focus { outline: none; border-color: #40E0D0; box-shadow: 0 0 0 3px rgba(64, 224, 208, 0.18); }
        button { padding: 10px 14px; border: none; border-radius: 10px; font-weight: bold; cursor: pointer; }
        .btn-abrir { width: 100%; margin-top: 18px; background: linear-gradient(135deg, #14b8a6, #2dd4bf); color: white; }
        .btn-cerrar { width: 100%; margin-top: 18px; background: linear-gradient(135deg, #dc2626, #ef4444); color: white; }
        .estado-abierta { color: #15803d; font-weight: bold; }
        .estado-cerrada { color: #b91c1c; font-weight: bold; }
        .alert-success { background: #dcfce7; border: 1px solid #86efac; color: #166534; padding: 12px; border-radius: 8px; margin-bottom: 15px; }
        .alert-error { background: #fee2e2; border: 1px solid #fca5a5; color: #991b1b; padding: 12px; border-radius: 8px; margin-bottom: 15px; }
        table { width: 100%; border-collapse: collapse; margin-top: 14px; }
        th, td { padding: 10px; border-bottom: 1px solid #e5e7eb; text-align: left; font-size: 14px; color: #0d3f3c; }
        th { background: #e6fbf8; color: #0f766e; }
        tbody tr:hover { background: #f2fffd; }
        .positivo { color: #15803d; font-weight: bold; }
        .negativo { color: #b91c1c; font-weight: bold; }
        .btn-volver { display:inline-block; background:#e6fbf8; color:#0d3f3c; padding:10px 14px; border-radius:8px; text-decoration:none; margin-bottom:15px; border: 1px solid #baf2eb; }
        @media (max-width: 950px) { .dashboard { grid-template-columns: 1fr; } .contenedor { grid-template-columns: 1fr; } }
    </style>
</head>
<body>

<x-header-navbar 
    title="Apertura y Cierre de Caja" 
    :navLinks="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Caja', 'url' => route('caja.productos.index')],
    ]"
    :estadoCaja="(bool)$cajaAbierta"
    :estadoCajaUrl="route('apertura-cierre-caja.index')"
/>

@if(session('success'))
    <div class="alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="alert-error">{{ session('error') }}</div>
@endif

@if($errors->any())
    <div class="alert-error">
        <strong>Errores encontrados:</strong>
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@php
    $saldoInicialActual = $cajaAbierta?->saldo_inicial ?? 0;
    $saldoEsperadoActual = $saldoInicialActual + $resumen['ventas_efectivo'] + $resumen['entradas_adicionales'] - $resumen['salidas_gastos'];
@endphp

<div class="dashboard">
    <div class="card"><h3>Estado de caja</h3><p>@if($cajaAbierta)<span class="estado-abierta">Abierta</span>@else<span class="estado-cerrada">Cerrada</span>@endif</p></div>
    <div class="card"><h3>Saldo inicial</h3><p>${{ number_format($saldoInicialActual, 0, ',', '.') }}</p></div>
    <div class="card"><h3>Ventas efectivo</h3><p>${{ number_format($resumen['ventas_efectivo'], 0, ',', '.') }}</p></div>
    <div class="card"><h3>Saldo esperado</h3><p>${{ number_format($saldoEsperadoActual, 0, ',', '.') }}</p></div>
</div>

<div class="dashboard">
    <div class="card"><h3>Total ventas</h3><p>${{ number_format($resumen['total_ventas'], 0, ',', '.') }}</p></div>
    <div class="card"><h3>Entradas adicionales</h3><p>${{ number_format($resumen['entradas_adicionales'], 0, ',', '.') }}</p></div>
    <div class="card"><h3>Gastos / salidas</h3><p>${{ number_format($resumen['salidas_gastos'], 0, ',', '.') }}</p></div>
    <div class="card"><h3>Ventas no efectivo</h3><p>${{ number_format($resumen['ventas_transferencia'] + $resumen['ventas_tarjeta'] + $resumen['ventas_nequi'] + $resumen['ventas_daviplata'], 0, ',', '.') }}</p></div>
</div>

<div class="contenedor">
    <div class="panel">
        @if(!$cajaAbierta)
            <h2>Abrir caja</h2>
            <form method="POST" action="{{ route('apertura-cierre-caja.abrir') }}">
                @csrf
                <label for="saldo_inicial">Saldo inicial</label>
                <input type="number" step="0.01" name="saldo_inicial" id="saldo_inicial" required>
                <label for="observacion_apertura">Observación de apertura</label>
                <textarea name="observacion_apertura" id="observacion_apertura" rows="3" placeholder="Ej: Caja abierta con base inicial"></textarea>
                <button type="submit" class="btn-abrir">Abrir caja</button>
            </form>
        @else
            <h2>Cerrar caja</h2>
            <p><strong>Fecha apertura:</strong> {{ $cajaAbierta->fecha_apertura->format('d/m/Y h:i A') }}</p>
            <p><strong>Saldo esperado en efectivo:</strong> ${{ number_format($saldoEsperadoActual, 0, ',', '.') }}</p>
            <form method="POST" action="{{ route('apertura-cierre-caja.cerrar', $cajaAbierta->id) }}" onsubmit="return confirm('¿Seguro que deseas cerrar la caja?')">
                @csrf
                <label for="dinero_contado">Dinero contado físicamente</label>
                <input type="number" step="0.01" name="dinero_contado" id="dinero_contado" required>
                <label for="observacion_cierre">Observación de cierre</label>
                <textarea name="observacion_cierre" id="observacion_cierre" rows="3" placeholder="Ej: Caja cerrada sin novedades"></textarea>
                <button type="submit" class="btn-cerrar">Cerrar caja</button>
            </form>
        @endif
    </div>

    <div class="panel">
        <h2>Resumen por método de pago</h2>
        <table>
            <thead><tr><th>Método</th><th>Total</th></tr></thead>
            <tbody>
                <tr><td>Efectivo</td><td>${{ number_format($resumen['ventas_efectivo'], 0, ',', '.') }}</td></tr>
                <tr><td>Transferencia</td><td>${{ number_format($resumen['ventas_transferencia'], 0, ',', '.') }}</td></tr>
                <tr><td>Tarjeta</td><td>${{ number_format($resumen['ventas_tarjeta'], 0, ',', '.') }}</td></tr>
                <tr><td>Nequi</td><td>${{ number_format($resumen['ventas_nequi'], 0, ',', '.') }}</td></tr>
                <tr><td>Daviplata</td><td>${{ number_format($resumen['ventas_daviplata'], 0, ',', '.') }}</td></tr>
            </tbody>
        </table>

        <h2>Historial de cajas</h2>
        <table>
            <thead><tr><th>Fecha</th><th>Estado</th><th>Saldo inicial</th><th>Esperado</th><th>Contado</th><th>Diferencia</th></tr></thead>
            <tbody>
                @forelse($historialCajas as $caja)
                    <tr>
                        <td>{{ $caja->fecha->format('d/m/Y') }}</td>
                        <td>@if($caja->estado === 'abierta')<span class="estado-abierta">Abierta</span>@else<span class="estado-cerrada">Cerrada</span>@endif</td>
                        <td>${{ number_format($caja->saldo_inicial, 0, ',', '.') }}</td>
                        <td>${{ number_format($caja->saldo_esperado, 0, ',', '.') }}</td>
                        <td>@if($caja->dinero_contado !== null)${{ number_format($caja->dinero_contado, 0, ',', '.') }}@else Pendiente @endif</td>
                        <td>
                            @if($caja->diferencia > 0)
                                <span class="positivo">+${{ number_format($caja->diferencia, 0, ',', '.') }}</span>
                            @elseif($caja->diferencia < 0)
                                <span class="negativo">-${{ number_format(abs($caja->diferencia), 0, ',', '.') }}</span>
                            @else
                                $0
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6">No hay cajas registradas.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
</body>
</html>

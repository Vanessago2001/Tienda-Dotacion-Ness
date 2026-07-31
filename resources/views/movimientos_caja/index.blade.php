<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Movimientos de Caja</title>

    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: linear-gradient(135deg, #f8fafc, #f3e8ff, #eef2ff); margin: 0; padding: 30px; min-height: 100vh; }
        

        h1 { margin: 0; color: #1e293b; font-size: 32px; font-weight: 700; text-shadow: 0 1px 2px rgba(0,0,0,0.05); }
        
        .dashboard { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 25px; }
        .card, .panel { background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(12px); border-radius: 24px; padding: 25px; box-shadow: 0 10px 30px rgba(0,0,0,0.06); }
        .card h3 { margin: 0; font-size: 15px; color: #64748b; font-weight: 600; }
        .card p { margin: 10px 0 0; font-size: 32px; font-weight: 700; color: #ffffff; }
        
        .contenedor { display: grid; grid-template-columns: 35% 63%; gap: 2%; }
        .panel h2 { font-size: 20px; color: #1e293b; margin-top: 0; margin-bottom: 20px; border-bottom: 2px solid #ccfbf1; padding-bottom: 10px; }
        
        label { display: block; margin-top: 15px; font-weight: 600; font-size: 14px; color: #475569; }
        input, select, textarea { width: 100%; padding: 12px; margin-top: 8px; border: 1px solid #b7f3ec; border-radius: 12px; box-sizing: border-box; background: white; transition: 0.3s; }
        input:focus, select:focus, textarea:focus { outline: none; border-color: #14b8a6; box-shadow: 0 0 0 4px rgba(20, 184, 166, 0.15); }
        
        button { padding: 12px 18px; border: none; border-radius: 12px; cursor: pointer; font-weight: 600; transition: 0.3s; }
        
        .btn-principal { width: 100%; margin-top: 25px; background: linear-gradient(135deg, #14b8a6, #2dd4bf); color: white; font-size: 16px; }
        .btn-principal:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(20, 184, 166, 0.3); }
        
        .btn-agregar { background: #e6fffb; color: #0f766e; text-decoration: none; }
        .btn-agregar:hover { background: #d7f9f4; transform: translateY(-2px); }
        
        .btn-logout { background: #fee2e2; color: #dc2626; padding: 10px 20px; border-radius: 12px; font-weight: bold; }
        .btn-logout:hover { background: #fca5a5; transform: translateY(-2px); }

        .btn-anular { background: #f59e0b; color: white; margin-bottom: 4px; padding: 8px 12px; border-radius: 10px; font-size: 13px; }
        .btn-editar { background: #3b82f6; color: white; text-decoration: none; display: inline-block; text-align: center; margin-bottom: 4px; padding: 8px 12px; border-radius: 10px; font-size: 13px; font-weight: bold; }
        .btn-eliminar { background: #dc2626; color: white; padding: 8px 12px; border-radius: 10px; font-size: 13px; }
        
        .action-buttons { display: flex; flex-direction: column; gap: 5px; }

        .entrada { color: #16a34a; font-weight: bold; }
        .salida { color: #dc2626; font-weight: bold; }

        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 14px 10px; border-bottom: 1px solid #f1f5f9; text-align: left; font-size: 14px; }
        th { background: #f0fdfa; color: #0f766e; font-weight: 700; border-radius: 8px; }
        tbody tr:hover { background: #f0fdfa; transition: 0.3s; }
        
        .alert-success { background: #dcfce7; border: 1px solid #86efac; color: #166534; padding: 15px; border-radius: 12px; margin-bottom: 20px; border-left: 5px solid #22c55e; font-weight: 600; }
        .alert-error { background: #fee2e2; border: 1px solid #fca5a5; color: #991b1b; padding: 15px; border-radius: 12px; margin-bottom: 20px; border-left: 5px solid #ef4444; font-weight: 600; }

        @media (max-width: 950px) { .dashboard { grid-template-columns: repeat(2, 1fr); } .contenedor { grid-template-columns: 1fr; } body { padding: 15px; } }
        @media (max-width: 600px) { .dashboard { grid-template-columns: 1fr; } }
    </style>
</head>
<body>

<x-header-navbar 
    title="Movimientos de Caja" 
    :navLinks="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Caja', 'url' => route('caja.productos.index')],
    ]"
    primaryAction="Ir a Caja POS"
    :primaryActionUrl="route('caja.productos.index')"
/>

@if(session('success'))
    <div class="alert-success">
        {{ session('success') }}
    </div>
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

<div class="dashboard">
    <div class="card">
        <h3>Ventas de hoy</h3>
        <p>${{ number_format($ventasHoy, 0, ',', '.') }}</p>
    </div>

    <div class="card">
        <h3>Entradas adicionales</h3>
        <p>${{ number_format($entradasHoy, 0, ',', '.') }}</p>
    </div>

    <div class="card">
        <h3>Gastos / salidas</h3>
        <p>${{ number_format($salidasHoy, 0, ',', '.') }}</p>
    </div>

    <div class="card">
        <h3>Saldo de caja</h3>
        <p>${{ number_format($saldoCaja, 0, ',', '.') }}</p>
    </div>
</div>

<div class="contenedor">

    <div class="panel">
        <h2>Registrar movimiento</h2>

        <form method="POST" action="{{ route('movimientos-caja.store') }}">
            @csrf

            <label for="tipo">Tipo de movimiento</label>
            <select name="tipo" id="tipo" required>
                <option value="">Seleccione</option>
                <option value="entrada">Entrada de dinero</option>
                <option value="salida">Salida / gasto</option>
            </select>

            <label for="concepto">Concepto</label>
            <input
                type="text"
                name="concepto"
                id="concepto"
                placeholder="Ej: Pago de arriendo, aporte inicial, transporte"
                required
            >

            <label for="categoria">Categoría</label>
            <select name="categoria" id="categoria" required>
                <option value="">Seleccione</option>
                <option value="aporte">Aporte</option>
                <option value="prestamo">Préstamo</option>
                <option value="ajuste_caja">Ajuste de caja</option>
                <option value="arriendo">Arriendo</option>
                <option value="servicios">Servicios</option>
                <option value="transporte">Transporte</option>
                <option value="compra_insumos">Compra de insumos</option>
                <option value="nomina">Nómina</option>
                <option value="mantenimiento">Mantenimiento</option>
                <option value="otro">Otro</option>
            </select>

            <label for="valor">Valor</label>
            <input
                type="number"
                step="0.01"
                name="valor"
                id="valor"
                placeholder="Ej: 50000"
                required
            >

            <label for="metodo_pago">Método de pago</label>
            <select name="metodo_pago" id="metodo_pago">
                <option value="">Seleccione</option>
                <option value="efectivo">Efectivo</option>
                <option value="transferencia">Transferencia</option>
                <option value="tarjeta">Tarjeta</option>
                <option value="nequi">Nequi</option>
                <option value="daviplata">Daviplata</option>
            </select>

            <label for="descripcion">Descripción</label>
            <textarea
                name="descripcion"
                id="descripcion"
                rows="3"
                placeholder="Observación del movimiento"
            ></textarea>

            <button type="submit" class="btn-principal">
                Guardar movimiento
            </button>
        </form>
    </div>

    <div class="panel">
        <h2>Movimientos de hoy</h2>

        <table>
            <thead>
                <tr>
                    <th>Tipo</th>
                    <th>Concepto</th>
                    <th>Categoría</th>
                    <th>Método</th>
                    <th>Valor</th>
                    @if(Auth::user() && Auth::user()->role === 'admin')
                    <th>Acción</th>
                    @endif
                </tr>
            </thead>

            <tbody>
                @forelse($movimientos as $movimiento)
                    <tr>
                        <td>
                            @if($movimiento->tipo === 'entrada')
                                <span class="entrada">Entrada</span>
                            @else
                                <span class="salida">Salida</span>
                            @endif
                        </td>

                        <td>{{ $movimiento->concepto }}</td>
                        <td>{{ ucfirst(str_replace('_', ' ', $movimiento->categoria)) }}</td>
                        <td>{{ ucfirst($movimiento->metodo_pago ?? 'No aplica') }}</td>
                        <td>${{ number_format($movimiento->valor, 0, ',', '.') }}</td>

                        @if(Auth::user() && Auth::user()->role === 'admin')
                        <td class="action-buttons">
                            <a href="{{ route('movimientos-caja.edit', $movimiento->id) }}" class="btn-editar" style="padding: 10px 14px; border-radius: 8px; font-weight: bold; font-size: 13px;">Editar</a>
                            
                            <form
                                method="POST"
                                action="{{ route('movimientos-caja.anular', $movimiento->id) }}"
                                onsubmit="return confirm('¿Seguro que deseas anular este movimiento?')"
                            >
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn-anular" style="width: 100%;">
                                    Anular
                                </button>
                            </form>

                            <form
                                method="POST"
                                action="{{ route('movimientos-caja.destroy', $movimiento->id) }}"
                                onsubmit="return confirm('¿Seguro que deseas eliminar permanentemente este movimiento?')"
                            >
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-eliminar" style="width: 100%;">
                                    Eliminar
                                </button>
                            </form>
                        </td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ (Auth::user() && Auth::user()->role === 'admin') ? '6' : '5' }}">
                            No hay movimientos registrados hoy.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

</body>
</html>

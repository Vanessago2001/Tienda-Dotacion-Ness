<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Movimiento</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: linear-gradient(135deg, #f8fafc, #f3e8ff, #eef2ff); margin: 0; padding: 30px; min-height: 100vh; display: flex; flex-direction: column; align-items: center; }
        

        h1 { margin: 0; color: #1e293b; font-size: 28px; font-weight: 700; text-shadow: 0 1px 2px rgba(0,0,0,0.05); }

        .panel { background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(12px); border-radius: 24px; padding: 35px; box-shadow: 0 10px 30px rgba(0,0,0,0.06); width: 100%; max-width: 600px; }
        h2 { font-size: 20px; color: #1e293b; margin-top: 0; margin-bottom: 20px; border-bottom: 2px solid #ede9fe; padding-bottom: 10px; }

        label { display: block; margin-top: 15px; font-weight: 600; font-size: 14px; color: #475569; }
        input, select, textarea { width: 100%; padding: 12px; margin-top: 8px; border: 1px solid #ddd6fe; border-radius: 12px; box-sizing: border-box; background: white; transition: 0.3s; }
        input:focus, select:focus, textarea:focus { outline: none; border-color: #14b8a6; box-shadow: 0 0 0 4px rgba(20, 184, 166, 0.15); }

        button { padding: 12px 20px; border: none; border-radius: 12px; font-weight: bold; font-size: 16px; cursor: pointer; transition: 0.3s; }

        .btn-principal { width: 100%; margin-top: 25px; background: linear-gradient(135deg, #14b8a6, #2dd4bf); color: white; font-size: 16px; }
        .btn-principal:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(124, 58, 237, 0.3); }

        .btn-cancelar { display: block; text-align: center; width: 100%; margin-top: 15px; padding: 12px 20px; background: #e0e7ff; color: #4338ca; text-decoration: none; border-radius: 12px; font-weight: bold; transition: 0.3s; }
        .btn-cancelar:hover { background: #ddd6fe; transform: translateY(-2px); }
        
        .btn-logout { background: #fee2e2; color: #dc2626; padding: 10px 20px; border-radius: 12px; font-weight: bold; cursor: pointer; border: none; }
        .btn-logout:hover { background: #fca5a5; transform: translateY(-2px); }

        .alert-error { background: #fee2e2; border: 1px solid #fca5a5; color: #991b1b; padding: 15px; border-radius: 12px; margin-bottom: 20px; border-left: 5px solid #ef4444; font-weight: 600; }
    </style>
</head>
<body>

<x-header-navbar 
    title="Editar Movimiento" 
    :navLinks="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Movimientos', 'url' => route('movimientos-caja.index')],
    ]"
    primaryAction="Ir a Caja POS"
    :primaryActionUrl="route('caja.productos.index')"
/>

<div class="panel">
    <h2>Actualizar Registro de Caja</h2>

    @if($errors->any())
        <div class="alert-error">
            <strong>Errores encontrados:</strong>
            <ul style="margin-top: 8px; margin-bottom: 0;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('movimientos-caja.update', $movimiento->id) }}">
        @csrf
        @method('PUT')

        <label for="tipo">Tipo de movimiento</label>
        <select name="tipo" id="tipo" required>
            <option value="entrada" {{ $movimiento->tipo == 'entrada' ? 'selected' : '' }}>Entrada de dinero</option>
            <option value="salida" {{ $movimiento->tipo == 'salida' ? 'selected' : '' }}>Salida / gasto</option>
        </select>

        <label for="concepto">Concepto</label>
        <input
            type="text"
            name="concepto"
            id="concepto"
            value="{{ old('concepto', $movimiento->concepto) }}"
            required
        >

        <label for="categoria">Categoría</label>
        <select name="categoria" id="categoria" required>
            <option value="aporte" {{ $movimiento->categoria == 'aporte' ? 'selected' : '' }}>Aporte</option>
            <option value="prestamo" {{ $movimiento->categoria == 'prestamo' ? 'selected' : '' }}>Préstamo</option>
            <option value="ajuste_caja" {{ $movimiento->categoria == 'ajuste_caja' ? 'selected' : '' }}>Ajuste de caja</option>
            <option value="arriendo" {{ $movimiento->categoria == 'arriendo' ? 'selected' : '' }}>Arriendo</option>
            <option value="servicios" {{ $movimiento->categoria == 'servicios' ? 'selected' : '' }}>Servicios</option>
            <option value="transporte" {{ $movimiento->categoria == 'transporte' ? 'selected' : '' }}>Transporte</option>
            <option value="compra_insumos" {{ $movimiento->categoria == 'compra_insumos' ? 'selected' : '' }}>Compra de insumos</option>
            <option value="nomina" {{ $movimiento->categoria == 'nomina' ? 'selected' : '' }}>Nómina</option>
            <option value="mantenimiento" {{ $movimiento->categoria == 'mantenimiento' ? 'selected' : '' }}>Mantenimiento</option>
            <option value="otro" {{ $movimiento->categoria == 'otro' ? 'selected' : '' }}>Otro</option>
        </select>

        <label for="valor">Valor</label>
        <input
            type="number"
            step="0.01"
            name="valor"
            id="valor"
            value="{{ old('valor', $movimiento->valor) }}"
            required
        >

        <label for="metodo_pago">Método de pago</label>
        <select name="metodo_pago" id="metodo_pago">
            <option value="">Seleccione</option>
            <option value="efectivo" {{ $movimiento->metodo_pago == 'efectivo' ? 'selected' : '' }}>Efectivo</option>
            <option value="transferencia" {{ $movimiento->metodo_pago == 'transferencia' ? 'selected' : '' }}>Transferencia</option>
            <option value="tarjeta" {{ $movimiento->metodo_pago == 'tarjeta' ? 'selected' : '' }}>Tarjeta</option>
            <option value="nequi" {{ $movimiento->metodo_pago == 'nequi' ? 'selected' : '' }}>Nequi</option>
            <option value="daviplata" {{ $movimiento->metodo_pago == 'daviplata' ? 'selected' : '' }}>Daviplata</option>
        </select>

        <label for="descripcion">Descripción</label>
        <textarea
            name="descripcion"
            id="descripcion"
            rows="3"
        >{{ old('descripcion', $movimiento->descripcion) }}</textarea>

        <button type="submit" class="btn-principal">
            Actualizar movimiento
        </button>
        
        <a href="{{ route('movimientos-caja.index') }}" class="btn-cancelar">Cancelar</a>
    </form>
</div>

</body>
</html>

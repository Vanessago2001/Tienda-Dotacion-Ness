@extends('layouts.panel')

@section('title', 'Nuevo movimiento de inventario')
@section('titulo', '📦 Nuevo movimiento')

@section('nav')
    <a href="{{ route('inventario.index') }}" class="btn-modulo">Inventario</a>
@endsection

@section('content')

<div class="header-card">
    <h1>Registrar movimiento</h1>
    <p>Entrada suma stock, salida resta stock, y ajuste fija el stock a un valor exacto.</p>
</div>

<form method="POST" action="{{ route('inventario.store') }}" class="form-card">
    @csrf

    <div class="campo">
        <label>Producto</label>
        <select name="product_id" id="product_id" required onchange="mostrarStock()">
            <option value="">Selecciona un producto</option>
            @foreach($productos as $p)
                <option value="{{ $p->id }}" data-stock="{{ $p->stock }}" @selected(old('product_id')==$p->id)>
                    {{ $p->name }} (stock: {{ $p->stock }})
                </option>
            @endforeach
        </select>
        <small id="stockActual" style="color:#0f766e; font-weight:600;"></small>
    </div>

    <div class="campo">
        <label>Tipo de movimiento</label>
        <select name="tipo" id="tipo" required onchange="ayudaTipo()">
            <option value="entrada" @selected(old('tipo')==='entrada')>Entrada (sumar)</option>
            <option value="salida"  @selected(old('tipo')==='salida')>Salida (restar)</option>
            <option value="ajuste"  @selected(old('tipo')==='ajuste')>Ajuste (fijar stock)</option>
        </select>
    </div>

    <div class="campo">
        <label id="labelCantidad">Cantidad</label>
        <input type="number" name="cantidad" value="{{ old('cantidad') }}" min="0" required>
    </div>

    <div class="campo">
        <label>Motivo (opcional)</label>
        <input type="text" name="motivo" value="{{ old('motivo') }}" placeholder="Ej: Compra a proveedor, producto dañado, conteo físico...">
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Registrar movimiento</button>
        <a href="{{ route('inventario.index') }}" class="btn btn-secondary">Cancelar</a>
    </div>
</form>

<script>
    function mostrarStock(){
        const opt = document.getElementById('product_id').selectedOptions[0];
        const s = document.getElementById('stockActual');
        s.textContent = opt && opt.dataset.stock !== undefined ? ('Stock actual: ' + opt.dataset.stock) : '';
    }
    function ayudaTipo(){
        const tipo = document.getElementById('tipo').value;
        const label = document.getElementById('labelCantidad');
        label.textContent = tipo === 'ajuste' ? 'Nuevo stock (valor exacto)' : 'Cantidad';
    }
    mostrarStock(); ayudaTipo();
</script>

@endsection

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Caja con Productos</title>
    <link rel="icon" href="{{ asset('images/logo_happy_store.png') }}">

    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: linear-gradient(135deg, #0d3f3c 0%, #1c7a74 45%, #40E0D0 100%); margin: 0; padding: 30px; min-height: 100vh; color: #0f172a; }
        
        .dashboard { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 25px; }
        .card, .panel { background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(12px); border-radius: 24px; padding: 25px; box-shadow: 0 10px 30px rgba(0,0,0,0.06); }
        .card h3 { margin: 0; font-size: 15px; color: #0f766e; font-weight: 600; }
        .card p { margin: 10px 0 0; font-size: 32px; font-weight: 700; color: #0f766e; }
        
        .contenedor { display: grid; grid-template-columns: 42% 56%; gap: 2%; }
        .panel h2 { font-size: 20px; color: #1e293b; margin-top: 0; margin-bottom: 20px; border-bottom: 2px solid #ccfbf1; padding-bottom: 10px; }
        
        label { display: block; margin-top: 15px; font-weight: 600; font-size: 14px; color: #475569; }
        input, select { width: 100%; padding: 12px; margin-top: 8px; border: 1px solid #b7f3ec; border-radius: 12px; box-sizing: border-box; background: white; transition: 0.3s; }
        input:focus, select:focus { outline: none; border-color: #14b8a6; box-shadow: 0 0 0 4px rgba(20, 184, 166, 0.15); }
        
        button { padding: 12px 18px; border: none; border-radius: 12px; cursor: pointer; font-weight: 600; transition: 0.3s; }
        
        .btn-principal { width: 100%; margin-top: 25px; background: linear-gradient(135deg, #14b8a6, #2dd4bf); color: white; font-size: 16px; }
        .btn-principal:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(20, 184, 166, 0.3); }


        
        .btn-agregar { width: 100%; margin-top: 15px; background: #e6fffb; color: #0f766e; }
        .btn-agregar:hover { background: #d7f9f4; transform: translateY(-2px); }
        
        .btn-cantidad { background: #ccfbf1; color: #0f766e; width: 30px; height: 30px; padding: 0; display: inline-flex; justify-content: center; align-items: center; border-radius: 8px; font-size: 16px; }
        .btn-eliminar { background: #fee2e2; color: #dc2626; padding: 8px 12px; border-radius: 10px; }
        .btn-logout { background: #fee2e2; color: #dc2626; padding: 10px 20px; border-radius: 12px; font-weight: bold; }
        .btn-logout:hover { background: #fca5a5; transform: translateY(-2px); }

        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 14px 10px; border-bottom: 1px solid #f1f5f9; text-align: left; font-size: 14px; }
        th { background: #e6fffb; color: #0f766e; font-weight: 700; border-radius: 8px; }
        tbody tr:hover { background: #f2fffd; transition: 0.3s; }
        
        .totales { margin-top: 25px; padding: 20px; background: #f2fffd; border-radius: 16px; border: 1px solid #b7f3ec; }
        .totales p { margin: 0 0 5px; font-weight: 600; color: #0f766e; }
        .total-texto { font-size: 28px; font-weight: 700; color: #0f766e; margin-bottom: 15px; }
        
        .alert-success { background: #dcfce7; border: 1px solid #86efac; color: #166534; padding: 15px; border-radius: 12px; margin-bottom: 20px; border-left: 5px solid #22c55e; font-weight: 600; }
        .alert-error { background: #fee2e2; border: 1px solid #fca5a5; color: #991b1b; padding: 15px; border-radius: 12px; margin-bottom: 20px; border-left: 5px solid #ef4444; font-weight: 600; }
        .alert-warning { background: #f0fdfa; border: 1px solid #99f6e4; color: #115e59; padding: 18px; border-radius: 14px; margin-bottom: 20px; border-left: 5px solid #14b8a6; font-weight: 600; }
        
        @media (max-width: 950px) { .dashboard { grid-template-columns: 1fr; } .contenedor { grid-template-columns: 1fr; } body { padding: 15px; } }
    </style>
</head>
<body>

<x-header-navbar 
    title="Gestión de Caja" 
    :navLinks="[
        ['label' => 'Caja', 'url' => route('caja.productos.index')],
    ]"
    :estadoCaja="$cajaAbierta"
    :estadoCajaUrl="route('apertura-cierre-caja.index')"
/>

@if(session('success'))
    <div class="alert-success">{{ session('success') }}</div>
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
    <div class="card"><h3>Total ventas de hoy</h3><p>${{ number_format($totalVentasHoy, 0, ',', '.') }}</p></div>
    <div class="card"><h3>Total efectivo hoy</h3><p>${{ number_format($totalEfectivoHoy, 0, ',', '.') }}</p></div>
    <div class="card"><h3>Cantidad de ventas</h3><p>{{ $cantidadVentasHoy }}</p></div>
</div>

<div class="contenedor">
    <div class="panel">
        <h2>Nueva venta</h2>

        @if(!$cajaAbierta)
            <div class="alert-warning">
                <strong>La caja se encuentra cerrada.</strong>
                <p style="margin: 8px 0 0; font-weight: 500;">Para registrar ventas debes abrir una sesión de caja primero.</p>
                <a href="{{ route('apertura-cierre-caja.index') }}" class="btn-link-caja">Abrir caja</a>
            </div>
        @else
        <form method="POST" action="{{ route('caja.productos.store') }}" id="formVenta">
            @csrf

            <label for="cliente_id">Cliente existente</label>
            <div style="display: flex; gap: 10px; align-items: flex-start; margin-top: 8px;">
                <select name="cliente_id" id="cliente_id" style="margin-top: 0; flex: 1;">
                    <option value="">Seleccione un cliente</option>
                    @foreach($clientes as $cliente)
                        <option value="{{ $cliente->id }}">{{ $cliente->name }} - {{ $cliente->document }}</option>
                    @endforeach
                </select>
                <button type="button" class="btn-eliminar" onclick="eliminarCliente()" style="padding: 12px; border-radius: 12px; height: 46px;" title="Eliminar cliente seleccionado">🗑️</button>
            </div>

            <label for="nombre_cliente">O ingresar nuevo cliente</label>
            <input type="text" name="nombre_cliente" id="nombre_cliente" placeholder="Nombre del cliente">

            <label for="documento_cliente">Documento</label>
            <input type="text" name="documento_cliente" id="documento_cliente" placeholder="Documento del cliente">
            <label>Escanear código de barras</label>
            
            <input
            type="text"
            id="barcodeScanner"
            placeholder="Escanee aquí"
            autocomplete="off">
            <label for="productoSelect">Producto</label>
            <select id="productoSelect" onchange="mostrarPreviewProducto()">
                <option value="">Seleccione un producto</option>
                @foreach($productos as $producto)
                    <option value="{{ $producto->id }}"
                            data-nombre="{{ $producto->name }}"
                            data-precio="{{ $producto->price }}"
                            data-stock="{{ $producto->stock }}"
                            data-foto="{{ $producto->photo_url }}">
                        {{ $producto->name }} - ${{ number_format($producto->price, 0, ',', '.') }} | Stock: {{ $producto->stock }}
                    </option>
                @endforeach
            </select>

            {{-- Vista previa del producto seleccionado (con su foto) --}}
            <div id="previewProducto" style="display:none; align-items:center; gap:12px; margin:10px 0; padding:10px; background:#f0fdfa; border:1px solid #ccfbf1; border-radius:12px;">
                <img id="previewProductoImg" src="#" alt="Producto"
                     style="width:60px; height:60px; object-fit:cover; border-radius:10px; border:1px solid #ddd;">
                <div>
                    <div id="previewProductoNombre" style="font-weight:700; color:#0f172a;"></div>
                    <div id="previewProductoPrecio" style="color:#0f766e; font-weight:600;"></div>
                </div>
            </div>

            <button type="button" class="btn-agregar" onclick="agregarProducto()">Agregar producto</button>

            <table id="tablaProductos">
                <thead>
                    <tr><th>Foto</th><th>Producto</th><th>Precio</th><th>Cantidad</th><th>Subtotal</th><th></th></tr>
                </thead>
                <tbody></tbody>
            </table>

            <label for="metodo_pago">Método de pago</label>
            <select name="metodo_pago" id="metodo_pago" required>
                <option value="efectivo">Efectivo</option>
                <option value="transferencia">Transferencia</option>
                <option value="tarjeta">Tarjeta</option>
                <option value="nequi">Nequi</option>
                <option value="daviplata">Daviplata</option>
            </select>

            <label for="dinero_recibido">Dinero recibido</label>
            <input type="number" step="0.01" name="dinero_recibido" id="dinero_recibido">

            <div class="totales">
                <p>Total venta:</p>
                <div class="total-texto">$<span id="totalTexto">0</span></div>
                <p>Cambio:</p>
                <div class="total-texto">$<span id="cambioTexto">0</span></div>
            </div>

            <button type="submit" class="btn-principal">Guardar venta</button>
        </form>
        @endif
    </div>

    <div class="panel">
        <h2>Ventas de hoy</h2>
        <table>
            <thead>
                <tr>
                    <th>Número</th>
                    <th>Cliente</th>
                    <th>Método</th>
                    <th>Total</th>
                    <th>Cambio</th>
                    <th>Factura</th>
                </tr>
            </thead>

            <tbody>
                @forelse($ventasHoy as $venta)
                    <tr>
                        <td><strong>{{ $venta->numero_venta }}</strong></td>
                        <td>{{ $venta->cliente?->name ?? 'Sin cliente' }}</td>
                        <td>{{ ucfirst($venta->metodo_pago) }}</td>
                        <td style="color:#0f766e; font-weight:bold;">${{ number_format($venta->total, 0, ',', '.') }}</td>
                        <td>${{ number_format($venta->cambio, 0, ',', '.') }}</td>

                        <td>
@if($venta->factura)
    <span style="color: #15803d; font-weight: bold;">
        Facturada
    </span>
    <br>
    <small>{{ $venta->factura->numero_factura }}</small>
    <br>

    <a
        href="{{ route('facturas.imprimir', $venta->factura->id) }}"
        target="_blank"
        style="display:inline-block; background:#2563eb; color:white; padding:8px 12px; border-radius:8px; text-decoration:none; margin-top:6px;"
    >
        Imprimir factura
    </a>
@elseif($venta->estado === 'pagada')
    <form
        method="POST"
        action="{{ route('facturas.generar', $venta->id) }}"
        onsubmit="return confirm('¿Deseas generar la factura de esta venta?')"
    >
        @csrf

        <button
            type="submit"
            style="background: #16a34a; color: white; padding: 8px 12px; border-radius: 8px;"
        >
            Generar factura
        </button>
    </form>
@else
    <span style="color: #b91c1c; font-weight: bold;">
        Venta anulada
    </span>
@endif


                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; color: #94a3b8; padding: 20px;">No hay ventas registradas hoy.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
    let productosVenta = [];
    const productoSelect = document.getElementById('productoSelect');
    const tablaProductosBody = document.querySelector('#tablaProductos tbody');
    const totalTexto = document.getElementById('totalTexto');
    const cambioTexto = document.getElementById('cambioTexto');
    const dineroRecibido = document.getElementById('dinero_recibido');
    const metodoPago = document.getElementById('metodo_pago');

    function eliminarCliente() {
        const select = document.getElementById('cliente_id');
        const id = select.value;
        if (!id) {
            alert('Seleccione un cliente de la lista para eliminar.');
            return;
        }
        if (confirm('¿Seguro que desea eliminar a este cliente?')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/caja-clientes/' + id;
            
            const csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = '{{ csrf_token() }}';
            form.appendChild(csrf);
            
            const method = document.createElement('input');
            method.type = 'hidden';
            method.name = '_method';
            method.value = 'DELETE';
            form.appendChild(method);
            
            document.body.appendChild(form);
            form.submit();
        }
    }

    function mostrarPreviewProducto() {
        const option = productoSelect.options[productoSelect.selectedIndex];
        const preview = document.getElementById('previewProducto');

        if (!option.value) {
            preview.style.display = 'none';
            return;
        }

        document.getElementById('previewProductoImg').src = option.dataset.foto;
        document.getElementById('previewProductoNombre').textContent = option.dataset.nombre;
        document.getElementById('previewProductoPrecio').textContent =
            '$' + formatoMoneda(parseFloat(option.dataset.precio)) + ' | Stock: ' + option.dataset.stock;
        preview.style.display = 'flex';
    }

    function agregarProducto() {
        const option = productoSelect.options[productoSelect.selectedIndex];
        if (!option.value) { alert('Seleccione un producto.'); return; }

        const productoId = option.value;
        const existente = productosVenta.find(item => item.product_id === productoId);

        if (existente) {
            if (existente.cantidad < existente.stock) existente.cantidad++;
            else alert('No hay más stock disponible para este producto.');
        } else {
            productosVenta.push({
                product_id: productoId,
                nombre: option.dataset.nombre,
                precio: parseFloat(option.dataset.precio),
                stock: parseInt(option.dataset.stock),
                foto: option.dataset.foto,
                cantidad: 1
            });
        }
        renderizarTabla();
    }

    function aumentarCantidad(productoId) {
        const item = productosVenta.find(item => item.product_id === productoId);
        if (item.cantidad < item.stock) item.cantidad++;
        else alert('No hay más stock disponible.');
        renderizarTabla();
    }

    function disminuirCantidad(productoId) {
        const item = productosVenta.find(item => item.product_id === productoId);
        if (item.cantidad > 1) item.cantidad--;
        renderizarTabla();
    }

    function eliminarProducto(productoId) {
        productosVenta = productosVenta.filter(item => item.product_id !== productoId);
        renderizarTabla();
    }

    function renderizarTabla() {
        tablaProductosBody.innerHTML = '';
        productosVenta.forEach((item, index) => {
            const subtotal = item.precio * item.cantidad;
            const row = document.createElement('tr');
            row.innerHTML = `
                <td><img src="${item.foto}" alt="${item.nombre}" style="width:45px; height:45px; object-fit:cover; border-radius:8px; border:1px solid #ddd;"></td>
                <td><strong>${item.nombre}</strong><input type="hidden" name="productos[${index}][product_id]" value="${item.product_id}"></td>
                <td>$${formatoMoneda(item.precio)}</td>
                <td>
                    <button type="button" class="btn-cantidad" onclick="disminuirCantidad('${item.product_id}')">-</button>
                    <span style="margin: 0 8px; font-weight: bold;">${item.cantidad}</span>
                    <button type="button" class="btn-cantidad" onclick="aumentarCantidad('${item.product_id}')">+</button>
                    <input type="hidden" name="productos[${index}][cantidad]" value="${item.cantidad}">
                </td>
                <td style="color:#0f766e; font-weight:bold;">$${formatoMoneda(subtotal)}</td>
                <td><button type="button" class="btn-eliminar" onclick="eliminarProducto('${item.product_id}')">X</button></td>
            `;
            tablaProductosBody.appendChild(row);
        });
        calcularTotal();
    }

    function calcularTotal() {
        let total = productosVenta.reduce((sum, item) => sum + (item.precio * item.cantidad), 0);
        totalTexto.textContent = formatoMoneda(total);
        calcularCambio(total);
    }

    function calcularCambio(total = null) {
        if (total === null) total = productosVenta.reduce((sum, item) => sum + (item.precio * item.cantidad), 0);
        const recibido = parseFloat(dineroRecibido.value) || 0;
        let cambio = metodoPago.value === 'efectivo' ? recibido - total : 0;
        if (cambio < 0) cambio = 0;
        cambioTexto.textContent = formatoMoneda(cambio);
    }

    function formatoMoneda(valor) {
        return new Intl.NumberFormat('es-CO').format(valor);
    }

    dineroRecibido.addEventListener('input', () => calcularCambio());
    metodoPago.addEventListener('change', () => calcularCambio());
    const scanner = document.getElementById("barcodeScanner");

scanner.addEventListener("keypress",function(e){

    if(e.key!="Enter") return;

    e.preventDefault();

    buscarCodigo(scanner.value);

});
    const scanner = document.getElementById("barcodeScanner");

scanner.addEventListener("keypress", function(e) {

    if (e.key != "Enter") return;

    e.preventDefault();

    buscarCodigo(scanner.value);

});

function buscarCodigo(codigo) {

    fetch("/buscar-barcode/" + codigo)

    .then(res => res.json())

    .then(producto => {

        if (producto == null) {

            alert("Producto no encontrado");

            scanner.value = "";

            scanner.focus();

            return;
        }

        let existente = productosVenta.find(p => p.product_id == producto.id);

        if (existente) {

            if (existente.cantidad < producto.stock) {
                existente.cantidad++;
            }

        } else {

            productosVenta.push({

                product_id: producto.id,
                nombre: producto.name,
                precio: parseFloat(producto.price),
                stock: parseInt(producto.stock),
                cantidad: 1

            });

        }

        renderizarTabla();

        scanner.value = "";

        scanner.focus();

    });

}
    document.getElementById('formVenta').addEventListener('submit', function(e) {
        if (productosVenta.length === 0) {
            e.preventDefault();
            alert('Debe agregar al menos un producto.');
        }
    });
</script>

</body>
</html>

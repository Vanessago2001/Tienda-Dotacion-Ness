{{-- Botón "Menú" que despliega todas las opciones al pasar el cursor --}}
<div class="menu-hover">
    <button type="button" class="menu-btn">☰ Menú ▾</button>
    <div class="menu-panel">
        <a href="{{ route('dashboard') }}" class="menu-item">🏠 Dashboard</a>
        <a href="{{ route('products.index') }}" class="menu-item">📦 Productos</a>
        @can('gestionar-inventario')
            <a href="{{ route('inventario.index') }}" class="menu-item">📊 Inventario</a>
        @endcan
        <a href="{{ route('caja.productos.index') }}" class="menu-item">🛒 Caja</a>
        <a href="{{ route('apertura-cierre-caja.index') }}" class="menu-item">🔓 Apertura/Cierre</a>
        @can('abrir-cerrar-caja')
            <a href="{{ route('reportes.caja-dia') }}" class="menu-item">🧾 Caja del día</a>
        @endcan
        @can('ver-historial')
            <a href="{{ route('historial-ventas.index') }}" class="menu-item">📜 Historial</a>
        @endcan
        @can('gestionar-facturas')
            <a href="{{ route('facturas.index') }}" class="menu-item">📄 Facturas</a>
            <a href="{{ route('movimientos-caja.index') }}" class="menu-item">💰 Movimientos</a>
        @endcan
        @can('ver-reportes')
            <a href="{{ route('reportes.ventas') }}" class="menu-item">📈 Informe Ventas</a>
        @endcan
        @can('ver-auditoria')
            <a href="{{ route('auditoria.index') }}" class="menu-item">🕵️ Auditoría</a>
        @endcan
        @can('gestionar-empresa')
            <a href="{{ route('companies.index') }}" class="menu-item">🏢 Empresa</a>
        @endcan
        @can('gestionar-usuarios')
            <a href="{{ route('usuarios.index') }}" class="menu-item">👥 Usuarios</a>
            <a href="{{ route('roles.index') }}" class="menu-item">🏷️ Roles</a>
        @endcan
    </div>
</div>

@once
<style>
    .menu-hover { position: relative; display: inline-block; }
    .menu-btn {
        background: linear-gradient(135deg,#14b8a6,#2dd4bf); color:#fff; border:none;
        padding:12px 22px; border-radius:14px; font-weight:700; font-size:15px; cursor:pointer;
        box-shadow:0 6px 16px rgba(20,184,166,.28); transition:.3s; font-family:'Segoe UI',Arial,sans-serif;
    }
    .menu-btn:hover { transform:translateY(-2px); }
    .menu-panel {
        position:absolute; top:100%; left:0; margin-top:12px; z-index:9999; display:none;
        background: rgba(255,255,255,0.98); backdrop-filter: blur(12px);
        border:1px solid #ccfbf1; border-radius:22px; box-shadow:0 20px 40px rgba(0,0,0,.18);
        padding:16px; grid-template-columns: repeat(3, minmax(150px,1fr)); gap:10px;
    }
    .menu-hover::after { content:''; position:absolute; top:100%; left:0; height:16px; width:100%; }
    .menu-hover:hover .menu-panel { display:grid; }
    .menu-item {
        text-decoration:none; padding:11px 14px; border-radius:12px;
        background:#e6fbf8; color:#0d3f3c; font-weight:600; font-size:14px;
        transition:.2s; display:flex; align-items:center; gap:8px; white-space:nowrap;
    }
    .menu-item:hover { background:#ccfbf1; transform:translateY(-2px); color:#0f766e; }
    @media (max-width: 768px) {
        .menu-panel { grid-template-columns: repeat(2, 1fr); min-width: 240px; }
    }
</style>
@endonce

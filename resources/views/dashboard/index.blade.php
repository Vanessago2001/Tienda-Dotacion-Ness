<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ __('Dashboard General') }} - <span class="text-teal-600">{{ ucfirst(Auth::user()->role) }}</span>
        </h2>
    </x-slot>

    <div class="py-12 min-h-screen" style="background: linear-gradient(135deg, #0d3f3c 0%, #1c7a74 45%, #40E0D0 100%);">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <!-- Navbar -->
            <div style="padding: 0 24px;">
                <x-header-navbar 
                    title="Dashboard Principal" 
                    :navLinks="[
                        ['label' => 'Dashboard', 'url' => route('dashboard')],
                    ]"
                    primaryAction="Ir a Caja POS"
                    :primaryActionUrl="route('caja.productos.index')"
                />
            </div>

            <!-- Menú desplegable con todas las opciones (se abre al pasar el cursor) -->
            <style>
                .menu-hover { position: relative; display: inline-block; }
                .menu-btn {
                    background: linear-gradient(135deg,#14b8a6,#2dd4bf); color:#fff; border:none;
                    padding:14px 26px; border-radius:16px; font-weight:700; font-size:16px; cursor:pointer;
                    box-shadow:0 8px 20px rgba(20,184,166,.30); transition:.3s;
                }
                .menu-btn:hover { transform:translateY(-2px); }
                .menu-panel {
                    position:absolute; top:100%; left:0; margin-top:12px; z-index:50; display:none;
                    background: rgba(255,255,255,0.97); backdrop-filter: blur(12px);
                    border:1px solid #ccfbf1; border-radius:24px; box-shadow:0 20px 40px rgba(0,0,0,.18);
                    padding:18px;
                    grid-template-columns: repeat(3, minmax(150px,1fr)); gap:10px;
                }
                /* puente invisible para que el panel no se cierre al bajar el cursor */
                .menu-hover::after { content:''; position:absolute; top:100%; left:0; height:16px; width:100%; }
                .menu-hover:hover .menu-panel { display:grid; }
                .menu-item {
                    text-decoration:none; padding:12px 16px; border-radius:14px;
                    background:#e6fbf8; color:#0d3f3c; font-weight:600; font-size:14px;
                    transition:.2s; display:flex; align-items:center; gap:8px; white-space:nowrap;
                }
                .menu-item:hover { background:#ccfbf1; transform:translateY(-2px); color:#0f766e; }
                @media (max-width: 768px) {
                    .menu-panel { grid-template-columns: repeat(2, 1fr); min-width: 260px; }
                }
            </style>

            <div style="padding: 0 24px;">
                <div class="menu-hover">
                    <button type="button" class="menu-btn">☰ Todas las opciones ▾</button>
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
            </div>

            <!-- Tarjeta de Bienvenida -->
            <div class="overflow-hidden shadow-sm sm:rounded-2xl border border-slate-200" style="background: rgba(255,255,255,0.97);">
                <div class="p-6 text-[#0d3f3c] flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div>
                        <h3 class="text-2xl font-bold text-[#0d3f3c]">¡Hola, {{ Auth::user()->name }}!</h3>
                        <p class="text-[#0f766e] text-sm mt-1">Has iniciado sesión en el sistema. Aquí tienes un resumen claro de los módulos y el estado general de tu operación.</p>
                    </div>
                    <div class="rounded-2xl bg-[#e6fbf8] px-4 py-3 text-sm text-[#0f766e] font-semibold border border-[#baf2eb]">
                        Panel principal • Gestión de caja y ventas
                    </div>
                </div>
            </div>

            @if(Auth::user()->role === 'cajero')
            <!-- ========================= PANEL CAJERO ========================= -->
            <style>
                body{
                    background: linear-gradient(135deg,#f8fafc,#f3e8ff,#eef2ff);
                }
                .dashboard-card{
                    background: rgba(255,255,255,.90);
                    backdrop-filter: blur(12px);
                    border:1px solid rgba(255,255,255,.5);
                    border-radius:24px;
                    box-shadow:0 10px 30px rgba(0,0,0,.06);
                    transition:.3s;
                }
                .dashboard-card:hover{
                    box-shadow:0 20px 40px rgba(0,0,0,.15);
                    transform:translateY(-5px);
                }
                .cajero-cards {
                    display: grid;
                    grid-template-columns: repeat(4, 1fr);
                    gap: 24px;
                }
                @media (max-width: 768px) {
                    .cajero-cards {
                        grid-template-columns: 1fr;
                    }
                }
            </style>
            
            <div class="mb-8">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold tracking-wider uppercase text-[#0d3f3c]" style="letter-spacing: 2px;">Panel cajero</h2>
                    <span class="rounded-full bg-[#e6fbf8] px-3 py-1 text-sm font-semibold text-[#0f766e] border border-[#baf2eb]">Accesos rápidos</span>
                </div>
                <div class="cajero-cards">
                    <!-- Tarjeta Productos -->
                    <a href="{{ route('products.index') }}" class="dashboard-card flex flex-col justify-center items-center py-12 px-6" style="text-decoration: none; border-bottom: 4px solid #14b8a6;">
                        <span class="text-5xl mb-4">📦</span>
                        <h3 class="text-lg font-bold text-[#0d3f3c] uppercase tracking-widest mt-2">Productos</h3>
                    </a>
                    
                    <!-- Tarjeta Facturas -->
                    <a href="{{ route('facturas.index') }}" class="dashboard-card flex flex-col justify-center items-center py-12 px-6" style="text-decoration: none; border-bottom: 4px solid #0f766e;">
                        <span class="text-5xl mb-4">📄</span>
                        <h3 class="text-lg font-bold text-[#0d3f3c] uppercase tracking-widest mt-2">Facturas</h3>
                    </a>

                    <!-- Tarjeta Caja POS -->
                    <a href="{{ route('caja.productos.index') }}" class="dashboard-card flex flex-col justify-center items-center py-12 px-6" style="text-decoration: none; border-bottom: 4px solid #2dd4bf;">
                        <span class="text-5xl mb-4">🛒</span>
                        <h3 class="text-lg font-bold text-[#0d3f3c] uppercase tracking-widest mt-2">Caja POS</h3>
                    </a>

                    <!-- Tarjeta Movimientos de Caja -->
                    <a href="{{ route('movimientos-caja.index') }}" class="dashboard-card flex flex-col justify-center items-center py-12 px-6" style="text-decoration: none; border-bottom: 4px solid #f59e0b;">
                        <span class="text-5xl mb-4">💰</span>
                        <h3 class="text-lg font-bold text-[#0d3f3c] uppercase tracking-widest mt-2 text-center">Movimientos de Caja</h3>
                    </a>
                </div>
            </div>
            <!-- ========================= FIN PANEL CAJERO ========================= -->
            @else
            
            <!-- ========================= LO NUEVO (GUÍA + GRÁFICAS) ========================= -->
            <style>
                body{
                    background: linear-gradient(135deg,#f8fafc,#f3e8ff,#eef2ff);
                }

                .dashboard-card{
                    background: rgba(255,255,255,.90);
                    backdrop-filter: blur(12px);
                    border:1px solid rgba(255,255,255,.5);
                    border-radius:24px;
                    box-shadow:0 10px 30px rgba(0,0,0,.06);
                }

                .dashboard-card:hover{
                    box-shadow:0 20px 40px rgba(0,0,0,.10);
                    transition:.3s;
                }

                .metric-card{
                    background:rgba(255,255,255,.95);
                    border:1px solid #baf2eb;
                    border-radius:20px;
                    padding:24px;
                    box-shadow:0 8px 20px rgba(0,0,0,.05);
                    transition:.3s;
                }

                .metric-card:hover{
                    transform:translateY(-5px);
                }

                .table-modern thead{
                    background:#e6fbf8;
                }

                .table-modern tbody tr:hover{
                    background:#f2fffd;
                }

                .table-modern td, .table-modern th{
                    color:#0d3f3c;
                }

                .btn-module{
                    display:block;
                    width:100%;
                    text-align:center;
                    padding:12px;
                    border-radius:12px;
                    background:linear-gradient(135deg,#2ec4b6,#40E0D0);
                    color:white;
                    font-weight:600;
                    transition:.3s;
                }

                .btn-module:hover{
                    transform:translateY(-2px);
                }

                .cards{
                    display:grid;
                    grid-template-columns: repeat(4, 1fr);
                    gap:20px;
                }

                @media (max-width:1024px){
                    .cards{
                        grid-template-columns: repeat(2, 1fr);
                    }
                }

                @media (max-width:640px){
                    .cards{
                        grid-template-columns: 1fr;
                    }
                }
                </style>

            <div class="cards">
                <div class="metric-card border-l-4 border-teal-500">
                    <h3>Usuarios</h3>
                    <p>{{ $totalUsers }}</p>
                </div>
                <div class="metric-card border-l-4 border-emerald-500">
                    <h3>Productos</h3>
                    <p>{{ $totalProducts }}</p>
                </div>
                <div class="metric-card border-l-4 border-amber-500">
                    <h3>Facturas</h3>
                    <p>{{ $totalInvoices }}</p>
                </div>
                <div class="metric-card border-l-4 border-rose-500">
                    <h3>Clientes</h3>
                    <p>{{ $totalCustomers }}</p>
                </div>
            </div>

            <div class="chart-container">
                <h2 class="text-lg font-bold text-gray-800 mb-4">Estadísticas Generales</h2>
                <canvas id="dashboardChart" height="80"></canvas>
            </div>
            <!-- ======================= FIN LO NUEVO ======================= -->

            <!-- Módulo: Productos -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100">
                <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                    <h3 class="text-lg font-bold text-gray-800">Últimos Productos</h3>
                    <a href="{{ route('products.index') }}" class="text-sm font-semibold text-teal-600 hover:text-teal-800 transition">Ver todos &rarr;</a>
                </div>
                <div class="p-6 overflow-x-auto">
                    <table class="table-modern w-full text-sm text-left text-gray-600">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 rounded-tl-lg">ID</th>
                                <th scope="col" class="px-6 py-3">Nombre</th>
                                <th scope="col" class="px-6 py-3">Precio</th>
                                <th scope="col" class="px-6 py-3">Stock</th>
                                <th scope="col" class="px-6 py-3 rounded-tr-lg">Creado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($products as $product)
                            <tr class="bg-white border-b hover:bg-gray-50 transition">
                                <td class="px-6 py-4 font-medium text-gray-900">{{ $product->id }}</td>
                                <td class="px-6 py-4">{{ $product->name }}</td>
                                <td class="px-6 py-4">${{ number_format($product->price ?? 0, 2) }}</td>
                                <td class="px-6 py-4 font-bold text-teal-600">{{ $product->stock }}</td>
                                <td class="px-6 py-4">{{ $product->created_at->format('d M Y') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-400">No hay productos registrados.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Módulo: Facturas -->
            @can('gestionar-facturas')
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100">
                <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                    <h3 class="text-lg font-bold text-gray-800">Últimas Facturas</h3>
                    <a href="{{ route('facturas.index') }}" class="text-sm font-semibold text-teal-600 hover:text-teal-800 transition">Ver todas &rarr;</a>
                </div>
                <div class="p-6 overflow-x-auto">
                    <table class="table-modern w-full text-sm text-left text-gray-600">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 rounded-tl-lg">ID</th>
                                <th scope="col" class="px-6 py-3">Número</th>
                                <th scope="col" class="px-6 py-3">Total</th>
                                <th scope="col" class="px-6 py-3 rounded-tr-lg">Fecha</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($invoices as $invoice)
                            <tr class="bg-white border-b hover:bg-gray-50 transition">
                                <td class="px-6 py-4 font-medium text-gray-900">{{ $invoice->id }}</td>
                                <td class="px-6 py-4">{{ $invoice->numero_factura ?? 'N/A' }}</td>
                                <td class="px-6 py-4">${{ number_format($invoice->total ?? 0, 2) }}</td>
                                <td class="px-6 py-4">{{ $invoice->fecha_emision ? $invoice->fecha_emision->format('d M Y') : 'N/A' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-gray-400">No hay facturas registradas.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @endcan

            <!-- Módulo: Reportes -->
            @can('ver-reportes')
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100">
                <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                    <h3 class="text-lg font-bold text-gray-800">Últimos Reportes</h3>
                    <a href="{{ route('reports.index') }}" class="text-sm font-semibold text-teal-600 hover:text-teal-800 transition">Ver todos &rarr;</a>
                </div>
                <div class="p-6 overflow-x-auto">
                    <table class="table-modern w-full text-sm text-left text-gray-600">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 rounded-tl-lg">ID</th>
                                <th scope="col" class="px-6 py-3">Título</th>
                                <th scope="col" class="px-6 py-3">Tipo</th>
                                <th scope="col" class="px-6 py-3 rounded-tr-lg">Generado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($reports as $report)
                            <tr class="bg-white border-b hover:bg-gray-50 transition">
                                <td class="px-6 py-4 font-medium text-gray-900">{{ $report->id }}</td>
                                <td class="px-6 py-4">{{ $report->title ?? 'Sin Título' }}</td>
                                <td class="px-6 py-4">{{ $report->type ?? 'General' }}</td>
                                <td class="px-6 py-4">{{ $report->created_at->format('d M Y') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-gray-400">No hay reportes generados.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @endcan

            <!-- Módulo: Empresas -->
            @can('gestionar-empresa')
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100">
                <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                    <h3 class="text-lg font-bold text-gray-800">Empresas</h3>
                    <a href="{{ route('companies.index') }}" class="text-sm font-semibold text-teal-600 hover:text-teal-800 transition">Ver todas &rarr;</a>
                </div>
                <div class="p-6 overflow-x-auto">
                    <table class="table-modern w-full text-sm text-left text-gray-600">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 rounded-tl-lg">ID</th>
                                <th scope="col" class="px-6 py-3">Nombre</th>
                                <th scope="col" class="px-6 py-3">NIT</th>
                                <th scope="col" class="px-6 py-3 rounded-tr-lg">Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($companies as $company)
                            <tr class="bg-white border-b hover:bg-gray-50 transition">
                                <td class="px-6 py-4 font-medium text-gray-900">{{ $company->id }}</td>
                                <td class="px-6 py-4">{{ $company->name }}</td>
                                <td class="px-6 py-4">{{ $company->nit ?? 'N/A' }}</td>
                                <td class="px-6 py-4"><span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Activa</span></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-gray-400">No hay empresas registradas.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @endcan

            <!-- Otros módulos (Proveedores, Clientes, Categorías) -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Clientes -->
                @can('gestionar-facturas')
                <div class="bg-white shadow-sm sm:rounded-xl border border-gray-100 p-6 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-bold text-gray-800">Clientes</h3>
                            <span class="bg-[#e6fbf8] text-[#0d3f3c] text-xs font-medium px-2.5 py-0.5 rounded">Módulo</span>
                        </div>
                        <p class="text-sm text-gray-500">Gestión de la cartera de clientes, información de contacto y preferencias.</p>
                    </div>
                    <a href="{{ route('customers.index') }}" class="btn-module mt-4">
                        Gestionar Clientes
                    </a>
                </div>
                @endcan

                <!-- Proveedores -->
                @can('editar-inventario')
                <div class="bg-white shadow-sm sm:rounded-xl border border-gray-100 p-6 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-bold text-gray-800">Proveedores</h3>
                            <span class="bg-[#e6fbf8] text-[#0d3f3c] text-xs font-medium px-2.5 py-0.5 rounded">Módulo</span>
                        </div>
                        <p class="text-sm text-gray-500">Administración de proveedores, información de suministros y contratos.</p>
                    </div>
                    <a href="{{ route('suppliers.index') }}" class="btn-module mt-4">
                        Gestionar Proveedores
                    </a>
                </div>
                @endcan

                <!-- Categorías -->
                @can('editar-inventario')
                <div class="bg-white shadow-sm sm:rounded-xl border border-gray-100 p-6 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-bold text-gray-800">Categorías</h3>
                            <span class="bg-[#e6fbf8] text-[#0d3f3c] text-xs font-medium px-2.5 py-0.5 rounded">Módulo</span>
                        </div>
                        <p class="text-sm text-gray-500">Clasificación de productos, estructuración de inventario y etiquetas.</p>
                    </div>
                    <a href="{{ route('categories.index') }}" class="btn-module mt-4">
                        Gestionar Categorías
                    </a>
                </div>
                @endcan

                <!-- Movimientos de Caja -->
                @can('gestionar-empresa')
                <div class="bg-white shadow-sm sm:rounded-xl border border-gray-100 p-6 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-bold text-gray-800">Movimientos de Caja</h3>
                            <span class="bg-[#e6fbf8] text-[#0d3f3c] text-xs font-medium px-2.5 py-0.5 rounded">Módulo</span>
                        </div>
                        <p class="text-sm text-gray-500">Gestión de ingresos, egresos, gastos y control general de la caja.</p>
                    </div>
                    <a href="{{ route('movimientos-caja.index') }}" class="btn-module mt-4" style="background:linear-gradient(135deg,#f59e0b,#d97706);">
                        Gestionar Caja
                    </a>
                </div>
                @endcan
            </div>
            @endif
        </div>
    </div>

    @if(Auth::user()->role !== 'cajero')
    <!-- Script de Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('dashboardChart');
            if(ctx) {
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: ['Usuarios', 'Productos', 'Facturas', 'Clientes'],
                        datasets: [{
                            label: 'Cantidad de Registros',
                            data: [
                                {{ $totalUsers }}, 
                                {{ $totalProducts }}, 
                                {{ $totalInvoices }}, 
                                {{ $totalCustomers }}
                            ],
                            backgroundColor: [
                                'rgba(99, 102, 241, 0.8)', // teal-500
                                'rgba(16, 185, 129, 0.8)', // emerald-500
                                'rgba(245, 158, 11, 0.8)',  // amber-500
                                'rgba(244, 63, 94, 0.8)'    // rose-500
                            ],
                            borderWidth: 0,
                            borderRadius: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            }
                        }
                    }
                });
            }
        });
    </script>
    @endif
</x-app-layout>

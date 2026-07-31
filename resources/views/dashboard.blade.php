<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ __('Dashboard General') }} - <span class="text-teal-600">{{ ucfirst(Auth::user()->role) }}</span>
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <!-- Tarjeta de Bienvenida -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100">
                <div class="p-6 text-gray-900 flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-bold text-gray-800">¡Hola, {{ Auth::user()->name }}!</h3>
                        <p class="text-gray-500 text-sm mt-1">Has iniciado sesión en el sistema. A continuación puedes ver el resumen de los módulos a los que tienes acceso.</p>
                    </div>
                    <div class="hidden sm:block">
                        <svg class="w-12 h-12 text-teal-100" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zm0 16a3 3 0 01-3-3h6a3 3 0 01-3 3z"></path></svg>
                    </div>
                </div>
            </div>

            <!-- Módulo: Productos -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100">
                <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                    <h3 class="text-lg font-bold text-gray-800">Últimos Productos</h3>
                    <a href="{{ route('products.index') }}" class="text-sm font-semibold text-teal-600 hover:text-teal-800 transition">Ver todos &rarr;</a>
                </div>
                <div class="p-6 overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 rounded-tl-lg">ID</th>
                                <th scope="col" class="px-6 py-3">Nombre</th>
                                <th scope="col" class="px-6 py-3">Precio</th>
                                <th scope="col" class="px-6 py-3 rounded-tr-lg">Creado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($products as $product)
                            <tr class="bg-white border-b hover:bg-gray-50 transition">
                                <td class="px-6 py-4 font-medium text-gray-900">{{ $product->id }}</td>
                                <td class="px-6 py-4">{{ $product->name }}</td>
                                <td class="px-6 py-4">${{ number_format($product->price ?? 0, 2) }}</td>
                                <td class="px-6 py-4">{{ $product->created_at->format('d M Y') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-gray-400">No hay productos registrados.</td>
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
                    <table class="w-full text-sm text-left text-gray-500">
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
                    <table class="w-full text-sm text-left text-gray-500">
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
                    <table class="w-full text-sm text-left text-gray-500">
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

            <!-- Otros módulos (Proveedores, Clientes, Categorías) pueden seguir el mismo patrón -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Clientes -->
                @can('gestionar-facturas') <!-- Asumiendo que contadores/vendedores/admin ven clientes -->
                <div class="bg-white shadow-sm sm:rounded-xl border border-gray-100 p-6 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-bold text-gray-800">Clientes</h3>
                            <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">Módulo</span>
                        </div>
                        <p class="text-sm text-gray-500">Gestión de la cartera de clientes, información de contacto y preferencias.</p>
                    </div>
                    <a href="{{ route('customers.index') }}" class="mt-4 block w-full text-center px-4 py-2 bg-gray-50 hover:bg-gray-100 text-sm font-medium text-gray-700 rounded-lg transition border border-gray-200">
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
                    <a href="{{ route('suppliers.index') }}" class="mt-4 block w-full text-center px-4 py-2 bg-gray-50 hover:bg-gray-100 text-sm font-medium text-gray-700 rounded-lg transition border border-gray-200">
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
                            <span class="bg-orange-100 text-orange-800 text-xs font-medium px-2.5 py-0.5 rounded">Módulo</span>
                        </div>
                        <p class="text-sm text-gray-500">Clasificación de productos, estructuración de inventario y etiquetas.</p>
                    </div>
                    <a href="{{ route('categories.index') }}" class="mt-4 block w-full text-center px-4 py-2 bg-gray-50 hover:bg-gray-100 text-sm font-medium text-gray-700 rounded-lg transition border border-gray-200">
                        Gestionar Categorías
                    </a>
                </div>
                @endcan
            </div>

        </div>
    </div>
</x-app-layout>

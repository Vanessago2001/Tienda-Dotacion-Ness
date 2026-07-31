<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Catálogo — Dotación</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">

    {{-- Barra superior --}}
    <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 py-4 flex items-center justify-between">
            <h1 class="text-xl font-bold text-gray-800">Catálogo de Dotación</h1>

            @auth
                <a href="{{ route('dashboard') }}"
                   class="px-4 py-2 bg-indigo-600 text-white rounded-md text-sm">
                    Ir al panel
                </a>
            @else
                <a href="{{ route('login') }}"
                   class="px-4 py-2 bg-indigo-600 text-white rounded-md text-sm">
                    Iniciar sesión
                </a>
            @endauth
        </div>
    </header>

    {{-- Grilla de productos --}}
    <main class="max-w-7xl mx-auto px-4 py-8">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse ($products as $product)
                <div class="bg-white rounded-lg shadow overflow-hidden flex flex-col">
                    <img src="{{ $product->photo_url }}"
                         alt="{{ $product->name }}"
                         class="w-full h-48 object-cover bg-gray-200"
                         onerror="this.src='https://via.placeholder.com/300x200?text=Sin+imagen'">

                    <div class="p-4 flex flex-col flex-1">
                        <h2 class="font-semibold text-gray-800">{{ $product->name }}</h2>
                        <p class="text-sm text-gray-500">{{ $product->category }}</p>
                        <p class="mt-2 text-lg font-bold text-indigo-600">
                            ${{ number_format($product->price, 0) }}
                        </p>
                    </div>
                </div>
            @empty
                <p class="col-span-full text-center text-gray-500">
                    Todavía no hay productos cargados.
                </p>
            @endforelse
        </div>
    </main>

</body>
</html>


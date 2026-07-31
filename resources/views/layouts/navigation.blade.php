<nav x-data="{ open: false }" style="position:relative; z-index:3000;" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">
    <!-- Primary Navigation Menu -->
    <div class="bg-white/95 backdrop-blur rounded-3xl shadow-lg px-6">
        <div class="flex justify-between items-center min-h-16 py-3 gap-4 flex-wrap">
            <div class="flex items-center gap-4 flex-wrap">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo />
                    </a>
                </div>

                <!-- Menú (todas las opciones en un botón) -->
                <div class="hidden sm:flex items-center">
                    <x-menu-boton />
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-teal-700 bg-white hover:text-teal-800 hover:bg-teal-50 focus:outline-none transition ease-in-out duration-150">
                            @if(Auth::user()->photo)
                                <img src="{{ asset('storage/' . Auth::user()->photo) }}" width="32" height="32" style="border-radius: 50%; object-fit: cover; margin-right: 8px;">
                            @endif
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-teal-600 hover:text-teal-700 hover:bg-teal-50 focus:outline-none focus:bg-teal-50 focus:text-teal-700 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden mt-3 bg-white/95 backdrop-blur rounded-3xl shadow-lg overflow-hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('products.index')" :active="request()->routeIs('products.*')">
                {{ __('Productos') }}
            </x-responsive-nav-link>
            @can('gestionar-inventario')
                <x-responsive-nav-link :href="route('inventario.index')" :active="request()->routeIs('inventario.*')">
                    {{ __('Inventario') }}
                </x-responsive-nav-link>
            @endcan
            <x-responsive-nav-link :href="route('caja.productos.index')" :active="request()->routeIs('caja.productos.*')">
                {{ __('Caja') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('apertura-cierre-caja.index')" :active="request()->routeIs('apertura-cierre-caja.*')">
                {{ __('Apertura/Cierre') }}
            </x-responsive-nav-link>
            @can('abrir-cerrar-caja')
                <x-responsive-nav-link :href="route('reportes.caja-dia')" :active="request()->routeIs('reportes.caja-dia')">
                    {{ __('Caja del día') }}
                </x-responsive-nav-link>
            @endcan
            @can('ver-historial')
                <x-responsive-nav-link :href="route('historial-ventas.index')" :active="request()->routeIs('historial-ventas.*')">
                    {{ __('Historial') }}
                </x-responsive-nav-link>
            @endcan
            @can('gestionar-facturas')
                <x-responsive-nav-link :href="route('facturas.index')" :active="request()->routeIs('facturas.*')">
                    {{ __('Facturas') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('movimientos-caja.index')" :active="request()->routeIs('movimientos-caja.*')">
                    {{ __('Movimientos') }}
                </x-responsive-nav-link>
            @endcan
            @can('ver-reportes')
                <x-responsive-nav-link :href="route('reportes.ventas')" :active="request()->routeIs('reportes.ventas')">
                    {{ __('Informe Ventas') }}
                </x-responsive-nav-link>
            @endcan
            @can('ver-auditoria')
                <x-responsive-nav-link :href="route('auditoria.index')" :active="request()->routeIs('auditoria.*')">
                    {{ __('Auditoría') }}
                </x-responsive-nav-link>
            @endcan
            @can('gestionar-empresa')
                <x-responsive-nav-link :href="route('companies.index')" :active="request()->routeIs('companies.*')">
                    {{ __('Empresa') }}
                </x-responsive-nav-link>
            @endcan
            @can('gestionar-usuarios')
                <x-responsive-nav-link :href="route('usuarios.index')" :active="request()->routeIs('usuarios.*')">
                    {{ __('Usuarios') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('roles.index')" :active="request()->routeIs('roles.*')">
                    {{ __('Roles') }}
                </x-responsive-nav-link>
            @endcan
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>

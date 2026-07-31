<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

public function boot(): void
{
    // El administrador siempre tiene todos los permisos (super-usuario)
    Gate::before(function ($user, string $ability) {
        return $user->isAdmin() ? true : null;
    });

    // A partir de aquí los permisos son POR USUARIO: el admin los asigna
    // a cada persona (incluidos los cajeros) desde /usuarios.
    Gate::define('gestionar-empresa',    fn ($user) => $user->hasPermission('gestionar-empresa'));
    Gate::define('gestionar-usuarios',   fn ($user) => $user->hasPermission('gestionar-usuarios'));
    Gate::define('gestionar-facturas',   fn ($user) => $user->hasPermission('gestionar-facturas'));
    Gate::define('anular-facturas',      fn ($user) => $user->hasPermission('anular-facturas'));
    Gate::define('editar-inventario',    fn ($user) => $user->hasPermission('editar-inventario'));
    Gate::define('gestionar-inventario', fn ($user) => $user->hasPermission('gestionar-inventario'));
    Gate::define('ver-auditoria',        fn ($user) => $user->hasPermission('ver-auditoria'));
    Gate::define('gestionar-movimientos',fn ($user) => $user->hasPermission('gestionar-movimientos'));
    Gate::define('abrir-cerrar-caja',    fn ($user) => $user->hasPermission('abrir-cerrar-caja'));
    Gate::define('ver-historial',        fn ($user) => $user->hasPermission('ver-historial'));
    Gate::define('ver-reportes',         fn ($user) => $user->hasPermission('ver-reportes'));
    }
}

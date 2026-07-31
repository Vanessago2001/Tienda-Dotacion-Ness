<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Catálogo de permisos del sistema
        $catalogo = [
            ['slug' => 'gestionar-empresa',     'name' => 'Gestionar empresa',       'description' => 'Editar los datos y la configuración de la empresa.'],
            ['slug' => 'gestionar-usuarios',    'name' => 'Gestionar usuarios',      'description' => 'Ver usuarios y asignar sus permisos.'],
            ['slug' => 'editar-inventario',     'name' => 'Editar inventario',       'description' => 'Crear, editar y eliminar productos.'],
            ['slug' => 'gestionar-inventario',  'name' => 'Movimientos de inventario', 'description' => 'Registrar entradas, salidas y ajustes de stock.'],
            ['slug' => 'ver-auditoria',         'name' => 'Ver auditoría',           'description' => 'Consultar el registro de acciones del sistema.'],
            ['slug' => 'gestionar-facturas',    'name' => 'Gestionar facturas',      'description' => 'Ver y generar facturas desde la caja.'],
            ['slug' => 'anular-facturas',       'name' => 'Anular facturas',         'description' => 'Anular facturas y generar la nota crédito (reintegra stock).'],
            ['slug' => 'gestionar-movimientos', 'name' => 'Movimientos de caja',     'description' => 'Registrar entradas y salidas (gastos) de la caja.'],
            ['slug' => 'abrir-cerrar-caja',     'name' => 'Abrir y cerrar caja',     'description' => 'Realizar la apertura y el cierre de la caja.'],
            ['slug' => 'ver-historial',         'name' => 'Ver historial de ventas', 'description' => 'Consultar el historial completo de ventas.'],
            ['slug' => 'ver-reportes',          'name' => 'Ver reportes',            'description' => 'Consultar los reportes del sistema.'],
        ];

        foreach ($catalogo as $permiso) {
            Permission::updateOrCreate(['slug' => $permiso['slug']], $permiso);
        }

        // 2. Permisos por defecto según el rol previo
        //    (para conservar el comportamiento actual del sistema).
        //    El admin NO se lista: siempre tiene todo vía Gate::before.
        $porDefecto = [
            'vendedor'  => ['editar-inventario', 'gestionar-inventario', 'gestionar-facturas', 'gestionar-movimientos', 'abrir-cerrar-caja', 'ver-historial'],
            'contador'  => ['gestionar-facturas', 'gestionar-movimientos', 'ver-reportes', 'ver-historial'],
            'cajero'    => ['gestionar-facturas', 'abrir-cerrar-caja', 'ver-historial'],
            'visitante' => [],
        ];

        foreach ($porDefecto as $rol => $slugs) {
            $ids = Permission::whereIn('slug', $slugs)->pluck('id');

            User::where('role', $rol)->get()->each(function ($user) use ($ids) {
                $user->permissions()->syncWithoutDetaching($ids);
            });
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Roles base del sistema (no se pueden eliminar).
        $roles = [
            ['slug' => 'admin',     'name' => 'Administrador', 'description' => 'Acceso total al sistema.'],
            ['slug' => 'vendedor',  'name' => 'Vendedor',      'description' => 'Gestiona inventario y ventas.'],
            ['slug' => 'contador',  'name' => 'Contador',      'description' => 'Facturas, movimientos y reportes.'],
            ['slug' => 'cajero',    'name' => 'Cajero',        'description' => 'Registra ventas en la caja.'],
            ['slug' => 'visitante', 'name' => 'Visitante',     'description' => 'Solo consulta.'],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(
                ['slug' => $role['slug']],
                array_merge($role, ['is_system' => true])
            );
        }
    }
}

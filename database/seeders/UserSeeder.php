<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Crea (o actualiza) un usuario de prueba por cada rol.
     * Es idempotente: puedes correrlo varias veces sin duplicar.
     * Todos quedan ACTIVOS y con la contraseña 12345678.
     */
    public function run(): void
    {
        $usuarios = [
            ['name' => 'Administrador', 'email' => 'admin@test.com',     'role' => 'admin'],
            ['name' => 'Vendedor',      'email' => 'vendedor@test.com',  'role' => 'vendedor'],
            ['name' => 'Contador',      'email' => 'contador@test.com',  'role' => 'contador'],
            ['name' => 'Cajero',        'email' => 'cajero@test.com',    'role' => 'cajero'],
            ['name' => 'Visitante',     'email' => 'visitante@test.com', 'role' => 'visitante'],
        ];

        foreach ($usuarios as $u) {
            User::updateOrCreate(
                ['email' => $u['email']],
                [
                    'name'     => $u['name'],
                    'role'     => $u['role'],
                    'active'   => true,
                    'password' => Hash::make('12345678'),
                ]
            );
        }
    }
}

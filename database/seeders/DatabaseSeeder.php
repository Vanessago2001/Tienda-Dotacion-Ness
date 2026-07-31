<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Roles base del sistema
        $this->call(RoleSeeder::class);

        // Usuarios de prueba (uno por rol, activos, contraseña 12345678)
        $this->call(UserSeeder::class);

        // Catálogo de permisos + asignación por defecto según el rol
        // (va después de crear los usuarios para asignarles sus permisos)
        $this->call(PermissionSeeder::class);
    }
}

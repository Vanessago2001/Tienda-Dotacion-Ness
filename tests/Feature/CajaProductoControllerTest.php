<?php

use App\Models\User;

it('muestra un aviso y botón para abrir caja cuando no hay una caja abierta', function () {
    $user = User::create([
        'name' => 'Cajero Test',
        'email' => 'cajero-test-' . uniqid() . '@example.com',
        'password' => bcrypt('password123'),
        'role' => 'cajero',
    ]);

    $this->actingAs($user)
        ->get(route('caja.productos.index'))
        ->assertOk()
        ->assertSee('La caja se encuentra cerrada')
        ->assertSee(route('apertura-cierre-caja.index'));
});

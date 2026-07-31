<?php

namespace App\Http\Controllers;

use App\Models\Auditoria;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Http\Request;

class UserPermissionController extends Controller
{
    /**
     * Lista de usuarios para gestionar sus permisos.
     */
    public function index()
    {
        $users = User::orderBy('name')->get();

        return view('admin.usuarios.index', compact('users'));
    }

    /**
     * Pantalla para marcar/desmarcar los permisos de un usuario.
     */
    public function edit(User $usuario)
    {
        $permisos  = Permission::orderBy('name')->get();
        $asignados = $usuario->permissions()->pluck('permissions.id')->toArray();

        return view('admin.usuarios.permisos', compact('usuario', 'permisos', 'asignados'));
    }

    /**
     * Guarda los permisos seleccionados para el usuario.
     */
    public function update(Request $request, User $usuario)
    {
        $data = $request->validate([
            'permisos'   => ['array'],
            'permisos.*' => ['integer', 'exists:permissions,id'],
        ]);

        $usuario->permissions()->sync($data['permisos'] ?? []);

        Auditoria::registrar('permisos', 'Usuarios', "Actualizó los permisos de {$usuario->name}");

        return redirect()
            ->route('usuarios.index')
            ->with('success', "Permisos de {$usuario->name} actualizados correctamente.");
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Auditoria;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::withCount('permissions')->orderBy('name')->get();

        return view('admin.usuarios.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::orderBy('name')->get();

        return view('admin.usuarios.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role'     => 'required|exists:roles,slug',
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'role'     => $data['role'],
            'active'   => $request->boolean('active'),
        ]);

        Auditoria::registrar('crear', 'Usuarios', "Creó el usuario {$user->name} ({$user->email}) con rol {$user->role}");

        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario creado correctamente.');
    }

    public function edit(User $usuario)
    {
        $roles = Role::orderBy('name')->get();

        return view('admin.usuarios.edit', compact('usuario', 'roles'));
    }

    public function update(Request $request, User $usuario)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $usuario->id,
            'role'     => 'required|exists:roles,slug',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $usuario->name  = $data['name'];
        $usuario->email = $data['email'];
        $usuario->role  = $data['role'];

        if (!empty($data['password'])) {
            $usuario->password = Hash::make($data['password']);
        }

        $usuario->save();

        Auditoria::registrar('editar', 'Usuarios', "Editó el usuario {$usuario->name}");

        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario actualizado correctamente.');
    }

    public function toggleActive(User $usuario)
    {
        if ($usuario->id === auth()->id()) {
            return back()->with('error', 'No puedes cambiar el estado de tu propio usuario.');
        }

        $usuario->active = !$usuario->active;
        $usuario->save();

        $estado = $usuario->active ? 'activó' : 'inactivó';
        Auditoria::registrar($usuario->active ? 'activar' : 'inactivar', 'Usuarios', "Se {$estado} al usuario {$usuario->name}");

        return back()->with('success', "Usuario {$usuario->name} " . ($usuario->active ? 'activado' : 'inactivado') . '.');
    }

    public function destroy(User $usuario)
    {
        if ($usuario->id === auth()->id()) {
            return back()->with('error', 'No puedes eliminar tu propio usuario.');
        }

        $nombre = $usuario->name;
        $usuario->delete();

        Auditoria::registrar('eliminar', 'Usuarios', "Eliminó el usuario {$nombre}");

        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario eliminado correctamente.');
    }
}

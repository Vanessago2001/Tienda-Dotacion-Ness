<?php

namespace App\Http\Controllers;

use App\Models\Auditoria;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::withCount('users')->orderBy('name')->get();

        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        return view('admin.roles.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
        ]);

        $slug = Str::slug($data['name']);

        $request->merge(['slug' => $slug]);
        $request->validate(['slug' => 'unique:roles,slug']);

        $role = Role::create([
            'name'        => $data['name'],
            'slug'        => $slug,
            'description' => $data['description'] ?? null,
            'is_system'   => false,
        ]);

        Auditoria::registrar('crear', 'Roles', "Creó el rol {$role->name}");

        return redirect()->route('roles.index')
            ->with('success', 'Rol creado correctamente.');
    }

    public function edit(Role $role)
    {
        return view('admin.roles.edit', compact('role'));
    }

    public function update(Request $request, Role $role)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
        ]);

        // El slug de los roles del sistema no se cambia (lo usan los middlewares).
        $role->name = $data['name'];
        $role->description = $data['description'] ?? null;

        if (!$role->is_system) {
            $nuevoSlug = Str::slug($data['name']);
            if ($nuevoSlug !== $role->slug && !Role::where('slug', $nuevoSlug)->exists()) {
                $role->slug = $nuevoSlug;
            }
        }

        $role->save();

        Auditoria::registrar('editar', 'Roles', "Editó el rol {$role->name}");

        return redirect()->route('roles.index')
            ->with('success', 'Rol actualizado correctamente.');
    }

    public function destroy(Role $role)
    {
        if ($role->is_system) {
            return back()->with('error', 'Los roles del sistema no se pueden eliminar.');
        }

        if ($role->users()->exists()) {
            return back()->with('error', 'No puedes eliminar un rol que tiene usuarios asignados.');
        }

        $nombre = $role->name;
        $role->delete();

        Auditoria::registrar('eliminar', 'Roles', "Eliminó el rol {$nombre}");

        return redirect()->route('roles.index')
            ->with('success', 'Rol eliminado correctamente.');
    }
}

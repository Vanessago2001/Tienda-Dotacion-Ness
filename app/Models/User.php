<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    public const ROLE_ADMIN = 'admin';
    public const ROLE_VENDEDOR = 'vendedor';
    public const ROLE_CONTADOR = 'contador';
    public const ROLE_VISITANTE = 'visitante';
    public const ROLE_CAJERO = 'cajero';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'photo',
        'active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'active' => 'boolean',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isVendedor(): bool
    {
        return $this->role === self::ROLE_VENDEDOR;
    }
    
    public function isContador(): bool
    {
        return $this->role === self::ROLE_CONTADOR;
    }

    public function isVisitante(): bool
    {
        return $this->role === self::ROLE_VISITANTE;
    }

    public function isCajero(): bool
    {
        return $this->role === self::ROLE_CAJERO;
    }

    /**
     * Permisos asignados individualmente a este usuario por el administrador.
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }

    /**
     * ¿El usuario tiene un permiso concreto?
     * El administrador siempre tiene todos los permisos.
     * La relación se carga una sola vez por petición (queda cacheada en el modelo).
     */
    public function hasPermission(string $slug): bool
    {
        if ($this->isAdmin()) {
            return true;
        }

        return $this->permissions->contains('slug', $slug);
    }
}

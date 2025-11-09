<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    // Definimos las constantes para los roles
    const ROLE_ADMIN = 'admin';
    const ROLE_VETERINARIO = 'veterinario';
    const ROLE_CLIENTE = 'cliente';

    protected $fillable = [
        'name',
        'email',
        'password',
        'rol',
        'direccion',
        'telefono',
        'nif',
        'especialidad',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
    // ========== FUNCIONES ==========

    /**
     * Verificamos si el usuario tiene un rol específico
     */
    public function hasRole($role)
    {
        return $this->rol === $role;
    }

    /**
     * Verificamos si el usuario tiene alguno de los roles especificados
     */
    public function hasAnyRole($roles)
    {
        return in_array($this->rol, $roles);
    }

    /**
     * Verificamos si es administrador
     */
    public function isAdmin()
    {
        return $this->rol === self::ROLE_ADMIN;
    }

    /**
     * Verificamos si es veterinario
     */
    public function isVeterinario()
    {
        return $this->rol === self::ROLE_VETERINARIO;
    }

    /**
     * Verificamos si es cliente
     */
    public function isCliente()
    {
        return $this->rol === self::ROLE_CLIENTE;
    }

    /**
     * Verificamos si puede acceder al panel administrativo
     */
    public function canAccessAdmin()
    {
        return in_array($this->rol, [self::ROLE_ADMIN, self::ROLE_VETERINARIO]);
    }

    // ========== RELACIONES ==========
    
    public function mascotas()
    {
        return $this->hasMany(Mascota::class, 'usuario_id');
    }

    public function citas()
    {
        return $this->hasMany(Cita::class, 'usuario_id');
    }

    public function disponibilidades()
    {
        return $this->hasMany(Disponibilidad::class, 'usuario_id');
    }

    public function tratamientos()
    {
        return $this->hasMany(Tratamiento::class, 'veterinario_id');
    }

    public function pagos()
    {
        return $this->hasMany(Pago::class, 'usuario_id');
    }
}
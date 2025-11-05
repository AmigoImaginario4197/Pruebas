<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

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

    // Un usuario tiene muchas mascotas
    public function mascotas()
    {
        return $this->hasMany(Mascota::class, 'usuario_id');
    }

    // Un usuario puede tener muchas citas veterinarias
    public function citas()
    {
        return $this->hasMany(Cita::class, 'usuario_id');
    }

    // Un veterinario puede tener varias disponibilidades de horario
    public function disponibilidades()
    {
        return $this->hasMany(Disponibilidad::class, 'usuario_id');
    }

    // Un veterinario puede haber aplicado muchos tratamientos
    public function tratamientos()
    {
        return $this->hasMany(Tratamiento::class, 'veterinario_id');
    }

    // Un usuario puede tener varios pagos registrados
    public function pagos()
    {
        return $this->hasMany(Pago::class, 'usuario_id');
    }
}

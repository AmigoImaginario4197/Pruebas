<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mascota extends Model
{
    use HasFactory;

    protected $table = 'mascota';

    protected $fillable = [
        'nombre',
        'especie',
        'raza',
        'edad',
        'peso',
        'foto',
        'fecha_nacimiento',
        'user_id',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function citas()
    {
        return $this->hasMany(Cita::class, 'mascota_id');
    }

    public function historialesMedicos()
    {
        return $this->hasMany(HistorialMedico::class, 'mascota_id');
    }

    public function planesDiarios()
    {
        return $this->hasMany(PlanDiario::class, 'mascota_id');
    }

    public function tratamientos()
    {
        return $this->hasMany(Tratamiento::class, 'mascota_id');
    }
}

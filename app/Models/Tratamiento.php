<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tratamiento extends Model
{
    use HasFactory;

    protected $table = 'tratamiento';

    protected $fillable = [
        'mascota_id',
        'veterinario_id',
        'diagnostico',
        'observaciones',
        'fecha_inicio',
        'fecha_fin',
    ];

    public function mascota()
    {
        return $this->belongsTo(Mascota::class, 'mascota_id');
    }

    public function veterinario()
    {
        return $this->belongsTo(User::class, 'veterinario_id');
    }

    public function medicamentos()
    {
        return $this->belongsToMany(Medicamento::class, 'tratamiento_medicamento')
                    ->withPivot('frecuencia', 'duracion')
                    ->withTimestamps();
    }
}

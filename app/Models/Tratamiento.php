<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Cita; // <-- IMPORTANTE: Añade esta línea

class Tratamiento extends Model
{
    use HasFactory;

    protected $table = 'tratamiento'; 

    protected $fillable = [
        'diagnostico',
        'observaciones',
        'fecha_inicio',
        'fecha_fin',
    ];

    /**
     * Define la relación: Un tratamiento pertenece a una Cita.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function cita()
    {
        return $this->belongsTo(Cita::class, 'cita_id');
    }

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
                    ->withPivot('dosis', 'frecuencia', 'duracion', 'instrucciones')
                    ->withTimestamps();
    }
}
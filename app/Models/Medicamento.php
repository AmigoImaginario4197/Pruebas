<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medicamento extends Model
{
    use HasFactory;

    protected $table = 'medicamento';

    protected $fillable = [
        'nombre',
        'dosis_recomendada',
        'descripcion',
        'foto',
    ];

    public function tratamientos()
    {
        return $this->belongsToMany(Tratamiento::class, 'tratamiento_medicamento', 'medicamento_id', 'tratamiento_id')
                    ->withPivot('frecuencia', 'duracion');
    }
}

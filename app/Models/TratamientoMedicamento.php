<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TratamientoMedicamento extends Model
{
    use HasFactory;

    protected $table = 'tratamiento_medicamento';

    protected $fillable = [
        'tratamiento_id',
        'medicamento_id',
        'frecuencia',
        'duracion',
    ];

    public function tratamiento()
    {
        return $this->belongsTo(Tratamiento::class, 'tratamiento_id');
    }

    public function medicamento()
    {
        return $this->belongsTo(Medicamento::class, 'medicamento_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanDiario extends Model
{
    use HasFactory;

    protected $table = 'plan_diario';

    protected $fillable = [
        'fecha',
        'actividad',
        'hora',
        'descripcion',
    ];

    public function mascota()
    {
        return $this->belongsTo(Mascota::class, 'mascota_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tarea extends Model
{
    use HasFactory;

    protected $table = 'tareas';

    protected $fillable = [
        'user_id',
        'titulo',
        'inicio',
        'fin',
        'color',
        'observaciones',
        'asignado_a_id',   
        'especialidad_asignada',
    ];

      protected $casts = [
        'inicio' => 'datetime',
        'fin'    => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function mascota()
    {
        return $this->belongsTo(Mascota::class, 'mascota_id');
    }

       public function creador()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function asignadoA()
    {
        return $this->belongsTo(User::class, 'asignado_a_id');
    }
}

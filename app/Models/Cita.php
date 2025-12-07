<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    use HasFactory;

    protected $table = 'cita';

    protected $fillable = [
        'user_id',       
        'mascota_id',     
        'veterinario_id',
        'servicio_id',
        'fecha_hora',
        'estado',
        'motivo',
    ];

    public function cliente()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function veterinario()
    {
        return $this->belongsTo(User::class, 'veterinario_id');
    }

    public function mascota()
    {
        return $this->belongsTo(Mascota::class, 'mascota_id');
    }

    public function servicio()
    {
        return $this->belongsTo(Servicio::class, 'servicio_id');
    }
 
}

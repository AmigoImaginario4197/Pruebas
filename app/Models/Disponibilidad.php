<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Disponibilidad extends Model
{
    use HasFactory;

    protected $table = 'disponibilidad';

    protected $fillable = [
        'usuario_id',
        'dia',
        'hora_inicio',
        'hora_fin',
    ];

    public function veterinario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}

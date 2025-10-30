<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Verificacion extends Model
{
    use HasFactory;

    protected $table = 'verificacion';

    protected $fillable = [
        'usuario_id',
        'codigo',
        'fecha_envio',
        'verificado',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}

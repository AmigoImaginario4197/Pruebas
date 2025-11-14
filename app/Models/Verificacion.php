<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Verificacion extends Model
{
    use HasFactory;

    protected $table = 'verificacion';

    protected $fillable = [
        'codigo_verificacion',
        'expira_en',
        'usado',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Archivo extends Model
{
    use HasFactory;

    protected $table = 'archivo';

    protected $fillable = [
        'nombre',
        'ruta_archivo',
        'fecha_subida',
    ];

    public function mascota()
    {
        return $this->belongsTo(Mascota::class, 'mascota_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

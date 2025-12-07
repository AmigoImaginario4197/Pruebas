<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogEvento extends Model
{
    use HasFactory;

    protected $table = 'log_eventos';

    protected $fillable = [
        'user_id', 
        'accion', 
        'detalle', 
        'fecha'
    ];

    public $timestamps = false;

    //Convertimos 'fecha' a objeto Carbon para poder formatearla fácil en la vista
    protected $casts = [
        'fecha' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
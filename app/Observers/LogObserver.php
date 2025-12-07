<?php

namespace App\Observers;

use App\Models\LogEvento;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class LogObserver
{
    public function created(Model $model)
    {
        $this->registrar('Creó', $model);
    }

    public function updated(Model $model)
    {
        $this->registrar('Editó', $model);
    }

    public function deleted(Model $model)
    {
        $this->registrar('Eliminó', $model);
    }

    private function registrar($accion, Model $model)
    {
        // Solo registramos si hay un usuario logueado (para evitar errores en tareas de sistema)
        if (!Auth::check()) return;

        // Obtenemos el nombre limpio del modelo (Ej: Mascota, Cita)
        $nombreClase = class_basename($model);
        
        // Tratamos de identificar el objeto (Nombre, Título o ID)
        $identificador = $model->nombre ?? $model->titulo ?? $model->motivo ?? 'ID: ' . $model->id;

        LogEvento::create([
            'user_id' => Auth::id(),
            'accion'  => $accion . ' ' . $nombreClase, 
            'detalle' => "Se realizó acción sobre: " . $identificador,
            'fecha'   => now()
        ]);
    }
}
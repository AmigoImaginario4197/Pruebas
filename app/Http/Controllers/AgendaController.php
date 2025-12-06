<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cita;
use App\Models\Tarea; 

class AgendaController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $eventos = [];

            // 1. CARGAR CITAS

            $citas = Cita::with(['mascota', 'user'])
                         ->where('estado', '!=', 'cancelada')
                         ->get();

            foreach ($citas as $cita) {
                // Lógica de colores según estado
                $color = match ($cita->estado) {
                    'pendiente' => '#ffc107',  // Amarillo
                    'confirmada' => '#198754', // Verde
                    'completada' => '#6c757d', // Gris
                    default => '#3788d8',      // Azul
                };

                $eventos[] = [
                    // Prefijo 'cita_' para que no se confunda con las tareas si tienen el mismo ID
                    'id'    => 'cita_' . $cita->id, 
                    'title' => '🐾 ' . $cita->mascota->nombre . ' - ' . $cita->motivo,
                    'start' => $cita->fecha_hora_inicio,
                    'end'   => $cita->fecha_hora_fin,
                    'backgroundColor' => $color,
                    'borderColor' => $color,
                    'textColor' => $cita->estado === 'pendiente' ? '#000000' : '#ffffff',
                    // Datos extra para que el JS sepa qué modal abrir
                    'extendedProps' => [
                        'tipo'    => 'cita', 
                        'real_id' => $cita->id,
                        'cliente' => $cita->user->name ?? 'Cliente',
                        'estado'  => $cita->estado,
                        'motivo'  => $cita->motivo
                    ]
                ];
            }


            // 2. CARGAR TAREAS INTERNAS (De la tabla 'tareas')

            $tareas = Tarea::with('user')->get();

            foreach ($tareas as $tarea) {
                $eventos[] = [
                    'id'    => 'tarea_' . $tarea->id,
                    'title' => '📋 ' . $tarea->titulo, 
                    'start' => $tarea->inicio,        
                    'end'   => $tarea->fin,           
                    'backgroundColor' => $tarea->color ?? '#6c757d', 
                    'borderColor' => $tarea->color ?? '#6c757d',
                    'textColor' => '#ffffff',
                    'extendedProps' => [
                        'tipo'    => 'tarea',
                        'real_id' => $tarea->id,
                        'creado_por' => $tarea->user->name ?? 'Sistema',
                        'observaciones' => $tarea->observaciones
                    ]
                ];
            }

            return response()->json($eventos);
        }

        return view('agenda.index');
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cita;
use App\Models\Tarea;
use Illuminate\Support\Facades\Auth;

class AgendaController extends Controller
{
    /**
     * Muestra la vista principal del calendario.
     */
    public function index()
    {
        return view('agenda.index');
    }

    /**
     * Devuelve los eventos en JSON para FullCalendar.
     */
    public function getEvents()
    {
        $events = [];
        
        /** @var \App\Models\User $user */
        $user = Auth::user(); // El comentario de arriba quita el rojo en isAdmin()
        
        $isAdmin = $user->isAdmin(); 

        // ==========================================
        // 1. CITAS MÉDICAS
        // ==========================================
        $appointments = Cita::with(['mascota', 'cliente'])
                            ->where('estado', '!=', 'cancelada')
                            ->get();

        foreach ($appointments as $appointment) {
            $color = match ($appointment->estado) {
                'confirmada' => '#198754', // Verde
                default      => '#ffc107', // Amarillo
            };
            
            $textColor = $appointment->estado === 'confirmada' ? '#ffffff' : '#000000';
            
            // Admin puede editar todo.
            // Opcional: Veterinario podría editar sus propias citas si agregas esa lógica
            $canEdit = $isAdmin; 

            $events[] = [
                'id'    => 'appointment_' . $appointment->id,
                'title' => '🐾 ' . $appointment->mascota->nombre,
                'start' => \Carbon\Carbon::parse($appointment->fecha_hora_inicio)->toIso8601String(),
                'end'   => \Carbon\Carbon::parse($appointment->fecha_hora_fin)->toIso8601String(),
                'backgroundColor' => $color,
                'borderColor' => $color,
                'textColor' => $textColor,
                'extendedProps' => [
                    'type'        => 'appointment',
                    'client'      => $appointment->cliente->name ?? 'N/A',
                    'reason'      => $appointment->motivo,
                    'status'      => $appointment->estado,
                    'edit_url'    => route('citas.edit', $appointment->id),
                    'can_edit'    => $canEdit,
                    'source_id'   => $appointment->id
                ]
            ];
        }

        // ==========================================
        // 2. TAREAS INTERNAS
        // ==========================================
        $tasks = Tarea::with('user')->get();

        foreach ($tasks as $task) {
            $color = '#6c757d'; // Gris
            
            // REGLA DE ORO: Solo Admin puede editar/borrar tareas.
            // El veterinario solo las ve.
            $canEdit = $isAdmin;

            $events[] = [
                'id'    => 'task_' . $task->id,
                'title' => '📋 ' . $task->titulo, 
                'start' => \Carbon\Carbon::parse($task->inicio)->toIso8601String(), 
                'end'   => \Carbon\Carbon::parse($task->fin)->toIso8601String(),    
                'backgroundColor' => $color, 
                'borderColor' => $color,
                'textColor' => '#ffffff',
                'extendedProps' => [
                    'type'        => 'task',
                    'created_by'  => $task->user->name ?? 'Sistema',
                    'notes'       => $task->observaciones,
                    'source_id'   => $task->id,
                    'can_edit'    => $canEdit,
                    'edit_url'    => route('tareas.edit', $task->id) // Necesario para el botón de editar
                ]
            ];
        }

        return response()->json($events);
    }
}
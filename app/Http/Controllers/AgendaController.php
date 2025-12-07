<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cita;
use App\Models\Tarea;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AgendaController extends Controller
{
    /**
     * Muestra la vista principal del calendario.
     */
    public function index()
    {
        $users = [];
        
        // Usamos User::find para evitar errores visuales en el editor
        $currentUser = User::find(Auth::id());

        // Si es Admin, cargamos la lista de usuarios para el filtro del select
        if ($currentUser && $currentUser->isAdmin()) {
            $users = User::orderBy('name')->get();
        }

        return view('agenda.index', compact('users'));
    }

    /**
     * Devuelve los eventos en JSON para FullCalendar.
     */
    public function getEvents(Request $request)
    {
        $events = [];
        
        $user = User::find(Auth::id()); 
        $isAdmin = $user->isAdmin(); 

        // Capturamos el filtro si existe en la URL (?user_id=5)
        $filtroUser = $request->input('user_id');

 
        // CITAS MÉDICAS
   
        $queryCitas = Cita::with(['mascota', 'cliente'])
                          ->where('estado', '!=', 'cancelada');

        // APLICAR FILTRO: Si es admin y seleccionó un usuario
        if ($isAdmin && $filtroUser) {
            // Filtramos por Cliente (user_id).
            $queryCitas->where('user_id', $filtroUser);
        }

        $appointments = $queryCitas->get();

        foreach ($appointments as $appointment) {
            $color = match ($appointment->estado) {
                'confirmada' => '#198754', 
                default      => '#ffc107', 
            };
            
            $textColor = $appointment->estado === 'confirmada' ? '#ffffff' : '#000000';
            
            // Admin puede editar todo
            $canEdit = $isAdmin; 

            $events[] = [
                'id'    => 'appointment_' . $appointment->id,
                'title' => '🐾 ' . $appointment->mascota->nombre,
                'start' => Carbon::parse($appointment->fecha_hora_inicio)->toIso8601String(),
                'end'   => Carbon::parse($appointment->fecha_hora_fin)->toIso8601String(),
                'backgroundColor' => $color,
                'borderColor' => $color,
                'textColor' => $textColor,
                'extendedProps' => [
                    'type'        => 'appointment',
                    'client'      => $appointment->cliente->name ?? 'N/A',
                    'reason'      => $appointment->motivo,
                    'status'      => $appointment->estado,
                    // URL para redirigir al dar clic 
                    'view_url'    => route('citas.show', $appointment->id),
                    'can_edit'    => $canEdit
                ]
            ];
        }

        // TAREAS INTERNAS

        $queryTareas = Tarea::with('user');

        // APLICAR FILTRO
        if ($isAdmin && $filtroUser) {
            // Filtramos por quién creó la tarea
            $queryTareas->where('user_id', $filtroUser);
        }

        $tasks = $queryTareas->get();

        foreach ($tasks as $task) {
            $color = '#6c757d'; 
            
            // Solo Admin puede editar/borrar tareas
            $canEdit = $isAdmin;

            $events[] = [
                'id'    => 'task_' . $task->id,
                'title' => '📋 ' . $task->titulo, 
                'start' => Carbon::parse($task->inicio)->toIso8601String(), 
                'end'   => Carbon::parse($task->fin)->toIso8601String(),    
                'backgroundColor' => $color, 
                'borderColor' => $color,
                'textColor' => '#ffffff',
                'extendedProps' => [
                    'type'        => 'task',
                    'created_by'  => $task->user->name ?? 'Sistema',
                    'notes'       => $task->observaciones,
                    // URL para redirigir al dar clic
                    'view_url'    => route('tareas.show', $task->id),
                    'can_edit'    => $canEdit
                ]
            ];
        }

        return response()->json($events);
    }
}
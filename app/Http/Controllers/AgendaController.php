<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Cita;   
use App\Models\Tarea;  
use App\Models\User;

class AgendaController extends Controller
{
    /**
     * Constructor para aplicar middleware de autenticación y autorización.
     */
    public function __construct()
    {
        // Requiere que el usuario esté autenticado para todas las acciones del controlador.
        $this->middleware('auth');
        
        // Aplica el middleware 'admin' a todas las acciones EXCEPTO a 'index' y 'getEvents'.
        // Esto significa que solo los admins pueden crear, guardar, editar, actualizar o borrar.
        $this->middleware('admin')->except(['index', 'getEvents']);
    }

    /**
     * Muestra la vista principal de la agenda (el contenedor del calendario).
     */
    public function index()
    {
        $users = [];
        $currentUser = User::find(Auth::id());

        // Si el usuario es un administrador, le pasamos la lista de todos los usuarios
        // para que pueda usar el filtro en la vista.
        if ($currentUser && $currentUser->isAdmin()) {
            $users = User::orderBy('name')->get();
        }

        return view('agenda.index', compact('users'));
    }

    /**
     * Proporciona los datos de eventos en formato JSON para FullCalendar.
     * Esta función contiene la lógica de negocio para filtrar qué ve cada rol.
     */
public function getEvents(Request $request)
{
    $currentUser = User::find(Auth::id());
    $filtroUserId = $request->input('user_id');

    // 1. DETERMINAR EL "USUARIO OBJETIVO" (¿De quién vamos a ver la agenda?)
    $targetUser = $currentUser; 

    // Si es Admin (usando la columna 'rol') y ha seleccionado a alguien, cambiamos el objetivo
    if ($currentUser->rol === 'admin' && $filtroUserId) {
        $foundUser = User::find($filtroUserId);
        if ($foundUser) {
            $targetUser = $foundUser;
        }
    }

    $appointments = collect();
    $tasks = collect();

    // 2. APLICAR LA LÓGICA SEGÚN EL ROL DEL OBJETIVO
    
    // CASO A: El objetivo es un CLIENTE
    if ($targetUser->rol === 'cliente') {
        // Regla: "Ver las citas que tiene agendadas" (donde él es el user_id)
        $appointments = Cita::with(['mascota', 'cliente', 'veterinario'])
                            ->where('estado', '!=', 'cancelada')
                            ->where('user_id', $targetUser->id) 
                            ->get();
        // Clientes no ven tareas.
    }

    // CASO B: El objetivo es un VETERINARIO
    elseif ($targetUser->rol === 'veterinario') {
        // Regla 1: "Ver citas que tiene que atender" (donde él es el veterinario_id)
        $appointments = Cita::with(['mascota', 'cliente', 'veterinario'])
                            ->where('estado', '!=', 'cancelada')
                            ->where('veterinario_id', $targetUser->id)
                            ->get();

        // Regla 2: "Ver tareas asignadas a él o a su especialidad"
        // (Buscamos en asignado_a_id y especialidad_asignada)
        $tasks = Tarea::with(['creador', 'asignadoA'])
                      ->where(function ($query) use ($targetUser) {
                          $query->where('asignado_a_id', $targetUser->id); // Asignación directa
                          
                          if (!empty($targetUser->especialidad)) {
                              $query->orWhere('especialidad_asignada', $targetUser->especialidad); // Asignación por especialidad
                          }
                          
                          // Opcional: Tareas generales sin asignación
                          // $query->orWhere(function($q) { $q->whereNull('asignado_a_id')->whereNull('especialidad_asignada'); });
                      })
                      ->get();
    }

    // CASO C: El objetivo es un ADMIN
    elseif ($targetUser->rol === 'admin') {
        // Regla 1: "Ver sus propias citas agendadas" (Como si el admin fuera dueño de una mascota)
        $appointments = Cita::with(['mascota', 'cliente', 'veterinario'])
                            ->where('estado', '!=', 'cancelada')
                            ->where('user_id', $targetUser->id)
                            ->get();

        // Regla 2: "Ver las tareas que haya creado" (donde user_id es él)
        $tasks = Tarea::with(['creador', 'asignadoA'])
                      ->where('user_id', $targetUser->id)
                      ->get();
    }

    // 3. FORMATEAR DATOS (Esto no cambia, solo nos aseguramos de usar Carbon)
    $events = [];

    foreach ($appointments as $appointment) {
        // Aseguramos que sea una fecha válida, ya que tu tabla Cita tiene 'fecha_hora'
        $fechaInicio = Carbon::parse($appointment->fecha_hora);
        $fechaFin = $fechaInicio->copy()->addMinutes(30);
        
        $color = match ($appointment->estado) { 'confirmada' => '#198754', default => '#ffc107', };
        $textColor = $appointment->estado === 'confirmada' ? '#ffffff' : '#000000';
        
        $events[] = [
            'id' => 'appointment_' . $appointment->id,
            'title' => '🐾 ' . ($appointment->mascota->nombre ?? 'N/A'),
            'start' => $fechaInicio->toIso8601String(),
            'end' => $fechaFin->toIso8601String(),
            'backgroundColor' => $color, 'borderColor' => $color, 'textColor' => $textColor,
            'extendedProps' => [
                'type' => 'appointment',
                'client' => $appointment->cliente->name ?? 'N/A',
                'veterinarian' => $appointment->veterinario->name ?? 'Sin asignar',
                'reason' => $appointment->motivo,
                'status' => $appointment->estado,
                'view_url' => route('citas.show', $appointment->id),
                'can_edit' => ($currentUser->rol === 'admin') // Solo si el usuario logueado es admin
            ]
        ];
    }

    foreach ($tasks as $task) {
        $events[] = [
            'id' => 'task_' . $task->id,
            'title' => '📋 ' . $task->titulo,
            'start' => Carbon::parse($task->inicio)->toIso8601String(),
            'end' => Carbon::parse($task->fin)->toIso8601String(),
            'backgroundColor' => $task->color ?? '#6c757d', 'borderColor' => $task->color ?? '#6c757d', 'textColor' => '#ffffff',
            'extendedProps' => [
                'type' => 'task',
                'source_id' => $task->id,
                'created_by' => $task->creador->name ?? 'Sistema', // Usamos la relación 'creador'
                'assigned_to' => $task->asignadoA->name ?? null,   // Usamos la relación 'asignadoA'
                'assigned_specialty' => $task->especialidad_asignada ?? null,
                'notes' => $task->observaciones,
                'edit_url' => route('tareas.edit', $task->id),
                'view_url' => route('tareas.show', $task->id),
                'can_edit' => ($currentUser->rol === 'admin')
            ]
        ];
    }

    return response()->json($events);
}
    
    // --- MÉTODOS DEL CONTROLADOR DE RECURSOS (PROTEGIDOS POR MIDDLEWARE 'admin') ---
    
    public function create()
    {
        // Lógica para mostrar el formulario de creación.
    }

    public function store(Request $request)
    {
        // Lógica para validar y guardar una nueva cita/tarea.
    }

    public function show($id)
    {
        // Lógica para mostrar los detalles de una cita/tarea.
        // ¡Recuerda añadir aquí también una capa de seguridad para que un
        // cliente no pueda ver una cita que no es suya por URL!
    }

    public function edit($id)
    {
        // Lógica para buscar el recurso y mostrar el formulario de edición.
    }

    public function update(Request $request, $id)
    {
        // Lógica para validar y actualizar una cita/tarea.
    }

    public function destroy($id)
    {
        // Lógica para encontrar y eliminar una cita/tarea.
    }
}
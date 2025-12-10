<?php

namespace App\Http\Controllers;

use App\Models\Tarea;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TareaController extends Controller
{
    /**
     * Constructor para aplicar middleware de seguridad.
     */
    public function __construct()
    {
        // 1. Todos los usuarios deben estar logueados.
        $this->middleware('auth');

        // 2. Definición de Permisos:
        
        // VER TAREAS (Index y Show): Permitido para Admin y Veterinario.
        $this->middleware('role:admin,veterinario')->only(['index', 'show']);

        // GESTIONAR TAREAS (Crear, Editar, Borrar): EXCLUSIVO para Admin.
        $this->middleware('role:admin')->only(['create', 'store', 'edit', 'update', 'destroy']);
    }

    /**
     * Muestra la lista de tareas.
     * FILTRA las tareas según el rol del usuario.
     */
    public function index()
    {
        $user = Auth::user();

        // Iniciamos la consulta cargando las relaciones para optimizar
        $query = Tarea::with('creador', 'asignadoA')->latest();

        // Lógica de Visibilidad:
        // Si NO es admin (es decir, es veterinario), filtramos para mostrar solo lo suyo.
        if ($user->rol !== 'admin') {
            $query->where(function ($q) use ($user) {
                // 1. Tareas asignadas directamente a él
                $q->where('asignado_a_id', $user->id)
                  
                  // 2. Tareas asignadas a su especialidad (si tiene)
                  ->orWhere(function ($sub) use ($user) {
                      if (!empty($user->especialidad)) {
                          $sub->where('especialidad_asignada', $user->especialidad);
                      } else {
                          // Si no tiene especialidad, esta condición no debe traer nada
                          $sub->whereRaw('1=0');
                      }
                  })
                  
                  // 3. Tareas generales (sin asignación específica)
                  ->orWhere(function ($sub) {
                      $sub->whereNull('asignado_a_id')->whereNull('especialidad_asignada');
                  });
            });
        }
        // Si es admin, no entra en el if, por lo que $query trae TODAS las tareas.

        $tareas = $query->paginate(15);
        return view('tareas.index', compact('tareas'));
    }

    /**
     * Muestra el detalle de una tarea específica.
     */
    public function show(Tarea $tarea)
    {
        // Opcional: Podrías añadir aquí una validación extra para que un veterinario
        // no pueda ver por URL una tarea que no le corresponde, pero con el filtro
        // del index suele ser suficiente para empezar.
        return view('tareas.show', compact('tarea'));
    }

    /**
     * Muestra el formulario para crear una nueva tarea.
     */
    public function create()
    {
        $veterinarios = User::where('rol', 'veterinario')->orderBy('name')->get();
        $especialidades = ['Cardiología', 'Cirugía', 'Dermatología', 'General', 'Oftalmología'];

        return view('tareas.create', compact('veterinarios', 'especialidades'));
    }

    /**
     * Guarda una nueva tarea en la base de datos.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'titulo' => 'required|string|max:255',
            'inicio' => 'required|date',
            'fin'    => 'required|date|after_or_equal:inicio',
            'observaciones' => 'nullable|string',
            'color'  => 'nullable|string',
            'asignado_a_id' => 'nullable|exists:users,id',
            'especialidad_asignada' => 'nullable|string|max:255',
        ]);

        // Asegurar exclusividad: Si asigna a persona, borra especialidad, y viceversa.
        if (!empty($data['asignado_a_id'])) {
            $data['especialidad_asignada'] = null;
        } elseif (!empty($data['especialidad_asignada'])) {
            $data['asignado_a_id'] = null;
        }

        $data['user_id'] = Auth::id();
        $data['color'] = $data['color'] ?? '#6c757d';

        Tarea::create($data);

        if (str_contains(url()->previous(), 'agenda')) {
            return redirect()->back()->with('success', 'Tarea interna agregada.');
        }
        return redirect()->route('tareas.index')->with('success', 'Tarea creada correctamente.');
    }

    /**
     * Muestra el formulario para editar una tarea existente.
     */
    public function edit(Tarea $tarea)
    {
        $veterinarios = User::where('rol', 'veterinario')->orderBy('name')->get();
        $especialidades = ['Cardiología', 'Cirugía', 'Dermatología', 'General', 'Oftalmología'];

        return view('tareas.edit', compact('tarea', 'veterinarios', 'especialidades'));
    }

    /**
     * Actualiza una tarea existente.
     */
    public function update(Request $request, Tarea $tarea)
    {
        $data = $request->validate([
            'titulo' => 'required|string|max:255',
            'inicio' => 'required|date',
            'fin'    => 'required|date|after_or_equal:inicio',
            'observaciones' => 'nullable|string',
            'color'  => 'nullable|string',
            'asignado_a_id' => 'nullable|exists:users,id',
            'especialidad_asignada' => 'nullable|string|max:255',
        ]);
        
        // Asegurar exclusividad
        if (!empty($data['asignado_a_id'])) {
            $data['especialidad_asignada'] = null;
        } elseif (!empty($data['especialidad_asignada'])) {
            $data['asignado_a_id'] = null;
        } else {
            // Si ambos vienen vacíos, significa que es una tarea general
            $data['asignado_a_id'] = null;
            $data['especialidad_asignada'] = null;
        }

        $tarea->update($data);

        return redirect()->route('tareas.index')->with('success', 'Tarea actualizada correctamente.');
    }

    /**
     * Elimina una tarea.
     */
    public function destroy(Tarea $tarea)
    {
        $tarea->delete();
        return redirect()->route('tareas.index')->with('success', 'Tarea eliminada correctamente.');
    }
}
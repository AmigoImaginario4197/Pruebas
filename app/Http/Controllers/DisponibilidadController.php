<?php

namespace App\Http\Controllers;

use App\Models\Disponibilidad;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class DisponibilidadController extends Controller
{
    /**
     * Constructor: Define los permisos de acceso.
     */
    public function __construct()
    {
        // Permitimos el acceso tanto a administradores como a veterinarios.
        // Ya no restringimos 'create' o 'store' solo a admins, 
        // porque ahora los veterinarios gestionan su propia agenda.
        $this->middleware('role:admin,veterinario');
    }

    /**
     * Muestra la lista de horarios disponibles.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // --- LÓGICA PARA EL ADMINISTRADOR ---
        if ($user->rol === 'admin') {
            $veterinarios = User::where('rol', 'veterinario')->orderBy('name')->get();
            $selectedVetId = $request->input('veterinario_id');
            $disponibilidades = [];

            if ($selectedVetId) {
                $disponibilidades = Disponibilidad::where('veterinario_id', $selectedVetId)
                                      ->where('fecha', '>=', now()->format('Y-m-d'))
                                      ->orderBy('fecha')->orderBy('hora_inicio')
                                      ->get();
            }
            
            return view('disponibilidad.index', compact('veterinarios', 'disponibilidades', 'selectedVetId'));
        }
        
        // --- LÓGICA PARA EL VETERINARIO ---
        // Usamos la consulta explícita para evitar advertencias del editor de código.
        $disponibilidades = Disponibilidad::where('veterinario_id', $user->id)
                              ->where('fecha', '>=', now()->format('Y-m-d'))
                              ->orderBy('fecha')->orderBy('hora_inicio')
                              ->get();
                              
        return view('disponibilidad.index_veterinario', compact('disponibilidades'));
    }

    /**
     * Muestra el formulario para crear un nuevo bloque.
     */
    public function create()
    {
        // Solo el admin necesita la lista de veterinarios para el selector.
        $veterinarios = Auth::user()->rol === 'admin' 
            ? User::where('rol', 'veterinario')->orderBy('name')->get() 
            : [];
            
        return view('disponibilidad.create', compact('veterinarios'));
    }

    /**
     * Guarda el nuevo bloque en la base de datos.
     */
    public function store(Request $request)
    {
        // Reglas de validación comunes
        $rules = [
            'fecha' => 'required|date|after_or_equal:today',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
        ];

        // Si es admin, el campo veterinario_id es obligatorio en el formulario.
        if (Auth::user()->rol === 'admin') {
            $rules['veterinario_id'] = 'required|exists:users,id';
        }

        $validated = $request->validate($rules);

        // Si es veterinario, asignamos su propio ID automáticamente.
        if (Auth::user()->rol === 'veterinario') {
            $validated['veterinario_id'] = Auth::id();
        }

        Disponibilidad::create($validated);
        
        // Redirección: Admin vuelve al filtro, Veterinario vuelve a su lista.
        if (Auth::user()->rol === 'admin') {
            return redirect()->route('disponibilidad.index', ['veterinario_id' => $validated['veterinario_id']])
                             ->with('success', 'Bloque creado correctamente.');
        }

        return redirect()->route('disponibilidad.index')->with('success', 'Bloque añadido a tu agenda.');
    }

    /**
     * Muestra el formulario para editar un bloque existente.
     */
    public function edit(Disponibilidad $disponibilidad)
    {
        // Seguridad: Un veterinario solo puede editar SU propio horario.
        if (Auth::user()->rol === 'veterinario' && $disponibilidad->veterinario_id !== Auth::id()) {
            abort(403, 'No tienes permiso para editar este horario.');
        }

        return view('disponibilidad.edit', compact('disponibilidad'));
    }

    /**
     * Actualiza los datos del bloque en la base de datos.
     */
    public function update(Request $request, Disponibilidad $disponibilidad)
    {
        // Seguridad
        if (Auth::user()->rol === 'veterinario' && $disponibilidad->veterinario_id !== Auth::id()) {
            abort(403, 'No tienes permiso para editar este horario.');
        }

        $validated = $request->validate([
            'fecha' => 'required|date|after_or_equal:today',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
        ]);

        $disponibilidad->update($validated);

        if (Auth::user()->rol === 'admin') {
            return redirect()->route('disponibilidad.index', ['veterinario_id' => $disponibilidad->veterinario_id])
                             ->with('success', 'Bloque actualizado.');
        }

        return redirect()->route('disponibilidad.index')->with('success', 'Bloque actualizado.');
    }

    /**
     * Elimina el bloque de la base de datos.
     */
    public function destroy(Disponibilidad $disponibilidad)
    {
        // Seguridad
        if (Auth::user()->rol === 'veterinario' && $disponibilidad->veterinario_id !== Auth::id()) {
            abort(403, 'No tienes permiso para eliminar este horario.');
        }
        
        $disponibilidad->delete();
        
        return back()->with('success', 'Bloque eliminado.');
    }
}
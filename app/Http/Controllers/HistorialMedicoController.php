<?php

namespace App\Http\Controllers;

use App\Models\HistorialMedico;
use App\Models\Mascota;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Tratamiento;

class HistorialMedicoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // Solo admin y veterinarios pueden crear, editar o borrar historiales
        $this->middleware('role:admin,veterinario')->except(['index', 'show']);
    }

    /**
     * Muestra la lista de historiales.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->rol === 'cliente') {
            // Cliente: Ve historial solo de SUS mascotas
            $historiales = HistorialMedico::whereHas('mascota', function($query) use ($user) {
                $query->where('user_id', $user->id); // Corrección: user_id
            })->orderBy('fecha', 'desc')->paginate(10);
        } else {
            // Admin/Vet: Ven TODOS los historiales
            $historiales = HistorialMedico::with('mascota.usuario')->orderBy('fecha', 'desc')->paginate(15);
        }

        return view('historial.index', compact('historiales'));
    }

    /**
     * Muestra el formulario para crear un nuevo historial.
     */
    public function create()
{
    // 1. Cargamos las mascotas para saber a quién le pasó algo
    $mascotas = Mascota::with('usuario')->get();

    // 2. Cargamos los tratamientos disponibles para poder vincular uno (si aplica)
    //    Esto es lo que faltaba y causaba el error "Undefined variable"
    $tratamientos = Tratamiento::all(); 

    return view('historial.create', compact('mascotas', 'tratamientos'));
}

    /**
     * Guarda un nuevo historial médico.
     */
    public function store(Request $request)
{
    // 1. Validación adaptada a la nueva vista
    $validated = $request->validate([
        // Verifica si tu tabla es 'mascotas' o 'mascota'. Lo normal es plural.
        'mascota_id' => 'required|exists:mascota,id', 
        
        'fecha' => 'required|date|before_or_equal:today',
        'descripcion' => 'required|string',
        
        // Permitimos que sea null (chequeo) o un ID válido
        'tratamiento_id' => 'nullable|exists:tratamientos,id', 
    ]);

    // 2. Si tenías un campo 'tipo' en la base de datos y no quieres borrarlo,
    // podemos rellenarlo automáticamente para que no dé error SQL.
    // Si ya borraste la columna 'tipo' de la BD, borra esta línea.
    $validated['tipo'] = 'Consulta / Historial'; 

    // 3. Crear el registro
    // Asegúrate de importar el modelo correcto arriba (Historial o HistorialMedico)
    \App\Models\HistorialMedico::create($validated);

    return redirect()->route('historial.index')
        ->with('success', 'Historial médico añadido correctamente.');
}

    /**
     * Muestra los detalles de un historial.
     */
    public function show($id)
    {
        // CARGAMOS RELACIONES: Mascota y Tratamiento (vital para el nuevo diseño)
        $historial = HistorialMedico::with(['mascota', 'tratamiento'])->findOrFail($id);
        $user = Auth::user();

        // Seguridad: Cliente solo ve lo suyo
        if ($user->rol === 'cliente' && $historial->mascota->user_id !== $user->id) {
            abort(403, 'No tienes permiso para ver este historial.');
        }

        return view('historial.show', compact('historial'));
    }

    /**
     * Muestra el formulario de edición.
     */
    public function edit($id)
    {
        $historial = HistorialMedico::findOrFail($id);
        
        // Cargamos mascotas y tratamientos para los selectores
        $mascotas = Mascota::with('usuario')->get();
        $tratamientos = \App\Models\Tratamiento::all(); // <--- IMPORTANTE: Faltaba esto
        
        return view('historial.edit', compact('historial', 'mascotas', 'tratamientos'));
    }

    /**
     * Actualiza el historial.
     */
     public function update(Request $request, $id)
    {
        $historial = HistorialMedico::findOrFail($id);

        // Validación actualizada (Igual que en Store)
        $validated = $request->validate([
            // 'mascota_id' normalmente no se edita en historial, pero si quieres permitirlo:
            // 'mascota_id' => 'required|exists:mascota,id', 
            
            'fecha' => 'required|date|before_or_equal:today',
            'descripcion' => 'required|string',
            'tratamiento_id' => 'nullable|exists:tratamientos,id', // <--- Nuevo campo
        ]);

        // Actualizamos (Usamos $validated directamente)
        $historial->update($validated);

        return redirect()->route('historial.index')->with('success', 'Historial médico actualizado correctamente.');
    }

    /**
     * Elimina el historial.
     */
    public function destroy($id)
    {
        $historial = HistorialMedico::findOrFail($id);
        $historial->delete();

        return redirect()->route('historial.index')->with('success', 'Registro eliminado del historial.');
    }
}
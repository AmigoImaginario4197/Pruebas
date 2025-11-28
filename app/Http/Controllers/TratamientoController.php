<?php

namespace App\Http\Controllers;

use App\Models\Tratamiento;
use App\Models\Mascota;
use App\Models\User;
use App\Models\Medicamento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class TratamientoController extends Controller
{
    public function __construct()
    {
        // Permitimos ver a todos (clientes ven lo suyo), pero editar solo a personal
        $this->middleware('auth');
        $this->middleware('role:admin,veterinario')->except(['index', 'show']);
    }

    /**
     * Muestra la lista de tratamientos.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->rol === 'cliente') {
            // Cliente: Ve tratamientos de SUS mascotas
            $tratamientos = Tratamiento::with(['mascota', 'veterinario'])
                ->whereHas('mascota', function($query) use ($user) {
                    $query->where('user_id', $user->id); // Corrección: user_id
                })
                ->orderBy('fecha_inicio', 'desc')
                ->paginate(10);
        } else {
            // Admin/Vet: Ven TODOS los tratamientos
            $tratamientos = Tratamiento::with(['mascota', 'veterinario'])
                ->orderBy('fecha_inicio', 'desc')
                ->paginate(15);
        }
            
        return view('tratamientos.index', compact('tratamientos'));
    }

    /**
     * Formulario de creación (Solo Admin/Vet).
     */
    public function create()
    {
        // Admin/Vet pueden crear tratamiento para CUALQUIER mascota
        $mascotas = Mascota::with('user')->get(); // Cargamos el dueño para mostrarlo en el select
        
        // Lista de medicamentos para el selector múltiple
        $medicamentos = Medicamento::orderBy('nombre')->get();
        
        return view('tratamientos.create', compact('mascotas', 'medicamentos'));
    }

    /**
     * Guardar Tratamiento y Medicamentos.
     */
    public function store(Request $request)
    {
        $request->validate([
            'mascota_id' => 'required|exists:mascota,id',
            'diagnostico' => 'required|string|max:500',
            'observaciones' => 'nullable|string',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
            
            // Validación de arrays para medicamentos (tabla pivote)
            'medicamentos' => 'nullable|array',
            'medicamentos.*' => 'exists:medicamento,id', // 'medicamento' en singular
            'dosis' => 'nullable|array',
            'frecuencia' => 'nullable|array',
            'duracion' => 'nullable|array',
        ]);

        // Creamos el tratamiento base
        $tratamiento = new Tratamiento($request->except(['medicamentos', 'dosis', 'frecuencia', 'duracion']));
        $tratamiento->veterinario_id = Auth::id(); // El veterinario logueado es el autor
        $tratamiento->save();

        // Guardamos los medicamentos en la tabla pivote
        if ($request->has('medicamentos')) {
            foreach ($request->medicamentos as $index => $medicamentoId) {
                if ($medicamentoId) {
                    $tratamiento->medicamentos()->attach($medicamentoId, [
                        'dosis' => $request->dosis[$index] ?? null,
                        'frecuencia' => $request->frecuencia[$index] ?? null,
                        'duracion' => $request->duracion[$index] ?? null,
                    ]);
                }
            }
        }

        return redirect()->route('tratamientos.index')
            ->with('success', 'Tratamiento creado exitosamente.');
    }

    /**
     * Ver detalles.
     */
    public function show($id)
    {
        $tratamiento = Tratamiento::with(['mascota', 'veterinario', 'medicamentos'])->findOrFail($id);
        $user = Auth::user();

        // Seguridad: Si es cliente, solo puede ver si es SU mascota
        if ($user->rol === 'cliente' && $tratamiento->mascota->user_id !== $user->id) {
            abort(403, 'No tienes permiso para ver este tratamiento.');
        }
        
        return view('tratamientos.show', compact('tratamiento'));
    }

    /**
     * Editar (Solo Admin/Vet).
     */
    public function edit($id)
    {
        $tratamiento = Tratamiento::with('medicamentos')->findOrFail($id);
        $mascotas = Mascota::with('user')->get();
        $medicamentos = Medicamento::orderBy('nombre')->get();
        
        return view('tratamientos.edit', compact('tratamiento', 'mascotas', 'medicamentos'));
    }

    /**
     * Actualizar.
     */
    public function update(Request $request, $id)
    {
        $tratamiento = Tratamiento::findOrFail($id);
        
        $request->validate([
            'diagnostico' => 'required|string|max:500',
            'observaciones' => 'nullable|string',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
            'medicamentos' => 'nullable|array',
        ]);

        // Actualizamos datos básicos
        $tratamiento->update($request->except(['medicamentos', 'dosis', 'frecuencia', 'duracion']));

        // Sincronizamos medicamentos (Sync es mejor que attach aquí, borra los viejos y pone los nuevos)
        $syncData = [];
        if ($request->has('medicamentos')) {
            foreach ($request->medicamentos as $index => $medicamentoId) {
                if ($medicamentoId) {
                    $syncData[$medicamentoId] = [
                        'dosis' => $request->dosis[$index] ?? null,
                        'frecuencia' => $request->frecuencia[$index] ?? null,
                        'duracion' => $request->duracion[$index] ?? null,
                    ];
                }
            }
        }
        $tratamiento->medicamentos()->sync($syncData);

        return redirect()->route('tratamientos.index')
            ->with('success', 'Tratamiento actualizado exitosamente.');
    }

    /**
     * Borrar (Solo Admin/Vet).
     */
    public function destroy($id)
    {
        $tratamiento = Tratamiento::findOrFail($id);
        $tratamiento->delete();

        return redirect()->route('tratamientos.index')
            ->with('success', 'Tratamiento eliminado exitosamente.');
    }
}
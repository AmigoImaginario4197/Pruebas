<?php

namespace App\Http\Controllers;

use App\Models\Tratamiento;
use App\Models\Mascota;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TratamientoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tratamientos = Tratamiento::with(['mascota', 'veterinario'])
            ->whereHas('mascota', function($query) {
                $query->where('usuario_id', Auth::id());
            })
            ->orderBy('fecha_inicio', 'desc')
            ->get();
            
        return view('tratamientos.index', compact('tratamientos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $mascotas = Mascota::where('usuario_id', Auth::id())->get();
        $veterinarios = User::where('rol', 'veterinario')->get();
        
        return view('tratamientos.create', compact('mascotas', 'veterinarios'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'mascota_id' => 'required|exists:mascota,id',
            'veterinario_id' => 'required|exists:users,id',
            'diagnostico' => 'required|string|max:500',
            'observaciones' => 'nullable|string',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'nullable|date|after:fecha_inicio',
        ]);

        // Verificar que la mascota pertenece al usuario
        $mascota = Mascota::findOrFail($request->mascota_id);
        if ($mascota->usuario_id !== Auth::id()) {
            abort(403);
        }

        Tratamiento::create($request->all());

        return redirect()->route('tratamientos.index')
            ->with('success', 'Tratamiento creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $tratamiento = Tratamiento::with(['mascota', 'veterinario', 'medicamentos'])
            ->findOrFail($id);
            
        // Verificar permisos
        if ($tratamiento->mascota->usuario_id !== Auth::id()) {
            abort(403);
        }
        
        return view('tratamientos.show', compact('tratamiento'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $tratamiento = Tratamiento::findOrFail($id);
        
        // Verificar permisos
        if ($tratamiento->mascota->usuario_id !== Auth::id()) {
            abort(403);
        }
        
        $mascotas = Mascota::where('usuario_id', Auth::id())->get();
        $veterinarios = User::where('rol', 'veterinario')->get();
        
        return view('tratamientos.edit', compact('tratamiento', 'mascotas', 'veterinarios'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $tratamiento = Tratamiento::findOrFail($id);
        
        // Verificar permisos
        if ($tratamiento->mascota->usuario_id !== Auth::id()) {
            abort(403);
        }
        
        $request->validate([
            'diagnostico' => 'required|string|max:500',
            'observaciones' => 'nullable|string',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'nullable|date|after:fecha_inicio',
        ]);

        $tratamiento->update($request->all());

        return redirect()->route('tratamientos.index')
            ->with('success', 'Tratamiento actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $tratamiento = Tratamiento::findOrFail($id);
        
        // Verificar permisos
        if ($tratamiento->mascota->usuario_id !== Auth::id()) {
            abort(403);
        }
        
        $tratamiento->delete();

        return redirect()->route('tratamientos.index')
            ->with('success', 'Tratamiento eliminado exitosamente.');
    }

    /**
     * Contar tratamientos activos del usuario
     */
    public function contarTratamientosActivos()
    {
        return Tratamiento::whereHas('mascota', function($query) {
            $query->where('usuario_id', Auth::id());
        })
        ->whereDate('fecha_inicio', '<=', Carbon::now())
        ->where(function($query) {
            $query->whereDate('fecha_fin', '>=', Carbon::now())
                  ->orWhereNull('fecha_fin');
        })
        ->count();
    }
}
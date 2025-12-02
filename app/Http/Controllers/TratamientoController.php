<?php

namespace App\Http\Controllers;

use App\Models\Tratamiento;
use App\Models\Mascota;
use App\Models\User;
use App\Models\Medicamento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class TratamientoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin,veterinario')->except(['index', 'show']);
    }

    public function index()
    {
        $user = Auth::user();
        
        if ($user->rol === 'cliente') {
            // Cliente ve tratamientos de sus mascotas
            $tratamientos = Tratamiento::with(['mascota', 'veterinario'])
                ->whereHas('mascota', function($query) use ($user) {
                    $query->where('user_id', $user->id);
                })
                ->orderBy('fecha_inicio', 'desc')
                ->paginate(10);
        } else {
            // Admin/Vet ven todos. CORRECCIÓN: 'mascota.usuario'
            $tratamientos = Tratamiento::with(['mascota.usuario', 'veterinario'])
                ->orderBy('fecha_inicio', 'desc')
                ->paginate(15);
        }
            
        return view('tratamientos.index', compact('tratamientos'));
    }

    public function create()
    {
        // CORRECCIÓN: 'with(\'usuario\')'
        $mascotas = Mascota::with('usuario')->orderBy('nombre')->get(); 
        $medicamentos = Medicamento::orderBy('nombre')->get();
        
        return view('tratamientos.create', compact('mascotas', 'medicamentos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'mascota_id' => 'required|exists:mascota,id',
            'diagnostico' => 'required|string|max:500',
            'observaciones' => 'nullable|string',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
            'medicamentos' => 'nullable|array',
            'medicamentos.*' => 'exists:medicamento,id',
            'dosis' => 'nullable|array',
            'frecuencia' => 'nullable|array',
            'duracion' => 'nullable|array',
        ]);

        $tratamiento = new Tratamiento($request->except(['medicamentos', 'dosis', 'frecuencia', 'duracion']));
        $tratamiento->veterinario_id = Auth::id();
        $tratamiento->save();

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

        return redirect()->route('tratamientos.index')->with('success', 'Tratamiento creado exitosamente.');
    }

    public function show($id)
    {
        $tratamiento = Tratamiento::with(['mascota', 'veterinario', 'medicamentos'])->findOrFail($id);
        $user = Auth::user();

        if ($user->rol === 'cliente' && $tratamiento->mascota->user_id !== $user->id) {
            abort(403, 'No tienes permiso para ver este tratamiento.');
        }
        
        return view('tratamientos.show', compact('tratamiento'));
    }

    public function edit($id)
    {
        $tratamiento = Tratamiento::with('medicamentos')->findOrFail($id);
        // CORRECCIÓN: 'with(\'usuario\')'
        $mascotas = Mascota::with('usuario')->orderBy('nombre')->get();
        $medicamentos = Medicamento::orderBy('nombre')->get();
        
        return view('tratamientos.edit', compact('tratamiento', 'mascotas', 'medicamentos'));
    }

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

        $tratamiento->update($request->except(['medicamentos', 'dosis', 'frecuencia', 'duracion']));

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

        return redirect()->route('tratamientos.index')->with('success', 'Tratamiento actualizado exitosamente.');
    }

    public function destroy($id)
    {
        $tratamiento = Tratamiento::findOrFail($id);
        $tratamiento->delete();
        return redirect()->route('tratamientos.index')->with('success', 'Tratamiento eliminado exitosamente.');
    }
}
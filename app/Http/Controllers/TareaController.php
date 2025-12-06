<?php

namespace App\Http\Controllers;

use App\Models\Tarea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TareaController extends Controller
{
    /**
     * Guarda una nueva tarea en la base de datos.
     */
    public function store(Request $request)
    {
        // 1. Validamos los datos que vienen del formulario
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'inicio' => 'required|date',
            'fin'    => 'required|date|after:inicio', // El fin debe ser después del inicio
            'color'  => 'nullable|string|max:20',
            'observaciones' => 'nullable|string',
        ]);

        // 2. Creamos la tarea usando el Modelo
        Tarea::create([
            'user_id' => Auth::id(),
            'titulo'  => $validated['titulo'],
            'inicio'  => $validated['inicio'],
            'fin'     => $validated['fin'],
            'color'   => $validated['color'] ?? '#6c757d', // Gris si no eligen color
            'observaciones' => $validated['observaciones'],
        ]);

        // 3. Redirigimos a la agenda con mensaje de éxito
        return redirect()->route('agenda.index')->with('success', 'Tarea interna agregada correctamente.');
    }

    /**
     * Actualiza una tarea (Útil si implementas Drag & Drop luego).
     */
    public function update(Request $request, $id)
    {
        $tarea = Tarea::findOrFail($id);

        // Validaciones simples
        $request->validate([
            'inicio' => 'required|date',
            'fin'    => 'required|date',
        ]);

        $tarea->update([
            'inicio' => $request->inicio,
            'fin'    => $request->fin,
           
        ]);

        if($request->ajax()) {
            return response()->json(['status' => 'success']);
        }

        return redirect()->route('agenda.index');
    }

    /**
     * Elimina una tarea.
     */
    public function destroy($id)
    {
        $tarea = Tarea::findOrFail($id);
        
        $tarea->delete();

        return redirect()->route('agenda.index')->with('success', 'Tarea eliminada.');
    }
}
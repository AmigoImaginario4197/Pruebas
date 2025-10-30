<?php

namespace App\Http\Controllers;

use App\Models\HistorialMedico;
use App\Models\Mascota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HistorialMedicoController extends Controller
{
    /**
     * Mostrar todos los historiales médicos de las mascotas del usuario autenticado.
     */
    public function mostrarHistorialesMedicos()
    {
        $mascotasUsuario = Mascota::where('usuario_id', Auth::id())->pluck('id');
        $historiales = HistorialMedico::whereIn('mascota_id', $mascotasUsuario)->get();

        return view('historial.index', compact('historiales'));
    }

    /**
     * Mostrar los historiales médicos de una mascota específica.
     */
    public function verHistorialPorMascota($mascota_id)
    {
        $mascota = Mascota::where('usuario_id', Auth::id())->findOrFail($mascota_id);
        $historiales = HistorialMedico::where('mascota_id', $mascota_id)->get();

        return view('historial.por_mascota', compact('mascota', 'historiales'));
    }

    /**
     * Mostrar formulario para crear un historial nuevo.
     */
    public function crearHistorialMedico()
    {
        $mascotas = Mascota::where('usuario_id', Auth::id())->get();
        return view('historial.create', compact('mascotas'));
    }

    /**
     * Guardar un historial médico nuevo.
     */
    public function guardarHistorialMedico(Request $request)
    {
        $request->validate([
            'mascota_id' => 'required|exists:mascotas,id',
            'tipo' => 'required|string|max:100',
            'descripcion' => 'required|string|max:255',
            'fecha' => 'required|date',
        ]);

        HistorialMedico::create([
            'mascota_id' => $request->mascota_id,
            'tipo' => $request->tipo,
            'descripcion' => $request->descripcion,
            'fecha' => $request->fecha,
        ]);

        return redirect()->route('historial.index')->with('success', 'Historial médico añadido correctamente.');
    }

    /**
     * Mostrar detalles de un historial médico.
     */
    public function mostrarHistorialMedico($id)
    {
        $historial = HistorialMedico::findOrFail($id);
        return view('historial.show', compact('historial'));
    }

    /**
     * Mostrar formulario para editar un historial médico.
     */
    public function editarHistorialMedico($id)
    {
        $historial = HistorialMedico::findOrFail($id);
        $mascotas = Mascota::where('usuario_id', Auth::id())->get();
        return view('historial.edit', compact('historial', 'mascotas'));
    }

    /**
     * Actualizar un historial médico existente.
     */
    public function actualizarHistorialMedico(Request $request, $id)
    {
        $historial = HistorialMedico::findOrFail($id);

        $request->validate([
            'tipo' => 'required|string|max:100',
            'descripcion' => 'required|string|max:255',
            'fecha' => 'required|date',
        ]);

        $historial->update($request->only(['tipo', 'descripcion', 'fecha']));

        return redirect()->route('historial.index')->with('success', 'Historial médico actualizado correctamente.');
    }

    /**
     * Eliminar un historial médico.
     */
    public function borrarHistorialMedico($id)
    {
        $historial = HistorialMedico::findOrFail($id);
        $historial->delete();

        return redirect()->route('historial.index')->with('success', 'Historial eliminado correctamente.');
    }
}

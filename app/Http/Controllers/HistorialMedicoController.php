<?php

namespace App\Http\Controllers;

use App\Models\HistorialMedico;
use App\Models\Mascota;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

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
        // Admin/Vet pueden añadir historial a cualquier mascota
        $mascotas = Mascota::with('usuario')->get();
        return view('historial.create', compact('mascotas'));
    }

    /**
     * Guarda un nuevo historial médico.
     */
    public function store(Request $request)
    {
        $request->validate([
            'mascota_id' => 'required|exists:mascota,id', // tabla 'mascota'
            'tipo' => 'required|string|max:100', // Ej: "Vacunación", "Consulta", "Cirugía"
            'descripcion' => 'required|string',
            'fecha' => 'required|date',
        ]);

        HistorialMedico::create($request->all());

        return redirect()->route('historial.index')->with('success', 'Historial médico añadido correctamente.');
    }

    /**
     * Muestra los detalles de un historial.
     */
    public function show($id)
    {
        $historial = HistorialMedico::with('mascota')->findOrFail($id);
        $user = Auth::user();

        // Seguridad: Si es cliente, verifica que la mascota sea suya
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
        $mascotas = Mascota::with('usuario')->get();
        
        return view('historial.edit', compact('historial', 'mascotas'));
    }

    /**
     * Actualiza el historial.
     */
    public function update(Request $request, $id)
    {
        $historial = HistorialMedico::findOrFail($id);

        $request->validate([
            'tipo' => 'required|string|max:100',
            'descripcion' => 'required|string',
            'fecha' => 'required|date',
        ]);

        $historial->update($request->only(['tipo', 'descripcion', 'fecha']));

        return redirect()->route('historial.index')->with('success', 'Historial médico actualizado.');
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
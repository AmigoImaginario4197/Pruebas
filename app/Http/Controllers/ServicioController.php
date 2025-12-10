<?php

namespace App\Http\Controllers;

use App\Models\Servicio;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ServicioController extends Controller
{
    /**
     * Constructor: Configuración de Permisos.
     */
    public function __construct()
    {
        // 1. Todos deben estar logueados
        $this->middleware('auth');

        // 2. PERMISOS DE LECTURA (Ver lista y detalles)
        // Permitimos pasar a Admin y Veterinario solo a estas dos funciones.
        $this->middleware('role:admin,veterinario')->only(['index', 'show']);

        // 3. PERMISOS DE GESTIÓN (Crear, Editar, Borrar)
        // Esto sigue siendo exclusivo del Admin.
        $this->middleware('role:admin')->only(['create', 'store', 'edit', 'update', 'destroy']);
    }

    /**
     * Muestra la lista de servicios.
     */
    public function index()
    {
        $servicios = Servicio::orderBy('nombre')->paginate(10);
        return view('servicios.index', compact('servicios'));
    }

    /**
     * Muestra los detalles de un servicio específico.
     */
    public function show(Servicio $servicio)
    {
        return view('servicios.show', compact('servicio'));
    }

    /**
     * Muestra el formulario de creación.
     */
    public function create()
    {
        return view('servicios.create');
    }

    /**
     * Guarda el nuevo servicio en la base de datos.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255|unique:servicio',
            'descripcion' => 'nullable|string|max:500',
            'precio' => 'required|numeric|min:0',
        ]);

        Servicio::create($validated);

        return redirect()->route('servicios.index')->with('success', 'Servicio creado correctamente.');
    }

    /**
     * Muestra el formulario de edición.
     */
    public function edit(Servicio $servicio)
    {
        return view('servicios.edit', compact('servicio'));
    }

    /**
     * Actualiza el servicio en la base de datos.
     */
    public function update(Request $request, Servicio $servicio)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255|unique:servicio,nombre,' . $servicio->id,
            'descripcion' => 'nullable|string|max:500',
            'precio' => 'required|numeric|min:0',
        ]);

        $servicio->update($validated);

        return redirect()->route('servicios.index')->with('success', 'Servicio actualizado correctamente.');
    }

    /**
     * Elimina el servicio.
     */
    public function destroy(Servicio $servicio)
    {
        $servicio->delete();
        return back()->with('success', 'Servicio eliminado correctamente.');
    }
}
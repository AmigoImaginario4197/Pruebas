<?php

namespace App\Http\Controllers;

use App\Models\Servicio;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ServicioController extends Controller
{
    /**
     * Muestra los detalles de un servicio específico.
     */
    public function show(Servicio $servicio)
{
    return view('servicios.show', compact('servicio'));
}
    /**
     * Constructor: Solo el administrador puede acceder a cualquier método de este controlador.
     */
    public function __construct()
    {
        $this->middleware('role:admin');
    }

    /**
     * Muestra la lista de servicios.
     */
    public function index()
    {
        // Ordenamos por nombre y paginamos
        $servicios = Servicio::orderBy('nombre')->paginate(10);
        return view('servicios.index', compact('servicios'));
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
            // Usamos 'servicio' en singular porque así se llama tu tabla
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
            // Ignoramos el ID actual para la validación de 'unique'
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
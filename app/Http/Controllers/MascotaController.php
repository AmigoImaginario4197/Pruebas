<?php

namespace App\Http\Controllers;

use App\Models\Mascota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class MascotaController extends Controller
{
   /**
     * Muestra una lista de las mascotas del usuario autenticado.
     */
    public function index()
    {
        $user = Auth::user();
 
        $mascotas = Mascota::where('user_id', Auth::id())->latest()->get();

        return view('mascotas.index', compact('mascotas'));
    }

    /**
     * Muestra el formulario para crear una nueva mascota.
     */
    public function create()
    {
        return view('mascotas.create');
    }

    /**
     * Almacena una nueva mascota en la base de datos.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
            'especie' => ['required', Rule::in(['Perro', 'Gato', 'Hamster', 'Conejo'])],
            'raza' => 'nullable|string|max:255',
            'edad' => 'nullable|integer|min:0',
            'peso' => 'nullable|numeric|min:0',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'fecha_nacimiento' => 'nullable|date',
        ]);

        if ($request->hasFile('foto')) {
            $rutaFoto = $request->file('foto')->store('fotos_mascotas', 'public');
            $validatedData['foto'] = $rutaFoto;
        }

        $validatedData['user_id'] = Auth::id();
        
        Mascota::create($validatedData);

        return redirect()->route('mascotas.index')->with('success', '¡Mascota registrada con éxito!');
    }

    /**
     * Muestra los detalles de una mascota específica en una página completa.
     */
    public function show(Mascota $mascota)
    {
        // Verificamos que la mascota pertenezca al usuario autenticado 
        if ($mascota->user_id !== Auth::id()) {
            abort(403, 'Acción no autorizada.');
        }

        return view('mascotas.show', compact('mascota'));
    }

    /**
     * Muestra el formulario para editar una mascota existente.
     */
    public function edit(Mascota $mascota)
    {
        // Verificamos que la mascota pertenezca al usuario autenticado 
        if ($mascota->user_id !== Auth::id()) {
            abort(403, 'Acción no autorizada.');
        }

        return view('mascotas.edit', compact('mascota'));
    }

    /**
     * Actualiza una mascota específica en la base de datos.
     */
    public function update(Request $request, Mascota $mascota)
    {
        // Verificamos que la mascota pertenezca al usuario autenticado (Seguridad)
        if ($mascota->user_id !== Auth::id()) {
            abort(403, 'Acción no autorizada.');
        }

        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
            'especie' => ['required', Rule::in(['Perro', 'Gato', 'Hamster', 'Conejo'])],
            'raza' => 'nullable|string|max:255',
            'edad' => 'nullable|integer|min:0',
            'peso' => 'nullable|numeric|min:0',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'fecha_nacimiento' => 'nullable|date',
        ]);

        if ($request->hasFile('foto')) {
            // Si hay una foto nueva, primero borramos la antigua si existe
            if ($mascota->foto) {
                Storage::disk('public')->delete($mascota->foto);
            }
            // Guardamos la nueva foto
            $rutaFoto = $request->file('foto')->store('fotos_mascotas', 'public');
            $validatedData['foto'] = $rutaFoto;
        }

        $mascota->update($validatedData);

        return redirect()->route('mascotas.index')->with('success', '¡Datos de la mascota actualizados!');
    }

    /**
     * Elimina una mascota específica de la base de datos.
     */
    public function destroy(Mascota $mascota)
    {
        // Verificamos que la mascota pertenezca al usuario autenticado (Seguridad)
        if ($mascota->user_id !== Auth::id()) {
            abort(403, 'Acción no autorizada.');
        }

        // Si la mascota tiene una foto asociada, la eliminamos del almacenamiento
        if ($mascota->foto) {
            Storage::disk('public')->delete($mascota->foto);
        }

        // Eliminamos el registro de la base de datos
        $mascota->delete();

        return redirect()->route('mascotas.index')->with('success', '¡Mascota eliminada con éxito!');
    }

}
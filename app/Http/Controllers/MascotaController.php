<?php

namespace App\Http\Controllers;

use App\Models\Mascota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule; // Importante para la validación de la especie

class MascotaController extends Controller
{
    // ... (El método index() que ya tienes se queda igual) ...
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $mascotas = $user->mascotas()->latest()->get();
        return view('mascotas.index', compact('mascotas'));
    }

    /**
     * Muestra el formulario para crear una nueva mascota.
     */
    public function create()
    {
        // Simplemente devolvemos la vista con el formulario.
        return view('mascotas.create');
    }

    /**
     * Almacena una nueva mascota en la base de datos.
     */
    public function store(Request $request)
    {
        // 1. Validación de los datos del formulario
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
            'especie' => ['required', Rule::in(['Perro', 'Gato', 'Hamster', 'Conejo'])],
            'raza' => 'nullable|string|max:255',
            'edad' => 'nullable|integer|min:0',
            'peso' => 'nullable|numeric|min:0',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Valida que sea una imagen
            'fecha_nacimiento' => 'nullable|date',
        ]);

        // 2. Manejo de la subida de la foto
        if ($request->hasFile('foto')) {
            // Guarda la imagen en 'storage/app/public/fotos_mascotas' y obtiene la ruta
            $rutaFoto = $request->file('foto')->store('fotos_mascotas', 'public');
            $validatedData['foto'] = $rutaFoto;
        }

        // 3. Añadir el ID del propietario (el usuario logueado)
        $validatedData['user_id'] = Auth::id();

        // 4. Crear la mascota en la base de datos
        Mascota::create($validatedData);

        // 5. Redirigir al listado de mascotas con un mensaje de éxito
        return redirect()->route('mascotas.index')->with('success', '¡Mascota registrada con éxito!');
    }

    // ... (El resto de métodos show, edit, update, destroy, etc.) ...
}
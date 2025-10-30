<?php

namespace App\Http\Controllers;

use App\Models\Mascota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MascotaController extends Controller
{
    /**
     * Mostramos todas las mascotas del usuario autenticado.
     */
    public function mostrarMascotas()
    {
        $mascotas = Mascota::where('usuario_id', Auth::id())->get();
        return view('mascotas.index', compact('mascotas'));
    }

    /**
     * Mostramos el formulario para crear una nueva mascota.
     */
    public function crearMascota()
    {
        return view('mascotas.create');
    }

    /**
     * Guardamos una nueva mascota en la base de datos.
     */
    public function guardarMascota(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'especie' => 'required|string|max:100',
            'raza' => 'nullable|string|max:100',
            'edad' => 'nullable|integer',
            'peso' => 'nullable|numeric',
            'foto' => 'nullable|image|max:2048',
        ]);

        $fotoPath = $request->hasFile('foto')
            ? $request->file('foto')->store('mascotas', 'public')
            : null;

        Mascota::create([
            'nombre' => $request->nombre,
            'especie' => $request->especie,
            'raza' => $request->raza,
            'edad' => $request->edad,
            'peso' => $request->peso,
            'foto' => $fotoPath,
            'usuario_id' => Auth::id(),
        ]);

        return redirect()->route('mascotas.index')->with('success', 'Mascota registrada con éxito.');
    }

    /**
     * Mostramos los detalles de una mascota.
     */
    public function mostrarDatosMascota($id)
    {
        $mascota = Mascota::findOrFail($id);
        return view('mascotas.show', compact('mascota'));
    }

    /**
     * Mostramos el formulario para editar una mascota.
     */
    public function editarMascota($id)
    {
        $mascota = Mascota::findOrFail($id);
        return view('mascotas.edit', compact('mascota'));
    }

    /**
     * Actualizamos los datos de una mascota.
     */
    public function actualizarMascota(Request $request, $id)
    {
        $mascota = Mascota::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:100',
            'especie' => 'required|string|max:100',
            'raza' => 'nullable|string|max:100',
            'edad' => 'nullable|integer',
            'peso' => 'nullable|numeric',
            'foto' => 'nullable|image|max:2048',
        ]);

        $data = $request->only(['nombre', 'especie', 'raza', 'edad', 'peso']);

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('mascotas', 'public');
        }

        $mascota->update($data);

        return redirect()->route('mascotas.index')->with('success', 'Mascota actualizada correctamente.');
    }

    /**
     * Eliminamos una mascota.
     */
    public function borrarMascota($id)
    {
        $mascota = Mascota::findOrFail($id);
        $mascota->delete();

        return redirect()->route('mascotas.index')->with('success', 'Mascota eliminada con éxito.');
    }
}

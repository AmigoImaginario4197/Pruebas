<?php

namespace App\Http\Controllers;

use App\Models\Mascota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Models\User;

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
    $user = Auth::user();
    $clientes = [];

    // Usamos comparación directa de rol para evitar errores
    if ($user->rol === 'veterinario' || $user->rol === 'admin') {
        // Cargamos los usuarios con rol cliente para el select
        $clientes = User::where('rol', 'cliente')->orderBy('name')->get();
    }

    return view('mascotas.create', compact('clientes'));
}

/**
 * Guarda la mascota en la base de datos.
 */
public function store(Request $request)
{
    $user = Auth::user();
    $esPersonal = ($user->rol === 'veterinario' || $user->rol === 'admin');

    // 1. Reglas de validación base
    $rules = [
        'nombre' => 'required|string|max:255',
        'especie' => ['required', Rule::in(['Perro', 'Gato', 'Hamster', 'Conejo'])],
        'raza' => 'nullable|string|max:255',
        'edad' => 'nullable|integer|min:0',
        'peso' => 'nullable|numeric|min:0',
        'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'fecha_nacimiento' => 'nullable|date',
    ];

    // 2. Si es personal médico, validamos que haya elegido un dueño
    if ($esPersonal) {
        $rules['user_id'] = 'required|exists:users,id';
    }

    $validatedData = $request->validate($rules);

    // 3. Procesar la foto (Tu código original)
    if ($request->hasFile('foto')) {
        $rutaFoto = $request->file('foto')->store('fotos_mascotas', 'public');
        $validatedData['foto'] = $rutaFoto;
    }

    // 4. Asignar el user_id correcto
    if ($esPersonal) {
        // Si es vet/admin, usamos el dueño que seleccionaron en el formulario
        // (El valor ya viene en $validatedData porque lo validamos arriba en $rules['user_id'])
    } else {
        // Si es cliente, se asigna a sí mismo
        $validatedData['user_id'] = $user->id;
    }

    Mascota::create($validatedData);

    // 5. Redirección inteligente
    if ($user->rol === 'veterinario') {
        return redirect()->route('veterinario.mascotas.index')->with('success', 'Paciente registrado con éxito.');
    }
    return redirect()->route('mascotas.index')->with('success', '¡Mascota registrada con éxito!');
}
    /**
     * Muestra los detalles de una mascota específica en una página completa.
     */
    public function show(Mascota $mascota)
{
    $user = Auth::user();
    
    $esAdmin = $user->rol === 'admin'; 
    $esVeterinario = $user->rol === 'veterinario'; 
    $esDueño = $mascota->user_id === $user->id;

    if (!$esAdmin && !$esVeterinario && !$esDueño) {
        abort(403, 'No tienes permiso para ver la ficha de este paciente.');
    }

    return view('mascotas.show', compact('mascota'));
}

    /**
     * Muestra el formulario para editar una mascota existente.
     */
    public function edit(Mascota $mascota)
{
    $user = Auth::user();

    if ($user->rol !== 'admin' && $mascota->user_id !== $user->id) {
        abort(403, 'Acción no autorizada. Solo el dueño o un administrador pueden editar esta ficha.');
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

    public function indexVeterinario(Request $request)
    {
        // Iniciamos la consulta cargando al dueño (user) para optimizar
        $query = Mascota::with('usuario');

        // Lógica del Buscador
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            
            $query->where(function($q) use ($search) {
                // Busca por nombre de la mascota...
                $q->where('nombre', 'LIKE', "%{$search}%")
                  // ... O por datos del dueño (Nombre o NIF)
                  ->orWhereHas('usuario', function($userQuery) use ($search) {
                      $userQuery->where('name', 'LIKE', "%{$search}%")
                                ->orWhere('nif', 'LIKE', "%{$search}%");
                  });
            });
        }

        // Ordenamos por los creados más recientemente y paginamos
        $mascotas = $query->latest()->paginate(12)->withQueryString();

        return view('veterinario.mascotas.index', compact('mascotas'));
    }

}
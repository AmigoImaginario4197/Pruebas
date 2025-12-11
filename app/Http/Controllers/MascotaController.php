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

        // 1. REGLAS DE VALIDACIÓN (Ahora raza, fecha y peso son OBLIGATORIOS)
        $rules = [
            'nombre' => 'required|string|max:255',
            'especie' => ['required', Rule::in(['Perro', 'Gato', 'Hamster', 'Conejo', 'Ave', 'Otro'])],
            
            // CAMBIO IMPORTANTE: 'nullable' -> 'required'
            'raza' => 'required|string|max:255',
            'fecha_nacimiento' => 'required|date|before_or_equal:today', // No permite fechas futuras
            'peso' => 'required|numeric|min:0.1', // Peso mínimo lógico
            
            // La edad puede ser nullable porque la calculamos o viene del form
            'edad' => 'nullable|integer|min:0',
            
            // Foto sigue siendo opcional
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];

        // 2. Si es personal médico, OBLIGAMOS a que elijan un dueño
        if ($esPersonal) {
            $rules['user_id'] = 'required|exists:users,id';
        }

        // Ejecutamos la validación
        $validatedData = $request->validate($rules);

        // 3. Procesar la foto
        if ($request->hasFile('foto')) {
            // Guardamos en 'mascotas' (asegúrate de que coincida con tu filesystem)
            $rutaFoto = $request->file('foto')->store('mascotas', 'public');
            $validatedData['foto'] = $rutaFoto;
        }

        // 4. Asignar el user_id correcto
        if (!$esPersonal) {
            // Si es un cliente normal, forzamos que el dueño sea él mismo.
            // (Si es personal, el user_id ya viene dentro de $validatedData gracias a la validación anterior)
            $validatedData['user_id'] = $user->id;
        }

        // 5. Crear la mascota
        Mascota::create($validatedData);

        // 6. Redirección Inteligente
        // Admin y Veterinario van al panel de gestión
        if ($esPersonal) {
            return redirect()->route('veterinario.mascotas.index')
                ->with('success', 'Paciente registrado correctamente en el sistema.');
        }

        // Clientes vuelven a su lista personal
        return redirect()->route('mascotas.index')
            ->with('success', '¡Has añadido una nueva mascota a tu familia!');
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

        // Si NO es admin Y TAMPOCO es el dueño -> Bloqueamos.
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
        $user = Auth::user();

        // 1. CORRECCIÓN DE PERMISOS:
        // Si NO es admin Y TAMPOCO es el dueño -> Error 403.
        // (Esto permite que el admin pase).
        if ($user->rol !== 'admin' && $mascota->user_id !== $user->id) {
            abort(403, 'Acción no autorizada.');
        }

        // 2. Validación
        $request->validate([
            'nombre' => 'required|string|max:255',
            'especie' => 'required|string',
            'foto' => 'nullable|image|max:2048', // Máximo 2MB
            // Agrega aquí el resto de validaciones (raza, peso, etc.)
        ]);

        // 3. Actualizar datos
        $mascota->nombre = $request->nombre;
        $mascota->especie = $request->especie;
        $mascota->raza = $request->raza;
        $mascota->fecha_nacimiento = $request->fecha_nacimiento;
        $mascota->edad = $request->edad;
        $mascota->peso = $request->peso;

        // 4. Manejo de la Foto (Si subieron una nueva)
        if ($request->hasFile('foto')) {
            // Borrar la vieja si existe
            if ($mascota->foto) {
                Storage::disk('public')->delete($mascota->foto);
            }
            // Guardar la nueva
            $mascota->foto = $request->file('foto')->store('mascotas', 'public');
        }

        $mascota->save();

        // 5. Redirección inteligente
        $ruta = ($user->rol === 'admin' || $user->rol === 'veterinario') 
            ? 'veterinario.mascotas.index' 
            : 'mascotas.index';

        return redirect()->route($ruta)->with('success', 'Mascota actualizada correctamente.');
    }

    /**
     * Elimina una mascota específica de la base de datos.
     */
        public function destroy(Mascota $mascota)
    {
        $user = Auth::user();

        // 1. Verificación de seguridad (Admin o Dueño)
        if ($user->rol !== 'admin' && $mascota->user_id !== $user->id) {
            abort(403, 'Acción no autorizada.');
        }

        // 2. Eliminar foto si existe
        if ($mascota->foto) {
            Storage::disk('public')->delete($mascota->foto);
        }

        // 3. Eliminar registro
        $mascota->delete();

        // 4. Lógica de Redirección (Tal como la pediste)
        if ($user->rol === 'admin') {
            return redirect()->route('veterinario.mascotas.index')
                ->with('success', 'Mascota eliminada por Administración.');
        } else {
            return redirect()->route('mascotas.index')
                ->with('success', 'Mascota eliminada correctamente.');
        }
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
<?php

// ARCHIVO: app/Http/Controllers/UserController.php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Muestra la lista de usuarios con búsqueda, filtro y paginación dinámica.
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Filtro de búsqueda por nombre o email
        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('email', 'like', '%' . $searchTerm . '%');
            });
        }

        // Filtro por rol
        if ($request->filled('rol')) {
            $query->where('rol', $request->input('rol'));
        }
        
        // Paginación dinámica (lee el valor del selector 'per_page' o usa 15 por defecto)
        $perPage = $request->input('per_page', 15);
        $users = $query->orderBy('name', 'asc')->paginate($perPage)->withQueryString();

        return view('users.index', compact('users'));
    }

    /**
     * Muestra el formulario para crear un nuevo usuario.
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Guarda un nuevo usuario en la base de datos.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'rol' => 'required|in:cliente,veterinario,admin',
            'telefono' => 'nullable|string|max:20',
            'nif' => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:255',
            // La especialidad es obligatoria solo si el rol es 'veterinario'.
            'especialidad' => 'nullable|string|max:255|required_if:rol,veterinario',
        ]);

        // Medida de seguridad: si el rol no es 'veterinario', la especialidad se guarda como nulo.
        if ($request->rol !== 'veterinario') {
            $validatedData['especialidad'] = null;
        }

        // Hasheamos la contraseña antes de crear el usuario.
        $validatedData['password'] = Hash::make($validatedData['password']);

        // Creamos el usuario con los datos validados.
        User::create($validatedData);

        return redirect()->route('users.index')->with('success', 'Usuario creado correctamente.');
    }

    /**
     * Muestra los detalles de un usuario específico.
     */
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    /**
     * Muestra el formulario para editar un usuario.
     */
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    /**
     * Actualiza un usuario existente en la base de datos.
     * Incluye la lógica para no cambiar la contraseña si se deja en blanco.
     */
    public function update(Request $request, User $user)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed', // 'nullable' es clave para no requerir la contraseña.
            'rol' => 'required|in:cliente,veterinario,admin',
            'telefono' => 'nullable|string|max:20',
            'nif' => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:255',
            'especialidad' => 'nullable|string|max:255|required_if:rol,veterinario',
        ]);

        // ========================================================
        //              SOLUCIÓN PARA LA ACTUALIZACIÓN DE CONTRASEÑA
        // ========================================================
        // 1. Asignamos todos los campos del request EXCEPTO la contraseña.
        //    Esto evita que un campo de contraseña vacío se convierta en 'null'.
        $user->fill($request->except('password'));

        // 2. Medida de seguridad: si el rol cambia, borramos la especialidad si no es veterinario.
        if ($request->rol !== 'veterinario') {
            $user->especialidad = null;
        }

        // 3. Actualizamos la contraseña SÓLO si el campo de contraseña del formulario no está vacío.
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // 4. Guardamos el modelo con todos los cambios.
        $user->save();
        
        return redirect()->route('users.index')->with('success', 'Usuario actualizado correctamente.');
    }

    /**
     * Elimina un usuario de la base de datos.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'Usuario eliminado correctamente.');
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * ====================================================================
     * MÉTODO INDEX: MUESTRA LA LISTA DE USUARIOS
     * ====================================================================
     * Este método es el corazón de la funcionalidad de listado.
     * Recibe los datos del formulario de búsqueda y filtra los resultados.
     */
    public function index(Request $request)
    {
        // 1. Inicia una consulta Eloquent. Esto nos permite añadir condiciones dinámicamente.
        $query = User::query();

        // 2. Aplica el filtro de búsqueda por nombre o email.
        // Se activa solo si el campo 'search' del formulario tiene contenido.
        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            // Agrupamos las condiciones con un 'where' anónimo para que funcionen como (A OR B).
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('email', 'like', '%' . $searchTerm . '%');
            });
        }

        // 3. Aplica el filtro por rol.
        // ====> ESTA ES LA CORRECCIÓN PRINCIPAL PARA TU ERROR SQL <====
        // Usamos 'rol' porque así se llama la columna en tu base de datos.
        if ($request->filled('rol')) {
            $query->where('rol', $request->input('rol'));
        }
        
        // 4. Ejecuta la consulta final.
        // Ordena los resultados por nombre y los divide en páginas de 10.
        // withQueryString() es CRUCIAL: mantiene los filtros (?search=...&rol=...) en los enlaces de paginación.
        $users = $query->orderBy('name', 'asc')->paginate(10)->withQueryString();

        // 5. Devuelve la vista y le pasa la colección de usuarios ya filtrada y paginada.
        return view('users.index', compact('users'));
    }

    /**
     * ====================================================================
     * MÉTODO CREATE: MUESTRA EL FORMULARIO DE CREACIÓN
     * ====================================================================
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * ====================================================================
     * MÉTODO STORE: GUARDA UN NUEVO USUARIO
     * ====================================================================
     */
    public function store(Request $request)
    {
        // 1. Valida los datos recibidos del formulario.
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed', // 'confirmed' busca un campo 'password_confirmation'
            'rol' => 'required|in:cliente,veterinario,admin', // Valida que el rol sea uno de los permitidos.
        ]);

        // 2. Crea el usuario en la base de datos.
        User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']), // Hashea la contraseña por seguridad.
            'rol' => $validatedData['rol'],
        ]);

        // 3. Redirige al listado de usuarios con un mensaje de éxito.
        return redirect()->route('admin.usuarios.index')->with('success', 'Usuario creado correctamente.');
    }

    /**
     * ====================================================================
     * MÉTODO SHOW: MUESTRA LOS DETALLES DE UN USUARIO
     * ====================================================================
     * Gracias al "Route-Model Binding" (User $user), Laravel busca el usuario por su ID automáticamente.
     */
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    /**
     * ====================================================================
     * MÉTODO EDIT: MUESTRA EL FORMULARIO DE EDICIÓN
     * ====================================================================
     */
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    /**
     * ====================================================================
     * MÉTODO UPDATE: ACTUALIZA UN USUARIO EXISTENTE
     * ====================================================================
     */
    public function update(Request $request, User $user)
    {
        // 1. Valida los datos recibidos.
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required', 'string', 'email', 'max:255',
                // Permite que el usuario actual mantenga su propio email sin que falle la validación 'unique'.
                Rule::unique('users')->ignore($user->id),
            ],
            'password' => 'nullable|string|min:8|confirmed', // 'nullable' permite dejar la contraseña en blanco.
            'rol' => 'required|in:cliente,veterinario,admin',
        ]);

        // 2. Asigna los nuevos valores al modelo.
        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];
        $user->rol = $validatedData['rol'];

        // 3. Actualiza la contraseña SÓLO si se ha proporcionado una nueva.
        if (!empty($validatedData['password'])) {
            $user->password = Hash::make($validatedData['password']);
        }

        // 4. Guarda los cambios en la base de datos.
        $user->save();
        
        // 5. Redirige al listado con un mensaje de éxito.
        return redirect()->route('admin.usuarios.index')->with('success', 'Usuario actualizado correctamente.');
    }

    /**
     * ====================================================================
     * MÉTODO DESTROY: ELIMINA UN USUARIO
     * ====================================================================
     */
    public function destroy(User $user)
    {
        // 1. Elimina el usuario de la base de datos.
        $user->delete();

        // 2. Redirige al listado con un mensaje de éxito.
        return redirect()->route('admin.usuarios.index')->with('success', 'Usuario eliminado correctamente.');
    }
}
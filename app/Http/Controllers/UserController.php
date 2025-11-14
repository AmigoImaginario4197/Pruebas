<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Mostramos la lista de usuarios (solo para administrador).
     */
    public function mostrarUsuarios()
    {
        if (Auth::user()->rol !== 'admin') {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }

        $usuarios = User::all();
        return view('usuarios.index', compact('usuarios'));
    }

    /**
     * Mostramos los datos del usuario autenticado.
     */
    public function mostrarDatosUsuario()
    {
        $usuario = Auth::user();
        return view('usuarios.show', compact('usuario'));
    }

    /**
     * Mostramos formulario de edición del perfil.
     */
    public function editarUsuario()
    {
        $usuario = Auth::user();
        return view('usuarios.edit', compact('usuario'));
    }

    /**
     * Actualizamos los datos del usuario autenticado.
     */
    public function actualizarUsuario(Request $request)
{
    $usuario = Auth::user();

    $request->validate([
        'name' => 'required|string|max:255',
        'email' => "required|email|unique:users,email,{$usuario->id}",
        'direccion' => 'nullable|string|max:255',
        'telefono' => 'nullable|string|max:20',
        'password' => 'nullable|min:6|confirmed',
    ]);

    $data = $request->only(['name', 'email', 'direccion', 'telefono']);

    if ($request->filled('password')) {
        $data['password'] = Hash::make($request->password);
    }

    User::where('id', $usuario->id)->update($data);

    return redirect()->route('perfil.show')->with('success', 'Perfil actualizado correctamente.');
}
    /**
     * Eliminamos una cuenta (solo si el usuario es administrador o el propietario).
     */
    public function eliminarUsuario($id)
    {
        $usuario = User::findOrFail($id);

        if (Auth::id() !== $usuario->id && Auth::user()->rol !== 'admin') {
            abort(403, 'No tienes permiso para eliminar este usuario.');
        }

        $usuario->delete();

        return redirect()->route('usuarios.index')->with('success', 'Usuario eliminado correctamente.');
    }
}

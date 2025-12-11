<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Validation\Rule;
use App\Models\User;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

  /**
     * Update the user's profile information.
     */
    public function update(Request $request): RedirectResponse
    {
        // 1. AQUI DEFINIMOS LAS REGLAS (Validación)
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            
            // Regla de email única (ignora el ID del usuario actual para que no de error consigo mismo)
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique(User::class)->ignore($request->user()->id)],

            // Teléfono: solo números, entre 9 y 15 dígitos
            'telefono' => ['required', 'numeric', 'digits_between:9,15'],
            
            // Regex para NIF (8 números y una letra al final)
            'nif' => ['required', 'string', 'size:9', 'regex:/^[0-9]{8}[A-Za-z]$/'], 
            
            'direccion' => ['required', 'string', 'max:255'],
        ], [
            // Mensajes de error personalizados (Opcional, para que se entienda mejor)
            'telefono.numeric' => 'El teléfono solo debe contener números.',
            'nif.regex' => 'El NIF debe tener 8 números y una letra (Ej: 12345678Z).',
        ]);

        // 2. LLENAMOS EL MODELO CON LOS DATOS VALIDADOS
        $request->user()->fill($validated);

        // Si cambió el email, reseteamos la verificación
        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        // 3. GUARDAMOS EN LA BASE DE DATOS
        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

        /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $user = $request->user();

        // 1. Limpieza manual de relaciones (Para evitar errores de SQL)
        // Si tienes estas relaciones definidas en tu modelo User, esto evitará el bloqueo.
        // Usa try-catch por si alguna relación no existe o falla, que no detenga el proceso.
        try {
            // Borra sus mascotas (y citas asociadas si están en cascada)
            $user->mascotas()->delete(); 
            
            // Borra sus pagos (Opcional: Si quieres mantener los pagos por contabilidad, comenta esta línea)
            $user->pagos()->delete(); 

            // Borra sus citas directas (si las hubiera)
            // $user->citas()->delete(); 
        } catch (\Exception $e) {
            // Si falla algo al borrar hijos, continuamos para intentar borrar al padre de todas formas
        }

        // 2. Cerramos sesión
        Auth::logout();

        // 3. Borramos al usuario
        // Si tienes SoftDeletes activado en el modelo, esto solo lo marca como borrado.
        // Si quieres borrarlo de verdad de la BD, usa: $user->forceDelete();
        $user->delete(); 

        // 4. Invalidamos sesión
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // 5. Adiós
        return Redirect::to('/');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log; // Importa Log para depurar
use App\Mail\ContactoFormMail;

class ContactoController extends Controller
{
    public function show()
    {
        return view('contacto');
    }

    public function enviar(Request $request)
    {
        $details = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100',
            'message' => 'required|string|min:10',
        ]);

        try {
            Mail::to('petcareoficialtm@gmail.com')->send(new ContactoFormMail($details));
        } catch (\Exception $e) {
            // Registra el error en los logs de Laravel (storage/logs/laravel.log)
            Log::error('Fallo en el envío de correo de contacto: ' . $e->getMessage());
            
            // Redirige de vuelta con un mensaje de error
            return redirect()->route('contacto')
                             ->with('error', 'Hubo un problema al enviar tu mensaje. Inténtalo más tarde.');
        }

        // Redirige de vuelta con un mensaje de éxito
        return redirect()->route('contacto')
                         ->with('success', '¡Mensaje enviado! Te responderemos pronto.');
    }
}
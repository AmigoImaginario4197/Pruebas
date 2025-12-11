<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Pago; 
use Stripe\Stripe;
use Stripe\Charge; 

class PagoController extends Controller
{
    public function index()
    {
        return view('pagos.index'); 
    }

    public function store(Request $request)
    {
        // 1. Validamos los datos
        $request->validate([
            'monto' => 'required|numeric|min:1', 
            'stripeToken' => 'required',
        ]);

        // 2. Configuración de Stripe
        Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            // 3. Procesar el cobro en Stripe
            $charge = Charge::create([
                "amount" => $request->monto * 100, 
                "currency" => "eur",
                "source" => $request->stripeToken,
                "description" => "Pago ID Usuario: " . Auth::id()
            ]);

            $pago = new Pago();
            
            // Relación con el Usuario (Coincide con tu Model y BD)
            $pago->user_id = Auth::id();
            
            // Datos del pago
            $pago->monto = $request->monto;
            $pago->estado = 'completado';
            
            // ID de transacción 
            $pago->stripe_id = $charge->id; 

            // Relación con Cita 
            // Si el formulario envía un 'cita_id', lo guardamos. Si no, se queda NULL.
            if ($request->has('cita_id')) {
                $pago->cita_id = $request->cita_id;
            }

            $pago->save();

            return redirect()
                ->route('dashboard')
                ->with('success', 'Pago realizado correctamente.');

        } catch (\Exception $e) {
            return back()->with('error', 'Error al procesar el pago: ' . $e->getMessage());
        }
    }
}
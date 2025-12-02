<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Mascota;
use App\Models\User;
use App\Models\Servicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Carbon\Carbon;

class CitaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        $citas = collect();

        if ($user->rol === 'admin') {
            $citas = Cita::with(['mascota', 'veterinario', 'cliente', 'servicio'])->orderBy('fecha_hora', 'desc')->paginate(15);
        } elseif ($user->rol === 'veterinario') {
            $citas = Cita::where('veterinario_id', $user->id)->with('servicio')->orderBy('fecha_hora', 'asc')->paginate(15);
        } else {
            $citas = Cita::where('user_id', $user->id)->with('servicio')->orderBy('fecha_hora', 'desc')->paginate(10);
        }

        return view('citas.index', compact('citas'));
    }

    public function create()
    {
        $user = Auth::user();
        $veterinarios = User::where('rol', 'veterinario')->get();
        $servicios = Servicio::all(); 
        $mascotas = collect();

        if ($user->rol === 'cliente') {
            $mascotas = Mascota::where('user_id', $user->id)->get();
            if ($mascotas->isEmpty()) {
                return redirect()->route('mascotas.create')->with('error', 'Primero debes registrar una mascota para agendar una cita.');
            }
        } else {
            $mascotas = Mascota::with('usuario')->get(); 
        }

        return view('citas.create', compact('mascotas', 'veterinarios', 'servicios'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'mascota_id'     => 'required|exists:mascota,id',
            'veterinario_id' => 'required|exists:users,id',
            'servicio_id'    => 'required|exists:servicio,id',
            'fecha_hora'     => 'required|date|after:now', 
            'motivo'         => 'required|string|max:255',
        ]);

        // Validación de conflictos de horario
        $inicio = Carbon::parse($validated['fecha_hora']);
        $fin = $inicio->copy()->addHour();
        
        $conflicto = Cita::where('veterinario_id', $validated['veterinario_id'])
            ->where('estado', '!=', 'cancelada')
            ->where(function ($query) use ($inicio, $fin) {
                $query->whereBetween('fecha_hora', [$inicio->copy()->subMinutes(59), $fin->copy()->subMinute()]);
            })->exists();

        if ($conflicto) {
            return back()->withErrors(['fecha_hora' => 'El veterinario ya tiene una cita ocupada en ese horario.'])->withInput();
        }

        if ($user->rol === 'cliente') {
            $validated['user_id'] = $user->id;
            $validated['estado'] = 'pendiente';
        } else {
            $mascota = Mascota::find($validated['mascota_id']);
            $validated['user_id'] = $mascota->user_id;
            $validated['estado'] = 'confirmada';
        }

        $cita = Cita::create($validated);

        if ($user->rol === 'cliente') {
            $servicio = Servicio::find($validated['servicio_id']);
            $precioCentavos = intval($servicio->precio * 100);

            Stripe::setApiKey(env('STRIPE_SECRET'));
            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'usd', 
                        'product_data' => [
                            'name' => 'Servicio: ' . $servicio->nombre,
                            'description' => 'Cita para ' . $cita->mascota->nombre,
                        ],
                        'unit_amount' => $precioCentavos, 
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => route('citas.success', $cita) . "?session_id={CHECKOUT_SESSION_ID}",
                'cancel_url' => route('citas.index'),
            ]);
            return redirect($session->url);
        }

        return redirect()->route('citas.index')->with('success', 'Cita agendada exitosamente.');
    }

    public function pagoExitoso(Request $request, Cita $cita)
    {
        if ($cita->user_id !== Auth::id()) { abort(403); }
        $cita->estado = 'confirmada';
        $cita->save();
        return redirect()->route('citas.index')->with('success', '¡Pago recibido! Tu cita ha sido confirmada.');
    }

    public function show(Cita $cita)
    {
        $user = Auth::user();
        if ($user->rol !== 'admin' && $user->id !== $cita->user_id && $user->id !== $cita->veterinario_id) {
            abort(403);
        }
        return view('citas.show', compact('cita'));
    }

    public function edit(Cita $cita)
    {
        $user = Auth::user();
        if ($user->rol !== 'admin' && $user->id !== $cita->user_id && $user->rol !== 'veterinario') {
            abort(403);
        }
        if ($user->rol === 'cliente' && ($cita->estado === 'cancelada' || $cita->estado === 'completada' || $cita->fecha_hora < now())) {
            return back()->with('error', 'No puedes modificar esta cita.');
        }

        $mascotas = Mascota::where('user_id', $cita->user_id)->get();
        $veterinarios = User::where('rol', 'veterinario')->get();
        $servicios = Servicio::all();

        return view('citas.edit', compact('cita', 'mascotas', 'veterinarios', 'servicios'));
    }

    public function update(Request $request, Cita $cita)
    {
        $user = Auth::user();
        $rules = ['fecha_hora' => 'required|date', 'motivo' => 'required|string|max:255'];

        if ($user->rol === 'cliente') {
            $rules['fecha_hora'] = 'required|date|after:now';
        }
        if ($user->rol === 'admin' || $user->rol === 'veterinario') {
            $rules['estado'] = 'required|in:pendiente,confirmada,cancelada,completada';
        }

        $validated = $request->validate($rules);
        $cita->update($validated);
        return redirect()->route('citas.index')->with('success', 'Cita actualizada correctamente.');
    }

    /**
     * Acción para CANCELAR (Cambia estado, no borra).
     */
    public function cancelar(Cita $cita)
    {
        $user = Auth::user();
        // Seguridad: Solo dueño, vet asignado o admin pueden cancelar
        if ($user->rol !== 'admin' && $user->id !== $cita->user_id && $user->id !== $cita->veterinario_id) {
            abort(403);
        }
        $cita->estado = 'cancelada';
        $cita->save();
        return back()->with('success', 'La cita ha sido cancelada.');
    }

    /**
     * Acción para BORRAR DE VERDAD (Elimina de la BD).
     */
    public function destroy(Cita $cita)
    {
        // Seguridad: SOLO el ADMIN puede borrar registros físicos.
        if (Auth::user()->rol !== 'admin') {
            abort(403, 'Solo el administrador puede eliminar registros permanentemente.');
        }
        $cita->delete();
        return back()->with('success', 'Registro de cita eliminado permanentemente.');
    }
}
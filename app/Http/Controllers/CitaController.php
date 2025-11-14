<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Mascota;
use App\Models\Servicio;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CitaController extends Controller
{
    /**
     * Muestre una lista de los recursos.
     */
    public function index()
    {
        $citas = Cita::with(['mascota', 'veterinario', 'servicio'])
            ->where('usuario_id', Auth::id())
            ->orderBy('fecha', 'desc')
            ->orderBy('hora', 'desc')
            ->get();
            
        return view('citas.index', compact('citas'));
    }

    /**
     * Muestre el formulario para crear un nuevo recurso.
     */
    public function create()
    {
        $mascotas = Mascota::where('usuario_id', Auth::id())->get();
        $veterinarios = User::where('rol', 'veterinario')->get();
        $servicios = Servicio::all(); 
        
        return view('citas.create', compact('mascotas', 'veterinarios', 'servicios'));
    }

    /**
     * Guarde un recurso recién creado en el almacenamiento.
     */
    public function store(Request $request)
    {
        $request->validate([
            'mascota_id' => 'required|exists:mascota,id',
            'veterinario_id' => 'required|exists:users,id',
            'servicio_id' => 'nullable|exists:servicios,id',
            'fecha' => 'required|date|after_or_equal:today',
            'hora' => 'required|date_format:H:i',
        ]);

        Cita::create([
            'usuario_id' => Auth::id(),
            'mascota_id' => $request->mascota_id,
            'veterinario_id' => $request->veterinario_id,
            'servicio_id' => $request->servicio_id,
            'fecha' => $request->fecha,
            'hora' => $request->hora,
            'estado' => 'pendiente',
        ]);

        return redirect()->route('citas.index')
            ->with('success', 'Cita creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $cita = Cita::with(['mascota', 'veterinario', 'servicio', 'usuario'])
            ->findOrFail($id);
            
        // Verificar que la cita pertenece al usuario
        $user = Auth::user();
        if ($cita->usuario_id !== Auth::id() && $user->rol !== 'admin') {
        abort(403);
}
        
        return view('citas.show', compact('cita'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $cita = Cita::findOrFail($id);
        
        // Verificar permisos
        if ($cita->usuario_id !== Auth::id()) {
            abort(403);
        }
        
        $mascotas = Mascota::where('usuario_id', Auth::id())->get();
        $veterinarios = User::where('rol', 'veterinario')->get();
        $servicios = Servicio::all();
        
        return view('citas.edit', compact('cita', 'mascotas', 'veterinarios', 'servicios'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $cita = Cita::findOrFail($id);
        
        // Verificar permisos
        if ($cita->usuario_id !== Auth::id()) {
            abort(403);
        }
        
        $request->validate([
            'mascota_id' => 'required|exists:mascota,id',
            'veterinario_id' => 'required|exists:users,id',
            'servicio_id' => 'nullable|exists:servicios,id',
            'fecha' => 'required|date|after_or_equal:today',
            'hora' => 'required|date_format:H:i',
            'estado' => 'required|in:pendiente,confirmada,cancelada,completada',
        ]);

        $cita->update($request->all());

        return redirect()->route('citas.index')
            ->with('success', 'Cita actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $cita = Cita::findOrFail($id);
        
        // Verificar permisos
        if ($cita->usuario_id !== Auth::id()) {
            abort(403);
        }
        
        $cita->delete();

        return redirect()->route('citas.index')
            ->with('success', 'Cita eliminada exitosamente.');
    }

    
}

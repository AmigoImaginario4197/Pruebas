<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cita;
use App\Models\Tratamiento;
use App\Models\User;
use App\Models\Mascota;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
       $user = User::find(Auth::id());
               if (!$user) {
            return redirect()->route('login'); }

        // 1. Conteo de Mascotas del usuario
        $conteoMascotas = $user->mascotas()->count();

        // 2. Conteo de Citas para Hoy
        $conteoCitasHoy = Cita::whereHas('mascota', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->whereDate('fecha_hora', Carbon::today())
        ->where('estado', '!=', 'cancelada')
        ->count();

        // 3. Conteo de Tratamientos Activos (CORRECCIÓN FINAL)
        // Ya no buscamos un campo 'estado'. Usamos la lógica de fechas.
        $conteoTratamientos = Tratamiento::whereHas('mascota', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->where('fecha_inicio', '<=', Carbon::now()) // El tratamiento ha empezado.
        ->where(function ($query) {
            // Y (el tratamiento no ha terminado O es un tratamiento abierto)
            $query->where('fecha_fin', '>=', Carbon::now())
                  ->orWhereNull('fecha_fin');
        })
        ->count();

        // 4. Conteo de Citas Completadas
        $conteoCitasCompletadas = Cita::whereHas('mascota', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->where('estado', 'completada')
        ->count();

        // 5. Lista de Próximas 3 Citas
        $proximasCitas = Cita::with(['mascota', 'servicio'])
            ->whereHas('mascota', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->where('fecha_hora', '>=', Carbon::now())
            ->whereIn('estado', ['pendiente', 'confirmada'])
            ->orderBy('fecha_hora', 'asc')
            ->limit(3)
            ->get();
        
        return view('dashboard', [
            'conteoMascotas' => $conteoMascotas,
            'conteoCitasHoy' => $conteoCitasHoy,
            'conteoTratamientos' => $conteoTratamientos,
            'conteoCitasCompletadas' => $conteoCitasCompletadas,
            'proximasCitas' => $proximasCitas,
        ]);
    }
}
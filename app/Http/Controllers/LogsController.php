<?php

namespace App\Http\Controllers;

use App\Models\LogEvento;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogsController extends Controller
{
    public function index(Request $request)
    {
        $user = User::find(Auth::id());
        // 1. Iniciamos la consulta base (ordenada por fecha)
        $query = LogEvento::with('user')->orderBy('fecha', 'desc');

        // 2. Lógica de Seguridad y Filtros
        if ($user->isAdmin()) {
            // Si es Admin, obtenemos todos los usuarios para llenar el <select>
            $users = User::orderBy('name')->get();

            // Si el Admin aplicó un filtro, lo usamos
            if ($request->has('user_id') && $request->user_id != '') {
                $query->where('user_id', $request->user_id);
            }
        } else {
            // Si NO es Admin, solo ve sus propios logs
            $query->where('user_id', $user->id);
            $users = collect(); // Colección vacía para evitar errores en la vista
        }

        // 3. Paginamos (manteniendo los filtros en la URL)
        $logs = $query->paginate(20)->withQueryString();

        return view('logs.index', compact('logs', 'users'));
    }
}
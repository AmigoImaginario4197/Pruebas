<?php

namespace App\Http\Controllers;

use App\Models\LogEvento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LogsController extends Controller
{
    public function index()
    {
    $user = User::find(Auth::id()); 

        // Verificamos permisos (solo admin ve todo)
        // Nota: Si tu editor subraya isAdmin en rojo, ignóralo, funcionará igual.
        $query = LogEvento::with('user')->orderBy('fecha', 'desc');

         if (!$user->isAdmin()) {
            // Si no es admin, filtramos para que solo vea SUS acciones
            $query->where('user_id', $user->id);
        }

        $logs = $query->paginate(20);

        return view('logs.index', compact('logs'));
    }
}
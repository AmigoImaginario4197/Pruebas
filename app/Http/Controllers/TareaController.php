<?php

namespace App\Http\Controllers;

use App\Models\Tarea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TareaController extends Controller
{
    /**
     * VER LISTA: Admin y Veterinarios
     */
    public function index()
    {
        $tareas = Tarea::with('user')->orderBy('inicio', 'desc')->paginate(10);
        return view('tareas.index', compact('tareas'));
    }

    /**
     * VER DETALLE: Admin y Veterinarios
     */
    public function show(Tarea $tarea)
    {
        return view('tareas.show', compact('tarea'));
    }

    /**
     * FORMULARIO CREAR: Solo Admin
     */
    public function create()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if (!$user->isAdmin()) abort(403, 'No tienes permiso para crear tareas.');

        return view('tareas.create');
    }

    /**
     * GUARDAR: Solo Admin
     */
    public function store(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if (!$user->isAdmin()) abort(403, 'No tienes permiso para crear tareas.');

        $data = $request->validate([
            'titulo' => 'required|string|max:255',
            'inicio' => 'required|date',
            'fin'    => 'required|date|after:inicio',
            'observaciones' => 'nullable|string',
            'color'  => 'nullable|string'
        ]);

        $data['user_id'] = $user->id;
        $data['color'] = $data['color'] ?? '#6c757d';

        Tarea::create($data);

        // Si viene del modal de la agenda, volvemos atrás
        if(str_contains(url()->previous(), 'agenda')) {
            return redirect()->back()->with('success', 'Tarea interna agregada.');
        }
        return redirect()->route('tareas.index')->with('success', 'Tarea creada correctamente.');
    }

    /**
     * FORMULARIO EDITAR: Solo Admin
     */
    public function edit(Tarea $tarea)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if (!$user->isAdmin()) abort(403, 'No tienes permiso para editar tareas.');

        return view('tareas.edit', compact('tarea'));
    }

    /**
     * ACTUALIZAR: Solo Admin
     */
    public function update(Request $request, Tarea $tarea)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if (!$user->isAdmin()) abort(403, 'No tienes permiso para editar tareas.');

        $data = $request->validate([
            'titulo' => 'required|string|max:255',
            'inicio' => 'required|date',
            'fin'    => 'required|date|after:inicio',
            'observaciones' => 'nullable|string',
            'color'  => 'nullable|string'
        ]);

        $tarea->update($data);

        return redirect()->route('tareas.index')->with('success', 'Tarea actualizada.');
    }

    /**
     * ELIMINAR: Solo Admin
     */
    public function destroy(Tarea $tarea)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if (!$user->isAdmin()) abort(403, 'No tienes permiso para eliminar tareas.');

        $tarea->delete();
        return redirect()->back()->with('success', 'Tarea eliminada.');
    }
}
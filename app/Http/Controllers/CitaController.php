<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

class CitaController extends Controller
{

public function index()
    {
        return view('citas.index');}

public function create()
    {
        return view('citas.create');
    }

public function store(Request $request)
    {
        // Lógica para almacenar la cita
       return redirect()->route('citas.index');
    }

}



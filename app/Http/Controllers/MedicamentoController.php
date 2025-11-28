<?php

// ARCHIVO: app/Http/Controllers/MedicamentoController.php

namespace App\Http\Controllers;

use App\Models\Medicamento;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage; 

class MedicamentoController extends Controller
{
    /**
     * El constructor define los permisos usando middleware.
     */
    public function __construct()
    {
        $this->middleware('role:admin')->except(['index', 'show']);
    }

    /**
     * Muestra la lista de todos los medicamentos.
     */
    public function index(Request $request)
    {
        $query = Medicamento::query();
        if ($request->filled('search')) {
            $query->where('nombre', 'like', '%' . $request->search . '%');
        }
        // Nota: Laravel usa el nombre de la tabla que está en el modelo, por lo que aquí no hay que cambiar nada.
        $medicamentos = $query->orderBy('nombre')->paginate(15)->withQueryString();
        return view('medicamentos.index', compact('medicamentos'));
    }

    /**
     * Muestra el formulario para crear un nuevo medicamento.
     */
    public function create()
    {
        return view('medicamentos.create');
    }

    /**
     * Guarda un nuevo medicamento en la base de datos.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            // ===================================================
            //  CAMBIO 1: Se usa el nombre de tabla 'medicamento'
            // ===================================================
            'nombre' => 'required|string|max:255|unique:medicamento',
            'dosis_recomendada' => 'nullable|string|max:255',
            'descripcion' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('medicamentos', 'public');
            $validated['foto'] = $path;
        }

        Medicamento::create($validated);

        return redirect()->route('medicamentos.index')->with('success', 'Medicamento añadido correctamente.');
    }

    /**
     * Muestra los detalles de un medicamento específico.
     */
    public function show(Medicamento $medicamento)
    {
        return view('medicamentos.show', compact('medicamento'));
    }

    /**
     * Muestra el formulario para editar un medicamento.
     */
    public function edit(Medicamento $medicamento)
    {
        return view('medicamentos.edit', compact('medicamento'));
    }

    /**
     * Actualiza un medicamento específico en la base de datos.
     */
    public function update(Request $request, Medicamento $medicamento)
    {
        $validated = $request->validate([
            // ===================================================
            //  CAMBIO 2: Se usa el nombre de tabla 'medicamento'
            // ===================================================
            'nombre' => 'required|string|max:255|unique:medicamento,nombre,' . $medicamento->id,
            'dosis_recomendada' => 'nullable|string|max:255',
            'descripcion' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            if ($medicamento->foto) {
                Storage::disk('public')->delete($medicamento->foto);
            }
            $path = $request->file('foto')->store('medicamentos', 'public');
            $validated['foto'] = $path;
        }

        $medicamento->update($validated);

        return redirect()->route('medicamentos.index')->with('success', 'Medicamento actualizado correctamente.');
    }

    /**
     * Elimina un medicamento específico de la base de datos.
     */
    public function destroy(Medicamento $medicamento)
    {
        if ($medicamento->foto) {
            Storage::disk('public')->delete($medicamento->foto);
        }

        $medicamento->delete();

        return redirect()->route('medicamentos.index')->with('success', 'Medicamento eliminado correctamente.');
    }
}
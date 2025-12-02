<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Tratamiento - Pet Care</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/panel.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="panel-container">     
        @include('layouts.sidebar')

        <main class="panel-main">
            <header class="panel-header">
                <div class="header-title">
                    <h3>Editar Tratamiento</h3>
                    <p>Modifica el diagnóstico o la receta.</p>
                </div>
            </header>

            <div class="panel-content">
                <div class="card">
                    <form action="{{ route('tratamientos.update', $tratamiento) }}" method="POST">
                        @csrf @method('PATCH')
                        <div class="card-body">
                            @if ($errors->any())
                                <div class="alert alert-danger mb-4"><ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
                            @endif

                            <h5 class="card-title mb-3">Datos Generales</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Paciente</label>
                                    {{-- Mostramos el nombre pero deshabilitado --}}
                                    <input type="text" class="form-control" value="{{ $tratamiento->mascota->nombre }}" disabled>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Diagnóstico</label>
                                    <input type="text" name="diagnostico" class="form-control" value="{{ old('diagnostico', $tratamiento->diagnostico) }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Fecha Inicio</label>
                                    <input type="date" name="fecha_inicio" class="form-control" value="{{ old('fecha_inicio', $tratamiento->fecha_inicio) }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Fecha Fin</label>
                                    <input type="date" name="fecha_fin" class="form-control" value="{{ old('fecha_fin', $tratamiento->fecha_fin) }}">
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="form-label">Observaciones</label>
                                    <textarea name="observaciones" class="form-control" rows="3">{{ old('observaciones', $tratamiento->observaciones) }}</textarea>
                                </div>
                            </div>

                            <hr class="my-4">

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="card-title mb-0">Receta / Medicamentos</h5>
                                <button type="button" class="btn btn-sm btn-success" onclick="agregarMedicamento()">
                                    <i class="bi bi-plus-circle"></i> Añadir Medicamento
                                </button>
                            </div>

                            <div id="medicamentos-container">
                                {{-- Cargar medicamentos existentes --}}
                                @foreach ($tratamiento->medicamentos as $medPivot)
                                    <div class="medicamento-row row g-2 align-items-end mb-3 border-bottom pb-3">
                                        <div class="col-md-3">
                                            <select name="medicamentos[]" class="form-select form-select-sm" required>
                                                @foreach ($medicamentos as $med)
                                                    <option value="{{ $med->id }}" @selected($medPivot->id == $med->id)>{{ $med->nombre }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3"><input type="text" name="dosis[]" class="form-control form-control-sm" value="{{ $medPivot->pivot->dosis }}" required></div>
                                        <div class="col-md-3"><input type="text" name="frecuencia[]" class="form-control form-control-sm" value="{{ $medPivot->pivot->frecuencia }}" required></div>
                                        <div class="col-md-2"><input type="text" name="duracion[]" class="form-control form-control-sm" value="{{ $medPivot->pivot->duracion }}" required></div>
                                        <div class="col-md-1 text-end"><button type="button" class="btn btn-danger btn-sm" onclick="this.closest('.medicamento-row').remove()"><i class="bi bi-trash"></i></button></div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="card-footer text-end">
                            <a href="{{ route('tratamientos.index') }}" class="btn btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <template id="medicamento-template">
        <div class="medicamento-row row g-2 align-items-end mb-3 border-bottom pb-3">
            <div class="col-md-3">
                <select name="medicamentos[]" class="form-select form-select-sm" required>
                    <option value="">-- Elegir --</option>
                    @foreach ($medicamentos as $med)
                        <option value="{{ $med->id }}">{{ $med->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3"><input type="text" name="dosis[]" class="form-control form-control-sm" placeholder="Dosis" required></div>
            <div class="col-md-3"><input type="text" name="frecuencia[]" class="form-control form-control-sm" placeholder="Frecuencia" required></div>
            <div class="col-md-2"><input type="text" name="duracion[]" class="form-control form-control-sm" placeholder="Duración" required></div>
            <div class="col-md-1 text-end"><button type="button" class="btn btn-danger btn-sm" onclick="this.closest('.medicamento-row').remove()"><i class="bi bi-trash"></i></button></div>
        </div>
    </template>

    <script>
        function agregarMedicamento() {
            const container = document.getElementById('medicamentos-container');
            const clone = document.getElementById('medicamento-template').content.cloneNode(true);
            container.appendChild(clone);
        }
    </script>
</body>
</html>
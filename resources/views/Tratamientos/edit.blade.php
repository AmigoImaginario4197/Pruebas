<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Tratamiento - Pet Care</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/panel.css') }}">
    <link rel="stylesheet" href="{{ asset('css/tratamientos.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-light">
    <div class="panel-container">     
        @include('layouts.sidebar')

        <main class="panel-main">
            <header class="panel-header">
                <div class="header-title">
                    <h3>Editar Tratamiento #{{ $tratamiento->id }}</h3>
                    <p>Gestión clínica y farmacológica.</p>
                </div>
                <div class="header-actions">
                    <a href="{{ route('tratamientos.index') }}" class="btn btn-secondary text-white">
                        <i class="bi bi-arrow-left"></i> Volver
                    </a>
                </div>
            </header>

            <div class="panel-content">
                <div class="profile-card">
                    <form action="{{ route('tratamientos.update', $tratamiento->id) }}" method="POST">
                        @csrf @method('PUT')
                        @if ($errors->any()) <div class="alert alert-danger mb-4">Revisa los errores.</div> @endif

                        <div class="tratamiento-layout">
                            <div class="layout-col-left">
                                <div class="section-header">
                                    <div class="section-icon"><i class="bi bi-clipboard2-pulse"></i></div>
                                    <h5 class="section-title">Datos Clínicos</h5>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Paciente</label>
                                    <input type="text" class="form-control" value="{{ $tratamiento->mascota->nombre }} ({{ $tratamiento->mascota->especie }})" disabled>
                                    <input type="hidden" name="mascota_id" value="{{ $tratamiento->mascota_id }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Diagnóstico <span class="text-danger">*</span></label>
                                    <input type="text" name="diagnostico" class="form-control" value="{{ old('diagnostico', $tratamiento->diagnostico) }}" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Observaciones</label>
                                    <textarea name="observaciones" class="form-control" rows="4">{{ old('observaciones', $tratamiento->observaciones) }}</textarea>
                                </div>
                            </div>
                            <div class="layout-col-right">
                                <div class="section-header">
                                    <div class="section-icon"><i class="bi bi-calendar-event"></i></div>
                                    <h5 class="section-title">Seguimiento</h5>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Inicio</label>
                                        <input type="date" name="fecha_inicio" class="form-control" value="{{ old('fecha_inicio', $tratamiento->fecha_inicio) }}" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Fin (Est.)</label>
                                        <input type="date" name="fecha_fin" class="form-control" value="{{ old('fecha_fin', $tratamiento->fecha_fin) }}">
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <label class="form-label">Responsable</label>
                                    <div class="vet-box d-flex align-items-center">
                                        <i class="bi bi-person-badge-fill fs-4 me-3"></i>
                                        <div>
                                            <small class="text-uppercase fw-bold" style="opacity: 0.7;">Veterinario</small>
                                            <div class="fw-bold fs-6">Dr. {{ $tratamiento->veterinario->name ?? Auth::user()->name }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="medicamentos-section">
                            <div class="med-header">
                                <h5 class="m-0 fw-bold text-dark"><i class="bi bi-capsule me-2"></i>Receta Médica</h5>
                                <button type="button" class="btn btn-sm btn-success text-white" onclick="agregarMedicamento()">
                                    <i class="bi bi-plus-lg"></i> Agregar
                                </button>
                            </div>
                            <div class="med-body" id="medicamentos-container">
                                @foreach ($tratamiento->medicamentos as $med_pivot)
                                <div class="medicamento-row row g-2 align-items-end">
                                    <div class="col-md-3">
                                        <label class="form-label small text-muted mb-1">Medicamento</label>
                                        <select name="medicamentos[]" class="form-select form-select-sm" required>
                                            <option value="">-- Elegir --</option>
                                            @foreach ($medicamentos as $med)
                                                <option value="{{ $med->id }}" @selected($med_pivot->id == $med->id)>
                                                    {{ $med->nombre . ($med->tipo ? " ($med->tipo)" : '') }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3"><label class="form-label small text-muted mb-1">Dosis</label><input type="text" name="dosis[]" class="form-control form-control-sm" value="{{ $med_pivot->pivot->dosis }}" required></div>
                                    <div class="col-md-3"><label class="form-label small text-muted mb-1">Frecuencia</label><input type="text" name="frecuencia[]" class="form-control form-control-sm" value="{{ $med_pivot->pivot->frecuencia }}" required></div>
                                    <div class="col-md-2"><label class="form-label small text-muted mb-1">Duración</label><input type="text" name="duracion[]" class="form-control form-control-sm" value="{{ $med_pivot->pivot->duracion }}" required></div>
                                    <div class="col-md-1 text-end"><button type="button" class="btn btn-outline-danger btn-sm border-0" onclick="eliminarFila(this)"><i class="bi bi-trash-fill"></i></button></div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="d-flex align-items-center mt-4 justify-content-end gap-3">
                            <a href="{{ route('tratamientos.index') }}" class="btn btn-secondary text-white px-4">Cancelar</a>
                            <button type="submit" class="btn-save px-4 shadow-sm">Guardar Cambios</button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    {{-- Reutilizamos el mismo template --}}
    <template id="medicamento-template">
        <div class="medicamento-row row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label small text-muted mb-1">Medicamento</label>
                <select name="medicamentos[]" class="form-select form-select-sm" required>
                    <option value="">-- Elegir --</option>
                    @foreach ($medicamentos as $med)
                        <option value="{{ $med->id }}">{{ $med->nombre . ($med->tipo ? " ($med->tipo)" : '') }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3"><label class="form-label small text-muted mb-1">Dosis</label><input type="text" name="dosis[]" class="form-control form-control-sm" required></div>
            <div class="col-md-3"><label class="form-label small text-muted mb-1">Frecuencia</label><input type="text" name="frecuencia[]" class="form-control form-control-sm" required></div>
            <div class="col-md-2"><label class="form-label small text-muted mb-1">Duración</label><input type="text" name="duracion[]" class="form-control form-control-sm" required></div>
            <div class="col-md-1 text-end"><button type="button" class="btn btn-outline-danger btn-sm border-0" onclick="eliminarFila(this)"><i class="bi bi-trash-fill"></i></button></div>
        </div>
    </template>

    <script src="{{ asset('js/tratamientos.js') }}"></script>
</body>
</html>
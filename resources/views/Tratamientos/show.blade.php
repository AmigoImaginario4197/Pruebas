<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle Tratamiento - Pet Care</title>
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
                    <h3>Detalle del Tratamiento</h3>
                    <p>Receta y observaciones médicas.</p>
                </div>
                <div class="header-actions">
                    <a href="{{ route('tratamientos.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a>
                </div>
            </header>

            <div class="panel-content">
                <div class="card mb-4">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 text-primary">{{ $tratamiento->diagnostico }}</h5>
                        @php $isActive = !$tratamiento->fecha_fin || \Carbon\Carbon::parse($tratamiento->fecha_fin)->isFuture(); @endphp
                        <span class="badge {{ $isActive ? 'bg-success' : 'bg-secondary' }}">{{ $isActive ? 'En Curso' : 'Finalizado' }}</span>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <p class="mb-1 text-muted small text-uppercase fw-bold">Paciente</p>
                                <p class="fs-5 fw-bold">{{ $tratamiento->mascota->nombre }}</p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-1 text-muted small text-uppercase fw-bold">Veterinario</p>
                                <p class="fs-5">Dr. {{ $tratamiento->veterinario->name }}</p>
                            </div>
                        </div>
                        
                        @if($tratamiento->observaciones)
                            <div class="alert alert-light border">
                                <strong>Observaciones:</strong> {{ $tratamiento->observaciones }}
                            </div>
                        @endif

                        <h5 class="mt-4 mb-3 border-bottom pb-2">Medicamentos Recetados</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>Medicamento</th>
                                        <th>Dosis</th>
                                        <th>Frecuencia</th>
                                        <th>Duración</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($tratamiento->medicamentos as $medicamento)
                                        <tr>
                                            <td class="fw-bold">{{ $medicamento->nombre }}</td>
                                            <td>{{ $medicamento->pivot->dosis }}</td>
                                            <td>{{ $medicamento->pivot->frecuencia }}</td>
                                            <td>{{ $medicamento->pivot->duracion }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="4" class="text-center text-muted">No hay medicamentos asignados.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    @if(!Auth::user()->isCliente())
                        <div class="card-footer text-end">
                            <a href="{{ route('tratamientos.edit', $tratamiento) }}" class="btn btn-warning"><i class="bi bi-pencil"></i> Editar</a>
                        </div>
                    @endif
                </div>
            </div>
        </main>
    </div>
</body>
</html>
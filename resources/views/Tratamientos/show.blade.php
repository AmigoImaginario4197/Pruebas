<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle Tratamiento - Pet Care</title>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <!-- CSS Externos -->
    <link rel="stylesheet" href="{{ asset('css/panel.css') }}">
    <link rel="stylesheet" href="{{ asset('css/tratamientos.css') }}">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-light">
    <div class="panel-container">     
        @include('layouts.sidebar')

        <main class="panel-main">
            {{-- Header --}}
            <header class="panel-header d-flex justify-content-between align-items-center mb-4">
                <div class="header-title">
                    <h3>Tratamiento #{{ $tratamiento->id }}</h3>
                    <p class="text-muted">Ficha clínica y prescripción médica.</p>
                </div>
                <div class="header-actions d-flex gap-2">
                    <a href="{{ route('tratamientos.index') }}" class="btn btn-secondary text-white btn-sm px-3">
                        <i class="bi bi-arrow-left me-1"></i> Volver
                    </a>
                    <button onclick="window.print()" class="btn btn-primary btn-sm px-3 shadow-sm">
                        <i class="bi bi-printer me-1"></i> Imprimir
                    </button>
                    @if(!Auth::user()->isCliente())
                        <a href="{{ route('tratamientos.edit', $tratamiento->id) }}" class="btn btn-warning btn-sm text-dark px-3 shadow-sm">
                            <i class="bi bi-pencil me-1"></i> Editar
                        </a>
                    @endif
                </div>
            </header>

            <div class="panel-content">
                <div class="profile-card">
                    
                    <div class="tratamiento-layout">
                        
                        <!-- COLUMNA IZQUIERDA: Personas (Paciente + Veterinario) -->
                        <div class="layout-col-left">
                            <div class="section-header">
                                <div class="section-icon"><i class="bi bi-people"></i></div>
                                <h5 class="section-title">Involucrados</h5>
                            </div>

                            {{-- 1. Diagnóstico --}}
                            <div class="mb-4">
                                <label class="text-uppercase text-muted small fw-bold mb-1" style="font-size: 0.7rem;">Diagnóstico Principal</label>
                                <h4 class="text-primary fw-bold m-0">{{ $tratamiento->diagnostico }}</h4>
                            </div>

                            {{-- 2. Paciente --}}
                            <div class="mb-4">
                                <label class="text-uppercase text-muted small fw-bold mb-2" style="font-size: 0.7rem;">Paciente</label>
                                <div class="patient-card d-flex align-items-center">
                                    <div class="bg-light p-3 rounded-circle me-3 text-success border">
                                        <i class="bi bi-heart-pulse-fill fs-4"></i>
                                    </div>
                                    <div>
                                        <div class="fs-5 fw-bold text-dark">{{ $tratamiento->mascota->nombre }}</div>
                                        <div class="text-muted small">
                                            {{ $tratamiento->mascota->especie }} • 
                                            <span class="text-dark fw-bold">Dueño:</span> {{ $tratamiento->mascota->usuario->name ?? 'N/A' }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- 3. Veterinario --}}
                            <div>
                                <label class="text-uppercase text-muted small fw-bold mb-2" style="font-size: 0.7rem;">Profesional Responsable</label>
                                <div class="vet-box d-flex align-items-center shadow-sm">
                                    <div class="bg-white p-2 rounded-circle me-3 shadow-sm text-primary d-flex justify-content-center align-items-center" style="width: 45px; height: 45px;">
                                        <i class="bi bi-person-badge-fill fs-5"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark fs-6">Dr. {{ $tratamiento->veterinario->name ?? 'No asignado' }}</div>
                                        <div class="small text-primary opacity-75">
                                            <i class="bi bi-award-fill me-1"></i>
                                            {{ $tratamiento->veterinario->especialidad ?? 'Veterinario General' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- COLUMNA DERECHA: Detalles del Caso (Fechas + Notas) -->
                        <div class="layout-col-right">
                            <div class="section-header">
                                <div class="section-icon"><i class="bi bi-clipboard-data"></i></div>
                                <h5 class="section-title">Detalles del Caso</h5>
                            </div>

                            {{-- 1. Fechas y Estado --}}
                            <div class="bg-white rounded border mb-4 shadow-sm overflow-hidden">
                                <div class="d-flex border-bottom">
                                    <div class="flex-fill p-3 border-end text-center">
                                        <span class="d-block text-muted small fw-bold text-uppercase mb-1" style="font-size: 0.65rem;">Fecha Inicio</span>
                                        <span class="fs-5 fw-bold text-dark d-block">
                                            {{ \Carbon\Carbon::parse($tratamiento->fecha_inicio)->format('d/m/Y') }}
                                        </span>
                                    </div>
                                    <div class="flex-fill p-3 text-center">
                                        <span class="d-block text-muted small fw-bold text-uppercase mb-1" style="font-size: 0.65rem;">Fecha Fin</span>
                                        @if($tratamiento->fecha_fin)
                                            <span class="fs-5 fw-bold text-dark d-block">
                                                {{ \Carbon\Carbon::parse($tratamiento->fecha_fin)->format('d/m/Y') }}
                                            </span>
                                        @else
                                            <span class="text-muted fst-italic fs-6 d-block mt-1">Indefinido</span>
                                        @endif
                                    </div>
                                </div>

                                @php 
                                    $isActive = !$tratamiento->fecha_fin || \Carbon\Carbon::parse($tratamiento->fecha_fin)->isFuture(); 
                                @endphp
                                <div class="p-3 bg-light text-center">
                                    <div class="d-flex align-items-center justify-content-center gap-2">
                                        <span class="text-uppercase fw-bold text-muted small" style="font-size: 0.75rem;">Estado:</span>
                                        
                                        {{-- 
                                            CORRECCIÓN AQUÍ: 
                                            Usamos las clases 'soft' que definimos en CSS. 
                                            Estas tienen fondo claro y TEXTO OSCURO (Verde o Gris oscuro).
                                        --}}
                                        <span class="badge {{ $isActive ? 'bg-success-soft' : 'bg-secondary-soft' }} border rounded-pill px-3 py-1">
                                            {{ $isActive ? 'En Curso' : 'Finalizado' }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            {{-- 2. Notas / Evolución --}}
                            @if($tratamiento->observaciones)
                                <div class="mt-4">
                                    <label class="text-uppercase text-muted small fw-bold mb-2" style="font-size: 0.7rem;">Notas / Evolución</label>
                                    <div class="p-3 bg-light rounded border border-light text-secondary fst-italic notes-content">
                                        <i class="bi bi-chat-square-quote-fill me-2 text-muted opacity-50"></i>
                                        {{ $tratamiento->observaciones }}
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- SECCIÓN RECETA MÉDICA --}}
                    <div class="medicamentos-section mt-5 shadow-sm">
                        <div class="med-header bg-light border-bottom py-3 px-4">
                            <div class="d-flex align-items-center">
                                <div class="bg-white p-1 rounded border me-2 shadow-sm d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                    <i class="bi bi-capsule text-danger"></i>
                                </div>
                                <h5 class="m-0 fw-bold text-dark">Receta Médica Prescrita</h5>
                            </div>
                            <small class="text-muted fw-bold">{{ count($tratamiento->medicamentos) }} item(s)</small>
                        </div>
                        
                        <div class="p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0 align-middle">
                                    <thead class="bg-light text-muted small text-uppercase">
                                        <tr>
                                            <th class="ps-4 py-3 fw-bold" style="width: 40%;">Medicamento</th>
                                            <th class="fw-bold">Dosis</th>
                                            <th class="fw-bold">Frecuencia</th>
                                            <th class="pe-4 fw-bold text-end">Duración</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white">
                                        @forelse ($tratamiento->medicamentos as $medicamento)
                                            <tr>
                                                <td class="ps-4 py-3">
                                                    <div class="d-flex align-items-center gap-3">
                                                        @if($medicamento->foto)
                                                            <img src="{{ asset('storage/' . $medicamento->foto) }}" alt="{{ $medicamento->nombre }}" class="med-img shadow-sm">
                                                        @else
                                                            <div class="med-img d-flex align-items-center justify-content-center bg-light text-secondary">
                                                                <i class="bi bi-capsule-pill fs-4 opacity-50"></i>
                                                            </div>
                                                        @endif
                                                        
                                                        <div>
                                                            <div class="fw-bold text-dark">{{ $medicamento->nombre }}</div>
                                                            @if($medicamento->tipo)
                                                                <span class="badge bg-light text-secondary border fw-normal mt-1">{{ $medicamento->tipo }}</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="cell-label">Dosis</span>
                                                    <span class="fw-bold text-dark">{{ $medicamento->pivot->dosis }}</span>
                                                </td>
                                                <td>
                                                    <span class="cell-label">Frecuencia</span>
                                                    <span class="fw-bold text-dark">{{ $medicamento->pivot->frecuencia }}</span>
                                                </td>
                                                <td class="pe-4 text-end">
                                                    <span class="cell-label text-end">Duración</span>
                                                    <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25">
                                                        {{ $medicamento->pivot->duracion }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center py-5">
                                                    <div class="text-muted opacity-50 mb-2"><i class="bi bi-prescription2 fs-1"></i></div>
                                                    <p class="text-muted small m-0">No se han registrado medicamentos.</p>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-none d-print-block mt-5 pt-4 border-top text-center text-muted small">
                        <p class="mb-1 fw-bold">Pet Care - Sistema Veterinario</p>
                        <p class="m-0">Generado el {{ date('d/m/Y H:i') }}</p>
                    </div>

                </div>
            </div>
        </main>
    </div>
</body>
</html>
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Cita - Pet Care</title>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/panel.css') }}">
    
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/citas.js'])
</head>
<body class="font-sans antialiased">
    <div class="panel-container">     
        @include('layouts.sidebar')

        <main class="panel-main">
            <header class="panel-header">
                <div class="header-title">
                    <h3>Editar Cita</h3>
                    <p>Modifica los detalles de la consulta.</p>
                </div>
            </header>

            <div class="panel-content">
                <div class="card shadow-sm border-0 p-4" style="max-width: 800px; margin: 0 auto;">
                    <form action="{{ route('citas.update', $cita) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6 pe-md-4">
                                <h5 class="mb-4 border-bottom pb-2">Detalles de la Cita</h5>

                                {{-- Selector de Veterinario --}}
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Veterinario</label>
                                    <select name="veterinario_id" id="veterinario_id" class="form-select" required>
                                        <option value="" selected disabled>-- Selecciona un Doctor --</option>
                                        @foreach($veterinarios as $vet)
                                            <option value="{{ $vet->id }}" 
                                                {{ $cita->veterinario_id == $vet->id ? 'selected' : '' }}>
                                                Dr. {{ $vet->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Selector de Fecha --}}
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Fecha de la Cita</label>
                                    <input type="date" id="fecha_selector" class="form-control" 
                                           min="{{ date('Y-m-d') }}" 
                                           value="{{ $cita->fecha_hora ? \Carbon\Carbon::parse($cita->fecha_hora)->format('Y-m-d') : '' }}" 
                                           required>
                                    <small class="text-muted">Selecciona un veterinario y una fecha para ver horas disponibles.</small>
                                </div>

                                {{-- Contenedor de Huecos (Slots) --}}
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Horarios Disponibles</label>
                                    <div id="slots-container" class="d-flex flex-wrap gap-2 p-3 border rounded bg-light" style="min-height: 80px;">
                                        <span class="text-muted fst-italic">Esperando datos...</span>
                                    </div>
                                    
                                    {{-- INPUT OCULTO: Aquí se guardará la fecha y hora final para enviar al backend --}}
                                    <input type="hidden" name="fecha_hora" id="fecha_hora_final" 
                                           value="{{ $cita->fecha_hora }}" required>
                                    @error('fecha_hora')
                                        <div class="text-danger mt-1 small">{{ $message }}</div>
                                    @enderror
                                </div>
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Cita - Pet Care</title>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/panel.css') }}">
    <link rel="stylesheet" href="{{ asset('css/citas.css') }}">
    
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/citas.js'])
</head>
<body class="font-sans antialiased">
    <div class="panel-container">     
        @include('layouts.sidebar')

        <main class="panel-main">
            <header class="panel-header">
                <h3>Editar Cita</h3>
            </header>

            <div class="panel-content">
                <div class="card shadow-sm border-0 p-4" style="max-width: 800px; margin: 0 auto;">
                    
                    <form action="{{ route('citas.update', $cita->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- DIV OCULTO PARA ERRORES --}}
                        @if ($errors->any())
                            <div id="backend-errors" style="display: none;">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="row">
                            {{-- COLUMNA IZQUIERDA --}}
                            <div class="col-md-6 pe-md-4">
                                <h5 class="mb-4 border-bottom pb-2">1. Detalles de la Cita</h5>
                                
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Mascota</label>
                                    <select name="mascota_id" class="form-select" {{ !Auth::user()->isAdmin() ? 'disabled' : '' }}>
                                        @foreach($mascotas as $mascota)
                                            <option value="{{ $mascota->id }}" {{ $cita->mascota_id == $mascota->id ? 'selected' : '' }}>{{ $mascota->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Servicio</label>
                                    <select name="servicio_id" id="servicio_id" class="form-select" {{ !Auth::user()->isAdmin() ? 'disabled' : '' }}>
                                        @foreach($servicios as $servicio)
                                            <option value="{{ $servicio->id }}" data-price="{{ $servicio->precio }}" {{ $cita->servicio_id == $servicio->id ? 'selected' : '' }}>
                                                {{ $servicio->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Motivo</label>
                                    <textarea name="motivo" class="form-control" rows="4" required>{{ old('motivo', $cita->motivo) }}</textarea>
                                </div>
                            </div>
                            
                            {{-- COLUMNA DERECHA --}}
                            <div class="col-md-6 ps-md-4 border-start">
                                <h5 class="mb-4 border-bottom pb-2">2. Horario y Estado</h5>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Veterinario</label>
                                    <select name="veterinario_id" id="veterinario_id" class="form-select" {{ !Auth::user()->isAdmin() ? 'disabled' : '' }}>
                                        @foreach($veterinarios as $vet)
                                            <option value="{{ $vet->id }}" {{ $cita->veterinario_id == $vet->id ? 'selected' : '' }}>{{ $vet->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Fecha</label>
                                    <input type="date" id="fecha_selector" class="form-control" value="{{ \Carbon\Carbon::parse($cita->fecha_hora)->format('Y-m-d') }}" min="{{ date('Y-m-d') }}" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Horarios Disponibles</label>
                                    <div id="slots-container" class="slots-container">...</div>
                                    <input type="hidden" name="fecha_hora" id="fecha_hora_final" value="{{ $cita->fecha_hora }}" required>
                                </div>
                                
                                {{-- Solo Admin/Vet pueden cambiar el estado --}}
                                @if(Auth::user()->rol !== 'cliente')
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Estado de la Cita</label>
                                        <select name="estado" class="form-select">
                                            <option value="pendiente" {{ $cita->estado == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                            <option value="confirmada" {{ $cita->estado == 'confirmada' ? 'selected' : '' }}>Confirmada</option>
                                            <option value="cancelada" {{ $cita->estado == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                                            <option value="completada" {{ $cita->estado == 'completada' ? 'selected' : '' }}>Completada</option>
                                        </select>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="mt-4 text-end">
                            <a href="{{ route('citas.index') }}" class="btn btn-light me-2">Cancelar</a>
                            <button type="submit" class="btn btn-warning">Actualizar Cita</button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
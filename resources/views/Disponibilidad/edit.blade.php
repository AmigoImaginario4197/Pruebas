<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Disponibilidad - Pet Care</title>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
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
                    <h3>Editar Bloque de Disponibilidad</h3>
                    <p>Modifica el horario para <strong>{{ $disponibilidad->veterinario->name }}</strong>.</p>
                </div>
            </header>
            <div class="panel-content">
                <div class="card">
                    <form action="{{ route('disponibilidad.update', $disponibilidad) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="card-body">
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <h5 class="alert-heading">Error de Validación</h5>
                                    <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                                </div>
                            @endif
                            <div class="row">
                                {{-- El veterinario no se puede cambiar en la edición, se muestra como texto --}}
                                <div class="col-12 mb-3">
                                    <label class="form-label">Veterinario</label>
                                    <input type="text" class="form-control" value="{{ $disponibilidad->veterinario->name }}" disabled>
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <label for="fecha" class="form-label">Fecha</label>
                                    <input type="date" name="fecha" id="fecha" class="form-control @error('fecha') is-invalid @enderror" value="{{ old('fecha', $disponibilidad->fecha) }}" required min="{{ now()->format('Y-m-d') }}">
                                    @error('fecha')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="hora_inicio" class="form-label">Hora de Inicio</label>
                                    <input type="time" name="hora_inicio" id="hora_inicio" class="form-control @error('hora_inicio') is-invalid @enderror" value="{{ old('hora_inicio', $disponibilidad->hora_inicio) }}" required>
                                    @error('hora_inicio')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="hora_fin" class="form-label">Hora de Fin</label>
                                    <input type="time" name="hora_fin" id="hora_fin" class="form-control @error('hora_fin') is-invalid @enderror" value="{{ old('hora_fin', $disponibilidad->hora_fin) }}" required>
                                    @error('hora_fin')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-end">
                            <a href="{{ route('disponibilidad.index') }}" class="btn btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
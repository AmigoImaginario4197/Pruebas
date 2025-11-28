<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Añadir Disponibilidad - Pet Care</title>
    
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
                    <h3>Añadir Bloque de Disponibilidad</h3>
                    <p>Define un nuevo bloque horario para un veterinario en una fecha específica.</p>
                </div>
            </header>
            <div class="panel-content">
                <div class="card">
                    <form action="{{ route('disponibilidad.store') }}" method="POST">
                        @csrf
                        <div class="card-body">
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <h5 class="alert-heading">Error de Validación</h5>
                                    <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                                </div>
                            @endif
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="veterinario_id" class="form-label">Veterinario</label>
                                    <select name="veterinario_id" id="veterinario_id" class="form-select @error('veterinario_id') is-invalid @enderror" required>
                                        <option value="">Selecciona un veterinario</option>
                                        @foreach ($veterinarios as $veterinario)
                                            <option value="{{ $veterinario->id }}" @selected(old('veterinario_id') == $veterinario->id)>{{ $veterinario->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('veterinario_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="fecha" class="form-label">Fecha</label>
                                    <input type="date" name="fecha" id="fecha" class="form-control @error('fecha') is-invalid @enderror" value="{{ old('fecha') }}" required min="{{ now()->format('Y-m-d') }}">
                                    @error('fecha')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="hora_inicio" class="form-label">Hora de Inicio</label>
                                    <input type="time" name="hora_inicio" id="hora_inicio" class="form-control @error('hora_inicio') is-invalid @enderror" value="{{ old('hora_inicio') }}" required>
                                    @error('hora_inicio')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="hora_fin" class="form-label">Hora de Fin</label>
                                    <input type="time" name="hora_fin" id="hora_fin" class="form-control @error('hora_fin') is-invalid @enderror" value="{{ old('hora_fin') }}" required>
                                    @error('hora_fin')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-end">
                            <a href="{{ route('disponibilidad.index') }}" class="btn btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Añadir Horario</button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
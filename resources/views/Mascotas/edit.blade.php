<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar a {{ $mascota->nombre }} - Pet Care</title>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <link rel="stylesheet" href="{{ asset('css/panel.css') }}">
    <link rel="stylesheet" href="{{ asset('css/mascota.css') }}">
    
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/mascota-form.js'])
</head>
<body class="font-sans antialiased bg-light">
    <div class="panel-container">
        
        @include('layouts.sidebar')

        <main class="panel-main">
            <header class="panel-header">
                <div class="header-title">
                    <h3>Editando a: {{ $mascota->nombre }}</h3>
                    <p>Actualiza la información de tu mascota.</p>
                </div>
                <div class="header-actions">
                    {{-- BOTÓN CANCELAR CORREGIDO --}}
                    @if(Auth::user()->rol === 'admin')
                    <a href="{{ route('veterinario.mascotas.index') }}" class="btn btn-primary">
                      <i class="bi bi-arrow-left"></i> Volver a Mascotas
                    </a>
                    @else
                    <a href="{{ route('mascotas.index') }}" class="btn btn-secondary">
                        <i class="bi bi-x-lg"></i> Volver a Mascotas
                    </a>
                    @endif
                </div>
            </header>

            <div class="panel-content">
                <form action="{{ route('mascotas.update', $mascota->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    
                    <div class="row g-4">
                        {{-- COLUMNA IZQUIERDA: FOTO --}}
                        <div class="col-lg-4">
                            <div class="card shadow-sm border-0 h-100">
                                <div class="card-body p-4 photo-upload-container">
                                    <h5 class="fw-bold mb-4">Foto de Perfil</h5>
                                    
                                    {{-- Previsualización de imagen --}}
                                    <img id="image-preview" 
                                         src="{{ $mascota->foto ? asset('storage/' . $mascota->foto) : asset('images/placeholder-pet.png') }}" 
                                         alt="Previsualización" class="photo-upload-preview">
                                    
                                    {{-- El input está oculto, se activa con el label --}}
                                    <input class="d-none" type="file" id="foto" name="foto" onchange="previewImage(event)">
                                    <small class="form-text text-muted d-block mt-2">Dejar en blanco para no cambiar.</small>
                                    @error('foto')
                                        <div class="text-danger mt-1 small">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- COLUMNA DERECHA: DATOS --}}
                        <div class="col-lg-8">
                            <div class="card shadow-sm border-0 h-100">
                                <div class="card-body p-4">
                                    <h5 class="fw-bold mb-4">Información General</h5>

                                    <div class="mb-3">
                                        <label for="nombre" class="form-label">Nombre</label>
                                        <input type="text" class="form-control" id="nombre" name="nombre" value="{{ old('nombre', $mascota->nombre) }}" required>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="especie" class="form-label">Especie</label>
                                            <select class="form-select" id="especie" name="especie" required>
                                                <option value="Perro" {{ old('especie', $mascota->especie) == 'Perro' ? 'selected' : '' }}>Perro</option>
                                                <option value="Gato" {{ old('especie', $mascota->especie) == 'Gato' ? 'selected' : '' }}>Gato</option>
                                                <option value="Hamster" {{ old('especie', $mascota->especie) == 'Hamster' ? 'selected' : '' }}>Hamster</option>
                                                <option value="Conejo" {{ old('especie', $mascota->especie) == 'Conejo' ? 'selected' : '' }}>Conejo</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="raza" class="form-label">Raza</label>
                                            <input type="text" class="form-control" id="raza" name="raza" value="{{ old('raza', $mascota->raza) }}">
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="edad" class="form-label">Edad (años)</label>
                                            <input type="number" class="form-control" id="edad" name="edad" value="{{ old('edad', $mascota->edad) }}">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="peso" class="form-label">Peso (kg)</label>
                                            <input type="number" step="0.1" class="form-control" id="peso" name="peso" value="{{ old('peso', $mascota->peso) }}">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
                                        <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" value="{{ old('fecha_nacimiento', $mascota->fecha_nacimiento) }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Botón de envío --}}
                    <div class="text-end mt-4">
                        <button type="submit" class="btn btn-warning">
                            <i class="bi bi-check-circle"></i> Actualizar Datos
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>
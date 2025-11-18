<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar a {{ $mascota->nombre }} - Pet Care</title>
    
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
            {{-- Header de la página --}}
            <header class="panel-header">
                <div class="header-title">
                    <h3>Editando a: {{ $mascota->nombre }}</h3>
                    <p>Actualiza la información de tu mascota.</p>
                </div>
                <div class="header-actions">
                    <a href="{{ route('mascotas.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Cancelar
                    </a>
                </div>
            </header>

            {{-- Contenido principal de la página con el formulario --}}
            <div class="panel-content">
                <div class="card p-4">
                    {{-- El formulario apunta a la ruta 'update' y usa el método PATCH --}}
                    {{-- enctype es crucial para poder subir archivos (la foto) --}}
                    <form action="{{ route('mascotas.update', $mascota->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH') {{-- Laravel usa PATCH para actualizaciones parciales --}}

                        <div class="row">
                            {{-- Columna para la foto --}}
                            <div class="col-md-4 text-center">
                                <label for="foto" class="form-label">Foto Actual</label><br>
                                <img src="{{ $mascota->foto ? asset('storage/' . $mascota->foto) : asset('images/placeholder-pet.png') }}" 
                                     alt="Foto de {{ $mascota->nombre }}" 
                                     class="img-fluid rounded mb-2" 
                                     style="max-height: 180px; object-fit: cover;">
                                <input class="form-control mt-2" type="file" id="foto" name="foto">
                                <small class="form-text text-muted">Dejar en blanco para no cambiar la foto.</small>
                                @error('foto')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Columna para los datos --}}
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="nombre" class="form-label">Nombre</label>
                                    <input type="text" class="form-control" id="nombre" name="nombre" value="{{ old('nombre', $mascota->nombre) }}" required>
                                    @error('nombre')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="especie" class="form-label">Especie</label>
                                    <select class="form-select" id="especie" name="especie" required>
                                        <option value="Perro" {{ old('especie', $mascota->especie) == 'Perro' ? 'selected' : '' }}>Perro</option>
                                        <option value="Gato" {{ old('especie', $mascota->especie) == 'Gato' ? 'selected' : '' }}>Gato</option>
                                        <option value="Hamster" {{ old('especie', $mascota->especie) == 'Hamster' ? 'selected' : '' }}>Hamster</option>
                                        <option value="Conejo" {{ old('especie', $mascota->especie) == 'Conejo' ? 'selected' : '' }}>Conejo</option>
                                    </select>
                                    @error('especie')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="raza" class="form-label">Raza</label>
                                        <input type="text" class="form-control" id="raza" name="raza" value="{{ old('raza', $mascota->raza) }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="edad" class="form-label">Edad (años)</label>
                                        <input type="number" class="form-control" id="edad" name="edad" value="{{ old('edad', $mascota->edad) }}">
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="peso" class="form-label">Peso (kg)</label>
                                        <input type="number" step="0.1" class="form-control" id="peso" name="peso" value="{{ old('peso', $mascota->peso) }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
                                        <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" value="{{ old('fecha_nacimiento', $mascota->fecha_nacimiento) }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-end mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Actualizar Datos
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
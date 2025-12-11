<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Mascota - Pet Care</title>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('css/panel.css') }}">
    <link rel="stylesheet" href="{{ asset('css/perfil.css') }}">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="panel-container">
        
        @include('layouts.sidebar')

        <main class="panel-main">
            
            @php
                $user = Auth::user();
                $rutaVolver = ($user->rol === 'admin' || $user->rol === 'veterinario') 
                    ? route('veterinario.mascotas.index') 
                    : route('mascotas.index');
            @endphp

            <header class="panel-header">
                <div class="header-title">
                    <h3>Editar Paciente: {{ $mascota->nombre }}</h3>
                    <p>Actualiza la información de la ficha clínica.</p>
                </div>
                <div class="header-actions">
                    <a href="{{ $rutaVolver }}" class="btn btn-secondary text-white">
                        <i class="bi bi-arrow-left"></i> Volver
                    </a>
                </div>
            </header>

            <div class="panel-content">
                <div class="profile-card">
                    <form action="{{ route('mascotas.update', $mascota->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- DATOS MASCOTA -->
                        <h5>Datos del Paciente</h5>
                        <p class="subtitle">Modifica los datos necesarios.</p>
                        
                        <div class="row" style="display: flex; gap: 20px; flex-wrap: wrap;">
                            <div class="form-group" style="flex: 1; min-width: 300px;">
                                <label for="nombre" class="form-label">Nombre</label>
                                <input type="text" id="nombre" name="nombre" class="form-control" 
                                       value="{{ old('nombre', $mascota->nombre) }}" 
                                       required placeholder="Ej: Max">
                                @error('nombre') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                            </div>

                            <div class="form-group" style="flex: 1; min-width: 300px;">
                                <label for="especie" class="form-label">Especie</label>
                                <select id="especie" name="especie" class="form-control" required>
                                    <option value="">-- Selecciona --</option>
                                    @foreach(['Perro', 'Gato', 'Hamster', 'Conejo'] as $esp)
                                        <option value="{{ $esp }}" {{ old('especie', $mascota->especie) == $esp ? 'selected' : '' }}>
                                            {{ $esp }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('especie') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="row" style="display: flex; gap: 20px; flex-wrap: wrap;">
                            <div class="form-group" style="flex: 1; min-width: 250px;">
                                <label for="raza" class="form-label">Raza</label>
                                <input type="text" id="raza" name="raza" class="form-control" 
                                       value="{{ old('raza', $mascota->raza) }}" 
                                       required placeholder="Ej: Labrador">
                                @error('raza') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                            </div>

                            <div class="form-group" style="flex: 1; min-width: 250px;">
                                <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
                                <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" 
                                       class="form-control" 
                                       value="{{ old('fecha_nacimiento', $mascota->fecha_nacimiento ? \Carbon\Carbon::parse($mascota->fecha_nacimiento)->format('Y-m-d') : '') }}" 
                                       required
                                       max="{{ date('Y-m-d') }}"> 
                                @error('fecha_nacimiento') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="row" style="display: flex; gap: 20px; flex-wrap: wrap;">
                            <div class="form-group" style="flex: 1; min-width: 150px;">
                                <label for="edad" class="form-label">Edad (Años)</label>
                                <input type="number" id="edad" name="edad" class="form-control" 
                                       value="{{ old('edad', $mascota->edad) }}" 
                                       min="0" readonly style="background-color: #f3f4f6;">
                                <small class="text-muted">Se calcula automáticamente.</small>
                            </div>

                            <div class="form-group" style="flex: 1; min-width: 150px;">
                                <label for="peso" class="form-label">Peso (kg)</label>
                                <input type="number" step="0.01" id="peso" name="peso" class="form-control" 
                                       value="{{ old('peso', $mascota->peso) }}" 
                                       required min="0.1">
                                @error('peso') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <!-- FOTO -->
                        <div class="form-group">
                            <label for="foto" class="form-label">Foto de la Mascota</label>
                            
                            @if($mascota->foto)
                                <div class="mb-3">
                                    <p class="text-sm text-gray-500 mb-1">Foto Actual:</p>
                                    <img src="{{ asset('storage/' . $mascota->foto) }}" alt="Foto actual" 
                                         style="height: 100px; border-radius: 8px; border: 1px solid #ddd;">
                                </div>
                            @endif

                            <input type="file" id="foto" name="foto" class="form-control" accept="image/*">
                            
                            <div class="mt-3">
                                <img id="photo-preview" src="#" alt="Vista previa" class="d-none" 
                                     style="max-height: 200px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); display: none;">
                            </div>
                            @error('foto') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                        </div>

                        <!-- BOTONES -->
                        <div class="d-flex align-items-center mt-4 pt-3 border-top" style="justify-content: flex-end; gap: 15px;">
                            <a href="{{ $rutaVolver }}" class="btn btn-secondary text-white" style="text-decoration: none; padding: 0.75rem 1.5rem; border-radius: 6px; background-color: #6c757d;">Cancelar</a>
                            
                            <button type="submit" class="btn-save">
                                <i class="bi bi-arrow-repeat"></i> Actualizar Mascota
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </main>
    </div>

    <script src="{{ asset('js/mascota.js') }}"></script>
</body>
</html>
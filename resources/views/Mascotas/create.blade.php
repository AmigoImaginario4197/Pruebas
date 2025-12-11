<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Añadir Mascota - Pet Care</title>
    
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
                    <h3>Registrar Paciente</h3>
                    <p>Completa los datos para crear una nueva ficha clínica.</p>
                </div>
                <div class="header-actions">
                    <a href="{{ $rutaVolver }}" class="btn btn-secondary text-white">
                        <i class="bi bi-arrow-left"></i> Volver
                    </a>
                </div>
            </header>

            <div class="panel-content">
                <div class="profile-card">
                    <form action="{{ route('mascotas.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- SECCIÓN PROPIETARIO (Solo Admin/Vet) -->
                        @if($user->rol === 'veterinario' || $user->rol === 'admin')
                            <div style="background-color: #f8fafc; padding: 20px; border-radius: 8px; border: 1px solid #e2e8f0; margin-bottom: 25px;">
                                <h5 style="color: #4f46e5; margin-bottom: 15px; font-size: 1rem;">
                                    <i class="bi bi-person-badge-fill"></i> Asignar Propietario
                                </h5>
                                <div class="form-group mb-0">
                                    <label for="user_id" class="form-label">Seleccionar Cliente</label>
                                    <select name="user_id" id="user_id" class="form-control" required>
                                        <option value="">-- Buscar cliente registrado --</option>
                                        @foreach($clientes as $cliente)
                                            <option value="{{ $cliente->id }}" {{ old('user_id') == $cliente->id ? 'selected' : '' }}>
                                                {{ $cliente->name }} ({{ $cliente->nif ?? 'Sin DNI' }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('user_id') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        @else
                            <input type="hidden" name="user_id" value="{{ Auth::id() }}">
                        @endif

                        <!-- DATOS MASCOTA -->
                        <h5>Datos del Paciente</h5>
                        <p class="subtitle">Información básica y características.</p>
                        
                        <div class="row" style="display: flex; gap: 20px; flex-wrap: wrap;">
                            <div class="form-group" style="flex: 1; min-width: 300px;">
                                <label for="nombre" class="form-label">Nombre</label>
                                <!-- YA TENÍA REQUIRED -->
                                <input type="text" id="nombre" name="nombre" class="form-control" value="{{ old('nombre') }}" required placeholder="Ej: Max">
                                @error('nombre') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                            </div>

                            <div class="form-group" style="flex: 1; min-width: 300px;">
                                <label for="especie" class="form-label">Especie</label>
                                <!-- YA TENÍA REQUIRED -->
                                <select id="especie" name="especie" class="form-control" required>
                                    <option value="">-- Selecciona --</option>
                                    <option value="Perro" {{ old('especie') == 'Perro' ? 'selected' : '' }}>Perro</option>
                                    <option value="Gato" {{ old('especie') == 'Gato' ? 'selected' : '' }}>Gato</option>
                                    <option value="Hamster" {{ old('especie') == 'Hamster' ? 'selected' : '' }}>Hamster</option>
                                    <option value="Conejo" {{ old('especie') == 'Conejo' ? 'selected' : '' }}>Conejo</option>
                                </select>
                                @error('especie') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="row" style="display: flex; gap: 20px; flex-wrap: wrap;">
                            <div class="form-group" style="flex: 1; min-width: 250px;">
                                <label for="raza" class="form-label">Raza</label>
                                <!-- AÑADIDO REQUIRED AQUÍ -->
                                <input type="text" id="raza" name="raza" class="form-control" value="{{ old('raza') }}" required placeholder="Ej: Labrador">
                                @error('raza') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                            </div>

                            <div class="form-group" style="flex: 1; min-width: 250px;">
                                <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
                                <!-- AÑADIDO REQUIRED AQUÍ -->
                                <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" 
                                       class="form-control" 
                                       value="{{ old('fecha_nacimiento') }}" 
                                       required
                                       max="{{ date('Y-m-d') }}"> 
                                @error('fecha_nacimiento') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="row" style="display: flex; gap: 20px; flex-wrap: wrap;">
                            <div class="form-group" style="flex: 1; min-width: 150px;">
                                <label for="edad" class="form-label">Edad (Años)</label>
                                <input type="number" id="edad" name="edad" class="form-control" value="{{ old('edad') }}" min="0" readonly style="background-color: #f3f4f6;">
                                <small class="text-muted">Se calcula automáticamente.</small>
                                @error('edad') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                            </div>

                            <div class="form-group" style="flex: 1; min-width: 150px;">
                                <label for="peso" class="form-label">Peso (kg)</label>
                                <!-- AÑADIDO REQUIRED AQUÍ -->
                                <input type="number" step="0.01" id="peso" name="peso" class="form-control" value="{{ old('peso') }}" required min="0.1" placeholder="0.00">
                                @error('peso') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <!-- FOTO (ESTE SIGUE SIENDO OPCIONAL) -->
                        <div class="form-group">
                            <label for="foto" class="form-label">Foto de la Mascota</label>
                            <input type="file" id="foto" name="foto" class="form-control" accept="image/*">
                            
                            <div class="mt-3">
                                <img id="photo-preview" src="#" alt="Vista previa" 
                                     style="max-height: 200px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); display: none;">
                            </div>
                            @error('foto') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                        </div>

                        <!-- BOTONES -->
                        <div class="d-flex align-items-center mt-4 pt-3 border-top" style="justify-content: flex-end; gap: 15px;">
                            <a href="{{ $rutaVolver }}" class="btn btn-secondary text-white" style="text-decoration: none; padding: 0.75rem 1.5rem; border-radius: 6px; background-color: #6c757d;">Cancelar</a>
                            
                            <button type="submit" class="btn-save">
                                <i class="bi bi-save"></i> Guardar Mascota
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </main>
    </div>

    <!-- JS Separado -->
    <script src="{{ asset('js/mascota.js') }}"></script>
</body>
</html>
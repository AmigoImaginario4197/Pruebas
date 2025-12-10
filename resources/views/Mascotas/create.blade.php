<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Añadir Mascota - Pet Care</title>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <!-- Cargamos nuestro CSS maestro -->
    <link rel="stylesheet" href="{{ asset('css/panel.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="panel-container">
        
        @include('layouts.sidebar')

        <main class="panel-main">
            <header class="panel-header">
                <div class="header-title">
                    <h3>Registrar Paciente</h3>
                    <p>Completa los datos para crear una nueva ficha clínica.</p>
                </div>
                <div class="header-actions">
                    {{-- Botón Volver inteligente (dependiendo del rol) --}}
                    @if(Auth::user()->rol === 'veterinario')
                        <a href="{{ route('veterinario.mascotas.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Volver a Pacientes
                        </a>
                    @else
                        <a href="{{ route('mascotas.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Volver
                        </a>
                    @endif
                </div>
            </header>

            <div class="panel-content">
                <div class="card shadow-sm border-0 p-4">
                    
                    {{-- Errores generales (opcional, pero útil) --}}
                    @if ($errors->any())
                        <div class="alert alert-danger mb-4">
                            <strong>Por favor revisa los siguientes errores:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('mascotas.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        {{-- ======================================================= --}}
                        {{--    SECCIÓN DE ASIGNACIÓN DE DUEÑO (SOLO VET/ADMIN)      --}}
                        {{-- ======================================================= --}}
                        @if(Auth::user()->rol === 'veterinario' || Auth::user()->rol === 'admin')
                            <div class="card bg-light border-0 mb-4">
                                <div class="card-body">
                                    <h5 class="card-title fw-bold text-primary mb-3">
                                        <i class="bi bi-person-badge"></i> Propietario de la Mascota
                                    </h5>
                                    
                                    <div class="form-group">
                                        <label for="user_id" class="form-label">Seleccionar Cliente</label>
                                        <select name="user_id" id="user_id" class="form-select form-select-lg @error('user_id') is-invalid @enderror" required>
                                            <option value="">-- Buscar cliente registrado --</option>
                                            @foreach($clientes as $cliente)
                                                <option value="{{ $cliente->id }}" {{ old('user_id') == $cliente->id ? 'selected' : '' }}>
                                                    {{ $cliente->name }} ({{ $cliente->nif ?? 'Sin DNI' }})
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('user_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- ======================================================= --}}
                        {{--    DATOS DE LA MASCOTA                                  --}}
                        {{-- ======================================================= --}}
                        <h5 class="mb-3 border-bottom pb-2">Datos del Paciente(Mascota)</h5>
                        
                        <div class="row g-3">
                            {{-- Nombre --}}
                            <div class="col-md-6">
                                <label for="nombre" class="form-label fw-bold">Nombre</label>
                                <input type="text" id="nombre" name="nombre" class="form-control @error('nombre') is-invalid @enderror" value="{{ old('nombre') }}" required>
                                @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            {{-- Especie --}}
                            <div class="col-md-6">
                                <label for="especie" class="form-label fw-bold">Especie</label>
                                <select id="especie" name="especie" class="form-select @error('especie') is-invalid @enderror" required>
                                    <option value="">-- Selecciona una especie --</option>
                                    <option value="Perro" {{ old('especie') == 'Perro' ? 'selected' : '' }}>Perro</option>
                                    <option value="Gato" {{ old('especie') == 'Gato' ? 'selected' : '' }}>Gato</option>
                                    <option value="Hamster" {{ old('especie') == 'Hamster' ? 'selected' : '' }}>Hamster</option>
                                    <option value="Conejo" {{ old('especie') == 'Conejo' ? 'selected' : '' }}>Conejo</option>
                                </select>
                                @error('especie') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            
                            {{-- Raza --}}
                            <div class="col-md-6">
                                <label for="raza" class="form-label fw-bold">Raza</label>
                                <input type="text" id="raza" name="raza" class="form-control @error('raza') is-invalid @enderror" value="{{ old('raza') }}">
                                @error('raza') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            {{-- Fecha Nacimiento --}}
                            <div class="col-md-6">
                                <label for="fecha_nacimiento" class="form-label fw-bold">Fecha de Nacimiento</label>
                                <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" class="form-control @error('fecha_nacimiento') is-invalid @enderror" value="{{ old('fecha_nacimiento') }}">
                                @error('fecha_nacimiento') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            {{-- Edad --}}
                            <div class="col-md-6">
                                <label for="edad" class="form-label fw-bold">Edad (años)</label>
                                <input type="number" id="edad" name="edad" class="form-control @error('edad') is-invalid @enderror" value="{{ old('edad') }}" min="0">
                                @error('edad') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            {{-- Peso --}}
                            <div class="col-md-6">
                                <label for="peso" class="form-label fw-bold">Peso (kg)</label>
                                <input type="number" step="0.01" id="peso" name="peso" class="form-control @error('peso') is-invalid @enderror" value="{{ old('peso') }}" min="0">
                                @error('peso') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            {{-- Foto --}}
                            <div class="col-12">
                                <label for="foto" class="form-label fw-bold">Foto de la Mascota</label>
                                <input type="file" id="foto" name="foto" class="form-control @error('foto') is-invalid @enderror" onchange="previewImage(event)" accept="image/*">
                                <div class="mt-2">
                                    <img id="photo-preview" src="#" alt="Vista previa" class="img-thumbnail d-none" style="max-height: 200px; object-fit: cover;">
                                </div>
                                @error('foto') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                            <a href="{{ route('mascotas.index') }}" class="btn btn-light border">Cancelar</a>
                            <button type="submit" class="btn btn-primary px-4">Guardar Mascota</button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    {{-- Script para la vista previa de la imagen --}}
    <script>
        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function() {
                const output = document.getElementById('photo-preview');
                output.src = reader.result;
                output.classList.remove('d-none');
            };
            if(event.target.files[0]) {
                reader.readAsDataURL(event.target.files[0]);
            }
        }
    </script>
</body>
</html>
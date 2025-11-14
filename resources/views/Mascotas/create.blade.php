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
    
    {{-- Estilos adicionales para el formulario --}}
    <style>
        .form-container { background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); }
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .form-group { display: flex; flex-direction: column; }
        .form-group.full-width { grid-column: 1 / -1; }
        .form-group label { margin-bottom: 8px; font-weight: 600; color: #333; }
        .form-group input, .form-group select {
            padding: 10px; border: 1px solid #ccc; border-radius: 6px; font-size: 1rem;
        }
        .form-group .error-message { color: #ef4444; font-size: 0.875rem; margin-top: 5px; }
        #photo-preview { max-width: 150px; max-height: 150px; margin-top: 10px; border-radius: 8px; border: 1px dashed #ccc; padding: 5px; display: none; }
    </style>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="panel-container">
        
        {{-- Incluimos el menú reutilizable --}}
        @include('layouts.sidebar')

        <!-- Main Content -->
        <main class="panel-main">
            <header class="panel-header">
                <div class="header-title">
                    <h3>Añadir Nueva Mascota</h3>
                    <p>Completa los datos de tu nuevo compañero.</p>
                </div>
            </header>

            <div class="panel-content">
                <div class="form-container">
                    {{-- El atributo enctype es CRUCIAL para subir archivos --}}
                    <form action="{{ route('mascotas.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-grid">
                            
                            <div class="form-group">
                                <label for="nombre">Nombre</label>
                                <input type="text" id="nombre" name="nombre" value="{{ old('nombre') }}" required>
                                @error('nombre') <span class="error-message">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-group">
                                <label for="especie">Especie</label>
                                <select id="especie" name="especie" required>
                                    <option value="">-- Selecciona una especie --</option>
                                    <option value="Perro" {{ old('especie') == 'Perro' ? 'selected' : '' }}>Perro</option>
                                    <option value="Gato" {{ old('especie') == 'Gato' ? 'selected' : '' }}>Gato</option>
                                    <option value="Hamster" {{ old('especie') == 'Hamster' ? 'selected' : '' }}>Hamster</option>
                                    <option value="Conejo" {{ old('especie') == 'Conejo' ? 'selected' : '' }}>Conejo</option>
                                </select>
                                @error('especie') <span class="error-message">{{ $message }}</span> @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="raza">Raza</label>
                                <input type="text" id="raza" name="raza" value="{{ old('raza') }}">
                                @error('raza') <span class="error-message">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-group">
                                <label for="fecha_nacimiento">Fecha de Nacimiento</label>
                                <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" value="{{ old('fecha_nacimiento') }}">
                                @error('fecha_nacimiento') <span class="error-message">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-group">
                                <label for="edad">Edad (años)</label>
                                <input type="number" id="edad" name="edad" value="{{ old('edad') }}">
                                @error('edad') <span class="error-message">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-group">
                                <label for="peso">Peso (kg)</label>
                                <input type="number" step="0.01" id="peso" name="peso" value="{{ old('peso') }}">
                                @error('peso') <span class="error-message">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-group full-width">
                                <label for="foto">Foto de la Mascota</label>
                                <input type="file" id="foto" name="foto" onchange="previewImage(event)">
                                <img id="photo-preview" src="#" alt="Vista previa de la foto" />
                                @error('foto') <span class="error-message">{{ $message }}</span> @enderror
                            </div>
                            
                        </div>
                        <div style="text-align: right; margin-top: 20px;">
                            <a href="{{ route('mascotas.index') }}" class="btn btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Guardar Mascota</button>
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
                output.style.display = 'block';
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
</body>
</html>
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Nuevo Usuario - Pet Care</title>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/panel.css') }}">
    
    {{-- Esta línea es la que carga tu app.js (y por lo tanto, tu user-form-manager.js) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="panel-container">     
        @include('layouts.sidebar')

        <main class="panel-main">
            <header class="panel-header">
                <div class="header-title">
                    <h3>Crear Nuevo Usuario</h3>
                    <p>Rellena los datos para registrar una nueva cuenta.</p>
                </div>
            </header>

            <div class="panel-content">
                <div class="card">
                    <form action="{{ route('users.store') }}" method="POST">
                        @csrf

                        <div class="card-body">

                            {{-- =============================================== --}}
                            {{--  BLOQUE PARA MOSTRAR ERRORES (MUY IMPORTANTE)    --}}
                            {{-- =============================================== --}}
                            @if ($errors->any())
                                <div class="alert alert-danger mb-4">
                                    <h5 class="alert-heading">¡Error de validación!</h5>
                                    <p>Por favor, corrige los siguientes errores:</p>
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div class="row">
                                {{-- Campos de Nombre, Email, etc. --}}
                                <div class="col-md-6 mb-3"><label for="name" class="form-label">Nombre</label><input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}">@error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                                <div class="col-md-6 mb-3"><label for="email" class="form-label">Email</label><input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}">@error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                                <div class="col-md-6 mb-3"><label for="telefono" class="form-label">Teléfono</label><input type="text" class="form-control @error('telefono') is-invalid @enderror" id="telefono" name="telefono" value="{{ old('telefono') }}">@error('telefono')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                                <div class="col-md-6 mb-3"><label for="nif" class="form-label">NIF</label><input type="text" class="form-control @error('nif') is-invalid @enderror" id="nif" name="nif" value="{{ old('nif') }}">@error('nif')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                                <div class="col-md-12 mb-3"><label for="direccion" class="form-label">Dirección</label><input type="text" class="form-control @error('direccion') is-invalid @enderror" id="direccion" name="direccion" value="{{ old('direccion') }}">@error('direccion')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>

                                {{-- Rol del Usuario --}}
                                <div class="col-md-6 mb-3">
                                    <label for="rol" class="form-label">Rol del Usuario</label>
                                    <select name="rol" id="rolSelect" class="form-select @error('rol') is-invalid @enderror">
                                        <option value="cliente" @selected(old('rol', 'cliente') == 'cliente')>Cliente</option>
                                        <option value="veterinario" @selected(old('rol') == 'veterinario')>Veterinario</option>
                                        <option value="admin" @selected(old('rol') == 'admin')>Admin</option>
                                    </select>
                                    @error('rol') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                {{-- Campo de Especialidad (Oculto por defecto y con el id correcto) --}}
                                <div class="col-md-6 mb-3" id="especialidad-wrapper" style="display: none;">
                                    <label for="especialidad" class="form-label">Especialidad</label>
                                    <select name="especialidad" id="especialidad" class="form-select @error('especialidad') is-invalid @enderror">
                                        <option value="">Seleccione una especialidad</option>
                                        <option value="Cardiología" @selected(old('especialidad') == 'Cardiología')>Cardiología</option>
                                        <option value="Cirugía" @selected(old('especialidad') == 'Cirugía')>Cirugía</option>
                                        <option value="Dermatología" @selected(old('especialidad') == 'Dermatología')>Dermatología</option>
                                    </select>
                                    @error('especialidad') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                
                                <hr class="my-4">

                                {{-- Contraseña --}}
                                <div class="col-md-6 mb-3"><label for="password" class="form-label">Contraseña</label><input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password">@error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                                <div class="col-md-6 mb-3"><label for="password_confirmation" class="form-label">Confirmar Contraseña</label><input type="password" class="form-control" id="password_confirmation" name="password_confirmation"></div>
                            </div>
                        </div>

                        <div class="card-footer text-end">
                            <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Crear Usuario</button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    
</body>
</html>
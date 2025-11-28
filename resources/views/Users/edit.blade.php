<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar: {{ $user->name }} - Pet Care</title>
    {{-- Dependencias de Estilos --}}
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
                    <h3>Editar Usuario: {{ $user->name }}</h3>
                    <p>Modifica los datos del perfil de usuario.</p>
                </div>
            </header>

            <div class="panel-content">
                <div class="card">
                    <form action="{{ route('users.update', $user) }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <div class="card-body">
                            <div class="row">
                                {{-- Campos de Nombre, Email, Teléfono, NIF, Dirección --}}
                                <div class="col-md-6 mb-3"><label for="name" class="form-label">Nombre Completo</label><input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}">@error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror</div>
                                <div class="col-md-6 mb-3"><label for="email" class="form-label">Correo Electrónico</label><input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}">@error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror</div>
                                <div class="col-md-6 mb-3"><label for="telefono" class="form-label">Teléfono</label><input type="text" class="form-control @error('telefono') is-invalid @enderror" id="telefono" name="telefono" value="{{ old('telefono', $user->telefono) }}">@error('telefono') <div class="invalid-feedback">{{ $message }}</div> @enderror</div>
                                <div class="col-md-6 mb-3"><label for="nif" class="form-label">NIF</label><input type="text" class="form-control @error('nif') is-invalid @enderror" id="nif" name="nif" value="{{ old('nif', $user->nif) }}">@error('nif') <div class="invalid-feedback">{{ $message }}</div> @enderror</div>
                                <div class="col-md-12 mb-3"><label for="direccion" class="form-label">Dirección</label><input type="text" class="form-control @error('direccion') is-invalid @enderror" id="direccion" name="direccion" value="{{ old('direccion', $user->direccion) }}">@error('direccion') <div class="invalid-feedback">{{ $message }}</div> @enderror</div>

                                {{-- Rol del Usuario --}}
                                <div class="col-md-6 mb-3">
                                    <label for="rol" class="form-label">Rol del Usuario</label>
                                    {{-- CAMBIO: Añadido el ID 'rolSelect' para el JavaScript --}}
                                    <select name="rol" id="rolSelect" class="form-select @error('rol') is-invalid @enderror">
                                        <option value="cliente" @selected(old('rol', $user->rol) == 'cliente')>Cliente</option>
                                        <option value="veterinario" @selected(old('rol', $user->rol) == 'veterinario')>Veterinario</option>
                                        <option value="admin" @selected(old('rol', $user->rol) == 'admin')>Admin</option>
                                    </select>
                                    @error('rol') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                {{-- ========================================================= --}}
                                {{--              NUEVO CAMPO DE ESPECIALIDAD                --}}
                                {{-- ========================================================= --}}
                                <div class="col-md-6 mb-3" id="especialidad-wrapper">
                                    <label for="especialidad" class="form-label">Especialidad (solo para veterinarios)</label>
                                    <select name="especialidad" id="especialidad" class="form-select @error('especialidad') is-invalid @enderror">
                                        <option value="">Seleccione una especialidad</option>
                                        <option value="Cardiología" @selected(old('especialidad', $user->especialidad) == 'Cardiología')>Cardiología</option>
                                        <option value="Cirugía" @selected(old('especialidad', $user->especialidad) == 'Cirugía')>Cirugía</option>
                                        <option value="Dermatología" @selected(old('especialidad', $user->especialidad) == 'Dermatología')>Dermatología</option>
                                    </select>
                                    @error('especialidad') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                
                                <hr class="my-4">

                                {{-- Cambio de Contraseña --}}
                                <div class="col-md-6 mb-3"><label for="password" class="form-label">Nueva Contraseña</label><input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password"><small class="form-text text-muted">Dejar en blanco para no cambiar.</small>@error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror</div>
                                <div class="col-md-6 mb-3"><label for="password_confirmation" class="form-label">Confirmar Nueva Contraseña</label><input type="password" class="form-control" id="password_confirmation" name="password_confirmation"></div>
                            </div>
                        </div>

                        <div class="card-footer text-end">
                            <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

</body>
</html>
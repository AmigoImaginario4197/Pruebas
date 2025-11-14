<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pet Care - Registro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="{{ asset('css/auth.css') }}" rel="stylesheet">
    <style>
        .input-error { color: #dc3545; font-size: 0.875em; margin-top: 0.25rem; }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-logo">
                <img src="{{ asset('images/logo.png') }}" alt="Pet Care Logo">
            </div>
            <h1 class="auth-title">Crear Cuenta</h1>
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Name -->
                <div class="mb-4">
                    <label for="name" class="form-label">Nombre</label>
                    <input id="name" class="form-control" type="text" name="name" placeholder="Ingresa tu nombre completo" value="{{ old('name') }}" required autofocus autocomplete="name">
                    @error('name')<div class="input-error">{{ $message }}</div>@enderror
                </div>

                <!-- Email Address -->
                <div class="mb-4">
                    <label for="email" class="form-label">Correo Electrónico</label>
                    <input id="email" class="form-control" type="email" name="email" placeholder="Ingresa tu correo electrónico" value="{{ old('email') }}" required autocomplete="username">
                    @error('email')<div class="input-error">{{ $message }}</div>@enderror
                </div>

                <!-- NIF/DNI -->
                <div class="mb-4">
                    <label for="nif" class="form-label">NIF/DNI</label>
                    <input id="nif" class="form-control" type="text" name="nif" placeholder="Ingresa tu NIF o DNI" value="{{ old('nif') }}" required>
                    @error('nif')<div class="input-error">{{ $message }}</div>@enderror
                </div>

                <!-- Teléfono (CORREGIDO) -->
                <div class="mb-4">
                    <label for="telefono" class="form-label">Teléfono</label>
                    <input id="telefono" class="form-control" type="tel" name="telefono" placeholder="Ingresa tu número de teléfono" value="{{ old('telefono') }}" required>
                    @error('telefono')<div class="input-error">{{ $message }}</div>@enderror
                </div>

                <!-- Dirección (CORREGIDO) -->
                <div class="mb-4">
                    <label for="direccion" class="form-label">Dirección</label>
                    <input id="direccion" class="form-control" type="text" name="direccion" placeholder="Ingresa tu dirección completa" value="{{ old('direccion') }}" required>
                    @error('direccion')<div class="input-error">{{ $message }}</div>@enderror
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <label for="password" class="form-label">Contraseña</label>
                    <input id="password" class="form-control" type="password" name="password" placeholder="Crea una contraseña segura" required autocomplete="new-password">
                    @error('password')<div class="input-error">{{ $message }}</div>@enderror
                </div>

                <!-- Confirm Password -->
                <div class="mb-4">
                    <label for="password_confirmation" class="form-label">Confirmar Contraseña</label>
                    <input id="password_confirmation" class="form-control" type="password" name="password_confirmation" placeholder="Repite tu contraseña" required autocomplete="new-password">
                    @error('password_confirmation')<div class="input-error">{{ $message }}</div>@enderror
                </div>

                <button type="submit" class="btn btn-auth">Registrarse</button>

                <div class="auth-links">
                    <a href="{{ route('login') }}">¿Ya tienes una cuenta? Inicia sesión</a>
                </div>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
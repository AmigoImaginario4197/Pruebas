<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pet Care - Restablecer Contraseña</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts: Roboto -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="{{ asset('css/auth.css') }}" rel="stylesheet">
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <!-- Logo -->
            <div class="auth-logo">
                <img src="{{ asset('images/logo.png') }}" alt="Pet Care Logo">
            </div>
            
            <h1 class="auth-title">Restablecer Contraseña</h1>

            <form method="POST" action="{{ route('password.store') }}">
                @csrf

                <!-- Password Reset Token -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <!-- Email Address -->
                <div class="mb-4">
                    <label for="email" class="form-label">Correo Electrónico</label>
                    <input id="email" 
                           class="form-control" 
                           type="email" 
                           name="email" 
                           placeholder="Ingresa tu correo electrónico"
                           value="{{ old('email', $request->email) }}" 
                           required 
                           autofocus 
                           autocomplete="username">
                    @error('email')
                        <div class="input-error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <label for="password" class="form-label">Nueva Contraseña</label>
                    <input id="password" 
                           class="form-control"
                           type="password"
                           name="password"
                           placeholder="Crea tu nueva contraseña"
                           required 
                           autocomplete="new-password">
                    @error('password')
                        <div class="input-error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="mb-4">
                    <label for="password_confirmation" class="form-label">Confirmar Nueva Contraseña</label>
                    <input id="password_confirmation" 
                           class="form-control"
                           type="password"
                           name="password_confirmation" 
                           placeholder="Repite tu nueva contraseña"
                           required 
                           autocomplete="new-password">
                    @error('password_confirmation')
                        <div class="input-error">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-auth">
                    Restablecer Contraseña
                </button>

                <!-- Login Link -->
                <div class="auth-links">
                    <a href="{{ route('login') }}">
                        Volver al inicio de sesión
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
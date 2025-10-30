<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pet Care - Recuperar Contraseña</title>
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
            
            <h1 class="auth-title">Recuperar Contraseña</h1>

            <div class="auth-subtitle">
                {{ __('¿Olvidaste tu contraseña? No hay problema. Solo dinos tu dirección de correo y te enviaremos un enlace para restablecer la contraseña que te permitirá elegir una nueva.') }}
            </div>

            <!-- Session Status -->
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <!-- Email Address -->
                <div class="mb-4">
                    <label for="email" class="form-label">Correo Electrónico</label>
                    <input id="email" 
                           class="form-control" 
                           type="email" 
                           name="email" 
                           placeholder="Ingresa tu correo electrónico"
                           value="{{ old('email') }}" 
                           required 
                           autofocus>
                    @error('email')
                        <div class="input-error">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-auth">
                    Enviar Enlace de Restablecimiento
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
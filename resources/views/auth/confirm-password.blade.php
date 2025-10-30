<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pet Care - Confirmar Contraseña</title>
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
            
            <h1 class="auth-title">Confirmar Contraseña</h1>

            <div class="auth-subtitle">
                {{ __('Esta es un área segura de la aplicación. Por favor confirma tu contraseña antes de continuar.') }}
            </div>

            <form method="POST" action="{{ route('password.confirm') }}">
                @csrf

                <!-- Password -->
                <div class="mb-4">
                    <label for="password" class="form-label">Contraseña</label>
                    <input id="password" 
                           class="form-control"
                           type="password"
                           name="password"
                           placeholder="Ingresa tu contraseña"
                           required 
                           autocomplete="current-password">
                    @error('password')
                        <div class="input-error">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-auth">
                    Confirmar
                </button>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
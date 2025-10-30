<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pet Care - Iniciar Sesión</title>
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
            
            <h1 class="auth-title">Iniciar Sesión</h1>

            <!-- Session Status -->
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email Address -->
                <div class="mb-4">
                    <label for="email" class="form-label">Correo</label>
                    <input id="email" 
                           class="form-control" 
                           type="email" 
                           name="email" 
                           placeholder="Ingresa tu correo"
                           value="{{ old('email') }}" 
                           required 
                           autofocus 
                           autocomplete="username">
                    @error('email')
                        <div class="input-error">{{ $message }}</div>
                    @enderror
                </div>

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

                <!-- Remember Me -->
                <div class="checkbox-container">
                    <input id="remember_me" 
                           type="checkbox" 
                           class="form-check-input" 
                           name="remember">
                    <label for="remember_me" class="form-check-label">
                        Recuérdame
                    </label>
                </div>

                <button type="submit" class="btn btn-auth">
                    Iniciar Sesión
                </button>

                <!-- Forgot Password & Register Links -->
                <div class="auth-links">
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}">
                            ¿Has olvidado tu contraseña?
                        </a>
                        <br>
                    @endif
                    <a href="{{ route('register') }}">
                        ¿No tienes cuenta? Crea una
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
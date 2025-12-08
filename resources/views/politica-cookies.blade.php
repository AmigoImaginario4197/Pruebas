<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Política de Cookies - PetCare</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
</head>
<body>
   {{-- === TU ENCABEZADO === --}}
    <header class="header bg-primary text-white py-3">
        <div class="custom-header-container">
            <div class="logo">
                <img src="{{ asset('images/logo.png') }}" alt="Pet Care Logo" class="header-logo">
            </div>
            <nav class="nav">
                <a href="{{ route('home') }}" class="text-white nav-link">Inicio</a>
                <a href="{{ route('presentacion') }}" class="text-white nav-link">Presentación</a>
                <a href="{{ route('faqs') }}" class="text-white nav-link">FAQs</a>
                <a href="{{ route('contacto') }}" class="text-white nav-link">Contacto</a>
            </nav>
            <div class="auth-buttons d-flex align-items-center">
                <a href="{{ route('login') }}" class="btn btn-outline-light me-2 auth-btn">Iniciar Sesión</a>
                <a href="{{ route('register') }}" class="btn btn-light auth-btn">Registrarse</a>
            </div>
        </div>
    </header>

    <main class="main-content">
        <div class="container py-5">
            <h1 class="mb-4">Política de Cookies</h1>
            <p><strong>Última actualización:</strong> {{ date('d/m/Y') }}</p>
            <p>Nuestro sitio web utiliza cookies para mejorar la experiencia del usuario.</p>

            <h4 class="mt-4">1. ¿Qué son las cookies?</h4>
            <p>Una cookie es un pequeño archivo que se almacena en su navegador para recordar información sobre su visita.</p>

            <h4 class="mt-4">2. ¿Qué cookies utilizamos?</h4>
            <ul>
                <li><strong>Cookies técnicas o esenciales:</strong> Son necesarias para el funcionamiento del sitio. La más importante es la cookie de sesión (por defecto, `veterinaria_session_cookie`), que le permite mantenerse conectado a su cuenta de forma segura. Sin esta cookie, no podría acceder a su panel de usuario.</li>
                <li><strong>Cookies de preferencias:</strong> (Si las tuvieras) Guardan sus preferencias, como el idioma o la región.</li>
                <li><strong>Cookies de análisis:</strong> (Si las tuvieras) Nos ayudan a entender cómo interactúan los usuarios con el sitio web, de forma anónima.</li>
            </ul>

            <h4 class="mt-4">3. Gestión de Cookies</h4>
            <p>Puede configurar su navegador para bloquear o eliminar cookies. Sin embargo, si bloquea las cookies técnicas, es posible que no pueda iniciar sesión o utilizar funciones esenciales de nuestra plataforma.</p>
        </div>
    </main>

   {{-- === TU PIE DE PÁGINA === --}}
    <footer class="footer bg-dark text-white text-center py-3">
        <div class="footer-links">
            <a href="{{ route('legal.aviso') }}">Aviso Legal</a>
            <a href="{{ route('legal.privacidad') }}">Política de Privacidad</a>
            <a href="{{ route('legal.politica-cookies') }}">Política de Cookies</a>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
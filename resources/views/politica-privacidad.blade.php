<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Política de Privacidad - PetCare</title>
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
            <h1 class="mb-4">Política de Privacidad</h1>
            <p><strong>Última actualización:</strong> {{ date('d/m/Y') }}</p>
            <p>En PetCare, nos comprometemos a proteger su privacidad. Esta política detalla cómo gestionamos sus datos personales al usar nuestros servicios.</p>

            <h4 class="mt-4">1. ¿Qué datos recopilamos?</h4>
            <p>Recopilamos los datos que usted nos proporciona directamente al registrarse (nombre, email), al registrar a su mascota (nombre, especie, etc.) y al agendar una cita. También podemos recopilar datos técnicos de navegación.</p>

            <h4 class="mt-4">2. ¿Cómo usamos sus datos?</h4>
            <p>Sus datos son utilizados para gestionar su cuenta, las citas, procesar pagos y comunicarnos con usted sobre recordatorios o información relevante para la salud de su mascota.</p>

            <h4 class="mt-4">3. Divulgación de datos</h4>
            <p>No compartiremos sus datos con terceros, excepto cuando sea necesario para prestar el servicio (ej. pasarela de pago Stripe) o por obligación legal.</p>
            
            <h4 class="mt-4">4. Seguridad de los datos</h4>
            <p>Hemos implementado medidas de seguridad técnicas y organizativas para proteger sus datos personales contra pérdida, uso no autorizado o acceso accidental.</p>

            <h4 class="mt-4">5. Sus Derechos</h4>
            <p>Tiene derecho a acceder, rectificar o suprimir sus datos personales. Puede ejercer estos derechos contactándonos a <strong>petcareoficialtm@gmail.com</strong>.</p>
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
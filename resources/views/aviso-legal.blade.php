<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aviso Legal - PetCare</title>
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

    {{-- === CONTENIDO PRINCIPAL === --}}
    <main class="main-content">
        <div class="container py-5">
            <h1 class="mb-4">Aviso Legal</h1>
            <p><strong>Última actualización:</strong> {{ date('d/m/Y') }}</p>

            <h4 class="mt-4">1. Datos Identificativos</h4>
            <p>En cumplimiento con el deber de información, a continuación se reflejan los siguientes datos: la empresa titular de este sitio web (en adelante, el Sitio Web) es <strong>PetCare S.L.</strong>, con domicilio a estos efectos en Av. de la Bahía, 123 - 11001 Cádiz, España. Correo electrónico de contacto: <strong>petcareoficialtm@gmail.com</strong>.</p>

            <h4 class="mt-4">2. Usuarios</h4>
            <p>El acceso y/o uso de este portal atribuye la condición de USUARIO, que acepta, desde dicho acceso y/o uso, las Condiciones Generales de Uso aquí reflejadas.</p>

            <h4 class="mt-4">3. Uso del Portal</h4>
            <p>El Sitio Web proporciona acceso a información y servicios (en adelante, "los contenidos") pertenecientes a PetCare. El USUARIO asume la responsabilidad del uso del portal, que se extiende al registro necesario para acceder a determinados servicios. En dicho registro, el USUARIO será responsable de aportar información veraz y lícita.</p>
            
            <h4 class="mt-4">4. Propiedad Intelectual e Industrial</h4>
            <p>PetCare es titular de todos los derechos de propiedad intelectual e industrial de su página web, así como de los elementos contenidos en la misma (imágenes, software, textos, marcas, etc.).</p>

            <h4 class="mt-4">5. Exclusión de Garantías y Responsabilidad</h4>
            <p>PetCare no se hace responsable de los daños y perjuicios de cualquier naturaleza que pudieran ocasionar errores u omisiones en los contenidos, falta de disponibilidad del portal o la transmisión de virus, a pesar de haber adoptado las medidas tecnológicas necesarias para evitarlo.</p>

            <h4 class="mt-4">6. Legislación Aplicable</h4>
            <p>La relación entre PetCare y el USUARIO se regirá por la normativa española vigente y cualquier controversia se someterá a los Juzgados y tribunales de la ciudad de Cádiz.</p>
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
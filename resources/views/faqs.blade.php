<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preguntas Frecuentes - PetCare</title>
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
                <a href="{{ route('faqs') }}" class="text-white nav-link fw-bold">FAQs</a>
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
            <div class="text-center mb-5">
                <h2 class="section-title">Preguntas Frecuentes</h2>
                <p class="text-muted col-lg-8 mx-auto">Respuestas a las dudas más comunes.</p>
            </div>
            <div class="accordion" id="faqAccordion">
                <div class="accordion-item shadow-sm mb-3">
                    <h2 class="accordion-header"><button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne">¿Cómo agendo una cita?</button></h2>
                    <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion"><div class="accordion-body">Regístrate en nuestra plataforma, ve a tu panel y selecciona "Nueva Cita". Podrás ver la disponibilidad de nuestros veterinarios y elegir el horario que te convenga.</div></div>
                </div>
                <div class="accordion-item shadow-sm mb-3">
                    <h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo">¿Atienden urgencias?</button></h2>
                    <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#faqAccordion"><div class="accordion-body">Sí, ofrecemos servicio de urgencias. Para una atención inmediata, llama a nuestro teléfono de emergencias: <strong>(555) 123-4567</strong>.</div></div>
                </div>
                <div class="accordion-item shadow-sm mb-3">
                    <h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree">¿Necesito registrar a mi mascota antes?</button></h2>
                    <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#faqAccordion"><div class="accordion-body">Sí. El registro nos permite tener un historial médico completo y agilizar el proceso durante la consulta.</div></div>
                </div>
            </div>
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
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiénes Somos - PetCare</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
    <link href="{{ asset('css/inicio.css') }}" rel="stylesheet">
</head>
<body>
    {{-- === ENCABEZADO === --}}
    <header class="header bg-primary text-white py-3">
        <div class="custom-header-container">
            <div class="logo">
                <img src="{{ asset('images/logo.png') }}" alt="Pet Care Logo" class="header-logo">
            </div>
            <nav class="nav">
                <a href="{{ route('home') }}" class="text-white nav-link">Inicio</a>
                <a href="{{ route('presentacion') }}" class="text-white nav-link fw-bold">Presentación</a>
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
            <section class="mb-5 text-center">
                <h2 class="section-title">Nuestra Misión</h2>
                <p class="col-lg-8 mx-auto text-muted lead">
                    Ofrecer una atención veterinaria excepcional y compasiva, combinando tecnología de punta con un profundo amor por los animales.
                </p>
            </section>
            <section>
                <h2 class="section-title text-center">Conoce al Equipo</h2>
                <div class="row">
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 text-center border-0 shadow-sm">
                            <img src="https://images.unsplash.com/photo-1583269556943-66094215039a?auto=format&fit=crop&w=800&q=80" class="card-img-top" alt="Veterinario 1">
                            <div class="card-body">
                                <h5 class="card-title fw-bold">Dra. Ana Pérez</h5>
                                <p class="card-text text-primary">Medicina Interna</p>
                                <p class="card-text small text-muted">Fundadora y especialista con más de 15 años de experiencia.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 text-center border-0 shadow-sm">
                            <img src="https://images.unsplash.com/photo-1612531386530-97286d97c2d2?auto=format&fit=crop&w=800&q=80" class="card-img-top" alt="Veterinario 2">
                            <div class="card-body">
                                <h5 class="card-title fw-bold">Dr. Carlos López</h5>
                                <p class="card-text text-primary">Cirugía y Traumatología</p>
                                <p class="card-text small text-muted">Experto en cirugías complejas y recuperación postoperatoria.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 text-center border-0 shadow-sm">
                            <img src="https://images.unsplash.com/photo-1582213782179-e0d53f98f2ca?auto=format&fit=crop&w=800&q=80" class="card-img-top" alt="Personal">
                            <div class="card-body">
                                <h5 class="card-title fw-bold">Equipo de Asistencia</h5>
                                <p class="card-text text-primary">Atención y Cuidados</p>
                                <p class="card-text small text-muted">Nuestro equipo de apoyo, siempre listos para ayudarte con una sonrisa.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>

    {{-- === PIE DE PÁGINA === --}}
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
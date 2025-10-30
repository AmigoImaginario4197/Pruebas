<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pet Care - Inicio</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts: Roboto -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
</head>
<body>
    <!-- Encabezado -->
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

    <!-- Contenido principal -->
    <main class="main-content">
        <div class="container">
            <section class="hero py-5">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h1 class="display-4 text-primary mb-4">Pet Care</h1>
                        <p class="lead text-muted">
                            Gestiona, organiza y supervisa el cuidado integral de las mascotas con PetCare Manager, una herramienta que promueve la salud animal, la responsabilidad del dueño y la eficiencia veterinaria.
                        </p>
                    </div>
                    <div class="col-md-6">
                        <img src="{{ asset('images/FondoPerro.jpg') }}" alt="Perro en cama" class="img-fluid rounded shadow main-image">
                    </div>
                </div>
            </section>
        </div>
    </main>

    <!-- Pie de página -->
    <footer class="footer bg-dark text-white text-center py-3">
        <div class="footer-links">
            <a href="{{ route('aviso-legal') }}" class="text-white mx-2 footer-link">Aviso legal</a> |
            <a href="{{ route('politica-privacidad') }}" class="text-white mx-2 footer-link">Política de privacidad</a> |
            <a href="{{ route('politica-cookies') }}" class="text-white mx-2 footer-link">Política de cookies</a>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contacto - PetCare</title>
    
    {{-- Tus imports originales --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
</head>
<body>
    {{-- Tu Header Original --}}
    <header class="header bg-primary text-white py-3">
        <div class="custom-header-container">
            <div class="logo">
                <img src="{{ asset('images/logo.png') }}" alt="Pet Care Logo" class="header-logo">
            </div>
            <nav class="nav">
                <a href="{{ route('home') }}" class="text-white nav-link">Inicio</a>
                <a href="{{ route('presentacion') }}" class="text-white nav-link">Presentación</a>
                <a href="{{ route('faqs') }}" class="text-white nav-link">FAQs</a>
                <a href="{{ route('contacto') }}" class="text-white nav-link fw-bold">Contacto</a>
            </nav>
            <div class="auth-buttons d-flex align-items-center">
                <a href="{{ route('login') }}" class="btn btn-outline-light me-2 auth-btn">Iniciar Sesión</a>
                <a href="{{ route('register') }}" class="btn btn-light auth-btn">Registrarse</a>
            </div>
        </div>
    </header>

    {{-- Contenido Principal --}}
    <main class="main-content">
        <div class="container py-5">
            <div class="text-center mb-5">
                <h2 class="section-title">Ponte en Contacto</h2>
                <p class="text-muted col-lg-8 mx-auto">Estamos aquí para ayudarte. Rellena el formulario o utiliza nuestros datos de contacto.</p>
            </div>

            {{-- BLOQUE PARA MOSTRAR MENSAJES Y ERRORES --}}
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    @if(session('success'))
                        <div class="alert alert-success text-center shadow-sm mb-4">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger text-center shadow-sm mb-4">{{ session('error') }}</div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger shadow-sm mb-4">
                            <strong>Por favor, corrige los siguientes errores:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>

            <div class="row g-5">
                {{-- Columna de Información y Formulario --}}
                <div class="col-lg-6">
                    <h4 class="fw-bold mb-4">Información de Contacto</h4>
                    <ul class="list-unstyled contact-info-list">
                        <li class="d-flex align-items-center mb-3"><i class="bi bi-geo-alt-fill fs-4 text-primary me-3"></i><span>Av. de la Bahía, 123 - 11001 Cádiz, España</span></li>
                        <li class="d-flex align-items-center mb-3"><i class="bi bi-telephone-fill fs-4 text-primary me-3"></i><span>+34 956 123 456</span></li>
                        <li class="d-flex align-items-center mb-3"><i class="bi bi-envelope-fill fs-4 text-primary me-3"></i><span>petcareoficialtm@gmail.com</span></li>
                    </ul>

                    <h4 class="fw-bold mt-5 mb-4">Envíanos un Mensaje</h4>
                    <form action="{{ route('contacto.enviar') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <input type="text" name="name" class="form-control" placeholder="Tu Nombre" required value="{{ old('name') }}">
                        </div>
                        <div class="mb-3">
                            <input type="email" name="email" class="form-control" placeholder="Tu Correo" required value="{{ old('email') }}">
                        </div>
                        <div class="mb-3">
                            <textarea name="message" class="form-control" rows="5" placeholder="Tu Mensaje (mínimo 10 caracteres)" required>{{ old('message') }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Enviar Mensaje</button>
                    </form>
                </div>

                {{-- Columna del Mapa --}}
                <div class="col-lg-6">
                    <div class="w-100 h-100 rounded shadow-sm" style="min-height: 400px;">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d205044.4379379238!2d-6.42514194094723!3d36.51610419208759!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd0dd13a2901a073%3A0x1034343525b8210!2zQ8OhZGl6LCBTcGFpbg!5e0!3m2!1sen!2s!4v1672323456789" 
                                width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </main>

    {{-- Tu Footer Original --}}
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
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmileCare | Votre Cabinet Dentaire de Confiance</title>
    <meta name="description" content="Découvrez SmileCare, votre partenaire pour un sourire éclatant. Gestion moderne et soins dentaires de haute qualité.">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <style>
        :root {
            --primary: #2563eb;
            --primary-dark: #1e40af;
            --primary-light: #eff6ff;
            --secondary: #0ea5e9;
            --accent: #f59e0b;
            --dark: #0f172a;
            --light: #f8fafc;
            --success: #10b981;
            --card-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: var(--dark);
            background-color: #ffffff;
            overflow-x: hidden;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
        }

        /* Navbar */
        .navbar {
            padding: 1.5rem 0;
            transition: all 0.3s ease;
            background: transparent;
        }

        .navbar.scrolled {
            padding: 1rem 0;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--primary) !important;
        }

        .navbar-brand i {
            margin-right: 0.5rem;
        }

        .nav-link {
            font-weight: 600;
            color: var(--dark) !important;
            margin: 0 1rem;
            transition: color 0.3s;
        }

        .nav-link:hover {
            color: var(--primary) !important;
        }

        .btn-auth {
            border-radius: 50px;
            padding: 0.6rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-login {
            background: var(--primary-light);
            color: var(--primary);
            border: none;
            margin-right: 0.5rem;
        }

        .btn-login:hover {
            background: var(--primary);
            color: white;
        }

        .btn-register {
            background: var(--primary);
            color: white;
            border: none;
        }

        .btn-register:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        }

        /* Hero Section */
        .hero {
            padding: 10rem 0 6rem;
            background: radial-gradient(circle at top right, var(--primary-light) 0%, #ffffff 50%);
            position: relative;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 20%;
            left: -5%;
            width: 300px;
            height: 300px;
            background: var(--primary-light);
            filter: blur(80px);
            border-radius: 50%;
            z-index: -1;
        }

        .hero-title {
            font-size: 4rem;
            line-height: 1.1;
            margin-bottom: 1.5rem;
            background: linear-gradient(to right, var(--dark), var(--primary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero-subtitle {
            font-size: 1.25rem;
            color: #64748b;
            margin-bottom: 2.5rem;
            max-width: 600px;
        }

        .hero-img-container {
            position: relative;
        }

        .hero-img-card {
            background: white;
            border-radius: 24px;
            padding: 1rem;
            box-shadow: var(--card-shadow);
            transform: perspective(1000px) rotateY(-10deg) rotateX(5deg);
            transition: all 0.5s ease;
        }

        .hero-img-card:hover {
            transform: perspective(1000px) rotateY(0deg) rotateX(0deg);
        }

        .floating-badge {
            position: absolute;
            background: white;
            padding: 1rem;
            border-radius: 16px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            gap: 0.75rem;
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        /* Features */
        .section-title {
            font-size: 2.5rem;
            margin-bottom: 3rem;
            text-align: center;
        }

        .feature-card {
            padding: 2.5rem;
            border-radius: 20px;
            background: white;
            border: 1px solid #f1f5f9;
            transition: all 0.3s ease;
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: var(--card-shadow);
            border-color: var(--primary-light);
        }

        .feature-icon {
            width: 60px;
            height: 60px;
            background: var(--primary-light);
            color: var(--primary);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1.5rem;
        }

        /* CTA Section */
        .cta-section {
            padding: 6rem 0;
            background: var(--dark);
            border-radius: 40px;
            margin: 4rem 2rem;
            color: white;
            text-align: center;
        }

        /* Footer */
        footer {
            padding: 4rem 0 2rem;
            background: var(--light);
        }

        .social-links a {
            width: 40px;
            height: 40px;
            background: white;
            color: var(--primary);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            transition: all 0.3s;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .social-links a:hover {
            background: var(--primary);
            color: white;
            transform: translateY(-3px);
        }

        @media (max-width: 991px) {
            .hero-title { font-size: 3rem; }
            .hero { padding-top: 8rem; }
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg fixed-top" id="mainNavbar">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-tooth"></i> SmileCare
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item"><a class="nav-link" href="#features">Services</a></li>
                    <li class="nav-item"><a class="nav-link" href="#about">À propos</a></li>
                    <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
                </ul>
                <div class="d-flex">
                    <a href="{{ route('login') }}" class="btn btn-auth btn-login">Connexion</a>
                    <a href="{{ route('register') }}" class="btn btn-auth btn-register">S'inscrire</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="hero">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6" data-aos="fade-right">
                    <h1 class="hero-title">Redonnez de l'éclat à votre sourire.</h1>
                    <p class="hero-subtitle">Soins dentaires de pointe, gestion simplifiée et accueil chaleureux. Votre santé bucco-dentaire est notre priorité absolue.</p>
                    <div class="d-flex gap-3">
                        <a href="{{ route('patient.register') }}" class="btn btn-primary btn-lg rounded-pill px-4">Prendre Rendez-vous</a>
                        <a href="#features" class="btn btn-outline-dark btn-lg rounded-pill px-4">Nos Services</a>
                    </div>
                    <div class="mt-5 d-flex align-items-center gap-4">
                        <div class="d-flex">
                            <img src="https://ui-avatars.com/api/?name=User+1&background=random" class="rounded-circle border border-white border-4" width="45" style="margin-right: -15px;">
                            <img src="https://ui-avatars.com/api/?name=User+2&background=random" class="rounded-circle border border-white border-4" width="45" style="margin-right: -15px;">
                            <img src="https://ui-avatars.com/api/?name=User+3&background=random" class="rounded-circle border border-white border-4" width="45">
                        </div>
                        <div class="text-sm">
                            <i class="fas fa-star text-warning"></i>
                            <span class="fw-bold">4.9/5</span> (2,000+ Patients satisfaits)
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 mt-5 mt-lg-0" data-aos="fade-left">
                    <div class="hero-img-container">
                        <div class="hero-img-card">
                            <img src="https://images.unsplash.com/photo-1629909613654-28e377c37b09?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80" alt="Clinique Dentaire" class="img-fluid rounded-4">
                        </div>
                        <div class="floating-badge" style="top: 10%; right: -5%;">
                            <i class="fas fa-check-circle text-success fs-4"></i>
                            <div>
                                <div class="fw-bold">Certifié CNAM</div>
                                <div class="small text-muted">Remboursement garanti</div>
                            </div>
                        </div>
                        <div class="floating-badge" style="bottom: 15%; left: -10%;">
                            <i class="fas fa-calendar-alt text-primary fs-4"></i>
                            <div>
                                <div class="fw-bold">RDV en ligne</div>
                                <div class="small text-muted">Confirmation instantanée</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Features Section -->
    <section class="py-5" id="features">
        <div class="container py-5">
            <h2 class="section-title" data-aos="fade-up">Une expertise complète</h2>
            <div class="row g-4">
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="feature-card h-100">
                        <div class="feature-icon">
                            <i class="fas fa-teeth"></i>
                        </div>
                        <h3>Implantologie</h3>
                        <p class="text-muted">Des solutions durables et esthétiques pour remplacer vos dents manquantes avec les dernières technologies.</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="feature-card h-100">
                        <div class="feature-icon">
                            <i class="fas fa-magic"></i>
                        </div>
                        <h3>Esthétique Dentaire</h3>
                        <p class="text-muted">Blanchiment, facettes et corrections pour un sourire parfait qui vous donne confiance au quotidien.</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="feature-card h-100">
                        <div class="feature-icon">
                            <i class="fas fa-child"></i>
                        </div>
                        <h3>Pédodontie</h3>
                        <p class="text-muted">Un accueil doux et ludique pour vos enfants, pour des dents saines dès le plus jeune âge.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-5 bg-light">
        <div class="container py-5">
            <div class="row text-center g-4">
                <div class="col-6 col-md-3" data-aos="zoom-in">
                    <h2 class="fw-bold text-primary">15+</h2>
                    <p class="text-muted mb-0">Années d'expérience</p>
                </div>
                <div class="col-6 col-md-3" data-aos="zoom-in" data-aos-delay="100">
                    <h2 class="fw-bold text-primary">5k+</h2>
                    <p class="text-muted mb-0">Patients suivis</p>
                </div>
                <div class="col-6 col-md-3" data-aos="zoom-in" data-aos-delay="200">
                    <h2 class="fw-bold text-primary">10+</h2>
                    <p class="text-muted mb-0">Spécialistes</p>
                </div>
                <div class="col-6 col-md-3" data-aos="zoom-in" data-aos-delay="300">
                    <h2 class="fw-bold text-primary">100%</h2>
                    <p class="text-muted mb-0">Satisfaction</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <div class="container">
        <div class="cta-section" data-aos="zoom-y-out">
            <h2 class="mb-4">Prêt à transformer votre sourire ?</h2>
            <p class="mb-5 opacity-75">Inscrivez-vous dès maintenant pour gérer vos rendez-vous et suivre votre dossier médical.</p>
            <div class="d-flex justify-content-center gap-3">
                <a href="{{ route('register') }}" class="btn btn-light btn-lg rounded-pill px-5">Commencer</a>
                <a href="#contact" class="btn btn-outline-light btn-lg rounded-pill px-5">En savoir plus</a>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-4">
                    <a class="navbar-brand mb-4 d-block" href="#">
                        <i class="fas fa-tooth"></i> SmileCare
                    </a>
                    <p class="text-muted">L'excellence au service de votre sourire. Une équipe passionnée par la santé bucco-dentaire et l'innovation médicale.</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 offset-lg-1">
                    <h5 class="mb-4">Navigation</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#" class="text-decoration-none text-muted">Services</a></li>
                        <li class="mb-2"><a href="#" class="text-decoration-none text-muted">À propos</a></li>
                        <li class="mb-2"><a href="#" class="text-decoration-none text-muted">Tarifs</a></li>
                        <li class="mb-2"><a href="#" class="text-decoration-none text-muted">Blog</a></li>
                    </ul>
                </div>
                <div class="col-lg-2">
                    <h5 class="mb-4">Espace Client</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="{{ route('login') }}" class="text-decoration-none text-muted">Connexion</a></li>
                        <li class="mb-2"><a href="{{ route('register') }}" class="text-decoration-none text-muted">Inscription</a></li>
                        <li class="mb-2"><a href="#" class="text-decoration-none text-muted">Support</a></li>
                    </ul>
                </div>
                <div class="col-lg-3">
                    <h5 class="mb-4">Contact</h5>
                    <ul class="list-unstyled text-muted">
                        <li class="mb-3 d-flex gap-3">
                            <i class="fas fa-map-marker-alt text-primary"></i>
                            123 Avenue de la Santé, Tunis
                        </li>
                        <li class="mb-3 d-flex gap-3">
                            <i class="fas fa-phone text-primary"></i>
                            +216 71 000 000
                        </li>
                        <li class="mb-3 d-flex gap-3">
                            <i class="fas fa-envelope text-primary"></i>
                            contact@smilecare.tn
                        </li>
                    </ul>
                </div>
            </div>
            <hr class="my-5 opacity-10">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start">
                    <p class="text-muted mb-0">&copy; {{ date('Y') }} SmileCare. Tous droits réservés.</p>
                </div>
                <div class="col-md-6 text-center text-md-end mt-3 mt-md-0">
                    <a href="#" class="text-decoration-none text-muted small me-4">Confidentialité</a>
                    <a href="#" class="text-decoration-none text-muted small">CGU</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- AOS Animation -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 1000,
            once: true,
            offset: 100
        });

        window.addEventListener('scroll', function() {
            if (window.scrollY > 50) {
                document.getElementById('mainNavbar').classList.add('scrolled');
            } else {
                document.getElementById('mainNavbar').classList.remove('scrolled');
            }
        });
    </script>
</body>
</html>

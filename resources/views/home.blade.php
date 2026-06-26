@extends('layouts.base')

@section('content')
    <style>
        :root {
            --primary-color: #4f46e5;
            --secondary-color: #6366f1;
            --accent-color: #f43f5e;
            --light-bg: #f9fafb;
            --dark-text: #1f2937;
            --light-text: #f9fafb;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: var(--light-bg);
            color: var(--dark-text);
            line-height: 1.6;
        }

        header {
            background-color: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .container {
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 0;
        }

        .logo {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-color);
            text-decoration: none;
            display: flex;
            align-items: center;
        }

        .logo i {
            margin-right: 0.5rem;
        }

        .nav-links {
            display: flex;
            list-style: none;
        }

        .nav-links li {
            margin-left: 2rem;
        }

        .nav-links a {
            text-decoration: none;
            color: var(--dark-text);
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .nav-links a:hover {
            color: var(--primary-color);
        }

        .auth-buttons {
            display: flex;
            align-items: center;
        }

        .auth-buttons a {
            text-decoration: none;
            margin-left: 1rem;
        }

        .btn {
            padding: 0.5rem 1.5rem;
            border-radius: 5px;
            font-weight: 500;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .btn-outline {
            border: 1px solid var(--primary-color);
            color: var(--primary-color);
            background: transparent;
        }

        .btn-outline:hover {
            background-color: var(--primary-color);
            color: white;
        }

        .btn-primary {
            background-color: var(--primary-color);
            color: white;
            border: none;
        }

        .btn-primary:hover {
            background-color: var(--secondary-color);
        }

        .hero {
            background: linear-gradient(to right, rgba(79, 70, 229, 0.9), rgba(99, 102, 241, 0.8)), url('/api/placeholder/1200/600') no-repeat center center/cover;
            color: white;
            padding: 5rem 0;
            text-align: center;
        }

        .hero-content {
            max-width: 800px;
            margin: 0 auto;
        }

        .hero h1 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        .hero p {
            font-size: 1.2rem;
            margin-bottom: 2rem;
        }

        .search-bar {
            display: flex;
            max-width: 600px;
            margin: 0 auto;
            background-color: white;
            border-radius: 50px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .search-bar input {
            flex: 1;
            padding: 1rem 1.5rem;
            border: none;
            outline: none;
            font-size: 1rem;
        }

        .search-bar button {
            padding: 1rem 2rem;
            background-color: var(--accent-color);
            color: white;
            border: none;
            cursor: pointer;
            font-weight: 500;
        }

        .categories {
            padding: 4rem 0;
            text-align: center;
        }

        .section-title {
            font-size: 2rem;
            margin-bottom: 2rem;
            text-align: center;
        }

        .category-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }

        .category-card {
            background-color: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .category-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .category-img {
            height: 150px;
            width: 100%;
            object-fit: cover;
        }

        .category-content {
            padding: 1.5rem;
        }

        .category-content h3 {
            margin-bottom: 0.5rem;
        }

        .featured-events {
            background-color: white;
            padding: 4rem 0;
        }

        .events-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }

        .event-card {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .event-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .event-img {
            height: 200px;
            width: 100%;
            object-fit: cover;
        }

        .event-content {
            padding: 1.5rem;
        }

        .event-date {
            display: inline-block;
            background-color: var(--primary-color);
            color: white;
            padding: 0.25rem 0.5rem;
            border-radius: 5px;
            margin-bottom: 0.5rem;
            font-size: 0.8rem;
        }

        .event-content h3 {
            margin-bottom: 0.5rem;
        }

        .event-content p {
            color: #6b7280;
            margin-bottom: 1rem;
        }

        .event-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 1px solid #e5e7eb;
            padding-top: 1rem;
            margin-top: 1rem;
        }

        .event-price {
            font-weight: 700;
            color: var(--primary-color);
        }

        .cta-section {
            background-color: var(--primary-color);
            color: white;
            padding: 4rem 0;
            text-align: center;
        }

        .cta-content {
            max-width: 800px;
            margin: 0 auto;
        }

        .cta-content h2 {
            font-size: 2rem;
            margin-bottom: 1rem;
        }

        .cta-content p {
            margin-bottom: 2rem;
        }

        .btn-white {
            background-color: white;
            color: var(--primary-color);
            padding: 0.8rem 2rem;
            border: none;
            border-radius: 5px;
            font-weight: 500;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
        }

        .btn-white:hover {
            background-color: var(--light-bg);
            transform: translateY(-2px);
        }

        .features {
            padding: 4rem 0;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }

        .feature-card {
            background-color: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            text-align: center;
        }

        .feature-icon {
            background-color: var(--light-bg);
            width: 70px;
            height: 70px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            color: var(--primary-color);
            font-size: 1.5rem;
        }

        .feature-card h3 {
            margin-bottom: 1rem;
        }

        footer {
            background-color: #1e293b;
            color: white;
            padding: 3rem 0 1rem;
        }

        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .footer-column h3 {
            margin-bottom: 1.5rem;
            font-size: 1.2rem;
        }

        .footer-links {
            list-style: none;
        }

        .footer-links li {
            margin-bottom: 0.8rem;
        }

        .footer-links a {
            color: #d1d5db;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .footer-links a:hover {
            color: white;
        }

        .social-links {
            display: flex;
            gap: 1rem;
        }

        .social-links a {
            background-color: rgba(255, 255, 255, 0.1);
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .social-links a:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }

        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding-top: 1.5rem;
            text-align: center;
        }

        @media (max-width: 768px) {
            .navbar {
                flex-direction: column;
                padding: 1rem 0;
            }

            .nav-links {
                margin: 1rem 0;
                flex-direction: column;
                align-items: center;
            }

            .nav-links li {
                margin: 0.5rem 0;
            }

            .auth-buttons {
                margin-top: 1rem;
            }

            .hero h1 {
                font-size: 2rem;
            }

            .search-bar {
                flex-direction: column;
                border-radius: 10px;
            }

            .search-bar button {
                border-radius: 0;
            }
        }
    </style>


    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <h1>Découvrez des événements inoubliables</h1>
                <p>Trouvez les meilleurs événements près de chez vous en quelques clics</p>
                <div class="search-bar">
                    <input type="text" placeholder="Rechercher des événements, des lieux...">
                    <button>Rechercher</button>
                </div>
            </div>
        </div>
    </section>

    <section class="categories">
        <div class="container">
            <h2 class="section-title">Explorez par catégorie</h2>
            <div class="category-grid">
                <div class="category-card">
                    <img src="https://th.bing.com/th/id/OIP.UqehV_VtqVVYuN8GJvYaqQHaEK?w=295&h=180&c=7&r=0&o=5&dpr=1.3&pid=1.7" alt="Concert" class="category-img">
                    <div class="category-content">
                        <h3>Concerts & Musique</h3>
                        <p>Découvrez les meilleurs concerts et festivals</p>
                    </div>
                </div>
                <div class="category-card">
                    <img src="https://th.bing.com/th/id/OIP.g3L8Cs_9cEGghmtmZqAA7gHaE8?w=277&h=184&c=7&r=0&o=5&dpr=1.3&pid=1.7" alt="Sports" class="category-img">
                    <div class="category-content">
                        <h3>Sports & Fitness</h3>
                        <p>Événements sportifs et activités fitness</p>
                    </div>
                </div>
                <div class="category-card">
                    <img src="data:image/jpeg;base64,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" alt="Ateliers" class="category-img">
                    <div class="category-content">
                        <h3>Ateliers & Formations</h3>
                        <p>Développez vos compétences et passions</p>
                    </div>
                </div>
                <div class="category-card">
                    <img src="https://th.bing.com/th/id/OIP.Th_AX048TT1Qnf8gvOJPDgHaE8?w=248&h=181&c=7&r=0&o=5&dpr=1.3&pid=1.7" alt="Arts" class="category-img">
                    <div class="category-content">
                        <h3>Arts & Culture</h3>
                        <p>Expositions, théâtre et performances</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="featured-events">
        <div class="container">
            <h2 class="section-title">Événements à la une</h2>
            <div class="events-grid">
                <div class="event-card">
                    <img src="https://th.bing.com/th/id/OIP.zZvvucVVsXws11XNTHDPMgHaE8?w=237&h=180&c=7&r=0&o=5&dpr=1.3&pid=1.7" alt="Festival de musique" class="event-img">
                    <div class="event-content">
                        <span class="event-date">12-15 Mai</span>
                        <h3>Festival Musique Électronique</h3>
                        <p>Le plus grand festival de musique électronique de la région avec des artistes internationaux</p>
                        <div class="event-footer">
                            <span class="event-price">5000 FCFA</span>
                            <a href="#" class="btn btn-outline">Réserver</a>
                        </div>
                    </div>
                </div>
                <div class="event-card">
                    <img src="https://th.bing.com/th/id/OIP.tbQjr5zc2SkTprT3CNfHnwHaE8?w=256&h=180&c=7&r=0&o=5&dpr=1.3&pid=1.7" alt="Conférence tech" class="event-img">
                    <div class="event-content">
                        <span class="event-date">23 Mai</span>
                        <h3>Conférence Tech Innovation</h3>
                        <p>Rencontrez les leaders de l'innovation et découvrez les dernières tendances technologiques</p>
                        <div class="event-footer">
                            <span class="event-price">5000 FCFA</span>
                            <a href="#" class="btn btn-outline">Réserver</a>
                        </div>
                    </div>
                </div>
                <div class="event-card">
                    <img src="data:image/jpeg;base64,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" alt="Marathon" class="event-img">
                    <div class="event-content">
                        <span class="event-date">5 Juin</span>
                        <h3>Marathon de la ville au TOGO</h3>
                        <p>Participez au grand marathon annuel  à travers les plus beaux quartiers de la ville de lomé</p>
                        <div class="event-footer">
                            <span class="event-price">5000 FCFA</span>
                            <a href="#" class="btn btn-outline">Réserver</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="cta-section">
        <div class="container">
            <div class="cta-content">
                <h2>Vous organisez un événement ?</h2>
                <p>Utilisez notre plateforme pour publier,  et promouvoir facilement vos événements. Touchez des communautés  passionnés.</p>
                <a href="#" class="btn-white">Contacter nous</a>
            </div>
        </div>
    </section>

    <section class="features">
        <div class="container">
            <h2 class="section-title">Pourquoi choisir TGEvent</h2>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-ticket-alt"></i>
                    </div>
                    <h3>Billetterie intégrée</h3>
                    <p>Vendez des billets facilement avec notre système de paiement sécurisé et personnalisable.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3>Analyses détaillées</h3>
                    <p>Suivez les performances de vos événements avec nos outils d'analyse avancés.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-bullhorn"></i>
                    </div>
                    <h3>Marketing puissant</h3>
                    <p>Promouvez vos événements auprès d'un public ciblé grâce à nos outils marketing.</p>
                </div>
            </div>
        </div>
    </section>


@endsection

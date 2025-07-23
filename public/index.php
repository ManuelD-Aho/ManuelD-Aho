<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion Soutenance FHB - Simplifiez votre parcours</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    'primary': '#27A062', // Vert institutionnel
                    'primary-dark': '#0B3C32',
                    'secondary': '#2F53CD',
                    'secondary-light': '#60A5FA',
                    'accent': '#2F54CC', // Jaune/Or pour le contraste
                    'yellow-custom': '#FFD700',
                    'yellow-bright': '#FFEB3B'
                },
                animation: {
                    'float': 'float 3s ease-in-out infinite',
                    'pulse-slow': 'pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                    'fade-in': 'fadeIn 0.6s ease-out forwards',
                }
            }
        }
    }
    </script>
    <style>
    @keyframes float {

        0%,
        100% {
            transform: translateY(0px);
        }

        50% {
            transform: translateY(-10px);
        }
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(30px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .geometric-shape {
        transition: all 0.3s ease;
    }

    .geometric-shape:hover {
        transform: rotate(180deg) scale(1.1);
    }

    .card-hover {
        transition: all 0.3s ease;
        transform: translateY(0);
    }

    .card-hover:hover {
        transform: translateY(-8px);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    }

    .nav-link {
        position: relative;
    }

    .nav-link:after {
        content: '';
        position: absolute;
        width: 0;
        height: 2px;
        bottom: 0;
        left: 0;
        background-color: #157A6E;
        transition: width 0.3s ease;
    }

    .nav-link:hover:after {
        width: 100%;
    }

    .gradient-bg {
        background: linear-gradient(135deg, #157A6E 0%, #0B3C32 100%);
    }

    .hero-gradient {
        background: linear-gradient(135deg, #33A74F 0%, #375BCE 100%);
    }
    </style>
</head>

<body class="bg-gray-50 font-sans overflow-x-hidden">
    <nav class="bg-white shadow-sm px-6 py-4 sticky top-0 z-50 backdrop-blur-sm">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <div class="flex items-center space-x-8">
                <div class="flex items-center space-x-3">
                    <img src="./images/logo.png" alt="logo" width="130" height="70" title="logo">
                </div>
                <div class="hidden md:flex space-x-6">
                    <a href="#features" class="nav-link text-gray-600 hover:text-primary transition">Fonctionnalités</a>
                    <a href="#process" class="nav-link text-gray-600 hover:text-primary transition">Processus</a>
                    <a href="#temoignages" class="nav-link text-gray-600 hover:text-primary transition">Témoignages</a>

                </div>
            </div>
            <div class="flex items-center space-x-4">
                <a href="page_connexion.php"
                    class="bg-primary text-white px-6 py-2 rounded-full hover:bg-primary-dark transition-all duration-300 hover:scale-105">
                    Connexion
                </a>
            </div>
        </div>
    </nav>

    <section class="relative bg-white py-20 overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 grid lg:grid-cols-2 gap-12 items-center">
            <div class="relative">
                <div class="absolute -top-10 -left-10 w-4 h-4 bg-accent rounded-full animate-float"></div>
                <div class="absolute top-20 -right-10 w-3 h-3 bg-primary rounded-full animate-pulse-slow"></div>
                <div class="absolute -bottom-5 left-20 geometric-shape">
                    <div class="w-8 h-8 border-2 border-primary rotate-45"></div>
                </div>

                <h1 class="text-5xl lg:text-6xl text-gray-700 font-bold mb-6 leading-tight">
                    Simplifiez la Gestion<br>
                    de Vos <span class="text-primary">Soutenances</span>
                </h1>

                <p class="text-gray-600 text-lg mb-8 max-w-md">
                    Notre plateforme centralise et optimise chaque étape du processus de soutenance pour les étudiants,
                    enseignants et l'administration de l'Université FHB.
                </p>

                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="page_connexion.php"
                        class="bg-primary text-white px-8 py-4 rounded-full hover:bg-primary-dark transition-all duration-300 hover:scale-105 text-center font-semibold">
                        Accéder à mon Espace
                    </a>
                    <a href="#features"
                        class="border-2 border-primary text-primary px-8 py-4 rounded-full hover:bg-primary hover:text-white transition-all duration-300 text-center font-semibold">
                        En Savoir Plus
                    </a>
                </div>

                <div class="flex space-x-8 mt-12">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-primary">10K+</div>
                        <div class="text-gray-600 text-sm">Étudiants Inscrits</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-primary">2K+</div>
                        <div class="text-gray-600 text-sm">Soutenances Gérées</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-primary">15+</div>
                        <div class="text-gray-600 text-sm">UFR Connectées</div>
                    </div>
                </div>
            </div>

            <div class="relative flex justify-center items-center">
                <div class="relative">
                    <div class="w-80 h-80 hero-gradient rounded-full flex items-center justify-center relative">
                        <div class="relative">
                            <div class="w-32 h-32 bg-white rounded-full mb-4 relative overflow-hidden shadow-lg">
                                <div class="absolute top-8 left-8 w-4 h-4 bg-primary rounded-full"></div>
                                <div class="absolute top-8 right-8 w-4 h-4 bg-primary rounded-full"></div>
                                <div
                                    class="absolute bottom-6 left-1/2 transform -translate-x-1/2 w-8 h-2 bg-primary rounded-full">
                                </div>
                            </div>
                            <div class="w-24 h-16 bg-primary rounded-lg mx-auto relative shadow-xl">
                                <div
                                    class="w-20 h-12 bg-white rounded-sm absolute top-2 left-2 flex items-center justify-center">
                                    <div class="w-12 h-1 bg-primary rounded"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="absolute -top-10 -right-10 w-20 h-20 bg-primary rounded-full animate-float opacity-80">
                    </div>
                    <div class="absolute -bottom-10 -left-10 geometric-shape">
                        <div class="w-16 h-16 border-4 border-accent transform rotate-45"></div>
                    </div>
                    <div class="absolute top-10 -left-20 w-6 h-6 bg-accent rounded-full animate-pulse-slow"></div>
                </div>
            </div>
        </div>
    </section>

    <section id="features" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold mb-4 text-gray-700">Une Plateforme, Trois Avantages Clés</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">Découvrez comment nous facilitons la vie de toute la
                    communauté universitaire.</p>
            </div>

            <div class="grid lg:grid-cols-3 gap-12">
                <div class="card-hover bg-white p-8 rounded-2xl shadow-lg">
                    <div class="mb-6">
                        <div class="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mb-4">
                            <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-xl font-bold mb-4 text-gray-700">Planification Simplifiée</h3>
                    <p class="text-gray-600">
                        Déposez vos rapports en quelques clics, vérifier l'avancer de votre rapport et la programmation
                        de
                        la date. Fini les allers-retours interminables.
                    </p>
                </div>

                <div class="card-hover bg-white p-8 rounded-2xl shadow-lg">
                    <div class="mb-6">
                        <div class="w-16 h-16 bg-accent/10 rounded-full flex items-center justify-center mb-4">
                            <svg class="w-8 h-8 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                                </path>
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-xl font-bold mb-4 text-gray-700">Suivi en Temps Réel</h3>
                    <p class="text-gray-600">
                        Recevez des notifications automatiques pour chaque étape validée. Suivez l'avancement de votre
                        dossier 24/7.
                    </p>
                </div>

                <div class="card-hover bg-white p-8 rounded-2xl shadow-lg">
                    <div class="mb-6">
                        <div class="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mb-4">
                            <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path>
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-xl font-bold mb-4 text-gray-700">Archivage Numérique</h3>
                    <p class="text-gray-600">
                        Tous les mémoires, thèses et PV de soutenance sont archivés de manière sécurisée et accessible à
                        tout moment.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section id="process" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-6 text-center">
            <h2 class="text-4xl font-bold mb-4 text-gray-700">Le Processus en 4 Étapes Claires</h2>
            <p class="text-gray-600 mb-12 max-w-2xl mx-auto">
                De la soumission du sujet à la finalisation, votre parcours est balisé et transparent.
            </p>

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="card-hover bg-gray-50 p-8 rounded-2xl border border-gray-100">
                    <div
                        class="w-20 h-20 bg-primary/20 text-primary rounded-full mx-auto mb-6 flex items-center justify-center text-3xl font-bold">
                        1</div>
                    <h3 class="font-bold text-lg mb-3 text-gray-700">Dépôt du Rapport</h3>
                    <p class="text-gray-600 text-sm">Soumettez votre proposition de mémoire ou de thèse directement sur
                        la plateforme pour validation.</p>
                </div>
                <div class="card-hover bg-gray-50 p-8 rounded-2xl border border-gray-100">
                    <div
                        class="w-20 h-20 bg-primary/20 text-primary rounded-full mx-auto mb-6 flex items-center justify-center text-3xl font-bold">
                        2</div>
                    <h3 class="font-bold text-lg mb-3 text-gray-700">Validation Administrative</h3>
                    <p class="text-gray-600 text-sm">Votre UFR examine et approuve votre demande de candidature à la
                        soutenance.
                    </p>
                </div>
                <div class="card-hover bg-gray-50 p-8 rounded-2xl border border-gray-100">
                    <div
                        class="w-20 h-20 bg-primary/20 text-primary rounded-full mx-auto mb-6 flex items-center justify-center text-3xl font-bold">
                        3</div>
                    <h3 class="font-bold text-lg mb-3 text-gray-700">Examination de la commission de validation</h3>
                    <p class="text-gray-600 text-sm">Votre rapport est examiné par les membres de la commission de
                        validation des soutenances, par la suite un encadreur et un directeur de mémoire vous est
                        attribués.</p>
                </div>
                <div class="card-hover bg-gray-50 p-8 rounded-2xl border border-gray-100">
                    <div
                        class="w-20 h-20 bg-primary/20 text-primary rounded-full mx-auto mb-6 flex items-center justify-center text-3xl font-bold">
                        4</div>
                    <h3 class="font-bold text-lg mb-3 text-gray-900">Soutenance & Archivage</h3>
                    <p class="text-gray-600 text-sm">Après la soutenance, déposez la version finale de votre document et
                        recevez le PV numérique.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="temoignages" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold mb-4 text-gray-900">Ce Que Disent Nos Utilisateurs</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">Étudiants, enseignants et administrateurs partagent leur
                    expérience.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <div class="card-hover bg-white p-8 rounded-2xl shadow-lg">
                    <div class="flex items-center mb-6">
                        <img class="w-12 h-12 rounded-full mr-4 object-cover"
                            src="https://i.pravatar.cc/150?u=a042581f4e29026704d" alt="Photo de Marie Dubois">
                        <div>
                            <h4 class="font-bold text-gray-900">Aya Konan</h4>
                            <p class="text-gray-600 text-sm">Étudiante en Master de Droit</p>
                        </div>
                    </div>
                    <p class="text-gray-600 mb-4">"La plateforme a rendu l'organisation de ma soutenance tellement plus
                        simple. Plus besoin de courir partout sur le campus !"</p>
                    <div class="text-accent">★★★★★</div>
                </div>

                <div class="card-hover bg-white p-8 rounded-2xl shadow-lg">
                    <div class="flex items-center mb-6">
                        <img class="w-12 h-12 rounded-full mr-4 object-cover"
                            src="https://i.pravatar.cc/150?u=a042581f4e29026704a" alt="Photo de Prof. Kouamé">
                        <div>
                            <h4 class="font-bold text-gray-900">Prof. Kouamé</h4>
                            <p class="text-gray-600 text-sm">UFR Sciences Économiques</p>
                        </div>
                    </div>
                    <p class="text-gray-600 mb-4">"Avoir un calendrier centralisé pour toutes les soutenances est un
                        gain de temps incroyable. Je peux gérer mon emploi du temps plus efficacement."</p>
                    <div class="text-accent">★★★★★</div>
                </div>

                <div class="card-hover bg-white p-8 rounded-2xl shadow-lg">
                    <div class="flex items-center mb-6">
                        <img class="w-12 h-12 rounded-full mr-4 object-cover"
                            src="https://i.pravatar.cc/150?u=a042581f4e29026704b" alt="Photo de Mme Bamba">
                        <div>
                            <h4 class="font-bold text-gray-900">Mme. Bamba</h4>
                            <p class="text-gray-600 text-sm">Scolarité, UFR LLC</p>
                        </div>
                    </div>
                    <p class="text-gray-600 mb-4">"Le suivi administratif est plus fluide et l'archivage numérique nous
                        a débarrassés de tonnes de papier. Une vraie révolution."</p>
                    <div class="text-accent">★★★★★</div>
                </div>
            </div>
        </div>
    </section>


    <footer class="bg-gray-800 text-white py-16">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid md:grid-cols-3 gap-8 mb-8">
                <div>
                    <div class="flex items-center space-x-3 mb-6">
                        <img src="./images/logo.png" alt="logo" width="130" height="70" title="logo">
                    </div>
                    <p class="text-gray-400 mb-4">La plateforme officielle pour la gestion des soutenances de MATH-INFO
                        à l'Université Félix Houphouët-Boigny.</p>
                </div>
                <div>
                    <h3 class="font-bold mb-4">Espaces Utilisateurs</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="page_connexion.php" class="hover:text-white transition">Espace Étudiant</a></li>
                        <li><a href="page_connexion.php" class="hover:text-white transition">Espace Enseignant</a></li>
                        <li><a href="page_connexion.php" class="hover:text-white transition">Espace Administration</a>
                        </li>
                    </ul>
                </div>

                <div>
                    <h3 class="font-bold mb-4">Support</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white transition">Centre d'aide</a></li>
                        <li><a href="#" class="hover:text-white transition">FAQ</a></li>
                        <li><a href="#" class="hover:text-white transition">Politique de confidentialité</a></li>
                        <li><a href="#" class="hover:text-white transition">Conditions d'utilisation</a></li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-800 pt-8 text-center">
                <p class="text-gray-400">© 2025 SoutenanceFHB - Université Félix Houphouët-Boigny. Tous droits réservés.
                </p>
            </div>
        </div>
    </footer>

    <script>
    // Smooth scrolling for navigation links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Intersection Observer for animations
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
                // Optional: unobserve after animation
                // observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    // Observe all cards for animation
    document.querySelectorAll('.card-hover').forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(30px)';
        card.style.transition =
            'opacity 0.6s ease-out, transform 0.6s ease-out, box-shadow 0.3s ease, background-color 0.3s ease';
        observer.observe(card);
    });
    </script>
</body>

</html>
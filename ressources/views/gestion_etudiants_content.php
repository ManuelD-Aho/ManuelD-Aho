<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Étudiants | Université</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'custom-primary': '#3457cb',
                        'custom-primary-dark': '#24407a',
                        'custom-success-dark': '#36865a',
                        'custom-success-light': '#59bf3d',
                    },
                    animation: {
                        'fade-in-down': 'fadeInDown 0.8s ease-out forwards',
                        'slide-in-right': 'slideInRight 0.8s ease-out forwards',
                        'scale-in': 'scaleIn 0.5s ease-out forwards',
                        'fade-in-up': 'fadeInUp 0.6s ease-out forwards',
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            min-height: 100vh;
        }

        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes slideInRight {
            from { opacity: 0; transform: translateX(50px); }
            to { opacity: 1; transform: translateX(0); }
        }

        @keyframes scaleIn {
            from { opacity: 0; transform: scale(0.9); }
            to { opacity: 1; transform: scale(1); }
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .card {
            background: white;
            border-radius: 20px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 8px 32px rgba(15, 23, 42, 0.08);
            transition: all 0.3s ease;
            overflow: hidden;
            position: relative;
        }

        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #3457cb 0%, #36865a 50%, #59bf3d 100%);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .card:hover::before {
            opacity: 1;
        }

        .card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(15, 23, 42, 0.15);
        }

        .icon-container {
            background: linear-gradient(135deg, #36865a 0%, #59bf3d 100%);
            border-radius: 16px;
            padding: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            box-shadow: 0 8px 16px rgba(54, 134, 90, 0.3);
            transition: all 0.3s ease;
        }

        .icon-container.blue {
            background: linear-gradient(135deg, #3457cb 0%, #24407a 100%);
            box-shadow: 0 8px 16px rgba(52, 87, 203, 0.3);
        }

        .card:hover .icon-container {
            transform: scale(1.1) rotate(5deg);
        }

        .btn-action {
            background: linear-gradient(135deg, #3457cb 0%, #24407a 100%);
            color: white;
            padding: 8px 16px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 12px rgba(52, 87, 203, 0.3);
        }

        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(52, 87, 203, 0.4);
        }

        .header-gradient {
            background: linear-gradient(135deg, #24407a 0%, #3457cb 100%);
        }

        .initial-hidden {
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInUp 0.6s ease-out forwards;
        }
    </style>
</head>

<body class="bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Header Section -->
        <div class="bg-white rounded-3xl shadow-xl border border-gray-100 mb-8 relative overflow-hidden animate-fade-in-down">
            <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-custom-primary to-custom-success-light"></div>
            <div class="p-8 lg:p-12">
                <div class="flex items-center gap-6 md:gap-8 flex-col md:flex-row text-center md:text-left">
                    <div class="bg-gradient-to-br from-custom-success-dark to-custom-success-light text-white w-20 h-20 md:w-24 md:h-24 rounded-2xl flex items-center justify-center text-4xl md:text-5xl shadow-lg transform transition-transform duration-300 hover:scale-110">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <div>
                        <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 mb-2 tracking-tight">Gestion des Étudiants</h1>
                        <p class="text-lg text-gray-600 font-normal">Centre de gestion des étudiants et inscriptions</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Carte pour ajouter un étudiant -->
            <a href="?page=gestion_etudiants&action=ajouter_des_etudiants" class="block">
                <div class="card initial-hidden" style="animation-delay: 0.1s">
                    <div class="p-8">
                        <div class="flex items-center mb-6">
                            <div class="icon-container mr-6">
                                <i class="fas fa-user-plus"></i>
                            </div>
                            <h2 class="text-2xl font-bold text-gray-900">Ajouter un Étudiant</h2>
                        </div>
                        <p class="text-gray-600 mb-6 leading-relaxed">
                            Créez un nouveau profil étudiant dans la base de données. Cette option permet d'ajouter
                            manuellement les informations d'un nouvel étudiant avec toutes ses données personnelles.
                        </p>
                        <div class="btn-action">
                            Accéder à l'ajout d'étudiant
                            <i class="fas fa-arrow-right transition-transform duration-300 group-hover:translate-x-1"></i>
                        </div>
                    </div>
                </div>
            </a>

            <!-- Carte pour inscrire un étudiant -->
            <a href="?page=gestion_etudiants&action=inscrire_des_etudiants" class="block">
                <div class="card initial-hidden" style="animation-delay: 0.2s">
                    <div class="p-8">
                        <div class="flex items-center mb-6">
                            <div class="icon-container blue mr-6">
                                <i class="fas fa-user-graduate"></i>
                            </div>
                            <h2 class="text-2xl font-bold text-gray-900">Inscrire un Étudiant</h2>
                        </div>
                        <p class="text-gray-600 mb-6 leading-relaxed">
                            Inscrivez un étudiant à une formation ou un cours. Cette option permet de gérer les inscriptions
                            académiques des étudiants existants dans le système.
                        </p>
                        <div class="btn-action">
                            Accéder aux inscriptions
                            <i class="fas fa-arrow-right transition-transform duration-300 group-hover:translate-x-1"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Statistics Section -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-12">
            <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100 initial-hidden" style="animation-delay: 0.3s">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-700 mb-2">Étudiants Actifs</h3>
                        <p class="text-3xl font-bold text-custom-primary">1,247</p>
                        <p class="text-sm text-gray-500 mt-1">
                            <i class="fas fa-arrow-up text-custom-success-dark mr-1"></i>
                            +12% ce mois
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-custom-primary/10 text-custom-primary rounded-xl flex items-center justify-center">
                        <i class="fas fa-users text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100 initial-hidden" style="animation-delay: 0.4s">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-700 mb-2">Nouvelles Inscriptions</h3>
                        <p class="text-3xl font-bold text-custom-success-dark">89</p>
                        <p class="text-sm text-gray-500 mt-1">
                            <i class="fas fa-arrow-up text-custom-success-dark mr-1"></i>
                            +8% ce mois
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-custom-success-dark/10 text-custom-success-dark rounded-xl flex items-center justify-center">
                        <i class="fas fa-user-plus text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100 initial-hidden" style="animation-delay: 0.5s">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-700 mb-2">Promotions</h3>
                        <p class="text-3xl font-bold text-purple-600">15</p>
                        <p class="text-sm text-gray-500 mt-1">
                            <i class="fas fa-graduation-cap text-purple-600 mr-1"></i>
                            Actives
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-purple-600/10 text-purple-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-graduation-cap text-xl"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Animation des cartes au chargement
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.initial-hidden');
            cards.forEach((card, index) => {
                card.style.animationDelay = `${index * 0.2}s`;
            });
        });
    </script>
</body>

</html>
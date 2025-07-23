<?php
// Supposons que votre contrôleur prépare ce tableau.
// Cette partie est un exemple pour que le code soit fonctionnel.
// VOUS N'AVEZ PAS BESOIN DE CHANGER VOTRE LOGIQUE PHP EXISTANTE.
$cardReclamation = isset($GLOBALS['cardReclamation']) ? $GLOBALS['cardReclamation'] : [
    [
        'bg_color' => 'bg-green-500', // ignoré dans le nouveau style
        'icon' => 'fas fa-plus-circle',
        'text_color' => 'text-white', // ignoré
        'title' => 'Soumettre une nouvelle réclamation',
        'description' => 'Un problème ou une question ? Soumettez votre réclamation ici et notre équipe vous répondra dans les plus brefs délais.',
        'link' => '?page=reclamations&action=creer',
        'title_link' => 'Créer une réclamation'
    ],
    [
        'bg_color' => 'bg-blue-500', // ignoré
        'icon' => 'fas fa-history',
        'text_color' => 'text-white', // ignoré
        'title' => 'Consulter l\'historique de mes réclamations',
        'description' => 'Suivez le statut de vos réclamations passées et consultez les réponses apportées par l\'administration.',
        'link' => '?page=reclamations&action=historique',
        'title_link' => 'Voir mon historique'
    ]
];
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Réclamations | Université</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
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
            background: linear-gradient(135deg, #3457cb 0%, #24407a 100%);
            border-radius: 16px;
            padding: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            box-shadow: 0 8px 16px rgba(52, 87, 203, 0.3);
            transition: all 0.3s ease;
        }

        .icon-container.green {
            background: linear-gradient(135deg, #36865a 0%, #59bf3d 100%);
            box-shadow: 0 8px 16px rgba(54, 134, 90, 0.3);
        }

        .card:hover .icon-container {
            transform: scale(1.1) rotate(5deg);
        }

        .btn-action {
            background: linear-gradient(135deg, #3457cb 0%, #24407a 100%);
            color: white;
            padding: 12px 24px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 12px rgba(52, 87, 203, 0.3);
            text-decoration: none;
        }

        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(52, 87, 203, 0.4);
            color: white;
        }

        .initial-hidden {
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInUp 0.6s ease-out forwards;
        }
    </style>
</head>

<body class="bg-gray-50 min-h-screen">

    <div id="notification-container" class="fixed top-5 right-5 z-[100] w-full max-w-sm">
        <?php if (isset($_SESSION['message'])): ?>
            <div id="session-message" data-type="<?= htmlspecialchars($_SESSION['message']['type']) ?>" class="hidden">
                <?= htmlspecialchars($_SESSION['message']['text']) ?>
            </div>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Header Section -->
        <div class="bg-white rounded-3xl shadow-xl border border-gray-100 mb-8 relative overflow-hidden animate-fade-in-down">
            <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-custom-primary to-custom-success-light"></div>
            <div class="p-8 lg:p-12">
                <div class="flex items-center gap-6 md:gap-8 flex-col md:flex-row text-center md:text-left">
                    <div class="bg-gradient-to-br from-custom-primary to-custom-primary-dark text-white w-20 h-20 md:w-24 md:h-24 rounded-2xl flex items-center justify-center text-4xl md:text-5xl shadow-lg transform transition-transform duration-300 hover:scale-110">
                        <i class="fas fa-comment-dots"></i>
                    </div>
                    <div>
                        <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 mb-2 tracking-tight">Portail des Réclamations</h1>
                        <p class="text-lg text-gray-600 font-normal">Un espace dédié pour soumettre et suivre vos demandes</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Card -->
        <div class="card initial-hidden" style="animation-delay: 0.1s">
            <div class="p-8">
                
                <?php foreach ($cardReclamation as $index => $card): ?>
                <div class="<?= $index > 0 ? 'border-t border-gray-200 pt-8 mt-8' : '' ?> initial-hidden" style="animation-delay: <?= 0.2 + ($index * 0.1) ?>s">
                    <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6">
                        <div class="flex items-start gap-6 flex-1">
                            <div class="icon-container <?= $index % 2 === 0 ? '' : 'green' ?> flex-shrink-0">
                                <?php if (!empty($card['icon'])): ?>
                                    <i class="<?php echo htmlspecialchars($card['icon']); ?>"></i>
                                <?php endif ?>
                            </div>
                            <div class="flex-1">
                                <h2 class="text-2xl font-bold text-gray-900 mb-3"><?php echo htmlspecialchars($card['title']); ?></h2>
                                <p class="text-gray-600 leading-relaxed text-lg"><?php echo htmlspecialchars($card['description']); ?></p>
                            </div>
                        </div>

                        <div class="flex-shrink-0 w-full lg:w-auto">
                            <a href="<?php echo htmlspecialchars($card['link']); ?>" class="btn-action w-full lg:w-auto justify-center lg:justify-start">
                                <?php echo htmlspecialchars($card['title_link']); ?>
                                <i class="fas fa-arrow-right transition-transform duration-300 group-hover:translate-x-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>

            </div>
        </div>

        <!-- Statistics Section -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
            <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100 initial-hidden" style="animation-delay: 0.4s">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-700 mb-2">Réclamations en cours</h3>
                        <p class="text-3xl font-bold text-custom-primary">24</p>
                        <p class="text-sm text-gray-500 mt-1">
                            <i class="fas fa-clock text-custom-primary mr-1"></i>
                            En traitement
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-custom-primary/10 text-custom-primary rounded-xl flex items-center justify-center">
                        <i class="fas fa-hourglass-half text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100 initial-hidden" style="animation-delay: 0.5s">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-700 mb-2">Réclamations résolues</h3>
                        <p class="text-3xl font-bold text-custom-success-dark">156</p>
                        <p class="text-sm text-gray-500 mt-1">
                            <i class="fas fa-check text-custom-success-dark mr-1"></i>
                            Ce mois
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-custom-success-dark/10 text-custom-success-dark rounded-xl flex items-center justify-center">
                        <i class="fas fa-check-circle text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100 initial-hidden" style="animation-delay: 0.6s">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-700 mb-2">Temps de réponse</h3>
                        <p class="text-3xl font-bold text-purple-600">2.4j</p>
                        <p class="text-sm text-gray-500 mt-1">
                            <i class="fas fa-tachometer-alt text-purple-600 mr-1"></i>
                            Moyenne
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-purple-600/10 text-purple-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-clock text-xl"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const sessionMessageDiv = document.getElementById('session-message');
        if (sessionMessageDiv) {
            const message = sessionMessageDiv.textContent;
            const type = sessionMessageDiv.dataset.type;
            showNotification(type, message);
        }

        // Animation des cartes au chargement
        const cards = document.querySelectorAll('.initial-hidden');
        cards.forEach((card, index) => {
            card.style.animationDelay = `${index * 0.1}s`;
        });
    });

    function showNotification(type, message) {
        const container = document.getElementById('notification-container');
        if (!container) return;

        const notif = document.createElement('div');
        notif.className = `p-4 rounded-lg shadow-lg text-sm mb-3 border-l-4 ${type === 'success' ? 'bg-green-100 border-green-500 text-green-800' : 'bg-red-100 border-red-500 text-red-800'}`;
        
        const title = type === 'success' ? 'Succès !' : 'Erreur !';
        notif.innerHTML = `<strong class="font-bold">${title}</strong> <span>${message}</span>`;
        
        notif.style.opacity = 0;
        notif.style.transition = 'opacity 0.5s ease-in-out';
        
        container.appendChild(notif);
        
        // Fade in
        setTimeout(() => {
            notif.style.opacity = 1;
        }, 10);
        
        // Fade out and remove
        setTimeout(() => {
            notif.style.opacity = 0;
            setTimeout(() => {
                notif.remove();
            }, 500);
        }, 5000);
    }
    </script>
</body>
</html>
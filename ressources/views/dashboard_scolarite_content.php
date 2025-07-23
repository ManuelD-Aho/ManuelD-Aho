<!DOCTYPE html>
<?php
require_once __DIR__ . '/../../app/config/database.php';
require_once __DIR__ . '/../../app/controllers/DashboardScolariteController.php';

$dashboardController = new DashboardScolariteController();
$dashboardData = $dashboardController->getDashboardData();

$stats = $dashboardData['stats'];
$inscriptionsParNiveau = $dashboardData['inscriptionsParNiveau'];
?>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Responsable Scolarité | Tableau de bord</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

        .stat-card {
            background: white;
            border-radius: 20px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 8px 32px rgba(15, 23, 42, 0.08);
            transition: all 0.3s ease;
            overflow: hidden;
            position: relative;
        }

        .stat-card::before {
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

        .stat-card:hover::before {
            opacity: 1;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 16px 48px rgba(15, 23, 42, 0.12);
        }

        .card {
            background: white;
            border-radius: 20px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 8px 32px rgba(15, 23, 42, 0.08);
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .header-gradient {
            background: linear-gradient(135deg, #24407a 0%, #3457cb 100%);
        }

        .gradient-green {
            background: linear-gradient(135deg, #36865a 0%, #59bf3d 100%);
        }

        .gradient-orange {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        }

        .gradient-red {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        }

        .gradient-purple {
            background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
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
                    <div class="bg-gradient-to-br from-custom-primary to-custom-primary-dark text-white w-20 h-20 md:w-24 md:h-24 rounded-2xl flex items-center justify-center text-4xl md:text-5xl shadow-lg transform transition-transform duration-300 hover:scale-110">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <div>
                        <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 mb-2 tracking-tight">Tableau de bord Scolarité</h1>
                        <p class="text-lg text-gray-600 font-normal">Gestion et suivi des inscriptions et paiements</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistiques principales -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="stat-card p-6 animate-slide-in-right" style="animation-delay: 0.1s">
                <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-custom-primary to-custom-primary-dark"></div>
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-custom-primary/10 text-custom-primary rounded-xl flex items-center justify-center text-2xl shadow-sm">
                        <i class="fas fa-users"></i>
                    </div>
                    <div>
                        <h3 class="text-3xl font-bold text-custom-primary mb-1"><?php echo number_format($stats['etudiants']); ?></h3>
                        <p class="text-sm font-semibold text-gray-600">Étudiants inscrits</p>
                        <p class="text-xs text-custom-primary font-medium mt-1">
                            <i class="fas fa-chart-line mr-1"></i>Total des inscriptions
                        </p>
                    </div>
                </div>
            </div>

            <div class="stat-card p-6 animate-slide-in-right" style="animation-delay: 0.2s">
                <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-yellow-500 to-orange-500"></div>
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-yellow-500/10 text-yellow-600 rounded-xl flex items-center justify-center text-2xl shadow-sm">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div>
                        <h3 class="text-3xl font-bold text-yellow-600 mb-1"><?php echo number_format($stats['reclamations_en_attente']); ?></h3>
                        <p class="text-sm font-semibold text-gray-600">Réclamations en attente</p>
                        <p class="text-xs text-yellow-600 font-medium mt-1">
                            <i class="fas fa-clock mr-1"></i>À traiter
                        </p>
                    </div>
                </div>
            </div>

            <div class="stat-card p-6 animate-slide-in-right" style="animation-delay: 0.3s">
                <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-custom-success-dark to-custom-success-light"></div>
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-custom-success-dark/10 text-custom-success-dark rounded-xl flex items-center justify-center text-2xl shadow-sm">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div>
                        <h3 class="text-3xl font-bold text-custom-success-dark mb-1"><?php echo number_format($stats['paiements_complets']); ?></h3>
                        <p class="text-sm font-semibold text-gray-600">Paiements complets</p>
                        <p class="text-xs text-custom-success-dark font-medium mt-1">
                            <i class="fas fa-check-double mr-1"></i>Validés
                        </p>
                    </div>
                </div>
            </div>

            <div class="stat-card p-6 animate-slide-in-right" style="animation-delay: 0.4s">
                <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-red-500 to-red-600"></div>
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-red-500/10 text-red-600 rounded-xl flex items-center justify-center text-2xl shadow-sm">
                        <i class="fas fa-euro-sign"></i>
                    </div>
                    <div>
                        <h3 class="text-3xl font-bold text-red-600 mb-1"><?php echo number_format($stats['paiements_partiels']); ?></h3>
                        <p class="text-sm font-semibold text-gray-600">Paiements partiels</p>
                        <p class="text-xs text-red-600 font-medium mt-1">
                            <i class="fas fa-hourglass-half mr-1"></i><?php echo number_format($stats['montant_attente'], 0, ',', ' '); ?> FCFA
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Graphiques et statistiques détaillées -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Graphique des inscriptions par niveau d'étude -->
            <div class="card initial-hidden" style="animation-delay: 0.5s">
                <div class="header-gradient px-8 py-6">
                    <h2 class="text-2xl font-bold text-white flex items-center">
                        <i class="fas fa-chart-bar mr-3"></i>
                        Inscriptions par niveau d'études
                    </h2>
                </div>
                <div class="p-8">
                    <div class="relative h-80">
                        <canvas id="inscriptionsChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Statistiques des réclamations et paiements -->
            <div class="card initial-hidden" style="animation-delay: 0.6s">
                <div class="header-gradient px-8 py-6">
                    <h2 class="text-2xl font-bold text-white flex items-center">
                        <i class="fas fa-chart-pie mr-3"></i>
                        Statistiques détaillées
                    </h2>
                </div>
                <div class="p-8 space-y-8">
                    <!-- Statistiques des réclamations -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Réclamations</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="p-4 rounded-xl gradient-orange text-white shadow-lg">
                                <p class="text-sm font-medium opacity-90">En attente</p>
                                <p class="text-2xl font-bold"><?php echo number_format($stats['reclamations_en_attente']); ?></p>
                            </div>
                            <div class="p-4 rounded-xl gradient-green text-white shadow-lg">
                                <p class="text-sm font-medium opacity-90">Résolues</p>
                                <p class="text-2xl font-bold"><?php echo number_format($stats['reclamations_resolues']); ?></p>
                            </div>
                            <div class="p-4 rounded-xl gradient-red text-white shadow-lg">
                                <p class="text-sm font-medium opacity-90">Rejetées</p>
                                <p class="text-2xl font-bold"><?php echo number_format($stats['reclamations_rejetees']); ?></p>
                            </div>
                            <div class="p-4 rounded-xl header-gradient text-white shadow-lg">
                                <p class="text-sm font-medium opacity-90">Total</p>
                                <p class="text-2xl font-bold"><?php echo number_format($stats['reclamations_total']); ?></p>
                            </div>
                        </div>
                    </div>

                    <!-- Statistiques des paiements -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Paiements</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="p-4 rounded-xl header-gradient text-white shadow-lg">
                                <p class="text-sm font-medium opacity-90">Paiements complets</p>
                                <p class="text-2xl font-bold"><?php echo number_format($stats['paiements_complets']); ?></p>
                            </div>
                            <div class="p-4 rounded-xl gradient-red text-white shadow-lg">
                                <p class="text-sm font-medium opacity-90">Paiements partiels</p>
                                <p class="text-2xl font-bold"><?php echo number_format($stats['paiements_partiels']); ?></p>
                            </div>
                            <div class="p-4 rounded-xl gradient-green text-white shadow-lg">
                                <p class="text-sm font-medium opacity-90">Montant total perçu</p>
                                <p class="text-xl font-bold"><?php echo number_format($stats['montant_percu'], 0, ',', ' '); ?> FCFA</p>
                            </div>
                            <div class="p-4 rounded-xl gradient-orange text-white shadow-lg">
                                <p class="text-sm font-medium opacity-90">Montant en attente</p>
                                <p class="text-xl font-bold"><?php echo number_format($stats['montant_attente'], 0, ',', ' '); ?> FCFA</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    // Configuration du graphique des inscriptions par niveau d'étude
    const ctx = document.getElementById('inscriptionsChart').getContext('2d');

    // Préparer les données pour le graphique
    const niveauLabels = [];
    const inscriptionsData = [];

    <?php foreach($inscriptionsParNiveau as $niveau): ?>
    niveauLabels.push('<?php echo $niveau['niveau']; ?>');
    inscriptionsData.push(<?php echo $niveau['total']; ?>);
    <?php endforeach; ?>

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: niveauLabels,
            datasets: [{
                label: 'Nombre d\'inscriptions',
                data: inscriptionsData,
                backgroundColor: [
                    'rgba(52, 87, 203, 0.8)',
                    'rgba(54, 134, 90, 0.8)',
                    'rgba(89, 191, 61, 0.8)',
                    'rgba(139, 92, 246, 0.8)',
                    'rgba(245, 158, 11, 0.8)'
                ],
                borderColor: [
                    '#3457cb',
                    '#36865a',
                    '#59bf3d',
                    '#8b5cf6',
                    '#f59e0b'
                ],
                borderWidth: 2,
                borderRadius: 8,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    cornerRadius: 8,
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        color: '#6b7280',
                        font: {
                            weight: '500'
                        }
                    },
                    grid: {
                        color: 'rgba(52, 87, 203, 0.1)',
                        drawBorder: false,
                    }
                },
                x: {
                    ticks: {
                        color: '#6b7280',
                        font: {
                            weight: '500'
                        }
                    },
                    grid: {
                        display: false
                    }
                }
            },
            animation: {
                duration: 1000,
                easing: 'easeOutQuart'
            }
        }
    });

    // Animation des cartes statistiques
    document.addEventListener('DOMContentLoaded', function() {
        const cards = document.querySelectorAll('.stat-card');
        cards.forEach((card, index) => {
            card.style.animationDelay = `${index * 0.1}s`;
        });
    });
    </script>
</body>

</html>
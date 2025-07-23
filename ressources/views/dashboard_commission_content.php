<?php
// Récupérer les données du contrôleur
global $stats;
$dashboardData = $stats ?? [];

// Données par défaut si pas de données
$totalRapports = $dashboardData['total_rapports'] ?? 0;
$tauxValidation = $dashboardData['taux_validation'] ?? 0;
$tempsMoyen = $dashboardData['temps_moyen'] ?? 0;
$enAttente = $dashboardData['en_attente'] ?? 0;

// Données pour les graphiques
$evolutionData = $dashboardData['evolution_mensuelle'] ?? [];
$repartitionData = $dashboardData['repartition_statuts'] ?? [];
$performanceData = $dashboardData['performance_categories'] ?? [];
$activitesData = $dashboardData['activites_recentes'] ?? [];
$rapportsDetails = $dashboardData['rapports_details'] ?? [];
$rapportsEnAttente = $dashboardData['rapports_en_attente'] ?? [];

// Fonctions utilitaires
function getTimeAgo($date)
{
    if (!$date) return 'N/A';
    $time = time() - strtotime($date);
    if ($time < 3600) return floor($time / 60) . 'min';
    if ($time < 86400) return floor($time / 3600) . 'h';
    return floor($time / 86400) . 'j';
}

function getStatusClass($status)
{
    switch ($status) {
        case 'valide':
            return 'bg-green-100 text-green-800';
        case 'rejete':
            return 'bg-red-100 text-red-800';
        case 'en_cours':
            return 'bg-orange-100 text-orange-800';
        default:
            return 'bg-gray-100 text-gray-800';
    }
}

// Préparer les données pour Chart.js
$evolutionLabels = [];
$evolutionFinalises = [];
$evolutionRejetes = [];

foreach ($evolutionData as $data) {
    $evolutionLabels[] = date('M Y', strtotime($data['mois'] . '-01'));
    $evolutionFinalises[] = $data['finalises'];
    $evolutionRejetes[] = $data['rejetes'];
}

$statusLabels = [];
$statusData = [];
$statusColors = ['#ef4444', '#10b981', '#f59e0b', '#6b7280'];

foreach ($repartitionData as $data) {
    $statusLabels[] = ucfirst($data['statut']);
    $statusData[] = $data['nombre'];
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistiques | Commission de Validation</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <style>
        .sidebar-hover:hover {
            background-color: #fef3c7;
            border-left: 4px solid #f59e0b;
        }

        .fade-in {
            animation: fadeIn 0.3s ease-in;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .stat-card {
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .chart-container {
            position: relative;
            height: 300px;
        }

        .progress-ring {
            transition: stroke-dasharray 0.6s ease-in-out;
        }

        .metric-value {
            font-size: 2.5rem;
            font-weight: 700;
            background: linear-gradient(135deg, #f59e0b, #d97706);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .trend-up {
            color: #10b981;
        }

        .trend-down {
            color: #ef4444;
        }

        .trend-stable {
            color: #6b7280;
        }
    </style>
</head>

<body class="font-sans antialiased bg-gray-50">
    <div class="flex h-screen overflow-hidden">

        <!-- Main content area -->
        <div class="flex-1 overflow-y-auto bg-gray-50">
            <div class="max-w-7xl mx-auto p-6">

                <div class="header bg-white rounded-3xl p-8 lg:p-12 mb-8 shadow-xl relative overflow-hidden">
                    <div class="flex items-center gap-6 md:gap-8 flex-col md:flex-row text-center md:text-left">
                        <div class="header-icon bg-gradient-to-br from-primary to-primary-dark text-white w-20 h-20 md:w-24 md:h-24 rounded-2xl flex items-center justify-center text-4xl md:text-5xl shadow-lg">
                            <i class="fas fa-clipboard-check"></i>
                        </div>
                        <div class="header-text">
                            <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 mb-2 tracking-tight">Tableau de bord Commission</h1>
                            <p class="text-lg text-gray-600 font-normal">Vue d'ensemble et suivi des rapports de soutenance</p>
                        </div>
                    </div>
                </div>


                <!-- STAT CARDS -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="stat-card bg-white rounded-2xl p-6 shadow-md transition-all duration-300 hover:translate-y-[-4px] hover:shadow-lg relative overflow-hidden" style="animation-delay: 0.1s">
                        <div class="stat-content flex items-center gap-4">
                            <div class="stat-icon bg-primary/10 text-primary w-12 h-12 rounded-xl flex items-center justify-center text-2xl">
                                <i class="fas fa-file-alt"></i>
                            </div>
                            <div class="stat-info">
                                <h3 class="text-4xl font-bold text-primary mb-1">12</h3>
                                <p class="text-sm font-medium text-gray-600">Rapports total</p>
                            </div>
                        </div>
                    </div>
                    <div class="stat-card bg-white rounded-2xl p-6 shadow-md transition-all duration-300 hover:translate-y-[-4px] hover:shadow-lg relative overflow-hidden" style="animation-delay: 0.2s">
                        <div class="stat-content flex items-center gap-4">
                            <div class="stat-icon bg-secondary/10 text-secondary w-12 h-12 rounded-xl flex items-center justify-center text-2xl">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="stat-info">
                                <h3 class="text-4xl font-bold text-secondary mb-1">75%</h3>
                                <p class="text-sm font-medium text-gray-600">Taux de validation</p>
                            </div>
                        </div>
                    </div>
                    <div class="stat-card bg-white rounded-2xl p-6 shadow-md transition-all duration-300 hover:translate-y-[-4px] hover:shadow-lg relative overflow-hidden" style="animation-delay: 0.3s">
                        <div class="stat-content flex items-center gap-4">
                            <div class="stat-icon bg-warning/10 text-warning w-12 h-12 rounded-xl flex items-center justify-center text-2xl">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="stat-info">
                                <h3 class="text-4xl font-bold text-warning mb-1">3.2j</h3>
                                <p class="text-sm font-medium text-gray-600">Temps moyen</p>
                            </div>
                        </div>
                    </div>
                    <div class="stat-card bg-white rounded-2xl p-6 shadow-md transition-all duration-300 hover:translate-y-[-4px] hover:shadow-lg relative overflow-hidden" style="animation-delay: 0.4s">
                        <div class="stat-content flex items-center gap-4">
                            <div class="stat-icon bg-danger/10 text-danger w-12 h-12 rounded-xl flex items-center justify-center text-2xl">
                                <i class="fas fa-hourglass-half"></i>
                            </div>
                            <div class="stat-info">
                                <h3 class="text-4xl font-bold text-danger mb-1">3</h3>
                                <p class="text-sm font-medium text-gray-600">En attente</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Row -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Evolution Chart -->
                <div class="bg-white rounded-lg shadow p-6 fade-in">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">
                            <i class="fas fa-chart-line text-blue-600 mr-2"></i>
                            Évolution des Comptes Rendus
                        </h3>
                        <div class="flex items-center space-x-2 text-sm">
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-blue-600 rounded-full mr-1"></div>
                                <span>Finalisés</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-yellow-600 rounded-full mr-1"></div>
                                <span>Rejetés</span>
                            </div>
                        </div>
                    </div>
                    <div class="chart-container">
                        <canvas id="evolutionChart"></canvas>
                    </div>
                </div>
                <!-- Graphique répartition -->
                <div class="bg-white rounded-xl shadow-lg p-6 fade-in">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                            <i class="fas fa-chart-bar text-green-600 mr-3"></i> Répartition par Statut
                        </h3>
                        <button onclick="refreshCharts()" class="text-gray-400 hover:text-gray-600 transition-colors duration-200">
                            <i class="fas fa-sync-alt text-lg"></i>
                        </button>
                    </div>
                    <div class="chart-container">
                        <canvas id="statusChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Detailed Stats -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">


                <!-- Recent Activity -->
                <div class="bg-white rounded-lg shadow p-6 fade-in">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">
                        <i class="fas fa-history text-indigo-600 mr-2"></i>
                        Rapports en attente de validation
                    </h3>
                    <div class="space-y-3">
                        <?php if (!empty($rapportsEnAttente)): ?>
                            <?php foreach ($rapportsEnAttente as $rapport): ?>
                                <div class="flex items-center space-x-3 p-2 hover:bg-gray-50 rounded-lg">
                                    <div class="flex-1">
                                        <p class="text-sm font-medium">Rapport <?php echo ucfirst($rapport['statut_rapport']); ?></p>
                                        <p class="text-xs text-gray-500">
                                            <?php echo $rapport['titre']; ?> - <?php echo $rapport['prenom_etudiant'] . ' ' . $rapport['nom_etudiant']; ?>
                                        </p>
                                        <p class="text-xs text-gray-400">
                                            Par <?php echo $rapport['prenom_enseignant'] . ' ' . $rapport['nom_enseignant']; ?>
                                        </p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="text-center text-gray-500 py-2">
                                <i class="fas fa-history text-4xl mb-2"></i>
                                <p>Aucun rapport en attente de validation</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-lg shadow p-6 fade-in">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">
                        <i class="fas fa-bolt text-yellow-600 mr-2"></i>
                        Actions Rapides
                    </h3>
                    <div class="grid grid-cols-2 gap-4 p-8">
                        <button onclick="generateReport()"
                            class="w-full flex flex-col items-center justify-center px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-50">
                            <i class="fas fa-chart-bar mr-2 mb-2 text-3xl"></i>
                            Générer Rapport Mensuel
                        </button>
                        <button onclick="exportData()"
                            class="w-full flex flex-col items-center justify-center px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-50">
                            <i class="fas fa-download mr-2 mb-2 text-3xl"></i>
                            Exporter Données
                        </button>
                        <button onclick="viewArchives()"
                            class="w-full flex flex-col items-center justify-center px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-50">
                            <i class="fas fa-archive mr-2 mb-2 text-3xl"></i>
                            Consulter Archives
                        </button>
                        <button onclick="settings()"
                            class="w-full flex flex-col items-center justify-center px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-50">
                            <i class="fas fa-cog mr-2 mb-2 text-3xl"></i>
                            Paramètres
                        </button>
                    </div>
                </div>

            </div>

            <!-- Performance Table -->
            <div class="bg-white rounded-lg shadow p-6 fade-in">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">
                        <i class="fas fa-table text-gray-600 mr-2"></i>
                        Détails des Performances
                    </h3>
                    <div class="flex items-center space-x-2">
                        <input type="text" placeholder="Rechercher..."
                            class="px-3 py-1 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500">
                        <button class="px-3 py-1 text-sm bg-yellow-600 text-white rounded-md hover:bg-yellow-700">
                            <i class="fas fa-filter"></i>
                        </button>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Titre</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Étudiant</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Enseignant</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Temps de traitement</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php if (!empty($rapportsDetails)): ?>
                                <?php foreach ($rapportsDetails as $rapport): ?>
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-2 text-sm">
                                            <span class="px-2 py-1 text-xs rounded-full <?php echo $rapport['statut'] === 'valider' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                                                <?php echo ucfirst($rapport['statut']); ?>
                                            </span>
                                        </td>
                                        <td class="px-4 py-2 text-sm text-gray-600">
                                            <?php echo $rapport['titre']; ?>
                                        </td>
                                        <td class="px-4 py-2 text-sm text-gray-600">
                                            <?php echo $rapport['prenom_etudiant'] . ' ' . $rapport['nom_etudiant']; ?>
                                        </td>
                                        <td class="px-4 py-2 text-sm text-gray-600">
                                            <?php echo $rapport['prenom_enseignant'] . ' ' . $rapport['nom_enseignant']; ?>
                                        </td>
                                        <td class="px-4 py-2 text-sm text-gray-600">
                                            <?php echo $rapport['temps_traitement'] ?? 0; ?> jours
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <button class="text-blue-600 hover:text-blue-900 mr-2">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="text-green-600 hover:text-green-900">
                                                <i class="fas fa-download"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                        <i class="fas fa-table text-2xl mb-2"></i>
                                        <p>Aucun rapport disponible</p>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- FOOTER -->
            <footer class="w-full py-6 bg-gradient-to-r from-green-200 to-blue-100 text-center text-gray-600 text-sm rounded-t-2xl mt-10 shadow-inner">
                &copy; <?php echo date('Y'); ?> Univalid - Dashboard Commission
            </footer>
        </div>
    </div>

    </div>

    <script>
        // Variables globales pour les graphiques
        let evolutionChart, statusChart;

        // Données pour les graphiques depuis PHP
        const evolutionData = {
            labels: <?php echo json_encode($evolutionLabels); ?>,
            datasets: [{
                    label: 'Finalisés',
                    data: <?php echo json_encode($evolutionFinalises); ?>,
                    borderColor: '#3b82f6', // Tailwind blue-500
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4,
                    fill: true
                },
                {
                    label: 'Rejetés',
                    data: <?php echo json_encode($evolutionRejetes); ?>,
                    borderColor: '#f59e0b', // Tailwind yellow-500
                    backgroundColor: 'rgba(245, 158, 11, 0.1)',
                    tension: 0.4,
                    fill: true
                }
            ]
        };

        const statusData = {
            labels: <?php echo json_encode($statusLabels); ?>,
            datasets: [{
                data: <?php echo json_encode($statusData); ?>,
                // Using a darker shade for bars for better visibility
                backgroundColor: ['#ef4444', '#10b981', '#f59e0b', '#6b7280'].map(color => Chart.helpers.color(color).alpha(0.8).rgbString()),
                borderColor: ['#dc2626', '#059669', '#d97706', '#4b5563'], // Corresponding darker borders
                borderWidth: 1
            }]
        };

        // Initialisation des graphiques
        function initCharts() {
            // Graphique d'évolution
            const evolutionCtx = document.getElementById('evolutionChart').getContext('2d');
            evolutionChart = new Chart(evolutionCtx, {
                type: 'line',
                data: evolutionData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                usePointStyle: true,
                                padding: 20,
                                font: {
                                    size: 13,
                                    family: 'Inter, sans-serif'
                                },
                                color: '#4b5563' // Tailwind gray-700
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(15, 23, 42, 0.9)', // Tailwind slate-900 with transparency
                            titleColor: '#f9fafb', // Tailwind gray-50
                            bodyColor: '#e5e7eb', // Tailwind gray-200
                            padding: 10,
                            cornerRadius: 6,
                            displayColors: true,
                            boxPadding: 4
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: '#e5e7eb', // Tailwind gray-200
                                borderColor: '#e5e7eb',
                                borderWidth: 1
                            },
                            ticks: {
                                color: '#6b7280' // Tailwind gray-500
                            }
                        },
                        x: {
                            grid: {
                                display: false,
                                borderColor: '#e5e7eb',
                                borderWidth: 1
                            },
                            ticks: {
                                color: '#6b7280' // Tailwind gray-500
                            }
                        }
                    },
                    elements: {
                        point: {
                            radius: 4,
                            hoverRadius: 6,
                            backgroundColor: '#ffffff', // White point fill
                            borderColor: function(context) {
                                return context.dataset.borderColor;
                            },
                            borderWidth: 2
                        },
                        line: {
                            borderWidth: 2
                        }
                    }
                }
            });

            // Graphique de statut - CHANGED TO BAR CHART
            const statusCtx = document.getElementById('statusChart').getContext('2d');
            statusChart = new Chart(statusCtx, {
                type: 'bar', // Changed from 'doughnut' to 'bar'
                data: statusData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false // No legend needed for single-dataset bar chart
                        },
                        tooltip: {
                            backgroundColor: 'rgba(15, 23, 42, 0.9)',
                            titleColor: '#f9fafb',
                            bodyColor: '#e5e7eb',
                            padding: 10,
                            cornerRadius: 6,
                            displayColors: true,
                            boxPadding: 2
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: '#e5e7eb',
                                borderColor: '#e5e7eb',
                                borderWidth: 1
                            },
                            ticks: {
                                color: '#6b7280'
                            }
                        },
                        x: {
                            grid: {
                                display: false,
                                borderColor: '#e5e7eb',
                                borderWidth: 1
                            },
                            ticks: {
                                color: '#6b7280'
                            }
                        }
                    }
                }
            });
        }

        // Fonctions d'action
        function showNotification(message, type) {
            const notification = document.createElement('div');
            notification.className = `fixed top-6 right-6 px-5 py-3 rounded-lg text-white text-sm font-semibold shadow-xl z-50 transform transition-all duration-300 ease-out flex items-center space-x-2 ${
                type === 'success' ? 'bg-green-500' :
                type === 'error' ? 'bg-red-500' :
                type === 'info' ? 'bg-blue-500' : 'bg-gray-500'
            }`;
            notification.innerHTML = `
                <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'times-circle' : 'info-circle'} text-lg"></i>
                <span>${message}</span>
            `;

            document.body.appendChild(notification);

            // Animate in
            setTimeout(() => {
                notification.style.transform = 'translateY(0)';
                notification.style.opacity = '1';
            }, 10); // Small delay to allow CSS to render initial state

            // Animate out and remove
            setTimeout(() => {
                notification.style.transform = 'translateY(-20px)';
                notification.style.opacity = '0';
                notification.addEventListener('transitionend', () => notification.remove());
            }, 3000);
        }

        function refreshCharts() {
            showNotification('Actualisation des données...', 'info');
            setTimeout(() => {
                // In a real application, you would re-fetch data here
                // and then update chart data and call chart.update()
                // For this example, we just simulate success
                showNotification('Données actualisées avec succès', 'success');
            }, 1500);
        }

        function generateReport() {
            showNotification('Génération du rapport mensuel...', 'info');
            setTimeout(() => {
                showNotification('Rapport mensuel généré avec succès', 'success');
            }, 3000);
        }

        function exportData() {
            showNotification('Export des données en cours...', 'info');
            setTimeout(() => {
                showNotification('Données exportées au format Excel', 'success');
            }, 2000);
        }

        function viewArchives() {
            showNotification('Redirection vers les archives...', 'info');
            setTimeout(() => {
                showNotification('Ouverture de la section archives', 'success');
            }, 1000);
        }

        function settings() {
            showNotification('Ouverture des paramètres...', 'info');
        }

        // Animation des métriques au chargement
        function animateMetrics() {
            const metrics = document.querySelectorAll('.metric-value');
            metrics.forEach((metric, index) => {
                const finalValueText = metric.textContent;
                const isPercentage = finalValueText.includes('%');
                const isDays = finalValueText.includes('j');
                const finalValue = parseFloat(finalValueText);

                metric.textContent = isPercentage ? '0%' : (isDays ? '0.0j' : '0');

                setTimeout(() => {
                    const duration = 1500; // milliseconds
                    const startTime = performance.now();

                    function animate(currentTime) {
                        const elapsedTime = currentTime - startTime;
                        const progress = Math.min(elapsedTime / duration, 1);
                        let currentValue = progress * finalValue;

                        if (isPercentage) {
                            metric.textContent = Math.round(currentValue) + '%';
                        } else if (isDays) {
                            metric.textContent = currentValue.toFixed(1) + 'j';
                        } else {
                            metric.textContent = Math.round(currentValue);
                        }

                        if (progress < 1) {
                            requestAnimationFrame(animate);
                        }
                    }
                    requestAnimationFrame(animate);
                }, index * 200);
            });
        }

        // Initialisation
        document.addEventListener('DOMContentLoaded', function() {
            initCharts();
            animateMetrics();

            const cards = document.querySelectorAll('.fade-in');
            cards.forEach((card, index) => {
                // Apply a slight delay for staggered fade-in
                card.style.animationDelay = `${index * 0.05}s`;
            });

            document.addEventListener('keydown', function(e) {
                if (e.ctrlKey && e.key === 'r') {
                    e.preventDefault();
                    refreshCharts();
                }
            });

            // Auto-refresh every 5 minutes (300000 ms)
            setInterval(() => {
                refreshCharts();
            }, 300000);
        });
    </script>
</body>

</html>
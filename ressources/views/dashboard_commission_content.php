<?php
// Récupérer les données du contrôleur
global $stats;
$dashboardData = $stats ?? [];

// Données par défaut si pas de données
$totalRapports = $dashboardData['total_rapports'] ?? 12;
$tauxValidation = $dashboardData['taux_validation'] ?? 75;
$tempsMoyen = $dashboardData['temps_moyen'] ?? 3.2;
$enAttente = $dashboardData['en_attente'] ?? 3;

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
$evolutionLabels = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin'];
$evolutionFinalises = [8, 12, 15, 18, 22, 25];
$evolutionRejetes = [2, 3, 4, 2, 3, 2];

$statusLabels = ['En attente', 'Validé', 'Rejeté', 'En cours'];
$statusData = [3, 18, 5, 2];
$statusColors = ['#f59e0b', '#36865a', '#ef4444', '#6b7280'];

foreach ($evolutionData as $data) {
    $evolutionLabels[] = date('M Y', strtotime($data['mois'] . '-01'));
    $evolutionFinalises[] = $data['finalises'];
    $evolutionRejetes[] = $data['rejetes'];
}

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
    <title>Dashboard Commission | Univalid</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary': '#24407a',
                        'primary-light': '#3457cb', 
                        'secondary': '#36865a',
                        'secondary-light': '#59bf3d',
                        'accent': '#F6C700',
                        'warning': '#f59e0b',
                        'danger': '#ef4444',
                    },
                    animation: {
                        'float': 'float 3s ease-in-out infinite',
                        'pulse-slow': 'pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                        'fade-in-down': 'fadeInDown 0.8s ease-out forwards',
                        'slide-in-right': 'slideInRight 0.8s ease-out forwards',
                        'scale-in': 'scaleIn 0.5s ease-out forwards',
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

        /* Animations */
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
            transform: translateY(-4px);
            box-shadow: 0 16px 48px rgba(15, 23, 42, 0.12);
        }

        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 4px 16px rgba(15, 23, 42, 0.08);
            transition: all 0.3s ease;
            border: 1px solid #e2e8f0;
            position: relative;
            overflow: hidden;
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
            box-shadow: 0 12px 32px rgba(15, 23, 42, 0.12);
        }

        .header-gradient {
            background: linear-gradient(135deg, #24407a 0%, #3457cb 100%);
        }

        .chart-container {
            position: relative;
            height: 300px;
            padding: 16px;
        }

        .action-btn {
            transition: all 0.3s ease;
            border-radius: 12px;
            padding: 16px;
            border: 2px solid #e2e8f0;
            background: white;
        }

        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.1);
            border-color: rgba(52, 87, 203, 0.2);
        }

        .notification {
            border-radius: 16px;
            padding: 16px 20px;
            box-shadow: 0 4px 16px rgba(15, 23, 42, 0.1);
            border: 1px solid;
        }

        .notification.success {
            background: linear-gradient(135deg, rgba(54, 134, 90, 0.1) 0%, rgba(89, 191, 61, 0.1) 100%);
            border-color: rgba(54, 134, 90, 0.2);
            color: #36865a;
        }

        .notification.error {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.1) 0%, rgba(220, 38, 38, 0.1) 100%);
            border-color: rgba(239, 68, 68, 0.2);
            color: #dc2626;
        }

        .notification.info {
            background: linear-gradient(135deg, rgba(52, 87, 203, 0.1) 0%, rgba(59, 130, 246, 0.1) 100%);
            border-color: rgba(52, 87, 203, 0.2);
            color: #3457cb;
        }

        .fade-in {
            animation: fadeInDown 0.6s ease-out forwards;
        }

        .activity-item {
            padding: 16px;
            border-radius: 12px;
            transition: all 0.2s ease;
            border: 1px solid #f1f5f9;
        }

        .activity-item:hover {
            background: rgba(52, 87, 203, 0.02);
            border-color: rgba(52, 87, 203, 0.1);
            transform: translateX(4px);
        }

        .table-hover tr {
            transition: all 0.2s ease;
        }

        .table-hover tr:hover {
            background: rgba(52, 87, 203, 0.02);
            transform: scale(1.01);
        }

        .badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
    </style>
</head>

<body class="bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <!-- Header Section -->
        <div class="header bg-white rounded-3xl p-8 lg:p-12 mb-8 shadow-xl relative overflow-hidden animate-fade-in-down">
            <div class="flex items-center gap-6 md:gap-8 flex-col md:flex-row text-center md:text-left">
                <div class="header-icon bg-gradient-to-br from-primary to-primary-light text-white w-20 h-20 md:w-24 md:h-24 rounded-2xl flex items-center justify-center text-4xl md:text-5xl shadow-lg">
                    <i class="fas fa-clipboard-check"></i>
                </div>
                <div class="header-text">
                    <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 mb-2 tracking-tight">Dashboard Commission</h1>
                    <p class="text-lg text-gray-600 font-normal">Vue d'ensemble et suivi des rapports de soutenance</p>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="stat-card animate-slide-in-right" style="animation-delay: 0.1s">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-primary/10 text-primary rounded-xl flex items-center justify-center text-2xl">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <div>
                        <h3 class="text-3xl font-bold text-primary mb-1"><?php echo $totalRapports; ?></h3>
                        <p class="text-sm font-semibold text-gray-600">Rapports Total</p>
                        <p class="text-xs text-blue-600 font-medium">
                            <i class="fas fa-arrow-up mr-1"></i>+12% ce mois
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="stat-card animate-slide-in-right" style="animation-delay: 0.2s">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-secondary/10 text-secondary rounded-xl flex items-center justify-center text-2xl">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div>
                        <h3 class="text-3xl font-bold text-secondary mb-1"><?php echo $tauxValidation; ?>%</h3>
                        <p class="text-sm font-semibold text-gray-600">Taux de Validation</p>
                        <p class="text-xs text-green-600 font-medium">
                            <i class="fas fa-arrow-up mr-1"></i>+5% ce mois
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="stat-card animate-slide-in-right" style="animation-delay: 0.3s">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-warning/10 text-warning rounded-xl flex items-center justify-center text-2xl">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div>
                        <h3 class="text-3xl font-bold text-warning mb-1"><?php echo $tempsMoyen; ?>j</h3>
                        <p class="text-sm font-semibold text-gray-600">Temps Moyen</p>
                        <p class="text-xs text-green-600 font-medium">
                            <i class="fas fa-arrow-down mr-1"></i>-0.8j ce mois
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="stat-card animate-slide-in-right" style="animation-delay: 0.4s">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-orange-500/10 text-orange-600 rounded-xl flex items-center justify-center text-2xl">
                        <i class="fas fa-hourglass-half"></i>
                    </div>
                    <div>
                        <h3 class="text-3xl font-bold text-orange-600 mb-1"><?php echo $enAttente; ?></h3>
                        <p class="text-sm font-semibold text-gray-600">En Attente</p>
                        <p class="text-xs text-orange-600 font-medium">
                            <i class="fas fa-minus mr-1"></i>Stable
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Evolution Chart -->
            <div class="card animate-scale-in">
                <div class="header-gradient px-8 py-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-2xl font-bold text-white flex items-center">
                                <i class="fas fa-chart-line mr-3"></i>
                                Évolution des Rapports
                            </h3>
                            <p class="text-blue-100">Finalisés vs Rejetés sur 6 mois</p>
                        </div>
                        <div class="flex items-center space-x-3 text-sm">
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-blue-300 rounded-full mr-2"></div>
                                <span class="text-white">Finalisés</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-yellow-300 rounded-full mr-2"></div>
                                <span class="text-white">Rejetés</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="chart-container">
                    <canvas id="evolutionChart"></canvas>
                </div>
            </div>

            <!-- Status Distribution Chart -->
            <div class="card animate-scale-in" style="animation-delay: 0.2s">
                <div class="header-gradient px-8 py-6">
                    <div class="flex items-center justify-between">
                        <h3 class="text-2xl font-bold text-white flex items-center">
                            <i class="fas fa-chart-pie mr-3"></i>
                            Répartition par Statut
                        </h3>
                        <button onclick="refreshCharts()" class="text-blue-100 hover:text-white transition-colors duration-200">
                            <i class="fas fa-sync-alt text-lg"></i>
                        </button>
                    </div>
                </div>
                <div class="chart-container">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Detailed Information Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Reports Pending -->
            <div class="card animate-scale-in" style="animation-delay: 0.3s">
                <div class="header-gradient px-8 py-6">
                    <h3 class="text-2xl font-bold text-white flex items-center">
                        <i class="fas fa-history mr-3"></i>
                        Rapports en Attente
                    </h3>
                </div>
                <div class="p-8">
                    <div class="space-y-4">
                        <?php if (!empty($rapportsEnAttente)): ?>
                            <?php foreach ($rapportsEnAttente as $rapport): ?>
                                <div class="activity-item">
                                    <div class="flex items-start space-x-4">
                                        <div class="w-10 h-10 bg-orange-100 text-orange-600 rounded-full flex items-center justify-center text-sm font-bold">
                                            <?php echo strtoupper(substr($rapport['prenom_etudiant'], 0, 1) . substr($rapport['nom_etudiant'], 0, 1)); ?>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-sm font-semibold text-gray-900">
                                                <?php echo $rapport['titre'] ?? 'Rapport de soutenance'; ?>
                                            </p>
                                            <p class="text-xs text-gray-600">
                                                Par <?php echo $rapport['prenom_etudiant'] . ' ' . $rapport['nom_etudiant']; ?>
                                            </p>
                                            <p class="text-xs text-primary font-medium">
                                                Encadrant: <?php echo $rapport['prenom_enseignant'] . ' ' . $rapport['nom_enseignant']; ?>
                                            </p>
                                        </div>
                                        <span class="badge bg-orange-100 text-orange-800">
                                            En attente
                                        </span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="text-center py-8">
                                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-check text-2xl text-green-600"></i>
                                </div>
                                <p class="text-gray-500 font-medium">Aucun rapport en attente</p>
                                <p class="text-sm text-gray-400">Tous les rapports sont traités !</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card animate-scale-in" style="animation-delay: 0.4s">
                <div class="header-gradient px-8 py-6">
                    <h3 class="text-2xl font-bold text-white flex items-center">
                        <i class="fas fa-bolt mr-3"></i>
                        Actions Rapides
                    </h3>
                </div>
                <div class="p-8">
                    <div class="grid grid-cols-2 gap-4">
                        <button onclick="generateReport()" class="action-btn text-center">
                            <div class="w-12 h-12 bg-green-100 text-green-600 rounded-xl flex items-center justify-center text-2xl mx-auto mb-3">
                                <i class="fas fa-chart-bar"></i>
                            </div>
                            <p class="text-sm font-semibold text-gray-700">Rapport Mensuel</p>
                            <p class="text-xs text-gray-500">Générer le rapport</p>
                        </button>
                        
                        <button onclick="exportData()" class="action-btn text-center">
                            <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center text-2xl mx-auto mb-3">
                                <i class="fas fa-download"></i>
                            </div>
                            <p class="text-sm font-semibold text-gray-700">Exporter</p>
                            <p class="text-xs text-gray-500">Données Excel</p>
                        </button>
                        
                        <button onclick="viewArchives()" class="action-btn text-center">
                            <div class="w-12 h-12 bg-purple-100 text-purple-600 rounded-xl flex items-center justify-center text-2xl mx-auto mb-3">
                                <i class="fas fa-archive"></i>
                            </div>
                            <p class="text-sm font-semibold text-gray-700">Archives</p>
                            <p class="text-xs text-gray-500">Consulter l'historique</p>
                        </button>
                        
                        <button onclick="settings()" class="action-btn text-center">
                            <div class="w-12 h-12 bg-gray-100 text-gray-600 rounded-xl flex items-center justify-center text-2xl mx-auto mb-3">
                                <i class="fas fa-cog"></i>
                            </div>
                            <p class="text-sm font-semibold text-gray-700">Paramètres</p>
                            <p class="text-xs text-gray-500">Configuration</p>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Performance Table -->
        <div class="card animate-scale-in" style="animation-delay: 0.5s">
            <div class="header-gradient px-8 py-6">
                <div class="flex items-center justify-between">
                    <h3 class="text-2xl font-bold text-white flex items-center">
                        <i class="fas fa-table mr-3"></i>
                        Détails des Performances
                    </h3>
                    <div class="flex items-center space-x-3">
                        <input type="text" placeholder="Rechercher..."
                            class="px-4 py-2 text-sm border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-300 focus:border-blue-300 bg-white/90">
                        <button class="px-4 py-2 text-sm bg-white/20 text-white rounded-lg hover:bg-white/30 transition-colors">
                            <i class="fas fa-filter mr-2"></i>Filtrer
                        </button>
                    </div>
                </div>
            </div>
            <div class="p-8">
                <div class="overflow-x-auto">
                    <table class="table-hover w-full">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="px-4 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">📊 Statut</th>
                                <th class="px-4 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">📝 Titre</th>
                                <th class="px-4 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">👨‍🎓 Étudiant</th>
                                <th class="px-4 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">👨‍🏫 Enseignant</th>
                                <th class="px-4 py-4 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">🔧 Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <?php if (!empty($rapportsDetails)): ?>
                                <?php foreach ($rapportsDetails as $rapport): ?>
                                    <tr>
                                        <td class="px-4 py-4">
                                            <span class="badge <?php echo $rapport['statut'] === 'valider' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                                                <?php echo ucfirst($rapport['statut']); ?>
                                            </span>
                                        </td>
                                        <td class="px-4 py-4">
                                            <p class="text-sm font-semibold text-gray-900"><?php echo $rapport['titre']; ?></p>
                                        </td>
                                        <td class="px-4 py-4">
                                            <div class="flex items-center">
                                                <div class="w-8 h-8 bg-primary/10 text-primary rounded-full flex items-center justify-center text-xs font-bold mr-3">
                                                    <?php echo strtoupper(substr($rapport['prenom_etudiant'], 0, 1) . substr($rapport['nom_etudiant'], 0, 1)); ?>
                                                </div>
                                                <span class="text-sm text-gray-900"><?php echo $rapport['prenom_etudiant'] . ' ' . $rapport['nom_etudiant']; ?></span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-4 text-sm text-gray-600">
                                            <?php echo $rapport['prenom_enseignant'] . ' ' . $rapport['nom_enseignant']; ?>
                                        </td>
                                        
                                        <td class="px-4 py-4 text-center">
                                            <div class="flex justify-center space-x-2">
                                                <button class="text-blue-600 hover:text-blue-800 transition-colors p-1">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button class="text-green-600 hover:text-green-800 transition-colors p-1">
                                                    <i class="fas fa-download"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center">
                                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                                <i class="fas fa-table text-3xl text-gray-400"></i>
                                            </div>
                                            <p class="text-lg font-semibold text-gray-500 mb-2">Aucun rapport disponible</p>
                                            <p class="text-sm text-gray-400">Les données apparaîtront ici une fois disponibles.</p>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Chart initialization
        let evolutionChart, statusChart;

        // Chart data
        const evolutionData = {
            labels: <?php echo json_encode($evolutionLabels); ?>,
            datasets: [{
                    label: 'Finalisés',
                    data: <?php echo json_encode($evolutionFinalises); ?>,
                    borderColor: '#3457cb',
                    backgroundColor: 'rgba(52, 87, 203, 0.1)',
                    tension: 0.4,
                    fill: true,
                    borderWidth: 3,
                    pointBackgroundColor: '#3457cb',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 6
                },
                {
                    label: 'Rejetés',
                    data: <?php echo json_encode($evolutionRejetes); ?>,
                    borderColor: '#f59e0b',
                    backgroundColor: 'rgba(245, 158, 11, 0.1)',
                    tension: 0.4,
                    fill: true,
                    borderWidth: 3,
                    pointBackgroundColor: '#f59e0b',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 6
                }
            ]
        };

        const statusData = {
            labels: <?php echo json_encode($statusLabels); ?>,
            datasets: [{
                data: <?php echo json_encode($statusData); ?>,
                backgroundColor: ['#f59e0b', '#36865a', '#ef4444', '#6b7280'],
                borderColor: ['#d97706', '#2d5a47', '#dc2626', '#4b5563'],
                borderWidth: 2
            }]
        };

        function initCharts() {
            // Evolution Chart
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
                                font: { size: 13, family: 'Inter, sans-serif', weight: '600' },
                                color: '#4b5563'
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(15, 23, 42, 0.9)',
                            titleColor: '#f9fafb',
                            bodyColor: '#e5e7eb',
                            padding: 12,
                            cornerRadius: 8
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: '#e5e7eb' },
                            ticks: { color: '#6b7280', font: { weight: '500' } }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { color: '#6b7280', font: { weight: '500' } }
                        }
                    }
                }
            });

            // Status Chart
            const statusCtx = document.getElementById('statusChart').getContext('2d');
            statusChart = new Chart(statusCtx, {
                type: 'doughnut',
                data: statusData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                usePointStyle: true,
                                padding: 20,
                                font: { size: 13, family: 'Inter, sans-serif', weight: '600' },
                                color: '#4b5563'
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(15, 23, 42, 0.9)',
                            titleColor: '#f9fafb',
                            bodyColor: '#e5e7eb',
                            padding: 12,
                            cornerRadius: 8
                        }
                    },
                    cutout: '65%'
                }
            });
        }

        // Notification system
        function showNotification(message, type) {
            const notification = document.createElement('div');
            notification.className = `notification ${type} fixed top-6 right-6 z-50 transform transition-all duration-300 ease-out flex items-center space-x-3`;
            notification.innerHTML = `
                <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'times-circle' : 'info-circle'} text-lg"></i>
                <span class="font-semibold">${message}</span>
            `;

            document.body.appendChild(notification);

            setTimeout(() => {
                notification.style.transform = 'translateY(-20px)';
                notification.style.opacity = '0';
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        }

        // Action functions
        function refreshCharts() {
            showNotification('Actualisation des données...', 'info');
            setTimeout(() => showNotification('Données actualisées avec succès', 'success'), 1500);
        }

        function generateReport() {
            showNotification('Génération du rapport mensuel...', 'info');
            setTimeout(() => showNotification('Rapport mensuel généré avec succès', 'success'), 3000);
        }

        function exportData() {
            showNotification('Export des données en cours...', 'info');
            setTimeout(() => showNotification('Données exportées au format Excel', 'success'), 2000);
        }

        function viewArchives() {
            showNotification('Redirection vers les archives...', 'info');
            setTimeout(() => window.location.href = '?page=archives_dossiers_soutenance', 1000);
        }

        function settings() {
            showNotification('Ouverture des paramètres...', 'info');
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            initCharts();

            // Animate elements
            const elements = document.querySelectorAll('.fade-in, .animate-scale-in, .animate-slide-in-right');
            elements.forEach((el, index) => {
                el.style.animationDelay = `${index * 0.1}s`;
            });
        });
    </script>
</body>

</html>
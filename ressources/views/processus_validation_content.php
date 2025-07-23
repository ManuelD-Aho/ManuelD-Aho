<?php
require_once __DIR__ . '/../../app/config/database.php';

$pdo = Database::getConnection();
$rapportModel = new RapportEtudiant($pdo);

// Récupérer tous les rapports (ou adapter selon le rôle/filtre)
$rapports = $rapportModel->getAllRapports(); // ou getRapportsByStatut('valide'), etc.
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Processus de Validation | Univalid</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
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

        .report-card {
            background: white;
            border-radius: 20px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 8px 32px rgba(15, 23, 42, 0.08);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            margin-bottom: 24px;
        }

        .report-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--card-gradient);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .report-card:hover::before {
            opacity: 1;
        }

        .report-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 16px 48px rgba(15, 23, 42, 0.12);
        }

        .report-card[data-status="en_cours"] {
            --card-gradient: linear-gradient(90deg, #f59e0b 0%, #fbbf24 100%);
        }

        .report-card[data-status="valide"] {
            --card-gradient: linear-gradient(90deg, #36865a 0%, #59bf3d 100%);
        }

        .report-card[data-status="rejete"] {
            --card-gradient: linear-gradient(90deg, #ef4444 0%, #f87171 100%);
        }

        .status-badge {
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-en_cours {
            background: linear-gradient(135deg, rgba(245, 158, 11, 0.1) 0%, rgba(251, 191, 36, 0.1) 100%);
            color: #f59e0b;
            border: 1px solid rgba(245, 158, 11, 0.2);
        }

        .status-valide {
            background: linear-gradient(135deg, rgba(54, 134, 90, 0.1) 0%, rgba(89, 191, 61, 0.1) 100%);
            color: #36865a;
            border: 1px solid rgba(54, 134, 90, 0.2);
        }

        .status-rejete {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.1) 0%, rgba(248, 113, 113, 0.1) 100%);
            color: #ef4444;
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

        .btn {
            padding: 12px 24px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #3457cb 0%, #24407a 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(52, 87, 203, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(52, 87, 203, 0.4);
        }

        .btn-secondary {
            background: linear-gradient(135deg, #36865a 0%, #59bf3d 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(54, 134, 90, 0.3);
        }

        .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(54, 134, 90, 0.4);
        }

        .btn-warning {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
        }

        .btn-warning:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(245, 158, 11, 0.4);
        }

        .btn-danger {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        }

        .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(239, 68, 68, 0.4);
        }

        .filter-input {
            background: white;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 12px 16px 12px 48px;
            transition: all 0.3s ease;
            width: 100%;
        }

        .filter-input:focus {
            border-color: #3457cb;
            box-shadow: 0 0 0 3px rgba(52, 87, 203, 0.1);
            outline: none;
        }

        .filter-select {
            background: white;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 12px 16px 12px 48px;
            transition: all 0.3s ease;
            width: 100%;
            appearance: none;
        }

        .filter-select:focus {
            border-color: #3457cb;
            box-shadow: 0 0 0 3px rgba(52, 87, 203, 0.1);
            outline: none;
        }

        .avatar {
            background: linear-gradient(135deg, #3457cb 0%, #36865a 100%);
            color: white;
            border-radius: 12px;
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 16px;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
            margin-right: 16px;
        }

        .header-gradient {
            background: linear-gradient(135deg, #24407a 0%, #3457cb 100%);
        }

        .floating-action {
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 1000;
            background: linear-gradient(135deg, #3457cb 0%, #24407a 100%);
            color: white;
            width: 56px;
            height: 56px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 24px rgba(52, 87, 203, 0.4);
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .floating-action:hover {
            transform: scale(1.1);
            box-shadow: 0 12px 32px rgba(52, 87, 203, 0.5);
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <!-- Header Section -->
        <div class="header bg-white rounded-3xl p-8 lg:p-12 mb-8 shadow-xl relative overflow-hidden animate-fade-in-down">
            <div class="flex items-center gap-6 md:gap-8 flex-col md:flex-row text-center md:text-left">
                <div class="header-icon bg-gradient-to-br from-primary to-primary-light text-white w-20 h-20 md:w-24 md:h-24 rounded-2xl flex items-center justify-center text-4xl md:text-5xl shadow-lg">
                    <i class="fas fa-cogs"></i>
                </div>
                <div class="header-text">
                    <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 mb-2 tracking-tight">Processus de Validation</h1>
                    <p class="text-lg text-gray-600 font-normal">Suivi des rapports traités par les membres de la commission</p>
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
                        <h3 class="text-3xl font-bold text-primary mb-1">12</h3>
                        <p class="text-sm font-semibold text-gray-600">Rapports Total</p>
                        <p class="text-xs text-blue-600 font-medium">
                            <i class="fas fa-info-circle mr-1"></i>Global
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="stat-card animate-slide-in-right" style="animation-delay: 0.2s">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-warning/10 text-warning rounded-xl flex items-center justify-center text-2xl">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div>
                        <h3 class="text-3xl font-bold text-warning mb-1">75%</h3>
                        <p class="text-sm font-semibold text-gray-600">En Attente</p>
                        <p class="text-xs text-orange-600 font-medium">
                            <i class="fas fa-hourglass-half mr-1"></i>9 rapports
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="stat-card animate-slide-in-right" style="animation-delay: 0.3s">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-secondary/10 text-secondary rounded-xl flex items-center justify-center text-2xl">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div>
                        <h3 class="text-3xl font-bold text-secondary mb-1">8</h3>
                        <p class="text-sm font-semibold text-gray-600">Validés</p>
                        <p class="text-xs text-green-600 font-medium">
                            <i class="fas fa-arrow-up mr-1"></i>3.2j moy.
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="stat-card animate-slide-in-right" style="animation-delay: 0.4s">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-red-500/10 text-red-600 rounded-xl flex items-center justify-center text-2xl">
                        <i class="fas fa-times-circle"></i>
                    </div>
                    <div>
                        <h3 class="text-3xl font-bold text-red-600 mb-1">3</h3>
                        <p class="text-sm font-semibold text-gray-600">Rejetés</p>
                        <p class="text-xs text-red-600 font-medium">
                            <i class="fas fa-arrow-down mr-1"></i>25% taux
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters Section -->
        <div class="card p-8 mb-8 animate-scale-in">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-primary rounded-full flex items-center justify-center">
                        <i class="fas fa-search text-white"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-800">Recherche & Filtres</h2>
                </div>
                <button onclick="resetFilters()" class="btn btn-primary">
                    <i class="fas fa-refresh"></i>
                    <span>Reset</span>
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="relative">
                    <input type="text" id="searchFilter" placeholder="Rechercher..." onkeyup="filterReports()"
                        class="filter-input">
                    <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                </div>
                <div class="relative">
                    <select id="statusFilter" onchange="filterReports()" class="filter-select">
                        <option value="">Tous les statuts</option>
                        <option value="en_cours">En cours d'évaluation</option>
                        <option value="valide">Validé</option>
                        <option value="rejete">Rejeté</option>
                    </select>
                    <i class="fas fa-flag absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                </div>
                <div class="relative">
                    <select id="memberFilter" onchange="filterReports()" class="filter-select">
                        <option value="">Tous les membres</option>
                        <option value="Dr. Kouassi">Dr. Kouassi</option>
                        <option value="Dr. Koné">Dr. Koné</option>
                        <option value="Pr. Assan">Pr. Assan</option>
                        <option value="Dr. Bamba">Dr. Bamba</option>
                    </select>
                    <i class="fas fa-user-tie absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                </div>
            </div>
        </div>

        <!-- Reports Grid -->
        <div class="flex items-center justify-between mb-8">
            <h3 class="text-3xl font-bold text-primary flex items-center">
                <i class="fas fa-folder-open mr-3 text-secondary"></i>
                Rapports de Validation
            </h3>
            <div class="bg-white px-6 py-3 rounded-xl border border-gray-200 shadow-sm">
                <span id="reportCount" class="font-semibold text-gray-700">12 rapports</span>
            </div>
        </div>

        <div id="reportList" class="space-y-6">
            <?php if (!empty($rapports)): ?>
                <?php foreach ($rapports as $rapport):
                    $statut = strtolower(trim($rapport->statut_rapport ?? ''));
                    $statusClass = $statut === 'valide' ? 'valide' : ($statut === 'rejete' ? 'rejete' : 'en_cours');
                    $statusLabel = $statut === 'valide' ? 'Validé' : ($statut === 'rejete' ? 'Rejeté' : 'En évaluation');
                    $titre = htmlspecialchars($rapport->nom_rapport ?? $rapport->titre ?? 'Titre inconnu');
                    $auteur = htmlspecialchars(($rapport->nom_etu ?? '') . ' ' . ($rapport->prenom_etu ?? ''));
                    $date = isset($rapport->date_rapport) ? date('d/m/Y', strtotime($rapport->date_rapport)) : '';
                ?>
                <div class="report-card p-8"
                     data-status="<?= $statusClass ?>"
                     data-title="<?= strtolower($titre) ?>">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                        <div class="flex items-center mb-6 lg:mb-0 lg:flex-1">
                            <div class="avatar">
                                <?= strtoupper(substr($auteur, 0, 2)) ?>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-2xl font-bold text-gray-800 mb-2 leading-tight">
                                    <?= $titre ?>
                                </h3>
                                <div class="flex flex-wrap gap-4 text-sm text-gray-600">
                                    <span class="flex items-center">
                                        <i class="fas fa-user-graduate mr-2 text-primary"></i>
                                        <strong data-user><?= $auteur ?></strong>
                                    </span>
                                    <span class="flex items-center">
                                        <i class="fas fa-calendar mr-2 text-primary"></i>
                                        <strong><?= $date ?></strong>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="flex flex-col lg:flex-row items-start lg:items-center space-y-4 lg:space-y-0 lg:space-x-6">
                            <div class="status-badge status-<?= $statusClass ?>">
                                <?= $statusLabel ?>
                            </div>
                            <div class="flex flex-wrap gap-3">
                                <button onclick="viewReport(<?= $rapport->id_rapport ?>)" class="btn btn-primary">
                                    <i class="fas fa-eye"></i>
                                    <span>Consulter</span>
                                </button>
                                <?php if ($statut === 'valide'): ?>
                                    <button onclick="makeFinalDecision(<?= $rapport->id_rapport ?>)" class="btn btn-secondary">
                                        <i class="fas fa-certificate"></i>
                                        <span>Verdict</span>
                                    </button>
                                <?php elseif ($statut === 'rejete'): ?>
                                    <button onclick="requestCorrections(<?= $rapport->id_rapport ?>)" class="btn btn-danger">
                                        <i class="fas fa-edit"></i>
                                        <span>Corrections</span>
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="card p-12 text-center">
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-folder-open text-4xl text-gray-400"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-700 mb-2">Aucun rapport disponible</h3>
                    <p class="text-gray-500">Les rapports apparaîtront ici une fois soumis.</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Floating Action Button -->
        <div class="floating-action" onclick="showAddModal()">
            <i class="fas fa-plus text-xl"></i>
        </div>
    </div>

    <script>
        function filterReports() {
            const statusFilter = document.getElementById('statusFilter').value.toLowerCase();
            const memberFilter = document.getElementById('memberFilter').value.toLowerCase();
            const searchFilter = document.getElementById('searchFilter').value.toLowerCase();
            const reportItems = document.querySelectorAll('.report-card');
            let visibleReportsCount = 0;

            reportItems.forEach(item => {
                const itemStatus = item.getAttribute('data-status');
                const itemTitle = item.getAttribute('data-title');
                const studentEl = item.querySelector('[data-user]');

                const itemStudent = studentEl ? studentEl.textContent.toLowerCase() : '';

                const statusMatch = statusFilter === '' || itemStatus === statusFilter;
                const memberMatch = memberFilter === '' || item.textContent.toLowerCase().includes(memberFilter);
                const searchMatch = searchFilter === '' ||
                    itemTitle.includes(searchFilter) ||
                    itemStudent.includes(searchFilter);

                if (statusMatch && memberMatch && searchMatch) {
                    item.style.display = 'block';
                    visibleReportsCount++;
                } else {
                    item.style.display = 'none';
                }
            });

            document.getElementById('reportCount').textContent = `${visibleReportsCount} rapports`;
        }

        function resetFilters() {
            document.getElementById('statusFilter').value = '';
            document.getElementById('memberFilter').value = '';
            document.getElementById('searchFilter').value = '';
            filterReports();
            showNotification('Filtres réinitialisés', 'success');
        }

        function viewReport(id) {
            showNotification(`Consultation du rapport ID: ${id}`, 'info');
        }

        function makeFinalDecision(id) {
            showNotification(`Décision finale pour le rapport ID: ${id}`, 'info');
        }

        function requestCorrections(id) {
            showNotification(`Demande de corrections pour le rapport ID: ${id}`, 'warning');
        }

        function showAddModal() {
            showNotification('Ouverture du formulaire d\'ajout', 'info');
        }

        function showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.className = `fixed top-6 right-6 z-50 p-4 rounded-lg shadow-lg max-w-sm transform transition-all duration-300 translate-x-full`;

            const bgColor = type === 'success' ? 'bg-gradient-to-r from-secondary to-secondary-light' :
                type === 'error' ? 'bg-gradient-to-r from-red-500 to-red-600' :
                type === 'warning' ? 'bg-gradient-to-r from-warning to-yellow-600' :
                'bg-gradient-to-r from-primary to-primary-light';

            notification.className += ` ${bgColor} text-white`;
            notification.innerHTML = `
                <div class="flex items-center">
                    <span class="mr-2">${type === 'success' ? '✅' : type === 'error' ? '❌' : type === 'warning' ? '⚠️' : 'ℹ️'}</span>
                    <span class="font-semibold">${message}</span>
                    <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;

            document.body.appendChild(notification);

            setTimeout(() => notification.classList.remove('translate-x-full'), 100);

            setTimeout(() => {
                notification.classList.add('translate-x-full');
                setTimeout(() => notification.remove(), 300);
            }, 4000);
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            filterReports();
            
            // Animation des cartes
            const cards = document.querySelectorAll('.stat-card, .card, .report-card');
            cards.forEach((card, index) => {
                card.style.animationDelay = `${index * 0.05}s`;
                card.classList.add('animate-scale-in');
            });
        });
    </script>
</body>

</html>
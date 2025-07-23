<?php
// Extraire les variables globales si elles existent
$reclamationsEnCours = $GLOBALS['reclamationsEnCours'] ?? [];
$reclamationsTraitees = $GLOBALS['reclamationsTraitees'] ?? [];
$messageErreur = $GLOBALS['messageErreur'] ?? '';
$messageSuccess = $GLOBALS['messageSuccess'] ?? '';

// Déterminer l'onglet actif
$activeTab = $_GET['tab'] ?? 'en_cours';
if (!in_array($activeTab, ['en_cours', 'historique'])) {
    $activeTab = 'en_cours';
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Réclamations Scolarité | Univalid</title>
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

        .tab-button {
            padding: 12px 24px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            border: 2px solid #e2e8f0;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .tab-button.active {
            background: linear-gradient(135deg, #3457cb 0%, #24407a 100%);
            color: white;
            border-color: #3457cb;
            box-shadow: 0 4px 12px rgba(52, 87, 203, 0.3);
            transform: translateY(-2px);
        }

        .tab-button:not(.active) {
            background: white;
            color: #64748b;
        }

        .tab-button:not(.active):hover {
            background: rgba(52, 87, 203, 0.05);
            color: #3457cb;
            border-color: rgba(52, 87, 203, 0.2);
            transform: translateY(-1px);
        }

        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(15, 23, 42, 0.7);
            backdrop-filter: blur(8px);
            z-index: 999;
            justify-content: center;
            align-items: center;
            animation: fadeInDown 0.3s ease-out forwards;
        }

        .modal-container {
            background: white;
            backdrop-filter: blur(20px);
            padding: 2.5rem;
            border-radius: 24px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            width: 95%;
            max-width: 700px;
            animation: scaleIn 0.5s ease-out forwards;
            position: relative;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .modal-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #3457cb 0%, #36865a 50%, #59bf3d 100%);
            border-radius: 24px 24px 0 0;
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

        .btn-gray {
            background: #f1f5f9;
            color: #64748b;
            border: 2px solid #e2e8f0;
        }

        .btn-gray:hover {
            background: white;
            border-color: rgba(52, 87, 203, 0.2);
            transform: translateY(-2px);
        }

        .form-input {
            width: 100%;
            padding: 12px 16px;
            background: #f8fafc;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .form-input:focus {
            outline: none;
            border-color: #3457cb;
            background: white;
            box-shadow: 0 0 0 3px rgba(52, 87, 203, 0.1);
        }

        .table-container {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 16px rgba(15, 23, 42, 0.08);
            border: 1px solid #e2e8f0;
        }

        .table-header {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            padding: 20px;
            border-bottom: 2px solid #e2e8f0;
        }

        .table-row {
            transition: all 0.2s ease;
            border-bottom: 1px solid #f1f5f9;
        }

        .table-row:hover {
            background: rgba(52, 87, 203, 0.02);
            transform: scale(1.01);
        }

        .search-container {
            position: relative;
        }

        .search-input {
            padding-left: 48px;
        }

        .search-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #64748b;
        }

        .notification {
            padding: 16px 20px;
            border-radius: 16px;
            margin-bottom: 24px;
            border: 1px solid;
            box-shadow: 0 4px 16px rgba(15, 23, 42, 0.1);
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

        .action-buttons {
            display: flex;
            gap: 8px;
        }

        .action-btn {
            padding: 8px;
            border-radius: 8px;
            transition: all 0.2s ease;
            cursor: pointer;
            border: none;
        }

        .action-btn:hover {
            transform: scale(1.1);
        }

        .action-btn.edit {
            background: rgba(52, 87, 203, 0.1);
            color: #3457cb;
        }

        .action-btn.edit:hover {
            background: rgba(52, 87, 203, 0.2);
        }

        .action-btn.delete {
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
        }

        .action-btn.delete:hover {
            background: rgba(239, 68, 68, 0.2);
        }

        .action-btn.view {
            background: rgba(34, 197, 94, 0.1);
            color: #22c55e;
        }

        .action-btn.view:hover {
            background: rgba(34, 197, 94, 0.2);
        }

        .status-badge {
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-en-attente {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            color: #92400e;
            border: 1px solid #f59e0b;
        }

        .status-en-cours {
            background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
            color: #1e40af;
            border: 1px solid #3b82f6;
        }

        .status-resolue {
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
            color: #065f46;
            border: 1px solid #10b981;
        }

        .status-rejetee {
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            color: #991b1b;
            border: 1px solid #ef4444;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <!-- Header Section -->
        <div class="header bg-white rounded-3xl p-8 lg:p-12 mb-8 shadow-xl relative overflow-hidden animate-fade-in-down">
            <div class="flex items-center gap-6 md:gap-8 flex-col md:flex-row text-center md:text-left">
                <div class="header-icon bg-gradient-to-br from-secondary to-secondary-light text-white w-20 h-20 md:w-24 md:h-24 rounded-2xl flex items-center justify-center text-4xl md:text-5xl shadow-lg">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="header-text">
                    <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 mb-2 tracking-tight">Gestion des Réclamations</h1>
                    <p class="text-lg text-gray-600 font-normal">Service de la Scolarité - Suivi et traitement des réclamations</p>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="stat-card animate-slide-in-right" style="animation-delay: 0.1s">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-warning/10 text-warning rounded-xl flex items-center justify-center text-2xl">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div>
                        <h3 class="text-3xl font-bold text-warning mb-1" id="countEnAttente">0</h3>
                        <p class="text-sm font-semibold text-gray-600">En Attente</p>
                        <p class="text-xs text-orange-600 font-medium">
                            <i class="fas fa-hourglass-half mr-1"></i>À traiter
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
                        <h3 class="text-3xl font-bold text-secondary mb-1" id="countResolue">0</h3>
                        <p class="text-sm font-semibold text-gray-600">Résolues</p>
                        <p class="text-xs text-green-600 font-medium">
                            <i class="fas fa-check mr-1"></i>Traitées
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="stat-card animate-slide-in-right" style="animation-delay: 0.3s">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-danger/10 text-danger rounded-xl flex items-center justify-center text-2xl">
                        <i class="fas fa-times-circle"></i>
                    </div>
                    <div>
                        <h3 class="text-3xl font-bold text-danger mb-1" id="countRejete">0</h3>
                        <p class="text-sm font-semibold text-gray-600">Rejetées</p>
                        <p class="text-xs text-red-600 font-medium">
                            <i class="fas fa-ban mr-1"></i>Refusées
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation tabs -->
        <div class="mb-8 animate-fade-in-down">
            <div class="flex justify-center">
                <nav class="flex space-x-4 p-2 bg-white/70 backdrop-blur-xl rounded-2xl border border-white/20 shadow-lg">
                    <a href="?page=gestion_reclamations_scolarite&tab=en_cours"
                        class="tab-button <?= ($activeTab === 'en_cours') ? 'active' : '' ?>">
                        <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <span>Réclamations à traiter</span>
                    </a>
                    <a href="?page=gestion_reclamations_scolarite&tab=historique"
                        class="tab-button <?= ($activeTab === 'historique') ? 'active' : '' ?>">
                        <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center">
                            <i class="fas fa-history"></i>
                        </div>
                        <span>Historique</span>
                    </a>
                </nav>
            </div>
        </div>

        <!-- Messages -->
        <?php if ($messageSuccess): ?>
            <div class="notification success animate-fade-in-down">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-3 text-lg"></i>
                    <span class="font-semibold"><?= htmlspecialchars($messageSuccess) ?></span>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($messageErreur): ?>
            <div class="notification error animate-fade-in-down">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle mr-3 text-lg"></i>
                    <span class="font-semibold"><?= htmlspecialchars($messageErreur) ?></span>
                </div>
            </div>
        <?php endif; ?>

        <!-- Contenu des onglets -->
        <div class="animate-scale-in">
            <!-- Onglet Réclamations à traiter -->
            <div id="tab_en_cours" class="<?= $activeTab === 'en_cours' ? '' : 'hidden' ?>">
                <!-- Header section avec barre de recherche -->
                <div class="card p-8 mb-8">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-4 lg:space-y-0">
                        <div>
                            <h2 class="text-3xl font-bold text-warning mb-2">Réclamations à traiter</h2>
                            <p class="text-gray-600">Gérez les réclamations en attente de traitement</p>
                        </div>
                        <div class="search-container flex-1 max-w-md">
                            <input type="text" id="searchInputEnCours" placeholder="Rechercher une réclamation..."
                                class="form-input search-input">
                            <i class="fas fa-search search-icon"></i>
                        </div>
                    </div>
                </div>

                <!-- Barre d'actions -->
                <div class="card p-6 mb-8">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-4 lg:space-y-0">
                        <div class="flex items-center gap-4">
                            <span class="bg-warning/10 text-warning px-4 py-2 rounded-full text-sm font-semibold" id="countEnCoursBadge">
                                0 réclamations à traiter
                            </span>
                        </div>
                        <div class="flex flex-wrap gap-3">
                            <button onclick="printTable('tableReclamationsEnCours', 'Réclamations à traiter')" class="btn btn-primary">
                                <i class="fas fa-print"></i>
                                <span>Imprimer</span>
                            </button>
                            <button onclick="exportTableToCSV('tableReclamationsEnCours', 'reclamations_a_traiter')" class="btn btn-warning">
                                <i class="fas fa-download"></i>
                                <span>Exporter</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Table Réclamations à traiter -->
                <div class="table-container">
                    <div class="table-header">
                        <h4 class="text-xl font-bold text-warning flex items-center">
                            <i class="fas fa-exclamation-triangle mr-3"></i>
                            Réclamations en attente de traitement
                        </h4>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full" id="tableReclamationsEnCours">
                            <thead>
                                <tr class="border-b border-gray-200">
                                    <th class="px-6 py-4 text-left text-sm font-bold text-gray-600 uppercase tracking-wider">#</th>
                                    <th class="px-6 py-4 text-left text-sm font-bold text-gray-600 uppercase tracking-wider">👤 Étudiant</th>
                                    <th class="px-6 py-4 text-left text-sm font-bold text-gray-600 uppercase tracking-wider">📝 Objet</th>
                                    <th class="px-6 py-4 text-left text-sm font-bold text-gray-600 uppercase tracking-wider">💬 Message</th>
                                    <th class="px-6 py-4 text-left text-sm font-bold text-gray-600 uppercase tracking-wider">📅 Date</th>
                                    <th class="px-6 py-4 text-left text-sm font-bold text-gray-600 uppercase tracking-wider">🏷️ Statut</th>
                                    <th class="px-6 py-4 text-center text-sm font-bold text-gray-600 uppercase tracking-wider">🔧 Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($reclamationsEnCours)): ?>
                                    <tr>
                                        <td colspan="7" class="px-6 py-12 text-center">
                                            <div class="flex flex-col items-center">
                                                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                                    <i class="fas fa-inbox text-3xl text-gray-400"></i>
                                                </div>
                                                <p class="text-lg font-semibold text-gray-500 mb-2">Aucune réclamation à traiter</p>
                                                <p class="text-sm text-gray-400">Toutes les réclamations ont été traitées</p>
                                            </div>
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($reclamationsEnCours as $i => $rec): ?>
                                        <tr class="table-row">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?= $i+1 ?></td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="w-10 h-10 bg-warning/10 text-warning rounded-full flex items-center justify-center text-sm font-bold mr-3">
                                                        <?= strtoupper(substr($rec->nom_etu, 0, 1) . substr($rec->prenom_etu, 0, 1)) ?>
                                                    </div>
                                                    <span class="font-semibold text-gray-900"><?= htmlspecialchars($rec->nom_etu . ' ' . $rec->prenom_etu) ?></span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="text-sm text-gray-900 max-w-xs truncate" title="<?= htmlspecialchars($rec->titre_reclamation ?? '') ?>">
                                                    <?= htmlspecialchars($rec->titre_reclamation ?? '') ?>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="text-sm text-gray-600 max-w-xs truncate" title="<?= htmlspecialchars($rec->description_reclamation ?? '') ?>">
                                                    <?= htmlspecialchars($rec->description_reclamation ?? '') ?>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <?= htmlspecialchars($rec->date_creation ?? '') ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="status-badge <?php 
                                                    if($rec->statut_reclamation === 'en attente') echo 'status-en-attente';
                                                    elseif($rec->statut_reclamation === 'en cours') echo 'status-en-cours';
                                                ?>">
                                                    <?= htmlspecialchars($rec->statut_reclamation) ?>
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                <div class="action-buttons">
                                                    <button type="button" onclick='showReclamationDetails(<?= json_encode($rec, JSON_HEX_APOS | JSON_HEX_QUOT) ?>)'
                                                        class="action-btn view">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <form method="post" action="?page=gestion_reclamations_scolarite&action=changer_statut&id=<?= $rec->id_reclamation ?>" class="inline-block">
                                                        <select name="nouveau_statut" onchange="this.form.submit()"
                                                            class="text-xs border border-gray-300 rounded-md px-2 py-1 focus:ring-2 focus:ring-warning focus:border-warning">
                                                            <option value="En attente" <?= strtolower($rec->statut_reclamation) === 'en attente' ? 'selected' : '' ?>>En attente</option>
                                                            <option value="Résolue" <?= strtolower($rec->statut_reclamation) === 'résolue' ? 'selected' : '' ?>>Résolue</option>
                                                            <option value="Rejetée" <?= strtolower($rec->statut_reclamation) === 'rejetée' ? 'selected' : '' ?>>Rejetée</option>
                                                        </select>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Onglet Historique -->
            <div id="tab_historique" class="<?= $activeTab === 'historique' ? '' : 'hidden' ?>">
                <!-- Header section avec barre de recherche -->
                <div class="card p-8 mb-8">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-4 lg:space-y-0">
                        <div>
                            <h2 class="text-3xl font-bold text-gray-600 mb-2">Historique des réclamations</h2>
                            <p class="text-gray-600">Consultez l'historique des réclamations traitées</p>
                        </div>
                        <div class="search-container flex-1 max-w-md">
                            <input type="text" id="searchInputHistorique" placeholder="Rechercher dans l'historique..."
                                class="form-input search-input">
                            <i class="fas fa-search search-icon"></i>
                        </div>
                    </div>
                </div>

                <!-- Barre d'actions -->
                <div class="card p-6 mb-8">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-4 lg:space-y-0">
                        <div class="flex items-center gap-4">
                            <span class="bg-gray-100 text-gray-800 px-4 py-2 rounded-full text-sm font-semibold" id="countTraiteesBadge">
                                0 réclamations traitées
                            </span>
                        </div>
                        <div class="flex flex-wrap gap-3">
                            <button onclick="printTable('tableReclamationsTraitees', 'Historique des réclamations')" class="btn btn-primary">
                                <i class="fas fa-print"></i>
                                <span>Imprimer</span>
                            </button>
                            <button onclick="exportTableToCSV('tableReclamationsTraitees', 'historique_reclamations')" class="btn btn-warning">
                                <i class="fas fa-download"></i>
                                <span>Exporter</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Table Historique -->
                <div class="table-container">
                    <div class="table-header">
                        <h4 class="text-xl font-bold text-gray-600 flex items-center">
                            <i class="fas fa-history mr-3"></i>
                            Réclamations traitées
                        </h4>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full" id="tableReclamationsTraitees">
                            <thead>
                                <tr class="border-b border-gray-200">
                                    <th class="px-6 py-4 text-left text-sm font-bold text-gray-600 uppercase tracking-wider">#</th>
                                    <th class="px-6 py-4 text-left text-sm font-bold text-gray-600 uppercase tracking-wider">👤 Étudiant</th>
                                    <th class="px-6 py-4 text-left text-sm font-bold text-gray-600 uppercase tracking-wider">📝 Objet</th>
                                    <th class="px-6 py-4 text-left text-sm font-bold text-gray-600 uppercase tracking-wider">💬 Message</th>
                                    <th class="px-6 py-4 text-left text-sm font-bold text-gray-600 uppercase tracking-wider">📅 Date</th>
                                    <th class="px-6 py-4 text-left text-sm font-bold text-gray-600 uppercase tracking-wider">🏷️ Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($reclamationsTraitees)): ?>
                                    <tr>
                                        <td colspan="6" class="px-6 py-12 text-center">
                                            <div class="flex flex-col items-center">
                                                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                                    <i class="fas fa-archive text-3xl text-gray-400"></i>
                                                </div>
                                                <p class="text-lg font-semibold text-gray-500 mb-2">Aucun historique de réclamation</p>
                                                <p class="text-sm text-gray-400">Les réclamations traitées apparaîtront ici</p>
                                            </div>
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($reclamationsTraitees as $i => $rec): ?>
                                        <tr class="table-row">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?= $i+1 ?></td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="w-10 h-10 bg-gray-100 text-gray-600 rounded-full flex items-center justify-center text-sm font-bold mr-3">
                                                        <?= strtoupper(substr($rec->nom_etu, 0, 1) . substr($rec->prenom_etu, 0, 1)) ?>
                                                    </div>
                                                    <span class="font-semibold text-gray-900"><?= htmlspecialchars($rec->nom_etu . ' ' . $rec->prenom_etu) ?></span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="text-sm text-gray-900 max-w-xs truncate" title="<?= htmlspecialchars($rec->titre_reclamation ?? '') ?>">
                                                    <?= htmlspecialchars($rec->titre_reclamation ?? '') ?>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="text-sm text-gray-600 max-w-xs truncate" title="<?= htmlspecialchars($rec->description_reclamation ?? '') ?>">
                                                    <?= htmlspecialchars($rec->description_reclamation ?? '') ?>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <?= htmlspecialchars($rec->date_creation ?? '') ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="status-badge <?php 
                                                    if(strtolower($rec->statut_reclamation) === 'résolue' || strtolower($rec->statut_reclamation) === 'traitée') echo 'status-resolue';
                                                    elseif(strtolower($rec->statut_reclamation) === 'rejeté' || strtolower($rec->statut_reclamation) === 'rejetée') echo 'status-rejetee';
                                                    else echo 'status-en-attente'; 
                                                ?>">
                                                    <?= htmlspecialchars($rec->statut_reclamation) ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Pagination -->
                <div class="flex flex-col md:flex-row md:items-center md:justify-between mt-6 gap-4">
                    <div class="text-gray-600 text-sm">
                        <span id="paginationInfo">Affichage des réclamations traitées</span>
                    </div>
                    <div class="flex justify-center" id="pagination">
                        <!-- La pagination sera générée par JavaScript -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal détails réclamation -->
    <div class="modal-overlay" id="detailsModal">
        <div class="modal-container">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h3 class="text-2xl font-bold text-primary">Détails de la réclamation</h3>
                    <p class="text-gray-600 mt-1">Informations complètes</p>
                </div>
                <button onclick="closeDetailsModal()" class="w-12 h-12 bg-gray-100 hover:bg-gray-200 rounded-2xl flex items-center justify-center text-gray-400 hover:text-gray-600 transition-all duration-300">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <div id="detailsContent" class="space-y-6">
                <!-- Le contenu sera généré par JavaScript -->
            </div>

            <div class="flex justify-end pt-8">
                <button onclick="closeDetailsModal()" class="btn btn-gray">
                    Fermer
                </button>
            </div>
        </div>
    </div>

    <script>
        // Variables globales
        const reclamationsEnCoursData = <?= json_encode($reclamationsEnCours) ?>;
        const reclamationsTraiteesData = <?= json_encode($reclamationsTraitees) ?>;

        // Mise à jour des compteurs
        function updateCounters() {
            // Compter selon les statuts
            let enAttenteCount = 0;
            let resolueCount = 0;
            let rejeteCount = 0;

            // Compter dans les réclamations en cours
            reclamationsEnCoursData.forEach(rec => {
                if (rec.statut_reclamation.toLowerCase() === 'en attente') {
                    enAttenteCount++;
                }
            });

            // Compter dans les réclamations traitées
            reclamationsTraiteesData.forEach(rec => {
                const status = rec.statut_reclamation.toLowerCase();
                if (status === 'résolue' || status === 'traitée') {
                    resolueCount++;
                } else if (status === 'rejeté' || status === 'rejetée') {
                    rejeteCount++;
                }
            });

            // Mettre à jour les affichages
            document.getElementById('countEnAttente').textContent = enAttenteCount;
            document.getElementById('countResolue').textContent = resolueCount;
            document.getElementById('countRejete').textContent = rejeteCount;

            // Mettre à jour les badges
            const countEnCoursBadge = document.getElementById('countEnCoursBadge');
            if (countEnCoursBadge) {
                countEnCoursBadge.textContent = `${enAttenteCount} réclamation${enAttenteCount > 1 ? 's' : ''} à traiter`;
            }

            const countTraiteesBadge = document.getElementById('countTraiteesBadge');
            if (countTraiteesBadge) {
                const total = resolueCount + rejeteCount;
                countTraiteesBadge.textContent = `${total} réclamation${total > 1 ? 's' : ''} traitée${total > 1 ? 's' : ''}`;
            }
        }

        // Fonction de recherche
        function filterTable(tableId, searchTerm) {
            const table = document.getElementById(tableId);
            if (!table) return;
            
            const rows = table.querySelectorAll('tbody tr');
            const term = searchTerm.toLowerCase();
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(term) ? '' : 'none';
            });
        }

        // Configuration de la recherche
        function setupSearch() {
            const searchEnCours = document.getElementById('searchInputEnCours');
            const searchHistorique = document.getElementById('searchInputHistorique');
            
            if (searchEnCours) {
                searchEnCours.addEventListener('keyup', function() {
                    filterTable('tableReclamationsEnCours', this.value);
                });
            }
            
            if (searchHistorique) {
                searchHistorique.addEventListener('keyup', function() {
                    filterTable('tableReclamationsTraitees', this.value);
                });
            }
        }

        // Fonction pour exporter une table spécifique
        function exportTableToCSV(tableId, filename) {
            const table = document.getElementById(tableId);
            if (!table) {
                showNotification('Table non trouvée', 'error');
                return;
            }

            let csv = [];

            // En-têtes du tableau
            const headers = Array.from(table.querySelectorAll('thead th'));
            const headerRow = headers.map(th => th.textContent.trim().replace(/\r?\n|\r/g, ' ')).join(';');
            csv.push(headerRow);

            // Données visibles uniquement
            const rows = table.querySelectorAll('tbody tr');
            rows.forEach(row => {
                if (row.style.display !== 'none') {
                    const cells = Array.from(row.querySelectorAll('td'));
                    const rowData = cells.map(td => {
                        let text = td.textContent.trim().replace(/\s+/g, ' ');
                        text = text.replace(/\r?\n|\r/g, ' ');
                        text = text.replace(/"/g, '""');
                        return '"' + text + '"';
                    });
                    csv.push(rowData.join(';'));
                }
            });

            // Télécharger
            const csvContent = csv.join('\n');
            const blob = new Blob(['\ufeff' + csvContent], {
                type: 'text/csv;charset=utf-8;'
            });
            const link = document.createElement('a');
            const url = URL.createObjectURL(blob);
            link.setAttribute('href', url);
            link.setAttribute('download', `${filename}_${new Date().toISOString().split('T')[0]}.csv`);
            link.style.visibility = 'hidden';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);

            showNotification(`Export ${filename} terminé avec succès`, 'success');
        }

        // Fonction pour imprimer une table spécifique
        function printTable(tableId, title) {
            const table = document.getElementById(tableId);
            if (!table) {
                showNotification('Table non trouvée', 'error');
                return;
            }

            // Créer une nouvelle fenêtre pour l'impression
            const printWindow = window.open('', '_blank', 'width=800,height=600');

            let printContent = `
                <!DOCTYPE html>
                <html>
                <head>
                    <title>${title}</title>
                    <style>
                        body { font-family: Arial, sans-serif; margin: 20px; font-size: 12px; }
                        .print-header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
                        .print-header h1 { margin: 0; color: #333; font-size: 18px; }
                        .print-header p { margin: 5px 0 0 0; color: #666; font-size: 12px; }
                        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; font-size: 11px; }
                        th { background-color: #f8f9fa; font-weight: bold; }
                        .print-footer { margin-top: 20px; text-align: center; font-size: 10px; color: #666; border-top: 1px solid #ddd; padding-top: 10px; }
                    </style>
                </head>
                <body>
                    <div class="print-header">
                        <h1>${title}</h1>
                        <p>Service de la Scolarité - Université</p>
                        <p>Date d'impression: ${new Date().toLocaleDateString('fr-FR')}</p>
                    </div>
                    ${table.outerHTML}
                    <div class="print-footer">
                        <p>Document généré automatiquement par le système de gestion des réclamations</p>
                    </div>
                </body>
                </html>
            `;

            printWindow.document.write(printContent);
            printWindow.document.close();

            printWindow.onload = function() {
                printWindow.print();
                printWindow.close();
            };

            showNotification(`Impression de ${title} lancée`, 'success');
        }

        // Fonction pour afficher les détails d'une réclamation
        function showReclamationDetails(rec) {
            const detailsContent = document.getElementById('detailsContent');
            let html = '';

            html += `
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h4 class="font-semibold text-gray-900 mb-2">Informations étudiant</h4>
                        <p class="text-gray-700"><span class="font-medium">Nom complet :</span> ${rec.nom_etu} ${rec.prenom_etu}</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h4 class="font-semibold text-gray-900 mb-2">Statut</h4>
                        <span class="status-badge ${
                            rec.statut_reclamation === 'en attente' ? 'status-en-attente' : 
                            rec.statut_reclamation === 'résolue' || rec.statut_reclamation === 'traitée' ? 'status-resolue' :
                            rec.statut_reclamation === 'rejeté' || rec.statut_reclamation === 'rejetée' ? 'status-rejetee' : 'status-en-attente'
                        }">
                            ${rec.statut_reclamation}
                        </span>
                    </div>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h4 class="font-semibold text-gray-900 mb-2">Objet de la réclamation</h4>
                    <p class="text-gray-700">${rec.titre_reclamation || 'Non spécifié'}</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h4 class="font-semibold text-gray-900 mb-2">Description détaillée</h4>
                    <p class="text-gray-700 whitespace-pre-wrap">${rec.description_reclamation || 'Aucune description fournie'}</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h4 class="font-semibold text-gray-900 mb-2">Date de création</h4>
                    <p class="text-gray-700">${rec.date_creation || 'Date non disponible'}</p>
                </div>
            `;

            detailsContent.innerHTML = html;
            document.getElementById('detailsModal').style.display = 'flex';
        }

        function closeDetailsModal() {
            document.getElementById('detailsModal').style.display = 'none';
        }

        // Notifications
        function showNotification(message, type = 'success') {
            const notification = document.createElement('div');
            notification.className = `fixed top-6 right-6 z-50 p-4 rounded-lg shadow-lg max-w-sm transform transition-all duration-300 ${
                type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
            }`;
            notification.innerHTML = `
                <div class="flex items-center">
                    <i class="fas fa-${type === 'success' ? 'check' : 'times'}-circle mr-2"></i>
                    <span>${message}</span>
                </div>
            `;
            
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.style.transform = 'translateX(100%)';
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        }

        // Pagination
        function initializePagination() {
            const table = document.getElementById('tableReclamationsTraitees');
            const tbody = table ? table.querySelector('tbody') : null;
            const pagination = document.getElementById('pagination');
            const paginationInfo = document.getElementById('paginationInfo');

            if (!tbody || !pagination) return;

            const rows = Array.from(tbody.querySelectorAll('tr'));
            const rowsPerPage = 10;
            let currentPage = 1;

            function showPage(page) {
                currentPage = page;
                const start = (page - 1) * rowsPerPage;
                const end = start + rowsPerPage;

                rows.forEach((row, i) => {
                    row.style.display = (i >= start && i < end) ? '' : 'none';
                });

                renderPagination();
                updatePaginationInfo();
            }

            function updatePaginationInfo() {
                const totalRows = rows.length;
                const start = (currentPage - 1) * rowsPerPage + 1;
                const end = Math.min(currentPage * rowsPerPage, totalRows);

                if (totalRows > 0) {
                    paginationInfo.textContent = `Affichage de ${start} à ${end} sur ${totalRows} réclamations traitées`;
                } else {
                    paginationInfo.textContent = 'Aucune réclamation traitée à afficher';
                }
            }

            function renderPagination() {
                const pageCount = Math.ceil(rows.length / rowsPerPage);
                pagination.innerHTML = '';

                if (pageCount <= 1) return;

                // Bouton précédent
                const prevBtn = document.createElement('button');
                prevBtn.innerHTML = '<i class="fa fa-chevron-left"></i>';
                prevBtn.className = `px-3 py-2 border border-gray-300 bg-white text-sm font-medium ${currentPage === 1 ? 'text-gray-300 cursor-not-allowed' : 'text-gray-700 hover:bg-gray-50'} rounded-l-md transition duration-200`;
                prevBtn.disabled = currentPage === 1;
                prevBtn.onclick = () => showPage(currentPage - 1);
                pagination.appendChild(prevBtn);

                // Pages
                for (let i = 1; i <= pageCount; i++) {
                    const btn = document.createElement('button');
                    btn.textContent = i;
                    btn.className = `px-3 py-2 border border-gray-300 text-sm font-medium ${i === currentPage ? 'bg-secondary text-white font-bold' : 'bg-white text-gray-700 hover:bg-gray-50'} transition duration-200`;
                    btn.onclick = () => showPage(i);
                    pagination.appendChild(btn);
                }

                // Bouton suivant
                const nextBtn = document.createElement('button');
                nextBtn.innerHTML = '<i class="fa fa-chevron-right"></i>';
                nextBtn.className = `px-3 py-2 border border-gray-300 bg-white text-sm font-medium ${currentPage === pageCount ? 'text-gray-300 cursor-not-allowed' : 'text-gray-700 hover:bg-gray-50'} rounded-r-md transition duration-200`;
                nextBtn.disabled = currentPage === pageCount;
                nextBtn.onclick = () => showPage(currentPage + 1);
                pagination.appendChild(nextBtn);
            }

            showPage(1);
        }

        // Fermeture des modales en cliquant à l'extérieur
        window.addEventListener('click', function(e) {
            const modal = document.getElementById('detailsModal');
            if (modal && e.target === modal) {
                closeDetailsModal();
            }
        });

        // Initialisation
        document.addEventListener('DOMContentLoaded', function() {
            updateCounters();
            setupSearch();
            initializePagination();
            
            // Animation des cartes
            const cards = document.querySelectorAll('.stat-card, .card');
            cards.forEach((card, index) => {
                card.style.animationDelay = `${index * 0.1}s`;
            });
        });
    </script>
</body>

</html>
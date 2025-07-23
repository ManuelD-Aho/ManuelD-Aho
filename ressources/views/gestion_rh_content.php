<?php
// Déterminer l'onglet actif (par défaut 'pers_admin')
$activeTab = $_GET['tab'] ?? 'pers_admin';
if (!in_array($activeTab, ['pers_admin', 'enseignant'])) { // Valider la valeur de l'onglet
    $activeTab = 'pers_admin';
}

// Récupération des messages depuis le contrôleur
$messageErreur = $GLOBALS['messageErreur'] ?? '';
$messageSuccess = $GLOBALS['messageSuccess'] ?? '';

// Récupération des données depuis le contrôleur
$personnel_admin = $GLOBALS['listePersAdmin'] ?? [];
$enseignants = $GLOBALS['listeEnseignants'] ?? [];
$listeGrades = $GLOBALS['listeGrades'] ?? [];
$listeFonctions = $GLOBALS['listeFonctions'] ?? [];
$listeSpecialites = $GLOBALS['listeSpecialites'] ?? [];

// Récupération des données pour édition
$pers_admin_a_modifier = $GLOBALS['pers_admin_a_modifier'] ?? null;
$enseignant_a_modifier = $GLOBALS['enseignant_a_modifier'] ?? null;

// Gestion des actions CRUD
$action = $_GET['action'] ?? '';
$id = $_GET['id'] ?? null;

// Structure pour le formulaire d'édition
$admin_edit = $pers_admin_a_modifier ?? null;

// Structure pour le formulaire d'édition enseignant
$enseignant_edit = $enseignant_a_modifier ?? null;
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion RH | Univalid</title>
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

        .checkbox-custom {
            width: 20px;
            height: 20px;
            border-radius: 6px;
            border: 2px solid #e2e8f0;
            transition: all 0.3s ease;
        }

        .checkbox-custom:checked {
            background: #3457cb;
            border-color: #3457cb;
        }

        .header-gradient {
            background: linear-gradient(135deg, #24407a 0%, #3457cb 100%);
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
                    <i class="fas fa-users-cog"></i>
                </div>
                <div class="header-text">
                    <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 mb-2 tracking-tight">Gestion des Ressources Humaines</h1>
                    <p class="text-lg text-gray-600 font-normal">Administration du personnel et des enseignants</p>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="stat-card animate-slide-in-right" style="animation-delay: 0.1s">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-primary/10 text-primary rounded-xl flex items-center justify-center text-2xl">
                        <i class="fas fa-users-cog"></i>
                    </div>
                    <div>
                        <h3 class="text-3xl font-bold text-primary mb-1"><?php echo count($personnel_admin); ?></h3>
                        <p class="text-sm font-semibold text-gray-600">Personnel Admin</p>
                        <p class="text-xs text-green-600 font-medium">
                            <i class="fas fa-arrow-up mr-1"></i>Actif
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="stat-card animate-slide-in-right" style="animation-delay: 0.2s">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-secondary/10 text-secondary rounded-xl flex items-center justify-center text-2xl">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                    <div>
                        <h3 class="text-3xl font-bold text-secondary mb-1"><?php echo count($enseignants); ?></h3>
                        <p class="text-sm font-semibold text-gray-600">Enseignants</p>
                        <p class="text-xs text-green-600 font-medium">
                            <i class="fas fa-arrow-up mr-1"></i>Actif
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="stat-card animate-slide-in-right" style="animation-delay: 0.3s">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-warning/10 text-warning rounded-xl flex items-center justify-center text-2xl">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <div>
                        <h3 class="text-3xl font-bold text-warning mb-1"><?php echo count($listeGrades); ?></h3>
                        <p class="text-sm font-semibold text-gray-600">Grades</p>
                        <p class="text-xs text-blue-600 font-medium">
                            <i class="fas fa-info-circle mr-1"></i>Disponibles
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="stat-card animate-slide-in-right" style="animation-delay: 0.4s">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-purple-500/10 text-purple-600 rounded-xl flex items-center justify-center text-2xl">
                        <i class="fas fa-briefcase"></i>
                    </div>
                    <div>
                        <h3 class="text-3xl font-bold text-purple-600 mb-1"><?php echo count($listeFonctions); ?></h3>
                        <p class="text-sm font-semibold text-gray-600">Fonctions</p>
                        <p class="text-xs text-blue-600 font-medium">
                            <i class="fas fa-info-circle mr-1"></i>Disponibles
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation tabs -->
        <div class="mb-8 animate-fade-in-down">
            <div class="flex justify-center">
                <nav class="flex space-x-4 p-2 bg-white/70 backdrop-blur-xl rounded-2xl border border-white/20 shadow-lg">
                    <a href="?page=gestion_rh&tab=pers_admin"
                        class="tab-button <?= ($activeTab === 'pers_admin') ? 'active' : '' ?>">
                        <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center">
                            <i class="fas fa-users-cog"></i>
                        </div>
                        <span>Personnel Administratif</span>
                    </a>
                    <a href="?page=gestion_rh&tab=enseignant"
                        class="tab-button <?= ($activeTab === 'enseignant') ? 'active' : '' ?>">
                        <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center">
                            <i class="fas fa-chalkboard-teacher"></i>
                        </div>
                        <span>Enseignants</span>
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
            <!-- Onglet Personnel administratif -->
            <div id="tab_pers_admin" class="<?= $activeTab === 'pers_admin' ? '' : 'hidden' ?>">
                <!-- Header section avec bouton -->
                <div class="card p-8 mb-8">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
                        <div>
                            <h2 class="text-3xl font-bold text-primary mb-2">Personnel Administratif</h2>
                            <p class="text-gray-600">Gérez efficacement votre équipe administrative</p>
                        </div>
                        <button onclick="showAddModal('pers_admin')" class="btn btn-primary">
                            <i class="fas fa-plus"></i>
                            <span>Nouveau Personnel</span>
                        </button>
                    </div>
                </div>

                <!-- Barre d'actions -->
                <div class="card p-6 mb-8">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-4 lg:space-y-0">
                        <div class="search-container flex-1 max-w-md">
                            <input type="text" id="searchInputAdmin" placeholder="Rechercher un personnel..."
                                class="form-input search-input">
                            <i class="fas fa-search search-icon"></i>
                        </div>
                        <div class="flex flex-wrap gap-3">
                            <button onclick="printTable('admin-table')" class="btn btn-primary">
                                <i class="fas fa-print"></i>
                                <span>Imprimer</span>
                            </button>
                            <button onclick="exportToExcel('admin-table')" class="btn btn-warning">
                                <i class="fas fa-download"></i>
                                <span>Exporter</span>
                            </button>
                            <button onclick="deleteSelected('admin')" id="deleteButtonAdmin" class="btn btn-danger" disabled>
                                <i class="fas fa-trash"></i>
                                <span>Supprimer</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Table Personnel -->
                <div class="table-container">
                    <div class="table-header">
                        <h4 class="text-xl font-bold text-primary flex items-center">
                            <i class="fas fa-list-ul mr-3"></i>
                            Liste du personnel administratif
                        </h4>
                    </div>
                    
                    <form method="POST" action="?page=gestion_rh&tab=pers_admin">
                        <input type="hidden" name="submit_delete_multiple" id="submitDeletePersHidden" value="0">
                        
                        <div class="overflow-x-auto">
                            <table class="min-w-full" id="admin-table">
                                <thead>
                                    <tr class="border-b border-gray-200">
                                        <th class="px-6 py-4 text-center">
                                            <input type="checkbox" id="selectAllAdmin" class="checkbox-custom">
                                        </th>
                                        <th class="px-6 py-4 text-left text-sm font-bold text-gray-600 uppercase tracking-wider">👤 Nom</th>
                                        <th class="px-6 py-4 text-left text-sm font-bold text-gray-600 uppercase tracking-wider">👤 Prénom</th>
                                        <th class="px-6 py-4 text-left text-sm font-bold text-gray-600 uppercase tracking-wider">✉️ Email</th>
                                        <th class="px-6 py-4 text-left text-sm font-bold text-gray-600 uppercase tracking-wider">📞 Téléphone</th>
                                        <th class="px-6 py-4 text-left text-sm font-bold text-gray-600 uppercase tracking-wider">💼 Poste</th>
                                        <th class="px-6 py-4 text-left text-sm font-bold text-gray-600 uppercase tracking-wider">📅 Date d'embauche</th>
                                        <th class="px-6 py-4 text-center text-sm font-bold text-gray-600 uppercase tracking-wider">🔧 Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($personnel_admin)): ?>
                                        <tr>
                                            <td colspan="8" class="px-6 py-12 text-center">
                                                <div class="flex flex-col items-center">
                                                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                                        <i class="fas fa-users-cog text-3xl text-gray-400"></i>
                                                    </div>
                                                    <p class="text-lg font-semibold text-gray-500 mb-2">Aucun personnel administratif</p>
                                                    <p class="text-sm text-gray-400">Ajoutez votre premier membre du personnel</p>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($personnel_admin as $admin): ?>
                                            <tr class="table-row">
                                                <td class="px-6 py-4 text-center">
                                                    <input type="checkbox" name="selected_ids[]"
                                                        value="<?= htmlspecialchars($admin->id_pers_admin) ?>"
                                                        class="checkbox-custom">
                                                </td>
                                                <td class="px-6 py-4">
                                                    <div class="flex items-center">
                                                        <div class="w-10 h-10 bg-primary/10 text-primary rounded-full flex items-center justify-center text-sm font-bold mr-3">
                                                            <?= strtoupper(substr($admin->nom_pers_admin, 0, 1) . substr($admin->prenom_pers_admin, 0, 1)) ?>
                                                        </div>
                                                        <span class="font-semibold text-gray-900"><?= htmlspecialchars($admin->nom_pers_admin) ?></span>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 font-medium text-gray-900"><?= htmlspecialchars($admin->prenom_pers_admin) ?></td>
                                                <td class="px-6 py-4 text-gray-600"><?= htmlspecialchars($admin->email_pers_admin) ?></td>
                                                <td class="px-6 py-4 text-gray-600"><?= htmlspecialchars($admin->tel_pers_admin) ?></td>
                                                <td class="px-6 py-4">
                                                    <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold">
                                                        <?= htmlspecialchars($admin->poste) ?>
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 text-gray-600"><?= htmlspecialchars($admin->date_embauche) ?></td>
                                                <td class="px-6 py-4 text-center">
                                                    <div class="action-buttons">
                                                        <button type="button" onclick="showEditModal('pers_admin', <?= $admin->id_pers_admin ?>)"
                                                            class="action-btn edit">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <button type="button" onclick="showDeleteModal('pers_admin', <?= $admin->id_pers_admin ?>)"
                                                            class="action-btn delete">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Onglet Enseignants -->
            <div id="tab_enseignant" class="<?= $activeTab === 'enseignant' ? '' : 'hidden' ?>">
                <!-- Header section -->
                <div class="card p-8 mb-8">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
                        <div>
                            <h2 class="text-3xl font-bold text-secondary mb-2">Enseignants</h2>
                            <p class="text-gray-600">Gérez efficacement votre corps enseignant</p>
                        </div>
                        <button onclick="showAddModal('enseignant')" class="btn btn-secondary">
                            <i class="fas fa-plus"></i>
                            <span>Nouvel Enseignant</span>
                        </button>
                    </div>
                </div>

                <!-- Barre d'actions -->
                <div class="card p-6 mb-8">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-4 lg:space-y-0">
                        <div class="search-container flex-1 max-w-md">
                            <input type="text" id="searchInputTeacher" placeholder="Rechercher un enseignant..."
                                class="form-input search-input">
                            <i class="fas fa-search search-icon"></i>
                        </div>
                        <div class="flex flex-wrap gap-3">
                            <button onclick="printTable('teacher-table')" class="btn btn-primary">
                                <i class="fas fa-print"></i>
                                <span>Imprimer</span>
                            </button>
                            <button onclick="exportToExcel('teacher-table')" class="btn btn-warning">
                                <i class="fas fa-download"></i>
                                <span>Exporter</span>
                            </button>
                            <button onclick="deleteSelected('teacher')" id="deleteButtonTeacher" class="btn btn-danger" disabled>
                                <i class="fas fa-trash"></i>
                                <span>Supprimer</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Table Enseignants -->
                <div class="table-container">
                    <div class="table-header">
                        <h4 class="text-xl font-bold text-secondary flex items-center">
                            <i class="fas fa-chalkboard-teacher mr-3"></i>
                            Liste des enseignants
                        </h4>
                    </div>
                    
                    <form method="post" action="?page=gestion_rh&tab=enseignant">
                        <input type="hidden" name="submit_delete_multiple" id="submitDeleteHidden" value="0">
                        
                        <div class="overflow-x-auto">
                            <table class="min-w-full" id="teacher-table">
                                <thead>
                                    <tr class="border-b border-gray-200">
                                        <th class="px-6 py-4 text-center">
                                            <input type="checkbox" id="selectAllTeacher" class="checkbox-custom">
                                        </th>
                                        <th class="px-6 py-4 text-left text-sm font-bold text-gray-600 uppercase tracking-wider">👤 Nom Complet</th>
                                        <th class="px-6 py-4 text-left text-sm font-bold text-gray-600 uppercase tracking-wider">✉️ Email</th>
                                        <th class="px-6 py-4 text-left text-sm font-bold text-gray-600 uppercase tracking-wider">🎓 Spécialité</th>
                                        <th class="px-6 py-4 text-left text-sm font-bold text-gray-600 uppercase tracking-wider">💼 Fonction</th>
                                        <th class="px-6 py-4 text-left text-sm font-bold text-gray-600 uppercase tracking-wider">🏆 Grade</th>
                                        <th class="px-6 py-4 text-left text-sm font-bold text-gray-600 uppercase tracking-wider">📅 Date Grade</th>
                                        <th class="px-6 py-4 text-center text-sm font-bold text-gray-600 uppercase tracking-wider">🔧 Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($enseignants)): ?>
                                        <tr>
                                            <td colspan="8" class="px-6 py-12 text-center">
                                                <div class="flex flex-col items-center">
                                                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                                        <i class="fas fa-chalkboard-teacher text-3xl text-gray-400"></i>
                                                    </div>
                                                    <p class="text-lg font-semibold text-gray-500 mb-2">Aucun enseignant</p>
                                                    <p class="text-sm text-gray-400">Ajoutez votre premier enseignant</p>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($enseignants as $enseignant): ?>
                                            <tr class="table-row">
                                                <td class="px-6 py-4 text-center">
                                                    <input type="checkbox" name="selected_ids[]"
                                                        value="<?= htmlspecialchars($enseignant->id_enseignant) ?>"
                                                        class="checkbox-custom">
                                                </td>
                                                <td class="px-6 py-4">
                                                    <div class="flex items-center">
                                                        <div class="w-10 h-10 bg-secondary/10 text-secondary rounded-full flex items-center justify-center text-sm font-bold mr-3">
                                                            <?= strtoupper(substr($enseignant->nom_enseignant, 0, 1) . substr($enseignant->prenom_enseignant, 0, 1)) ?>
                                                        </div>
                                                        <span class="font-semibold text-gray-900">
                                                            <?= htmlspecialchars($enseignant->nom_enseignant . ' ' . $enseignant->prenom_enseignant) ?>
                                                        </span>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 text-gray-600"><?= htmlspecialchars($enseignant->mail_enseignant) ?></td>
                                                <td class="px-6 py-4">
                                                    <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">
                                                        <?= htmlspecialchars($enseignant->lib_specialite) ?>
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <span class="px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-xs font-semibold">
                                                        <?= htmlspecialchars($enseignant->lib_fonction) ?>
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-semibold">
                                                        <?= htmlspecialchars($enseignant->lib_grade) ?>
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 text-gray-600"><?= htmlspecialchars($enseignant->date_grade) ?></td>
                                                <td class="px-6 py-4 text-center">
                                                    <div class="action-buttons">
                                                        <button type="button" onclick="showEditModal('enseignant', <?= $enseignant->id_enseignant ?>)"
                                                            class="action-btn edit">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <button type="button" onclick="showDeleteModal('enseignant', <?= $enseignant->id_enseignant ?>)"
                                                            class="action-btn delete">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Personnel Administratif -->
    <div class="modal-overlay" id="modal-admin">
        <div class="modal-container">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h3 class="text-2xl font-bold text-primary">Nouveau Personnel Administratif</h3>
                    <p class="text-gray-600 mt-1">Complétez les informations ci-dessous</p>
                </div>
                <button onclick="closeModal('modal-admin')" class="w-12 h-12 bg-gray-100 hover:bg-gray-200 rounded-2xl flex items-center justify-center text-gray-400 hover:text-gray-600 transition-all duration-300">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <form id="modal-admin-form" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nom</label>
                        <input type="text" name="nom" id="modal-admin-nom" class="form-input" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Prénom</label>
                        <input type="text" name="prenom" id="modal-admin-prenom" class="form-input" required>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                        <input type="email" name="email" id="modal-admin-email" class="form-input" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Téléphone</label>
                        <input type="tel" name="telephone" id="modal-admin-telephone" class="form-input" required>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Poste</label>
                        <input type="text" name="poste" id="modal-admin-poste" class="form-input" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Date d'embauche</label>
                        <input type="date" name="date_embauche" id="modal-admin-date" class="form-input" required>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-4 pt-8">
                    <button type="button" onclick="closeModal('modal-admin')" class="btn btn-gray flex-1">
                        Annuler
                    </button>
                    <button type="submit" class="btn btn-primary flex-1">
                        <i class="fas fa-save"></i>
                        Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Enseignant -->
    <div class="modal-overlay" id="modal-enseignant">
        <div class="modal-container">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h3 class="text-2xl font-bold text-secondary">Nouvel Enseignant</h3>
                    <p class="text-gray-600 mt-1">Complétez les informations ci-dessous</p>
                </div>
                <button onclick="closeModal('modal-enseignant')" class="w-12 h-12 bg-gray-100 hover:bg-gray-200 rounded-2xl flex items-center justify-center text-gray-400 hover:text-gray-600 transition-all duration-300">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <form id="modal-enseignant-form" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nom</label>
                        <input type="text" name="nom" id="modal-enseignant-nom" class="form-input" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Prénom</label>
                        <input type="text" name="prenom" id="modal-enseignant-prenom" class="form-input" required>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                        <input type="email" name="email" id="modal-enseignant-email" class="form-input" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Spécialité</label>
                        <select name="specialite" id="modal-enseignant-specialite" class="form-input" required>
                            <option value="">Sélectionner une spécialité</option>
                            <?php foreach ($listeSpecialites as $specialite): ?>
                                <option value="<?= htmlspecialchars($specialite->id_specialite) ?>">
                                    <?= htmlspecialchars($specialite->lib_specialite) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Grade</label>
                        <select name="grade" id="modal-enseignant-grade" class="form-input" required>
                            <option value="">Sélectionner un grade</option>
                            <?php foreach ($listeGrades as $grade): ?>
                                <option value="<?= htmlspecialchars($grade->id_grade) ?>">
                                    <?= htmlspecialchars($grade->lib_grade) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Date Grade</label>
                        <input type="date" name="date_grade" id="modal-enseignant-date-grade" class="form-input" required>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-4 pt-8">
                    <button type="button" onclick="closeModal('modal-enseignant')" class="btn btn-gray flex-1">
                        Annuler
                    </button>
                    <button type="submit" class="btn btn-secondary flex-1">
                        <i class="fas fa-save"></i>
                        Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal de confirmation de suppression -->
    <div class="modal-overlay" id="delete-modal">
        <div class="modal-container max-w-md">
            <div class="text-center">
                <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-exclamation-triangle text-red-500 text-3xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-4">Confirmer la suppression</h3>
                <p class="text-gray-600 mb-8">Êtes-vous sûr de vouloir supprimer les éléments sélectionnés ? Cette action est irréversible.</p>

                <div class="flex gap-4">
                    <button onclick="closeModal('delete-modal')" class="btn btn-gray flex-1">
                        Annuler
                    </button>
                    <button onclick="confirmDelete()" class="btn btn-danger flex-1">
                        <i class="fas fa-trash"></i>
                        Supprimer
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Variables globales pour la gestion
        let currentDeleteType = '';
        const personnelAdminData = <?= json_encode($personnel_admin) ?>;
        const enseignantsData = <?= json_encode($enseignants) ?>;

        // Gestion des checkboxes
        function initializeCheckboxes() {
            // Personnel Admin
            const selectAllAdmin = document.getElementById('selectAllAdmin');
            const deleteButtonAdmin = document.getElementById('deleteButtonAdmin');
            
            if (selectAllAdmin) {
                selectAllAdmin.addEventListener('change', function() {
                    const checkboxes = document.querySelectorAll('#tab_pers_admin input[name="selected_ids[]"]');
                    checkboxes.forEach(cb => cb.checked = this.checked);
                    updateDeleteButton('admin');
                });
            }

            // Enseignants
            const selectAllTeacher = document.getElementById('selectAllTeacher');
            const deleteButtonTeacher = document.getElementById('deleteButtonTeacher');
            
            if (selectAllTeacher) {
                selectAllTeacher.addEventListener('change', function() {
                    const checkboxes = document.querySelectorAll('#tab_enseignant input[name="selected_ids[]"]');
                    checkboxes.forEach(cb => cb.checked = this.checked);
                    updateDeleteButton('teacher');
                });
            }

            // Event listeners pour les checkboxes individuelles
            document.addEventListener('change', function(e) {
                if (e.target.name === 'selected_ids[]') {
                    const isAdmin = e.target.closest('#tab_pers_admin');
                    updateDeleteButton(isAdmin ? 'admin' : 'teacher');
                }
            });
        }

        function updateDeleteButton(type) {
            const selector = type === 'admin' ? '#tab_pers_admin' : '#tab_enseignant';
            const deleteBtn = document.getElementById(type === 'admin' ? 'deleteButtonAdmin' : 'deleteButtonTeacher');
            const checkedBoxes = document.querySelectorAll(`${selector} input[name="selected_ids[]"]:checked`);
            
            if (deleteBtn) {
                deleteBtn.disabled = checkedBoxes.length === 0;
            }
        }

        // Gestion des modales
        function showAddModal(type) {
            const modal = document.getElementById('modal-' + type);
            if (modal) {
                modal.style.display = 'flex';
                // Reset form
                const form = modal.querySelector('form');
                if (form) form.reset();
            }
        }

        function showEditModal(type, id) {
            const modal = document.getElementById('modal-' + type);
            if (modal) {
                modal.style.display = 'flex';
                
                // Pré-remplir le formulaire
                const data = type === 'pers_admin' ? 
                    personnelAdminData.find(item => item.id_pers_admin == id) :
                    enseignantsData.find(item => item.id_enseignant == id);
                
                if (data && type === 'pers_admin') {
                    document.getElementById('modal-admin-nom').value = data.nom_pers_admin || '';
                    document.getElementById('modal-admin-prenom').value = data.prenom_pers_admin || '';
                    document.getElementById('modal-admin-email').value = data.email_pers_admin || '';
                    document.getElementById('modal-admin-telephone').value = data.tel_pers_admin || '';
                    document.getElementById('modal-admin-poste').value = data.poste || '';
                    document.getElementById('modal-admin-date').value = data.date_embauche || '';
                } else if (data && type === 'enseignant') {
                    document.getElementById('modal-enseignant-nom').value = data.nom_enseignant || '';
                    document.getElementById('modal-enseignant-prenom').value = data.prenom_enseignant || '';
                    document.getElementById('modal-enseignant-email').value = data.mail_enseignant || '';
                    // Autres champs enseignant...
                }
            }
        }

        function showDeleteModal(type, id = null) {
            currentDeleteType = type;
            const modal = document.getElementById('delete-modal');
            if (modal) {
                modal.style.display = 'flex';
                
                // Si suppression individuelle, cocher l'élément
                if (id) {
                    const selector = type === 'pers_admin' ? '#tab_pers_admin' : '#tab_enseignant';
                    const checkboxes = document.querySelectorAll(`${selector} input[name="selected_ids[]"]`);
                    checkboxes.forEach(cb => cb.checked = false);
                    
                    const targetCheckbox = document.querySelector(`${selector} input[value="${id}"]`);
                    if (targetCheckbox) targetCheckbox.checked = true;
                    
                    updateDeleteButton(type === 'pers_admin' ? 'admin' : 'teacher');
                }
            }
        }

        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) modal.style.display = 'none';
        }

        function confirmDelete() {
            // Logique de suppression
            console.log('Suppression confirmée pour:', currentDeleteType);
            closeModal('delete-modal');
        }

        function deleteSelected(type) {
            const activeType = type === 'admin' ? 'pers_admin' : 'enseignant';
            showDeleteModal(activeType);
        }

        // Fonctions d'export et impression
        function exportToExcel(tableId) {
            console.log('Export Excel pour:', tableId);
            // Logique d'export
        }

        function printTable(tableId) {
            console.log('Impression pour:', tableId);
            // Logique d'impression
        }

        // Recherche
        function setupSearch() {
            const searchAdmin = document.getElementById('searchInputAdmin');
            const searchTeacher = document.getElementById('searchInputTeacher');
            
            if (searchAdmin) {
                searchAdmin.addEventListener('keyup', function() {
                    filterTable('admin-table', this.value);
                });
            }
            
            if (searchTeacher) {
                searchTeacher.addEventListener('keyup', function() {
                    filterTable('teacher-table', this.value);
                });
            }
        }

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

        // Fermeture des modales en cliquant à l'extérieur
        window.addEventListener('click', function(e) {
            const modals = ['modal-admin', 'modal-enseignant', 'delete-modal'];
            modals.forEach(modalId => {
                const modal = document.getElementById(modalId);
                if (modal && e.target === modal) {
                    closeModal(modalId);
                }
            });
        });

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

        // Initialisation
        document.addEventListener('DOMContentLoaded', function() {
            initializeCheckboxes();
            setupSearch();
            
            // Animation des cartes
            const cards = document.querySelectorAll('.stat-card, .card');
            cards.forEach((card, index) => {
                card.style.animationDelay = `${index * 0.1}s`;
            });
        });
    </script>
</body>

</html>
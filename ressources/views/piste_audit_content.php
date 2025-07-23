<?php 
// Définir des valeurs par défaut pour éviter les erreurs
$logs = $logs ?? [];
$totalLogs = $totalLogs ?? 0;
$totalPages = $totalPages ?? 1;
$page = $page ?? 1;
$perPage = $perPage ?? 20;
$actions = $actions ?? [];
$users = $users ?? [];

// Fonction helper pour déterminer la classe CSS des badges d'action
function getActionBadgeClass($action) {
    switch ($action) {
        case 'Connexion':
            return 'bg-green-100 text-green-800';
        case 'Déconnexion':
            return 'bg-blue-100 text-blue-800';
        case 'Créer':
            return 'bg-green-100 text-green-800';
        case 'Modifier':
            return 'bg-yellow-100 text-yellow-800';
        case 'Supprimer':
            return 'bg-red-100 text-red-800';
        case 'Consulter':
            return 'bg-indigo-100 text-indigo-800';
        case 'Exporter':
            return 'bg-purple-100 text-purple-800';
        case 'Imprimer':
            return 'bg-gray-100 text-gray-800';
        case 'Restaurer':
            return 'bg-orange-100 text-orange-800';
        case 'Déposer':
            return 'bg-teal-100 text-teal-800';
        default:
            return 'bg-gray-100 text-gray-800';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Piste D'Audit | Univalid</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/fr.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
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

        .btn {
            padding: 12px 24px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            border: none;
            cursor: pointer;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            transition: all 0.3s ease;
        }

        .btn:hover::before {
            width: 300px;
            height: 300px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #3457cb 0%, #24407a 100%);
            color: white;
            box-shadow: 0 4px 16px rgba(52, 87, 203, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(52, 87, 203, 0.4);
        }

        .btn-success {
            background: linear-gradient(135deg, #36865a 0%, #59bf3d 100%);
            color: white;
            box-shadow: 0 4px 16px rgba(54, 134, 90, 0.3);
        }

        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(54, 134, 90, 0.4);
        }

        .btn-secondary {
            background: #f8f9fa;
            color: #334155;
            border: 2px solid #e2e8f0;
        }

        .btn-secondary:hover {
            background: white;
            border-color: rgba(52, 87, 203, 0.2);
            transform: translateY(-2px);
        }

        .header-gradient {
            background: linear-gradient(135deg, #24407a 0%, #3457cb 100%);
        }

        .table-hover tbody tr:hover {
            background: rgba(52, 87, 203, 0.02);
            transform: scale(1.01);
            transition: all 0.2s ease;
        }

        .pagination-item {
            padding: 8px 16px;
            margin: 0 2px;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .pagination-item:hover {
            background: rgba(52, 87, 203, 0.1);
            transform: translateY(-1px);
        }

        .filter-input {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 10px 16px;
            transition: all 0.3s ease;
        }

        .filter-input:focus {
            outline: none;
            border-color: #3457cb;
            box-shadow: 0 0 0 3px rgba(52, 87, 203, 0.1);
        }

        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 4px 16px rgba(15, 23, 42, 0.08);
            transition: all 0.3s ease;
            border: 1px solid #e2e8f0;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.12);
        }

        .icon-gradient {
            background: linear-gradient(135deg, #3457cb 0%, #36865a 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
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
                    <i class="fas fa-shield-alt"></i>
                </div>
                <div class="header-text">
                    <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 mb-2 tracking-tight">Piste d'Audit</h1>
                    <p class="text-lg text-gray-600 font-normal">Suivi et traçabilité de toutes les actions système</p>
                </div>
            </div>
            <div class="absolute top-4 right-4 date-picker">
                <label class="block text-sm font-medium text-gray-700 mb-2">Date du jour</label>
                <input type="text"
                    class="w-40 px-4 py-2 bg-white border-2 border-gray-200 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-light focus:border-primary-light text-sm font-medium"
                    readonly value="<?php echo date('d/m/Y'); ?>">
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="stat-card animate-slide-in-right" style="animation-delay: 0.1s">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-primary/10 text-primary rounded-xl flex items-center justify-center text-2xl">
                        <i class="fas fa-list"></i>
                    </div>
                    <div>
                        <h3 class="text-3xl font-bold text-primary"><?php echo $totalLogs; ?></h3>
                        <p class="text-sm font-medium text-gray-600">Total des logs</p>
                    </div>
                </div>
            </div>
            <div class="stat-card animate-slide-in-right" style="animation-delay: 0.2s">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-secondary/10 text-secondary rounded-xl flex items-center justify-center text-2xl">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div>
                        <h3 class="text-3xl font-bold text-secondary"><?php echo date('H:i'); ?></h3>
                        <p class="text-sm font-medium text-gray-600">Heure actuelle</p>
                    </div>
                </div>
            </div>
            <div class="stat-card animate-slide-in-right" style="animation-delay: 0.3s">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-green-500/10 text-green-600 rounded-xl flex items-center justify-center text-2xl">
                        <i class="fas fa-users"></i>
                    </div>
                    <div>
                        <h3 class="text-3xl font-bold text-green-600"><?php echo count($users); ?></h3>
                        <p class="text-sm font-medium text-gray-600">Utilisateurs actifs</p>
                    </div>
                </div>
            </div>
            <div class="stat-card animate-slide-in-right" style="animation-delay: 0.4s">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-yellow-500/10 text-yellow-600 rounded-xl flex items-center justify-center text-2xl">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div>
                        <h3 class="text-3xl font-bold text-yellow-600"><?php echo count($actions); ?></h3>
                        <p class="text-sm font-medium text-gray-600">Types d'actions</p>
                    </div>
                </div>
            </div>
        </div>

        <?php 
        // Messages de notification avec design moderne
        if (isset($_GET['success']) && $_GET['success'] === 'cleanup'): ?>
        <div class="bg-green-50 border border-green-200 text-green-800 px-6 py-4 rounded-2xl relative mb-6 shadow-lg animate-scale-in" role="alert">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle text-green-500 text-xl"></i>
                </div>
                <div class="ml-3">
                    <p class="font-semibold">Succès !</p>
                    <p class="text-sm"><?php echo $_GET['deleted'] ?? 0; ?> enregistrements d'audit ont été supprimés.</p>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <?php if (isset($_GET['error'])): ?>
        <div class="bg-red-50 border border-red-200 text-red-800 px-6 py-4 rounded-2xl relative mb-6 shadow-lg animate-scale-in" role="alert">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-red-500 text-xl"></i>
                </div>
                <div class="ml-3">
                    <p class="font-semibold">Erreur !</p>
                    <p class="text-sm">
                        <?php 
                        switch ($_GET['error']) {
                            case 'invalid_days':
                                echo 'Nombre de jours invalide pour le nettoyage.';
                                break;
                            case 'invalid_id':
                                echo 'ID de log invalide.';
                                break;
                            case 'log_not_found':
                                echo 'Log d\'audit introuvable.';
                                break;
                            default:
                                echo 'Une erreur s\'est produite.';
                        }
                        ?>
                    </p>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Main Content Card -->
        <div class="card mb-8 animate-scale-in">
            <!-- Card Header -->
            <div class="header-gradient px-8 py-6">
                <h2 class="text-2xl font-bold text-white flex items-center">
                    <i class="fas fa-history mr-3 text-2xl"></i>
                    Historique des Actions Système
                </h2>
            </div>

            <!-- Filters Section -->
            <div class="px-8 py-6 bg-gray-50 border-b border-gray-200">
                <form method="GET" action="?page=piste_audit"
                    class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-6">
                    <input type="hidden" name="page" value="piste_audit">
                    
                    <!-- Date Range Filters -->
                    <div class="flex flex-col sm:flex-row gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">📅 Date début</label>
                            <input type="text" name="date_debut" id="date_debut"
                                value="<?php echo htmlspecialchars($_GET['date_debut'] ?? ''); ?>"
                                placeholder="Sélectionner la date"
                                class="filter-input w-full sm:w-48">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">📅 Date fin</label>
                            <input type="text" name="date_fin" id="date_fin"
                                value="<?php echo htmlspecialchars($_GET['date_fin'] ?? ''); ?>"
                                placeholder="Sélectionner la date"
                                class="filter-input w-full sm:w-48">
                        </div>
                    </div>

                    <!-- Additional Filters -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">⚡ Action</label>
                        <select name="action" class="filter-input w-full sm:w-48">
                            <option value="">Toutes les actions</option>
                            <?php foreach ($actions as $action): ?>
                            <option value="<?php echo htmlspecialchars($action->id_action); ?>"
                                <?php echo (isset($_GET['action']) && $_GET['action'] === $action->id_action) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($action->lib_action); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Search Filter -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">🔍 Recherche</label>
                        <input type="text" name="search" id="search"
                            value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>"
                            placeholder="Rechercher dans les logs..."
                            class="filter-input w-full sm:w-64">
                    </div>

                    <!-- Filter Buttons -->
                    <div class="flex gap-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search mr-2"></i> Filtrer
                        </button>
                        <a href="?page=piste_audit" class="btn btn-secondary">
                            <i class="fas fa-times mr-2"></i> Réinitialiser
                        </a>
                    </div>
                </form>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-between items-center px-8 py-4 bg-white border-b border-gray-200">
                <div class="text-sm text-gray-600">
                    <span class="font-semibold text-primary"><?php echo $totalLogs ?? 0; ?></span> enregistrements trouvés
                </div>
                <div class="flex space-x-3">
                    <a href="?page=piste_audit&action=export&<?php echo http_build_query(array_filter($_GET, function($key) { return $key !== 'page'; }, ARRAY_FILTER_USE_KEY)); ?>"
                        class="btn" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white;">
                        <i class="fas fa-file-export mr-2"></i> Exporter
                    </a>
                    <button onclick="window.print()" class="btn btn-primary">
                        <i class="fas fa-print mr-2"></i> Imprimer
                    </button>
                    <a href="?page=piste_audit" class="btn btn-success">
                        <i class="fas fa-sync-alt mr-2"></i> Actualiser
                    </a>
                </div>
            </div>

            <!-- Table Content -->
            <div class="overflow-x-auto">
                <table class="w-full table-hover">
                    <thead class="bg-gray-50 text-gray-700 border-b border-gray-200">
                        <tr>
                            <th class="py-4 px-6 text-left text-xs font-bold uppercase tracking-wider">
                                📅 Date
                            </th>
                            <th class="py-4 px-6 text-left text-xs font-bold uppercase tracking-wider">
                                🕐 Heure
                            </th>
                            <th class="py-4 px-6 text-left text-xs font-bold uppercase tracking-wider">⚡ Action</th>
                            <th class="py-4 px-6 text-left text-xs font-bold uppercase tracking-wider">📋 Table</th>
                            <th class="py-4 px-6 text-left text-xs font-bold uppercase tracking-wider">👤 Utilisateur</th>
                            <th class="py-4 px-6 text-left text-xs font-bold uppercase tracking-wider">📧 Email</th>
                            <th class="py-4 px-6 text-center text-xs font-bold uppercase tracking-wider">🔧 Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php if (empty($logs)): ?>
                        <tr>
                            <td colspan="7" class="py-12 px-6 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                        <i class="fas fa-search text-3xl text-gray-400"></i>
                                    </div>
                                    <p class="text-lg font-semibold text-gray-500 mb-2">Aucun log trouvé</p>
                                    <p class="text-sm text-gray-400">Aucun log d'audit ne correspond aux critères sélectionnés.</p>
                                </div>
                            </td>
                        </tr>
                        <?php else: ?>
                        <?php foreach ($logs as $log): ?>
                        <tr class="hover:bg-gray-50 transition-all duration-200">
                            <td class="py-4 px-6 text-sm font-medium"><?php echo date('d/m/Y', strtotime($log['created_at'])); ?></td>
                            <td class="py-4 px-6 text-sm text-gray-600"><?php echo date('H:i:s', strtotime($log['created_at'])); ?></td>
                            <td class="py-4 px-6">
                                <span class="badge <?php echo getActionBadgeClass($log['lib_action']); ?>">
                                    <?php echo htmlspecialchars($log['lib_action']); ?>
                                </span>
                            </td>
                            <td class="py-4 px-6 text-sm font-mono"><?php echo htmlspecialchars($log['table_name'] ?? 'N/A'); ?></td>
                            <td class="py-4 px-6 text-sm font-semibold text-primary"><?php echo htmlspecialchars($log['nom_utilisateur'] ?? 'N/A'); ?></td>
                            <td class="py-4 px-6 text-sm text-gray-600"><?php echo htmlspecialchars($log['login_utilisateur'] ?? 'N/A'); ?></td>
                            <td class="py-4 px-6 text-center">
                                <a href="?page=piste_audit&action=details&id=<?php echo $log['id_piste']; ?>"
                                    class="text-primary hover:text-primary-light transition-colors duration-200 font-semibold"
                                    title="Voir les détails">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <?php if (($totalPages ?? 1) > 1): ?>
            <div class="px-8 py-6 bg-gray-50 border-t border-gray-200 flex items-center justify-between">
                <div class="text-sm text-gray-600">
                    Affichage de <span class="font-semibold text-primary"><?php echo (($page - 1) * $perPage) + 1; ?></span> à 
                    <span class="font-semibold text-primary"><?php echo min($page * $perPage, $totalLogs); ?></span> sur 
                    <span class="font-semibold text-primary"><?php echo $totalLogs; ?></span> enregistrements
                </div>
                <div class="flex items-center space-x-2">
                    <?php if ($page > 1): ?>
                    <a href="?page=piste_audit&page_num=<?php echo $page - 1; ?>&<?php echo http_build_query(array_filter($_GET, function($key) { return !in_array($key, ['page', 'page_num']); }, ARRAY_FILTER_USE_KEY)); ?>"
                        class="pagination-item bg-white border border-gray-300 text-gray-700 hover:bg-primary hover:text-white">
                        <i class="fas fa-chevron-left"></i>
                    </a>
                    <?php endif; ?>

                    <?php for ($i = max(1, $page - 2); $i <= min($totalPages, $page + 2); $i++): ?>
                    <?php if ($i == $page): ?>
                    <span class="pagination-item bg-primary text-white border border-primary"><?php echo $i; ?></span>
                    <?php else: ?>
                    <a href="?page=piste_audit&page_num=<?php echo $i; ?>&<?php echo http_build_query(array_filter($_GET, function($key) { return !in_array($key, ['page', 'page_num']); }, ARRAY_FILTER_USE_KEY)); ?>"
                        class="pagination-item bg-white border border-gray-300 text-gray-700 hover:bg-primary hover:text-white"><?php echo $i; ?></a>
                    <?php endif; ?>
                    <?php endfor; ?>

                    <?php if ($page < $totalPages): ?>
                    <a href="?page=piste_audit&page_num=<?php echo $page + 1; ?>&<?php echo http_build_query(array_filter($_GET, function($key) { return !in_array($key, ['page', 'page_num']); }, ARRAY_FILTER_USE_KEY)); ?>"
                        class="pagination-item bg-white border border-gray-300 text-gray-700 hover:bg-primary hover:text-white">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Cleanup Section -->
        
    </div>

    <script>
        // Initialisation des datepickers avec style personnalisé
        document.addEventListener('DOMContentLoaded', function() {
            flatpickr("#date_debut", {
                locale: "fr",
                dateFormat: "Y-m-d",
                allowInput: true,
                placeholder: "Date de début"
            });

            flatpickr("#date_fin", {
                locale: "fr",
                dateFormat: "Y-m-d",
                allowInput: true,
                placeholder: "Date de fin"
            });

            // Auto-submit du formulaire de recherche avec délai
            let searchTimeout;
            document.getElementById('search').addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    this.form.submit();
                }, 800);
            });

            // Animation d'apparition des notifications
            const notifications = document.querySelectorAll('[role="alert"]');
            notifications.forEach((notification, index) => {
                notification.style.animationDelay = `${index * 0.1}s`;
                setTimeout(() => {
                    notification.style.opacity = '0';
                    notification.style.transform = 'translateY(-20px)';
                    setTimeout(() => notification.remove(), 500);
                }, 5000);
            });
        });
    </script>
</body>

</html>
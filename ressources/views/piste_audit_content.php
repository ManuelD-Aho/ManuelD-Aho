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
        case 'Création': // Corrigé pour correspondre à la base de données
            return 'bg-green-100 text-green-800';
        case 'Modification': // Corrigé pour correspondre à la base de données
            return 'bg-yellow-100 text-yellow-800';
        case 'Suppression': // Corrigé pour correspondre à la base de données
            return 'bg-red-100 text-red-800';
        case 'Consultation': // Corrigé pour correspondre à la base de données
            return 'bg-indigo-100 text-indigo-800';
        case 'Exportation': // Corrigé pour correspondre à la base de données
            return 'bg-purple-100 text-purple-800';
        case 'Impression': // Corrigé pour correspondre à la base de données
            return 'bg-gray-100 text-gray-800';
        case 'Restauration': // Corrigé pour correspondre à la base de données
            return 'bg-orange-100 text-orange-800';
        case 'Dépôt': // Corrigé pour correspondre à la base de données
            return 'bg-teal-100 text-teal-800';
        case 'Evaluation': // Corrigé pour correspondre à la base de données
            return 'bg-cyan-100 text-cyan-800';
        case 'Validation': // Corrigé pour correspondre à la base de données
            return 'bg-lime-100 text-lime-800'; // Nouvelle couleur pour la validation
        case 'Rejet': // Corrigé pour correspondre à la base de données
            return 'bg-pink-100 text-pink-800'; // Nouvelle couleur pour le rejet
        case 'Accès': // Corrigé pour correspondre à la base de données
            return 'bg-blue-200 text-blue-900'; // Nouvelle couleur pour l'accès
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
    <title>Piste D'Audit</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/fr.js"></script>
    <!-- Inclure le script audit.js -->
    <script src="public/js/audit.js" defer></script>
</head>

<body class="bg-gray-50 min-h-screen animated-bg font-sans">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header with Logo and Title -->
    <div class="flex justify-between items-center mb-8">
        <div class="flex items-center">
            <div class="bg-green-500 text-white p-3 rounded-full mr-4">
                <i class="fas fa-shield-alt text-xl"></i>
            </div>
            <h1 class="text-2xl font-bold text-gray-800"> Piste d'Audit</h1>
        </div>
        <div class="date-picker relative">
            <label class="block text-sm font-medium text-gray-700 mb-1">Date du jour</label>
            <input type="text"
                   class="w-40 px-4 py-2 bg-white border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-green-600 focus:border-0 text-sm"
                   readonly value="<?php echo date('d/m/Y'); ?>">
        </div>
    </div>

    <?php
    // Définir des valeurs par défaut pour éviter les erreurs
    $logs = $GLOBALS['auditLog'] ?? []; // Utiliser $GLOBALS['auditLog']
    $totalLogs = $GLOBALS['totalLogs'] ?? 0;
    $totalPages = $GLOBALS['totalPages'] ?? 1;
    $page = $GLOBALS['page'] ?? 1;
    $perPage = $GLOBALS['perPage'] ?? 20;
    $actions = $GLOBALS['actions'] ?? []; // Utiliser $GLOBALS['actions']
    $tables = $GLOBALS['tables'] ?? []; // Assurez-vous que AuditController::getTablesList() est appelée
    $statuts = $GLOBALS['statuts'] ?? []; // Assurez-vous que AuditController::getStatutsList() est appelée
    ?>

    <!-- Messages de notification -->
    <?php if (isset($_GET['success'])): ?>
        <div id="success-notification"
             class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6 notification"
             role="alert">
            <strong class="font-bold">Succès !</strong>
            <span class="block sm:inline">
                <?php
                if ($_GET['success'] === 'cleanup') {
                    echo htmlspecialchars($_GET['deleted'] ?? 0) . ' enregistrements d\'audit ont été supprimés.';
                } elseif ($_GET['success'] === 'log_deleted') {
                    echo 'Le log d\'audit a été supprimé avec succès.';
                } else {
                    echo 'Opération réussie.';
                }
                ?>
            </span>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
        <div id="error-notification"
             class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6 notification"
             role="alert">
            <strong class="font-bold">Erreur !</strong>
            <span class="block sm:inline">
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
                    case 'cleanup_failed':
                        echo 'Échec du nettoyage des logs.';
                        break;
                    case 'delete_failed':
                        echo 'Échec de la suppression du log.';
                        break;
                    default:
                        echo 'Une erreur s\'est produite.';
                }
                ?>
            </span>
        </div>
    <?php endif; ?>

    <!-- Main Content Card -->
    <div class="bg-white shadow-lg rounded-xl overflow-hidden transition-all hover:shadow-xl">
        <!-- Card Header -->
        <div class="bg-gradient-to-r from-green-600 to-green-800 px-6 py-4">
            <h2 class="text-lg font-semibold text-white flex items-center">
                <i class="fas fa-history mr-2"></i>
                Piste d'Audit - Historique des Actions
            </h2>
        </div>

        <!-- Filters Section -->
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <form method="GET" action="?page=piste_audit"
                  class="flex flex-col md:flex-row md:items-end md:justify-between gap-4">
                <input type="hidden" name="page" value="piste_audit">

                <!-- Date Range Filters -->
                <div class="flex flex-col sm:flex-row gap-4">
                    <div class="w-full sm:w-auto">
                        <label class="block text-xs text-gray-500 mb-1">Date début</label>
                        <input type="text" name="date_debut" id="date_debut"
                               value="<?php echo htmlspecialchars($_GET['date_debut'] ?? ''); ?>"
                               placeholder="Date de début"
                               class="w-full sm:w-40 px-3 py-2 bg-white border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 text-sm">
                    </div>
                    <div class="w-full sm:w-auto">
                        <label class="block text-xs text-gray-500 mb-1">Date fin</label>
                        <input type="text" name="date_fin" id="date_fin"
                               value="<?php echo htmlspecialchars($_GET['date_fin'] ?? ''); ?>"
                               placeholder="Date de fin"
                               class="w-full sm:w-40 px-3 py-2 bg-white border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 text-sm">
                    </div>
                </div>

                <!-- Additional Filters -->
                <div class="w-full sm:w-auto">
                    <label class="block text-xs text-gray-500 mb-1">Action</label>
                    <select name="action"
                            class="w-full sm:w-40 px-3 py-2 bg-white border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 text-sm">
                        <option value="">Toutes les actions</option>
                        <?php foreach ($GLOBALS['actions'] as $action): ?>
                            <option value="<?php echo htmlspecialchars($action->lib_action); ?>"
                                <?php echo (isset($_GET['action']) && $_GET['action'] === $action->lib_action) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($action->lib_action); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="w-full sm:w-auto">
                    <label class="block text-xs text-gray-500 mb-1">Table</label>
                    <select name="table"
                            class="w-full sm:w-40 px-3 py-2 bg-white border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 text-sm">
                        <option value="">Toutes les tables</option>
                        <?php
                        // Récupérer la liste des tables distinctes depuis les logs
                        $auditController = new AuditController(); // Instancier le contrôleur
                        $tablesList = $auditController->getTablesList();
                        foreach ($tablesList as $table): ?>
                            <option value="<?php echo htmlspecialchars($table); ?>"
                                <?php echo (isset($_GET['table']) && $_GET['table'] === $table) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($table); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="w-full sm:w-auto">
                    <label class="block text-xs text-gray-500 mb-1">Statut</label>
                    <select name="statut"
                            class="w-full sm:w-40 px-3 py-2 bg-white border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 text-sm">
                        <option value="">Tous les statuts</option>
                        <?php
                        // Récupérer la liste des statuts distincts depuis les logs
                        $statutsList = $auditController->getStatutsList();
                        foreach ($statutsList as $statut): ?>
                            <option value="<?php echo htmlspecialchars($statut); ?>"
                                <?php echo (isset($_GET['statut']) && $_GET['statut'] === $statut) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($statut); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Search Filter -->
                <div class="w-full sm:w-auto">
                    <label class="block text-xs text-gray-500 mb-1">Recherche</label>
                    <input type="text" name="search" id="search"
                           value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>"
                           placeholder="Rechercher..."
                           class="w-full sm:w-48 px-3 py-2 bg-white border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 text-sm">
                </div>

                <!-- Filter Buttons -->
                <div class="flex gap-2">
                    <button type="submit"
                            class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg text-sm shadow-sm transition-all hover-scale focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 flex items-center">
                        <i class="fas fa-search mr-2"></i> Filtrer
                    </button>
                    <a href="?page=piste_audit"
                       class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm shadow-sm transition-all hover-scale focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 flex items-center">
                        <i class="fas fa-times mr-2"></i> Réinitialiser
                    </a>
                </div>
            </form>
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-between items-center px-6 py-3 bg-white border-b border-gray-200">
            <div class="text-sm text-gray-500">
                <span class="font-medium"><?php echo $totalLogs ?? 0; ?></span> enregistrements trouvés
            </div>
            <div class="flex space-x-2">
                <a href="?page=piste_audit&action=export&<?php echo http_build_query(array_filter($_GET, function($key) { return $key !== 'page'; }, ARRAY_FILTER_USE_KEY)); ?>"
                   class="bg-amber-500 hover:bg-amber-600 text-white px-4 py-2 rounded-lg text-sm shadow-sm transition-all hover-scale focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 flex items-center">
                    <i class="fas fa-file-export mr-2"></i> Exporter
                </a>
                <button type="button" onclick="window.print()"
                        class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm shadow-sm transition-all hover-scale focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 flex items-center">
                    <i class="fas fa-print mr-2"></i> Imprimer
                </button>
                <a href="?page=piste_audit"
                   class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg text-sm shadow-sm transition-all hover-scale focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 flex items-center">
                    <i class="fas fa-sync-alt mr-2"></i> Actualiser
                </a>
            </div>
        </div>

        <!-- Table Content -->
        <div class="overflow-x-auto">
            <table class="w-full table-hover">
                <thead class="bg-gray-50 text-gray-700 border-b border-gray-200">
                <tr>
                    <th class="py-3 px-4 text-left text-xs font-medium uppercase tracking-wider">
                        <div class="flex items-center cursor-pointer">
                            Date
                            <i class="fas fa-sort ml-1 text-gray-400"></i>
                        </div>
                    </th>
                    <th class="py-3 px-4 text-left text-xs font-medium uppercase tracking-wider">
                        <div class="flex items-center cursor-pointer">
                            Heure
                            <i class="fas fa-sort ml-1 text-gray-400"></i>
                        </div>
                    </th>
                    <th class="py-3 px-4 text-left text-xs font-medium uppercase tracking-wider">Action</th>
                    <th class="py-3 px-4 text-left text-xs font-medium uppercase tracking-wider">Statut</th>
                    <th class="py-3 px-4 text-left text-xs font-medium uppercase tracking-wider">Table</th>
                    <th class="py-3 px-4 text-left text-xs font-medium uppercase tracking-wider">Login
                        Utilisateur</th>
                    <th class="py-3 px-4 text-left text-xs font-medium uppercase tracking-wider">Nom Utilisateur
                    </th>
                    <th class="py-3 px-4 text-center text-xs font-medium uppercase tracking-wider">Actions</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                <?php if (empty($logs)): ?>
                    <tr>
                        <td colspan="8" class="py-8 px-4 text-center text-gray-500">
                            <i class="fas fa-search text-4xl mb-4 text-gray-300"></i>
                            <p>Aucun log d'audit trouvé pour les critères sélectionnés.</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($logs as $log): ?>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="py-3 px-4 text-sm"><?php echo date('d/m/Y', strtotime($log['date_creation'])); ?>
                            </td>
                            <td class="py-3 px-4 text-sm"><?php echo date('H:i:s', strtotime($log['date_creation'])); ?>
                            </td>
                            <td class="py-3 px-4">
                                <span
                                        class="px-2 py-1 text-xs font-semibold rounded-full <?php echo getActionBadgeClass($log['action']); ?>">
                                    <?php echo htmlspecialchars($log['action']); ?>
                                </span>
                            </td>
                            <td class="py-3 px-4">
                                <span
                                        class="px-2 py-1 text-xs font-semibold rounded-full <?php echo $log['statut_action'] === 'Succès' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                                    <?php echo htmlspecialchars($log['statut_action']); ?>
                                </span>
                            </td>
                            <td class="py-3 px-4 text-sm"><?php echo htmlspecialchars($log['nom_table'] ?? 'N/A'); ?>
                            </td>
                            <td class="py-3 px-4 text-sm custom-tooltip" title="Cliquer pour copier">
                                <?php echo htmlspecialchars($log['login_utilisateur'] ?? 'N/A'); ?></td>
                            <td class="py-3 px-4 text-sm custom-tooltip" title="Cliquer pour copier">
                                <?php echo htmlspecialchars($log['nom_utilisateur'] ?? 'N/A'); ?></td>
                            <td class="py-3 px-4 text-center">
                                <button type="button"
                                        onclick="confirmAction('Êtes-vous sûr de vouloir supprimer ce log ?', function() { window.location.href='?page=piste_audit&action=delete_log&log_id=<?php echo $log['id_piste']; ?>'; });"
                                        class="text-red-600 hover:text-red-800 mx-1 custom-tooltip"
                                        data-tooltip="Supprimer ce log">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <?php if (($totalPages ?? 1) > 1): ?>
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex items-center justify-between">
                <div class="text-sm text-gray-700">
                    Affichage de <span class="font-medium"><?php echo (($page - 1) * $perPage) + 1; ?></span> à <span
                            class="font-medium"><?php echo min($page * $perPage, $totalLogs); ?></span> sur <span
                            class="font-medium"><?php echo $totalLogs; ?></span> enregistrements
                </div>
                <div class="flex items-center space-x-1">
                    <?php if ($page > 1): ?>
                        <a href="?page=piste_audit&page_num=<?php echo $page - 1; ?>&<?php echo http_build_query(array_filter($_GET, function($key) { return !in_array($key, ['page', 'page_num']); }, ARRAY_FILTER_USE_KEY)); ?>"
                           class="pagination-item">
                            <i class="fas fa-chevron-left mr-2"></i>
                        </a>
                    <?php else: ?>
                        <button class="pagination-item" disabled>
                            <i class="fas fa-chevron-left mr-2"></i>
                        </button>
                    <?php endif; ?>

                    <?php for ($i = max(1, $page - 2); $i <= min($totalPages, $page + 2); $i++): ?>
                        <?php if ($i == $page): ?>
                            <span class="pagination-item bg-green-500 text-white"><?php echo $i; ?></span>
                        <?php else: ?>
                            <a href="?page=piste_audit&page_num=<?php echo $i; ?>&<?php echo http_build_query(array_filter($_GET, function($key) { return !in_array($key, ['page', 'page_num']); }, ARRAY_FILTER_USE_KEY)); ?>"
                               class="pagination-item"><?php echo $i; ?></a>
                        <?php endif; ?>
                    <?php endfor; ?>

                    <?php if ($page < $totalPages): ?>
                        <a href="?page=piste_audit&page_num=<?php echo $page + 1; ?>&<?php echo http_build_query(array_filter($_GET, function($key) { return !in_array($key, ['page', 'page_num']); }, ARRAY_FILTER_USE_KEY)); ?>"
                           class="pagination-item">
                            <i class="fas fa-chevron-right ml-2"></i>
                        </a>
                    <?php else: ?>
                        <button class="pagination-item" disabled>
                            <i class="fas fa-chevron-right ml-2"></i>
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Cleanup Section -->
    <div class="mt-6 bg-white shadow-lg rounded-xl overflow-hidden">
        <div class="bg-gradient-to-r from-red-600 to-red-800 px-6 py-4">
            <h3 class="text-lg font-semibold text-white flex items-center">
                <i class="fas fa-trash-alt mr-2"></i>
                Nettoyage des Logs
            </h3>
        </div>
        <div class="px-6 py-4">
            <form method="POST" action="?page=piste_audit&action=cleanup" class="flex items-end gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Supprimer les logs de plus de</label>
                    <input type="number" name="days" min="1" max="365" value="30" required
                           class="w-32 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500">
                    <span class="text-sm text-gray-500 ml-2">jours</span>
                </div>
                <button type="submit"
                        class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm shadow-sm transition-all hover-scale focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 flex items-center"
                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer les anciens logs ? Cette action est irréversible.')">
                    <i class="fas fa-trash-alt mr-2"></i> Nettoyer
                </button>
            </form>
            <p class="text-sm text-gray-600 mt-2">
                <i class="fas fa-info-circle mr-1"></i>
                Cette action supprimera définitivement tous les logs d'audit antérieurs à la période spécifiée.
            </p>
        </div>
    </div>
</div>

<style>
    .animated-bg {
        background: linear-gradient(-45deg, #f0f9ff, #f0fdf4, #fefce8, #fef2f2);
        background-size: 400% 400%;
        animation: gradient 15s ease infinite;
    }

    @keyframes gradient {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }

    .btn-gradient-primary {
        background: linear-gradient(135deg, #10b981, #059669);
    }

    .btn-gradient-warning {
        background: linear-gradient(135deg, #f59e0b, #d97706);
    }

    .btn-gradient-secondary {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
    }

    .btn-gradient-danger {
        background: linear-gradient(135deg, #ef4444, #dc2626);
    }

    .hover-scale:hover {
        transform: scale(1.05);
    }

    .table-hover tbody tr:hover {
        background-color: #f9fafb;
    }

    .pagination-item {
        @apply px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:text-gray-700 transition-colors;
    }

    .pagination-item:disabled {
        @apply opacity-50 cursor-not-allowed;
    }

    .custom-tooltip {
        position: relative;
    }

    .custom-tooltip:hover::after {
        content: attr(data-tooltip);
        position: absolute;
        bottom: 100%;
        left: 50%;
        transform: translateX(-50%);
        background: #374151;
        color: white;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 12px;
        white-space: nowrap;
        z-index: 1000;
    }

    @media print {
        .no-print { display: none !important; }
        body { background: white !important; }
        .bg-white { background: white !important; }
        .shadow-lg { box-shadow: none !important; }
        .rounded-xl { border-radius: 0 !important; }
        table { border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; }
    }
</style>

<script>
    // Initialisation des datepickers
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
    });

    // Auto-submit du formulaire de recherche lors de la saisie
    let searchTimeout;
    document.getElementById('search').addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            this.form.submit();
        }, 500);
    });
</script>
</body>

</html>
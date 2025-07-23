<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sauvegarde et Restauration | Univalid</title>
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
            text-decoration: none;
            display: inline-flex;
            align-items: center;
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

        .btn-warning {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
            box-shadow: 0 4px 16px rgba(245, 158, 11, 0.3);
        }

        .btn-warning:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(245, 158, 11, 0.4);
        }

        .btn-danger {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
            box-shadow: 0 4px 16px rgba(239, 68, 68, 0.3);
        }

        .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(239, 68, 68, 0.4);
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

        .input-field {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 12px 16px;
            transition: all 0.3s ease;
            width: 100%;
        }

        .input-field:focus {
            outline: none;
            border-color: #3457cb;
            box-shadow: 0 0 0 3px rgba(52, 87, 203, 0.1);
        }

        .notification {
            border-radius: 16px;
            padding: 16px 20px;
            margin-bottom: 24px;
            animation: fadeInDown 0.5s ease-out;
            transition: all 0.5s ease-in-out;
        }

        .notification.fade-out {
            opacity: 0;
            transform: translateY(-20px);
        }

        .notification.success {
            background: linear-gradient(135deg, rgba(54, 134, 90, 0.1) 0%, rgba(89, 191, 61, 0.1) 100%);
            border: 1px solid rgba(54, 134, 90, 0.2);
            color: #36865a;
        }

        .notification.error {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.1) 0%, rgba(220, 38, 38, 0.1) 100%);
            border: 1px solid rgba(239, 68, 68, 0.2);
            color: #dc2626;
        }

        .notification.info {
            background: linear-gradient(135deg, rgba(52, 87, 203, 0.1) 0%, rgba(59, 130, 246, 0.1) 100%);
            border: 1px solid rgba(52, 87, 203, 0.2);
            color: #3457cb;
        }

        .notification.warning {
            background: linear-gradient(135deg, rgba(245, 158, 11, 0.1) 0%, rgba(217, 119, 6, 0.1) 100%);
            border: 1px solid rgba(245, 158, 11, 0.2);
            color: #d97706;
        }

        .table-hover tbody tr {
            transition: all 0.2s ease;
        }

        .table-hover tbody tr:hover {
            background: rgba(52, 87, 203, 0.02);
            transform: scale(1.01);
        }

        .modal {
            backdrop-filter: blur(10px);
            background: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        .badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .badge.automatic {
            background: linear-gradient(135deg, #8b5cf6 0%, #a78bfa 100%);
            color: white;
        }

        .badge.manual {
            background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%);
            color: white;
        }
    </style>
</head>

<body class="bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Header Section -->
        <div class="header bg-white rounded-3xl p-8 lg:p-12 mb-8 shadow-xl relative overflow-hidden animate-fade-in-down">
            <div class="flex items-center gap-6 md:gap-8 flex-col md:flex-row text-center md:text-left">
                <div class="header-icon bg-gradient-to-br from-primary to-primary-light text-white w-20 h-20 md:w-24 md:h-24 rounded-2xl flex items-center justify-center text-4xl md:text-5xl shadow-lg">
                    <i class="fas fa-database"></i>
                </div>
                <div class="header-text">
                    <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 mb-2 tracking-tight">Sauvegarde & Restauration</h1>
                    <p class="text-lg text-gray-600 font-normal">Gestion sécurisée des sauvegardes de la base de données</p>
                </div>
            </div>
        </div>

        <!-- Notifications -->
        <?php if (isset($_GET['success'])): ?>
        <div id="success-notification" class="notification success">
            <div class="flex items-center">
                <i class="fas fa-check-circle text-2xl mr-4"></i>
                <div>
                    <strong class="font-bold">Succès !</strong>
                    <span class="block">La sauvegarde a été créée avec succès.</span>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <?php if (isset($_GET['restored'])): ?>
        <div id="restored-notification" class="notification info">
            <div class="flex items-center">
                <i class="fas fa-undo-alt text-2xl mr-4"></i>
                <div>
                    <strong class="font-bold">Restauration réussie !</strong>
                    <span class="block">La base de données a été restaurée avec succès.</span>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <?php if (isset($_GET['deleted'])): ?>
        <div id="deleted-notification" class="notification warning">
            <div class="flex items-center">
                <i class="fas fa-trash-alt text-2xl mr-4"></i>
                <div>
                    <strong class="font-bold">Suppression réussie !</strong>
                    <span class="block">La sauvegarde a été supprimée avec succès.</span>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <?php if (isset($_GET['error'])): ?>
        <div id="error-notification" class="notification error">
            <div class="flex items-center">
                <i class="fas fa-exclamation-triangle text-2xl mr-4"></i>
                <div>
                    <strong class="font-bold">Erreur !</strong>
                    <span class="block">
                        <?php 
                        switch ($_GET['error']) {
                            case 'docker_not_available':
                                echo 'Docker n\'est pas disponible. Le système tentera d\'utiliser une méthode alternative.';
                                break;
                            case 'container_not_running':
                                echo 'Le conteneur de base de données n\'est pas en cours d\'exécution. Le système tentera d\'utiliser une méthode alternative.';
                                break;
                            case 'backup_failed':
                                echo 'La sauvegarde a échoué. Vérifiez que la base de données est accessible et que les permissions sont correctes.';
                                break;
                            default:
                                echo 'Une erreur s\'est produite lors de l\'opération.';
                        }
                        ?>
                    </span>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Create Backup Section -->
        <div class="card mb-8 animate-scale-in">
            <div class="header-gradient px-8 py-6">
                <h2 class="text-2xl font-bold text-white flex items-center">
                    <i class="fas fa-plus-circle mr-3 text-2xl"></i>
                    Créer une Nouvelle Sauvegarde
                </h2>
            </div>
            <div class="p-8">
                <div class="flex items-center mb-6">
                    <div class="w-12 h-12 bg-secondary/10 text-secondary rounded-xl flex items-center justify-center text-2xl mr-4">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <p class="text-gray-600 text-lg">
                        Créez une sauvegarde manuelle de la base de données. Il est recommandé de le faire avant toute modification majeure du système.
                    </p>
                </div>
                
                <form id="createBackupForm" method="POST" action="?page=sauvegarde_restauration&action=create" class="space-y-6">
                    <div>
                        <label for="backup_name" class="block text-sm font-semibold text-gray-700 mb-3">
                            <i class="fas fa-tag mr-2 text-gray-500"></i>Nom de la sauvegarde (optionnel)
                        </label>
                        <input type="text" name="backup_name" id="backup_name" 
                               placeholder="Ex: avant_mise_a_jour_v2_0" 
                               class="input-field md:w-1/2">
                    </div>
                    <div>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-database mr-2"></i>Lancer la Sauvegarde Manuelle
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Existing Backups Section -->
        <div class="card animate-scale-in" style="animation-delay: 0.2s">
            <div class="header-gradient px-8 py-6">
                <h2 class="text-2xl font-bold text-white flex items-center">
                    <i class="fas fa-archive mr-3 text-2xl"></i>
                    Sauvegardes Existantes
                </h2>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full table-hover">
                    <thead class="bg-gray-50 text-gray-700 border-b border-gray-200">
                        <tr>
                            <th class="py-4 px-6 text-left text-xs font-bold uppercase tracking-wider">
                                📁 Nom du Fichier
                            </th>
                            <th class="py-4 px-6 text-left text-xs font-bold uppercase tracking-wider">
                                📊 Taille
                            </th>
                            <th class="py-4 px-6 text-left text-xs font-bold uppercase tracking-wider">
                                📅 Date de Création
                            </th>
                            <th class="py-4 px-6 text-left text-xs font-bold uppercase tracking-wider">
                                🏷️ Type
                            </th>
                            <th class="py-4 px-6 text-center text-xs font-bold uppercase tracking-wider">
                                🔧 Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php if (empty($backups)): ?>
                        <tr>
                            <td colspan="5" class="py-12 px-6 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                        <i class="fas fa-archive text-3xl text-gray-400"></i>
                                    </div>
                                    <p class="text-lg font-semibold text-gray-500 mb-2">Aucune sauvegarde disponible</p>
                                    <p class="text-sm text-gray-400">Créez votre première sauvegarde pour commencer.</p>
                                </div>
                            </td>
                        </tr>
                        <?php else: ?>
                        <?php foreach ($backups as $backup): ?>
                        <tr class="hover:bg-gray-50 transition-all duration-200">
                            <td class="py-4 px-6">
                                <div class="flex items-center">
                                    <i class="fas fa-file-archive mr-3 text-primary text-lg"></i>
                                    <span class="font-semibold text-primary"><?php echo htmlspecialchars($backup['filename']); ?></span>
                                </div>
                            </td>
                            <td class="py-4 px-6 text-sm font-medium text-gray-700">
                                <?php echo htmlspecialchars($backup['size']); ?>
                            </td>
                            <td class="py-4 px-6 text-sm text-gray-700">
                                <?php echo htmlspecialchars($backup['created_at']); ?>
                            </td>
                            <td class="py-4 px-6">
                                <span class="badge <?php echo $backup['type'] === 'Automatique' ? 'automatic' : 'manual'; ?>">
                                    <?php echo htmlspecialchars($backup['type']); ?>
                                </span>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <div class="flex justify-center space-x-3">
                                    <button type="button" 
                                            class="text-secondary hover:text-secondary-light transition-colors duration-200 font-semibold" 
                                            title="Restaurer"
                                            onclick="openRestoreModal('<?php echo htmlspecialchars($backup['filename']); ?>')">
                                        <i class="fas fa-undo-alt mr-1"></i> Restaurer
                                    </button>
                                    <a href="?page=sauvegarde_restauration&action=download&filename=<?php echo urlencode($backup['filename']); ?>"
                                       class="text-primary hover:text-primary-light transition-colors duration-200 font-semibold" 
                                       title="Télécharger">
                                        <i class="fas fa-download mr-1"></i> Télécharger
                                    </a>
                                    <button type="button" 
                                            class="text-red-600 hover:text-red-700 transition-colors duration-200 font-semibold" 
                                            title="Supprimer"
                                            onclick="openDeleteModal('<?php echo htmlspecialchars($backup['filename']); ?>')">
                                        <i class="fas fa-trash mr-1"></i> Supprimer
                                    </button>
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

    <!-- Modal de confirmation de suppression -->
    <div id="deleteModal" class="modal fixed inset-0 overflow-y-auto h-full w-full hidden z-50 flex items-center justify-center">
        <div class="modal-content relative mx-auto p-8 w-96 shadow-2xl">
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-6">
                    <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-4">Confirmer la suppression</h3>
                <div class="mb-6">
                    <p class="text-gray-600 mb-3">
                        Êtes-vous sûr de vouloir supprimer la sauvegarde :
                    </p>
                    <p class="font-semibold text-gray-900" id="deleteFileName"></p>
                    <div class="mt-4 p-3 bg-red-50 border border-red-200 rounded-xl">
                        <p class="text-sm text-red-700 font-semibold">
                            ⚠️ Cette action est irréversible
                        </p>
                    </div>
                </div>
                <form id="deleteForm" method="POST" action="?page=sauvegarde_restauration&action=delete">
                    <input type="hidden" name="filename" id="deleteFileInput">
                    <div class="flex justify-center space-x-4">
                        <button type="button" id="cancelDelete" class="btn btn-secondary">
                            <i class="fas fa-times mr-2"></i>Annuler
                        </button>
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash-alt mr-2"></i>Supprimer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal de confirmation de restauration -->
    <div id="restoreModal" class="modal fixed inset-0 overflow-y-auto h-full w-full hidden z-50 flex items-center justify-center">
        <div class="modal-content relative mx-auto p-8 w-96 shadow-2xl">
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-orange-100 mb-6">
                    <i class="fas fa-exclamation-triangle text-orange-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-4">Confirmer la restauration</h3>
                <div class="mb-6">
                    <p class="text-gray-600 mb-3">
                        Êtes-vous sûr de vouloir restaurer la base de données à partir de la sauvegarde :
                    </p>
                    <p class="font-semibold text-gray-900" id="restoreFileName"></p>
                    <div class="mt-4 p-4 bg-red-50 border border-red-200 rounded-xl">
                        <p class="text-sm text-red-700 font-bold mb-2">
                            ⚠️ ATTENTION : CETTE ACTION EST IRRÉVERSIBLE
                        </p>
                        <p class="text-xs text-red-600">
                            Toutes les données actuelles seront écrasées et remplacées par celles de la sauvegarde.
                        </p>
                    </div>
                </div>
                <form id="restoreForm" method="POST" action="?page=sauvegarde_restauration&action=restore">
                    <input type="hidden" name="filename" id="restoreFileInput">
                    <div class="flex justify-center space-x-4">
                        <button type="button" id="cancelRestore" class="btn btn-secondary">
                            <i class="fas fa-times mr-2"></i>Annuler
                        </button>
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-undo-alt mr-2"></i>Restaurer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Fonction pour faire disparaître les notifications après un délai
        function fadeOutNotification(elementId, delay = 4000) {
            const element = document.getElementById(elementId);
            if (element) {
                setTimeout(() => {
                    element.classList.add('fade-out');
                    setTimeout(() => {
                        element.remove();
                    }, 500);
                }, delay);
            }
        }

        // Fonction pour ouvrir la modal de suppression
        function openDeleteModal(filename) {
            document.getElementById('deleteFileName').textContent = filename;
            document.getElementById('deleteFileInput').value = filename;
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        // Fonction pour fermer la modal de suppression
        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
        }

        // Fonction pour ouvrir la modal de restauration
        function openRestoreModal(filename) {
            document.getElementById('restoreFileName').textContent = filename;
            document.getElementById('restoreFileInput').value = filename;
            document.getElementById('restoreModal').classList.remove('hidden');
        }

        // Fonction pour fermer la modal de restauration
        function closeRestoreModal() {
            document.getElementById('restoreModal').classList.add('hidden');
        }

        // Initialisation au chargement de la page
        document.addEventListener('DOMContentLoaded', function() {
            // Notifications avec auto-hide
            if (document.getElementById('success-notification')) {
                fadeOutNotification('success-notification', 4000);
            }
            if (document.getElementById('restored-notification')) {
                fadeOutNotification('restored-notification', 4000);
            }
            if (document.getElementById('deleted-notification')) {
                fadeOutNotification('deleted-notification', 4000);
            }
            if (document.getElementById('error-notification')) {
                fadeOutNotification('error-notification', 6000);
            }

            // Gestion des modals
            document.getElementById('cancelDelete').addEventListener('click', closeDeleteModal);
            document.getElementById('cancelRestore').addEventListener('click', closeRestoreModal);

            // Fermer les modals en cliquant à l'extérieur
            document.getElementById('deleteModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeDeleteModal();
                }
            });

            document.getElementById('restoreModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeRestoreModal();
                }
            });

            // Animation d'apparition des notifications
            const notifications = document.querySelectorAll('.notification');
            notifications.forEach((notification, index) => {
                notification.style.animationDelay = `${index * 0.1}s`;
            });

            // Animation des lignes du tableau
            const tableRows = document.querySelectorAll('tbody tr');
            tableRows.forEach((row, index) => {
                row.style.animationDelay = `${index * 0.05}s`;
                row.classList.add('animate-slide-in-right');
            });
        });
    </script>
</body>

</html>
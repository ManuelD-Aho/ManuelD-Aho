<?php
// VOTRE LOGIQUE PHP EST CONSERVÉE À L'IDENTIQUE
$infosDepot = isset($GLOBALS['infosDepot']) ? $GLOBALS['infosDepot'] : [];

$candidature_validee = false;
$message_candidature = '';

if (isset($_SESSION['num_etu'])) {
    $candidatures_etudiant = isset($GLOBALS['candidatures_etudiant']) ? $GLOBALS['candidatures_etudiant'] : [];
    
    foreach ($candidatures_etudiant as $candidature) {
        if ($candidature['statut_candidature'] === 'Validée') {
            $candidature_validee = true;
            break;
        }
    }
    
    if (!$candidature_validee) {
        $message_candidature = "Vous devez avoir une candidature validée pour accéder aux fonctionnalités de gestion des rapports.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Rapports</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }

        /* Le CSS des notifications est conservé tel quel car il est bien fait */
        .notification { position: relative; background: white; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); padding: 16px; min-width: 320px; border-left: 4px solid; transform: translateX(100%); opacity: 0; transition: all 0.3s ease-out; display: flex; align-items: flex-start; gap: 12px; }
        .notification.show { transform: translateX(0); opacity: 1; }
        .notification.hide { transform: translateX(100%); opacity: 0; }
        .notification.success { border-left-color: #10b981; }
        .notification.error { border-left-color: #ef4444; }
        .notification.info { border-left-color: #3b82f6; }
        .notification.warning { border-left-color: #f59e0b; }
        .notification-icon { flex-shrink: 0; width: 24px; height: 24px; }
        .notification-content { flex: 1; }
        .notification-title { font-weight: 600; font-size: 1rem; margin-bottom: 2px; color: #1f2937; }
        .notification-message { font-size: 0.875rem; color: #4b5563; }
        .notification-close { position: absolute; top: 8px; right: 8px; border: none; background: transparent; cursor: pointer; color: #9ca3af; }
        .notification-progress { position: absolute; bottom: 0; left: 0; height: 4px; background: rgba(0,0,0,0.1); width: 100%; }
        .notification.success .notification-progress { background: #10b981; }
        .notification.error .notification-progress { background: #ef4444; }
    </style>
</head>

<body class="bg-gray-50 text-gray-800">

    <div id="notificationContainer" class="fixed top-5 right-5 z-[100] space-y-3"></div>

    <div id="deleteModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black bg-opacity-50">
        <div class="bg-white rounded-lg p-6 max-w-md w-full mx-auto shadow-xl">
            <h3 class="text-xl font-semibold mb-2 text-gray-900">Confirmer la suppression</h3>
            <p class="text-gray-600 mb-6">Êtes-vous sûr de vouloir supprimer le rapport <strong id="rapportNom" class="text-gray-800"></strong> ? Cette action est irréversible.</p>
            <form method="POST" action="?page=gestion_rapports">
                <input type="hidden" name="action" value="supprimer_rapport">
                <input type="hidden" name="rapport_id" id="rapportIdToDelete">
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="fermerModalSuppression()" class="px-4 py-2 text-gray-700 bg-gray-100 hover:bg-gray-200 font-semibold rounded-md transition">Annuler</button>
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white font-semibold rounded-md hover:bg-red-700 transition">Supprimer</button>
                </div>
            </form>
        </div>
    </div>

    <div class="container max-w-6xl mx-auto px-4 py-12">
        <div class="text-left mb-10">
            <h1 class="text-3xl font-bold text-gray-900 mb-1">Gestion des Rapports</h1>
            <p class="text-md text-gray-600">Créez, suivez et consultez les retours sur vos rapports de master.</p>
        </div>

        <div class="bg-white border border-gray-200 rounded-lg shadow-sm mb-12">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-xl font-bold text-gray-800">Tableau de bord</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 divide-y md:divide-y-0 md:divide-x border-gray-200">
                <?php
                function render_action_card($icon, $title, $description, $link, $button_text, $is_enabled) {
                    echo '<div class="p-8 flex flex-col items-center text-center">';
                    echo '<div class="text-blue-600 mb-4">' . $icon . '</div>';
                    echo '<h3 class="text-lg font-bold text-gray-900 mb-2">' . $title . '</h3>';
                    echo '<p class="text-gray-600 mb-6 flex-grow">' . $description . '</p>';
                    if ($is_enabled) {
                        echo '<a href="' . $link . '" class="mt-auto w-full bg-blue-600 text-white font-semibold px-5 py-2.5 rounded-md hover:bg-blue-700 transition-colors duration-300">' . $button_text . '</a>';
                    } else {
                        echo '<button onclick="showCandidatureRequiredMessage()" disabled class="mt-auto w-full bg-gray-200 text-gray-500 font-semibold px-5 py-2.5 rounded-md cursor-not-allowed">' . $button_text . '</button>';
                    }
                    echo '</div>';
                }

                render_action_card('<i class="fa-solid fa-plus fa-2x"></i>', 'Créer un Rapport', 'Rédigez et soumettez un nouveau rapport via notre plateforme.', '?page=gestion_rapports&action=creer_rapport', 'Commencer', $candidature_validee);
                render_action_card('<i class="fa-solid fa-list-check fa-2x"></i>', 'Suivre l\'Avancée', 'Surveillez l\'état de vos soumissions en temps réel.', '?page=gestion_rapports&action=suivi_rapport', 'Consulter', $candidature_validee);
                render_action_card('<i class="fa-solid fa-comments fa-2x"></i>', 'Consulter les Retours', 'Accédez aux commentaires des évaluateurs pour améliorer votre travail.', '?page=gestion_rapports&action=commentaire_rapport', 'Voir les retours', $candidature_validee);
                ?>
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
            <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                <h2 class="text-xl font-bold text-gray-800">Mes Rapports Récents</h2>
                <span class="bg-blue-100 text-blue-800 text-sm font-medium px-3 py-1 rounded-full">
                    <?= count($infosDepot) ?> rapport(s)
                </span>
            </div>
            
            <div class="divide-y divide-gray-200">
                <?php if (isset($rapportsRecents) && !empty($rapportsRecents)): ?>
                    <?php foreach ($rapportsRecents as $rapport): ?>
                    <div class="p-6 flex flex-col md:flex-row justify-between items-start md:items-center hover:bg-gray-50 transition-colors">
                        <div class="mb-4 md:mb-0">
                            <h4 class="text-md font-semibold text-gray-800"><?= htmlspecialchars($rapport->nom_rapport) ?></h4>
                            <p class="text-sm text-gray-500">Créé le <?= date('d/m/Y', strtotime($rapport->date_rapport)) ?></p>
                        </div>
                        <div class="flex items-center space-x-2 flex-shrink-0">
                            <?php
                            $infoDepot = $infosDepot[$rapport->id_rapport] ?? ['peutDeposer' => true, 'messageDepot' => '', 'dejaDepose' => false];
                            ?>

                            <?php if ($infoDepot['dejaDepose']): ?>
                                 <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fa-solid fa-check-circle mr-1.5"></i> Déposé
                                </span>
                                <a href="?page=gestion_rapports&action=creer_rapport&edit=<?= $rapport->id_rapport ?>" class="text-gray-500 hover:text-blue-600 p-2 rounded-md"><i class="fa-solid fa-eye"></i></a>
                            <?php else: ?>
                                <?php if ($infoDepot['peutDeposer']): ?>
                                <form method="POST" action="?page=gestion_rapports">
                                    <input type="hidden" name="id_rapport" value="<?= $rapport->id_rapport ?>">
                                    <input type="hidden" name="action" value="deposer_rapport">
                                    <button type="submit" title="Déposer le rapport" class="bg-blue-100 text-blue-700 hover:bg-blue-200 font-semibold px-3 py-1.5 rounded-md text-sm">Déposer</button>
                                </form>
                                <?php else: ?>
                                    <span title="<?= htmlspecialchars($infoDepot['messageDepot']) ?>" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 cursor-help">
                                        <i class="fa-solid fa-clock mr-1.5"></i> Évaluation en cours
                                    </span>
                                <?php endif; ?>
                                <a href="?page=gestion_rapports&action=creer_rapport&edit=<?= $rapport->id_rapport ?>" class="text-gray-500 hover:text-blue-600 p-2 rounded-md" title="Modifier"><i class="fa-solid fa-pen-to-square"></i></a>
                                <button onclick="confirmerSuppression(<?= $rapport->id_rapport ?>, '<?= htmlspecialchars(addslashes($rapport->nom_rapport)) ?>')" class="text-gray-500 hover:text-red-600 p-2 rounded-md" title="Supprimer"><i class="fa-solid fa-trash-can"></i></button>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center p-12">
                        <i class="fa-solid fa-folder-open fa-3x text-gray-300 mb-4"></i>
                        <p class="text-gray-500 text-lg mb-4">Vous n'avez aucun rapport pour le moment.</p>
                        <?php if ($candidature_validee): ?>
                        <a href="?page=gestion_rapports&action=creer_rapport" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg inline-flex items-center">
                            <i class="fas fa-plus mr-2"></i> Créer mon premier rapport
                        </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
             <?php if (isset($rapportsRecents) && count($rapportsRecents) > 0): ?>
            <div class="p-4 bg-gray-50 text-center border-t border-gray-200">
                <a href="?page=gestion_rapports&action=suivi_rapport" class="text-blue-600 hover:underline font-semibold text-sm">Voir tous les rapports &rarr;</a>
            </div>
            <?php endif; ?>
        </div>

    </div>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        <?php if (isset($_GET['message'])): ?>
        <?php if ($_GET['message'] === 'depot_ok'): ?>
        showNotification('success', 'Le rapport a bien été déposé.');
        <?php elseif ($_GET['message'] === 'depot_fail'): ?>
        showNotification('error', 'Impossible de déposer le rapport.');
        <?php elseif ($_GET['message'] === 'depot_en_cours'): ?>
        showNotification('warning', 'Un autre rapport est déjà en cours d\'évaluation.');
        <?php elseif ($_GET['message'] === 'suppression_ok'): ?>
        showNotification('success', 'Le rapport a été supprimé avec succès.');
        <?php endif; ?>
        <?php endif; ?>
    });

    function voirRapport(rapportId) {
        window.location.href = `?page=gestion_rapports&action=creer_rapport&edit=${rapportId}`;
    }

    function showNotification(type, message, title = null) {
        const container = document.getElementById('notificationContainer');
        const notif = document.createElement('div');
        notif.className = `notification ${type}`;
        
        const typeTitles = { success: 'Succès', error: 'Erreur', info: 'Information', warning: 'Attention' };
        const icons = {
            success: '<i class="fa-solid fa-check-circle fa-lg text-green-500"></i>',
            error: '<i class="fa-solid fa-times-circle fa-lg text-red-500"></i>',
            info: '<i class="fa-solid fa-info-circle fa-lg text-blue-500"></i>',
            warning: '<i class="fa-solid fa-exclamation-triangle fa-lg text-yellow-500"></i>'
        };

        notif.innerHTML = `
            <div class="notification-icon">${icons[type]}</div>
            <div class="notification-content">
                <h4 class="notification-title">${title || typeTitles[type]}</h4>
                <p class="notification-message">${message}</p>
            </div>
            <button class="notification-close"><i class="fa-solid fa-times"></i></button>
            <div class="notification-progress"></div>
        `;
        container.appendChild(notif);

        const closeBtn = notif.querySelector('.notification-close');
        const progressBar = notif.querySelector('.notification-progress');
        
        let timeoutId = setTimeout(() => hide(notif), 5000);
        
        closeBtn.onclick = () => {
            clearTimeout(timeoutId);
            hide(notif);
        };

        requestAnimationFrame(() => {
            progressBar.style.transition = 'width 5s linear';
            progressBar.style.width = '0%';
        });
        setTimeout(() => notif.classList.add('show'), 10);

        function hide(element) {
            element.classList.remove('show');
            element.classList.add('hide');
            setTimeout(() => element.remove(), 300);
        }
    }

    function showCandidatureRequiredMessage() {
        showNotification('error', "Vous devez avoir une candidature validée pour accéder à cette fonctionnalité.");
    }

    function confirmerSuppression(rapportId, rapportNom) {
        document.getElementById('rapportIdToDelete').value = rapportId;
        document.getElementById('rapportNom').textContent = `"${rapportNom}"`;
        document.getElementById('deleteModal').classList.remove('hidden');
    }

    function fermerModalSuppression() {
        document.getElementById('deleteModal').classList.add('hidden');
    }
    </script>
</body>
</html>
<?php

// VOTRE LOGIQUE PHP EST CONSERVÉE À L'IDENTIQUE
$stage_info = isset($GLOBALS['stage_info']) ? $GLOBALS['stage_info'] : [];
$compte_rendu = isset($GLOBALS['compte_rendu']) ? $GLOBALS['compte_rendu'] : [];
$has_candidature = isset($GLOBALS['has_candidature']) ? $GLOBALS['has_candidature'] : false;
$candidature = isset($GLOBALS['candidature']) ? $GLOBALS['candidature'] : null;
$candidatures_etudiant = isset($GLOBALS['candidatures_etudiant']) ? $GLOBALS['candidatures_etudiant'] : [];

// Vérifier s'il existe au moins une candidature en attente ou validée
$disableCandidature = empty($stage_info);
$candidatureStatus = null; // Pour stocker le statut de la candidature bloquante

foreach ($candidatures_etudiant as $cand) {
    if (in_array($cand['statut_candidature'], ['En attente', 'Validée'])) {
        $disableCandidature = true;
        $candidatureStatus = $cand['statut_candidature'];
        break;
    }
}

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Candidature à la soutenance</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        body {
            font-family: 'Inter', sans-serif;
        }
        .modal-backdrop {
            background-color: rgba(0, 0, 0, 0.5);
            transition: opacity 0.3s ease-in-out;
        }
        .modal-content {
            transition: transform 0.3s ease-in-out, opacity 0.3s ease-in-out;
        }
        .modal.hidden .modal-backdrop {
            opacity: 0;
        }
        .modal.hidden .modal-content {
            transform: scale(0.95);
            opacity: 0;
        }
    </style>
</head>

<body class="bg-gray-100">

    <div id="notification-area" class="fixed top-5 right-5 z-[100] w-full max-w-sm space-y-3">
        <?php if (isset($_SESSION['success'])): ?>
        <div class="notification bg-green-100 border border-green-200 text-green-800 p-4 rounded-lg shadow-lg flex items-start">
            <p class="text-sm"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></p>
        </div>
        <?php endif; ?>
        <?php if (isset($_SESSION['error'])): ?>
        <div class="notification bg-red-100 border border-red-200 text-red-800 p-4 rounded-lg shadow-lg flex items-start">
            <p class="text-sm"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></p>
        </div>
        <?php endif; ?>
        <div id="warningMessage" class="notification bg-yellow-100 border border-yellow-200 text-yellow-800 p-4 rounded-lg shadow-lg items-start hidden">
            <p id="warningMessageText" class="text-sm"></p>
        </div>
    </div>
    
    <div class="container max-w-4xl mx-auto px-4 py-16">

        <div class="text-center mb-10">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Candidature à la soutenance</h1>
            <p class="text-md text-gray-600">Suivez les étapes ci-dessous pour soumettre votre dossier.</p>
        </div>

        <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">

            <div class="p-6 md:p-8">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                    <div>
                        <div class="flex items-center mb-1">
                            <span class="flex items-center justify-center w-8 h-8 rounded-full bg-gray-200 text-gray-700 font-bold text-sm mr-3">1</span>
                            <h2 class="text-lg font-bold text-gray-900">Informations du stage</h2>
                        </div>
                        <p class="text-gray-600 ml-11 md:ml-0">Cette première étape est requise pour débloquer la suite du processus.</p>
                    </div>
                    <div class="mt-4 md:mt-0 md:ml-4 flex-shrink-0">
                        <button onclick="openStageInfoModal()" class="w-full md:w-auto bg-blue-600 text-white font-semibold px-5 py-2.5 rounded-md hover:bg-blue-700 transition-colors duration-300">
                            <?php echo empty($stage_info) ? 'Remplir les informations' : 'Mettre à jour'; ?>
                        </button>
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-200 p-6 md:p-8">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                    <div>
                        <div class="flex items-center mb-1">
                            <span class="flex items-center justify-center w-8 h-8 rounded-full <?php echo empty($stage_info) ? 'bg-gray-100 text-gray-400' : 'bg-gray-200 text-gray-700'; ?> font-bold text-sm mr-3">2</span>
                            <h2 class="text-lg font-bold <?php echo empty($stage_info) ? 'text-gray-400' : 'text-gray-900'; ?>">Demande de candidature</h2>
                            <?php if ($candidatureStatus): ?>
                                <span class="ml-3 text-xs font-semibold py-0.5 px-2.5 rounded-full <?php echo $candidatureStatus === 'Validée' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'; ?>">
                                    <?php echo htmlspecialchars($candidatureStatus); ?>
                                </span>
                            <?php endif; ?>
                        </div>
                         <p class="<?php echo empty($stage_info) ? 'text-gray-400' : 'text-gray-600'; ?> ml-11 md:ml-0">Soumettez votre dossier à l'administration pour validation.</p>
                    </div>
                     <div class="mt-4 md:mt-0 md:ml-4 flex-shrink-0">
                        <?php
                            $onclick_cand = empty($stage_info)
                                ? 'showWarning("Veuillez d\'abord remplir les informations de stage à l\'étape 1."); return false;'
                                : ($disableCandidature
                                    ? 'showWarning("Vous avez déjà une candidature en attente ou validée."); return false;'
                                    : 'openConfirmationModal()');
                            $btnClass_cand = $disableCandidature || empty($stage_info)
                                ? 'bg-gray-200 text-gray-500 cursor-not-allowed'
                                : 'bg-blue-600 hover:bg-blue-700';
                        ?>
                        <button onclick="<?php echo $onclick_cand; ?>" class="w-full md:w-auto <?php echo $btnClass_cand; ?> text-white font-semibold px-5 py-2.5 rounded-md transition-colors duration-300">
                           Faire une demande
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>


    <div id="confirmationModal" class="modal fixed inset-0 z-50 hidden items-center justify-center p-4">
        <div class="modal-backdrop fixed inset-0" onclick="closeConfirmationModal()"></div>
        <div class="modal-content bg-white rounded-lg p-6 max-w-md w-full mx-auto shadow-xl z-10">
            <h3 class="text-xl font-semibold mb-2 text-gray-900">Confirmer votre demande</h3>
            <p class="text-gray-600 mb-6">Êtes-vous sûr de vouloir soumettre votre demande de candidature ? Cette action ne pourra pas être annulée.</p>
            <form method="POST" action="?page=candidature_soutenance&action=demande_candidature">
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeConfirmationModal()" class="px-4 py-2 text-gray-700 bg-gray-100 hover:bg-gray-200 font-semibold rounded-md transition">Annuler</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-700 transition">Confirmer</button>
                </div>
            </form>
        </div>
    </div>
    
    <div id="stageInfoModal" class="modal fixed inset-0 z-50 hidden items-center justify-center p-4">
        <div class="modal-backdrop fixed inset-0" onclick="closeStageInfoModal()"></div>
        <div class="modal-content bg-white rounded-lg max-w-3xl w-full z-10 flex flex-col max-h-[90vh]">
            <div class="p-4 border-b border-gray-200">
                 <h3 class="text-xl font-semibold text-gray-900">Informations du stage</h3>
            </div>
            <form id="stageInfoForm" class="p-6 space-y-4 overflow-y-auto" method="POST" action="?page=candidature_soutenance&action=info_stage">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label for="entreprise" class="block text-sm font-medium text-gray-700">Entreprise</label>
                        <input type="text" name="entreprise" required value="<?php echo isset($stage_info['nom_entreprise']) ? htmlspecialchars($stage_info['nom_entreprise']) : ''; ?>" class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                     <div class="md:col-span-2">
                        <label for="sujet" class="block text-sm font-medium text-gray-700">Sujet du stage</label>
                        <input type="text" name="sujet" required value="<?php echo isset($stage_info['sujet_stage']) ? htmlspecialchars($stage_info['sujet_stage']) : ''; ?>" class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                     <div>
                        <label for="date_debut" class="block text-sm font-medium text-gray-700">Date de début</label>
                        <input type="date" name="date_debut" required value="<?php echo isset($stage_info['date_debut_stage']) ? htmlspecialchars($stage_info['date_debut_stage']) : ''; ?>" class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label for="date_fin" class="block text-sm font-medium text-gray-700">Date de fin</label>
                        <input type="date" name="date_fin" required value="<?php echo isset($stage_info['date_fin_stage']) ? htmlspecialchars($stage_info['date_fin_stage']) : ''; ?>" class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                     <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700">Description du stage</label>
                        <textarea name="description" required rows="4" class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"><?php echo isset($stage_info['description_stage']) ? htmlspecialchars($stage_info['description_stage']) : ''; ?></textarea>
                    </div>
                    <div class="md:col-span-2">
                        <label for="encadrant" class="block text-sm font-medium text-gray-700">Nom de l'encadrant</label>
                        <input type="text" name="encadrant" required value="<?php echo isset($stage_info['encadrant_entreprise']) ? htmlspecialchars($stage_info['encadrant_entreprise']) : ''; ?>" class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                         <label for="email_encadrant" class="block text-sm font-medium text-gray-700">Email de l'encadrant</label>
                        <input type="email" name="email_encadrant" required value="<?php echo isset($stage_info['email_encadrant']) ? htmlspecialchars($stage_info['email_encadrant']) : ''; ?>" class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                     <div>
                        <label for="telephone_encadrant" class="block text-sm font-medium text-gray-700">Téléphone de l'encadrant</label>
                        <input type="tel" name="telephone_encadrant" required value="<?php echo isset($stage_info['telephone_encadrant']) ? htmlspecialchars($stage_info['telephone_encadrant']) : ''; ?>" class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>
            </form>
             <div class="p-4 border-t border-gray-200 bg-gray-50 flex justify-end space-x-3">
                <button type="button" onclick="closeStageInfoModal()" class="px-5 py-2 text-gray-800 bg-white border border-gray-300 hover:bg-gray-50 font-semibold rounded-md transition-colors">
                    Annuler
                </button>
                <button type="submit" form="stageInfoForm" name="btn_enregistrer" value="1" class="px-5 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 font-semibold transition-colors flex items-center">
                    Enregistrer
                </button>
            </div>
        </div>
    </div>


    <script>
    function openModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }
    }

    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = '';
        }
    }
    
    const openConfirmationModal = () => openModal('confirmationModal');
    const closeConfirmationModal = () => closeModal('confirmationModal');
    const openStageInfoModal = () => openModal('stageInfoModal');
    const closeStageInfoModal = () => closeModal('stageInfoModal');

    function showWarning(message) {
        const warningBox = document.getElementById('warningMessage');
        const warningText = document.getElementById('warningMessageText');
        
        warningText.textContent = message;
        warningBox.classList.remove('hidden');
        warningBox.classList.add('flex');

        setTimeout(() => {
            warningBox.style.transition = 'opacity 0.5s ease';
            warningBox.style.opacity = '0';
            setTimeout(() => {
                warningBox.classList.add('hidden');
                warningBox.style.opacity = '1'; 
            }, 500);
        }, 5000);
    }

    document.addEventListener('DOMContentLoaded', function() {
        const notifications = document.querySelectorAll('#notification-area .notification:not(#warningMessage)');
        notifications.forEach(function(notification) {
            setTimeout(function() {
                notification.style.transition = 'opacity 0.5s ease';
                notification.style.opacity = '0';
                setTimeout(() => notification.remove(), 500);
            }, 5000);
        });
    });
    </script>
</body>

</html>
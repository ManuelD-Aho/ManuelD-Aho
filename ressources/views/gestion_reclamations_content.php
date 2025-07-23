<?php
// Supposons que votre contrôleur prépare ce tableau.
// Cette partie est un exemple pour que le code soit fonctionnel.
// VOUS N'AVEZ PAS BESOIN DE CHANGER VOTRE LOGIQUE PHP EXISTANTE.
$cardReclamation = isset($GLOBALS['cardReclamation']) ? $GLOBALS['cardReclamation'] : [
    [
        'bg_color' => 'bg-green-500', // ignoré dans le nouveau style
        'icon' => 'fas fa-plus-circle',
        'text_color' => 'text-white', // ignoré
        'title' => 'Soumettre une nouvelle réclamation',
        'description' => 'Un problème ou une question ? Soumettez votre réclamation ici et notre équipe vous répondra dans les plus brefs délais.',
        'link' => '?page=reclamations&action=creer',
        'title_link' => 'Créer une réclamation'
    ],
    [
        'bg_color' => 'bg-blue-500', // ignoré
        'icon' => 'fas fa-history',
        'text_color' => 'text-white', // ignoré
        'title' => 'Consulter l\'historique de mes réclamations',
        'description' => 'Suivez le statut de vos réclamations passées et consultez les réponses apportées par l\'administration.',
        'link' => '?page=reclamations&action=historique',
        'title_link' => 'Voir mon historique'
    ]
];
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Réclamations</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>

<body class="bg-gray-100">

    <div id="notification-container" class="fixed top-5 right-5 z-[100] w-full max-w-sm">
        <?php if (isset($_SESSION['message'])): ?>
            <div id="session-message" data-type="<?= htmlspecialchars($_SESSION['message']['type']) ?>" class="hidden">
                <?= htmlspecialchars($_SESSION['message']['text']) ?>
            </div>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>
    </div>


    <div class="container mx-auto py-16 px-4">
        <div class="max-w-4xl mx-auto">
            <div class="text-center mb-10">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Portail des Réclamations</h1>
                <p class="text-md text-gray-600">Un espace dédié pour soumettre et suivre vos demandes.</p>
            </div>

            <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                <div class="divide-y divide-gray-200">
                    
                    <?php foreach ($cardReclamation as $card): ?>
                    <div class="p-8 hover:bg-gray-50 transition-colors duration-200">
                        <div class="flex flex-col md:flex-row items-start md:items-center justify-between">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-12 h-12 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mr-5">
                                    <?php if (!empty($card['icon'])): ?>
                                        <i class="<?php echo htmlspecialchars($card['icon']); ?> text-xl"></i>
                                    <?php endif ?>
                                </div>
                                <div>
                                    <h2 class="text-lg font-bold text-gray-900"><?php echo htmlspecialchars($card['title']); ?></h2>
                                    <p class="text-gray-600 mt-1"><?php echo htmlspecialchars($card['description']); ?></p>
                                </div>
                            </div>

                            <div class="mt-5 md:mt-0 md:ml-6 flex-shrink-0">
                                <a href="<?php echo htmlspecialchars($card['link']); ?>" class="inline-block w-full text-center md:w-auto bg-blue-600 text-white font-semibold px-5 py-2.5 rounded-md hover:bg-blue-700 transition-colors duration-300">
                                    <?php echo htmlspecialchars($card['title_link']); ?>
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>

                </div>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const sessionMessageDiv = document.getElementById('session-message');
        if (sessionMessageDiv) {
            const message = sessionMessageDiv.textContent;
            const type = sessionMessageDiv.dataset.type;
            showNotification(type, message);
        }
    });

    function showNotification(type, message) {
        const container = document.getElementById('notification-container');
        if (!container) return;

        const notif = document.createElement('div');
        notif.className = `p-4 rounded-lg shadow-lg text-sm mb-3 border-l-4 ${type === 'success' ? 'bg-green-100 border-green-500 text-green-800' : 'bg-red-100 border-red-500 text-red-800'}`;
        
        const title = type === 'success' ? 'Succès !' : 'Erreur !';
        notif.innerHTML = `<strong class="font-bold">${title}</strong> <span>${message}</span>`;
        
        notif.style.opacity = 0;
        notif.style.transition = 'opacity 0.5s ease-in-out';
        
        container.appendChild(notif);
        
        // Fade in
        setTimeout(() => {
            notif.style.opacity = 1;
        }, 10);
        
        // Fade out and remove
        setTimeout(() => {
            notif.style.opacity = 0;
            setTimeout(() => {
                notif.remove();
            }, 500);
        }, 5000);
    }
    </script>
</body>
</html>
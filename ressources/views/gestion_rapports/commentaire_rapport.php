<?php
// THIS IS EXAMPLE DATA to make the page render.
// YOUR PHP LOGIC for all variables is preserved.
$statistiquesCompteRendu = $GLOBALS['statistiquesCompteRendu'] ?? ['total' => 0, 'semaine' => 0, 'mois' => 0];
$rapports = $GLOBALS['rapports'] ?? [];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compte Rendu des Rapports</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>

<body class="bg-gray-100">
    <div class="container max-w-7xl mx-auto px-4 py-12">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Compte Rendu des Rapports</h1>
            <p class="text-md text-gray-600">Consultez et évaluez les rapports soumis par les étudiants.</p>
        </div>

        <?php if (isset($statistiquesCompteRendu)): ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 mb-8">
            <?php
            function render_stat_card($label, $value, $icon, $color) {
                echo "<div class='bg-white border border-gray-200 rounded-lg p-5'><div class='flex items-center justify-between'><div><p class='text-gray-500 text-sm font-medium'>$label</p><h3 class='text-2xl font-bold text-gray-800'>$value</h3></div><div class='p-3 rounded-full bg-$color-100'><i class='fa-solid $icon text-xl text-$color-600'></i></div></div></div>";
            }
            render_stat_card("Total Rapports", $statistiquesCompteRendu['total'] ?? 0, "fa-file-alt", "blue");
            render_stat_card("Soumis cette semaine", $statistiquesCompteRendu['semaine'] ?? 0, "fa-calendar-week", "green");
            render_stat_card("Soumis ce mois", $statistiquesCompteRendu['mois'] ?? 0, "fa-calendar-day", "yellow");
            ?>
        </div>
        <?php endif; ?>

        <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
            <div class="p-4 bg-gray-50 border-b border-gray-200">
                <form method="GET">
                    <input type="hidden" name="page" value="gestion_rapports">
                    <input type="hidden" name="action" value="commentaire_rapport">
                    <div class="flex flex-col sm:flex-row items-center gap-4">
                        <input type="text" name="search" placeholder="Rechercher par nom, thème..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>" class="flex-grow w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <select name="statut" class="w-full sm:w-auto border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Tous les statuts</option>
                            <option value="en_attente" <?= (isset($_GET['statut']) && $_GET['statut'] === 'en_attente') ? 'selected' : '' ?>>En attente</option>
                            <option value="en_cours" <?= (isset($_GET['statut']) && $_GET['statut'] === 'en_cours') ? 'selected' : '' ?>>En cours</option>
                            <option value="valider" <?= (isset($_GET['statut']) && $_GET['statut'] === 'valider') ? 'selected' : '' ?>>Validé</option>
                            <option value="rejeter" <?= (isset($_GET['statut']) && $_GET['statut'] === 'rejeter') ? 'selected' : '' ?>>Rejeté</option>
                        </select>
                        <button type="submit" class="w-full sm:w-auto bg-blue-600 text-white font-semibold px-4 py-2 rounded-md hover:bg-blue-700 transition">Filtrer</button>
                        <?php if (!empty($_GET['statut']) || !empty($_GET['search'])): ?>
                            <a href="?page=gestion_rapports&action=commentaire_rapport" class="w-full sm:w-auto text-center bg-gray-200 text-gray-700 font-semibold px-4 py-2 rounded-md hover:bg-gray-300 transition">Reset</a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>

            <div class="divide-y divide-gray-200">
                <?php if (isset($rapports) && !empty($rapports)): foreach ($rapports as $rapport): ?>
                <div class="p-6 hover:bg-gray-50 transition-colors">
                    <div class="flex flex-col sm:flex-row justify-between sm:items-start gap-4">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900"><?= htmlspecialchars($rapport['nom_rapport']) ?></h3>
                            <p class="text-sm text-gray-600 mt-1">Étudiant: <span class="font-medium text-gray-700"><?= htmlspecialchars($rapport['prenom_etu'] . ' ' . $rapport['nom_etu']) ?></span></p>
                            <p class="text-sm text-gray-500">Soumis le: <?= date('d/m/Y', strtotime($rapport['date_rapport'])) ?></p>
                        </div>
                        <div class="flex-shrink-0 flex flex-col sm:items-end gap-2">
                             <?php
                                $status_map = [
                                    'en_attente' => ['class' => 'bg-yellow-100 text-yellow-800', 'text' => 'En attente'],
                                    'en_cours' => ['class' => 'bg-blue-100 text-blue-800', 'text' => 'En cours'],
                                    'valider' => ['class' => 'bg-green-100 text-green-800', 'text' => 'Validé'],
                                    'rejeter' => ['class' => 'bg-red-100 text-red-800', 'text' => 'Rejeté'],
                                ];
                                $status_info = $status_map[$rapport['statut_rapport']] ?? ['class' => 'bg-gray-100 text-gray-800', 'text' => 'Inconnu'];
                            ?>
                            <span class="px-2.5 py-1 text-xs font-medium rounded-full <?= $status_info['class'] ?>"><?= $status_info['text'] ?></span>
                            <button onclick="voirDetailsRapport(<?= $rapport['id_rapport'] ?>)" class="text-sm text-blue-600 hover:underline font-semibold mt-1">
                                Voir & Commenter
                            </button>
                        </div>
                    </div>
                </div>
                <?php endforeach; else: ?>
                <div class="text-center p-12">
                    <i class="fa-solid fa-folder-open fa-3x text-gray-300 mb-4"></i>
                    <p class="text-lg text-gray-500">Aucun rapport à afficher.</p>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <div id="detailsModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black bg-opacity-60">
            <div class="bg-white rounded-lg max-w-4xl w-full shadow-xl flex flex-col max-h-[90vh]">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h2 class="text-xl font-bold text-gray-900">Détails et Compte Rendu</h2>
                    <button onclick="fermerModalDetails()" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
                </div>
                <div id="modalContent" class="p-6 overflow-y-auto">
                    </div>
            </div>
        </div>
    </div>

    <script>
    // VOTRE JAVASCRIPT POUR LA MODALE EST CONSERVÉ À L'IDENTIQUE
    function voirDetailsRapport(rapportId) {
        const modal = document.getElementById('detailsModal');
        const modalContent = document.getElementById('modalContent');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        modalContent.innerHTML = `<div class="text-center p-8"><i class="fas fa-spinner fa-spin text-blue-500 text-3xl"></i><p class="mt-2 text-gray-600">Chargement...</p></div>`;

        fetch(`?page=gestion_rapports&action=get_commentaires&id=${rapportId}`)
            .then(response => response.ok ? response.text() : Promise.reject('Network response was not ok.'))
            .then(html => modalContent.innerHTML = html)
            .catch(error => {
                console.error('Fetch Error:', error);
                modalContent.innerHTML = `<div class="text-center p-8 text-red-600"><p>Erreur lors du chargement des détails.</p></div>`;
            });
    }

    function fermerModalDetails() {
        const modal = document.getElementById('detailsModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    document.getElementById('detailsModal').addEventListener('click', (e) => {
        if (e.target === document.getElementById('detailsModal')) {
            fermerModalDetails();
        }
    });
    </script>
</body>
</html>
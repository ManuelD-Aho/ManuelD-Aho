<?php
// THIS IS EXAMPLE DATA to make the page render.
// YOUR PHP LOGIC for session messages, filters, stats, claims, and pagination IS PRESERVED.
$_SESSION['message'] = $_SESSION['message'] ?? null;
$statistiques = $GLOBALS['statistiques'] ?? ['total' => 0, 'en_attente' => 0, 'resolue' => 0, 'rejetee' => 0];
$reclamations = $GLOBALS['reclamations'] ?? [];
$totalPages = $GLOBALS['totalPages'] ?? 1;
$page = $GLOBALS['page'] ?? 1;
$totalReclamations = $GLOBALS['totalReclamations'] ?? 0;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suivi des Réclamations</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-100">
    <div class="container max-w-7xl mx-auto px-4 py-12">
        <div class="flex flex-col sm:flex-row justify-between sm:items-center mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Suivi des Réclamations</h1>
                <p class="text-md text-gray-600">Consultez, filtrez et gérez l'historique de vos demandes.</p>
            </div>
            <a href="?page=reclamations&action=creer" class="inline-flex items-center justify-center w-full sm:w-auto bg-blue-600 text-white font-semibold px-5 py-2.5 rounded-md hover:bg-blue-700 transition-colors">
                <i class="fa-solid fa-plus mr-2"></i>Nouvelle réclamation
            </a>
        </div>
        
        <?php if (isset($_SESSION['message'])): ?>
        <div class="mb-6 p-4 rounded-md text-sm <?= $_SESSION['message']['type'] === 'success' ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700'; ?>">
            <?= htmlspecialchars($_SESSION['message']['text']); ?>
        </div>
        <?php unset($_SESSION['message']); endif; ?>

        <div class="bg-white border border-gray-200 rounded-lg shadow-sm mb-8">
            
        </div>

        <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
            <div class="p-6 border-t border-gray-200 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                <?php
                function render_stat_card($label, $value, $icon, $color) {
                    echo "<div class='bg-gray-50 p-4 rounded-lg'><div class='flex items-center justify-between'><div><p class='text-gray-500 text-sm'>$label</p><h3 class='text-2xl font-bold'>$value</h3></div><div class='p-3 rounded-full bg-$color-100'><i class='fa-solid $icon text-$color-600'></i></div></div></div>";
                }
                render_stat_card("Total", $statistiques['total'], "fa-list-ol", "blue");
                render_stat_card("En attente", $statistiques['en_attente'], "fa-clock", "yellow");
                render_stat_card("Résolues", $statistiques['resolue'], "fa-check-circle", "green");
                render_stat_card("Rejetées", $statistiques['rejetee'], "fa-times-circle", "red");
                ?>
            </div>
            <div class="p-6">
                <h2 class="text-lg font-bold text-gray-800 mb-4">Filtrer les résultats</h2>
                <form method="GET">
                    <input type="hidden" name="page" value="reclamations">
                    <input type="hidden" name="action" value="historique">
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                            <select id="status" name="status" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="all" <?= (!isset($_GET['status']) || $_GET['status'] === 'all') ? 'selected' : ''; ?>>Tous</option>
                                <option value="en_attente" <?= (isset($_GET['status']) && $_GET['status'] === 'en_attente') ? 'selected' : ''; ?>>En attente</option>
                                <option value="resolue" <?= (isset($_GET['status']) && $_GET['status'] === 'resolue') ? 'selected' : ''; ?>>Résolue</option>
                                <option value="rejetee" <?= (isset($_GET['status']) && $_GET['status'] === 'rejetee') ? 'selected' : ''; ?>>Rejetée</option>
                            </select>
                        </div>
                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                            <select id="type" name="type" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="all" <?= (!isset($_GET['type']) || $_GET['type'] === 'all') ? 'selected' : ''; ?>>Tous</option>
                                <option value="TECH" <?= (isset($_GET['type']) && $_GET['type'] === 'TECH') ? 'selected' : ''; ?>>Technique</option>
                                <option value="ADMIN" <?= (isset($_GET['type']) && $_GET['type'] === 'ADMIN') ? 'selected' : ''; ?>>Administrative</option>
                            </select>
                        </div>
                        <div class="flex items-end space-x-2">
                            <button type="submit" class="w-full bg-blue-600 text-white font-semibold px-4 py-2 rounded-md hover:bg-blue-700 transition">Appliquer</button>
                            <a href="?page=reclamations&action=historique" class="w-full text-center bg-gray-200 text-gray-700 font-semibold px-4 py-2 rounded-md hover:bg-gray-300 transition">Reset</a>
                        </div>
                    </div>
                </form>
            </div>
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-bold text-gray-800">Historique des réclamations</h2>
            </div>

            <?php if (empty($reclamations)): ?>
                <div class="text-center p-12">
                    <i class="fa-solid fa-inbox fa-3x text-gray-300 mb-4"></i>
                    <p class="text-lg text-gray-500">Aucune réclamation ne correspond à vos critères.</p>
                </div>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Sujet</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($reclamations as $rec): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm font-medium text-gray-600">#<?= $rec['id_reclamation']; ?></td>
                                <td class="px-6 py-4 text-sm text-gray-800 font-semibold max-w-xs truncate" title="<?= htmlspecialchars($rec['titre_reclamation']); ?>"><?= htmlspecialchars($rec['titre_reclamation']); ?></td>
                                <td class="px-6 py-4 text-sm"><span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800"><?= htmlspecialchars($rec['type_reclamation']); ?></span></td>
                                <td class="px-6 py-4 text-sm text-gray-500"><?= date('d/m/Y', strtotime($rec['date_creation'])); ?></td>
                                <td class="px-6 py-4 text-sm">
                                    <?php
                                    $status_classes = ['En attente' => 'bg-yellow-100 text-yellow-800', 'Résolue' => 'bg-green-100 text-green-800', 'Rejetée' => 'bg-red-100 text-red-800'];
                                    $status_class = $status_classes[$rec['statut_reclamation']] ?? 'bg-gray-100 text-gray-800';
                                    ?>
                                    <span class="px-2 py-1 font-medium rounded-full text-xs <?= $status_class; ?>"><?= htmlspecialchars($rec['statut_reclamation']); ?></span>
                                </td>
                                <td class="px-6 py-4 text-sm font-medium">
                                    <button onclick="voirDetailsReclamation(<?= $rec['id_reclamation']; ?>)" class="text-blue-600 hover:text-blue-800 hover:underline">Voir les détails</button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php if (isset($totalPages) && $totalPages > 1): ?>
                <div class="px-6 py-4 border-t border-gray-200 flex flex-col sm:flex-row justify-between items-center gap-4">
                    <p class="text-sm text-gray-600">Page <span class="font-semibold"><?= $page; ?></span> sur <span class="font-semibold"><?= $totalPages; ?></span></p>
                    <div class="inline-flex rounded-md shadow-sm">
                         <a href="?page=reclamations&action=historique&p=<?= $page - 1; ?><?= isset($_GET['status']) ? '&status='.urlencode($_GET['status']) : ''; ?><?= isset($_GET['type']) ? '&type='.urlencode($_GET['type']) : ''; ?>" class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-l-md hover:bg-gray-50 <?= $page <= 1 ? 'opacity-50 cursor-not-allowed' : ''; ?>">Précédent</a>
                         <a href="?page=reclamations&action=historique&p=<?= $page + 1; ?><?= isset($_GET['status']) ? '&status='.urlencode($_GET['status']) : ''; ?><?= isset($_GET['type']) ? '&type='.urlencode($_GET['type']) : ''; ?>" class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border-t border-b border-r border-gray-300 rounded-r-md hover:bg-gray-50 <?= $page >= $totalPages ? 'opacity-50 cursor-not-allowed' : ''; ?>">Suivant</a>
                    </div>
                </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>

        <div id="detailsModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black bg-opacity-60">
            <div class="bg-white rounded-lg max-w-3xl w-full shadow-xl flex flex-col max-h-[90vh]">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h2 class="text-xl font-bold text-gray-900">Détails de la réclamation</h2>
                    <button onclick="fermerModalDetails()" class="text-gray-400 hover:text-gray-600">&times;</button>
                </div>
                <div id="modalContent" class="p-6 overflow-y-auto">
                    </div>
            </div>
        </div>

    </div>
    <script>
    // VOTRE JAVASCRIPT POUR LA MODALE EST CONSERVÉ À L'IDENTIQUE
    function voirDetailsReclamation(reclamationId) {
        const modal = document.getElementById('detailsModal');
        const modalContent = document.getElementById('modalContent');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        modalContent.innerHTML = `<div class="text-center p-8"><i class="fas fa-spinner fa-spin text-blue-500 text-3xl"></i><p class="mt-2 text-gray-600">Chargement...</p></div>`;

        fetch(`?page=reclamations&action=get_reclamation_details&id=${reclamationId}`)
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
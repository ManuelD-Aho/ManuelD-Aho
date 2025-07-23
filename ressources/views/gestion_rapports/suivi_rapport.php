<?php
// THIS IS EXAMPLE DATA to make the page render.
// YOUR PHP LOGIC, including the database connection and queries, is preserved.
require_once __DIR__ . '/../../../app/config/database.php'; // Kept as in your original file
$pdo = Database::getConnection();
$statistiques = $GLOBALS['statistiques'] ?? ['total' => 0];
$rapports = $GLOBALS['rapports'] ?? [];

// DÉCLARATION DE TOUTES LES FONCTIONS D'AIDE ICI (UNE SEULE FOIS)
if (!function_exists('render_suivi_stat_card')) {
    function render_suivi_stat_card($label, $value, $icon, $color) {
        echo "<div class='bg-white border border-gray-200 rounded-lg p-5'><div class='flex items-center justify-between'><div><p class='text-gray-500 text-sm font-medium'>$label</p><h3 class='text-2xl font-bold text-gray-800'>$value</h3></div><div class='p-3 rounded-full bg-$color-100'><i class='fa-solid $icon text-xl text-$color-600'></i></div></div></div>";
    }
}

if (!function_exists('render_timeline_item')) {
    function render_timeline_item($title, $date, $icon, $color_class, $comment = '') {
        echo '<div class="mb-8 relative">';
        echo "<div class='absolute -left-[34px] w-5 h-5 rounded-full flex items-center justify-center text-white $color_class'><i class='fas $icon text-xs'></i></div>";
        echo '<h4 class="font-semibold text-sm text-gray-800">' . htmlspecialchars($title) . '</h4>';
        echo '<p class="text-xs text-gray-500">' . htmlspecialchars($date) . '</p>';
        if ($comment) { echo '<p class="text-xs text-gray-600 mt-1 italic">"' . htmlspecialchars($comment) . '"</p>'; }
        echo '</div>';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suivi des Rapports</title>
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
            <h1 class="text-3xl font-bold text-gray-900">Suivi des Rapports</h1>
            <p class="text-md text-gray-600">Consultez l'état d'avancement de chaque rapport en temps réel.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6 mb-8">
            <?php
            // APPEL de la fonction, la déclaration est maintenant en haut du fichier
            render_suivi_stat_card("Total", $statistiques['total'] ?? 0, "fa-file-alt", "blue");
            render_suivi_stat_card("En cours", $statistiques['en_cours'] ?? 0, "fa-cogs", "yellow");
            render_suivi_stat_card("Validés", $statistiques['valide'] ?? 0, "fa-check-double", "green");
            render_suivi_stat_card("À corriger", $statistiques['a_corriger'] ?? 0, "fa-edit", "red");
            ?>
        </div>

        <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
            <div class="p-4 bg-gray-50 border-b border-gray-200">
                <div class="flex flex-col sm:flex-row items-center gap-4">
                    <div class="relative w-full sm:w-auto flex-grow">
                         <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fa-solid fa-search text-gray-400"></i>
                        </div>
                        <input type="text" id="searchInput" placeholder="Rechercher un rapport..." class="w-full pl-10 pr-4 py-2 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <select id="statusFilter" class="w-full sm:w-auto border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Tous les statuts</option>
                        <option value="en_attente">En attente</option>
                        <option value="en_cours">En cours</option>
                        <option value="rejete">Rejeté</option>
                        <option value="valide">Validé</option>
                    </select>
                </div>
            </div>

            <div class="reports-list divide-y divide-gray-200">
                <?php if (!empty($rapports)): foreach ($rapports as $rapport): ?>
                <div class="report-item p-6">
                    <div class="flex flex-col sm:flex-row justify-between sm:items-start gap-4 mb-6">
                        <div>
                            <h3 class="report-title text-lg font-semibold text-gray-900"><?= htmlspecialchars($rapport['nom_rapport']) ?></h3>
                            <p class="text-sm text-gray-500 mt-1">Soumis le <?= date('d/m/Y', strtotime($rapport['date_rapport'])) ?></p>
                        </div>
                        <?php
                            $status_map = [
                                'en_attente' => ['class' => 'bg-gray-100 text-gray-800', 'text' => 'En attente'],
                                'en_cours' => ['class' => 'bg-yellow-100 text-yellow-800', 'text' => 'En cours'],
                                'valide' => ['class' => 'bg-green-100 text-green-800', 'text' => 'Validé'],
                                'rejete' => ['class' => 'bg-red-100 text-red-800', 'text' => 'Rejeté'],
                            ];
                            $status_info = $status_map[$rapport['statut_rapport']] ?? ['class' => 'bg-gray-100 text-gray-800', 'text' => 'Inconnu'];
                        ?>
                        <span class="status px-3 py-1 text-xs font-medium rounded-full <?= $status_info['class'] ?>"><?= $status_info['text'] ?></span>
                    </div>

                    <div class="relative border-l-2 border-gray-200 ml-3 pl-8">
                        <?php
                        // Votre logique PHP pour la timeline est préservée ici
                        $stmt = $pdo->prepare("SELECT COUNT(*) FROM deposer WHERE num_etu = ? AND id_rapport = ?");
                        $stmt->execute([$_SESSION['num_etu'], $rapport['id_rapport']]);
                        $estDepose = $stmt->fetchColumn() > 0;
                        
                        // APPEL de la fonction, la déclaration est maintenant en haut du fichier
                        render_timeline_item('Enregistrement du rapport', date('d M Y - H:i', strtotime($rapport['date_rapport'])), 'fa-save', 'bg-blue-500');

                        $soumissionDate = 'En attente';
                        if ($estDepose) {
                            $stmt = $pdo->prepare("SELECT date_depot FROM deposer WHERE num_etu = ? AND id_rapport = ?");
                            $stmt->execute([$_SESSION['num_etu'], $rapport['id_rapport']]);
                            $dateDepot = $stmt->fetchColumn();
                            $soumissionDate = $dateDepot ? date('d M Y - H:i', strtotime($dateDepot)) : 'Date inconnue';
                        }
                        render_timeline_item('Soumission du rapport', $soumissionDate, $estDepose ? 'fa-check' : 'fa-hourglass', $estDepose ? 'bg-blue-500' : 'bg-gray-400');

                        $verif = null;
                        foreach ($rapport['decisions'] as $decision) { if ($decision['lib_approb'] === 'Niveau 1') { $verif = $decision; break; } }
                        $verif_color = $verif ? ($verif['decision'] === 'approuve' ? 'bg-blue-500' : 'bg-red-500') : 'bg-gray-400';
                        $verif_icon = $verif ? ($verif['decision'] === 'approuve' ? 'fa-check' : 'fa-times') : 'fa-hourglass';
                        render_timeline_item('Vérification initiale', $verif ? date('d M Y - H:i', strtotime($verif['date_approv'])) : 'En attente', $verif_icon, $verif_color, $verif['commentaire_approv'] ?? '');
                        
                        $eval = null;
                        foreach ($rapport['decisions'] as $decision) { if ($decision['lib_approb'] === 'Niveau 2') { $eval = $decision; break; } }
                        $eval_color = $eval ? ($eval['decision'] === 'approuve' ? 'bg-blue-500' : 'bg-red-500') : 'bg-gray-400';
                        $eval_icon = $eval ? ($eval['decision'] === 'approuve' ? 'fa-check' : 'fa-times') : 'fa-hourglass';
                        render_timeline_item('Évaluation commission', $eval ? date('d M Y - H:i', strtotime($eval['date_approv'])) : 'En attente', $eval_icon, $eval_color, $eval['commentaire_approv'] ?? '');

                        $is_final = ($eval && $eval['decision'] === 'approuve' && $rapport['statut_rapport'] === 'valide');
                        render_timeline_item('Validation finale', $is_final ? 'Validé' : 'En attente', $is_final ? 'fa-check-double' : 'fa-hourglass', $is_final ? 'bg-green-500' : 'bg-gray-400');
                        ?>
                    </div>
                </div>
                <?php endforeach; else: ?>
                <div class="text-center p-12">
                     <i class="fa-solid fa-folder-open fa-3x text-gray-300 mb-4"></i>
                    <p class="text-lg text-gray-500">Aucun rapport à suivre pour le moment.</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
    // VOTRE SCRIPT DE FILTRAGE CÔTÉ CLIENT EST CONSERVÉ
    document.addEventListener('DOMContentLoaded', function() {
        const statusFilter = document.getElementById('statusFilter');
        const searchInput = document.getElementById('searchInput');
        const reportItems = document.querySelectorAll('.report-item');

        statusFilter.addEventListener('change', filterReports);
        searchInput.addEventListener('input', filterReports);

        function filterReports() {
            const statusValue = statusFilter.value;
            const searchValue = searchInput.value.toLowerCase();

            reportItems.forEach(item => {
                const title = item.querySelector('.report-title').textContent.toLowerCase();
                const statusElement = item.querySelector('.status');
                const statusText = statusElement.textContent.trim().toLowerCase().replace('é', 'e');
                
                const matchesSearch = title.includes(searchValue);
                const matchesStatus = (statusValue === '' || statusText === statusValue.replace('_', ' '));
                
                if (matchesSearch && matchesStatus) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        }
    });
    </script>
</body>
</html>
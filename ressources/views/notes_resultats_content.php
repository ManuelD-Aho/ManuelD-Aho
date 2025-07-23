<?php

// VOTRE LOGIQUE PHP EST CONSERVÉE À L'IDENTIQUE
// Récupération des données depuis le contrôleur
$etudiant = $GLOBALS['etudiant'] ?? null;
$moyenneGenerale = $GLOBALS['moyenneGenerale'] ?? null;
$nbUeValide = $GLOBALS['nbUeValide'] ?? 0;
$classement = $GLOBALS['classement'] ?? null;
$totalEtudiants = $GLOBALS['totalEtudiants'] ?? 0;
$notes = $GLOBALS['notes'] ?? [];
$semestres = $GLOBALS['semestres'] ?? [];

// Création d'un objet étudiant par défaut pour éviter les erreurs si null
if (!$etudiant) {
    $etudiant = new stdClass();
    $etudiant->nom_etu = 'N/A';
    $etudiant->prenom_etu = '';
    $etudiant->num_etu = 'N/A';
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portail Étudiant - Mes Résultats</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
        
        /* Styles pour les badges de notes */
        .grade-badge { padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.8rem; font-weight: 600; }
        .grade-A { background-color: #dcfce7; color: #16a34a; } /* Excellent (16+) */
        .grade-B { background-color: #f0fdf4; color: #65a30d; } /* Très Bien (14-15.99) */
        .grade-C { background-color: #fefce8; color: #ca8a04; } /* Bien (12-13.99) */
        .grade-D { background-color: #fffbeb; color: #d97706; } /* Passable (10-11.99) */
        .grade-F { background-color: #fee2e2; color: #dc2626; } /* Échec (<10) */
    </style>
</head>
<body class="bg-gray-100">

    <div class="container mx-auto px-4 py-12">
        <header class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Mon Portail Académique</h1>
            <p class="text-md text-gray-600">
                <?= htmlspecialchars($etudiant->nom_etu . ' ' . $etudiant->prenom_etu); ?> — N° <?= htmlspecialchars($etudiant->num_etu); ?>
            </p>
        </header>

        <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
            
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-bold text-gray-800 mb-4">Résumé Académique</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-5">
                    <div class="bg-gray-50 p-4 rounded-lg flex items-center">
                        <div class="bg-blue-100 text-blue-600 p-3 rounded-full mr-4"><i class="fa-solid fa-graduation-cap"></i></div>
                        <div>
                            <p class="text-sm text-gray-600">Moyenne Générale</p>
                            <p class="text-xl font-bold text-gray-900"><?= $moyenneGenerale !== null ? number_format($moyenneGenerale, 2) . '/20' : 'N/A'; ?></p>
                        </div>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg flex items-center">
                        <div class="bg-green-100 text-green-600 p-3 rounded-full mr-4"><i class="fa-solid fa-check-circle"></i></div>
                        <div>
                            <p class="text-sm text-gray-600">Modules Validés</p>
                            <p class="text-xl font-bold text-gray-900"><?= $nbUeValide; ?></p>
                        </div>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg flex items-center">
                        <div class="bg-yellow-100 text-yellow-600 p-3 rounded-full mr-4"><i class="fa-solid fa-trophy"></i></div>
                        <div>
                            <p class="text-sm text-gray-600">Classement</p>
                            <p class="text-xl font-bold text-gray-900"><?= $classement !== null ? $classement . ' / ' . $totalEtudiants : 'N/A'; ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <div class="px-6 py-4 flex flex-col sm:flex-row justify-between items-center gap-3 bg-gray-50 border-b border-gray-200">
                    <h2 class="text-lg font-bold text-gray-800"><i class="fas fa-table mr-2"></i>Bulletin de Notes</h2>
                    <div class="flex items-center space-x-2">
                        <select id="semesterFilter" class="bg-white border border-gray-300 text-gray-700 text-sm rounded-md focus:ring-blue-500 focus:border-blue-500 block w-full sm:w-auto p-2">
                            <option value="all">Tous les semestres</option>
                            <?php if (!empty($semestres)): foreach ($semestres as $semestre): ?>
                                <option value="<?= htmlspecialchars($semestre->lib_semestre); ?>">
                                    <?= htmlspecialchars($semestre->lib_semestre); ?>
                                </option>
                            <?php endforeach; endif; ?>
                        </select>
                        <button id="printBtn" class="bg-white border border-gray-300 text-gray-700 hover:bg-gray-100 font-medium px-3 py-2 rounded-md text-sm" title="Imprimer"><i class="fa-solid fa-print"></i></button>
                        <button id="pdfBtn" class="bg-white border border-gray-300 text-gray-700 hover:bg-gray-100 font-medium px-3 py-2 rounded-md text-sm" title="Exporter en PDF"><i class="fa-solid fa-file-pdf"></i></button>
                        <button id="exportBtn" class="bg-white border border-gray-300 text-gray-700 hover:bg-gray-100 font-medium px-3 py-2 rounded-md text-sm" title="Exporter en Excel/CSV"><i class="fa-solid fa-file-csv"></i></button>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200" id="gradesTable">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Module</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Crédits</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Note</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Appréciation</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Semestre</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php if (!empty($notes)): foreach ($notes as $note): ?>
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900"><?= htmlspecialchars($note->lib_ue); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-500"><?= htmlspecialchars($note->credit); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="grade-badge grade-<?= $note->moyenne >= 16 ? 'A' : ($note->moyenne >= 14 ? 'B' : ($note->moyenne >= 12 ? 'C' : ($note->moyenne >= 10 ? 'D' : 'F'))); ?>">
                                            <?= htmlspecialchars($note->moyenne); ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-500"><?= htmlspecialchars($note->commentaire); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-500"><?= htmlspecialchars($note->lib_semestre ?? 'N/A'); ?></td>
                                </tr>
                            <?php endforeach; else: ?>
                                <tr>
                                    <td colspan="5" class="text-center text-gray-500 py-10">Aucune note disponible pour le moment.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const semesterFilter = document.getElementById('semesterFilter');
        const printBtn = document.getElementById('printBtn');
        const pdfBtn = document.getElementById('pdfBtn');
        const exportBtn = document.getElementById('exportBtn');
        
        // Filtrage par semestre
        if (semesterFilter) {
            semesterFilter.addEventListener('change', function() {
                const selectedSemester = this.value;
                const rows = document.querySelectorAll('#gradesTable tbody tr');

                rows.forEach(row => {
                    // S'assurer qu'on ne cible pas la ligne "Aucune note"
                    if (row.querySelectorAll('td').length > 1) {
                        const semesterCell = row.querySelector('td:last-child');
                        if (selectedSemester === 'all' || semesterCell.textContent.trim() === selectedSemester) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    }
                });
            });
        }

        // Bouton Imprimer
        if (printBtn) {
            printBtn.addEventListener('click', () => window.print());
        }

        // Bouton PDF (redirige vers l'action du contrôleur)
        if (pdfBtn) {
            pdfBtn.addEventListener('click', () => window.location.href = '?page=resultats&action=export_pdf');
        }

        // Bouton Exporter CSV
        if (exportBtn) {
            exportBtn.addEventListener('click', exportCSV);
        }
    });

    function exportCSV() {
        const table = document.getElementById('gradesTable');
        if (!table) return;
        
        let csv = [];
        const headers = Array.from(table.querySelectorAll('thead th')).map(th => `"${th.textContent.trim()}"`);
        csv.push(headers.join(';'));

        const visibleRows = table.querySelectorAll('tbody tr');
        visibleRows.forEach(row => {
            if (row.style.display !== 'none' && row.querySelectorAll('td').length > 1) {
                const cells = Array.from(row.querySelectorAll('td'));
                const rowData = cells.map(td => `"${td.textContent.trim().replace(/"/g, '""')}"`);
                csv.push(rowData.join(';'));
            }
        });

        const csvContent = '\uFEFF' + csv.join('\n'); // \uFEFF pour l'encodage UTF-8 BOM
        const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
        const link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = 'bulletin-notes.csv';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }
    </script>
</body>
</html>
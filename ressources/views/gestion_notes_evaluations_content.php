<?php

$students = isset($GLOBALS['listeEtudiants']) ? $GLOBALS['listeEtudiants'] : [];
$niveauxEtude = isset($GLOBALS['niveauxEtude']) ? $GLOBALS['niveauxEtude'] : [];
$selectedNiveau = isset($GLOBALS['selectedNiveau']) ? $GLOBALS['selectedNiveau'] : null;
$selectedStudent = isset($GLOBALS['selectedStudent']) ? $GLOBALS['selectedStudent'] : null;
$studentGrades = isset($GLOBALS['studentGrades']) ? $GLOBALS['studentGrades'] : [];

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Responsable Scolarité | Gestion des Notes</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'custom-primary': '#3457cb',
                        'custom-primary-dark': '#24407a',
                        'custom-success-dark': '#36865a',
                        'custom-success-light': '#59bf3d',
                    },
                    animation: {
                        'fade-in-down': 'fadeInDown 0.8s ease-out forwards',
                        'slide-in-right': 'slideInRight 0.8s ease-out forwards',
                        'scale-in': 'scaleIn 0.5s ease-out forwards',
                        'fade-in-up': 'fadeInUp 0.6s ease-out forwards',
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

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .card {
        background: white;
        border-radius: 20px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 8px 32px rgba(15, 23, 42, 0.08);
        transition: all 0.3s ease;
        overflow: hidden;
    }

    .header-gradient {
        background: linear-gradient(135deg, #24407a 0%, #3457cb 100%);
    }

    .stat-card {
        background: white;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 8px 32px rgba(15, 23, 42, 0.08);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
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

    .stat-card:hover::before {
        opacity: 1;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 16px 48px rgba(15, 23, 42, 0.12);
    }

    .initial-hidden {
        opacity: 0;
        transform: translateY(20px);
        animation: fadeInUp 0.6s ease-out forwards;
    }

    .progress-ring__circle {
        transition: stroke-dashoffset 0.35s;
        transform: rotate(-90deg);
        transform-origin: 50% 50%;
    }

    .sidebar-item.active {
        background-color: #e6f7ff;
        border-left: 4px solid #3b82f6;
        color: #3b82f6;
    }

    .sidebar-item.active i {
        color: #3b82f6;
    }

    .note-input:focus {
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.5);
    }

    .status-badge {
        padding: 0.25rem 0.5rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .search-container {
        position: relative;
    }

    .search-results {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        z-index: 10;
        max-height: 300px;
        overflow-y: auto;
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 0.375rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }

    .search-item {
        padding: 0.75rem 1rem;
        cursor: pointer;
        transition: background-color 0.2s;
    }

    .search-item:hover {
        background-color: #f3f4f6;
    }

    .tab-content {
        display: none;
    }

    .tab-content.active {
        display: block;
        animation: fadeIn 0.3s ease-in;
    }

    /* Styles pour les alertes */
    [role="alert"] {
        opacity: 0;
        transition: opacity 0.5s ease-in-out;
    }

    [role="alert"].show {
        opacity: 1;
    }

    #alertContainer {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 1000;
        max-width: 400px;
    }
    </style>
    <script>
    function toggleSemestreValidation(semestre) {
        fetch('<?php echo '?page=gestion_notes_evaluations'; ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    semestre: semestre,
                    etudiant_id: '<?php echo $GLOBALS['selectedStudent']->num_etu; ?>'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                } else {
                    alert('Erreur lors de la validation du semestre');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                alert('Erreur lors de la validation du semestre');
            });
    }

    document.getElementById('saisiForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);

        fetch('<?php echo '?page=gestion_notes_evaluations&action=enregistrer_notes'; ?>', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                } else {
                    alert('Erreur lors de l\'enregistrement des notes');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                alert('Erreur lors de l\'enregistrement des notes');
            });
    });
    </script>
</head>

<body class="font-sans antialiased bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Header Section -->
        <div class="bg-white rounded-3xl shadow-xl border border-gray-100 mb-8 relative overflow-hidden animate-fade-in-down">
            <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-custom-primary to-custom-success-light"></div>
            <div class="p-8 lg:p-12">
                <div class="flex items-center gap-6 md:gap-8 flex-col md:flex-row text-center md:text-left">
                    <div class="bg-gradient-to-br from-custom-primary to-custom-primary-dark text-white w-20 h-20 md:w-24 md:h-24 rounded-2xl flex items-center justify-center text-4xl md:text-5xl shadow-lg transform transition-transform duration-300 hover:scale-110">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div>
                        <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 mb-2 tracking-tight">Gestion des Notes</h1>
                        <p class="text-lg text-gray-600 font-normal">Saisie et suivi des évaluations étudiantes</p>
                    </div>
                </div>
            </div>
        </div>

        <div id="alertContainer">
                <?php if (isset($_SESSION['success'])): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6"
                    role="alert">
                    <span class="block sm:inline"><?php echo $_SESSION['success']; ?></span>
                </div>
                <?php unset($_SESSION['success']); ?>
                <?php endif; ?>

                <?php if (isset($_SESSION['error'])): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
                    <span class="block sm:inline"><?php echo $_SESSION['error']; ?></span>
                </div>
                <?php unset($_SESSION['error']); ?>
                <?php endif; ?>
            </div>

            <!-- Interface de saisie des notes (optimisée) -->
            <div id="notes" class="tab-content active">
                <!-- Header with student search -->
                <div class="card p-8 mb-8 initial-hidden" style="animation-delay: 0.1s">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold text-gray-800">Gestion des Notes</h2>
                        <div class="flex items-center space-x-4">
                            <!-- Niveau d'étude -->
                            <div class="relative">
                                <select id="niveauSelect"
                                    class="block w-64 px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Sélectionner un niveau</option>
                                    <?php 
                                    $niveaux = isset($GLOBALS['niveaux']) ? $GLOBALS['niveaux'] : $niveauxEtude;
                                    foreach ($niveaux as $niveau): ?>
                                    <option value="<?php echo htmlspecialchars($niveau->id_niv_etude); ?>"
                                        <?php echo $selectedNiveau == $niveau->id_niv_etude ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($niveau->lib_niv_etude); ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <!-- Étudiant -->
                            <div class="relative">
                                <select id="studentSelect"
                                    class="block w-64 px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Sélectionner un étudiant</option>
                                    <?php 
                                    $etudiants = isset($GLOBALS['etudiants']) ? $GLOBALS['etudiants'] : [];
                                    foreach ($etudiants as $etudiant): ?>
                                    <option value="<?php echo htmlspecialchars($etudiant->num_etu); ?>"
                                        <?php echo isset($selectedStudent) && $selectedStudent->num_etu == $etudiant->num_etu ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($etudiant->nom_etu . ' ' . $etudiant->prenom_etu); ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Message initial -->
                                            <?php if (empty($selectedNiveau)) { ?>
                    <div class="text-center py-12 bg-gray-50 rounded-lg">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-100 mb-4">
                            <i class="fas fa-graduation-cap text-2xl text-blue-600"></i>
                        </div>
                        <h4 class="text-lg font-medium text-gray-900 mb-2">Sélectionnez un niveau d'étude</h4>
                        <p class="text-gray-500">Veuillez sélectionner un niveau d'étude pour afficher les semestres et
                            les unités d'enseignement.</p>
                    </div>
                    <?php } ?>

                    <!-- Informations de l'étudiant -->
                    <?php if (!empty($GLOBALS['selectedStudent'])): ?>
                    <div class="stat-card p-6 mb-8 initial-hidden" style="animation-delay: 0.2s">
                        <div class="flex items-center space-x-4">
                            <div class="flex-shrink-0">
                                <div class="w-16 h-16 rounded-full bg-custom-primary/10 flex items-center justify-center">
                                    <i class="fas fa-user text-2xl text-custom-primary"></i>
                                </div>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-xl font-semibold text-gray-900">
                                    <?php echo htmlspecialchars($GLOBALS['selectedStudent']->nom_etu . ' ' . $GLOBALS['selectedStudent']->prenom_etu); ?>
                                </h3>
                                <p class="text-gray-600">Numéro d'étudiant:
                                    <?php echo htmlspecialchars($GLOBALS['selectedStudent']->num_etu); ?></p>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Semestres et UE -->
                    <div class="space-y-6">
                        <?php if (!empty($GLOBALS['selectedNiveau'])) { ?>

                        <form id="saisiForm" class="space-y-6" action="?page=gestion_notes_evaluations<?php 
                                echo !empty($GLOBALS['selectedNiveau']) ? '&niveau=' . htmlspecialchars($GLOBALS['selectedNiveau']) : '';
                                echo !empty($GLOBALS['selectedStudent']) ? '&student=' . htmlspecialchars($GLOBALS['selectedStudent']->num_etu) : '';
                            ?>&action=enregistrer_notes" method="POST">
                            <?php 
                                $currentSemestre = null;
                                if (!empty($GLOBALS['studentUes'])) {
                                    foreach ($GLOBALS['studentUes'] as $ue) {
                                        if ($currentSemestre !== $ue->lib_semestre) {
                                            if ($currentSemestre !== null) {
                                                echo '</div></div>';
                                            }
                                            $currentSemestre = $ue->lib_semestre;
                                            
                                            // Calculer le total des crédits pour ce semestre
                                            $totalCreditsSemestre = 0;
                                            foreach ($GLOBALS['studentUes'] as $ueSemestre) {
                                                if ($ueSemestre->lib_semestre === $currentSemestre) {
                                                    $totalCreditsSemestre += $ueSemestre->credit;
                                                }
                                            }
                                            ?>
                            <div class="card overflow-hidden initial-hidden" style="animation-delay: 0.3s">
                                <div class="header-gradient px-8 py-6">
                                    <div class="flex justify-between items-center">
                                        <h3 class="text-lg font-semibold text-white">
                                            <?php echo htmlspecialchars($ue->lib_semestre); ?></h3>
                                        <span class="text-sm text-blue-100"><?php echo $totalCreditsSemestre; ?>
                                            crédits</span>
                                    </div>
                                </div>
                                <div class="p-6">
                                    <?php
                                    }
                                    ?>
                                    <div class="mb-6 last:mb-0">
                                        <?php
                                        // Récupérer les ECUE de cette UE
                                        $ecues = [];
                                        if (!empty($GLOBALS['studentEcues'])) {
                                            foreach ($GLOBALS['studentEcues'] as $ecue) {
                                                if ($ecue->id_ue == $ue->id_ue) {
                                                    $ecues[] = $ecue;
                                                }
                                            }
                                        }
                                        
                                        if (!empty($ecues)) {
                                            // Affichage avec ECUE
                                            echo '<div class="flex justify-between items-center mb-2">';
                                            echo '<h4 class="text-lg font-medium text-gray-900">' . htmlspecialchars($ue->lib_ue) . '</h4>';
                                            echo '<span class="text-sm text-gray-500">' . $ue->credit . ' crédits</span>';
                                            echo '</div>';
                                            
                                            echo '<div class="bg-gray-50 rounded-lg p-4 mb-4">';
                                            echo '<h5 class="text-sm font-medium text-gray-700 mb-3">Éléments constitutifs (ECUE)</h5>';
                                            echo '<div class="space-y-4">';
                                            foreach ($ecues as $ecue) {
                                                echo '<div class="flex items-center space-x-8">';
                                                echo '<div class="flex-1">';
                                                echo '<h4 class="text-sm font-medium text-gray-900">' . htmlspecialchars($ecue->lib_ecue) . '</h4>';
                                                echo '</div>';
                                                echo '<div class="w-24 mx-4">';
                                                echo '<input type="number" step="0.01" min="0" max="20" name="notes_ecue[' . $ecue->id_ecue . ']" value="';
                                                $note_ecue = null;
                                                if (!empty($GLOBALS['studentGrades'])) {
                                                    foreach ($GLOBALS['studentGrades'] as $grade) {
                                                        if ($grade->id_ecue == $ecue->id_ecue) {
                                                            $note_ecue = $grade->moyenne;
                                                            break;
                                                        }
                                                    }
                                                }
                                                echo $note_ecue !== null ? htmlspecialchars($note_ecue) : '';
                                                echo '" class="note-input w-full px-2 py-1 text-sm border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">';
                                                echo '</div>';
                                                echo '<div class="flex-1 mx-4">';
                                                echo '<input type="text" name="commentaires_ecue[' . $ecue->id_ecue . ']" value="';
                                                $commentaire_ecue = null;
                                                if (!empty($GLOBALS['studentGrades'])) {
                                                    foreach ($GLOBALS['studentGrades'] as $grade) {
                                                        if ($grade->id_ecue == $ecue->id_ecue) {
                                                            $commentaire_ecue = $grade->commentaire;
                                                            break;
                                                        }
                                                    }
                                                }
                                                echo $commentaire_ecue !== null ? htmlspecialchars($commentaire_ecue) : '';
                                                echo '" placeholder="Commentaire" class="w-full px-2 py-1 text-sm border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">';
                                                echo '</div>';
                                                echo '</div>';
                                            }
                                            echo '</div>';
                                            // Affichage de la moyenne de l'UE (lecture seule)
                                            $moyenne_ue = null;
                                            $nb_ecue = 0;
                                            $somme = 0;
                                            foreach ($ecues as $ecue) {
                                                foreach ($GLOBALS['studentGrades'] as $grade) {
                                                    if ($grade->id_ecue == $ecue->id_ecue && $grade->moyenne !== null) {
                                                        $somme += $grade->moyenne;
                                                        $nb_ecue++;
                                                    }
                                                }
                                            }
                                            if ($nb_ecue > 0) {
                                                $moyenne_ue = round($somme / $nb_ecue, 2);
                                            }
                                            echo '<div class="mt-4 text-right"><span class="text-sm text-blue-700 font-semibold">Moyenne UE : ' . ($moyenne_ue !== null ? $moyenne_ue : '-') . '</span></div>';
                                            echo '</div>';
                                        } else {
                                            // Affichage sans ECUE - tout sur une ligne
                                            echo '<div class="flex items-center space-x-8">';
                                            echo '<div class="flex-1">';
                                            echo '<h4 class="text-lg font-medium text-gray-900">' . htmlspecialchars($ue->lib_ue) . '</h4>';
                                            echo '</div>';
                                            echo '<div class="w-24 mx-4">';
                                            echo '<input type="number" step="0.01" min="0" max="20" name="notes[' . $ue->id_ue . ']" value="';
                                            $note = null;
                                            if (!empty($GLOBALS['studentGrades'])) {
                                                foreach ($GLOBALS['studentGrades'] as $grade) {
                                                    if ($grade->id_ue == $ue->id_ue) {
                                                        $note = $grade->moyenne;
                                                        break;
                                                    }
                                                }
                                            }
                                            echo $note !== null ? htmlspecialchars($note) : '';
                                            echo '" class="note-input w-full px-2 py-1 text-sm border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">';
                                            echo '</div>';
                                            echo '<div class="flex-1 mx-4">';
                                            echo '<input type="text" name="commentaires[' . $ue->id_ue . ']" value="';
                                            $commentaire = null;
                                            if (!empty($GLOBALS['studentGrades'])) {
                                                foreach ($GLOBALS['studentGrades'] as $grade) {
                                                    if ($grade->id_ue == $ue->id_ue) {
                                                        $commentaire = $grade->commentaire;
                                                        break;
                                                    }
                                                }
                                            }
                                            echo $commentaire !== null ? htmlspecialchars($commentaire) : '';
                                            echo '" placeholder="Commentaire" class="w-full px-2 py-1 text-sm border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">';
                                            echo '</div>';
                                            echo '<div class="w-20 text-right ml-4">';
                                            echo '<span class="text-sm text-gray-500">' . $ue->credit . ' crédits</span>';
                                            echo '</div>';
                                            echo '</div>';
                                        }
                                        ?>
                                    </div>
                                    <?php
                                }
                                if ($currentSemestre !== null) {
                                    echo '</div></div>';
                                }
                            ?>
                                    <div class="flex justify-end mt-6">
                                        <button type="submit" name="btn_enregistrer_notes"
                                            class="px-6 py-3 bg-gradient-to-r from-custom-primary to-custom-primary-dark text-white rounded-xl hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-custom-primary focus:ring-offset-2 transition-all duration-300 font-semibold">
                                            Enregistrer les notes
                                        </button>
                                    </div>
                        </form>
                        <?php } ?>

                        <?php } ?>
                    </div>

                    <!-- Résumé des notes -->
                    <?php if (!empty($GLOBALS['selectedStudent'])): ?>
                    <div class="card p-8 mb-8 mt-8 initial-hidden" style="animation-delay: 0.4s">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
                            <div class="stat-card p-6 text-center">
                                <h3 class="text-sm font-medium text-gray-500 mb-2">Moyenne Générale</h3>
                                <p class="text-3xl font-bold text-custom-primary mb-1">
                                    <?php
                                    $totalNotes = 0;
                                    $totalCredits = 0;
                                    foreach ($GLOBALS['studentGrades'] as $grade) {
                                        $totalNotes += $grade->moyenne * $grade->credit;
                                        $totalCredits += $grade->credit;
                                    }
                                    echo $totalCredits > 0 ? number_format($totalNotes / $totalCredits, 2) : '0.00';
                                    ?>
                                </p>
                            </div>
                            <div class="stat-card p-6 text-center">
                                <h3 class="text-sm font-medium text-gray-500 mb-2">Moyenne UE majeures</h3>
                                <p class="text-3xl font-bold text-custom-success-dark mb-1">
                                    <?php
                                    $sumMaj = $credMaj = 0;
                                    foreach ($GLOBALS['studentGrades'] as $grade) {
                                        if ($grade->credit > 3) {
                                            $sumMaj += $grade->moyenne * $grade->credit;
                                            $credMaj += $grade->credit;
                                        }
                                    }
                                    $moyMaj = $credMaj ? round($sumMaj / $credMaj, 2) : '-';
                                    echo $moyMaj;
                                    ?>
                                </p>
                            </div>
                            <div class="stat-card p-6 text-center">
                                <h3 class="text-sm font-medium text-gray-500 mb-2">Moyenne UE mineures</h3>
                                <p class="text-3xl font-bold text-yellow-600 mb-1">
                                    <?php
                                    $sumMin = $credMin = 0;
                                    foreach ($GLOBALS['studentGrades'] as $grade) {
                                        if ($grade->credit <= 3) {
                                            $sumMin += $grade->moyenne * $grade->credit;
                                            $credMin += $grade->credit;
                                        }
                                    }
                                    $moyMin = $credMin ? round($sumMin / $credMin, 2) : '-';
                                    echo $moyMin;
                                    ?>
                                </p>
                            </div>
                            <div class="bg-white rounded-lg p-4 shadow-sm">
                                <h3 class="text-sm font-medium text-gray-500 mb-1">Crédits Attribués</h3>
                                <p class="text-2xl font-bold text-green-600">
                                    <?php
                                    // Validation du semestre selon les moyennes majeures/mineures
                                    $semestreValide = ($moyMaj !== '-' && $moyMin !== '-' && $moyMaj >= 10 && $moyMin >= 10);
                                    if ($semestreValide) {
                                        echo $totalCredits;
                                    } else {
                                        // Sinon, somme des crédits des UE validées individuellement
                                        $creditsValides = 0;
                                        foreach ($GLOBALS['studentGrades'] as $grade) {
                                            if ($grade->moyenne >= 10) {
                                                $creditsValides += $grade->credit;
                                            }
                                        }
                                        echo $creditsValides;
                                    }
                                    ?>
                                </p>
                            </div>
                            <div class="bg-white rounded-lg p-4 shadow-sm">
                                <h3 class="text-sm font-medium text-gray-500 mb-1">Validation Semestre</h3>
                                <p
                                    class="text-2xl font-bold <?php echo $semestreValide ? 'text-green-600' : 'text-red-600'; ?>">
                                    <?php echo $semestreValide ? 'Validé' : 'Non validé'; ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($GLOBALS['selectedStudent'])): ?>
                    <div class="flex justify-end mb-4 no-print">
                        <a href="?page=gestion_notes_evaluations&action=imprimer_releve&student=<?= urlencode($GLOBALS['selectedStudent']->num_etu) ?>&niveau=<?= urlencode($GLOBALS['selectedNiveau']) ?>"
                            target="_blank" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                            <i class="fa fa-file-pdf mr-2"></i> Imprimer le relevé de notes (PDF)
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const niveauSelect = document.getElementById('niveauSelect');
        const studentSelect = document.getElementById('studentSelect');

        // Gestion des messages d'alerte
        const alerts = document.querySelectorAll('[role="alert"]');
        alerts.forEach(alert => {
            // Faire apparaître l'alerte
            setTimeout(() => {
                alert.classList.add('show');
            }, 100);

            // Faire disparaître l'alerte après 5 secondes
            setTimeout(() => {
                alert.classList.remove('show');
                setTimeout(() => {
                    alert.remove();
                }, 500);
            }, 5000);
        });

        niveauSelect.addEventListener('change', function() {
            const niveauId = this.value;
            if (niveauId) {
                window.location.href = `?page=gestion_notes_evaluations&niveau=${niveauId}`;
            }
        });

        studentSelect.addEventListener('change', function() {
            const studentId = this.value;
            const niveauId = niveauSelect.value;
            if (studentId && niveauId) {
                window.location.href =
                    `?page=gestion_notes_evaluations&niveau=${niveauId}&student=${studentId}`;
            }
        });
    });
    </script>
</body>

</html>
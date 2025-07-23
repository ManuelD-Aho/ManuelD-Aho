<?php
require_once __DIR__ . '/../../app/config/database.php';

// Connexion à la base
$pdo = Database::getConnection();
$rapportModel = new RapportEtudiant($pdo);

// Récupérer les rapports en attente d'évaluation
$rapportsEnAttente = $rapportModel->getRapportsByStatut('en_attente');
$rapportsTotal = $rapportModel->getAllRapports();

// Calcul des statistiques
$totalRapports = count($rapportsTotal);
$rapportsEnAttenteCount = count($rapportsEnAttente);
$rapportsValides = count($rapportModel->getRapportsByStatut('valide'));
$rapportsRejetes = count($rapportModel->getRapportsByStatut('rejete'));

// Pourcentage des rapports en attente
$pourcentageEnAttente = $totalRapports > 0 ? round(($rapportsEnAttenteCount / $totalRapports) * 100) : 0;

// Gestion de la soumission d'évaluation
$messageSuccess = '';
$messageErreur = '';
$selectedRapport = null;

// Récupérer le rapport sélectionné (par défaut le premier en attente)
$selectedRapportId = $_GET['rapport_id'] ?? ($rapportsEnAttente[0]->id_rapport ?? null);
if ($selectedRapportId) {
    $selectedRapport = $rapportModel->getRapportDetail($selectedRapportId);
}

// Traitement de la soumission de décision
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['decision'])) {
    $id_rapport = $_POST['id_rapport'] ?? null;
    $decision = $_POST['decision'] ?? '';
    $commentaire = $_POST['commentaire'] ?? '';
    
    if ($id_rapport && in_array($decision, ['valider', 'rejeter'])) {
        try {
            // Mettre à jour le statut du rapport
            $nouveauStatut = ($decision === 'valider') ? 'valide' : 'rejete';
            
            if ($rapportModel->updateStatutRapport($id_rapport, $nouveauStatut)) {
                $messageSuccess = "Décision enregistrée avec succès ! Le rapport a été " . 
                                ($decision === 'valider' ? 'validé' : 'rejeté') . ".";
                
                // Recharger les données
                $rapportsEnAttente = $rapportModel->getRapportsByStatut('en_attente');
                $selectedRapport = $rapportModel->getRapportDetail($id_rapport);
            } else {
                $messageErreur = "Erreur lors de l'enregistrement de la décision.";
            }
        } catch (Exception $e) {
            $messageErreur = "Erreur système : " . $e->getMessage();
        }
    } else {
        $messageErreur = "Données invalides. Veuillez vérifier votre sélection.";
    }
}

// Gestion des actions spéciales (preview et download)
$action = $_GET['action'] ?? '';

if ($action === 'preview' && isset($_GET['id'])) {
    $id_rapport = $_GET['id'];
    $rapport = $rapportModel->getRapportDetail($id_rapport);
    
    if ($rapport) {
        $cheminFichier = $rapport->chemin_fichier ?? 'rapport_' . $id_rapport . '.html';
        $fichierContenu = __DIR__ . '/../../ressources/uploads/rapports/' . $cheminFichier;
        
        if (file_exists($fichierContenu)) {
            $contenu = file_get_contents($fichierContenu);
            
            echo '<!DOCTYPE html>';
            echo '<html lang="fr">';
            echo '<head>';
            echo '<meta charset="UTF-8">';
            echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
            echo '<title>Prévisualisation - ' . htmlspecialchars($rapport->nom_rapport ?? $rapport->theme_rapport) . '</title>';
            echo '<style>';
            echo 'body { font-family: "Inter", sans-serif; margin: 20px; line-height: 1.6; color: #333; }';
            echo '.header { background: linear-gradient(135deg, #3457cb 0%, #24407a 100%); color: white; padding: 20px; border-radius: 12px; margin-bottom: 20px; }';
            echo '.content { max-width: 100%; overflow-wrap: break-word; }';
            echo 'h1, h2, h3 { color: #24407a; }';
            echo 'table { width: 100%; border-collapse: collapse; margin: 15px 0; }';
            echo 'table th, table td { border: 1px solid #e2e8f0; padding: 8px; text-align: left; }';
            echo 'table th { background-color: #f8fafc; }';
            echo 'img { max-width: 100%; height: auto; }';
            echo '</style>';
            echo '</head>';
            echo '<body>';
            echo '<div class="header">';
            echo '<h2>📄 ' . htmlspecialchars($rapport->nom_rapport ?? $rapport->theme_rapport) . '</h2>';
            echo '<p><strong>Étudiant :</strong> ' . htmlspecialchars($rapport->prenom_etu . ' ' . $rapport->nom_etu) . '</p>';
            echo '<p><strong>Date :</strong> ' . date('d/m/Y à H:i', strtotime($rapport->date_depot ?? $rapport->date_rapport)) . '</p>';
            echo '</div>';
            echo '<div class="content">';
            echo $contenu;
            echo '</div>';
            echo '</body>';
            echo '</html>';
        } else {
            echo '<div style="text-align: center; padding: 50px; color: #e74c3c;">❌ Fichier du rapport non trouvé</div>';
        }
    } else {
        echo '<div style="text-align: center; padding: 50px; color: #e74c3c;">❌ Rapport non trouvé</div>';
    }
    exit;
}

if ($action === 'download_pdf' && isset($_GET['id'])) {
    $id_rapport = $_GET['id'];
    $rapport = $rapportModel->getRapportDetail($id_rapport);
    
    if ($rapport) {
        $cheminFichier = $rapport->chemin_fichier ?? 'rapport_' . $id_rapport . '.html';
        $fichierContenu = __DIR__ . '/../../ressources/uploads/rapports/' . $cheminFichier;
        
        if (file_exists($fichierContenu)) {
            $contenu = file_get_contents($fichierContenu);
            
            if (file_exists(__DIR__ . '/../../vendor/autoload.php')) {
                require_once __DIR__ . '/../../vendor/autoload.php';
                
                $html = '<!DOCTYPE html>
                <html lang="fr">
                <head>
                    <meta charset="UTF-8">
                    <title>' . htmlspecialchars($rapport->nom_rapport ?? $rapport->theme_rapport) . '</title>
                    <style>
                        body { font-family: "Inter", sans-serif; margin: 20px; line-height: 1.6; color: #333; }
                        .header { border-bottom: 3px solid #3457cb; padding-bottom: 15px; margin-bottom: 20px; }
                        .header h1 { color: #3457cb; margin: 0; font-size: 24px; }
                        .info { background: #f8fafc; padding: 15px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #3457cb; }
                        .info p { margin: 5px 0; }
                        .content { margin-top: 20px; }
                        h1, h2, h3 { color: #24407a; }
                        table { width: 100%; border-collapse: collapse; margin: 15px 0; }
                        table th, table td { border: 1px solid #e2e8f0; padding: 8px; text-align: left; font-size: 12px; }
                        table th { background-color: #f8fafc; }
                        img { max-width: 100%; height: auto; }
                        @page { margin: 2cm; }
                    </style>
                </head>
                <body>
                    <div class="header">
                        <h1>' . htmlspecialchars($rapport->nom_rapport ?? $rapport->theme_rapport) . '</h1>
                    </div>
                    <div class="info">
                        <p><strong>Étudiant :</strong> ' . htmlspecialchars($rapport->prenom_etu . ' ' . $rapport->nom_etu) . '</p>
                        <p><strong>Email :</strong> ' . htmlspecialchars($rapport->email_etu ?? 'Non renseigné') . '</p>
                        <p><strong>Date de dépôt :</strong> ' . date('d/m/Y à H:i', strtotime($rapport->date_depot ?? $rapport->date_rapport)) . '</p>
                        <p><strong>Statut :</strong> ' . ucfirst($rapport->statut_rapport ?? 'En attente') . '</p>
                    </div>
                    <div class="content">
                        ' . $contenu . '
                    </div>
                </body>
                </html>';
                
                $options = new \Dompdf\Options();
                $options->set('isHtml5ParserEnabled', true);
                $options->set('isPhpEnabled', false);
                $options->set('isRemoteEnabled', false);
                $options->set('defaultFont', 'Arial');
                
                $dompdf = new \Dompdf\Dompdf($options);
                $dompdf->loadHtml($html);
                $dompdf->setPaper('A4', 'portrait');
                $dompdf->render();
                
                $nomRapport = $rapport->nom_rapport ?? $rapport->theme_rapport ?? 'Rapport';
                $nomEtudiant = $rapport->prenom_etu . '_' . $rapport->nom_etu;
                $nomFichier = 'Rapport_' . preg_replace('/[^a-zA-Z0-9_-]/', '_', $nomEtudiant) . '_' . date('Y-m-d') . '.pdf';
                
                header('Content-Type: application/pdf');
                header('Content-Disposition: attachment; filename="' . $nomFichier . '"');
                header('Content-Length: ' . strlen($dompdf->output()));
                
                echo $dompdf->output();
            } else {
                header('Content-Type: text/html; charset=utf-8');
                header('Content-Disposition: attachment; filename="rapport_' . $id_rapport . '.html"');
                echo $contenu;
            }
        } else {
            http_response_code(404);
            echo 'Fichier du rapport non trouvé';
        }
    } else {
        http_response_code(404);
        echo 'Rapport non trouvé';
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Évaluations | Univalid</title>
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
                        'warning': '#f59e0b',
                        'danger': '#ef4444',
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

        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 4px 16px rgba(15, 23, 42, 0.08);
            transition: all 0.3s ease;
            border: 1px solid #e2e8f0;
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
            box-shadow: 0 12px 32px rgba(15, 23, 42, 0.12);
        }

        .sidebar {
            width: 320px;
            background: white;
            box-shadow: 0 8px 32px rgba(15, 23, 42, 0.08);
            backdrop-filter: blur(10px);
            border-right: 1px solid #e2e8f0;
            border-radius: 0 20px 20px 0;
        }

        .dossier-item {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            margin-bottom: 8px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
            position: relative;
            cursor: pointer;
        }

        .dossier-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 32px rgba(15, 23, 42, 0.12);
            border-color: rgba(52, 87, 203, 0.2);
        }

        .dossier-item.active {
            background: linear-gradient(135deg, rgba(52, 87, 203, 0.1) 0%, rgba(52, 87, 203, 0.05) 100%);
            border-color: #3457cb;
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(52, 87, 203, 0.2);
        }

        .dossier-item.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: linear-gradient(to bottom, #3457cb, #36865a);
        }

        .avatar {
            background: linear-gradient(135deg, #3457cb 0%, #36865a 100%);
            color: white;
            border-radius: 12px;
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 14px;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        }

        .tab-container {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 6px;
            box-shadow: 0 4px 16px rgba(15, 23, 42, 0.08);
        }

        .tab-btn {
            padding: 12px 24px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            cursor: pointer;
        }

        .tab-active {
            background: linear-gradient(135deg, #3457cb 0%, #24407a 100%);
            color: white;
            box-shadow: 0 4px 16px rgba(52, 87, 203, 0.3);
            transform: translateY(-1px);
        }

        .tab-inactive {
            color: #64748b;
            background: #f8fafc;
        }

        .tab-inactive:hover {
            background: rgba(52, 87, 203, 0.1);
            color: #3457cb;
            transform: translateY(-1px);
        }

        .search-container {
            position: relative;
        }

        .search-input {
            background: white;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 12px 16px 12px 48px;
            transition: all 0.3s ease;
            width: 100%;
        }

        .search-input:focus {
            border-color: #3457cb;
            box-shadow: 0 0 0 3px rgba(52, 87, 203, 0.1);
            outline: none;
        }

        .btn {
            padding: 12px 24px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #3457cb 0%, #24407a 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(52, 87, 203, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(52, 87, 203, 0.4);
        }

        .btn-secondary {
            background: linear-gradient(135deg, #36865a 0%, #59bf3d 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(54, 134, 90, 0.3);
        }

        .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(54, 134, 90, 0.4);
        }

        .btn-gray {
            background: #f1f5f9;
            color: #64748b;
            border: 2px solid #e2e8f0;
        }

        .btn-gray:hover {
            background: white;
            border-color: rgba(52, 87, 203, 0.2);
            transform: translateY(-2px);
        }

        .radio-option {
            background: white;
            border: 2px solid #e2e8f0;
            border-radius: 16px;
            padding: 16px;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .radio-option:hover {
            border-color: rgba(52, 87, 203, 0.2);
            background: rgba(52, 87, 203, 0.02);
        }

        .radio-option.selected {
            border-color: #3457cb;
            background: rgba(52, 87, 203, 0.1);
        }

        .form-textarea {
            background: white;
            border: 2px solid #e2e8f0;
            border-radius: 16px;
            padding: 16px;
            transition: all 0.3s ease;
            resize: vertical;
            min-height: 120px;
            width: 100%;
        }

        .form-textarea:focus {
            outline: none;
            border-color: #3457cb;
            box-shadow: 0 0 0 3px rgba(52, 87, 203, 0.1);
        }

        .notification {
            padding: 16px 20px;
            border-radius: 16px;
            margin-bottom: 24px;
            border: 1px solid;
            box-shadow: 0 4px 16px rgba(15, 23, 42, 0.1);
        }

        .notification.success {
            background: linear-gradient(135deg, rgba(54, 134, 90, 0.1) 0%, rgba(89, 191, 61, 0.1) 100%);
            border-color: rgba(54, 134, 90, 0.2);
            color: #36865a;
        }

        .notification.error {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.1) 0%, rgba(220, 38, 38, 0.1) 100%);
            border-color: rgba(239, 68, 68, 0.2);
            color: #dc2626;
        }

        .header-gradient {
            background: linear-gradient(135deg, #24407a 0%, #3457cb 100%);
        }

        .empty-state {
            background: white;
            border-radius: 24px;
            border: 2px dashed #e2e8f0;
            padding: 48px;
            text-align: center;
        }

        .preview-container {
            border: 2px dashed #e2e8f0;
            border-radius: 16px;
            background: #f8fafc;
            min-height: 400px;
        }
    </style>
</head>

<body class="bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <!-- Header Section -->
        <div class="header bg-white rounded-3xl p-8 lg:p-12 mb-8 shadow-xl relative overflow-hidden animate-fade-in-down">
            <div class="flex items-center gap-6 md:gap-8 flex-col md:flex-row text-center md:text-left">
                <div class="header-icon bg-gradient-to-br from-primary to-primary-light text-white w-20 h-20 md:w-24 md:h-24 rounded-2xl flex items-center justify-center text-4xl md:text-5xl shadow-lg">
                    <i class="fas fa-clipboard-check"></i>
                </div>
                <div class="header-text">
                    <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 mb-2 tracking-tight">Évaluation des Rapports</h1>
                    <p class="text-lg text-gray-600 font-normal">Évaluer les rapports en attente d'examen</p>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="stat-card animate-slide-in-right" style="animation-delay: 0.1s">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-primary/10 text-primary rounded-xl flex items-center justify-center text-2xl">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <div>
                        <h3 class="text-3xl font-bold text-primary mb-1"><?= $totalRapports ?></h3>
                        <p class="text-sm font-semibold text-gray-600">Rapports Total</p>
                        <p class="text-xs text-blue-600 font-medium">
                            <i class="fas fa-info-circle mr-1"></i>Global
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="stat-card animate-slide-in-right" style="animation-delay: 0.2s">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-warning/10 text-warning rounded-xl flex items-center justify-center text-2xl">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div>
                        <h3 class="text-3xl font-bold text-warning mb-1"><?= $rapportsEnAttenteCount ?></h3>
                        <p class="text-sm font-semibold text-gray-600">En Attente</p>
                        <p class="text-xs text-orange-600 font-medium">
                            <i class="fas fa-hourglass-half mr-1"></i><?= $pourcentageEnAttente ?>% du total
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="stat-card animate-slide-in-right" style="animation-delay: 0.3s">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-secondary/10 text-secondary rounded-xl flex items-center justify-center text-2xl">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div>
                        <h3 class="text-3xl font-bold text-secondary mb-1"><?= $rapportsValides ?></h3>
                        <p class="text-sm font-semibold text-gray-600">Validés</p>
                        <p class="text-xs text-green-600 font-medium">
                            <i class="fas fa-arrow-up mr-1"></i>Acceptés
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="stat-card animate-slide-in-right" style="animation-delay: 0.4s">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-red-500/10 text-red-600 rounded-xl flex items-center justify-center text-2xl">
                        <i class="fas fa-times-circle"></i>
                    </div>
                    <div>
                        <h3 class="text-3xl font-bold text-red-600 mb-1"><?= $rapportsRejetes ?></h3>
                        <p class="text-sm font-semibold text-gray-600">Rejetés</p>
                        <p class="text-xs text-red-600 font-medium">
                            <i class="fas fa-arrow-down mr-1"></i>Refusés
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Messages -->
        <?php if ($messageSuccess): ?>
            <div class="notification success animate-fade-in-down">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-3 text-lg"></i>
                    <span class="font-semibold"><?= htmlspecialchars($messageSuccess) ?></span>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($messageErreur): ?>
            <div class="notification error animate-fade-in-down">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle mr-3 text-lg"></i>
                    <span class="font-semibold"><?= htmlspecialchars($messageErreur) ?></span>
                </div>
            </div>
        <?php endif; ?>

        <!-- Main Content Area -->
        <div class="flex gap-8">
            <!-- Sidebar -->
            <aside class="sidebar p-6 flex-shrink-0 overflow-y-auto animate-scale-in">
                <div class="mb-8">
                    <h2 class="text-3xl font-bold text-primary mb-2">📂 Dossiers</h2>
                    <p class="text-sm text-gray-600">Rapports en attente d'évaluation</p>
                </div>

                <div class="mb-6">
                    <div class="search-container">
                        <input type="text" id="searchInput" placeholder="Rechercher un dossier..."
                            class="search-input" />
                        <svg class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>

                <ul id="dossierList" class="space-y-2">
                    <?php if (!empty($rapportsEnAttente)): ?>
                        <?php foreach ($rapportsEnAttente as $index => $rapport): 
                            $initiales = strtoupper(substr($rapport->prenom_etu, 0, 1) . substr($rapport->nom_etu, 0, 1));
                            $dateDepot = date('d/m/Y', strtotime($rapport->date_rapport));
                            $isActive = ($selectedRapport && $selectedRapport->id_rapport == $rapport->id_rapport);
                        ?>
                        <li>
                            <div class="dossier-item <?= $isActive ? 'active' : '' ?> p-4" onclick="selectDossier(<?= $rapport->id_rapport ?>)">
                                <div class="flex items-center space-x-4">
                                    <div class="avatar">
                                        <?= $initiales ?>
                                    </div>
                                    <div class="flex-1 overflow-hidden">
                                        <div class="text-sm font-semibold text-gray-900 truncate"><?= htmlspecialchars($rapport->prenom_etu . ' ' . $rapport->nom_etu) ?></div>
                                        <div class="text-xs text-gray-500 truncate"><?= htmlspecialchars($rapport->nom_rapport ?? $rapport->theme_rapport) ?></div>
                                        <div class="text-xs text-secondary mt-1">📅 <?= $dateDepot ?></div>
                                    </div>
                                    <?php if ($isActive): ?>
                                    <svg class="h-5 w-5 text-primary flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li>
                            <div class="empty-state text-center py-8">
                                <div class="text-4xl mb-4">📂</div>
                                <p class="text-gray-500">Aucun dossier en attente</p>
                            </div>
                        </li>
                    <?php endif; ?>
                </ul>
            </aside>

            <!-- Main Content -->
            <main class="flex-1 animate-scale-in" style="animation-delay: 0.2s">
                <div class="flex flex-col lg:flex-row lg:justify-between items-start lg:items-center mb-8">
                    <div class="mb-6 lg:mb-0">
                        <h1 class="text-4xl font-bold text-primary mb-2">
                            <?= $selectedRapport ? htmlspecialchars($selectedRapport->prenom_etu . ' ' . $selectedRapport->nom_etu) : 'Aucun dossier sélectionné' ?>
                        </h1>
                        <p class="text-xl text-gray-600"><?= $selectedRapport ? htmlspecialchars($selectedRapport->nom_rapport ?? $selectedRapport->theme_rapport) : 'Sélectionnez un dossier dans la liste' ?></p>
                    </div>

                    <div class="tab-container flex space-x-2">
                        <button id="tab-info" class="tab-btn tab-active" onclick="switchTab('info')">
                            📋 Informations
                        </button>
                        <button id="tab-preview" class="tab-btn tab-inactive" onclick="switchTab('preview')">
                            👁️ Aperçu
                        </button>
                        <button id="tab-decision" class="tab-btn tab-inactive" onclick="switchTab('decision')">
                            ⚖️ Décision
                        </button>
                    </div>
                </div>

                <!-- Tab Content -->
                <section id="content-info" class="animate-fade-in-down">
                    <?php if ($selectedRapport): ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div class="card p-6">
                            <div class="text-sm text-gray-500 mb-2 font-medium">👤 Étudiant</div>
                            <div class="font-bold text-primary text-xl"><?= htmlspecialchars($selectedRapport->prenom_etu . ' ' . $selectedRapport->nom_etu) ?></div>
                        </div>

                        <div class="card p-6">
                            <div class="text-sm text-gray-500 mb-2 font-medium">✉️ Email</div>
                            <div class="font-semibold text-gray-800"><?= htmlspecialchars($selectedRapport->email_etu ?? 'Non renseigné') ?></div>
                        </div>

                        <div class="card p-6">
                            <div class="text-sm text-gray-500 mb-2 font-medium">🎓 Promotion</div>
                            <div class="font-bold text-primary text-xl"><?= htmlspecialchars($selectedRapport->promotion_etu ?? '2024-2025') ?></div>
                        </div>

                        <div class="card p-6">
                            <div class="text-sm text-gray-500 mb-2 font-medium">📅 Déposé le</div>
                            <div class="font-bold text-gray-800 text-xl"><?= date('d/m/Y', strtotime($selectedRapport->date_depot ?? $selectedRapport->date_rapport)) ?></div>
                        </div>
                    </div>

                    <div class="card p-8">
                        <div class="text-sm text-gray-500 mb-4 font-medium">📝 Sujet du Rapport</div>
                        <div class="font-semibold text-primary text-2xl leading-relaxed">
                            <?= htmlspecialchars($selectedRapport->theme_rapport ?? $selectedRapport->nom_rapport ?? 'Sujet non renseigné') ?>
                        </div>
                    </div>
                    <?php else: ?>
                    <div class="empty-state">
                        <div class="text-6xl mb-4">📋</div>
                        <h3 class="text-2xl font-bold text-gray-700 mb-2">Aucun dossier sélectionné</h3>
                        <p class="text-gray-500">Sélectionnez un dossier dans la liste de gauche pour commencer l'évaluation.</p>
                    </div>
                    <?php endif; ?>
                </section>

                <section id="content-preview" class="hidden animate-fade-in-down">
                    <?php if ($selectedRapport): ?>
                    <div class="card p-8">
                        <h3 class="text-2xl font-bold text-primary mb-6 flex items-center">
                            <span class="text-2xl mr-3">👁️</span>
                            Aperçu du Rapport
                        </h3>

                        <div class="mb-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <div class="text-sm text-gray-600 mb-1">📄 Nom du fichier</div>
                                    <div class="font-semibold text-gray-800">
                                        <?= htmlspecialchars($selectedRapport->chemin_fichier ?? 'rapport_' . $selectedRapport->id_rapport . '.html') ?>
                                    </div>
                                </div>
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <div class="text-sm text-gray-600 mb-1">📊 Statut</div>
                                    <div class="font-semibold">
                                        <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm">
                                            En attente d'évaluation
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="preview-container overflow-hidden mb-6 min-h-96 flex items-center justify-center">
                            <div id="preview-content" class="text-center">
                                <div class="w-20 h-20 rounded-full bg-gradient-to-br from-primary to-secondary flex items-center justify-center mb-4 mx-auto">
                                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <p class="text-xl font-semibold mb-2">Document disponible</p>
                                <p class="text-gray-600"><?= htmlspecialchars($selectedRapport->nom_rapport ?? 'Rapport_' . $selectedRapport->prenom_etu . '_' . $selectedRapport->nom_etu . '_2025') ?></p>
                                <p class="text-sm text-gray-500 mt-2">Cliquez sur "Prévisualiser" pour voir le contenu</p>
                            </div>
                        </div>

                        <div class="flex justify-end space-x-4">
                            <button class="btn btn-gray" onclick="previewRapport(<?= $selectedRapport->id_rapport ?>)">
                                👁️ Prévisualiser
                            </button>
                            <button class="btn btn-primary" onclick="downloadRapportPDF(<?= $selectedRapport->id_rapport ?>)">
                                <i class="fas fa-download"></i>
                                Télécharger PDF
                            </button>
                        </div>
                    </div>
                    <?php else: ?>
                    <div class="empty-state">
                        <div class="text-6xl mb-4">👁️</div>
                        <h3 class="text-2xl font-bold text-gray-700 mb-2">Aucun dossier sélectionné</h3>
                        <p class="text-gray-500">Sélectionnez un dossier pour voir l'aperçu du rapport.</p>
                    </div>
                    <?php endif; ?>
                </section>

                <section id="content-decision" class="hidden animate-fade-in-down">
                    <?php if ($selectedRapport): ?>
                    <div class="max-w-2xl mx-auto">
                        <div class="card p-8">
                            <div class="text-center mb-8">
                                <h3 class="text-3xl font-bold text-primary mb-2">Prendre une Décision</h3>
                                <p class="text-gray-600">Évaluez le rapport et prenez une décision</p>
                            </div>

                            <form method="POST" class="space-y-6">
                                <input type="hidden" name="id_rapport" value="<?= $selectedRapport->id_rapport ?>">

                                <div class="space-y-4">
                                    <div class="radio-option" onclick="selectOption(this, 'valider')">
                                        <label class="flex items-center text-lg cursor-pointer">
                                            <input type="radio" name="decision" value="valider" class="mr-4 h-5 w-5 text-secondary">
                                            <div>
                                                <div class="font-semibold text-secondary">✅ Valider le rapport</div>
                                                <div class="text-sm text-gray-500 mt-1">Le rapport répond aux critères et peut être accepté</div>
                                            </div>
                                        </label>
                                    </div>

                                    <div class="radio-option" onclick="selectOption(this, 'rejeter')">
                                        <label class="flex items-center text-lg cursor-pointer">
                                            <input type="radio" name="decision" value="rejeter" class="mr-4 h-5 w-5 text-red-500">
                                            <div>
                                                <div class="font-semibold text-red-600">❌ Rejeter le rapport</div>
                                                <div class="text-sm text-gray-500 mt-1">Le rapport nécessite des améliorations</div>
                                            </div>
                                        </label>
                                    </div>
                                </div>

                                <div>
                                    <label for="commentaire" class="block font-semibold text-primary mb-3 text-lg">
                                        💬 Commentaire pour l'étudiant
                                    </label>
                                    <textarea id="commentaire" name="commentaire"
                                        class="form-textarea"
                                        placeholder="Ajoutez vos observations, suggestions ou félicitations..."></textarea>
                                </div>

                                <div class="flex justify-end space-x-4 pt-6">
                                    <button type="button" class="btn btn-gray">
                                        Annuler
                                    </button>
                                    <button type="submit" class="btn btn-secondary">
                                        <i class="fas fa-check"></i>
                                        Soumettre la Décision
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <?php else: ?>
                    <div class="empty-state">
                        <div class="text-6xl mb-4">⚖️</div>
                        <h3 class="text-2xl font-bold text-gray-700 mb-2">Aucun dossier sélectionné</h3>
                        <p class="text-gray-500">Sélectionnez un dossier pour prendre une décision d'évaluation.</p>
                    </div>
                    <?php endif; ?>
                </section>
            </main>
        </div>
    </div>

    <script>
        function switchTab(tab) {
            const tabs = ['info', 'preview', 'decision'];

            tabs.forEach(t => {
                const tabBtn = document.getElementById('tab-' + t);
                const contentSection = document.getElementById('content-' + t);

                if (t === tab) {
                    tabBtn.classList.add('tab-active');
                    tabBtn.classList.remove('tab-inactive');
                    contentSection.classList.remove('hidden');
                } else {
                    tabBtn.classList.remove('tab-active');
                    tabBtn.classList.add('tab-inactive');
                    contentSection.classList.add('hidden');
                }
            });
        }

        function selectOption(element, value) {
            document.querySelectorAll('.radio-option').forEach(option => {
                option.classList.remove('selected');
            });

            element.classList.add('selected');

            const radioInput = element.querySelector('input[type="radio"]');
            if (radioInput) {
                radioInput.checked = true;
            }
        }

        function selectDossier(rapportId) {
            const currentUrl = window.location.href.split('?')[0];
            const currentParams = new URLSearchParams(window.location.search);
            currentParams.set('rapport_id', rapportId);
            window.location.href = currentUrl + '?' + currentParams.toString();
        }

        function previewRapport(rapportId) {
            const previewContent = document.getElementById('preview-content');
            
            previewContent.innerHTML = `
                <div class="flex flex-col items-center justify-center h-64">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary mb-4"></div>
                    <p class="text-gray-500">Chargement de la prévisualisation...</p>
                </div>
            `;

            const iframe = document.createElement('iframe');
            iframe.src = `?page=evaluations_dossiers_soutenance&action=preview&id=${rapportId}`;
            iframe.style.width = '100%';
            iframe.style.height = '500px';
            iframe.style.border = 'none';
            iframe.style.borderRadius = '12px';
            
            iframe.onload = function() {
                previewContent.innerHTML = '';
                previewContent.appendChild(iframe);
            };

            iframe.onerror = function() {
                previewContent.innerHTML = `
                    <div class="h-64 flex flex-col items-center justify-center text-red-500">
                        <i class="fas fa-exclamation-triangle text-4xl mb-4"></i>
                        <p>Erreur lors du chargement de la prévisualisation</p>
                    </div>
                `;
            };
        }

        function downloadRapportPDF(rapportId) {
            showToast('Génération du PDF en cours...', 'info');
            
            const downloadUrl = `?page=evaluations_dossiers_soutenance&action=download_pdf&id=${rapportId}`;
            
            const link = document.createElement('a');
            link.href = downloadUrl;
            link.download = '';
            link.style.display = 'none';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            
            setTimeout(() => {
                showToast('Téléchargement initié', 'success');
            }, 1000);
        }

        function showToast(message, type = 'info') {
            const toast = document.createElement('div');
            toast.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg max-w-sm transform transition-all duration-300 translate-x-full`;

            const bgColor = type === 'success' ? 'bg-gradient-to-r from-secondary to-secondary-light' :
                type === 'error' ? 'bg-gradient-to-r from-red-500 to-red-600' :
                'bg-gradient-to-r from-primary to-primary-light';

            toast.className += ` ${bgColor} text-white`;
            toast.innerHTML = `
                <div class="flex items-center">
                    <span class="mr-2">${type === 'success' ? '✅' : type === 'error' ? '❌' : 'ℹ️'}</span>
                    <span>${message}</span>
                    <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;

            document.body.appendChild(toast);

            setTimeout(() => toast.classList.remove('translate-x-full'), 100);

            setTimeout(() => {
                toast.classList.add('translate-x-full');
                setTimeout(() => toast.remove(), 300);
            }, 4000);
        }

        // Search functionality
        document.getElementById('searchInput')?.addEventListener('keyup', function() {
            const filter = this.value.toLowerCase();
            const dossiers = document.querySelectorAll('.dossier-item');
            
            dossiers.forEach(dossier => {
                const text = dossier.textContent.toLowerCase();
                dossier.style.display = text.includes(filter) ? '' : 'none';
            });
        });

        // Keyboard shortcuts
        document.addEventListener('keydown', (e) => {
            if (e.ctrlKey || e.metaKey) {
                switch (e.key) {
                    case '1':
                        e.preventDefault();
                        switchTab('info');
                        break;
                    case '2':
                        e.preventDefault();
                        switchTab('preview');
                        break;
                    case '3':
                        e.preventDefault();
                        switchTab('decision');
                        break;
                }
            }
        });

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            // Animation des cartes
            const cards = document.querySelectorAll('.stat-card, .card');
            cards.forEach((card, index) => {
                card.style.animationDelay = `${index * 0.1}s`;
            });
        });
    </script>
</body>

</html>
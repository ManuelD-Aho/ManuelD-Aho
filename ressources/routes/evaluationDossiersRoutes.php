<?php
if (isset($_GET['page']) && $_GET['page'] === 'evaluations_dossiers_soutenance') {
    require_once __DIR__ . '/../../app/controllers/EvaluationDossiersController.php';
    $controller = new EvaluationDossiersController();
    
    // Si on demande un fichier de rapport
    if (isset($_GET['fichier'])) {
        $id_rapport = $_GET['fichier'];
        $cheminFichier = __DIR__ . "/../uploads/rapports/rapport_{$id_rapport}.html";
        
        if (file_exists($cheminFichier)) {
            header('Content-Type: text/html; charset=utf-8');
            header('Content-Disposition: inline; filename="rapport_' . $id_rapport . '.html"');
            readfile($cheminFichier);
            exit;
        } else {
            http_response_code(404);
            echo "Fichier non trouvé";
            exit;
        }
    }
    
    // Si c'est une action AJAX (traitement de décision) - gérer GET et POST
    if (isset($_GET['action']) && $_GET['action'] === 'traiter_decision') {
        header('Content-Type: application/json');
        
        // Debug: log les paramètres reçus
        error_log("DEBUG: Action traiter_decision appelée");
        error_log("DEBUG: Méthode HTTP: " . $_SERVER['REQUEST_METHOD']);
        error_log("DEBUG: GET params: " . print_r($_GET, true));
        error_log("DEBUG: POST params: " . print_r($_POST, true));
        
        $controller->traiterAction();
        exit;
    }
    
    // Toujours récupérer les statistiques et la liste des dossiers
    $data = $controller->index();
    $stats = $data['stats'];
    $dossiers = $data['dossiers'];
    
    // Si on demande le détail d'un dossier, le récupérer en plus
    if (isset($_GET['detail'])) {
        $detail = $controller->detail($_GET['detail']);
    }
} 
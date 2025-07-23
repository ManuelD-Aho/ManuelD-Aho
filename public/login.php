<?php
session_start();
require_once __DIR__ . '/../app/config/database.php';
require_once __DIR__ . '/../app/controllers/AuthController.php';
require_once __DIR__ . '/../app/controllers/MenuController.php';


$authController = new AuthController(Database::getConnection());
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    if ($authController->login($email, $password)) {
        // Récupérer les traitements autorisés pour l'utilisateur
        $menuController = new MenuController();
        $traitements = $menuController->genererMenu($_SESSION['id_GU']);

        // Utiliser le premier traitement comme page par défaut
        $defaultPage = !empty($traitements) ? $traitements[0]['lib_traitement'] : 'dashboard';

        header('Location: layout.php?page=' . urlencode($defaultPage));
        exit;
    } else {
        $_SESSION['error'] = 'Login ou mot de passe incorrect';
        header('Location: page_connexion.php');
        exit;
    }
}
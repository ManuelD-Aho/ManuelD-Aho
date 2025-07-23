<?php
session_start();
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/config/database.php';
require_once __DIR__ . '/../app/utils/EmailService.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée.']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$email = $data['email'] ?? null;

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Adresse e-mail invalide.']);
    exit;
}

try {
    $db = Database::getConnection();

    // 1. Vérifier si l'utilisateur existe
    $stmt = $db->prepare("SELECT id_utilisateur, nom_utilisateur FROM utilisateur WHERE login_utilisateur = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        // Pour des raisons de sécurité, on ne dit pas si l'email existe ou non.
        echo json_encode(['success' => true, 'message' => 'Si un compte est associé à cet email, un lien de réinitialisation a été envoyé.']);
        exit;
    }

    // 2. Générer un token sécurisé
    $token = bin2hex(random_bytes(32));
    $expiresAt = (new DateTime())->modify('+1 hour')->format('Y-m-d H:i:s');

    // 3. Stocker le token dans la base de données
    $stmt = $db->prepare("UPDATE utilisateur SET reset_token = ?, reset_token_expires_at = ? WHERE id_utilisateur = ?");
    $stmt->execute([$token, $expiresAt, $user['id_utilisateur']]);

    // 4. Envoyer l'e-mail
    $resetLink = "http://localhost:8080/reset_password.php?token=" . $token; // Page à créer
    $emailService = new EmailService();

    $subject = "Réinitialisation de votre mot de passe - Univalid";
    $body = "Bonjour " . htmlspecialchars($user['nom_utilisateur']) . ",<br><br>"
        . "Vous avez demandé une réinitialisation de mot de passe. Cliquez sur le lien ci-dessous pour continuer :<br>"
        . "<a href='" . $resetLink . "'>" . $resetLink . "</a><br><br>"
        . "Ce lien expirera dans une heure.<br><br>"
        . "Si vous n'êtes pas à l'origine de cette demande, veuillez ignorer cet e-mail.";

    if ($emailService->sendEmail($email, $subject, $body, true)) {
        echo json_encode(['success' => true, 'message' => 'Un lien de réinitialisation a été envoyé à votre adresse e-mail.']);
    } else {
        throw new Exception("L'envoi de l'e-mail a échoué.");
    }

} catch (Exception $e) {
    error_log("Erreur de réinitialisation de mot de passe: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Une erreur est survenue. Veuillez réessayer plus tard.']);
}
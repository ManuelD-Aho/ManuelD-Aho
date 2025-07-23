<?php
session_start();
require_once __DIR__ . '/../app/config/database.php';
require_once __DIR__ . '/../app/models/Utilisateur.php';

$db = Database::getConnection();
$utilisateurModel = new Utilisateur($db);

$token = $_GET['token'] ?? null;
$error = null;
$success = null;
$showForm = false;
$user = null;

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (!$token) {
        $error = "Aucun jeton de réinitialisation fourni.";
    } else {
        $user = $utilisateurModel->findByResetToken($token);
        if (!$user) {
            $error = "Ce lien de réinitialisation est invalide ou a expiré.";
        } else {
            $showForm = true;
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'] ?? null;
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    $user = $utilisateurModel->findByResetToken($token);

    if (!$user) {
        $error = "Le jeton de réinitialisation est invalide ou a expiré.";
    } elseif (empty($password) || empty($confirm_password)) {
        $error = "Veuillez remplir les deux champs.";
        $showForm = true; 
    } elseif ($password !== $confirm_password) {
        $error = "Les mots de passe ne correspondent pas.";
        $showForm = true;
    } elseif (strlen($password) < 8) {
        $error = "Le mot de passe doit contenir au moins 8 caractères.";
        $showForm = true;
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        if ($utilisateurModel->updatePasswordById($user->id_utilisateur, $hashedPassword)) {
            $success = "Votre mot de passe a été réinitialisé avec succès !";
        } else {
            $error = "Une erreur est survenue. Veuillez réessayer.";
            $showForm = true;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr" data-theme="corporate">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialiser le Mot de Passe</title>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5/dist/full.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-base-200 flex items-center justify-center p-4">

<div class="card w-full max-w-md shadow-2xl bg-base-100">
    <div class="card-body">
        <?php if ($showForm): ?>
            <h2 class="card-title justify-center text-2xl">Réinitialiser le mot de passe</h2>
            <p class="text-center">Veuillez saisir votre nouveau mot de passe.</p>

            <?php if ($error): ?>
                <div role="alert" class="alert alert-error">
                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <span><?php echo htmlspecialchars($error); ?></span>
                </div>
            <?php endif; ?>

            <form method="POST" action="reset_password.php" class="space-y-4">
                <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                <div class="form-control">
                    <label class="label"><span class="label-text">Nouveau mot de passe</span></label>
                    <input type="password" name="password" class="input input-bordered" required>
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text">Confirmer le mot de passe</span></label>
                    <input type="password" name="confirm_password" class="input input-bordered" required>
                </div>
                <div class="form-control mt-6">
                    <button type="submit" class="btn btn-primary">Mettre à jour</button>
                </div>
            </form>

        <?php elseif ($success): ?>
            <div class="text-center">
                <h2 class="card-title justify-center text-2xl text-success">Succès !</h2>
                <p><?php echo htmlspecialchars($success); ?></p>
                <div class="card-actions justify-center mt-4">
                    <a href="page_connexion.php" class="btn btn-primary">Se connecter</a>
                </div>
            </div>

        <?php else: // Error case ?>
            <div class="text-center">
                 <h2 class="card-title justify-center text-2xl text-error">Erreur</h2>
                 <p><?php echo htmlspecialchars($error); ?></p>
                 <div class="card-actions justify-center mt-4">
                    <a href="forgot_password.php" class="btn btn-primary">Demander un nouveau lien</a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
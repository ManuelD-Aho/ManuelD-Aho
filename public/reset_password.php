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

// --- GESTION DE LA REQUÊTE GET (Validation du token) ---
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (!$token) {
        $error = "Aucun jeton de réinitialisation fourni. Veuillez refaire une demande.";
    } else {
        $user = $utilisateurModel->findByResetToken($token);
        if (!$user) {
            $error = "Ce lien de réinitialisation est invalide ou a expiré. Veuillez refaire une demande.";
        } else {
            $showForm = true;
        }
    }
}

// --- GESTION DE LA REQUÊTE POST (Soumission du nouveau mot de passe) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'] ?? null;
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    $user = $utilisateurModel->findByResetToken($token);

    if (!$user) {
        $error = "Le jeton de réinitialisation est invalide ou a expiré. La session a peut-être expiré pendant que vous saisissiez votre mot de passe.";
    } elseif (empty($password) || empty($confirm_password)) {
        $error = "Veuillez remplir les deux champs de mot de passe.";
        $showForm = true; // Afficher à nouveau le formulaire
    } elseif ($password !== $confirm_password) {
        $error = "Les mots de passe ne correspondent pas.";
        $showForm = true; // Afficher à nouveau le formulaire
    } elseif (strlen($password) < 8) {
        $error = "Le mot de passe doit contenir au moins 8 caractères.";
        $showForm = true; // Afficher à nouveau le formulaire
    } else {
        // Tout est valide, on met à jour le mot de passe
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        if ($utilisateurModel->updatePasswordById($user->id_utilisateur, $hashedPassword)) {
            $success = "Votre mot de passe a été réinitialisé avec succès ! Vous pouvez maintenant vous connecter.";
        } else {
            $error = "Une erreur est survenue lors de la mise à jour de votre mot de passe. Veuillez réessayer.";
            $showForm = true; // Afficher à nouveau le formulaire
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialiser le Mot de Passe - Univalid</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary': '#27A062', 'primary-dark': '#0B3C32', 'secondary': '#2F53CD',
                        'secondary-light': '#60A5FA', 'accent': '#2F54CC', 'yellow-custom': '#FFD700',
                        'yellow-bright': '#FFEB3B'
                    },
                    animation: {
                        'float': 'float 3s ease-in-out infinite',
                        'pulse-slow': 'pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                        'slide-in': 'slideIn 0.6s ease-out',
                        'bounce-in': 'bounceIn 0.8s ease-out'
                    }
                }
            }
        }
    </script>
    <style>
        .hero-gradient { background: linear-gradient(135deg, #33A74F 0%, #375BCE 100%); }
        @keyframes float { 0%, 100% { transform: translateY(0px); } 50% { transform: translateY(-15px); } }
        @keyframes slideIn { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes bounceIn { 0% { opacity: 0; transform: scale(0.3); } 50% { opacity: 1; transform: scale(1.05); } 70% { transform: scale(0.9); } 100% { opacity: 1; transform: scale(1); } }
        .input-group { position: relative; }
        .input-group input:focus + label, .input-group input:not(:placeholder-shown) + label { transform: translateY(-24px) scale(0.85); color: #000; }
        .input-group label { position: absolute; left: 16px; top: 16px; color: #6B7280; transition: all 0.2s ease; pointer-events: none; background: white; padding: 0 4px; }
        .form-container { backdrop-filter: blur(20px); background: rgba(255, 255, 255, 0.95); border: 1px solid rgba(255, 255, 255, 0.2); }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-gray-100 font-sans overflow-hidden">
<!-- Background Elements -->
<div class="fixed inset-0 pointer-events-none">
    <div class="absolute -top-40 -right-40 w-96 h-96 bg-primary rounded-full opacity-20 animate-pulse-slow"></div>
    <div class="absolute -bottom-20 -left-20 w-64 h-64 border-4 border-gray-200 transform rotate-45 opacity-30"></div>
</div>

<!-- Main Content -->
<div class="relative z-10 flex items-center justify-center min-h-screen py-20 px-6">
    <div class="w-full max-w-md mx-auto">
        <div class="form-container rounded-3xl p-8 lg:p-12 shadow-2xl animate-slide-in">

            <!-- Affichage du formulaire si le token est valide -->
            <?php if ($showForm): ?>
                <div class="text-center mb-8">
                    <div class="w-16 h-16 bg-primary rounded-full mx-auto mb-4 flex items-center justify-center">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.623 5.873M17 17a6 6 0 01-6 5.873A6 6 0 015.127 17M12 17a2 2 0 012-2m-2 2a2 2 0 00-2-2m2 2a2 2 0 01-2-2m2 2a2 2 0 002-2m-2 2a2 2 0 01-2-2m0 0a2 2 0 012-2m-2 2a2 2 0 00-2-2m2 2a2 2 0 01-2-2"></path></svg>
                    </div>
                    <h1 class="text-3xl font-bold mb-2 text-primary">Réinitialiser le mot de passe</h1>
                    <p class="text-gray-600">Veuillez saisir votre nouveau mot de passe.</p>
                </div>

                <?php if ($error): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
                        <span class="block sm:inline"><?php echo htmlspecialchars($error); ?></span>
                    </div>
                <?php endif; ?>

                <form method="POST" action="reset_password.php" class="space-y-6">
                    <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">

                    <div class="input-group">
                        <input type="password" id="password" name="password" placeholder=" " class="w-full px-4 py-4 border-2 border-gray-200 rounded-xl focus:border-green-400 focus:outline-none transition-all duration-200 bg-white/50" required>
                        <label for="password">Nouveau mot de passe</label>
                    </div>

                    <div class="input-group">
                        <input type="password" id="confirm_password" name="confirm_password" placeholder=" " class="w-full px-4 py-4 border-2 border-gray-200 rounded-xl focus:border-green-400 focus:outline-none transition-all duration-200 bg-white/50" required>
                        <label for="confirm_password">Confirmer le mot de passe</label>
                    </div>

                    <button type="submit" class="w-full bg-primary text-white py-4 rounded-xl font-semibold hover:bg-green-700 transition-all duration-300 hover:scale-105 transform hover:shadow-lg">
                        Mettre à jour le mot de passe
                    </button>
                </form>

                <!-- Affichage d'un message d'erreur si le token est invalide -->
            <?php elseif ($error): ?>
                <div class="text-center">
                    <div class="w-20 h-20 bg-red-100 rounded-full mx-auto mb-6 flex items-center justify-center animate-bounce-in">
                        <svg class="w-10 h-10 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h2 class="text-2xl font-bold mb-4 text-red-600">Lien Invalide</h2>
                    <p class="text-gray-600 mb-8"><?php echo htmlspecialchars($error); ?></p>
                    <a href="forgot_password.php" class="w-full block bg-primary text-white py-3 rounded-xl font-semibold text-center hover:bg-green-700 transition-all duration-300">
                        Demander un nouveau lien
                    </a>
                </div>

                <!-- Affichage d'un message de succès -->
            <?php elseif ($success): ?>
                <div class="text-center">
                    <div class="w-20 h-20 bg-green-100 rounded-full mx-auto mb-6 flex items-center justify-center animate-bounce-in">
                        <svg class="w-10 h-10 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <h2 class="text-2xl font-bold mb-4 text-green-600">Succès !</h2>
                    <p class="text-gray-600 mb-8"><?php echo htmlspecialchars($success); ?></p>
                    <a href="page_connexion.php" class="w-full block bg-primary text-white py-3 rounded-xl font-semibold text-center hover:bg-green-700 transition-all duration-300">
                        Aller à la page de connexion
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>
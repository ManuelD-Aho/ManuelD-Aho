<?php
// VOTRE LOGIQUE PHP EST CONSERVÉE À L'IDENTIQUE
$nom_user = $_SESSION['nom_utilisateur'] ?? 'N/A';
$login_user = $_SESSION['login_utilisateur'] ?? 'N/A';
$libelle_type_utilisateur = $_SESSION['type_utilisateur'] ?? 'N/A';
$libelle_niveau_acces = $_SESSION['niveau_acces'] ?? 'N/A';
$libelle_GU = $_SESSION['lib_GU'] ?? 'N/A';

// Informations spécifiques
$specialite = $_SESSION['specialite'] ?? 'Non spécifiée';
$grade = $_SESSION['grade'] ?? 'Non spécifié';
$fonction = $_SESSION['fonction'] ?? 'Non spécifiée';
$date_grade = !empty($_SESSION['date_grade']) ? date('d/m/Y', strtotime($_SESSION['date_grade'])) : 'N/A';
$date_fonction = !empty($_SESSION['date_fonction']) ? date('d/m/Y', strtotime($_SESSION['date_fonction'])) : 'N/A';
$telephone = $_SESSION['telephone'] ?? 'Non spécifié';
$poste = $_SESSION['poste'] ?? 'Non spécifié';
$date_embauche = !empty($_SESSION['date_embauche']) ? date('d/m/Y', strtotime($_SESSION['date_embauche'])) : 'N/A';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Profil</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>

<body class="bg-gray-100 text-gray-800" x-data="{ currentTab: '<?php echo isset($_GET['tab']) && $_GET['tab'] === 'password' ? 'password' : 'profile'; ?>' }">
    <div class="container max-w-4xl mx-auto px-4 py-12">
        
        <header class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Mon Profil</h1>
            <p class="text-md text-gray-600">Gérez vos informations personnelles et votre mot de passe.</p>
        </header>

        <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
            <div class="border-b border-gray-200">
                <nav class="flex -mb-px px-6" aria-label="Tabs">
                    <button @click="currentTab = 'profile'" :class="{'border-blue-500 text-blue-600': currentTab === 'profile', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': currentTab !== 'profile'}" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200">
                        <i class="fa-solid fa-user-gear mr-2"></i>Informations
                    </button>
                    <button @click="currentTab = 'password'" :class="{'border-blue-500 text-blue-600': currentTab === 'password', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': currentTab !== 'password'}" class="whitespace-nowrap ml-8 py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200">
                        <i class="fa-solid fa-key mr-2"></i>Mot de passe
                    </button>
                </nav>
            </div>

            <div x-show="currentTab === 'profile'" x-transition:enter="transition-opacity ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                <div class="p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Informations du compte</h3>
                    <dl class="divide-y divide-gray-100">
                        <?php
                        function render_profile_item($label, $value, $icon_class = 'fa-solid fa-circle-question') {
                            echo '<div class="px-2 py-4 sm:grid sm:grid-cols-3 sm:gap-4">';
                            echo '<dt class="text-sm font-medium text-gray-600 flex items-center"><i class="' . $icon_class . ' w-5 text-center text-gray-400 mr-2"></i>' . $label . '</dt>';
                            echo '<dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">' . htmlspecialchars($value) . '</dd>';
                            echo '</div>';
                        }
                        render_profile_item("Nom d'utilisateur", $nom_user, 'fa-solid fa-user');
                        render_profile_item("Adresse e-mail (Login)", $login_user, 'fa-solid fa-at');
                        render_profile_item("Type de compte", $libelle_type_utilisateur, 'fa-solid fa-user-tag');
                        render_profile_item("Niveau d'accès", $libelle_niveau_acces, 'fa-solid fa-shield-halved');
                        render_profile_item("Groupe Utilisateur", $libelle_GU, 'fa-solid fa-users');
                        ?>
                    </dl>
                </div>

                <?php if ($libelle_type_utilisateur === 'Enseignant simple' || $libelle_type_utilisateur === 'Enseignant administratif'): ?>
                <div class="p-6 border-t border-gray-200">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Informations Professionnelles (Enseignant)</h3>
                    <dl class="divide-y divide-gray-100">
                        <?php
                        render_profile_item("Spécialité", $specialite, 'fa-solid fa-microscope');
                        render_profile_item("Grade", $grade, 'fa-solid fa-award');
                        render_profile_item("Date d'obtention du grade", $date_grade, 'fa-solid fa-calendar-check');
                        render_profile_item("Fonction", $fonction, 'fa-solid fa-briefcase');
                        render_profile_item("Date d'occupation de la fonction", $date_fonction, 'fa-solid fa-calendar-day');
                        ?>
                    </dl>
                </div>
                <?php elseif ($libelle_type_utilisateur === 'Personnel administratif'): ?>
                <div class="p-6 border-t border-gray-200">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Informations Professionnelles (Personnel)</h3>
                    <dl class="divide-y divide-gray-100">
                        <?php
                        render_profile_item("Téléphone", $telephone, 'fa-solid fa-phone');
                        render_profile_item("Poste occupé", $poste, 'fa-solid fa-desktop');
                        render_profile_item("Date d'embauche", $date_embauche, 'fa-solid fa-calendar-plus');
                        ?>
                    </dl>
                </div>
                <?php endif; ?>
            </div>

            <div x-show="currentTab === 'password'" x-transition:enter="transition-opacity ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                <form action="?page=profil&tab=password" method="POST">
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-6">Changer mon mot de passe</h3>

                        <?php if (isset($_SESSION['password_error'])): ?>
                        <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 border border-red-200" role="alert">
                            <?= htmlspecialchars($_SESSION['password_error']); unset($_SESSION['password_error']); ?>
                        </div>
                        <?php endif; ?>

                        <?php if (isset($_SESSION['password_success'])): ?>
                        <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 border border-green-200" role="alert">
                            <?= htmlspecialchars($_SESSION['password_success']); unset($_SESSION['password_success']); ?>
                        </div>
                        <?php endif; ?>

                        <input type="hidden" name="id_utilisateur" value="<?= $_SESSION['id_utilisateur'] ?? ''; ?>">
                        <div class="space-y-6">
                            <div>
                                <label for="currentPassword" class="block text-sm font-medium text-gray-700">Mot de passe actuel</label>
                                <input id="currentPassword" type="password" name="currentPassword" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="newPassword" class="block text-sm font-medium text-gray-700">Nouveau mot de passe</label>
                                    <input id="newPassword" type="password" name="newPassword" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <div>
                                    <label for="confirmPassword" class="block text-sm font-medium text-gray-700">Confirmer le mot de passe</label>
                                    <input id="confirmPassword" type="password" name="confirmPassword" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 text-right">
                        <button type="submit" name="update_password" class="bg-blue-600 text-white font-semibold px-5 py-2.5 rounded-md hover:bg-blue-700 transition-colors duration-300">
                            Mettre à jour
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    // Le JS pour la gestion du changement d'onglet via l'URL est conservé
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('tab') === 'password') {
            // Alpine.js s'en charge déjà grâce à l'initialisation de x-data
            // Pas besoin de code supplémentaire ici.
        }
    });
    </script>
</body>
</html>
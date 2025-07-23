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
    <title>Mon Profil | Univalid</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
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

        .btn {
            padding: 12px 24px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            border: none;
            cursor: pointer;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            transition: all 0.3s ease;
        }

        .btn:hover::before {
            width: 300px;
            height: 300px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #3457cb 0%, #24407a 100%);
            color: white;
            box-shadow: 0 4px 16px rgba(52, 87, 203, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(52, 87, 203, 0.4);
        }

        .header-gradient {
            background: linear-gradient(135deg, #24407a 0%, #3457cb 100%);
        }

        .tab-button {
            padding: 12px 24px;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .tab-active {
            background: linear-gradient(135deg, #3457cb 0%, #24407a 100%);
            color: white;
            box-shadow: 0 4px 16px rgba(52, 87, 203, 0.3);
        }

        .tab-inactive {
            color: #64748b;
            background: transparent;
        }

        .tab-inactive:hover {
            background: rgba(52, 87, 203, 0.1);
            color: #3457cb;
        }

        .input-field {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 12px 16px;
            transition: all 0.3s ease;
        }

        .input-field:focus {
            outline: none;
            border-color: #3457cb;
            box-shadow: 0 0 0 3px rgba(52, 87, 203, 0.1);
        }

        .profile-item {
            padding: 20px 0;
            border-bottom: 1px solid #f1f5f9;
            transition: all 0.2s ease;
        }

        .profile-item:hover {
            background: rgba(52, 87, 203, 0.02);
            transform: translateX(4px);
        }

        .icon-container {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 16px;
        }

        .notification {
            border-radius: 16px;
            padding: 16px 20px;
            margin-bottom: 24px;
            animation: fadeInDown 0.5s ease-out;
        }

        .notification.success {
            background: linear-gradient(135deg, rgba(54, 134, 90, 0.1) 0%, rgba(89, 191, 61, 0.1) 100%);
            border: 1px solid rgba(54, 134, 90, 0.2);
            color: #36865a;
        }

        .notification.error {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.1) 0%, rgba(220, 38, 38, 0.1) 100%);
            border: 1px solid rgba(239, 68, 68, 0.2);
            color: #dc2626;
        }
    </style>
</head>

<body class="bg-gray-50 min-h-screen" x-data="{ currentTab: '<?php echo isset($_GET['tab']) && $_GET['tab'] === 'password' ? 'password' : 'profile'; ?>' }">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Header Section -->
        <div class="header bg-white rounded-3xl p-8 lg:p-12 mb-8 shadow-xl relative overflow-hidden animate-fade-in-down">
            <div class="flex items-center gap-6 md:gap-8 flex-col md:flex-row text-center md:text-left">
                <div class="header-icon bg-gradient-to-br from-primary to-primary-light text-white w-20 h-20 md:w-24 md:h-24 rounded-2xl flex items-center justify-center text-4xl md:text-5xl shadow-lg">
                    <i class="fas fa-user-circle"></i>
                </div>
                <div class="header-text">
                    <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 mb-2 tracking-tight">Mon Profil</h1>
                    <p class="text-lg text-gray-600 font-normal">Gérez vos informations personnelles et paramètres de compte</p>
                </div>
            </div>
        </div>

        <!-- Main Card -->
        <div class="card animate-scale-in">
            <!-- Tab Navigation -->
            <div class="border-b border-gray-200">
                <nav class="flex px-8 pt-6" aria-label="Tabs">
                    <button @click="currentTab = 'profile'" 
                            :class="currentTab === 'profile' ? 'tab-active' : 'tab-inactive'"
                            class="tab-button mr-4">
                        <i class="fas fa-user-gear mr-2"></i>Informations Personnelles
                    </button>
                    <button @click="currentTab = 'password'" 
                            :class="currentTab === 'password' ? 'tab-active' : 'tab-inactive'"
                            class="tab-button">
                        <i class="fas fa-key mr-2"></i>Sécurité & Mot de passe
                    </button>
                </nav>
            </div>

            <!-- Profile Tab -->
            <div x-show="currentTab === 'profile'" 
                 x-transition:enter="transition-opacity ease-out duration-300" 
                 x-transition:enter-start="opacity-0" 
                 x-transition:enter-end="opacity-100">
                
                <!-- Account Information Section -->
                <div class="p-8">
                    <div class="flex items-center mb-6">
                        <div class="icon-container bg-primary/10 text-primary">
                            <i class="fas fa-id-card text-xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900">Informations du Compte</h3>
                    </div>
                    
                    <div class="space-y-2">
                        <?php
                        function render_profile_item($icon, $label, $value, $iconBg = 'bg-gray-100', $iconColor = 'text-gray-600') {
                            echo '<div class="profile-item">';
                            echo '<div class="flex items-center">';
                            echo '<div class="icon-container ' . $iconBg . ' ' . $iconColor . '">';
                            echo '<i class="' . $icon . ' text-lg"></i>';
                            echo '</div>';
                            echo '<div class="flex-1">';
                            echo '<dt class="text-sm font-semibold text-gray-600">' . $label . '</dt>';
                            echo '<dd class="text-lg font-bold text-gray-900 mt-1">' . htmlspecialchars($value) . '</dd>';
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                        }
                        
                        render_profile_item('fas fa-user', 'Nom d\'utilisateur', $nom_user, 'bg-primary/10', 'text-primary');
                        render_profile_item('fas fa-envelope', 'Adresse e-mail', $login_user, 'bg-blue-100', 'text-blue-600');
                        render_profile_item('fas fa-user-tag', 'Type de compte', $libelle_type_utilisateur, 'bg-purple-100', 'text-purple-600');
                        render_profile_item('fas fa-shield-halved', 'Niveau d\'accès', $libelle_niveau_acces, 'bg-green-100', 'text-green-600');
                        render_profile_item('fas fa-users', 'Groupe Utilisateur', $libelle_GU, 'bg-yellow-100', 'text-yellow-600');
                        ?>
                    </div>
                </div>

                <?php if ($libelle_type_utilisateur === 'Enseignant simple' || $libelle_type_utilisateur === 'Enseignant administratif'): ?>
                <!-- Professional Information for Teachers -->
                <div class="px-8 pb-8 border-t border-gray-100">
                    <div class="flex items-center mb-6 pt-8">
                        <div class="icon-container bg-secondary/10 text-secondary">
                            <i class="fas fa-graduation-cap text-xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900">Informations Professionnelles (Enseignant)</h3>
                    </div>
                    
                    <div class="space-y-2">
                        <?php
                        render_profile_item('fas fa-microscope', 'Spécialité', $specialite, 'bg-indigo-100', 'text-indigo-600');
                        render_profile_item('fas fa-award', 'Grade', $grade, 'bg-orange-100', 'text-orange-600');
                        render_profile_item('fas fa-calendar-check', 'Date d\'obtention du grade', $date_grade, 'bg-pink-100', 'text-pink-600');
                        render_profile_item('fas fa-briefcase', 'Fonction', $fonction, 'bg-teal-100', 'text-teal-600');
                        render_profile_item('fas fa-calendar-day', 'Date d\'occupation de la fonction', $date_fonction, 'bg-cyan-100', 'text-cyan-600');
                        ?>
                    </div>
                </div>
                <?php elseif ($libelle_type_utilisateur === 'Personnel administratif'): ?>
                <!-- Professional Information for Staff -->
                <div class="px-8 pb-8 border-t border-gray-100">
                    <div class="flex items-center mb-6 pt-8">
                        <div class="icon-container bg-secondary/10 text-secondary">
                            <i class="fas fa-building text-xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900">Informations Professionnelles (Personnel)</h3>
                    </div>
                    
                    <div class="space-y-2">
                        <?php
                        render_profile_item('fas fa-phone', 'Téléphone', $telephone, 'bg-green-100', 'text-green-600');
                        render_profile_item('fas fa-desktop', 'Poste occupé', $poste, 'bg-blue-100', 'text-blue-600');
                        render_profile_item('fas fa-calendar-plus', 'Date d\'embauche', $date_embauche, 'bg-purple-100', 'text-purple-600');
                        ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <!-- Password Tab -->
            <div x-show="currentTab === 'password'" 
                 x-transition:enter="transition-opacity ease-out duration-300" 
                 x-transition:enter-start="opacity-0" 
                 x-transition:enter-end="opacity-100">
                
                <form action="?page=profil&tab=password" method="POST" class="p-8">
                    <div class="flex items-center mb-8">
                        <div class="icon-container bg-red-100 text-red-600">
                            <i class="fas fa-lock text-xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900">Changer mon mot de passe</h3>
                    </div>

                    <?php if (isset($_SESSION['password_error'])): ?>
                    <div class="notification error">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-triangle mr-3"></i>
                            <span><?= htmlspecialchars($_SESSION['password_error']); unset($_SESSION['password_error']); ?></span>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['password_success'])): ?>
                    <div class="notification success">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle mr-3"></i>
                            <span><?= htmlspecialchars($_SESSION['password_success']); unset($_SESSION['password_success']); ?></span>
                        </div>
                    </div>
                    <?php endif; ?>

                    <input type="hidden" name="id_utilisateur" value="<?= $_SESSION['id_utilisateur'] ?? ''; ?>">
                    
                    <div class="space-y-6">
                        <div>
                            <label for="currentPassword" class="block text-sm font-semibold text-gray-700 mb-3">
                                <i class="fas fa-key mr-2 text-gray-500"></i>Mot de passe actuel
                            </label>
                            <input id="currentPassword" type="password" name="currentPassword" required 
                                   class="input-field w-full" placeholder="Saisissez votre mot de passe actuel">
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="newPassword" class="block text-sm font-semibold text-gray-700 mb-3">
                                    <i class="fas fa-lock mr-2 text-gray-500"></i>Nouveau mot de passe
                                </label>
                                <input id="newPassword" type="password" name="newPassword" required 
                                       class="input-field w-full" placeholder="Nouveau mot de passe">
                            </div>
                            <div>
                                <label for="confirmPassword" class="block text-sm font-semibold text-gray-700 mb-3">
                                    <i class="fas fa-lock mr-2 text-gray-500"></i>Confirmer le mot de passe
                                </label>
                                <input id="confirmPassword" type="password" name="confirmPassword" required 
                                       class="input-field w-full" placeholder="Confirmez le nouveau mot de passe">
                            </div>
                        </div>
                        
                        <!-- Password Requirements -->
                        <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                            <h4 class="text-sm font-semibold text-blue-800 mb-2">
                                <i class="fas fa-info-circle mr-2"></i>Exigences du mot de passe
                            </h4>
                            <ul class="text-sm text-blue-700 space-y-1">
                                <li><i class="fas fa-check text-green-600 mr-2"></i>Au moins 8 caractères</li>
                                <li><i class="fas fa-check text-green-600 mr-2"></i>Une lettre majuscule et une minuscule</li>
                                <li><i class="fas fa-check text-green-600 mr-2"></i>Au moins un chiffre</li>
                                <li><i class="fas fa-check text-green-600 mr-2"></i>Un caractère spécial (@, #, $, etc.)</li>
                            </ul>
                        </div>
                    </div>

                    <div class="mt-8 pt-6 border-t border-gray-200 flex justify-end">
                        <button type="submit" name="update_password" class="btn btn-primary">
                            <i class="fas fa-save mr-2"></i>Mettre à jour le mot de passe
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Gestion avancée des onglets et animations
        document.addEventListener('DOMContentLoaded', function() {
            // Animation d'apparition des notifications
            const notifications = document.querySelectorAll('.notification');
            notifications.forEach((notification, index) => {
                notification.style.animationDelay = `${index * 0.1}s`;
                
                // Auto-hide après 5 secondes
                setTimeout(() => {
                    notification.style.opacity = '0';
                    notification.style.transform = 'translateY(-20px)';
                    setTimeout(() => notification.remove(), 500);
                }, 5000);
            });

            // Validation en temps réel du mot de passe
            const newPassword = document.getElementById('newPassword');
            const confirmPassword = document.getElementById('confirmPassword');
            
            if (newPassword && confirmPassword) {
                confirmPassword.addEventListener('input', function() {
                    if (this.value !== newPassword.value) {
                        this.style.borderColor = '#ef4444';
                    } else {
                        this.style.borderColor = '#36865a';
                    }
                });
            }

            // Animation des éléments de profil
            const profileItems = document.querySelectorAll('.profile-item');
            profileItems.forEach((item, index) => {
                item.style.animationDelay = `${index * 0.1}s`;
                item.classList.add('animate-slide-in-right');
            });
        });
    </script>
</body>
</html>
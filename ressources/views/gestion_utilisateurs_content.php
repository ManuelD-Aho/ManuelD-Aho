<?php

$utilisateur_a_modifier = $GLOBALS['utilisateur_a_modifier'];
$showModal = isset($_GET['action']) && ($_GET['action'] === 'edit' || $_GET['action'] === 'add' || $_GET['action'] === 'addMasse');

$utilisateurs = $GLOBALS['utilisateurs'] ?? [];
$niveau_acces = $GLOBALS['niveau_acces'];
$types_utilisateur = $GLOBALS['types_utilisateur'];
$groupes_utilisateur = $GLOBALS['groupes_utilisateur'];


// Calculer les statistiques sur l'ensemble des utilisateurs
$allUtilisateurs = $GLOBALS['utilisateurs'] ?? [];
$totalUtilisateurs = count($allUtilisateurs);
$utilisateursActifs = count(array_filter($allUtilisateurs, function ($u) {
    return $u->statut_utilisateur === 'Actif';
}));
$utilisateursInactifs = $totalUtilisateurs - $utilisateursActifs;

// Pagination
$page = isset($_GET['p']) ? (int)$_GET['p'] : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

// Search functionality
$search = isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '';

// Filter the list based on search
if (!empty($search)) {
    $allUtilisateurs = array_filter($allUtilisateurs, function ($utilisateur) use ($search) {
        return stripos($utilisateur->nom_utilisateur, $search) !== false ||
            stripos($utilisateur->prenom_utilisateur, $search) !== false ||
            stripos($utilisateur->email_utilisateur, $search) !== false;
    });
}

// Total pages calculation
$total_items = count($allUtilisateurs);
$total_pages = ceil($total_items / $limit);

// Validation de la page courante
if ($page < 1) {
    $page = 1;
} elseif ($page > $total_pages && $total_pages > 0) {
    $page = $total_pages;
}

// Slice the array for pagination
$utilisateurs = array_slice($allUtilisateurs, $offset, $limit);






?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Utilisateurs</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        // Custom colors based on your request
                        'custom-primary': '#3457cb', // Main vibrant blue
                        'custom-primary-dark': '#24407a', // Darker blue for text/headings
                        'custom-success-light': '#59bf3d', // Lighter green
                        'custom-success-dark': '#36865a', // Darker green
                        'gray-bg': '#f8f9fa', // Light gray background
                        'gray-text': '#4a5568', // Standard text gray
                        'gray-muted': '#718096', // Muted text gray
                        'gray-border': '#e2e8f0', // Light border gray
                        'gray-card-bg': '#ffffff', // Card background white
                        'gray-shadow': 'rgba(0, 0, 0, 0.05)', // Subtle shadow
                        'red-error': '#dc2626', // Standard red for errors
                        'blue-info': '#3b82f6', // Standard blue for info
                    },
                    keyframes: {
                        fadeInUp: {
                            '0%': {
                                opacity: '0',
                                transform: 'translateY(20px)'
                            },
                            '100%': {
                                opacity: '1',
                                transform: 'translateY(0)'
                            },
                        },
                        pulseCustom: {
                            '0%, 100%': {
                                opacity: '1'
                            },
                            '50%': {
                                opacity: '0.6'
                            },
                        },
                        slideInRight: {
                            '0%': {
                                transform: 'translateX(100%)',
                                opacity: '0'
                            },
                            '100%': {
                                transform: 'translateX(0)',
                                opacity: '1'
                            },
                        },
                        fadeOut: {
                            '0%': {
                                opacity: '1'
                            },
                            '100%': {
                                opacity: '0'
                            },
                        },
                        spinCustom: {
                            '0%': {
                                transform: 'rotate(0deg)'
                            },
                            '100%': {
                                transform: 'rotate(360deg)'
                            },
                        },
                        progressBar: {
                            '0%': {
                                width: '0%'
                            },
                            '50%': {
                                width: '70%'
                            },
                            '100%': {
                                width: '100%'
                            },
                        }
                    },
                    animation: {
                        'fade-in-up': 'fadeInUp 0.6s ease-out forwards',
                        'pulse-custom': 'pulseCustom 2s ease-in-out infinite',
                        'slide-in-right': 'slideInRight 0.5s ease-out forwards',
                        'fade-out-custom': 'fadeOut 0.5s ease-out forwards',
                        'spin-custom': 'spinCustom 1s linear infinite',
                        'progress-bar': 'progressBar 2s ease-in-out infinite',
                    }
                }
            }
        }
    </script>

</head>

<body class="font-sans antialiased">

    <?php if (!empty($GLOBALS['messageSuccess']) || !empty($GLOBALS['messageErreur'])): ?>
        <div class="fixed top-4 right-4 z-50 space-y-4">
            <?php if (!empty($GLOBALS['messageSuccess'])): ?>
                <div id="successNotification" class="notification success">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle mr-3 text-xl"></i>
                        <p class="font-medium"><?= htmlspecialchars($GLOBALS['messageSuccess']) ?></p>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (!empty($GLOBALS['messageErreur'])): ?>
                <div id="errorNotification" class="notification error">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle mr-3 text-xl"></i>
                        <p class="font-medium"><?= htmlspecialchars($GLOBALS['messageErreur']) ?></p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>




    <div class="header bg-white rounded-3xl p-8 lg:p-12 mb-8 shadow-xl relative overflow-hidden">
        <div class="flex items-center gap-6 md:gap-8 flex-col md:flex-row text-center md:text-left">
            <div class="header-icon bg-gradient-to-br from-primary to-primary-dark text-white w-20 h-20 md:w-24 md:h-24 rounded-2xl flex items-center justify-center text-4xl md:text-5xl shadow-lg">
                <i class="fas fa-clipboard-check"></i>
            </div>
            <div class="header-text">
                <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 mb-2 tracking-tight">Gestion des utilisateurs</h1>
                <p class="text-lg text-gray-600 font-normal">Superviser et administrer les comptes utilisateurs.</p>
            </div>
        </div>
    </div>



    <!-- Stats Overview -->
    <section class="container  mx-auto px-6 py-8 -mt-8 relative z-20">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="stat-card bg-white rounded-2xl p-6 shadow-md transition-all duration-300 hover:translate-y-[-4px] hover:shadow-lg relative overflow-hidden" style="animation-delay: 0.1s">
                <div class="stat-content flex items-center gap-4">
                    <div class="stat-icon bg-primary/10 text-primary w-12 h-12 rounded-xl flex items-center justify-center text-2xl">
                        <i class="fas fa-users text-custom-primary text-2xl"></i>
                    </div>
                    <div class="stat-info">
                        <h3 class="text-4xl font-bold text-primary mb-1"><?php echo $totalUtilisateurs; ?></h3>
                        <p class="text-sm font-medium text-gray-600">Total Utilisateurs </p>
                    </div>
                </div>
            </div>
            <div class="stat-card bg-white rounded-2xl p-6 shadow-md transition-all duration-300 hover:translate-y-[-4px] hover:shadow-lg relative overflow-hidden" style="animation-delay: 0.2s">
                <div class="stat-content flex items-center gap-4">
                    <div class="stat-icon bg-secondary/10 text-secondary w-12 h-12 rounded-xl flex items-center justify-center text-2xl">
                        <i class="fas fa-user-check text-custom-success-light text-2xl"></i>
                    </div>
                    <div class="stat-info">
                        <h3 class="text-4xl font-bold text-secondary mb-1"><?php echo $utilisateursActifs; ?></h3>
                        <p class="text-sm font-medium text-gray-600">Utilisateurs Actifs</p>
                    </div>
                </div>
            </div>
            <div class="stat-card bg-white rounded-2xl p-6 shadow-md transition-all duration-300 hover:translate-y-[-4px] hover:shadow-lg relative overflow-hidden" style="animation-delay: 0.3s">
                <div class="stat-content flex items-center gap-4">
                    <div class="stat-icon bg-warning/10 text-warning w-12 h-12 rounded-xl flex items-center justify-center text-2xl">
                        <i class="fas fa-user-times text-red-error text-2xl"></i>
                    </div>
                    <div class="stat-info">
                        <h3 class="text-4xl font-bold text-warning mb-1"><?php echo $utilisateursInactifs; ?></h3>
                        <p class="text-sm font-medium text-gray-600">Utilisateurs Inactifs</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="bg-gray-card-bg rounded-xl shadow-lg p-6 border border-gray-border mb-8 initial-hidden" style="animation-delay: 0.5s;">
        <h2 class="text-2xl font-bold text-custom-primary-dark mb-6 text-center lg:text-left">
            Inventaire des Actions
        </h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <a href="?page=gestion_utilisateurs&action=add"
                class="flex items-center justify-center bg-custom-success-dark text-white font-semibold py-3 px-4 rounded-lg shadow-md hover:bg-custom-success-light transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-custom-success-light/50">
                <i class="fas fa-user-plus mr-2"></i> Ajouter un Utilisateur
            </a>

            <a href="?page=gestion_utilisateurs&action=addMasse"
                class="flex items-center justify-center bg-blue-info text-white font-semibold py-3 px-4 rounded-lg shadow-md hover:bg-custom-primary transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-info/50">
                <i class="fas fa-users-medical mr-2"></i> Ajout multiple
            </a>

            <button id="activerButton" type="button" disabled
                class="flex items-center justify-center bg-custom-success-light/80 text-white font-medium py-3 px-4 rounded-lg shadow transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-custom-success-light/50 disabled:opacity-50 disabled:cursor-not-allowed">
                <i class="fa-solid fa-eye mr-2"></i> Activer
            </button>

            <button onclick="printTable()"
                class="flex items-center justify-center bg-gray-100 hover:bg-gray-200 text-gray-text font-medium py-3 px-4 rounded-lg shadow transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-gray-200/50">
                <i class="fas fa-print mr-2"></i> Imprimer
            </button>

            <button onclick="exportToExcel()"
                class="flex items-center justify-center bg-gray-100 hover:bg-gray-200 text-gray-text font-medium py-3 px-4 rounded-lg shadow transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-gray-200/50">
                <i class="fas fa-file-export mr-2"></i> Exporter
            </button>

            <button id="desactiverButton" type="button" disabled
                class="flex items-center justify-center bg-red-error/80 text-white font-medium py-3 px-4 rounded-lg shadow transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-red-error/50 disabled:opacity-50 disabled:cursor-not-allowed">
                <i class="fa-solid fa-eye-slash mr-2"></i> Désactiver
            </button>
        </div>
    </div>

    <div class="bg-gray-card-bg rounded-xl shadow-lg overflow-hidden border border-gray-border w-full max-w-7xl initial-hidden" style="animation-delay: 0.4s;">

        <div class="px-6 py-4 flex justify-between items-center flex-wrap gap-4">
            <h2 class="text-xl font-bold">Liste des Utilisateurs</h2>
        </div>

        <div class="px-6 py-4 flex flex-col sm:flex-row justify-between items-center border-b border-gray-border gap-4">
            <div class="relative w-full sm:w-1/2 md:w-1/3">
                <input type="text" id="searchInput" placeholder="Rechercher par nom, groupe ou login..." value="<?= $search ?>"
                    class="w-full px-4 py-2 pl-10 border border-gray-border rounded-lg focus:outline-none focus:ring-2 focus:ring-custom-primary focus:border-custom-primary transition-all duration-200 text-gray-800">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <i class="fas fa-search text-gray-muted"></i>
                </span>
            </div>

        </div>

        <form class="overflow-x-auto" method="POST" action="?page=gestion_utilisateurs" id="formListeUtilisateurs">
            <input type="hidden" name="submit_disable_multiple" id="submitDisableHidden" value="0">
            <input type="hidden" name="submit_enable_multiple" id="submitEnableHidden" value="0">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-4 py-3 text-center w-12">
                            <input type="checkbox" id="selectAllCheckbox" class="custom-checkbox">
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            <div class="flex items-center cursor-pointer hover:text-gray-900 transition-colors">
                                <span>Nom d'utilisateur/Login utilisateur</span>
                                <i class="fas fa-sort ml-2 text-gray-muted text-sm"></i>
                            </div>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            <div class="flex items-center cursor-pointer hover:text-gray-900 transition-colors">
                                <span>Groupe utilisateur</span>
                                <i class="fas fa-sort ml-2 text-gray-muted text-sm"></i>
                            </div>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            <div class="flex items-center cursor-pointer hover:text-gray-900 transition-colors">
                                <span>Statut</span>
                                <i class="fas fa-sort ml-2 text-gray-muted text-sm"></i>
                            </div>
                        </th>

                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider w-24">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200" id="usersTableBody">
                    <?php if (empty($utilisateurs)): ?>
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-users text-gray-300 text-5xl mb-4"></i>
                                    <p class="text-lg font-medium">Aucun utilisateur trouvé.</p>
                                    <p class="text-sm mt-2 text-gray-muted">Ajoutez de nouveaux utilisateurs en cliquant sur le bouton
                                        "Ajouter un Utilisateur".</p>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($utilisateurs as $user): ?>
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-4 py-4 text-center">
                                    <input type="checkbox" name="selected_ids[]" value="<?php echo htmlspecialchars($user->id_utilisateur); ?>"
                                        class="custom-checkbox">
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 font-medium">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 flex items-center justify-center rounded-full bg-custom-primary/10 text-custom-primary text-xs font-bold flex-shrink-0">
                                            <?= strtoupper(substr($user->nom_utilisateur, 0, 1) . (isset($user->prenom_utilisateur) ? substr($user->prenom_utilisateur, 0, 1) : '')) ?>
                                        </div>
                                        <span><?php echo htmlspecialchars($user->nom_utilisateur) . '/' . htmlspecialchars($user->login_utilisateur); ?></span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    <?php echo htmlspecialchars($user->lib_GU); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                                            <?php echo $user->statut_utilisateur === 'Actif' ? 'bg-custom-success-light/10 text-custom-success-dark' : 'bg-red-error/10 text-red-error'; ?>">
                                        <?php echo htmlspecialchars($user->statut_utilisateur); ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="flex justify-center space-x-3">
                                        <a href="?page=gestion_utilisateurs&action=edit&id_utilisateur=<?php echo $user->id_utilisateur; ?>"
                                            class="text-blue-info hover:text-custom-primary transition-colors duration-150 text-lg p-2 rounded-full hover:bg-blue-info/10"
                                            title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" onclick="confirmDeleteIndividual('<?php echo $user->id_utilisateur; ?>')"
                                            class="text-red-error hover:text-danger transition-colors duration-150 text-lg p-2 rounded-full hover:bg-red-error/10"
                                            title="Supprimer">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </form>

        <?php if ($total_pages > 1 || (!empty($search) && $total_items > 0)): ?>
            <div class="px-6 py-4 flex flex-col sm:flex-row justify-between items-center gap-4 border-t border-gray-border bg-gray-50">
                <div class="text-sm text-gray-600">
                    Affichage de <?= $offset + 1 ?> à <?= min($offset + $limit, $total_items) ?> sur
                    <?= $total_items ?> entrées
                </div>
                <div class="flex flex-wrap justify-center gap-2">
                    <?php if ($page > 1): ?>
                        <a href="?page=gestion_utilisateurs&p=<?= $page - 1 ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?>"
                            class="px-3 py-2 bg-gray-card-bg border border-gray-border rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-100 flex items-center gap-1 transition-all duration-150">
                            <i class="fas fa-chevron-left"></i>Précédent
                        </a>
                    <?php endif; ?>

                    <?php
                    $start_page = max(1, $page - 2);
                    $end_page = min($total_pages, $page + 2);

                    if ($start_page > 1) {
                        echo '<a href="?page=gestion_utilisateurs&p=1' . (!empty($search) ? '&search=' . urlencode($search) : '') . '" class="px-3 py-2 bg-gray-card-bg border border-gray-border rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-100 transition-all duration-150">1</a>';
                        if ($start_page > 2) {
                            echo '<span class="px-3 py-2 text-gray-muted">...</span>';
                        }
                    }

                    for ($i = $start_page; $i <= $end_page; $i++):
                        $searchParam = !empty($search) ? '&search=' . urlencode($search) : '';
                    ?>
                        <a href="?page=gestion_utilisateurs&p=<?= $i ?><?= $searchParam ?>"
                            class="px-3 py-2 rounded-lg text-sm font-medium transition-all duration-150
                                <?php echo $i === $page ? 'bg-custom-primary text-white border-custom-primary shadow-md' : 'bg-gray-card-bg text-gray-700 border border-gray-border hover:bg-gray-100'; ?>">
                            <?= $i ?>
                        </a>
                    <?php endfor;

                    if ($end_page < $total_pages) {
                        if ($end_page < $total_pages - 1) {
                            echo '<span class="px-3 py-2 text-gray-muted">...</span>';
                        }
                        $searchParam = !empty($search) ? '&search=' . urlencode($search) : '';
                        echo '<a href="?page=gestion_utilisateurs&p=' . $total_pages . $searchParam . '" class="px-3 py-2 bg-gray-card-bg border border-gray-border rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-100 transition-all duration-150">' . $total_pages . '</a>';
                    }
                    ?>

                    <?php if ($page < $total_pages): ?>
                        <a href="?page=gestion_utilisateurs&p=<?= $page + 1 ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?>"
                            class="px-3 py-2 bg-gray-card-bg border border-gray-border rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-100 flex items-center gap-1 transition-all duration-150">
                            Suivant<i class="fas fa-chevron-right ml-1"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <div class="mt-8 text-center text-gray-600 text-sm w-full max-w-7xl">
        <p class="py-4 bg-gray-card-bg rounded-lg shadow-lg border border-gray-border">© 2025 Système de Gestion des Utilisateurs. Tous droits réservés.</p>
    </div>




    <div id="userModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50 transition-all duration-300 <?php echo $showModal && ($_GET['action'] === 'edit' || $_GET['action'] === 'add') ? 'opacity-100 scale-100' : 'hidden opacity-0 scale-95'; ?>">
        <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-4xl max-h-[95vh] overflow-hidden transform transition-all duration-300 <?php echo $showModal && ($_GET['action'] === 'edit' || $_GET['action'] === 'add') ? 'scale-100 opacity-100' : 'scale-95 opacity-0'; ?>">

            <!-- Close Button -->
            <button onclick="closeUserModal()" class="absolute top-6 right-6 z-20 w-12 h-12 bg-white/20 backdrop-blur-md rounded-xl flex items-center justify-center text-white hover:bg-white/30 transition-all duration-200 group">
                <i class="fas fa-times text-lg group-hover:rotate-90 transition-transform duration-200"></i>
            </button>

            <!-- Header with Gradient -->
            <div class="bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-500 px-8 py-12 relative overflow-hidden">
                <!-- Decorative Background -->
                <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=" 40" height="40" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg" %3E%3Cg fill="%23ffffff" fill-opacity="0.1" %3E%3Cpath d="m20 20 20-20v40z" /%3E%3C/g%3E%3C/svg%3E')] opacity-20"></div>
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-16 translate-x-16"></div>
                <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/10 rounded-full translate-y-12 -translate-x-12"></div>

                <!-- Header Content -->
                <div class="relative z-10 flex items-center">
                    <div class="bg-white/20 backdrop-blur-md p-4 rounded-2xl mr-6 shadow-lg">
                        <i class="fas fa-user-plus text-white text-3xl"></i>
                    </div>
                    <div>
                        <h3 id="userModalTitle" class="text-3xl font-bold text-white mb-2 tracking-tight">
                            <?php echo isset($utilisateur_a_modifier) && $_GET['action'] == 'edit' ? 'Modifier un utilisateur' : 'Ajouter un Utilisateur' ?>
                        </h3>
                        <p class="text-white/80 text-lg font-medium">
                            <?php echo isset($utilisateur_a_modifier) && $_GET['action'] == 'edit' ? 'Mettre à jour les informations' : 'Créer un nouveau compte utilisateur' ?>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Form Content -->
            <div class="p-8 overflow-y-auto max-h-[calc(95vh-220px)]">
                <form id="userForm" class="space-y-8" method="POST" action="?page=gestion_utilisateurs">
                    <input type="hidden" id="userId" name="id_utilisateur" value="<?php echo $utilisateur_a_modifier ? htmlspecialchars($utilisateur_a_modifier->id_utilisateur) : ''; ?>">

                    <!-- Row 1: Nom et Login -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Nom d'utilisateur -->
                        <div class="space-y-3">
                            <label for="nom_utilisateur" class="block text-sm font-bold text-gray-800 flex items-center mb-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center mr-3 shadow-lg">
                                    <i class="fas fa-user text-white text-sm"></i>
                                </div>
                                Nom d'utilisateur
                            </label>
                            <?php if ($_GET['action'] === 'add'): ?>
                                <div class="relative">
                                    <select name="nom_utilisateur" id="nom_utilisateur" required class="w-full px-6 py-4 border-2 border-gray-200 rounded-2xl focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition-all duration-200 bg-gray-50/50 hover:bg-white text-gray-800 appearance-none cursor-pointer font-medium shadow-sm">
                                        <option value="">Sélectionner une personne</option>
                                        <?php if (isset($enseignantsNonUtilisateurs) && !empty($enseignantsNonUtilisateurs)): ?>
                                            <optgroup label="👨‍🏫 Enseignants">
                                                <?php foreach ($enseignantsNonUtilisateurs as $enseignant): ?>
                                                    <option value="<?php echo htmlspecialchars($enseignant->nom_enseignant . ' ' . $enseignant->prenom_enseignant); ?>"
                                                        data-login="<?php echo htmlspecialchars($enseignant->mail_enseignant); ?>">
                                                        <?php echo htmlspecialchars($enseignant->nom_enseignant . ' ' . $enseignant->prenom_enseignant); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </optgroup>
                                        <?php endif; ?>
                                        <?php if (isset($personnelNonUtilisateurs) && !empty($personnelNonUtilisateurs)): ?>
                                            <optgroup label="👥 Personnel Administratif">
                                                <?php foreach ($personnelNonUtilisateurs as $personnel): ?>
                                                    <option value="<?php echo htmlspecialchars($personnel->nom_pers_admin . ' ' . $personnel->prenom_pers_admin); ?>"
                                                        data-login="<?php echo htmlspecialchars($personnel->email_pers_admin); ?>">
                                                        <?php echo htmlspecialchars($personnel->nom_pers_admin . ' ' . $personnel->prenom_pers_admin); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </optgroup>
                                        <?php endif; ?>
                                        <?php if (isset($etudiantsNonUtilisateurs) && !empty($etudiantsNonUtilisateurs)): ?>
                                            <optgroup label="🎓 Étudiants">
                                                <?php foreach ($etudiantsNonUtilisateurs as $etudiant): ?>
                                                    <option value="<?php echo htmlspecialchars($etudiant->nom_etu . ' ' . $etudiant->prenom_etu); ?>"
                                                        data-login="<?php echo htmlspecialchars($etudiant->email_etu); ?>">
                                                        <?php echo htmlspecialchars($etudiant->nom_etu . ' ' . $etudiant->prenom_etu); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </optgroup>
                                        <?php endif; ?>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-6 pointer-events-none">
                                        <i class="fas fa-chevron-down text-gray-400"></i>
                                    </div>
                                </div>
                            <?php else: ?>
                                <input type="text" name="nom_utilisateur" id="nom_utilisateur" required value="<?php echo $utilisateur_a_modifier ? htmlspecialchars($utilisateur_a_modifier->nom_utilisateur) : ''; ?>" class="w-full px-6 py-4 border-2 border-gray-200 rounded-2xl focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition-all duration-200 bg-gray-50/50 hover:bg-white text-gray-800 font-medium shadow-sm">
                            <?php endif; ?>
                        </div>

                        <!-- Login -->
                        <div class="space-y-3">
                            <label for="login_utilisateur" class="block text-sm font-bold text-gray-800 flex items-center mb-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl flex items-center justify-center mr-3 shadow-lg">
                                    <i class="fas fa-envelope text-white text-sm"></i>
                                </div>
                                Login (Email)
                            </label>
                            <input type="email" name="login_utilisateur" id="login_utilisateur" required value="<?php echo $utilisateur_a_modifier ? htmlspecialchars($utilisateur_a_modifier->login_utilisateur) : ''; ?>" class="w-full px-6 py-4 border-2 border-gray-200 rounded-2xl focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition-all duration-200 bg-gray-50/50 hover:bg-white text-gray-800 font-medium shadow-sm" placeholder="exemple@domaine.com">
                        </div>
                    </div>

                    <!-- Row 2: Type et Statut -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Type utilisateur -->
                        <div class="space-y-3">
                            <label for="id_type_utilisateur" class="block text-sm font-bold text-gray-800 flex items-center mb-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center mr-3 shadow-lg">
                                    <i class="fas fa-id-badge text-white text-sm"></i>
                                </div>
                                Type utilisateur
                            </label>
                            <div class="relative">
                                <select name="id_type_utilisateur" id="id_type_utilisateur" required class="w-full px-6 py-4 border-2 border-gray-200 rounded-2xl focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition-all duration-200 bg-gray-50/50 hover:bg-white text-gray-800 appearance-none cursor-pointer font-medium shadow-sm">
                                    <option value="">Sélectionner un type utilisateur</option>
                                    <?php foreach ($types_utilisateur as $type): ?>
                                        <option value="<?php echo htmlspecialchars($type->id_type_utilisateur); ?>" <?php echo ($utilisateur_a_modifier && $type->id_type_utilisateur == $utilisateur_a_modifier->id_type_utilisateur) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($type->lib_type_utilisateur); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-6 pointer-events-none">
                                    <i class="fas fa-chevron-down text-gray-400"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Statut -->
                        <div class="space-y-3">
                            <label for="statut_utilisateur" class="block text-sm font-bold text-gray-800 flex items-center mb-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl flex items-center justify-center mr-3 shadow-lg">
                                    <i class="fas fa-toggle-on text-white text-sm"></i>
                                </div>
                                Statut
                            </label>
                            <div class="relative">
                                <select name="statut_utilisateur" id="statut_utilisateur" required class="w-full px-6 py-4 border-2 border-gray-200 rounded-2xl focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition-all duration-200 bg-gray-50/50 hover:bg-white text-gray-800 appearance-none cursor-pointer font-medium shadow-sm">
                                    <option value="">Sélectionner un statut</option>
                                    <option value="Actif" <?php echo ($utilisateur_a_modifier && $utilisateur_a_modifier->statut_utilisateur === 'Actif') ? 'selected' : ''; ?>>✅ Actif</option>
                                    <option value="Inactif" <?php echo ($utilisateur_a_modifier && $utilisateur_a_modifier->statut_utilisateur === 'Inactif') ? 'selected' : ''; ?>>❌ Inactif</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-6 pointer-events-none">
                                    <i class="fas fa-chevron-down text-gray-400"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Row 3: Groupe et Niveau d'accès -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Groupe utilisateur -->
                        <div class="space-y-3">
                            <label for="id_GU" class="block text-sm font-bold text-gray-800 flex items-center mb-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-teal-500 to-teal-600 rounded-xl flex items-center justify-center mr-3 shadow-lg">
                                    <i class="fas fa-users text-white text-sm"></i>
                                </div>
                                Groupe utilisateur
                            </label>
                            <div class="relative">
                                <select name="id_GU" id="id_GU" required class="w-full px-6 py-4 border-2 border-gray-200 rounded-2xl focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition-all duration-200 bg-gray-50/50 hover:bg-white text-gray-800 appearance-none cursor-pointer font-medium shadow-sm">
                                    <option value="">Sélectionner un groupe utilisateur</option>
                                    <?php foreach ($groupes_utilisateur as $groupe): ?>
                                        <option value="<?php echo htmlspecialchars($groupe->id_GU); ?>" <?php echo ($utilisateur_a_modifier && $groupe->id_GU == $utilisateur_a_modifier->id_GU) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($groupe->lib_GU); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-6 pointer-events-none">
                                    <i class="fas fa-chevron-down text-gray-400"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Niveau d'accès -->
                        <div class="space-y-3">
                            <label for="id_niveau_acces" class="block text-sm font-bold text-gray-800 flex items-center mb-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-red-500 to-red-600 rounded-xl flex items-center justify-center mr-3 shadow-lg">
                                    <i class="fas fa-lock text-white text-sm"></i>
                                </div>
                                Niveau d'accès
                            </label>
                            <div class="relative">
                                <select name="id_niveau_acces" id="id_niveau_acces" required class="w-full px-6 py-4 border-2 border-gray-200 rounded-2xl focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition-all duration-200 bg-gray-50/50 hover:bg-white text-gray-800 appearance-none cursor-pointer font-medium shadow-sm">
                                    <option value="">Sélectionner un niveau</option>
                                    <?php foreach ($niveau_acces as $niveau): ?>
                                        <option value="<?php echo htmlspecialchars($niveau->id_niveau_acces_donnees); ?>" <?php echo ($utilisateur_a_modifier && $niveau->id_niveau_acces_donnees == $utilisateur_a_modifier->id_niv_acces_donnee) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($niveau->lib_niveau_acces_donnees); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-6 pointer-events-none">
                                    <i class="fas fa-chevron-down text-gray-400"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex flex-col sm:flex-row gap-4 justify-end pt-8 border-t-2 border-gray-100">
                        <button type="button" onclick="closeUserModal()" class="px-8 py-4 bg-white border-2 border-gray-300 text-gray-700 font-bold rounded-2xl hover:bg-gray-50 hover:border-gray-400 hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-center gap-3 shadow-sm">
                            <i class="fas fa-times"></i>
                            Annuler
                        </button>
                        <?php if (isset($utilisateur_a_modifier) && $_GET['action'] == 'edit'): ?>
                            <button type="button" onclick="submitModifyForm()" class="px-8 py-4 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-bold rounded-2xl hover:shadow-xl hover:shadow-indigo-500/25 transform hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-center gap-3">
                                <i class="fas fa-save"></i>
                                Modifier
                            </button>
                        <?php else: ?>
                            <button type="submit" name="btn_add_utilisateur" class="px-8 py-4 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-bold rounded-2xl hover:shadow-xl hover:shadow-indigo-500/25 transform hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-center gap-3">
                                <i class="fas fa-save"></i>
                                Enregistrer
                            </button>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div id="userMasseModal" class="fixed inset-0 bg-gray-900 bg-opacity-70 flex items-center justify-center z-50 modal-transition <?php echo $showModal && $_GET['action'] === 'addMasse' ? 'opacity-100 scale-100' : 'hidden opacity-0 scale-95'; ?>">
        <div class="relative w-full max-w-3xl max-h-[100vh] bg-gray-card-bg rounded-2xl shadow-3xl transform transition-all duration-300 <?php echo $showModal && $_GET['action'] === 'addMasse' ? 'scale-100 opacity-100' : 'scale-95 opacity-0'; ?> overflow-hidden">

            <div class="bg-gradient-primary  p-5 md:p-5 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="bg-white/20 p-2 rounded-full flex-shrink-0"> <i class="fas fa-users-medical text-lg"></i> </div>
                    <div class="flex flex-col">
                        <h3 class="text-lg font-bold">Ajout multiple d'utilisateurs</h3> <span class=" text-xs opacity-80 mt-1">Simplifiez la tâche en ajoutant plusieurs utilisateurs en même temps.</span>
                    </div>
                </div>
                <button onclick="closeMasseModal()" class="text-white hover:text-gray-200 focus:outline-none p-2 rounded-full hover:bg-white/20 transition-colors">
                    <i class="fas fa-times fa-lg"></i>
                </button>
            </div>

            <div class="p-5 md:p-6 space-y-5 overflow-y-auto max-h-[calc(80vh-120px)]">
                <form method="POST" action="?page=gestion_utilisateurs" class="space-y-4" id="userMasse">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-3 bg-gray-50 p-3 rounded-lg border border-gray-border">
                        <h4 class="col-span-1 md:col-span-2 text-sm font-bold text-gray-800 border-b border-gray-200 pb-2 mb-2"> <i class="fas fa-cogs text-custom-primary mr-2"></i> Attribuer un rôle et un statut
                        </h4>
                        <div class="space-y-1">
                            <label for="mass_type_utilisateur" class="block text-sm font-semibold text-gray-700">
                                <i class="fas fa-id-badge text-gray-muted mr-2"></i>Type utilisateur
                            </label>
                            <select name="id_type_utilisateur" id="mass_type_utilisateur" required class="w-full px-3 py-2 border border-gray-border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-custom-primary focus:border-custom-primary bg-gray-card-bg transition-all duration-200 text-gray-800">
                                <option value="">Sélectionner un type utilisateur</option>
                                <?php foreach ($types_utilisateur as $type): ?>
                                    <option value="<?php echo htmlspecialchars($type->id_type_utilisateur); ?>">
                                        <?php echo htmlspecialchars($type->lib_type_utilisateur); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="space-y-1">
                            <label for="mass_groupe_utilisateur" class="block text-sm font-semibold text-gray-700">
                                <i class="fas fa-users text-gray-muted mr-2"></i>Groupe utilisateur
                            </label>
                            <select name="id_GU" id="mass_groupe_utilisateur" required class="w-full px-3 py-2 border border-gray-border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-custom-primary focus:border-custom-primary bg-gray-card-bg transition-all duration-200 text-gray-800">
                                <option value="">Sélectionner un groupe utilisateur</option>
                                <?php foreach ($groupes_utilisateur as $groupe): ?>
                                    <option value="<?php echo htmlspecialchars($groupe->id_GU); ?>">
                                        <?php echo htmlspecialchars($groupe->lib_GU); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="space-y-1">
                            <label for="mass_niveau_acces" class="block text-sm font-semibold text-gray-700">
                                <i class="fas fa-lock text-gray-muted mr-2"></i>Niveau d'accès
                            </label>
                            <select name="id_niveau_acces" id="mass_niveau_acces" required class="w-full px-3 py-2 border border-gray-border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-custom-primary focus:border-custom-primary bg-gray-card-bg transition-all duration-200 text-gray-800">
                                <option value="">Sélectionner un niveau</option>
                                <?php foreach ($niveau_acces as $niveau): ?>
                                    <option value="<?php echo htmlspecialchars($niveau->id_niveau_acces_donnees); ?>">
                                        <?php echo htmlspecialchars($niveau->lib_niveau_acces_donnees); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="space-y-1">
                            <label for="mass_statut" class="block text-sm font-semibold text-gray-700">
                                <i class="fas fa-toggle-on text-gray-muted mr-2"></i>Statut
                            </label>
                            <select name="statut_utilisateur" id="mass_statut" required class="w-full px-3 py-2 border border-gray-border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-custom-primary focus:border-custom-primary bg-gray-card-bg transition-all duration-200 text-gray-800">
                                <option value="">Sélectionner un statut</option>
                                <option value="Actif">Actif</option>
                                <option value="Inactif">Inactif</option>
                            </select>
                        </div>
                    </div>

                    <div class="space-y-3 bg-gray-50 p-3 rounded-lg border border-gray-border">
                        <h4 class="text-sm font-bold text-gray-800 border-b border-gray-200 pb-2 mb-2"> <i class="fas fa-user-friends text-custom-primary mr-2"></i> Personnes à ajouter
                        </h4>
                        <label class="block text-sm font-semibold text-gray-700">
                            Sélectionner les utilisateurs dans la liste :
                        </label>
                        <select name="selected_persons[]" multiple size="6" required class="w-full px-3 py-2 border border-gray-border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-custom-primary focus:border-custom-primary bg-gray-card-bg transition-all duration-200 text-gray-800 custom-select-multiple" style="min-height: 140px;"> <?php if (isset($enseignantsNonUtilisateurs) && !empty($enseignantsNonUtilisateurs)): ?>
                                <optgroup label="Enseignants" class="font-bold text-custom-primary-dark">
                                    <?php foreach ($enseignantsNonUtilisateurs as $enseignant): ?>
                                        <option value="ens_<?php echo htmlspecialchars($enseignant->id_enseignant); ?>" class="py-1.5 px-3 hover:bg-custom-primary/10 transition-colors cursor-pointer rounded-md my-0.5 block"> <?php echo htmlspecialchars($enseignant->nom_enseignant . ' ' . $enseignant->prenom_enseignant); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </optgroup>
                            <?php endif; ?>
                            <?php if (isset($personnelNonUtilisateurs) && !empty($personnelNonUtilisateurs)): ?>
                                <optgroup label="Personnel Administratif" class="font-bold text-custom-primary-dark">
                                    <?php foreach ($personnelNonUtilisateurs as $personnel): ?>
                                        <option value="pers_<?php echo htmlspecialchars($personnel->id_pers_admin); ?>" class="py-1.5 px-3 hover:bg-custom-primary/10 transition-colors cursor-pointer rounded-md my-0.5 block">
                                            <?php echo htmlspecialchars($personnel->nom_pers_admin . ' ' . $personnel->prenom_pers_admin); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </optgroup>
                            <?php endif; ?>
                            <?php if (isset($etudiantsNonUtilisateurs) && !empty($etudiantsNonUtilisateurs)): ?>
                                <optgroup label="Étudiants" class="font-bold text-custom-primary-dark">
                                    <?php foreach ($etudiantsNonUtilisateurs as $etudiant): ?>
                                        <option value="etu_<?php echo htmlspecialchars($etudiant->num_etu); ?>" class="py-1.5 px-3 hover:bg-custom-primary/10 transition-colors cursor-pointer rounded-md my-0.5 block">
                                            <?php echo htmlspecialchars($etudiant->nom_etu . ' ' . $etudiant->prenom_etu); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </optgroup>
                            <?php endif; ?>
                        </select>
                        <p class="text-xs text-gray-muted mt-2 leading-normal"> <i class="fas fa-info-circle mr-1"></i>
                            Maintenez la touche **Shift** pour sélectionner une plage ou la touche **Ctrl (Cmd sur Mac)** pour une sélection multiple non-contiguë.
                        </p>
                    </div>

                    <div class="flex justify-end gap-4 pt-3 border-t border-gray-100">
                        <button type="button" onclick="closeMasseModal()" class="px-4 py-2 rounded-lg text-sm font-medium shadow-sm text-gray-700 bg-gray-card-bg hover:bg-gray-100 transition-all duration-200"> <i class="fas fa-times mr-2"></i>Annuler
                        </button>
                        <button type="submit" name="btn_add_multiple" class="px-4 py-2 rounded-lg shadow-lg bg-gradient-primary hover:shadow-xl transition-all duration-200"> <i class="fas fa-users mr-2"></i>Ajouter en masse
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div id="disableModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50 hidden modal-transition">
        <div class="bg-gray-card-bg rounded-lg p-6 max-w-sm w-full mx-4 shadow-2xl modal-transition transform scale-95 opacity-0">
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-error/10 mb-4">
                    <i class="fas fa-exclamation-triangle text-red-error text-2xl"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-4">Confirmation de désactivation</h3>
                <p class="text-sm text-gray-600 mb-6">
                    <i class="fas fa-info-circle mr-2"></i>
                    Êtes-vous sûr de vouloir désactiver les utilisateurs sélectionnés ?
                </p>
                <div class="flex justify-center gap-4">
                    <button type="button" id="confirmDelete" class="px-5 py-2 bg-red-error text-white rounded-lg hover:bg-red-error/80 focus:outline-none focus:ring-2 focus:ring-red-error/50 focus:ring-offset-2 transition-all duration-200 font-medium flex items-center gap-1">
                        <i class="fas fa-check"></i>Confirmer
                    </button>
                    <button type="button" id="cancelDelete" class="px-5 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-300 focus:ring-offset-2 transition-all duration-200 font-medium flex items-center gap-1">
                        <i class="fas fa-times"></i>Annuler
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div id="enableModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50 hidden modal-transition">
        <div class="bg-gray-card-bg rounded-lg p-6 max-w-sm w-full mx-4 shadow-2xl modal-transition transform scale-95 opacity-0">
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-custom-success-light/10 mb-4">
                    <i class="fas fa-user-check text-custom-success-light text-2xl"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-4">Confirmation de réactivation</h3>
                <p class="text-sm text-gray-600 mb-6">
                    <i class="fas fa-info-circle mr-2"></i>
                    Êtes-vous sûr de vouloir réactiver les utilisateurs sélectionnés ?
                </p>
                <div class="flex justify-center gap-4">
                    <button type="button" id="confirmEnable" class="px-5 py-2 bg-custom-success-dark text-white rounded-lg hover:bg-custom-success-light/80 focus:outline-none focus:ring-2 focus:ring-custom-success-light/50 focus:ring-offset-2 transition-all duration-200 font-medium flex items-center gap-1">
                        <i class="fas fa-check"></i>Confirmer
                    </button>
                    <button type="button" id="cancelEnable" class="px-5 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-300 focus:ring-offset-2 transition-all duration-200 font-medium flex items-center gap-1">
                        <i class="fas fa-times"></i>Annuler
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div id="modifyModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50 hidden modal-transition">
        <div class="bg-gray-card-bg rounded-lg p-6 max-w-sm w-full mx-4 shadow-2xl modal-transition transform scale-95 opacity-0">
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-blue-info/10 mb-4">
                    <i class="fas fa-edit text-blue-info text-2xl"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-4">Confirmation de modification</h3>
                <p class="text-sm text-gray-600 mb-6">
                    <i class="fas fa-info-circle mr-2"></i>
                    Êtes-vous sûr de vouloir modifier cet utilisateur ?
                </p>
                <div class="flex justify-center gap-4">
                    <button type="button" id="confirmModify" class="px-5 py-2 bg-custom-primary text-white rounded-lg hover:bg-custom-primary-dark focus:outline-none focus:ring-2 focus:ring-custom-primary/50 focus:ring-offset-2 transition-all duration-200 font-medium flex items-center gap-1">
                        <i class="fas fa-check"></i>Confirmer
                    </button>
                    <button type="button" id="cancelModify" class="px-5 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-300 focus:ring-offset-2 transition-all duration-200 font-medium flex items-center gap-1">
                        <i class="fas fa-times"></i>Annuler
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div id="loader" class="fixed inset-0 bg-gray-900 bg-opacity-70 flex items-center justify-center z-50 hidden">
        <div class="bg-gray-card-bg rounded-lg p-8 max-w-xs w-full mx-4 shadow-2xl text-center">
            <div class="flex justify-center items-center mb-6">
                <div class="spinner"></div>
            </div>
            <h3 class="mt-4 text-xl font-bold text-gray-900">Traitement en cours</h3>
            <p class="mt-2 text-sm text-gray-600">Veuillez patienter...</p>
            <div class="mt-5 w-full bg-gray-200 rounded-full h-2">
                <div class="h-full rounded-full progress-bar-fill"></div>
            </div>
        </div>
    </div>

    <script>
        // Variables pour le modal utilisateur
        const userModal = document.getElementById('userModal');
        const userForm = document.getElementById('userForm');
        const userModalTitle = document.getElementById('userModalTitle');

        const searchInput = document.getElementById('searchInput');

        const selectAllCheckbox = document.getElementById('selectAllCheckbox');

        const desactiverButton = document.getElementById('desactiverButton');
        const activerButton = document.getElementById('activerButton');
        const submitDisableHidden = document.getElementById('submitDisableHidden');

        const modifyModal = document.getElementById('modifyModal');
        const confirmModify = document.getElementById('confirmModify');
        const cancelModify = document.getElementById('cancelModify');

        const disableModal = document.getElementById('disableModal');
        const confirmDelete = document.getElementById('confirmDelete');
        const cancelDelete = document.getElementById('cancelDelete');

        const enableModal = document.getElementById('enableModal');
        const confirmEnable = document.getElementById('confirmEnable');
        const cancelEnable = document.getElementById('cancelEnable');

        const submitEnableHidden = document.getElementById('submitEnableHidden');
        const formListeUser = document.getElementById('formListeUtilisateurs');

        // Main loader variable
        const mainLoader = document.getElementById('loader');

        // --- MODAL FUNCTIONS ---

        // Universal modal open/close with transitions
        function openModalWithTransition(modalElement) {
            modalElement.classList.remove('hidden');
            setTimeout(() => {
                modalElement.classList.add('opacity-100');
                modalElement.querySelector('.relative > div:first-child, .bg-white').classList.remove('scale-95', 'opacity-0');
                modalElement.querySelector('.relative > div:first-child, .bg-white').classList.add('scale-100', 'opacity-100');
            }, 50);
        }

        function closeFromOutside(modalElement, event, callback) {
            if (event.target === modalElement) {
                closeModalWithTransition(modalElement);
                if (callback) callback();
            }
        }

        function closeModalWithTransition(modalElement, callback) {
            modalElement.classList.remove('opacity-100');
            modalElement.querySelector('.relative > div:first-child, .bg-white').classList.remove('scale-100', 'opacity-100');
            modalElement.querySelector('.relative > div:first-child, .bg-white').classList.add('scale-95', 'opacity-0');

            modalElement.addEventListener('transitionend', function handler() {
                modalElement.classList.add('hidden');
                if (callback) callback();
                modalElement.removeEventListener('transitionend', handler);
            }, {
                once: true
            });
        }

        // Specific modal open/close functions using the universal ones
        function openUserModal() {
            openModalWithTransition(userModal);
        }

        function closeUserModal() {
            closeModalWithTransition(userModal, () => {
                window.location.href = '?page=gestion_utilisateurs';
            });
        }
        userModal.addEventListener('click', (e) => closeFromOutside(userModal, e, () => {
            window.location.href = '?page=gestion_utilisateurs';
        }));

        function openMasseModal() {
            openModalWithTransition(masseModal);
        }

        function closeMasseModal() {
            closeModalWithTransition(masseModal, () => {
                window.location.href = '?page=gestion_utilisateurs';
            });
        }
        masseModal.addEventListener('click', (e) => closeFromOutside(masseModal, e, () => {
            window.location.href = '?page=gestion_utilisateurs';
        }));

        function openDisableModal() {
            openModalWithTransition(disableModal);
        }

        function closeDisableModal() {
            closeModalWithTransition(disableModal, resetCheckboxesAndButtons);
        }
        disableModal.addEventListener('click', (e) => closeFromOutside(disableModal, e, resetCheckboxesAndButtons));

        function openEnableModal() {
            openModalWithTransition(enableModal);
        }

        function closeEnableModal() {
            closeModalWithTransition(enableModal, resetCheckboxesAndButtons);
        }
        enableModal.addEventListener('click', (e) => closeFromOutside(enableModal, e, resetCheckboxesAndButtons));

        function openModifyModal() {
            openModalWithTransition(modifyModal);
        }

        function closeModifyModal() {
            closeModalWithTransition(modifyModal);
        }
        modifyModal.addEventListener('click', (e) => closeFromOutside(modifyModal, e));


        // --- UI STATE FUNCTIONS ---

        function updateDisableButtonState() {
            const checkedBoxes = document.querySelectorAll('.user-checkbox:checked');
            let hasActiveUsers = false;
            checkedBoxes.forEach(checkbox => {
                const row = checkbox.closest('tr');
                const statusCell = row.querySelector('td:nth-child(4) span');
                if (statusCell && statusCell.textContent.trim() === 'Actif') {
                    hasActiveUsers = true;
                }
            });
            desactiverButton.disabled = !hasActiveUsers;
            desactiverButton.classList.toggle('opacity-50', !hasActiveUsers);
            desactiverButton.classList.toggle('cursor-not-allowed', !hasActiveUsers);
        }

        function updateEnableButtonState() {
            const checkedBoxes = document.querySelectorAll('.user-checkbox:checked');
            let hasInactiveUsers = false;
            checkedBoxes.forEach(checkbox => {
                const row = checkbox.closest('tr');
                const statusCell = row.querySelector('td:nth-child(4) span');
                if (statusCell && statusCell.textContent.trim() === 'Inactif') {
                    hasInactiveUsers = true;
                }
            });
            activerButton.disabled = !hasInactiveUsers;
            activerButton.classList.toggle('opacity-50', !hasInactiveUsers);
            activerButton.classList.toggle('cursor-not-allowed', !hasInactiveUsers);
        }


        // --- EVENT LISTENERS & DOM CONTENT LOADED ---

        document.addEventListener('DOMContentLoaded', function() {
            // Initial modal display based on URL action
            const action = new URLSearchParams(window.location.search).get('action');
            if (action === 'add' || action === 'edit') {
                openUserModal();
            } else if (action === 'addMasse') {
                openMasseModal();
            }

            // Observe elements for fade-in animation
            document.querySelectorAll('.initial-hidden').forEach(el => {
                observer.observe(el);
            });

            // Call filterReports on load to populate the table (important for initial display)
            filterReports();

            // Auto-update login field in add/edit modal
            document.getElementById('nom_utilisateur')?.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const loginInput = document.getElementById('login_utilisateur');
                if (selectedOption && selectedOption.dataset.login) {
                    loginInput.value = selectedOption.dataset.login;
                }
            });

            // Manage notification display and auto-hide
            const successNotification = document.getElementById('successNotification');
            const errorNotification = document.getElementById('errorNotification');

            if (successNotification) {
                setTimeout(() => {
                    successNotification.classList.add('animate-fade-out-custom');
                }, 4500);
                successNotification.addEventListener('animationend', (event) => {
                    if (event.animationName === 'fadeOut') {
                        successNotification.remove();
                    }
                });
            }
            if (errorNotification) {
                setTimeout(() => {
                    errorNotification.classList.add('animate-fade-out-custom');
                }, 4500);
                errorNotification.addEventListener('animationend', (event) => {
                    if (event.animationName === 'fadeOut') {
                        errorNotification.remove();
                    }
                });
            }

            // Initial button states for activate/deactivate
            updateDisableButtonState();
            updateEnableButtonState();
        });

        // Search input event listener (debounced for performance if many users)
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                filterReports();
            }, 300); // Debounce search for 300ms
        });


        // Select all checkboxes functionality
        selectAllCheckbox.addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.user-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateDisableButtonState();
            updateEnableButtonState();
        });

        // Event listener for individual checkbox changes
        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('user-checkbox')) {
                updateDisableButtonState();
                updateEnableButtonState();
                const allCheckboxes = document.querySelectorAll('.user-checkbox');
                const checkedBoxes = document.querySelectorAll('.user-checkbox:checked');
                selectAllCheckbox.checked = checkedBoxes.length === allCheckboxes.length && allCheckboxes.length > 0;
            }
        });


        // --- MODAL & FORM SUBMISSION LOGIC ---

        function submitModifyForm() {
            openModifyModal();
        }

        confirmModify.addEventListener('click', function() {
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'btn_modifier_utilisateur';
            hiddenInput.value = '1';
            userForm.appendChild(hiddenInput);
            userForm.submit();
        });
        cancelModify.addEventListener('click', closeModifyModal);

        desactiverButton.addEventListener('click', openDisableModal);
        confirmDelete.addEventListener('click', function() {
            submitDisableHidden.value = '2';
            submitEnableHidden.value = '0';
            formListeUser.submit();
            showLoader("Désactivation en cours...");
        });
        cancelDelete.addEventListener('click', closeDisableModal);

        activerButton.addEventListener('click', openEnableModal);
        confirmEnable.addEventListener('click', function() {
            submitEnableHidden.value = '3';
            submitDisableHidden.value = '0';
            formListeUser.submit();
            showLoader("Activation en cours...");
        });
        cancelEnable.addEventListener('click', closeEnableModal);

        function resetCheckboxesAndButtons() {
            const checkboxes = document.querySelectorAll('.user-checkbox:checked');
            checkboxes.forEach(checkbox => {
                checkbox.checked = false;
            });
            selectAllCheckbox.checked = false;
            updateDisableButtonState();
            updateEnableButtonState();
        }

        document.getElementById('userMasse').addEventListener('submit', function(e) {
            e.preventDefault();
            showLoader("Ajout en masse en cours...");
            this.submit();
        });


        // --- LOADER MANAGEMENT ---

        function showLoader(message = "Chargement...") {
            mainLoader.classList.remove('hidden');
            const loaderText = mainLoader.querySelector('h3');
            if (loaderText) loaderText.textContent = message;
            const progressBar = mainLoader.querySelector('.progress-bar-fill');
            if (progressBar) {
                progressBar.style.width = '0%';
                progressBar.classList.add('animate-progress-bar');
            }
        }

        function hideLoader() {
            mainLoader.classList.add('hidden');
            const progressBar = mainLoader.querySelector('.progress-bar-fill');
            if (progressBar) {
                progressBar.classList.remove('animate-progress-bar');
                progressBar.style.width = '100%';
            }
        }

        // Hide loader when page is fully loaded and messages (if any) are processed
        window.addEventListener('load', hideLoader);
        const globalSuccessMessage = <?= json_encode($GLOBALS['messageSuccess'] ?? '') ?>;
        const globalErrorMessage = <?= json_encode($GLOBALS['messageErreur'] ?? '') ?>;
        if (globalSuccessMessage || globalErrorMessage) {
            hideLoader();
        } else {
            document.addEventListener('DOMContentLoaded', hideLoader);
        }

        // --- FILTERING & PAGINATION ---

        function filterReports() {
            const searchTerm = searchInput.value.toLowerCase();
            const tableBody = document.getElementById('usersTableBody');
            let hasVisibleResults = false;

            const allUsers = <?php echo json_encode(array_map(function ($user) {
                                    return [
                                        'id' => $user->id_utilisateur,
                                        'username' => $user->nom_utilisateur . ' ' . (isset($user->prenom_utilisateur) ? $user->prenom_utilisateur : ''), // Combine name fields
                                        'groupe' => $user->lib_GU,
                                        'statut' => $user->statut_utilisateur,
                                        'login' => $user->login_utilisateur
                                    ];
                                }, $GLOBALS['allUtilisateurs'] ?? [])); ?>;

            const filteredUsers = allUsers.filter(user =>
                user.username.toLowerCase().includes(searchTerm) ||
                user.groupe.toLowerCase().includes(searchTerm) ||
                user.statut.toLowerCase().includes(searchTerm) ||
                user.login.toLowerCase().includes(searchTerm)
            );

            tableBody.innerHTML = '';
            if (filteredUsers.length > 0) {
                hasVisibleResults = true;
                filteredUsers.forEach(user => {
                    const row = document.createElement('tr');
                    row.className = 'hover:bg-gray-50 transition-colors duration-150';
                    row.innerHTML = `
                        <td class="px-4 py-4 text-center">
                            <input type="checkbox" name="selected_ids[]" value="${user.id}" class="custom-checkbox">
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 font-medium">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 flex items-center justify-center rounded-full bg-custom-primary/10 text-custom-primary text-xs font-bold flex-shrink-0">
                                    ${user.username.split(' ').map(n => n.charAt(0)).join('').toUpperCase()}
                                </div>
                                <span>${user.username}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                            ${user.groupe}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold
                                ${user.statut === 'Actif' ? 'bg-custom-success-light/10 text-custom-success-dark' : 'bg-red-error/10 text-red-error'}">
                                ${user.statut}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                            <div class="flex items-center">
                                <i class="fas fa-envelope text-gray-muted mr-2"></i>
                                ${user.login}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <div class="flex justify-center space-x-3">
                                <a href="?page=gestion_utilisateurs&action=edit&id_utilisateur=${user.id}"
                                    class="text-blue-info hover:text-custom-primary transition-colors duration-150 text-lg p-2 rounded-full hover:bg-blue-info/10"
                                    title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" onclick="confirmDeleteIndividual('${user.id}')"
                                    class="text-red-error hover:text-danger transition-colors duration-150 text-lg p-2 rounded-full hover:bg-red-error/10"
                                    title="Supprimer">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </td>
                    `;
                    tableBody.appendChild(row);
                });
            } else {
                const noResultsRow = document.createElement('tr');
                noResultsRow.className = 'no-results';
                noResultsRow.innerHTML = `
                    <td colspan="6" class="px-6 py-12 text-center text-gray-muted">
                        <div class="flex flex-col items-center">
                            <i class="fas fa-search text-gray-300 text-5xl mb-4"></i>
                            <p class="text-lg font-medium">Aucun résultat trouvé pour "${searchTerm}".</p>
                            <p class="text-sm mt-2">Essayez une autre recherche.</p>
                        </div>
                    </td>
                `;
                tableBody.appendChild(noResultsRow);
            }

            updateDisableButtonState();
            updateEnableButtonState();

            const paginationContainer = document.querySelector('.pagination');
            if (paginationContainer) {
                paginationContainer.style.display = (searchTerm === '') ? 'flex' : 'none';
            }
        }

        // --- PRINT AND EXPORT FUNCTIONS ---

        function exportToExcel() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const allUsersForExport = <?php echo json_encode(array_map(function ($user) {
                                            return [
                                                'username' => $user->nom_utilisateur . ' ' . (isset($user->prenom_utilisateur) ? $user->prenom_utilisateur : ''),
                                                'groupe' => $user->lib_GU,
                                                'statut' => $user->statut_utilisateur,
                                                'login' => $user->login_utilisateur
                                            ];
                                        }, $GLOBALS['allUtilisateurs'] ?? [])); ?>;

            const usersToExport = searchTerm ? allUsersForExport.filter(user =>
                user.username.toLowerCase().includes(searchTerm) ||
                user.groupe.toLowerCase().includes(searchTerm) ||
                user.statut.toLowerCase().includes(searchTerm) ||
                user.login.toLowerCase().includes(searchTerm)
            ) : allUsersForExport;

            let csvContent = "data:text/csv;charset=utf-8,";
            csvContent += "Nom d'utilisateur,Groupe utilisateur,Statut,Login\n";
            usersToExport.forEach(user => {
                csvContent += `"${user.username}","${user.groupe}","${user.statut}","${user.login}"\n`;
            });

            const encodedUri = encodeURI(csvContent);
            const link = document.createElement('a');
            link.setAttribute('href', encodedUri);
            link.setAttribute('download', 'utilisateurs.csv');
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

        function printTable() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const allUsersForPrint = <?php echo json_encode(array_map(function ($user) {
                                            return [
                                                'username' => $user->nom_utilisateur . ' ' . (isset($user->prenom_utilisateur) ? $user->prenom_utilisateur : ''),
                                                'groupe' => $user->lib_GU,
                                                'statut' => $user->statut_utilisateur,
                                                'login' => $user->login_utilisateur
                                            ];
                                        }, $GLOBALS['allUtilisateurs'] ?? [])); ?>;

            const usersToPrint = searchTerm ? allUsersForPrint.filter(user =>
                user.username.toLowerCase().includes(searchTerm) ||
                user.groupe.toLowerCase().includes(searchTerm) ||
                user.statut.toLowerCase().includes(searchTerm) ||
                user.login.toLowerCase().includes(searchTerm)
            ) : allUsersForPrint;

            const printWindow = window.open('', '_blank');
            const tableHTML = `
                <html>
                    <head>
                        <title>Liste des utilisateurs</title>
                        <style>
                            body { font-family: 'Inter', sans-serif; padding: 20px; }
                            h2 { font-size: 24px; margin-bottom: 20px; text-align: center; }
                            table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                            th, td { border: 1px solid #e5e7eb; padding: 12px; text-align: left; font-size: 14px; }
                            th { background-color: #f3f4f6; font-weight: bold; color: #4b5563; text-transform: uppercase; }
                            tr:nth-child(even) { background-color: #f9fafb; }
                            .status-badge {
                                display: inline-block;
                                padding: 4px 8px;
                                border-radius: 9999px;
                                font-size: 11px;
                                font-weight: 600;
                            }
                            .status-active { background-color: rgba(89, 191, 61, 0.1); color: #36865a; }
                            .status-inactive { background-color: rgba(220, 38, 38, 0.1); color: #dc2626; }
                        </style>
                    </head>
                    <body>
                        <h2>Liste des utilisateurs</h2>
                        <table>
                            <thead>
                                <tr>
                                    <th>Nom d'utilisateur</th>
                                    <th>Groupe utilisateur</th>
                                    <th>Statut</th>
                                    <th>Login</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${usersToPrint.map(user => `
                                    <tr>
                                        <td>${user.username}</td>
                                        <td>${user.groupe}</td>
                                        <td><span class="status-badge ${user.statut === 'Actif' ? 'status-active' : 'status-inactive'}">${user.statut}</span></td>
                                        <td>${user.login}</td>
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                    </body>
                </html>
            `;

            printWindow.document.write(tableHTML);
            printWindow.document.close();
            printWindow.focus();
            printWindow.print();
            printWindow.close();
        }

        // --- INDIVIDUAL DELETE CONFIRMATION ---
        function confirmDeleteIndividual(userId) {
            if (confirm(`Êtes-vous sûr de vouloir supprimer l'utilisateur avec l'ID ${userId} ?`)) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '?page=gestion_utilisateurs';

                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'delete_user_id';
                input.value = userId;
                form.appendChild(input);

                document.body.appendChild(form);
                form.submit();
                showLoader("Suppression en cours...");
            }
        }
    </script>

</body>

</html>
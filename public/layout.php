<?php
session_start();
// Inclure les fichiers nécessaires
include '../app/config/database.php';
include '../app/controllers/AuthController.php';
include '../app/controllers/MenuController.php';
include 'menu.php';

// Initialiser le middleware d'audit


//inclusion des routes
include __DIR__ . '/../ressources/routes/gestionUtilisateurRoutes.php';
include __DIR__ . '/../ressources/routes/gestionRhRoutes.php';
include __DIR__ . '/../ressources/routes/gestionDashboardRoutes.php';
include __DIR__ . '/../ressources/routes/gestionScolariteRoutes.php';
include __DIR__ . '/../ressources/routes/gestionNotesRoutes.php';
include __DIR__ . '/../ressources/routes/gestionCandidaturesRoutes.php';
include __DIR__ . '/../ressources/routes/verificationRapportsRoutes.php';
include __DIR__ . '/../ressources/routes/gestionReclamationsScolariteRoutes.php';
include __DIR__ . '/../ressources/routes/evaluationDossiersRoutes.php';
include __DIR__ . '/../ressources/routes/gestionDossiersCandidaturesRoutes.php';
include __DIR__ . '/../ressources/routes/sauvegardeRestaurationRoutes.php';
include __DIR__ . '/../ressources/routes/notesResultatsRoutes.php';


// Si l'utilisateur n'est pas connecté, rediriger vers la page de login
if (!isset($_SESSION['id_utilisateur'])) {
    header('Location: page_connexion.php');
    exit;
} else {

    // Récupérer les traitements autorisés pour le groupe d'utilisateur
    $menuController = new MenuController();
    $traitements = $menuController->genererMenu($_SESSION['id_GU']);

    // Déterminer la page actuelle
    $currentMenuSlug = ''; // Page par défaut
    $currentPageLabel = ''; // Label par défaut

    if (isset($_GET['page'])) {
        // Vérifier que la page demandée est bien dans les traitements autorisés
        foreach ($traitements as $traitement) {
            if ($traitement['lib_traitement'] === $_GET['page']) {
                $currentMenuSlug = $traitement['lib_traitement'];
                $currentPageLabel = $traitement['label_traitement'];
                break;
            }
        }

        // Exception pour les pages spéciales qui ne sont pas dans les traitements
        if (empty($currentMenuSlug)) {
            $specialPages = ['archive_comptes_rendus', 'redaction_compte_rendu'];
            if (in_array($_GET['page'], $specialPages)) {
                $currentMenuSlug = $_GET['page'];
                $currentPageLabel = ($_GET['page'] === 'archive_comptes_rendus') ? 'Archives des comptes rendus' : 'Rédaction de compte rendu';
            }
        }
    }

    // Si aucune page valide n'a été trouvée, utiliser la première page autorisée
    if (empty($currentMenuSlug) && !empty($traitements)) {
        $currentMenuSlug = $traitements[0]['lib_traitement'];
        $currentPageLabel = $traitements[0]['label_traitement'];

        // Rediriger vers la première page avec le paramètre page pour éviter les problèmes de rechargement
        if (!isset($_GET['page'])) {
            header('Location: layout.php?page=' . urlencode($currentMenuSlug));
            exit;
        }
    }

    // Générer le HTML du menu
    $menuView = new MenuView();
    $menuHTML = $menuView->afficherMenu($traitements, $currentMenuSlug);

    // Initialiser les variables
    $currentAction = null;
    $contentFile = '';


    $partialsBasePath = '..' . DIRECTORY_SEPARATOR . 'ressources' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR;



    // Utilisation d'une structure switch...case 
    switch ($currentMenuSlug) {
        case 'parametres_generaux':
            include __DIR__ . '/../ressources/routes/parametreGenerauxRouteur.php'; // ajuste le chemin selon ta structure
            if (isset($_GET['action'])) {
                $allowedActions = [
                    'annees_academiques',
                    'grades',
                    'fonctions',
                    'fonction_utilisateur',
                    'specialites',
                    'niveaux_etude',
                    'ue',
                    'ecue',
                    'statut_jury',
                    'niveaux_approbation',
                    'semestres',
                    'niveaux_acces',
                    'traitements',
                    'entreprises',
                    'actions',
                    'fonctions_enseignants',
                    'messages',
                    'gestion_attribution',
                ];
                if (in_array($_GET['action'], $allowedActions)) {
                    $currentAction = $_GET['action'];
                    $contentFile = $partialsBasePath . 'parametres_generaux' . DIRECTORY_SEPARATOR . $currentAction . '.php';
                    $currentPageLabel = ucfirst(str_replace('_', ' ', $currentAction));
                }
            } else {
                // Action non valide, afficher la page des cartes par défaut ou une erreur
                $contentFile = $partialsBasePath . 'parametres_generaux_content.php';
                $currentPageLabel = 'Paramètres Généraux';
                // Optionnel: afficher un message d'erreur pour action non valide
            }
            break;

        case 'gestion_reclamations':
            include __DIR__ . '/../ressources/routes/gestionReclamationsRouteur.php'; // Ajustez le chemin si nécessaire

            $allowedActions = ['soumettre_reclamation', 'suivi_historique_reclamation'];
            $ajaxActions = ['get_reclamation_details'];

            if (isset($_GET['action'])) {
                if (in_array($_GET['action'], $ajaxActions)) {
                    // Les actions AJAX sont déjà traitées dans les routes, pas besoin de fichier de vue
                    exit;
                } elseif (in_array($_GET['action'], $allowedActions)) {
                    $currentAction = $_GET['action'];
                    $contentFile = $partialsBasePath . 'gestion_reclamations/' . $currentAction . '.php';
                    $currentPageLabel = ucfirst(str_replace('_', ' ', $currentAction));
                } else {
                    // Si aucune action valide n'est spécifiée, affichez la page par défaut
                    $contentFile = $partialsBasePath . 'gestion_reclamations_content.php';
                    $currentPageLabel = 'Gestion des réclamations';
                }
            } else {
                // Si aucune action valide n'est spécifiée, affichez la page par défaut
                $contentFile = $partialsBasePath . 'gestion_reclamations_content.php';
                $currentPageLabel = 'Gestion des réclamations';
            }

            break;

        case 'gestion_rapports':
            // Ajustez le chemin si nécessaire
            include __DIR__ . '/../ressources/routes/gestionRapportsRoutes.php';

            $allowedActions = ['creer_rapport', 'suivi_rapport', 'commentaire_rapport'];
            $ajaxActions = ['get_commentaires', 'get_rapport'];

            if (isset($_GET['action'])) {
                if (in_array($_GET['action'], $ajaxActions)) {
                    // Les actions AJAX sont déjà traitées dans les routes, pas besoin de fichier de vue
                    exit;
                } elseif (in_array($_GET['action'], $allowedActions)) {
                    $currentAction = $_GET['action'];
                    $contentFile = $partialsBasePath . 'gestion_rapports/' . $currentAction . '.php';
                    $currentPageLabel = ucfirst(str_replace('_', ' ', $currentAction));
                } else {
                    // Si aucune action valide n'est spécifiée, affichez la page par défaut
                    $contentFile = $partialsBasePath . 'gestion_rapports_content.php';
                    $currentPageLabel = 'Gestion des rapports';
                }
            } else {
                // Si aucune action n'est spécifiée, affichez la page par défaut
                $contentFile = $partialsBasePath . 'gestion_rapports_content.php';
                $currentPageLabel = 'Gestion des rapports';
            }

            break;
        case 'candidature_soutenance':
            // Ajustez le chemin si nécessaire
            include __DIR__ . '/../ressources/routes/candidatureSoutenanceRoutes.php';

            $allowedActions = ['compte_rendu_etudiant'];

            if (isset($_GET['action']) && in_array($_GET['action'], $allowedActions)) {
                $currentAction = $_GET['action'];
                $contentFile = $partialsBasePath . 'candidature_soutenance/' . $currentAction . '.php';
                $currentPageLabel = ucfirst(str_replace('_', ' ', $currentAction));
            } else {
                // Si aucune action valide n'est spécifiée, affichez la page par défaut
                $contentFile = $partialsBasePath . 'candidature_soutenance_content.php';
                $currentPageLabel = 'Candidater pour la soutenance';
            }


            break;
        case 'gestion_etudiants':
            // Ajustez le chemin si nécessaire
            include __DIR__ . '/../ressources/routes/gestionEtudiantRoutes.php';

            // Gérer l'action d'impression de reçu PDF
            if (isset($_GET['modalAction']) && $_GET['modalAction'] === 'imprimer_recu' && isset($_GET['id_inscription'])) {
                // Inclure l'autoloader de Composer pour Dompdf
                require_once __DIR__ . '/../vendor/autoload.php'; // Assurez-vous que ce chemin est correct

                $id_inscription = $_GET['id_inscription'];

                // Démarrer la mise en mémoire tampon de sortie
                ob_start();

                // Inclure le fichier du modèle de reçu
                include __DIR__ . '../../ressources/views/gestion_etudiants/recu_inscription.php';

                // Capturer le contenu de la mémoire tampon et le stocker dans $html
                $html = ob_get_clean();

                // Instancier Dompdf
                $dompdf = new Dompdf\Dompdf();

                // Définir le répertoire de base pour les ressources (images, css)
                $dompdf->setBasePath(__DIR__ . '../public/images/');

                // Charger le HTML

                $dompdf->loadHtml($html);

                // (Optionnel) Définir la taille et l\'orientation du papier
                $dompdf->setPaper('A4', 'portrait');

                // Rendre le PDF
                $dompdf->render();

                // Envoyer le PDF au navigateur
                // Le paramètre Attachment => false permet d\'afficher le PDF directement
                $dompdf->stream("recu_paiement_" . $id_inscription . ".pdf", array("Attachment" => false));

                exit; // Arrêter l\'exécution pour ne pas charger le reste de la page
            }

            $allowedActions = ['ajouter_des_etudiants', 'inscrire_des_etudiants'];


            if (isset($_GET['action']) && in_array($_GET['action'], $allowedActions)) {
                $currentAction = $_GET['action'];
                $contentFile = $partialsBasePath . 'gestion_etudiants/' . $currentAction . '.php';
                $currentPageLabel = ucfirst(str_replace('_', ' ', $currentAction));
            } else {
                // Si aucune action valide n'est spécifiée, affichez la page par défaut
                $contentFile = $partialsBasePath . 'gestion_etudiants_content.php';
                $currentPageLabel = 'Gestion des étudiants';
            }
            break;
        case 'gestion_scolarite':
            // Gérer l'action d'impression de reçu PDF
            if (isset($_GET['action']) && $_GET['action'] === 'imprimer_recu' && isset($_GET['id'])) {
                // Inclure l'autoloader de Composer pour Dompdf
                require_once __DIR__ . '/../vendor/autoload.php'; // Assurez-vous que ce chemin est correct

                $id_versement = $_GET['id'];

                // Démarrer la mise en mémoire tampon de sortie
                ob_start();

                // Inclure le fichier du modèle de reçu
                include __DIR__ . '../../ressources/views/recu_versement.php';

                // Capturer le contenu de la mémoire tampon et le stocker dans $html
                $html = ob_get_clean();

                // Instancier Dompdf
                $dompdf = new Dompdf\Dompdf();

                // Définir le répertoire de base pour les ressources (images, css)
                $dompdf->setBasePath(__DIR__ . '../public/images/');

                // Charger le HTML

                $dompdf->loadHtml($html);

                // (Optionnel) Définir la taille et l\'orientation du papier
                $dompdf->setPaper('A4', 'portrait');

                // Rendre le PDF
                $dompdf->render();

                // Envoyer le PDF au navigateur
                // Le paramètre Attachment => false permet d\'afficher le PDF directement
                $dompdf->stream("recu_paiement_" . $id_versement . ".pdf", array("Attachment" => false));

                exit; // Arrêter l\'exécution pour ne pas charger le reste de la page
            }
            // Afficher la vue par défaut
            $contentFile = $partialsBasePath . 'gestion_scolarite_content.php';
            $currentPageLabel = 'Gestion de la scolarité';

            break;

        case 'gestion_notes_evaluations':
            // Gérer l'action d'impression de reçu PDF
            if (isset($_GET['action']) && $_GET['action'] === 'imprimer_releve' && isset($_GET['student']) && isset($_GET['niveau'])) {
                // Inclure l'autoloader de Composer pour Dompdf
                require_once __DIR__ . '/../vendor/autoload.php'; // Assurez-vous que ce chemin est correct

                $id_etudiant = $_GET['student'];
                $niveau = $_GET['niveau'];

                // Démarrer la mise en mémoire tampon de sortie
                ob_start();

                // Inclure le fichier du modèle de reçu
                include __DIR__ . '../../ressources/views/releve_notes.php';

                // Capturer le contenu de la mémoire tampon et le stocker dans $html
                $html = ob_get_clean();

                // Instancier Dompdf
                $dompdf = new Dompdf\Dompdf();

                // Définir le répertoire de base pour les ressources (images, css)
                $dompdf->setBasePath(__DIR__ . '../public/images/');

                // Charger le HTML

                $dompdf->loadHtml($html);

                // (Optionnel) Définir la taille et l\'orientation du papier
                $dompdf->setPaper('A4', 'portrait');

                // Rendre le PDF
                $dompdf->render();

                // Envoyer le PDF au navigateur
                // Le paramètre Attachment => false permet d\'afficher le PDF directement
                $dompdf->stream("releve_notes_" . $id_etudiant . "_" . $niveau . ".pdf", array("Attachment" => false));

                exit; // Arrêter l\'exécution pour ne pas charger le reste de la page
            }
            // Afficher la vue par défaut
            $contentFile = $partialsBasePath . 'gestion_notes_evaluations_content.php';
            $currentPageLabel = 'Gestion des notes et évaluations';

            break;

        case 'gestion_dossiers_candidatures':
            // Le fichier de routes est déjà inclus au début
            // Afficher directement la page de gestion des dossiers de candidatures vérifiés
            $contentFile = $partialsBasePath . 'gestion_dossiers_candidatures_content.php';
            $currentPageLabel = 'Gestion des dossiers de candidatures vérifiés';
            break;

        case 'evaluations_dossiers_soutenance':
            // Le fichier de routes est déjà inclus au début
            // Afficher directement la page d'évaluation des dossiers
            $contentFile = $partialsBasePath . 'evaluations_dossiers_soutenance_content.php';
            $currentPageLabel = 'Évaluations des dossiers de soutenance';
            break;

        case 'archive_comptes_rendus':
            // Le fichier de routes est déjà inclus au début
            // Afficher directement la page des archives de comptes rendus
            $contentFile = $partialsBasePath . 'redaction_compte_rendu/archives_compte_rendu_content.php';
            $currentPageLabel = 'Archives des comptes rendus';
            break;

        default:

            $groupeUtilisateur = $_SESSION['lib_GU'];


            if ($groupeUtilisateur) {

                $contentFile = $partialsBasePath . $currentMenuSlug . '_content.php';
            }

            // Vérification du fichier de contenu
            if (empty($contentFile) || !file_exists($contentFile)) {
                $contentFile = ''; // Réinitialiser si le fichier n'existe pas
            }
            break;
    }

    // Debug temporaire
    if (isset($_GET['page'])) {
        error_log('PAGE DEMANDEE : ' . $_GET['page']);
        foreach ($traitements as $t) {
            error_log('TRAITEMENT AUTORISE : ' . $t['lib_traitement']);
        }
    }




    // Tableau de données pour vos cartes avec des titres et descriptions personnalisés.
    // Chaque élément du tableau représente une carte pour .
    $cardPGeneraux = [
        [
            'title' => 'Années Académiques',
            'description' => 'Gérer les années académiques, les dates de début et de fin.',
            'link' => '?page=parametres_generaux&action=annees_academiques', // Adaptez le lien
            'icon' => './images/date-du-calendrier.png' // Optionnel: vous pouvez ajouter une icône
        ],
        [
            'title' => 'Gestion des Grades',
            'description' => 'Définir et administrer les différents grades académiques.',
            'link' => '?page=parametres_generaux&action=grades',
            'icon' => './images/diplome.png'
        ],
        [
            'title' => 'Fonctions Utilisateurs',
            'description' => 'Configurer les rôles et fonctions des utilisateurs du système.',
            'link' => '?page=parametres_generaux&action=fonction_utilisateur&tab=groupes',
            'icon' => './images/equipe.png'
        ],
        [
            'title' => 'Spécialités des enseignants',
            'description' => 'Administrer les spécialités et filières proposées.',
            'link' => '?page=parametres_generaux&action=specialites',
            'icon' => './images/marche-de-niche.png'
        ],
        [
            'title' => 'Niveaux d\'Étude',
            'description' => 'Gérer les différents niveaux d\'étude (Licence, Master, etc.).',
            'link' => '?page=parametres_generaux&action=niveaux_etude',
            'icon' => './images/livre.png'
        ],
        [
            'title' => 'Unités d\'Enseignement (UE)',
            'description' => 'Définir les unités d\'enseignement et leurs crédits.',
            'link' => '?page=parametres_generaux&action=ue',
            'icon' => './images/livre-ouvert.png'
        ],
        [
            'title' => 'Éléments Constitutifs (ECUE)',
            'description' => 'Gérer les éléments constitutifs des unités d\'enseignement.',
            'link' => '?page=parametres_generaux&action=ecue',
            'icon' => './images/piece-de-puzzle.png'
        ],
        [
            'title' => 'Statuts du Jury',
            'description' => 'Configurer les différents statuts possibles pour les membres du jury.',
            'link' => '?page=parametres_generaux&action=statut_jury',
            'icon' => './images/droit.png'
        ],

        [
            'title' => 'Niveaux d\'Approbation',
            'description' => 'Définir les circuits et niveaux d\'approbation pour les documents.',
            'link' => '?page=parametres_generaux&action=niveaux_approbation',
            'icon' => './images/check.png'
        ],
        [
            'title' => 'Semestres',
            'description' => 'Définir les différents semestres et UE associées.',
            'link' => '?page=parametres_generaux&action=semestres',
            'icon' => './images/diplome.png'
        ],
        [
            'title' => 'Niveaux d\'Accès',
            'description' => 'Définir les différents niveaux d\'accès pour les utilisateurs',
            'link' => '?page=parametres_generaux&action=niveaux_acces',
            'icon' => './images/check.png',
        ],
        [
            'title' => 'Traitements',
            'description' => 'Définir les traitements à affecter aux différents utilisateurs.',
            'link' => '?page=parametres_generaux&action=traitements',
            'icon' => './images/bd.png'
        ],
        [
            'title' => 'Entreprises',
            'description' => 'Gérer les entreprises partenaires et leurs informations.',
            'link' => '?page=parametres_generaux&action=entreprises',
            'icon' => './images/valise.png'
        ],
        [
            'title' => 'Actions',
            'description' => 'Définir les actions possibles pour les utilisateurs dans le système.',
            'link' => '?page=parametres_generaux&action=actions',
            'icon' => './images/cible.png'
        ],
        [
            'title' => 'Fonctions',
            'description' => 'Définir les fonctions exercées par les enseignants dans le système.',
            'link' => '?page=parametres_generaux&action=fonctions',
            'icon' => './images/valise.png'
        ],
        [
            'title' => 'Messagerie',
            'description' => 'Définition des messages d\'erreur à afficher dans le système.',
            'link' => '?page=parametres_generaux&action=messages',
            'icon' => './images/enveloppe.png'
        ],
        [
            'title' => 'Gestion des Attributions',
            'description' => 'Gérer les attributions de traitement pour chacun des groupes utilisateurs dans le système.',
            'link' => '?page=parametres_generaux&action=gestion_attribution',
            'icon' => './images/attribution.png'
        ],
    ];

    $cardReclamation = [
        [
            'title' => 'Soumettre une Réclamation',
            'description' => 'Déposez une nouvelle réclamation en remplissant le formulaire dédié.',
            'link' => '?page=gestion_reclamations&action=soumettre_reclamation', // Adaptez le lien
            'icon' => 'fa-solid fa-circle-exclamation ', // Optionnel: vous pouvez ajouter une icône
            'title_link' => 'Soumettre',
            'bg_color' => 'bg-blue-300 ',
            'text_color' => 'text-blue-500'
        ],
        [
            'title' => 'Suivi et historique des réclamations',
            'description' => 'Consultez l\'état actuel de vos réclamations en cours et accédez à l\'historique complet de vos réclamations passées.',
            'link' => '?page=gestion_reclamations&action=suivi_historique_reclamation',
            'icon' => 'fa-solid fa-eye ',
            'title_link' => 'Suivi et historique',
            'bg_color' => 'bg-yellow-500 ',
            'text_color' => 'text-yellow-600'
        ]

    ];
}


?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UniValid | <?php echo htmlspecialchars($currentPageLabel); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="shortcut icon" href="./images/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    'primary-blue': '#2563eb',
                    'accent-green': '##2BA065',
                    'sidebar-blue': '#C4D4F2',
                    'header-blue': '#C4D4F2',
                    'soft-gray': '#f7fafc',
                },
                animation: {
                    'float': 'float 3s ease-in-out infinite',
                }
            }
        }
    }
    </script>
    <style>
    @keyframes float {

        0%,
        100% {
            transform: translateY(0px);
        }

        50% {
            transform: translateY(-10px);
        }
    }
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.12.0/cdn.min.js" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.1/chart.min.js"></script>
</head>

<body
    class="min-h-screen bg-gradient-to-br from-soft-gray via-white to-sidebar-blue font-sans antialiased relative overflow-x-hidden">
    <!-- Cercles décoratifs flottants -->
    <div
        class="pointer-events-none fixed -top-32 -left-32 w-96 h-96 bg-primary-blue rounded-full opacity-10 z-0 animate-float">
    </div>
    <div
        class="pointer-events-none fixed bottom-0 right-0 w-80 h-80 bg-accent-green rounded-full opacity-10 z-0 animate-float">
    </div>
    <div x-data="{ sidebarOpen: false }" class="flex h-screen bg-transparent overflow-hidden relative z-10">
        <!-- Sidebar -->
        <div x-show="sidebarOpen" @click.away="sidebarOpen = false" class="fixed inset-0 z-40 flex md:hidden">
            <div class="fixed inset-0 bg-gray-600 bg-opacity-75"></div>
            <div class="relative flex-1 flex flex-col max-w-xs w-full bg-sidebar-blue rounded-r-3xl shadow-xl p-4">
                <div class="absolute top-0 right-0 -mr-12 pt-2">
                    <button @click="sidebarOpen = false"
                        class="ml-1 flex items-center justify-center h-10 w-10 rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white">
                        <span class="sr-only">Close sidebar</span>
                        <i class="fas fa-times text-primary-blue"></i>
                    </button>
                </div>
                <!-- Sidebar content for mobile -->
                <div class="flex items-center justify-center h-20 mb-6 opacity-45">
                    <img src="./images/logo.png" height="80" width="80" alt="Logo" class="rounded-full shadow-md">
                </div>
                <div class="flex-1 h-0 pb-4 overflow-y-auto">
                    <nav class="space-y-1">
                        <?php echo $menuHTML; ?>
                        <form action="logout.php" method="POST" class="w-full mt-4">
                            <button type="submit"
                                class="w-full flex items-center justify-center px-4 py-3 text-sm font-semibold rounded-full text-white bg-primary-blue hover:bg-accent-green hover:text-white transition-all duration-200 shadow">
                                <i class="fas fa-power-off mr-2"></i> Déconnexion
                            </button>
                        </form>
                    </nav>
                </div>
            </div>
        </div>
        <!-- Static sidebar for desktop -->
        <div class="hidden md:flex md:flex-shrink-0 min-h-screen">
            <div class="flex flex-col w-64 h-full bg-sidebar-blue rounded-3xl shadow-xl m-4 p-4">
                <div class="flex items-center justify-center h-20 mb-6">
                    <img src="./images/logo.png" height="100" width="100" alt="Logo">
                </div>
                <div class="flex flex-col flex-grow overflow-y-auto">
                    <nav class="space-y-3">
                        <?php echo $menuHTML; ?>
                    </nav>
                </div>
                <div class="mt-6">
                    <form action="logout.php" method="POST" id="logoutFormDesktop" class="w-full">
                        <button type="submit" form="logoutFormDesktop"
                            class="w-full flex items-center justify-center px-4 py-3 text-sm font-semibold rounded-full text-white bg-primary-blue hover:bg-accent-green hover:text-white transition-all duration-200 shadow">
                            <i class="fas fa-power-off mr-2"></i> Déconnexion
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <!-- Main content -->
        <div class="flex flex-col flex-1 w-0 overflow-hidden">
            <!-- Top navigation -->
            <div
                class="relative z-10 flex-shrink-0 flex h-20 bg-header-blue shadow-xl rounded-3xl m-4 mx-4 items-center px-8">
                <button @click.stop="sidebarOpen = true"
                    class="px-4 border-r border-gray-200 text-primary-blue focus:outline-none focus:ring-2 focus:ring-inset focus:ring-accent-green md:hidden">
                    <span class="sr-only">Open sidebar</span>
                    <i class="fas fa-bars"></i>
                </button>
                <div class="flex items-center space-x-4 flex-1">
                    <img src="./images/logo.png" alt="Logo" class="w-20 rounded-full hidden md:block">
                </div>
                <div class="flex items-center space-x-6">
                    <div class="text-right">
                        <p class="text-md font-semibold text-primary-blue">Bienvenue,
                            <?php echo htmlspecialchars($_SESSION['nom_utilisateur']) ?></p>
                        <p class="text-xs text-accent-green"><?php echo htmlspecialchars($_SESSION['lib_GU']) ?></p>
                    </div>
                    <div
                        class="flex items-center justify-center w-12 h-12 rounded-full bg-primary-blue text-white font-bold text-lg shadow-md">
                        <i class="fas fa-user"></i>
                    </div>
                </div>
            </div>
            <!-- Main content area -->
            <main class="flex-1 relative overflow-y-auto focus:outline-none p-6 bg-soft-gray">
                <?php
                if (!empty($contentFile) && file_exists($contentFile)) {
                    include $contentFile;
                } else {
                    echo "<div class='bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative' role='alert'>";
                    echo "<strong class='font-bold'>Erreur de contenu !</strong>";
                    if (empty($contentFile)) {
                        echo "<span class='block sm:inline'> Aucun fichier de contenu n'a été spécifié pour cette vue.</span>";
                    } else {
                        echo "<span class='block sm:inline'> Le fichier de contenu pour '" . htmlspecialchars($currentPageLabel) . "' n'a pas été trouvé.</span>";
                        echo "<p class='text-sm'>Chemin vérifié : " . htmlspecialchars($contentFile) . "</p>";
                    }
                    echo "</div>";
                }
                ?>
            </main>
        </div>
    </div>
</body>

</html>
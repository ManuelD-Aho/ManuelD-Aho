<?php
// Utilisation des variables globales au lieu d'appeler directement la base de données
$etudiantsNonInscrits = isset($GLOBALS['etudiantsNonInscrits']) ? $GLOBALS['etudiantsNonInscrits'] : [];
$niveaux = isset($GLOBALS['niveaux']) ? $GLOBALS['niveaux'] : [];
$etudiantsInscrits = isset($GLOBALS['etudiantsInscrits']) ? $GLOBALS['etudiantsInscrits'] : [];
$listeAnnees = isset($GLOBALS['listeAnnees']) ? $GLOBALS['listeAnnees'] : [];
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription des étudiants | Université</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'custom-primary': '#3457cb',
                        'custom-primary-dark': '#24407a',
                        'custom-success-dark': '#36865a',
                        'custom-success-light': '#59bf3d',
                    },
                    animation: {
                        'fade-in-down': 'fadeInDown 0.8s ease-out forwards',
                        'slide-in-right': 'slideInRight 0.8s ease-out forwards',
                        'scale-in': 'scaleIn 0.5s ease-out forwards',
                        'fade-in-up': 'fadeInUp 0.6s ease-out forwards',
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

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .card {
            background: white;
            border-radius: 20px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 8px 32px rgba(15, 23, 42, 0.08);
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 40px rgba(15, 23, 42, 0.12);
        }

        .header-gradient {
            background: linear-gradient(135deg, #24407a 0%, #3457cb 100%);
        }

        .section-card {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 24px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .section-card::before {
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

        .section-card:hover::before {
            opacity: 1;
        }

        .form-input {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 12px 16px;
            transition: all 0.3s ease;
            background: white;
        }

        .form-input:focus {
            border-color: #3457cb;
            box-shadow: 0 0 0 3px rgba(52, 87, 203, 0.1);
            outline: none;
        }

        .form-input:read-only {
            background-color: #f8fafc;
            border-color: #cbd5e1;
        }

        .btn-primary {
            background: linear-gradient(135deg, #3457cb 0%, #24407a 100%);
            border: none;
            border-radius: 12px;
            padding: 12px 24px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(52, 87, 203, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(52, 87, 203, 0.4);
        }

        .btn-success {
            background: linear-gradient(135deg, #36865a 0%, #59bf3d 100%);
            border: none;
            border-radius: 12px;
            padding: 12px 24px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(54, 134, 90, 0.3);
        }

        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(54, 134, 90, 0.4);
        }

        .btn-danger {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            border: none;
            border-radius: 12px;
            padding: 12px 24px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        }

        .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(239, 68, 68, 0.4);
        }

        .initial-hidden {
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInUp 0.6s ease-out forwards;
        }

        .table-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(15, 23, 42, 0.08);
            border: 1px solid #e2e8f0;
            overflow: hidden;
        }

        .modal-backdrop {
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(8px);
        }

        .modal-content {
            background: white;
            border-radius: 24px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 20px 60px rgba(15, 23, 42, 0.3);
            transform: scale(0.95);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .modal-content.show {
            transform: scale(1);
        }
    </style>
</head>

<body class="bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Header Section -->
        <div class="bg-white rounded-3xl shadow-xl border border-gray-100 mb-8 relative overflow-hidden animate-fade-in-down">
            <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-custom-primary to-custom-success-light"></div>
            <div class="p-8 lg:p-12">
                <div class="flex items-center gap-6 md:gap-8 flex-col md:flex-row text-center md:text-left">
                    <div class="bg-gradient-to-br from-custom-success-dark to-custom-success-light text-white w-20 h-20 md:w-24 md:h-24 rounded-2xl flex items-center justify-center text-4xl md:text-5xl shadow-lg transform transition-transform duration-300 hover:scale-110">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <div>
                        <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 mb-2 tracking-tight">Inscription des Étudiants</h1>
                        <p class="text-lg text-gray-600 font-normal">Processus d'inscription et suivi des étudiants</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Messages de notification -->
        <?php if (isset($GLOBALS['messageSuccess']) && !empty($GLOBALS['messageSuccess'])): ?>
        <div id="successMessage" class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-xl shadow-lg mb-6 initial-hidden animate-fade-in-down">
            <div class="flex items-center">
                <i class="fas fa-check-circle text-green-500 text-xl mr-3"></i>
                <span><?php echo $GLOBALS['messageSuccess']; ?></span>
            </div>
        </div>
        <?php unset($GLOBALS['messageSuccess']); ?>
        <?php endif; ?>

        <?php if (isset($GLOBALS['messageErreur']) && !empty($GLOBALS['messageErreur'])): ?>
        <div id="errorMessage" class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-xl shadow-lg mb-6 initial-hidden animate-fade-in-down">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle text-red-500 text-xl mr-3"></i>
                <span><?php echo $GLOBALS['messageErreur']; ?></span>
            </div>
        </div>
        <?php unset($GLOBALS['messageErreur']); ?>
        <?php endif; ?>

        <!-- Formulaire d'inscription -->
        <div class="card mb-8 initial-hidden" style="animation-delay: 0.1s">
            <div class="header-gradient px-8 py-6">
                <h2 class="text-2xl font-bold text-white flex items-center">
                    <i class="fas fa-user-plus mr-3"></i>Nouvelle inscription
                </h2>
            </div>
            <div class="p-8">
                <form id="inscriptionForm" method="POST" action="?page=gestion_etudiants&action=inscrire_des_etudiants" class="space-y-8">
                    <input type="hidden" name="modalAction" value="<?php echo isset($GLOBALS['inscriptionAModifier']) ? 'modifier' : 'inscrire'; ?>">
                    <?php if (isset($GLOBALS['inscriptionAModifier'])): ?>
                    <input type="hidden" name="id_inscription" value="<?php echo $GLOBALS['inscriptionAModifier']['id_inscription']; ?>">
                    <?php endif; ?>

                    <!-- Section Année académique -->
                    <div class="section-card">
                        <h3 class="text-lg font-semibold text-custom-primary mb-6 flex items-center">
                            <i class="fas fa-calendar-alt mr-3 text-custom-success-dark"></i>Année académique
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="annee_academique" class="block text-sm font-medium text-gray-700 mb-2">Sélectionner l'année académique *</label>
                                <select class="form-input w-full" id="annee_academique" name="annee_academique" required>
                                    <option value="">Choisir une année académique...</option>
                                    <?php foreach ($listeAnnees as $annee): ?>
                                    <option value="<?php echo $annee->id_annee_acad; ?>" <?php echo (isset($GLOBALS['inscriptionAModifier']) && $GLOBALS['inscriptionAModifier']['id_annee_acad'] == $annee->id_annee_acad) ? 'selected' : ''; ?>>
                                        <?php echo date('Y', strtotime($annee->date_deb)) . ' - ' . date('Y', strtotime($annee->date_fin)); ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Section Informations étudiant -->
                    <div class="section-card">
                        <h3 class="text-lg font-semibold text-custom-primary mb-6 flex items-center">
                            <i class="fas fa-user-graduate mr-3 text-custom-success-dark"></i>Informations étudiant
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="etudiant" class="block text-sm font-medium text-gray-700 mb-2">Sélectionner un étudiant *</label>
                                <select class="form-input w-full" id="etudiant" name="etudiant" <?php echo (isset($GLOBALS['inscriptionAModifier']) && isset($_GET['id'])) ? '' : 'required'; ?>>
                                    <option value="">Choisir un étudiant...</option>
                                    <?php foreach ($etudiantsNonInscrits as $etudiant): ?>
                                    <option value="<?php echo $etudiant['num_etu']; ?>" <?php echo (isset($GLOBALS['inscriptionAModifier']) && $GLOBALS['inscriptionAModifier']['id_etudiant'] == $etudiant['num_etu']) ? 'selected' : ''; ?>>
                                        <?php echo $etudiant['num_etu'] . ' - ' . $etudiant['nom_etu'] . ' ' . $etudiant['prenom_etu']; ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div>
                                <label for="niveau" class="block text-sm font-medium text-gray-700 mb-2">Niveau d'études *</label>
                                <select class="form-input w-full" id="niveau" name="niveau" required>
                                    <option value="">Choisir un niveau...</option>
                                    <?php foreach ($niveaux as $niveau): ?>
                                    <option value="<?php echo $niveau['id_niv_etude']; ?>" data-montant-total="<?php echo $niveau['montant_scolarite']; ?>" data-montant-inscription="<?php echo $niveau['montant_inscription']; ?>" <?php echo (isset($GLOBALS['inscriptionAModifier']) && $GLOBALS['inscriptionAModifier']['id_niveau'] == $niveau['id_niv_etude']) ? 'selected' : ''; ?>>
                                        <?php echo $niveau['lib_niv_etude']; ?> - <?php echo number_format($niveau['montant_scolarite'], 2); ?> FCFA
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Section Détails étudiant -->
                    <div class="section-card">
                        <h3 class="text-lg font-semibold text-custom-primary mb-6 flex items-center">
                            <i class="fas fa-id-card mr-3 text-custom-success-dark"></i>Détails étudiant
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Numéro étudiant</label>
                                <input type="text" required class="form-input w-full" id="num_etu" name="num_etu" value="<?php echo isset($GLOBALS['etudiantInfo']['num_etu']) ? htmlspecialchars($GLOBALS['etudiantInfo']['num_etu']) : ''; ?>" readonly>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nom</label>
                                <input type="text" class="form-input w-full" id="nom_etu" name="nom_etu" required value="<?php echo isset($GLOBALS['etudiantInfo']['nom_etu']) ? htmlspecialchars($GLOBALS['etudiantInfo']['nom_etu']) : ''; ?>" readonly>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Prénom</label>
                                <input type="text" class="form-input w-full" id="prenom_etu" name="prenom_etu" required value="<?php echo isset($GLOBALS['etudiantInfo']['prenom_etu']) ? htmlspecialchars($GLOBALS['etudiantInfo']['prenom_etu']) : ''; ?>" readonly>
                            </div>
                        </div>
                    </div>

                    <!-- Section Paiement -->
                    <div class="section-card">
                        <h3 class="text-lg font-semibold text-custom-primary mb-6 flex items-center">
                            <i class="fas fa-money-bill-wave mr-3 text-custom-success-dark"></i>Détails du paiement
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Montant total de la scolarité</label>
                                    <input type="text" class="form-input w-full" id="montant_total" readonly>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Premier versement *</label>
                                    <input type="number" class="form-input w-full" id="premier_versement" name="premier_versement" required value="<?php echo isset($GLOBALS['inscriptionAModifier']) ? $GLOBALS['inscriptionAModifier']['montant_premier_versement'] : ''; ?>">
                                </div>
                            </div>
                            <div class="space-y-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Reste à payer</label>
                                    <input type="text" class="form-input w-full" id="reste_payer" name="reste_payer" readonly>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Nombre de tranches</label>
                                    <select class="form-input w-full" id="nombre_tranches" name="nombre_tranches">
                                        <option value="1" <?php echo (isset($GLOBALS['inscriptionAModifier']) && $GLOBALS['inscriptionAModifier']['nombre_tranche'] == 1) ? 'selected' : ''; ?>>1 tranche</option>
                                        <option value="2" <?php echo (isset($GLOBALS['inscriptionAModifier']) && $GLOBALS['inscriptionAModifier']['nombre_tranche'] == 2) ? 'selected' : ''; ?>>2 tranches</option>
                                        <option value="3" <?php echo (isset($GLOBALS['inscriptionAModifier']) && $GLOBALS['inscriptionAModifier']['nombre_tranche'] == 3) ? 'selected' : ''; ?>>3 tranches</option>
                                        <option value="4" <?php echo (isset($GLOBALS['inscriptionAModifier']) && $GLOBALS['inscriptionAModifier']['nombre_tranche'] == 4) ? 'selected' : ''; ?>>4 tranches</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Méthode de paiement *</label>
                                    <select class="form-input w-full" id="methode_paiement" name="methode_paiement" required>
                                        <option value="">Sélectionner une méthode de paiement</option>
                                        <option value="Espèce" <?php echo (isset($GLOBALS['inscriptionAModifier']) && isset($GLOBALS['inscriptionAModifier']['methode_paiement']) && $GLOBALS['inscriptionAModifier']['methode_paiement'] == 'Espèce') ? 'selected' : ''; ?>>Espèce</option>
                                        <option value="Carte bancaire" <?php echo (isset($GLOBALS['inscriptionAModifier']) && isset($GLOBALS['inscriptionAModifier']['methode_paiement']) && $GLOBALS['inscriptionAModifier']['methode_paiement'] == 'Carte bancaire') ? 'selected' : ''; ?>>Carte bancaire</option>
                                        <option value="Virement" <?php echo (isset($GLOBALS['inscriptionAModifier']) && isset($GLOBALS['inscriptionAModifier']['methode_paiement']) && $GLOBALS['inscriptionAModifier']['methode_paiement'] == 'Virement') ? 'selected' : ''; ?>>Virement</option>
                                        <option value="Chèque" <?php echo (isset($GLOBALS['inscriptionAModifier']) && isset($GLOBALS['inscriptionAModifier']['methode_paiement']) && $GLOBALS['inscriptionAModifier']['methode_paiement'] == 'Chèque') ? 'selected' : ''; ?>>Chèque</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Boutons d'action -->
                    <div class="flex justify-between items-center pt-6 border-t border-gray-200">
                        <?php if(isset($GLOBALS['inscriptionAModifier'])) : ?>
                        <button type="button" name="btn_annuler_insciption" id="btnAnnuler" onclick="window.location.href='?page=gestion_etudiants&action=inscrire_des_etudiants'" class="btn-danger text-white flex items-center">
                            <i class="fas fa-times mr-2"></i>Annuler
                        </button>
                        <button type="submit" name="btn_modifier_insciption" id="edit_inscription" class="btn-primary text-white flex items-center">
                            <i class="fas fa-save mr-2"></i>Modifier l'inscription
                        </button>
                        <?php else : ?>
                        <div></div>
                        <button type="submit" name="btn_add_insciption" id="add_inscription" class="btn-success text-white flex items-center">
                            <i class="fas fa-save mr-2"></i>Enregistrer l'inscription
                        </button>
                        <?php endif ?>
                    </div>
                </form>
            </div>
        </div>

        <!-- Liste des étudiants inscrits -->
        <div class="table-container initial-hidden" style="animation-delay: 0.2s">
            <div class="header-gradient px-8 py-6">
                <h2 class="text-2xl font-bold text-white flex items-center">
                    <i class="fas fa-users mr-3"></i>Étudiants inscrits
                </h2>
            </div>
            <div class="p-8">
                <!-- Barre d'outils -->
                <div class="mb-6 flex flex-col sm:flex-row gap-4 items-center justify-between">
                    <div class="relative w-full sm:w-96">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <input type="text" id="searchInput" class="form-input w-full pl-10" placeholder="Rechercher un étudiant...">
                    </div>

                    <div class="flex flex-wrap gap-3">
                        <button onclick="exportToExcel()" class="btn-success text-white flex items-center">
                            <i class="fas fa-file-excel mr-2"></i>Excel
                        </button>
                        <button onclick="printTable()" class="btn-primary text-white flex items-center">
                            <i class="fas fa-print mr-2"></i>Imprimer
                        </button>
                    </div>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto rounded-xl border border-gray-200">
                    <table id="inscriptionsTable" class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Numéro</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Niveau</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Année académique</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date d'inscription</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                                <th class="px-6 py-4 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($etudiantsInscrits as $inscrit): ?>
                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo $inscrit['id_etudiant']; ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo $inscrit['nom'] . ' ' . $inscrit['prenom']; ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo $inscrit['nom_niveau']; ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo date('Y', strtotime($inscrit['date_deb'])) . ' - ' . date('Y', strtotime($inscrit['date_fin'])); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo date('d/m/Y', strtotime($inscrit['date_inscription'])); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo $inscrit['statut_inscription'] === 'Validée' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'; ?>">
                                        <?php echo $inscrit['statut_inscription']; ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                    <div class="flex items-center justify-center space-x-2">
                                        <button onclick="modifierInscription(<?php echo $inscrit['id_inscription']; ?>)" class="text-custom-primary hover:text-custom-primary-dark p-2 hover:bg-custom-primary/10 rounded-lg transition-all duration-200">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button onclick="supprimerInscription(<?php echo $inscrit['id_inscription']; ?>)" class="text-red-500 hover:text-red-700 p-2 hover:bg-red-50 rounded-lg transition-all duration-200">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                        <button onclick="imprimerRecu(<?php echo $inscrit['id_inscription']; ?>)" class="text-custom-success-dark hover:text-custom-success-light p-2 hover:bg-custom-success-dark/10 rounded-lg transition-all duration-200">
                                            <i class="fas fa-print"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="flex justify-center items-center space-x-2 mt-6">
                    <button id="prevPage" class="px-3 py-2 rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200 transition-colors duration-200">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <div id="pageNumbers" class="flex space-x-2"></div>
                    <button id="nextPage" class="px-3 py-2 rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200 transition-colors duration-200">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de confirmation de suppression -->
    <div id="deleteModal" class="fixed inset-0 modal-backdrop hidden items-center justify-center z-50">
        <div class="modal-content max-w-md w-full mx-4 p-8">
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4">
                    <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-4">Confirmer la suppression</h3>
                <p class="text-gray-600 mb-6">Êtes-vous sûr de vouloir supprimer cette inscription ? Cette action est irréversible.</p>
                <div class="flex justify-center space-x-4">
                    <button id="cancelDelete" class="px-6 py-3 bg-gray-300 text-gray-700 rounded-xl hover:bg-gray-400 font-medium transition-all duration-200">
                        Annuler
                    </button>
                    <button id="confirmDelete" class="btn-danger text-white">
                        <i class="fas fa-trash mr-2"></i>Supprimer
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
    // [Le JavaScript reste largement identique mais avec quelques améliorations d'UX]
    // Variables globales pour la pagination
    const itemsPerPage = 10;
    let currentPage = 1;
    let filteredData = [];
    let allData = [];

    // Variables pour la modale de suppression
    let inscriptionToDelete = null;
    const deleteModal = document.getElementById('deleteModal');
    const cancelDelete = document.getElementById('cancelDelete');
    const confirmDelete = document.getElementById('confirmDelete');

    // Fonction d'export Excel
    window.exportToExcel = function() {
        const table = document.getElementById('inscriptionsTable');
        const searchInput = document.getElementById('searchInput');
        const dataToExport = searchInput.value.trim() === '' ? allData : filteredData;

        let csvContent = "data:text/csv;charset=utf-8,";

        // En-têtes
        const headers = Array.from(table.querySelectorAll('thead th'))
            .slice(0, -1)
            .map(th => th.textContent.trim());
        csvContent += headers.join(",") + "\r\n";

        // Données
        dataToExport.forEach(item => {
            const rowData = item.data
                .slice(0, -1)
                .map(cell => `"${cell}"`)
                .join(",");
            csvContent += rowData + "\r\n";
        });

        const encodedUri = encodeURI(csvContent);
        const link = document.createElement("a");
        link.setAttribute("href", encodedUri);
        link.setAttribute("download", "inscriptions.csv");
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    };

    // Fonction d'impression
    window.printTable = function() {
        const printWindow = window.open('', '_blank');
        const table = document.getElementById('inscriptionsTable');
        const searchInput = document.getElementById('searchInput');
        const dataToPrint = searchInput.value.trim() === '' ? allData : filteredData;

        const tableClone = table.cloneNode(true);
        const headers = tableClone.querySelectorAll('thead th');
        const lastHeader = headers[headers.length - 1];
        lastHeader.parentNode.removeChild(lastHeader);

        const tbody = tableClone.querySelector('tbody');
        tbody.innerHTML = '';

        dataToPrint.forEach(item => {
            const newRow = document.createElement('tr');
            item.data.slice(0, -1).forEach(cellData => {
                const cell = document.createElement('td');
                cell.textContent = cellData;
                cell.style.padding = '8px';
                cell.style.border = '1px solid #ddd';
                newRow.appendChild(cell);
            });
            tbody.appendChild(newRow);
        });

        printWindow.document.write(`
            <html>
                <head>
                    <title>Liste des inscriptions</title>
                    <style>
                        body { font-family: 'Inter', sans-serif; margin: 20px; }
                        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
                        th { background-color: #f8fafc; font-weight: 600; }
                        h2 { color: #1f2937; margin-bottom: 20px; }
                    </style>
                </head>
                <body>
                    <h2>Liste des inscriptions</h2>
                    ${tableClone.outerHTML}
                </body>
            </html>
        `);
        printWindow.document.close();
        printWindow.print();
        printWindow.focus();
        printWindow.close();
    };

    document.addEventListener('DOMContentLoaded', function() {
        const etudiantSelect = document.getElementById('etudiant');
        const niveauSelect = document.getElementById('niveau');
        const premierVersementInput = document.getElementById('premier_versement');
        const montantTotalInput = document.getElementById('montant_total');
        const restePayerInput = document.getElementById('reste_payer');

        // Gestion de la sélection d'un étudiant
        etudiantSelect.addEventListener('change', function() {
            const numEtu = this.value;
            if (numEtu) {
                const selectedOption = this.options[this.selectedIndex];
                const etudiantInfo = selectedOption.textContent.split(' - ');
                const numEtu = etudiantInfo[0];
                const nomPrenom = etudiantInfo[1].split(' ');
                const nom = nomPrenom[0];
                const prenom = nomPrenom[1];

                document.getElementById('num_etu').value = numEtu;
                document.getElementById('nom_etu').value = nom;
                document.getElementById('prenom_etu').value = prenom;
            } else {
                document.getElementById('num_etu').value = '';
                document.getElementById('nom_etu').value = '';
                document.getElementById('prenom_etu').value = '';
            }
        });

        // Fonction pour formater les montants
        function formaterMontant(montant) {
            return new Intl.NumberFormat('fr-FR').format(montant);
        }

        // Fonction pour calculer le reste à payer
        function calculerResteAPayer() {
            const montantTotal = parseFloat(montantTotalInput.value.replace(/\s/g, '')) || 0;
            const premierVersement = parseFloat(premierVersementInput.value) || 0;
            const reste = montantTotal - premierVersement;
            restePayerInput.value = formaterMontant(reste);
        }

        // Événement lors du changement de niveau
        niveauSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const montantTotal = selectedOption.getAttribute('data-montant-total');
            const montantInscription = selectedOption.getAttribute('data-montant-inscription');

            montantTotalInput.value = formaterMontant(montantTotal);
            premierVersementInput.value = montantInscription;
            calculerResteAPayer();
        });

        // Événements pour le calcul automatique
        premierVersementInput.addEventListener('input', calculerResteAPayer);

        // Initialisation des calculs
        if (niveauSelect.value) {
            const event = new Event('change');
            niveauSelect.dispatchEvent(event);
        }

        // Validation du formulaire
        document.getElementById('inscriptionForm').addEventListener('submit', function(e) {
            const montantTotal = parseFloat(niveauSelect.options[niveauSelect.selectedIndex].dataset.montantTotal);
            const premierVersement = parseFloat(premierVersementInput.value);

            if (premierVersement > montantTotal) {
                e.preventDefault();
                alert("Le premier versement ne peut pas être supérieur au montant total.");
                return;
            }
        });

        // Fonction pour initialiser les données de la table
        function initializeData() {
            const table = document.getElementById('inscriptionsTable');
            const rows = Array.from(table.querySelectorAll('tbody tr'));
            allData = rows.map(row => ({
                element: row,
                data: Array.from(row.cells).map(cell => cell.textContent.trim())
            }));
            filteredData = [...allData];
            updateTable();
        }

        // Fonction pour mettre à jour la table
        function updateTable() {
            const start = (currentPage - 1) * itemsPerPage;
            const end = start + itemsPerPage;
            const paginatedData = filteredData.slice(start, end);

            allData.forEach(item => item.element.style.display = 'none');
            paginatedData.forEach(item => item.element.style.display = '');

            updatePagination();
        }

        // Fonction pour mettre à jour la pagination
        function updatePagination() {
            const totalPages = Math.ceil(filteredData.length / itemsPerPage);
            const pageNumbers = document.getElementById('pageNumbers');
            pageNumbers.innerHTML = '';

            for (let i = 1; i <= totalPages; i++) {
                const button = document.createElement('button');
                button.textContent = i;
                button.className = `px-3 py-2 rounded-lg transition-colors duration-200 ${i === currentPage ? 'bg-custom-primary text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'}`;
                button.onclick = () => {
                    currentPage = i;
                    updateTable();
                };
                pageNumbers.appendChild(button);
            }

            document.getElementById('prevPage').disabled = currentPage === 1;
            document.getElementById('nextPage').disabled = currentPage === totalPages;
        }

        // Gestionnaires de pagination
        document.getElementById('prevPage').addEventListener('click', () => {
            if (currentPage > 1) {
                currentPage--;
                updateTable();
            }
        });

        document.getElementById('nextPage').addEventListener('click', () => {
            const totalPages = Math.ceil(filteredData.length / itemsPerPage);
            if (currentPage < totalPages) {
                currentPage++;
                updateTable();
            }
        });

        // Fonction de recherche
        document.getElementById('searchInput').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            filteredData = allData.filter(item =>
                item.data.some(cell => cell.toLowerCase().includes(searchTerm))
            );
            currentPage = 1;
            updateTable();
        });

        // Fonctions globales
        window.modifierInscription = function(idInscription) {
            window.location.href = `?page=gestion_etudiants&action=inscrire_des_etudiants&modalAction=modifier&id=${idInscription}`;
        };

        window.imprimerRecu = function(idInscription) {
            window.open(`?page=gestion_etudiants&action=inscrire_des_etudiants&modalAction=imprimer_recu&id_inscription=${idInscription}`, '_blank');
        };

        window.supprimerInscription = function(idInscription) {
            inscriptionToDelete = idInscription;
            deleteModal.classList.remove('hidden');
            deleteModal.classList.add('flex');
        };

        // Gestionnaires de modale
        cancelDelete.addEventListener('click', function() {
            deleteModal.classList.add('hidden');
            deleteModal.classList.remove('flex');
            inscriptionToDelete = null;
        });

        confirmDelete.addEventListener('click', function() {
            if (inscriptionToDelete) {
                window.location.href = `?page=gestion_etudiants&action=inscrire_des_etudiants&modalAction=supprimer&id=${inscriptionToDelete}`;
            }
        });

        deleteModal.addEventListener('click', function(e) {
            if (e.target === deleteModal) {
                deleteModal.classList.add('hidden');
                deleteModal.classList.remove('flex');
                inscriptionToDelete = null;
            }
        });

        // Gestion des messages
        const successMessage = document.getElementById('successMessage');
        const errorMessage = document.getElementById('errorMessage');

        function hideMessage(element) {
            if (element) {
                setTimeout(() => {
                    element.style.opacity = '0';
                    setTimeout(() => {
                        element.remove();
                    }, 300);
                }, 5000);
            }
        }

        hideMessage(successMessage);
        hideMessage(errorMessage);

        // Initialiser les données
        initializeData();

        // Animation des cartes au chargement
        const cards = document.querySelectorAll('.initial-hidden');
        cards.forEach((card, index) => {
            card.style.animationDelay = `${index * 0.1}s`;
        });
    });
    </script>
</body>

</html>
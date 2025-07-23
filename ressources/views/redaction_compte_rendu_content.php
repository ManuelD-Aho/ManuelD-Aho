<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rédaction de Compte-Rendu</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'custom-primary': '#4f46e5',
                        'custom-primary-dark': '#3730a3',
                        'gray-card-bg': '#f8fafc',
                        'gray-border': '#e2e8f0',
                        'gray-muted': '#64748b'
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4 py-6">
        <!-- En-tête -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="bg-custom-primary/10 p-3 rounded-full">
                        <i class="fas fa-file-alt text-custom-primary text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Rédaction de Compte-Rendu</h1>
                        <p class="text-gray-600 text-sm">Créez et gérez vos comptes-rendus de rapports</p>
                    </div>
                </div>
                <button onclick="chargerModele()" class="bg-custom-primary text-white px-4 py-2 rounded-lg hover:bg-custom-primary-dark transition-all duration-200 flex items-center gap-2">
                    <i class="fas fa-download"></i>
                    Charger Modèle
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <!-- Section Sélection des Rapports -->
            <div class="lg:col-span-4">
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex items-center gap-2 mb-4">
                        <i class="fas fa-list-check text-custom-primary"></i>
                        <h2 class="text-lg font-semibold text-gray-800">Rapports Concernés</h2>
                    </div>

                    <!-- Filtres -->
                    <div class="mb-4 space-y-2">
                        <select class="w-full px-3 py-2 border border-gray-border rounded-lg text-sm" onchange="filtrerRapports(this.value)">
                            <option value="tous">Tous les rapports</option>
                            <option value="valide">Validés uniquement</option>
                            <option value="rejete">Rejetés uniquement</option>
                        </select>
                        <input type="text" placeholder="Rechercher un rapport..." class="w-full px-3 py-2 border border-gray-border rounded-lg text-sm" oninput="rechercherRapports(this.value)">
                    </div>

                    <!-- Liste des rapports -->
                    <div class="space-y-2 max-h-96 overflow-y-auto" id="listeRapports">
                        <!-- Rapport 1 -->
                        <div class="border border-gray-200 rounded-lg p-3 hover:bg-gray-50 transition-colors rapport-item" data-statut="valide">
                            <div class="flex items-start gap-3">
                                <input type="checkbox" class="mt-1 h-4 w-4 text-custom-primary focus:ring-custom-primary border-gray-300 rounded" onchange="toggleRapport(this)" data-rapport-id="1">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full font-medium">Validé</span>
                                        <span class="text-xs text-gray-500">#RPT-2024-001</span>
                                    </div>
                                    <h4 class="text-sm font-medium text-gray-900 truncate">Rapport d'activité mensuel - Janvier 2024</h4>
                                    <p class="text-xs text-gray-600 mt-1">Soumis par: Jean Dupont</p>
                                    <p class="text-xs text-gray-500">Date: 15/01/2024</p>
                                </div>
                            </div>
                        </div>

                        <!-- Rapport 2 -->
                        <div class="border border-gray-200 rounded-lg p-3 hover:bg-gray-50 transition-colors rapport-item" data-statut="rejete">
                            <div class="flex items-start gap-3">
                                <input type="checkbox" class="mt-1 h-4 w-4 text-custom-primary focus:ring-custom-primary border-gray-300 rounded" onchange="toggleRapport(this)" data-rapport-id="2">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="bg-red-100 text-red-800 text-xs px-2 py-1 rounded-full font-medium">Rejeté</span>
                                        <span class="text-xs text-gray-500">#RPT-2024-002</span>
                                    </div>
                                    <h4 class="text-sm font-medium text-gray-900 truncate">Rapport financier Q1</h4>
                                    <p class="text-xs text-gray-600 mt-1">Soumis par: Marie Martin</p>
                                    <p class="text-xs text-gray-500">Date: 28/01/2024</p>
                                </div>
                            </div>
                        </div>

                        <!-- Rapport 3 -->
                        <div class="border border-gray-200 rounded-lg p-3 hover:bg-gray-50 transition-colors rapport-item" data-statut="valide">
                            <div class="flex items-start gap-3">
                                <input type="checkbox" class="mt-1 h-4 w-4 text-custom-primary focus:ring-custom-primary border-gray-300 rounded" onchange="toggleRapport(this)" data-rapport-id="3">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full font-medium">Validé</span>
                                        <span class="text-xs text-gray-500">#RPT-2024-003</span>
                                    </div>
                                    <h4 class="text-sm font-medium text-gray-900 truncate">Analyse des performances équipe A</h4>
                                    <p class="text-xs text-gray-600 mt-1">Soumis par: Pierre Leblanc</p>
                                    <p class="text-xs text-gray-500">Date: 05/02/2024</p>
                                </div>
                            </div>
                        </div>

                        <!-- Rapport 4 -->
                        <div class="border border-gray-200 rounded-lg p-3 hover:bg-gray-50 transition-colors rapport-item" data-statut="rejete">
                            <div class="flex items-start gap-3">
                                <input type="checkbox" class="mt-1 h-4 w-4 text-custom-primary focus:ring-custom-primary border-gray-300 rounded" onchange="toggleRapport(this)" data-rapport-id="4">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="bg-red-100 text-red-800 text-xs px-2 py-1 rounded-full font-medium">Rejeté</span>
                                        <span class="text-xs text-gray-500">#RPT-2024-004</span>
                                    </div>
                                    <h4 class="text-sm font-medium text-gray-900 truncate">Rapport de projet XYZ</h4>
                                    <p class="text-xs text-gray-600 mt-1">Soumis par: Sophie Moreau</p>
                                    <p class="text-xs text-gray-500">Date: 12/02/2024</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Compteur de sélection -->
                    <div class="mt-4 p-3 bg-gray-50 rounded-lg">
                        <p class="text-sm text-gray-600">
                            <span class="font-medium" id="compteurSelection">0</span> rapport(s) sélectionné(s)
                        </p>
                    </div>
                </div>
            </div>

            <!-- Section Rédaction -->
            <div class="lg:col-span-8">
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-edit text-custom-primary"></i>
                            <h2 class="text-lg font-semibold text-gray-800">Rédaction du Compte-Rendu</h2>
                        </div>
                        <div class="flex gap-2">
                            <button onclick="sauvegarderBrouillon()" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition-all duration-200 flex items-center gap-2 text-sm">
                                <i class="fas fa-save"></i>
                                Brouillon
                            </button>
                            <button onclick="previsualiser()" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition-all duration-200 flex items-center gap-2 text-sm">
                                <i class="fas fa-eye"></i>
                                Aperçu
                            </button>
                        </div>
                    </div>

                    <form id="formCompteRendu" class="space-y-6">
                        <!-- Informations générales -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Titre du compte-rendu</label>
                                <input type="text" id="titreCompteRendu" class="w-full px-3 py-2 border border-gray-border rounded-lg focus:ring-2 focus:ring-custom-primary focus:border-custom-primary" placeholder="Titre du compte-rendu">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Date</label>
                                <input type="date" id="dateCompteRendu" class="w-full px-3 py-2 border border-gray-border rounded-lg focus:ring-2 focus:ring-custom-primary focus:border-custom-primary">
                            </div>
                        </div>

                        <!-- Rapports sélectionnés -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Rapports concernés</label>
                            <div id="rapportsSelectionnes" class="bg-gray-50 border border-gray-200 rounded-lg p-3 min-h-[60px]">
                                <p class="text-gray-500 text-sm italic">Aucun rapport sélectionné</p>
                            </div>
                        </div>

                        <!-- Contenu du compte-rendu -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Contenu du compte-rendu</label>
                            <textarea id="contenuCompteRendu" rows="15" class="w-full px-3 py-2 border border-gray-border rounded-lg focus:ring-2 focus:ring-custom-primary focus:border-custom-primary resize-none" placeholder="Rédigez votre compte-rendu ici..."></textarea>
                        </div>

                        <!-- Actions -->
                        <div class="flex justify-end gap-4 pt-4 border-t border-gray-200">
                            <button type="button" class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition-all duration-200">
                                <i class="fas fa-times mr-2"></i>Annuler
                            </button>
                            <button type="submit" class="bg-custom-primary text-white px-6 py-2 rounded-lg hover:bg-custom-primary-dark transition-all duration-200">
                                <i class="fas fa-check mr-2"></i>Finaliser le Compte-Rendu
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de prévisualisation -->
    <div id="modalPreview" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full mx-4 max-h-[90vh] overflow-hidden">
            <div class="bg-custom-primary text-white p-4 flex items-center justify-between">
                <h3 class="text-lg font-semibold">Aperçu du Compte-Rendu</h3>
                <button onclick="fermerPreview()" class="text-white hover:text-gray-200">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="p-6 overflow-y-auto max-h-[calc(90vh-80px)]" id="contenuPreview">
                <!-- Contenu généré dynamiquement -->
            </div>
            <div class="bg-gray-50 p-4 flex justify-end gap-4">
                <button onclick="fermerPreview()" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">Fermer</button>
                <button onclick="exporterPDF()" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">
                    <i class="fas fa-file-pdf mr-2"></i>Exporter PDF
                </button>
            </div>
        </div>
    </div>

    <script>
        // Variables globales
        let rapportsSelectionnes = [];
        const modeleCompteRendu = `COMPTE-RENDU DE VALIDATION/REJET DE RAPPORTS

Date: {DATE}
Titre: {TITRE}

RAPPORTS CONCERNÉS:
{RAPPORTS_LISTE}

ANALYSE ET OBSERVATIONS:

1. SYNTHÈSE GÉNÉRALE
   - Nombre total de rapports examinés: {NOMBRE_RAPPORTS}
   - Rapports validés: {NOMBRE_VALIDES}
   - Rapports rejetés: {NOMBRE_REJETES}

2. POINTS SAILLANTS
   [À compléter selon l'analyse des rapports]

3. RECOMMANDATIONS
   [À compléter avec les recommandations spécifiques]

4. DÉCISIONS PRISES
   [À compléter avec les décisions actées]

5. ACTIONS DE SUIVI
   [À compléter avec les actions à entreprendre]

CONCLUSION:
[À compléter avec la conclusion générale]

Établi par: [Nom du rédacteur]
Fonction: [Fonction]
Date de rédaction: {DATE}`;

        // Initialisation
        document.addEventListener('DOMContentLoaded', function() {
            // Définir la date du jour
            document.getElementById('dateCompteRendu').value = new Date().toISOString().split('T')[0];
        });

        // Fonction pour charger le modèle
        function chargerModele() {
            const titre = document.getElementById('titreCompteRendu').value || 'Compte-rendu de validation de rapports';
            const date = document.getElementById('dateCompteRendu').value || new Date().toISOString().split('T')[0];

            let rapportsListe = '';
            let nombreValides = 0;
            let nombreRejetes = 0;

            rapportsSelectionnes.forEach(rapport => {
                rapportsListe += `- ${rapport.titre} (#${rapport.reference}) - ${rapport.statut}\n`;
                if (rapport.statut === 'Validé') nombreValides++;
                else nombreRejetes++;
            });

            if (rapportsListe === '') {
                rapportsListe = '[Aucun rapport sélectionné]';
            }

            const modelePersonnalise = modeleCompteRendu
                .replace(/{DATE}/g, date)
                .replace(/{TITRE}/g, titre)
                .replace(/{RAPPORTS_LISTE}/g, rapportsListe)
                .replace(/{NOMBRE_RAPPORTS}/g, rapportsSelectionnes.length)
                .replace(/{NOMBRE_VALIDES}/g, nombreValides)
                .replace(/{NOMBRE_REJETES}/g, nombreRejetes);

            document.getElementById('contenuCompteRendu').value = modelePersonnalise;

            // Animation de feedback
            const btn = event.target;
            btn.innerHTML = '<i class="fas fa-check mr-2"></i>Modèle Chargé';
            btn.classList.add('bg-green-600');
            setTimeout(() => {
                btn.innerHTML = '<i class="fas fa-download mr-2"></i>Charger Modèle';
                btn.classList.remove('bg-green-600');
            }, 2000);
        }

        // Fonction pour toggle un rapport
        function toggleRapport(checkbox) {
            const rapportId = checkbox.dataset.rapportId;
            const rapportDiv = checkbox.closest('.rapport-item');
            const titre = rapportDiv.querySelector('h4').textContent;
            const reference = rapportDiv.querySelector('.text-xs.text-gray-500').textContent;
            const statut = rapportDiv.querySelector('.bg-green-100, .bg-red-100').textContent;

            if (checkbox.checked) {
                rapportsSelectionnes.push({
                    id: rapportId,
                    titre: titre,
                    reference: reference,
                    statut: statut
                });
                rapportDiv.classList.add('ring-2', 'ring-custom-primary', 'bg-blue-50');
            } else {
                rapportsSelectionnes = rapportsSelectionnes.filter(r => r.id !== rapportId);
                rapportDiv.classList.remove('ring-2', 'ring-custom-primary', 'bg-blue-50');
            }

            mettreAJourSelection();
        }

        // Mettre à jour l'affichage de la sélection
        function mettreAJourSelection() {
            document.getElementById('compteurSelection').textContent = rapportsSelectionnes.length;

            const container = document.getElementById('rapportsSelectionnes');
            if (rapportsSelectionnes.length === 0) {
                container.innerHTML = '<p class="text-gray-500 text-sm italic">Aucun rapport sélectionné</p>';
            } else {
                let html = '<div class="space-y-2">';
                rapportsSelectionnes.forEach(rapport => {
                    const couleur = rapport.statut === 'Validé' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
                    html += `
                        <div class="flex items-center gap-2 text-sm">
                            <span class="${couleur} px-2 py-1 rounded-full text-xs font-medium">${rapport.statut}</span>
                            <span class="text-gray-600">${rapport.reference}</span>
                            <span class="text-gray-900">${rapport.titre}</span>
                        </div>
                    `;
                });
                html += '</div>';
                container.innerHTML = html;
            }
        }

        // Filtrer les rapports
        function filtrerRapports(filtre) {
            const rapports = document.querySelectorAll('.rapport-item');
            rapports.forEach(rapport => {
                if (filtre === 'tous') {
                    rapport.style.display = 'block';
                } else {
                    const statut = rapport.dataset.statut;
                    rapport.style.display = statut === filtre ? 'block' : 'none';
                }
            });
        }

        // Rechercher des rapports
        function rechercherRapports(terme) {
            const rapports = document.querySelectorAll('.rapport-item');
            terme = terme.toLowerCase();

            rapports.forEach(rapport => {
                const titre = rapport.querySelector('h4').textContent.toLowerCase();
                const auteur = rapport.querySelector('.text-xs.text-gray-600').textContent.toLowerCase();
                const reference = rapport.querySelector('.text-xs.text-gray-500').textContent.toLowerCase();

                const match = titre.includes(terme) || auteur.includes(terme) || reference.includes(terme);
                rapport.style.display = match ? 'block' : 'none';
            });
        }

        // Prévisualiser le compte-rendu
        function previsualiser() {
            const titre = document.getElementById('titreCompteRendu').value || 'Sans titre';
            const date = document.getElementById('dateCompteRendu').value || 'Non spécifiée';
            const contenu = document.getElementById('contenuCompteRendu').value || 'Aucun contenu';

            const html = `
                <div class="space-y-6">
                    <div class="text-center border-b pb-4">
                        <h1 class="text-2xl font-bold text-gray-900">${titre}</h1>
                        <p class="text-gray-600 mt-2">Date: ${date}</p>
                    </div>
                    <div class="whitespace-pre-wrap text-gray-800 leading-relaxed">${contenu}</div>
                </div>
            `;

            document.getElementById('contenuPreview').innerHTML = html;
            document.getElementById('modalPreview').classList.remove('hidden');
        }

        // Fermer la prévisualisation
        function fermerPreview() {
            document.getElementById('modalPreview').classList.add('hidden');
        }

        // Sauvegarder en brouillon
        function sauvegarderBrouillon() {
            // Ici vous pourriez implémenter la sauvegarde
            alert('Brouillon sauvegardé avec succès !');
        }

        // Exporter en PDF
        function exporterPDF() {
            alert('Fonctionnalité d\'export PDF à implémenter avec une bibliothèque comme jsPDF');
        }

        // Gestionnaire de soumission du formulaire
        document.getElementById('formCompteRendu').addEventListener('submit', function(e) {
            e.preventDefault();

            if (rapportsSelectionnes.length === 0) {
                alert('Veuillez sélectionner au moins un rapport.');
                return;
            }

            if (!document.getElementById('titreCompteRendu').value.trim()) {
                alert('Veuillez saisir un titre pour le compte-rendu.');
                return;
            }

            if (!document.getElementById('contenuCompteRendu').value.trim()) {
                alert('Veuillez saisir le contenu du compte-rendu.');
                return;
            }

            // Ici vous pourriez implémenter la soumission du formulaire
            alert('Compte-rendu finalisé avec succès !');
        });
    </script>
</body>

</html>
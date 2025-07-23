<?php
// THIS IS EXAMPLE DATA to make the page render.
// YOUR PHP LOGIC for all variables is preserved.
$erreurs = $GLOBALS['erreurs'] ?? [];
$isEditMode = $GLOBALS['isEditMode'] ?? false;
$rapport = $GLOBALS['rapport'] ?? null;
$contenuRapport = $GLOBALS['contenuRapport'] ?? '';
$rapportDejaDepose = $GLOBALS['rapportDejaDepose'] ?? false;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Éditeur de Rapport</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.3/tinymce.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
        
        /* Custom styles for TinyMCE to match the theme */
        .tox-tinymce {
            border-radius: 0 0 0.5rem 0.5rem !important; /* rounded-b-lg */
            border: 1px solid #d1d5db !important; /* border-gray-300 */
            border-top: none !important;
        }
        .tox .tox-edit-area__iframe {
            background-color: #ffffff; /* bg-white */
        }
    </style>
</head>

<body class="bg-gray-100">
    <div class="container max-w-7xl mx-auto px-4 py-8">
        <div class="flex flex-col sm:flex-row justify-between sm:items-center mb-6 gap-4">
            <div>
                <a href="?page=gestion_rapports" class="text-sm text-gray-600 hover:text-blue-600 hover:underline mb-2 block">
                    <i class="fa-solid fa-arrow-left mr-2"></i>Retour à la gestion des rapports
                </a>
                <h1 class="text-3xl font-bold text-gray-900">
                    <?= $rapportDejaDepose ? "Consultation du Rapport" : "Éditeur de Rapport" ?>
                </h1>
                <p class="text-md text-gray-600">
                    <?= $rapportDejaDepose ? "Ce rapport est en lecture seule car il a déjà été déposé." : "Rédigez et mettez en forme votre rapport de stage." ?>
                </p>
            </div>
            <div class="flex items-center space-x-2">
                <?php if (!$rapportDejaDepose): ?>
                    <button id="saveBtn" class="bg-blue-600 text-white font-semibold px-4 py-2 rounded-md hover:bg-blue-700 transition-colors flex items-center">
                        <i class="fa-solid fa-save mr-2"></i>Enregistrer
                    </button>
                    <button id="deposerBtn" class="bg-yellow-500 text-white font-semibold px-4 py-2 rounded-md hover:bg-yellow-600 transition-colors flex items-center">
                        <i class="fa-solid fa-paper-plane mr-2"></i>Déposer
                    </button>
                <?php endif; ?>
                <button id="exportBtn" class="bg-green-600 text-white font-semibold px-4 py-2 rounded-md hover:bg-green-700 transition-colors flex items-center">
                    <i class="fa-solid fa-file-pdf mr-2"></i>Exporter PDF
                </button>
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
            <form id="rapportForm" method="POST">
                <input type="hidden" name="action" value="save_rapport">
                <?php if ($isEditMode && isset($rapport)): ?>
                    <input type="hidden" name="edit_id" value="<?= $rapport['id_rapport'] ?>">
                <?php endif; ?>

                <div class="p-6 border-b border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="nom_rapport" class="block text-sm font-medium text-gray-700 mb-1">Nom du rapport*</label>
                            <input type="text" id="nom_rapport" name="nom_rapport" value="<?= isset($rapport) ? htmlspecialchars($rapport['nom_rapport']) : '' ?>" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 <?= $rapportDejaDepose ? 'bg-gray-100' : '' ?>" <?= $rapportDejaDepose ? 'readonly' : 'required' ?>>
                        </div>
                        <div>
                            <label for="theme_rapport" class="block text-sm font-medium text-gray-700 mb-1">Thème du rapport*</label>
                            <input type="text" id="theme_rapport" name="theme_rapport" value="<?= isset($rapport) ? htmlspecialchars($rapport['theme_rapport']) : '' ?>" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 <?= $rapportDejaDepose ? 'bg-gray-100' : '' ?>" <?= $rapportDejaDepose ? 'readonly' : 'required' ?>>
                        </div>
                    </div>
                </div>
                
                <div class="p-3 bg-gray-50 border-b border-gray-200 flex items-center justify-between">
                    <button id="loadTemplateBtn" class="bg-gray-200 text-gray-800 font-semibold px-4 py-2 rounded-md hover:bg-gray-300 transition-colors flex items-center text-sm" <?= $rapportDejaDepose ? 'disabled' : '' ?>>
                        <i class="fa-solid fa-paste mr-2"></i>Charger un modèle
                    </button>
                    <span id="loadingIndicator" class="hidden text-sm text-gray-600 flex items-center"><i class="fa-solid fa-spinner fa-spin mr-2"></i>Chargement...</span>
                </div>
                
                <div>
                    <textarea id="editor" name="contenu_rapport"></textarea>
                </div>
            </form>
        </div>
    </div>
    
    <div id="notificationContainer" class="fixed top-5 right-5 z-[100] space-y-3"></div>

    <script>
    // YOUR JAVASCRIPT IS PRESERVED AND ADAPTED FOR THE NEW STRUCTURE
    document.addEventListener('DOMContentLoaded', function() {
        const isReadOnly = <?= $rapportDejaDepose ? 'true' : 'false' ?>;
        
        tinymce.init({
            selector: '#editor',
            height: 700,
            menubar: false,
            plugins: 'advlist autolink lists link image charmap preview anchor searchreplace visualblocks code fullscreen insertdatetime media table help wordcount',
            toolbar: 'undo redo | blocks | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | code fullscreen',
            content_style: 'body { font-family: Inter, sans-serif; font-size: 16px; line-height: 1.6; }',
            readonly: isReadOnly,
            setup: function(editor) {
                editor.on('init', function() {
                    <?php if ($isEditMode && !empty($contenuRapport)): ?>
                        editor.setContent(<?= json_encode($contenuRapport) ?>);
                        showNotification('info', isReadOnly ? 'Rapport chargé en mode consultation.' : 'Rapport chargé en mode édition.');
                    <?php endif; ?>
                });
            }
        });

        // Event Listeners
        document.getElementById('loadTemplateBtn')?.addEventListener('click', loadTemplate);
        document.getElementById('saveBtn')?.addEventListener('click', saveData);
        document.getElementById('exportBtn')?.addEventListener('click', exportPdf);
        document.getElementById('deposerBtn')?.addEventListener('click', submitForDeposit);

        function getEditorData() {
            const editor = tinymce.get('editor');
            if (!editor) {
                showNotification('error', 'L\'éditeur n\'est pas initialisé.');
                return null;
            }
            const nomRapport = document.getElementById('nom_rapport').value.trim();
            const themeRapport = document.getElementById('theme_rapport').value.trim();
            const content = editor.getContent();

            if (!nomRapport) { showNotification('error', 'Le nom du rapport est requis.'); return null; }
            if (!themeRapport) { showNotification('error', 'Le thème du rapport est requis.'); return null; }
            if (!content) { showNotification('error', 'Le contenu du rapport ne peut pas être vide.'); return null; }
            
            return { nomRapport, themeRapport, content };
        }

        function saveData(e) {
            e.preventDefault();
            const data = getEditorData();
            if (!data) return;

            const btn = document.getElementById('saveBtn');
            setButtonLoading(btn, true);

            const formData = new FormData(document.getElementById('rapportForm'));
            formData.set('contenu_rapport', data.content);

            fetch('?page=gestion_rapports&action=save_rapport', { method: 'POST', body: formData })
                .then(res => res.json())
                .then(result => {
                    if (result.success) {
                        showNotification('success', result.message || 'Rapport enregistré avec succès !');
                        if (result.new_id && !document.querySelector('input[name="edit_id"]')) {
                            // If it was a new report, reload to get the ID for subsequent saves
                            setTimeout(() => window.location.href = `?page=gestion_rapports&action=creer_rapport&edit=${result.new_id}`, 1500);
                        }
                    } else {
                        showNotification('error', result.message || 'Une erreur est survenue.');
                    }
                })
                .catch(() => showNotification('error', 'Erreur de communication avec le serveur.'))
                .finally(() => setButtonLoading(btn, false));
        }

        function exportPdf(e) {
            e.preventDefault();
            const data = getEditorData();
            if (!data) return;

            const btn = document.getElementById('exportBtn');
            setButtonLoading(btn, true);

            const formData = new FormData();
            formData.append('action', 'export_pdf');
            formData.append('contenu_rapport', data.content);
            formData.append('nom_rapport', data.nomRapport);
            formData.append('theme_rapport', data.themeRapport);

            fetch('?page=gestion_rapports&action=export_pdf', { method: 'POST', body: formData })
                .then(response => {
                    if (response.ok) return response.blob();
                    throw new Error('La génération du PDF a échoué.');
                })
                .then(blob => {
                    const url = window.URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.style.display = 'none';
                    a.href = url;
                    a.download = `${data.nomRapport || 'rapport'}.pdf`;
                    document.body.appendChild(a);
                    a.click();
                    window.URL.revokeObjectURL(url);
                    showNotification('success', 'Le PDF a été généré avec succès.');
                })
                .catch(err => showNotification('error', err.message))
                .finally(() => setButtonLoading(btn, false));
        }
        
        function submitForDeposit(e) {
            e.preventDefault();
            const data = getEditorData();
            if (!data) return;

            if (confirm("Êtes-vous sûr de vouloir déposer ce rapport ? Cette action est définitive et vous ne pourrez plus le modifier.")) {
                const form = document.getElementById('rapportForm');
                form.querySelector('input[name="action"]').value = 'deposer_rapport';
                form.submit();
            }
        }

        function loadTemplate(e) {
            e.preventDefault();
            const btn = e.currentTarget;
            setButtonLoading(btn, true, document.getElementById('loadingIndicator'));
            
            setTimeout(() => {
                const templateContent = `<h1>Titre de votre Rapport</h1><p>Commencez à écrire ici...</p>`; // Simplified template
                tinymce.get('editor').setContent(templateContent);
                showNotification('success', 'Modèle chargé.');
                setButtonLoading(btn, false, document.getElementById('loadingIndicator'));
            }, 1000);
        }

        function setButtonLoading(btn, isLoading, indicator = null) {
            if (!btn) return;
            const originalText = btn.dataset.originalText || btn.innerText;
            if (!btn.dataset.originalText) btn.dataset.originalText = originalText;
            
            if (isLoading) {
                btn.disabled = true;
                if (indicator) {
                    indicator.classList.remove('hidden');
                    btn.classList.add('hidden');
                } else {
                    btn.innerHTML = `<i class="fa-solid fa-spinner fa-spin mr-2"></i>Chargement...`;
                }
            } else {
                btn.disabled = false;
                 if (indicator) {
                    indicator.classList.add('hidden');
                    btn.classList.remove('hidden');
                } else {
                    btn.innerHTML = originalText;
                }
            }
        }
        
        function showNotification(type, message) {
            // Notification logic from your previous code
            const container = document.getElementById('notificationContainer');
            const notif = document.createElement('div');
            notif.className = `p-4 rounded-lg shadow-lg text-sm border-l-4 flex items-start gap-3 ${
                type === 'success' ? 'bg-green-50 border-green-500 text-green-800' : 
                type === 'error' ? 'bg-red-50 border-red-500 text-red-800' : 
                'bg-blue-50 border-blue-500 text-blue-800'
            }`;
            const icons = { success: 'fa-check-circle', error: 'fa-times-circle', info: 'fa-info-circle' };
            notif.innerHTML = `<div><i class="fa-solid ${icons[type]}"></i></div><div>${message}</div>`;
            container.appendChild(notif);
            setTimeout(() => {
                notif.style.transition = 'opacity 0.5s ease';
                notif.style.opacity = '0';
                setTimeout(() => notif.remove(), 500);
            }, 5000);
        }
    });
    </script>
</body>
</html>
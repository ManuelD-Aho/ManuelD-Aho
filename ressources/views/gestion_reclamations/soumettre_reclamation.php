<?php
// This is example data to make the page render.
// YOUR PHP LOGIC FOR $erreurs, $_SESSION['message'], and $typesReclamation IS PRESERVED.
$erreurs = $GLOBALS['erreurs'] ?? [];
$typesReclamation = $GLOBALS['typesReclamation'] ?? ['TECH' => 'Problème Technique', 'ADMIN' => 'Question Administrative', 'NOTE' => 'Erreur de Note'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouvelle Réclamation</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/quill/1.3.7/quill.snow.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/quill/1.3.7/quill.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }

        /* Custom styles for Quill to match the new theme */
        .ql-toolbar.ql-snow {
            border-radius: 0.375rem 0.375rem 0 0; /* rounded-md */
            border-color: #d1d5db; /* border-gray-300 */
        }
        .ql-container.ql-snow {
            border-radius: 0 0 0.375rem 0.375rem; /* rounded-md */
            border-color: #d1d5db; /* border-gray-300 */
            min-height: 200px;
            font-size: 1rem;
        }
        .ql-editor.ql-blank::before {
            color: #9ca3af; /* text-gray-400 */
            font-style: normal;
        }
        /* Style for Quill editor with an error */
        .ql-error .ql-toolbar.ql-snow,
        .ql-error .ql-container.ql-snow {
            border-color: #ef4444; /* border-red-500 */
        }
    </style>
</head>

<body class="bg-gray-100 py-12">
    <div class="container max-w-4xl mx-auto px-4">
        
        <div class="mb-6">
             <a href="?page=reclamations" class="text-sm text-gray-600 hover:text-blue-600 hover:underline">
                <i class="fa-solid fa-arrow-left mr-2"></i>Retour au portail des réclamations
            </a>
        </div>
        
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
            <div class="px-6 py-4 border-b border-gray-200">
                <h1 class="text-xl font-bold text-gray-900">Soumettre une nouvelle réclamation</h1>
            </div>

            <form method="POST" id="reclamationForm" class="p-6" novalidate>
                <?php if (isset($_SESSION['message'])): ?>
                <div class="p-4 mb-6 text-sm rounded-md <?= $_SESSION['message']['type'] === 'success' ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700' ?>" role="alert">
                    <?= htmlspecialchars($_SESSION['message']['text']); ?>
                </div>
                <?php unset($_SESSION['message']); endif; ?>

                <?php if (!empty($erreurs)): ?>
                <div class="p-4 mb-6 text-sm text-red-700 bg-red-50 rounded-md border border-red-200">
                    <h4 class="font-bold mb-2">Veuillez corriger les erreurs suivantes :</h4>
                    <ul class="list-disc list-inside space-y-1">
                        <?php foreach ($erreurs as $erreur): ?>
                            <li><?= htmlspecialchars($erreur); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endif; ?>
                
                <div class="space-y-6">
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Type de réclamation*</label>
                        <select id="type" name="type" required class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 <?= isset($erreurs['type']) ? 'border-red-500' : '' ?>">
                            <option value="">Sélectionnez un type...</option>
                            <?php foreach ($typesReclamation as $key => $label): ?>
                            <option value="<?= htmlspecialchars($key); ?>" <?= (isset($_POST['type']) && $_POST['type'] === $key) ? 'selected' : ''; ?>>
                                <?= htmlspecialchars($label); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label for="objet" class="block text-sm font-medium text-gray-700 mb-1">Objet*</label>
                        <input type="text" id="objet" name="objet" required value="<?= htmlspecialchars($_POST['objet'] ?? ''); ?>" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 <?= isset($erreurs['objet']) ? 'border-red-500' : ''; ?>" placeholder="Ex: Problème d'accès à la plateforme" minlength="5">
                    </div>

                    <div>
                        <label for="editor" class="block text-sm font-medium text-gray-700 mb-1">Description détaillée*</label>
                        <div class="<?= isset($erreurs['description']) ? 'ql-error' : ''; ?>">
                            <div id="editor"><?= $_POST['content'] ?? ''; ?></div>
                        </div>
                        <input type="hidden" name="content" id="content">
                    </div>

                    <div class="p-4 text-sm text-blue-700 bg-blue-50 border border-blue-200 rounded-md">
                        <p class="font-semibold mb-2"><i class="fa-solid fa-circle-info mr-2"></i>Informations importantes</p>
                        <ul class="list-disc list-inside space-y-1">
                            <li>Votre réclamation sera traitée dans les meilleurs délais.</li>
                            <li>Vous recevrez des notifications par e-mail à chaque étape du traitement.</li>
                            <li>Les informations fournies resteront confidentielles.</li>
                        </ul>
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-gray-200 flex justify-end">
                    <button type="submit" class="bg-blue-600 text-white font-semibold px-6 py-2.5 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-300">
                        Soumettre la réclamation
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
    // Le script est conservé mais légèrement adapté pour le nouveau style
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof Quill === 'undefined') {
            console.error('Quill library not found.');
            return;
        }

        const quill = new Quill('#editor', {
            theme: 'snow',
            modules: {
                toolbar: [
                    [{ 'header': [1, 2, 3, false] }],
                    ['bold', 'italic', 'underline'],
                    [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                    ['link', 'clean']
                ]
            },
            placeholder: 'Décrivez votre problème ou votre question aussi précisément que possible...'
        });

        const form = document.getElementById('reclamationForm');
        const contentInput = document.getElementById('content');
        
        if (form) {
            form.addEventListener('submit', function(e) {
                // Mettre à jour le champ caché avec le contenu de l'éditeur
                if (contentInput) {
                    contentInput.value = quill.root.innerHTML;
                }

                // Validation côté client simple pour l'éditeur
                if (quill.getText().trim().length < 60) {
                    e.preventDefault(); 
                    alert('La description détaillée doit contenir au moins 60 caractères pour assurer un traitement efficace.');
                    const editorContainer = document.querySelector('.ql-container');
                    if(editorContainer) editorContainer.parentElement.classList.add('ql-error');
                    return false;
                }
            });
        }

        // Compteur de caractères optionnel mais utile
        const charCounter = document.createElement('div');
        charCounter.className = 'text-xs text-right text-gray-500 mt-1';
        const editorDiv = document.getElementById('editor');
        if (editorDiv && editorDiv.parentElement) {
             editorDiv.parentElement.parentElement.appendChild(charCounter);
        }
       
        const updateCharCount = () => {
            const textLength = quill.getText().trim().length;
            charCounter.textContent = `${textLength} / 60 caractères min.`;
            charCounter.style.color = textLength >= 60 ? '#16a34a' : '#6b7280'; // Vert si ok, gris sinon
        };
        
        quill.on('text-change', updateCharCount);
        updateCharCount(); // Appel initial
    });
    </script>
</body>
</html>
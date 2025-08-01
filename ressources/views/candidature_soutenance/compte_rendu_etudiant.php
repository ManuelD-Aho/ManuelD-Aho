<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compte Rendu de Soutenance</title>
</head>

<body class="bg-gradient-to-br from-[#f6f7ff] to-[#e9ebfa] min-h-screen">
    <div class="container max-w-4xl mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Compte Rendu de Soutenance</h1>
                <a href="?page=candidature_soutenance" class="text-blue-500 hover:text-blue-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
            </div>

            <div class="space-y-6">
                <!-- Statut de la candidature -->
                <div class="border-b pb-4">
                    <h2 class="text-lg font-semibold text-gray-700 mb-2">Statut de la candidature</h2>
                    <div class="flex items-center">
                        <span
                            class="px-3 py-1 rounded-full text-sm font-medium
                            <?php echo $compte_rendu['statut'] === 'accepté' ? 'bg-green-100 text-green-800' : 
                                    ($compte_rendu['statut'] === 'refusé' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800'); ?>">
                            <?php echo ucfirst($compte_rendu['statut']); ?>
                        </span>
                    </div>
                </div>

                <!-- Date de la soutenance -->
                <div class="border-b pb-4">
                    <h2 class="text-lg font-semibold text-gray-700 mb-2">Date de la soutenance</h2>
                    <p class="text-gray-600"><?php echo date('d/m/Y', strtotime($compte_rendu['date_soutenance'])); ?>
                    </p>
                </div>

                <!-- Évaluation technique -->
                <div class="border-b pb-4">
                    <h2 class="text-lg font-semibold text-gray-700 mb-2">Évaluation technique</h2>
                    <div class="space-y-2">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Qualité du travail</span>
                            <span class="font-medium"><?php echo $compte_rendu['note_technique']; ?>/20</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-500 h-2 rounded-full"
                                style="width: <?php echo ($compte_rendu['note_technique']/20)*100; ?>%"></div>
                        </div>
                    </div>
                </div>

                <!-- Évaluation de la présentation -->
                <div class="border-b pb-4">
                    <h2 class="text-lg font-semibold text-gray-700 mb-2">Évaluation de la présentation</h2>
                    <div class="space-y-2">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Qualité de la présentation</span>
                            <span class="font-medium"><?php echo $compte_rendu['note_presentation']; ?>/20</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-500 h-2 rounded-full"
                                style="width: <?php echo ($compte_rendu['note_presentation']/20)*100; ?>%"></div>
                        </div>
                    </div>
                </div>

                <!-- Commentaires -->
                <div>
                    <h2 class="text-lg font-semibold text-gray-700 mb-2">Commentaires</h2>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-gray-600 whitespace-pre-line">
                            <?php echo nl2br(htmlspecialchars($compte_rendu['commentaires'])); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
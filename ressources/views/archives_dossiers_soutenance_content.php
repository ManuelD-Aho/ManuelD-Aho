<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Archives des Dossiers de Soutenance | Univalid</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
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
            text-decoration: none;
            display: inline-flex;
            align-items: center;
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

        .btn-success {
            background: linear-gradient(135deg, #36865a 0%, #59bf3d 100%);
            color: white;
            box-shadow: 0 4px 16px rgba(54, 134, 90, 0.3);
        }

        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(54, 134, 90, 0.4);
        }

        .btn-secondary {
            background: #f8f9fa;
            color: #334155;
            border: 2px solid #e2e8f0;
        }

        .btn-secondary:hover {
            background: white;
            border-color: rgba(52, 87, 203, 0.2);
            transform: translateY(-2px);
        }

        .header-gradient {
            background: linear-gradient(135deg, #24407a 0%, #3457cb 100%);
        }

        .filter-input {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 10px 16px;
            transition: all 0.3s ease;
        }

        .filter-input:focus {
            outline: none;
            border-color: #3457cb;
            box-shadow: 0 0 0 3px rgba(52, 87, 203, 0.1);
        }

        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 4px 16px rgba(15, 23, 42, 0.08);
            transition: all 0.3s ease;
            border: 1px solid #e2e8f0;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.12);
        }

        .archive-item {
            background: white;
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 4px 16px rgba(15, 23, 42, 0.08);
            transition: all 0.3s ease;
            border: 1px solid #e2e8f0;
            margin-bottom: 16px;
        }

        .archive-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.12);
        }

        .badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .badge.archived {
            background: linear-gradient(135deg, #6b7280 0%, #9ca3af 100%);
            color: white;
        }

        .badge.validated {
            background: linear-gradient(135deg, #36865a 0%, #59bf3d 100%);
            color: white;
        }

        .badge.rejected {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
        }

        .pagination-item {
            padding: 8px 16px;
            margin: 0 2px;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .pagination-item:hover {
            background: rgba(52, 87, 203, 0.1);
            transform: translateY(-1px);
        }

        .table-hover tbody tr {
            transition: all 0.2s ease;
        }

        .table-hover tbody tr:hover {
            background: rgba(52, 87, 203, 0.02);
            transform: scale(1.01);
        }
    </style>
</head>

<body class="bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Header Section -->
        <div class="header bg-white rounded-3xl p-8 lg:p-12 mb-8 shadow-xl relative overflow-hidden animate-fade-in-down">
            <div class="flex items-center gap-6 md:gap-8 flex-col md:flex-row text-center md:text-left">
                <div class="header-icon bg-gradient-to-br from-primary to-primary-light text-white w-20 h-20 md:w-24 md:h-24 rounded-2xl flex items-center justify-center text-4xl md:text-5xl shadow-lg">
                    <i class="fas fa-archive"></i>
                </div>
                <div class="header-text">
                    <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 mb-2 tracking-tight">Archives des Dossiers</h1>
                    <p class="text-lg text-gray-600 font-normal">Consultation des dossiers de soutenance archivés et validés</p>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="stat-card animate-slide-in-right" style="animation-delay: 0.1s">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-gray-500/10 text-gray-600 rounded-xl flex items-center justify-center text-2xl">
                        <i class="fas fa-archive"></i>
                    </div>
                    <div>
                        <h3 class="text-3xl font-bold text-gray-600">128</h3>
                        <p class="text-sm font-medium text-gray-600">Total archivés</p>
                    </div>
                </div>
            </div>
            <div class="stat-card animate-slide-in-right" style="animation-delay: 0.2s">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-secondary/10 text-secondary rounded-xl flex items-center justify-center text-2xl">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div>
                        <h3 class="text-3xl font-bold text-secondary">95</h3>
                        <p class="text-sm font-medium text-gray-600">Validés</p>
                    </div>
                </div>
            </div>
            <div class="stat-card animate-slide-in-right" style="animation-delay: 0.3s">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-red-500/10 text-red-600 rounded-xl flex items-center justify-center text-2xl">
                        <i class="fas fa-times-circle"></i>
                    </div>
                    <div>
                        <h3 class="text-3xl font-bold text-red-600">33</h3>
                        <p class="text-sm font-medium text-gray-600">Rejetés</p>
                    </div>
                </div>
            </div>
            <div class="stat-card animate-slide-in-right" style="animation-delay: 0.4s">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-yellow-500/10 text-yellow-600 rounded-xl flex items-center justify-center text-2xl">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <div>
                        <h3 class="text-3xl font-bold text-yellow-600"><?php echo date('Y'); ?></h3>
                        <p class="text-sm font-medium text-gray-600">Année en cours</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters Section -->
        <div class="card mb-8 animate-scale-in">
            <div class="header-gradient px-8 py-6">
                <h2 class="text-2xl font-bold text-white flex items-center">
                    <i class="fas fa-filter mr-3 text-2xl"></i>
                    Filtres et Recherche
                </h2>
            </div>
            <div class="p-8">
                <form method="GET" action="?page=archives_dossiers_soutenance" class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                    <input type="hidden" name="page" value="archives_dossiers_soutenance">
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">🔍 Recherche</label>
                        <input type="text" name="search" 
                               value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>"
                               placeholder="Nom, prénom, sujet..."
                               class="filter-input w-full">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">📊 Statut</label>
                        <select name="statut" class="filter-input w-full">
                            <option value="">Tous les statuts</option>
                            <option value="valide" <?php echo (isset($_GET['statut']) && $_GET['statut'] === 'valide') ? 'selected' : ''; ?>>Validé</option>
                            <option value="rejete" <?php echo (isset($_GET['statut']) && $_GET['statut'] === 'rejete') ? 'selected' : ''; ?>>Rejeté</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">📅 Année</label>
                        <select name="annee" class="filter-input w-full">
                            <option value="">Toutes les années</option>
                            <?php for ($year = date('Y'); $year >= 2020; $year--): ?>
                            <option value="<?php echo $year; ?>" <?php echo (isset($_GET['annee']) && $_GET['annee'] == $year) ? 'selected' : ''; ?>>
                                <?php echo $year; ?>
                            </option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    
                    <div class="flex items-end">
                        <div class="flex gap-3 w-full">
                            <button type="submit" class="btn btn-primary flex-1">
                                <i class="fas fa-search mr-2"></i> Filtrer
                            </button>
                            <a href="?page=archives_dossiers_soutenance" class="btn btn-secondary">
                                <i class="fas fa-times"></i>
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Archives List -->
        <div class="card animate-scale-in" style="animation-delay: 0.2s">
            <div class="header-gradient px-8 py-6">
                <div class="flex justify-between items-center">
                    <h2 class="text-2xl font-bold text-white flex items-center">
                        <i class="fas fa-list mr-3 text-2xl"></i>
                        Dossiers Archivés
                    </h2>
                    <div class="flex space-x-3">
                        <button class="btn btn-success text-sm">
                            <i class="fas fa-file-export mr-2"></i> Exporter
                        </button>
                        <button onclick="window.print()" class="btn text-sm" style="background: white; color: #24407a;">
                            <i class="fas fa-print mr-2"></i> Imprimer
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="p-8">
                <?php
                // Simulation de données d'archives
                $archives = [
                    [
                        'id' => 1,
                        'nom' => 'Kouame',
                        'prenom' => 'Jean-Baptiste',
                        'numero' => '20230001',
                        'sujet' => 'Développement d\'une application mobile de gestion de bibliothèque',
                        'statut' => 'valide',
                        'date_soutenance' => '2024-06-15',
                        'note' => '15.5/20',
                        'mention' => 'Bien'
                    ],
                    [
                        'id' => 2,
                        'nom' => 'Traore',
                        'prenom' => 'Aissata',
                        'numero' => '20230002',
                        'sujet' => 'Système de recommandation basé sur l\'intelligence artificielle',
                        'statut' => 'valide',
                        'date_soutenance' => '2024-06-10',
                        'note' => '17.0/20',
                        'mention' => 'Très Bien'
                    ],
                    [
                        'id' => 3,
                        'nom' => 'Diallo',
                        'prenom' => 'Mohamed',
                        'numero' => '20230003',
                        'sujet' => 'Plateforme e-commerce pour produits locaux',
                        'statut' => 'rejete',
                        'date_soutenance' => '2024-06-05',
                        'note' => '8.5/20',
                        'mention' => 'Insuffisant'
                    ],
                    [
                        'id' => 4,
                        'nom' => 'Kone',
                        'prenom' => 'Mariam',
                        'numero' => '20230004',
                        'sujet' => 'Application de suivi médical pour patients chroniques',
                        'statut' => 'valide',
                        'date_soutenance' => '2024-06-20',
                        'note' => '16.5/20',
                        'mention' => 'Très Bien'
                    ]
                ];
                ?>
                
                <?php if (empty($archives)): ?>
                <div class="text-center py-12">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-archive text-3xl text-gray-400"></i>
                    </div>
                    <p class="text-lg font-semibold text-gray-500 mb-2">Aucun dossier archivé</p>
                    <p class="text-sm text-gray-400">Les dossiers validés apparaîtront ici après archivage.</p>
                </div>
                <?php else: ?>
                
                <div class="space-y-4">
                    <?php foreach ($archives as $index => $archive): ?>
                    <div class="archive-item animate-slide-in-right" style="animation-delay: <?php echo ($index * 0.1); ?>s">
                        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                            <div class="flex-1">
                                <div class="flex items-center gap-4 mb-3">
                                    <div class="w-12 h-12 bg-primary/10 text-primary rounded-full flex items-center justify-center font-bold">
                                        <?php echo strtoupper(substr($archive['prenom'], 0, 1) . substr($archive['nom'], 0, 1)); ?>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold text-gray-900">
                                            <?php echo htmlspecialchars($archive['prenom'] . ' ' . $archive['nom']); ?>
                                        </h3>
                                        <p class="text-sm text-gray-600">N° <?php echo htmlspecialchars($archive['numero']); ?></p>
                                    </div>
                                    <div class="ml-auto lg:ml-0">
                                        <span class="badge <?php echo $archive['statut']; ?>">
                                            <?php echo ucfirst($archive['statut']); ?>
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                                    <div>
                                        <p class="text-sm font-medium text-gray-600 mb-1">📝 Sujet</p>
                                        <p class="text-sm text-gray-900"><?php echo htmlspecialchars($archive['sujet']); ?></p>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-600 mb-1">📅 Date de soutenance</p>
                                        <p class="text-sm text-gray-900"><?php echo date('d/m/Y', strtotime($archive['date_soutenance'])); ?></p>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-600 mb-1">📊 Résultat</p>
                                        <p class="text-sm font-semibold <?php echo $archive['statut'] === 'valide' ? 'text-green-600' : 'text-red-600'; ?>">
                                            <?php echo htmlspecialchars($archive['note']); ?> - <?php echo htmlspecialchars($archive['mention']); ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex gap-3 lg:flex-col lg:w-auto">
                                <button class="btn btn-primary text-sm" onclick="viewDetails(<?php echo $archive['id']; ?>)">
                                    <i class="fas fa-eye mr-2"></i> Voir
                                </button>
                                <button class="btn btn-secondary text-sm" onclick="downloadReport(<?php echo $archive['id']; ?>)">
                                    <i class="fas fa-download mr-2"></i> PDF
                                </button>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <!-- Pagination -->
                <div class="mt-8 flex items-center justify-between">
                    <div class="text-sm text-gray-600">
                        Affichage de <span class="font-semibold text-primary">1</span> à 
                        <span class="font-semibold text-primary">4</span> sur 
                        <span class="font-semibold text-primary">128</span> dossiers
                    </div>
                    <div class="flex items-center space-x-2">
                        <a href="#" class="pagination-item bg-white border border-gray-300 text-gray-700 hover:bg-primary hover:text-white">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                        <span class="pagination-item bg-primary text-white border border-primary">1</span>
                        <a href="#" class="pagination-item bg-white border border-gray-300 text-gray-700 hover:bg-primary hover:text-white">2</a>
                        <a href="#" class="pagination-item bg-white border border-gray-300 text-gray-700 hover:bg-primary hover:text-white">3</a>
                        <span class="pagination-item text-gray-500">...</span>
                        <a href="#" class="pagination-item bg-white border border-gray-300 text-gray-700 hover:bg-primary hover:text-white">32</a>
                        <a href="#" class="pagination-item bg-white border border-gray-300 text-gray-700 hover:bg-primary hover:text-white">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </div>
                </div>
                
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        // Fonctions d'interaction
        function viewDetails(id) {
            // Redirection vers la page de détails
            window.location.href = `?page=archives_dossiers_soutenance&action=view&id=${id}`;
        }

        function downloadReport(id) {
            // Téléchargement du rapport PDF
            window.location.href = `?page=archives_dossiers_soutenance&action=download&id=${id}`;
        }

        // Initialisation
        document.addEventListener('DOMContentLoaded', function() {
            // Animation des éléments d'archive
            const archiveItems = document.querySelectorAll('.archive-item');
            archiveItems.forEach((item, index) => {
                item.style.animationDelay = `${index * 0.1}s`;
            });

            // Animation des cartes statistiques
            const statCards = document.querySelectorAll('.stat-card');
            statCards.forEach((card, index) => {
                card.style.animationDelay = `${index * 0.1}s`;
            });

            // Soumission automatique du formulaire de recherche
            let searchTimeout;
            const searchInput = document.querySelector('input[name="search"]');
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(() => {
                        this.form.submit();
                    }, 800);
                });
            }

            // Effet hover sur les éléments d'archive
            archiveItems.forEach(item => {
                item.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-2px) scale(1.02)';
                });
                
                item.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0) scale(1)';
                });
            });

            console.log('✅ Archives des dossiers de soutenance initialisées');
        });
    </script>
</body>

</html>

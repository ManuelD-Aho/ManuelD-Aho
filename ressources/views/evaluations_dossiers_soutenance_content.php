<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Évaluations | Univalid</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        /* Palette de couleurs améliorée */
        :root {
            --clr-bg: #f8fafc;
            --clr-sidebar: #ffffff;
            --clr-primary: #24407a;
            --clr-accent: #3457cb;
            --clr-accent-light: #e6eaff;
            --clr-accent-hover: #2d4bb8;
            --clr-success: #36865a;
            --clr-success-light: #59bf3d;
            --clr-success-bg: #f0f9f4;
            --clr-text: #334155;
            --clr-text-light: #64748b;
            --clr-border: #e2e8f0;
            --clr-shadow: rgba(15, 23, 42, 0.08);
            --clr-shadow-hover: rgba(15, 23, 42, 0.12);
        }

        body {
            background: linear-gradient(135deg, var(--clr-bg) 0%, #f1f5f9 100%);
            color: var(--clr-text);
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
        }

        .sidebar {
            width: 320px;
            background: var(--clr-sidebar);
            box-shadow: 0 8px 32px var(--clr-shadow);
            backdrop-filter: blur(10px);
            border-right: 1px solid var(--clr-border);
        }

        .logo-gradient {
            background: linear-gradient(135deg, var(--clr-primary) 0%, var(--clr-accent) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .search-container {
            position: relative;
            overflow: hidden;
        }

        .search-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, var(--clr-accent-light), transparent);
            border-radius: 12px;
            opacity: 0;
            transition: opacity 0.3s ease;
            z-index: 0;
        }

        .search-container:focus-within::before {
            opacity: 1;
        }

        .search-input {
            background: white;
            border: 2px solid var(--clr-border);
            border-radius: 12px;
            padding: 12px 16px 12px 48px;
            transition: all 0.3s ease;
            position: relative;
            z-index: 1;
        }

        .search-input:focus {
            border-color: var(--clr-accent);
            box-shadow: 0 0 0 3px rgba(52, 87, 203, 0.1);
            outline: none;
        }

        .dossier-item {
            background: white;
            border: 1px solid var(--clr-border);
            border-radius: 16px;
            margin-bottom: 8px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
            position: relative;
        }

        .dossier-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 32px var(--clr-shadow-hover);
            border-color: var(--clr-accent-light);
        }

        .dossier-item.active {
            background: linear-gradient(135deg, var(--clr-accent-light) 0%, rgba(52, 87, 203, 0.05) 100%);
            border-color: var(--clr-accent);
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(52, 87, 203, 0.2);
        }

        .dossier-item.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: linear-gradient(to bottom, var(--clr-accent), var(--clr-success));
        }

        .avatar {
            background: linear-gradient(135deg, var(--clr-accent) 0%, var(--clr-success) 100%);
            color: white;
            border-radius: 12px;
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 14px;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        }

        .tab-container {
            background: white;
            border: 1px solid var(--clr-border);
            border-radius: 16px;
            padding: 6px;
            box-shadow: 0 4px 16px var(--clr-shadow);
        }

        .tab-btn {
            padding: 12px 24px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .tab-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .tab-btn:hover::before {
            left: 100%;
        }

        .tab-active {
            background: linear-gradient(135deg, var(--clr-accent) 0%, var(--clr-primary) 100%);
            color: white;
            box-shadow: 0 4px 16px rgba(52, 87, 203, 0.3);
            transform: translateY(-1px);
        }

        .tab-inactive {
            color: var(--clr-text-light);
            background: var(--clr-bg);
        }

        .tab-inactive:hover {
            background: var(--clr-accent-light);
            color: var(--clr-accent);
            transform: translateY(-1px);
        }

        .card {
            background: white;
            border-radius: 20px;
            border: 1px solid var(--clr-border);
            box-shadow: 0 8px 32px var(--clr-shadow);
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
            background: linear-gradient(90deg, var(--clr-accent) 0%, var(--clr-success) 50%, var(--clr-success-light) 100%);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .card:hover::before {
            opacity: 1;
        }

        .card:hover {
            transform: translateY(-4px);
            box-shadow: 0 16px 48px var(--clr-shadow-hover);
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
            background: linear-gradient(135deg, var(--clr-accent) 0%, var(--clr-primary) 100%);
            color: white;
            box-shadow: 0 4px 16px rgba(52, 87, 203, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(52, 87, 203, 0.4);
        }

        .btn-success {
            background: linear-gradient(135deg, var(--clr-success) 0%, var(--clr-success-light) 100%);
            color: white;
            box-shadow: 0 4px 16px rgba(54, 134, 90, 0.3);
        }

        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(54, 134, 90, 0.4);
        }

        .btn-secondary {
            background: var(--clr-bg);
            color: var(--clr-text);
            border: 2px solid var(--clr-border);
        }

        .btn-secondary:hover {
            background: white;
            border-color: var(--clr-accent-light);
            transform: translateY(-2px);
        }

        .status-badge {
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-validated {
            background: linear-gradient(135deg, var(--clr-success-bg) 0%, rgba(89, 191, 61, 0.1) 100%);
            color: var(--clr-success);
            border: 1px solid rgba(54, 134, 90, 0.2);
        }

        .status-pending {
            background: linear-gradient(135deg, #fef3c7 0%, rgba(245, 158, 11, 0.1) 100%);
            color: #92400e;
            border: 1px solid rgba(245, 158, 11, 0.2);
        }

        .form-container {
            background: white;
            border-radius: 24px;
            border: 1px solid var(--clr-border);
            box-shadow: 0 16px 64px var(--clr-shadow);
            position: relative;
            overflow: hidden;
        }

        .form-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: linear-gradient(90deg, var(--clr-accent) 0%, var(--clr-success) 50%, var(--clr-success-light) 100%);
        }

        .radio-option {
            background: white;
            border: 2px solid var(--clr-border);
            border-radius: 16px;
            padding: 16px;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .radio-option:hover {
            border-color: var(--clr-accent-light);
            background: rgba(52, 87, 203, 0.02);
        }

        .radio-option.selected {
            border-color: var(--clr-accent);
            background: var(--clr-accent-light);
        }

        .textarea-custom {
            background: white;
            border: 2px solid var(--clr-border);
            border-radius: 16px;
            padding: 16px;
            transition: all 0.3s ease;
            resize: vertical;
            min-height: 120px;
        }

        .textarea-custom:focus {
            outline: none;
            border-color: var(--clr-accent);
            box-shadow: 0 0 0 3px rgba(52, 87, 203, 0.1);
        }

        .empty-state {
            background: white;
            border-radius: 24px;
            border: 2px dashed var(--clr-border);
            padding: 48px;
            text-align: center;
        }

        .icon-gradient {
            background: linear-gradient(135deg, var(--clr-accent) 0%, var(--clr-success) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Animations */
        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .animate-slide-up {
            animation: slideInUp 0.6s ease-out;
        }

        .animate-fade-in {
            animation: fadeIn 0.4s ease-out;
        }

        /* Scrollbar personnalisé */
        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(to bottom, var(--clr-accent), var(--clr-success));
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(to bottom, var(--clr-accent-hover), var(--clr-success));
        }

        /* Media queries pour responsive */
        @media (max-width: 1024px) {
            .sidebar {
                width: 280px;
            }
        }

        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                left: -320px;
                top: 0;
                height: 100vh;
                z-index: 50;
                transition: left 0.3s ease;
            }

            .sidebar.mobile-open {
                left: 0;
            }
        }
    </style>
</head>

<body class="flex h-screen antialiased">

    <div class="header bg-white rounded-3xl p-8 lg:p-12 mb-8 shadow-xl relative overflow-hidden">
        <div class="flex items-center gap-6 md:gap-8 flex-col md:flex-row text-center md:text-left">
            <div class="header-icon bg-gradient-to-br from-primary to-primary-dark text-white w-20 h-20 md:w-24 md:h-24 rounded-2xl flex items-center justify-center text-4xl md:text-5xl shadow-lg">
                <i class="fas fa-clipboard-check"></i>
            </div>
            <div class="header-text">
                <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 mb-2 tracking-tight">Évaluation des documents de soutenance</h1>
                <p class="text-lg text-gray-600 font-normal">Évaluer les rapports en attente d'examen</p>
            </div>
        </div>
    </div>


    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="stat-card bg-white rounded-2xl p-6 shadow-md transition-all duration-300 hover:translate-y-[-4px] hover:shadow-lg relative overflow-hidden" style="animation-delay: 0.1s">
            <div class="stat-content flex items-center gap-4">
                <div class="stat-icon bg-primary/10 text-primary w-12 h-12 rounded-xl flex items-center justify-center text-2xl">
                    <i class="fas fa-file-alt"></i>
                </div>
                <div class="stat-info">
                    <h3 class="text-4xl font-bold text-primary mb-1">12</h3>
                    <p class="text-sm font-medium text-gray-600">Rapports total</p>
                </div>
            </div>
        </div>
        <div class="stat-card bg-white rounded-2xl p-6 shadow-md transition-all duration-300 hover:translate-y-[-4px] hover:shadow-lg relative overflow-hidden" style="animation-delay: 0.2s">
            <div class="stat-content flex items-center gap-4">
                <div class="stat-icon bg-secondary/10 text-secondary w-12 h-12 rounded-xl flex items-center justify-center text-2xl">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-info">
                    <h3 class="text-4xl font-bold text-secondary mb-1">75%</h3>
                    <p class="text-sm font-medium text-gray-600">Rapports en attente de validation</p>
                </div>
            </div>
        </div>
        <div class="stat-card bg-white rounded-2xl p-6 shadow-md transition-all duration-300 hover:translate-y-[-4px] hover:shadow-lg relative overflow-hidden" style="animation-delay: 0.3s">
            <div class="stat-content flex items-center gap-4">
                <div class="stat-icon bg-warning/10 text-warning w-12 h-12 rounded-xl flex items-center justify-center text-2xl">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-info">
                    <h3 class="text-4xl font-bold text-warning mb-1">3.2j</h3>
                    <p class="text-sm font-medium text-gray-600">Rapports validés</p>
                </div>
            </div>
        </div>
        <div class="stat-card bg-white rounded-2xl p-6 shadow-md transition-all duration-300 hover:translate-y-[-4px] hover:shadow-lg relative overflow-hidden" style="animation-delay: 0.4s">
            <div class="stat-content flex items-center gap-4">
                <div class="stat-icon bg-danger/10 text-danger w-12 h-12 rounded-xl flex items-center justify-center text-2xl">
                    <i class="fas fa-hourglass-half"></i>
                </div>
                <div class="stat-info">
                    <h3 class="text-4xl font-bold text-danger mb-1">3</h3>
                    <p class="text-sm font-medium text-gray-600">Rapports rejetés</p>
                </div>
            </div>
        </div>
    </div>


    <aside class="sidebar border-r border-clr-border p-6 flex-shrink-0 overflow-y-auto">
        <div class="mb-8">
            <h2 class="text-4xl font-extrabold logo-gradient">Univalid</h2>
            <p class="text-sm text-clr-text-light mt-2">Gestion des évaluations</p>
        </div>

        <div class="mb-6">
            <div class="search-container">
                <input type="text" id="searchInput" placeholder="Rechercher un dossier..."
                    class="search-input w-full text-sm" />
                <svg class="absolute left-4 top-1/2 -translate-y-1/2 text-clr-text-light h-5 w-5 z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
        </div>

        <div class="mb-4">
            <h3 class="text-lg font-semibold text-clr-primary mb-4 flex items-center">
                <span class="icon-gradient text-xl mr-2">📂</span>
                Dossiers en attente
            </h3>
        </div>

        <ul id="dossierList" class="space-y-2">
            <!-- Exemple de dossier actif -->
            <li>
                <div class="dossier-item active p-4">
                    <div class="flex items-center space-x-4">
                        <div class="avatar">
                            MJ
                        </div>
                        <div class="flex-1 overflow-hidden">
                            <div class="text-sm font-semibold text-clr-text truncate">Marie Dubois</div>
                            <div class="text-xs text-clr-text-light truncate">Rapport de stage - Développement Web</div>
                            <div class="text-xs text-clr-success mt-1">Déposé le 15/07/2025</div>
                        </div>
                        <svg class="h-5 w-5 text-clr-accent flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>
            </li>

            <!-- Exemples de dossiers non actifs -->
            <li>
                <div class="dossier-item p-4">
                    <div class="flex items-center space-x-4">
                        <div class="avatar">
                            PL
                        </div>
                        <div class="flex-1 overflow-hidden">
                            <div class="text-sm font-semibold text-clr-text truncate">Pierre Lefebvre</div>
                            <div class="text-xs text-clr-text-light truncate">Mémoire - Intelligence Artificielle</div>
                            <div class="text-xs text-clr-text-light mt-1">Déposé le 12/07/2025</div>
                        </div>
                    </div>
                </div>
            </li>

            <li>
                <div class="dossier-item p-4">
                    <div class="flex items-center space-x-4">
                        <div class="avatar">
                            SB
                        </div>
                        <div class="flex-1 overflow-hidden">
                            <div class="text-sm font-semibold text-clr-text truncate">Sophie Bernard</div>
                            <div class="text-xs text-clr-text-light truncate">Projet de fin d'études - Data Science</div>
                            <div class="text-xs text-clr-text-light mt-1">Déposé le 10/07/2025</div>
                        </div>
                    </div>
                </div>
            </li>
        </ul>
    </aside>

    <main class="flex-1 p-8 overflow-y-auto">
        <div class="flex flex-col lg:flex-row lg:justify-between items-start lg:items-center mb-8 animate-slide-up">
            <div class="mb-6 lg:mb-0">
                <h1 class="text-5xl font-extrabold text-clr-primary mb-2">
                    Marie Dubois
                </h1>
                <p class="text-xl text-clr-text-light">Rapport de stage - Développement Web</p>
            </div>

            <div class="tab-container flex space-x-2">
                <button id="tab-info" class="tab-btn tab-active" onclick="switchTab('info')">
                    📋 Informations
                </button>
                <button id="tab-history" class="tab-btn tab-inactive" onclick="switchTab('history')">
                    👁️ Aperçu
                </button>
                <button id="tab-decision" class="tab-btn tab-inactive" onclick="switchTab('decision')">
                    ⚖️ Décision
                </button>
            </div>
        </div>

        <section id="content-info" class="animate-fade-in">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6 mb-8">
                <div class="card p-6">
                    <div class="text-sm text-clr-text-light mb-2 font-medium">👤 Étudiant</div>
                    <div class="font-bold text-clr-primary text-xl">Marie Dubois</div>
                </div>

                <div class="card p-6">
                    <div class="text-sm text-clr-text-light mb-2 font-medium">✉️ Email</div>
                    <div class="font-semibold text-clr-text">marie.dubois@univ.fr</div>
                </div>

                <div class="card p-6">
                    <div class="text-sm text-clr-text-light mb-2 font-medium">🎓 Promotion</div>
                    <div class="font-bold text-clr-primary text-xl">2024-2025</div>
                </div>

                <div class="card p-6">
                    <div class="text-sm text-clr-text-light mb-2 font-medium">📅 Déposé le</div>
                    <div class="font-bold text-clr-text text-xl">15/07/2025</div>
                </div>


            </div>

            <div class="card p-8">
                <div class="text-sm text-clr-text-light mb-4 font-medium">📝 Sujet du Rapport</div>
                <div class="font-semibold text-clr-primary text-2xl leading-relaxed">
                    Développement d'une application web moderne avec React et Node.js :
                    Étude de cas d'une plateforme e-commerce
                </div>

            </div>
        </section>

        <section id="content-history" class="hidden animate-fade-in">
            <div class="card p-8 h-full flex flex-col">
                <h3 class="text-2xl font-bold text-clr-primary mb-6 flex items-center">
                    <span class="icon-gradient text-2xl mr-3">👁️</span>
                    Aperçu du Rapport
                </h3>

                <div class="flex-1 border-2 border-dashed border-clr-border rounded-2xl overflow-hidden mb-6 min-h-96">
                    <div class="h-full flex flex-col items-center justify-center text-lg text-clr-text-light py-20">
                        <div class="w-20 h-20 rounded-full bg-gradient-to-br from-clr-accent to-clr-success flex items-center justify-center mb-4">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <p class="text-xl font-semibold mb-2">Document disponible</p>
                        <p>Rapport_Marie_Dubois_2025.pdf</p>
                    </div>
                </div>

                <div class="flex justify-end space-x-4">
                    <button class="btn btn-secondary">
                        <span class="relative z-10">👁️ Prévisualiser</span>
                    </button>
                    <button class="btn btn-primary">
                        <span class="relative z-10">
                            <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Télécharger
                        </span>
                    </button>
                </div>
            </div>
        </section>

        <section id="content-decision" class="hidden animate-fade-in">
            <div class="max-w-2xl mx-auto">
                <form id="form-decision" class="form-container p-8">
                    <input type="hidden" name="id_rapport" value="1">

                    <div class="text-center mb-8">
                        <h3 class="text-3xl font-bold text-clr-primary mb-2">Prendre une Décision</h3>
                        <p class="text-clr-text-light">Évaluez le rapport et prenez une décision</p>
                    </div>

                    <div class="space-y-4 mb-8">
                        <div class="radio-option" onclick="selectOption(this, 'valider')">
                            <label class="flex items-center text-lg cursor-pointer">
                                <input type="radio" name="decision" value="valider" class="mr-4 h-5 w-5 text-clr-success focus:ring-clr-success">
                                <div>
                                    <div class="font-semibold text-clr-success">✅ Valider le rapport</div>
                                    <div class="text-sm text-clr-text-light mt-1">Le rapport répond aux critères et peut être accepté</div>
                                </div>
                            </label>
                        </div>

                        <div class="radio-option" onclick="selectOption(this, 'rejeter')">
                            <label class="flex items-center text-lg cursor-pointer">
                                <input type="radio" name="decision" value="rejeter" class="mr-4 h-5 w-5 text-red-500 focus:ring-red-500">
                                <div>
                                    <div class="font-semibold text-red-600">📝 Demander des corrections</div>
                                    <div class="text-sm text-clr-text-light mt-1">Le rapport nécessite des améliorations</div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div class="mb-8">
                        <label for="commentaire" class="block font-semibold text-clr-primary mb-3 text-lg">
                            💬 Commentaire pour l'étudiant
                        </label>
                        <textarea id="commentaire" name="commentaire"
                            class="textarea-custom w-full"
                            placeholder="Ajoutez vos observations, suggestions ou félicitations..."></textarea>
                    </div>

                    <div class="flex justify-end space-x-4">
                        <button type="button" class="btn btn-secondary">
                            <span class="relative z-10">Annuler</span>
                        </button>
                        <button type="submit" class="btn btn-success">
                            <span class="relative z-10">
                                <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Soumettre la Décision
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </section>

        <script>
            function switchTab(tab) {
                const tabs = ['info', 'history', 'decision'];

                tabs.forEach(t => {
                    const tabBtn = document.getElementById('tab-' + t);
                    const contentSection = document.getElementById('content-' + t);

                    if (t === tab) {
                        tabBtn.classList.add('tab-active');
                        tabBtn.classList.remove('tab-inactive');
                        contentSection.classList.remove('hidden');
                        contentSection.classList.add('animate-fade-in');
                    } else {
                        tabBtn.classList.remove('tab-active');
                        tabBtn.classList.add('tab-inactive');
                        contentSection.classList.add('hidden');
                        contentSection.classList.remove('animate-fade-in');
                    }
                });
            }

            function selectOption(element, value) {
                // Retirer la classe selected de tous les radio-option
                document.querySelectorAll('.radio-option').forEach(option => {
                    option.classList.remove('selected');
                });

                // Ajouter la classe selected à l'élément cliqué
                element.classList.add('selected');

                // Cocher le radio button correspondant
                const radioInput = element.querySelector('input[type="radio"]');
                if (radioInput) {
                    radioInput.checked = true;
                }
            }

            // Initialisation au chargement de la page
            document.addEventListener('DOMContentLoaded', () => {
                switchTab('info');

                // Logique de recherche dans la sidebar
                const searchInput = document.getElementById('searchInput');
                const dossierList = document.getElementById('dossierList');
                const dossierItems = dossierList.getElementsByTagName('li');

                searchInput.addEventListener('keyup', function() {
                    const filter = searchInput.value.toLowerCase();

                    for (let i = 0; i < dossierItems.length; i++) {
                        const item = dossierItems[i];
                        const studentName = item.querySelector('.text-sm.font-semibold').textContent.toLowerCase();
                        const reportName = item.querySelector('.text-xs.text-clr-text-light').textContent.toLowerCase();

                        if (studentName.includes(filter) || reportName.includes(filter)) {
                            item.style.display = '';
                        } else {
                            item.style.display = 'none';
                        }
                    }
                });

                // Animation des cartes au scroll
                const observerOptions = {
                    threshold: 0.1,
                    rootMargin: '0px 0px -50px 0px'
                };

                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            entry.target.style.animationDelay = Math.random() * 0.3 + 's';
                            entry.target.classList.add('animate-slide-up');
                        }
                    });
                }, observerOptions);

                // Observer toutes les cartes
                document.querySelectorAll('.card').forEach(card => {
                    observer.observe(card);
                });

                // Gestion du formulaire de décision
                const form = document.getElementById('form-decision');
                if (form) {
                    form.addEventListener('submit', function(e) {
                        e.preventDefault();

                        const formData = new FormData(form);
                        const decision = formData.get('decision');
                        const commentaire = formData.get('commentaire');

                        if (!decision) {
                            alert('Veuillez sélectionner une décision');
                            return;
                        }

                        // Animation de soumission
                        const submitBtn = form.querySelector('button[type="submit"]');
                        const originalText = submitBtn.innerHTML;

                        submitBtn.innerHTML = '<span class="relative z-10">⏳ Traitement...</span>';
                        submitBtn.disabled = true;

                        // Simulation d'envoi (remplacer par vraie logique)
                        setTimeout(() => {
                            alert('Décision enregistrée avec succès !');
                            submitBtn.innerHTML = originalText;
                            submitBtn.disabled = false;
                        }, 2000);
                    });
                }

                // Gestion responsive de la sidebar
                const createMobileToggle = () => {
                    if (window.innerWidth <= 768) {
                        const sidebar = document.querySelector('.sidebar');
                        const main = document.querySelector('main');

                        if (!document.querySelector('.mobile-toggle')) {
                            const toggleBtn = document.createElement('button');
                            toggleBtn.className = 'mobile-toggle fixed top-4 left-4 z-50 bg-white p-3 rounded-full shadow-lg border border-clr-border btn-primary';
                            toggleBtn.innerHTML = '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>';

                            toggleBtn.addEventListener('click', () => {
                                sidebar.classList.toggle('mobile-open');
                            });

                            document.body.appendChild(toggleBtn);

                            // Fermer la sidebar en cliquant sur le main
                            main.addEventListener('click', () => {
                                sidebar.classList.remove('mobile-open');
                            });
                        }
                    }
                };

                createMobileToggle();
                window.addEventListener('resize', createMobileToggle);

                // Effet de parallax léger sur les cartes
                window.addEventListener('scroll', () => {
                    const cards = document.querySelectorAll('.card');
                    const scrolled = window.pageYOffset;

                    cards.forEach((card, index) => {
                        const rate = scrolled * -0.5 * (index % 3 + 1) * 0.1;
                        card.style.transform = `translateY(${rate}px)`;
                    });
                });

                // Préchargement des images d'avatar avec couleurs dynamiques
                const avatars = document.querySelectorAll('.avatar');
                const colors = [
                    'linear-gradient(135deg, var(--clr-accent) 0%, var(--clr-success) 100%)',
                    'linear-gradient(135deg, var(--clr-success) 0%, var(--clr-success-light) 100%)',
                    'linear-gradient(135deg, var(--clr-primary) 0%, var(--clr-accent) 100%)',
                ];

                avatars.forEach((avatar, index) => {
                    avatar.style.background = colors[index % colors.length];
                });
            });

            // Fonction utilitaire pour les notifications toast
            function showToast(message, type = 'info') {
                const toast = document.createElement('div');
                toast.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg max-w-sm transform transition-all duration-300 translate-x-full`;

                const bgColor = type === 'success' ? 'bg-gradient-to-r from-clr-success to-clr-success-light' :
                    type === 'error' ? 'bg-gradient-to-r from-red-500 to-red-600' :
                    'bg-gradient-to-r from-clr-accent to-clr-primary';

                toast.className += ` ${bgColor} text-white`;
                toast.innerHTML = `
                    <div class="flex items-center">
                        <span class="mr-2">${type === 'success' ? '✅' : type === 'error' ? '❌' : 'ℹ️'}</span>
                        <span>${message}</span>
                        <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                `;

                document.body.appendChild(toast);

                // Animation d'apparition
                setTimeout(() => toast.classList.remove('translate-x-full'), 100);

                // Auto-suppression après 4 secondes
                setTimeout(() => {
                    toast.classList.add('translate-x-full');
                    setTimeout(() => toast.remove(), 300);
                }, 4000);
            }

            // Raccourcis clavier
            document.addEventListener('keydown', (e) => {
                if (e.ctrlKey || e.metaKey) {
                    switch (e.key) {
                        case '1':
                            e.preventDefault();
                            switchTab('info');
                            break;
                        case '2':
                            e.preventDefault();
                            switchTab('history');
                            break;
                        case '3':
                            e.preventDefault();
                            switchTab('decision');
                            break;
                    }
                }

                // Escape pour fermer la sidebar mobile
                if (e.key === 'Escape') {
                    document.querySelector('.sidebar')?.classList.remove('mobile-open');
                }
            });
        </script>
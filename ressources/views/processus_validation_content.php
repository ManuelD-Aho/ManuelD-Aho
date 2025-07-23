<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commission de Validation | Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'accent': '#6366f1',
                        'accent-dark': '#4f46e5',
                        'success': '#10b981',
                        'warning': '#f59e0b',
                        'danger': '#ef4444',
                        'dark': '#1f2937',
                        'light': '#f8fafc',
                    },
                    fontFamily: {
                        'poppins': ['Poppins', 'sans-serif']
                    },
                    animation: {
                        'slide-up': 'slideUp 0.5s ease-out',
                        'fade-in': 'fadeIn 0.6s ease-out',
                        'bounce-gentle': 'bounceGentle 2s infinite',
                    },
                    keyframes: {
                        slideUp: {
                            '0%': {
                                transform: 'translateY(30px)',
                                opacity: '0'
                            },
                            '100%': {
                                transform: 'translateY(0)',
                                opacity: '1'
                            }
                        },
                        fadeIn: {
                            '0%': {
                                opacity: '0'
                            },
                            '100%': {
                                opacity: '1'
                            }
                        },
                        bounceGentle: {
                            '0%, 100%': {
                                transform: 'translateY(0)'
                            },
                            '50%': {
                                transform: 'translateY(-5px)'
                            }
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .report-card {
            background: white;
            border-left: 4px solid transparent;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .report-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--card-color), var(--card-color-light));
        }

        .report-card[data-status="en_cours"] {
            --card-color: #f59e0b;
            --card-color-light: #fbbf24;
        }

        .report-card[data-status="valide"] {
            --card-color: #10b981;
            --card-color-light: #34d399;
        }

        .report-card[data-status="rejete"] {
            --card-color: #ef4444;
            --card-color-light: #f87171;
        }

        .report-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        .status-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 8px;
            animation: pulse 2s infinite;
        }

        .status-dot.en_cours {
            background-color: #f59e0b;
        }

        .status-dot.valide {
            background-color: #10b981;
        }

        .status-dot.rejete {
            background-color: #ef4444;
        }

        .floating-action {
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 1000;
        }

        .progress-ring {
            transform: rotate(-90deg);
        }

        .progress-ring-circle {
            transition: stroke-dashoffset 0.35s;
            transform: rotate(0deg);
            transform-origin: 50% 50%;
        }
    </style>
</head>

<body class="font-poppins bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500">

    <!-- Header Section -->
    <div class="header bg-white rounded-3xl p-8 lg:p-12 mb-8 shadow-xl relative overflow-hidden">
        <div class="flex items-center gap-6 md:gap-8 flex-col md:flex-row text-center md:text-left">
            <div class="header-icon bg-gradient-to-br from-primary to-primary-dark text-white w-20 h-20 md:w-24 md:h-24 rounded-2xl flex items-center justify-center text-4xl md:text-5xl shadow-lg">
                <i class="fas fa-clipboard-check"></i>
            </div>
            <div class="header-text">
                <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 mb-2 tracking-tight">Processus de validation</h1>
                <p class="text-lg text-gray-600 font-normal">Passez en revu les rapports traités et/ou en cours de traitements par les autres membres de la commission</p>
            </div>
        </div>
    </div>

    <!-- Stats Overview -->
    <section class="container mx-auto px-6 py-8 -mt-8 relative z-20">
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
    </section>

    <!-- Filters Section -->
    <section class="container mx-auto px-6 py-8">
        <div class="bg-white rounded-3xl shadow-2xl p-8 mb-8">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-accent rounded-full flex items-center justify-center">
                        <i class="fas fa-search text-white"></i>
                    </div>
                    <h2 class="text-2xl font-semibold text-gray-800">Recherche & Filtres</h2>
                </div>
                <button onclick="resetFilters()" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-xl transition-colors duration-200 flex items-center space-x-2">
                    <i class="fas fa-refresh text-gray-600"></i>
                    <span class="text-gray-600 font-medium">Reset</span>
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="relative">
                    <input type="text" id="searchFilter" placeholder="Rechercher..." onkeyup="filterReports()"
                        class="w-full pl-12 pr-4 py-4 border-2 border-gray-200 rounded-2xl focus:border-accent focus:outline-none transition-colors duration-200">
                    <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                </div>
                <div class="relative">
                    <select id="statusFilter" onchange="filterReports()"
                        class="w-full pl-12 pr-4 py-4 border-2 border-gray-200 rounded-2xl focus:border-accent focus:outline-none transition-colors duration-200 appearance-none bg-white">
                        <option value="">Tous les statuts</option>
                        <option value="en_cours">En cours d'évaluation</option>
                        <option value="valide">Validé</option>
                        <option value="rejete">Rejeté</option>
                    </select>
                    <i class="fas fa-flag absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                </div>
                <div class="relative">
                    <select id="memberFilter" onchange="filterReports()"
                        class="w-full pl-12 pr-4 py-4 border-2 border-gray-200 rounded-2xl focus:border-accent focus:outline-none transition-colors duration-200 appearance-none bg-white">
                        <option value="">Tous les membres</option>
                        <option value="Dr. Kouassi">Dr. Kouassi</option>
                        <option value="Dr. Koné">Dr. Koné</option>
                        <option value="Pr. Assan">Pr. Assan</option>
                        <option value="Dr. Bamba">Dr. Bamba</option>
                    </select>
                    <i class="fas fa-user-tie absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                </div>
            </div>
        </div>
    </section>

    <!-- Reports Grid -->
    <section class="container mx-auto px-6 pb-16">
        <div class="flex items-center justify-between mb-8">
            <h3 class="text-2xl font-bold  flex items-center">
                <i class="fas fa-folder-open mr-3 text-yellow-300"></i>
                Rapports de Validation
            </h3>
            <div class="bg-white bg-opacity-20 px-4 py-2 rounded-xl text-white">
                <span id="reportCount" class="font-medium">12 rapports</span>
            </div>
        </div>

        <div id="reportList" class="space-y-6">
            <!-- Rapport 1 - En cours -->
            <div class="report-card rounded-3xl shadow-xl p-8 animate-slide-up" data-status="en_cours" data-title="intelligence artificielle dans le diagnostic médical">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-6">
                    <div class="flex-1 mb-4 lg:mb-0">
                        <div class="flex items-start mb-3">
                            <span class="status-dot en_cours"></span>
                            <h3 class="text-2xl font-bold text-gray-800 leading-tight">
                                Intelligence Artificielle dans le Diagnostic Médical
                            </h3>
                        </div>
                        <div class="flex flex-wrap gap-4 text-sm text-gray-600">
                            <span class="flex items-center">
                                <i class="fas fa-user-graduate mr-2 text-accent"></i>
                                <strong data-user>Marie Lambert</strong>
                            </span>
                            <span class="flex items-center">
                                <i class="fas fa-chalkboard-teacher mr-2 text-accent"></i>
                                <strong data-supervisor>Dr. Martin</strong>
                            </span>
                            <span class="flex items-center">
                                <i class="fas fa-calendar mr-2 text-accent"></i>
                                <strong>20/05/2025</strong>
                            </span>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="bg-warning bg-opacity-10 px-4 py-2 rounded-xl">
                            <span class="text-warning font-semibold">En évaluation</span>
                        </div>
                        <div class="flex items-center">
                            <svg class="progress-ring w-16 h-16" viewBox="0 0 36 36">
                                <path class="progress-ring-circle" stroke="#f59e0b" stroke-width="3" fill="transparent" stroke-dasharray="50, 100" stroke-linecap="round" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                                <text x="18" y="20.35" class="text-xs font-bold" fill="#f59e0b" text-anchor="middle">2/4</text>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Evaluations -->
                <div class="bg-gray-50 rounded-2xl p-6 mb-6">
                    <h4 class="text-lg font-semibold mb-4 flex items-center">
                        <i class="fas fa-users mr-2 text-accent"></i>
                        Évaluations
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-white p-4 rounded-xl border-l-4 border-green-400">
                            <div class="flex items-center justify-between mb-2">
                                <span class="font-semibold">Dr. Kouassi</span>
                                <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">✓ Validé</span>
                            </div>
                            <p class="text-sm text-gray-600 italic">"Excellent travail, méthodologie rigoureuse..."</p>
                        </div>
                        <div class="bg-white p-4 rounded-xl border-l-4 border-green-400">
                            <div class="flex items-center justify-between mb-2">
                                <span class="font-semibold">Dr. Koné</span>
                                <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">✓ Validé</span>
                            </div>
                            <p class="text-sm text-gray-600 italic">"Innovation intéressante dans l'application..."</p>
                        </div>
                        <div class="bg-white p-4 rounded-xl border-l-4 border-gray-300">
                            <div class="flex items-center justify-between mb-2">
                                <span class="font-semibold">Pr. Assan</span>
                                <span class="px-2 py-1 bg-gray-100 text-gray-600 rounded-full text-xs">⏳ En attente</span>
                            </div>
                            <p class="text-sm text-gray-400">Évaluation en cours...</p>
                        </div>
                        <div class="bg-white p-4 rounded-xl border-l-4 border-gray-300">
                            <div class="flex items-center justify-between mb-2">
                                <span class="font-semibold">Dr. Bamba</span>
                                <span class="px-2 py-1 bg-gray-100 text-gray-600 rounded-full text-xs">⏳ En attente</span>
                            </div>
                            <p class="text-sm text-gray-400">Évaluation en cours...</p>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex flex-wrap gap-3 justify-end">
                    <button onclick="viewReport(1)" class="px-6 py-3 bg-accent text-white rounded-xl hover:bg-accent-dark transition-colors duration-200 flex items-center space-x-2">
                        <i class="fas fa-eye"></i>
                        <span>Consulter</span>
                    </button>
                </div>
            </div>

            <!-- Rapport 2 - Validé -->
            <div class="report-card rounded-3xl shadow-xl p-8 animate-slide-up" data-status="valide" data-title="système de gestion des ressources humaines" style="animation-delay: 0.1s;">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-6">
                    <div class="flex-1 mb-4 lg:mb-0">
                        <div class="flex items-start mb-3">
                            <span class="status-dot valide"></span>
                            <h3 class="text-2xl font-bold text-gray-800 leading-tight">
                                Système de Gestion des Ressources Humaines
                            </h3>
                        </div>
                        <div class="flex flex-wrap gap-4 text-sm text-gray-600">
                            <span class="flex items-center">
                                <i class="fas fa-user-graduate mr-2 text-accent"></i>
                                <strong data-user>Jean Dupont</strong>
                            </span>
                            <span class="flex items-center">
                                <i class="fas fa-chalkboard-teacher mr-2 text-accent"></i>
                                <strong data-supervisor>Dr. Dubois</strong>
                            </span>
                            <span class="flex items-center">
                                <i class="fas fa-calendar mr-2 text-accent"></i>
                                <strong>18/05/2025</strong>
                            </span>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="bg-success bg-opacity-10 px-4 py-2 rounded-xl">
                            <span class="text-success font-semibold">Validé</span>
                        </div>
                        <div class="flex items-center">
                            <svg class="progress-ring w-16 h-16" viewBox="0 0 36 36">
                                <path class="progress-ring-circle" stroke="#10b981" stroke-width="3" fill="transparent" stroke-dasharray="100, 100" stroke-linecap="round" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                                <text x="18" y="20.35" class="text-xs font-bold" fill="#10b981" text-anchor="middle">4/4</text>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex flex-wrap gap-3 justify-end">
                    <button onclick="viewReport(2)" class="px-6 py-3 bg-accent text-white rounded-xl hover:bg-accent-dark transition-colors duration-200 flex items-center space-x-2">
                        <i class="fas fa-eye"></i>
                        <span>Consulter</span>
                    </button>
                    <button onclick="makeFinalDecision(2)" class="px-6 py-3 bg-success text-white rounded-xl hover:bg-green-600 transition-colors duration-200 flex items-center space-x-2">
                        <i class="fas fa-certificate"></i>
                        <span>Verdict</span>
                    </button>
                </div>
            </div>

            <!-- Rapport 3 - Rejeté -->
            <div class="report-card rounded-3xl shadow-xl p-8 animate-slide-up" data-status="rejete" data-title="application mobile de commerce électronique" style="animation-delay: 0.2s;">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-6">
                    <div class="flex-1 mb-4 lg:mb-0">
                        <div class="flex items-start mb-3">
                            <span class="status-dot rejete"></span>
                            <h3 class="text-2xl font-bold text-gray-800 leading-tight">
                                Application Mobile de Commerce Électronique
                            </h3>
                        </div>
                        <div class="flex flex-wrap gap-4 text-sm text-gray-600">
                            <span class="flex items-center">
                                <i class="fas fa-user-graduate mr-2 text-accent"></i>
                                <strong data-user>Léa Garnier</strong>
                            </span>
                            <span class="flex items-center">
                                <i class="fas fa-chalkboard-teacher mr-2 text-accent"></i>
                                <strong data-supervisor>Pr. Diallo</strong>
                            </span>
                            <span class="flex items-center">
                                <i class="fas fa-calendar mr-2 text-accent"></i>
                                <strong>25/05/2025</strong>
                            </span>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="bg-danger bg-opacity-10 px-4 py-2 rounded-xl">
                            <span class="text-danger font-semibold">Rejeté</span>
                        </div>
                        <div class="flex items-center">
                            <svg class="progress-ring w-16 h-16" viewBox="0 0 36 36">
                                <path class="progress-ring-circle" stroke="#ef4444" stroke-width="3" fill="transparent" stroke-dasharray="0, 100" stroke-linecap="round" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                                <text x="18" y="20.35" class="text-xs font-bold" fill="#ef4444" text-anchor="middle">✕</text>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex flex-wrap gap-3 justify-end">
                    <button onclick="viewReport(3)" class="px-6 py-3 bg-accent text-white rounded-xl hover:bg-accent-dark transition-colors duration-200 flex items-center space-x-2">
                        <i class="fas fa-eye"></i>
                        <span>Consulter</span>
                    </button>
                    <button onclick="requestCorrections(3)" class="px-6 py-3 bg-danger text-white rounded-xl hover:bg-red-600 transition-colors duration-200 flex items-center space-x-2">
                        <i class="fas fa-edit"></i>
                        <span>Corrections</span>
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Floating Action Button -->
    <div class="floating-action">
        <button class="w-14 h-14 bg-accent hover:bg-accent-dark text-white rounded-full shadow-2xl transition-all duration-200 transform hover:scale-110 flex items-center justify-center">
            <i class="fas fa-plus text-xl"></i>
        </button>
    </div>

    <script>
        function filterReports() {
            const statusFilter = document.getElementById('statusFilter').value.toLowerCase();
            const memberFilter = document.getElementById('memberFilter').value.toLowerCase();
            const searchFilter = document.getElementById('searchFilter').value.toLowerCase();
            const reportItems = document.querySelectorAll('.report-card');
            let visibleReportsCount = 0;

            reportItems.forEach(item => {
                const itemStatus = item.getAttribute('data-status');
                const itemTitle = item.getAttribute('data-title');
                const studentEl = item.querySelector('[data-user]');
                const supervisorEl = item.querySelector('[data-supervisor]');

                const itemStudent = studentEl ? studentEl.textContent.toLowerCase() : '';
                const itemSupervisor = supervisorEl ? supervisorEl.textContent.toLowerCase() : '';

                const statusMatch = statusFilter === '' || itemStatus === statusFilter;
                const memberMatch = memberFilter === '' || item.textContent.toLowerCase().includes(memberFilter);
                const searchMatch = searchFilter === '' ||
                    itemTitle.includes(searchFilter) ||
                    itemStudent.includes(searchFilter) ||
                    itemSupervisor.includes(searchFilter);

                if (statusMatch && memberMatch && searchMatch) {
                    item.style.display = 'block';
                    visibleReportsCount++;
                } else {
                    item.style.display = 'none';
                }
            });

            document.getElementById('reportCount').textContent = `${visibleReportsCount} rapports`;
        }

        function resetFilters() {
            document.getElementById('statusFilter').value = '';
            document.getElementById('memberFilter').value = '';
            document.getElementById('searchFilter').value = '';
            filterReports();
        }

        function viewReport(id) {
            alert(`Consultation du rapport ID: ${id}`);
        }

        function makeFinalDecision(id) {
            alert(`Décision finale pour le rapport ID: ${id}`);
        }

        function requestCorrections(id) {
            alert(`Demande de corrections pour le rapport ID: ${id}`);
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            filterReports();
        });
    </script>
</body>

</html>
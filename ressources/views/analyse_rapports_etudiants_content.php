<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analyse des Rapports | Mr. Diarra</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .sidebar-hover:hover {
            background-color: #ecfdf5;
            border-left: 4px solid #10b981;
        }
        .fade-in {
            animation: fadeIn 0.3s ease-in;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .stat-card {
            transition: all 0.3s ease;
        }
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        .chart-container {
            position: relative;
            height: 300px;
        }
        .metric-value {
            font-size: 2.5rem;
            font-weight: 700;
            background: linear-gradient(135deg, #10b981, #059669);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .trend-up { color: #10b981; }
        .trend-down { color: #ef4444; }
        .trend-stable { color: #6b7280; }
        .report-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
        }
        .green-theme {
            --primary-color: #10b981;
            --primary-light: #d1fae5;
            --primary-dark: #059669;
        }
    </style>
</head>
<body class="font-sans antialiased bg-gray-50">
<div class="flex h-screen overflow-hidden">
    <!-- Main content area -->
    <div class="flex-1 overflow-y-auto bg-gray-50">
        <div class="max-w-7xl mx-auto p-6">
            <!-- Header -->
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-2xl font-bold text-gray-800">
                    <i class="fas fa-chart-pie text-green-600 mr-2"></i>
                    Analyse des Rapports Étudiant
                </h1>
                <div class="flex space-x-3">
                    <div class="relative">
                        <select class="appearance-none bg-white border border-gray-300 rounded-md pl-3 pr-8 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            <option>Tous les rapports</option>
                            <option>Validés</option>
                            <option>En attente</option>
                            <option>À corriger</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                            <i class="fas fa-chevron-down text-xs"></i>
                        </div>
                    </div>
                    <button class="px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700 flex items-center">
                        <i class="fas fa-download mr-2"></i>
                        Exporter
                    </button>
                </div>
            </div>

            <!-- KPI Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="stat-card bg-white rounded-lg shadow p-6 fade-in">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Rapports analysés</p>
                            <p class="metric-value">42</p>
                            <div class="flex items-center mt-2">
                                <i class="fas fa-arrow-up text-sm trend-up mr-1"></i>
                                <span class="text-sm text-green-600">+15% ce mois</span>
                            </div>
                        </div>
                        <div class="p-3 rounded-full bg-green-100">
                            <i class="fas fa-file-alt text-green-600 text-2xl"></i>
                        </div>
                    </div>
                </div>

                <div class="stat-card bg-white rounded-lg shadow p-6 fade-in">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Taux de validation</p>
                            <p class="metric-value">78%</p>
                            <div class="flex items-center mt-2">
                                <i class="fas fa-arrow-up text-sm trend-up mr-1"></i>
                                <span class="text-sm text-green-600">+5% ce mois</span>
                            </div>
                        </div>
                        <div class="p-3 rounded-full bg-blue-100">
                            <i class="fas fa-check-circle text-blue-600 text-2xl"></i>
                        </div>
                    </div>
                </div>

                <div class="stat-card bg-white rounded-lg shadow p-6 fade-in">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Moyenne des notes</p>
                            <p class="metric-value">14.2/20</p>
                            <div class="flex items-center mt-2">
                                <i class="fas fa-arrow-up text-sm trend-up mr-1"></i>
                                <span class="text-sm text-green-600">+0.8 ce mois</span>
                            </div>
                        </div>
                        <div class="p-3 rounded-full bg-purple-100">
                            <i class="fas fa-star text-purple-600 text-2xl"></i>
                        </div>
                    </div>
                </div>

                <div class="stat-card bg-white rounded-lg shadow p-6 fade-in">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Retards</p>
                            <p class="metric-value">3</p>
                            <div class="flex items-center mt-2">
                                <i class="fas fa-arrow-down text-sm trend-down mr-1"></i>
                                <span class="text-sm text-red-600">-2 ce mois</span>
                            </div>
                        </div>
                        <div class="p-3 rounded-full bg-red-100">
                            <i class="fas fa-clock text-red-600 text-2xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Row -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Quality Evolution -->
                <div class="bg-white rounded-lg shadow p-6 fade-in">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">
                            <i class="fas fa-chart-line text-green-600 mr-2"></i>
                            Évolution de la qualité
                        </h3>
                        <div class="flex items-center space-x-2 text-sm">
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-green-600 rounded-full mr-1"></div>
                                <span>2025</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-gray-300 rounded-full mr-1"></div>
                                <span>2024</span>
                            </div>
                        </div>
                    </div>
                    <div class="chart-container">
                        <canvas id="qualityChart"></canvas>
                    </div>
                </div>

                <!-- Categories Distribution -->
                <div class="bg-white rounded-lg shadow p-6 fade-in">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">
                            <i class="fas fa-chart-pie text-blue-600 mr-2"></i>
                            Répartition par domaine
                        </h3>
                        <button onclick="refreshCharts()" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                    </div>
                    <div class="chart-container">
                        <canvas id="categoriesChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Detailed Analysis -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                <!-- Performance by Criteria -->
                <div class="bg-white rounded-lg shadow p-6 fade-in">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">
                        <i class="fas fa-list-check text-green-600 mr-2"></i>
                        Performance par critère
                    </h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-green-600 rounded-full mr-3"></div>
                                <span class="text-sm font-medium">Méthodologie</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <div class="w-20 bg-gray-200 rounded-full h-2">
                                    <div class="bg-green-600 h-2 rounded-full" style="width: 85%"></div>
                                </div>
                                <span class="text-sm font-semibold">8.5/10</span>
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-blue-600 rounded-full mr-3"></div>
                                <span class="text-sm font-medium">Rigueur scientifique</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <div class="w-20 bg-gray-200 rounded-full h-2">
                                    <div class="bg-blue-600 h-2 rounded-full" style="width: 78%"></div>
                                </div>
                                <span class="text-sm font-semibold">7.8/10</span>
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-purple-600 rounded-full mr-3"></div>
                                <span class="text-sm font-medium">Clarté de rédaction</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <div class="w-20 bg-gray-200 rounded-full h-2">
                                    <div class="bg-purple-600 h-2 rounded-full" style="width: 82%"></div>
                                </div>
                                <span class="text-sm font-semibold">8.2/10</span>
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-yellow-600 rounded-full mr-3"></div>
                                <span class="text-sm font-medium">Originalité</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <div class="w-20 bg-gray-200 rounded-full h-2">
                                    <div class="bg-yellow-600 h-2 rounded-full" style="width: 65%"></div>
                                </div>
                                <span class="text-sm font-semibold">6.5/10</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Evaluations -->
                <div class="bg-white rounded-lg shadow p-6 fade-in">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">
                        <i class="fas fa-history text-green-600 mr-2"></i>
                        Évaluations récentes
                    </h3>
                    <div class="space-y-3">
                        <div class="flex items-center space-x-3 p-2 hover:bg-gray-50 rounded-lg">
                            <div class="p-2 bg-green-100 rounded-full">
                                <i class="fas fa-check text-green-600 text-xs"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium">Rapport validé (15.5/20)</p>
                                <p class="text-xs text-gray-500">IA Médicale - Marie L.</p>
                            </div>
                            <span class="text-xs text-gray-400">2h</span>
                        </div>
                        <div class="flex items-center space-x-3 p-2 hover:bg-gray-50 rounded-lg">
                            <div class="p-2 bg-blue-100 rounded-full">
                                <i class="fas fa-edit text-blue-600 text-xs"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium">Évaluation en cours</p>
                                <p class="text-xs text-gray-500">Blockchain - Jean D.</p>
                            </div>
                            <span class="text-xs text-gray-400">4h</span>
                        </div>
                        <div class="flex items-center space-x-3 p-2 hover:bg-gray-50 rounded-lg">
                            <div class="p-2 bg-orange-100 rounded-full">
                                <i class="fas fa-redo text-orange-600 text-xs"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium">Corrections demandées</p>
                                <p class="text-xs text-gray-500">Réseaux - Thomas M.</p>
                            </div>
                            <span class="text-xs text-gray-400">1j</span>
                        </div>
                        <div class="flex items-center space-x-3 p-2 hover:bg-gray-50 rounded-lg">
                            <div class="p-2 bg-purple-100 rounded-full">
                                <i class="fas fa-plus text-purple-600 text-xs"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium">Nouveau rapport</p>
                                <p class="text-xs text-gray-500">Finance - Sophie D.</p>
                            </div>
                            <span class="text-xs text-gray-400">2j</span>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-lg shadow p-6 fade-in">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">
                        <i class="fas fa-bolt text-green-600 mr-2"></i>
                        Actions rapides
                    </h3>
                    <div class="space-y-3">
                        <button onclick="generateReport()"
                                class="w-full flex items-center justify-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700">
                            <i class="fas fa-chart-bar mr-2"></i>
                            Générer rapport analytique
                        </button>
                        <button onclick="exportData()"
                                class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-50">
                            <i class="fas fa-file-excel mr-2"></i>
                            Exporter en Excel
                        </button>
                        <button onclick="comparePeriods()"
                                class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-50">
                            <i class="fas fa-not-equal mr-2"></i>
                            Comparer périodes
                        </button>
                        <button onclick="settings()"
                                class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-50">
                            <i class="fas fa-sliders-h mr-2"></i>
                            Paramètres d'analyse
                        </button>
                    </div>
                </div>
            </div>

            <!-- Reports Table -->
            <div class="bg-white rounded-lg shadow p-6 fade-in">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">
                        <i class="fas fa-table text-green-600 mr-2"></i>
                        Détails des rapports
                    </h3>
                    <div class="flex items-center space-x-2">
                        <input type="text" placeholder="Rechercher..."
                               class="px-3 py-1 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <button class="px-3 py-1 text-sm bg-green-600 text-white rounded-md hover:bg-green-700">
                            <i class="fas fa-filter"></i>
                        </button>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Étudiant</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Domaine</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Note</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 rounded-full bg-green-100 flex items-center justify-center">
                                        <span class="text-green-600 font-medium">ML</span>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">Marie Lambert</div>
                                        <div class="text-sm text-gray-500">M2 IA</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">IA Médicale</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-green-600">15.5/20</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Validé</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">23/05/2025</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button class="text-green-600 hover:text-green-900 mr-3">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="text-blue-600 hover:text-blue-900">
                                    <i class="fas fa-download"></i>
                                </button>
                            </td>
                        </tr>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                        <span class="text-blue-600 font-medium">JD</span>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">Jean Dupont</div>
                                        <div class="text-sm text-gray-500">M2 Blockchain</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Blockchain Santé</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-blue-600">-</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">En cours</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">22/05/2025</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button class="text-green-600 hover:text-green-900 mr-3">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="text-blue-600 hover:text-blue-900">
                                    <i class="fas fa-comment"></i>
                                </button>
                            </td>
                        </tr>
                        <tr class="hover:bg-gray-50">

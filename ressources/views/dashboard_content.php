<?php

$stat_etudiants = $GLOBALS['stats_etudiants'] ?? [
    'total' => 0,
    'actifs' => 0,
    'inactifs' => 0,
    'taux_activite' => 0
];

$stat_enseignants = $GLOBALS['stats_enseignants'] ?? [
    'total' => 0,
    'actifs' => 0,
    'inactifs' => 0,
    'taux_activite' => 0
];
$stat_personnel = $GLOBALS['stats_personnel'] ?? [
    'total' => 0,
    'actifs' => 0,
    'inactifs' => 0,
    'taux_activite' => 0
];
$stat_utilisateurs = $GLOBALS['stats_utilisateurs'] ?? [
    'total' => 0,
    'actifs' => 0,
    'inactifs' => 0,
    'taux_activite' => 0
];

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Administrateur | Univalid</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                        'warning': '#f59e0b',
                        'danger': '#ef4444',
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

        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 4px 16px rgba(15, 23, 42, 0.08);
            transition: all 0.3s ease;
            border: 1px solid #e2e8f0;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
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

        .stat-card:hover::before {
            opacity: 1;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 32px rgba(15, 23, 42, 0.12);
        }

        .btn-tab {
            padding: 8px 16px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 12px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            color: white;
        }

        .btn-tab.active {
            background: linear-gradient(135deg, #3457cb 0%, #24407a 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(52, 87, 203, 0.3);
        }

        

        .btn-tab:not(.active):hover {
            background: rgba(52, 87, 203, 0.2);
            transform: translateY(-1px);
        }

        .calendar-day {
            aspect-ratio: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.875rem;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.2s ease;
            font-weight: 500;
        }

        .calendar-day:hover {
            background: rgba(52, 87, 203, 0.1);
            transform: scale(1.1);
        }

        .calendar-day.today {
            background: linear-gradient(135deg, #3457cb 0%, #24407a 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(52, 87, 203, 0.3);
        }

        .calendar-day.selected {
            background: linear-gradient(135deg, #36865a 0%, #59bf3d 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(54, 134, 90, 0.3);
        }

        .calendar-day.other-month {
            color: #9ca3af;
            opacity: 0.5;
        }

        .calendar-header {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 0.5rem;
            margin-bottom: 16px;
        }

        .calendar-header div {
            text-align: center;
            font-size: 0.75rem;
            color: #3457cb;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 0.5rem;
        }

        .stats-table {
            border-radius: 12px;
            overflow: hidden;
        }

        .stats-table th {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            color: #3457cb;
            font-weight: 700;
            padding: 16px;
            text-align: left;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stats-table td {
            padding: 16px;
            border-bottom: 1px solid #f1f5f9;
            font-weight: 500;
        }

        .stats-table tr:hover {
            background: rgba(52, 87, 203, 0.02);
        }

        .header-gradient {
            background: linear-gradient(135deg, #24407a 0%, #3457cb 100%);
        }

        .chart-container {
            position: relative;
            height: 320px;
            padding: 16px;
        }

        .nav-button {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            background: rgba(52, 87, 203, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #3457cb;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .nav-button:hover {
            background: rgba(52, 87, 203, 0.2);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(52, 87, 203, 0.2);
        }
    </style>
</head>

<body class="bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <!-- Header Section -->
        <div class="header bg-white rounded-3xl p-8 lg:p-12 mb-8 shadow-xl relative overflow-hidden animate-fade-in-down">
            <div class="flex items-center gap-6 md:gap-8 flex-col md:flex-row text-center md:text-left">
                <div class="header-icon bg-gradient-to-br from-primary to-primary-light text-white w-20 h-20 md:w-24 md:h-24 rounded-2xl flex items-center justify-center text-4xl md:text-5xl shadow-lg">
                    <i class="fas fa-tachometer-alt"></i>
                </div>
                <div class="header-text">
                    <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 mb-2 tracking-tight">Dashboard Administrateur</h1>
                    <p class="text-lg text-gray-600 font-normal">Vue d'ensemble et suivi des performances système</p>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="stat-card animate-slide-in-right" style="animation-delay: 0.1s">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-primary/10 text-primary rounded-xl flex items-center justify-center text-2xl">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <div>
                        <h3 class="text-3xl font-bold text-primary mb-1"><?php echo $stat_etudiants['total']; ?></h3>
                        <p class="text-sm font-semibold text-gray-600">Étudiants</p>
                        <p class="text-xs text-green-600 font-medium">
                            <i class="fas fa-arrow-up mr-1"></i><?php echo $stat_etudiants['taux_activite']; ?>% actifs
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="stat-card animate-slide-in-right" style="animation-delay: 0.2s">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-secondary/10 text-secondary rounded-xl flex items-center justify-center text-2xl">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                    <div>
                        <h3 class="text-3xl font-bold text-secondary mb-1"><?php echo $stat_enseignants['total']; ?></h3>
                        <p class="text-sm font-semibold text-gray-600">Enseignants</p>
                        <p class="text-xs text-green-600 font-medium">
                            <i class="fas fa-arrow-up mr-1"></i><?php echo $stat_enseignants['taux_activite']; ?>% actifs
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="stat-card animate-slide-in-right" style="animation-delay: 0.3s">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-warning/10 text-warning rounded-xl flex items-center justify-center text-2xl">
                        <i class="fas fa-users-cog"></i>
                    </div>
                    <div>
                        <h3 class="text-3xl font-bold text-warning mb-1"><?php echo $stat_personnel['total']; ?></h3>
                        <p class="text-sm font-semibold text-gray-600">Personnel Admin</p>
                        <p class="text-xs text-green-600 font-medium">
                            <i class="fas fa-arrow-up mr-1"></i><?php echo $stat_personnel['taux_activite']; ?>% actifs
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="stat-card animate-slide-in-right" style="animation-delay: 0.4s">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-purple-500/10 text-purple-600 rounded-xl flex items-center justify-center text-2xl">
                        <i class="fas fa-users"></i>
                    </div>
                    <div>
                        <h3 class="text-3xl font-bold text-purple-600 mb-1"><?php echo $stat_utilisateurs['total']; ?></h3>
                        <p class="text-sm font-semibold text-gray-600">Total Utilisateurs</p>
                        <p class="text-xs text-green-600 font-medium">
                            <i class="fas fa-arrow-up mr-1"></i><?php echo $stat_utilisateurs['taux_activite']; ?>% actifs
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column - Charts and Table -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Evolution Chart -->
                <div class="card animate-scale-in">
                    <div class="header-gradient px-8 py-6">
                        <div class="flex justify-between items-center">
                            <div>
                                <h2 class="text-2xl font-bold text-white">Répartition des Utilisateurs</h2>
                                <p class="text-blue-100">par type utilisateur</p>
                            </div>
                            <div class="bg-white/10 rounded-xl p-2">
                                <i class="fas fa-chart-pie text-white text-xl"></i>
                            </div>
                        </div>
                    </div>
                    <div class="chart-container">
                        <canvas id="evolutionChart"></canvas>
                    </div>
                </div>

                <!-- Statistics Table -->
                <div class="card animate-scale-in" style="animation-delay: 0.2s">
                    <div class="header-gradient px-8 py-6">
                        <h2 class="text-2xl font-bold text-white flex items-center">
                            <i class="fas fa-chart-bar mr-3"></i>
                            Statistiques Détaillées
                        </h2>
                    </div>
                    <div class="p-8">
                        <div class="overflow-x-auto">
                            <table class="stats-table w-full">
                                <thead>
                                    <tr>
                                        <th>📊 Catégorie</th>
                                        <th>👥 Total</th>
                                        <th>✅ Actifs</th>
                                        <th>❌ Inactifs</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <div class="flex items-center">
                                                <div class="w-3 h-3 bg-primary rounded-full mr-3"></div>
                                                <span class="font-semibold text-primary">Étudiants</span>
                                            </div>
                                        </td>
                                        <td class="font-bold"><?php echo $stat_etudiants['total']; ?></td>
                                        <td><span class="text-green-600 font-semibold"><?php echo $stat_etudiants['actifs']; ?></span></td>
                                        <td><span class="text-red-500 font-semibold"><?php echo $stat_etudiants['inactifs']; ?></span></td>
                                        
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="flex items-center">
                                                <div class="w-3 h-3 bg-secondary rounded-full mr-3"></div>
                                                <span class="font-semibold text-secondary">Enseignants</span>
                                            </div>
                                        </td>
                                        <td class="font-bold"><?php echo $stat_enseignants['total']; ?></td>
                                        <td><span class="text-green-600 font-semibold"><?php echo $stat_enseignants['actifs']; ?></span></td>
                                        <td><span class="text-red-500 font-semibold"><?php echo $stat_enseignants['inactifs']; ?></span></td>
                                       
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="flex items-center">
                                                <div class="w-3 h-3 bg-warning rounded-full mr-3"></div>
                                                <span class="font-semibold text-warning">Personnel Admin</span>
                                            </div>
                                        </td>
                                        <td class="font-bold"><?php echo $stat_personnel['total']; ?></td>
                                        <td><span class="text-green-600 font-semibold"><?php echo $stat_personnel['actifs']; ?></span></td>
                                        <td><span class="text-red-500 font-semibold"><?php echo $stat_personnel['inactifs']; ?></span></td>
                                        
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="flex items-center">
                                                <div class="w-3 h-3 bg-purple-600 rounded-full mr-3"></div>
                                                <span class="font-semibold text-purple-600">Total Utilisateurs</span>
                                            </div>
                                        </td>
                                        <td class="font-bold"><?php echo $stat_utilisateurs['total']; ?></td>
                                        <td><span class="text-green-600 font-semibold"><?php echo $stat_utilisateurs['actifs']; ?></span></td>
                                        <td><span class="text-red-500 font-semibold"><?php echo $stat_utilisateurs['inactifs']; ?></span></td>
                                        
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Calendar -->
            <div class="lg:col-span-1">
                <div class="card animate-scale-in" style="animation-delay: 0.3s">
                    <div class="header-gradient px-8 py-6">
                        <div class="flex justify-between items-center">
                            <h2 class="text-2xl font-bold text-white flex items-center">
                                <i class="fas fa-calendar-alt mr-3"></i>
                                Calendrier
                            </h2>
                            <div class="flex space-x-2">
                                <button id="prevMonth" class="nav-button">
                                    <i class="fas fa-chevron-left"></i>
                                </button>
                                <button id="nextMonth" class="nav-button">
                                    <i class="fas fa-chevron-right"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-8">
                        <div class="text-center mb-6">
                            <h3 id="currentMonth" class="text-xl font-bold text-primary"></h3>
                        </div>

                        <div class="calendar-header">
                            <div>Lun</div>
                            <div>Mar</div>
                            <div>Mer</div>
                            <div>Jeu</div>
                            <div>Ven</div>
                            <div>Sam</div>
                            <div>Dim</div>
                        </div>
                        <div id="calendarDays" class="calendar-grid"></div>
                        
                        <div class="mt-8 p-4 bg-gradient-to-r from-primary/5 to-secondary/5 rounded-xl">
                            <h4 class="font-semibold text-primary mb-3 flex items-center">
                                <i class="fas fa-bell mr-2"></i>
                                Événements du jour
                            </h4>
                            <div class="space-y-2 text-sm">
                                <div class="flex items-center text-gray-600">
                                    <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                                    Aucun événement prévu
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Data for charts
        const chartData = {
            etudiants: {
                labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin'],
                data: [120, 150, 180, 200, 220, 250]
            },
            enseignants: {
                labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin'],
                data: [20, 25, 30, 35, 40, 45]
            },
            personnels: {
                labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin'],
                data: [15, 18, 22, 25, 28, 30]
            }
        };

        // User Distribution Doughnut Chart
        const evolutionCtx = document.getElementById('evolutionChart').getContext('2d');
        let evolutionChart = new Chart(evolutionCtx, {
            type: 'doughnut',
            data: {
                labels: ['Étudiants', 'Enseignants', 'Personnel Admin'],
                datasets: [{
                    data: [<?php echo $stat_etudiants['total']; ?>, <?php echo $stat_enseignants['total']; ?>, <?php echo $stat_personnel['total']; ?>],
                    backgroundColor: [
                        '#3457cb',
                        '#36865a', 
                        '#f59e0b'
                    ],
                    borderColor: [
                        '#24407a',
                        '#2d5a47',
                        '#d97706'
                    ],
                    borderWidth: 2,
                    hoverOffset: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '60%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: '#374151',
                            font: {
                                size: 14,
                                weight: '600'
                            },
                            padding: 20,
                            usePointStyle: true,
                            pointStyle: 'circle'
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.parsed;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((value / total) * 100).toFixed(1);
                                return `${label}: ${value} (${percentage}%)`;
                            }
                        },
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleFont: {
                            size: 14,
                            weight: 'bold'
                        },
                        bodyFont: {
                            size: 13
                        },
                        cornerRadius: 8,
                        displayColors: true
                    }
                },
                animation: {
                    animateRotate: true,
                    animateScale: true,
                    duration: 1000
                }
            }
        });

        // Chart hover effects
        evolutionChart.options.onHover = (event, activeElements) => {
            event.native.target.style.cursor = activeElements.length > 0 ? 'pointer' : 'default';
        };

        // Calendar functionality
        let currentDate = new Date();
        let selectedDate = new Date();

        function updateCalendar() {
            const year = currentDate.getFullYear();
            const month = currentDate.getMonth();

            const monthNames = ["Janvier", "Février", "Mars", "Avril", "Mai", "Juin", 
                              "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"];
            document.getElementById('currentMonth').textContent = `${monthNames[month]} ${year}`;

            const firstDay = new Date(year, month, 1);
            const lastDay = new Date(year, month + 1, 0);
            const startingDay = firstDay.getDay() || 7;
            const totalDays = lastDay.getDate();

            const prevMonthLastDay = new Date(year, month, 0).getDate();
            const prevMonthDays = Array.from({length: startingDay - 1}, (_, i) => prevMonthLastDay - i).reverse();

            const remainingDays = 42 - (prevMonthDays.length + totalDays);
            const nextMonthDays = Array.from({length: remainingDays}, (_, i) => i + 1);

            const allDays = [
                ...prevMonthDays.map(day => ({day, isCurrentMonth: false})),
                ...Array.from({length: totalDays}, (_, i) => ({day: i + 1, isCurrentMonth: true})),
                ...nextMonthDays.map(day => ({day, isCurrentMonth: false}))
            ];

            const calendarHTML = allDays.map(({day, isCurrentMonth}) => {
                const isToday = isCurrentMonth && day === new Date().getDate() &&
                    month === new Date().getMonth() && year === new Date().getFullYear();
                const isSelected = isCurrentMonth && day === selectedDate.getDate() &&
                    month === selectedDate.getMonth() && year === selectedDate.getFullYear();

                let classes = 'calendar-day';
                if (!isCurrentMonth) classes += ' other-month';
                if (isToday) classes += ' today';
                if (isSelected) classes += ' selected';

                return `<div class="${classes}" data-date="${year}-${month + 1}-${day}">${day}</div>`;
            }).join('');

            document.getElementById('calendarDays').innerHTML = calendarHTML;

            document.querySelectorAll('.calendar-day').forEach(day => {
                day.addEventListener('click', () => {
                    const [year, month, dayNum] = day.dataset.date.split('-').map(Number);
                    selectedDate = new Date(year, month - 1, dayNum);
                    updateCalendar();
                });
            });
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            updateCalendar();

            document.getElementById('prevMonth').addEventListener('click', () => {
                currentDate.setMonth(currentDate.getMonth() - 1);
                updateCalendar();
            });

            document.getElementById('nextMonth').addEventListener('click', () => {
                currentDate.setMonth(currentDate.getMonth() + 1);
                updateCalendar();
            });

            // Animation for stat cards
            const statCards = document.querySelectorAll('.stat-card');
            statCards.forEach((card, index) => {
                card.style.animationDelay = `${index * 0.1}s`;
            });
        });
    </script>
</body>

</html>
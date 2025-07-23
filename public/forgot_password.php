<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mot de Passe Oublié - UniValid</title>
    <link rel="shortcut icon" href="./images/logo.png" type="image/x-icon">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary': '#27A062', // Vert institutionnel
                        'primary-dark': '#0B3C32',
                        'secondary': '#2F53CD',
                        'secondary-light': '#60A5FA',
                        'accent': '#2F54CC', // Jaune/Or pour le contraste
                        'yellow-custom': '#FFD700',
                        'yellow-bright': '#FFEB3B'
                    },
                    animation: {
                        'float': 'float 3s ease-in-out infinite',
                        'float-delayed': 'float 3s ease-in-out 1.5s infinite',
                        'pulse-slow': 'pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                        'slide-in': 'slideIn 0.6s ease-out',
                        'bounce-in': 'bounceIn 0.8s ease-out',
                        'shake': 'shake 0.5s ease-in-out'
                    }
                }
            }
        }
    </script>
    <style>
        .hero-gradient {
            background: linear-gradient(135deg, #33A74F 0%, #375BCE 100%);
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-15px);
            }
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes bounceIn {
            0% {
                opacity: 0;
                transform: scale(0.3);
            }

            50% {
                opacity: 1;
                transform: scale(1.05);
            }

            70% {
                transform: scale(0.9);
            }

            100% {
                opacity: 1;
                transform: scale(1);
            }
        }

        @keyframes shake {

            0%,
            100% {
                transform: translateX(0);
            }

            25% {
                transform: translateX(-5px);
            }

            75% {
                transform: translateX(5px);
            }
        }

        .geometric-shape {
            transition: all 0.4s ease;
        }

        .geometric-shape:hover {
            transform: rotate(180deg) scale(1.2);
        }

        .input-group {
            position: relative;
        }

        .input-group input:focus+label,
        .input-group input:not(:placeholder-shown)+label {
            transform: translateY(-24px) scale(0.85);
            color: #000;
        }

        .input-group label {
            position: absolute;
            left: 16px;
            top: 16px;
            color: #6B7280;
            transition: all 0.2s ease;
            pointer-events: none;
            background: white;
            padding: 0 4px;
        }

        .form-container {
            backdrop-filter: blur(20px);
            background: rgba(255, 255, 255, 0.95);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .success-state {
            background: linear-gradient(135deg, #10B981, #059669);
        }
    </style>
</head>

<body class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-gray-100 font-sans overflow-hidden">

<!-- Background Geometric Elements -->
<div class="fixed inset-0 pointer-events-none">
    <div class="absolute -top-40 -right-40 w-96 h-96 bg-primary rounded-full opacity-20 animate-pulse-slow">
    </div>
    <div class="absolute top-20 left-20 w-8 h-8 bg-primary rounded-full animate-float"></div>
    <div class="absolute top-40 right-32 w-6 h-6 bg-accent rounded-full animate-float-delayed"></div>
    <div class="absolute bottom-32 left-16 geometric-shape">
        <div class="w-12 h-12 border-3 border-accent transform rotate-45"></div>
    </div>
    <div class="absolute bottom-20 right-20 geometric-shape">
        <div class="w-10 h-10 border-2 border-gray-300 transform rotate-45"></div>
    </div>
    <div class="absolute top-1/2 left-10 w-4 h-4 bg-accent rounded-full animate-pulse-slow"></div>
    <div class="absolute -bottom-20 -left-20 w-64 h-64 border-4 border-gray-200 transform rotate-45 opacity-30">
    </div>
</div>


<!-- Main Content -->
<div class="relative z-10 flex items-center justify-center min-h-screen py-20 px-6">
    <div class="w-full max-w-6xl grid lg:grid-cols-2 gap-12 items-center">

        <!-- Left Side - Illustration -->
        <div class="hidden lg:flex justify-center items-center relative">
            <div class="relative animate-bounce-in">
                <!-- Main illustration circle -->
                <div
                        class="w-96 h-96 hero-gradient rounded-full flex items-center justify-center relative overflow-hidden">
                    <!-- Email/Lock illustration -->
                    <div class="relative z-10">
                        <!-- Large envelope -->
                        <div class="w-32 h-24 bg-white rounded-lg mb-8 relative shadow-lg">
                            <!-- Envelope flap -->
                            <div class="absolute -top-2 left-0 w-full h-8 bg-gray-200 rounded-t-lg"></div>
                            <div
                                    class="absolute top-0 left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-16 border-r-16 border-b-12 border-transparent border-b-white">
                            </div>

                            <!-- Lock icon on envelope -->
                            <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
                                <div class="w-8 h-8 bg-accent-400 rounded-full flex items-center justify-center">
                                    <div class="w-4 h-4 bg-primary rounded-sm relative">
                                        <div
                                                class="absolute -top-1 left-1/2 transform -translate-x-1/2 w-2 h-2 border-2 border-primary rounded-full bg-transparent">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Flying email particles -->
                        <div class="absolute -top-4 -left-4 w-4 h-3 bg-white rounded-sm animate-float opacity-60">
                        </div>
                        <div
                                class="absolute -top-2 right-0 w-3 h-2 bg-white rounded-sm animate-float-delayed opacity-80">
                        </div>
                        <div
                                class="absolute -bottom-2 -right-2 w-5 h-4 bg-white rounded-sm animate-float opacity-70">
                        </div>
                    </div>

                    <!-- Decorative elements -->
                    <div class="absolute top-16 left-12 w-6 h-6 bg-white rounded-full opacity-60 animate-float">
                    </div>
                    <div
                            class="absolute bottom-24 right-20 w-8 h-8 bg-accent rounded-full opacity-40 animate-float-delayed">
                    </div>
                </div>

                <!-- Floating elements around illustration -->
                <div class="absolute -top-8 -right-8 w-16 h-16 bg-accent rounded-full animate-float"></div>
                <div
                        class="absolute -bottom-8 -left-8 w-20 h-20 border-4 border-primary transform rotate-45 animate-float-delayed">
                </div>
                <div class="absolute top-20 -left-12 w-8 h-8 bg-primary-400 rounded-full animate-pulse-slow"></div>
            </div>
        </div>

        <!-- Right Side - Reset Form -->
        <div class="animate-slide-in">
            <div class="form-container rounded-3xl p-8 lg:p-12 shadow-2xl max-w-md mx-auto" id="resetForm">
                <!-- Initial State -->
                <div id="initialState">
                    <!-- Header -->
                    <div class="text-center mb-8">
                        <div
                                class="w-16 h-16 bg-primary rounded-full mx-auto mb-4 flex items-center justify-center">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 15v2m0 0v2m0-2h2m-2 0h-2m8-6V9a8 8 0 10-16 0v2m16 0a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2v-6a2 2 0 012-2h12z">
                                </path>
                            </svg>
                        </div>
                        <h1 class="text-3xl font-bold mb-2 text-primary ">Mot de Passe Oublié ?</h1>
                        <p class="text-gray-600">Pas de problème ! Entrez votre email et nous vous enverrons un lien
                            de réinitialisation.</p>
                    </div>

                    <!-- Reset Form -->
                    <form id="passwordResetForm" class="space-y-6">
                        <!-- Email Input -->
                        <div class="input-group">
                            <input type="email" id="resetEmail" placeholder=" "
                                   class="w-full px-4 py-4 border-2 border-gray-200 rounded-xl focus:border-green-400 focus:outline-none transition-all duration-200 bg-white/50"
                                   required>
                            <label for="resetEmail">Adresse email</label>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit"
                                class="w-full bg-primary text-white py-4 rounded-xl font-semibold hover:bg-green-700 transition-all duration-300 hover:scale-105 transform hover:shadow-lg">
                            Envoyer le Lien de Réinitialisation
                        </button>
                    </form>

                    <!-- Back to Login -->
                    <div class="text-center mt-8">
                        <a href="page_connexion.php"
                           class="text-gray-600 hover:text-black flex items-center justify-center space-x-2 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            <span>Retour à la connexion</span>
                        </a>
                    </div>
                </div>

                <!-- Success State (Hidden initially) -->
                <div id="successState" class="hidden text-center">
                    <div
                            class="w-20 h-20 success-state rounded-full mx-auto mb-6 flex items-center justify-center animate-bounce-in">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>

                    <h2 class="text-2xl font-bold mb-4">Email Envoyé !</h2>
                    <p class="text-gray-600 mb-8">
                        Nous avons envoyé un lien de réinitialisation à votre adresse email.
                        Vérifiez votre boîte de réception et suivez les instructions.
                    </p>

                    <div class="space-y-4">
                        <button onclick="resendEmail()"
                                class="w-full bg-gray-100 text-gray-700 py-3 rounded-xl font-medium hover:bg-gray-200 transition-all duration-300">
                            Renvoyer l'Email
                        </button>

                        <a href="#"
                           class="block w-full bg-black text-white py-3 rounded-xl font-semibold text-center hover:bg-gray-800 transition-all duration-300">
                            Retour à la Connexion
                        </a>
                    </div>

                    <p class="text-sm text-gray-500 mt-6">
                        Vous ne recevez pas l'email ? Vérifiez vos spams ou
                        <a href="#" class="text-yellow-600 hover:text-yellow-700 font-medium">contactez le
                            support</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Form submission handling
    const form = document.getElementById('passwordResetForm');
    const submitBtn = form.querySelector('button[type="submit"]');
    const initialState = document.getElementById('initialState');
    const successState = document.getElementById('successState');
    const resetForm = document.getElementById('resetForm');

    form.addEventListener('submit', function(e) {
        e.preventDefault();

        const emailInput = document.getElementById('resetEmail');
        const email = emailInput.value;

        // Validate email
        if (!email || !isValidEmail(email)) {
            shakeForm();
            return;
        }

        // Loading state
        submitBtn.innerHTML = `
                <div class="flex items-center justify-center space-x-2">
                    <div class="w-4 h-4 border-2 border-black border-t-transparent rounded-full animate-spin"></div>
                    <span>Envoi en cours...</span>
                </div>
            `;

        submitBtn.disabled = true;

        // Simulate API call
        setTimeout(() => {
            showSuccessState();
        }, 2000);
    });

    function isValidEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }

    function shakeForm() {
        resetForm.classList.add('animate-shake');
        const emailInput = document.getElementById('resetEmail');
        emailInput.style.borderColor = '#EF4444';

        setTimeout(() => {
            resetForm.classList.remove('animate-shake');
            emailInput.style.borderColor = '';
        }, 500);
    }

    function showSuccessState() {
        initialState.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
        initialState.style.opacity = '0';
        initialState.style.transform = 'translateY(-20px)';

        setTimeout(() => {
            initialState.classList.add('hidden');
            successState.classList.remove('hidden');
            successState.style.opacity = '0';
            successState.style.transform = 'translateY(20px)';

            setTimeout(() => {
                successState.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                successState.style.opacity = '1';
                successState.style.transform = 'translateY(0)';
            }, 50);
        }, 300);
    }

    function resendEmail() {
        const resendBtn = event.target;
        resendBtn.innerHTML = `
                <div class="flex items-center justify-center space-x-2">
                    <div class="w-4 h-4 border-2 border-gray-600 border-t-transparent rounded-full animate-spin"></div>
                    <span>Renvoi...</span>
                </div>
            `;

        setTimeout(() => {
            resendBtn.innerHTML = 'Email Renvoyé !';
            resendBtn.classList.add('bg-green-100', 'text-green-700');

            setTimeout(() => {
                resendBtn.innerHTML = 'Renvoyer l\'Email';
                resendBtn.classList.remove('bg-green-100', 'text-green-700');
            }, 2000);
        }, 1500);
    }

    // Input effects
    const inputs = document.querySelectorAll('input[type="email"]');
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.classList.add('focused');
        });

        input.addEventListener('blur', function() {
            if (!this.value) {
                this.parentElement.classList.remove('focused');
            }
        });
    });

    // Mouse parallax effect
    window.addEventListener('mousemove', (e) => {
        const shapes = document.querySelectorAll('.geometric-shape');
        const mouseX = e.clientX / window.innerWidth;
        const mouseY = e.clientY / window.innerHeight;

        shapes.forEach((shape, index) => {
            const speed = (index + 1) * 0.02;
            const x = (mouseX - 0.5) * speed * 100;
            const y = (mouseY - 0.5) * speed * 100;

            shape.style.transform = `translate(${x}px, ${y}px) rotate(45deg)`;
        });
    });

    // Entrance animations
    const formElements = document.querySelectorAll('.input-group, button, .text-center');
    formElements.forEach((element, index) => {
        element.style.opacity = '0';
        element.style.transform = 'translateY(20px)';

        setTimeout(() => {
            element.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            element.style.opacity = '1';
            element.style.transform = 'translateY(0)';
        }, index * 100 + 300);
    });
</script>
</body>

</html>
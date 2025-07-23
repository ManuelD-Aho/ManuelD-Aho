<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    'primary': '#27A062',
                    'primary-dark': '#0B3C32',
                    'secondary': '#2F53CD',
                    'secondary-light': '#60A5FA',
                    'accent': '#2F54CC',
                    'yellow-custom': '#FFD700',
                    'yellow-bright': '#FFEB3B'
                },
                animation: {
                    'float': 'float 3s ease-in-out infinite',
                    'float-delayed': 'float 3s ease-in-out 1.5s infinite',
                    'pulse-slow': 'pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                    'slide-in': 'slideIn 0.6s ease-out',
                    'bounce-in': 'bounceIn 0.8s ease-out',
                    'fade-in': 'fadeIn 0.6s ease-out'
                }
            }
        }
    }
    </script>
    <style>
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

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
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
        color: #157A6E;
        font-weight: 600;
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
        border: 1px solid rgba(21, 122, 110, 0.1);
        box-shadow: 0 25px 50px -12px rgba(21, 122, 110, 0.1);
    }

    .glass-effect {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .gradient-bg {
        background: linear-gradient(135deg, #157A6E 0%, #0B3C32 100%);
    }

    .hero-gradient {
        background: linear-gradient(135deg, #33A74F 0%, #375BCE 100%);
    }

    .nav-link {
        position: relative;
    }

    .nav-link:after {
        content: '';
        position: absolute;
        width: 0;
        height: 2px;
        bottom: 0;
        left: 0;
        background-color: #157A6E;
        transition: width 0.3s ease;
    }

    .nav-link:hover:after {
        width: 100%;
    }
    </style>
</head>

<body class="min-h-screen bg-gray-50 font-sans overflow-hidden">

    <!-- Background Geometric Elements -->
    <div class="fixed inset-0 pointer-events-none">
        <!-- Large background circle -->
        <div class="absolute -top-40 -right-40 w-96 h-96 bg-accent rounded-full opacity-10 animate-pulse-slow"></div>

        <!-- Floating geometric shapes -->
        <div class="absolute top-20 left-20 w-8 h-8 bg-accent rounded-full animate-float opacity-60"></div>
        <div class="absolute top-40 right-32 w-6 h-6 bg-primary rounded-full animate-float-delayed opacity-40"></div>
        <div class="absolute bottom-32 left-16 geometric-shape">
            <div class="w-12 h-12 border-3 border-accent transform rotate-45 opacity-30"></div>
        </div>
        <div class="absolute bottom-20 right-20 geometric-shape">
            <div class="w-10 h-10 border-2 border-primary transform rotate-45 opacity-20"></div>
        </div>
        <div class="absolute top-1/2 left-10 w-4 h-4 bg-accent rounded-full animate-pulse-slow opacity-50"></div>

        <!-- Large geometric shape -->
        <div class="absolute -bottom-20 -left-20 w-64 h-64 border-4 border-primary/10 transform rotate-45 opacity-30">
        </div>

        <!-- Additional decorative elements -->
        <div class="absolute top-1/4 right-1/4 w-16 h-16 border-2 border-accent/20 rounded-full animate-float"></div>
        <div class="absolute bottom-1/4 left-1/3 w-12 h-12 bg-primary/5 rounded-full animate-float-delayed"></div>
    </div>

    <!-- Main Content -->
    <div class="relative z-10 flex items-center justify-center min-h-[calc(100vh-80px)] py-12 px-6">
        <div class="w-full max-w-6xl grid lg:grid-cols-2 gap-12 items-center">

            <!-- Left Side - Illustration -->
            <div class="hidden lg:flex justify-center items-center relative">
                <div class="relative animate-bounce-in">
                    <!-- Main illustration circle -->
                    <div
                        class="w-96 h-96 hero-gradient rounded-full flex items-center justify-center relative overflow-hidden shadow-2xl">
                        <!-- Character with laptop -->
                        <div class="relative z-10">
                            <!-- Person outline -->
                            <div class="w-40 h-48 relative mb-8">
                                <!-- Head -->
                                <div class="w-16 h-16 bg-white rounded-full mx-auto mb-4 relative shadow-lg">
                                    <div class="absolute top-4 left-3 w-2 h-2 bg-primary rounded-full"></div>
                                    <div class="absolute top-4 right-3 w-2 h-2 bg-primary rounded-full"></div>
                                    <div
                                        class="absolute bottom-3 left-1/2 transform -translate-x-1/2 w-4 h-1 bg-primary rounded-full">
                                    </div>
                                </div>

                                <!-- Body -->
                                <div class="w-20 h-24 bg-white rounded-t-full mx-auto relative shadow-lg">
                                    <!-- Arms -->
                                    <div
                                        class="absolute -left-6 top-4 w-12 h-3 bg-white rounded-full transform -rotate-12 shadow-md">
                                    </div>
                                    <div
                                        class="absolute -right-6 top-4 w-12 h-3 bg-white rounded-full transform rotate-12 shadow-md">
                                    </div>
                                </div>
                            </div>

                            <!-- Laptop -->
                            <div class="w-32 h-20 bg-gray-800 rounded-lg mx-auto relative transform -mt-4 shadow-xl">
                                <div
                                    class="w-28 h-16 bg-white rounded-sm absolute top-2 left-2 flex items-center justify-center">
                                    <!-- Screen content -->
                                    <div class="space-y-1">
                                        <div class="w-16 h-1 bg-primary rounded"></div>
                                        <div class="w-12 h-1 bg-gray-300 rounded"></div>
                                        <div class="w-20 h-1 bg-gray-300 rounded"></div>
                                        <div class="w-8 h-1 bg-accent rounded"></div>
                                    </div>
                                </div>
                                <!-- Keyboard -->
                                <div
                                    class="absolute -bottom-1 left-1/2 transform -translate-x-1/2 w-24 h-2 bg-gray-700 rounded-sm">
                                </div>
                            </div>
                        </div>

                        <!-- Decorative elements inside circle -->
                        <div
                            class="absolute top-10 left-10 w-8 h-8 bg-white rounded-full opacity-60 animate-float shadow-lg">
                        </div>
                        <div
                            class="absolute bottom-20 right-16 w-6 h-6 bg-primary rounded-full opacity-40 animate-float-delayed shadow-md">
                        </div>
                        <div class="absolute top-32 right-8 w-4 h-4 bg-white rounded-sm opacity-50 animate-pulse-slow">
                        </div>
                    </div>

                    <!-- Floating elements around illustration -->
                    <div
                        class="absolute -top-8 -right-8 w-16 h-16 bg-primary rounded-full animate-float shadow-xl opacity-80">
                    </div>
                    <div
                        class="absolute -bottom-8 -left-8 w-20 h-20 border-4 border-accent transform rotate-45 animate-float-delayed opacity-60">
                    </div>
                    <div class="absolute top-20 -left-12 w-8 h-8 bg-accent rounded-full animate-pulse-slow shadow-lg">
                    </div>
                    <div
                        class="absolute bottom-32 -right-16 w-12 h-12 border-2 border-primary rounded-full animate-float opacity-40">
                    </div>
                </div>


            </div>

            <!-- Right Side - Login Form -->
            <div class="animate-slide-in">
                <div class="form-container rounded-3xl p-8 lg:p-12 shadow-2xl max-w-md mx-auto">
                    <!-- Header -->
                    <div class="text-center mb-8">
                        <div class=" mx-auto mb-4 flex items-center justify-center">
                            <img src="./images/logo.png" alt="logo UniValid" class="w-20">
                        </div>
                        <h1 class="text-3xl font-bold mb-2 text-primary">Connexion</h1>
                        <p class="text-gray-600">Connectez-vous pour continuer à suivre vos évaluations </p>
                    </div>

                    <!-- Login Form -->
                    <form class="space-y-6" method="POST" action="login.php">
                        <!-- Email Input -->
                        <div class="input-group">
                            <input type="email" id="email" name="email" placeholder=" "
                                class="w-full px-4 py-4 border-2 border-gray-200 rounded-xl focus:border-primary focus:outline-none transition-all duration-200 bg-white/70 hover:bg-white focus:bg-white"
                                required>
                            <label for="email">Adresse email</label>
                        </div>

                        <!-- Password Input -->
                        <div class="input-group">
                            <input type="password" id="password" name="password" placeholder=" "
                                class="w-full px-4 py-4 border-2 border-gray-200 rounded-xl focus:border-primary focus:outline-none transition-all duration-200 bg-white/70 hover:bg-white focus:bg-white"
                                required>
                            <label for="password">Mot de passe</label>
                        </div>

                        <!-- Remember & Forgot Password -->
                        <div class="flex justify-between items-center text-sm">
                            <label class="flex items-center space-x-2 cursor-pointer">
                                <a href="index.php"><span class="text-accent font-medium hover:text-accent-700">Aller à
                                        l'accueil</span></a>
                            </label>
                            <a href="forgot_password.php" class="text-accent hover:text-accent-700 font-medium">
                                Mot de passe oublié ?
                            </a>
                        </div>

                        <!-- Login Button -->
                        <button type="submit"
                            class="w-full bg-primary text-white py-4 rounded-xl font-semibold hover:bg-primary transition-all duration-300 hover:scale-105 transform hover:shadow-lg focus:outline-none focus:ring-4 focus:ring-primary/20">
                            Se Connecter
                        </button>
                    </form>
                </div>


            </div>
        </div>
    </div>

    <script>
    // Enhanced form validation and interaction effects
    const inputs = document.querySelectorAll('input[type="email"], input[type="password"]');

    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.classList.add('focused');
            this.parentElement.querySelector('label').style.color = '#157A6E';
        });

        input.addEventListener('blur', function() {
            if (!this.value) {
                this.parentElement.classList.remove('focused');
                this.parentElement.querySelector('label').style.color = '#6B7280';
            }
        });

        // Real-time validation feedback
        input.addEventListener('input', function() {
            if (this.type === 'email') {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (emailRegex.test(this.value)) {
                    this.style.borderColor = '#157A6E';
                } else if (this.value.length > 0) {
                    this.style.borderColor = '#EF4444';
                }
            }
        });
    });

    // Enhanced parallax effect for background elements
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

    // Enhanced form submission with loading effect
    const form = document.querySelector('form');
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalBtnText = submitBtn.innerHTML;

    form.addEventListener('submit', function(e) {
        //  e.preventDefault();

        // Loading state
        submitBtn.disabled = true;
        submitBtn.innerHTML = `
            <div class="flex items-center justify-center space-x-2">
                <div class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
                <span>Connexion en cours...</span>
            </div>
        `;

        // Simulate loading (remove this in production)
        setTimeout(() => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnText;
            // Here you would typically handle the actual login response
        }, 2000);
    });

    // Staggered entrance animation for form elements
    const formElements = document.querySelectorAll('.input-group, button, .text-center, .grid');
    formElements.forEach((element, index) => {
        element.style.opacity = '0';
        element.style.transform = 'translateY(20px)';

        setTimeout(() => {
            element.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            element.style.opacity = '1';
            element.style.transform = 'translateY(0)';
        }, index * 100 + 300);
    });

    // Social login button interactions
    const socialButtons = document.querySelectorAll('.grid button');
    socialButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            // Add ripple effect
            const ripple = document.createElement('div');
            ripple.classList.add('absolute', 'bg-gray-300', 'rounded-full', 'animate-ping');
            ripple.style.width = '20px';
            ripple.style.height = '20px';
            ripple.style.left = '50%';
            ripple.style.top = '50%';
            ripple.style.transform = 'translate(-50%, -50%)';

            this.style.position = 'relative';
            this.appendChild(ripple);

            setTimeout(() => {
                ripple.remove();
            }, 600);
        });
    });

    // Enhanced keyboard navigation
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && e.target.tagName === 'INPUT') {
            const inputs = Array.from(document.querySelectorAll('input'));
            const currentIndex = inputs.indexOf(e.target);

            if (currentIndex < inputs.length - 1) {
                inputs[currentIndex + 1].focus();
            } else {
                submitBtn.click();
            }
        }
    });
    </script>
</body>

</html>
<?php
// Si usas este archivo directamente como template, mantengo la parte PHP mínima que tenías
// para mostrar mensajes de error via ?error=...
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ingresar al Sistema - Gestión de Recursos</title>

    <!-- TailwindCSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .fade-in {
            animation: fadeIn 0.9s ease;
        }
        .shake {
            animation: shake 0.5s ease-in-out;
        }
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }
        /* Small helper to keep SVG icons crisp */
        .icon { width: 2rem; height: 2rem; }
    </style>
</head>

<body class="min-h-screen flex">

    <!-- MITAD IZQUIERDA (decorativa con ICONOS SVG, no imágenes) -->
    <div class="hidden lg:flex w-1/2 bg-gradient-to-br from-blue-700 via-blue-600 to-blue-800 
                flex-col justify-center items-center text-white p-10 relative overflow-hidden">

        <!-- Capa oscura sutil -->
        <div class="absolute inset-0 bg-black opacity-20"></div>

        <!-- Contenido principal con iconos -->
        <div class="relative z-10 text-center fade-in space-y-6 max-w-lg">
            <h1 class="text-4xl font-bold mb-2 drop-shadow-lg">Sistema de Gestión de Recursos</h1>
            <p class="text-lg text-blue-100 max-w-md leading-relaxed drop-shadow">
                Automatizado bajo los lineamientos del <strong>PMBOK 6</strong>.
                Administra recursos, equipos, adquisiciones y asignaciones de forma profesional.
            </p>

            <!-- Row of flat icons -->
            <div class="mt-4 flex items-center justify-center gap-6">
                <!-- Icon: users -->
                <div class="flex flex-col items-center">
                    <svg class="icon text-blue-200" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 21v-2a4 4 0 00-4-4H9a4 4 0 00-4 4v2"></path>
                        <circle cx="12" cy="7" r="4" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"></circle>
                    </svg>
                    <span class="text-xs text-blue-100 mt-2">Equipo</span>
                </div>

                <!-- Icon: gear (config) -->
                <div class="flex flex-col items-center">
                    <svg class="icon text-blue-200" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15.5A3.5 3.5 0 1112 8.5a3.5 3.5 0 010 7z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 01-2.83 2.83l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-4 0v-.09a1.65 1.65 0 00-1-1.51 1.65 1.65 0 00-1.82.33l-.06.06A2 2 0 014.27 17.9l.06-.06a1.65 1.65 0 00.33-1.82 1.65 1.65 0 00-1.51-1H3a2 2 0 010-4h.09a1.65 1.65 0 001.51-1 1.65 1.65 0 00-.33-1.82l-.06-.06A2 2 0 016.1 4.27l.06.06a1.65 1.65 0 001.82.33H9a1.65 1.65 0 001-1.51V3a2 2 0 014 0v.09c.1.6.59 1.09 1.19 1.19H16a1.65 1.65 0 001.51 1 1.65 1.65 0 001.82-.33l.06-.06A2 2 0 0119.73 6.1l-.06.06a1.65 1.65 0 00-.33 1.82V9c.12.5.6.89 1.11 1h.09a2 2 0 010 4h-.09c-.51.11-.99.5-1.11 1v1.09c.06.51.35 1 .83 1.29z"></path>
                    </svg>
                    <span class="text-xs text-blue-100 mt-2">Configuración</span>
                </div>

                <!-- Icon: document -->
                <div class="flex flex-col items-center">
                    <svg class="icon text-blue-200" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14 2v6h6"></path>
                    </svg>
                    <span class="text-xs text-blue-100 mt-2">Documentos</span>
                </div>
            </div>
        </div>

        <!-- Decorative simple shapes (using gradients and SVGs) -->
        <svg class="absolute -bottom-10 -right-10 w-64 h-64 opacity-20" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
            <defs><linearGradient id="g1" x1="0" x2="1"><stop offset="0" stop-color="#60a5fa"/><stop offset="1" stop-color="#3b82f6"/></linearGradient></defs>
            <circle cx="50" cy="50" r="50" fill="url(#g1)"></circle>
        </svg>
        <svg class="absolute top-10 left-10 w-40 h-40 opacity-12" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
            <circle cx="50" cy="50" r="50" fill="#93c5fd"></circle>
        </svg>
    </div>

    <!-- MITAD DERECHA: FORMULARIO -->
    <div class="w-full lg:w-1/2 flex items-center justify-center p-8 bg-gray-100">

        <div class="w-full max-w-md bg-white shadow-2xl rounded-2xl p-8 fade-in">

            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-blue-600">Bienvenido</h2>
                <p class="text-gray-500 mt-1 text-sm">Ingresa tus credenciales</p>
                
                <!-- Mensaje de error (oculto por defecto) -->
                <?php if(isset($_GET['error'])): ?>
                <div id="error-message" class="mt-4 p-3 bg-red-50 border border-red-200 text-red-600 rounded-lg text-sm shake">
                    <?php 
                    switch($_GET['error']) {
                        case 'credenciales':
                            echo 'Correo o contraseña incorrectos.';
                            break;
                        case 'vacio':
                            echo 'Por favor, completa todos los campos.';
                            break;
                        case 'inactivo':
                            echo 'Tu cuenta está inactiva. Contacta al administrador.';
                            break;
                        default:
                            echo 'Error en el inicio de sesión.';
                    }
                    ?>
                </div>
                <?php endif; ?>
            </div>
            
            <form action="../controllers/AuthController.php" method="POST" class="space-y-5" id="login-form">

                <div>
                    <label class="block mb-1 text-gray-700 font-semibold">Correo Electrónico</label>
                    <input 
                        type="email" 
                        name="email" 
                        id="email"
                        required
                        placeholder="ejemplo@empresa.com"
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 
                               focus:ring-2 focus:ring-blue-500 focus:outline-none transition-all"
                        autocomplete="email"
                    >
                    <p id="email-error" class="text-red-500 text-xs mt-1 hidden">Por favor, ingresa un correo válido.</p>
                </div>

                <div>
                    <label class="block mb-1 text-gray-700 font-semibold">Contraseña</label>
                    <div class="relative">
                        <input 
                            type="password" 
                            name="password" 
                            id="password"
                            required
                            placeholder="Ingresa tu contraseña"
                            class="w-full px-4 py-3 rounded-lg border border-gray-300
                                   focus:ring-2 focus:ring-blue-500 focus:outline-none pr-10"
                            autocomplete="current-password"
                        >
                        <button 
                            type="button" 
                            id="toggle-password"
                            class="absolute right-3 top-3 text-gray-500 hover:text-blue-600"
                            aria-label="Mostrar u ocultar contraseña"
                        >
                            <!-- SVG eye icon (initial) -->
                            <svg id="icon-eye" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                              <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                    <p id="password-error" class="text-red-500 text-xs mt-1 hidden">La contraseña es requerida.</p>
                </div>

                <div class="flex items-center justify-between text-sm">
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded border-gray-400 text-blue-600">
                        <span class="text-gray-600">Recordar sesión</span>
                    </label>

                    <a href="#" class="text-blue-600 hover:text-blue-700 font-semibold">
                        ¿Olvidaste tu contraseña?
                    </a>
                </div>

                <button 
                    type="submit"
                    id="submit-btn"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg 
                           text-lg font-semibold transition-colors duration-300 shadow-md 
                           hover:shadow-lg disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    <span id="btn-text">Ingresar</span>
                    <span id="btn-loading" class="hidden">
                        <svg class="animate-spin h-5 w-5 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Verificando...
                    </span>
                </button>

            </form>

            <div class="mt-6 text-center">
                <p class="text-gray-600 text-sm mb-3">Credenciales de prueba:</p>
                <div class="bg-gray-50 p-3 rounded-lg border border-gray-200 text-left text-xs">
                    <div class="mb-1"><span class="font-semibold">Admin:</span> admin@gestionrecursos.com</div>
                    <div class="mb-1"><span class="font-semibold">Gerente:</span> maria.gerente@gestionrecursos.com</div>
                    <div><span class="font-semibold">Contraseña:</span> Admin123</div>
                </div>
            </div>

            <p class="text-center text-gray-500 text-xs mt-6">
                © <?php echo date('Y'); ?> Sistema de Gestión de Recursos — PMBOK 6
            </p>

        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('login-form');
            const emailInput = document.getElementById('email');
            const passwordInput = document.getElementById('password');
            const togglePasswordBtn = document.getElementById('toggle-password');
            const submitBtn = document.getElementById('submit-btn');
            const btnText = document.getElementById('btn-text');
            const btnLoading = document.getElementById('btn-loading');
            const emailError = document.getElementById('email-error');
            const passwordError = document.getElementById('password-error');
            const errorMessage = document.getElementById('error-message');
            const iconEye = document.getElementById('icon-eye');

            // Auto-ocultar mensaje de error después de 5 segundos
            if (errorMessage) {
                setTimeout(() => {
                    errorMessage.style.opacity = '0';
                    errorMessage.style.transition = 'opacity 0.5s';
                    setTimeout(() => errorMessage.style.display = 'none', 500);
                }, 5000);
            }

            // Validación de email en tiempo real
            emailInput.addEventListener('blur', function() {
                const email = this.value.trim();
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                
                if (!emailRegex.test(email)) {
                    emailError.classList.remove('hidden');
                    this.classList.add('border-red-500');
                } else {
                    emailError.classList.add('hidden');
                    this.classList.remove('border-red-500');
                }
            });

            // Validación de contraseña en tiempo real
            passwordInput.addEventListener('blur', function() {
                if (this.value.length < 1) {
                    passwordError.classList.remove('hidden');
                    this.classList.add('border-red-500');
                } else {
                    passwordError.classList.add('hidden');
                    this.classList.remove('border-red-500');
                }
            });

            // Mostrar/ocultar contraseña (cambia icono SVG)
            togglePasswordBtn.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);

                // Swap icon between eye and eye-off
                if (type === 'text') {
                    iconEye.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.956 9.956 0 012.27-3.65M6.1 6.1A9.955 9.955 0 0112 5c4.478 0 8.268 2.943 9.542 7a9.954 9.954 0 01-1.826 3.213M3 3l18 18"></path>';
                } else {
                    iconEye.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />';
                }
            });

            // Validación del formulario antes de enviar
            form.addEventListener('submit', function(e) {
                let isValid = true;
                
                // Validar email
                const email = emailInput.value.trim();
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(email)) {
                    emailError.classList.remove('hidden');
                    emailInput.classList.add('border-red-500');
                    isValid = false;
                }
                
                // Validar contraseña
                if (passwordInput.value.length < 1) {
                    passwordError.classList.remove('hidden');
                    passwordInput.classList.add('border-red-500');
                    isValid = false;
                }
                
                if (!isValid) {
                    e.preventDefault();
                    if (errorMessage) errorMessage.classList.add('shake');
                    setTimeout(() => {
                        if (errorMessage) errorMessage.classList.remove('shake');
                    }, 500);
                } else {
                    // Mostrar indicador de carga
                    submitBtn.disabled = true;
                    btnText.classList.add('hidden');
                    btnLoading.classList.remove('hidden');
                }
            });

            // Limpiar errores al escribir
            emailInput.addEventListener('input', function() {
                emailError.classList.add('hidden');
                this.classList.remove('border-red-500');
            });

            passwordInput.addEventListener('input', function() {
                passwordError.classList.add('hidden');
                this.classList.remove('border-red-500');
            });

            // Autofocus en el campo de email
            emailInput.focus();
        });
    </script>

</body>
</html>

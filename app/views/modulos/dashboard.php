<?php
// dashboard.php - VERSIÓN CORREGIDA SIN session_start DUPLICADO

// ============================================
// MANEJO SEGURO DE SESIÓN
// ============================================

// Verificar si hay sesión activa antes de intentar verificarla
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar sesión
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}
$usuario = $_SESSION['usuario'];

// ============================================
// PREVENIR CONFLICTOS DE FUNCIONES
// ============================================

// Si sidebar.php ya definió getBaseUrl, no la redeclares
if (!function_exists('getBaseUrl')) {
    function getBaseUrl() {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || 
                    $_SERVER['SERVER_PORT'] == 443) ? 'https://' : 'http://';
        
        $host = $_SERVER['HTTP_HOST'];
        
        // Si estamos en localhost con puerto específico
        if (strpos($host, 'localhost') !== false && $_SERVER['SERVER_PORT'] != 80 && $_SERVER['SERVER_PORT'] != 443) {
            $host .= ':' . $_SERVER['SERVER_PORT'];
        }
        
        // Detectar carpeta del proyecto
        $request_uri = $_SERVER['REQUEST_URI'];
        if (strpos($request_uri, '/Proyecto_GestionRecursos/') !== false) {
            $base_path = '/Proyecto_GestionRecursos/';
        } else {
            $base_path = '/';
        }
        
        return $protocol . $host . $base_path;
    }
}

// ============================================
// INCLUIR COMPONENTES CON RUTAS CORRECTAS
// ============================================

// dashboard.php está en: vistas/modulos/
// Los componentes están en: vistas/componentes/

// Incluir head primero
require_once __DIR__ . '/../componentes/head.php';

// Incluir sidebar (que tiene su propio sistema de rutas)
require_once __DIR__ . '/../componentes/sidebar.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistema de Gestión de Recursos</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .fade-in {
            animation: fadeIn 0.5s ease-in;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        .sidebar-transition {
            transition: all 0.3s ease;
        }
        .hover-lift:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Contenedor principal -->
    <div class="flex min-h-screen">
        <!-- Sidebar ya incluido -->
        
        <!-- Contenido principal -->
        <main class="flex-1 p-6 ml-72 transition-all duration-300 min-h-screen overflow-auto fade-in">
            <!-- MENSAJE GLOBAL -->
            <?php 
            // Función para mostrar mensajes (solo si no existe)
            if (!function_exists('mostrarMensajeDashboard')) {
                function mostrarMensajeDashboard() {
                    if (isset($_SESSION['error_login'])) {
                        echo '<div class="mb-6 border rounded-xl p-4 bg-red-50 border-red-200 text-red-800">';
                        echo htmlspecialchars($_SESSION['error_login']);
                        echo '</div>';
                        unset($_SESSION['error_login']);
                    }
                    
                    if (isset($_SESSION['mensaje'])) {
                        $mensaje = $_SESSION['mensaje'];
                        $tipo = $_SESSION['tipo_mensaje'] ?? 'info';
                        unset($_SESSION['mensaje'], $_SESSION['tipo_mensaje']);
                        
                        $colores = [
                            'success' => 'bg-green-50 border-green-200 text-green-800',
                            'error' => 'bg-red-50 border-red-200 text-red-800',
                            'info' => 'bg-blue-50 border-blue-200 text-blue-800',
                            'warning' => 'bg-yellow-50 border-yellow-200 text-yellow-800'
                        ];
                        
                        $color = $colores[$tipo] ?? $colores['info'];
                        
                        echo "<div class='border rounded-xl p-4 mb-6 $color'>";
                        echo htmlspecialchars($mensaje);
                        echo "</div>";
                    }
                }
            }
            
            mostrarMensajeDashboard();
            ?>
            
            <!-- ENCABEZADO -->
            <header class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
                <div>
                    <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900">
                        Bienvenido, <span class="text-indigo-600"><?php echo strtoupper($usuario['nombre']); ?></span>
                    </h1>
                    <p class="mt-1 text-sm text-gray-600">Panel de control del sistema de gestión de recursos humanos</p>
                </div>
                <div class="flex items-center gap-4">
                    <div class="text-right">
                        <div class="text-sm font-medium text-gray-700"><?php echo date('d/m/Y'); ?></div>
                        <div class="text-xs text-gray-500 hora-actual"><?php echo date('H:i:s'); ?></div>
                    </div>
                    <div class="w-10 h-10 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-full flex items-center justify-center text-white font-bold">
                        <?php echo strtoupper(substr($_SESSION['usuario_nombre'] ?? 'A', 0, 1)); ?>
                    </div>
                </div>
            </header>

            <!-- ESTADÍSTICAS RÁPIDAS -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Tarjeta 1: Proyectos -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover-lift transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Proyectos Activos</p>
                            <p class="mt-2 text-3xl font-bold text-gray-900">8</p>
                            <p class="mt-1 text-xs text-green-600 flex items-center">
                                <i class="fas fa-arrow-up mr-1"></i> 2 nuevos esta semana
                            </p>
                        </div>
                        <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center">
                            <i class="fas fa-project-diagram text-blue-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Tarjeta 2: Recursos -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover-lift transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Recursos Humanos</p>
                            <p class="mt-2 text-3xl font-bold text-gray-900">24</p>
                            <p class="mt-1 text-xs text-blue-600 flex items-center">
                                <i class="fas fa-check-circle mr-1"></i> 18 disponibles
                            </p>
                        </div>
                        <div class="w-12 h-12 bg-green-50 rounded-lg flex items-center justify-center">
                            <i class="fas fa-users text-green-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Tarjeta 3: Horas -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover-lift transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Horas Registradas</p>
                            <p class="mt-2 text-3xl font-bold text-gray-900">1,248</p>
                            <p class="mt-1 text-xs text-amber-600 flex items-center">
                                <i class="fas fa-clock mr-1"></i> Esta semana
                            </p>
                        </div>
                        <div class="w-12 h-12 bg-amber-50 rounded-lg flex items-center justify-center">
                            <i class="fas fa-clock text-amber-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Tarjeta 4: Presupuesto -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover-lift transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Presupuesto</p>
                            <p class="mt-2 text-3xl font-bold text-gray-900">78%</p>
                            <p class="mt-1 text-xs text-purple-600 flex items-center">
                                <i class="fas fa-chart-pie mr-1"></i> $156,000 / $200,000
                            </p>
                        </div>
                        <div class="w-12 h-12 bg-purple-50 rounded-lg flex items-center justify-center">
                            <i class="fas fa-dollar-sign text-purple-600 text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECCIÓN PRINCIPAL -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- PROYECTOS ACTIVOS -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                            <div class="flex items-center justify-between">
                                <h2 class="text-lg font-semibold text-gray-900">Proyectos Activos</h2>
                                <a href="#" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                                    Ver todos <i class="fas fa-arrow-right ml-1"></i>
                                </a>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                <?php
                                // Datos de ejemplo
                                $proyectos = [
                                    [
                                        'nombre' => 'Sistema ERP Empresarial',
                                        'cliente' => 'Tech Solutions S.A.',
                                        'progreso' => 75,
                                        'estado' => 'En progreso',
                                        'fecha' => '15/01/2024 - 30/06/2024',
                                        'color' => 'blue'
                                    ],
                                    [
                                        'nombre' => 'Portal Web Corporativo',
                                        'cliente' => 'Inno Designs E.I.R.L.',
                                        'progreso' => 90,
                                        'estado' => 'En revisión',
                                        'fecha' => '01/02/2024 - 31/03/2024',
                                        'color' => 'amber'
                                    ],
                                    [
                                        'nombre' => 'App Móvil de Gestión',
                                        'cliente' => 'Mobile Tech Perú',
                                        'progreso' => 45,
                                        'estado' => 'Planificación',
                                        'fecha' => '01/03/2024 - 30/09/2024',
                                        'color' => 'green'
                                    ]
                                ];
                                
                                foreach ($proyectos as $proyecto):
                                ?>
                                <div class="border border-gray-100 rounded-lg p-4 hover:bg-gray-50 transition-colors">
                                    <div class="flex items-start justify-between mb-3">
                                        <div>
                                            <h3 class="font-medium text-gray-900"><?php echo $proyecto['nombre']; ?></h3>
                                            <p class="text-sm text-gray-500 mt-1">Cliente: <?php echo $proyecto['cliente']; ?></p>
                                        </div>
                                        <span class="px-3 py-1 text-xs font-medium rounded-full 
                                            bg-<?php echo $proyecto['color']; ?>-100 text-<?php echo $proyecto['color']; ?>-800">
                                            <?php echo $proyecto['estado']; ?>
                                        </span>
                                    </div>
                                    
                                    <div class="mb-2">
                                        <div class="flex justify-between text-sm text-gray-600 mb-1">
                                            <span>Progreso: <?php echo $proyecto['progreso']; ?>%</span>
                                            <span><?php echo $proyecto['progreso']; ?>%</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div class="h-2 rounded-full bg-<?php echo $proyecto['color']; ?>-500" 
                                                 style="width: <?php echo $proyecto['progreso']; ?>%"></div>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-center justify-between text-xs text-gray-500">
                                        <span><i class="far fa-calendar mr-1"></i> <?php echo $proyecto['fecha']; ?></span>
                                        <span class="flex items-center">
                                            <i class="fas fa-user-friends mr-1"></i> 5 recursos
                                        </span>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ACTIVIDAD RECIENTE -->
                <div>
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                            <h2 class="text-lg font-semibold text-gray-900">Actividad Reciente</h2>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 mt-1">
                                        <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-user-plus text-indigo-600 text-sm"></i>
                                        </div>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-gray-900">
                                            <span class="font-medium">Juan Pérez</span> asignó un nuevo recurso
                                        </p>
                                        <p class="text-xs text-gray-500 mt-1">Proyecto: Sistema ERP • Hace 2 horas</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 mt-1">
                                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-check-circle text-green-600 text-sm"></i>
                                        </div>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-gray-900">
                                            <span class="font-medium">María García</span> completó una tarea
                                        </p>
                                        <p class="text-xs text-gray-500 mt-1">Proyecto: Portal Web • Hace 5 horas</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 mt-1">
                                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-clock text-blue-600 text-sm"></i>
                                        </div>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-gray-900">
                                            <span class="font-medium">Carlos López</span> registró 8 horas
                                        </p>
                                        <p class="text-xs text-gray-500 mt-1">Proyecto: App Móvil • Ayer, 15:30</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 mt-1">
                                        <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-file-alt text-purple-600 text-sm"></i>
                                        </div>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-gray-900">
                                            <span class="font-medium">Ana Torres</span> actualizó documentación
                                        </p>
                                        <p class="text-xs text-gray-500 mt-1">Proyecto: Sistema ERP • Ayer, 11:15</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-6 pt-6 border-t border-gray-200">
                                <a href="#" class="block text-center text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                                    <i class="fas fa-history mr-2"></i> Ver historial completo
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ACCESOS RÁPIDOS -->
            <div class="mt-8">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Accesos Rápidos</h2>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <?php
                    // Usar Router del sidebar si existe
                    if (class_exists('Router')) {
                        echo '<a href="' . Router::url('definir_roles') . '" 
                               class="bg-white border border-gray-200 rounded-xl p-4 text-center hover:shadow-md transition-all duration-300 hover-lift">
                                <div class="w-12 h-12 bg-indigo-50 rounded-lg flex items-center justify-center mx-auto mb-3">
                                    <i class="fas fa-user-tag text-indigo-600 text-xl"></i>
                                </div>
                                <p class="font-medium text-gray-900">Definir Roles</p>
                                <p class="text-xs text-gray-500 mt-1">Gestionar roles por proyecto</p>
                            </a>';
                        
                        echo '<a href="' . Router::url('catalogo_recursos') . '" 
                               class="bg-white border border-gray-200 rounded-xl p-4 text-center hover:shadow-md transition-all duration-300 hover-lift">
                                <div class="w-12 h-12 bg-green-50 rounded-lg flex items-center justify-center mx-auto mb-3">
                                    <i class="fas fa-users-cog text-green-600 text-xl"></i>
                                </div>
                                <p class="font-medium text-gray-900">Catálogo Recursos</p>
                                <p class="text-xs text-gray-500 mt-1">Ver recursos disponibles</p>
                            </a>';
                        
                        echo '<a href="' . Router::url('generar_plan') . '" 
                               class="bg-white border border-gray-200 rounded-xl p-4 text-center hover:shadow-md transition-all duration-300 hover-lift">
                                <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center mx-auto mb-3">
                                    <i class="fas fa-tasks text-blue-600 text-xl"></i>
                                </div>
                                <p class="font-medium text-gray-900">Generar Plan</p>
                                <p class="text-xs text-gray-500 mt-1">Crear plan de recursos</p>
                            </a>';
                        
                        echo '<a href="' . Router::url('controlar_recursos') . '" 
                               class="bg-white border border-gray-200 rounded-xl p-4 text-center hover:shadow-md transition-all duration-300 hover-lift">
                                <div class="w-12 h-12 bg-purple-50 rounded-lg flex items-center justify-center mx-auto mb-3">
                                    <i class="fas fa-chart-line text-purple-600 text-xl"></i>
                                </div>
                                <p class="font-medium text-gray-900">Controlar</p>
                                <p class="text-xs text-gray-500 mt-1">Monitorear recursos</p>
                            </a>';
                    } else {
                        // Rutas alternativas si Router no existe
                        $base_url = getBaseUrl();
                        echo '<a href="' . $base_url . 'vistas/modulos/planificar_recursos/definir_roles.php" 
                               class="bg-white border border-gray-200 rounded-xl p-4 text-center hover:shadow-md transition-all duration-300 hover-lift">
                                <div class="w-12 h-12 bg-indigo-50 rounded-lg flex items-center justify-center mx-auto mb-3">
                                    <i class="fas fa-user-tag text-indigo-600 text-xl"></i>
                                </div>
                                <p class="font-medium text-gray-900">Definir Roles</p>
                                <p class="text-xs text-gray-500 mt-1">Gestionar roles por proyecto</p>
                            </a>';
                    }
                    ?>
                </div>
            </div>
        </main>
    </div>

    <!-- Scripts adicionales -->
    <script>
    // Función para actualizar la hora en tiempo real
    function actualizarHora() {
        const ahora = new Date();
        const options = { hour: '2-digit', minute: '2-digit', second: '2-digit' };
        const hora = ahora.toLocaleTimeString('es-ES', options);
        const elementosHora = document.querySelectorAll('.hora-actual');
        elementosHora.forEach(el => {
            el.textContent = hora;
        });
    }
    
    // Actualizar cada segundo
    setInterval(actualizarHora, 1000);
    actualizarHora(); // Ejecutar inmediatamente
    
    // Notificaciones toast
    function mostrarNotificacion(mensaje, tipo = 'info') {
        const toast = document.createElement('div');
        toast.className = `fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg text-white ${
            tipo === 'success' ? 'bg-green-500' :
            tipo === 'error' ? 'bg-red-500' :
            tipo === 'warning' ? 'bg-yellow-500' : 'bg-blue-500'
        } z-50 transform transition-transform duration-300 translate-x-full`;
        toast.textContent = mensaje;
        document.body.appendChild(toast);
        
        // Animación de entrada
        setTimeout(() => {
            toast.classList.remove('translate-x-full');
            toast.classList.add('translate-x-0');
        }, 10);
        
        // Auto-eliminar después de 5 segundos
        setTimeout(() => {
            toast.classList.remove('translate-x-0');
            toast.classList.add('translate-x-full');
            setTimeout(() => {
                document.body.removeChild(toast);
            }, 300);
        }, 5000);
    }
    
    // Verificar si hay mensajes en sesión para mostrar
    <?php if (isset($_SESSION['mensaje'])): ?>
    mostrarNotificacion('<?php echo addslashes($_SESSION['mensaje']); ?>', '<?php echo $_SESSION['tipo_mensaje'] ?? 'info'; ?>');
    <?php 
        unset($_SESSION['mensaje'], $_SESSION['tipo_mensaje']);
    endif; 
    ?>
    </script>
</body>
</html>
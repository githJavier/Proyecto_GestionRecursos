<?php
// sidebar.php - SISTEMA MEJORADO DE RUTAS CON PREVENCIÓN DE CONFLICTOS

// ============================================
// PREVENIR DECLARACIONES DUPLICADAS
// ============================================

// Verificar si getBaseUrl ya fue declarada (por ejemplo, en dashboard.php)
if (!function_exists('getBaseUrl')) {
    /**
     * Detecta automáticamente la URL base del proyecto
     * Funciona en localhost, producción y subcarpetas
     */
    function getBaseUrl() {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || 
                    $_SERVER['SERVER_PORT'] == 443) ? 'https://' : 'http://';
        
        $host = $_SERVER['HTTP_HOST'];
        
        // Si estamos en localhost con puerto específico
        if (strpos($host, 'localhost') !== false && $_SERVER['SERVER_PORT'] != 80 && $_SERVER['SERVER_PORT'] != 443) {
            $host .= ':' . $_SERVER['SERVER_PORT'];
        }
        
        // Detectar carpeta del proyecto de forma más simple y confiable
        $request_uri = $_SERVER['REQUEST_URI'];
        
        // Para tu proyecto específico
        if (strpos($request_uri, '/Proyecto_GestionRecursos/') !== false) {
            $base_path = '/Proyecto_GestionRecursos/';
        } else {
            $base_path = '/';
        }
        
        return $protocol . $host . $base_path;
    }
}

// Verificar si la clase Router ya existe
if (!class_exists('Router')) {
    /**
     * Sistema de rutas centralizado - ÚNICO LUGAR PARA MODIFICAR RUTAS
     */
    class Router {
        private static $base_url;
        private static $routes = [
            // Dashboard
            'dashboard' => 'app/views/modulos/dashboard.php',
            
            // Login
            'login' => 'app/views/login.php',
            
            // Planificar Recursos
            'planificar' => 'app/controllers/PlanificarController.php',
            // Estimar Recursos
            'estimar' => 'app/controllers/EstimacionController.php',
            // Adquirir Recursos
            'adquirir' => 'app/controllers/AdquisicionController.php',
            //Desarrollar
            'desarrollar' => 'app/controllers/DesarrollarController.php',
            // Dirigir
            'dirigir' => 'app/controllers/DirigirController.php',
            // Controlar Recursos 
            'controlar_recursos' => 'app/controllers/ControlarController.php',
            
            // Sistema
            'logout' => 'app/controllers/cerrar_sesion.php',
            
            // Páginas principales
            'principal' => 'views/principal.php',
        ];
        
        /**
         * Inicializar el router
         */
        public static function init() {
            if (!isset(self::$base_url)) {
                self::$base_url = getBaseUrl();
            }
        }
        
        /**
         * Obtener URL completa para una ruta
         */
        public static function url($route_name, $params = []) {
            // Asegurarse de que el router esté inicializado
            if (!isset(self::$base_url)) {
                self::init();
            }
            
            if (!isset(self::$routes[$route_name])) {
                error_log("Ruta no encontrada: $route_name");
                return self::$base_url . 'views/modulos/dashboard.php'; // Ruta por defecto
            }
            
            $url = self::$base_url . self::$routes[$route_name];
            
            // Agregar parámetros si existen
            if (!empty($params)) {
                $url .= '?' . http_build_query($params);
            }
            
            return $url;
        }
        
        /**
         * Método rápido para rutas comunes
         */
        public static function __callStatic($name, $arguments) {
            if (strpos($name, 'to_') === 0) {
                $route_name = substr($name, 3);
                return self::url($route_name, $arguments[0] ?? []);
            }
            return self::$base_url ?? '';
        }
        
        /**
         * Obtener la URL base
         */
        public static function base() {
            if (!isset(self::$base_url)) {
                self::init();
            }
            return self::$base_url;
        }
        
        /**
         * Agregar rutas dinámicamente
         */
        public static function addRoute($name, $path) {
            self::$routes[$name] = $path;
        }
        
        /**
         * Obtener todas las rutas (para debugging)
         */
        public static function getRoutes() {
            return self::$routes;
        }
    }
}

// ============================================
// INICIALIZACIÓN SEGURA DEL ROUTER
// ============================================

// Solo inicializar el Router si no se ha hecho antes
if (!isset($GLOBALS['router_initialized'])) {
    if (class_exists('Router') && method_exists('Router', 'init')) {
        Router::init();
        $GLOBALS['router_initialized'] = true;
    }
}

// Definir funciones helper solo si no existen
if (!function_exists('route')) {
    function route($name, $params = []) {
        return Router::url($name, $params);
    }
}

if (!function_exists('base_url')) {
    function base_url($path = '') {
        return Router::base() . ltrim($path, '/');
    }
}

// ============================================
// OBTENER DATOS DEL USUARIO SEGURO
// ============================================

// Obtener datos del usuario de forma segura
$usuario = $_SESSION['usuario'] ?? [
    'nombre' => 'Admin',
    'email' => 'admin@localhost',
    'rol' => 'Administrador'
];

// Extraer datos individuales
$nombre_usuario = $usuario['nombre'] ?? 'Usuario';
$email_usuario = $usuario['email'] ?? 'user@example.com';
$rol_usuario = $usuario['rol'] ?? 'Usuario';
$inicial_usuario = strtoupper(substr($nombre_usuario, 0, 1));

// ============================================
// HTML DEL SIDEBAR
// ============================================
?>

  <aside id="sidebar" class="z-20 flex-shrink-0 w-72 transition-all duration-300 bg-white border-r border-slate-200 p-4 fixed inset-y-0 left-0 flex flex-col overflow-y-auto" aria-label="Barra lateral de navegación">
    <div>
      <div class="flex items-center gap-3 px-1 mb-4">
        <div class="w-10 h-10 flex items-center justify-center rounded-lg bg-indigo-50 text-indigo-600 font-bold shadow-sm">
          <?php echo htmlspecialchars($inicial_usuario); ?>
        </div>
        <div>
          <h2 class="text-lg font-semibold text-slate-900"><?php echo htmlspecialchars($nombre_usuario); ?></h2>
          <p class="text-xs text-slate-500"><?php echo htmlspecialchars($rol_usuario); ?></p>
        </div>
      </div>

      <nav class="mt-6">
        <ul class="space-y-1">
          <!-- DASHBOARD -->
          <li>
            <a href="<?php echo Router::url('dashboard'); ?>" 
               class="group flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-700 font-normal hover:bg-indigo-100 hover:border-indigo-200 hover:text-indigo-700 transition-all duration-200">
              <svg class="w-5 h-5 text-indigo-400 group-hover:text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l9-9 9 9M3 12v9a2 2 0 002 2h14a2 2 0 002-2v-9"></path>
              </svg>
              <span class="text-sm">Dashboard</span>
            </a>
          </li>

          <!-- PLANIFICAR RECURSOS -->
            <li>
              <a href="<?php echo Router::url('planificar'); ?>" 
                class="group flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-700 font-normal hover:bg-indigo-100 hover:border-indigo-200 hover:text-indigo-700 transition-all duration-200">
                  <svg class="w-5 h-5 text-indigo-400 group-hover:text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                  </svg>
                  <span class="text-sm">Planificar Recursos</span>
              </a>
          </li>

          <!-- ESTIMAR RECURSOS -->
          <li>
            <a href="<?php echo Router::url('estimar'); ?>" 
               class="group flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-700 font-normal hover:bg-indigo-100 hover:border-indigo-200 hover:text-indigo-700 transition-all duration-200">
              <svg class="w-5 h-5 text-indigo-400 group-hover:text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
              </svg>
              Estimar Recursos
            </a>
          </li>
          
          <li>
            <a href="<?php echo Router::url('adquirir'); ?>" 
               class="group flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-700 font-normal hover:bg-indigo-100 hover:border-indigo-200 hover:text-indigo-700 transition-all duration-200">
              <svg class="w-5 h-5 text-indigo-400 group-hover:text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
              Adquirir Recursos
            </a>
          </li>
          
          <li>
            <a href="<?php echo Router::url('desarrollar'); ?>" 
               class="group flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-700 font-normal hover:bg-indigo-100 hover:border-indigo-200 hover:text-indigo-700 transition-all duration-200">
              <svg class="w-5 h-5 text-indigo-400 group-hover:text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
              </svg>
              Desarrollar Equipo
            </a>
          </li>
          
          <li>
            <a href="<?php echo Router::url('dirigir'); ?>" 
               class="group flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-700 font-normal hover:bg-indigo-100 hover:border-indigo-200 hover:text-indigo-700 transition-all duration-200">
              <svg class="w-5 h-5 text-indigo-400 group-hover:text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
              </svg>
              Dirigir Equipo
            </a>
          </li>
          
          <li>
            <a href="<?php echo Router::url('controlar_recursos'); ?>" 
               class="group flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-700 font-normal hover:bg-indigo-100 hover:border-indigo-200 hover:text-indigo-700 transition-all duration-200">
              <svg class="w-5 h-5 text-indigo-400 group-hover:text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
              Controlar Recursos
            </a>
          </li>
        </ul>
      </nav>
    </div>

    <div class="mt-auto pt-6 border-t border-slate-200">
      <div class="px-3 mb-4">
        <div class="text-xs font-medium text-slate-500 uppercase tracking-wider mb-2">Sesión activa</div>
        <div class="flex items-center gap-3">
          <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center">
            <span class="text-sm font-semibold text-indigo-600">
              <?php echo htmlspecialchars($inicial_usuario); ?>
            </span>
          </div>
          <div class="flex-1">
            <div class="text-sm font-medium text-slate-800"><?php echo htmlspecialchars($nombre_usuario); ?></div>
            <div class="text-xs text-slate-500"><?php echo htmlspecialchars($rol_usuario); ?></div>
          </div>
        </div>
      </div>
      
      <a href="<?php echo Router::url('logout'); ?>" 
         class="w-full inline-flex items-center justify-center gap-2 py-2.5 px-3 rounded-lg bg-red-50 text-red-700 text-sm font-medium hover:bg-red-100 hover:text-red-800 transition-all duration-200 group">
        <svg class="w-4 h-4 group-hover:rotate-90 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
        </svg>
        Cerrar sesión
      </a>
    </div>
  </aside>


<!-- Script para el menú desplegable -->
<script>
function toggleSubmenu(id) {
    const submenu = document.getElementById(id);
    const icon = document.getElementById('icon-' + id);
    
    if (submenu.classList.contains('hidden')) {
        submenu.classList.remove('hidden');
        submenu.classList.add('block');
        icon.style.transform = 'rotate(180deg)';
    } else {
        submenu.classList.remove('block');
        submenu.classList.add('hidden');
        icon.style.transform = 'rotate(0deg)';
    }
}

// Cerrar menús al hacer clic fuera
document.addEventListener('click', function(event) {
    if (!event.target.closest('button[onclick^="toggleSubmenu"]')) {
        document.querySelectorAll('[id^="submenu-"]').forEach(submenu => {
            submenu.classList.add('hidden');
            submenu.classList.remove('block');
            const id = submenu.id;
            const icon = document.getElementById('icon-' + id);
            if (icon) icon.style.transform = 'rotate(0deg)';
        });
    }
});

// Estado inicial: cerrar todos los submenús al cargar
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('[id^="submenu-"]').forEach(submenu => {
        submenu.classList.add('hidden');
    });
});
</script>
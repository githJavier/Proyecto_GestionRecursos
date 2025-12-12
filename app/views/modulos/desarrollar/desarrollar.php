<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Desarrollar Equipo | PMBOK 6</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        /* Mismo estilo base que los anteriores procesos */
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }
        
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);
        }
        
        .hover-lift {
            transition: all 0.3s ease;
        }
        
        .hover-lift:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.12);
        }
        
        .gradient-bg-desarrollo {
            background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
        }
        
        .gradient-bg-proceso4 {
            background: linear-gradient(135deg, #38b2ac 0%, #319795 100%);
        }
        
        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
        }
        
        /* Colores específicos para niveles de experiencia */
        .badge-junior { background-color: #fed7d7; color: #9b2c2c; }
        .badge-intermedio { background-color: #feebc8; color: #9c4221; }
        .badge-senior { background-color: #c6f6d5; color: #276749; }
        .badge-experto { background-color: #e9d8fd; color: #553c9a; }
        
        /* Colores para estados de capacitación */
        .badge-pendiente { background-color: #fed7d7; color: #9b2c2c; }
        .badge-en_curso { background-color: #bee3f8; color: #2c5282; }
        .badge-completada { background-color: #c6f6d5; color: #276749; }
        .badge-cancelada { background-color: #e2e8f0; color: #4a5568; }
        .badge-atrasada { background-color: #fbd38d; color: #c05621; }
        
        .tab-desarrollo {
            padding: 0.75rem 1.5rem;
            border-radius: 0.75rem;
            font-weight: 500;
            transition: all 0.2s ease;
        }
        
        .tab-desarrollo.active {
            background: linear-gradient(135deg, #38b2ac 0%, #319795 100%);
            color: white;
            box-shadow: 0 4px 20px rgba(56, 178, 172, 0.3);
        }
        
        .tab-desarrollo:not(.active):hover {
            background-color: #edf2f7;
        }
        
        .progress-bar {
            height: 0.5rem;
            border-radius: 0.25rem;
            overflow: hidden;
            background-color: #e2e8f0;
        }
        
        .progress-fill {
            height: 100%;
            border-radius: 0.25rem;
            transition: width 0.3s ease;
        }
        
        .skill-tag {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            background-color: #edf2f7;
            color: #4a5568;
            border-radius: 0.5rem;
            font-size: 0.75rem;
            margin: 0.125rem;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-teal-50">
    
    <!-- Sidebar -->
    <?php include __DIR__ . '/../../componentes/sidebar.php'; ?>
    
    <main class="ml-72 p-8">
        
        <!-- ========== MENSAJES GLOBALES ========== -->
        <?php if (isset($mensaje) && !empty($mensaje)): ?>
        <div class="mb-6 p-4 rounded-lg border <?php echo $tipo_mensaje === 'success' ? 'bg-gradient-to-r from-green-50 to-emerald-50 border-green-200 text-green-700' : 'bg-gradient-to-r from-red-50 to-rose-50 border-red-200 text-red-700'; ?> shadow-sm">
            <div class="flex items-center">
                <i class="fas <?php echo $tipo_mensaje === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'; ?> mr-3 text-lg"></i>
                <span class="font-medium"><?php echo htmlspecialchars($mensaje); ?></span>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- ========== ENCABEZADO PRINCIPAL ========== -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Desarrollar Equipo</h1>
                <p class="text-gray-600">Proceso 4 PMBOK 6 | Desarrollo de habilidades del equipo del proyecto</p>
            </div>
            <div class="flex items-center gap-3 bg-white rounded-xl px-4 py-3 shadow-sm border border-gray-200">
                <div class="w-10 h-10 bg-gradient-to-r from-teal-500 to-green-600 rounded-full flex items-center justify-center text-white font-bold">
                    <?php echo isset($_SESSION['usuario']) ? strtoupper(substr($_SESSION['usuario']['nombre'], 0, 1)) : 'D'; ?>
                </div>
                <div>
                    <div class="text-sm text-gray-500">Líder de Equipo</div>
                    <div class="font-medium text-gray-800"><?php echo isset($_SESSION['usuario']) ? htmlspecialchars($_SESSION['usuario']['nombre']) : 'Líder'; ?></div>
                </div>
            </div>
        </div>
        
        <!-- ========== INDICADORES DE PROCESO PMBOK ========== -->
        <div class="grid grid-cols-1 md:grid-cols-6 gap-4 mb-8">
            <?php for($i = 1; $i <= 3; $i++): ?>
            <div class="bg-white p-4 rounded-xl border border-gray-200 opacity-70">
                <div class="text-sm text-gray-400">Proceso <?php echo $i; ?></div>
                <div class="text-lg font-semibold text-gray-400">
                    <?php 
                    $nombres = [1 => 'Planificar', 2 => 'Estimar', 3 => 'Adquirir'];
                    echo $nombres[$i];
                    ?>
                </div>
                <div class="mt-2 text-xs text-green-600 flex items-center">
                    <i class="fas fa-check-circle mr-1"></i> Completado
                </div>
            </div>
            <?php endfor; ?>
            
            <div class="gradient-bg-proceso4 p-4 rounded-xl text-white hover-lift shadow-lg">
                <div class="text-sm font-medium">Proceso 4</div>
                <div class="text-lg font-semibold">Desarrollar</div>
                <div class="mt-2 text-xs flex items-center">
                    <i class="fas fa-users mr-1"></i> En Progreso
                </div>
            </div>
            
            <?php for($i = 5; $i <= 6; $i++): ?>
            <div class="bg-white p-4 rounded-xl border border-gray-200 opacity-70">
                <div class="text-sm text-gray-400">Proceso <?php echo $i; ?></div>
                <div class="text-lg font-semibold text-gray-400">
                    <?php 
                    $nombres = [5 => 'Dirigir', 6 => 'Controlar'];
                    echo $nombres[$i];
                    ?>
                </div>
                <div class="mt-2 text-xs text-gray-400">
                    <i class="fas fa-lock mr-1"></i> Bloqueado
                </div>
            </div>
            <?php endfor; ?>
        </div>
        
        <!-- ========== NAVEGACIÓN INTERNA DEL PROCESO 4 ========== -->
        <div class="flex gap-3 mb-8 bg-white rounded-2xl p-2 shadow-sm border border-gray-200">
            <a href="?accion=listar" 
               class="tab-desarrollo <?php echo ($accion === 'listar' || !isset($accion)) ? 'active' : ''; ?>">
                <i class="fas fa-users mr-2"></i>
                Equipo del Proyecto
            </a>
            <a href="?accion=asignar" 
               class="tab-desarrollo <?php echo $accion === 'asignar' ? 'active' : ''; ?>">
                <i class="fas fa-user-plus mr-2"></i>
                Asignar Recurso
            </a>
            <a href="?accion=capacitaciones" 
               class="tab-desarrollo <?php echo $accion === 'capacitaciones' ? 'active' : ''; ?>">
                <i class="fas fa-graduation-cap mr-2"></i>
                Capacitaciones
            </a>
            <a href="?accion=reportes" 
               class="tab-desarrollo <?php echo $accion === 'reportes' ? 'active' : ''; ?>">
                <i class="fas fa-chart-bar mr-2"></i>
                Reportes
            </a>
        </div>
        
        <!-- ========== SECCIÓN: LISTAR RECURSOS HUMANOS ========== -->
        <?php if ($accion === 'listar' || !isset($accion)): ?>
        
        <!-- Filtros -->
        <div class="mb-8 glass-card rounded-2xl p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Filtros del Equipo</h2>
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <input type="hidden" name="accion" value="listar">
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Proyecto</label>
                    <select name="proyecto_id" class="form-input w-full px-4 py-2.5 border border-gray-300 rounded-xl">
                        <option value="">Todos los proyectos</option>
                        <?php if (isset($proyectos)): ?>
                            <?php foreach ($proyectos as $proyecto): ?>
                                <option value="<?php echo htmlspecialchars($proyecto['id'] ?? ''); ?>" 
                                    <?php echo (isset($_GET['proyecto_id']) && $_GET['proyecto_id'] == $proyecto['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($proyecto['nombre'] ?? ''); ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nivel Experiencia</label>
                    <select name="nivel_experiencia" class="form-input w-full px-4 py-2.5 border border-gray-300 rounded-xl">
                        <option value="">Todos los niveles</option>
                        <option value="junior" <?php echo (isset($_GET['nivel_experiencia']) && $_GET['nivel_experiencia'] == 'junior') ? 'selected' : ''; ?>>Junior</option>
                        <option value="intermedio" <?php echo (isset($_GET['nivel_experiencia']) && $_GET['nivel_experiencia'] == 'intermedio') ? 'selected' : ''; ?>>Intermedio</option>
                        <option value="senior" <?php echo (isset($_GET['nivel_experiencia']) && $_GET['nivel_experiencia'] == 'senior') ? 'selected' : ''; ?>>Senior</option>
                        <option value="experto" <?php echo (isset($_GET['nivel_experiencia']) && $_GET['nivel_experiencia'] == 'experto') ? 'selected' : ''; ?>>Experto</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Rol en Proyecto</label>
                    <input type="text" name="rol_proyecto" 
                           value="<?php echo isset($_GET['rol_proyecto']) ? htmlspecialchars($_GET['rol_proyecto']) : ''; ?>"
                           class="form-input w-full px-4 py-2.5 border border-gray-300 rounded-xl"
                           placeholder="Ej: Desarrollador, Diseñador...">
                </div>
                
                <div class="flex items-end">
                    <button type="submit" 
                            class="w-full gradient-bg-proceso4 hover:opacity-90 text-white px-4 py-2.5 rounded-xl font-medium flex items-center justify-center gap-2">
                        <i class="fas fa-filter"></i>
                        Aplicar Filtros
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Busca la sección de "Recursos que necesitan capacitación" (alrededor de línea 200) -->

        <!-- Recursos que necesitan capacitación -->
        <?php if (isset($necesitan_capacitacion) && count($necesitan_capacitacion) > 0): ?>
        <div class="mb-8 glass-card rounded-2xl p-6 border-2 border-yellow-200">
            <div class="flex items-center justify-between gap-3 mb-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-r from-yellow-400 to-orange-400 rounded-xl flex items-center justify-center">
                        <i class="fas fa-exclamation-triangle text-white"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">Recursos que Necesitan Capacitación</h3>
                </div>
                <span class="status-badge bg-yellow-100 text-yellow-800">
                    <i class="fas fa-users mr-1"></i>
                    <?php echo count($necesitan_capacitacion); ?> recursos
                </span>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <?php foreach($necesitan_capacitacion as $recurso): 
                    // Verificar si ya tiene capacitaciones relacionadas
                    $tiene_capacitaciones_relacionadas = false;
                    if (isset($recurso['capacitaciones_relacionadas']) && $recurso['capacitaciones_relacionadas'] > 0) {
                        $tiene_capacitaciones_relacionadas = true;
                        continue; // Saltar este recurso si ya tiene capacitaciones relacionadas
                    }
                ?>
                <div class="bg-white p-4 rounded-xl border border-yellow-100 hover:shadow transition-all duration-200">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-yellow-100 to-orange-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-yellow-600"></i>
                        </div>
                        <div class="flex-1">
                            <div class="flex justify-between items-start">
                                <div>
                                    <div class="font-medium text-gray-900"><?php echo htmlspecialchars($recurso['usuario_nombre'] ?? ''); ?></div>
                                    <div class="text-sm text-gray-500 mb-2"><?php echo htmlspecialchars($recurso['proyecto_nombre'] ?? ''); ?></div>
                                </div>
                                <span class="text-xs px-2 py-1 bg-blue-100 text-blue-800 rounded-lg">
                                    <?php 
                                    $nivel = $recurso['nivel_experiencia'] ?? 'intermedio';
                                    echo ucfirst($nivel);
                                    ?>
                                </span>
                            </div>
                            
                            <div class="text-xs text-yellow-700 bg-yellow-50 p-2 rounded-lg mb-3">
                                <i class="fas fa-lightbulb mr-1"></i>
                                <span class="font-medium">Necesidad:</span>
                                <?php echo htmlspecialchars(substr($recurso['capacitacion_requerida'] ?? 'Sin especificar', 0, 80)); ?>
                                <?php if (strlen($recurso['capacitacion_requerida'] ?? '') > 80): ?>...<?php endif; ?>
                            </div>
                            
                            <div class="flex justify-between items-center">
                                <a href="?accion=crear_capacitacion&recurso_id=<?php echo $recurso['id']; ?>" 
                                class="text-xs bg-gradient-to-r from-yellow-500 to-orange-500 hover:opacity-90 text-white px-3 py-1.5 rounded-lg font-medium flex items-center gap-1">
                                    <i class="fas fa-plus text-xs"></i>
                                    Programar capacitación
                                </a>
                                
                                <button onclick="marcarComoResuelto(<?php echo $recurso['id']; ?>)" 
                                        class="text-xs bg-gradient-to-r from-green-500 to-emerald-500 hover:opacity-90 text-white px-3 py-1.5 rounded-lg font-medium flex items-center gap-1"
                                        title="Marcar como resuelto manualmente">
                                    <i class="fas fa-check text-xs"></i>
                                    Resuelto
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Modal para marcar como resuelto -->
        <div id="modalResuelto" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50 hidden">
            <div class="bg-white rounded-2xl p-8 max-w-md w-full mx-4">
                <div class="text-center mb-6">
                    <div class="w-16 h-16 mx-auto bg-gradient-to-r from-green-100 to-emerald-100 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-check-circle text-2xl text-green-600"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">¿Marcar necesidad como resuelta?</h3>
                    <p class="text-gray-600 mb-6">
                        Esta acción marcará la necesidad de capacitación como resuelta sin crear una capacitación.
                    </p>
                    <div class="text-sm text-gray-500 mb-6">
                        <i class="fas fa-info-circle mr-1"></i>
                        Si ya programaste una capacitación relacionada, se marcará automáticamente.
                    </div>
                </div>
                <div class="flex justify-center gap-4">
                    <button type="button" onclick="cerrarModalResuelto()"
                            class="px-6 py-3 border-2 border-gray-300 text-gray-700 hover:bg-gray-50 rounded-xl font-medium flex-1">
                        Cancelar
                    </button>
                    <form method="POST" action="" id="formResuelto" class="flex-1">
                        <input type="hidden" name="accion" value="marcar_resuelto">
                        <input type="hidden" name="id" id="recursoIdResuelto" value="">
                        <button type="submit" 
                                class="w-full px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-500 hover:opacity-90 text-white rounded-xl font-medium">
                            Confirmar
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <script>
            function marcarComoResuelto(recursoId) {
                document.getElementById('recursoIdResuelto').value = recursoId;
                document.getElementById('modalResuelto').classList.remove('hidden');
            }
            
            function cerrarModalResuelto() {
                document.getElementById('modalResuelto').classList.add('hidden');
            }
            
            // Cerrar modal al hacer clic fuera
            document.getElementById('modalResuelto').addEventListener('click', function(e) {
                if (e.target.id === 'modalResuelto') {
                    cerrarModalResuelto();
                }
            });
        </script>
        <?php endif; ?>
        
        <!-- Estadísticas rápidas -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <?php 
            // Calcular estadísticas
            $total_recursos = isset($recursos) ? count($recursos) : 0;
            $total_capacitaciones = 0;
            $costo_total_capacitaciones = 0;
            $horas_totales = 0;
            
            if (isset($recursos) && is_array($recursos)) {
                foreach ($recursos as $recurso) {
                    $total_capacitaciones += $recurso['total_capacitaciones'] ?? 0;
                    $costo_total_capacitaciones += $recurso['costo_total_capacitaciones'] ?? 0;
                    $horas_totales += $recurso['horas_realizadas'] ?? 0;
                }
            }
            ?>
            
            <div class="stat-card bg-white rounded-2xl p-6 border border-gray-200 shadow-sm hover-lift">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-gradient-to-r from-teal-50 to-teal-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-users text-teal-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Recursos Totales</p>
                        <p class="text-2xl font-bold text-gray-800">
                            <?php echo $total_recursos; ?>
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="stat-card bg-white rounded-2xl p-6 border border-gray-200 shadow-sm hover-lift">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-gradient-to-r from-blue-50 to-blue-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-graduation-cap text-blue-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Capacitaciones</p>
                        <p class="text-2xl font-bold text-gray-800">
                            <?php echo $total_capacitaciones; ?>
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="stat-card bg-white rounded-2xl p-6 border border-gray-200 shadow-sm hover-lift">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-gradient-to-r from-green-50 to-green-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-clock text-green-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Horas Trabajadas</p>
                        <p class="text-2xl font-bold text-gray-800">
                            <?php echo $horas_totales; ?>
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="stat-card bg-white rounded-2xl p-6 border border-gray-200 shadow-sm hover-lift">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-gradient-to-r from-purple-50 to-purple-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-money-bill-wave text-purple-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Inversión en Capacitación</p>
                        <p class="text-2xl font-bold text-gray-800">
                            $<?php echo number_format($costo_total_capacitaciones, 2); ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Tabla de recursos humanos -->
        <div class="glass-card rounded-2xl p-6 mb-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Equipo del Proyecto</h2>
                <a href="?accion=asignar" 
                   class="gradient-bg-proceso4 hover:opacity-90 text-white px-5 py-2.5 rounded-xl font-medium flex items-center gap-2 hover-lift shadow-lg">
                    <i class="fas fa-user-plus"></i>
                    Asignar Nuevo Recurso
                </a>
            </div>
            
            <?php if (empty($recursos) || !is_array($recursos)): ?>
            <div class="text-center py-16">
                <div class="w-24 h-24 mx-auto bg-gradient-to-br from-teal-50 to-teal-100 rounded-full flex items-center justify-center mb-6">
                    <i class="fas fa-users text-4xl text-gray-400"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-700 mb-3">No hay recursos asignados</h3>
                <p class="text-gray-500 mb-6 max-w-md mx-auto">Comienza asignando recursos humanos a tus proyectos</p>
                <a href="?accion=asignar" 
                   class="gradient-bg-proceso4 hover:opacity-90 text-white px-6 py-3 rounded-xl font-medium inline-flex items-center gap-2 shadow-lg hover-lift">
                    <i class="fas fa-user-plus"></i>
                    Asignar primer recurso
                </a>
            </div>
            <?php else: ?>
            <div class="overflow-x-auto rounded-xl border border-gray-200">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gradient-to-r from-teal-50 to-teal-100">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 uppercase">Recurso / Proyecto</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 uppercase">Rol / Experiencia</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 uppercase">Horas / Capacitación</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 uppercase">Habilidades / Necesidades</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 uppercase w-64">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        <?php foreach($recursos as $recurso): 
                            // Colores para niveles de experiencia
                            $nivel_colores = [
                                'junior' => ['bg' => 'badge-junior', 'icon' => 'fa-user-graduate'],
                                'intermedio' => ['bg' => 'badge-intermedio', 'icon' => 'fa-user'],
                                'senior' => ['bg' => 'badge-senior', 'icon' => 'fa-user-tie'],
                                'experto' => ['bg' => 'badge-experto', 'icon' => 'fa-user-ninja']
                            ];
                            $nivel = $recurso['nivel_experiencia'] ?? 'intermedio';
                            $nivel_style = $nivel_colores[$nivel] ?? $nivel_colores['intermedio'];
                            
                            // Calcular porcentaje de horas
                            $horas_asignadas = $recurso['horas_asignadas'] ?? 1;
                            $horas_realizadas = $recurso['horas_realizadas'] ?? 0;
                            $porcentaje_horas = $horas_asignadas > 0 ? ($horas_realizadas / $horas_asignadas) * 100 : 0;
                            
                            // Estado de capacitación
                            $total_capacitaciones = $recurso['total_capacitaciones'] ?? 0;
                            $completadas = $recurso['capacitaciones_completadas'] ?? 0;
                            $porcentaje_capacitacion = $total_capacitaciones > 0 ? ($completadas / $total_capacitaciones) * 100 : 0;
                        ?>
                        <tr class="table-row-hover hover:bg-teal-50/30">
                            <td class="px-6 py-4">
                                <div class="flex items-start gap-4">
                                    <div class="w-12 h-12 bg-gradient-to-br from-teal-100 to-green-100 rounded-xl flex items-center justify-center mt-1">
                                        <i class="fas fa-user text-teal-600"></i>
                                    </div>
                                    <div class="flex-1">
                                        <div class="font-semibold text-gray-900 mb-1"><?php echo htmlspecialchars($recurso['usuario_nombre'] ?? 'Sin nombre'); ?></div>
                                        <div class="text-sm text-gray-600 mb-2"><?php echo htmlspecialchars($recurso['proyecto_nombre'] ?? 'Sin proyecto'); ?></div>
                                        <div class="flex items-center gap-3 text-xs">
                                            <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded-lg">
                                                <i class="fas fa-envelope mr-1"></i>
                                                <?php echo htmlspecialchars($recurso['usuario_email'] ?? ''); ?>
                                            </span>
                                            <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded-lg">
                                                <?php echo htmlspecialchars($recurso['usuario_departamento'] ?? 'Sin depto'); ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="space-y-2">
                                    <div>
                                        <div class="text-sm text-gray-500">Rol en Proyecto</div>
                                        <div class="font-medium text-gray-900"><?php echo htmlspecialchars($recurso['rol_proyecto'] ?? 'Sin rol'); ?></div>
                                    </div>
                                    <div class="status-badge <?php echo $nivel_style['bg']; ?>">
                                        <i class="fas <?php echo $nivel_style['icon']; ?> text-xs"></i>
                                        <?php echo ucfirst($nivel); ?>
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        Asignado: <?php echo isset($recurso['fecha_asignacion']) ? date('d/m/Y', strtotime($recurso['fecha_asignacion'])) : 'N/A'; ?>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="space-y-3">
                                    <!-- Horas trabajadas -->
                                    <div>
                                        <div class="flex justify-between text-sm text-gray-500 mb-1">
                                            <span>Horas</span>
                                            <span><?php echo $horas_realizadas; ?>/<?php echo $horas_asignadas; ?></span>
                                        </div>
                                        <div class="progress-bar">
                                            <div class="progress-fill bg-green-500" style="width: <?php echo min($porcentaje_horas, 100); ?>%"></div>
                                        </div>
                                        <div class="text-xs text-gray-500 mt-1">
                                            <?php echo number_format($porcentaje_horas, 1); ?>% completado
                                        </div>
                                    </div>
                                    
                                    <!-- Capacitaciones -->
                                    <div>
                                        <div class="flex justify-between text-sm text-gray-500 mb-1">
                                            <span>Capacitaciones</span>
                                            <span><?php echo $completadas; ?>/<?php echo $total_capacitaciones; ?></span>
                                        </div>
                                        <div class="progress-bar">
                                            <div class="progress-fill bg-blue-500" style="width: <?php echo min($porcentaje_capacitacion, 100); ?>%"></div>
                                        </div>
                                        <div class="text-xs text-gray-500 mt-1">
                                            <?php echo number_format($porcentaje_capacitacion, 1); ?>% completado
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="space-y-3">
                                    <!-- Habilidades -->
                                    <div>
                                        <div class="text-sm text-gray-500 mb-2">Habilidades</div>
                                        <div class="flex flex-wrap gap-1">
                                            <?php 
                                            $habilidades = explode(',', $recurso['habilidades'] ?? '');
                                            foreach(array_slice($habilidades, 0, 3) as $habilidad):
                                                if (!empty(trim($habilidad))):
                                            ?>
                                            <span class="skill-tag"><?php echo htmlspecialchars(trim($habilidad)); ?></span>
                                            <?php 
                                                endif;
                                            endforeach; 
                                            if (count($habilidades) > 3): ?>
                                            <span class="skill-tag text-blue-600">+<?php echo count($habilidades) - 3; ?> más</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    
                                    <!-- Necesidades de capacitación -->
                                    <?php if (!empty($recurso['capacitacion_requerida'])): ?>
                                    <div>
                                        <div class="text-sm text-gray-500 mb-1">Capacitación Requerida</div>
                                        <div class="text-xs text-yellow-700 bg-yellow-50 p-2 rounded-lg">
                                            <i class="fas fa-lightbulb mr-1"></i>
                                            <?php echo htmlspecialchars(substr($recurso['capacitacion_requerida'], 0, 100)); ?>
                                            <?php if (strlen($recurso['capacitacion_requerida']) > 100): ?>...<?php endif; ?>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col gap-3 min-w-[220px]">
                                    <!-- Información rápida -->
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <div class="text-xs font-medium text-gray-500 mb-1">ID</div>
                                            <div class="text-sm font-semibold text-gray-700">#<?php echo $recurso['id']; ?></div>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-xs font-medium text-gray-500 mb-1">Capacitaciones</div>
                                            <div class="text-sm font-semibold text-blue-600"><?php echo $total_capacitaciones; ?></div>
                                        </div>
                                    </div>
                                    
                                    <!-- Acciones principales -->
                                    <div class="flex items-center justify-between gap-2">
                                        <div class="flex items-center gap-2">
                                            <a href="?accion=ver&id=<?php echo $recurso['id']; ?>" 
                                               class="w-9 h-9 bg-blue-50 hover:bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center hover:scale-105 transition-all duration-200 group relative"
                                               title="Ver detalles">
                                                <i class="fas fa-eye text-sm"></i>
                                                <span class="absolute -top-8 left-1/2 transform -translate-x-1/2 bg-gray-800 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">
                                                    Ver detalles
                                                </span>
                                            </a>
                                            
                                            <?php if ($usuario_rol != 'miembro_equipo'): ?>
                                            <a href="?accion=editar_recurso&id=<?php echo $recurso['id']; ?>" 
                                               class="w-9 h-9 bg-emerald-50 hover:bg-emerald-100 text-emerald-600 rounded-lg flex items-center justify-center hover:scale-105 transition-all duration-200 group relative"
                                               title="Editar">
                                                <i class="fas fa-edit text-sm"></i>
                                                <span class="absolute -top-8 left-1/2 transform -translate-x-1/2 bg-gray-800 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">
                                                    Editar
                                                </span>
                                            </a>
                                            
                                            <!-- Botón para agregar capacitación -->
                                            <a href="?accion=crear_capacitacion&recurso_id=<?php echo $recurso['id']; ?>" 
                                               class="w-9 h-9 bg-purple-50 hover:bg-purple-100 text-purple-600 rounded-lg flex items-center justify-center hover:scale-105 transition-all duration-200 group relative"
                                               title="Agregar capacitación">
                                                <i class="fas fa-graduation-cap text-sm"></i>
                                                <span class="absolute -top-8 left-1/2 transform -translate-x-1/2 bg-gray-800 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">
                                                    Capacitar
                                                </span>
                                            </a>
                                            <?php endif; ?>
                                        </div>
                                        
                                        <?php if ($usuario_rol != 'miembro_equipo'): ?>
                                        <form method="POST" action="" 
                                              onsubmit="return confirm('¿Estás seguro de eliminar este recurso?');">
                                            <input type="hidden" name="accion" value="eliminar_recurso">
                                            <input type="hidden" name="id" value="<?php echo $recurso['id']; ?>">
                                            <button type="submit" 
                                                    class="w-9 h-9 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg flex items-center justify-center hover:scale-105 transition-all duration-200 group relative"
                                                    title="Eliminar">
                                                <i class="fas fa-trash text-sm"></i>
                                                <span class="absolute -top-8 left-1/2 transform -translate-x-1/2 bg-gray-800 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">
                                                    Eliminar
                                                </span>
                                            </button>
                                        </form>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <!-- Formulario rápido para actualizar horas -->
                                    <div class="pt-2 border-t border-gray-100">
                                        <form method="POST" action="" class="flex items-center gap-2">
                                            <input type="hidden" name="accion" value="actualizar_horas">
                                            <input type="hidden" name="id" value="<?php echo $recurso['id']; ?>">
                                            <input type="number" name="horas_realizadas" 
                                                   value="<?php echo $horas_realizadas; ?>"
                                                   class="w-20 px-2 py-1 text-sm border border-gray-300 rounded-lg"
                                                   min="0" max="9999"
                                                   placeholder="Horas">
                                            <button type="submit" 
                                                    class="px-3 py-1 bg-gradient-to-r from-teal-50 to-teal-100 hover:from-teal-100 hover:to-teal-200 text-teal-700 text-xs font-medium rounded-lg flex items-center gap-1">
                                                <i class="fas fa-save text-xs"></i>
                                                Actualizar
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>
        
        <?php endif; ?>
        <!-- ========== FIN SECCIÓN LISTAR ========== -->
        
        <!-- ========== SECCIÓN: ASIGNAR RECURSO ========== -->
        <?php if ($accion === 'asignar'): ?>

        <div class="max-w-4xl mx-auto">
            <div class="glass-card rounded-2xl p-8">
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-14 h-14 bg-gradient-to-br from-teal-100 to-green-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-user-plus text-2xl text-teal-600"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Asignar Recurso Humano</h2>
                        <p class="text-gray-600">Asignar miembros del equipo a proyectos para su desarrollo</p>
                    </div>
                </div>
                
                <!-- Mensajes de error -->
                <?php if (isset($errores) && !empty($errores)): ?>
                <div class="mb-6 p-4 rounded-lg border bg-gradient-to-r from-red-50 to-rose-50 border-red-200 text-red-700 shadow-sm">
                    <div class="flex items-center mb-2">
                        <i class="fas fa-exclamation-circle mr-3 text-lg"></i>
                        <span class="font-medium">Por favor, corrige los siguientes errores:</span>
                    </div>
                    <ul class="list-disc pl-10 mt-2 text-sm">
                        <?php foreach($errores as $campo => $mensaje): ?>
                            <li><span class="font-medium"><?php echo htmlspecialchars($campo); ?>:</span> <?php echo htmlspecialchars($mensaje); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endif; ?>
                
                <form method="POST" action="?accion=asignar_recurso" id="formAsignar" class="space-y-6">
                    <input type="hidden" name="accion" value="asignar_recurso">
                    
                    <!-- Paso 1: Seleccionar usuario y proyecto -->
                    <div class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-2xl p-6 mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Paso 1: Seleccionar Usuario y Proyecto</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-3">Usuario *</label>
                                <select name="usuario_id" required 
                                        class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                                        onchange="cargarDatosUsuario(this.value)">
                                    <option value="">Seleccionar usuario</option>
                                    <?php if (isset($usuarios_disponibles) && is_array($usuarios_disponibles)): ?>
                                        <?php foreach ($usuarios_disponibles as $usuario): ?>
                                        <option value="<?php echo htmlspecialchars($usuario['id']); ?>" 
                                                <?php echo (isset($datos_form['usuario_id']) && $datos_form['usuario_id'] == $usuario['id']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($usuario['nombre'] ?? ''); ?> 
                                            (<?php echo htmlspecialchars($usuario['email'] ?? ''); ?>)
                                        </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-3">Proyecto *</label>
                                <select name="proyecto_id" required 
                                        class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                                        onchange="cargarDatosProyecto(this.value)">
                                    <option value="">Seleccionar proyecto</option>
                                    <?php if (isset($proyectos_disponibles) && is_array($proyectos_disponibles)): ?>
                                        <?php foreach ($proyectos_disponibles as $proyecto): ?>
                                        <option value="<?php echo htmlspecialchars($proyecto['id']); ?>" 
                                                <?php echo (isset($datos_form['proyecto_id']) && $datos_form['proyecto_id'] == $proyecto['id']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($proyecto['nombre'] ?? ''); ?> 
                                            (<?php echo ucfirst($proyecto['estado'] ?? 'planificacion'); ?>)
                                        </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Información del usuario y proyecto seleccionados -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div id="info-usuario" class="bg-white p-6 rounded-xl border border-gray-200 hidden">
                            <h4 class="font-semibold text-gray-900 mb-3">Información del Usuario</h4>
                            <div class="space-y-2">
                                <div>
                                    <div class="text-sm text-gray-500">Nombre</div>
                                    <div class="font-medium text-gray-900" id="usuario-nombre">-</div>
                                </div>
                                <div>
                                    <div class="text-sm text-gray-500">Email</div>
                                    <div class="font-medium text-gray-900" id="usuario-email">-</div>
                                </div>
                                <div>
                                    <div class="text-sm text-gray-500">Rol</div>
                                    <div class="font-medium text-gray-900" id="usuario-rol">-</div>
                                </div>
                                <div>
                                    <div class="text-sm text-gray-500">Departamento</div>
                                    <div class="font-medium text-gray-900" id="usuario-departamento">-</div>
                                </div>
                            </div>
                        </div>
                        
                        <div id="info-proyecto" class="bg-white p-6 rounded-xl border border-gray-200 hidden">
                            <h4 class="font-semibold text-gray-900 mb-3">Información del Proyecto</h4>
                            <div class="space-y-2">
                                <div>
                                    <div class="text-sm text-gray-500">Proyecto</div>
                                    <div class="font-medium text-gray-900" id="proyecto-nombre">-</div>
                                </div>
                                <div>
                                    <div class="text-sm text-gray-500">Estado</div>
                                    <div class="font-medium text-gray-900" id="proyecto-estado">-</div>
                                </div>
                                <div>
                                    <div class="text-sm text-gray-500">Recursos Asignados</div>
                                    <div class="font-medium text-gray-900" id="proyecto-recursos">-</div>
                                </div>
                                <div>
                                    <div class="text-sm text-gray-500">Días Restantes</div>
                                    <div class="font-medium text-gray-900" id="proyecto-dias">-</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Paso 2: Información de asignación -->
                    <div class="bg-gradient-to-r from-teal-50 to-green-50 rounded-2xl p-6 mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Paso 2: Información de Asignación</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-3">Rol en el Proyecto *</label>
                                <input type="text" name="rol_proyecto" required 
                                    value="<?php echo isset($datos_form['rol_proyecto']) ? htmlspecialchars($datos_form['rol_proyecto']) : ''; ?>"
                                    class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl"
                                    placeholder="Ej: Desarrollador Full Stack, Diseñador UX/UI...">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-3">Nivel de Experiencia *</label>
                                <div class="grid grid-cols-4 gap-2" id="nivel-experiencia-container">
                                    <?php 
                                    // Definir niveles de experiencia
                                    $niveles_experiencia = [
                                        'junior' => ['color' => 'bg-red-100 border-red-300 text-red-800', 'icon' => 'fa-user-graduate'],
                                        'intermedio' => ['color' => 'bg-yellow-100 border-yellow-300 text-yellow-800', 'icon' => 'fa-user'],
                                        'senior' => ['color' => 'bg-green-100 border-green-300 text-green-800', 'icon' => 'fa-user-tie'],
                                        'experto' => ['color' => 'bg-purple-100 border-purple-300 text-purple-800', 'icon' => 'fa-user-ninja']
                                    ];
                                    
                                    // Determinar nivel seleccionado (sin valor por defecto)
                                    $nivel_seleccionado = isset($datos_form['nivel_experiencia']) ? $datos_form['nivel_experiencia'] : '';

                                    foreach ($niveles_experiencia as $valor => $estilo): 
                                        $is_checked = ($nivel_seleccionado == $valor);
                                    ?>
                                    <label class="radio-card p-2.5 border-2 rounded-lg <?php echo $estilo['color']; ?> 
                                        <?php echo $is_checked ? 'selected ring-2 ring-offset-1 ring-opacity-50' : ''; ?> 
                                        hover:scale-[1.02] transition-all duration-200 cursor-pointer">
                                        <input type="radio" name="nivel_experiencia" value="<?php echo $valor; ?>" 
                                            class="hidden nivel-experiencia-input"
                                            <?php echo $is_checked ? 'checked' : ''; ?>>
                                        <div class="flex flex-col items-center justify-center">
                                            <i class="fas <?php echo $estilo['icon']; ?> text-sm mb-1"></i>
                                            <div class="text-xs font-medium truncate"><?php echo ucfirst($valor); ?></div>
                                        </div>
                                    </label>
                                    <?php endforeach; ?>
                                </div>
                                
                                <!-- Mensaje de error oculto -->
                                <div id="nivel-error" class="hidden mt-2 text-sm text-red-600">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    <span>Por favor, selecciona un nivel de experiencia</span>
                                </div>
                                
                                <!-- Campo oculto para validación HTML5 -->
                                <input type="hidden" name="nivel_experiencia_validacion" 
                                    value="<?php echo $nivel_seleccionado; ?>" 
                                    required>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-3">Habilidades</label>
                                <textarea name="habilidades" rows="3" 
                                        class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl"
                                        placeholder="Lista de habilidades separadas por coma..."><?php echo isset($datos_form['habilidades']) ? htmlspecialchars($datos_form['habilidades']) : ''; ?></textarea>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-3">Capacitación Requerida</label>
                                <textarea name="capacitacion_requerida" rows="3" 
                                        class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl"
                                        placeholder="Capacitaciones específicas que necesita..."><?php echo isset($datos_form['capacitacion_requerida']) ? htmlspecialchars($datos_form['capacitacion_requerida']) : ''; ?></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Paso 3: Horas y fecha -->
                    <div class="bg-gradient-to-r from-amber-50 to-orange-50 rounded-2xl p-6 mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Paso 3: Horas y Fecha</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-3">Horas Asignadas</label>
                                <input type="number" name="horas_asignadas" min="0" step="1" 
                                    value="<?php echo isset($datos_form['horas_asignadas']) ? htmlspecialchars($datos_form['horas_asignadas']) : '160'; ?>"
                                    class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-3">Horas Realizadas</label>
                                <input type="number" name="horas_realizadas" min="0" step="1" 
                                    value="<?php echo isset($datos_form['horas_realizadas']) ? htmlspecialchars($datos_form['horas_realizadas']) : '0'; ?>"
                                    class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-3">Fecha de Asignación</label>
                                <input type="date" name="fecha_asignacion" 
                                    value="<?php echo isset($datos_form['fecha_asignacion']) ? htmlspecialchars($datos_form['fecha_asignacion']) : date('Y-m-d'); ?>"
                                    class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Botones de acción -->
                    <div class="pt-6 border-t border-gray-200">
                        <div class="flex justify-between">
                            <a href="?accion=listar" 
                            class="px-8 py-3 border-2 border-gray-300 text-gray-700 hover:bg-gray-50 rounded-xl font-medium transition-all hover-lift">
                                Cancelar
                            </a>
                            <div class="flex gap-4">
                                <button type="submit" name="guardar_y_continuar" 
                                        class="px-8 py-3 bg-gradient-to-r from-emerald-500 to-green-500 hover:opacity-90 text-white rounded-xl font-medium shadow-md hover-lift flex items-center gap-2">
                                    <i class="fas fa-save"></i>
                                    Guardar y Continuar
                                </button>
                                <button type="submit" name="guardar" 
                                        class="gradient-bg-proceso4 hover:opacity-90 text-white px-8 py-3 rounded-xl font-medium shadow-md hover-lift flex items-center gap-2">
                                    <i class="fas fa-check"></i>
                                    Asignar Recurso
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <script>
            // Datos de usuarios disponibles
            const usuariosData = {
                <?php if (isset($usuarios_disponibles) && is_array($usuarios_disponibles)): ?>
                    <?php foreach ($usuarios_disponibles as $usuario): ?>
                    "<?php echo $usuario['id']; ?>": {
                        nombre: "<?php echo addslashes($usuario['nombre'] ?? ''); ?>",
                        email: "<?php echo addslashes($usuario['email'] ?? ''); ?>",
                        rol: "<?php echo addslashes($usuario['rol'] ?? ''); ?>",
                        departamento: "<?php echo addslashes($usuario['departamento'] ?? ''); ?>",
                        proyectos: <?php echo $usuario['proyectos_actuales'] ?? 0; ?>
                    },
                    <?php endforeach; ?>
                <?php endif; ?>
            };
            
            // Datos de proyectos disponibles
            const proyectosData = {
                <?php if (isset($proyectos_disponibles) && is_array($proyectos_disponibles)): ?>
                    <?php foreach ($proyectos_disponibles as $proyecto): ?>
                    "<?php echo $proyecto['id']; ?>": {
                        nombre: "<?php echo addslashes($proyecto['nombre'] ?? ''); ?>",
                        estado: "<?php echo addslashes($proyecto['estado'] ?? ''); ?>",
                        recursos: <?php echo $proyecto['recursos_asignados'] ?? 0; ?>,
                        dias: <?php echo $proyecto['dias_restantes'] ?? 0; ?>,
                        gerente: "<?php echo addslashes($proyecto['gerente_nombre'] ?? ''); ?>"
                    },
                    <?php endforeach; ?>
                <?php endif; ?>
            };
            
            function cargarDatosUsuario(id) {
                const infoDiv = document.getElementById('info-usuario');
                if (id && usuariosData[id]) {
                    const data = usuariosData[id];
                    document.getElementById('usuario-nombre').textContent = data.nombre;
                    document.getElementById('usuario-email').textContent = data.email;
                    document.getElementById('usuario-rol').textContent = data.rol;
                    document.getElementById('usuario-departamento').textContent = data.departamento;
                    infoDiv.classList.remove('hidden');
                } else {
                    infoDiv.classList.add('hidden');
                }
            }
            
            function cargarDatosProyecto(id) {
                const infoDiv = document.getElementById('info-proyecto');
                if (id && proyectosData[id]) {
                    const data = proyectosData[id];
                    document.getElementById('proyecto-nombre').textContent = data.nombre;
                    document.getElementById('proyecto-estado').textContent = data.estado;
                    document.getElementById('proyecto-recursos').textContent = data.recursos + ' recursos asignados';
                    document.getElementById('proyecto-dias').textContent = data.dias + ' días restantes';
                    infoDiv.classList.remove('hidden');
                } else {
                    infoDiv.classList.add('hidden');
                }
            }
            
            // Inicializar si hay datos seleccionados
            document.addEventListener('DOMContentLoaded', function() {
                const selectUsuario = document.querySelector('select[name="usuario_id"]');
                const selectProyecto = document.querySelector('select[name="proyecto_id"]');
                
                if (selectUsuario && selectUsuario.value) {
                    cargarDatosUsuario(selectUsuario.value);
                }
                
                if (selectProyecto && selectProyecto.value) {
                    cargarDatosProyecto(selectProyecto.value);
                }
                
                // Efecto para radio cards de nivel de experiencia
                document.querySelectorAll('.radio-card').forEach(card => {
                    card.addEventListener('click', function() {
                        // Quitar selección de todas las cards
                        document.querySelectorAll('.radio-card').forEach(c => {
                            c.classList.remove('selected', 'ring-2', 'ring-offset-1', 'ring-opacity-50');
                        });
                        
                        // Agregar selección a esta card
                        this.classList.add('selected', 'ring-2', 'ring-offset-1', 'ring-opacity-50');
                        
                        // Marcar el radio input como checked
                        const radioInput = this.querySelector('input[type="radio"]');
                        if (radioInput) {
                            radioInput.checked = true;
                            
                            // Actualizar campo oculto para validación
                            document.querySelector('input[name="nivel_experiencia_validacion"]').value = radioInput.value;
                            
                            // Ocultar mensaje de error si estaba visible
                            document.getElementById('nivel-error').classList.add('hidden');
                        }
                    });
                });
                
                // Si hay un nivel pre-seleccionado (por error de validación), marcar la card correspondiente
                const nivelSeleccionado = document.querySelector('input[name="nivel_experiencia"]:checked');
                if (nivelSeleccionado) {
                    const card = nivelSeleccionado.closest('.radio-card');
                    if (card) {
                        card.classList.add('selected', 'ring-2', 'ring-offset-1', 'ring-opacity-50');
                        document.querySelector('input[name="nivel_experiencia_validacion"]').value = nivelSeleccionado.value;
                    }
                }
                
                // Validación del formulario
                document.getElementById('formAsignar').addEventListener('submit', function(e) {
                    // Validar nivel de experiencia
                    const nivelSeleccionado = document.querySelector('input[name="nivel_experiencia"]:checked');
                    const nivelErrorDiv = document.getElementById('nivel-error');
                    
                    if (!nivelSeleccionado) {
                        e.preventDefault(); // Detener envío del formulario
                        nivelErrorDiv.classList.remove('hidden');
                        
                        // Resaltar el contenedor
                        document.getElementById('nivel-experiencia-container').classList.add('border', 'border-red-300', 'p-2', 'rounded-lg');
                        
                        // Scroll al campo
                        nivelErrorDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        
                        return false;
                    } else {
                        // Si hay nivel seleccionado, ocultar error y quitar resaltado
                        nivelErrorDiv.classList.add('hidden');
                        document.getElementById('nivel-experiencia-container').classList.remove('border', 'border-red-300', 'p-2', 'rounded-lg');
                    }
                    
                    // Validación adicional si es necesario
                    return true;
                });
                
                // Si el usuario hace clic fuera después de ver el error, quitar el resaltado
                document.getElementById('nivel-experiencia-container').addEventListener('click', function() {
                    this.classList.remove('border', 'border-red-300', 'p-2', 'rounded-lg');
                    document.getElementById('nivel-error').classList.add('hidden');
                });
            });
        </script>

        <?php endif; ?>
        <!-- ========== FIN SECCIÓN ASIGNAR ========== -->

        <!-- ========== SECCIÓN: VER DETALLE DE RECURSO ========== -->
        <?php if ($accion === 'ver' && isset($recurso)): ?>

        <div class="max-w-6xl mx-auto">
            <div class="glass-card rounded-2xl p-8">
                <!-- Header -->
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-14 h-14 bg-gradient-to-br from-teal-100 to-green-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-user-tie text-2xl text-teal-600"></i>
                    </div>
                    <div class="flex-1">
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">Detalle del Recurso Humano</h2>
                        <p class="text-gray-600">ID: <?php echo htmlspecialchars($recurso['id']); ?> | Asignado: <?php echo isset($recurso['fecha_asignacion']) ? date('d/m/Y', strtotime($recurso['fecha_asignacion'])) : 'N/A'; ?></p>
                    </div>
                    <div class="flex gap-3">
                        <a href="?accion=listar" class="px-4 py-2.5 border border-gray-300 text-gray-700 hover:bg-gray-50 rounded-xl font-medium">
                            <i class="fas fa-arrow-left mr-2"></i> Volver
                        </a>
                        <?php if ($usuario_rol != 'miembro_equipo'): ?>
                        <a href="?accion=editar_recurso&id=<?php echo $recurso['id']; ?>" 
                        class="gradient-bg-proceso4 hover:opacity-90 text-white px-4 py-2.5 rounded-xl font-medium">
                            <i class="fas fa-edit mr-2"></i> Editar
                        </a>
                        <a href="?accion=crear_capacitacion&recurso_id=<?php echo $recurso['id']; ?>" 
                        class="bg-gradient-to-r from-purple-500 to-indigo-500 hover:opacity-90 text-white px-4 py-2.5 rounded-xl font-medium">
                            <i class="fas fa-graduation-cap mr-2"></i> Nueva Capacitación
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Información principal -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <!-- Información del usuario -->
                    <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-2xl p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Información del Usuario</h3>
                        <div class="space-y-3">
                            <div>
                                <div class="text-sm text-gray-500">Nombre Completo</div>
                                <div class="text-lg font-semibold text-gray-900"><?php echo htmlspecialchars($recurso['usuario_nombre'] ?? 'Sin nombre'); ?></div>
                            </div>
                            <div>
                                <div class="text-sm text-gray-500">Email</div>
                                <div class="text-lg font-semibold text-gray-900"><?php echo htmlspecialchars($recurso['usuario_email'] ?? 'No especificado'); ?></div>
                            </div>
                            <div>
                                <div class="text-sm text-gray-500">Rol en Sistema</div>
                                <div class="status-badge bg-blue-100 text-blue-800">
                                    <?php echo htmlspecialchars($recurso['usuario_rol'] ?? 'miembro_equipo'); ?>
                                </div>
                            </div>
                            <div>
                                <div class="text-sm text-gray-500">Departamento</div>
                                <div class="font-medium text-gray-900"><?php echo htmlspecialchars($recurso['usuario_departamento'] ?? 'No asignado'); ?></div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Información del proyecto -->
                    <div class="bg-gradient-to-r from-purple-50 to-purple-100 rounded-2xl p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Información del Proyecto</h3>
                        <div class="space-y-3">
                            <div>
                                <div class="text-sm text-gray-500">Proyecto Asignado</div>
                                <div class="text-lg font-semibold text-gray-900"><?php echo htmlspecialchars($recurso['proyecto_nombre'] ?? 'Sin proyecto'); ?></div>
                            </div>
                            <div>
                                <div class="text-sm text-gray-500">Estado del Proyecto</div>
                                <?php 
                                $estado_proyecto_colores = [
                                    'planificacion' => 'bg-yellow-100 text-yellow-800',
                                    'en_ejecucion' => 'bg-green-100 text-green-800',
                                    'en_pausa' => 'bg-blue-100 text-blue-800',
                                    'completado' => 'bg-gray-100 text-gray-800',
                                    'cancelado' => 'bg-red-100 text-red-800'
                                ];
                                $estado_proyecto = $recurso['proyecto_estado'] ?? 'planificacion';
                                $color_proyecto = $estado_proyecto_colores[$estado_proyecto] ?? 'bg-gray-100 text-gray-800';
                                ?>
                                <div class="status-badge <?php echo $color_proyecto; ?>">
                                    <?php echo ucfirst($estado_proyecto); ?>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <div class="text-sm text-gray-500">Fecha Inicio</div>
                                    <div class="font-medium text-gray-900"><?php echo isset($recurso['fecha_inicio']) ? date('d/m/Y', strtotime($recurso['fecha_inicio'])) : 'N/A'; ?></div>
                                </div>
                                <div>
                                    <div class="text-sm text-gray-500">Fecha Fin Estimada</div>
                                    <div class="font-medium text-gray-900"><?php echo isset($recurso['fecha_fin_estimada']) ? date('d/m/Y', strtotime($recurso['fecha_fin_estimada'])) : 'N/A'; ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Información de asignación -->
                    <div class="bg-gradient-to-r from-emerald-50 to-green-100 rounded-2xl p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Información de Asignación</h3>
                        <div class="space-y-3">
                            <div>
                                <div class="text-sm text-gray-500">Rol en Proyecto</div>
                                <div class="text-lg font-semibold text-gray-900"><?php echo htmlspecialchars($recurso['rol_proyecto'] ?? 'Sin rol'); ?></div>
                            </div>
                            <div>
                                <div class="text-sm text-gray-500">Nivel de Experiencia</div>
                                <?php 
                                $nivel_colores = [
                                    'junior' => ['bg' => 'badge-junior', 'icon' => 'fa-user-graduate'],
                                    'intermedio' => ['bg' => 'badge-intermedio', 'icon' => 'fa-user'],
                                    'senior' => ['bg' => 'badge-senior', 'icon' => 'fa-user-tie'],
                                    'experto' => ['bg' => 'badge-experto', 'icon' => 'fa-user-ninja']
                                ];
                                $nivel = $recurso['nivel_experiencia'] ?? 'intermedio';
                                $nivel_style = $nivel_colores[$nivel] ?? $nivel_colores['intermedio'];
                                ?>
                                <div class="status-badge <?php echo $nivel_style['bg']; ?>">
                                    <i class="fas <?php echo $nivel_style['icon']; ?> text-xs"></i>
                                    <?php echo ucfirst($nivel); ?>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <div class="text-sm text-gray-500">Horas Asignadas</div>
                                    <div class="text-lg font-bold text-gray-900"><?php echo $recurso['horas_asignadas'] ?? 0; ?></div>
                                </div>
                                <div>
                                    <div class="text-sm text-gray-500">Horas Realizadas</div>
                                    <div class="text-lg font-bold text-teal-600"><?php echo $recurso['horas_realizadas'] ?? 0; ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Habilidades y Capacitación Requerida -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <!-- Habilidades -->
                    <div class="bg-gradient-to-r from-amber-50 to-orange-50 rounded-2xl p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Habilidades</h3>
                        <?php if (!empty($recurso['habilidades'])): ?>
                        <div class="flex flex-wrap gap-2">
                            <?php 
                            $habilidades = explode(',', $recurso['habilidades']);
                            foreach($habilidades as $habilidad):
                                if (!empty(trim($habilidad))):
                            ?>
                            <span class="skill-tag bg-white border border-amber-200 text-amber-800">
                                <i class="fas fa-check-circle text-xs mr-1"></i>
                                <?php echo htmlspecialchars(trim($habilidad)); ?>
                            </span>
                            <?php 
                                endif;
                            endforeach; 
                            ?>
                        </div>
                        <?php else: ?>
                        <div class="text-center py-4">
                            <i class="fas fa-tools text-4xl text-gray-300 mb-3"></i>
                            <p class="text-gray-500">No se han especificado habilidades</p>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Capacitación Requerida -->
                    <div class="bg-gradient-to-r from-yellow-50 to-yellow-100 rounded-2xl p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Capacitación Requerida</h3>
                        <?php if (!empty($recurso['capacitacion_requerida'])): ?>
                        <div class="bg-white p-4 rounded-xl border border-yellow-200">
                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-lightbulb text-yellow-600"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-gray-700"><?php echo nl2br(htmlspecialchars($recurso['capacitacion_requerida'])); ?></p>
                                </div>
                            </div>
                        </div>
                        <?php else: ?>
                        <div class="text-center py-4">
                            <i class="fas fa-graduation-cap text-4xl text-gray-300 mb-3"></i>
                            <p class="text-gray-500">No se han especificado necesidades de capacitación</p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Capacitaciones -->
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl p-6 mb-8">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold text-gray-900">Capacitaciones Asignadas</h3>
                        <a href="?accion=crear_capacitacion&recurso_id=<?php echo $recurso['id']; ?>" 
                        class="bg-gradient-to-r from-purple-500 to-indigo-500 hover:opacity-90 text-white px-4 py-2 rounded-xl font-medium flex items-center gap-2">
                            <i class="fas fa-plus"></i>
                            Nueva Capacitación
                        </a>
                    </div>
                    
                    <?php if (empty($capacitaciones) || !is_array($capacitaciones)): ?>
                    <div class="text-center py-8">
                        <i class="fas fa-graduation-cap text-5xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500">No hay capacitaciones registradas para este recurso</p>
                        <a href="?accion=crear_capacitacion&recurso_id=<?php echo $recurso['id']; ?>" 
                        class="mt-4 inline-flex items-center px-4 py-2 bg-gradient-to-r from-purple-500 to-indigo-500 text-white rounded-lg font-medium">
                            <i class="fas fa-plus mr-2"></i>
                            Crear primera capacitación
                        </a>
                    </div>
                    <?php else: ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <?php foreach($capacitaciones as $capacitacion): 
                            // Colores para estados de capacitación
                            $estado_colores_capacitacion = [
                                'pendiente' => ['bg' => 'badge-pendiente', 'icon' => 'fa-clock'],
                                'en_curso' => ['bg' => 'badge-en_curso', 'icon' => 'fa-spinner'],
                                'completada' => ['bg' => 'badge-completada', 'icon' => 'fa-check-circle'],
                                'cancelada' => ['bg' => 'badge-cancelada', 'icon' => 'fa-times-circle'],
                                'atrasada' => ['bg' => 'badge-atrasada', 'icon' => 'fa-exclamation-triangle']
                            ];
                            $estado_capacitacion = $capacitacion['estado_actual'] ?? 'pendiente';
                            $estado_style_capacitacion = $estado_colores_capacitacion[$estado_capacitacion] ?? $estado_colores_capacitacion['pendiente'];
                        ?>
                        <div class="bg-white rounded-xl p-5 border border-gray-200 hover:shadow-lg transition-all duration-200">
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <h4 class="font-semibold text-gray-900 mb-1"><?php echo htmlspecialchars($capacitacion['tipo_capacitacion'] ?? 'Sin tipo'); ?></h4>
                                    <div class="text-xs text-gray-500"><?php echo htmlspecialchars($capacitacion['descripcion'] ?? ''); ?></div>
                                </div>
                                <span class="status-badge <?php echo $estado_style_capacitacion['bg']; ?>">
                                    <i class="fas <?php echo $estado_style_capacitacion['icon']; ?> text-xs"></i>
                                    <?php echo ucfirst($estado_capacitacion); ?>
                                </span>
                            </div>
                            
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Duración:</span>
                                    <span class="font-medium"><?php echo $capacitacion['duracion_horas'] ?? 0; ?> horas</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Periodo:</span>
                                    <span class="font-medium"><?php echo $capacitacion['fecha_inicio_formatted'] ?? 'N/A'; ?> - <?php echo $capacitacion['fecha_fin_formatted'] ?? 'N/A'; ?></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Costo:</span>
                                    <span class="font-medium text-green-600">$<?php echo isset($capacitacion['costo']) ? number_format($capacitacion['costo'], 2) : '0.00'; ?></span>
                                </div>
                                <?php if (!empty($capacitacion['certificacion'])): ?>
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Certificación:</span>
                                    <span class="font-medium text-blue-600"><?php echo htmlspecialchars($capacitacion['certificacion']); ?></span>
                                </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="mt-4 pt-4 border-t border-gray-100">
                                <?php if ($usuario_rol != 'miembro_equipo'): ?>
                                <div class="flex gap-2">
                                    <!-- Botón rápido para cambiar estado -->
                                    <div class="relative group flex-1">
                                        <button class="w-full text-center px-3 py-1.5 bg-gradient-to-r from-gray-50 to-gray-100 hover:from-gray-100 hover:to-gray-200 text-gray-700 text-xs font-medium rounded-lg flex items-center justify-center gap-1"
                                                title="Cambiar estado">
                                            <i class="fas fa-exchange-alt text-xs"></i>
                                            Cambiar Estado
                                        </button>
                                        <div class="absolute bottom-full mb-1 left-0 w-40 bg-white rounded-lg shadow-xl border border-gray-200 z-20 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                                            <form method="POST" action="" class="p-2 space-y-1">
                                                <input type="hidden" name="accion" value="cambiar_estado_capacitacion">
                                                <input type="hidden" name="id" value="<?php echo $capacitacion['id']; ?>">
                                                
                                                <button type="submit" name="estado" value="pendiente" 
                                                        class="w-full text-left px-3 py-2 text-xs hover:bg-yellow-50 rounded-md text-yellow-700 flex items-center gap-2">
                                                    <i class="fas fa-clock text-xs"></i>
                                                    <span>Pendiente</span>
                                                </button>
                                                <button type="submit" name="estado" value="en_curso" 
                                                        class="w-full text-left px-3 py-2 text-xs hover:bg-blue-50 rounded-md text-blue-700 flex items-center gap-2">
                                                    <i class="fas fa-spinner text-xs"></i>
                                                    <span>En Curso</span>
                                                </button>
                                                <button type="submit" name="estado" value="completada" 
                                                        class="w-full text-left px-3 py-2 text-xs hover:bg-green-50 rounded-md text-green-700 flex items-center gap-2">
                                                    <i class="fas fa-check-circle text-xs"></i>
                                                    <span>Completada</span>
                                                </button>
                                                <button type="submit" name="estado" value="cancelada" 
                                                        class="w-full text-left px-3 py-2 text-xs hover:bg-red-50 rounded-md text-red-700 flex items-center gap-2">
                                                    <i class="fas fa-times-circle text-xs"></i>
                                                    <span>Cancelada</span>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
                
                <!-- Horas trabajadas -->
                <?php if (isset($horas_trabajadas) && !empty($horas_trabajadas)): ?>
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-2xl p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Seguimiento de Horas</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gradient-to-r from-green-100 to-emerald-100">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Proyecto</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Horas Asignadas</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Horas Realizadas</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Porcentaje</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Estado</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                <?php foreach($horas_trabajadas as $horas): 
                                    $porcentaje = $horas['porcentaje_completado'] ?? 0;
                                    $estado_horas = '';
                                    $color_horas = '';
                                    
                                    if ($porcentaje >= 100) {
                                        $estado_horas = 'Completado';
                                        $color_horas = 'bg-green-100 text-green-800';
                                    } elseif ($porcentaje >= 75) {
                                        $estado_horas = 'Avanzado';
                                        $color_horas = 'bg-blue-100 text-blue-800';
                                    } elseif ($porcentaje >= 50) {
                                        $estado_horas = 'En Progreso';
                                        $color_horas = 'bg-yellow-100 text-yellow-800';
                                    } else {
                                        $estado_horas = 'Iniciando';
                                        $color_horas = 'bg-gray-100 text-gray-800';
                                    }
                                ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3">
                                        <div class="font-medium text-gray-900"><?php echo htmlspecialchars($horas['proyecto'] ?? 'Sin proyecto'); ?></div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="text-lg font-semibold text-gray-700"><?php echo $horas['horas_asignadas'] ?? 0; ?></div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="text-lg font-semibold text-green-600"><?php echo $horas['horas_realizadas'] ?? 0; ?></div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center">
                                            <div class="w-full bg-gray-200 rounded-full h-2 mr-3">
                                                <div class="bg-green-500 h-2 rounded-full" style="width: <?php echo min($porcentaje, 100); ?>%"></div>
                                            </div>
                                            <span class="text-sm font-medium text-gray-700"><?php echo number_format($porcentaje, 1); ?>%</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="status-badge <?php echo $color_horas; ?>">
                                            <?php echo $estado_horas; ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <?php endif; ?>
        <!-- ========== FIN SECCIÓN VER ========== -->
        
        <!-- ========== SECCIÓN: EDITAR RECURSO ========== -->
        <?php if ($accion === 'editar_recurso' && isset($recurso)): ?>

        <div class="max-w-4xl mx-auto">
            <div class="glass-card rounded-2xl p-8">
                <!-- Header -->
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-14 h-14 bg-gradient-to-br from-teal-100 to-green-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-user-edit text-2xl text-teal-600"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Editar Recurso Humano</h2>
                        <p class="text-gray-600">Actualizar información de <?php echo htmlspecialchars($recurso['usuario_nombre'] ?? 'el recurso'); ?></p>
                    </div>
                </div>
                
                <!-- Información actual -->
                <div class="mb-8 bg-gradient-to-r from-blue-50 to-blue-100 rounded-2xl p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Información Actual</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <div class="text-sm text-gray-600">Usuario</div>
                            <div class="font-semibold text-gray-900"><?php echo htmlspecialchars($recurso['usuario_nombre'] ?? ''); ?></div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-600">Proyecto</div>
                            <div class="font-semibold text-gray-900"><?php echo htmlspecialchars($recurso['proyecto_nombre'] ?? ''); ?></div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-600">Rol Actual</div>
                            <div class="font-semibold text-gray-900"><?php echo htmlspecialchars($recurso['rol_proyecto'] ?? ''); ?></div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-600">Nivel Actual</div>
                            <span class="status-badge <?php 
                                $nivel_colores = [
                                    'junior' => 'badge-junior',
                                    'intermedio' => 'badge-intermedio',
                                    'senior' => 'badge-senior',
                                    'experto' => 'badge-experto'
                                ];
                                echo $nivel_colores[$recurso['nivel_experiencia']] ?? 'badge-intermedio';
                            ?>">
                                <?php echo ucfirst($recurso['nivel_experiencia'] ?? 'intermedio'); ?>
                            </span>
                        </div>
                    </div>
                </div>
                
                <!-- Mensajes de error -->
                <?php if (isset($errores) && !empty($errores)): ?>
                <div class="mb-6 p-4 rounded-lg border bg-gradient-to-r from-red-50 to-rose-50 border-red-200 text-red-700 shadow-sm">
                    <div class="flex items-center mb-2">
                        <i class="fas fa-exclamation-circle mr-3 text-lg"></i>
                        <span class="font-medium">Por favor, corrige los siguientes errores:</span>
                    </div>
                    <ul class="list-disc pl-10 mt-2 text-sm">
                        <?php foreach($errores as $campo => $mensaje): ?>
                            <li><span class="font-medium"><?php echo htmlspecialchars($campo); ?>:</span> <?php echo htmlspecialchars($mensaje); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endif; ?>
                
                <!-- Formulario de edición -->
                <form method="POST" action="?accion=actualizar_recurso" class="space-y-6">
                    <input type="hidden" name="accion" value="actualizar_recurso">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($recurso['id']); ?>">
                    
                    <!-- Información de asignación -->
                    <div class="bg-gradient-to-r from-teal-50 to-green-50 rounded-2xl p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Información de Asignación</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-3">Rol en el Proyecto *</label>
                                <input type="text" name="rol_proyecto" required 
                                    value="<?php echo htmlspecialchars($recurso['rol_proyecto'] ?? ''); ?>"
                                    class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                                    placeholder="Ej: Desarrollador Full Stack, Diseñador UX/UI...">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-3">Nivel de Experiencia *</label>
                                <select name="nivel_experiencia" required 
                                        class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                                    <?php 
                                    $niveles_experiencia = [
                                        'junior' => 'Junior',
                                        'intermedio' => 'Intermedio', 
                                        'senior' => 'Senior',
                                        'experto' => 'Experto'
                                    ];
                                    foreach ($niveles_experiencia as $valor => $texto):
                                    ?>
                                    <option value="<?php echo $valor; ?>" 
                                        <?php echo (isset($recurso['nivel_experiencia']) && $recurso['nivel_experiencia'] == $valor) ? 'selected' : ''; ?>>
                                        <?php echo $texto; ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-3">Habilidades</label>
                                <textarea name="habilidades" rows="3" 
                                        class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                                        placeholder="Lista de habilidades separadas por coma..."><?php echo htmlspecialchars($recurso['habilidades'] ?? ''); ?></textarea>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-3">Capacitación Requerida</label>
                                <textarea name="capacitacion_requerida" rows="3" 
                                        class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                                        placeholder="Capacitaciones específicas que necesita..."><?php echo htmlspecialchars($recurso['capacitacion_requerida'] ?? ''); ?></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Horas trabajadas -->
                    <div class="bg-gradient-to-r from-amber-50 to-orange-50 rounded-2xl p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Horas Trabajadas</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-3">Horas Asignadas</label>
                                <input type="number" name="horas_asignadas" min="0" step="1" 
                                    value="<?php echo htmlspecialchars($recurso['horas_asignadas'] ?? 0); ?>"
                                    class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-3">Horas Realizadas</label>
                                <input type="number" name="horas_realizadas" min="0" step="1" 
                                    value="<?php echo htmlspecialchars($recurso['horas_realizadas'] ?? 0); ?>"
                                    class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                            </div>
                        </div>
                        
                        <div class="mt-6">
                            <div class="text-sm text-gray-600 mb-2">Progreso de Horas</div>
                            <?php 
                            $horas_asignadas = $recurso['horas_asignadas'] ?? 1;
                            $horas_realizadas = $recurso['horas_realizadas'] ?? 0;
                            $porcentaje_horas = $horas_asignadas > 0 ? ($horas_realizadas / $horas_asignadas) * 100 : 0;
                            ?>
                            <div class="flex items-center">
                                <div class="flex-1 bg-gray-200 rounded-full h-2 mr-3">
                                    <div class="bg-green-500 h-2 rounded-full" style="width: <?php echo min($porcentaje_horas, 100); ?>%"></div>
                                </div>
                                <span class="text-sm font-medium text-gray-700"><?php echo number_format($porcentaje_horas, 1); ?>%</span>
                            </div>
                            <div class="text-xs text-gray-500 mt-1">
                                <?php echo $horas_realizadas; ?> de <?php echo $horas_asignadas; ?> horas completadas
                            </div>
                        </div>
                    </div>
                    
                    <!-- Botones de acción -->
                    <div class="pt-6 border-t border-gray-200">
                        <div class="flex justify-between">
                            <div>
                                <a href="?accion=ver&id=<?php echo $recurso['id']; ?>" 
                                class="px-8 py-3 border-2 border-gray-300 text-gray-700 hover:bg-gray-50 rounded-xl font-medium transition-all hover-lift mr-4">
                                    <i class="fas fa-times mr-2"></i>
                                    Cancelar
                                </a>
                                <a href="?accion=ver&id=<?php echo $recurso['id']; ?>" 
                                class="px-8 py-3 bg-gradient-to-r from-blue-500 to-indigo-500 hover:opacity-90 text-white rounded-xl font-medium shadow-md hover-lift">
                                    <i class="fas fa-eye mr-2"></i>
                                    Ver Detalle
                                </a>
                            </div>
                            <div class="flex gap-4">
                                <button type="button" onclick="confirmarEliminacion()"
                                        class="px-8 py-3 border-2 border-red-300 text-red-700 hover:bg-red-50 rounded-xl font-medium transition-all hover-lift">
                                    <i class="fas fa-trash mr-2"></i>
                                    Eliminar
                                </button>
                                <button type="submit" 
                                        class="gradient-bg-proceso4 hover:opacity-90 text-white px-8 py-3 rounded-xl font-medium shadow-md hover-lift flex items-center gap-2">
                                    <i class="fas fa-save"></i>
                                    Guardar Cambios
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal de confirmación de eliminación -->
        <div id="modalEliminar" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50 hidden">
            <div class="bg-white rounded-2xl p-8 max-w-md w-full mx-4">
                <div class="text-center mb-6">
                    <div class="w-16 h-16 mx-auto bg-gradient-to-r from-red-100 to-rose-100 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-exclamation-triangle text-2xl text-red-600"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">¿Eliminar Recurso?</h3>
                    <p class="text-gray-600 mb-6">
                        Estás a punto de eliminar a <span class="font-semibold"><?php echo htmlspecialchars($recurso['usuario_nombre'] ?? ''); ?></span> 
                        del proyecto <span class="font-semibold"><?php echo htmlspecialchars($recurso['proyecto_nombre'] ?? ''); ?></span>.
                    </p>
                    <p class="text-sm text-gray-500 mb-6">
                        Esta acción eliminará todas las capacitaciones asociadas y el historial de horas trabajadas.
                        <span class="block font-semibold text-red-600 mt-2">Esta acción no se puede deshacer.</span>
                    </p>
                </div>
                <div class="flex justify-center gap-4">
                    <button type="button" onclick="cerrarModal()"
                            class="px-6 py-3 border-2 border-gray-300 text-gray-700 hover:bg-gray-50 rounded-xl font-medium flex-1">
                        Cancelar
                    </button>
                    <form method="POST" action="" class="flex-1">
                        <input type="hidden" name="accion" value="eliminar_recurso">
                        <input type="hidden" name="id" value="<?php echo $recurso['id']; ?>">
                        <button type="submit" 
                                class="w-full px-6 py-3 bg-gradient-to-r from-red-500 to-rose-500 hover:opacity-90 text-white rounded-xl font-medium">
                            Confirmar Eliminación
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <script>
            function confirmarEliminacion() {
                document.getElementById('modalEliminar').classList.remove('hidden');
            }
            
            function cerrarModal() {
                document.getElementById('modalEliminar').classList.add('hidden');
            }
            
            // Cerrar modal al hacer clic fuera
            document.getElementById('modalEliminar').addEventListener('click', function(e) {
                if (e.target.id === 'modalEliminar') {
                    cerrarModal();
                }
            });
            
            // Cerrar modal con ESC
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    cerrarModal();
                }
            });
        </script>

        <?php endif; ?>
        <!-- ========== FIN SECCIÓN EDITAR RECURSO ========== -->

        <!-- ========== SECCIÓN: LISTA DE CAPACITACIONES ========== -->
        <?php if ($accion === 'capacitaciones'): ?>

        <div class="glass-card rounded-2xl p-8">
            <!-- Header -->
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Gestión de Capacitaciones</h2>
                    <p class="text-gray-600">Planifica y gestiona las capacitaciones del equipo del proyecto</p>
                </div>
                <a href="?accion=crear_capacitacion" 
                class="gradient-bg-proceso4 hover:opacity-90 text-white px-5 py-2.5 rounded-xl font-medium flex items-center gap-2 hover-lift shadow-lg">
                    <i class="fas fa-graduation-cap"></i>
                    Nueva Capacitación
                </a>
            </div>
            
            <!-- Filtros -->
            <div class="mb-8 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Filtrar Capacitaciones</h3>
                <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <input type="hidden" name="accion" value="capacitaciones">
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Proyecto</label>
                        <select name="proyecto_id" class="form-input w-full px-4 py-2.5 border border-gray-300 rounded-xl">
                            <option value="">Todos los proyectos</option>
                            <?php if (isset($proyectos)): ?>
                                <?php foreach ($proyectos as $proyecto): ?>
                                    <option value="<?php echo htmlspecialchars($proyecto['id'] ?? ''); ?>" 
                                        <?php echo (isset($_GET['proyecto_id']) && $_GET['proyecto_id'] == $proyecto['id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($proyecto['nombre'] ?? ''); ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                        <select name="estado" class="form-input w-full px-4 py-2.5 border border-gray-300 rounded-xl">
                            <option value="">Todos los estados</option>
                            <option value="pendiente" <?php echo (isset($_GET['estado']) && $_GET['estado'] == 'pendiente') ? 'selected' : ''; ?>>Pendiente</option>
                            <option value="en_curso" <?php echo (isset($_GET['estado']) && $_GET['estado'] == 'en_curso') ? 'selected' : ''; ?>>En Curso</option>
                            <option value="completada" <?php echo (isset($_GET['estado']) && $_GET['estado'] == 'completada') ? 'selected' : ''; ?>>Completada</option>
                            <option value="cancelada" <?php echo (isset($_GET['estado']) && $_GET['estado'] == 'cancelada') ? 'selected' : ''; ?>>Cancelada</option>
                        </select>
                    </div>
                    
                    <div class="flex items-end">
                        <button type="submit" 
                                class="w-full gradient-bg-proceso4 hover:opacity-90 text-white px-4 py-2.5 rounded-xl font-medium flex items-center justify-center gap-2">
                            <i class="fas fa-filter"></i>
                            Aplicar Filtros
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Estadísticas rápidas -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <?php
                // Calcular estadísticas de capacitaciones
                $total_capacitaciones = 0;
                $total_en_curso = 0;
                $total_completadas = 0;
                $costo_total = 0;
                $horas_totales = 0;
                
                if (isset($recursos) && is_array($recursos)) {
                    foreach ($recursos as $recurso) {
                        $total_capacitaciones += $recurso['total_capacitaciones'] ?? 0;
                        $total_completadas += $recurso['capacitaciones_completadas'] ?? 0;
                        $costo_total += $recurso['costo_total_capacitaciones'] ?? 0;
                        $horas_totales += $recurso['total_horas_capacitacion'] ?? 0;
                    }
                }
                $total_en_curso = $total_capacitaciones - $total_completadas;
                $porcentaje_completado = $total_capacitaciones > 0 ? ($total_completadas / $total_capacitaciones) * 100 : 0;
                ?>
                
                <div class="stat-card bg-white rounded-2xl p-6 border border-gray-200 shadow-sm hover-lift">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 bg-gradient-to-r from-blue-50 to-blue-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-graduation-cap text-blue-600 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Total Capacitaciones</p>
                            <p class="text-2xl font-bold text-gray-800"><?php echo $total_capacitaciones; ?></p>
                        </div>
                    </div>
                </div>
                
                <div class="stat-card bg-white rounded-2xl p-6 border border-gray-200 shadow-sm hover-lift">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 bg-gradient-to-r from-green-50 to-green-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-check-circle text-green-600 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Completadas</p>
                            <p class="text-2xl font-bold text-gray-800">
                                <?php echo $total_completadas; ?> (<?php echo number_format($porcentaje_completado, 1); ?>%)
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="stat-card bg-white rounded-2xl p-6 border border-gray-200 shadow-sm hover-lift">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 bg-gradient-to-r from-purple-50 to-purple-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-clock text-purple-600 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Horas Totales</p>
                            <p class="text-2xl font-bold text-gray-800"><?php echo $horas_totales; ?></p>
                        </div>
                    </div>
                </div>
                
                <div class="stat-card bg-white rounded-2xl p-6 border border-gray-200 shadow-sm hover-lift">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 bg-gradient-to-r from-yellow-50 to-yellow-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-money-bill-wave text-yellow-600 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Inversión Total</p>
                            <p class="text-2xl font-bold text-gray-800">
                                $<?php echo number_format($costo_total, 2); ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Lista de capacitaciones -->
            <div class="space-y-6">
                <?php if (empty($recursos) || !is_array($recursos)): ?>
                <div class="text-center py-16">
                    <div class="w-24 h-24 mx-auto bg-gradient-to-br from-blue-50 to-indigo-100 rounded-full flex items-center justify-center mb-6">
                        <i class="fas fa-graduation-cap text-4xl text-gray-400"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-700 mb-3">No hay capacitaciones registradas</h3>
                    <p class="text-gray-500 mb-6 max-w-md mx-auto">Comienza creando capacitaciones para desarrollar las habilidades de tu equipo</p>
                    <a href="?accion=crear_capacitacion" 
                    class="gradient-bg-proceso4 hover:opacity-90 text-white px-6 py-3 rounded-xl font-medium inline-flex items-center gap-2 shadow-lg hover-lift">
                        <i class="fas fa-plus"></i>
                        Crear primera capacitación
                    </a>
                </div>
                <?php else: ?>
                    <?php foreach($recursos as $recurso): 
                        if (isset($recurso['capacitaciones_detalle']) && is_array($recurso['capacitaciones_detalle']) && count($recurso['capacitaciones_detalle']) > 0):
                            foreach($recurso['capacitaciones_detalle'] as $capacitacion): 
                                // Colores para estados de capacitación
                                $estado_colores = [
                                    'pendiente' => ['bg' => 'badge-pendiente', 'icon' => 'fa-clock'],
                                    'en_curso' => ['bg' => 'badge-en_curso', 'icon' => 'fa-spinner'],
                                    'completada' => ['bg' => 'badge-completada', 'icon' => 'fa-check-circle'],
                                    'cancelada' => ['bg' => 'badge-cancelada', 'icon' => 'fa-times-circle'],
                                    'atrasada' => ['bg' => 'badge-atrasada', 'icon' => 'fa-exclamation-triangle']
                                ];
                                $estado = $capacitacion['estado_actual'] ?? 'pendiente';
                                $estado_style = $estado_colores[$estado] ?? $estado_colores['pendiente'];
                                
                                // Determinar si está atrasada
                                $fecha_fin = $capacitacion['fecha_fin'] ?? null;
                                $hoy = date('Y-m-d');
                                $esta_atrasada = $fecha_fin && $fecha_fin < $hoy && $estado != 'completada' && $estado != 'cancelada';
                                
                                if ($esta_atrasada) {
                                    $estado = 'atrasada';
                                    $estado_style = $estado_colores['atrasada'];
                                }
                    ?>
                    <div class="bg-white rounded-2xl border border-gray-200 hover:shadow-lg transition-all duration-200 overflow-hidden">
                        <div class="p-6">
                            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-4">
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900 mb-2">
                                        <?php echo htmlspecialchars($capacitacion['tipo_capacitacion'] ?? 'Sin tipo'); ?>
                                    </h3>
                                    <p class="text-gray-600"><?php echo htmlspecialchars($capacitacion['descripcion'] ?? ''); ?></p>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="status-badge <?php echo $estado_style['bg']; ?>">
                                        <i class="fas <?php echo $estado_style['icon']; ?> text-xs"></i>
                                        <?php echo ucfirst($estado); ?>
                                    </span>
                                    <?php if ($usuario_rol != 'miembro_equipo'): ?>
                                    <div class="flex gap-2">
                                        <button onclick="toggleAcciones(<?php echo $capacitacion['id']; ?>)" 
                                                class="w-8 h-8 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <div id="acciones-<?php echo $capacitacion['id']; ?>" 
                                            class="hidden absolute mt-10 bg-white rounded-lg shadow-xl border border-gray-200 z-20 p-2 min-w-[150px]">
                                            <a href="?accion=editar_capacitacion&id=<?php echo $capacitacion['id']; ?>" 
                                            class="flex items-center gap-2 px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-md">
                                                <i class="fas fa-edit text-xs"></i>
                                                Editar
                                            </a>
                                            <form method="POST" action="" 
                                                onsubmit="return confirm('¿Estás seguro de eliminar esta capacitación?');">
                                                <input type="hidden" name="accion" value="eliminar_capacitacion">
                                                <input type="hidden" name="id" value="<?php echo $capacitacion['id']; ?>">
                                                <button type="submit" 
                                                        class="w-full text-left flex items-center gap-2 px-3 py-2 text-sm text-red-600 hover:bg-red-50 rounded-md">
                                                    <i class="fas fa-trash text-xs"></i>
                                                    Eliminar
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <!-- Información detallada -->
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                                <div class="space-y-2">
                                    <div class="text-sm text-gray-500">Recurso</div>
                                    <div class="font-semibold text-gray-900 flex items-center gap-2">
                                        <div class="w-8 h-8 bg-gradient-to-br from-teal-100 to-green-100 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-user text-teal-600 text-xs"></i>
                                        </div>
                                        <?php echo htmlspecialchars($recurso['usuario_nombre'] ?? 'Sin nombre'); ?>
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        Proyecto: <?php echo htmlspecialchars($recurso['proyecto_nombre'] ?? 'Sin proyecto'); ?>
                                    </div>
                                </div>
                                
                                <div class="space-y-2">
                                    <div class="text-sm text-gray-500">Periodo</div>
                                    <div class="font-semibold text-gray-900">
                                        <?php echo isset($capacitacion['fecha_inicio']) ? date('d/m/Y', strtotime($capacitacion['fecha_inicio'])) : 'N/A'; ?>
                                        <i class="fas fa-arrow-right mx-2 text-gray-400"></i>
                                        <?php echo isset($capacitacion['fecha_fin']) ? date('d/m/Y', strtotime($capacitacion['fecha_fin'])) : 'N/A'; ?>
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        Duración: <?php echo $capacitacion['duracion_horas'] ?? 0; ?> horas
                                    </div>
                                </div>
                                
                                <div class="space-y-2">
                                    <div class="text-sm text-gray-500">Costo</div>
                                    <div class="font-semibold text-green-600 text-lg">
                                        $<?php echo isset($capacitacion['costo']) ? number_format($capacitacion['costo'], 2) : '0.00'; ?>
                                    </div>
                                    <?php if (!empty($capacitacion['certificacion'])): ?>
                                    <div class="text-xs text-blue-600 bg-blue-50 px-2 py-1 rounded inline-block">
                                        <i class="fas fa-certificate mr-1"></i>
                                        <?php echo htmlspecialchars($capacitacion['certificacion']); ?>
                                    </div>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="space-y-2">
                                    <div class="text-sm text-gray-500">Progreso</div>
                                    <div class="flex items-center">
                                        <div class="w-full bg-gray-200 rounded-full h-2 mr-3">
                                            <?php
                                            $porcentaje_capacitacion = 0;
                                            if ($estado == 'completada') {
                                                $porcentaje_capacitacion = 100;
                                            } elseif ($estado == 'en_curso') {
                                                // Calcular progreso basado en fechas si está en curso
                                                $fecha_inicio = $capacitacion['fecha_inicio'] ?? null;
                                                $fecha_fin = $capacitacion['fecha_fin'] ?? null;
                                                if ($fecha_inicio && $fecha_fin) {
                                                    $total_dias = (strtotime($fecha_fin) - strtotime($fecha_inicio)) / (60 * 60 * 24);
                                                    $dias_transcurridos = (strtotime($hoy) - strtotime($fecha_inicio)) / (60 * 60 * 24);
                                                    if ($total_dias > 0) {
                                                        $porcentaje_capacitacion = min(($dias_transcurridos / $total_dias) * 100, 100);
                                                    }
                                                }
                                            }
                                            ?>
                                            <div class="bg-green-500 h-2 rounded-full" style="width: <?php echo $porcentaje_capacitacion; ?>%"></div>
                                        </div>
                                        <span class="text-sm font-medium text-gray-700"><?php echo number_format($porcentaje_capacitacion, 0); ?>%</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Acciones rápidas -->
                            <?php if ($usuario_rol != 'miembro_equipo'): ?>
                            <div class="pt-4 border-t border-gray-100">
                                <div class="flex flex-wrap gap-2">
                                    <form method="POST" action="" class="inline-flex">
                                        <input type="hidden" name="accion" value="cambiar_estado_capacitacion">
                                        <input type="hidden" name="id" value="<?php echo $capacitacion['id']; ?>">
                                        
                                        <?php foreach(['pendiente', 'en_curso', 'completada', 'cancelada'] as $estado_btn): 
                                            $btn_colores = [
                                                'pendiente' => 'bg-yellow-100 hover:bg-yellow-200 text-yellow-700',
                                                'en_curso' => 'bg-blue-100 hover:bg-blue-200 text-blue-700',
                                                'completada' => 'bg-green-100 hover:bg-green-200 text-green-700',
                                                'cancelada' => 'bg-red-100 hover:bg-red-200 text-red-700'
                                            ];
                                        ?>
                                        <button type="submit" name="estado" value="<?php echo $estado_btn; ?>"
                                                class="px-3 py-1.5 rounded-lg text-xs font-medium flex items-center gap-1 <?php echo $btn_colores[$estado_btn]; ?>
                                                    <?php echo ($estado == $estado_btn) ? 'ring-2 ring-offset-1' : ''; ?>">
                                            <i class="fas fa-<?php echo $estado_btn == 'pendiente' ? 'clock' : ($estado_btn == 'en_curso' ? 'spinner' : ($estado_btn == 'completada' ? 'check-circle' : 'times-circle')); ?> text-xs"></i>
                                            <?php echo ucfirst($estado_btn); ?>
                                        </button>
                                        <?php endforeach; ?>
                                    </form>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; endif; endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <script>
            function toggleAcciones(id) {
                const menu = document.getElementById('acciones-' + id);
                menu.classList.toggle('hidden');
                
                // Cerrar otros menús abiertos
                document.querySelectorAll('[id^="acciones-"]').forEach(otherMenu => {
                    if (otherMenu.id !== 'acciones-' + id) {
                        otherMenu.classList.add('hidden');
                    }
                });
            }
            
            // Cerrar menús al hacer clic fuera
            document.addEventListener('click', function(event) {
                if (!event.target.closest('[onclick^="toggleAcciones"]') && !event.target.closest('[id^="acciones-"]')) {
                    document.querySelectorAll('[id^="acciones-"]').forEach(menu => {
                        menu.classList.add('hidden');
                    });
                }
            });
        </script>

        <?php endif; ?>
        <!-- ========== FIN SECCIÓN CAPACITACIONES ========== -->

        <!-- ========== SECCIÓN: CREAR CAPACITACIÓN ========== -->
        <?php if ($accion === 'crear_capacitacion'): ?>

        <div class="max-w-4xl mx-auto">
            <div class="glass-card rounded-2xl p-8">
                <!-- Header -->
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-14 h-14 bg-gradient-to-br from-purple-100 to-indigo-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-graduation-cap text-2xl text-purple-600"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Nueva Capacitación</h2>
                        <p class="text-gray-600">Programa una nueva capacitación para el desarrollo del equipo</p>
                    </div>
                </div>
                
                <!-- Mensajes de error -->
                <?php if (isset($errores) && !empty($errores)): ?>
                <div class="mb-6 p-4 rounded-lg border bg-gradient-to-r from-red-50 to-rose-50 border-red-200 text-red-700 shadow-sm">
                    <div class="flex items-center mb-2">
                        <i class="fas fa-exclamation-circle mr-3 text-lg"></i>
                        <span class="font-medium">Por favor, corrige los siguientes errores:</span>
                    </div>
                    <ul class="list-disc pl-10 mt-2 text-sm">
                        <?php foreach($errores as $campo => $mensaje): ?>
                            <li><span class="font-medium"><?php echo htmlspecialchars($campo); ?>:</span> <?php echo htmlspecialchars($mensaje); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endif; ?>
                
                <form method="POST" action="?accion=crear_capacitacion" class="space-y-6">
                    <input type="hidden" name="accion" value="crear_capacitacion">
                    <input type="hidden" name="origen" value="<?php echo isset($_GET['recurso_id']) ? 'detalle' : 'listado'; ?>">
                    
                    <!-- Información del recurso -->
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl p-6 mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Paso 1: Seleccionar Recurso</h3>
                        
                        <div>
                            <label class="block text-sm font-semibold text-gray-800 mb-3">Recurso Humano *</label>
                            <select name="recurso_humano_id" required 
                                    class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                                    onchange="cargarInfoRecurso(this.value)">
                                <option value="">Seleccionar recurso</option>
                                <?php if (isset($recursos) && is_array($recursos)): ?>
                                    <?php foreach ($recursos as $recurso): 
                                        $selected = (isset($_GET['recurso_id']) && $_GET['recurso_id'] == $recurso['id']) || 
                                                (isset($datos_form['recurso_humano_id']) && $datos_form['recurso_humano_id'] == $recurso['id']);
                                    ?>
                                    <option value="<?php echo htmlspecialchars($recurso['id']); ?>" 
                                            <?php echo $selected ? 'selected' : ''; ?>
                                            data-info='<?php 
                                                echo json_encode([
                                                    'nombre' => $recurso['usuario_nombre'] ?? '',
                                                    'proyecto' => $recurso['proyecto_nombre'] ?? '',
                                                    'rol' => $recurso['rol_proyecto'] ?? '',
                                                    'nivel' => $recurso['nivel_experiencia'] ?? 'intermedio',
                                                    'capacitacion_requerida' => $recurso['capacitacion_requerida'] ?? ''
                                                ]);
                                            ?>'>
                                        <?php echo htmlspecialchars($recurso['usuario_nombre'] ?? ''); ?> 
                                        (Proyecto: <?php echo htmlspecialchars($recurso['proyecto_nombre'] ?? ''); ?>)
                                    </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        
                        <!-- Información del recurso seleccionado -->
                        <div id="info-recurso" class="mt-4 bg-white p-4 rounded-xl border border-blue-200 hidden">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <div class="text-sm text-gray-500">Nombre</div>
                                    <div class="font-semibold text-gray-900" id="recurso-nombre">-</div>
                                </div>
                                <div>
                                    <div class="text-sm text-gray-500">Proyecto</div>
                                    <div class="font-semibold text-gray-900" id="recurso-proyecto">-</div>
                                </div>
                                <div>
                                    <div class="text-sm text-gray-500">Rol</div>
                                    <div class="font-semibold text-gray-900" id="recurso-rol">-</div>
                                </div>
                                <div>
                                    <div class="text-sm text-gray-500">Nivel</div>
                                    <div class="font-semibold text-gray-900 capitalize" id="recurso-nivel">-</div>
                                </div>
                            </div>
                            <div class="mt-3">
                                <div class="text-sm text-gray-500">Capacitación Requerida</div>
                                <div class="text-sm text-gray-700" id="recurso-capacitacion">Sin especificar</div>
                            </div>
                        </div>
                        
                        <?php if (isset($recurso_seleccionado) && !empty($recurso_seleccionado['capacitacion_requerida'])): ?>
                        <div class="mt-6 bg-gradient-to-r from-yellow-50 to-orange-50 rounded-2xl p-6 border-2 border-yellow-200">
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-lightbulb text-yellow-600"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900">Necesidad de Capacitación Detectada</h4>
                                    <p class="text-sm text-gray-600">Este recurso tiene una necesidad registrada</p>
                                </div>
                            </div>
                            
                            <div class="bg-white p-4 rounded-xl border border-yellow-200 mb-4">
                                <p class="text-gray-700"><?php echo nl2br(htmlspecialchars($recurso_seleccionado['capacitacion_requerida'])); ?></p>
                            </div>
                            
                            <div class="text-sm text-gray-600">
                                <div class="flex items-center gap-2 mb-2">
                                    <i class="fas fa-info-circle text-blue-500"></i>
                                    <span>Al crear esta capacitación, el sistema verificará si cubre esta necesidad.</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-check-circle text-green-500"></i>
                                    <span>Si es relacionada, la necesidad se marcará automáticamente como resuelta.</span>
                                </div>
                            </div>
                            
                            <div class="mt-4">
                                <label class="flex items-center gap-3 cursor-pointer">
                                    <input type="checkbox" name="marcar_como_resuelta" value="1" 
                                        class="w-5 h-5 text-teal-600 border-gray-300 rounded focus:ring-teal-500"
                                        checked>
                                    <span class="text-sm font-medium text-gray-800">Marcar necesidad como resuelta al crear esta capacitación</span>
                                </label>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Información de la capacitación -->
                    <div class="bg-gradient-to-r from-purple-50 to-indigo-50 rounded-2xl p-6 mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Paso 2: Información de la Capacitación</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-3">Tipo de Capacitación *</label>
                                <select name="tipo_capacitacion" required 
                                        class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                                    <option value="">Seleccionar tipo</option>
                                    <?php if (isset($tipos_capacitacion) && is_array($tipos_capacitacion)): ?>
                                        <?php foreach ($tipos_capacitacion as $tipo): 
                                            $selected = isset($datos_form['tipo_capacitacion']) && $datos_form['tipo_capacitacion'] == $tipo;
                                        ?>
                                        <option value="<?php echo htmlspecialchars($tipo); ?>" 
                                                <?php echo $selected ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($tipo); ?>
                                        </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-3">Estado *</label>
                                <select name="estado" required 
                                        class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                                    <?php if (isset($estados_capacitacion) && is_array($estados_capacitacion)): ?>
                                        <?php foreach ($estados_capacitacion as $estado): 
                                            $selected = isset($datos_form['estado']) ? $datos_form['estado'] == $estado : $estado == 'pendiente';
                                        ?>
                                        <option value="<?php echo htmlspecialchars($estado); ?>" 
                                                <?php echo $selected ? 'selected' : ''; ?>>
                                            <?php echo ucfirst($estado); ?>
                                        </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="mt-6">
                            <label class="block text-sm font-semibold text-gray-800 mb-3">Descripción *</label>
                            <textarea name="descripcion" rows="3" required
                                    class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                                    placeholder="Descripción detallada de la capacitación..."><?php echo isset($datos_form['descripcion']) ? htmlspecialchars($datos_form['descripcion']) : ''; ?></textarea>
                        </div>
                    </div>
                    
                    <!-- Duración y fechas -->
                    <div class="bg-gradient-to-r from-teal-50 to-green-50 rounded-2xl p-6 mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Paso 3: Duración y Fechas</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-3">Duración (horas) *</label>
                                <input type="number" name="duracion_horas" required min="1" step="1" 
                                    value="<?php echo isset($datos_form['duracion_horas']) ? htmlspecialchars($datos_form['duracion_horas']) : '8'; ?>"
                                    class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-3">Fecha Inicio *</label>
                                <input type="date" name="fecha_inicio" required 
                                    value="<?php echo isset($datos_form['fecha_inicio']) ? htmlspecialchars($datos_form['fecha_inicio']) : date('Y-m-d'); ?>"
                                    class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-3">Fecha Fin *</label>
                                <input type="date" name="fecha_fin" required 
                                    value="<?php echo isset($datos_form['fecha_fin']) ? htmlspecialchars($datos_form['fecha_fin']) : date('Y-m-d', strtotime('+1 week')); ?>"
                                    class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                            </div>
                        </div>
                        
                        <div class="mt-4 text-sm text-gray-600" id="info-fechas">
                            <!-- Información de fechas calculada por JavaScript -->
                        </div>
                    </div>
                    
                    <!-- Costo y certificación -->
                    <div class="bg-gradient-to-r from-amber-50 to-orange-50 rounded-2xl p-6 mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Paso 4: Costo y Certificación</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-3">Costo (USD)</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500">$</span>
                                    </div>
                                    <input type="number" name="costo" min="0" step="0.01" 
                                        value="<?php echo isset($datos_form['costo']) ? htmlspecialchars($datos_form['costo']) : '0.00'; ?>"
                                        class="form-input w-full pl-8 px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                                        placeholder="0.00">
                                </div>
                                <div class="text-xs text-gray-500 mt-2">Costo total de la capacitación</div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-3">Certificación</label>
                                <input type="text" name="certificacion" 
                                    value="<?php echo isset($datos_form['certificacion']) ? htmlspecialchars($datos_form['certificacion']) : ''; ?>"
                                    class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                                    placeholder="Ej: AWS Certified Solutions Architect">
                                <div class="text-xs text-gray-500 mt-2">Certificación obtenida al completar</div>
                            </div>
                        </div>
                        
                        <div class="mt-6">
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" name="notificar_recurso" value="1" 
                                    class="w-5 h-5 text-teal-600 border-gray-300 rounded focus:ring-teal-500"
                                    checked>
                                <span class="text-sm font-medium text-gray-800">Notificar al recurso sobre esta capacitación</span>
                            </label>
                            <label class="flex items-center gap-3 cursor-pointer mt-2">
                                <input type="checkbox" name="crear_recordatorio" value="1" 
                                    class="w-5 h-5 text-teal-600 border-gray-300 rounded focus:ring-teal-500"
                                    checked>
                                <span class="text-sm font-medium text-gray-800">Crear recordatorio en el calendario</span>
                            </label>
                        </div>
                    </div>
                    
                    <!-- Botones de acción -->
                    <div class="pt-6 border-t border-gray-200">
                        <div class="flex justify-between">
                            <?php if (isset($_GET['recurso_id'])): ?>
                                <a href="?accion=ver&id=<?php echo $_GET['recurso_id']; ?>" 
                                class="px-8 py-3 border-2 border-gray-300 text-gray-700 hover:bg-gray-50 rounded-xl font-medium transition-all hover-lift">
                                    <i class="fas fa-arrow-left mr-2"></i>
                                    Volver al Recurso
                                </a>
                            <?php else: ?>
                                <a href="?accion=capacitaciones" 
                                class="px-8 py-3 border-2 border-gray-300 text-gray-700 hover:bg-gray-50 rounded-xl font-medium transition-all hover-lift">
                                    <i class="fas fa-times mr-2"></i>
                                    Cancelar
                                </a>
                            <?php endif; ?>
                            
                            <div class="flex gap-4">
                                <button type="submit" name="guardar_y_otra" 
                                        class="px-8 py-3 bg-gradient-to-r from-emerald-500 to-green-500 hover:opacity-90 text-white rounded-xl font-medium shadow-md hover-lift flex items-center gap-2">
                                    <i class="fas fa-save"></i>
                                    Guardar y Crear Otra
                                </button>
                                <button type="submit" name="guardar" 
                                        class="gradient-bg-proceso4 hover:opacity-90 text-white px-8 py-3 rounded-xl font-medium shadow-md hover-lift flex items-center gap-2">
                                    <i class="fas fa-check"></i>
                                    Crear Capacitación
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <script>
            // Datos de recursos
            const recursosData = {};
            <?php if (isset($recursos) && is_array($recursos)): ?>
                <?php foreach ($recursos as $recurso): ?>
                recursosData[<?php echo $recurso['id']; ?>] = {
                    nombre: "<?php echo addslashes($recurso['usuario_nombre'] ?? ''); ?>",
                    proyecto: "<?php echo addslashes($recurso['proyecto_nombre'] ?? ''); ?>",
                    rol: "<?php echo addslashes($recurso['rol_proyecto'] ?? ''); ?>",
                    nivel: "<?php echo addslashes($recurso['nivel_experiencia'] ?? ''); ?>",
                    capacitacion_requerida: "<?php echo addslashes($recurso['capacitacion_requerida'] ?? ''); ?>"
                };
                <?php endforeach; ?>
            <?php endif; ?>
            
            function cargarInfoRecurso(id) {
                const infoDiv = document.getElementById('info-recurso');
                if (id && recursosData[id]) {
                    const data = recursosData[id];
                    document.getElementById('recurso-nombre').textContent = data.nombre;
                    document.getElementById('recurso-proyecto').textContent = data.proyecto;
                    document.getElementById('recurso-rol').textContent = data.rol;
                    document.getElementById('recurso-nivel').textContent = data.nivel.charAt(0).toUpperCase() + data.nivel.slice(1);
                    document.getElementById('recurso-capacitacion').textContent = 
                        data.capacitacion_requerida || 'Sin especificar';
                    infoDiv.classList.remove('hidden');
                    
                    // Si hay capacitación requerida, sugerir en la descripción
                    if (data.capacitacion_requerida && !document.querySelector('textarea[name="descripcion"]').value) {
                        document.querySelector('textarea[name="descripcion"]').value = 
                            data.capacitacion_requerida;
                    }
                } else {
                    infoDiv.classList.add('hidden');
                }
            }
            
            // Calcular información de fechas
            function calcularInfoFechas() {
                const fechaInicio = document.querySelector('input[name="fecha_inicio"]');
                const fechaFin = document.querySelector('input[name="fecha_fin"]');
                const duracion = document.querySelector('input[name="duracion_horas"]');
                const infoDiv = document.getElementById('info-fechas');
                
                if (fechaInicio.value && fechaFin.value && duracion.value) {
                    const inicio = new Date(fechaInicio.value);
                    const fin = new Date(fechaFin.value);
                    const diffTime = Math.abs(fin - inicio);
                    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                    const horasPorDia = duracion.value / (diffDays + 1);
                    
                    let infoHTML = '<div class="grid grid-cols-1 md:grid-cols-3 gap-4">';
                    infoHTML += `<div><strong>Duración total:</strong> ${diffDays + 1} días</div>`;
                    infoHTML += `<div><strong>Inicio:</strong> ${inicio.toLocaleDateString('es-ES')}</div>`;
                    infoHTML += `<div><strong>Fin:</strong> ${fin.toLocaleDateString('es-ES')}</div>`;
                    
                    if (horasPorDia > 0) {
                        infoHTML += `<div class="md:col-span-3"><strong>Horas por día:</strong> ${horasPorDia.toFixed(1)} horas</div>`;
                    }
                    
                    infoHTML += '</div>';
                    infoDiv.innerHTML = infoHTML;
                }
            }
            
            // Inicializar
            document.addEventListener('DOMContentLoaded', function() {
                const selectRecurso = document.querySelector('select[name="recurso_humano_id"]');
                if (selectRecurso && selectRecurso.value) {
                    cargarInfoRecurso(selectRecurso.value);
                }
                
                // Calcular fechas al cargar
                calcularInfoFechas();
                
                // Actualizar información de fechas al cambiar
                const fechasInputs = document.querySelectorAll('input[name="fecha_inicio"], input[name="fecha_fin"], input[name="duracion_horas"]');
                fechasInputs.forEach(input => {
                    input.addEventListener('change', calcularInfoFechas);
                });
                
                // Validar que fecha fin sea mayor o igual a fecha inicio
                const fechaInicio = document.querySelector('input[name="fecha_inicio"]');
                const fechaFin = document.querySelector('input[name="fecha_fin"]');
                
                fechaInicio.addEventListener('change', function() {
                    if (fechaFin.value && this.value > fechaFin.value) {
                        fechaFin.value = this.value;
                        calcularInfoFechas();
                    }
                });
                
                fechaFin.addEventListener('change', function() {
                    if (fechaInicio.value && this.value < fechaInicio.value) {
                        alert('La fecha de fin no puede ser anterior a la fecha de inicio');
                        this.value = fechaInicio.value;
                        calcularInfoFechas();
                    }
                });
            });
        </script>

        <?php endif; ?>
        <!-- ========== FIN SECCIÓN CREAR CAPACITACIÓN ========== -->

        <!-- ========== SECCIÓN: EDITAR CAPACITACIÓN ========== -->
        <?php if ($accion === 'editar_capacitacion' && isset($capacitacion)): ?>

        <div class="max-w-4xl mx-auto">
            <div class="glass-card rounded-2xl p-8">
                <!-- Header -->
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-14 h-14 bg-gradient-to-br from-purple-100 to-indigo-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-edit text-2xl text-purple-600"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Editar Capacitación</h2>
                        <p class="text-gray-600">ID: <?php echo htmlspecialchars($capacitacion['id']); ?> | Recurso: <?php echo htmlspecialchars($capacitacion['usuario_nombre'] ?? ''); ?></p>
                    </div>
                </div>
                
                <!-- Información actual -->
                <div class="mb-8 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Información Actual</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <div class="text-sm text-gray-600">Recurso</div>
                            <div class="font-semibold text-gray-900"><?php echo htmlspecialchars($capacitacion['usuario_nombre'] ?? ''); ?></div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-600">Proyecto</div>
                            <div class="font-semibold text-gray-900"><?php echo htmlspecialchars($capacitacion['proyecto_nombre'] ?? ''); ?></div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-600">Estado Actual</div>
                            <?php 
                            $estado_colores = [
                                'pendiente' => 'bg-yellow-100 text-yellow-800',
                                'en_curso' => 'bg-blue-100 text-blue-800',
                                'completada' => 'bg-green-100 text-green-800',
                                'cancelada' => 'bg-red-100 text-red-800'
                            ];
                            $estado_actual = $capacitacion['estado'] ?? 'pendiente';
                            $color_actual = $estado_colores[$estado_actual] ?? 'bg-gray-100 text-gray-800';
                            ?>
                            <span class="status-badge <?php echo $color_actual; ?>">
                                <?php echo ucfirst($estado_actual); ?>
                            </span>
                        </div>
                        <div>
                            <div class="text-sm text-gray-600">Última Actualización</div>
                            <div class="font-semibold text-gray-900">
                                <?php echo isset($capacitacion['fecha_modificacion']) ? date('d/m/Y H:i', strtotime($capacitacion['fecha_modificacion'])) : 'N/A'; ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Mensajes de error -->
                <?php if (isset($errores) && !empty($errores)): ?>
                <div class="mb-6 p-4 rounded-lg border bg-gradient-to-r from-red-50 to-rose-50 border-red-200 text-red-700 shadow-sm">
                    <div class="flex items-center mb-2">
                        <i class="fas fa-exclamation-circle mr-3 text-lg"></i>
                        <span class="font-medium">Por favor, corrige los siguientes errores:</span>
                    </div>
                    <ul class="list-disc pl-10 mt-2 text-sm">
                        <?php foreach($errores as $campo => $mensaje): ?>
                            <li><span class="font-medium"><?php echo htmlspecialchars($campo); ?>:</span> <?php echo htmlspecialchars($mensaje); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endif; ?>
                
                <form method="POST" action="?accion=editar_capacitacion" class="space-y-6">
                    <input type="hidden" name="accion" value="editar_capacitacion">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($capacitacion['id']); ?>">
                    
                    <!-- Información del recurso -->
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl p-6 mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Recurso Asignado</h3>
                        
                        <div class="bg-white p-4 rounded-xl border border-blue-200">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <div class="text-sm text-gray-600">Recurso</div>
                                    <div class="font-semibold text-gray-900"><?php echo htmlspecialchars($capacitacion['usuario_nombre'] ?? ''); ?></div>
                                </div>
                                <div>
                                    <div class="text-sm text-gray-600">Proyecto</div>
                                    <div class="font-semibold text-gray-900"><?php echo htmlspecialchars($capacitacion['proyecto_nombre'] ?? ''); ?></div>
                                </div>
                            </div>
                            <input type="hidden" name="recurso_humano_id" value="<?php echo htmlspecialchars($capacitacion['recurso_humano_id']); ?>">
                            <div class="mt-3 text-sm text-gray-500">
                                <i class="fas fa-info-circle mr-1"></i>
                                No se puede cambiar el recurso una vez creada la capacitación
                            </div>
                        </div>
                    </div>
                    
                    <!-- Información de la capacitación -->
                    <div class="bg-gradient-to-r from-purple-50 to-indigo-50 rounded-2xl p-6 mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Información de la Capacitación</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-3">Tipo de Capacitación *</label>
                                <select name="tipo_capacitacion" required 
                                        class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                                    <option value="">Seleccionar tipo</option>
                                    <?php if (isset($tipos_capacitacion) && is_array($tipos_capacitacion)): ?>
                                        <?php foreach ($tipos_capacitacion as $tipo): ?>
                                        <option value="<?php echo htmlspecialchars($tipo); ?>" 
                                                <?php echo (isset($datos_form['tipo_capacitacion']) && $datos_form['tipo_capacitacion'] == $tipo) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($tipo); ?>
                                        </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-3">Estado *</label>
                                <select name="estado" required 
                                        class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                                    <?php if (isset($estados_capacitacion) && is_array($estados_capacitacion)): ?>
                                        <?php foreach ($estados_capacitacion as $estado): ?>
                                        <option value="<?php echo htmlspecialchars($estado); ?>" 
                                                <?php echo (isset($datos_form['estado']) && $datos_form['estado'] == $estado) ? 'selected' : ''; ?>>
                                            <?php echo ucfirst($estado); ?>
                                        </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="mt-6">
                            <label class="block text-sm font-semibold text-gray-800 mb-3">Descripción *</label>
                            <textarea name="descripcion" rows="3" required
                                    class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                                    placeholder="Descripción detallada de la capacitación..."><?php echo isset($datos_form['descripcion']) ? htmlspecialchars($datos_form['descripcion']) : ''; ?></textarea>
                        </div>
                    </div>
                    
                    <!-- Duración y fechas -->
                    <div class="bg-gradient-to-r from-teal-50 to-green-50 rounded-2xl p-6 mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Duración y Fechas</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-3">Duración (horas) *</label>
                                <input type="number" name="duracion_horas" required min="1" step="1" 
                                    value="<?php echo isset($datos_form['duracion_horas']) ? htmlspecialchars($datos_form['duracion_horas']) : '8'; ?>"
                                    class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-3">Fecha Inicio *</label>
                                <input type="date" name="fecha_inicio" required 
                                    value="<?php echo isset($datos_form['fecha_inicio']) ? htmlspecialchars($datos_form['fecha_inicio']) : date('Y-m-d'); ?>"
                                    class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-3">Fecha Fin *</label>
                                <input type="date" name="fecha_fin" required 
                                    value="<?php echo isset($datos_form['fecha_fin']) ? htmlspecialchars($datos_form['fecha_fin']) : date('Y-m-d', strtotime('+1 week')); ?>"
                                    class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                            </div>
                        </div>
                        
                        <div class="mt-4 text-sm text-gray-600" id="info-fechas">
                            <!-- Información de fechas calculada por JavaScript -->
                        </div>
                    </div>
                    
                    <!-- Costo y certificación -->
                    <div class="bg-gradient-to-r from-amber-50 to-orange-50 rounded-2xl p-6 mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Costo y Certificación</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-3">Costo (USD)</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500">$</span>
                                    </div>
                                    <input type="number" name="costo" min="0" step="0.01" 
                                        value="<?php echo isset($datos_form['costo']) ? htmlspecialchars($datos_form['costo']) : '0.00'; ?>"
                                        class="form-input w-full pl-8 px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                                        placeholder="0.00">
                                </div>
                                <div class="text-xs text-gray-500 mt-2">Costo total de la capacitación</div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-3">Certificación</label>
                                <input type="text" name="certificacion" 
                                    value="<?php echo isset($datos_form['certificacion']) ? htmlspecialchars($datos_form['certificacion']) : ''; ?>"
                                    class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                                    placeholder="Ej: AWS Certified Solutions Architect">
                                <div class="text-xs text-gray-500 mt-2">Certificación obtenida al completar</div>
                            </div>
                        </div>
                        
                        <div class="mt-6">
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" name="notificar_recurso" value="1" 
                                    class="w-5 h-5 text-teal-600 border-gray-300 rounded focus:ring-teal-500"
                                    checked>
                                <span class="text-sm font-medium text-gray-800">Notificar al recurso sobre los cambios</span>
                            </label>
                            <label class="flex items-center gap-3 cursor-pointer mt-2">
                                <input type="checkbox" name="actualizar_calendario" value="1" 
                                    class="w-5 h-5 text-teal-600 border-gray-300 rounded focus:ring-teal-500"
                                    checked>
                                <span class="text-sm font-medium text-gray-800">Actualizar fechas en el calendario</span>
                            </label>
                        </div>
                    </div>
                    
                    <!-- Botones de acción -->
                    <div class="pt-6 border-t border-gray-200">
                        <div class="flex justify-between">
                            <div>
                                <a href="?accion=capacitaciones" 
                                class="px-8 py-3 border-2 border-gray-300 text-gray-700 hover:bg-gray-50 rounded-xl font-medium transition-all hover-lift mr-4">
                                    <i class="fas fa-times mr-2"></i>
                                    Cancelar
                                </a>
                                <a href="?accion=ver&id=<?php echo $capacitacion['recurso_humano_id']; ?>" 
                                class="px-8 py-3 bg-gradient-to-r from-blue-500 to-indigo-500 hover:opacity-90 text-white rounded-xl font-medium shadow-md hover-lift">
                                    <i class="fas fa-eye mr-2"></i>
                                    Ver Recurso
                                </a>
                            </div>
                            <div class="flex gap-4">
                                <button type="button" onclick="confirmarEliminacion()"
                                        class="px-8 py-3 border-2 border-red-300 text-red-700 hover:bg-red-50 rounded-xl font-medium transition-all hover-lift">
                                    <i class="fas fa-trash mr-2"></i>
                                    Eliminar
                                </button>
                                <button type="submit" 
                                        class="gradient-bg-proceso4 hover:opacity-90 text-white px-8 py-3 rounded-xl font-medium shadow-md hover-lift flex items-center gap-2">
                                    <i class="fas fa-save"></i>
                                    Guardar Cambios
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal de confirmación de eliminación -->
        <div id="modalEliminar" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50 hidden">
            <div class="bg-white rounded-2xl p-8 max-w-md w-full mx-4">
                <div class="text-center mb-6">
                    <div class="w-16 h-16 mx-auto bg-gradient-to-r from-red-100 to-rose-100 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-exclamation-triangle text-2xl text-red-600"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">¿Eliminar Capacitación?</h3>
                    <p class="text-gray-600 mb-6">
                        Estás a punto de eliminar la capacitación 
                        <span class="font-semibold"><?php echo htmlspecialchars($capacitacion['tipo_capacitacion'] ?? ''); ?></span>
                        asignada a <span class="font-semibold"><?php echo htmlspecialchars($capacitacion['usuario_nombre'] ?? ''); ?></span>.
                    </p>
                    <p class="text-sm text-gray-500 mb-6">
                        Esta acción eliminará todos los datos asociados a esta capacitación.
                        <span class="block font-semibold text-red-600 mt-2">Esta acción no se puede deshacer.</span>
                    </p>
                </div>
                <div class="flex justify-center gap-4">
                    <button type="button" onclick="cerrarModal()"
                            class="px-6 py-3 border-2 border-gray-300 text-gray-700 hover:bg-gray-50 rounded-xl font-medium flex-1">
                        Cancelar
                    </button>
                    <form method="POST" action="" class="flex-1">
                        <input type="hidden" name="accion" value="eliminar_capacitacion">
                        <input type="hidden" name="id" value="<?php echo $capacitacion['id']; ?>">
                        <button type="submit" 
                                class="w-full px-6 py-3 bg-gradient-to-r from-red-500 to-rose-500 hover:opacity-90 text-white rounded-xl font-medium">
                            Confirmar Eliminación
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <script>
            // Función para calcular información de fechas
            function calcularInfoFechas() {
                const fechaInicio = document.querySelector('input[name="fecha_inicio"]');
                const fechaFin = document.querySelector('input[name="fecha_fin"]');
                const duracion = document.querySelector('input[name="duracion_horas"]');
                const infoDiv = document.getElementById('info-fechas');
                
                if (fechaInicio.value && fechaFin.value && duracion.value) {
                    const inicio = new Date(fechaInicio.value);
                    const fin = new Date(fechaFin.value);
                    const diffTime = Math.abs(fin - inicio);
                    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                    const horasPorDia = duracion.value / (diffDays + 1);
                    
                    let infoHTML = '<div class="grid grid-cols-1 md:grid-cols-3 gap-4">';
                    infoHTML += `<div><strong>Duración total:</strong> ${diffDays + 1} días</div>`;
                    infoHTML += `<div><strong>Inicio:</strong> ${inicio.toLocaleDateString('es-ES')}</div>`;
                    infoHTML += `<div><strong>Fin:</strong> ${fin.toLocaleDateString('es-ES')}</div>`;
                    
                    if (horasPorDia > 0) {
                        infoHTML += `<div class="md:col-span-3"><strong>Horas por día:</strong> ${horasPorDia.toFixed(1)} horas</div>`;
                    }
                    
                    infoHTML += '</div>';
                    infoDiv.innerHTML = infoHTML;
                }
            }
            
            // Inicializar cálculo de fechas
            document.addEventListener('DOMContentLoaded', function() {
                calcularInfoFechas();
                
                // Actualizar información de fechas al cambiar
                const fechasInputs = document.querySelectorAll('input[name="fecha_inicio"], input[name="fecha_fin"], input[name="duracion_horas"]');
                fechasInputs.forEach(input => {
                    input.addEventListener('change', calcularInfoFechas);
                });
                
                // Validar que fecha fin sea mayor o igual a fecha inicio
                const fechaInicio = document.querySelector('input[name="fecha_inicio"]');
                const fechaFin = document.querySelector('input[name="fecha_fin"]');
                
                fechaInicio.addEventListener('change', function() {
                    if (fechaFin.value && this.value > fechaFin.value) {
                        fechaFin.value = this.value;
                        calcularInfoFechas();
                    }
                });
                
                fechaFin.addEventListener('change', function() {
                    if (fechaInicio.value && this.value < fechaInicio.value) {
                        alert('La fecha de fin no puede ser anterior a la fecha de inicio');
                        this.value = fechaInicio.value;
                        calcularInfoFechas();
                    }
                });
            });
            
            function confirmarEliminacion() {
                document.getElementById('modalEliminar').classList.remove('hidden');
            }
            
            function cerrarModal() {
                document.getElementById('modalEliminar').classList.add('hidden');
            }
            
            // Cerrar modal al hacer clic fuera
            document.getElementById('modalEliminar').addEventListener('click', function(e) {
                if (e.target.id === 'modalEliminar') {
                    cerrarModal();
                }
            });
            
            // Cerrar modal con ESC
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    cerrarModal();
                }
            });
        </script>

        <?php endif; ?>
        <!-- ========== FIN SECCIÓN EDITAR CAPACITACIÓN ========== -->

        <!-- ========== SECCIÓN: REPORTES ========== -->
        <?php if ($accion === 'reportes'): ?>

        <div class="glass-card rounded-2xl p-8">
            <!-- Header -->
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Reportes y Métricas</h2>
                    <p class="text-gray-600">Análisis de desempeño y desarrollo del equipo del proyecto</p>
                </div>
                <div class="flex gap-3">
                    <button onclick="window.print()" 
                            class="px-4 py-2.5 border border-gray-300 text-gray-700 hover:bg-gray-50 rounded-xl font-medium flex items-center gap-2">
                        <i class="fas fa-print"></i>
                        Imprimir
                    </button>
                    <button onclick="exportarPDF()" 
                            class="gradient-bg-proceso4 hover:opacity-90 text-white px-4 py-2.5 rounded-xl font-medium flex items-center gap-2">
                        <i class="fas fa-file-pdf"></i>
                        Exportar PDF
                    </button>
                </div>
            </div>
            
            <!-- Filtros -->
            <div class="mb-8 bg-gradient-to-r from-teal-50 to-green-50 rounded-2xl p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Filtrar Reportes</h3>
                <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <input type="hidden" name="accion" value="reportes">
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Proyecto</label>
                        <select name="proyecto_id" class="form-input w-full px-4 py-2.5 border border-gray-300 rounded-xl">
                            <option value="">Todos los proyectos</option>
                            <?php if (isset($proyectos)): ?>
                                <?php foreach ($proyectos as $proyecto): ?>
                                    <option value="<?php echo htmlspecialchars($proyecto['id'] ?? ''); ?>" 
                                        <?php echo (isset($_GET['proyecto_id']) && $_GET['proyecto_id'] == $proyecto['id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($proyecto['nombre'] ?? ''); ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Periodo</label>
                        <select name="periodo" class="form-input w-full px-4 py-2.5 border border-gray-300 rounded-xl">
                            <option value="mes_actual" <?php echo (isset($_GET['periodo']) && $_GET['periodo'] == 'mes_actual') ? 'selected' : ''; ?>>Mes Actual</option>
                            <option value="trimestre_actual" <?php echo (isset($_GET['periodo']) && $_GET['periodo'] == 'trimestre_actual') ? 'selected' : ''; ?>>Trimestre Actual</option>
                            <option value="ano_actual" <?php echo (isset($_GET['periodo']) && $_GET['periodo'] == 'ano_actual') ? 'selected' : ''; ?>>Año Actual</option>
                            <option value="todo" <?php echo (isset($_GET['periodo']) && $_GET['periodo'] == 'todo') ? 'selected' : ''; ?>>Todo el Historial</option>
                        </select>
                    </div>
                    
                    <div class="flex items-end">
                        <button type="submit" 
                                class="w-full gradient-bg-proceso4 hover:opacity-90 text-white px-4 py-2.5 rounded-xl font-medium flex items-center justify-center gap-2">
                            <i class="fas fa-chart-line"></i>
                            Generar Reporte
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Estadísticas principales -->
            <?php if (isset($estadisticas)): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm hover-lift">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 bg-gradient-to-r from-teal-50 to-teal-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-users text-teal-600 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Recursos Totales</p>
                            <p class="text-2xl font-bold text-gray-800"><?php echo $estadisticas['total_recursos'] ?? 0; ?></p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm hover-lift">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 bg-gradient-to-r from-blue-50 to-blue-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-graduation-cap text-blue-600 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Capacitaciones</p>
                            <p class="text-2xl font-bold text-gray-800"><?php echo $estadisticas['total_capacitaciones'] ?? 0; ?></p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm hover-lift">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 bg-gradient-to-r from-green-50 to-green-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-clock text-green-600 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Horas Trabajadas</p>
                            <p class="text-2xl font-bold text-gray-800"><?php echo number_format($estadisticas['total_horas_trabajadas'] ?? 0, 0); ?></p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm hover-lift">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 bg-gradient-to-r from-purple-50 to-purple-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-money-bill-wave text-purple-600 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Inversión Total</p>
                            <p class="text-2xl font-bold text-gray-800">
                                $<?php echo isset($estadisticas['total_costo_capacitacion']) ? number_format($estadisticas['total_costo_capacitacion'], 2) : '0.00'; ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Resumen ejecutivo -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                <!-- Panel de resumen -->
                <div class="bg-gradient-to-r from-gray-900 to-gray-800 rounded-2xl p-6 text-white">
                    <h3 class="text-xl font-bold mb-6">Resumen Ejecutivo</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <div class="text-sm text-gray-300 mb-1">Retorno Estimado por Capacitación</div>
                            <div class="text-3xl font-bold text-teal-400">
                                <?php echo number_format($estadisticas['horas_mejora_estimada'] ?? 0, 0); ?> horas
                            </div>
                            <div class="text-xs text-gray-400">15% mejora estimada por capacitación</div>
                        </div>
                        
                        <div>
                            <div class="text-sm text-gray-300 mb-1">Capacitaciones Atrasadas</div>
                            <div class="text-3xl font-bold <?php echo ($estadisticas['capacitaciones_atrasadas'] ?? 0) > 0 ? 'text-red-400' : 'text-green-400'; ?>">
                                <?php echo $estadisticas['capacitaciones_atrasadas'] ?? 0; ?>
                            </div>
                        </div>
                        
                        <div>
                            <div class="text-sm text-gray-300 mb-1">Eficiencia del Equipo</div>
                            <div class="text-3xl font-bold text-green-400">
                                <?php echo number_format($estadisticas['eficiencia_equipo'] ?? 0, 1); ?>%
                            </div>
                        </div>
                        
                        <div>
                            <div class="text-sm text-gray-300 mb-1">Tasa de Completitud</div>
                            <div class="text-3xl font-bold text-blue-400">
                                <?php echo number_format($estadisticas['tasa_completitud_capacitaciones'] ?? 0, 1); ?>%
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Distribución por nivel -->
                <div class="bg-white rounded-2xl p-6 border border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-6">Distribución por Nivel de Experiencia</h3>
                    
                    <div class="space-y-4">
                        <?php 
                        $niveles = ['junior', 'intermedio', 'senior', 'experto'];
                        $colores_niveles = [
                            'junior' => 'bg-red-500',
                            'intermedio' => 'bg-yellow-500', 
                            'senior' => 'bg-green-500',
                            'experto' => 'bg-purple-500'
                        ];
                        
                        $nombres_niveles = [
                            'junior' => 'Junior',
                            'intermedio' => 'Intermedio',
                            'senior' => 'Senior',
                            'experto' => 'Experto'
                        ];
                        
                        $total_personas = array_sum($estadisticas['distribucion_niveles'] ?? []);
                        foreach($niveles as $nivel): 
                            $cantidad = $estadisticas['distribucion_niveles'][$nivel] ?? 0;
                            $porcentaje = $total_personas > 0 ? ($cantidad / $total_personas) * 100 : 0;
                        ?>
                        <div>
                            <div class="flex justify-between text-sm text-gray-600 mb-1">
                                <span class="font-medium"><?php echo $nombres_niveles[$nivel]; ?></span>
                                <span><?php echo $cantidad; ?> (<?php echo number_format($porcentaje, 1); ?>%)</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="<?php echo $colores_niveles[$nivel]; ?> h-2 rounded-full" 
                                    style="width: <?php echo $porcentaje; ?>%"></div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            
            <!-- Gráficos y métricas detalladas -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                <!-- Horas trabajadas por proyecto -->
                <div class="bg-white rounded-2xl p-6 border border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-6">Horas Trabajadas por Proyecto</h3>
                    
                    <?php if (isset($distribucion_proyectos) && !empty($distribucion_proyectos)): ?>
                    <div class="space-y-4">
                        <?php foreach($distribucion_proyectos as $proyecto): 
                            $porcentaje = $proyecto['porcentaje_completado'] ?? 0;
                            $barra_color = $porcentaje >= 80 ? 'bg-green-500' : ($porcentaje >= 50 ? 'bg-yellow-500' : 'bg-red-500');
                        ?>
                        <div>
                            <div class="flex justify-between text-sm text-gray-600 mb-1">
                                <span class="font-medium truncate"><?php echo htmlspecialchars($proyecto['proyecto'] ?? 'Sin proyecto'); ?></span>
                                <span><?php echo $proyecto['horas_trabajadas'] ?? 0; ?>/<?php echo $proyecto['horas_asignadas'] ?? 0; ?></span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="<?php echo $barra_color; ?> h-2 rounded-full" 
                                    style="width: <?php echo min($porcentaje, 100); ?>%"></div>
                            </div>
                            <div class="text-xs text-gray-500 mt-1">
                                <?php echo number_format($porcentaje, 1); ?>% completado | 
                                <?php echo $proyecto['total_recursos'] ?? 0; ?> recursos
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php else: ?>
                    <div class="text-center py-8">
                        <i class="fas fa-clock text-4xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500">No hay datos de horas trabajadas</p>
                    </div>
                    <?php endif; ?>
                </div>
                
                <!-- Efectividad de capacitaciones -->
                <div class="bg-white rounded-2xl p-6 border border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-6">Estado de Capacitaciones</h3>
                    
                    <?php 
                    $estados_capacitacion = ['pendiente', 'en_curso', 'completada', 'cancelada'];
                    $colores_estados = [
                        'pendiente' => 'bg-yellow-500',
                        'en_curso' => 'bg-blue-500',
                        'completada' => 'bg-green-500',
                        'cancelada' => 'bg-red-500'
                    ];
                    
                    $nombres_estados = [
                        'pendiente' => 'Pendiente',
                        'en_curso' => 'En Curso',
                        'completada' => 'Completada',
                        'cancelada' => 'Cancelada'
                    ];
                    
                    if (isset($estadisticas['capacitaciones_por_estado'])): 
                        $total_capacitaciones = array_sum($estadisticas['capacitaciones_por_estado']);
                    ?>
                    <div class="space-y-4">
                        <?php foreach($estados_capacitacion as $estado): 
                            $cantidad = $estadisticas['capacitaciones_por_estado'][$estado] ?? 0;
                            $porcentaje = $total_capacitaciones > 0 ? ($cantidad / $total_capacitaciones) * 100 : 0;
                        ?>
                        <div>
                            <div class="flex justify-between text-sm text-gray-600 mb-1">
                                <span class="font-medium"><?php echo $nombres_estados[$estado]; ?></span>
                                <span><?php echo $cantidad; ?> (<?php echo number_format($porcentaje, 1); ?>%)</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="<?php echo $colores_estados[$estado]; ?> h-2 rounded-full" 
                                    style="width: <?php echo $porcentaje; ?>%"></div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="mt-6 pt-4 border-t border-gray-200">
                        <div class="text-sm text-gray-600">
                            <div class="flex justify-between mb-1">
                                <span>Capacitaciones por recurso:</span>
                                <span class="font-medium"><?php echo $estadisticas['total_capacitaciones'] > 0 && $estadisticas['total_recursos'] > 0 ? number_format($estadisticas['total_capacitaciones'] / $estadisticas['total_recursos'], 1) : '0.0'; ?></span>
                            </div>
                            <div class="flex justify-between mb-1">
                                <span>Horas de capacitación por recurso:</span>
                                <span class="font-medium"><?php echo number_format($estadisticas['horas_capacitacion_por_recurso'] ?? 0, 1); ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span>Costo promedio por capacitación:</span>
                                <span class="font-medium">$<?php echo number_format($estadisticas['costo_promedio_capacitacion'] ?? 0, 2); ?></span>
                            </div>
                        </div>
                    </div>
                    <?php else: ?>
                    <div class="text-center py-8">
                        <i class="fas fa-graduation-cap text-4xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500">No hay datos de capacitaciones</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Distribución de costos por tipo de capacitación -->
            <?php if (isset($metricas_costos) && !empty($metricas_costos)): ?>
            <div class="bg-white rounded-2xl p-6 border border-gray-200 mb-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">Distribución de Costos por Tipo de Capacitación</h3>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gradient-to-r from-purple-50 to-purple-100">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Tipo de Capacitación</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Cantidad</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Horas Promedio</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Costo Promedio</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Costo Total</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">% del Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <?php 
                            $costo_total_tipos = array_sum(array_column($metricas_costos, 'costo_total'));
                            foreach($metricas_costos as $tipo): 
                                $porcentaje = $costo_total_tipos > 0 ? ($tipo['costo_total'] / $costo_total_tipos) * 100 : 0;
                            ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="font-medium text-gray-900"><?php echo htmlspecialchars($tipo['tipo_capacitacion'] ?? 'Sin tipo'); ?></div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-lg font-semibold text-gray-700"><?php echo $tipo['cantidad'] ?? 0; ?></div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-gray-600"><?php echo number_format($tipo['horas_promedio'] ?? 0, 1); ?> horas</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-green-600 font-medium">$<?php echo number_format($tipo['costo_promedio'] ?? 0, 2); ?></div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-green-600 font-bold">$<?php echo number_format($tipo['costo_total'] ?? 0, 2); ?></div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-purple-500 h-2 rounded-full" style="width: <?php echo $porcentaje; ?>%"></div>
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1 text-center"><?php echo number_format($porcentaje, 1); ?>%</div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Tabla detallada de métricas -->
            <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden mb-8">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-6">Métricas de Desempeño</h3>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gradient-to-r from-teal-50 to-teal-100">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Métrica</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Valor Actual</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Objetivo</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Estado</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <?php 
                                $metricas = [
                                    [
                                        'nombre' => 'Horas de Capacitación por Recurso',
                                        'valor' => number_format($estadisticas['horas_capacitacion_por_recurso'] ?? 0, 1),
                                        'objetivo' => '20.0',
                                        'icon' => 'fa-graduation-cap'
                                    ],
                                    [
                                        'nombre' => 'Costo Promedio por Capacitación',
                                        'valor' => '$' . number_format($estadisticas['costo_promedio_capacitacion'] ?? 0, 2),
                                        'objetivo' => '$500.00',
                                        'icon' => 'fa-money-bill-wave'
                                    ],
                                    [
                                        'nombre' => 'Tasa de Completitud de Capacitaciones',
                                        'valor' => number_format($estadisticas['tasa_completitud_capacitaciones'] ?? 0, 1) . '%',
                                        'objetivo' => '85%',
                                        'icon' => 'fa-check-circle'
                                    ],
                                    [
                                        'nombre' => 'Horas Trabajadas Promedio',
                                        'valor' => number_format($estadisticas['horas_trabajadas_promedio'] ?? 0, 1),
                                        'objetivo' => '160.0',
                                        'icon' => 'fa-clock'
                                    ],
                                    [
                                        'nombre' => 'Eficiencia del Equipo',
                                        'valor' => number_format($estadisticas['eficiencia_equipo'] ?? 0, 1) . '%',
                                        'objetivo' => '90%',
                                        'icon' => 'fa-chart-line'
                                    ],
                                    [
                                        'nombre' => 'Porcentaje de Horas Completadas',
                                        'valor' => number_format($estadisticas['porcentaje_horas_completadas'] ?? 0, 1) . '%',
                                        'objetivo' => '95%',
                                        'icon' => 'fa-percentage'
                                    ]
                                ];
                                
                                foreach($metricas as $metrica): 
                                    $valor_num = floatval(str_replace(['$', '%'], '', $metrica['valor']));
                                    $objetivo_num = floatval(str_replace(['$', '%'], '', $metrica['objetivo']));
                                    
                                    $estado = '';
                                    $color = '';
                                    $diferencia = $valor_num - $objetivo_num;
                                    
                                    if ($diferencia >= 0) {
                                        $estado = 'Superado';
                                        $color = 'bg-green-100 text-green-800';
                                        $icono = 'fa-check-circle';
                                    } elseif ($diferencia >= -10) {
                                        $estado = 'En Progreso';
                                        $color = 'bg-yellow-100 text-yellow-800';
                                        $icono = 'fa-spinner';
                                    } else {
                                        $estado = 'Por Mejorar';
                                        $color = 'bg-red-100 text-red-800';
                                        $icono = 'fa-exclamation-triangle';
                                    }
                                ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 bg-gradient-to-br from-teal-50 to-teal-100 rounded-lg flex items-center justify-center">
                                                <i class="fas <?php echo $metrica['icon']; ?> text-teal-600"></i>
                                            </div>
                                            <div class="font-medium text-gray-900"><?php echo $metrica['nombre']; ?></div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-lg font-semibold text-gray-700"><?php echo $metrica['valor']; ?></div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-gray-600"><?php echo $metrica['objetivo']; ?></div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="status-badge <?php echo $color; ?>">
                                            <i class="fas <?php echo $icono; ?> text-xs mr-1"></i>
                                            <?php echo $estado; ?>
                                        </span>
                                        <div class="text-xs text-gray-500 mt-1">
                                            <?php echo $diferencia >= 0 ? '+' : ''; ?><?php echo number_format($diferencia, 1); ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- Recursos que necesitan capacitación -->
            <?php if (isset($necesitan_capacitacion) && count($necesitan_capacitacion) > 0): ?>
            <div class="mb-8 bg-gradient-to-r from-yellow-50 to-orange-50 rounded-2xl p-6 border-2 border-yellow-200">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-gradient-to-r from-yellow-400 to-orange-400 rounded-xl flex items-center justify-center">
                        <i class="fas fa-exclamation-triangle text-white"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">Recursos que Necesitan Capacitación Urgente</h3>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <?php foreach($necesitan_capacitacion as $recurso): ?>
                    <div class="bg-white p-4 rounded-xl border border-yellow-100 hover:shadow transition-all duration-200">
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-yellow-100 to-orange-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-yellow-600"></i>
                            </div>
                            <div class="flex-1">
                                <div class="font-medium text-gray-900"><?php echo htmlspecialchars($recurso['usuario_nombre'] ?? ''); ?></div>
                                <div class="text-sm text-gray-500 mb-2"><?php echo htmlspecialchars($recurso['proyecto_nombre'] ?? ''); ?></div>
                                <div class="text-xs text-yellow-700 bg-yellow-50 p-2 rounded-lg">
                                    <i class="fas fa-lightbulb mr-1"></i>
                                    <?php echo htmlspecialchars(substr($recurso['capacitacion_requerida'] ?? 'Sin especificar', 0, 80)); ?>...
                                </div>
                                <div class="mt-2 flex justify-between items-center">
                                    <span class="text-xs text-gray-500">
                                        <i class="fas fa-chart-line mr-1"></i>
                                        <?php echo $recurso['longitud_capacitacion'] ?? 0; ?> caracteres
                                    </span>
                                    <a href="?accion=crear_capacitacion&recurso_id=<?php echo $recurso['id']; ?>" 
                                    class="text-xs text-blue-600 hover:text-blue-800 font-medium">
                                        <i class="fas fa-plus mr-1"></i> Programar
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Recomendaciones -->
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Recomendaciones basadas en métricas</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-white p-5 rounded-xl border border-blue-200 hover:shadow transition-all duration-200">
                        <div class="flex items-start gap-3">
                            <div class="w-12 h-12 bg-gradient-to-r from-blue-100 to-blue-200 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-graduation-cap text-blue-600"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900 mb-2">Optimizar Capacitaciones</h4>
                                <p class="text-sm text-gray-600 mb-3">
                                    <?php if (($estadisticas['capacitaciones_atrasadas'] ?? 0) > 0): ?>
                                    Hay <span class="font-semibold text-red-600"><?php echo $estadisticas['capacitaciones_atrasadas']; ?> capacitaciones atrasadas</span>. 
                                    Prioriza su revisión y actualización.
                                    <?php else: ?>
                                    Todas las capacitaciones están al día. Considera nuevas capacitaciones para mantener actualizado al equipo.
                                    <?php endif; ?>
                                </p>
                                <div class="text-xs text-blue-600 font-medium">
                                    <i class="fas fa-chart-bar mr-1"></i>
                                    <?php echo $estadisticas['total_capacitaciones'] ?? 0; ?> capacitaciones totales
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white p-5 rounded-xl border border-green-200 hover:shadow transition-all duration-200">
                        <div class="flex items-start gap-3">
                            <div class="w-12 h-12 bg-gradient-to-r from-green-100 to-green-200 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-users text-green-600"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900 mb-2">Balance de Experiencia</h4>
                                <p class="text-sm text-gray-600 mb-3">
                                    El equipo tiene 
                                    <span class="font-semibold text-blue-600"><?php echo $estadisticas['distribucion_niveles']['junior'] ?? 0; ?> juniors</span>,
                                    <span class="font-semibold text-yellow-600"><?php echo $estadisticas['distribucion_niveles']['intermedio'] ?? 0; ?> intermedios</span>,
                                    <span class="font-semibold text-green-600"><?php echo $estadisticas['distribucion_niveles']['senior'] ?? 0; ?> seniors</span>, y
                                    <span class="font-semibold text-purple-600"><?php echo $estadisticas['distribucion_niveles']['experto'] ?? 0; ?> expertos</span>.
                                </p>
                                <div class="text-xs text-green-600 font-medium">
                                    <i class="fas fa-balance-scale mr-1"></i>
                                    <?php echo $estadisticas['total_recursos'] ?? 0; ?> recursos totales
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <?php else: ?>
            <!-- Si no hay estadísticas -->
            <div class="text-center py-16">
                <div class="w-24 h-24 mx-auto bg-gradient-to-br from-teal-50 to-teal-100 rounded-full flex items-center justify-center mb-6">
                    <i class="fas fa-chart-pie text-4xl text-gray-400"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-700 mb-3">No hay datos para mostrar</h3>
                <p class="text-gray-500 mb-6 max-w-md mx-auto">Comienza asignando recursos y creando capacitaciones para generar reportes</p>
                <div class="flex gap-4 justify-center">
                    <a href="?accion=asignar" 
                    class="gradient-bg-proceso4 hover:opacity-90 text-white px-6 py-3 rounded-xl font-medium inline-flex items-center gap-2 shadow-lg hover-lift">
                        <i class="fas fa-user-plus"></i>
                        Asignar recursos
                    </a>
                    <a href="?accion=crear_capacitacion" 
                    class="bg-gradient-to-r from-purple-500 to-indigo-500 hover:opacity-90 text-white px-6 py-3 rounded-xl font-medium inline-flex items-center gap-2 shadow-lg hover-lift">
                        <i class="fas fa-graduation-cap"></i>
                        Crear capacitación
                    </a>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <script>
            function exportarPDF() {
                // En una implementación real, aquí iría la lógica para exportar a PDF
                alert('La exportación a PDF está disponible en la versión completa del sistema.\nPara ahora, use la función de impresión (Ctrl+P).');
                
                // Para una implementación real, podrías usar:
                // window.location.href = 'exportar_pdf.php?accion=reportes&' + window.location.search.substring(1);
            }
            
            // Agregar tooltips a las métricas
            document.addEventListener('DOMContentLoaded', function() {
                // Agregar tooltips informativos
                const metricas = document.querySelectorAll('.hover\\:bg-gray-50');
                metricas.forEach(metrica => {
                    const valor = metrica.querySelector('.text-lg')?.textContent || '';
                    const objetivo = metrica.querySelector('.text-gray-600')?.textContent || '';
                    const diferencia = metrica.querySelector('.text-gray-500')?.textContent || '';
                    
                    if (valor && objetivo) {
                        metrica.title = `Valor: ${valor} | Objetivo: ${objetivo} | Diferencia: ${diferencia}`;
                    }
                });
            });
        </script>

        <?php endif; ?>
        <!-- ========== FIN SECCIÓN REPORTES ========== -->
        
        <!-- ========== INFORMACIÓN DEL PROCESO PMBOK ========== -->
        <div class="mt-8 glass-card rounded-2xl p-6">
            <div class="flex items-start gap-4">
                <div class="w-14 h-14 bg-gradient-to-r from-teal-100 to-green-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-info-circle text-teal-600 text-xl"></i>
                </div>
                <div class="flex-1">
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Proceso 4: Desarrollar el Equipo</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-gradient-to-r from-teal-50 to-teal-100 p-4 rounded-xl">
                            <h4 class="font-semibold text-teal-800 mb-2">Objetivo</h4>
                            <p class="text-sm text-teal-700">Mejorar las competencias, la interacción de los miembros del equipo y el entorno del equipo en general para mejorar el rendimiento del proyecto.</p>
                        </div>
                        <div class="bg-gradient-to-r from-purple-50 to-purple-100 p-4 rounded-xl">
                            <h4 class="font-semibold text-purple-800 mb-2">Herramientas</h4>
                            <ul class="text-sm text-purple-700 list-disc pl-5">
                                <li>Entrenamiento</li>
                                <li>Actividades de formación de equipos</li>
                                <li>Reconocimiento y recompensas</li>
                                <li>Evaluación de desempeño</li>
                            </ul>
                        </div>
                        <div class="bg-gradient-to-r from-emerald-50 to-green-100 p-4 rounded-xl">
                            <h4 class="font-semibold text-emerald-800 mb-2">Salidas</h4>
                            <ul class="text-sm text-emerald-700 list-disc pl-5">
                                <li>Evaluaciones de desempeño</li>
                                <li>Actualizaciones de activos</li>
                                <li>Solicitudes de cambio</li>
                                <li>Actualizaciones documentos</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </main>
    
    <script>
        // Scripts globales
        document.addEventListener('DOMContentLoaded', function() {
            // Efecto para radio cards
            document.querySelectorAll('.radio-card').forEach(card => {
                card.addEventListener('click', function() {
                    document.querySelectorAll('.radio-card').forEach(c => c.classList.remove('selected'));
                    this.classList.add('selected');
                    const radioInput = this.querySelector('input[type="radio"]');
                    if (radioInput) {
                        radioInput.checked = true;
                    }
                });
            });
            
            // Auto-ocultar mensajes después de 5 segundos
            const messages = document.querySelectorAll('[class*="bg-gradient-to-r"]');
            messages.forEach(message => {
                if (message.textContent.includes('éxito') || message.textContent.includes('error') || message.textContent.includes('Error')) {
                    setTimeout(() => {
                        message.style.opacity = '0';
                        message.style.transition = 'opacity 0.5s ease';
                        setTimeout(() => message.remove(), 500);
                    }, 5000);
                }
            });
            
            // Seleccionar automáticamente el radio button en radio cards seleccionadas
            document.querySelectorAll('.radio-card.selected').forEach(card => {
                const radioInput = card.querySelector('input[type="radio"]');
                if (radioInput) {
                    radioInput.checked = true;
                }
            });
        });
    </script>
</body>
</html>
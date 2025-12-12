<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dirigir Equipo | PMBOK 6</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }
        
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);
        }
        
        .gradient-bg-proceso5 {
            background: linear-gradient(135deg, #9f7aea 0%, #805ad5 100%);
        }
        
        .hover-lift {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        
        .hover-lift:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
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
        
        .badge-pendiente { background-color: #fed7d7; color: #9b2c2c; }
        .badge-en_progreso { background-color: #bee3f8; color: #2c5282; }
        .badge-revision { background-color: #fbd38d; color: #c05621; }
        .badge-completada { background-color: #c6f6d5; color: #276749; }
        .badge-atrasada { background-color: #feb2b2; color: #9b2c2c; }
        .badge-cancelada { background-color: #e2e8f0; color: #4a5568; }
        
        .badge-critica { background-color: #fed7d7; color: #9b2c2c; }
        .badge-alta { background-color: #fbd38d; color: #c05621; }
        .badge-media { background-color: #bee3f8; color: #2c5282; }
        .badge-baja { background-color: #c6f6d5; color: #276749; }
        
        .tab-dirigir {
            padding: 0.75rem 1.5rem;
            border-radius: 0.75rem;
            font-weight: 500;
            transition: all 0.2s ease;
        }
        
        .tab-dirigir.active {
            background: linear-gradient(135deg, #9f7aea 0%, #805ad5 100%);
            color: white;
            box-shadow: 0 4px 20px rgba(159, 122, 234, 0.3);
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
        
        .form-input {
            background: white;
            border: 1px solid #d1d5db;
            border-radius: 0.75rem;
            padding: 0.75rem 1rem;
            width: 100%;
            transition: all 0.2s;
        }
        
        .form-input:focus {
            outline: none;
            border-color: #9f7aea;
            box-shadow: 0 0 0 3px rgba(159, 122, 234, 0.1);
        }
        
        .table-row-hover:hover {
            background-color: rgba(159, 122, 234, 0.05);
        }
        
        .badge-email { background-color: #bee3f8; color: #2c5282; }
        .badge-reunion { background-color: #c6f6d5; color: #276749; }
        .badge-reporte { background-color: #fbd38d; color: #c05621; }
        .badge-notificacion { background-color: #e9d8fd; color: #553c9a; }
        .badge-mensaje { background-color: #fed7e2; color: #9b2c2c; }
        
        .badge-prioridad-alta { background-color: #fed7d7; color: #9b2c2c; }
        .badge-prioridad-normal { background-color: #bee3f8; color: #2c5282; }
        .badge-prioridad-baja { background-color: #c6f6d5; color: #276749; }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-purple-50">
    
    <!-- Sidebar -->
    <?php if (file_exists(__DIR__ . '/../../componentes/sidebar.php')): ?>
        <?php include __DIR__ . '/../../componentes/sidebar.php'; ?>
    <?php endif; ?>
    
    <main class="ml-0 md:ml-72 p-4 md:p-8">
        
        <!-- ========== MENSAJES GLOBALES ========== -->
        <?php if (isset($mensaje) && !empty($mensaje)): ?>
        <div class="mb-6 p-4 rounded-lg border <?php echo $tipo_mensaje === 'success' ? 'bg-gradient-to-r from-green-50 to-emerald-50 border-green-200 text-green-700' : 'bg-gradient-to-r from-red-50 to-rose-50 border-red-200 text-red-700'; ?> shadow-sm">
            <div class="flex items-center">
                <i class="fas <?php echo $tipo_mensaje === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'; ?> mr-3 text-lg"></i>
                <span class="font-medium"><?php echo htmlspecialchars($mensaje); ?></span>
                <button onclick="this.parentElement.parentElement.remove()" class="ml-auto text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- ========== ENCABEZADO PRINCIPAL ========== -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2">Dirigir Equipo</h1>
                <p class="text-gray-600">Proceso 5 PMBOK 6 | Dirección y gestión del equipo del proyecto</p>
            </div>
            <div class="flex items-center gap-3 bg-white rounded-xl px-4 py-3 shadow-sm border border-gray-200">
                <div class="w-10 h-10 bg-gradient-to-r from-purple-500 to-indigo-600 rounded-full flex items-center justify-center text-white font-bold">
                    <?php echo isset($_SESSION['usuario']) ? strtoupper(substr($_SESSION['usuario']['nombre'], 0, 1)) : 'D'; ?>
                </div>
                <div>
                    <div class="text-sm text-gray-500"><?php echo htmlspecialchars($usuario_rol ?? 'Usuario'); ?></div>
                    <div class="font-medium text-gray-800"><?php echo htmlspecialchars($usuario_nombre ?? 'Usuario'); ?></div>
                </div>
            </div>
        </div>
        
        <!-- ========== INDICADORES DE PROCESO PMBOK ========== -->
        <div class="grid grid-cols-2 md:grid-cols-6 gap-4 mb-8">
            <?php for($i = 1; $i <= 4; $i++): ?>
            <div class="bg-white p-4 rounded-xl border border-gray-200 opacity-70 hover-lift">
                <div class="text-sm text-gray-400">Proceso <?php echo $i; ?></div>
                <div class="text-lg font-semibold text-gray-400">
                    <?php 
                    $nombres = [1 => 'Planificar', 2 => 'Estimar', 3 => 'Adquirir', 4 => 'Desarrollar'];
                    echo $nombres[$i];
                    ?>
                </div>
                <div class="mt-2 text-xs text-green-600 flex items-center">
                    <i class="fas fa-check-circle mr-1"></i> Completado
                </div>
            </div>
            <?php endfor; ?>
            
            <div class="gradient-bg-proceso5 p-4 rounded-xl text-white hover-lift shadow-lg">
                <div class="text-sm font-medium">Proceso 5</div>
                <div class="text-lg font-semibold">Dirigir</div>
                <div class="mt-2 text-xs flex items-center">
                    <i class="fas fa-user-tie mr-1"></i> En Progreso
                </div>
            </div>
            
            <div class="bg-white p-4 rounded-xl border border-gray-200 opacity-70 hover-lift">
                <div class="text-sm text-gray-400">Proceso 6</div>
                <div class="text-lg font-semibold text-gray-400">Controlar</div>
                <div class="mt-2 text-xs text-gray-400">
                    <i class="fas fa-lock mr-1"></i> Bloqueado
                </div>
            </div>
        </div>
        
        <!-- ========== NAVEGACIÓN INTERNA DEL PROCESO 5 ========== -->
        <div class="flex flex-wrap gap-2 md:gap-3 mb-8 bg-white rounded-2xl p-2 shadow-sm border border-gray-200">
            <a href="?accion=dashboard" 
               class="tab-dirigir <?php echo ($accion === 'dashboard' || !isset($accion)) ? 'active' : ''; ?> hover-lift">
                <i class="fas fa-tachometer-alt mr-2"></i>
                <span class="hidden md:inline">Dashboard</span>
                <span class="md:hidden">Dash</span>
            </a>
            <a href="?accion=tareas" 
               class="tab-dirigir <?php echo $accion === 'tareas' ? 'active' : ''; ?> hover-lift">
                <i class="fas fa-tasks mr-2"></i>
                <span class="hidden md:inline">Gestión de Tareas</span>
                <span class="md:hidden">Tareas</span>
            </a>
            <a href="?accion=comunicaciones" 
               class="tab-dirigir <?php echo $accion === 'comunicaciones' ? 'active' : ''; ?> hover-lift">
                <i class="fas fa-comments mr-2"></i>
                <span class="hidden md:inline">Comunicaciones</span>
                <span class="md:hidden">Comms</span>
            </a>
            <a href="?accion=reportes" 
               class="tab-dirigir <?php echo $accion === 'reportes' ? 'active' : ''; ?> hover-lift">
                <i class="fas fa-chart-bar mr-2"></i>
                <span class="hidden md:inline">Reportes</span>
                <span class="md:hidden">Rep</span>
            </a>
            <?php if (($usuario_rol == 'gerente' || $usuario_rol == 'administrador') && ($accion == 'crear_tarea' || $accion == 'crear_comunicacion')): ?>
            <div class="ml-auto px-4 py-2 bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl">
                <span class="text-green-700 font-medium">
                    <i class="fas fa-plus-circle mr-2"></i>
                    <?php echo $accion == 'crear_tarea' ? 'Creando Tarea' : 'Creando Comunicación'; ?>
                </span>
            </div>
            <?php endif; ?>
        </div>
        
<!-- ========== SECCIÓN: DASHBOARD ========== -->
<?php if ($accion === 'dashboard' || !isset($accion)): ?>

<!-- DEBUG: Ver qué datos llegan -->
<?php 
// Debug: mostrar datos recibidos
echo "<!-- DEBUG: accion = " . ($accion ?? 'NULL') . " -->\n";
echo "<!-- DEBUG: dashboard variable exists? " . (isset($dashboard) ? 'YES' : 'NO') . " -->\n";

if (isset($dashboard)) {
    echo "<!-- DEBUG: dashboard data: " . htmlspecialchars(print_r($dashboard, true)) . " -->\n";
} else {
    echo "<!-- DEBUG: dashboard is NOT SET -->\n";
}
?>

<!-- Aquí va el contenido real del dashboard -->
<div class="dashboard">
    <h1>Dashboard</h1>
    <!-- ... resto del contenido ... -->
</div>

<?php endif; ?>
        
        <!-- ========== SECCIÓN: GESTIÓN DE TAREAS ========== -->
        <?php if ($accion === 'tareas'): ?>
        
        <div class="glass-card rounded-2xl p-6">
            <!-- Encabezado y botones -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Gestión de Tareas</h2>
                    <p class="text-gray-600">Asigna, gestiona y da seguimiento a las tareas del equipo</p>
                </div>
                <?php if ($usuario_rol != 'miembro_equipo'): ?>
                <div class="flex flex-wrap gap-2">
                    <a href="?accion=crear_tarea" 
                       class="gradient-bg-proceso5 hover:opacity-90 text-white px-5 py-2.5 rounded-xl font-medium flex items-center gap-2 hover-lift shadow-lg">
                        <i class="fas fa-plus"></i>
                        Nueva Tarea
                    </a>
                    <a href="?accion=reportes" 
                       class="bg-gradient-to-r from-blue-500 to-cyan-500 hover:opacity-90 text-white px-5 py-2.5 rounded-xl font-medium flex items-center gap-2 hover-lift shadow-lg">
                        <i class="fas fa-chart-bar"></i>
                        Reportes
                    </a>
                </div>
                <?php endif; ?>
            </div>
            
            <!-- Estadísticas rápidas -->
            <?php if (isset($estadisticas_tareas)): ?>
            <div class="grid grid-cols-2 md:grid-cols-5 gap-3 mb-6">
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 p-4 rounded-xl">
                    <div class="text-sm text-gray-500">Total</div>
                    <div class="text-xl font-bold text-gray-800"><?php echo $estadisticas_tareas['total_tareas'] ?? 0; ?></div>
                </div>
                <div class="bg-gradient-to-r from-yellow-50 to-amber-50 p-4 rounded-xl">
                    <div class="text-sm text-gray-500">Pendientes</div>
                    <div class="text-xl font-bold text-yellow-600"><?php echo $estadisticas_tareas['tareas_pendientes'] ?? 0; ?></div>
                </div>
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 p-4 rounded-xl">
                    <div class="text-sm text-gray-500">Completadas</div>
                    <div class="text-xl font-bold text-green-600"><?php echo $estadisticas_tareas['tareas_completadas'] ?? 0; ?></div>
                </div>
                <div class="bg-gradient-to-r from-red-50 to-rose-50 p-4 rounded-xl">
                    <div class="text-sm text-gray-500">Atrasadas</div>
                    <div class="text-xl font-bold text-red-600"><?php echo $estadisticas_tareas['tareas_vencidas'] ?? 0; ?></div>
                </div>
                <div class="bg-gradient-to-r from-purple-50 to-indigo-50 p-4 rounded-xl">
                    <div class="text-sm text-gray-500">Eficiencia</div>
                    <div class="text-xl font-bold text-purple-600"><?php echo number_format($estadisticas_tareas['eficiencia_horas'] ?? 0, 1); ?>%</div>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Filtros -->
            <div class="mb-6 bg-gradient-to-r from-purple-50 to-indigo-50 rounded-2xl p-4 md:p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Filtrar Tareas</h3>
                <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <input type="hidden" name="accion" value="tareas">
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Proyecto</label>
                        <select name="proyecto_id" class="form-input">
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
                        <select name="estado" class="form-input">
                            <option value="">Todos los estados</option>
                            <option value="pendiente" <?php echo (isset($_GET['estado']) && $_GET['estado'] == 'pendiente') ? 'selected' : ''; ?>>Pendiente</option>
                            <option value="en_progreso" <?php echo (isset($_GET['estado']) && $_GET['estado'] == 'en_progreso') ? 'selected' : ''; ?>>En Progreso</option>
                            <option value="revision" <?php echo (isset($_GET['estado']) && $_GET['estado'] == 'revision') ? 'selected' : ''; ?>>En Revisión</option>
                            <option value="completada" <?php echo (isset($_GET['estado']) && $_GET['estado'] == 'completada') ? 'selected' : ''; ?>>Completada</option>
                            <option value="atrasada" <?php echo (isset($_GET['estado']) && $_GET['estado'] == 'atrasada') ? 'selected' : ''; ?>>Atrasada</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Prioridad</label>
                        <select name="prioridad" class="form-input">
                            <option value="">Todas las prioridades</option>
                            <option value="critica" <?php echo (isset($_GET['prioridad']) && $_GET['prioridad'] == 'critica') ? 'selected' : ''; ?>>Crítica</option>
                            <option value="alta" <?php echo (isset($_GET['prioridad']) && $_GET['prioridad'] == 'alta') ? 'selected' : ''; ?>>Alta</option>
                            <option value="media" <?php echo (isset($_GET['prioridad']) && $_GET['prioridad'] == 'media') ? 'selected' : ''; ?>>Media</option>
                            <option value="baja" <?php echo (isset($_GET['prioridad']) && $_GET['prioridad'] == 'baja') ? 'selected' : ''; ?>>Baja</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Ordenar por</label>
                        <select name="orden" class="form-input" onchange="this.form.submit()">
                            <option value="fecha_asc" <?php echo (isset($_GET['orden']) && $_GET['orden'] == 'fecha_asc') ? 'selected' : ''; ?>>Fecha ↑</option>
                            <option value="fecha_desc" <?php echo (isset($_GET['orden']) && $_GET['orden'] == 'fecha_desc') ? 'selected' : ''; ?>>Fecha ↓</option>
                            <option value="prioridad_asc" <?php echo (isset($_GET['orden']) && $_GET['orden'] == 'prioridad_asc') ? 'selected' : ''; ?>>Prioridad ↑</option>
                            <option value="prioridad_desc" <?php echo (isset($_GET['orden']) && $_GET['orden'] == 'prioridad_desc') ? 'selected' : ''; ?>>Prioridad ↓</option>
                        </select>
                    </div>
                    
                    <div class="flex items-end">
                        <button type="submit" 
                                class="w-full gradient-bg-proceso5 hover:opacity-90 text-white px-4 py-2.5 rounded-xl font-medium flex items-center justify-center gap-2 hover-lift">
                            <i class="fas fa-filter"></i>
                            Aplicar Filtros
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Tabla de tareas -->
            <?php if (isset($tareas) && !empty($tareas)): ?>
            <div class="overflow-x-auto rounded-xl border border-gray-200 bg-white">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gradient-to-r from-purple-50 to-purple-100">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 uppercase">Tarea / Proyecto</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 uppercase">Asignado / Estado</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 uppercase">Fechas / Progreso</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 uppercase">Horas</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 uppercase">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        <?php foreach($tareas as $tarea): ?>
                        <tr class="table-row-hover">
                            <td class="px-6 py-4">
                                <div class="font-semibold text-gray-900 mb-1 truncate max-w-xs"><?php echo htmlspecialchars(substr($tarea['descripcion_tarea'] ?? 'Sin descripción', 0, 80)); ?></div>
                                <div class="text-sm text-gray-600 flex items-center">
                                    <i class="fas fa-project-diagram mr-1"></i>
                                    <?php echo htmlspecialchars($tarea['proyecto_nombre'] ?? 'Sin proyecto'); ?>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="space-y-2">
                                    <div>
                                        <div class="text-sm text-gray-500">Asignado a</div>
                                        <div class="font-medium text-gray-900 truncate max-w-xs"><?php echo htmlspecialchars($tarea['recurso_nombre'] ?? 'Sin asignar'); ?></div>
                                    </div>
                                    <?php 
                                    $estado = $tarea['estado_actual'] ?? 'pendiente';
                                    $estado_colores = [
                                        'pendiente' => 'badge-pendiente',
                                        'en_progreso' => 'badge-en_progreso',
                                        'revision' => 'badge-revision',
                                        'completada' => 'badge-completada',
                                        'atrasada' => 'badge-atrasada',
                                        'cancelada' => 'badge-cancelada'
                                    ];
                                    ?>
                                    <span class="status-badge <?php echo $estado_colores[$estado] ?? 'badge-pendiente'; ?>">
                                        <i class="fas fa-circle text-xs mr-1"></i>
                                        <?php echo ucfirst(str_replace('_', ' ', $estado)); ?>
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="space-y-2">
                                    <div>
                                        <div class="text-sm text-gray-500">Fecha Límite</div>
                                        <div class="font-medium <?php echo ($tarea['dias_restantes'] ?? 0) < 0 ? 'text-red-600' : 'text-gray-900'; ?>">
                                            <?php echo isset($tarea['fecha_limite']) ? date('d/m/Y', strtotime($tarea['fecha_limite'])) : 'N/A'; ?>
                                            <?php if (($tarea['dias_restantes'] ?? 0) < 0): ?>
                                            <span class="text-xs text-red-500 ml-1">(Vencida)</span>
                                            <?php elseif (($tarea['dias_restantes'] ?? 0) == 0): ?>
                                            <span class="text-xs text-orange-500 ml-1">(Hoy)</span>
                                            <?php elseif (($tarea['dias_restantes'] ?? 0) <= 3): ?>
                                            <span class="text-xs text-yellow-500 ml-1">(Próxima)</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="text-sm text-gray-500">Progreso</div>
                                        <div class="flex items-center gap-2">
                                            <div class="flex-1">
                                                <div class="progress-bar">
                                                    <div class="progress-fill bg-gradient-to-r from-green-400 to-emerald-500" 
                                                         style="width: <?php echo min(100, max(0, $tarea['porcentaje_completado'] ?? 0)); ?>%"></div>
                                                </div>
                                            </div>
                                            <span class="text-sm font-medium text-gray-700"><?php echo $tarea['porcentaje_completado'] ?? 0; ?>%</span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="space-y-2">
                                    <div>
                                        <div class="text-sm text-gray-500">Estimadas</div>
                                        <div class="font-medium text-gray-900"><?php echo $tarea['horas_estimadas'] ?? 0; ?>h</div>
                                    </div>
                                    <div>
                                        <div class="text-sm text-gray-500">Reales</div>
                                        <div class="font-medium <?php echo ($tarea['horas_reales'] ?? 0) > ($tarea['horas_estimadas'] ?? 0) ? 'text-red-600' : 'text-green-600'; ?>">
                                            <?php echo $tarea['horas_reales'] ?? 0; ?>h
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col gap-2">
                                    <a href="?accion=ver_tarea&id=<?php echo $tarea['id']; ?>" 
                                       class="px-3 py-1.5 bg-gradient-to-r from-blue-50 to-blue-100 hover:from-blue-100 hover:to-blue-200 text-blue-600 rounded-lg text-sm font-medium flex items-center justify-center gap-1 hover-lift">
                                        <i class="fas fa-eye text-xs"></i>
                                        Ver
                                    </a>
                                    <?php if ($usuario_rol != 'miembro_equipo' || ($tarea['usuario_id'] ?? 0) == $usuario_id): ?>
                                    <a href="?accion=editar_tarea&id=<?php echo $tarea['id']; ?>" 
                                       class="px-3 py-1.5 bg-gradient-to-r from-green-50 to-green-100 hover:from-green-100 hover:to-green-200 text-green-600 rounded-lg text-sm font-medium flex items-center justify-center gap-1 hover-lift">
                                        <i class="fas fa-edit text-xs"></i>
                                        Editar
                                    </a>
                                    <?php endif; ?>
                                    <?php if ($usuario_rol != 'miembro_equipo' && $tarea['estado'] != 'completada'): ?>
                                    <form method="POST" class="inline" onsubmit="return confirm('¿Marcar esta tarea como completada?')">
                                        <input type="hidden" name="accion" value="cambiar_estado_tarea">
                                        <input type="hidden" name="id" value="<?php echo $tarea['id']; ?>">
                                        <input type="hidden" name="estado" value="completada">
                                        <button type="submit" 
                                                class="w-full px-3 py-1.5 bg-gradient-to-r from-emerald-50 to-emerald-100 hover:from-emerald-100 hover:to-emerald-200 text-emerald-600 rounded-lg text-sm font-medium flex items-center justify-center gap-1 hover-lift">
                                            <i class="fas fa-check text-xs"></i>
                                            Completar
                                        </button>
                                    </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Paginación -->
            <div class="mt-6 flex justify-between items-center">
                <div class="text-sm text-gray-500">
                    Mostrando <?php echo count($tareas); ?> de <?php echo $estadisticas_tareas['total_tareas'] ?? count($tareas); ?> tareas
                </div>
                <div class="flex gap-2">
                    <button class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">1</button>
                    <button class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">2</button>
                    <button class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">3</button>
                    <button class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>
            <?php else: ?>
            <div class="text-center py-16">
                <div class="w-24 h-24 mx-auto bg-gradient-to-br from-purple-50 to-purple-100 rounded-full flex items-center justify-center mb-6">
                    <i class="fas fa-tasks text-4xl text-gray-400"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-700 mb-3">No hay tareas asignadas</h3>
                <p class="text-gray-500 mb-6 max-w-md mx-auto">
                    <?php if ($usuario_rol == 'miembro_equipo'): ?>
                    No tienes tareas asignadas en este momento.
                    <?php else: ?>
                    Comienza creando tareas para dirigir a tu equipo.
                    <?php endif; ?>
                </p>
                <?php if ($usuario_rol != 'miembro_equipo'): ?>
                <a href="?accion=crear_tarea" 
                   class="gradient-bg-proceso5 hover:opacity-90 text-white px-6 py-3 rounded-xl font-medium inline-flex items-center gap-2 shadow-lg hover-lift">
                    <i class="fas fa-plus"></i>
                    Crear primera tarea
                </a>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>
        
        <?php endif; ?>
        <!-- ========== FIN SECCIÓN TAREAS ========== -->
        
        <!-- ========== SECCIÓN: CREAR/EDITAR TAREA ========== -->
        <?php if ($accion === 'crear_tarea' || $accion === 'editar_tarea'): ?>
        
        <div class="glass-card rounded-2xl p-6">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">
                        <?php echo $accion === 'crear_tarea' ? 'Crear Nueva Tarea' : 'Editar Tarea'; ?>
                    </h2>
                    <p class="text-gray-600">Completa los detalles de la tarea para asignarla al equipo</p>
                </div>
                <a href="?accion=tareas" 
                   class="px-4 py-2.5 bg-gradient-to-r from-gray-100 to-gray-200 hover:from-gray-200 hover:to-gray-300 text-gray-700 rounded-xl font-medium flex items-center gap-2 hover-lift">
                    <i class="fas fa-arrow-left"></i>
                    Volver
                </a>
            </div>
            
            <!-- Mostrar errores de validación -->
            <?php if (isset($errores) && !empty($errores)): ?>
            <div class="mb-6 p-4 rounded-lg border-2 border-red-200 bg-gradient-to-r from-red-50 to-rose-50">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 bg-gradient-to-r from-red-400 to-red-500 rounded-xl flex items-center justify-center">
                        <i class="fas fa-exclamation-triangle text-white"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-red-900">Por favor corrige los siguientes errores:</h3>
                    </div>
                </div>
                <ul class="list-disc pl-5 text-red-700">
                    <?php foreach ($errores as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>
            
            <form method="POST" class="space-y-6">
                <input type="hidden" name="accion" value="<?php echo $accion === 'crear_tarea' ? 'crear_tarea' : 'actualizar_tarea'; ?>">
                <?php if (isset($tarea['id'])): ?>
                <input type="hidden" name="id" value="<?php echo $tarea['id']; ?>">
                <?php endif; ?>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Proyecto -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2 required">Proyecto</label>
                        <select name="proyecto_id" class="form-input" required>
                            <option value="">Seleccionar proyecto...</option>
                            <?php if (isset($proyectos)): ?>
                                <?php foreach ($proyectos as $proyecto): ?>
                                    <option value="<?php echo htmlspecialchars($proyecto['id'] ?? ''); ?>"
                                        <?php echo (isset($datos_form['proyecto_id']) && $datos_form['proyecto_id'] == $proyecto['id']) || (isset($tarea['proyecto_id']) && $tarea['proyecto_id'] == $proyecto['id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($proyecto['nombre'] ?? ''); ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    
                    <!-- Recurso asignado -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2 required">Asignar a</label>
                        <select name="recurso_humano_id" class="form-input" required>
                            <option value="">Seleccionar recurso...</option>
                            <?php if (isset($recursos)): ?>
                                <?php foreach ($recursos as $recurso): ?>
                                    <option value="<?php echo htmlspecialchars($recurso['id'] ?? ''); ?>"
                                        data-carga="<?php echo $recurso['tareas_actuales'] ?? 0; ?>"
                                        data-horas="<?php echo $recurso['horas_totales_asignadas'] ?? 0; ?>"
                                        <?php echo (isset($datos_form['recurso_humano_id']) && $datos_form['recurso_humano_id'] == $recurso['id']) || (isset($tarea['recurso_humano_id']) && $tarea['recurso_humano_id'] == $recurso['id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($recurso['nombre'] ?? ''); ?> 
                                        (<?php echo htmlspecialchars($recurso['rol_proyecto'] ?? ''); ?>)
                                        - <?php echo $recurso['tareas_actuales'] ?? 0; ?> tareas
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        <div class="mt-1 text-xs text-gray-500" id="info-recurso"></div>
                    </div>
                </div>
                
                <!-- Descripción de la tarea -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2 required">Descripción de la tarea</label>
                    <textarea name="descripcion_tarea" rows="4" class="form-input" required
                        placeholder="Describe la tarea con detalle, incluyendo objetivos específicos y resultados esperados..."><?php echo htmlspecialchars($datos_form['descripcion_tarea'] ?? $tarea['descripcion_tarea'] ?? ''); ?></textarea>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Fechas -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Fecha de Asignación</label>
                        <input type="date" name="fecha_asignacion" class="form-input"
                               value="<?php echo htmlspecialchars($datos_form['fecha_asignacion'] ?? $tarea['fecha_asignacion'] ?? date('Y-m-d')); ?>">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2 required">Fecha Límite</label>
                        <input type="date" name="fecha_limite" class="form-input" required
                               value="<?php echo htmlspecialchars($datos_form['fecha_limite'] ?? $tarea['fecha_limite'] ?? ''); ?>">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                        <select name="estado" class="form-input">
                            <?php if (isset($estados)): ?>
                                <?php foreach ($estados as $estado): ?>
                                    <option value="<?php echo htmlspecialchars($estado); ?>"
                                        <?php echo (isset($datos_form['estado']) && $datos_form['estado'] == $estado) || (isset($tarea['estado']) && $tarea['estado'] == $estado) ? 'selected' : ''; ?>>
                                        <?php echo ucfirst(str_replace('_', ' ', $estado)); ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <!-- Horas y porcentaje -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Horas Estimadas</label>
                        <input type="number" name="horas_estimadas" step="0.5" min="0" class="form-input"
                               value="<?php echo htmlspecialchars($datos_form['horas_estimadas'] ?? $tarea['horas_estimadas'] ?? '8'); ?>">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Horas Reales</label>
                        <input type="number" name="horas_reales" step="0.5" min="0" class="form-input"
                               value="<?php echo htmlspecialchars($datos_form['horas_reales'] ?? $tarea['horas_reales'] ?? '0'); ?>">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Porcentaje Completado</label>
                        <div class="flex items-center gap-2">
                            <input type="range" name="porcentaje_completado" min="0" max="100" step="5" class="flex-1"
                                   value="<?php echo htmlspecialchars($datos_form['porcentaje_completado'] ?? $tarea['porcentaje_completado'] ?? '0'); ?>"
                                   oninput="this.nextElementSibling.value = this.value + '%'">
                            <output class="w-16 text-center font-medium text-gray-700"><?php echo $datos_form['porcentaje_completado'] ?? $tarea['porcentaje_completado'] ?? '0'; ?>%</output>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2 required">Prioridad</label>
                        <select name="prioridad" class="form-input" required>
                            <?php if (isset($prioridades)): ?>
                                <?php foreach ($prioridades as $prioridad): ?>
                                    <option value="<?php echo htmlspecialchars($prioridad); ?>"
                                        <?php echo (isset($datos_form['prioridad']) && $datos_form['prioridad'] == $prioridad) || (isset($tarea['prioridad']) && $tarea['prioridad'] == $prioridad) ? 'selected' : ''; ?>>
                                        <?php echo ucfirst($prioridad); ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>
                
                <!-- Botones de acción -->
                <div class="flex flex-wrap gap-3 pt-6 border-t border-gray-200">
                    <?php if ($accion === 'crear_tarea'): ?>
                    <button type="submit" name="guardar_y_continuar" 
                            class="bg-gradient-to-r from-green-500 to-emerald-500 hover:opacity-90 text-white px-6 py-3 rounded-xl font-medium flex items-center gap-2 hover-lift shadow-lg">
                        <i class="fas fa-save"></i>
                        Guardar y crear otra
                    </button>
                    <?php endif; ?>
                    
                    <button type="submit" 
                            class="gradient-bg-proceso5 hover:opacity-90 text-white px-6 py-3 rounded-xl font-medium flex items-center gap-2 hover-lift shadow-lg">
                        <i class="fas <?php echo $accion === 'crear_tarea' ? 'fa-plus' : 'fa-save'; ?>"></i>
                        <?php echo $accion === 'crear_tarea' ? 'Crear Tarea' : 'Guardar Cambios'; ?>
                    </button>
                    
                    <a href="?accion=tareas" 
                       class="px-6 py-3 bg-gradient-to-r from-gray-100 to-gray-200 hover:from-gray-200 hover:to-gray-300 text-gray-700 rounded-xl font-medium flex items-center gap-2 hover-lift">
                        <i class="fas fa-times"></i>
                        Cancelar
                    </a>
                    
                    <?php if (isset($tarea['id'])): ?>
                    <button type="button" onclick="confirmarEliminar(<?php echo $tarea['id']; ?>)" 
                            class="ml-auto px-6 py-3 bg-gradient-to-r from-red-500 to-rose-500 hover:opacity-90 text-white rounded-xl font-medium flex items-center gap-2 hover-lift shadow-lg">
                        <i class="fas fa-trash"></i>
                        Eliminar Tarea
                    </button>
                    <?php endif; ?>
                </div>
            </form>
            
            <?php if (isset($tarea['id'])): ?>
            <!-- Formulario oculto para eliminar -->
            <form method="POST" id="form-eliminar-<?php echo $tarea['id']; ?>" class="hidden">
                <input type="hidden" name="accion" value="eliminar_tarea">
                <input type="hidden" name="id" value="<?php echo $tarea['id']; ?>">
            </form>
            <?php endif; ?>
        </div>
        
        <script>
        // Información de carga del recurso seleccionado
        document.addEventListener('DOMContentLoaded', function() {
            const selectRecurso = document.querySelector('select[name="recurso_humano_id"]');
            const infoRecurso = document.getElementById('info-recurso');
            
            function actualizarInfoRecurso() {
                const opcionSeleccionada = selectRecurso.options[selectRecurso.selectedIndex];
                if (opcionSeleccionada.value) {
                    const tareas = opcionSeleccionada.getAttribute('data-carga');
                    const horas = opcionSeleccionada.getAttribute('data-horas');
                    infoRecurso.innerHTML = `Carga actual: ${tareas} tareas, ${horas} horas asignadas`;
                } else {
                    infoRecurso.innerHTML = '';
                }
            }
            
            if (selectRecurso) {
                selectRecurso.addEventListener('change', actualizarInfoRecurso);
                actualizarInfoRecurso();
            }
        });
        
        function confirmarEliminar(id) {
            if (confirm('¿Estás seguro de eliminar esta tarea? Esta acción no se puede deshacer.')) {
                document.getElementById('form-eliminar-' + id).submit();
            }
        }
        </script>
        
        <?php endif; ?>
        <!-- ========== FIN SECCIÓN CREAR/EDITAR TAREA ========== -->
        
        <!-- ========== SECCIÓN: VER TAREA ========== -->
        <?php if ($accion === 'ver_tarea' && isset($tarea)): ?>
        
        <div class="glass-card rounded-2xl p-6">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Detalles de la Tarea</h2>
                    <div class="text-gray-600">ID: #<?php echo htmlspecialchars($tarea['id'] ?? ''); ?></div>
                </div>
                <div class="flex flex-wrap gap-2">
                    <a href="?accion=tareas" 
                       class="px-4 py-2.5 bg-gradient-to-r from-gray-100 to-gray-200 hover:from-gray-200 hover:to-gray-300 text-gray-700 rounded-xl font-medium flex items-center gap-2 hover-lift">
                        <i class="fas fa-arrow-left"></i>
                        Volver
                    </a>
                    <?php if ($usuario_rol != 'miembro_equipo' || $tarea['usuario_id'] == $usuario_id): ?>
                    <a href="?accion=editar_tarea&id=<?php echo $tarea['id']; ?>" 
                       class="gradient-bg-proceso5 hover:opacity-90 text-white px-5 py-2.5 rounded-xl font-medium flex items-center gap-2 hover-lift shadow-lg">
                        <i class="fas fa-edit"></i>
                        Editar Tarea
                    </a>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Información principal -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Columna izquierda: Detalles -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Descripción -->
                    <div class="bg-gradient-to-r from-gray-50 to-gray-100 p-6 rounded-2xl">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Descripción</h3>
                        <div class="prose max-w-none">
                            <p class="text-gray-700 whitespace-pre-wrap"><?php echo nl2br(htmlspecialchars($tarea['descripcion_tarea'] ?? 'Sin descripción')); ?></p>
                        </div>
                    </div>
                    
                    <!-- Progreso y horas -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-gradient-to-r from-blue-50 to-blue-100 p-6 rounded-2xl">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Progreso</h3>
                            <div class="space-y-4">
                                <div>
                                    <div class="flex justify-between text-sm text-gray-600 mb-1">
                                        <span>Porcentaje completado</span>
                                        <span class="font-medium"><?php echo $tarea['porcentaje_completado'] ?? 0; ?>%</span>
                                    </div>
                                    <div class="progress-bar">
                                        <div class="progress-fill bg-gradient-to-r from-green-400 to-emerald-500" 
                                             style="width: <?php echo min(100, max(0, $tarea['porcentaje_completado'] ?? 0)); ?>%"></div>
                                    </div>
                                </div>
                                <?php if ($usuario_rol != 'miembro_equipo' || $tarea['usuario_id'] == $usuario_id): ?>
                                <form method="POST" class="space-y-3">
                                    <input type="hidden" name="accion" value="actualizar_porcentaje">
                                    <input type="hidden" name="id" value="<?php echo $tarea['id']; ?>">
                                    <div class="flex items-center gap-3">
                                        <input type="range" name="porcentaje" min="0" max="100" step="5" 
                                               value="<?php echo $tarea['porcentaje_completado'] ?? 0; ?>"
                                               class="flex-1"
                                               oninput="this.nextElementSibling.value = this.value + '%'">
                                        <output class="w-16 text-center font-medium text-gray-700"><?php echo $tarea['porcentaje_completado'] ?? 0; ?>%</output>
                                    </div>
                                    <button type="submit" 
                                            class="w-full bg-gradient-to-r from-green-500 to-emerald-500 hover:opacity-90 text-white px-4 py-2 rounded-lg font-medium text-sm">
                                        Actualizar Progreso
                                    </button>
                                </form>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="bg-gradient-to-r from-purple-50 to-purple-100 p-6 rounded-2xl">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Gestión de Tiempo</h3>
                            <div class="space-y-4">
                                <div>
                                    <div class="text-sm text-gray-600 mb-1">Horas Estimadas</div>
                                    <div class="text-2xl font-bold text-gray-900"><?php echo $tarea['horas_estimadas'] ?? 0; ?>h</div>
                                </div>
                                <div>
                                    <div class="text-sm text-gray-600 mb-1">Horas Reales</div>
                                    <div class="text-2xl font-bold <?php echo ($tarea['horas_reales'] ?? 0) > ($tarea['horas_estimadas'] ?? 0) ? 'text-red-600' : 'text-green-600'; ?>">
                                        <?php echo $tarea['horas_reales'] ?? 0; ?>h
                                    </div>
                                </div>
                                <?php if ($usuario_rol != 'miembro_equipo'): ?>
                                <form method="POST" class="space-y-3">
                                    <input type="hidden" name="accion" value="actualizar_horas_tarea">
                                    <input type="hidden" name="id" value="<?php echo $tarea['id']; ?>">
                                    <div class="flex items-center gap-3">
                                        <input type="number" name="horas_reales" step="0.5" min="0" 
                                               value="<?php echo $tarea['horas_reales'] ?? 0; ?>"
                                               class="form-input flex-1" placeholder="Nuevas horas">
                                        <span class="text-gray-600">h</span>
                                    </div>
                                    <button type="submit" 
                                            class="w-full bg-gradient-to-r from-blue-500 to-cyan-500 hover:opacity-90 text-white px-4 py-2 rounded-lg font-medium text-sm">
                                        Actualizar Horas
                                    </button>
                                </form>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Columna derecha: Metadatos -->
                <div class="space-y-6">
                    <!-- Estado y prioridad -->
                    <div class="bg-gradient-to-r from-indigo-50 to-indigo-100 p-6 rounded-2xl">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Estado y Prioridad</h3>
                        <div class="space-y-4">
                            <div>
                                <div class="text-sm text-gray-600 mb-2">Estado Actual</div>
                                <?php 
                                $estado = $tarea['estado_actual'] ?? 'pendiente';
                                $estado_colores = [
                                    'pendiente' => 'badge-pendiente',
                                    'en_progreso' => 'badge-en_progreso',
                                    'revision' => 'badge-revision',
                                    'completada' => 'badge-completada',
                                    'atrasada' => 'badge-atrasada'
                                ];
                                ?>
                                <span class="status-badge <?php echo $estado_colores[$estado] ?? 'badge-pendiente'; ?> text-base">
                                    <i class="fas fa-circle text-xs mr-2"></i>
                                    <?php echo ucfirst(str_replace('_', ' ', $estado)); ?>
                                </span>
                            </div>
                            
                            <div>
                                <div class="text-sm text-gray-600 mb-2">Prioridad</div>
                                <?php 
                                $prioridad = $tarea['prioridad'] ?? 'media';
                                $prioridad_colores = [
                                    'critica' => 'badge-critica',
                                    'alta' => 'badge-alta', 
                                    'media' => 'badge-media',
                                    'baja' => 'badge-baja'
                                ];
                                ?>
                                <span class="status-badge <?php echo $prioridad_colores[$prioridad] ?? 'badge-media'; ?> text-base">
                                    <i class="fas fa-flag text-xs mr-2"></i>
                                    <?php echo ucfirst($prioridad); ?>
                                </span>
                            </div>
                            
                            <?php if ($usuario_rol != 'miembro_equipo'): ?>
                            <form method="POST" class="pt-4">
                                <input type="hidden" name="accion" value="cambiar_estado_tarea">
                                <input type="hidden" name="id" value="<?php echo $tarea['id']; ?>">
                                <div class="text-sm text-gray-600 mb-2">Cambiar Estado</div>
                                <div class="flex gap-2">
                                    <select name="estado" class="form-input flex-1 text-sm">
                                        <option value="pendiente" <?php echo $tarea['estado'] == 'pendiente' ? 'selected' : ''; ?>>Pendiente</option>
                                        <option value="en_progreso" <?php echo $tarea['estado'] == 'en_progreso' ? 'selected' : ''; ?>>En Progreso</option>
                                        <option value="revision" <?php echo $tarea['estado'] == 'revision' ? 'selected' : ''; ?>>En Revisión</option>
                                        <option value="completada" <?php echo $tarea['estado'] == 'completada' ? 'selected' : ''; ?>>Completada</option>
                                    </select>
                                    <button type="submit" class="px-3 py-2 bg-gradient-to-r from-purple-500 to-indigo-500 hover:opacity-90 text-white rounded-lg text-sm">
                                        <i class="fas fa-sync"></i>
                                    </button>
                                </div>
                            </form>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Información del proyecto -->
                    <div class="bg-gradient-to-r from-blue-50 to-cyan-100 p-6 rounded-2xl">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Proyecto</h3>
                        <div class="space-y-3">
                            <div>
                                <div class="text-sm text-gray-600">Nombre del Proyecto</div>
                                <div class="font-medium text-gray-900"><?php echo htmlspecialchars($tarea['proyecto_nombre'] ?? 'Sin proyecto'); ?></div>
                            </div>
                            <div>
                                <div class="text-sm text-gray-600">Descripción</div>
                                <div class="text-gray-700"><?php echo htmlspecialchars(substr($tarea['proyecto_descripcion'] ?? 'Sin descripción', 0, 100)); ?>...</div>
                            </div>
                            <div>
                                <div class="text-sm text-gray-600">Estado del Proyecto</div>
                                <div class="font-medium <?php echo $tarea['proyecto_estado'] == 'en_ejecucion' ? 'text-green-600' : 'text-gray-600'; ?>">
                                    <?php echo ucfirst(str_replace('_', ' ', $tarea['proyecto_estado'] ?? 'desconocido')); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Información del recurso asignado -->
                    <div class="bg-gradient-to-r from-green-50 to-emerald-100 p-6 rounded-2xl">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Recurso Asignado</h3>
                        <div class="space-y-3">
                            <div>
                                <div class="text-sm text-gray-600">Nombre</div>
                                <div class="font-medium text-gray-900"><?php echo htmlspecialchars($tarea['recurso_nombre'] ?? 'Sin asignar'); ?></div>
                            </div>
                            <div>
                                <div class="text-sm text-gray-600">Email</div>
                                <div class="text-gray-700 truncate"><?php echo htmlspecialchars($tarea['recurso_email'] ?? 'Sin email'); ?></div>
                            </div>
                            <div>
                                <div class="text-sm text-gray-600">Rol en el Proyecto</div>
                                <div class="font-medium text-gray-900"><?php echo htmlspecialchars($tarea['rol_proyecto'] ?? 'Sin rol'); ?></div>
                            </div>
                            <?php if (!empty($tarea['habilidades'])): ?>
                            <div>
                                <div class="text-sm text-gray-600">Habilidades</div>
                                <div class="text-gray-700"><?php echo htmlspecialchars($tarea['habilidades']); ?></div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Fechas -->
                    <div class="bg-gradient-to-r from-orange-50 to-amber-100 p-6 rounded-2xl">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Fechas</h3>
                        <div class="space-y-3">
                            <div>
                                <div class="text-sm text-gray-600">Fecha de Asignación</div>
                                <div class="font-medium text-gray-900"><?php echo isset($tarea['fecha_asignacion']) ? date('d/m/Y', strtotime($tarea['fecha_asignacion'])) : 'N/A'; ?></div>
                            </div>
                            <div>
                                <div class="text-sm text-gray-600">Fecha Límite</div>
                                <div class="font-medium <?php echo (strtotime($tarea['fecha_limite'] ?? '') < time() && $tarea['estado'] != 'completada') ? 'text-red-600' : 'text-gray-900'; ?>">
                                    <?php echo isset($tarea['fecha_limite']) ? date('d/m/Y', strtotime($tarea['fecha_limite'])) : 'N/A'; ?>
                                </div>
                            </div>
                            <div>
                                <div class="text-sm text-gray-600">Días restantes</div>
                                <div class="font-medium <?php echo ($tarea['dias_restantes'] ?? 0) < 0 ? 'text-red-600' : (($tarea['dias_restantes'] ?? 0) <= 3 ? 'text-yellow-600' : 'text-green-600'); ?>">
                                    <?php 
                                    $dias = $tarea['dias_restantes'] ?? 0;
                                    if ($dias < 0) {
                                        echo abs($dias) . ' días de retraso';
                                    } elseif ($dias == 0) {
                                        echo 'Vence hoy';
                                    } else {
                                        echo $dias . ' días';
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <?php endif; ?>
        <!-- ========== FIN SECCIÓN VER TAREA ========== -->
        
        <!-- ========== SECCIÓN: COMUNICACIONES ========== -->
        <?php if ($accion === 'comunicaciones'): ?>
        
        <div class="glass-card rounded-2xl p-6">
            <!-- Encabezado -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Gestión de Comunicaciones</h2>
                    <p class="text-gray-600">Gestiona mensajes, reuniones, reportes y notificaciones del equipo</p>
                </div>
                <div class="flex flex-wrap gap-2">
                    <a href="?accion=crear_comunicacion" 
                       class="gradient-bg-proceso5 hover:opacity-90 text-white px-5 py-2.5 rounded-xl font-medium flex items-center gap-2 hover-lift shadow-lg">
                        <i class="fas fa-plus"></i>
                        Nueva Comunicación
                    </a>
                    <a href="?accion=comunicaciones&leido=0" 
                       class="bg-gradient-to-r from-yellow-500 to-orange-500 hover:opacity-90 text-white px-5 py-2.5 rounded-xl font-medium flex items-center gap-2 hover-lift shadow-lg">
                        <i class="fas fa-envelope"></i>
                        No leídas
                    </a>
                </div>
            </div>
            
            <!-- Contador de mensajes no leídos -->
            <?php if (isset($comunicaciones_no_leidas) && count($comunicaciones_no_leidas) > 0): ?>
            <div class="mb-6 p-4 rounded-lg border-2 border-yellow-200 bg-gradient-to-r from-yellow-50 to-orange-50 hover-lift">
                <div class="flex flex-col md:flex-row items-center gap-4">
                    <div class="w-12 h-12 bg-gradient-to-r from-yellow-400 to-orange-400 rounded-xl flex items-center justify-center">
                        <i class="fas fa-envelope text-white text-xl"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-semibold text-gray-900">Tienes <?php echo count($comunicaciones_no_leidas); ?> mensajes no leídos</h3>
                        <p class="text-sm text-gray-600">Revisa tus comunicaciones pendientes para mantenerte actualizado</p>
                    </div>
                    <div class="flex gap-2">
                        <form method="POST" class="inline" onsubmit="return confirm('¿Marcar todos como leídos?')">
                            <input type="hidden" name="accion" value="marcar_todo_leido">
                            <button type="submit" 
                                    class="px-4 py-2 bg-gradient-to-r from-yellow-500 to-orange-500 hover:opacity-90 text-white rounded-lg font-medium">
                                Marcar todo leído
                            </button>
                        </form>
                        <a href="?accion=comunicaciones&leido=0" 
                           class="px-4 py-2 bg-gradient-to-r from-gray-800 to-gray-900 hover:opacity-90 text-white rounded-lg font-medium">
                            Ver no leídos
                        </a>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Filtros -->
            <div class="mb-6 bg-gradient-to-r from-purple-50 to-indigo-50 rounded-2xl p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Filtrar Comunicaciones</h3>
                <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <input type="hidden" name="accion" value="comunicaciones">
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Proyecto</label>
                        <select name="proyecto_id" class="form-input">
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
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tipo</label>
                        <select name="tipo" class="form-input">
                            <option value="">Todos los tipos</option>
                            <option value="email" <?php echo (isset($_GET['tipo']) && $_GET['tipo'] == 'email') ? 'selected' : ''; ?>>Email</option>
                            <option value="reunion" <?php echo (isset($_GET['tipo']) && $_GET['tipo'] == 'reunion') ? 'selected' : ''; ?>>Reunión</option>
                            <option value="reporte" <?php echo (isset($_GET['tipo']) && $_GET['tipo'] == 'reporte') ? 'selected' : ''; ?>>Reporte</option>
                            <option value="notificacion" <?php echo (isset($_GET['tipo']) && $_GET['tipo'] == 'notificacion') ? 'selected' : ''; ?>>Notificación</option>
                            <option value="mensaje" <?php echo (isset($_GET['tipo']) && $_GET['tipo'] == 'mensaje') ? 'selected' : ''; ?>>Mensaje</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Prioridad</label>
                        <select name="prioridad" class="form-input">
                            <option value="">Todas las prioridades</option>
                            <option value="alta" <?php echo (isset($_GET['prioridad']) && $_GET['prioridad'] == 'alta') ? 'selected' : ''; ?>>Alta</option>
                            <option value="normal" <?php echo (isset($_GET['prioridad']) && $_GET['prioridad'] == 'normal') ? 'selected' : ''; ?>>Normal</option>
                            <option value="baja" <?php echo (isset($_GET['prioridad']) && $_GET['prioridad'] == 'baja') ? 'selected' : ''; ?>>Baja</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                        <select name="leido" class="form-input">
                            <option value="">Todos</option>
                            <option value="0" <?php echo (isset($_GET['leido']) && $_GET['leido'] == '0') ? 'selected' : ''; ?>>No leídos</option>
                            <option value="1" <?php echo (isset($_GET['leido']) && $_GET['leido'] == '1') ? 'selected' : ''; ?>>Leídos</option>
                        </select>
                    </div>
                    
                    <div class="flex items-end">
                        <button type="submit" 
                                class="w-full gradient-bg-proceso5 hover:opacity-90 text-white px-4 py-2.5 rounded-xl font-medium flex items-center justify-center gap-2 hover-lift">
                            <i class="fas fa-filter"></i>
                            Aplicar Filtros
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Lista de comunicaciones -->
            <?php if (isset($comunicaciones) && !empty($comunicaciones)): ?>
            <div class="space-y-4">
                <?php foreach($comunicaciones as $com): ?>
                <div class="bg-white rounded-xl border border-gray-200 hover-lift overflow-hidden <?php echo !$com['leido'] ? 'border-l-4 border-l-yellow-400' : ''; ?>">
                    <div class="p-6">
                        <div class="flex flex-col md:flex-row md:items-start justify-between gap-4 mb-4">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <?php 
                                    $tipo_colores = [
                                        'email' => 'badge-email',
                                        'reunion' => 'badge-reunion',
                                        'reporte' => 'badge-reporte',
                                        'notificacion' => 'badge-notificacion',
                                        'mensaje' => 'badge-mensaje'
                                    ];
                                    $prioridad_colores = [
                                        'alta' => 'badge-prioridad-alta',
                                        'normal' => 'badge-prioridad-normal',
                                        'baja' => 'badge-prioridad-baja'
                                    ];
                                    ?>
                                    <span class="status-badge <?php echo $tipo_colores[$com['tipo']] ?? 'badge-email'; ?>">
                                        <?php echo ucfirst($com['tipo'] ?? 'email'); ?>
                                    </span>
                                    <span class="status-badge <?php echo $prioridad_colores[$com['prioridad']] ?? 'badge-prioridad-normal'; ?>">
                                        <?php echo ucfirst($com['prioridad'] ?? 'normal'); ?>
                                    </span>
                                    <?php if (!$com['leido']): ?>
                                    <span class="status-badge bg-gradient-to-r from-yellow-100 to-orange-100 text-yellow-800">
                                        <i class="fas fa-envelope text-xs mr-1"></i> No leído
                                    </span>
                                    <?php endif; ?>
                                </div>
                                
                                <h3 class="text-xl font-semibold text-gray-900 mb-2"><?php echo htmlspecialchars($com['asunto'] ?? 'Sin asunto'); ?></h3>
                                
                                <div class="text-gray-600 mb-3 line-clamp-2"><?php echo htmlspecialchars(substr($com['mensaje'] ?? '', 0, 200)); ?>...</div>
                                
                                <div class="flex flex-wrap items-center gap-4 text-sm text-gray-500">
                                    <div class="flex items-center">
                                        <i class="fas fa-user-circle mr-2"></i>
                                        <span class="font-medium text-gray-700"><?php echo htmlspecialchars($com['emisor_nombre'] ?? 'Desconocido'); ?></span>
                                    </div>
                                    <?php if ($com['receptor_nombre']): ?>
                                    <div class="flex items-center">
                                        <i class="fas fa-arrow-right mr-2"></i>
                                        <span class="font-medium text-gray-700"><?php echo htmlspecialchars($com['receptor_nombre']); ?></span>
                                    </div>
                                    <?php else: ?>
                                    <div class="flex items-center">
                                        <i class="fas fa-users mr-2"></i>
                                        <span class="font-medium text-gray-700">Todos los miembros</span>
                                    </div>
                                    <?php endif; ?>
                                    <div class="flex items-center">
                                        <i class="fas fa-calendar-alt mr-2"></i>
                                        <span><?php echo htmlspecialchars($com['fecha_envio_formatted'] ?? date('d/m/Y H:i', strtotime($com['fecha_envio']))); ?></span>
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-project-diagram mr-2"></i>
                                        <span><?php echo htmlspecialchars($com['proyecto_nombre'] ?? 'Sin proyecto'); ?></span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex gap-2">
                                <a href="?accion=ver_comunicacion&id=<?php echo $com['id']; ?>" 
                                   class="px-4 py-2 bg-gradient-to-r from-blue-50 to-blue-100 hover:from-blue-100 hover:to-blue-200 text-blue-600 rounded-lg font-medium flex items-center gap-2">
                                    <i class="fas fa-eye"></i>
                                    Ver
                                </a>
                                <?php if (($usuario_rol == 'administrador') || ($com['emisor_id'] == $usuario_id)): ?>
                                <a href="?accion=editar_comunicacion&id=<?php echo $com['id']; ?>" 
                                   class="px-4 py-2 bg-gradient-to-r from-green-50 to-green-100 hover:from-green-100 hover:to-green-200 text-green-600 rounded-lg font-medium flex items-center gap-2">
                                    <i class="fas fa-edit"></i>
                                    Editar
                                </a>
                                <?php endif; ?>
                                <?php if (!$com['leido'] && (!$com['receptor_id'] || $com['receptor_id'] == $usuario_id)): ?>
                                <form method="POST" class="inline">
                                    <input type="hidden" name="accion" value="marcar_como_leida">
                                    <input type="hidden" name="id" value="<?php echo $com['id']; ?>">
                                    <button type="submit" 
                                            class="px-4 py-2 bg-gradient-to-r from-yellow-50 to-orange-100 hover:from-yellow-100 hover:to-orange-200 text-yellow-700 rounded-lg font-medium flex items-center gap-2">
                                        <i class="fas fa-envelope-open"></i>
                                        Marcar leído
                                    </button>
                                </form>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            
            <!-- Paginación -->
            <div class="mt-6 flex justify-between items-center">
                <div class="text-sm text-gray-500">
                    Mostrando <?php echo count($comunicaciones); ?> comunicaciones
                </div>
                <div class="flex gap-2">
                    <button class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">1</button>
                    <button class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">2</button>
                    <button class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">3</button>
                    <button class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>
            <?php else: ?>
            <div class="text-center py-16">
                <div class="w-24 h-24 mx-auto bg-gradient-to-br from-purple-50 to-purple-100 rounded-full flex items-center justify-center mb-6">
                    <i class="fas fa-comments text-4xl text-gray-400"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-700 mb-3">No hay comunicaciones</h3>
                <p class="text-gray-500 mb-6 max-w-md mx-auto">Comienza enviando una comunicación al equipo</p>
                <a href="?accion=crear_comunicacion" 
                   class="gradient-bg-proceso5 hover:opacity-90 text-white px-6 py-3 rounded-xl font-medium inline-flex items-center gap-2 shadow-lg hover-lift">
                    <i class="fas fa-plus"></i>
                    Crear primera comunicación
                </a>
            </div>
            <?php endif; ?>
        </div>
        
        <?php endif; ?>
        <!-- ========== FIN SECCIÓN COMUNICACIONES ========== -->
        
        <!-- ========== SECCIÓN: CREAR/EDITAR COMUNICACIÓN ========== -->
        <?php if ($accion === 'crear_comunicacion' || $accion === 'editar_comunicacion'): ?>
        
        <div class="glass-card rounded-2xl p-6">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">
                        <?php echo $accion === 'crear_comunicacion' ? 'Crear Nueva Comunicación' : 'Editar Comunicación'; ?>
                    </h2>
                    <p class="text-gray-600">Envía mensajes, notificaciones o convoca reuniones al equipo</p>
                </div>
                <a href="?accion=comunicaciones" 
                   class="px-4 py-2.5 bg-gradient-to-r from-gray-100 to-gray-200 hover:from-gray-200 hover:to-gray-300 text-gray-700 rounded-xl font-medium flex items-center gap-2 hover-lift">
                    <i class="fas fa-arrow-left"></i>
                    Volver
                </a>
            </div>
            
            <!-- Mostrar errores de validación -->
            <?php if (isset($errores) && !empty($errores)): ?>
            <div class="mb-6 p-4 rounded-lg border-2 border-red-200 bg-gradient-to-r from-red-50 to-rose-50">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 bg-gradient-to-r from-red-400 to-red-500 rounded-xl flex items-center justify-center">
                        <i class="fas fa-exclamation-triangle text-white"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-red-900">Por favor corrige los siguientes errores:</h3>
                    </div>
                </div>
                <ul class="list-disc pl-5 text-red-700">
                    <?php foreach ($errores as $campo => $mensaje): ?>
                    <li><span class="font-medium"><?php echo htmlspecialchars(ucfirst(str_replace('_', ' ', $campo))); ?>:</span> <?php echo htmlspecialchars($mensaje); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>
            
            <form method="POST" class="space-y-6">
                <input type="hidden" name="accion" value="<?php echo $accion === 'crear_comunicacion' ? 'crear_comunicacion' : 'actualizar_comunicacion'; ?>">
                <?php if (isset($comunicacion['id'])): ?>
                <input type="hidden" name="id" value="<?php echo $comunicacion['id']; ?>">
                <?php endif; ?>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Proyecto -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2 required">Proyecto</label>
                        <select name="proyecto_id" class="form-input" required>
                            <option value="">Seleccionar proyecto...</option>
                            <?php if (isset($proyectos)): ?>
                                <?php foreach ($proyectos as $proyecto): ?>
                                    <option value="<?php echo htmlspecialchars($proyecto['id'] ?? ''); ?>"
                                        <?php echo (isset($datos_form['proyecto_id']) && $datos_form['proyecto_id'] == $proyecto['id']) || (isset($comunicacion['proyecto_id']) && $comunicacion['proyecto_id'] == $proyecto['id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($proyecto['nombre'] ?? ''); ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    
                    <!-- Tipo -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2 required">Tipo</label>
                        <select name="tipo" class="form-input" required>
                            <option value="">Seleccionar tipo...</option>
                            <?php if (isset($tipos)): ?>
                                <?php foreach ($tipos as $tipo): ?>
                                    <option value="<?php echo htmlspecialchars($tipo); ?>"
                                        <?php echo (isset($datos_form['tipo']) && $datos_form['tipo'] == $tipo) || (isset($comunicacion['tipo']) && $comunicacion['tipo'] == $tipo) ? 'selected' : ''; ?>>
                                        <?php echo ucfirst($tipo); ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Receptor (solo para gerentes y admin) -->
                    <?php if ($usuario_rol == 'gerente' || $usuario_rol == 'administrador'): ?>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Destinatario</label>
                        <select name="receptor_id" class="form-input">
                            <option value="">Todos los miembros del proyecto (general)</option>
                            <?php if (isset($usuarios)): ?>
                                <?php foreach ($usuarios as $usuario): ?>
                                    <?php if ($usuario['id'] != $usuario_id): ?>
                                    <option value="<?php echo htmlspecialchars($usuario['id'] ?? ''); ?>"
                                        <?php echo (isset($datos_form['receptor_id']) && $datos_form['receptor_id'] == $usuario['id']) || (isset($comunicacion['receptor_id']) && $comunicacion['receptor_id'] == $usuario['id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($usuario['nombre'] ?? ''); ?> 
                                        (<?php echo htmlspecialchars($usuario['rol'] ?? ''); ?>)
                                    </option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        <div class="mt-1 text-xs text-gray-500">Dejar en blanco para enviar a todo el equipo</div>
                    </div>
                    <?php else: ?>
                    <input type="hidden" name="receptor_id" value="">
                    <?php endif; ?>
                    
                    <!-- Prioridad -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Prioridad</label>
                        <select name="prioridad" class="form-input">
                            <?php if (isset($prioridades)): ?>
                                <?php foreach ($prioridades as $prioridad): ?>
                                    <option value="<?php echo htmlspecialchars($prioridad); ?>"
                                        <?php echo (isset($datos_form['prioridad']) && $datos_form['prioridad'] == $prioridad) || (isset($comunicacion['prioridad']) && $comunicacion['prioridad'] == $prioridad) ? 'selected' : ''; ?>>
                                        <?php echo ucfirst($prioridad); ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>
                
                <!-- Asunto -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2 required">Asunto</label>
                    <input type="text" name="asunto" class="form-input" required
                           value="<?php echo htmlspecialchars($datos_form['asunto'] ?? $comunicacion['asunto'] ?? ''); ?>"
                           placeholder="Ingresa el asunto de la comunicación...">
                </div>
                
                <!-- Mensaje -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2 required">Mensaje</label>
                    <textarea name="mensaje" rows="8" class="form-input" required
                        placeholder="Escribe tu mensaje aquí..."><?php echo htmlspecialchars($datos_form['mensaje'] ?? $comunicacion['mensaje'] ?? ''); ?></textarea>
                </div>
                
                <!-- Botones de acción -->
                <div class="flex flex-wrap gap-3 pt-6 border-t border-gray-200">
                    <?php if ($accion === 'crear_comunicacion'): ?>
                    <button type="submit" name="enviar_y_otra" 
                            class="bg-gradient-to-r from-green-500 to-emerald-500 hover:opacity-90 text-white px-6 py-3 rounded-xl font-medium flex items-center gap-2 hover-lift shadow-lg">
                        <i class="fas fa-paper-plane"></i>
                        Enviar y crear otra
                    </button>
                    <?php endif; ?>
                    
                    <button type="submit" 
                            class="gradient-bg-proceso5 hover:opacity-90 text-white px-6 py-3 rounded-xl font-medium flex items-center gap-2 hover-lift shadow-lg">
                        <i class="fas <?php echo $accion === 'crear_comunicacion' ? 'fa-paper-plane' : 'fa-save'; ?>"></i>
                        <?php echo $accion === 'crear_comunicacion' ? 'Enviar Comunicación' : 'Guardar Cambios'; ?>
                    </button>
                    
                    <a href="?accion=comunicaciones" 
                       class="px-6 py-3 bg-gradient-to-r from-gray-100 to-gray-200 hover:from-gray-200 hover:to-gray-300 text-gray-700 rounded-xl font-medium flex items-center gap-2 hover-lift">
                        <i class="fas fa-times"></i>
                        Cancelar
                    </a>
                    
                    <?php if (isset($comunicacion['id']) && ($usuario_rol == 'administrador' || $comunicacion['emisor_id'] == $usuario_id)): ?>
                    <button type="button" onclick="confirmarEliminarComunicacion(<?php echo $comunicacion['id']; ?>)" 
                            class="ml-auto px-6 py-3 bg-gradient-to-r from-red-500 to-rose-500 hover:opacity-90 text-white rounded-xl font-medium flex items-center gap-2 hover-lift shadow-lg">
                        <i class="fas fa-trash"></i>
                        Eliminar Comunicación
                    </button>
                    <?php endif; ?>
                </div>
            </form>
            
            <?php if (isset($comunicacion['id'])): ?>
            <!-- Formulario oculto para eliminar -->
            <form method="POST" id="form-eliminar-comunicacion-<?php echo $comunicacion['id']; ?>" class="hidden">
                <input type="hidden" name="accion" value="eliminar_comunicacion">
                <input type="hidden" name="id" value="<?php echo $comunicacion['id']; ?>">
            </form>
            <?php endif; ?>
        </div>
        
        <script>
        function confirmarEliminarComunicacion(id) {
            if (confirm('¿Estás seguro de eliminar esta comunicación? Esta acción no se puede deshacer.')) {
                document.getElementById('form-eliminar-comunicacion-' + id).submit();
            }
        }
        </script>
        
        <?php endif; ?>
        <!-- ========== FIN SECCIÓN CREAR/EDITAR COMUNICACIÓN ========== -->
        
        <!-- ========== SECCIÓN: VER COMUNICACIÓN ========== -->
        <?php if ($accion === 'ver_comunicacion' && isset($comunicacion)): ?>
        
        <div class="glass-card rounded-2xl p-6">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Detalles de la Comunicación</h2>
                    <div class="text-gray-600">ID: #<?php echo htmlspecialchars($comunicacion['id'] ?? ''); ?></div>
                </div>
                <div class="flex flex-wrap gap-2">
                    <a href="?accion=comunicaciones" 
                       class="px-4 py-2.5 bg-gradient-to-r from-gray-100 to-gray-200 hover:from-gray-200 hover:to-gray-300 text-gray-700 rounded-xl font-medium flex items-center gap-2 hover-lift">
                        <i class="fas fa-arrow-left"></i>
                        Volver
                    </a>
                    <?php if (($usuario_rol == 'administrador') || ($comunicacion['emisor_id'] == $usuario_id)): ?>
                    <a href="?accion=editar_comunicacion&id=<?php echo $comunicacion['id']; ?>" 
                       class="gradient-bg-proceso5 hover:opacity-90 text-white px-5 py-2.5 rounded-xl font-medium flex items-center gap-2 hover-lift shadow-lg">
                        <i class="fas fa-edit"></i>
                        Editar
                    </a>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Cabecera de la comunicación -->
            <div class="bg-gradient-to-r from-gray-50 to-gray-100 p-6 rounded-2xl mb-6">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-4">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 mb-2"><?php echo htmlspecialchars($comunicacion['asunto'] ?? 'Sin asunto'); ?></h1>
                        <div class="flex flex-wrap items-center gap-3">
                            <?php 
                            $tipo_colores = [
                                'email' => 'badge-email',
                                'reunion' => 'badge-reunion',
                                'reporte' => 'badge-reporte',
                                'notificacion' => 'badge-notificacion',
                                'mensaje' => 'badge-mensaje'
                            ];
                            $prioridad_colores = [
                                'alta' => 'badge-prioridad-alta',
                                'normal' => 'badge-prioridad-normal',
                                'baja' => 'badge-prioridad-baja'
                            ];
                            ?>
                            <span class="status-badge <?php echo $tipo_colores[$comunicacion['tipo']] ?? 'badge-email'; ?> text-base">
                                <i class="fas fa-tag mr-1"></i>
                                <?php echo ucfirst($comunicacion['tipo'] ?? 'email'); ?>
                            </span>
                            <span class="status-badge <?php echo $prioridad_colores[$comunicacion['prioridad']] ?? 'badge-prioridad-normal'; ?> text-base">
                                <i class="fas fa-flag mr-1"></i>
                                <?php echo ucfirst($comunicacion['prioridad'] ?? 'normal'); ?>
                            </span>
                            <?php if (!$comunicacion['leido'] && (!$comunicacion['receptor_id'] || $comunicacion['receptor_id'] == $usuario_id)): ?>
                            <span class="status-badge bg-gradient-to-r from-yellow-100 to-orange-100 text-yellow-800 text-base">
                                <i class="fas fa-envelope mr-1"></i> No leído
                            </span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-sm text-gray-500">Fecha de envío</div>
                        <div class="font-medium text-gray-900">
                            <?php echo htmlspecialchars($comunicacion['fecha_envio_formatted'] ?? date('d/m/Y H:i', strtotime($comunicacion['fecha_envio']))); ?>
                        </div>
                    </div>
                </div>
                
                <!-- Información de remitente y destinatario -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                    <div class="bg-white p-4 rounded-xl">
                        <div class="text-sm text-gray-500 mb-2">Remitente</div>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-gradient-to-r from-purple-500 to-indigo-600 rounded-full flex items-center justify-center text-white font-bold">
                                <?php echo isset($comunicacion['emisor_nombre']) ? strtoupper(substr($comunicacion['emisor_nombre'], 0, 1)) : 'E'; ?>
                            </div>
                            <div>
                                <div class="font-medium text-gray-900"><?php echo htmlspecialchars($comunicacion['emisor_nombre'] ?? 'Desconocido'); ?></div>
                                <div class="text-sm text-gray-600"><?php echo htmlspecialchars($comunicacion['emisor_email'] ?? ''); ?></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-center">
                        <i class="fas fa-arrow-right text-2xl text-gray-300"></i>
                    </div>
                    
                    <div class="bg-white p-4 rounded-xl">
                        <div class="text-sm text-gray-500 mb-2">Destinatario</div>
                        <div class="flex items-center gap-3">
                            <?php if ($comunicacion['receptor_nombre']): ?>
                            <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-cyan-600 rounded-full flex items-center justify-center text-white font-bold">
                                <?php echo strtoupper(substr($comunicacion['receptor_nombre'], 0, 1)); ?>
                            </div>
                            <div>
                                <div class="font-medium text-gray-900"><?php echo htmlspecialchars($comunicacion['receptor_nombre']); ?></div>
                                <div class="text-sm text-gray-600"><?php echo htmlspecialchars($comunicacion['receptor_email'] ?? ''); ?></div>
                            </div>
                            <?php else: ?>
                            <div class="w-10 h-10 bg-gradient-to-r from-green-500 to-emerald-600 rounded-full flex items-center justify-center text-white">
                                <i class="fas fa-users"></i>
                            </div>
                            <div>
                                <div class="font-medium text-gray-900">Todos los miembros</div>
                                <div class="text-sm text-gray-600">Comunicación general</div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Contenido del mensaje -->
            <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden mb-6">
                <div class="p-8">
                    <div class="prose max-w-none">
                        <div class="whitespace-pre-wrap text-gray-700"><?php echo nl2br(htmlspecialchars($comunicacion['mensaje'] ?? 'Sin contenido')); ?></div>
                    </div>
                </div>
            </div>
            
            <!-- Información del proyecto -->
            <div class="bg-gradient-to-r from-blue-50 to-cyan-100 p-6 rounded-2xl">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-cyan-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-project-diagram text-white text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Proyecto Relacionado</h3>
                        <p class="text-gray-600">Esta comunicación está asociada al siguiente proyecto</p>
                    </div>
                </div>
                <div class="bg-white p-4 rounded-xl">
                    <div class="font-medium text-gray-900 text-lg mb-2"><?php echo htmlspecialchars($comunicacion['proyecto_nombre'] ?? 'Sin proyecto'); ?></div>
                    <p class="text-gray-600">ID del Proyecto: #<?php echo htmlspecialchars($comunicacion['proyecto_id'] ?? ''); ?></p>
                </div>
            </div>
        </div>
        
        <?php endif; ?>
        <!-- ========== FIN SECCIÓN VER COMUNICACIÓN ========== -->
        
        <!-- ========== SECCIÓN: REPORTES ========== -->
        <?php if ($accion === 'reportes'): ?>
        
        <div class="glass-card rounded-2xl p-6">
            <!-- Encabezado -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Reportes y Análisis</h2>
                    <p class="text-gray-600">Estadísticas, métricas y análisis del desempeño del equipo</p>
                </div>
                <div class="flex flex-wrap gap-2">
                    <form method="GET" class="flex gap-2">
                        <input type="hidden" name="accion" value="reportes">
                        <select name="periodo" class="form-input" onchange="this.form.submit()">
                            <option value="mes_actual" <?php echo (isset($_GET['periodo']) && $_GET['periodo'] == 'mes_actual') ? 'selected' : ''; ?>>Mes Actual</option>
                            <option value="trimestre" <?php echo (isset($_GET['periodo']) && $_GET['periodo'] == 'trimestre') ? 'selected' : ''; ?>>Último Trimestre</option>
                            <option value="anio" <?php echo (isset($_GET['periodo']) && $_GET['periodo'] == 'anio') ? 'selected' : ''; ?>>Último Año</option>
                            <option value="personalizado" <?php echo (isset($_GET['periodo']) && $_GET['periodo'] == 'personalizado') ? 'selected' : ''; ?>>Personalizado</option>
                        </select>
                    </form>
                    <button onclick="window.print()" 
                            class="px-5 py-2.5 bg-gradient-to-r from-gray-100 to-gray-200 hover:from-gray-200 hover:to-gray-300 text-gray-700 rounded-xl font-medium flex items-center gap-2 hover-lift">
                        <i class="fas fa-print"></i>
                        Imprimir
                    </button>
                </div>
            </div>
            
            <!-- Filtro por proyecto -->
            <div class="mb-6">
                <form method="GET" class="flex flex-col md:flex-row gap-3">
                    <input type="hidden" name="accion" value="reportes">
                    <select name="proyecto_id" class="form-input md:w-64" onchange="this.form.submit()">
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
                </form>
            </div>
            
            <?php if (isset($estadisticas) && isset($carga_trabajo)): ?>
            <!-- Estadísticas principales -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-gradient-to-r from-purple-50 to-purple-100 p-6 rounded-2xl">
                    <div class="text-sm text-gray-600 mb-2">Total Tareas</div>
                    <div class="text-3xl font-bold text-gray-900"><?php echo $estadisticas['total_tareas'] ?? 0; ?></div>
                    <div class="text-xs text-gray-500 mt-2">Asignadas en el sistema</div>
                </div>
                
                <div class="bg-gradient-to-r from-blue-50 to-cyan-100 p-6 rounded-2xl">
                    <div class="text-sm text-gray-600 mb-2">Eficiencia</div>
                    <div class="text-3xl font-bold <?php echo ($estadisticas['eficiencia_horas'] ?? 0) >= 100 ? 'text-green-600' : (($estadisticas['eficiencia_horas'] ?? 0) >= 80 ? 'text-yellow-600' : 'text-red-600'); ?>">
                        <?php echo number_format($estadisticas['eficiencia_horas'] ?? 0, 1); ?>%
                    </div>
                    <div class="text-xs text-gray-500 mt-2">Horas reales/estimadas</div>
                </div>
                
                <div class="bg-gradient-to-r from-green-50 to-emerald-100 p-6 rounded-2xl">
                    <div class="text-sm text-gray-600 mb-2">Completadas</div>
                    <div class="text-3xl font-bold text-green-600"><?php echo $estadisticas['tareas_completadas'] ?? 0; ?></div>
                    <div class="text-xs text-gray-500 mt-2">Tareas finalizadas</div>
                </div>
                
                <div class="bg-gradient-to-r from-red-50 to-rose-100 p-6 rounded-2xl">
                    <div class="text-sm text-gray-600 mb-2">Atrasadas</div>
                    <div class="text-3xl font-bold text-red-600"><?php echo $estadisticas['tareas_vencidas'] ?? 0; ?></div>
                    <div class="text-xs text-gray-500 mt-2">Tareas vencidas</div>
                </div>
            </div>
            
            <!-- Gráficos -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <!-- Gráfico de distribución de tareas -->
                <div class="bg-white p-6 rounded-2xl border border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Distribución de Tareas por Estado</h3>
                    <div class="h-64">
                        <canvas id="reporteEstadoChart"></canvas>
                    </div>
                </div>
                
                <!-- Gráfico de carga de trabajo -->
                <div class="bg-white p-6 rounded-2xl border border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Carga de Trabajo por Recurso</h3>
                    <div class="h-64">
                        <canvas id="cargaTrabajoChart"></canvas>
                    </div>
                </div>
            </div>
            
            <!-- Tabla de carga de trabajo -->
            <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden mb-8">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Carga de Trabajo por Miembro</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Recurso</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Rol</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Total Tareas</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Horas Estimadas</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Horas Reales</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Progreso Promedio</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Eficiencia</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            <?php foreach($carga_trabajo as $recurso): ?>
                            <?php 
                            $eficiencia = $recurso['horas_estimadas'] > 0 ? ($recurso['horas_reales'] / $recurso['horas_estimadas'] * 100) : 0;
                            $color_eficiencia = $eficiencia >= 100 ? 'text-green-600' : ($eficiencia >= 80 ? 'text-yellow-600' : 'text-red-600');
                            ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="font-medium text-gray-900"><?php echo htmlspecialchars($recurso['recurso_nombre'] ?? ''); ?></div>
                                    <div class="text-sm text-gray-500"><?php echo htmlspecialchars($recurso['proyecto_nombre'] ?? ''); ?></div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 text-xs font-medium bg-gradient-to-r from-gray-100 to-gray-200 text-gray-700 rounded-full">
                                        <?php echo htmlspecialchars($recurso['rol_proyecto'] ?? ''); ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-center">
                                        <div class="font-bold text-gray-900"><?php echo $recurso['total_tareas'] ?? 0; ?></div>
                                        <div class="text-xs text-gray-500">
                                            <?php echo $recurso['tareas_pendientes'] ?? 0; ?>P / <?php echo $recurso['tareas_en_progreso'] ?? 0; ?>EP / <?php echo $recurso['tareas_completadas'] ?? 0; ?>C
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-medium text-gray-900"><?php echo number_format($recurso['horas_estimadas'] ?? 0, 1); ?>h</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-medium <?php echo ($recurso['horas_reales'] ?? 0) > ($recurso['horas_estimadas'] ?? 0) ? 'text-red-600' : 'text-green-600'; ?>">
                                        <?php echo number_format($recurso['horas_reales'] ?? 0, 1); ?>h
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <div class="flex-1">
                                            <div class="progress-bar">
                                                <div class="progress-fill bg-gradient-to-r from-green-400 to-emerald-500" 
                                                     style="width: <?php echo min(100, max(0, $recurso['porcentaje_promedio'] ?? 0)); ?>%"></div>
                                            </div>
                                        </div>
                                        <span class="text-sm font-medium text-gray-700"><?php echo number_format($recurso['porcentaje_promedio'] ?? 0, 1); ?>%</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-bold <?php echo $color_eficiencia; ?>">
                                        <?php echo number_format($eficiencia, 1); ?>%
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Tareas próximas a vencer -->
            <?php if (isset($tareas_proximas) && !empty($tareas_proximas)): ?>
            <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-900">Tareas Próximas a Vencer (14 días)</h3>
                        <span class="px-3 py-1 bg-gradient-to-r from-red-100 to-rose-100 text-red-700 rounded-full text-sm font-medium">
                            <?php echo count($tareas_proximas); ?> tareas
                        </span>
                    </div>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <?php foreach(array_slice($tareas_proximas, 0, 6) as $tarea): ?>
                        <div class="bg-gradient-to-r from-gray-50 to-gray-100 p-4 rounded-xl">
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <div class="font-medium text-gray-900 truncate"><?php echo htmlspecialchars(substr($tarea['descripcion_tarea'] ?? 'Sin nombre', 0, 60)); ?></div>
                                    <div class="text-sm text-gray-600 mt-1"><?php echo htmlspecialchars($tarea['proyecto_nombre'] ?? ''); ?></div>
                                </div>
                                <span class="status-badge <?php echo ($tarea['dias_restantes'] ?? 0) < 0 ? 'badge-atrasada' : 'badge-alta'; ?>">
                                    <?php echo ($tarea['dias_restantes'] ?? 0) < 0 ? 'Vencida' : ($tarea['dias_restantes'] . ' días'); ?>
                                </span>
                            </div>
                            <div class="text-sm text-gray-600 mb-2">
                                <i class="fas fa-user mr-1"></i>
                                <?php echo htmlspecialchars($tarea['recurso_nombre'] ?? 'Sin asignar'); ?>
                            </div>
                            <div class="flex justify-between items-center">
                                <div class="text-sm text-gray-500">
                                    <?php echo isset($tarea['fecha_limite']) ? date('d/m/Y', strtotime($tarea['fecha_limite'])) : 'N/A'; ?>
                                </div>
                                <a href="?accion=ver_tarea&id=<?php echo $tarea['id']; ?>" 
                                   class="text-purple-600 hover:text-purple-800 text-sm font-medium">
                                    Ver detalles
                                </a>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Script para gráficos de reportes -->
            <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Gráfico de distribución de tareas
                const estadoCtx = document.getElementById('reporteEstadoChart')?.getContext('2d');
                if (estadoCtx) {
                    new Chart(estadoCtx, {
                        type: 'pie',
                        data: {
                            labels: ['Pendientes', 'En Progreso', 'Completadas', 'Atrasadas', 'En Revisión'],
                            datasets: [{
                                data: [
                                    <?php echo $estadisticas['tareas_pendientes'] ?? 0; ?>,
                                    <?php echo $estadisticas['tareas_en_progreso'] ?? 0; ?>,
                                    <?php echo $estadisticas['tareas_completadas'] ?? 0; ?>,
                                    <?php echo $estadisticas['tareas_atrasadas'] ?? 0; ?>,
                                    <?php echo $estadisticas['tareas_en_revision'] ?? 0; ?>
                                ],
                                backgroundColor: [
                                    '#fbd38d',
                                    '#bee3f8', 
                                    '#c6f6d5',
                                    '#fed7d7',
                                    '#e9d8fd'
                                ],
                                borderWidth: 2,
                                borderColor: '#ffffff'
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'right'
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            const label = context.label || '';
                                            const value = context.raw || 0;
                                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                            const percentage = Math.round((value / total) * 100);
                                            return `${label}: ${value} (${percentage}%)`;
                                        }
                                    }
                                }
                            }
                        }
                    });
                }
                
                // Gráfico de carga de trabajo
                const cargaCtx = document.getElementById('cargaTrabajoChart')?.getContext('2d');
                if (cargaCtx && <?php echo isset($carga_trabajo) ? 'true' : 'false'; ?>) {
                    const recursos = <?php echo json_encode(array_slice(array_column($carga_trabajo, 'recurso_nombre'), 0, 8)); ?>;
                    const horasEstimadas = <?php echo json_encode(array_slice(array_column($carga_trabajo, 'horas_estimadas'), 0, 8)); ?>;
                    const horasReales = <?php echo json_encode(array_slice(array_column($carga_trabajo, 'horas_reales'), 0, 8)); ?>;
                    
                    new Chart(cargaCtx, {
                        type: 'bar',
                        data: {
                            labels: recursos,
                            datasets: [{
                                label: 'Horas Estimadas',
                                data: horasEstimadas,
                                backgroundColor: '#9f7aea',
                                borderColor: '#805ad5',
                                borderWidth: 1
                            }, {
                                label: 'Horas Reales',
                                data: horasReales,
                                backgroundColor: '#68d391',
                                borderColor: '#48bb78',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                x: {
                                    grid: {
                                        display: false
                                    }
                                },
                                y: {
                                    beginAtZero: true,
                                    title: {
                                        display: true,
                                        text: 'Horas'
                                    }
                                }
                            },
                            plugins: {
                                legend: {
                                    position: 'top'
                                }
                            }
                        }
                    });
                }
            });
            </script>
            
            <?php else: ?>
            <div class="text-center py-16">
                <div class="w-24 h-24 mx-auto bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center mb-6">
                    <i class="fas fa-chart-bar text-4xl text-gray-400"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-700 mb-3">No hay datos para mostrar</h3>
                <p class="text-gray-500 mb-6 max-w-md mx-auto">Genera tareas y comunicaciones para ver reportes estadísticos</p>
            </div>
            <?php endif; ?>
        </div>
        
        <?php endif; ?>
        <!-- ========== FIN SECCIÓN REPORTES ========== -->
        
        <!-- ========== INFORMACIÓN DEL PROCESO PMBOK ========== -->
        <div class="mt-8 glass-card rounded-2xl p-6">
            <div class="flex flex-col md:flex-row items-start gap-6">
                <div class="w-16 h-16 bg-gradient-to-r from-purple-100 to-indigo-100 rounded-xl flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-info-circle text-purple-600 text-2xl"></i>
                </div>
                <div class="flex-1">
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Proceso 5: Dirigir el Equipo del Proyecto</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="bg-gradient-to-r from-purple-50 to-purple-100 p-5 rounded-xl">
                            <h4 class="font-semibold text-purple-800 mb-3 flex items-center gap-2">
                                <i class="fas fa-bullseye"></i>
                                Objetivo
                            </h4>
                            <p class="text-sm text-purple-700 leading-relaxed">
                                Dirigir el equipo, tomar decisiones, resolver conflictos y comunicar información 
                                para un rendimiento óptimo del proyecto. Incluye liderazgo, motivación y 
                                gestión del desempeño del equipo.
                            </p>
                        </div>
                        
                        <div class="bg-gradient-to-r from-indigo-50 to-indigo-100 p-5 rounded-xl">
                            <h4 class="font-semibold text-indigo-800 mb-3 flex items-center gap-2">
                                <i class="fas fa-tools"></i>
                                Herramientas y Técnicas
                            </h4>
                            <ul class="text-sm text-indigo-700 space-y-1">
                                <li class="flex items-center gap-2"><i class="fas fa-comments text-xs"></i> Comunicación efectiva</li>
                                <li class="flex items-center gap-2"><i class="fas fa-user-friends text-xs"></i> Liderazgo y motivación</li>
                                <li class="flex items-center gap-2"><i class="fas fa-handshake text-xs"></i> Gestión de conflictos</li>
                                <li class="flex items-center gap-2"><i class="fas fa-chart-line text-xs"></i> Evaluación de desempeño</li>
                                <li class="flex items-center gap-2"><i class="fas fa-users text-xs"></i> Reuniones de equipo</li>
                            </ul>
                        </div>
                        
                        <div class="bg-gradient-to-r from-violet-50 to-violet-100 p-5 rounded-xl">
                            <h4 class="font-semibold text-violet-800 mb-3 flex items-center gap-2">
                                <i class="fas fa-file-export"></i>
                                Salidas Principales
                            </h4>
                            <ul class="text-sm text-violet-700 space-y-1">
                                <li class="flex items-center gap-2"><i class="fas fa-clipboard-check text-xs"></i> Evaluaciones de desempeño</li>
                                <li class="flex items-center gap-2"><i class="fas fa-file-contract text-xs"></i> Registros de reuniones</li>
                                <li class="flex items-center gap-2"><i class="fas fa-exchange-alt text-xs"></i> Solicitudes de cambio</li>
                                <li class="flex items-center gap-2"><i class="fas fa-sync-alt text-xs"></i> Actualizaciones de documentos</li>
                                <li class="flex items-center gap-2"><i class="fas fa-user-check text-xs"></i> Reconocimientos del equipo</li>
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
            // Auto-ocultar mensajes después de 5 segundos
            const messages = document.querySelectorAll('[class*="bg-gradient-to-r"]');
            messages.forEach(message => {
                if (message.textContent.includes('éxito') || message.textContent.includes('error') || message.textContent.includes('Error') || message.textContent.includes('Por favor')) {
                    setTimeout(() => {
                        message.style.opacity = '0';
                        message.style.transition = 'opacity 0.5s ease';
                        setTimeout(() => message.remove(), 500);
                    }, 5000);
                }
            });
            
            // Formatear fechas automáticamente
            const dateInputs = document.querySelectorAll('input[type="date"]');
            dateInputs.forEach(input => {
                if (!input.value) {
                    input.value = new Date().toISOString().split('T')[0];
                }
            });
            
            // Validación de formularios
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    const requiredFields = form.querySelectorAll('[required]');
                    let isValid = true;
                    
                    requiredFields.forEach(field => {
                        if (!field.value.trim()) {
                            isValid = false;
                            field.classList.add('border-red-500');
                            field.classList.remove('border-gray-300');
                            
                            // Crear mensaje de error si no existe
                            if (!field.nextElementSibling || !field.nextElementSibling.classList.contains('text-red-500')) {
                                const errorMsg = document.createElement('div');
                                errorMsg.className = 'text-red-500 text-xs mt-1';
                                errorMsg.textContent = 'Este campo es obligatorio';
                                field.parentNode.insertBefore(errorMsg, field.nextSibling);
                            }
                        } else {
                            field.classList.remove('border-red-500');
                            field.classList.add('border-gray-300');
                            
                            // Remover mensaje de error si existe
                            if (field.nextElementSibling && field.nextElementSibling.classList.contains('text-red-500')) {
                                field.nextElementSibling.remove();
                            }
                        }
                    });
                    
                    if (!isValid) {
                        e.preventDefault();
                        
                        // Mostrar mensaje general de error
                        if (!document.querySelector('.form-global-error')) {
                            const errorDiv = document.createElement('div');
                            errorDiv.className = 'form-global-error mb-4 p-4 rounded-lg bg-red-50 text-red-700 border border-red-200';
                            errorDiv.innerHTML = `
                                <div class="flex items-center">
                                    <i class="fas fa-exclamation-circle mr-2"></i>
                                    <span>Por favor completa todos los campos obligatorios marcados en rojo.</span>
                                </div>
                            `;
                            form.prepend(errorDiv);
                            
                            // Hacer scroll al error
                            errorDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }
                    }
                });
            });
            
            // Actualizar dinámicamente el porcentaje en los sliders
            const rangeInputs = document.querySelectorAll('input[type="range"]');
            rangeInputs.forEach(input => {
                input.addEventListener('input', function() {
                    const output = this.nextElementSibling;
                    if (output && output.tagName === 'OUTPUT') {
                        output.value = this.value + '%';
                    }
                });
            });
            
            // Toggle para menú móvil
            const sidebarToggle = document.createElement('button');
            sidebarToggle.className = 'md:hidden fixed top-4 left-4 z-50 w-10 h-10 bg-purple-600 text-white rounded-full flex items-center justify-center shadow-lg';
            sidebarToggle.innerHTML = '<i class="fas fa-bars"></i>';
            sidebarToggle.onclick = function() {
                const sidebar = document.querySelector('aside');
                if (sidebar) {
                    sidebar.classList.toggle('hidden');
                    sidebar.classList.toggle('block');
                }
            };
            document.body.appendChild(sidebarToggle);
        });
        
        // Función para exportar a PDF
        function exportToPDF(elementId) {
            const element = document.getElementById(elementId);
            if (element) {
                window.print();
            }
        }
        
        // Función para copiar al portapapeles
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                alert('Copiado al portapapeles');
            }).catch(err => {
                console.error('Error al copiar: ', err);
            });
        }
    </script>
</body>
</html>
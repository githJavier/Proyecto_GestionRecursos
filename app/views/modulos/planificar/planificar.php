<?php
// Vista recibe: $accion, $datos, $mensaje, $tipo_mensaje, $usuario
// $usuario viene del controlador

// Incluir sidebar
require_once __DIR__ . '/../../componentes/sidebar.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Planificar Recursos | PMBOK 6</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
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
        
        .hover-lift {
            transition: all 0.3s ease;
        }
        
        .hover-lift:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.12);
        }
        
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .gradient-bg-planificacion {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
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
        
        .stat-card {
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
        }
        
        .stat-card:hover {
            border-left-color: #10b981;
        }
        
        .table-row-hover {
            transition: background-color 0.2s ease;
        }
        
        .table-row-hover:hover {
            background-color: #f8fafc;
        }
        
        .form-input {
            transition: all 0.2s ease;
        }
        
        .form-input:focus {
            border-color: #10b981;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
        }
        
        .radio-card {
            transition: all 0.2s ease;
            cursor: pointer;
        }
        
        .radio-card:hover {
            border-color: #10b981;
            background-color: #f7fafc;
        }
        
        .radio-card.selected {
            border-color: #10b981;
            background-color: #d1fae5;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
        }
        
        .tab-planificacion {
            padding: 0.75rem 1.5rem;
            border-radius: 0.75rem;
            font-weight: 500;
            transition: all 0.2s ease;
        }
        
        .tab-planificacion.active {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            box-shadow: 0 4px 20px rgba(16, 185, 129, 0.3);
        }
        
        .tab-planificacion:not(.active):hover {
            background-color: #edf2f7;
        }
        
        /* Clase para truncar texto */
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-emerald-50">
    
    <!-- Sidebar incluido -->
    
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
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Planificar Recursos</h1>
                <p class="text-gray-600">Proceso 1 PMBOK 6 | Planificación de recursos de actividades</p>
            </div>
            <div class="flex items-center gap-3 bg-white rounded-xl px-4 py-3 shadow-sm border border-gray-200">
                <div class="w-10 h-10 bg-gradient-to-r from-emerald-500 to-green-600 rounded-full flex items-center justify-center text-white font-bold">
                    <?php echo isset($usuario) ? strtoupper(substr($usuario['nombre'], 0, 1)) : 'P'; ?>
                </div>
                <div>
                    <div class="text-sm text-gray-500">Planificador</div>
                    <div class="font-medium text-gray-800"><?php echo isset($usuario) ? htmlspecialchars($usuario['nombre']) : 'Planificador'; ?></div>
                </div>
            </div>
        </div>
        
        <!-- ========== INDICADORES DE PROCESO PMBOK ========== -->
        <div class="grid grid-cols-1 md:grid-cols-6 gap-4 mb-8">
            <div class="gradient-bg-planificacion p-4 rounded-xl text-white hover-lift shadow-lg">
                <div class="text-sm font-medium">Proceso 1</div>
                <div class="text-lg font-semibold">Planificar</div>
                <div class="mt-2 text-xs flex items-center">
                    <i class="fas fa-chart-line mr-1"></i> En Progreso
                </div>
            </div>
            
            <div class="bg-white p-4 rounded-xl border border-gray-200 opacity-70">
                <div class="text-sm text-gray-400">Proceso 2</div>
                <div class="text-lg font-semibold text-gray-400">Estimar</div>
                <div class="mt-2 text-xs text-gray-400">
                    <i class="fas fa-lock mr-1"></i> Bloqueado
                </div>
            </div>
            
            <?php for($i = 3; $i <= 6; $i++): ?>
            <div class="bg-white p-4 rounded-xl border border-gray-200 opacity-70">
                <div class="text-sm text-gray-400">Proceso <?php echo $i; ?></div>
                <div class="text-lg font-semibold text-gray-400">
                    <?php 
                    $nombres = [3 => 'Adquirir', 4 => 'Desarrollar', 5 => 'Dirigir', 6 => 'Controlar'];
                    echo $nombres[$i];
                    ?>
                </div>
                <div class="mt-2 text-xs text-gray-400">
                    <i class="fas fa-lock mr-1"></i> Bloqueado
                </div>
            </div>
            <?php endfor; ?>
        </div>
        
        <!-- ========== NAVEGACIÓN INTERNA DEL PROCESO 1 ========== -->
        <div class="flex gap-3 mb-8 bg-white rounded-2xl p-2 shadow-sm border border-gray-200">
            <a href="?accion=dashboard" 
               class="tab-planificacion <?php echo $accion === 'dashboard' || !isset($accion) ? 'active' : ''; ?>">
                <i class="fas fa-chart-line mr-2"></i>
                Dashboard
            </a>
            <a href="?accion=proyectos" 
               class="tab-planificacion <?php echo $accion === 'proyectos' ? 'active' : ''; ?>">
                <i class="fas fa-project-diagram mr-2"></i>
                Proyectos
            </a>
            <a href="?accion=recursos" 
               class="tab-planificacion <?php echo $accion === 'recursos' ? 'active' : ''; ?>">
                <i class="fas fa-users-cog mr-2"></i>
                Recursos
            </a>
            <a href="?accion=reportes" 
               class="tab-planificacion <?php echo $accion === 'reportes' ? 'active' : ''; ?>">
                <i class="fas fa-chart-bar mr-2"></i>
                Reportes
            </a>
        </div>
        
        <!-- ========== SECCIÓN: DASHBOARD ========== -->
        <?php if ($accion === 'dashboard' || !isset($accion)): ?>
        
        <!-- Estadísticas rápidas -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="stat-card bg-white rounded-2xl p-6 border border-gray-200 shadow-sm hover-lift">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-gradient-to-r from-emerald-50 to-emerald-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-project-diagram text-emerald-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Proyectos</p>
                        <p class="text-2xl font-bold text-gray-800">
                            <?php 
                            $total_proyectos = 0;
                            if (isset($datos['proyectos']) && is_array($datos['proyectos'])) {
                                $total_proyectos = count($datos['proyectos']);
                            }
                            echo $total_proyectos;
                            ?>
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="stat-card bg-white rounded-2xl p-6 border border-gray-200 shadow-sm hover-lift">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-gradient-to-r from-blue-50 to-blue-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-play-circle text-blue-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Proyectos Activos</p>
                        <p class="text-2xl font-bold text-gray-800">
                            <?php 
                            $activos = 0;
                            if (isset($datos['proyectos']) && is_array($datos['proyectos'])) {
                                foreach ($datos['proyectos'] as $proyecto) {
                                    if ($proyecto['estado'] === 'en_ejecucion' || $proyecto['estado'] === 'planificacion') {
                                        $activos++;
                                    }
                                }
                            }
                            echo $activos;
                            ?>
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="stat-card bg-white rounded-2xl p-6 border border-gray-200 shadow-sm hover-lift">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-gradient-to-r from-amber-50 to-amber-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-boxes text-amber-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Recursos Planificados</p>
                        <p class="text-2xl font-bold text-gray-800">
                            <?php 
                            $total_recursos = 0;
                            if (isset($datos['planificaciones']) && is_array($datos['planificaciones'])) {
                                $total_recursos = count($datos['planificaciones']);
                            }
                            echo $total_recursos;
                            ?>
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
                        <p class="text-sm text-gray-500">Presupuesto Total</p>
                        <p class="text-2xl font-bold text-gray-800">
                            <?php 
                            $total_presupuesto = 0;
                            if (isset($datos['proyectos']) && is_array($datos['proyectos'])) {
                                foreach ($datos['proyectos'] as $proyecto) {
                                    $total_presupuesto += $proyecto['presupuesto_estimado'] ?? 0;
                                }
                            }
                            echo '$' . number_format($total_presupuesto, 2);
                            ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Proyectos recientes -->
        <div class="glass-card rounded-2xl p-8 mb-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Proyectos Recientes</h2>
                <a href="?accion=proyectos" 
                   class="gradient-bg-planificacion hover:opacity-90 text-white px-5 py-2.5 rounded-xl font-medium flex items-center gap-2 hover-lift shadow-lg">
                    <i class="fas fa-list"></i>
                    Ver Todos
                </a>
            </div>
            
            <?php if (empty($datos['proyectos']) || !is_array($datos['proyectos'])): ?>
            <div class="text-center py-12">
                <div class="w-24 h-24 mx-auto bg-gradient-to-br from-emerald-50 to-green-50 rounded-full flex items-center justify-center mb-6">
                    <i class="fas fa-folder-open text-4xl text-gray-400"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-700 mb-3">No hay proyectos creados</h3>
                <p class="text-gray-500 mb-6 max-w-md mx-auto">Comienza creando tu primer proyecto para planificar recursos</p>
                <a href="?accion=crear_proyecto" 
                   class="gradient-bg-planificacion hover:opacity-90 text-white px-6 py-3 rounded-xl font-medium inline-flex items-center gap-2 shadow-lg hover-lift">
                    <i class="fas fa-plus"></i>
                    Crear primer proyecto
                </a>
            </div>
            <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach(array_slice($datos['proyectos'], 0, 6) as $proyecto): ?>
                <div class="bg-white rounded-2xl border border-gray-200 p-6 hover-lift hover:border-emerald-200">
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-emerald-100 to-green-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-project-diagram text-emerald-600"></i>
                        </div>
                        <?php 
                        $estados = [
                            'planificacion' => ['color' => 'bg-amber-100 text-amber-800', 'icon' => 'fa-clock', 'text' => 'Planificación'],
                            'en_ejecucion' => ['color' => 'bg-emerald-100 text-emerald-800', 'icon' => 'fa-play', 'text' => 'En ejecución'],
                            'completado' => ['color' => 'bg-blue-100 text-blue-800', 'icon' => 'fa-check', 'text' => 'Completado'],
                            'cancelado' => ['color' => 'bg-rose-100 text-rose-800', 'icon' => 'fa-times', 'text' => 'Cancelado']
                        ];
                        $estado = $estados[$proyecto['estado']] ?? $estados['planificacion'];
                        ?>
                        <span class="status-badge <?php echo $estado['color']; ?>">
                            <i class="fas <?php echo $estado['icon']; ?> text-xs"></i>
                            <?php echo $estado['text']; ?>
                        </span>
                    </div>
                    
                    <h3 class="text-lg font-semibold text-gray-900 mb-3"><?php echo htmlspecialchars($proyecto['nombre']); ?></h3>
                    <p class="text-gray-600 text-sm mb-4 line-clamp-2"><?php echo htmlspecialchars($proyecto['descripcion'] ?? 'Sin descripción'); ?></p>
                    
                    <div class="space-y-3 mb-6">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Inicio:</span>
                            <span class="font-medium text-gray-700"><?php echo date('d/m/Y', strtotime($proyecto['fecha_inicio'])); ?></span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Fin estimado:</span>
                            <span class="font-medium text-gray-700"><?php echo date('d/m/Y', strtotime($proyecto['fecha_fin_estimada'])); ?></span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Presupuesto:</span>
                            <span class="font-medium text-emerald-600">$<?php echo number_format($proyecto['presupuesto_estimado'], 2); ?></span>
                        </div>
                    </div>
                    
                    <div class="flex gap-3 pt-4 border-t border-gray-100">
                        <a href="?accion=recursos&proyecto_id=<?php echo $proyecto['id']; ?>" 
                           class="flex-1 gradient-bg-planificacion hover:opacity-90 text-white px-4 py-2.5 rounded-lg font-medium text-center text-sm">
                            <i class="fas fa-tasks mr-2"></i> Planificar
                        </a>
                        <a href="?accion=editar_proyecto&id=<?php echo $proyecto['id']; ?>" 
                           class="w-10 h-10 bg-emerald-50 hover:bg-emerald-100 text-emerald-600 rounded-lg flex items-center justify-center">
                            <i class="fas fa-edit"></i>
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
        
        <?php endif; ?>
        <!-- ========== FIN SECCIÓN DASHBOARD ========== -->
        
        <!-- ========== SECCIÓN: PROYECTOS ========== -->
        <?php if ($accion === 'proyectos'): ?>
        
        <div class="glass-card rounded-2xl p-8 mb-8">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Gestión de Proyectos</h2>
                    <p class="text-gray-600">Crea y administra los proyectos del portafolio</p>
                </div>
                <a href="?accion=crear_proyecto" 
                   class="gradient-bg-planificacion hover:opacity-90 text-white px-5 py-2.5 rounded-xl font-medium flex items-center gap-2 hover-lift shadow-lg">
                    <i class="fas fa-plus"></i>
                    Nuevo Proyecto
                </a>
            </div>
            
            <?php if (empty($datos['proyectos']) || !is_array($datos['proyectos'])): ?>
            <div class="text-center py-16">
                <div class="w-24 h-24 mx-auto bg-gradient-to-br from-emerald-50 to-green-50 rounded-full flex items-center justify-center mb-6">
                    <i class="fas fa-folder-open text-4xl text-gray-400"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-700 mb-3">No hay proyectos creados</h3>
                <p class="text-gray-500 mb-6 max-w-md mx-auto">Comienza creando tu primer proyecto para gestionar los recursos</p>
                <a href="?accion=crear_proyecto" 
                   class="gradient-bg-planificacion hover:opacity-90 text-white px-6 py-3 rounded-xl font-medium inline-flex items-center gap-2 shadow-lg hover-lift">
                    <i class="fas fa-plus"></i>
                    Crear primer proyecto
                </a>
            </div>
            <?php else: ?>
            <!-- Filtros -->
            <div class="mb-8 bg-gradient-to-r from-emerald-50 to-green-50 rounded-2xl p-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                        <select class="form-input w-full px-4 py-2.5 border border-gray-300 rounded-xl" id="filtro-estado">
                            <option value="">Todos los estados</option>
                            <option value="planificacion">Planificación</option>
                            <option value="en_ejecucion">En ejecución</option>
                            <option value="completado">Completado</option>
                            <option value="cancelado">Cancelado</option>
                        </select>
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Buscar proyecto</label>
                        <input type="text" 
                               id="filtro-buscar"
                               class="form-input w-full px-4 py-2.5 border border-gray-300 rounded-xl"
                               placeholder="Buscar por nombre o descripción...">
                    </div>
                    
                    <div class="flex items-end">
                        <button onclick="aplicarFiltros()" 
                                class="w-full gradient-bg-planificacion hover:opacity-90 text-white px-4 py-2.5 rounded-xl font-medium flex items-center justify-center gap-2">
                            <i class="fas fa-filter"></i>
                            Aplicar Filtros
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Tabla de proyectos -->
            <div class="overflow-x-auto rounded-xl border border-gray-200">
                <table class="min-w-full divide-y divide-gray-200" id="tabla-proyectos">
                    <thead class="bg-gradient-to-r from-emerald-50 to-emerald-100">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 uppercase">Proyecto</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 uppercase">Estado</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 uppercase">Fechas</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 uppercase">Presupuesto</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 uppercase">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        <?php foreach($datos['proyectos'] as $proyecto): ?>
                        <tr class="table-row-hover hover:bg-emerald-50/30" data-estado="<?php echo $proyecto['estado']; ?>" data-nombre="<?php echo htmlspecialchars(strtolower($proyecto['nombre'])); ?>">
                            <td class="px-6 py-4">
                                <div class="flex items-start gap-4">
                                    <div class="w-12 h-12 bg-gradient-to-br from-emerald-100 to-green-100 rounded-xl flex items-center justify-center mt-1">
                                        <i class="fas fa-project-diagram text-emerald-600"></i>
                                    </div>
                                    <div class="flex-1">
                                        <div class="font-semibold text-gray-900 mb-1"><?php echo htmlspecialchars($proyecto['nombre']); ?></div>
                                        <div class="text-sm text-gray-600 mb-2 line-clamp-2"><?php echo htmlspecialchars($proyecto['descripcion'] ?? 'Sin descripción'); ?></div>
                                        <div class="text-xs text-gray-400">
                                            ID: <?php echo $proyecto['id']; ?>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <?php 
                                $estados = [
                                    'planificacion' => ['color' => 'bg-amber-100 text-amber-800', 'icon' => 'fa-clock'],
                                    'en_ejecucion' => ['color' => 'bg-emerald-100 text-emerald-800', 'icon' => 'fa-play'],
                                    'completado' => ['color' => 'bg-blue-100 text-blue-800', 'icon' => 'fa-check'],
                                    'cancelado' => ['color' => 'bg-rose-100 text-rose-800', 'icon' => 'fa-times']
                                ];
                                $estado = $estados[$proyecto['estado']] ?? $estados['planificacion'];
                                ?>
                                <div class="status-badge <?php echo $estado['color']; ?>">
                                    <i class="fas <?php echo $estado['icon']; ?> text-xs"></i>
                                    <?php echo ucfirst(str_replace('_', ' ', $proyecto['estado'])); ?>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="space-y-2">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Inicio:</span>
                                        <span class="font-medium"><?php echo date('d/m/Y', strtotime($proyecto['fecha_inicio'])); ?></span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Fin estimado:</span>
                                        <span class="font-medium"><?php echo date('d/m/Y', strtotime($proyecto['fecha_fin_estimada'])); ?></span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-900 text-lg">$<?php echo number_format($proyecto['presupuesto_estimado'], 2); ?></div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <a href="?accion=recursos&proyecto_id=<?php echo $proyecto['id']; ?>" 
                                       class="w-10 h-10 bg-emerald-50 hover:bg-emerald-100 text-emerald-600 rounded-xl flex items-center justify-center hover-lift"
                                       title="Planificar recursos">
                                        <i class="fas fa-tasks"></i>
                                    </a>
                                    <a href="?accion=editar_proyecto&id=<?php echo $proyecto['id']; ?>" 
                                       class="w-10 h-10 bg-blue-50 hover:bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center hover-lift"
                                       title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="#" 
                                       class="w-10 h-10 bg-gray-50 hover:bg-gray-100 text-gray-600 rounded-xl flex items-center justify-center hover-lift"
                                       title="Ver detalles">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>

        <script>
            function aplicarFiltros() {
                const filtroEstado = document.getElementById('filtro-estado').value;
                const filtroBuscar = document.getElementById('filtro-buscar').value.toLowerCase();
                const filas = document.querySelectorAll('#tabla-proyectos tbody tr');
                
                filas.forEach(fila => {
                    const estado = fila.getAttribute('data-estado');
                    const nombre = fila.getAttribute('data-nombre');
                    let mostrar = true;
                    
                    if (filtroEstado && estado !== filtroEstado) {
                        mostrar = false;
                    }
                    
                    if (filtroBuscar && !nombre.includes(filtroBuscar)) {
                        mostrar = false;
                    }
                    
                    if (mostrar) {
                        fila.style.display = '';
                    } else {
                        fila.style.display = 'none';
                    }
                });
            }
            
            document.addEventListener('DOMContentLoaded', function() {
                // Inicializar filtro de búsqueda
                const inputBuscar = document.getElementById('filtro-buscar');
                inputBuscar.addEventListener('keyup', aplicarFiltros);
                
                // Inicializar filtro de estado
                const selectEstado = document.getElementById('filtro-estado');
                selectEstado.addEventListener('change', aplicarFiltros);
            });
        </script>
        
        <?php endif; ?>
        <!-- ========== FIN SECCIÓN PROYECTOS ========== -->
        
        <!-- ========== SECCIÓN: CREAR PROYECTO ========== -->
        <?php if ($accion === 'crear_proyecto'): ?>
        
        <div class="max-w-3xl mx-auto">
            <div class="glass-card rounded-2xl p-8">
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-14 h-14 bg-gradient-to-br from-emerald-100 to-green-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-plus text-2xl text-emerald-600"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Crear Nuevo Proyecto</h2>
                        <p class="text-gray-600">Define los detalles del nuevo proyecto</p>
                    </div>
                </div>
                
                <form method="POST" class="space-y-6">
                    <input type="hidden" name="accion" value="crear_proyecto">
                    <?php if (isset($_SESSION['csrf_token'])): ?>
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    <?php endif; ?>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-800 mb-3">Nombre del Proyecto *</label>
                            <input type="text" name="nombre" required 
                                   class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                                   placeholder="Ej: Sistema de Gestión ERP">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-gray-800 mb-3">Estado *</label>
                            <select name="estado" required 
                                    class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                                <option value="planificacion">Planificación</option>
                                <option value="en_ejecucion">En ejecución</option>
                                <option value="en_pausa">En pausa</option>
                                <option value="completado">Completado</option>
                                <option value="cancelado">Cancelado</option>
                            </select>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-800 mb-3">Descripción</label>
                        <textarea name="descripcion" rows="3" 
                                  class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                                  placeholder="Describe los objetivos y alcance del proyecto..."></textarea>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-800 mb-3">Fecha Inicio *</label>
                            <input type="date" name="fecha_inicio" required 
                                   value="<?php echo date('Y-m-d'); ?>"
                                   class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-800 mb-3">Fecha Fin Estimada *</label>
                            <input type="date" name="fecha_fin_estimada" required 
                                   value="<?php echo date('Y-m-d', strtotime('+3 months')); ?>"
                                   class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-800 mb-3">Presupuesto Estimado *</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500">$</span>
                            <input type="number" name="presupuesto_estimado" step="0.01" min="0" required 
                                   class="form-input w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                                   placeholder="0.00">
                        </div>
                    </div>
                    
                    <div class="pt-6 border-t border-gray-200">
                        <div class="flex justify-end gap-4">
                            <a href="?accion=proyectos" class="px-8 py-3 border-2 border-gray-300 text-gray-700 hover:bg-gray-50 rounded-xl font-medium transition-all hover-lift">
                                Cancelar
                            </a>
                            <button type="submit" class="gradient-bg-planificacion hover:opacity-90 text-white px-8 py-3 rounded-xl font-medium shadow-md hover-lift flex items-center gap-2">
                                <i class="fas fa-check"></i>
                                Crear Proyecto
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <?php endif; ?>
        <!-- ========== FIN SECCIÓN CREAR PROYECTO ========== -->
        
        <!-- ========== SECCIÓN: EDITAR PROYECTO ========== -->
        <?php if ($accion === 'editar_proyecto'): ?>
        
        <div class="max-w-3xl mx-auto">
            <div class="glass-card rounded-2xl p-8">
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-14 h-14 bg-gradient-to-br from-emerald-100 to-green-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-edit text-2xl text-emerald-600"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Editar Proyecto</h2>
                        <p class="text-gray-600">Modifica los detalles del proyecto</p>
                    </div>
                </div>
                
                <?php if (empty($datos['proyecto'])): ?>
                <div class="text-center py-12">
                    <div class="w-20 h-20 mx-auto bg-gradient-to-br from-red-50 to-red-100 rounded-full flex items-center justify-center mb-6">
                        <i class="fas fa-exclamation-triangle text-3xl text-red-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-700 mb-3">Proyecto no encontrado</h3>
                    <p class="text-gray-500 mb-6">El proyecto que intentas editar no existe o ha sido eliminado</p>
                    <a href="?accion=proyectos" class="gradient-bg-planificacion hover:opacity-90 text-white px-6 py-3 rounded-xl font-medium inline-flex items-center gap-2 shadow-md hover-lift">
                        <i class="fas fa-arrow-left"></i>
                        Volver a Proyectos
                    </a>
                </div>
                <?php else: ?>
                <form method="POST" class="space-y-6">
                    <input type="hidden" name="accion" value="editar_proyecto">
                    <input type="hidden" name="id" value="<?php echo $datos['proyecto']['id']; ?>">
                    <?php if (isset($_SESSION['csrf_token'])): ?>
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    <?php endif; ?>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-800 mb-3">Nombre del Proyecto *</label>
                            <input type="text" name="nombre" required 
                                   value="<?php echo htmlspecialchars($datos['proyecto']['nombre']); ?>"
                                   class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-gray-800 mb-3">Estado *</label>
                            <select name="estado" required 
                                    class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                                <option value="planificacion" <?php echo $datos['proyecto']['estado'] === 'planificacion' ? 'selected' : ''; ?>>Planificación</option>
                                <option value="en_ejecucion" <?php echo $datos['proyecto']['estado'] === 'en_ejecucion' ? 'selected' : ''; ?>>En ejecución</option>
                                <option value="en_pausa" <?php echo $datos['proyecto']['estado'] === 'en_pausa' ? 'selected' : ''; ?>>En pausa</option>
                                <option value="completado" <?php echo $datos['proyecto']['estado'] === 'completado' ? 'selected' : ''; ?>>Completado</option>
                                <option value="cancelado" <?php echo $datos['proyecto']['estado'] === 'cancelado' ? 'selected' : ''; ?>>Cancelado</option>
                            </select>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-800 mb-3">Descripción</label>
                        <textarea name="descripcion" rows="3" 
                                  class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent"><?php echo htmlspecialchars($datos['proyecto']['descripcion']); ?></textarea>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-800 mb-3">Fecha Inicio *</label>
                            <input type="date" name="fecha_inicio" required 
                                   value="<?php echo $datos['proyecto']['fecha_inicio']; ?>"
                                   class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-800 mb-3">Fecha Fin Estimada *</label>
                            <input type="date" name="fecha_fin_estimada" required 
                                   value="<?php echo $datos['proyecto']['fecha_fin_estimada']; ?>"
                                   class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-800 mb-3">Presupuesto Estimado *</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500">$</span>
                            <input type="number" name="presupuesto_estimado" step="0.01" min="0" required 
                                   value="<?php echo $datos['proyecto']['presupuesto_estimado']; ?>"
                                   class="form-input w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                        </div>
                    </div>
                    
                    <div class="pt-6 border-t border-gray-200">
                        <div class="flex justify-between">
                            <a href="?accion=proyectos" class="px-8 py-3 border-2 border-gray-300 text-gray-700 hover:bg-gray-50 rounded-xl font-medium transition-all hover-lift">
                                Cancelar
                            </a>
                            <div class="flex gap-4">
                                <button type="submit" class="gradient-bg-planificacion hover:opacity-90 text-white px-8 py-3 rounded-xl font-medium shadow-md hover-lift flex items-center gap-2">
                                    <i class="fas fa-save"></i>
                                    Guardar Cambios
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
                <?php endif; ?>
            </div>
        </div>
        
        <?php endif; ?>
        <!-- ========== FIN SECCIÓN EDITAR PROYECTO ========== -->
        
        <!-- ========== SECCIÓN: RECURSOS ========== -->
        <?php if ($accion === 'recursos'): ?>
        
        <div class="glass-card rounded-2xl p-8 mb-8">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Planificación de Recursos</h2>
                    <p class="text-gray-600">Asigna y gestiona recursos para los proyectos</p>
                </div>
                <a href="?accion=crear_recurso" 
                   class="gradient-bg-planificacion hover:opacity-90 text-white px-5 py-2.5 rounded-xl font-medium flex items-center gap-2 hover-lift shadow-lg">
                    <i class="fas fa-plus"></i>
                    Nuevo Recurso
                </a>
            </div>
            
            <!-- Filtro por proyecto -->
            <div class="mb-8 bg-gradient-to-r from-emerald-50 to-green-50 rounded-2xl p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Filtrar por Proyecto</h3>
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center shadow-sm">
                        <i class="fas fa-filter text-emerald-600"></i>
                    </div>
                    <div class="flex-1">
                        <form method="GET" class="flex items-center gap-4">
                            <input type="hidden" name="accion" value="recursos">
                            <select name="proyecto_id" onchange="this.form.submit()" 
                                    class="form-input flex-1 px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                                <option value="">Todos los proyectos</option>
                                <?php if (isset($datos['proyectos']) && is_array($datos['proyectos'])): ?>
                                    <?php foreach($datos['proyectos'] as $proyecto): ?>
                                    <option value="<?php echo $proyecto['id']; ?>" 
                                            <?php echo (isset($datos['proyecto_actual']) && $datos['proyecto_actual'] == $proyecto['id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($proyecto['nombre']); ?>
                                    </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </form>
                    </div>
                </div>
            </div>
            
            <?php if (empty($datos['planificaciones']) || !is_array($datos['planificaciones'])): ?>
            <div class="text-center py-16">
                <div class="w-24 h-24 mx-auto bg-gradient-to-br from-emerald-50 to-green-50 rounded-full flex items-center justify-center mb-6">
                    <i class="fas fa-box-open text-4xl text-gray-400"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-700 mb-3">No hay recursos planificados</h3>
                <p class="text-gray-500 mb-6 max-w-md mx-auto">Comienza planificando recursos para optimizar la ejecución del proyecto</p>
                <?php if (isset($datos['proyecto_actual']) && $datos['proyecto_actual']): ?>
                <a href="?accion=crear_recurso&proyecto_id=<?php echo $datos['proyecto_actual']; ?>" 
                   class="gradient-bg-planificacion hover:opacity-90 text-white px-6 py-3 rounded-xl font-medium inline-flex items-center gap-2 shadow-lg hover-lift">
                    <i class="fas fa-plus"></i>
                    Planificar primer recurso
                </a>
                <?php else: ?>
                <a href="?accion=crear_recurso" 
                   class="gradient-bg-planificacion hover:opacity-90 text-white px-6 py-3 rounded-xl font-medium inline-flex items-center gap-2 shadow-lg hover-lift">
                    <i class="fas fa-plus"></i>
                    Planificar recurso
                </a>
                <?php endif; ?>
            </div>
            <?php else: ?>
            <!-- Estadísticas de recursos -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="stat-card bg-gradient-to-br from-blue-50 to-blue-100 rounded-2xl p-6 border border-blue-200">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center shadow-sm">
                            <i class="fas fa-boxes text-blue-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Total Recursos</p>
                            <p class="text-2xl font-bold text-gray-800"><?php echo count($datos['planificaciones']); ?></p>
                        </div>
                    </div>
                </div>
                
                <div class="stat-card bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-2xl p-6 border border-emerald-200">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center shadow-sm">
                            <i class="fas fa-money-bill-wave text-emerald-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Inversión Total</p>
                            <p class="text-2xl font-bold text-gray-800">
                                <?php 
                                $total_inversion = 0;
                                foreach ($datos['planificaciones'] as $recurso) {
                                    $total_inversion += $recurso['costo_total_estimado'] ?? 0;
                                }
                                echo '$' . number_format($total_inversion, 2);
                                ?>
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="stat-card bg-gradient-to-br from-red-50 to-red-100 rounded-2xl p-6 border border-red-200">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center shadow-sm">
                            <i class="fas fa-exclamation-triangle text-red-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Alta Prioridad</p>
                            <p class="text-2xl font-bold text-gray-800">
                                <?php 
                                $alta_prioridad = 0;
                                foreach ($datos['planificaciones'] as $recurso) {
                                    if (($recurso['prioridad'] ?? '') === 'alta') {
                                        $alta_prioridad++;
                                    }
                                }
                                echo $alta_prioridad;
                                ?>
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="stat-card bg-gradient-to-br from-purple-50 to-purple-100 rounded-2xl p-6 border border-purple-200">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center shadow-sm">
                            <i class="fas fa-layer-group text-purple-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Tipos Diferentes</p>
                            <p class="text-2xl font-bold text-gray-800">
                                <?php 
                                $tipos = [];
                                foreach ($datos['planificaciones'] as $recurso) {
                                    $tipo = $recurso['tipo_recurso'] ?? '';
                                    if ($tipo && !in_array($tipo, $tipos)) {
                                        $tipos[] = $tipo;
                                    }
                                }
                                echo count($tipos);
                                ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Tabla de recursos -->
            <div class="overflow-x-auto rounded-xl border border-gray-200">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gradient-to-r from-emerald-50 to-emerald-100">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 uppercase">Proyecto</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 uppercase">Tipo Recurso</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 uppercase">Descripción</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 uppercase">Cantidad / Costo</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 uppercase">Prioridad</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 uppercase">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        <?php foreach($datos['planificaciones'] as $recurso): ?>
                        <tr class="table-row-hover hover:bg-emerald-50/30">
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900"><?php echo htmlspecialchars($recurso['proyecto_nombre'] ?? 'Sin proyecto'); ?></div>
                            </td>
                            <td class="px-6 py-4">
                                <?php 
                                $tipo_colores = [
                                    'humano' => ['bg' => 'bg-blue-100 text-blue-800', 'icon' => 'fa-user'],
                                    'material' => ['bg' => 'bg-emerald-100 text-emerald-800', 'icon' => 'fa-box'],
                                    'equipo' => ['bg' => 'bg-purple-100 text-purple-800', 'icon' => 'fa-tools'],
                                    'financiero' => ['bg' => 'bg-amber-100 text-amber-800', 'icon' => 'fa-money-bill'],
                                    'tecnologico' => ['bg' => 'bg-rose-100 text-rose-800', 'icon' => 'fa-laptop']
                                ];
                                $tipo_recurso = $recurso['tipo_recurso'] ?? 'material';
                                $tipo = $tipo_colores[$tipo_recurso] ?? ['bg' => 'bg-gray-100 text-gray-800', 'icon' => 'fa-cube'];
                                ?>
                                <div class="status-badge <?php echo $tipo['bg']; ?>">
                                    <i class="fas <?php echo $tipo['icon']; ?> text-xs"></i>
                                    <?php echo ucfirst($tipo_recurso); ?>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900 mb-1"><?php echo htmlspecialchars($recurso['descripcion'] ?? 'Sin descripción'); ?></div>
                                <div class="text-sm text-gray-500">Fase: <?php echo htmlspecialchars($recurso['fase_proyecto'] ?? 'No especificada'); ?></div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="space-y-2">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Cantidad:</span>
                                        <span class="font-medium"><?php echo $recurso['cantidad_estimada'] ?? 0; ?> unidades</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Unitario:</span>
                                        <span class="font-medium">$<?php echo isset($recurso['costo_unitario_estimado']) ? number_format($recurso['costo_unitario_estimado'], 2) : '0.00'; ?></span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Total:</span>
                                        <span class="font-medium text-emerald-600">$<?php echo isset($recurso['costo_total_estimado']) ? number_format($recurso['costo_total_estimado'], 2) : '0.00'; ?></span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <?php 
                                $prioridad_colores = [
                                    'alta' => ['bg' => 'bg-red-100 text-red-800', 'icon' => 'fa-exclamation-triangle'],
                                    'media' => ['bg' => 'bg-yellow-100 text-yellow-800', 'icon' => 'fa-exclamation-circle'],
                                    'baja' => ['bg' => 'bg-green-100 text-green-800', 'icon' => 'fa-check-circle']
                                ];
                                $prioridad = $recurso['prioridad'] ?? 'media';
                                $prioridad_estilo = $prioridad_colores[$prioridad] ?? $prioridad_colores['media'];
                                ?>
                                <div class="status-badge <?php echo $prioridad_estilo['bg']; ?>">
                                    <i class="fas <?php echo $prioridad_estilo['icon']; ?> text-xs"></i>
                                    <?php echo ucfirst($prioridad); ?>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <a href="?accion=editar_recurso&id=<?php echo $recurso['id']; ?>" 
                                       class="w-10 h-10 bg-emerald-50 hover:bg-emerald-100 text-emerald-600 rounded-xl flex items-center justify-center hover-lift"
                                       title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" action="" 
                                          onsubmit="return confirm('¿Estás seguro de eliminar este recurso?');">
                                        <input type="hidden" name="accion" value="eliminar_recurso">
                                        <input type="hidden" name="id" value="<?php echo $recurso['id']; ?>">
                                        <?php if (isset($_SESSION['csrf_token'])): ?>
                                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                        <?php endif; ?>
                                        <button type="submit" 
                                                class="w-10 h-10 bg-red-50 hover:bg-red-100 text-red-600 rounded-xl flex items-center justify-center hover-lift"
                                                title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
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
        <!-- ========== FIN SECCIÓN RECURSOS ========== -->
        
        <!-- ========== SECCIÓN: CREAR RECURSO ========== -->
        <?php if ($accion === 'crear_recurso'): ?>
        
        <div class="max-w-3xl mx-auto">
            <div class="glass-card rounded-2xl p-8">
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-14 h-14 bg-gradient-to-br from-green-100 to-blue-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-users-cog text-2xl text-green-600"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Planificar Nuevo Recurso</h2>
                        <p class="text-gray-600">Define los detalles del recurso para el proyecto</p>
                    </div>
                </div>
                
                <form method="POST" class="space-y-6">
                    <input type="hidden" name="accion" value="crear_recurso">
                    <?php if (isset($_SESSION['csrf_token'])): ?>
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    <?php endif; ?>
                    
                    <!-- Paso 1: Proyecto y tipo -->
                    <div class="bg-gradient-to-r from-emerald-50 to-green-50 rounded-2xl p-6 mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Paso 1: Proyecto y Tipo</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-3">Proyecto *</label>
                                <select name="proyecto_id" required 
                                        class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                                    <option value="">Seleccionar proyecto</option>
                                    <?php if (isset($datos['proyectos']) && is_array($datos['proyectos'])): ?>
                                        <?php foreach($datos['proyectos'] as $proyecto): ?>
                                        <option value="<?php echo htmlspecialchars($proyecto['id']); ?>" 
                                                <?php echo (isset($_GET['proyecto_id']) && $_GET['proyecto_id'] == $proyecto['id']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($proyecto['nombre']); ?>
                                        </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-3">Tipo de Recurso *</label>
                                <select name="tipo_recurso" required 
                                        class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                                    <option value="humano">Humano</option>
                                    <option value="material">Material</option>
                                    <option value="equipo">Equipo</option>
                                    <option value="financiero">Financiero</option>
                                    <option value="tecnologico">Tecnológico</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Paso 2: Descripción -->
                    <div class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-2xl p-6 mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Paso 2: Descripción</h3>
                        
                        <div>
                            <label class="block text-sm font-semibold text-gray-800 mb-3">Descripción *</label>
                            <textarea name="descripcion" rows="3" required 
                                      class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                                      placeholder="Describe el recurso, sus características y función..."></textarea>
                        </div>
                        
                        <div class="mt-6">
                            <label class="block text-sm font-semibold text-gray-800 mb-3">Fase del Proyecto</label>
                            <input type="text" name="fase_proyecto" 
                                   class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                                   placeholder="Ej: Inicio, Planificación, Ejecución...">
                        </div>
                    </div>
                    
                    <!-- Paso 3: Cantidad y costo -->
                    <div class="bg-gradient-to-r from-amber-50 to-orange-50 rounded-2xl p-6 mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Paso 3: Cantidad y Costo</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-3">Cantidad Estimada *</label>
                                <input type="number" name="cantidad_estimada" min="1" required 
                                       class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                                       placeholder="Ej: 10"
                                       oninput="calcularCostoTotal()">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-3">Costo Unitario Estimado *</label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500">$</span>
                                    <input type="number" name="costo_unitario_estimado" step="0.01" min="0" required 
                                           class="form-input w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                                           placeholder="0.00"
                                           oninput="calcularCostoTotal()">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Resultado cálculo -->
                        <div class="mt-6 bg-white p-4 rounded-xl border-2 border-emerald-200">
                            <div class="flex justify-between items-center">
                                <div>
                                    <div class="text-sm text-gray-500">Costo Total Estimado</div>
                                    <div class="text-2xl font-bold text-emerald-600" id="costo-total-estimado">$0.00</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Paso 4: Prioridad -->
                    <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-2xl p-6 mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Paso 4: Prioridad</h3>
                        
                        <div>
                            <label class="block text-sm font-semibold text-gray-800 mb-3">Prioridad *</label>
                            <div class="flex gap-3">
                                <?php 
                                $prioridades = [
                                    'alta' => ['color' => 'bg-red-100 border-red-300 text-red-800', 'icon' => 'fa-exclamation-triangle'],
                                    'media' => ['color' => 'bg-yellow-100 border-yellow-300 text-yellow-800', 'icon' => 'fa-exclamation-circle'],
                                    'baja' => ['color' => 'bg-green-100 border-green-300 text-green-800', 'icon' => 'fa-check-circle']
                                ];
                                foreach ($prioridades as $valor => $estilo): ?>
                                <label class="radio-card flex-1 p-4 border-2 rounded-xl <?php echo $estilo['color']; ?> 
                                       <?php echo (isset($datos_form['prioridad']) && $datos_form['prioridad'] == $valor) ? 'selected' : ''; ?>">
                                    <input type="radio" name="prioridad" value="<?php echo $valor; ?>" 
                                           class="hidden" 
                                           <?php echo (isset($datos_form['prioridad']) && $datos_form['prioridad'] == $valor) ? 'checked' : ''; ?>
                                           <?php echo $valor === 'media' ? 'checked' : ''; ?>
                                           required>
                                    <div class="text-center">
                                        <i class="fas <?php echo $estilo['icon']; ?> text-lg mb-2"></i>
                                        <div class="font-medium"><?php echo ucfirst($valor); ?></div>
                                    </div>
                                </label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Botones de acción -->
                    <div class="pt-6 border-t border-gray-200">
                        <div class="flex justify-end gap-4">
                            <a href="?accion=recursos" class="px-8 py-3 border-2 border-gray-300 text-gray-700 hover:bg-gray-50 rounded-xl font-medium transition-all hover-lift">
                                Cancelar
                            </a>
                            <button type="submit" class="gradient-bg-planificacion hover:opacity-90 text-white px-8 py-3 rounded-xl font-medium shadow-md hover-lift flex items-center gap-2">
                                <i class="fas fa-check"></i>
                                Planificar Recurso
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <script>
            function calcularCostoTotal() {
                const cantidad = parseFloat(document.querySelector('input[name="cantidad_estimada"]').value) || 0;
                const costoUnitario = parseFloat(document.querySelector('input[name="costo_unitario_estimado"]').value) || 0;
                const costoTotal = cantidad * costoUnitario;
                
                document.getElementById('costo-total-estimado').textContent = '$' + costoTotal.toFixed(2);
            }
            
            // Inicializar
            document.addEventListener('DOMContentLoaded', function() {
                calcularCostoTotal();
                
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
            });
        </script>
        
        <?php endif; ?>
        <!-- ========== FIN SECCIÓN CREAR RECURSO ========== -->
        
        <!-- ========== SECCIÓN: EDITAR RECURSO ========== -->
        <?php if ($accion === 'editar_recurso'): ?>
        
        <div class="max-w-3xl mx-auto">
            <div class="glass-card rounded-2xl p-8">
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-14 h-14 bg-gradient-to-br from-green-100 to-blue-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-edit text-2xl text-green-600"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Editar Recurso</h2>
                        <p class="text-gray-600">Modifica los detalles del recurso planificado</p>
                    </div>
                </div>
                
                <?php if (empty($datos['recurso'])): ?>
                <div class="text-center py-12">
                    <div class="w-20 h-20 mx-auto bg-gradient-to-br from-red-50 to-red-100 rounded-full flex items-center justify-center mb-6">
                        <i class="fas fa-exclamation-triangle text-3xl text-red-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-700 mb-3">Recurso no encontrado</h3>
                    <p class="text-gray-500 mb-6">El recurso que intentas editar no existe o ha sido eliminado</p>
                    <a href="?accion=recursos" class="gradient-bg-planificacion hover:opacity-90 text-white px-6 py-3 rounded-xl font-medium inline-flex items-center gap-2 shadow-md hover-lift">
                        <i class="fas fa-arrow-left"></i>
                        Volver a Recursos
                    </a>
                </div>
                <?php else: ?>
                <form method="POST" class="space-y-6">
                    <input type="hidden" name="accion" value="editar_recurso">
                    <input type="hidden" name="id" value="<?php echo $datos['recurso']['id']; ?>">
                    <?php if (isset($_SESSION['csrf_token'])): ?>
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    <?php endif; ?>
                    
                    <!-- Información básica -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-800 mb-3">Proyecto *</label>
                            <select name="proyecto_id" required 
                                    class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                                <option value="">Seleccionar proyecto</option>
                                <?php if (isset($datos['proyectos']) && is_array($datos['proyectos'])): ?>
                                    <?php foreach($datos['proyectos'] as $proyecto): ?>
                                    <option value="<?php echo htmlspecialchars($proyecto['id']); ?>" 
                                            <?php echo ($datos['recurso']['proyecto_id'] == $proyecto['id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($proyecto['nombre']); ?>
                                    </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-gray-800 mb-3">Tipo de Recurso *</label>
                            <select name="tipo_recurso" required 
                                    class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                                <option value="humano" <?php echo $datos['recurso']['tipo_recurso'] === 'humano' ? 'selected' : ''; ?>>Humano</option>
                                <option value="material" <?php echo $datos['recurso']['tipo_recurso'] === 'material' ? 'selected' : ''; ?>>Material</option>
                                <option value="equipo" <?php echo $datos['recurso']['tipo_recurso'] === 'equipo' ? 'selected' : ''; ?>>Equipo</option>
                                <option value="financiero" <?php echo $datos['recurso']['tipo_recurso'] === 'financiero' ? 'selected' : ''; ?>>Financiero</option>
                                <option value="tecnologico" <?php echo $datos['recurso']['tipo_recurso'] === 'tecnologico' ? 'selected' : ''; ?>>Tecnológico</option>
                            </select>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-800 mb-3">Descripción *</label>
                        <textarea name="descripcion" rows="3" required 
                                  class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent"><?php echo htmlspecialchars($datos['recurso']['descripcion']); ?></textarea>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-800 mb-3">Cantidad Estimada *</label>
                            <input type="number" name="cantidad_estimada" min="1" required 
                                   value="<?php echo $datos['recurso']['cantidad_estimada']; ?>"
                                   class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                                   oninput="calcularCostoTotalEditar()">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-800 mb-3">Costo Unitario Estimado *</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500">$</span>
                                <input type="number" name="costo_unitario_estimado" step="0.01" min="0" required 
                                       value="<?php echo $datos['recurso']['costo_unitario_estimado']; ?>"
                                       class="form-input w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                                       oninput="calcularCostoTotalEditar()">
                            </div>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-800 mb-3">Prioridad *</label>
                            <select name="prioridad" required 
                                    class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                                <option value="alta" <?php echo $datos['recurso']['prioridad'] === 'alta' ? 'selected' : ''; ?>>Alta</option>
                                <option value="media" <?php echo $datos['recurso']['prioridad'] === 'media' ? 'selected' : ''; ?>>Media</option>
                                <option value="baja" <?php echo $datos['recurso']['prioridad'] === 'baja' ? 'selected' : ''; ?>>Baja</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-800 mb-3">Fase del Proyecto</label>
                            <input type="text" name="fase_proyecto" 
                                   value="<?php echo htmlspecialchars($datos['recurso']['fase_proyecto']); ?>"
                                   class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                        </div>
                    </div>
                    
                    <!-- Costo total -->
                    <div class="bg-gradient-to-r from-emerald-50 to-green-50 rounded-2xl p-6">
                        <h4 class="font-semibold text-gray-800 mb-3">Información Calculada</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <div class="text-sm text-gray-500">Costo Total Actual</div>
                                <div class="text-xl font-bold text-gray-900">$<?php echo number_format($datos['recurso']['costo_total_estimado'], 2); ?></div>
                            </div>
                            <div>
                                <div class="text-sm text-gray-500">Costo Total Nuevo</div>
                                <div class="text-xl font-bold text-emerald-600">
                                    $<span id="costo-total-nuevo"><?php echo number_format($datos['recurso']['costo_total_estimado'], 2); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="pt-6 border-t border-gray-200">
                        <div class="flex justify-between">
                            <a href="?accion=recursos" class="px-8 py-3 border-2 border-gray-300 text-gray-700 hover:bg-gray-50 rounded-xl font-medium transition-all hover-lift">
                                Cancelar
                            </a>
                            <div class="flex gap-4">
                                <button type="submit" class="gradient-bg-planificacion hover:opacity-90 text-white px-8 py-3 rounded-xl font-medium shadow-md hover-lift flex items-center gap-2">
                                    <i class="fas fa-save"></i>
                                    Guardar Cambios
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
                
                <script>
                    function calcularCostoTotalEditar() {
                        const cantidad = parseFloat(document.querySelector('input[name="cantidad_estimada"]').value) || 0;
                        const costoUnitario = parseFloat(document.querySelector('input[name="costo_unitario_estimado"]').value) || 0;
                        const costoTotal = cantidad * costoUnitario;
                        document.getElementById('costo-total-nuevo').textContent = costoTotal.toFixed(2);
                    }
                    
                    document.addEventListener('DOMContentLoaded', function() {
                        calcularCostoTotalEditar();
                    });
                </script>
                <?php endif; ?>
            </div>
        </div>
        
        <?php endif; ?>
        <!-- ========== FIN SECCIÓN EDITAR RECURSO ========== -->
        
        <!-- ========== SECCIÓN: REPORTES ========== -->
        <?php if ($accion === 'reportes'): ?>
        
        <div class="glass-card rounded-2xl p-8 mb-8">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Reportes de Planificación</h2>
                    <p class="text-gray-600">Análisis y métricas de recursos por proyecto</p>
                </div>
                <div class="flex gap-3">
                    <button onclick="window.print()" 
                            class="px-5 py-2.5 bg-gray-800 hover:bg-gray-900 text-white rounded-xl font-medium shadow-md hover-lift flex items-center gap-2">
                        <i class="fas fa-print"></i>
                        Imprimir
                    </button>
                    <a href="?accion=dashboard" 
                       class="gradient-bg-planificacion hover:opacity-90 text-white px-5 py-2.5 rounded-xl font-medium flex items-center gap-2 hover-lift">
                        <i class="fas fa-home"></i>
                        Dashboard
                    </a>
                </div>
            </div>
            
            <!-- Filtros para reportes -->
            <div class="mb-8 bg-gradient-to-r from-amber-50 to-orange-50 rounded-2xl p-6">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center shadow-sm">
                        <i class="fas fa-chart-pie text-amber-600"></i>
                    </div>
                    <div class="flex-1">
                        <label class="block text-sm font-semibold text-gray-800 mb-2">Seleccionar proyecto para análisis</label>
                        <form method="GET" class="flex items-center gap-4">
                            <input type="hidden" name="accion" value="reportes">
                            <select name="proyecto_id" onchange="this.form.submit()" 
                                    class="form-input flex-1 px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                                <option value="">Todos los proyectos</option>
                                <?php if (isset($datos['proyectos']) && is_array($datos['proyectos'])): ?>
                                    <?php foreach($datos['proyectos'] as $proyecto): ?>
                                    <option value="<?php echo htmlspecialchars($proyecto['id']); ?>" 
                                        <?php echo (isset($datos['proyecto_actual']) && $datos['proyecto_actual'] == $proyecto['id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($proyecto['nombre']); ?>
                                    </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </form>
                    </div>
                </div>
            </div>
            
            <?php if (!isset($datos['proyecto_actual']) || empty($datos['proyecto_actual'])): ?>
            <div class="text-center py-16">
                <div class="w-24 h-24 mx-auto bg-gradient-to-br from-amber-50 to-orange-50 rounded-full flex items-center justify-center mb-6">
                    <i class="fas fa-chart-bar text-4xl text-gray-400"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-700 mb-3">Selecciona un proyecto</h3>
                <p class="text-gray-500 mb-6 max-w-md mx-auto">Elige un proyecto del menú superior para visualizar los reportes detallados</p>
            </div>
            <?php else: ?>
            <!-- Estadísticas generales -->
            <div class="mb-10">
                <h3 class="text-xl font-semibold text-gray-900 mb-6">Resumen de Planificación</h3>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div class="stat-card bg-gradient-to-br from-blue-50 to-blue-100 rounded-2xl p-6 border border-blue-200">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center shadow-sm">
                                <i class="fas fa-boxes text-blue-600"></i>
                            </div>
                            <div>
                                <p class="text-2xl font-bold text-gray-900"><?php echo $datos['resumen']['total_recursos'] ?? 0; ?></p>
                                <p class="text-sm font-semibold text-blue-700">Total Recursos</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="stat-card bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-2xl p-6 border border-emerald-200">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center shadow-sm">
                                <i class="fas fa-money-bill-wave text-emerald-600"></i>
                            </div>
                            <div>
                                <p class="text-2xl font-bold text-gray-900">$<?php echo isset($datos['resumen']['presupuesto_total']) ? number_format($datos['resumen']['presupuesto_total'], 2) : '0.00'; ?></p>
                                <p class="text-sm font-semibold text-emerald-700">Presupuesto Total</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="stat-card bg-gradient-to-br from-red-50 to-red-100 rounded-2xl p-6 border border-red-200">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center shadow-sm">
                                <i class="fas fa-exclamation-triangle text-red-600"></i>
                            </div>
                            <div>
                                <p class="text-2xl font-bold text-gray-900"><?php echo $datos['resumen']['alta_prioridad'] ?? 0; ?></p>
                                <p class="text-sm font-semibold text-red-700">Alta Prioridad</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="stat-card bg-gradient-to-br from-purple-50 to-purple-100 rounded-2xl p-6 border border-purple-200">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center shadow-sm">
                                <i class="fas fa-layer-group text-purple-600"></i>
                            </div>
                            <div>
                                <p class="text-2xl font-bold text-gray-900"><?php echo isset($datos['recursos_tipo']) ? count($datos['recursos_tipo']) : 0; ?></p>
                                <p class="text-sm font-semibold text-purple-700">Tipos de Recursos</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Distribución por tipo de recurso -->
            <?php if (isset($datos['recursos_tipo']) && is_array($datos['recursos_tipo'])): ?>
            <div class="mb-10">
                <h3 class="text-xl font-semibold text-gray-900 mb-6">Distribución por Tipo de Recurso</h3>
                <div class="overflow-hidden rounded-2xl border border-gray-200 shadow-sm">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gradient-to-r from-emerald-50 to-green-100">
                            <tr>
                                <th class="px-8 py-4 text-left text-sm font-semibold text-gray-700 uppercase">Tipo de Recurso</th>
                                <th class="px-8 py-4 text-left text-sm font-semibold text-gray-700 uppercase">Cantidad</th>
                                <th class="px-8 py-4 text-left text-sm font-semibold text-gray-700 uppercase">Costo Total</th>
                                <th class="px-8 py-4 text-left text-sm font-semibold text-gray-700 uppercase">Porcentaje</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            <?php 
                            $total_presupuesto = $datos['resumen']['presupuesto_total'] ?? 1;
                            foreach($datos['recursos_tipo'] as $tipo): 
                                $porcentaje = $total_presupuesto > 0 ? ($tipo['costo_total'] / $total_presupuesto) * 100 : 0;
                                $color_class = [
                                    'humano' => 'bg-blue-100 text-blue-800',
                                    'material' => 'bg-emerald-100 text-emerald-800',
                                    'equipo' => 'bg-purple-100 text-purple-800',
                                    'financiero' => 'bg-amber-100 text-amber-800',
                                    'tecnologico' => 'bg-rose-100 text-rose-800'
                                ][$tipo['tipo_recurso']] ?? 'bg-gray-100 text-gray-800';
                            ?>
                            <tr class="table-row-hover">
                                <td class="px-8 py-5">
                                    <div class="status-badge <?php echo $color_class; ?>">
                                        <?php echo ucfirst($tipo['tipo_recurso']); ?>
                                    </div>
                                </td>
                                <td class="px-8 py-5">
                                    <div class="text-2xl font-bold text-gray-900"><?php echo $tipo['cantidad'] ?? 0; ?></div>
                                </td>
                                <td class="px-8 py-5">
                                    <div class="font-bold text-gray-900 text-lg">$<?php echo isset($tipo['costo_total']) ? number_format($tipo['costo_total'], 2) : '0.00'; ?></div>
                                </td>
                                <td class="px-8 py-5">
                                    <div class="space-y-2">
                                        <div class="flex justify-between text-sm">
                                            <span class="font-medium text-gray-700"><?php echo number_format($porcentaje, 1); ?>%</span>
                                            <span class="text-gray-500">del total</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                                            <div class="h-2.5 rounded-full bg-gradient-to-r from-emerald-500 to-green-600" 
                                                 style="width: <?php echo $porcentaje; ?>%"></div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php endif; ?>
            <?php endif; ?>
        </div>
        
        <?php endif; ?>
        <!-- ========== FIN SECCIÓN REPORTES ========== -->
        
        <!-- ========== INFORMACIÓN DEL PROCESO PMBOK ========== -->
        <div class="mt-8 glass-card rounded-2xl p-6">
            <div class="flex items-start gap-4">
                <div class="w-14 h-14 bg-gradient-to-r from-emerald-100 to-green-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-info-circle text-emerald-600 text-xl"></i>
                </div>
                <div class="flex-1">
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Proceso 1: Planificar la Gestión de Recursos</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-gradient-to-r from-emerald-50 to-emerald-100 p-4 rounded-xl">
                            <h4 class="font-semibold text-emerald-800 mb-2">Objetivo</h4>
                            <p class="text-sm text-emerald-700">Definir cómo estimar, adquirir, gestionar y utilizar los recursos físicos y del equipo.</p>
                        </div>
                        <div class="bg-gradient-to-r from-blue-50 to-blue-100 p-4 rounded-xl">
                            <h4 class="font-semibold text-blue-800 mb-2">Herramientas</h4>
                            <ul class="text-sm text-blue-700 list-disc pl-5">
                                <li>Juicio de expertos</li>
                                <li>Análisis de datos</li>
                                <li>Reuniones</li>
                                <li>Plantillas</li>
                            </ul>
                        </div>
                        <div class="bg-gradient-to-r from-purple-50 to-purple-100 p-4 rounded-xl">
                            <h4 class="font-semibold text-purple-800 mb-2">Salidas</h4>
                            <ul class="text-sm text-purple-700 list-disc pl-5">
                                <li>Plan de gestión de recursos</li>
                                <li>Matriz de asignación</li>
                                <li>Cronograma de recursos</li>
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
            // Efecto hover
            const cards = document.querySelectorAll('.hover-lift');
            cards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transition = 'all 0.3s ease';
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
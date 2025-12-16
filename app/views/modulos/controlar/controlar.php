<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Controlar Recursos | PMBOK 6</title>
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
        
        .gradient-bg-proceso6 {
            background: linear-gradient(135deg, #f687b3 0%, #ed64a6 100%);
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
        
        .badge-humano { background-color: #bee3f8; color: #2c5282; }
        .badge-material { background-color: #fed7d7; color: #9b2c2c; }
        .badge-equipo { background-color: #c6f6d5; color: #276749; }
        .badge-financiero { background-color: #fbd38d; color: #c05621; }
        .badge-tecnologico { background-color: #e9d8fd; color: #553c9a; }
        
        .badge-favorable { background-color: #c6f6d5; color: #276749; }
        .badge-desfavorable { background-color: #fed7d7; color: #9b2c2c; }
        .badge-neutral { background-color: #e2e8f0; color: #4a5568; }
        
        .tab-control {
            padding: 0.75rem 1.5rem;
            border-radius: 0.75rem;
            font-weight: 500;
            transition: all 0.2s ease;
        }
        
        .tab-control.active {
            background: linear-gradient(135deg, #f687b3 0%, #ed64a6 100%);
            color: white;
            box-shadow: 0 4px 20px rgba(246, 135, 179, 0.3);
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
            border-color: #f687b3;
            box-shadow: 0 0 0 3px rgba(246, 135, 179, 0.1);
        }
        
        .table-row-hover:hover {
            background-color: rgba(246, 135, 179, 0.05);
        }
        
        .badge-periodo-semanal { background-color: #bee3f8; color: #2c5282; }
        .badge-periodo-mensual { background-color: #c6f6d5; color: #276749; }
        .badge-periodo-trimestral { background-color: #fbd38d; color: #c05621; }
        .badge-periodo-anual { background-color: #e9d8fd; color: #553c9a; }
        
        .variation-indicator {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-right: 0.5rem;
        }
        
        .variation-up {
            background-color: #fed7d7;
            color: #9b2c2c;
        }
        
        .variation-down {
            background-color: #c6f6d5;
            color: #276749;
        }
        
        .variation-neutral {
            background-color: #e2e8f0;
            color: #4a5568;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-pink-50">
    
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
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2">Controlar Recursos</h1>
                <p class="text-gray-600">Proceso 6 PMBOK 6 | Monitoreo y control del uso de recursos</p>
            </div>
            <div class="flex items-center gap-3 bg-white rounded-xl px-4 py-3 shadow-sm border border-gray-200">
                <div class="w-10 h-10 bg-gradient-to-r from-pink-500 to-rose-600 rounded-full flex items-center justify-center text-white font-bold">
                    <?php echo isset($_SESSION['usuario']) ? strtoupper(substr($_SESSION['usuario']['nombre'], 0, 1)) : 'C'; ?>
                </div>
                <div>
                    <div class="text-sm text-gray-500"><?php echo htmlspecialchars($usuario_rol ?? 'Usuario'); ?></div>
                    <div class="font-medium text-gray-800"><?php echo htmlspecialchars($usuario_nombre ?? 'Usuario'); ?></div>
                </div>
            </div>
        </div>
        
        <!-- ========== INDICADORES DE PROCESO PMBOK ========== -->
        <div class="grid grid-cols-2 md:grid-cols-6 gap-4 mb-8">
            <?php for($i = 1; $i <= 5; $i++): ?>
            <div class="bg-white p-4 rounded-xl border border-gray-200 opacity-70 hover-lift">
                <div class="text-sm text-gray-400">Proceso <?php echo $i; ?></div>
                <div class="text-lg font-semibold text-gray-400">
                    <?php 
                    $nombres = [1 => 'Planificar', 2 => 'Estimar', 3 => 'Adquirir', 4 => 'Desarrollar', 5 => 'Dirigir'];
                    echo $nombres[$i];
                    ?>
                </div>
                <div class="mt-2 text-xs text-green-600 flex items-center">
                    <i class="fas fa-check-circle mr-1"></i> Completado
                </div>
            </div>
            <?php endfor; ?>
            
            <div class="gradient-bg-proceso6 p-4 rounded-xl text-white hover-lift shadow-lg">
                <div class="text-sm font-medium">Proceso 6</div>
                <div class="text-lg font-semibold">Controlar</div>
                <div class="mt-2 text-xs flex items-center">
                    <i class="fas fa-chart-line mr-1"></i> En Progreso
                </div>
            </div>
        </div>
        
        <!-- ========== NAVEGACIÓN INTERNA DEL PROCESO 6 ========== -->
        <div class="flex flex-wrap gap-2 md:gap-3 mb-8 bg-white rounded-2xl p-2 shadow-sm border border-gray-200">
            <a href="?accion=dashboard" 
               class="tab-control <?php echo ($accion === 'dashboard' || !isset($accion)) ? 'active' : ''; ?> hover-lift">
                <i class="fas fa-tachometer-alt mr-2"></i>
                <span class="hidden md:inline">Dashboard</span>
                <span class="md:hidden">Dash</span>
            </a>
            <a href="?accion=controles" 
               class="tab-control <?php echo $accion === 'controles' ? 'active' : ''; ?> hover-lift">
                <i class="fas fa-clipboard-check mr-2"></i>
                <span class="hidden md:inline">Controles</span>
                <span class="md:hidden">Ctrl</span>
            </a>
            <a href="?accion=reportes_rendimiento" 
               class="tab-control <?php echo $accion === 'reportes_rendimiento' ? 'active' : ''; ?> hover-lift">
                <i class="fas fa-chart-bar mr-2"></i>
                <span class="hidden md:inline">Reportes</span>
                <span class="md:hidden">Rep</span>
            </a>
            <a href="?accion=analisis" 
               class="tab-control <?php echo $accion === 'analisis' ? 'active' : ''; ?> hover-lift">
                <i class="fas fa-chart-line mr-2"></i>
                <span class="hidden md:inline">Análisis</span>
                <span class="md:hidden">Anal</span>
            </a>
            <?php if (($usuario_rol == 'gerente' || $usuario_rol == 'administrador') && ($accion == 'crear_control' || $accion == 'crear_reporte')): ?>
            <div class="ml-auto px-4 py-2 bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl">
                <span class="text-green-700 font-medium">
                    <i class="fas fa-plus-circle mr-2"></i>
                    <?php echo $accion == 'crear_control' ? 'Creando Control' : 'Creando Reporte'; ?>
                </span>
            </div>
            <?php endif; ?>
        </div>
        
        <!-- ========== SECCIÓN: DASHBOARD ========== -->
        <?php if ($accion === 'dashboard' || !isset($accion)): ?>

        <div class="glass-card rounded-2xl p-6">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Dashboard de Control</h2>
                    <p class="text-gray-600">Vista general del monitoreo y control de recursos</p>
                </div>
                <div class="flex gap-2">
                    <form method="GET" class="flex gap-2">
                        <input type="hidden" name="accion" value="dashboard">
                        <select name="proyecto_id" class="form-input" onchange="this.form.submit()">
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
            </div>
            
            <?php if (isset($dashboard) && is_array($dashboard) && !empty($dashboard) && !isset($dashboard['error'])): ?>
            
            <!-- Estadísticas principales -->
            <?php if (isset($dashboard['estadisticas'])): ?>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-gradient-to-r from-pink-50 to-pink-100 p-6 rounded-2xl hover-lift">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-gradient-to-r from-pink-500 to-rose-600 rounded-xl flex items-center justify-center">
                            <i class="fas fa-clipboard-list text-white text-xl"></i>
                        </div>
                        <div>
                            <div class="text-sm text-gray-600">Total Controles</div>
                            <div class="text-3xl font-bold text-gray-900"><?php echo $dashboard['estadisticas']['total_controles'] ?? 0; ?></div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gradient-to-r from-green-50 to-emerald-100 p-6 rounded-2xl hover-lift">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-600 rounded-xl flex items-center justify-center">
                            <i class="fas fa-arrow-down text-white text-xl"></i>
                        </div>
                        <div>
                            <div class="text-sm text-gray-600">Variación Favorable</div>
                            <div class="text-3xl font-bold text-green-600"><?php echo $dashboard['estadisticas']['variaciones_favorables'] ?? 0; ?></div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gradient-to-r from-red-50 to-rose-100 p-6 rounded-2xl hover-lift">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-gradient-to-r from-red-500 to-rose-600 rounded-xl flex items-center justify-center">
                            <i class="fas fa-arrow-up text-white text-xl"></i>
                        </div>
                        <div>
                            <div class="text-sm text-gray-600">Variación Desfavorable</div>
                            <div class="text-3xl font-bold text-red-600"><?php echo $dashboard['estadisticas']['variaciones_desfavorables'] ?? 0; ?></div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gradient-to-r from-blue-50 to-cyan-100 p-6 rounded-2xl hover-lift">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-cyan-600 rounded-xl flex items-center justify-center">
                            <i class="fas fa-percentage text-white text-xl"></i>
                        </div>
                        <div>
                            <div class="text-sm text-gray-600">Eficiencia</div>
                            <div class="text-3xl font-bold text-blue-600"><?php echo number_format($dashboard['estadisticas']['eficiencia_recursos_promedio'] ?? 0, 1); ?>%</div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Gráficos y métricas -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <!-- Distribución por tipo de recurso -->
                <div class="bg-white p-6 rounded-2xl border border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Controles por Tipo de Recurso</h3>
                    <div class="h-64">
                        <canvas id="dashboardTipoChart"></canvas>
                    </div>
                </div>
                
                <!-- Eficiencia y variación -->
                <div class="bg-white p-6 rounded-2xl border border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Métricas de Desempeño</h3>
                    <div class="space-y-6">
                        <div>
                            <div class="flex justify-between text-sm text-gray-600 mb-1">
                                <span>Eficiencia de Recursos</span>
                                <span class="font-bold <?php echo ($dashboard['estadisticas']['eficiencia_recursos_promedio'] ?? 0) >= 80 ? 'text-green-600' : (($dashboard['estadisticas']['eficiencia_recursos_promedio'] ?? 0) >= 60 ? 'text-yellow-600' : 'text-red-600'); ?>">
                                    <?php echo number_format($dashboard['estadisticas']['eficiencia_recursos_promedio'] ?? 0, 1); ?>%
                                </span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill bg-gradient-to-r from-green-400 to-emerald-500" 
                                    style="width: <?php echo min(100, max(0, $dashboard['estadisticas']['eficiencia_recursos_promedio'] ?? 0)); ?>%"></div>
                            </div>
                        </div>
                        
                        <div>
                            <div class="flex justify-between text-sm text-gray-600 mb-1">
                                <span>Cumplimiento de Plazos</span>
                                <span class="font-bold <?php echo ($dashboard['estadisticas']['cumplimiento_plazos_promedio'] ?? 0) >= 90 ? 'text-green-600' : (($dashboard['estadisticas']['cumplimiento_plazos_promedio'] ?? 0) >= 70 ? 'text-yellow-600' : 'text-red-600'); ?>">
                                    <?php echo number_format($dashboard['estadisticas']['cumplimiento_plazos_promedio'] ?? 0, 1); ?>%
                                </span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill bg-gradient-to-r from-blue-400 to-cyan-500" 
                                    style="width: <?php echo min(100, max(0, $dashboard['estadisticas']['cumplimiento_plazos_promedio'] ?? 0)); ?>%"></div>
                            </div>
                        </div>
                        
                        <div>
                            <div class="flex justify-between text-sm text-gray-600 mb-1">
                                <span>Variación Total</span>
                                <span class="font-bold <?php echo ($dashboard['estadisticas']['variacion_total'] ?? 0) < 0 ? 'text-green-600' : (($dashboard['estadisticas']['variacion_total'] ?? 0) == 0 ? 'text-gray-600' : 'text-red-600'); ?>">
                                    $<?php echo number_format($dashboard['estadisticas']['variacion_total'] ?? 0, 2); ?>
                                </span>
                            </div>
                            <div class="text-xs text-gray-500">
                                <?php 
                                $valor_total = $dashboard['estadisticas']['valor_planificado_total'] ?? 0;
                                if ($valor_total > 0) {
                                    $porcentaje = abs(($dashboard['estadisticas']['variacion_total'] ?? 0) / $valor_total * 100);
                                    echo number_format($porcentaje, 2) . '% vs planificado';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Alertas de desviación -->
            <?php if (isset($dashboard['alertas']) && !empty($dashboard['alertas'])): ?>
            <div class="bg-gradient-to-r from-orange-50 to-amber-50 p-6 rounded-2xl mb-8">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">Alertas de Desviación (>10%)</h3>
                    <span class="px-3 py-1 bg-gradient-to-r from-orange-100 to-amber-100 text-orange-700 rounded-full text-sm font-medium">
                        <?php echo count($dashboard['alertas']); ?> alertas
                    </span>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <?php foreach(array_slice($dashboard['alertas'], 0, 6) as $alerta): ?>
                    <div class="bg-white p-4 rounded-xl border border-orange-200 hover-lift">
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex-1">
                                <div class="font-medium text-gray-900 truncate"><?php echo htmlspecialchars(substr($alerta['metrica'] ?? 'Sin nombre', 0, 50)); ?></div>
                                <div class="text-sm text-gray-600 mt-1"><?php echo htmlspecialchars($alerta['proyecto_nombre'] ?? ''); ?></div>
                            </div>
                            <span class="status-badge <?php echo $alerta['tipo_variacion'] == 'favorable' ? 'badge-favorable' : 'badge-desfavorable'; ?> ml-2">
                                <?php echo number_format($alerta['porcentaje_desviacion'] ?? 0, 1); ?>%
                            </span>
                        </div>
                        <div class="text-sm text-gray-600 mb-2">
                            <span class="font-medium"><?php echo ucfirst($alerta['tipo_recurso'] ?? ''); ?></span>
                            • <?php echo isset($alerta['fecha_control']) ? date('d/m/Y', strtotime($alerta['fecha_control'])) : 'N/A'; ?>
                        </div>
                        <div class="flex justify-between items-center">
                            <div class="text-xs <?php echo $alerta['variacion'] < 0 ? 'text-green-600' : 'text-red-600'; ?>">
                                <?php echo $alerta['variacion'] < 0 ? 'Ahorro: $' : 'Sobrecosto: $'; ?><?php echo number_format(abs($alerta['variacion']), 2); ?>
                            </div>
                            <a href="?accion=ver_control&id=<?php echo $alerta['id']; ?>" 
                            class="text-pink-600 hover:text-pink-800 text-xs font-medium">
                                Ver control
                            </a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php if (count($dashboard['alertas']) > 6): ?>
                <div class="text-center mt-4">
                    <a href="?accion=controles" class="text-orange-600 hover:text-orange-800 text-sm font-medium">
                        Ver todas las alertas →
                    </a>
                </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>
            
            <!-- Controles recientes -->
            <?php if (isset($dashboard['controles_recientes']) && !empty($dashboard['controles_recientes'])): ?>
            <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden mb-8">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Controles Recientes</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Métrica / Proyecto</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Tipo / Fecha</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Valores</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Variación</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            <?php foreach(array_slice($dashboard['controles_recientes'], 0, 5) as $control): ?>
                            <tr class="table-row-hover">
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-gray-900 mb-1 truncate max-w-xs"><?php echo htmlspecialchars(substr($control['metrica'] ?? 'Sin métrica', 0, 40)); ?></div>
                                    <div class="text-sm text-gray-600 flex items-center">
                                        <i class="fas fa-project-diagram mr-1"></i>
                                        <?php echo htmlspecialchars($control['proyecto_nombre'] ?? 'Sin proyecto'); ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="space-y-1">
                                        <span class="status-badge <?php echo 'badge-' . ($control['tipo_recurso'] ?? 'humano'); ?>">
                                            <?php echo ucfirst($control['tipo_recurso'] ?? 'humano'); ?>
                                        </span>
                                        <div class="text-sm text-gray-500">
                                            <?php echo isset($control['fecha_control']) ? date('d/m/Y', strtotime($control['fecha_control'])) : 'N/A'; ?>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="space-y-1">
                                        <div class="text-sm">
                                            <span class="text-gray-500">Planificado:</span>
                                            <span class="font-medium text-gray-900">$<?php echo number_format($control['valor_planificado'] ?? 0, 2); ?></span>
                                        </div>
                                        <div class="text-sm">
                                            <span class="text-gray-500">Actual:</span>
                                            <span class="font-medium text-gray-900">$<?php echo number_format($control['valor_actual'] ?? 0, 2); ?></span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <?php 
                                        $variacion = $control['variacion'] ?? 0;
                                        $porcentaje = $control['valor_planificado'] != 0 ? abs($variacion / $control['valor_planificado'] * 100) : 0;
                                        ?>
                                        <div class="variation-indicator <?php echo $variacion < 0 ? 'variation-down' : ($variacion > 0 ? 'variation-up' : 'variation-neutral'); ?>">
                                            <i class="fas fa-arrow-<?php echo $variacion < 0 ? 'down' : ($variacion > 0 ? 'up' : 'right'); ?> text-xs"></i>
                                        </div>
                                        <div>
                                            <div class="font-medium <?php echo $variacion < 0 ? 'text-green-600' : ($variacion > 0 ? 'text-red-600' : 'text-gray-600'); ?>">
                                                $<?php echo number_format(abs($variacion), 2); ?>
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                <?php echo number_format($porcentaje, 1); ?>%
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col gap-2">
                                        <a href="?accion=ver_control&id=<?php echo $control['id']; ?>" 
                                        class="px-3 py-1.5 bg-gradient-to-r from-blue-50 to-blue-100 hover:from-blue-100 hover:to-blue-200 text-blue-600 rounded-lg text-sm font-medium flex items-center justify-center gap-1 hover-lift">
                                            <i class="fas fa-eye text-xs"></i>
                                            Ver
                                        </a>
                                        <?php if ($usuario_rol != 'miembro_equipo'): ?>
                                        <a href="?accion=editar_control&id=<?php echo $control['id']; ?>" 
                                        class="px-3 py-1.5 bg-gradient-to-r from-green-50 to-green-100 hover:from-green-100 hover:to-green-200 text-green-600 rounded-lg text-sm font-medium flex items-center justify-center gap-1 hover-lift">
                                            <i class="fas fa-edit text-xs"></i>
                                            Editar
                                        </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="p-4 border-t border-gray-200 text-center">
                    <a href="?accion=controles" class="text-pink-600 hover:text-pink-800 text-sm font-medium">
                        Ver todos los controles →
                    </a>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Resumen por proyecto -->
            <?php if (isset($dashboard['recursos_resumen']) && !empty($dashboard['recursos_resumen'])): ?>
            <div class="bg-gradient-to-r from-gray-50 to-gray-100 p-6 rounded-2xl">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">Resumen por Proyecto</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php foreach(array_slice($dashboard['recursos_resumen'], 0, 6) as $proyecto): ?>
                    <div class="bg-white p-4 rounded-xl border border-gray-200 hover-lift">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-indigo-600 rounded-full flex items-center justify-center text-white font-bold">
                                <?php echo isset($proyecto['proyecto_nombre']) ? strtoupper(substr($proyecto['proyecto_nombre'], 0, 1)) : 'P'; ?>
                            </div>
                            <div>
                                <div class="font-medium text-gray-900 truncate"><?php echo htmlspecialchars(substr($proyecto['proyecto_nombre'] ?? '', 0, 25)); ?></div>
                                <div class="text-sm text-gray-600"><?php echo $proyecto['total_recursos_planificados'] ?? 0; ?> recursos</div>
                            </div>
                        </div>
                        <div class="space-y-3">
                            <div>
                                <div class="text-xs text-gray-500 mb-1">Presupuesto Planificado</div>
                                <div class="text-lg font-bold text-gray-900">$<?php echo number_format($proyecto['presupuesto_planificado'] ?? 0, 2); ?></div>
                            </div>
                            <div>
                                <div class="text-xs text-gray-500 mb-1">Controles Realizados</div>
                                <div class="flex items-center gap-2">
                                    <div class="flex-1">
                                        <div class="progress-bar">
                                            <div class="progress-fill bg-gradient-to-r from-pink-400 to-rose-500" 
                                                style="width: <?php echo min(100, max(0, ($proyecto['controles_realizados'] ?? 0) / max(1, $proyecto['total_recursos_planificados'] ?? 1) * 100)); ?>%"></div>
                                        </div>
                                    </div>
                                    <span class="text-sm font-medium text-gray-700"><?php echo $proyecto['controles_realizados'] ?? 0; ?></span>
                                </div>
                            </div>
                            <div class="text-xs <?php echo ($proyecto['variacion_total'] ?? 0) < 0 ? 'text-green-600' : 'text-red-600'; ?>">
                                <?php echo ($proyecto['variacion_total'] ?? 0) < 0 ? 'Ahorro: $' : 'Sobrecosto: $'; ?><?php echo number_format(abs($proyecto['variacion_total'] ?? 0), 2); ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php if (count($dashboard['recursos_resumen']) > 6): ?>
                <div class="text-center mt-6">
                    <a href="?accion=analisis" class="text-gray-700 hover:text-gray-900 text-sm font-medium">
                        Ver análisis completo →
                    </a>
                </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>
            
            <?php else: ?>
            <!-- Mensaje cuando no hay datos -->
            <div class="text-center py-16">
                <div class="w-24 h-24 mx-auto bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center mb-6">
                    <i class="fas fa-chart-line text-4xl text-gray-400"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-700 mb-3">No hay datos de control disponibles</h3>
                <p class="text-gray-500 mb-6 max-w-md mx-auto">
                    El dashboard se mostrará cuando haya controles y reportes de rendimiento en el sistema.
                    <?php if ($usuario_rol != 'miembro_equipo'): ?>
                    Comienza creando controles de recursos para monitorear su uso.
                    <?php endif; ?>
                </p>
                <div class="flex flex-wrap gap-3 justify-center">
                    <?php if ($usuario_rol != 'miembro_equipo'): ?>
                    <a href="?accion=crear_control" 
                    class="gradient-bg-proceso6 hover:opacity-90 text-white px-6 py-3 rounded-xl font-medium inline-flex items-center gap-2 shadow-lg hover-lift">
                        <i class="fas fa-plus"></i>
                        Crear primer control
                    </a>
                    <?php endif; ?>
                    <a href="?accion=reportes_rendimiento" 
                    class="bg-gradient-to-r from-blue-500 to-cyan-500 hover:opacity-90 text-white px-6 py-3 rounded-xl font-medium inline-flex items-center gap-2 shadow-lg hover-lift">
                        <i class="fas fa-chart-bar"></i>
                        Ver reportes
                    </a>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <script>
        // Script para el gráfico del dashboard
        document.addEventListener('DOMContentLoaded', function() {
            <?php if (isset($dashboard['estadisticas'])): ?>
            const tipoCtx = document.getElementById('dashboardTipoChart')?.getContext('2d');
            if (tipoCtx) {
                new Chart(tipoCtx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Humanos', 'Materiales', 'Equipos', 'Financieros', 'Tecnológicos'],
                        datasets: [{
                            data: [
                                <?php echo $dashboard['estadisticas']['controles_humanos'] ?? 0; ?>,
                                <?php echo $dashboard['estadisticas']['controles_materiales'] ?? 0; ?>,
                                <?php echo $dashboard['estadisticas']['controles_equipos'] ?? 0; ?>,
                                <?php echo $dashboard['estadisticas']['controles_financieros'] ?? 0; ?>,
                                <?php echo $dashboard['estadisticas']['controles_tecnologicos'] ?? 0; ?>
                            ],
                            backgroundColor: [
                                '#bee3f8', // azul para humanos
                                '#fed7d7', // rojo para materiales
                                '#c6f6d5', // verde para equipos
                                '#fbd38d', // amarillo para financieros
                                '#e9d8fd'  // violeta para tecnológicos
                            ],
                            borderWidth: 2,
                            borderColor: '#ffffff'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '70%',
                        plugins: {
                            legend: {
                                position: 'bottom'
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
            <?php endif; ?>
        });
        </script>

        <?php endif; ?>
        <!-- ========== FIN SECCIÓN DASHBOARD ========== -->

        <!-- ========== SECCIÓN: CONTROLES DE RECURSOS ========== -->
        <?php if ($accion === 'controles'): ?>

        <div class="glass-card rounded-2xl p-6">
            <!-- Encabezado y botones -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Controles de Recursos</h2>
                    <p class="text-gray-600">Monitoreo y seguimiento del uso real vs planificado de recursos</p>
                </div>
                <?php if ($usuario_rol != 'miembro_equipo'): ?>
                <div class="flex flex-wrap gap-2">
                    <a href="?accion=crear_control" 
                    class="gradient-bg-proceso6 hover:opacity-90 text-white px-5 py-2.5 rounded-xl font-medium flex items-center gap-2 hover-lift shadow-lg">
                        <i class="fas fa-plus"></i>
                        Nuevo Control
                    </a>
                    <a href="?accion=analisis" 
                    class="bg-gradient-to-r from-blue-500 to-cyan-500 hover:opacity-90 text-white px-5 py-2.5 rounded-xl font-medium flex items-center gap-2 hover-lift shadow-lg">
                        <i class="fas fa-chart-line"></i>
                        Análisis
                    </a>
                </div>
                <?php endif; ?>
            </div>
            
            <!-- Estadísticas rápidas -->
            <?php if (isset($estadisticas)): ?>
            <div class="grid grid-cols-2 md:grid-cols-5 gap-3 mb-6">
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 p-4 rounded-xl">
                    <div class="text-sm text-gray-500">Total</div>
                    <div class="text-xl font-bold text-gray-800"><?php echo $estadisticas['total_controles'] ?? 0; ?></div>
                </div>
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 p-4 rounded-xl">
                    <div class="text-sm text-gray-500">Favorables</div>
                    <div class="text-xl font-bold text-green-600"><?php echo $estadisticas['variaciones_favorables'] ?? 0; ?></div>
                </div>
                <div class="bg-gradient-to-r from-red-50 to-rose-50 p-4 rounded-xl">
                    <div class="text-sm text-gray-500">Desfavorables</div>
                    <div class="text-xl font-bold text-red-600"><?php echo $estadisticas['variaciones_desfavorables'] ?? 0; ?></div>
                </div>
                <div class="bg-gradient-to-r from-yellow-50 to-amber-50 p-4 rounded-xl">
                    <div class="text-sm text-gray-500">Neutrales</div>
                    <div class="text-xl font-bold text-yellow-600"><?php echo $estadisticas['variaciones_neutrales'] ?? 0; ?></div>
                </div>
                <div class="bg-gradient-to-r from-purple-50 to-indigo-50 p-4 rounded-xl">
                    <div class="text-sm text-gray-500">Variación Total</div>
                    <div class="text-xl font-bold <?php echo ($estadisticas['variacion_total'] ?? 0) < 0 ? 'text-green-600' : 'text-red-600'; ?>">
                        $<?php echo number_format($estadisticas['variacion_total'] ?? 0, 2); ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Filtros -->
            <div class="mb-6 bg-gradient-to-r from-pink-50 to-rose-50 rounded-2xl p-4 md:p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Filtrar Controles</h3>
                <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <input type="hidden" name="accion" value="controles">
                    
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
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tipo Recurso</label>
                        <select name="tipo_recurso" class="form-input">
                            <option value="">Todos los tipos</option>
                            <option value="humano" <?php echo (isset($_GET['tipo_recurso']) && $_GET['tipo_recurso'] == 'humano') ? 'selected' : ''; ?>>Humano</option>
                            <option value="material" <?php echo (isset($_GET['tipo_recurso']) && $_GET['tipo_recurso'] == 'material') ? 'selected' : ''; ?>>Material</option>
                            <option value="equipo" <?php echo (isset($_GET['tipo_recurso']) && $_GET['tipo_recurso'] == 'equipo') ? 'selected' : ''; ?>>Equipo</option>
                            <option value="financiero" <?php echo (isset($_GET['tipo_recurso']) && $_GET['tipo_recurso'] == 'financiero') ? 'selected' : ''; ?>>Financiero</option>
                            <option value="tecnologico" <?php echo (isset($_GET['tipo_recurso']) && $_GET['tipo_recurso'] == 'tecnologico') ? 'selected' : ''; ?>>Tecnológico</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Variación</label>
                        <select name="tipo_variacion" class="form-input">
                            <option value="">Todas</option>
                            <option value="favorable" <?php echo (isset($_GET['tipo_variacion']) && $_GET['tipo_variacion'] == 'favorable') ? 'selected' : ''; ?>>Favorable</option>
                            <option value="desfavorable" <?php echo (isset($_GET['tipo_variacion']) && $_GET['tipo_variacion'] == 'desfavorable') ? 'selected' : ''; ?>>Desfavorable</option>
                            <option value="neutral" <?php echo (isset($_GET['tipo_variacion']) && $_GET['tipo_variacion'] == 'neutral') ? 'selected' : ''; ?>>Neutral</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Fecha Desde</label>
                        <input type="date" name="fecha_desde" class="form-input" value="<?php echo $_GET['fecha_desde'] ?? ''; ?>">
                    </div>
                    
                    <div class="flex items-end">
                        <button type="submit" 
                                class="w-full gradient-bg-proceso6 hover:opacity-90 text-white px-4 py-2.5 rounded-xl font-medium flex items-center justify-center gap-2 hover-lift">
                            <i class="fas fa-filter"></i>
                            Aplicar Filtros
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Alertas importantes -->
            <?php if (isset($alertas) && !empty($alertas)): ?>
            <div class="mb-6 p-4 rounded-lg border-2 border-red-200 bg-gradient-to-r from-red-50 to-rose-50">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-r from-red-400 to-red-500 rounded-xl flex items-center justify-center">
                        <i class="fas fa-exclamation-triangle text-white"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-semibold text-red-900">¡Alertas de Desviación!</h3>
                        <p class="text-sm text-red-700">Existen <?php echo count($alertas); ?> controles con desviación superior al 10%</p>
                    </div>
                    <a href="?accion=controles&tipo_variacion=desfavorable" 
                    class="px-4 py-2 bg-gradient-to-r from-red-500 to-rose-500 hover:opacity-90 text-white rounded-lg font-medium">
                        Ver Alertas
                    </a>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Tabla de controles -->
            <?php if (isset($controles) && !empty($controles)): ?>
            <div class="overflow-x-auto rounded-xl border border-gray-200 bg-white">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gradient-to-r from-pink-50 to-pink-100">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 uppercase">Métrica / Proyecto</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 uppercase">Tipo / Fecha</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 uppercase">Valor Planificado</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 uppercase">Valor Actual</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 uppercase">Variación</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 uppercase">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        <?php foreach($controles as $control): ?>
                        <?php 
                        $variacion = $control['variacion'] ?? 0;
                        $porcentaje = $control['valor_planificado'] != 0 ? abs($variacion / $control['valor_planificado'] * 100) : 0;
                        ?>
                        <tr class="table-row-hover">
                            <td class="px-6 py-4">
                                <div class="font-semibold text-gray-900 mb-1 truncate max-w-xs"><?php echo htmlspecialchars(substr($control['metrica'] ?? 'Sin métrica', 0, 40)); ?></div>
                                <div class="text-sm text-gray-600 flex items-center">
                                    <i class="fas fa-project-diagram mr-1"></i>
                                    <?php echo htmlspecialchars($control['proyecto_nombre'] ?? 'Sin proyecto'); ?>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="space-y-2">
                                    <span class="status-badge <?php echo 'badge-' . ($control['tipo_recurso'] ?? 'humano'); ?>">
                                        <i class="fas fa-circle text-xs mr-1"></i>
                                        <?php echo ucfirst($control['tipo_recurso'] ?? 'humano'); ?>
                                    </span>
                                    <div class="text-sm text-gray-500">
                                        <?php echo isset($control['fecha_control']) ? date('d/m/Y', strtotime($control['fecha_control'])) : 'N/A'; ?>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900">$<?php echo number_format($control['valor_planificado'] ?? 0, 2); ?></div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900">$<?php echo number_format($control['valor_actual'] ?? 0, 2); ?></div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="variation-indicator <?php echo $variacion < 0 ? 'variation-down' : ($variacion > 0 ? 'variation-up' : 'variation-neutral'); ?>">
                                        <i class="fas fa-arrow-<?php echo $variacion < 0 ? 'down' : ($variacion > 0 ? 'up' : 'right'); ?> text-xs"></i>
                                    </div>
                                    <div>
                                        <div class="font-medium <?php echo $variacion < 0 ? 'text-green-600' : ($variacion > 0 ? 'text-red-600' : 'text-gray-600'); ?>">
                                            $<?php echo number_format(abs($variacion), 2); ?>
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            <?php echo number_format($porcentaje, 1); ?>%
                                        </div>
                                    </div>
                                </div>
                                <?php if (!empty($control['desviacion'])): ?>
                                <div class="text-xs text-gray-500 mt-1 truncate max-w-xs" title="<?php echo htmlspecialchars($control['desviacion']); ?>">
                                    <?php echo htmlspecialchars(substr($control['desviacion'], 0, 30)); ?>...
                                </div>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col gap-2">
                                    <a href="?accion=ver_control&id=<?php echo $control['id']; ?>" 
                                    class="px-3 py-1.5 bg-gradient-to-r from-blue-50 to-blue-100 hover:from-blue-100 hover:to-blue-200 text-blue-600 rounded-lg text-sm font-medium flex items-center justify-center gap-1 hover-lift">
                                        <i class="fas fa-eye text-xs"></i>
                                        Ver
                                    </a>
                                    <?php if ($usuario_rol != 'miembro_equipo'): ?>
                                    <a href="?accion=editar_control&id=<?php echo $control['id']; ?>" 
                                    class="px-3 py-1.5 bg-gradient-to-r from-green-50 to-green-100 hover:from-green-100 hover:to-green-200 text-green-600 rounded-lg text-sm font-medium flex items-center justify-center gap-1 hover-lift">
                                        <i class="fas fa-edit text-xs"></i>
                                        Editar
                                    </a>
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
                    Mostrando <?php echo count($controles); ?> de <?php echo $estadisticas['total_controles'] ?? count($controles); ?> controles
                </div>
                <div class="flex gap-2">
                    <button class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button class="px-4 py-2 bg-pink-600 text-white rounded-lg hover:bg-pink-700">1</button>
                    <button class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">2</button>
                    <button class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">3</button>
                    <button class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>
            <?php else: ?>
            <div class="text-center py-16">
                <div class="w-24 h-24 mx-auto bg-gradient-to-br from-pink-50 to-pink-100 rounded-full flex items-center justify-center mb-6">
                    <i class="fas fa-clipboard-check text-4xl text-gray-400"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-700 mb-3">No hay controles registrados</h3>
                <p class="text-gray-500 mb-6 max-w-md mx-auto">
                    <?php if ($usuario_rol == 'miembro_equipo'): ?>
                    No hay controles de recursos disponibles para visualizar.
                    <?php else: ?>
                    Comienza creando controles para monitorear el uso de recursos.
                    <?php endif; ?>
                </p>
                <?php if ($usuario_rol != 'miembro_equipo'): ?>
                <a href="?accion=crear_control" 
                class="gradient-bg-proceso6 hover:opacity-90 text-white px-6 py-3 rounded-xl font-medium inline-flex items-center gap-2 shadow-lg hover-lift">
                    <i class="fas fa-plus"></i>
                    Crear primer control
                </a>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>

        <?php endif; ?>
        <!-- ========== FIN SECCIÓN CONTROLES ========== -->

        <!-- ========== SECCIÓN: CREAR/EDITAR CONTROL ========== -->
        <?php if ($accion === 'crear_control' || $accion === 'editar_control'): ?>

        <div class="glass-card rounded-2xl p-6">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">
                        <?php echo $accion === 'crear_control' ? 'Crear Nuevo Control' : 'Editar Control'; ?>
                    </h2>
                    <p class="text-gray-600">Registra el monitoreo de uso real vs planificado de recursos</p>
                </div>
                <a href="?accion=controles" 
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
            
            <form method="POST" class="space-y-6" id="form-control">
                <input type="hidden" name="accion" value="<?php echo $accion === 'crear_control' ? 'crear_control' : 'actualizar_control'; ?>">
                <?php if (isset($control['id'])): ?>
                <input type="hidden" name="id" value="<?php echo $control['id']; ?>">
                <?php endif; ?>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Proyecto -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2 required">Proyecto</label>
                        <select name="proyecto_id" class="form-input" required id="proyecto_id">
                            <option value="">Seleccionar proyecto...</option>
                            <?php if (isset($proyectos)): ?>
                                <?php foreach ($proyectos as $proyecto): ?>
                                    <option value="<?php echo htmlspecialchars($proyecto['id'] ?? ''); ?>"
                                        <?php echo (isset($datos_form['proyecto_id']) && $datos_form['proyecto_id'] == $proyecto['id']) || (isset($control['proyecto_id']) && $control['proyecto_id'] == $proyecto['id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($proyecto['nombre'] ?? ''); ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    
                    <!-- Tipo de recurso -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2 required">Tipo de Recurso</label>
                        <select name="tipo_recurso" class="form-input" required id="tipo_recurso">
                            <option value="">Seleccionar tipo...</option>
                            <?php if (isset($tipos_recurso)): ?>
                                <?php foreach ($tipos_recurso as $tipo): ?>
                                    <option value="<?php echo htmlspecialchars($tipo); ?>"
                                        <?php echo (isset($datos_form['tipo_recurso']) && $datos_form['tipo_recurso'] == $tipo) || (isset($control['tipo_recurso']) && $control['tipo_recurso'] == $tipo) ? 'selected' : ''; ?>>
                                        <?php echo ucfirst($tipo); ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Recurso específico (opcional) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Recurso Específico</label>
                        <select name="recurso_id" class="form-input" id="recurso_id">
                            <option value="">Seleccionar recurso (opcional)...</option>
                            <?php if (isset($recursos)): ?>
                                <?php foreach ($recursos as $recurso): ?>
                                    <option value="<?php echo htmlspecialchars($recurso['id'] ?? ''); ?>"
                                        data-proyecto="<?php echo htmlspecialchars($recurso['proyecto_id'] ?? ''); ?>"
                                        data-tipo="<?php echo htmlspecialchars($recurso['tipo_recurso'] ?? ''); ?>"
                                        <?php 
                                        $selected = false;
                                        if (isset($datos_form['recurso_id']) && $datos_form['recurso_id'] == $recurso['id']) {
                                            $selected = true;
                                        } elseif (isset($control['recurso_id']) && $control['recurso_id'] == $recurso['id']) {
                                            $selected = true;
                                        }
                                        echo $selected ? 'selected' : '';
                                        ?>>
                                        <?php echo htmlspecialchars($recurso['descripcion'] ?? ''); ?> 
                                        (<?php echo htmlspecialchars($recurso['proyecto_nombre'] ?? ''); ?>)
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        <div class="mt-1 text-xs text-gray-500" id="info-recurso"></div>
                    </div>
                    
                    <!-- Tabla de referencia -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tabla de Referencia</label>
                        <input type="text" name="tabla_referencia" class="form-input"
                            value="<?php echo htmlspecialchars($datos_form['tabla_referencia'] ?? $control['tabla_referencia'] ?? ''); ?>"
                            placeholder="Ej: planificacion_recursos, estimacion_recursos...">
                    </div>
                </div>
                
                <!-- Métrica -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2 required">Métrica</label>
                    <input type="text" name="metrica" class="form-input" required
                        value="<?php echo htmlspecialchars($datos_form['metrica'] ?? $control['metrica'] ?? ''); ?>"
                        placeholder="Ej: Costo desarrollo, Horas trabajadas, Cantidad materiales...">
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Valores -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2 required">Valor Planificado</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">$</span>
                            <input type="number" name="valor_planificado" step="0.01" class="form-input pl-8" required
                                value="<?php echo htmlspecialchars($datos_form['valor_planificado'] ?? $control['valor_planificado'] ?? '0'); ?>"
                                id="valor_planificado">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2 required">Valor Actual</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">$</span>
                            <input type="number" name="valor_actual" step="0.01" class="form-input pl-8" required
                                value="<?php echo htmlspecialchars($datos_form['valor_actual'] ?? $control['valor_actual'] ?? '0'); ?>"
                                id="valor_actual" oninput="calcularVariacion()">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Variación</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">$</span>
                            <input type="number" name="variacion" step="0.01" class="form-input pl-8" 
                                value="<?php echo htmlspecialchars($datos_form['variacion'] ?? $control['variacion'] ?? '0'); ?>"
                                id="variacion" readonly>
                        </div>
                        <div class="mt-1 text-sm" id="variacion-info"></div>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Fecha -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Fecha de Control</label>
                        <input type="date" name="fecha_control" class="form-input"
                            value="<?php echo htmlspecialchars($datos_form['fecha_control'] ?? $control['fecha_control'] ?? date('Y-m-d')); ?>">
                    </div>
                    
                    <!-- Responsable -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Responsable</label>
                        <select name="responsable_id" class="form-input">
                            <option value="<?php echo $usuario_id; ?>">Yo mismo (<?php echo htmlspecialchars($usuario_nombre); ?>)</option>
                            <?php if (isset($responsables)): ?>
                                <?php foreach ($responsables as $responsable): ?>
                                    <?php if ($responsable['id'] != $usuario_id): ?>
                                    <option value="<?php echo htmlspecialchars($responsable['id'] ?? ''); ?>"
                                        <?php echo (isset($datos_form['responsable_id']) && $datos_form['responsable_id'] == $responsable['id']) || (isset($control['responsable_id']) && $control['responsable_id'] == $responsable['id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($responsable['nombre'] ?? ''); ?> 
                                        (<?php echo htmlspecialchars($responsable['rol'] ?? ''); ?>)
                                    </option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>
                
                <!-- Desviación -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Desviación</label>
                    <textarea name="desviacion" rows="3" class="form-input"
                        placeholder="Describe la desviación identificada..."><?php echo htmlspecialchars($datos_form['desviacion'] ?? $control['desviacion'] ?? ''); ?></textarea>
                </div>
                
                <!-- Acción correctiva -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Acción Correctiva</label>
                    <textarea name="accion_correctiva" rows="3" class="form-input"
                        placeholder="Describe las acciones correctivas a implementar..."><?php echo htmlspecialchars($datos_form['accion_correctiva'] ?? $control['accion_correctiva'] ?? ''); ?></textarea>
                </div>
                
                <!-- Botones de acción -->
                <div class="flex flex-wrap gap-3 pt-6 border-t border-gray-200">
                    <?php if ($accion === 'crear_control'): ?>
                    <button type="submit" name="guardar_y_continuar" 
                            class="bg-gradient-to-r from-green-500 to-emerald-500 hover:opacity-90 text-white px-6 py-3 rounded-xl font-medium flex items-center gap-2 hover-lift shadow-lg">
                        <i class="fas fa-save"></i>
                        Guardar y crear otro
                    </button>
                    <?php endif; ?>
                    
                    <button type="submit" 
                            class="gradient-bg-proceso6 hover:opacity-90 text-white px-6 py-3 rounded-xl font-medium flex items-center gap-2 hover-lift shadow-lg">
                        <i class="fas <?php echo $accion === 'crear_control' ? 'fa-plus' : 'fa-save'; ?>"></i>
                        <?php echo $accion === 'crear_control' ? 'Crear Control' : 'Guardar Cambios'; ?>
                    </button>
                    
                    <a href="?accion=controles" 
                    class="px-6 py-3 bg-gradient-to-r from-gray-100 to-gray-200 hover:from-gray-200 hover:to-gray-300 text-gray-700 rounded-xl font-medium flex items-center gap-2 hover-lift">
                        <i class="fas fa-times"></i>
                        Cancelar
                    </a>
                    
                    <?php if (isset($control['id'])): ?>
                    <button type="button" onclick="confirmarEliminar(<?php echo $control['id']; ?>)" 
                            class="ml-auto px-6 py-3 bg-gradient-to-r from-red-500 to-rose-500 hover:opacity-90 text-white rounded-xl font-medium flex items-center gap-2 hover-lift shadow-lg">
                        <i class="fas fa-trash"></i>
                        Eliminar Control
                    </button>
                    <?php endif; ?>
                </div>
            </form>
            
            <?php if (isset($control['id'])): ?>
            <!-- Formulario oculto para eliminar -->
            <form method="POST" id="form-eliminar-<?php echo $control['id']; ?>" class="hidden">
                <input type="hidden" name="accion" value="eliminar_control">
                <input type="hidden" name="id" value="<?php echo $control['id']; ?>">
            </form>
            <?php endif; ?>
        </div>

        <script>
        // Filtrar recursos por proyecto y tipo
        function filtrarRecursos() {
            const proyectoId = document.getElementById('proyecto_id').value;
            const tipoRecurso = document.getElementById('tipo_recurso').value;
            const recursoSelect = document.getElementById('recurso_id');
            const infoRecurso = document.getElementById('info-recurso');
            
            let recursosFiltrados = 0;
            let infoText = '';
            
            // Filtrar opciones
            Array.from(recursoSelect.options).forEach(option => {
                if (option.value === '') {
                    option.style.display = 'block';
                    return;
                }
                
                const proyectoMatch = !proyectoId || option.getAttribute('data-proyecto') === proyectoId;
                const tipoMatch = !tipoRecurso || option.getAttribute('data-tipo') === tipoRecurso;
                
                if (proyectoMatch && tipoMatch) {
                    option.style.display = 'block';
                    recursosFiltrados++;
                } else {
                    option.style.display = 'none';
                }
            });
            
            // Actualizar información
            if (proyectoId && tipoRecurso) {
                infoText = `${recursosFiltrados} recursos disponibles para este proyecto y tipo`;
            } else if (proyectoId) {
                infoText = `${recursosFiltrados} recursos disponibles para este proyecto`;
            } else if (tipoRecurso) {
                infoText = `${recursosFiltrados} recursos disponibles para este tipo`;
            } else {
                infoText = 'Selecciona proyecto y tipo para filtrar recursos';
            }
            
            infoRecurso.innerHTML = infoText;
        }
        
        // Calcular variación automáticamente
        function calcularVariacion() {
            const valorPlanificado = parseFloat(document.getElementById('valor_planificado').value) || 0;
            const valorActual = parseFloat(document.getElementById('valor_actual').value) || 0;
            const variacion = valorActual - valorPlanificado;
            const porcentaje = valorPlanificado !== 0 ? (variacion / valorPlanificado * 100) : 0;
            
            document.getElementById('variacion').value = variacion.toFixed(2);
            
            let infoHtml = '';
            if (variacion < 0) {
                infoHtml = `<span class="text-green-600 font-medium"><i class="fas fa-arrow-down mr-1"></i> Variación favorable: $${Math.abs(variacion).toFixed(2)} (${Math.abs(porcentaje).toFixed(1)}%)</span>`;
            } else if (variacion > 0) {
                infoHtml = `<span class="text-red-600 font-medium"><i class="fas fa-arrow-up mr-1"></i> Variación desfavorable: $${variacion.toFixed(2)} (${porcentaje.toFixed(1)}%)</span>`;
            } else {
                infoHtml = `<span class="text-gray-600 font-medium"><i class="fas fa-equals mr-1"></i> Sin variación</span>`;
            }
            
            document.getElementById('variacion-info').innerHTML = infoHtml;
        }
        
        document.addEventListener('DOMContentLoaded', function() {
            // Inicializar filtros
            document.getElementById('proyecto_id').addEventListener('change', filtrarRecursos);
            document.getElementById('tipo_recurso').addEventListener('change', filtrarRecursos);
            filtrarRecursos();
            
            // Inicializar cálculo de variación
            calcularVariacion();
            
            // Event listeners para cálculo en tiempo real
            document.getElementById('valor_planificado').addEventListener('input', calcularVariacion);
            document.getElementById('valor_actual').addEventListener('input', calcularVariacion);
        });
        
        function confirmarEliminar(id) {
            if (confirm('¿Estás seguro de eliminar este control? Esta acción no se puede deshacer.')) {
                document.getElementById('form-eliminar-' + id).submit();
            }
        }
        </script>

        <?php endif; ?>
        <!-- ========== FIN SECCIÓN CREAR/EDITAR CONTROL ========== -->

        <!-- ========== SECCIÓN: VER CONTROL ========== -->
        <?php if ($accion === 'ver_control' && isset($control)): ?>

        <div class="glass-card rounded-2xl p-6">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Detalles del Control</h2>
                    <div class="text-gray-600">ID: #<?php echo htmlspecialchars($control['id'] ?? ''); ?></div>
                </div>
                <div class="flex flex-wrap gap-2">
                    <a href="?accion=controles" 
                    class="px-4 py-2.5 bg-gradient-to-r from-gray-100 to-gray-200 hover:from-gray-200 hover:to-gray-300 text-gray-700 rounded-xl font-medium flex items-center gap-2 hover-lift">
                        <i class="fas fa-arrow-left"></i>
                        Volver
                    </a>
                    <?php if ($usuario_rol != 'miembro_equipo'): ?>
                    <a href="?accion=editar_control&id=<?php echo $control['id']; ?>" 
                    class="gradient-bg-proceso6 hover:opacity-90 text-white px-5 py-2.5 rounded-xl font-medium flex items-center gap-2 hover-lift shadow-lg">
                        <i class="fas fa-edit"></i>
                        Editar Control
                    </a>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Información principal -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Columna izquierda: Detalles -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Métricas y valores -->
                    <div class="bg-gradient-to-r from-pink-50 to-pink-100 p-6 rounded-2xl">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Métricas del Control</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="text-center p-4 bg-white rounded-xl">
                                <div class="text-sm text-gray-500 mb-2">Valor Planificado</div>
                                <div class="text-2xl font-bold text-gray-900">$<?php echo number_format($control['valor_planificado'] ?? 0, 2); ?></div>
                            </div>
                            
                            <div class="text-center p-4 bg-white rounded-xl">
                                <div class="text-sm text-gray-500 mb-2">Valor Actual</div>
                                <div class="text-2xl font-bold text-gray-900">$<?php echo number_format($control['valor_actual'] ?? 0, 2); ?></div>
                            </div>
                            
                            <div class="text-center p-4 bg-white rounded-xl">
                                <div class="text-sm text-gray-500 mb-2">Variación</div>
                                <div class="text-2xl font-bold <?php echo ($control['variacion'] ?? 0) < 0 ? 'text-green-600' : (($control['variacion'] ?? 0) > 0 ? 'text-red-600' : 'text-gray-600'); ?>">
                                    $<?php echo number_format(abs($control['variacion'] ?? 0), 2); ?>
                                </div>
                                <div class="text-sm <?php echo ($control['variacion'] ?? 0) < 0 ? 'text-green-600' : (($control['variacion'] ?? 0) > 0 ? 'text-red-600' : 'text-gray-600'); ?>">
                                    <?php 
                                    $porcentaje = $control['valor_planificado'] != 0 ? abs($control['variacion'] / $control['valor_planificado'] * 100) : 0;
                                    echo number_format($porcentaje, 1); ?>%
                                </div>
                            </div>
                        </div>
                        
                        <!-- Gráfico de variación -->
                        <div class="mt-6">
                            <div class="flex items-center justify-between mb-2">
                                <div class="text-sm text-gray-600">Representación de la variación</div>
                                <div class="text-sm font-medium <?php echo ($control['variacion'] ?? 0) < 0 ? 'text-green-600' : (($control['variacion'] ?? 0) > 0 ? 'text-red-600' : 'text-gray-600'); ?>">
                                    <?php echo ($control['variacion'] ?? 0) < 0 ? 'Ahorro' : (($control['variacion'] ?? 0) > 0 ? 'Sobrecosto' : 'Sin variación'); ?>
                                </div>
                            </div>
                            <div class="flex items-center h-8 bg-gradient-to-r from-gray-100 to-gray-200 rounded-lg overflow-hidden">
                                <div class="h-full bg-gradient-to-r from-green-400 to-emerald-500" 
                                    style="width: <?php echo $control['valor_planificado'] > 0 ? min(100, max(0, ($control['valor_actual'] / $control['valor_planificado'] * 100))) : 0; ?>%">
                                </div>
                            </div>
                            <div class="flex justify-between text-xs text-gray-500 mt-1">
                                <span>0%</span>
                                <span>100%</span>
                                <span>200%</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Desviación y acción correctiva -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-gradient-to-r from-orange-50 to-amber-100 p-6 rounded-2xl">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Desviación Identificada</h3>
                            <?php if (!empty($control['desviacion'])): ?>
                            <div class="bg-white p-4 rounded-xl">
                                <p class="text-gray-700 whitespace-pre-wrap"><?php echo nl2br(htmlspecialchars($control['desviacion'])); ?></p>
                            </div>
                            <?php else: ?>
                            <div class="text-center py-8">
                                <i class="fas fa-check-circle text-3xl text-green-400 mb-3"></i>
                                <p class="text-gray-600">No se identificaron desviaciones significativas</p>
                            </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="bg-gradient-to-r from-green-50 to-emerald-100 p-6 rounded-2xl">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Acción Correctiva</h3>
                            <?php if (!empty($control['accion_correctiva'])): ?>
                            <div class="bg-white p-4 rounded-xl">
                                <p class="text-gray-700 whitespace-pre-wrap"><?php echo nl2br(htmlspecialchars($control['accion_correctiva'])); ?></p>
                            </div>
                            <?php else: ?>
                            <div class="text-center py-8">
                                <i class="fas fa-lightbulb text-3xl text-yellow-400 mb-3"></i>
                                <p class="text-gray-600">No se requieren acciones correctivas</p>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <!-- Columna derecha: Metadatos -->
                <div class="space-y-6">
                    <!-- Información básica -->
                    <div class="bg-gradient-to-r from-blue-50 to-cyan-100 p-6 rounded-2xl">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Información del Control</h3>
                        <div class="space-y-3">
                            <div>
                                <div class="text-sm text-gray-600 mb-1">Métrica</div>
                                <div class="font-medium text-gray-900"><?php echo htmlspecialchars($control['metrica'] ?? 'Sin métrica'); ?></div>
                            </div>
                            
                            <div>
                                <div class="text-sm text-gray-600 mb-1">Tipo de Recurso</div>
                                <span class="status-badge <?php echo 'badge-' . ($control['tipo_recurso'] ?? 'humano'); ?>">
                                    <?php echo ucfirst($control['tipo_recurso'] ?? 'humano'); ?>
                                </span>
                            </div>
                            
                            <div>
                                <div class="text-sm text-gray-600 mb-1">Fecha de Control</div>
                                <div class="font-medium text-gray-900"><?php echo isset($control['fecha_control']) ? date('d/m/Y', strtotime($control['fecha_control'])) : 'N/A'; ?></div>
                            </div>
                            
                            <?php if (!empty($control['tabla_referencia'])): ?>
                            <div>
                                <div class="text-sm text-gray-600 mb-1">Tabla de Referencia</div>
                                <div class="font-medium text-gray-900"><?php echo htmlspecialchars($control['tabla_referencia']); ?></div>
                            </div>
                            <?php endif; ?>
                            
                            <div>
                                <div class="text-sm text-gray-600 mb-1">Tipo de Variación</div>
                                <span class="status-badge <?php echo 'badge-' . ($control['tipo_variacion'] ?? 'neutral'); ?>">
                                    <i class="fas fa-<?php echo ($control['tipo_variacion'] ?? 'neutral') == 'favorable' ? 'arrow-down' : (($control['tipo_variacion'] ?? 'neutral') == 'desfavorable' ? 'arrow-up' : 'equals'); ?> text-xs mr-1"></i>
                                    <?php echo ucfirst($control['tipo_variacion'] ?? 'neutral'); ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Información del proyecto -->
                    <div class="bg-gradient-to-r from-purple-50 to-indigo-100 p-6 rounded-2xl">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Proyecto Relacionado</h3>
                        <div class="space-y-3">
                            <div>
                                <div class="text-sm text-gray-600">Nombre del Proyecto</div>
                                <div class="font-medium text-gray-900"><?php echo htmlspecialchars($control['proyecto_nombre'] ?? 'Sin proyecto'); ?></div>
                            </div>
                            <div>
                                <div class="text-sm text-gray-600">Descripción</div>
                                <div class="text-gray-700"><?php echo htmlspecialchars(substr($control['proyecto_descripcion'] ?? 'Sin descripción', 0, 100)); ?>...</div>
                            </div>
                            <div>
                                <div class="text-sm text-gray-600">Presupuesto Estimado</div>
                                <div class="font-medium text-gray-900">$<?php echo number_format($control['presupuesto_estimado'] ?? 0, 2); ?></div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Responsable -->
                    <div class="bg-gradient-to-r from-green-50 to-emerald-100 p-6 rounded-2xl">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Responsable</h3>
                        <div class="space-y-3">
                            <div>
                                <div class="text-sm text-gray-600">Nombre</div>
                                <div class="font-medium text-gray-900"><?php echo htmlspecialchars($control['responsable_nombre'] ?? 'Sin asignar'); ?></div>
                            </div>
                            <div>
                                <div class="text-sm text-gray-600">Email</div>
                                <div class="text-gray-700 truncate"><?php echo htmlspecialchars($control['responsable_email'] ?? 'Sin email'); ?></div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Porcentaje de ejecución -->
                    <div class="bg-gradient-to-r from-yellow-50 to-amber-100 p-6 rounded-2xl">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Porcentaje de Ejecución</h3>
                        <div class="text-center">
                            <div class="text-4xl font-bold <?php echo ($control['porcentaje_ejecucion'] ?? 100) > 100 ? 'text-red-600' : (($control['porcentaje_ejecucion'] ?? 100) > 90 ? 'text-yellow-600' : 'text-green-600'); ?>">
                                <?php echo number_format($control['porcentaje_ejecucion'] ?? 100, 1); ?>%
                            </div>
                            <div class="text-sm text-gray-600 mt-2">
                                <?php echo ($control['valor_actual'] ?? 0) > ($control['valor_planificado'] ?? 0) ? 'Sobreejecución' : 'Subejecución'; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php endif; ?>
        <!-- ========== FIN SECCIÓN VER CONTROL ========== -->

        <!-- ========== SECCIÓN: REPORTES DE RENDIMIENTO ========== -->
        <?php if ($accion === 'reportes_rendimiento'): ?>

        <div class="glass-card rounded-2xl p-6">
            <!-- Encabezado -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Reportes de Rendimiento</h2>
                    <p class="text-gray-600">Análisis periódico del desempeño de recursos por proyecto</p>
                </div>
                <?php if ($usuario_rol != 'miembro_equipo'): ?>
                <div class="flex flex-wrap gap-2">
                    <a href="?accion=crear_reporte" 
                    class="gradient-bg-proceso6 hover:opacity-90 text-white px-5 py-2.5 rounded-xl font-medium flex items-center gap-2 hover-lift shadow-lg">
                        <i class="fas fa-plus"></i>
                        Nuevo Reporte
                    </a>
                    <button onclick="window.print()" 
                            class="px-5 py-2.5 bg-gradient-to-r from-gray-100 to-gray-200 hover:from-gray-200 hover:to-gray-300 text-gray-700 rounded-xl font-medium flex items-center gap-2 hover-lift">
                        <i class="fas fa-print"></i>
                        Imprimir
                    </button>
                </div>
                <?php endif; ?>
            </div>
            
            <!-- Filtros -->
            <div class="mb-6 bg-gradient-to-r from-blue-50 to-cyan-50 rounded-2xl p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Filtrar Reportes</h3>
                <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <input type="hidden" name="accion" value="reportes_rendimiento">
                    
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
                        <label class="block text-sm font-medium text-gray-700 mb-2">Período</label>
                        <select name="periodo" class="form-input">
                            <option value="">Todos los períodos</option>
                            <option value="semanal" <?php echo (isset($_GET['periodo']) && $_GET['periodo'] == 'semanal') ? 'selected' : ''; ?>>Semanal</option>
                            <option value="mensual" <?php echo (isset($_GET['periodo']) && $_GET['periodo'] == 'mensual') ? 'selected' : ''; ?>>Mensual</option>
                            <option value="trimestral" <?php echo (isset($_GET['periodo']) && $_GET['periodo'] == 'trimestral') ? 'selected' : ''; ?>>Trimestral</option>
                            <option value="anual" <?php echo (isset($_GET['periodo']) && $_GET['periodo'] == 'anual') ? 'selected' : ''; ?>>Anual</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Fecha Desde</label>
                        <input type="date" name="fecha_desde" class="form-input" value="<?php echo $_GET['fecha_desde'] ?? ''; ?>">
                    </div>
                    
                    <div class="flex items-end">
                        <button type="submit" 
                                class="w-full gradient-bg-proceso6 hover:opacity-90 text-white px-4 py-2.5 rounded-xl font-medium flex items-center justify-center gap-2 hover-lift">
                            <i class="fas fa-filter"></i>
                            Aplicar Filtros
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Generar reporte automático -->
            <?php if ($usuario_rol != 'miembro_equipo'): ?>
            <div class="mb-6 p-4 rounded-lg border-2 border-green-200 bg-gradient-to-r from-green-50 to-emerald-50">
                <div class="flex flex-col md:flex-row items-center gap-4">
                    <div class="w-12 h-12 bg-gradient-to-r from-green-400 to-emerald-500 rounded-xl flex items-center justify-center">
                        <i class="fas fa-robot text-white text-xl"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-semibold text-gray-900">Generar Reporte Automático</h3>
                        <p class="text-sm text-gray-600">Genera un reporte de rendimiento basado en datos del sistema</p>
                    </div>
                    <form method="POST" class="flex gap-2">
                        <input type="hidden" name="accion" value="generar_reporte_automatico">
                        <select name="proyecto_id" class="form-input" required>
                            <option value="">Seleccionar proyecto...</option>
                            <?php if (isset($proyectos)): ?>
                                <?php foreach ($proyectos as $proyecto): ?>
                                    <option value="<?php echo htmlspecialchars($proyecto['id'] ?? ''); ?>">
                                        <?php echo htmlspecialchars($proyecto['nombre'] ?? ''); ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        <select name="periodo" class="form-input" required>
                            <option value="mensual">Mensual</option>
                            <option value="semanal">Semanal</option>
                            <option value="trimestral">Trimestral</option>
                            <option value="anual">Anual</option>
                        </select>
                        <button type="submit" 
                                class="px-4 py-2 bg-gradient-to-r from-green-500 to-emerald-500 hover:opacity-90 text-white rounded-xl font-medium flex items-center gap-2">
                            <i class="fas fa-magic"></i>
                            Generar
                        </button>
                    </form>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Lista de reportes -->
            <?php if (isset($reportes) && !empty($reportes)): ?>
            <div class="space-y-4">
                <?php foreach($reportes as $reporte): ?>
                <div class="bg-white rounded-xl border border-gray-200 hover-lift overflow-hidden">
                    <div class="p-6">
                        <div class="flex flex-col md:flex-row md:items-start justify-between gap-4 mb-4">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <span class="status-badge <?php echo 'badge-periodo-' . ($reporte['periodo'] ?? 'mensual'); ?>">
                                        <?php echo ucfirst($reporte['periodo'] ?? 'mensual'); ?>
                                    </span>
                                    <span class="status-badge <?php echo ($reporte['tipo_variacion_presupuesto'] ?? 'neutral') == 'ahorro' ? 'badge-favorable' : (($reporte['tipo_variacion_presupuesto'] ?? 'neutral') == 'sobrecosto' ? 'badge-desfavorable' : 'badge-neutral'); ?>">
                                        <?php 
                                        $tipo = $reporte['tipo_variacion_presupuesto'] ?? 'neutral';
                                        echo $tipo == 'ahorro' ? 'Ahorro' : ($tipo == 'sobrecosto' ? 'Sobrecosto' : 'Neutral');
                                        ?>
                                    </span>
                                </div>
                                
                                <h3 class="text-xl font-semibold text-gray-900 mb-2">
                                    Reporte <?php echo ucfirst($reporte['periodo'] ?? ''); ?> - 
                                    <?php echo isset($reporte['fecha_inicio']) ? date('d/m/Y', strtotime($reporte['fecha_inicio'])) : ''; ?> 
                                    al <?php echo isset($reporte['fecha_fin']) ? date('d/m/Y', strtotime($reporte['fecha_fin'])) : ''; ?>
                                </h3>
                                
                                <div class="text-gray-600 mb-3"><?php echo htmlspecialchars(substr($reporte['observaciones'] ?? '', 0, 200)); ?>...</div>
                                
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                                    <div class="text-center p-3 bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg">
                                        <div class="text-sm text-gray-600 mb-1">Eficiencia</div>
                                        <div class="text-lg font-bold <?php echo ($reporte['eficiencia_recursos'] ?? 0) >= 80 ? 'text-green-600' : (($reporte['eficiencia_recursos'] ?? 0) >= 60 ? 'text-yellow-600' : 'text-red-600'); ?>">
                                            <?php echo number_format($reporte['eficiencia_recursos'] ?? 0, 1); ?>%
                                        </div>
                                    </div>
                                    
                                    <div class="text-center p-3 bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg">
                                        <div class="text-sm text-gray-600 mb-1">Cumplimiento</div>
                                        <div class="text-lg font-bold <?php echo ($reporte['cumplimiento_plazos'] ?? 0) >= 90 ? 'text-green-600' : (($reporte['cumplimiento_plazos'] ?? 0) >= 70 ? 'text-yellow-600' : 'text-red-600'); ?>">
                                            <?php echo number_format($reporte['cumplimiento_plazos'] ?? 0, 1); ?>%
                                        </div>
                                    </div>
                                    
                                    <div class="text-center p-3 bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg">
                                        <div class="text-sm text-gray-600 mb-1">Variación</div>
                                        <div class="text-lg font-bold <?php echo ($reporte['variacion_presupuesto'] ?? 0) < 0 ? 'text-green-600' : (($reporte['variacion_presupuesto'] ?? 0) > 0 ? 'text-red-600' : 'text-gray-600'); ?>">
                                            $<?php echo number_format($reporte['variacion_presupuesto'] ?? 0, 2); ?>
                                        </div>
                                    </div>
                                    
                                    <div class="text-center p-3 bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg">
                                        <div class="text-sm text-gray-600 mb-1">Productividad</div>
                                        <div class="text-lg font-bold <?php echo ($reporte['productividad_equipo'] ?? 0) >= 80 ? 'text-green-600' : (($reporte['productividad_equipo'] ?? 0) >= 60 ? 'text-yellow-600' : 'text-red-600'); ?>">
                                            <?php echo number_format($reporte['productividad_equipo'] ?? 0, 1); ?>%
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="flex flex-wrap items-center gap-4 text-sm text-gray-500">
                                    <div class="flex items-center">
                                        <i class="fas fa-project-diagram mr-2"></i>
                                        <span class="font-medium text-gray-700"><?php echo htmlspecialchars($reporte['proyecto_nombre'] ?? 'Sin proyecto'); ?></span>
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-user-circle mr-2"></i>
                                        <span class="font-medium text-gray-700"><?php echo htmlspecialchars($reporte['generado_por_nombre'] ?? 'Sistema'); ?></span>
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-calendar-alt mr-2"></i>
                                        <span><?php echo htmlspecialchars($reporte['fecha_generacion_formatted'] ?? date('d/m/Y H:i', strtotime($reporte['fecha_generacion'] ?? 'now'))); ?></span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex gap-2">
                                <a href="?accion=ver_reporte&id=<?php echo $reporte['id']; ?>" 
                                class="px-4 py-2 bg-gradient-to-r from-blue-50 to-blue-100 hover:from-blue-100 hover:to-blue-200 text-blue-600 rounded-lg font-medium flex items-center gap-2">
                                    <i class="fas fa-eye"></i>
                                    Ver
                                </a>
                                <?php if ($usuario_rol != 'miembro_equipo'): ?>
                                <a href="?accion=editar_reporte&id=<?php echo $reporte['id']; ?>" 
                                class="px-4 py-2 bg-gradient-to-r from-green-50 to-green-100 hover:from-green-100 hover:to-green-200 text-green-600 rounded-lg font-medium flex items-center gap-2">
                                    <i class="fas fa-edit"></i>
                                    Editar
                                </a>
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
                    Mostrando <?php echo count($reportes); ?> reportes
                </div>
                <div class="flex gap-2">
                    <button class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button class="px-4 py-2 bg-pink-600 text-white rounded-lg hover:bg-pink-700">1</button>
                    <button class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">2</button>
                    <button class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">3</button>
                    <button class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>
            <?php else: ?>
            <div class="text-center py-16">
                <div class="w-24 h-24 mx-auto bg-gradient-to-br from-blue-50 to-cyan-100 rounded-full flex items-center justify-center mb-6">
                    <i class="fas fa-chart-bar text-4xl text-gray-400"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-700 mb-3">No hay reportes de rendimiento</h3>
                <p class="text-gray-500 mb-6 max-w-md mx-auto">
                    <?php 
                    if ($usuario_rol == 'miembro_equipo') {
                        echo 'No hay reportes disponibles para visualizar.';
                    } else {
                        echo 'Comienza creando reportes de rendimiento para analizar el desempeño.';
                    }
                    ?>
                </p>
                <?php if ($usuario_rol != 'miembro_equipo'): ?>
                <a href="?accion=crear_reporte" 
                class="gradient-bg-proceso6 hover:opacity-90 text-white px-6 py-3 rounded-xl font-medium inline-flex items-center gap-2 shadow-lg hover-lift">
                    <i class="fas fa-plus"></i>
                    Crear primer reporte
                </a>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>

        <?php endif; ?>
        <!-- ========== FIN SECCIÓN REPORTES ========== -->

        <!-- ========== SECCIÓN: CREAR/EDITAR REPORTE ========== -->
        <?php if ($accion === 'crear_reporte' || $accion === 'editar_reporte'): ?>

        <div class="glass-card rounded-2xl p-6">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">
                        <?php echo $accion === 'crear_reporte' ? 'Crear Nuevo Reporte' : 'Editar Reporte'; ?>
                    </h2>
                    <p class="text-gray-600">Genera reportes periódicos de rendimiento de recursos</p>
                </div>
                <a href="?accion=reportes_rendimiento" 
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
                <input type="hidden" name="accion" value="<?php echo $accion === 'crear_reporte' ? 'crear_reporte' : 'actualizar_reporte'; ?>">
                <?php if (isset($reporte['id'])): ?>
                <input type="hidden" name="id" value="<?php echo $reporte['id']; ?>">
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
                                        <?php 
                                        $selected = false;
                                        if (isset($datos_form['proyecto_id']) && $datos_form['proyecto_id'] == $proyecto['id']) {
                                            $selected = true;
                                        } elseif (isset($reporte['proyecto_id']) && $reporte['proyecto_id'] == $proyecto['id']) {
                                            $selected = true;
                                        }
                                        echo $selected ? 'selected' : '';
                                        ?>>
                                        <?php echo htmlspecialchars($proyecto['nombre'] ?? ''); ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    
                    <!-- Período -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2 required">Período</label>
                        <select name="periodo" class="form-input" required>
                            <option value="">Seleccionar período...</option>
                            <?php if (isset($periodos)): ?>
                                <?php foreach ($periodos as $periodo): ?>
                                    <option value="<?php echo htmlspecialchars($periodo); ?>"
                                        <?php 
                                        $selected = false;
                                        if (isset($datos_form['periodo']) && $datos_form['periodo'] == $periodo) {
                                            $selected = true;
                                        } elseif (isset($reporte['periodo']) && $reporte['periodo'] == $periodo) {
                                            $selected = true;
                                        }
                                        echo $selected ? 'selected' : '';
                                        ?>>
                                        <?php echo ucfirst($periodo); ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Fechas -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2 required">Fecha Inicio</label>
                        <input type="date" name="fecha_inicio" class="form-input" required
                            value="<?php echo htmlspecialchars($datos_form['fecha_inicio'] ?? $reporte['fecha_inicio'] ?? date('Y-m-01')); ?>">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2 required">Fecha Fin</label>
                        <input type="date" name="fecha_fin" class="form-input" required
                            value="<?php echo htmlspecialchars($datos_form['fecha_fin'] ?? $reporte['fecha_fin'] ?? date('Y-m-t')); ?>">
                    </div>
                </div>
                
                <!-- Métricas de rendimiento -->
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 p-6 rounded-2xl">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Métricas de Rendimiento</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2 required">Eficiencia de Recursos (%)</label>
                            <div class="relative">
                                <input type="number" name="eficiencia_recursos" min="0" max="100" step="0.1" class="form-input pr-8" required
                                    value="<?php echo htmlspecialchars($datos_form['eficiencia_recursos'] ?? $reporte['eficiencia_recursos'] ?? '0'); ?>">
                                <span class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500">%</span>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2 required">Cumplimiento de Plazos (%)</label>
                            <div class="relative">
                                <input type="number" name="cumplimiento_plazos" min="0" max="100" step="0.1" class="form-input pr-8" required
                                    value="<?php echo htmlspecialchars($datos_form['cumplimiento_plazos'] ?? $reporte['cumplimiento_plazos'] ?? '0'); ?>">
                                <span class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500">%</span>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2 required">Variación Presupuesto ($)</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">$</span>
                                <input type="number" name="variacion_presupuesto" step="0.01" class="form-input pl-8" required
                                    value="<?php echo htmlspecialchars($datos_form['variacion_presupuesto'] ?? $reporte['variacion_presupuesto'] ?? '0'); ?>">
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2 required">Productividad Equipo (%)</label>
                            <div class="relative">
                                <input type="number" name="productividad_equipo" min="0" max="100" step="0.1" class="form-input pr-8" required
                                    value="<?php echo htmlspecialchars($datos_form['productividad_equipo'] ?? $reporte['productividad_equipo'] ?? '0'); ?>">
                                <span class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500">%</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Observaciones -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2 required">Observaciones</label>
                    <textarea name="observaciones" rows="6" class="form-input" required
                        placeholder="Describe el análisis de rendimiento, incluyendo hallazgos, tendencias y recomendaciones..."><?php echo htmlspecialchars($datos_form['observaciones'] ?? $reporte['observaciones'] ?? ''); ?></textarea>
                </div>
                
                <!-- Botones de acción -->
                <div class="flex flex-wrap gap-3 pt-6 border-t border-gray-200">
                    <?php if ($accion === 'crear_reporte'): ?>
                    <button type="submit" name="guardar_y_continuar" 
                            class="bg-gradient-to-r from-green-500 to-emerald-500 hover:opacity-90 text-white px-6 py-3 rounded-xl font-medium flex items-center gap-2 hover-lift shadow-lg">
                        <i class="fas fa-save"></i>
                        Guardar y crear otro
                    </button>
                    <?php endif; ?>
                    
                    <button type="submit" 
                            class="gradient-bg-proceso6 hover:opacity-90 text-white px-6 py-3 rounded-xl font-medium flex items-center gap-2 hover-lift shadow-lg">
                        <i class="fas <?php echo $accion === 'crear_reporte' ? 'fa-file-export' : 'fa-save'; ?>"></i>
                        <?php echo $accion === 'crear_reporte' ? 'Crear Reporte' : 'Guardar Cambios'; ?>
                    </button>
                    
                    <a href="?accion=reportes_rendimiento" 
                    class="px-6 py-3 bg-gradient-to-r from-gray-100 to-gray-200 hover:from-gray-200 hover:to-gray-300 text-gray-700 rounded-xl font-medium flex items-center gap-2 hover-lift">
                        <i class="fas fa-times"></i>
                        Cancelar
                    </a>
                    
                    <?php if (isset($reporte['id']) && $usuario_rol != 'miembro_equipo'): ?>
                    <button type="button" onclick="confirmarEliminarReporte(<?php echo $reporte['id']; ?>)" 
                            class="ml-auto px-6 py-3 bg-gradient-to-r from-red-500 to-rose-500 hover:opacity-90 text-white rounded-xl font-medium flex items-center gap-2 hover-lift shadow-lg">
                        <i class="fas fa-trash"></i>
                        Eliminar Reporte
                    </button>
                    <?php endif; ?>
                </div>
            </form>
            
            <?php if (isset($reporte['id'])): ?>
            <!-- Formulario oculto para eliminar -->
            <form method="POST" id="form-eliminar-reporte-<?php echo $reporte['id']; ?>" class="hidden">
                <input type="hidden" name="accion" value="eliminar_reporte">
                <input type="hidden" name="id" value="<?php echo $reporte['id']; ?>">
            </form>
            <?php endif; ?>
        </div>

        <script>
        function confirmarEliminarReporte(id) {
            if (confirm('¿Estás seguro de eliminar este reporte? Esta acción no se puede deshacer.')) {
                document.getElementById('form-eliminar-reporte-' + id).submit();
            }
        }
        </script>

        <?php endif; ?>
        <!-- ========== FIN SECCIÓN CREAR/EDITAR REPORTE ========== -->

        <!-- ========== SECCIÓN: VER REPORTE ========== -->
        <?php if ($accion === 'ver_reporte' && isset($reporte)): ?>

        <div class="glass-card rounded-2xl p-6">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Detalles del Reporte</h2>
                    <div class="text-gray-600">ID: #<?php echo htmlspecialchars($reporte['id'] ?? ''); ?></div>
                </div>
                <div class="flex flex-wrap gap-2">
                    <a href="?accion=reportes_rendimiento" 
                    class="px-4 py-2.5 bg-gradient-to-r from-gray-100 to-gray-200 hover:from-gray-200 hover:to-gray-300 text-gray-700 rounded-xl font-medium flex items-center gap-2 hover-lift">
                        <i class="fas fa-arrow-left"></i>
                        Volver
                    </a>
                    <?php if ($usuario_rol != 'miembro_equipo'): ?>
                    <a href="?accion=editar_reporte&id=<?php echo $reporte['id']; ?>" 
                    class="gradient-bg-proceso6 hover:opacity-90 text-white px-5 py-2.5 rounded-xl font-medium flex items-center gap-2 hover-lift shadow-lg">
                        <i class="fas fa-edit"></i>
                        Editar
                    </a>
                    <?php endif; ?>
                    <button onclick="window.print()" 
                            class="px-4 py-2.5 bg-gradient-to-r from-gray-100 to-gray-200 hover:from-gray-200 hover:to-gray-300 text-gray-700 rounded-xl font-medium flex items-center gap-2 hover-lift">
                        <i class="fas fa-print"></i>
                        Imprimir
                    </button>
                </div>
            </div>
            
            <!-- Cabecera del reporte -->
            <div class="bg-gradient-to-r from-blue-50 to-cyan-100 p-6 rounded-2xl mb-6">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-4">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 mb-2">
                            Reporte de Rendimiento <?php echo ucfirst($reporte['periodo'] ?? ''); ?>
                        </h1>
                        <div class="flex flex-wrap items-center gap-3">
                            <span class="status-badge <?php echo 'badge-periodo-' . ($reporte['periodo'] ?? 'mensual'); ?> text-base">
                                <i class="fas fa-calendar-alt mr-1"></i>
                                <?php echo ucfirst($reporte['periodo'] ?? 'mensual'); ?>
                            </span>
                            <span class="status-badge <?php echo ($reporte['variacion_presupuesto'] ?? 0) < 0 ? 'badge-favorable' : (($reporte['variacion_presupuesto'] ?? 0) > 0 ? 'badge-desfavorable' : 'badge-neutral'); ?> text-base">
                                <i class="fas fa-<?php echo ($reporte['variacion_presupuesto'] ?? 0) < 0 ? 'arrow-down' : (($reporte['variacion_presupuesto'] ?? 0) > 0 ? 'arrow-up' : 'equals'); ?> mr-1"></i>
                                <?php echo ($reporte['variacion_presupuesto'] ?? 0) < 0 ? 'Ahorro' : (($reporte['variacion_presupuesto'] ?? 0) > 0 ? 'Sobrecosto' : 'Neutral'); ?>
                            </span>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-sm text-gray-500">Fecha de generación</div>
                        <div class="font-medium text-gray-900">
                            <?php echo isset($reporte['fecha_generacion']) ? date('d/m/Y H:i', strtotime($reporte['fecha_generacion'])) : 'N/A'; ?>
                        </div>
                    </div>
                </div>
                
                <!-- Período y proyecto -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                    <div class="bg-white p-4 rounded-xl">
                        <div class="text-sm text-gray-500 mb-2">Período Reportado</div>
                        <div class="font-medium text-gray-900">
                            <?php echo isset($reporte['fecha_inicio']) ? date('d/m/Y', strtotime($reporte['fecha_inicio'])) : ''; ?> 
                            al <?php echo isset($reporte['fecha_fin']) ? date('d/m/Y', strtotime($reporte['fecha_fin'])) : ''; ?>
                        </div>
                    </div>
                    
                    <div class="bg-white p-4 rounded-xl">
                        <div class="text-sm text-gray-500 mb-2">Proyecto</div>
                        <div class="font-medium text-gray-900"><?php echo htmlspecialchars($reporte['proyecto_nombre'] ?? 'Sin proyecto'); ?></div>
                    </div>
                </div>
            </div>
            
            <!-- Métricas de rendimiento -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-gradient-to-r from-green-50 to-emerald-100 p-6 rounded-2xl text-center">
                    <div class="text-sm text-gray-600 mb-2">Eficiencia de Recursos</div>
                    <div class="text-3xl font-bold <?php echo ($reporte['eficiencia_recursos'] ?? 0) >= 80 ? 'text-green-600' : (($reporte['eficiencia_recursos'] ?? 0) >= 60 ? 'text-yellow-600' : 'text-red-600'); ?>">
                        <?php echo number_format($reporte['eficiencia_recursos'] ?? 0, 1); ?>%
                    </div>
                    <div class="progress-bar mt-3">
                        <div class="progress-fill bg-gradient-to-r from-green-400 to-emerald-500" 
                            style="width: <?php echo min(100, max(0, $reporte['eficiencia_recursos'] ?? 0)); ?>%"></div>
                    </div>
                </div>
                
                <div class="bg-gradient-to-r from-blue-50 to-cyan-100 p-6 rounded-2xl text-center">
                    <div class="text-sm text-gray-600 mb-2">Cumplimiento de Plazos</div>
                    <div class="text-3xl font-bold <?php echo ($reporte['cumplimiento_plazos'] ?? 0) >= 90 ? 'text-green-600' : (($reporte['cumplimiento_plazos'] ?? 0) >= 70 ? 'text-yellow-600' : 'text-red-600'); ?>">
                        <?php echo number_format($reporte['cumplimiento_plazos'] ?? 0, 1); ?>%
                    </div>
                    <div class="progress-bar mt-3">
                        <div class="progress-fill bg-gradient-to-r from-blue-400 to-cyan-500" 
                            style="width: <?php echo min(100, max(0, $reporte['cumplimiento_plazos'] ?? 0)); ?>%"></div>
                    </div>
                </div>
                
                <div class="bg-gradient-to-r from-<?php echo ($reporte['variacion_presupuesto'] ?? 0) < 0 ? 'green' : (($reporte['variacion_presupuesto'] ?? 0) > 0 ? 'red' : 'gray'); ?>-50 to-<?php echo ($reporte['variacion_presupuesto'] ?? 0) < 0 ? 'emerald' : (($reporte['variacion_presupuesto'] ?? 0) > 0 ? 'rose' : 'gray'); ?>-100 p-6 rounded-2xl text-center">
                    <div class="text-sm text-gray-600 mb-2">Variación Presupuesto</div>
                    <div class="text-3xl font-bold <?php echo ($reporte['variacion_presupuesto'] ?? 0) < 0 ? 'text-green-600' : (($reporte['variacion_presupuesto'] ?? 0) > 0 ? 'text-red-600' : 'text-gray-600'); ?>">
                        $<?php echo number_format($reporte['variacion_presupuesto'] ?? 0, 2); ?>
                    </div>
                    <div class="text-sm <?php echo ($reporte['variacion_presupuesto'] ?? 0) < 0 ? 'text-green-600' : (($reporte['variacion_presupuesto'] ?? 0) > 0 ? 'text-red-600' : 'text-gray-600'); ?> mt-2">
                        <?php echo ($reporte['variacion_presupuesto'] ?? 0) < 0 ? 'Ahorro' : (($reporte['variacion_presupuesto'] ?? 0) > 0 ? 'Sobrecosto' : 'Sin variación'); ?>
                    </div>
                </div>
                
                <div class="bg-gradient-to-r from-yellow-50 to-amber-100 p-6 rounded-2xl text-center">
                    <div class="text-sm text-gray-600 mb-2">Productividad del Equipo</div>
                    <div class="text-3xl font-bold <?php echo ($reporte['productividad_equipo'] ?? 0) >= 80 ? 'text-green-600' : (($reporte['productividad_equipo'] ?? 0) >= 60 ? 'text-yellow-600' : 'text-red-600'); ?>">
                        <?php echo number_format($reporte['productividad_equipo'] ?? 0, 1); ?>%
                    </div>
                    <div class="progress-bar mt-3">
                        <div class="progress-fill bg-gradient-to-r from-yellow-400 to-orange-500" 
                            style="width: <?php echo min(100, max(0, $reporte['productividad_equipo'] ?? 0)); ?>%"></div>
                    </div>
                </div>
            </div>
            
            <!-- Observaciones -->
            <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden mb-8">
                <div class="p-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Observaciones y Análisis</h3>
                    <div class="prose max-w-none">
                        <div class="whitespace-pre-wrap text-gray-700"><?php echo nl2br(htmlspecialchars($reporte['observaciones'] ?? 'Sin observaciones')); ?></div>
                    </div>
                </div>
            </div>
            
            <!-- Información del generador -->
            <div class="bg-gradient-to-r from-gray-50 to-gray-100 p-6 rounded-2xl">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-indigo-600 rounded-full flex items-center justify-center text-white font-bold">
                        <?php echo isset($reporte['generado_por_nombre']) ? strtoupper(substr($reporte['generado_por_nombre'], 0, 1)) : 'S'; ?>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Generado por</h3>
                        <p class="text-gray-600">
                            <?php echo htmlspecialchars($reporte['generado_por_nombre'] ?? 'Sistema'); ?>
                            <?php if (!empty($reporte['generado_por_email'])): ?>
                            • <?php echo htmlspecialchars($reporte['generado_por_email']); ?>
                            <?php endif; ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <?php endif; ?>
        <!-- ========== FIN SECCIÓN VER REPORTE ========== -->

        <!-- ========== SECCIÓN: ANÁLISIS ========== -->
        <?php if ($accion === 'analisis'): ?>
        
        <div class="glass-card rounded-2xl p-6">
            <!-- Encabezado -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Análisis de Tendencia</h2>
                    <p class="text-gray-600">Análisis estadístico y tendencias del control de recursos</p>
                </div>
                <div class="flex flex-wrap gap-2">
                    <form method="GET" class="flex gap-2">
                        <input type="hidden" name="accion" value="analisis">
                        <select name="proyecto_id" class="form-input" onchange="this.form.submit()">
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
                    <button onclick="window.print()" 
                            class="px-5 py-2.5 bg-gradient-to-r from-gray-100 to-gray-200 hover:from-gray-200 hover:to-gray-300 text-gray-700 rounded-xl font-medium flex items-center gap-2 hover-lift">
                        <i class="fas fa-print"></i>
                        Imprimir
                    </button>
                </div>
            </div>
            
            <?php if (isset($tendencias) && isset($historico) && isset($estadisticas)): ?>
            
            <!-- Resumen estadístico -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 p-6 rounded-2xl">
                    <div class="text-sm text-gray-600 mb-2">Promedio Variación</div>
                    <div class="text-2xl font-bold <?php echo ($estadisticas['variacion_total'] ?? 0) < 0 ? 'text-green-600' : (($estadisticas['variacion_total'] ?? 0) > 0 ? 'text-red-600' : 'text-gray-600'); ?>">
                        $<?php echo number_format($estadisticas['variacion_total'] ?? 0, 2); ?>
                    </div>
                    <div class="text-xs text-gray-500 mt-2">Acumulado total</div>
                </div>
                
                <div class="bg-gradient-to-r from-green-50 to-emerald-100 p-6 rounded-2xl">
                    <div class="text-sm text-gray-600 mb-2">Eficiencia Promedio</div>
                    <div class="text-2xl font-bold <?php echo ($estadisticas['eficiencia_recursos_promedio'] ?? 0) >= 80 ? 'text-green-600' : (($estadisticas['eficiencia_recursos_promedio'] ?? 0) >= 60 ? 'text-yellow-600' : 'text-red-600'); ?>">
                        <?php echo number_format($estadisticas['eficiencia_recursos_promedio'] ?? 0, 1); ?>%
                    </div>
                    <div class="text-xs text-gray-500 mt-2">Promedio histórico</div>
                </div>
                
                <div class="bg-gradient-to-r from-blue-50 to-cyan-100 p-6 rounded-2xl">
                    <div class="text-sm text-gray-600 mb-2">Cumplimiento Promedio</div>
                    <div class="text-2xl font-bold <?php echo ($estadisticas['cumplimiento_plazos_promedio'] ?? 0) >= 90 ? 'text-green-600' : (($estadisticas['cumplimiento_plazos_promedio'] ?? 0) >= 70 ? 'text-yellow-600' : 'text-red-600'); ?>">
                        <?php echo number_format($estadisticas['cumplimiento_plazos_promedio'] ?? 0, 1); ?>%
                    </div>
                    <div class="text-xs text-gray-500 mt-2">Promedio histórico</div>
                </div>
                
                <div class="bg-gradient-to-r from-yellow-50 to-amber-100 p-6 rounded-2xl">
                    <div class="text-sm text-gray-600 mb-2">Productividad Promedio</div>
                    <div class="text-2xl font-bold <?php echo ($estadisticas['productividad_equipo_promedio'] ?? 0) >= 80 ? 'text-green-600' : (($estadisticas['productividad_equipo_promedio'] ?? 0) >= 60 ? 'text-yellow-600' : 'text-red-600'); ?>">
                        <?php echo number_format($estadisticas['productividad_equipo_promedio'] ?? 0, 1); ?>%
                    </div>
                    <div class="text-xs text-gray-500 mt-2">Promedio histórico</div>
                </div>
            </div>
            
            <!-- Gráficos de análisis -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <!-- Gráfico de tendencias por tipo -->
                <div class="bg-white p-6 rounded-2xl border border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Tendencias por Tipo de Recurso</h3>
                    <div class="h-64">
                        <canvas id="tendenciasTipoChart"></canvas>
                    </div>
                </div>
                
                <!-- Gráfico de variación histórica -->
                <div class="bg-white p-6 rounded-2xl border border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Variación Promedio por Tipo</h3>
                    <div class="h-64">
                        <canvas id="variacionTipoChart"></canvas>
                    </div>
                </div>
            </div>
            
            <!-- Tabla de tendencias -->
            <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden mb-8">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Análisis por Tipo de Recurso</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Tipo de Recurso</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Total Controles</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Promedio Planificado</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Promedio Actual</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Promedio Variación</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Favorables</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Desfavorables</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Tendencia</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            <?php foreach($tendencias as $tendencia): ?>
                            <?php 
                            $tendencia_texto = '';
                            $tendencia_color = '';
                            if ($tendencia['promedio_variacion'] < 0) {
                                $tendencia_texto = 'Favorable';
                                $tendencia_color = 'text-green-600';
                            } elseif ($tendencia['promedio_variacion'] > 0) {
                                $tendencia_texto = 'Desfavorable';
                                $tendencia_color = 'text-red-600';
                            } else {
                                $tendencia_texto = 'Neutral';
                                $tendencia_color = 'text-gray-600';
                            }
                            ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <span class="status-badge <?php echo 'badge-' . ($tendencia['tipo_recurso'] ?? 'humano'); ?>">
                                        <?php echo ucfirst($tendencia['tipo_recurso'] ?? 'humano'); ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-medium text-gray-900"><?php echo $tendencia['total_controles'] ?? 0; ?></div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-medium text-gray-900">$<?php echo number_format($tendencia['promedio_planificado'] ?? 0, 2); ?></div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-medium text-gray-900">$<?php echo number_format($tendencia['promedio_actual'] ?? 0, 2); ?></div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-bold <?php echo $tendencia_color; ?>">
                                        $<?php echo number_format($tendencia['promedio_variacion'] ?? 0, 2); ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-center">
                                        <div class="font-medium text-green-600"><?php echo $tendencia['favorables'] ?? 0; ?></div>
                                        <div class="text-xs text-gray-500">
                                            <?php echo $tendencia['total_controles'] > 0 ? round(($tendencia['favorables'] / $tendencia['total_controles']) * 100, 1) : 0; ?>%
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-center">
                                        <div class="font-medium text-red-600"><?php echo $tendencia['desfavorables'] ?? 0; ?></div>
                                        <div class="text-xs text-gray-500">
                                            <?php echo $tendencia['total_controles'] > 0 ? round(($tendencia['desfavorables'] / $tendencia['total_controles']) * 100, 1) : 0; ?>%
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-medium <?php echo $tendencia_color; ?>">
                                        <?php echo $tendencia_texto; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Análisis histórico -->
            <div class="bg-gradient-to-r from-gray-50 to-gray-100 p-6 rounded-2xl">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">Evolución Histórica (Últimos 90 días)</h3>
                <div class="h-80">
                    <canvas id="historicoChart"></canvas>
                </div>
            </div>
            
            <!-- Script para gráficos de análisis -->
            <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Gráfico de tendencias por tipo
                const tendenciasCtx = document.getElementById('tendenciasTipoChart')?.getContext('2d');
                if (tendenciasCtx) {
                    const tipos = <?php echo json_encode(array_column($tendencias, 'tipo_recurso')); ?>;
                    const totales = <?php echo json_encode(array_column($tendencias, 'total_controles')); ?>;
                    
                    new Chart(tendenciasCtx, {
                        type: 'bar',
                        data: {
                            labels: tipos,
                            datasets: [{
                                label: 'Total Controles',
                                data: totales,
                                backgroundColor: [
                                    '#bee3f8', // humano
                                    '#fed7d7', // material
                                    '#c6f6d5', // equipo
                                    '#fbd38d', // financiero
                                    '#e9d8fd'  // tecnológico
                                ],
                                borderColor: [
                                    '#90cdf4',
                                    '#feb2b2',
                                    '#9ae6b4',
                                    '#f6ad55',
                                    '#d6bcfa'
                                ],
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    title: {
                                        display: true,
                                        text: 'Cantidad de Controles'
                                    }
                                },
                                x: {
                                    grid: {
                                        display: false
                                    }
                                }
                            },
                            plugins: {
                                legend: {
                                    display: false
                                }
                            }
                        }
                    });
                }
                
                // Gráfico de variación por tipo
                const variacionCtx = document.getElementById('variacionTipoChart')?.getContext('2d');
                if (variacionCtx) {
                    const tipos = <?php echo json_encode(array_column($tendencias, 'tipo_recurso')); ?>;
                    const variaciones = <?php echo json_encode(array_column($tendencias, 'promedio_variacion')); ?>;
                    
                    const colores = variaciones.map(val => val < 0 ? '#48bb78' : (val > 0 ? '#f56565' : '#a0aec0'));
                    
                    new Chart(variacionCtx, {
                        type: 'bar',
                        data: {
                            labels: tipos,
                            datasets: [{
                                label: 'Variación Promedio ($)',
                                data: variaciones,
                                backgroundColor: colores,
                                borderColor: colores.map(color => color.replace('0.8', '1')),
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: false,
                                    title: {
                                        display: true,
                                        text: 'Variación Promedio ($)'
                                    }
                                },
                                x: {
                                    grid: {
                                        display: false
                                    }
                                }
                            },
                            plugins: {
                                legend: {
                                    display: false
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            const valor = context.raw || 0;
                                            return `$${valor.toFixed(2)}`;
                                        }
                                    }
                                }
                            }
                        }
                    });
                }
                
                // Gráfico histórico
                const historicoCtx = document.getElementById('historicoChart')?.getContext('2d');
                if (historicoCtx && <?php echo !empty($historico) ? 'true' : 'false'; ?>) {
                    // Agrupar datos por fecha
                    const fechas = [...new Set(<?php echo json_encode(array_column($historico, 'fecha')); ?>)].sort();
                    const tipos = [...new Set(<?php echo json_encode(array_column($historico, 'tipo_recurso')); ?>)];
                    
                    // Crear datasets para cada tipo
                    const datasets = tipos.map(tipo => {
                        const datosTipo = <?php echo json_encode($historico); ?>.filter(item => item.tipo_recurso === tipo);
                        const datosPorFecha = fechas.map(fecha => {
                            const dato = datosTipo.find(item => item.fecha === fecha);
                            return dato ? dato.variacion_promedio : 0;
                        });
                        
                        const colores = {
                            'humano': '#63b3ed',
                            'material': '#fc8181',
                            'equipo': '#68d391',
                            'financiero': '#f6ad55',
                            'tecnologico': '#b794f4'
                        };
                        
                        return {
                            label: tipo.charAt(0).toUpperCase() + tipo.slice(1),
                            data: datosPorFecha,
                            borderColor: colores[tipo] || '#a0aec0',
                            backgroundColor: colores[tipo] ? colores[tipo].replace(')', ', 0.1)').replace('rgb', 'rgba') : 'rgba(160, 174, 192, 0.1)',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.4
                        };
                    });
                    
                    new Chart(historicoCtx, {
                        type: 'line',
                        data: {
                            labels: fechas.map(fecha => {
                                const d = new Date(fecha);
                                return `${d.getDate()}/${d.getMonth() + 1}`;
                            }),
                            datasets: datasets
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    title: {
                                        display: true,
                                        text: 'Variación Promedio ($)'
                                    }
                                },
                                x: {
                                    title: {
                                        display: true,
                                        text: 'Fecha'
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
                    <i class="fas fa-chart-line text-4xl text-gray-400"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-700 mb-3">No hay datos para análisis</h3>
                <p class="text-gray-500 mb-6 max-w-md mx-auto">Genera controles de recursos para ver análisis estadísticos y tendencias</p>
            </div>
            <?php endif; ?>
        </div>
        
        <?php endif; ?>
        <!-- ========== FIN SECCIÓN ANÁLISIS ========== -->
        
        <!-- ========== INFORMACIÓN DEL PROCESO PMBOK ========== -->
        <div class="mt-8 glass-card rounded-2xl p-6">
            <div class="flex flex-col md:flex-row items-start gap-6">
                <div class="w-16 h-16 bg-gradient-to-r from-pink-100 to-rose-100 rounded-xl flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-info-circle text-pink-600 text-2xl"></i>
                </div>
                <div class="flex-1">
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Proceso 6: Controlar los Recursos del Proyecto</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="bg-gradient-to-r from-pink-50 to-pink-100 p-5 rounded-xl">
                            <h4 class="font-semibold text-pink-800 mb-3 flex items-center gap-2">
                                <i class="fas fa-bullseye"></i>
                                Objetivo
                            </h4>
                            <p class="text-sm text-pink-700 leading-relaxed">
                                Monitorear el uso real de recursos, comparar con lo planificado, 
                                identificar desviaciones y tomar acciones correctivas para 
                                optimizar la utilización de recursos y asegurar el cumplimiento 
                                de los objetivos del proyecto.
                            </p>
                        </div>
                        
                        <div class="bg-gradient-to-r from-rose-50 to-rose-100 p-5 rounded-xl">
                            <h4 class="font-semibold text-rose-800 mb-3 flex items-center gap-2">
                                <i class="fas fa-tools"></i>
                                Herramientas y Técnicas
                            </h4>
                            <ul class="text-sm text-rose-700 space-y-1">
                                <li class="flex items-center gap-2"><i class="fas fa-chart-line text-xs"></i> Análisis de variación</li>
                                <li class="flex items-center gap-2"><i class="fas fa-balance-scale text-xs"></i> Medición de desempeño</li>
                                <li class="flex items-center gap-2"><i class="fas fa-exclamation-triangle text-xs"></i> Sistemas de alerta</li>
                                <li class="flex items-center gap-2"><i class="fas fa-file-alt text-xs"></i> Reportes de rendimiento</li>
                                <li class="flex items-center gap-2"><i class="fas fa-search text-xs"></i> Auditorías de recursos</li>
                            </ul>
                        </div>
                        
                        <div class="bg-gradient-to-r from-fuchsia-50 to-fuchsia-100 p-5 rounded-xl">
                            <h4 class="font-semibold text-fuchsia-800 mb-3 flex items-center gap-2">
                                <i class="fas fa-file-export"></i>
                                Salidas Principales
                            </h4>
                            <ul class="text-sm text-fuchsia-700 space-y-1">
                                <li class="flex items-center gap-2"><i class="fas fa-clipboard-check text-xs"></i> Informes de control</li>
                                <li class="flex items-center gap-2"><i class="fas fa-sync-alt text-xs"></i> Actualizaciones de planes</li>
                                <li class="flex items-center gap-2"><i class="fas fa-lightbulb text-xs"></i> Acciones correctivas</li>
                                <li class="flex items-center gap-2"><i class="fas fa-chart-bar text-xs"></i> Métricas de desempeño</li>
                                <li class="flex items-center gap-2"><i class="fas fa-history text-xs"></i> Lecciones aprendidas</li>
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
                    const today = new Date();
                    if (input.name === 'fecha_inicio') {
                        input.value = new Date(today.getFullYear(), today.getMonth(), 1).toISOString().split('T')[0];
                    } else if (input.name === 'fecha_fin') {
                        input.value = new Date(today.getFullYear(), today.getMonth() + 1, 0).toISOString().split('T')[0];
                    } else {
                        input.value = today.toISOString().split('T')[0];
                    }
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
            
            // Toggle para menú móvil
            const sidebarToggle = document.createElement('button');
            sidebarToggle.className = 'md:hidden fixed top-4 left-4 z-50 w-10 h-10 bg-pink-600 text-white rounded-full flex items-center justify-center shadow-lg';
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
<?php
// Vista unificada para el Proceso 2: Estimar Recursos
// Variables disponibles según la acción:
// - $accion: 'listar', 'crear', 'editar', 'ver', 'reportes'
// - $estimaciones, $proyectos, $recursosPlanificados, $estimacion, $estadisticas, etc.
// - $mensaje, $tipo_mensaje, $errores, $datos_form
// - $metodosEstimacion, $nivelesConfianza
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estimar Recursos | PMBOK 6</title>
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
        
        .gradient-bg-estimacion {
            background: linear-gradient(135deg, #4299e1 0%, #2b6cb0 100%);
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
            border-left-color: #4299e1;
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
            border-color: #4299e1;
            box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.1);
        }
        
        .radio-card {
            transition: all 0.2s ease;
            cursor: pointer;
        }
        
        .radio-card:hover {
            border-color: #4299e1;
            background-color: #f7fafc;
        }
        
        .radio-card.selected {
            border-color: #4299e1;
            background-color: #ebf8ff;
            box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.1);
        }
        
        .tab-estimacion {
            padding: 0.75rem 1.5rem;
            border-radius: 0.75rem;
            font-weight: 500;
            transition: all 0.2s ease;
        }
        
        .tab-estimacion.active {
            background: linear-gradient(135deg, #4299e1 0%, #2b6cb0 100%);
            color: white;
            box-shadow: 0 4px 20px rgba(66, 153, 225, 0.3);
        }
        
        .tab-estimacion:not(.active):hover {
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
<body class="bg-gradient-to-br from-gray-50 to-blue-50">
    
    <!-- Sidebar (debes ajustar la ruta según tu estructura) -->
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
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Estimar Recursos</h1>
                <p class="text-gray-600">Proceso 2 PMBOK 6 | Estimación de recursos de actividades</p>
            </div>
            <div class="flex items-center gap-3 bg-white rounded-xl px-4 py-3 shadow-sm border border-gray-200">
                <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold">
                    <?php echo isset($_SESSION['usuario']) ? strtoupper(substr($_SESSION['usuario']['nombre'], 0, 1)) : 'E'; ?>
                </div>
                <div>
                    <div class="text-sm text-gray-500">Estimador</div>
                    <div class="font-medium text-gray-800"><?php echo isset($_SESSION['usuario']) ? htmlspecialchars($_SESSION['usuario']['nombre']) : 'Estimador'; ?></div>
                </div>
            </div>
        </div>
        
        <!-- ========== INDICADORES DE PROCESO PMBOK ========== -->
        <div class="grid grid-cols-1 md:grid-cols-6 gap-4 mb-8">
            <div class="bg-white p-4 rounded-xl border border-gray-200 hover-lift">
                <div class="text-sm text-green-600 font-medium">Proceso 1</div>
                <div class="text-lg font-semibold">Planificar</div>
                <div class="mt-2 text-xs text-green-600 flex items-center">
                    <i class="fas fa-check-circle mr-1"></i> Completado
                </div>
            </div>
            
            <div class="gradient-bg-estimacion p-4 rounded-xl text-white hover-lift shadow-lg">
                <div class="text-sm font-medium">Proceso 2</div>
                <div class="text-lg font-semibold">Estimar</div>
                <div class="mt-2 text-xs flex items-center">
                    <i class="fas fa-chart-line mr-1"></i> En Progreso
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
        
        <!-- ========== NAVEGACIÓN INTERNA DEL PROCESO 2 ========== -->
        <div class="flex gap-3 mb-8 bg-white rounded-2xl p-2 shadow-sm border border-gray-200">
            <a href="?accion=listar" 
               class="tab-estimacion <?php echo ($accion === 'listar' || !isset($accion)) ? 'active' : ''; ?>">
                <i class="fas fa-list mr-2"></i>
                Lista de Estimaciones
            </a>
            <a href="?accion=crear" 
               class="tab-estimacion <?php echo $accion === 'crear' ? 'active' : ''; ?>">
                <i class="fas fa-plus mr-2"></i>
                Nueva Estimación
            </a>
            <a href="?accion=reportes" 
               class="tab-estimacion <?php echo $accion === 'reportes' ? 'active' : ''; ?>">
                <i class="fas fa-chart-bar mr-2"></i>
                Reportes
            </a>
        </div>
        
        <!-- ========== SECCIÓN: LISTAR ESTIMACIONES ========== -->
        <?php if ($accion === 'listar' || !isset($accion)): ?>
        
        <!-- Filtros -->
        <div class="mb-8 glass-card rounded-2xl p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Filtros de Estimaciones</h2>
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
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tipo de Recurso</label>
                    <select name="tipo_recurso" class="form-input w-full px-4 py-2.5 border border-gray-300 rounded-xl">
                        <option value="">Todos los tipos</option>
                        <?php 
                        $tiposRecursos = ['humano', 'material', 'equipo', 'financiero', 'tecnologico'];
                        foreach ($tiposRecursos as $tipo): ?>
                            <option value="<?php echo $tipo; ?>" 
                                <?php echo (isset($_GET['tipo_recurso']) && $_GET['tipo_recurso'] == $tipo) ? 'selected' : ''; ?>>
                                <?php echo ucfirst($tipo); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Método</label>
                    <input type="text" name="metodo_estimacion" 
                           value="<?php echo isset($_GET['metodo_estimacion']) ? htmlspecialchars($_GET['metodo_estimacion']) : ''; ?>"
                           class="form-input w-full px-4 py-2.5 border border-gray-300 rounded-xl"
                           placeholder="Buscar por método...">
                </div>
                
                <div class="flex items-end">
                    <button type="submit" 
                            class="w-full gradient-bg-estimacion hover:opacity-90 text-white px-4 py-2.5 rounded-xl font-medium flex items-center justify-center gap-2">
                        <i class="fas fa-filter"></i>
                        Aplicar Filtros
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Estadísticas rápidas -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="stat-card bg-white rounded-2xl p-6 border border-gray-200 shadow-sm hover-lift">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-gradient-to-r from-blue-50 to-blue-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-file-invoice-dollar text-blue-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Total Estimado</p>
                        <p class="text-2xl font-bold text-gray-800">
                            <?php 
                            $total = 0;
                            if (isset($estimaciones) && is_array($estimaciones)) {
                                foreach ($estimaciones as $est) {
                                    $total += $est['costo_real_total'] ?? 0;
                                }
                            }
                            echo '$' . number_format($total, 2);
                            ?>
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="stat-card bg-white rounded-2xl p-6 border border-gray-200 shadow-sm hover-lift">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-gradient-to-r from-green-50 to-green-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-check-circle text-green-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Estimaciones</p>
                        <p class="text-2xl font-bold text-gray-800"><?php echo isset($estimaciones) ? count($estimaciones) : 0; ?></p>
                    </div>
                </div>
            </div>
            
            <div class="stat-card bg-white rounded-2xl p-6 border border-gray-200 shadow-sm hover-lift">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-gradient-to-r from-yellow-50 to-yellow-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-chart-line text-yellow-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Método Principal</p>
                        <p class="text-lg font-semibold text-gray-800">
                            <?php 
                            if (isset($estimaciones) && !empty($estimaciones)) {
                                $metodos = array_column($estimaciones, 'metodo_estimacion');
                                $metodos = array_filter($metodos);
                                if (!empty($metodos)) {
                                    $metodoCounts = array_count_values($metodos);
                                    arsort($metodoCounts);
                                    echo htmlspecialchars(key($metodoCounts));
                                } else {
                                    echo 'N/A';
                                }
                            } else {
                                echo 'N/A';
                            }
                            ?>
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="stat-card bg-white rounded-2xl p-6 border border-gray-200 shadow-sm hover-lift">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-gradient-to-r from-purple-50 to-purple-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-user-tie text-purple-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Estimadores</p>
                        <p class="text-2xl font-bold text-gray-800">
                            <?php 
                            if (isset($estimaciones) && !empty($estimaciones)) {
                                $estimadores = array_column($estimaciones, 'estimador_id');
                                $estimadores = array_filter($estimadores);
                                if (!empty($estimadores)) {
                                    echo count(array_unique($estimadores));
                                } else {
                                    echo '0';
                                }
                            } else {
                                echo '0';
                            }
                            ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Tabla de estimaciones -->
        <div class="glass-card rounded-2xl p-6 mb-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Estimaciones de Recursos</h2>
                <a href="?accion=crear" 
                   class="gradient-bg-estimacion hover:opacity-90 text-white px-5 py-2.5 rounded-xl font-medium flex items-center gap-2 hover-lift shadow-lg">
                    <i class="fas fa-plus"></i>
                    Nueva Estimación
                </a>
            </div>
            
            <?php if (empty($estimaciones) || !is_array($estimaciones)): ?>
            <div class="text-center py-16">
                <div class="w-24 h-24 mx-auto bg-gradient-to-br from-blue-50 to-purple-50 rounded-full flex items-center justify-center mb-6">
                    <i class="fas fa-chart-line text-4xl text-gray-400"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-700 mb-3">No hay estimaciones registradas</h3>
                <p class="text-gray-500 mb-6 max-w-md mx-auto">Comienza creando tu primera estimación de recursos</p>
                <a href="?accion=crear" 
                   class="gradient-bg-estimacion hover:opacity-90 text-white px-6 py-3 rounded-xl font-medium inline-flex items-center gap-2 shadow-lg hover-lift">
                    <i class="fas fa-plus"></i>
                    Crear primera estimación
                </a>
            </div>
            <?php else: ?>
            <div class="overflow-x-auto rounded-xl border border-gray-200">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gradient-to-r from-blue-50 to-blue-100">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 uppercase">Proyecto / Recurso</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 uppercase">Método</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 uppercase">Estimación vs Planificación</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 uppercase">Confianza</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 uppercase">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        <?php foreach($estimaciones as $est): 
                            $costo_planificado_total = $est['costo_planificado_total'] ?? 0;
                            $costo_real_total = $est['costo_real_total'] ?? 0;
                            $variacion_porcentaje = ($costo_planificado_total > 0) 
                                ? (($costo_real_total - $costo_planificado_total) / $costo_planificado_total * 100) 
                                : 0;
                        ?>
                        <tr class="table-row-hover hover:bg-blue-50/30">
                            <td class="px-6 py-4">
                                <div class="flex items-start gap-4">
                                    <div class="w-12 h-12 bg-gradient-to-br from-blue-100 to-purple-100 rounded-xl flex items-center justify-center mt-1">
                                        <?php 
                                        $iconos = [
                                            'humano' => 'fa-user',
                                            'material' => 'fa-box',
                                            'equipo' => 'fa-tools',
                                            'financiero' => 'fa-money-bill',
                                            'tecnologico' => 'fa-laptop'
                                        ];
                                        $tipo_recurso = $est['tipo_recurso'] ?? 'material';
                                        $icono = $iconos[$tipo_recurso] ?? 'fa-cube';
                                        ?>
                                        <i class="fas <?php echo $icono; ?> text-blue-600"></i>
                                    </div>
                                    <div class="flex-1">
                                        <div class="font-semibold text-gray-900 mb-1"><?php echo htmlspecialchars($est['proyecto_nombre'] ?? 'Sin proyecto'); ?></div>
                                        <div class="text-sm text-gray-600 mb-2 line-clamp-2"><?php echo htmlspecialchars($est['recurso_descripcion'] ?? 'Sin descripción'); ?></div>
                                        <div class="flex items-center gap-3 text-xs">
                                            <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded-lg">
                                                <?php echo $est['cantidad_real'] ?? 0; ?> unidades
                                            </span>
                                            <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded-lg font-medium">
                                                $<?php echo isset($est['costo_real_total']) ? number_format($est['costo_real_total'], 2) : '0.00'; ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900 mb-1"><?php echo htmlspecialchars($est['metodo_estimacion'] ?? 'No especificado'); ?></div>
                                <div class="text-sm text-gray-500"><?php echo isset($est['fecha_estimacion']) ? date('d/m/Y', strtotime($est['fecha_estimacion'])) : 'N/A'; ?></div>
                                <div class="text-xs text-gray-400 mt-1">por <?php echo htmlspecialchars($est['estimador_nombre'] ?? 'Desconocido'); ?></div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="space-y-2">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Planificado:</span>
                                        <span class="font-medium">$<?php echo isset($est['costo_planificado_total']) ? number_format($est['costo_planificado_total'], 2) : '0.00'; ?></span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Estimado:</span>
                                        <span class="font-medium text-blue-600">$<?php echo isset($est['costo_real_total']) ? number_format($est['costo_real_total'], 2) : '0.00'; ?></span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Variación:</span>
                                        <span class="font-medium <?php echo $variacion_porcentaje < 0 ? 'text-green-600' : ($variacion_porcentaje > 0 ? 'text-red-600' : 'text-gray-600'); ?>">
                                            <?php echo ($variacion_porcentaje > 0 ? '+' : '') . number_format($variacion_porcentaje, 1); ?>%
                                        </span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <?php 
                                $confianza_colores = [
                                    'alto' => ['bg' => 'bg-green-100 text-green-800', 'icon' => 'fa-check-double'],
                                    'medio' => ['bg' => 'bg-yellow-100 text-yellow-800', 'icon' => 'fa-check'],
                                    'baja' => ['bg' => 'bg-red-100 text-red-800', 'icon' => 'fa-exclamation'],
                                    'bajo' => ['bg' => 'bg-red-100 text-red-800', 'icon' => 'fa-exclamation']
                                ];
                                $nivel_confianza = $est['nivel_confianza'] ?? 'medio';
                                $confianza = $confianza_colores[$nivel_confianza] ?? $confianza_colores['medio'];
                                ?>
                                <div class="status-badge <?php echo $confianza['bg']; ?>">
                                    <i class="fas <?php echo $confianza['icon']; ?> text-xs"></i>
                                    <?php echo ucfirst($nivel_confianza); ?>
                                </div>
                                <?php if (isset($est['estado_adquisicion']) && !empty($est['estado_adquisicion'])): ?>
                                <div class="mt-2">
                                    <span class="text-xs px-2 py-1 bg-purple-100 text-purple-700 rounded-lg">
                                        <?php echo ucfirst($est['estado_adquisicion']); ?>
                                    </span>
                                </div>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <a href="?accion=ver&id=<?php echo $est['id']; ?>" 
                                       class="w-10 h-10 bg-blue-50 hover:bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center hover-lift"
                                       title="Ver detalles">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="?accion=editar&id=<?php echo $est['id']; ?>" 
                                       class="w-10 h-10 bg-emerald-50 hover:bg-emerald-100 text-emerald-600 rounded-xl flex items-center justify-center hover-lift"
                                       title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" action="" 
                                          onsubmit="return confirm('¿Estás seguro de eliminar esta estimación?');">
                                        <input type="hidden" name="accion" value="eliminar">
                                        <input type="hidden" name="id" value="<?php echo $est['id']; ?>">
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
        <!-- ========== FIN SECCIÓN LISTAR ========== -->
        
        <!-- ========== SECCIÓN: CREAR ESTIMACIÓN ========== -->
        <?php if ($accion === 'crear'): ?>
        
        <div class="max-w-4xl mx-auto">
            <div class="glass-card rounded-2xl p-8">
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-14 h-14 bg-gradient-to-br from-green-100 to-blue-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-calculator text-2xl text-green-600"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Crear Nueva Estimación</h2>
                        <p class="text-gray-600">Estimar los recursos necesarios para actividades del proyecto</p>
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
                
                <form method="POST" action="?accion=guardar" class="space-y-6">
                    <input type="hidden" name="accion" value="guardar">
                    <?php if (isset($_SESSION['csrf_token'])): ?>
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    <?php endif; ?>
                    
                    <!-- Paso 1: Seleccionar recurso planificado -->
                    <div class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-2xl p-6 mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Paso 1: Seleccionar Recurso Planificado</h3>
                        
                        <div>
                            <label class="block text-sm font-semibold text-gray-800 mb-3">Recurso Planificado *</label>
                            <select name="planificacion_id" required 
                                    class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    onchange="cargarDatosPlanificacion(this.value)">
                                <option value="">Seleccionar recurso planificado</option>
                                <?php if (isset($recursosPlanificados) && is_array($recursosPlanificados)): ?>
                                    <?php foreach ($recursosPlanificados as $recurso): ?>
                                    <option value="<?php echo htmlspecialchars($recurso['id']); ?>" 
                                            <?php echo (isset($datos_form['planificacion_id']) && $datos_form['planificacion_id'] == $recurso['id']) ? 'selected' : ''; ?>>
                                        [<?php echo htmlspecialchars($recurso['proyecto_nombre'] ?? ''); ?>] 
                                        <?php echo htmlspecialchars($recurso['descripcion'] ?? ''); ?> 
                                        (<?php echo ucfirst($recurso['tipo_recurso'] ?? ''); ?>)
                                    </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        
                        <!-- Información del recurso seleccionado -->
                        <div id="info-recurso" class="mt-4 hidden">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="bg-white p-4 rounded-xl border border-gray-200">
                                    <div class="text-sm text-gray-500">Cantidad Planificada</div>
                                    <div class="text-xl font-bold text-gray-900" id="cantidad-planificada">0</div>
                                </div>
                                <div class="bg-white p-4 rounded-xl border border-gray-200">
                                    <div class="text-sm text-gray-500">Costo Unitario Planificado</div>
                                    <div class="text-xl font-bold text-gray-900" id="costo-unitario-plan">$0.00</div>
                                </div>
                                <div class="bg-white p-4 rounded-xl border border-gray-200">
                                    <div class="text-sm text-gray-500">Costo Total Planificado</div>
                                    <div class="text-xl font-bold text-gray-900" id="costo-total-plan">$0.00</div>
                                </div>
                                <div class="bg-white p-4 rounded-xl border border-gray-200">
                                    <div class="text-sm text-gray-500">Prioridad</div>
                                    <div class="text-xl font-bold text-gray-900" id="prioridad-recurso">Media</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Paso 2: Datos de estimación -->
                    <div class="bg-gradient-to-r from-emerald-50 to-green-50 rounded-2xl p-6 mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Paso 2: Datos de Estimación</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-3">Cantidad Real Estimada *</label>
                                <input type="number" name="cantidad_real" min="1" required 
                                       value="<?php echo isset($datos_form['cantidad_real']) ? htmlspecialchars($datos_form['cantidad_real']) : ''; ?>"
                                       class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl"
                                       placeholder="Ej: 5"
                                       oninput="calcularCostoTotal()">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-3">Costo Real Unitario *</label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500">$</span>
                                    <input type="number" name="costo_real_unitario" step="0.01" min="0" required 
                                           value="<?php echo isset($datos_form['costo_real_unitario']) ? htmlspecialchars($datos_form['costo_real_unitario']) : ''; ?>"
                                           class="form-input w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl"
                                           placeholder="0.00"
                                           oninput="calcularCostoTotal()">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Resultado cálculo -->
                        <div class="mt-6 bg-white p-4 rounded-xl border-2 border-blue-200">
                            <div class="flex justify-between items-center">
                                <div>
                                    <div class="text-sm text-gray-500">Costo Total Estimado</div>
                                    <div class="text-2xl font-bold text-blue-600" id="costo-total-estimado">$0.00</div>
                                </div>
                                <div>
                                    <div class="text-sm text-gray-500">Variación vs Planificación</div>
                                    <div class="text-lg font-bold" id="variacion-estimacion">0.00%</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Paso 3: Métodos y confianza -->
                    <div class="bg-gradient-to-r from-amber-50 to-orange-50 rounded-2xl p-6 mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Paso 3: Método y Confianza</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-3">Método de Estimación *</label>
                                <select name="metodo_estimacion" required 
                                        class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl">
                                    <option value="">Seleccionar método</option>
                                    <?php 
                                    $metodosEstimacion = [
                                        'Juicio de expertos',
                                        'Estimación por analogía',
                                        'Estimación paramétrica',
                                        'Estimación bottom-up',
                                        'Estimación three-point',
                                        'Análisis de reserva',
                                        'Estimación de costos de calidad'
                                    ];
                                    foreach ($metodosEstimacion as $metodo): ?>
                                    <option value="<?php echo htmlspecialchars($metodo); ?>"
                                        <?php echo (isset($datos_form['metodo_estimacion']) && $datos_form['metodo_estimacion'] == $metodo) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($metodo); ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-3">Nivel de Confianza *</label>
                                <div class="flex gap-3">
                                    <?php 
                                    $nivelesConfianza = [
                                        'alto' => ['color' => 'bg-green-100 border-green-300 text-green-800', 'icon' => 'fa-check-double'],
                                        'medio' => ['color' => 'bg-yellow-100 border-yellow-300 text-yellow-800', 'icon' => 'fa-check'],
                                        'baja' => ['color' => 'bg-red-100 border-red-300 text-red-800', 'icon' => 'fa-exclamation']
                                    ];
                                    foreach ($nivelesConfianza as $valor => $estilo): ?>
                                    <label class="radio-card flex-1 p-4 border-2 rounded-xl <?php echo $estilo['color']; ?> 
                                           <?php echo (isset($datos_form['nivel_confianza']) && $datos_form['nivel_confianza'] == $valor) ? 'selected' : ''; ?>">
                                        <input type="radio" name="nivel_confianza" value="<?php echo $valor; ?>" 
                                               class="hidden" 
                                               <?php echo (isset($datos_form['nivel_confianza']) && $datos_form['nivel_confianza'] == $valor) ? 'checked' : ''; ?>
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
                        
                        <div class="mt-6">
                            <label class="block text-sm font-semibold text-gray-800 mb-3">Observaciones</label>
                            <textarea name="observaciones" rows="3" 
                                      class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl"
                                      placeholder="Observaciones adicionales sobre la estimación..."><?php echo isset($datos_form['observaciones']) ? htmlspecialchars($datos_form['observaciones']) : ''; ?></textarea>
                        </div>
                    </div>
                    
                    <!-- Fecha -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-800 mb-3">Fecha de Estimación</label>
                        <input type="date" name="fecha_estimacion" 
                               value="<?php echo isset($datos_form['fecha_estimacion']) ? htmlspecialchars($datos_form['fecha_estimacion']) : date('Y-m-d'); ?>"
                               class="form-input px-4 py-3 border border-gray-300 rounded-xl">
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
                                        class="gradient-bg-estimacion hover:opacity-90 text-white px-8 py-3 rounded-xl font-medium shadow-md hover-lift flex items-center gap-2">
                                    <i class="fas fa-check"></i>
                                    Guardar Estimación
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <script>
            // Datos de recursos planificados
            const recursosData = {
                <?php if (isset($recursosPlanificados) && is_array($recursosPlanificados)): ?>
                    <?php foreach ($recursosPlanificados as $recurso): ?>
                    "<?php echo $recurso['id']; ?>": {
                        cantidad: <?php echo $recurso['cantidad_estimada'] ?? 0; ?>,
                        costoUnitario: <?php echo $recurso['costo_unitario_estimado'] ?? 0; ?>,
                        costoTotal: <?php echo $recurso['costo_total_estimado'] ?? 0; ?>,
                        prioridad: "<?php echo ucfirst($recurso['prioridad'] ?? 'media'); ?>"
                    },
                    <?php endforeach; ?>
                <?php endif; ?>
            };
            
            console.log("Recursos cargados en JavaScript:", Object.keys(recursosData).length);
            
            function cargarDatosPlanificacion(id) {
                const infoDiv = document.getElementById('info-recurso');
                console.log("Cargando datos para planificación ID:", id);
                if (id && recursosData[id]) {
                    const data = recursosData[id];
                    console.log("Datos encontrados:", data);
                    document.getElementById('cantidad-planificada').textContent = data.cantidad;
                    document.getElementById('costo-unitario-plan').textContent = '$' + data.costoUnitario.toFixed(2);
                    document.getElementById('costo-total-plan').textContent = '$' + data.costoTotal.toFixed(2);
                    document.getElementById('prioridad-recurso').textContent = data.prioridad;
                    infoDiv.classList.remove('hidden');
                    
                    // Pre-cargar valores si los campos están vacíos
                    const cantidadInput = document.querySelector('input[name="cantidad_real"]');
                    const costoInput = document.querySelector('input[name="costo_real_unitario"]');
                    
                    if (cantidadInput && !cantidadInput.value) {
                        cantidadInput.value = data.cantidad;
                    }
                    if (costoInput && !costoInput.value) {
                        costoInput.value = data.costoUnitario;
                    }
                    
                    calcularCostoTotal();
                } else {
                    console.log("No se encontraron datos para ID:", id);
                    infoDiv.classList.add('hidden');
                }
            }
            
            function calcularCostoTotal() {
                const cantidad = parseFloat(document.querySelector('input[name="cantidad_real"]').value) || 0;
                const costoUnitario = parseFloat(document.querySelector('input[name="costo_real_unitario"]').value) || 0;
                const costoTotal = cantidad * costoUnitario;
                
                document.getElementById('costo-total-estimado').textContent = '$' + costoTotal.toFixed(2);
                
                // Calcular variación
                const planificacionId = document.querySelector('select[name="planificacion_id"]').value;
                if (planificacionId && recursosData[planificacionId]) {
                    const costoPlanificado = recursosData[planificacionId].costoTotal;
                    const variacion = ((costoTotal - costoPlanificado) / costoPlanificado) * 100;
                    
                    const variacionSpan = document.getElementById('variacion-estimacion');
                    variacionSpan.textContent = variacion.toFixed(2) + '%';
                    variacionSpan.className = 'text-lg font-bold ' + 
                        (variacion < 0 ? 'text-green-600' : (variacion > 0 ? 'text-red-600' : 'text-gray-600'));
                    
                    if (variacion > 0) {
                        variacionSpan.innerHTML = '<i class="fas fa-arrow-up mr-1"></i>' + variacionSpan.textContent;
                    } else if (variacion < 0) {
                        variacionSpan.innerHTML = '<i class="fas fa-arrow-down mr-1"></i>' + variacionSpan.textContent;
                    }
                }
            }
            
            // Inicializar si hay un recurso seleccionado
            document.addEventListener('DOMContentLoaded', function() {
                console.log("Página cargada, recursos disponibles:", Object.keys(recursosData).length);
                const select = document.querySelector('select[name="planificacion_id"]');
                if (select && select.value) {
                    cargarDatosPlanificacion(select.value);
                }
                
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
                
                // Inicializar cálculo si hay valores en los campos
                calcularCostoTotal();
            });
        </script>
        
        <?php endif; ?>
        <!-- ========== FIN SECCIÓN CREAR ========== -->
        
        <!-- ========== SECCIÓN: VER DETALLE ========== -->
        <?php if ($accion === 'ver' && isset($estimacion)): ?>

        <div class="max-w-4xl mx-auto">
            <div class="glass-card rounded-2xl p-8">
                <!-- Header -->
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-14 h-14 bg-gradient-to-br from-blue-100 to-purple-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-eye text-2xl text-blue-600"></i>
                    </div>
                    <div class="flex-1">
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">Detalle de Estimación</h2>
                        <p class="text-gray-600">ID: <?php echo htmlspecialchars($estimacion['id']); ?> | Fecha: <?php echo isset($estimacion['fecha_estimacion']) ? date('d/m/Y', strtotime($estimacion['fecha_estimacion'])) : 'N/A'; ?></p>
                    </div>
                    <div class="flex gap-3">
                        <a href="?accion=listar" class="px-4 py-2.5 border border-gray-300 text-gray-700 hover:bg-gray-50 rounded-xl font-medium">
                            <i class="fas fa-arrow-left mr-2"></i> Volver
                        </a>
                        <?php if (($usuario['id'] == $estimacion['estimador_id'] || $usuario_rol == 'administrador') && $usuario_rol != 'miembro_equipo'): ?>
                        <a href="?accion=editar&id=<?php echo $estimacion['id']; ?>" 
                        class="gradient-bg-estimacion hover:opacity-90 text-white px-4 py-2.5 rounded-xl font-medium">
                            <i class="fas fa-edit mr-2"></i> Editar
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Información principal -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-2xl p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Información del Proyecto</h3>
                        <div class="space-y-3">
                            <div>
                                <div class="text-sm text-gray-500">Proyecto</div>
                                <div class="text-lg font-semibold text-gray-900"><?php echo htmlspecialchars($estimacion['proyecto_nombre'] ?? 'Sin proyecto'); ?></div>
                            </div>
                            <div>
                                <div class="text-sm text-gray-500">Recurso Planificado</div>
                                <div class="text-lg font-semibold text-gray-900"><?php echo htmlspecialchars($estimacion['recurso_descripcion'] ?? 'Sin descripción'); ?></div>
                                <div class="text-sm text-gray-600 mt-1">
                                    <span class="status-badge bg-blue-100 text-blue-800">
                                        <i class="fas fa-tag text-xs"></i>
                                        <?php echo ucfirst($estimacion['tipo_recurso'] ?? 'material'); ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gradient-to-r from-purple-50 to-purple-100 rounded-2xl p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Información de Estimación</h3>
                        <div class="space-y-3">
                            <div>
                                <div class="text-sm text-gray-500">Estimador</div>
                                <div class="text-lg font-semibold text-gray-900"><?php echo htmlspecialchars($estimacion['estimador_nombre'] ?? 'Desconocido'); ?></div>
                                <div class="text-sm text-gray-600"><?php echo htmlspecialchars($estimacion['estimador_email'] ?? ''); ?></div>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <div class="text-sm text-gray-500">Método</div>
                                    <div class="font-medium text-gray-900"><?php echo htmlspecialchars($estimacion['metodo_estimacion'] ?? 'No especificado'); ?></div>
                                </div>
                                <div>
                                    <div class="text-sm text-gray-500">Confianza</div>
                                    <?php 
                                    $confianza_colores = [
                                        'alto' => ['bg' => 'bg-green-100 text-green-800', 'icon' => 'fa-check-double'],
                                        'medio' => ['bg' => 'bg-yellow-100 text-yellow-800', 'icon' => 'fa-check'],
                                        'baja' => ['bg' => 'bg-red-100 text-red-800', 'icon' => 'fa-exclamation'],
                                        'bajo' => ['bg' => 'bg-red-100 text-red-800', 'icon' => 'fa-exclamation']
                                    ];
                                    $nivel_confianza = $estimacion['nivel_confianza'] ?? 'medio';
                                    $confianza = $confianza_colores[$nivel_confianza] ?? $confianza_colores['medio'];
                                    ?>
                                    <div class="status-badge <?php echo $confianza['bg']; ?>">
                                        <i class="fas <?php echo $confianza['icon']; ?> text-xs"></i>
                                        <?php echo ucfirst($nivel_confianza); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Comparación Planificación vs Estimación -->
                <div class="bg-gradient-to-r from-emerald-50 to-green-50 rounded-2xl p-6 mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-6">Comparación: Planificación vs Estimación</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Planificación -->
                        <div class="bg-white rounded-xl p-6 border border-gray-200">
                            <h4 class="font-semibold text-gray-900 mb-4 text-center">Planificación Original</h4>
                            <div class="space-y-4">
                                <div class="text-center">
                                    <div class="text-3xl font-bold text-gray-900"><?php echo $estimacion['cantidad_planificada'] ?? 0; ?></div>
                                    <div class="text-sm text-gray-500">Cantidad Planificada</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-3xl font-bold text-gray-900">$<?php echo isset($estimacion['costo_planificado_unitario']) ? number_format($estimacion['costo_planificado_unitario'], 2) : '0.00'; ?></div>
                                    <div class="text-sm text-gray-500">Costo Unitario</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-3xl font-bold text-gray-900">$<?php echo isset($estimacion['costo_planificado_total']) ? number_format($estimacion['costo_planificado_total'], 2) : '0.00'; ?></div>
                                    <div class="text-sm text-gray-500">Costo Total</div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Estimación -->
                        <div class="bg-white rounded-xl p-6 border-2 border-blue-200">
                            <h4 class="font-semibold text-gray-900 mb-4 text-center">Estimación Real</h4>
                            <div class="space-y-4">
                                <div class="text-center">
                                    <div class="text-3xl font-bold text-blue-600"><?php echo $estimacion['cantidad_real'] ?? 0; ?></div>
                                    <div class="text-sm text-gray-500">Cantidad Estimada</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-3xl font-bold text-blue-600">$<?php echo isset($estimacion['costo_real_unitario']) ? number_format($estimacion['costo_real_unitario'], 2) : '0.00'; ?></div>
                                    <div class="text-sm text-gray-500">Costo Unitario</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-3xl font-bold text-blue-600">$<?php echo isset($estimacion['costo_real_total']) ? number_format($estimacion['costo_real_total'], 2) : '0.00'; ?></div>
                                    <div class="text-sm text-gray-500">Costo Total</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Variación -->
                    <?php 
                    $cantidad_planificada = $estimacion['cantidad_planificada'] ?? 1;
                    $cantidad_real = $estimacion['cantidad_real'] ?? 0;
                    $costo_planificado_total = $estimacion['costo_planificado_total'] ?? 1;
                    $costo_real_total = $estimacion['costo_real_total'] ?? 0;
                    
                    $variacion_cantidad = $cantidad_planificada > 0 ? (($cantidad_real - $cantidad_planificada) / $cantidad_planificada) * 100 : 0;
                    $variacion_costo_total = $costo_planificado_total > 0 ? (($costo_real_total - $costo_planificado_total) / $costo_planificado_total) * 100 : 0;
                    ?>
                    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="text-center p-4 rounded-xl <?php echo $variacion_cantidad == 0 ? 'bg-gray-100' : ($variacion_cantidad > 0 ? 'bg-red-50 border border-red-200' : 'bg-green-50 border border-green-200'); ?>">
                            <div class="text-sm text-gray-500 mb-1">Variación en Cantidad</div>
                            <div class="text-2xl font-bold <?php echo $variacion_cantidad == 0 ? 'text-gray-700' : ($variacion_cantidad > 0 ? 'text-red-600' : 'text-green-600'); ?>">
                                <?php echo ($variacion_cantidad > 0 ? '+' : '') . number_format($variacion_cantidad, 1); ?>%
                            </div>
                        </div>
                        <div class="text-center p-4 rounded-xl <?php echo $variacion_costo_total == 0 ? 'bg-gray-100' : ($variacion_costo_total > 0 ? 'bg-red-50 border border-red-200' : 'bg-green-50 border border-green-200'); ?>">
                            <div class="text-sm text-gray-500 mb-1">Variación en Costo Total</div>
                            <div class="text-2xl font-bold <?php echo $variacion_costo_total == 0 ? 'text-gray-700' : ($variacion_costo_total > 0 ? 'text-red-600' : 'text-green-600'); ?>">
                                <?php echo ($variacion_costo_total > 0 ? '+' : '') . number_format($variacion_costo_total, 1); ?>%
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Observaciones -->
                <?php if (!empty($estimacion['observaciones'])): ?>
                <div class="bg-gradient-to-r from-amber-50 to-orange-50 rounded-2xl p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Observaciones</h3>
                    <div class="bg-white p-4 rounded-xl border border-gray-200">
                        <p class="text-gray-700"><?php echo nl2br(htmlspecialchars($estimacion['observaciones'])); ?></p>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <?php endif; ?>
        <!-- ========== FIN SECCIÓN VER ========== -->
        
        <!-- ========== SECCIÓN: EDITAR ESTIMACIÓN ========== -->
        <?php if ($accion === 'editar' && isset($estimacion)): ?>

        <div class="max-w-4xl mx-auto">
            <div class="glass-card rounded-2xl p-8">
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-14 h-14 bg-gradient-to-br from-emerald-100 to-green-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-edit text-2xl text-emerald-600"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Editar Estimación</h2>
                        <p class="text-gray-600">ID: <?php echo htmlspecialchars($estimacion['id']); ?> | Fecha: <?php echo isset($estimacion['fecha_estimacion']) ? date('d/m/Y', strtotime($estimacion['fecha_estimacion'])) : 'N/A'; ?></p>
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
                
                <form method="POST" action="?accion=actualizar" class="space-y-6">
                    <input type="hidden" name="id" value="<?php echo $estimacion['id']; ?>">
                    <input type="hidden" name="accion" value="actualizar">
                    <?php if (isset($_SESSION['csrf_token'])): ?>
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    <?php endif; ?>
                    
                    <!-- CAMPO OCULTO AGREGADO PARA SOLUCIONAR EL ERROR -->
                    <input type="hidden" name="planificacion_id" value="<?php echo $estimacion['planificacion_id']; ?>">
                    
                    <!-- Información del recurso (solo lectura) -->
                    <div class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-2xl p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Información del Recurso</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <div class="text-sm text-gray-500">Proyecto</div>
                                <div class="text-lg font-semibold text-gray-900"><?php echo htmlspecialchars($estimacion['proyecto_nombre'] ?? 'Sin proyecto'); ?></div>
                            </div>
                            <div>
                                <div class="text-sm text-gray-500">Recurso</div>
                                <div class="text-lg font-semibold text-gray-900"><?php echo htmlspecialchars($estimacion['recurso_descripcion'] ?? 'Sin descripción'); ?></div>
                            </div>
                            <div>
                                <div class="text-sm text-gray-500">Tipo</div>
                                <span class="status-badge bg-blue-100 text-blue-800">
                                    <?php echo ucfirst($estimacion['tipo_recurso'] ?? 'material'); ?>
                                </span>
                            </div>
                            <div>
                                <div class="text-sm text-gray-500">Planificación Original</div>
                                <div class="font-medium text-gray-900">$<?php echo isset($estimacion['costo_planificado_total']) ? number_format($estimacion['costo_planificado_total'], 2) : '0.00'; ?></div>
                            </div>
                            <div>
                                <div class="text-sm text-gray-500">ID Planificación</div>
                                <div class="font-medium text-gray-900"><?php echo htmlspecialchars($estimacion['planificacion_id']); ?></div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Datos de estimación -->
                    <div class="bg-gradient-to-r from-emerald-50 to-green-50 rounded-2xl p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Datos de Estimación</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-3">Cantidad Real Estimada *</label>
                                <input type="number" name="cantidad_real" min="1" required 
                                    value="<?php echo isset($datos_form['cantidad_real']) ? $datos_form['cantidad_real'] : $estimacion['cantidad_real']; ?>"
                                    class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl"
                                    oninput="calcularCostoTotalEditar()">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-3">Costo Real Unitario *</label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500">$</span>
                                    <input type="number" name="costo_real_unitario" step="0.01" min="0" required 
                                        value="<?php echo isset($datos_form['costo_real_unitario']) ? $datos_form['costo_real_unitario'] : $estimacion['costo_real_unitario']; ?>"
                                        class="form-input w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl"
                                        oninput="calcularCostoTotalEditar()">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Resultado cálculo -->
                        <div class="mt-6 bg-white p-4 rounded-xl border-2 border-blue-200">
                            <div class="flex justify-between items-center">
                                <div>
                                    <div class="text-sm text-gray-500">Costo Total Estimado</div>
                                    <div class="text-2xl font-bold text-blue-600" id="costo-total-estimado-editar">
                                        $<?php echo isset($estimacion['costo_real_total']) ? number_format($estimacion['costo_real_total'], 2) : '0.00'; ?>
                                    </div>
                                </div>
                                <div>
                                    <div class="text-sm text-gray-500">Variación vs Planificación</div>
                                    <div class="text-lg font-bold" id="variacion-estimacion-editar">
                                        <?php 
                                        $costo_planificado_total = $estimacion['costo_planificado_total'] ?? 1;
                                        $costo_real_total = $estimacion['costo_real_total'] ?? 0;
                                        $variacion = $costo_planificado_total > 0 ? (($costo_real_total - $costo_planificado_total) / $costo_planificado_total) * 100 : 0;
                                        echo number_format($variacion, 2) . '%';
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Métodos y confianza -->
                    <div class="bg-gradient-to-r from-amber-50 to-orange-50 rounded-2xl p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Método y Confianza</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-3">Método de Estimación *</label>
                                <select name="metodo_estimacion" required 
                                        class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl">
                                    <option value="">Seleccionar método</option>
                                    <?php 
                                    $metodosEstimacion = [
                                        'Juicio de expertos',
                                        'Estimación por analogía',
                                        'Estimación paramétrica',
                                        'Estimación bottom-up',
                                        'Estimación three-point',
                                        'Análisis de reserva',
                                        'Estimación de costos de calidad'
                                    ];
                                    foreach ($metodosEstimacion as $metodo): 
                                        $selected = (isset($datos_form['metodo_estimacion']) && $datos_form['metodo_estimacion'] == $metodo) || 
                                                (!isset($datos_form['metodo_estimacion']) && $estimacion['metodo_estimacion'] == $metodo);
                                    ?>
                                    <option value="<?php echo htmlspecialchars($metodo); ?>" <?php echo $selected ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($metodo); ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-3">Nivel de Confianza *</label>
                                <div class="flex gap-3">
                                    <?php 
                                    $nivelesConfianza = [
                                        'alto' => ['color' => 'bg-green-100 border-green-300 text-green-800', 'icon' => 'fa-check-double'],
                                        'medio' => ['color' => 'bg-yellow-100 border-yellow-300 text-yellow-800', 'icon' => 'fa-check'],
                                        'baja' => ['color' => 'bg-red-100 border-red-300 text-red-800', 'icon' => 'fa-exclamation']
                                    ];
                                    foreach ($nivelesConfianza as $valor => $estilo): 
                                        $selected = (isset($datos_form['nivel_confianza']) && $datos_form['nivel_confianza'] == $valor) || 
                                                (!isset($datos_form['nivel_confianza']) && $estimacion['nivel_confianza'] == $valor);
                                    ?>
                                    <label class="radio-card flex-1 p-4 border-2 rounded-xl <?php echo $estilo['color']; ?> 
                                        <?php echo $selected ? 'selected' : ''; ?>">
                                        <input type="radio" name="nivel_confianza" value="<?php echo $valor; ?>" 
                                            class="hidden" <?php echo $selected ? 'checked' : ''; ?> required>
                                        <div class="text-center">
                                            <i class="fas <?php echo $estilo['icon']; ?> text-lg mb-2"></i>
                                            <div class="font-medium"><?php echo ucfirst($valor); ?></div>
                                        </div>
                                    </label>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-6">
                            <label class="block text-sm font-semibold text-gray-800 mb-3">Observaciones</label>
                            <textarea name="observaciones" rows="3" 
                                    class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl"><?php 
                                echo isset($datos_form['observaciones']) ? htmlspecialchars($datos_form['observaciones']) : htmlspecialchars($estimacion['observaciones'] ?? ''); 
                            ?></textarea>
                        </div>
                        
                        <div class="mt-6">
                            <label class="block text-sm font-semibold text-gray-800 mb-3">Fecha de Estimación</label>
                            <input type="date" name="fecha_estimacion" 
                                value="<?php echo isset($datos_form['fecha_estimacion']) ? $datos_form['fecha_estimacion'] : ($estimacion['fecha_estimacion'] ?? date('Y-m-d')); ?>"
                                class="form-input px-4 py-3 border border-gray-300 rounded-xl">
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
                                <button type="submit" 
                                        class="gradient-bg-estimacion hover:opacity-90 text-white px-8 py-3 rounded-xl font-medium shadow-md hover-lift flex items-center gap-2">
                                    <i class="fas fa-save"></i>
                                    Guardar Cambios
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <script>
            function calcularCostoTotalEditar() {
                const cantidad = parseFloat(document.querySelector('input[name="cantidad_real"]').value) || 0;
                const costoUnitario = parseFloat(document.querySelector('input[name="costo_real_unitario"]').value) || 0;
                const costoTotal = cantidad * costoUnitario;
                
                document.getElementById('costo-total-estimado-editar').textContent = '$' + costoTotal.toFixed(2);
                
                // Calcular variación
                const costoPlanificado = <?php echo $estimacion['costo_planificado_total'] ?? 1; ?>;
                const variacion = ((costoTotal - costoPlanificado) / costoPlanificado) * 100;
                
                const variacionSpan = document.getElementById('variacion-estimacion-editar');
                variacionSpan.textContent = variacion.toFixed(2) + '%';
                variacionSpan.className = 'text-lg font-bold ' + 
                    (variacion < 0 ? 'text-green-600' : (variacion > 0 ? 'text-red-600' : 'text-gray-600'));
                
                if (variacion > 0) {
                    variacionSpan.innerHTML = '<i class="fas fa-arrow-up mr-1"></i>' + variacionSpan.textContent;
                } else if (variacion < 0) {
                    variacionSpan.innerHTML = '<i class="fas fa-arrow-down mr-1"></i>' + variacionSpan.textContent;
                }
            }
            
            // Efecto para radio cards
            document.addEventListener('DOMContentLoaded', function() {
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
                
                // Inicializar cálculo
                calcularCostoTotalEditar();
            });
        </script>

        <?php endif; ?>
        <!-- ========== FIN SECCIÓN EDITAR ========== -->
        
        <!-- ========== SECCIÓN: REPORTES ========== -->
        <?php if ($accion === 'reportes'): ?>

        <div class="mb-8 glass-card rounded-2xl p-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Reportes de Estimaciones</h2>
            
            <!-- Filtros de reportes -->
            <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-2xl p-6 mb-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Filtrar Reportes</h3>
                <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <input type="hidden" name="accion" value="reportes">
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Proyecto</label>
                        <select name="proyecto_id" 
                                class="form-input w-full px-4 py-2.5 border border-gray-300 rounded-xl">
                            <option value="">Todos los proyectos</option>
                            <?php if (isset($proyectos)): ?>
                                <?php foreach ($proyectos as $proyecto): ?>
                                    <option value="<?php echo htmlspecialchars($proyecto['id']); ?>" 
                                        <?php echo (isset($_GET['proyecto_id']) && $_GET['proyecto_id'] == $proyecto['id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($proyecto['nombre']); ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Periodo</label>
                        <select name="periodo" class="form-input w-full px-4 py-2.5 border border-gray-300 rounded-xl">
                            <option value="mes">Último mes</option>
                            <option value="trimestre">Último trimestre</option>
                            <option value="semestre">Último semestre</option>
                            <option value="anio">Último año</option>
                            <option value="todo">Todo el periodo</option>
                        </select>
                    </div>
                    
                    <div class="flex items-end">
                        <button type="submit" 
                                class="w-full gradient-bg-estimacion hover:opacity-90 text-white px-4 py-2.5 rounded-xl font-medium flex items-center justify-center gap-2">
                            <i class="fas fa-chart-bar"></i>
                            Generar Reporte
                        </button>
                    </div>
                </form>
            </div>
            
            <?php 
            // Procesar datos para obtener estadísticas
            $estadisticas_totales = [
                'total_estimaciones' => 0,
                'costo_total_estimado' => 0,
                'variacion_promedio' => 0,
                'distribucion_tipos' => [],
                'metodos_mas_usados' => [],
                'niveles_confianza' => [
                    'alto' => 0,
                    'medio' => 0,
                    'bajo' => 0
                ]
            ];
            
            // Necesitamos obtener los datos de tipo de recurso y métodos
            // Como no vienen en $estadisticas, intentaremos obtenerlos de otra manera
            if (isset($estimaciones) && is_array($estimaciones)) {
                // Usar las estimaciones si están disponibles
                foreach ($estimaciones as $est) {
                    $estadisticas_totales['total_estimaciones']++;
                    $estadisticas_totales['costo_total_estimado'] += $est['costo_real_total'] ?? 0;
                    
                    // Distribución por tipo de recurso
                    $tipo = $est['tipo_recurso'] ?? 'material';
                    if (!isset($estadisticas_totales['distribucion_tipos'][$tipo])) {
                        $estadisticas_totales['distribucion_tipos'][$tipo] = [
                            'cantidad' => 0,
                            'costo_total' => 0
                        ];
                    }
                    $estadisticas_totales['distribucion_tipos'][$tipo]['cantidad']++;
                    $estadisticas_totales['distribucion_tipos'][$tipo]['costo_total'] += $est['costo_real_total'] ?? 0;
                    
                    // Métodos más usados
                    $metodo = $est['metodo_estimacion'] ?? 'No especificado';
                    if (!isset($estadisticas_totales['metodos_mas_usados'][$metodo])) {
                        $estadisticas_totales['metodos_mas_usados'][$metodo] = [
                            'metodo' => $metodo,
                            'cantidad' => 0
                        ];
                    }
                    $estadisticas_totales['metodos_mas_usados'][$metodo]['cantidad']++;
                }
            } elseif (isset($estadisticas) && is_array($estadisticas)) {
                // Usar los datos de $estadisticas si están disponibles
                foreach ($estadisticas as $est) {
                    $estadisticas_totales['total_estimaciones'] += $est['total_estimaciones'] ?? 0;
                    $estadisticas_totales['costo_total_estimado'] += $est['costo_total_estimado'] ?? 0;
                    
                    // Métodos más usados (si están en los datos)
                    if (isset($est['metodo_estimacion'])) {
                        $metodo = $est['metodo_estimacion'];
                        if (!isset($estadisticas_totales['metodos_mas_usados'][$metodo])) {
                            $estadisticas_totales['metodos_mas_usados'][$metodo] = [
                                'metodo' => $metodo,
                                'cantidad' => 0
                            ];
                        }
                        $estadisticas_totales['metodos_mas_usados'][$metodo]['cantidad'] += $est['total_estimaciones'] ?? 1;
                    }
                    
                    // Niveles de confianza (si están en los datos)
                    if (isset($est['alto_confianza'])) $estadisticas_totales['niveles_confianza']['alto'] += $est['alto_confianza'];
                    if (isset($est['medio_confianza'])) $estadisticas_totales['niveles_confianza']['medio'] += $est['medio_confianza'];
                    if (isset($est['bajo_confianza'])) $estadisticas_totales['niveles_confianza']['bajo'] += $est['bajo_confianza'];
                }
            }
            
            // Calcular variación promedio basada en $comparacion
            if (isset($comparacion) && is_array($comparacion)) {
                $total_variacion = 0;
                $contador_variacion = 0;
                
                foreach ($comparacion as $item) {
                    if (isset($item['variacion_porcentaje'])) {
                        $total_variacion += abs($item['variacion_porcentaje']);
                        $contador_variacion++;
                    }
                }
                
                if ($contador_variacion > 0) {
                    $estadisticas_totales['variacion_promedio'] = $total_variacion / $contador_variacion;
                }
            }
            
            // Convertir arrays asociativos a indexados para facilitar el uso
            if (!empty($estadisticas_totales['metodos_mas_usados'])) {
                $metodos_array = array_values($estadisticas_totales['metodos_mas_usados']);
                usort($metodos_array, function($a, $b) {
                    return $b['cantidad'] - $a['cantidad'];
                });
                $estadisticas_totales['metodos_mas_usados'] = $metodos_array;
            }
            
            // Verificar si hay datos para mostrar
            $hay_datos_estadisticas = ($estadisticas_totales['total_estimaciones'] > 0);
            ?>
            
            <?php if ($hay_datos_estadisticas): ?>
            
            <!-- Estadísticas generales -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="stat-card bg-white rounded-2xl p-6 border border-gray-200 shadow-sm hover-lift">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 bg-gradient-to-r from-blue-50 to-blue-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-chart-line text-blue-600 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Estimaciones Totales</p>
                            <p class="text-2xl font-bold text-gray-800">
                                <?php echo $estadisticas_totales['total_estimaciones']; ?>
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="stat-card bg-white rounded-2xl p-6 border border-gray-200 shadow-sm hover-lift">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 bg-gradient-to-r from-green-50 to-green-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-money-bill-wave text-green-600 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Costo Total Estimado</p>
                            <p class="text-2xl font-bold text-gray-800">
                                $<?php echo number_format($estadisticas_totales['costo_total_estimado'], 2); ?>
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="stat-card bg-white rounded-2xl p-6 border border-gray-200 shadow-sm hover-lift">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 bg-gradient-to-r from-yellow-50 to-yellow-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-balance-scale text-yellow-600 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Variación Promedio</p>
                            <p class="text-2xl font-bold text-gray-800">
                                <?php echo number_format($estadisticas_totales['variacion_promedio'], 1) . '%'; ?>
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="stat-card bg-white rounded-2xl p-6 border border-gray-200 shadow-sm hover-lift">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 bg-gradient-to-r from-purple-50 to-purple-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-chart-pie text-purple-600 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Precisión</p>
                            <p class="text-2xl font-bold text-gray-800">
                                <?php 
                                // Calcular precisión basada en variación
                                $precision = 0;
                                if ($estadisticas_totales['variacion_promedio'] > 0) {
                                    $precision = max(0, 100 - ($estadisticas_totales['variacion_promedio'] / 2));
                                } else {
                                    $precision = 100;
                                }
                                echo number_format($precision, 1) . '%';
                                ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Gráfico de comparación -->
            <div class="glass-card rounded-2xl p-6 mb-8">
                <h3 class="text-xl font-bold text-gray-900 mb-6">Comparación: Planificado vs Estimado</h3>
                
                <?php if (isset($comparacion) && is_array($comparacion) && count($comparacion) > 0): ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gradient-to-r from-blue-50 to-blue-100">
                            <tr>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 uppercase">Proyecto</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 uppercase">Planificado</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 uppercase">Estimado</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 uppercase">Variación</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 uppercase">Estado</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            <?php foreach($comparacion as $item): 
                                // Saltar proyectos sin estimaciones
                                if (empty($item['total_estimaciones']) || $item['total_estimaciones'] == 0) {
                                    continue;
                                }
                                
                                $costo_planificado = $item['costo_total_planificado'] ?? 0;
                                $costo_estimado = $item['costo_total_estimado'] ?? 0;
                                $total_estimaciones = $item['total_estimaciones'] ?? 0;
                                $variacion = $item['variacion_porcentaje'] ?? 0;
                            ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="font-medium text-gray-900"><?php echo htmlspecialchars($item['proyecto_nombre'] ?? 'Sin nombre'); ?></div>
                                    <div class="text-sm text-gray-500"><?php echo $total_estimaciones; ?> estimaciones</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-lg font-semibold text-gray-700">$<?php echo number_format($costo_planificado, 2); ?></div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-lg font-semibold text-blue-600">$<?php echo number_format($costo_estimado, 2); ?></div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <span class="text-lg font-bold <?php echo $variacion < 0 ? 'text-green-600' : ($variacion > 0 ? 'text-red-600' : 'text-gray-600'); ?>">
                                            <?php echo ($variacion > 0 ? '+' : '') . number_format($variacion, 1); ?>%
                                        </span>
                                        <?php if ($variacion > 0): ?>
                                        <i class="fas fa-arrow-up ml-2 text-red-500"></i>
                                        <?php elseif ($variacion < 0): ?>
                                        <i class="fas fa-arrow-down ml-2 text-green-500"></i>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <?php 
                                    $estado = '';
                                    $color = '';
                                    $variacion_abs = abs($variacion);
                                    
                                    if ($variacion_abs <= 5) {
                                        $estado = 'Precisa';
                                        $color = 'bg-green-100 text-green-800';
                                    } elseif ($variacion_abs <= 15) {
                                        $estado = 'Aceptable';
                                        $color = 'bg-yellow-100 text-yellow-800';
                                    } else {
                                        $estado = 'Crítica';
                                        $color = 'bg-red-100 text-red-800';
                                    }
                                    ?>
                                    <span class="status-badge <?php echo $color; ?>">
                                        <?php echo $estado; ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <div class="text-center py-12">
                    <div class="w-24 h-24 mx-auto bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center mb-6">
                        <i class="fas fa-chart-bar text-4xl text-gray-400"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-700 mb-3">No hay datos para mostrar</h3>
                    <p class="text-gray-500 mb-6">No se encontraron proyectos con estimaciones para el periodo seleccionado</p>
                </div>
                <?php endif; ?>
            </div>
            
            <!-- Niveles de confianza -->
            <div class="glass-card rounded-2xl p-6 mb-8">
                <h3 class="text-xl font-bold text-gray-900 mb-6">Niveles de Confianza</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <?php 
                    $colores_confianza = [
                        'alto' => ['bg' => 'bg-green-100 border-green-300', 'text' => 'text-green-800', 'icon' => 'fa-check-double'],
                        'medio' => ['bg' => 'bg-yellow-100 border-yellow-300', 'text' => 'text-yellow-800', 'icon' => 'fa-check'],
                        'bajo' => ['bg' => 'bg-red-100 border-red-300', 'text' => 'text-red-800', 'icon' => 'fa-exclamation']
                    ];
                    
                    foreach ($colores_confianza as $nivel => $estilo): 
                        $cantidad = $estadisticas_totales['niveles_confianza'][$nivel] ?? 0;
                        $porcentaje = ($estadisticas_totales['total_estimaciones'] > 0) ? 
                            ($cantidad / $estadisticas_totales['total_estimaciones'] * 100) : 0;
                    ?>
                    <div class="bg-white rounded-xl p-6 border-2 <?php echo $estilo['bg']; ?> hover-lift">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-12 h-12 <?php echo $estilo['bg']; ?> rounded-xl flex items-center justify-center">
                                <i class="fas <?php echo $estilo['icon']; ?> <?php echo $estilo['text']; ?> text-xl"></i>
                            </div>
                            <div>
                                <div class="text-lg font-semibold <?php echo $estilo['text']; ?>"><?php echo ucfirst($nivel); ?></div>
                                <div class="text-sm text-gray-500">Nivel de Confianza</div>
                            </div>
                        </div>
                        
                        <div class="mb-2">
                            <div class="text-sm text-gray-500 mb-1">Cantidad</div>
                            <div class="text-2xl font-bold <?php echo $estilo['text']; ?>">
                                <?php echo $cantidad; ?>
                            </div>
                        </div>
                        
                        <div>
                            <div class="text-sm text-gray-500 mb-1">Porcentaje</div>
                            <div class="text-lg font-semibold <?php echo $estilo['text']; ?>">
                                <?php echo number_format($porcentaje, 1); ?>%
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <!-- Métodos de estimación más usados -->
            <div class="glass-card rounded-2xl p-6 mb-8">
                <h3 class="text-xl font-bold text-gray-900 mb-6">Métodos de Estimación Más Utilizados</h3>
                
                <?php if (!empty($estadisticas_totales['metodos_mas_usados'])): ?>
                <div class="space-y-6">
                    <?php 
                    $total_metodos = array_sum(array_column($estadisticas_totales['metodos_mas_usados'], 'cantidad'));
                    foreach($estadisticas_totales['metodos_mas_usados'] as $metodo):
                        $porcentaje = ($metodo['cantidad'] / $total_metodos) * 100;
                    ?>
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <div class="font-medium text-gray-900"><?php echo htmlspecialchars($metodo['metodo']); ?></div>
                            <div class="text-sm text-gray-500">
                                <?php echo $metodo['cantidad']; ?> usos (<?php echo number_format($porcentaje, 1); ?>%)
                            </div>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div class="bg-blue-600 h-2.5 rounded-full" style="width: <?php echo $porcentaje; ?>%"></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php else: ?>
                <div class="text-center py-8">
                    <i class="fas fa-clipboard-list text-5xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500">No hay datos de métodos de estimación disponibles</p>
                </div>
                <?php endif; ?>
            </div>
            
            <!-- Resumen general -->
            <div class="glass-card rounded-2xl p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-6">Resumen General</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="bg-gradient-to-r from-blue-50 to-blue-100 p-6 rounded-xl">
                        <h4 class="font-semibold text-blue-800 mb-3">Proyectos Analizados</h4>
                        <div class="text-3xl font-bold text-blue-700 mb-2">
                            <?php 
                            $proyectos_analizados = 0;
                            if (isset($comparacion) && is_array($comparacion)) {
                                foreach($comparacion as $item) {
                                    if (isset($item['total_estimaciones']) && $item['total_estimaciones'] > 0) {
                                        $proyectos_analizados++;
                                    }
                                }
                            }
                            echo $proyectos_analizados;
                            ?>
                        </div>
                        <p class="text-sm text-blue-600">de un total de <?php echo isset($comparacion) ? count($comparacion) : 0; ?> proyectos</p>
                    </div>
                    
                    <div class="bg-gradient-to-r from-green-50 to-green-100 p-6 rounded-xl">
                        <h4 class="font-semibold text-green-800 mb-3">Estimaciones Realizadas</h4>
                        <div class="text-3xl font-bold text-green-700 mb-2">
                            <?php echo $estadisticas_totales['total_estimaciones']; ?>
                        </div>
                        <p class="text-sm text-green-600">estimaciones registradas</p>
                    </div>
                    
                    <div class="bg-gradient-to-r from-purple-50 to-purple-100 p-6 rounded-xl">
                        <h4 class="font-semibold text-purple-800 mb-3">Inversión Total</h4>
                        <div class="text-3xl font-bold text-purple-700 mb-2">
                            $<?php echo number_format($estadisticas_totales['costo_total_estimado'], 2); ?>
                        </div>
                        <p class="text-sm text-purple-600">costo estimado total</p>
                    </div>
                </div>
            </div>
            
            <?php else: ?>
            <div class="text-center py-16">
                <div class="w-24 h-24 mx-auto bg-gradient-to-br from-blue-50 to-purple-50 rounded-full flex items-center justify-center mb-6">
                    <i class="fas fa-chart-bar text-4xl text-gray-400"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-700 mb-3">No hay datos de reportes</h3>
                <p class="text-gray-500 mb-6">No se encontraron estimaciones para generar reportes</p>
                <a href="?accion=listar" 
                class="gradient-bg-estimacion hover:opacity-90 text-white px-6 py-3 rounded-xl font-medium inline-flex items-center gap-2 shadow-lg hover-lift">
                    <i class="fas fa-arrow-left"></i>
                    Volver a estimaciones
                </a>
            </div>
            <?php endif; ?>
        </div>

        <?php endif; ?>
        <!-- ========== FIN SECCIÓN REPORTES ========== -->
        
        <!-- ========== INFORMACIÓN DEL PROCESO PMBOK ========== -->
        <div class="mt-8 glass-card rounded-2xl p-6">
            <div class="flex items-start gap-4">
                <div class="w-14 h-14 bg-gradient-to-r from-blue-100 to-purple-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-info-circle text-blue-600 text-xl"></i>
                </div>
                <div class="flex-1">
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Proceso 2: Estimar los Recursos de las Actividades</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-gradient-to-r from-blue-50 to-blue-100 p-4 rounded-xl">
                            <h4 class="font-semibold text-blue-800 mb-2">Objetivo</h4>
                            <p class="text-sm text-blue-700">Estimar los tipos y cantidades de materiales, personas, equipos o suministros necesarios para realizar cada actividad.</p>
                        </div>
                        <div class="bg-gradient-to-r from-purple-50 to-purple-100 p-4 rounded-xl">
                            <h4 class="font-semibold text-purple-800 mb-2">Herramientas</h4>
                            <ul class="text-sm text-purple-700 list-disc pl-5">
                                <li>Juicio de expertos</li>
                                <li>Estimación bottom-up</li>
                                <li>Análisis de datos</li>
                                <li>Software de estimación</li>
                            </ul>
                        </div>
                        <div class="bg-gradient-to-r from-emerald-50 to-green-100 p-4 rounded-xl">
                            <h4 class="font-semibold text-emerald-800 mb-2">Salidas</h4>
                            <ul class="text-sm text-emerald-700 list-disc pl-5">
                                <li>Estimaciones de recursos</li>
                                <li>Bases de las estimaciones</li>
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
            console.log("Script global cargado");
            
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
            
            // Actualizar variaciones en tiempo real
            const variaciones = document.querySelectorAll('[id*="variacion"]');
            variaciones.forEach(variacion => {
                if (variacion.textContent.includes('+')) {
                    variacion.innerHTML = '<i class="fas fa-arrow-up mr-1"></i>' + variacion.textContent;
                } else if (variacion.textContent.includes('-')) {
                    variacion.innerHTML = '<i class="fas fa-arrow-down mr-1"></i>' + variacion.textContent;
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
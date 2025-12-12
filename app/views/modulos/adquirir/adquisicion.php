<?php
// Vista unificada para el Proceso 3: Adquirir Recursos
// Variables disponibles según la acción:
// - $accion: 'listar', 'crear', 'editar', 'ver', 'reportes'
// - $adquisiciones, $proyectos, $estimaciones, $adquisicion, $estadisticas, etc.
// - $mensaje, $tipo_mensaje, $errores, $datos_form
// - $metodosAdquisicion, $estados
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adquirir Recursos | PMBOK 6</title>
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
        
        .gradient-bg-adquisicion {
            background: linear-gradient(135deg, #4299e1 0%, #2b6cb0 100%);
        }
        
        .gradient-bg-proceso3 {
            background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%);
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
            border-left-color: #ed8936;
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
            border-color: #ed8936;
            box-shadow: 0 0 0 3px rgba(237, 137, 54, 0.1);
        }
        
        .radio-card {
            transition: all 0.2s ease;
            cursor: pointer;
        }
        
        .radio-card:hover {
            border-color: #ed8936;
            background-color: #f7fafc;
        }
        
        .radio-card.selected {
            border-color: #ed8936;
            background-color: #fffaf0;
            box-shadow: 0 0 0 3px rgba(237, 137, 54, 0.1);
        }
        
        .tab-adquisicion {
            padding: 0.75rem 1.5rem;
            border-radius: 0.75rem;
            font-weight: 500;
            transition: all 0.2s ease;
        }
        
        .tab-adquisicion.active {
            background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%);
            color: white;
            box-shadow: 0 4px 20px rgba(237, 137, 54, 0.3);
        }
        
        .tab-adquisicion:not(.active):hover {
            background-color: #edf2f7;
        }
        
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .timeline-item {
            position: relative;
            padding-left: 2rem;
            margin-bottom: 1.5rem;
        }
        
        .timeline-item:before {
            content: '';
            position: absolute;
            left: 0;
            top: 0.5rem;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #ed8936;
        }
        
        .timeline-item:after {
            content: '';
            position: absolute;
            left: 5px;
            top: 1.5rem;
            bottom: -1.5rem;
            width: 2px;
            background: #e2e8f0;
        }
        
        .timeline-item:last-child:after {
            display: none;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-orange-50">
    
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
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Adquirir Recursos</h1>
                <p class="text-gray-600">Proceso 3 PMBOK 6 | Adquisición de recursos para el proyecto</p>
            </div>
            <div class="flex items-center gap-3 bg-white rounded-xl px-4 py-3 shadow-sm border border-gray-200">
                <div class="w-10 h-10 bg-gradient-to-r from-orange-500 to-red-600 rounded-full flex items-center justify-center text-white font-bold">
                    <?php echo isset($_SESSION['usuario']) ? strtoupper(substr($_SESSION['usuario']['nombre'], 0, 1)) : 'A'; ?>
                </div>
                <div>
                    <div class="text-sm text-gray-500">Responsable</div>
                    <div class="font-medium text-gray-800"><?php echo isset($_SESSION['usuario']) ? htmlspecialchars($_SESSION['usuario']['nombre']) : 'Responsable'; ?></div>
                </div>
            </div>
        </div>
        
        <!-- ========== INDICADORES DE PROCESO PMBOK ========== -->
        <div class="grid grid-cols-1 md:grid-cols-6 gap-4 mb-8">
            <div class="bg-white p-4 rounded-xl border border-gray-200 opacity-70">
                <div class="text-sm text-gray-400">Proceso 1</div>
                <div class="text-lg font-semibold text-gray-400">Planificar</div>
                <div class="mt-2 text-xs text-green-600 flex items-center">
                    <i class="fas fa-check-circle mr-1"></i> Completado
                </div>
            </div>
            
            <div class="bg-white p-4 rounded-xl border border-gray-200 opacity-70">
                <div class="text-sm text-gray-400">Proceso 2</div>
                <div class="text-lg font-semibold text-gray-400">Estimar</div>
                <div class="mt-2 text-xs text-green-600 flex items-center">
                    <i class="fas fa-check-circle mr-1"></i> Completado
                </div>
            </div>
            
            <div class="gradient-bg-proceso3 p-4 rounded-xl text-white hover-lift shadow-lg">
                <div class="text-sm font-medium">Proceso 3</div>
                <div class="text-lg font-semibold">Adquirir</div>
                <div class="mt-2 text-xs flex items-center">
                    <i class="fas fa-shopping-cart mr-1"></i> En Progreso
                </div>
            </div>
            
            <?php for($i = 4; $i <= 6; $i++): ?>
            <div class="bg-white p-4 rounded-xl border border-gray-200 opacity-70">
                <div class="text-sm text-gray-400">Proceso <?php echo $i; ?></div>
                <div class="text-lg font-semibold text-gray-400">
                    <?php 
                    $nombres = [4 => 'Desarrollar', 5 => 'Dirigir', 6 => 'Controlar'];
                    echo $nombres[$i];
                    ?>
                </div>
                <div class="mt-2 text-xs text-gray-400">
                    <i class="fas fa-lock mr-1"></i> Bloqueado
                </div>
            </div>
            <?php endfor; ?>
        </div>
        
        <!-- ========== NAVEGACIÓN INTERNA DEL PROCESO 3 ========== -->
        <div class="flex gap-3 mb-8 bg-white rounded-2xl p-2 shadow-sm border border-gray-200">
            <a href="?accion=listar" 
               class="tab-adquisicion <?php echo ($accion === 'listar' || !isset($accion)) ? 'active' : ''; ?>">
                <i class="fas fa-list mr-2"></i>
                Adquisiciones
            </a>
            <a href="?accion=crear" 
               class="tab-adquisicion <?php echo $accion === 'crear' ? 'active' : ''; ?>">
                <i class="fas fa-plus mr-2"></i>
                Nueva Adquisición
            </a>
            <a href="?accion=reportes" 
               class="tab-adquisicion <?php echo $accion === 'reportes' ? 'active' : ''; ?>">
                <i class="fas fa-chart-bar mr-2"></i>
                Reportes
            </a>
        </div>
        
        <!-- ========== SECCIÓN: LISTAR ADQUISICIONES ========== -->
        <?php if ($accion === 'listar' || !isset($accion)): ?>
        
        <!-- Filtros -->
        <div class="mb-8 glass-card rounded-2xl p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Filtros de Adquisiciones</h2>
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
                    <label class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                    <select name="estado" class="form-input w-full px-4 py-2.5 border border-gray-300 rounded-xl">
                        <option value="">Todos los estados</option>
                        <option value="pendiente" <?php echo (isset($_GET['estado']) && $_GET['estado'] == 'pendiente') ? 'selected' : ''; ?>>Pendiente</option>
                        <option value="ordenado" <?php echo (isset($_GET['estado']) && $_GET['estado'] == 'ordenado') ? 'selected' : ''; ?>>Ordenado</option>
                        <option value="entregado" <?php echo (isset($_GET['estado']) && $_GET['estado'] == 'entregado') ? 'selected' : ''; ?>>Entregado</option>
                        <option value="cancelado" <?php echo (isset($_GET['estado']) && $_GET['estado'] == 'cancelado') ? 'selected' : ''; ?>>Cancelado</option>
                    </select>
                </div>
                
                <div class="flex items-end">
                    <button type="submit" 
                            class="w-full gradient-bg-proceso3 hover:opacity-90 text-white px-4 py-2.5 rounded-xl font-medium flex items-center justify-center gap-2">
                        <i class="fas fa-filter"></i>
                        Aplicar Filtros
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Próximas a vencer -->
        <?php if (isset($adquisiciones_proximas) && count($adquisiciones_proximas) > 0): ?>
        <div class="mb-8 glass-card rounded-2xl p-6 border-2 border-yellow-200">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-gradient-to-r from-yellow-400 to-orange-400 rounded-xl flex items-center justify-center">
                    <i class="fas fa-clock text-white"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900">Adquisiciones Próximas a Vencer</h3>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Proyecto</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Recurso</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Fecha Entrega</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Días Restantes</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($adquisiciones_proximas as $adq): ?>
                        <tr class="border-b border-gray-100 hover:bg-yellow-50">
                            <td class="px-4 py-3"><?php echo htmlspecialchars($adq['proyecto_nombre'] ?? ''); ?></td>
                            <td class="px-4 py-3"><?php echo htmlspecialchars($adq['recurso_descripcion'] ?? ''); ?></td>
                            <td class="px-4 py-3"><?php echo isset($adq['fecha_entrega_estimada']) ? date('d/m/Y', strtotime($adq['fecha_entrega_estimada'])) : ''; ?></td>
                            <td class="px-4 py-3">
                                <span class="px-3 py-1 rounded-full text-sm font-medium <?php echo $adq['dias_restantes'] <= 3 ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800'; ?>">
                                    <?php echo $adq['dias_restantes']; ?> días
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <a href="?accion=ver&id=<?php echo $adq['id']; ?>" 
                                   class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                    Ver Detalles
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Estadísticas rápidas -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="stat-card bg-white rounded-2xl p-6 border border-gray-200 shadow-sm hover-lift">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-gradient-to-r from-orange-50 to-orange-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-shopping-cart text-orange-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Total Adquisiciones</p>
                        <p class="text-2xl font-bold text-gray-800">
                            <?php 
                            $total = 0;
                            if (isset($adquisiciones) && is_array($adquisiciones)) {
                                $total = count($adquisiciones);
                            }
                            echo $total;
                            ?>
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
                        <p class="text-sm text-gray-500">Inversión Total</p>
                        <p class="text-2xl font-bold text-gray-800">
                            <?php 
                            $total_inversion = 0;
                            if (isset($adquisiciones) && is_array($adquisiciones)) {
                                foreach ($adquisiciones as $adq) {
                                    $total_inversion += $adq['costo_adquisicion'] ?? 0;
                                }
                            }
                            echo '$' . number_format($total_inversion, 2);
                            ?>
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="stat-card bg-white rounded-2xl p-6 border border-gray-200 shadow-sm hover-lift">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-gradient-to-r from-blue-50 to-blue-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-truck text-blue-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Entregadas</p>
                        <p class="text-2xl font-bold text-gray-800">
                            <?php 
                            $entregadas = 0;
                            if (isset($adquisiciones) && is_array($adquisiciones)) {
                                foreach ($adquisiciones as $adq) {
                                    if (($adq['estado'] ?? '') === 'entregado') {
                                        $entregadas++;
                                    }
                                }
                            }
                            echo $entregadas;
                            ?>
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="stat-card bg-white rounded-2xl p-6 border border-gray-200 shadow-sm hover-lift">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-gradient-to-r from-purple-50 to-purple-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-building text-purple-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Proveedores</p>
                        <p class="text-2xl font-bold text-gray-800">
                            <?php 
                            $proveedores = [];
                            if (isset($adquisiciones) && is_array($adquisiciones)) {
                                foreach ($adquisiciones as $adq) {
                                    if (!empty($adq['proveedor'])) {
                                        $proveedores[$adq['proveedor']] = true;
                                    }
                                }
                            }
                            echo count($proveedores);
                            ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Tabla de adquisiciones -->
        <div class="glass-card rounded-2xl p-6 mb-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Adquisiciones de Recursos</h2>
                <a href="?accion=crear" 
                class="gradient-bg-proceso3 hover:opacity-90 text-white px-5 py-2.5 rounded-xl font-medium flex items-center gap-2 hover-lift shadow-lg">
                    <i class="fas fa-plus"></i>
                    Nueva Adquisición
                </a>
            </div>
            
            <?php if (empty($adquisiciones) || !is_array($adquisiciones)): ?>
            <div class="text-center py-16">
                <div class="w-24 h-24 mx-auto bg-gradient-to-br from-orange-50 to-orange-100 rounded-full flex items-center justify-center mb-6">
                    <i class="fas fa-shopping-cart text-4xl text-gray-400"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-700 mb-3">No hay adquisiciones registradas</h3>
                <p class="text-gray-500 mb-6 max-w-md mx-auto">Comienza creando tu primera adquisición de recursos</p>
                <a href="?accion=crear" 
                class="gradient-bg-proceso3 hover:opacity-90 text-white px-6 py-3 rounded-xl font-medium inline-flex items-center gap-2 shadow-lg hover-lift">
                    <i class="fas fa-plus"></i>
                    Crear primera adquisición
                </a>
            </div>
            <?php else: ?>
            <div class="overflow-x-auto rounded-xl border border-gray-200">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gradient-to-r from-orange-50 to-orange-100">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 uppercase">Proyecto / Recurso</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 uppercase">Proveedor / Contrato</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 uppercase">Estado / Fechas</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 uppercase">Costo / Método</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 uppercase">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        <?php foreach($adquisiciones as $adq): 
                            // Colores para estados
                            $estado_colores = [
                                'pendiente' => ['bg' => 'bg-yellow-100 text-yellow-800', 'icon' => 'fa-clock'],
                                'ordenado' => ['bg' => 'bg-blue-100 text-blue-800', 'icon' => 'fa-check'],
                                'entregado' => ['bg' => 'bg-green-100 text-green-800', 'icon' => 'fa-check-double'],
                                'cancelado' => ['bg' => 'bg-red-100 text-red-800', 'icon' => 'fa-times']
                            ];
                            $estado = $adq['estado'] ?? 'pendiente';
                            $estado_style = $estado_colores[$estado] ?? $estado_colores['pendiente'];
                            
                            // Icono según tipo de recurso
                            $iconos_tipo = [
                                'humano' => 'fa-user',
                                'material' => 'fa-box',
                                'equipo' => 'fa-tools',
                                'financiero' => 'fa-money-bill',
                                'tecnologico' => 'fa-laptop'
                            ];
                            $tipo_recurso = $adq['tipo_recurso'] ?? 'material';
                            $icono_tipo = $iconos_tipo[$tipo_recurso] ?? 'fa-cube';
                        ?>
                        <tr class="table-row-hover hover:bg-orange-50/30">
                            <td class="px-6 py-4">
                                <div class="flex items-start gap-4">
                                    <div class="w-12 h-12 bg-gradient-to-br from-orange-100 to-red-100 rounded-xl flex items-center justify-center mt-1">
                                        <i class="fas <?php echo $icono_tipo; ?> text-orange-600"></i>
                                    </div>
                                    <div class="flex-1">
                                        <div class="font-semibold text-gray-900 mb-1"><?php echo htmlspecialchars($adq['proyecto_nombre'] ?? 'Sin proyecto'); ?></div>
                                        <div class="text-sm text-gray-600 mb-2 line-clamp-2"><?php echo htmlspecialchars($adq['recurso_descripcion'] ?? 'Sin descripción'); ?></div>
                                        <div class="flex items-center gap-3 text-xs">
                                            <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded-lg">
                                                <?php echo $adq['cantidad_real'] ?? 0; ?> unidades
                                            </span>
                                            <span class="px-2 py-1 bg-orange-100 text-orange-700 rounded-lg">
                                                <?php echo ucfirst($tipo_recurso); ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900 mb-1"><?php echo htmlspecialchars($adq['proveedor'] ?? 'No especificado'); ?></div>
                                <div class="text-sm text-gray-500"><?php echo htmlspecialchars($adq['metodo_adquisicion'] ?? 'Sin método'); ?></div>
                                <?php if (!empty($adq['contrato_ref'])): ?>
                                <div class="text-xs text-blue-600 mt-1">
                                    <i class="fas fa-file-contract mr-1"></i>
                                    <?php echo htmlspecialchars($adq['contrato_ref']); ?>
                                </div>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4">
                                <div class="space-y-2">
                                    <div class="status-badge <?php echo $estado_style['bg']; ?>">
                                        <i class="fas <?php echo $estado_style['icon']; ?> text-xs"></i>
                                        <?php echo ucfirst($estado); ?>
                                    </div>
                                    <div class="text-sm">
                                        <div class="text-gray-600">Orden: <?php echo isset($adq['fecha_orden']) ? date('d/m/Y', strtotime($adq['fecha_orden'])) : 'N/A'; ?></div>
                                        <div class="text-gray-600">Entrega: <?php echo isset($adq['fecha_entrega_estimada']) ? date('d/m/Y', strtotime($adq['fecha_entrega_estimada'])) : 'N/A'; ?></div>
                                        <?php if (!empty($adq['fecha_entrega_real'])): ?>
                                        <div class="text-green-600 font-medium">Real: <?php echo date('d/m/Y', strtotime($adq['fecha_entrega_real'])); ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="space-y-2">
                                    <div class="text-lg font-bold text-orange-600">$<?php echo isset($adq['costo_adquisicion']) ? number_format($adq['costo_adquisicion'], 2) : '0.00'; ?></div>
                                    <div class="text-sm text-gray-500">vs Estimado: $<?php echo isset($adq['costo_real_total']) ? number_format($adq['costo_real_total'], 2) : '0.00'; ?></div>
                                    <?php 
                                    $costo_estimado = $adq['costo_real_total'] ?? 1;
                                    $costo_real = $adq['costo_adquisicion'] ?? 0;
                                    $variacion = $costo_estimado > 0 ? (($costo_real - $costo_estimado) / $costo_estimado) * 100 : 0;
                                    ?>
                                    <div class="text-sm <?php echo $variacion <= 0 ? 'text-green-600' : 'text-red-600'; ?>">
                                        <?php echo ($variacion > 0 ? '+' : '') . number_format($variacion, 1); ?>%
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col items-start gap-3 min-w-[180px]">
                                    <!-- Acciones principales -->
                                    <div class="flex items-center gap-3">
                                        <a href="?accion=ver&id=<?php echo $adq['id']; ?>" 
                                        class="w-9 h-9 bg-blue-50 hover:bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center hover-lift transition-all duration-200"
                                        title="Ver detalles">
                                            <i class="fas fa-eye text-sm"></i>
                                        </a>
                                        
                                        <?php if ($usuario_rol != 'miembro_equipo'): ?>
                                        <a href="?accion=editar&id=<?php echo $adq['id']; ?>" 
                                        class="w-9 h-9 bg-emerald-50 hover:bg-emerald-100 text-emerald-600 rounded-lg flex items-center justify-center hover-lift transition-all duration-200"
                                        title="Editar">
                                            <i class="fas fa-edit text-sm"></i>
                                        </a>
                                        
                                        <!-- Botón rápido para cambiar estado -->
                                        <div class="relative group">
                                            <button class="w-9 h-9 bg-purple-50 hover:bg-purple-100 text-purple-600 rounded-lg flex items-center justify-center hover-lift transition-all duration-200"
                                                    title="Cambiar estado">
                                                <i class="fas fa-exchange-alt text-sm"></i>
                                            </button>
                                            <div class="absolute right-0 top-full mt-1 w-48 bg-white rounded-lg shadow-xl border border-gray-200 z-20 hidden group-hover:block animate-fadeIn">
                                                <form method="POST" action="" class="p-2">
                                                    <input type="hidden" name="accion" value="cambiar_estado">
                                                    <input type="hidden" name="id" value="<?php echo $adq['id']; ?>">
                                                    <?php if (isset($_SESSION['csrf_token'])): ?>
                                                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                                    <?php endif; ?>
                                                    
                                                    <button type="submit" name="estado" value="pendiente" 
                                                            class="w-full text-left px-3 py-2.5 text-sm hover:bg-yellow-50 rounded-lg text-yellow-700 flex items-center gap-2 transition-colors duration-150">
                                                        <i class="fas fa-clock text-xs"></i>
                                                        <span>Pendiente</span>
                                                    </button>
                                                    <button type="submit" name="estado" value="ordenado" 
                                                            class="w-full text-left px-3 py-2.5 text-sm hover:bg-blue-50 rounded-lg text-blue-700 flex items-center gap-2 transition-colors duration-150">
                                                        <i class="fas fa-check text-xs"></i>
                                                        <span>Ordenado</span>
                                                    </button>
                                                    <button type="submit" name="estado" value="entregado" 
                                                            class="w-full text-left px-3 py-2.5 text-sm hover:bg-green-50 rounded-lg text-green-700 flex items-center gap-2 transition-colors duration-150">
                                                        <i class="fas fa-check-double text-xs"></i>
                                                        <span>Entregado</span>
                                                    </button>
                                                    <button type="submit" name="estado" value="cancelado" 
                                                            class="w-full text-left px-3 py-2.5 text-sm hover:bg-red-50 rounded-lg text-red-700 flex items-center gap-2 transition-colors duration-150">
                                                        <i class="fas fa-times text-xs"></i>
                                                        <span>Cancelar</span>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                        
                                        <form method="POST" action="" 
                                            onsubmit="return confirm('¿Estás seguro de eliminar esta adquisición?');"
                                            class="inline-block">
                                            <input type="hidden" name="accion" value="eliminar">
                                            <input type="hidden" name="id" value="<?php echo $adq['id']; ?>">
                                            <?php if (isset($_SESSION['csrf_token'])): ?>
                                            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                            <?php endif; ?>
                                            <button type="submit" 
                                                    class="w-9 h-9 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg flex items-center justify-center hover-lift transition-all duration-200"
                                                    title="Eliminar">
                                                <i class="fas fa-trash text-sm"></i>
                                            </button>
                                        </form>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <!-- Botón rápido de vista (siempre visible) -->
                                    <div class="w-full">
                                        <a href="?accion=ver&id=<?php echo $adq['id']; ?>" 
                                        class="w-full text-center px-3 py-1.5 bg-gradient-to-r from-gray-50 to-gray-100 hover:from-gray-100 hover:to-gray-200 text-gray-700 text-xs font-medium rounded-lg flex items-center justify-center gap-1.5 hover-lift transition-all duration-200">
                                            <i class="fas fa-external-link-alt text-xs"></i>
                                            Ver Detalles Completos
                                        </a>
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
        
        <!-- ========== SECCIÓN: CREAR ADQUISICIÓN ========== -->
        <?php if ($accion === 'crear'): ?>
        
        <div class="max-w-4xl mx-auto">
            <div class="glass-card rounded-2xl p-8">
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-14 h-14 bg-gradient-to-br from-orange-100 to-red-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-shopping-cart text-2xl text-orange-600"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Crear Nueva Adquisición</h2>
                        <p class="text-gray-600">Adquirir recursos según las estimaciones realizadas</p>
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
                    
                    <!-- Paso 1: Seleccionar estimación -->
                    <div class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-2xl p-6 mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Paso 1: Seleccionar Estimación</h3>
                        
                        <div>
                            <label class="block text-sm font-semibold text-gray-800 mb-3">Estimación *</label>
                            <select name="estimacion_id" required 
                                    class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                                    onchange="cargarDatosEstimacion(this.value)">
                                <option value="">Seleccionar estimación para adquisición</option>
                                <?php if (isset($estimaciones) && is_array($estimaciones)): ?>
                                    <?php foreach ($estimaciones as $est): ?>
                                    <option value="<?php echo htmlspecialchars($est['id']); ?>" 
                                            <?php echo (isset($datos_form['estimacion_id']) && $datos_form['estimacion_id'] == $est['id']) ? 'selected' : ''; ?>>
                                        [<?php echo htmlspecialchars($est['proyecto_nombre'] ?? ''); ?>] 
                                        <?php echo htmlspecialchars($est['recurso_descripcion'] ?? ''); ?> 
                                        ($<?php echo isset($est['costo_real_total']) ? number_format($est['costo_real_total'], 2) : '0.00'; ?>)
                                    </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        
                        <!-- Información de la estimación seleccionada -->
                        <div id="info-estimacion" class="mt-4 hidden">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="bg-white p-4 rounded-xl border border-gray-200">
                                    <div class="text-sm text-gray-500">Proyecto</div>
                                    <div class="text-lg font-bold text-gray-900" id="proyecto-estimacion">-</div>
                                </div>
                                <div class="bg-white p-4 rounded-xl border border-gray-200">
                                    <div class="text-sm text-gray-500">Recurso</div>
                                    <div class="text-lg font-bold text-gray-900" id="recurso-estimacion">-</div>
                                </div>
                                <div class="bg-white p-4 rounded-xl border border-gray-200">
                                    <div class="text-sm text-gray-500">Cantidad Estimada</div>
                                    <div class="text-xl font-bold text-gray-900" id="cantidad-estimacion">0</div>
                                </div>
                                <div class="bg-white p-4 rounded-xl border border-gray-200">
                                    <div class="text-sm text-gray-500">Costo Estimado</div>
                                    <div class="text-xl font-bold text-gray-900" id="costo-estimacion">$0.00</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Paso 2: Datos de adquisición -->
                    <div class="bg-gradient-to-r from-orange-50 to-red-50 rounded-2xl p-6 mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Paso 2: Datos de Adquisición</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-3">Proveedor *</label>
                                <input type="text" name="proveedor" required 
                                       value="<?php echo isset($datos_form['proveedor']) ? htmlspecialchars($datos_form['proveedor']) : ''; ?>"
                                       class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl"
                                       placeholder="Nombre del proveedor">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-3">Método de Adquisición *</label>
                                <select name="metodo_adquisicion" required 
                                        class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl">
                                    <option value="">Seleccionar método</option>
                                    <?php 
                                    $metodosAdquisicion = [
                                        'RFP (Request for Proposal)',
                                        'Licitación',
                                        'Contrato directo',
                                        'Cotización directa',
                                        'Subasta inversa',
                                        'Compra consolidada',
                                        'Alianza estratégica'
                                    ];
                                    foreach ($metodosAdquisicion as $metodo): ?>
                                    <option value="<?php echo htmlspecialchars($metodo); ?>"
                                        <?php echo (isset($datos_form['metodo_adquisicion']) && $datos_form['metodo_adquisicion'] == $metodo) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($metodo); ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-3">Fecha de Orden *</label>
                                <input type="date" name="fecha_orden" required 
                                       value="<?php echo isset($datos_form['fecha_orden']) ? htmlspecialchars($datos_form['fecha_orden']) : date('Y-m-d'); ?>"
                                       class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-3">Entrega Estimada *</label>
                                <input type="date" name="fecha_entrega_estimada" required 
                                       value="<?php echo isset($datos_form['fecha_entrega_estimada']) ? htmlspecialchars($datos_form['fecha_entrega_estimada']) : date('Y-m-d', strtotime('+7 days')); ?>"
                                       class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-3">Entrega Real</label>
                                <input type="date" name="fecha_entrega_real" 
                                       value="<?php echo isset($datos_form['fecha_entrega_real']) ? htmlspecialchars($datos_form['fecha_entrega_real']) : ''; ?>"
                                       class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Paso 3: Costo y estado -->
                    <div class="bg-gradient-to-r from-emerald-50 to-green-50 rounded-2xl p-6 mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Paso 3: Costo y Estado</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-3">Costo de Adquisición *</label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500">$</span>
                                    <input type="number" name="costo_adquisicion" step="0.01" min="0" required 
                                           value="<?php echo isset($datos_form['costo_adquisicion']) ? htmlspecialchars($datos_form['costo_adquisicion']) : ''; ?>"
                                           class="form-input w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl"
                                           placeholder="0.00"
                                           oninput="calcularVariacion()">
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-3">Estado *</label>
                                <div class="flex gap-3">
                                    <?php 
                                    $estados = [
                                        'pendiente' => ['color' => 'bg-yellow-100 border-yellow-300 text-yellow-800', 'icon' => 'fa-clock'],
                                        'ordenado' => ['color' => 'bg-blue-100 border-blue-300 text-blue-800', 'icon' => 'fa-check'],
                                        'entregado' => ['color' => 'bg-green-100 border-green-300 text-green-800', 'icon' => 'fa-check-double'],
                                        'cancelado' => ['color' => 'bg-red-100 border-red-300 text-red-800', 'icon' => 'fa-times']
                                    ];
                                    foreach ($estados as $valor => $estilo): ?>
                                    <label class="radio-card flex-1 p-4 border-2 rounded-xl <?php echo $estilo['color']; ?> 
                                           <?php echo (isset($datos_form['estado']) && $datos_form['estado'] == $valor) ? 'selected' : ''; ?>">
                                        <input type="radio" name="estado" value="<?php echo $valor; ?>" 
                                               class="hidden" 
                                               <?php echo (isset($datos_form['estado']) && $datos_form['estado'] == $valor) ? 'checked' : ($valor == 'pendiente' ? 'checked' : ''); ?>
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
                        
                        <!-- Resultado cálculo -->
                        <div class="mt-6 bg-white p-4 rounded-xl border-2 border-orange-200">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="text-center">
                                    <div class="text-sm text-gray-500">Costo Estimado</div>
                                    <div class="text-xl font-bold text-gray-900" id="costo-estimado-display">$0.00</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-sm text-gray-500">Costo Adquisición</div>
                                    <div class="text-xl font-bold text-orange-600" id="costo-adquisicion-display">$0.00</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-sm text-gray-500">Variación</div>
                                    <div class="text-xl font-bold" id="variacion-adquisicion">0.00%</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Información adicional -->
                    <div class="bg-gradient-to-r from-purple-50 to-indigo-50 rounded-2xl p-6 mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Información Adicional</h3>
                        
                        <div>
                            <label class="block text-sm font-semibold text-gray-800 mb-3">Referencia de Contrato</label>
                            <input type="text" name="contrato_ref" 
                                   value="<?php echo isset($datos_form['contrato_ref']) ? htmlspecialchars($datos_form['contrato_ref']) : ''; ?>"
                                   class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl"
                                   placeholder="Ej: CONT-2024-001">
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
                                        class="gradient-bg-proceso3 hover:opacity-90 text-white px-8 py-3 rounded-xl font-medium shadow-md hover-lift flex items-center gap-2">
                                    <i class="fas fa-check"></i>
                                    Guardar Adquisición
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <script>
            // Datos de estimaciones
            const estimacionesData = {
                <?php if (isset($estimaciones) && is_array($estimaciones)): ?>
                    <?php foreach ($estimaciones as $est): ?>
                    "<?php echo $est['id']; ?>": {
                        proyecto: "<?php echo addslashes($est['proyecto_nombre'] ?? ''); ?>",
                        recurso: "<?php echo addslashes($est['recurso_descripcion'] ?? ''); ?>",
                        cantidad: <?php echo $est['cantidad_real'] ?? 0; ?>,
                        costo: <?php echo $est['costo_real_total'] ?? 0; ?>,
                        metodo: "<?php echo addslashes($est['metodo_estimacion'] ?? ''); ?>"
                    },
                    <?php endforeach; ?>
                <?php endif; ?>
            };
            
            function cargarDatosEstimacion(id) {
                const infoDiv = document.getElementById('info-estimacion');
                if (id && estimacionesData[id]) {
                    const data = estimacionesData[id];
                    document.getElementById('proyecto-estimacion').textContent = data.proyecto;
                    document.getElementById('recurso-estimacion').textContent = data.recurso;
                    document.getElementById('cantidad-estimacion').textContent = data.cantidad;
                    document.getElementById('costo-estimacion').textContent = '$' + data.costo.toFixed(2);
                    document.getElementById('costo-estimado-display').textContent = '$' + data.costo.toFixed(2);
                    infoDiv.classList.remove('hidden');
                    
                    // Pre-cargar costo si está vacío
                    const costoInput = document.querySelector('input[name="costo_adquisicion"]');
                    if (costoInput && !costoInput.value) {
                        costoInput.value = data.costo.toFixed(2);
                    }
                    
                    calcularVariacion();
                } else {
                    infoDiv.classList.add('hidden');
                }
            }
            
            function calcularVariacion() {
                const estimacionId = document.querySelector('select[name="estimacion_id"]').value;
                const costoInput = parseFloat(document.querySelector('input[name="costo_adquisicion"]').value) || 0;
                
                document.getElementById('costo-adquisicion-display').textContent = '$' + costoInput.toFixed(2);
                
                if (estimacionId && estimacionesData[estimacionId]) {
                    const costoEstimado = estimacionesData[estimacionId].costo;
                    const variacion = ((costoInput - costoEstimado) / costoEstimado) * 100;
                    
                    const variacionSpan = document.getElementById('variacion-adquisicion');
                    variacionSpan.textContent = variacion.toFixed(2) + '%';
                    variacionSpan.className = 'text-xl font-bold ' + 
                        (variacion < 0 ? 'text-green-600' : (variacion > 0 ? 'text-red-600' : 'text-gray-600'));
                    
                    if (variacion > 0) {
                        variacionSpan.innerHTML = '<i class="fas fa-arrow-up mr-1"></i>' + variacionSpan.textContent;
                    } else if (variacion < 0) {
                        variacionSpan.innerHTML = '<i class="fas fa-arrow-down mr-1"></i>' + variacionSpan.textContent;
                    }
                }
            }
            
            // Inicializar si hay una estimación seleccionada
            document.addEventListener('DOMContentLoaded', function() {
                const select = document.querySelector('select[name="estimacion_id"]');
                if (select && select.value) {
                    cargarDatosEstimacion(select.value);
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
                
                // Inicializar cálculo
                calcularVariacion();
            });
        </script>
        
        <?php endif; ?>
        <!-- ========== FIN SECCIÓN CREAR ========== -->
        
        <!-- ========== SECCIÓN: VER DETALLE ========== -->
        <?php if ($accion === 'ver' && isset($adquisicion)): ?>

        <div class="max-w-4xl mx-auto">
            <div class="glass-card rounded-2xl p-8">
                <!-- Header -->
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-14 h-14 bg-gradient-to-br from-orange-100 to-red-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-eye text-2xl text-orange-600"></i>
                    </div>
                    <div class="flex-1">
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">Detalle de Adquisición</h2>
                        <p class="text-gray-600">ID: <?php echo htmlspecialchars($adquisicion['id']); ?> | Orden: <?php echo isset($adquisicion['fecha_orden']) ? date('d/m/Y', strtotime($adquisicion['fecha_orden'])) : 'N/A'; ?></p>
                    </div>
                    <div class="flex gap-3">
                        <a href="?accion=listar" class="px-4 py-2.5 border border-gray-300 text-gray-700 hover:bg-gray-50 rounded-xl font-medium">
                            <i class="fas fa-arrow-left mr-2"></i> Volver
                        </a>
                        <?php if ($usuario_rol != 'miembro_equipo'): ?>
                        <a href="?accion=editar&id=<?php echo $adquisicion['id']; ?>" 
                        class="gradient-bg-proceso3 hover:opacity-90 text-white px-4 py-2.5 rounded-xl font-medium">
                            <i class="fas fa-edit mr-2"></i> Editar
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Timeline del proceso -->
                <div class="mb-8 bg-gradient-to-r from-blue-50 to-purple-50 rounded-2xl p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Proceso de Adquisición</h3>
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="font-medium text-gray-900">Planificación</div>
                            <div class="text-sm text-gray-500">Recurso: <?php echo htmlspecialchars($adquisicion['recurso_descripcion'] ?? ''); ?></div>
                        </div>
                        <div class="timeline-item">
                            <div class="font-medium text-gray-900">Estimación</div>
                            <div class="text-sm text-gray-500">$<?php echo isset($adquisicion['costo_real_total']) ? number_format($adquisicion['costo_real_total'], 2) : '0.00'; ?> | Método: <?php echo htmlspecialchars($adquisicion['metodo_estimacion'] ?? ''); ?></div>
                        </div>
                        <div class="timeline-item">
                            <div class="font-medium text-gray-900">Adquisición</div>
                            <div class="text-sm text-gray-500">Proveedor: <?php echo htmlspecialchars($adquisicion['proveedor'] ?? ''); ?></div>
                        </div>
                    </div>
                </div>
                
                <!-- Información principal -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-2xl p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Información del Proyecto</h3>
                        <div class="space-y-3">
                            <div>
                                <div class="text-sm text-gray-500">Proyecto</div>
                                <div class="text-lg font-semibold text-gray-900"><?php echo htmlspecialchars($adquisicion['proyecto_nombre'] ?? 'Sin proyecto'); ?></div>
                            </div>
                            <div>
                                <div class="text-sm text-gray-500">Recurso</div>
                                <div class="text-lg font-semibold text-gray-900"><?php echo htmlspecialchars($adquisicion['recurso_descripcion'] ?? 'Sin descripción'); ?></div>
                                <div class="text-sm text-gray-600 mt-1">
                                    <span class="status-badge bg-blue-100 text-blue-800">
                                        <i class="fas fa-tag text-xs"></i>
                                        <?php echo ucfirst($adquisicion['tipo_recurso'] ?? 'material'); ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gradient-to-r from-purple-50 to-purple-100 rounded-2xl p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Información de Proveedor</h3>
                        <div class="space-y-3">
                            <div>
                                <div class="text-sm text-gray-500">Proveedor</div>
                                <div class="text-lg font-semibold text-gray-900"><?php echo htmlspecialchars($adquisicion['proveedor'] ?? 'No especificado'); ?></div>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <div class="text-sm text-gray-500">Método</div>
                                    <div class="font-medium text-gray-900"><?php echo htmlspecialchars($adquisicion['metodo_adquisicion'] ?? 'No especificado'); ?></div>
                                </div>
                                <div>
                                    <div class="text-sm text-gray-500">Estado</div>
                                    <?php 
                                    $estado_colores = [
                                        'pendiente' => ['bg' => 'bg-yellow-100 text-yellow-800', 'icon' => 'fa-clock'],
                                        'ordenado' => ['bg' => 'bg-blue-100 text-blue-800', 'icon' => 'fa-check'],
                                        'entregado' => ['bg' => 'bg-green-100 text-green-800', 'icon' => 'fa-check-double'],
                                        'cancelado' => ['bg' => 'bg-red-100 text-red-800', 'icon' => 'fa-times']
                                    ];
                                    $estado = $adquisicion['estado'] ?? 'pendiente';
                                    $estado_style = $estado_colores[$estado] ?? $estado_colores['pendiente'];
                                    ?>
                                    <div class="status-badge <?php echo $estado_style['bg']; ?>">
                                        <i class="fas <?php echo $estado_style['icon']; ?> text-xs"></i>
                                        <?php echo ucfirst($estado); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Comparación Estimación vs Adquisición -->
                <div class="bg-gradient-to-r from-emerald-50 to-green-50 rounded-2xl p-6 mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-6">Comparación: Estimación vs Adquisición</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Estimación -->
                        <div class="bg-white rounded-xl p-6 border border-gray-200">
                            <h4 class="font-semibold text-gray-900 mb-4 text-center">Estimación Original</h4>
                            <div class="space-y-4">
                                <div class="text-center">
                                    <div class="text-3xl font-bold text-gray-900"><?php echo $adquisicion['cantidad_real'] ?? 0; ?></div>
                                    <div class="text-sm text-gray-500">Cantidad Estimada</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-3xl font-bold text-gray-900">$<?php echo isset($adquisicion['costo_real_total']) ? number_format($adquisicion['costo_real_total'], 2) : '0.00'; ?></div>
                                    <div class="text-sm text-gray-500">Costo Estimado</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-sm text-gray-500">Método</div>
                                    <div class="font-medium text-gray-900"><?php echo htmlspecialchars($adquisicion['metodo_estimacion'] ?? 'N/A'); ?></div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Adquisición -->
                        <div class="bg-white rounded-xl p-6 border-2 border-orange-200">
                            <h4 class="font-semibold text-gray-900 mb-4 text-center">Adquisición Real</h4>
                            <div class="space-y-4">
                                <div class="text-center">
                                    <div class="text-3xl font-bold text-orange-600"><?php echo $adquisicion['cantidad_estimada'] ?? 0; ?></div>
                                    <div class="text-sm text-gray-500">Cantidad Adquirida</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-3xl font-bold text-orange-600">$<?php echo isset($adquisicion['costo_adquisicion']) ? number_format($adquisicion['costo_adquisicion'], 2) : '0.00'; ?></div>
                                    <div class="text-sm text-gray-500">Costo Real</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-sm text-gray-500">Proveedor</div>
                                    <div class="font-medium text-gray-900"><?php echo htmlspecialchars($adquisicion['proveedor'] ?? 'N/A'); ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Variación -->
                    <?php 
                    $costo_estimado = $adquisicion['costo_real_total'] ?? 1;
                    $costo_real = $adquisicion['costo_adquisicion'] ?? 0;
                    $variacion = $costo_estimado > 0 ? (($costo_real - $costo_estimado) / $costo_estimado) * 100 : 0;
                    ?>
                    <div class="mt-6 text-center p-4 rounded-xl <?php echo $variacion == 0 ? 'bg-gray-100' : ($variacion > 0 ? 'bg-red-50 border border-red-200' : 'bg-green-50 border border-green-200'); ?>">
                        <div class="text-sm text-gray-500 mb-1">Variación en Costo</div>
                        <div class="text-2xl font-bold <?php echo $variacion == 0 ? 'text-gray-700' : ($variacion > 0 ? 'text-red-600' : 'text-green-600'); ?>">
                            <?php echo ($variacion > 0 ? '+' : '') . number_format($variacion, 1); ?>%
                            <?php if ($variacion > 0): ?>
                            <i class="fas fa-arrow-up ml-2"></i>
                            <?php elseif ($variacion < 0): ?>
                            <i class="fas fa-arrow-down ml-2"></i>
                            <?php endif; ?>
                        </div>
                        <div class="text-sm text-gray-600 mt-2">
                            <?php 
                            if ($variacion > 0) {
                                echo "La adquisición costó " . number_format($variacion, 1) . "% más de lo estimado";
                            } elseif ($variacion < 0) {
                                echo "Se ahorró " . number_format(abs($variacion), 1) . "% respecto a la estimación";
                            } else {
                                echo "El costo coincide exactamente con la estimación";
                            }
                            ?>
                        </div>
                    </div>
                </div>
                
                <!-- Fechas y contrato -->
                <div class="bg-gradient-to-r from-amber-50 to-orange-50 rounded-2xl p-6 mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Cronología y Documentación</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="text-center p-4 bg-white rounded-xl border border-gray-200">
                            <div class="text-sm text-gray-500">Fecha de Orden</div>
                            <div class="text-lg font-bold text-gray-900"><?php echo isset($adquisicion['fecha_orden']) ? date('d/m/Y', strtotime($adquisicion['fecha_orden'])) : 'N/A'; ?></div>
                        </div>
                        
                        <div class="text-center p-4 bg-white rounded-xl border border-gray-200">
                            <div class="text-sm text-gray-500">Entrega Estimada</div>
                            <div class="text-lg font-bold text-gray-900"><?php echo isset($adquisicion['fecha_entrega_estimada']) ? date('d/m/Y', strtotime($adquisicion['fecha_entrega_estimada'])) : 'N/A'; ?></div>
                        </div>
                        
                        <div class="text-center p-4 bg-white rounded-xl border <?php echo !empty($adquisicion['fecha_entrega_real']) ? 'border-green-200' : 'border-gray-200'; ?>">
                            <div class="text-sm text-gray-500">Entrega Real</div>
                            <div class="text-lg font-bold <?php echo !empty($adquisicion['fecha_entrega_real']) ? 'text-green-600' : 'text-gray-900'; ?>">
                                <?php echo !empty($adquisicion['fecha_entrega_real']) ? date('d/m/Y', strtotime($adquisicion['fecha_entrega_real'])) : 'Pendiente'; ?>
                            </div>
                        </div>
                    </div>
                    
                    <?php if (!empty($adquisicion['contrato_ref'])): ?>
                    <div class="mt-6 p-4 bg-white rounded-xl border border-blue-200">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-file-contract text-blue-500 text-xl"></i>
                            <div>
                                <div class="text-sm text-gray-500">Referencia de Contrato</div>
                                <div class="text-lg font-medium text-blue-700"><?php echo htmlspecialchars($adquisicion['contrato_ref']); ?></div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <?php endif; ?>
        <!-- ========== FIN SECCIÓN VER ========== -->
        
        <!-- ========== SECCIÓN: EDITAR ADQUISICIÓN ========== -->
        <?php if ($accion === 'editar' && isset($adquisicion)): ?>

        <div class="max-w-4xl mx-auto">
            <div class="glass-card rounded-2xl p-8">
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-14 h-14 bg-gradient-to-br from-orange-100 to-red-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-edit text-2xl text-orange-600"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Editar Adquisición</h2>
                        <p class="text-gray-600">ID: <?php echo htmlspecialchars($adquisicion['id']); ?> | Orden: <?php echo isset($adquisicion['fecha_orden']) ? date('d/m/Y', strtotime($adquisicion['fecha_orden'])) : 'N/A'; ?></p>
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
                    <input type="hidden" name="id" value="<?php echo $adquisicion['id']; ?>">
                    <input type="hidden" name="accion" value="actualizar">
                    <?php if (isset($_SESSION['csrf_token'])): ?>
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    <?php endif; ?>
                    
                    <!-- CAMPO OCULTO PARA estimacion_id -->
                    <input type="hidden" name="estimacion_id" value="<?php echo $adquisicion['estimacion_id']; ?>">
                    
                    <!-- Información del recurso (solo lectura) -->
                    <div class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-2xl p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Información del Recurso</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <div class="text-sm text-gray-500">Proyecto</div>
                                <div class="text-lg font-semibold text-gray-900"><?php echo htmlspecialchars($adquisicion['proyecto_nombre'] ?? 'Sin proyecto'); ?></div>
                            </div>
                            <div>
                                <div class="text-sm text-gray-500">Recurso</div>
                                <div class="text-lg font-semibold text-gray-900"><?php echo htmlspecialchars($adquisicion['recurso_descripcion'] ?? 'Sin descripción'); ?></div>
                            </div>
                            <div>
                                <div class="text-sm text-gray-500">Tipo</div>
                                <span class="status-badge bg-blue-100 text-blue-800">
                                    <?php echo ucfirst($adquisicion['tipo_recurso'] ?? 'material'); ?>
                                </span>
                            </div>
                            <div>
                                <div class="text-sm text-gray-500">Estimación Original</div>
                                <div class="font-medium text-gray-900">$<?php echo isset($adquisicion['costo_real_total']) ? number_format($adquisicion['costo_real_total'], 2) : '0.00'; ?></div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Datos de adquisición -->
                    <div class="bg-gradient-to-r from-orange-50 to-red-50 rounded-2xl p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Datos de Adquisición</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-3">Proveedor *</label>
                                <input type="text" name="proveedor" required 
                                    value="<?php echo isset($datos_form['proveedor']) ? $datos_form['proveedor'] : htmlspecialchars($adquisicion['proveedor'] ?? ''); ?>"
                                    class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-3">Método de Adquisición *</label>
                                <select name="metodo_adquisicion" required 
                                        class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl">
                                    <option value="">Seleccionar método</option>
                                    <?php 
                                    $metodosAdquisicion = [
                                        'RFP (Request for Proposal)',
                                        'Licitación',
                                        'Contrato directo',
                                        'Cotización directa',
                                        'Subasta inversa',
                                        'Compra consolidada',
                                        'Alianza estratégica'
                                    ];
                                    foreach ($metodosAdquisicion as $metodo): 
                                        $selected = (isset($datos_form['metodo_adquisicion']) && $datos_form['metodo_adquisicion'] == $metodo) || 
                                                (!isset($datos_form['metodo_adquisicion']) && $adquisicion['metodo_adquisicion'] == $metodo);
                                    ?>
                                    <option value="<?php echo htmlspecialchars($metodo); ?>" <?php echo $selected ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($metodo); ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-3">Fecha de Orden *</label>
                                <input type="date" name="fecha_orden" required 
                                    value="<?php echo isset($datos_form['fecha_orden']) ? $datos_form['fecha_orden'] : $adquisicion['fecha_orden']; ?>"
                                    class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-3">Entrega Estimada *</label>
                                <input type="date" name="fecha_entrega_estimada" required 
                                    value="<?php echo isset($datos_form['fecha_entrega_estimada']) ? $datos_form['fecha_entrega_estimada'] : $adquisicion['fecha_entrega_estimada']; ?>"
                                    class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-3">Entrega Real</label>
                                <input type="date" name="fecha_entrega_real" 
                                    value="<?php echo isset($datos_form['fecha_entrega_real']) ? $datos_form['fecha_entrega_real'] : $adquisicion['fecha_entrega_real']; ?>"
                                    class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Costo y estado -->
                    <div class="bg-gradient-to-r from-emerald-50 to-green-50 rounded-2xl p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Costo y Estado</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-3">Costo de Adquisición *</label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500">$</span>
                                    <input type="number" name="costo_adquisicion" step="0.01" min="0" required 
                                        value="<?php echo isset($datos_form['costo_adquisicion']) ? $datos_form['costo_adquisicion'] : $adquisicion['costo_adquisicion']; ?>"
                                        class="form-input w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl"
                                        oninput="calcularVariacionEditar()">
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-3">Estado *</label>
                                <div class="flex gap-3">
                                    <?php 
                                    $estados = [
                                        'pendiente' => ['color' => 'bg-yellow-100 border-yellow-300 text-yellow-800', 'icon' => 'fa-clock'],
                                        'ordenado' => ['color' => 'bg-blue-100 border-blue-300 text-blue-800', 'icon' => 'fa-check'],
                                        'entregado' => ['color' => 'bg-green-100 border-green-300 text-green-800', 'icon' => 'fa-check-double'],
                                        'cancelado' => ['color' => 'bg-red-100 border-red-300 text-red-800', 'icon' => 'fa-times']
                                    ];
                                    foreach ($estados as $valor => $estilo): 
                                        $selected = (isset($datos_form['estado']) && $datos_form['estado'] == $valor) || 
                                                (!isset($datos_form['estado']) && $adquisicion['estado'] == $valor);
                                    ?>
                                    <label class="radio-card flex-1 p-4 border-2 rounded-xl <?php echo $estilo['color']; ?> 
                                        <?php echo $selected ? 'selected' : ''; ?>">
                                        <input type="radio" name="estado" value="<?php echo $valor; ?>" 
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
                        
                        <!-- Resultado cálculo -->
                        <div class="mt-6 bg-white p-4 rounded-xl border-2 border-orange-200">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="text-center">
                                    <div class="text-sm text-gray-500">Costo Estimado</div>
                                    <div class="text-xl font-bold text-gray-900" id="costo-estimado-editar">
                                        $<?php echo isset($adquisicion['costo_real_total']) ? number_format($adquisicion['costo_real_total'], 2) : '0.00'; ?>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <div class="text-sm text-gray-500">Costo Adquisición</div>
                                    <div class="text-xl font-bold text-orange-600" id="costo-adquisicion-editar">
                                        $<?php echo isset($adquisicion['costo_adquisicion']) ? number_format($adquisicion['costo_adquisicion'], 2) : '0.00'; ?>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <div class="text-sm text-gray-500">Variación</div>
                                    <div class="text-xl font-bold" id="variacion-editar">
                                        <?php 
                                        $costo_estimado = $adquisicion['costo_real_total'] ?? 1;
                                        $costo_real = $adquisicion['costo_adquisicion'] ?? 0;
                                        $variacion = $costo_estimado > 0 ? (($costo_real - $costo_estimado) / $costo_estimado) * 100 : 0;
                                        echo number_format($variacion, 2) . '%';
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Información adicional -->
                    <div class="bg-gradient-to-r from-purple-50 to-indigo-50 rounded-2xl p-6 mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Información Adicional</h3>
                        
                        <div>
                            <label class="block text-sm font-semibold text-gray-800 mb-3">Referencia de Contrato</label>
                            <input type="text" name="contrato_ref" 
                                value="<?php echo isset($datos_form['contrato_ref']) ? $datos_form['contrato_ref'] : htmlspecialchars($adquisicion['contrato_ref'] ?? ''); ?>"
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl">
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
                                        class="gradient-bg-proceso3 hover:opacity-90 text-white px-8 py-3 rounded-xl font-medium shadow-md hover-lift flex items-center gap-2">
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
            function calcularVariacionEditar() {
                const costoInput = parseFloat(document.querySelector('input[name="costo_adquisicion"]').value) || 0;
                const costoEstimado = <?php echo $adquisicion['costo_real_total'] ?? 1; ?>;
                const variacion = ((costoInput - costoEstimado) / costoEstimado) * 100;
                
                document.getElementById('costo-adquisicion-editar').textContent = '$' + costoInput.toFixed(2);
                
                const variacionSpan = document.getElementById('variacion-editar');
                variacionSpan.textContent = variacion.toFixed(2) + '%';
                variacionSpan.className = 'text-xl font-bold ' + 
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
                calcularVariacionEditar();
            });
        </script>

        <?php endif; ?>
        <!-- ========== FIN SECCIÓN EDITAR ========== -->
        
        <!-- ========== SECCIÓN: REPORTES ========== -->
        <?php if ($accion === 'reportes'): ?>

        <div class="mb-8 glass-card rounded-2xl p-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Reportes de Adquisiciones</h2>
            
            <!-- Filtros de reportes -->
            <div class="bg-gradient-to-r from-orange-50 to-red-50 rounded-2xl p-6 mb-8">
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
                                class="w-full gradient-bg-proceso3 hover:opacity-90 text-white px-4 py-2.5 rounded-xl font-medium flex items-center justify-center gap-2">
                            <i class="fas fa-chart-bar"></i>
                            Generar Reporte
                        </button>
                    </div>
                </form>
            </div>
            
            <?php 
            // Procesar datos para obtener estadísticas
            $estadisticas_totales = [
                'total_adquisiciones' => 0,
                'costo_total_adquisiciones' => 0,
                'variacion_promedio' => 0,
                'distribucion_estados' => [],
                'metodos_mas_usados' => [],
                'proveedores_principales' => []
            ];
            
            if (isset($estadisticas) && is_array($estadisticas)) {
                foreach ($estadisticas as $est) {
                    $estadisticas_totales['total_adquisiciones'] += $est['total_adquisiciones'] ?? 0;
                    $estadisticas_totales['costo_total_adquisiciones'] += $est['costo_total_adquisiciones'] ?? 0;
                    
                    // Distribución por estado
                    if (isset($est['estado'])) {
                        $estado = $est['estado'];
                        if (!isset($estadisticas_totales['distribucion_estados'][$estado])) {
                            $estadisticas_totales['distribucion_estados'][$estado] = [
                                'cantidad' => 0,
                                'costo_total' => 0
                            ];
                        }
                        $estadisticas_totales['distribucion_estados'][$estado]['cantidad'] += $est['total_adquisiciones'] ?? 0;
                        $estadisticas_totales['distribucion_estados'][$estado]['costo_total'] += $est['costo_total_adquisiciones'] ?? 0;
                    }
                    
                    // Métodos más usados
                    if (isset($est['metodo_adquisicion'])) {
                        $metodo = $est['metodo_adquisicion'];
                        if (!isset($estadisticas_totales['metodos_mas_usados'][$metodo])) {
                            $estadisticas_totales['metodos_mas_usados'][$metodo] = [
                                'metodo' => $metodo,
                                'cantidad' => 0
                            ];
                        }
                        $estadisticas_totales['metodos_mas_usados'][$metodo]['cantidad'] += $est['total_adquisiciones'] ?? 0;
                    }
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
            
            // Convertir arrays asociativos a indexados
            if (!empty($estadisticas_totales['metodos_mas_usados'])) {
                $metodos_array = array_values($estadisticas_totales['metodos_mas_usados']);
                usort($metodos_array, function($a, $b) {
                    return $b['cantidad'] - $a['cantidad'];
                });
                $estadisticas_totales['metodos_mas_usados'] = $metodos_array;
            }
            
            if (!empty($estadisticas_totales['distribucion_estados'])) {
                $estados_array = [];
                foreach ($estadisticas_totales['distribucion_estados'] as $estado => $datos) {
                    $estados_array[] = [
                        'estado' => $estado,
                        'cantidad' => $datos['cantidad'],
                        'costo_total' => $datos['costo_total']
                    ];
                }
                $estadisticas_totales['distribucion_estados'] = $estados_array;
            }
            
            // Verificar si hay datos para mostrar
            $hay_datos_estadisticas = ($estadisticas_totales['total_adquisiciones'] > 0);
            ?>
            
            <?php if ($hay_datos_estadisticas): ?>
            
            <!-- Estadísticas generales -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="stat-card bg-white rounded-2xl p-6 border border-gray-200 shadow-sm hover-lift">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 bg-gradient-to-r from-orange-50 to-orange-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-shopping-cart text-orange-600 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Adquisiciones Totales</p>
                            <p class="text-2xl font-bold text-gray-800">
                                <?php echo $estadisticas_totales['total_adquisiciones']; ?>
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
                            <p class="text-sm text-gray-500">Inversión Total</p>
                            <p class="text-2xl font-bold text-gray-800">
                                $<?php echo number_format($estadisticas_totales['costo_total_adquisiciones'], 2); ?>
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
                        <div class="w-14 h-14 bg-gradient-to-r from-blue-50 to-blue-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-truck text-blue-600 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Entregadas</p>
                            <p class="text-2xl font-bold text-gray-800">
                                <?php 
                                $entregadas = 0;
                                if (!empty($estadisticas_totales['distribucion_estados'])) {
                                    foreach ($estadisticas_totales['distribucion_estados'] as $estado) {
                                        if ($estado['estado'] === 'entregado') {
                                            $entregadas = $estado['cantidad'];
                                            break;
                                        }
                                    }
                                }
                                echo $entregadas;
                                ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Gráfico de comparación -->
            <div class="glass-card rounded-2xl p-6 mb-8">
                <h3 class="text-xl font-bold text-gray-900 mb-6">Comparación: Estimado vs Adquirido</h3>
                
                <?php if (isset($comparacion) && is_array($comparacion) && count($comparacion) > 0): ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gradient-to-r from-orange-50 to-orange-100">
                            <tr>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 uppercase">Proyecto</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 uppercase">Estimado</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 uppercase">Adquirido</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 uppercase">Variación</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 uppercase">Eficiencia</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            <?php foreach($comparacion as $item): 
                                // Saltar proyectos sin adquisiciones
                                if (empty($item['total_adquisiciones']) || $item['total_adquisiciones'] == 0) {
                                    continue;
                                }
                                
                                $costo_estimado = $item['costo_total_estimado'] ?? 0;
                                $costo_adquirido = $item['costo_total_adquisicion'] ?? 0;
                                $total_adquisiciones = $item['total_adquisiciones'] ?? 0;
                                $variacion = $item['variacion_porcentaje'] ?? 0;
                            ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="font-medium text-gray-900"><?php echo htmlspecialchars($item['proyecto_nombre'] ?? 'Sin nombre'); ?></div>
                                    <div class="text-sm text-gray-500"><?php echo $total_adquisiciones; ?> adquisiciones</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-lg font-semibold text-gray-700">$<?php echo number_format($costo_estimado, 2); ?></div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-lg font-semibold text-orange-600">$<?php echo number_format($costo_adquirido, 2); ?></div>
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
                                    $eficiencia = '';
                                    $color = '';
                                    $variacion_abs = abs($variacion);
                                    
                                    if ($variacion_abs <= 5) {
                                        $eficiencia = 'Excelente';
                                        $color = 'bg-green-100 text-green-800';
                                    } elseif ($variacion_abs <= 10) {
                                        $eficiencia = 'Buena';
                                        $color = 'bg-blue-100 text-blue-800';
                                    } elseif ($variacion_abs <= 20) {
                                        $eficiencia = 'Aceptable';
                                        $color = 'bg-yellow-100 text-yellow-800';
                                    } else {
                                        $eficiencia = 'Baja';
                                        $color = 'bg-red-100 text-red-800';
                                    }
                                    ?>
                                    <span class="status-badge <?php echo $color; ?>">
                                        <?php echo $eficiencia; ?>
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
                    <p class="text-gray-500 mb-6">No se encontraron proyectos con adquisiciones para el periodo seleccionado</p>
                </div>
                <?php endif; ?>
            </div>
            
            <!-- Distribución por estado -->
            <div class="glass-card rounded-2xl p-6 mb-8">
                <h3 class="text-xl font-bold text-gray-900 mb-6">Distribución por Estado</h3>
                
                <?php if (!empty($estadisticas_totales['distribucion_estados'])): ?>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <?php 
                    $colores_estado = [
                        'pendiente' => ['bg' => 'bg-yellow-100 border-yellow-300', 'text' => 'text-yellow-800', 'icon' => 'fa-clock'],
                        'ordenado' => ['bg' => 'bg-blue-100 border-blue-300', 'text' => 'text-blue-800', 'icon' => 'fa-check'],
                        'entregado' => ['bg' => 'bg-green-100 border-green-300', 'text' => 'text-green-800', 'icon' => 'fa-check-double'],
                        'cancelado' => ['bg' => 'bg-red-100 border-red-300', 'text' => 'text-red-800', 'icon' => 'fa-times']
                    ];
                    
                    foreach ($estadisticas_totales['distribucion_estados'] as $estado): 
                        $color = $colores_estado[$estado['estado']] ?? $colores_estado['pendiente'];
                        $porcentaje = ($estadisticas_totales['total_adquisiciones'] > 0) ? 
                            ($estado['cantidad'] / $estadisticas_totales['total_adquisiciones'] * 100) : 0;
                    ?>
                    <div class="bg-white rounded-xl p-6 border-2 <?php echo $color['bg']; ?> hover-lift">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-12 h-12 <?php echo $color['bg']; ?> rounded-xl flex items-center justify-center">
                                <i class="fas <?php echo $color['icon']; ?> <?php echo $color['text']; ?> text-xl"></i>
                            </div>
                            <div>
                                <div class="text-lg font-semibold <?php echo $color['text']; ?>"><?php echo ucfirst($estado['estado']); ?></div>
                                <div class="text-sm text-gray-500">Estado</div>
                            </div>
                        </div>
                        
                        <div class="mb-2">
                            <div class="text-sm text-gray-500 mb-1">Cantidad</div>
                            <div class="text-2xl font-bold <?php echo $color['text']; ?>">
                                <?php echo $estado['cantidad']; ?>
                            </div>
                        </div>
                        
                        <div>
                            <div class="text-sm text-gray-500 mb-1">Porcentaje</div>
                            <div class="text-lg font-semibold <?php echo $color['text']; ?>">
                                <?php echo number_format($porcentaje, 1); ?>%
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <div class="text-sm text-gray-500 mb-1">Costo Total</div>
                            <div class="text-md font-medium <?php echo $color['text']; ?>">
                                $<?php echo number_format($estado['costo_total'], 2); ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php else: ?>
                <div class="text-center py-8">
                    <i class="fas fa-chart-pie text-5xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500">No hay datos de distribución por estado</p>
                </div>
                <?php endif; ?>
            </div>
            
            <!-- Métodos de adquisición más usados -->
            <div class="glass-card rounded-2xl p-6 mb-8">
                <h3 class="text-xl font-bold text-gray-900 mb-6">Métodos de Adquisición Más Utilizados</h3>
                
                <?php if (!empty($estadisticas_totales['metodos_mas_usados'])): ?>
                <div class="space-y-6">
                    <?php 
                    $total_metodos = $estadisticas_totales['total_adquisiciones'];
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
                            <div class="bg-orange-600 h-2.5 rounded-full" style="width: <?php echo $porcentaje; ?>%"></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php else: ?>
                <div class="text-center py-8">
                    <i class="fas fa-clipboard-list text-5xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500">No hay datos de métodos de adquisición</p>
                </div>
                <?php endif; ?>
            </div>
            
            <!-- Resumen general -->
            <div class="glass-card rounded-2xl p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-6">Resumen General</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="bg-gradient-to-r from-orange-50 to-orange-100 p-6 rounded-xl">
                        <h4 class="font-semibold text-orange-800 mb-3">Proyectos con Adquisiciones</h4>
                        <div class="text-3xl font-bold text-orange-700 mb-2">
                            <?php 
                            $proyectos_con_adquisiciones = 0;
                            if (isset($comparacion) && is_array($comparacion)) {
                                foreach($comparacion as $item) {
                                    if (isset($item['total_adquisiciones']) && $item['total_adquisiciones'] > 0) {
                                        $proyectos_con_adquisiciones++;
                                    }
                                }
                            }
                            echo $proyectos_con_adquisiciones;
                            ?>
                        </div>
                        <p class="text-sm text-orange-600">de un total de <?php echo isset($comparacion) ? count($comparacion) : 0; ?> proyectos</p>
                    </div>
                    
                    <div class="bg-gradient-to-r from-green-50 to-green-100 p-6 rounded-xl">
                        <h4 class="font-semibold text-green-800 mb-3">Adquisiciones Realizadas</h4>
                        <div class="text-3xl font-bold text-green-700 mb-2">
                            <?php echo $estadisticas_totales['total_adquisiciones']; ?>
                        </div>
                        <p class="text-sm text-green-600">adquisiciones registradas</p>
                    </div>
                    
                    <div class="bg-gradient-to-r from-purple-50 to-purple-100 p-6 rounded-xl">
                        <h4 class="font-semibold text-purple-800 mb-3">Inversión Total</h4>
                        <div class="text-3xl font-bold text-purple-700 mb-2">
                            $<?php echo number_format($estadisticas_totales['costo_total_adquisiciones'], 2); ?>
                        </div>
                        <p class="text-sm text-purple-600">costo total de adquisiciones</p>
                    </div>
                </div>
            </div>
            
            <?php else: ?>
            <div class="text-center py-16">
                <div class="w-24 h-24 mx-auto bg-gradient-to-br from-orange-50 to-orange-100 rounded-full flex items-center justify-center mb-6">
                    <i class="fas fa-chart-bar text-4xl text-gray-400"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-700 mb-3">No hay datos de reportes</h3>
                <p class="text-gray-500 mb-6">No se encontraron adquisiciones para generar reportes</p>
                <a href="?accion=listar" 
                class="gradient-bg-proceso3 hover:opacity-90 text-white px-6 py-3 rounded-xl font-medium inline-flex items-center gap-2 shadow-lg hover-lift">
                    <i class="fas fa-arrow-left"></i>
                    Volver a adquisiciones
                </a>
            </div>
            <?php endif; ?>
        </div>

        <?php endif; ?>
        <!-- ========== FIN SECCIÓN REPORTES ========== -->
        
        <!-- ========== INFORMACIÓN DEL PROCESO PMBOK ========== -->
        <div class="mt-8 glass-card rounded-2xl p-6">
            <div class="flex items-start gap-4">
                <div class="w-14 h-14 bg-gradient-to-r from-orange-100 to-red-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-info-circle text-orange-600 text-xl"></i>
                </div>
                <div class="flex-1">
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Proceso 3: Adquirir Recursos</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-gradient-to-r from-orange-50 to-orange-100 p-4 rounded-xl">
                            <h4 class="font-semibold text-orange-800 mb-2">Objetivo</h4>
                            <p class="text-sm text-orange-700">Obtener los recursos del equipo, suministros o materiales necesarios para completar las actividades del proyecto.</p>
                        </div>
                        <div class="bg-gradient-to-r from-purple-50 to-purple-100 p-4 rounded-xl">
                            <h4 class="font-semibold text-purple-800 mb-2">Herramientas</h4>
                            <ul class="text-sm text-purple-700 list-disc pl-5">
                                <li>Negociación</li>
                                <li>Adquisición</li>
                                <li>Análisis de proveedores</li>
                                <li>Contratación</li>
                            </ul>
                        </div>
                        <div class="bg-gradient-to-r from-emerald-50 to-green-100 p-4 rounded-xl">
                            <h4 class="font-semibold text-emerald-800 mb-2">Salidas</h4>
                            <ul class="text-sm text-emerald-700 list-disc pl-5">
                                <li>Recursos físicos asignados</li>
                                <li>Acuerdos con proveedores</li>
                                <li>Cambios en documentos</li>
                                <li>Actualizaciones activos</li>
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
            
            // Menú desplegable de cambio de estado
            document.querySelectorAll('.relative.group button').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const menu = this.nextElementSibling;
                    menu.classList.toggle('hidden');
                });
            });
            
            // Cerrar menú al hacer clic fuera
            document.addEventListener('click', function() {
                document.querySelectorAll('.group .hidden').forEach(menu => {
                    if (!menu.classList.contains('hidden')) {
                        menu.classList.add('hidden');
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
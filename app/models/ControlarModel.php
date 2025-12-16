<?php
/**
 * Modelo para la gestión de controlar recursos - Proceso 6 PMBOK
 */
class ControlRecursosModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    /**
     * Obtener controles de recursos con datos relacionados
     */
    public function obtenerControles($filtros = []) {
        $sql = "SELECT 
                    cr.*,
                    p.nombre as proyecto_nombre,
                    p.estado as proyecto_estado,
                    u.nombre as responsable_nombre,
                    u.email as responsable_email,
                    CASE 
                        WHEN cr.variacion < 0 THEN 'favorable'
                        WHEN cr.variacion > 0 THEN 'desfavorable'
                        ELSE 'neutral'
                    END as tipo_variacion,
                    ABS(cr.variacion) as variacion_absoluta,
                    (cr.valor_actual * 100.0 / NULLIF(cr.valor_planificado, 0)) as porcentaje_ejecucion
                FROM control_recursos cr
                JOIN proyectos p ON cr.proyecto_id = p.id
                LEFT JOIN usuarios u ON cr.responsable_id = u.id
                WHERE 1=1";

        $params = [];

        // Filtros
        if (!empty($filtros['proyecto_id'])) {
            $sql .= " AND cr.proyecto_id = ?";
            $params[] = $filtros['proyecto_id'];
        }

        if (!empty($filtros['tipo_recurso'])) {
            $sql .= " AND cr.tipo_recurso = ?";
            $params[] = $filtros['tipo_recurso'];
        }

        if (!empty($filtros['tipo_variacion'])) {
            if ($filtros['tipo_variacion'] == 'favorable') {
                $sql .= " AND cr.variacion < 0";
            } elseif ($filtros['tipo_variacion'] == 'desfavorable') {
                $sql .= " AND cr.variacion > 0";
            } else {
                $sql .= " AND cr.variacion = 0";
            }
        }

        if (!empty($filtros['fecha_desde'])) {
            $sql .= " AND cr.fecha_control >= ?";
            $params[] = $filtros['fecha_desde'];
        }

        if (!empty($filtros['fecha_hasta'])) {
            $sql .= " AND cr.fecha_control <= ?";
            $params[] = $filtros['fecha_hasta'];
        }

        $sql .= " ORDER BY cr.fecha_control DESC, cr.id DESC";

        try {
            $stmt = $this->db->prepare($sql);
            if ($params) {
                $stmt->execute($params);
            } else {
                $stmt->execute();
            }
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            error_log("Error obtenerControles: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtener un control específico por ID
     */
    public function obtenerControlPorId($id) {
        $sql = "SELECT 
                    cr.*,
                    p.nombre as proyecto_nombre,
                    p.descripcion as proyecto_descripcion,
                    p.presupuesto_estimado,
                    u.nombre as responsable_nombre,
                    u.email as responsable_email,
                    CASE 
                        WHEN cr.variacion < 0 THEN 'favorable'
                        WHEN cr.variacion > 0 THEN 'desfavorable'
                        ELSE 'neutral'
                    END as tipo_variacion
                FROM control_recursos cr
                JOIN proyectos p ON cr.proyecto_id = p.id
                LEFT JOIN usuarios u ON cr.responsable_id = u.id
                WHERE cr.id = ?";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error obtenerControlPorId: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Obtener reportes de rendimiento
     */
    public function obtenerReportesRendimiento($filtros = [], $limite = null) {
        $sql = "SELECT 
                    rr.*,
                    p.nombre as proyecto_nombre,
                    u.nombre as generado_por_nombre,
                    DATE_FORMAT(rr.fecha_generacion, '%d/%m/%Y %H:%i') as fecha_generacion_formatted,
                    CASE 
                        WHEN rr.variacion_presupuesto < 0 THEN 'ahorro'
                        WHEN rr.variacion_presupuesto > 0 THEN 'sobrecosto'
                        ELSE 'neutral'
                    END as tipo_variacion_presupuesto
                FROM reportes_rendimiento rr
                JOIN proyectos p ON rr.proyecto_id = p.id
                LEFT JOIN usuarios u ON rr.generado_por = u.id
                WHERE 1=1";

        $params = [];

        // Filtros
        if (!empty($filtros['proyecto_id'])) {
            $sql .= " AND rr.proyecto_id = ?";
            $params[] = $filtros['proyecto_id'];
        }

        if (!empty($filtros['periodo'])) {
            $sql .= " AND rr.periodo = ?";
            $params[] = $filtros['periodo'];
        }

        if (!empty($filtros['fecha_desde'])) {
            $sql .= " AND rr.fecha_inicio >= ?";
            $params[] = $filtros['fecha_desde'];
        }

        if (!empty($filtros['fecha_hasta'])) {
            $sql .= " AND rr.fecha_fin <= ?";
            $params[] = $filtros['fecha_hasta'];
        }

        $sql .= " ORDER BY rr.fecha_generacion DESC, rr.id DESC";

        if ($limite) {
            $sql .= " LIMIT ?";
            $params[] = $limite;
        }

        try {
            $stmt = $this->db->prepare($sql);
            if ($params) {
                $stmt->execute($params);
            } else {
                $stmt->execute();
            }
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error obtenerReportesRendimiento: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtener un reporte específico por ID
     */
    public function obtenerReportePorId($id) {
        $sql = "SELECT 
                    rr.*,
                    p.nombre as proyecto_nombre,
                    p.descripcion as proyecto_descripcion,
                    u.nombre as generado_por_nombre,
                    u.email as generado_por_email,
                    DATE_FORMAT(rr.fecha_generacion, '%Y-%m-%d') as fecha_generacion_form,
                    DATE_FORMAT(rr.fecha_generacion, '%H:%i') as hora_generacion_form
                FROM reportes_rendimiento rr
                JOIN proyectos p ON rr.proyecto_id = p.id
                LEFT JOIN usuarios u ON rr.generado_por = u.id
                WHERE rr.id = ?";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error obtenerReportePorId: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Obtener recursos para monitorear
     */
    public function obtenerRecursosParaControl($proyecto_id = null) {
        $sql = "SELECT 
                    pr.id,
                    pr.proyecto_id,
                    p.nombre as proyecto_nombre,
                    pr.tipo_recurso,
                    pr.descripcion,
                    pr.cantidad_estimada,
                    pr.costo_total_estimado,
                    pr.prioridad,
                    COALESCE(er.costo_real_total, 0) as costo_real_total,
                    COALESCE(er.cantidad_real, 0) as cantidad_real,
                    COALESCE(ar.costo_adquisicion, 0) as costo_adquisicion,
                    COALESCE(ar.estado, 'no_adquirido') as estado_adquisicion
                FROM planificacion_recursos pr
                JOIN proyectos p ON pr.proyecto_id = p.id
                LEFT JOIN estimacion_recursos er ON pr.id = er.planificacion_id
                LEFT JOIN adquisicion_recursos ar ON er.id = ar.estimacion_id
                WHERE 1=1";

        $params = [];

        if ($proyecto_id) {
            $sql .= " AND pr.proyecto_id = ?";
            $params[] = $proyecto_id;
        }

        $sql .= " ORDER BY p.nombre, pr.prioridad, pr.tipo_recurso";

        try {
            $stmt = $this->db->prepare($sql);
            if ($params) {
                $stmt->execute($params);
            } else {
                $stmt->execute();
            }
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error obtenerRecursosParaControl: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Crear nuevo control de recurso
     */
    public function crearControl($datos) {
        $this->db->beginTransaction();
        
        try {
            $sql = "INSERT INTO control_recursos (
                        proyecto_id,
                        recurso_id,
                        tipo_recurso,
                        tabla_referencia,
                        metrica,
                        valor_planificado,
                        valor_actual,
                        variacion,
                        fecha_control,
                        desviacion,
                        accion_correctiva,
                        responsable_id
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                $datos['proyecto_id'],
                $datos['recurso_id'] ?? null,
                $datos['tipo_recurso'],
                $datos['tabla_referencia'] ?? null,
                $datos['metrica'],
                $datos['valor_planificado'] ?? 0,
                $datos['valor_actual'] ?? 0,
                $datos['variacion'] ?? 0,
                $datos['fecha_control'] ?? date('Y-m-d'),
                $datos['desviacion'] ?? null,
                $datos['accion_correctiva'] ?? null,
                $datos['responsable_id'] ?? null
            ]);
            
            $control_id = $this->db->lastInsertId();
            
            $this->db->commit();
            return $control_id;
            
        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log("Error crearControl: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Actualizar control de recurso
     */
    public function actualizarControl($id, $datos) {
        $sql = "UPDATE control_recursos SET
                    metrica = ?,
                    valor_planificado = ?,
                    valor_actual = ?,
                    variacion = ?,
                    fecha_control = ?,
                    desviacion = ?,
                    accion_correctiva = ?,
                    responsable_id = ?
                WHERE id = ?";

        try {
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                $datos['metrica'],
                $datos['valor_planificado'],
                $datos['valor_actual'],
                $datos['variacion'],
                $datos['fecha_control'],
                $datos['desviacion'],
                $datos['accion_correctiva'],
                $datos['responsable_id'],
                $id
            ]);
        } catch (PDOException $e) {
            error_log("Error actualizarControl: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Eliminar control de recurso
     */
    public function eliminarControl($id) {
        $sql = "DELETE FROM control_recursos WHERE id = ?";
        try {
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log("Error eliminarControl: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Crear reporte de rendimiento
     */
    public function crearReporteRendimiento($datos) {
        $sql = "INSERT INTO reportes_rendimiento (
                    proyecto_id,
                    periodo,
                    fecha_inicio,
                    fecha_fin,
                    eficiencia_recursos,
                    cumplimiento_plazos,
                    variacion_presupuesto,
                    productividad_equipo,
                    observaciones,
                    generado_por
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        try {
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                $datos['proyecto_id'],
                $datos['periodo'],
                $datos['fecha_inicio'],
                $datos['fecha_fin'],
                $datos['eficiencia_recursos'] ?? 0,
                $datos['cumplimiento_plazos'] ?? 0,
                $datos['variacion_presupuesto'] ?? 0,
                $datos['productividad_equipo'] ?? 0,
                $datos['observaciones'],
                $datos['generado_por']
            ]);
        } catch (PDOException $e) {
            error_log("Error crearReporteRendimiento: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Actualizar reporte de rendimiento
     */
    public function actualizarReporteRendimiento($id, $datos) {
        $sql = "UPDATE reportes_rendimiento SET
                    periodo = ?,
                    fecha_inicio = ?,
                    fecha_fin = ?,
                    eficiencia_recursos = ?,
                    cumplimiento_plazos = ?,
                    variacion_presupuesto = ?,
                    productividad_equipo = ?,
                    observaciones = ?
                WHERE id = ?";

        try {
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                $datos['periodo'],
                $datos['fecha_inicio'],
                $datos['fecha_fin'],
                $datos['eficiencia_recursos'],
                $datos['cumplimiento_plazos'],
                $datos['variacion_presupuesto'],
                $datos['productividad_equipo'],
                $datos['observaciones'],
                $id
            ]);
        } catch (PDOException $e) {
            error_log("Error actualizarReporteRendimiento: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Eliminar reporte de rendimiento
     */
    public function eliminarReporteRendimiento($id) {
        $sql = "DELETE FROM reportes_rendimiento WHERE id = ?";
        try {
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log("Error eliminarReporteRendimiento: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtener estadísticas de control
     */
    public function obtenerEstadisticasControl($proyecto_id = null) {
        $sql = "SELECT 
                    COUNT(*) as total_controles,
                    COUNT(CASE WHEN tipo_recurso = 'humano' THEN 1 END) as controles_humanos,
                    COUNT(CASE WHEN tipo_recurso = 'material' THEN 1 END) as controles_materiales,
                    COUNT(CASE WHEN tipo_recurso = 'equipo' THEN 1 END) as controles_equipos,
                    COUNT(CASE WHEN tipo_recurso = 'financiero' THEN 1 END) as controles_financieros,
                    COUNT(CASE WHEN tipo_recurso = 'tecnologico' THEN 1 END) as controles_tecnologicos,
                    
                    COUNT(CASE WHEN variacion < 0 THEN 1 END) as variaciones_favorables,
                    COUNT(CASE WHEN variacion > 0 THEN 1 END) as variaciones_desfavorables,
                    COUNT(CASE WHEN variacion = 0 THEN 1 END) as variaciones_neutrales,
                    
                    COALESCE(SUM(valor_planificado), 0) as valor_planificado_total,
                    COALESCE(SUM(valor_actual), 0) as valor_actual_total,
                    COALESCE(SUM(variacion), 0) as variacion_total,
                    
                    COALESCE(AVG(eficiencia_recursos), 0) as eficiencia_recursos_promedio,
                    COALESCE(AVG(cumplimiento_plazos), 0) as cumplimiento_plazos_promedio,
                    COALESCE(AVG(variacion_presupuesto), 0) as variacion_presupuesto_promedio,
                    COALESCE(AVG(productividad_equipo), 0) as productividad_equipo_promedio
                    
                FROM control_recursos cr
                LEFT JOIN reportes_rendimiento rr ON cr.proyecto_id = rr.proyecto_id
                WHERE 1=1";

        $params = [];

        if ($proyecto_id) {
            $sql .= " AND cr.proyecto_id = ?";
            $params[] = $proyecto_id;
        }

        try {
            $stmt = $this->db->prepare($sql);
            if ($params) {
                $stmt->execute($params);
            } else {
                $stmt->execute();
            }
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error obtenerEstadisticasControl: " . $e->getMessage());
            return [
                'total_controles' => 0,
                'controles_humanos' => 0,
                'controles_materiales' => 0,
                'controles_equipos' => 0,
                'controles_financieros' => 0,
                'controles_tecnologicos' => 0,
                'variaciones_favorables' => 0,
                'variaciones_desfavorables' => 0,
                'variaciones_neutrales' => 0,
                'valor_planificado_total' => 0,
                'valor_actual_total' => 0,
                'variacion_total' => 0,
                'eficiencia_recursos_promedio' => 0,
                'cumplimiento_plazos_promedio' => 0,
                'variacion_presupuesto_promedio' => 0,
                'productividad_equipo_promedio' => 0
            ];
        }
    }

    /**
     * Obtener alertas de desviación
     */
    public function obtenerAlertasDesviacion($umbral = 10) {
        $sql = "SELECT 
                    cr.*,
                    p.nombre as proyecto_nombre,
                    ABS(cr.variacion * 100.0 / NULLIF(cr.valor_planificado, 0)) as porcentaje_desviacion,
                    CASE 
                        WHEN cr.variacion < 0 THEN 'favorable'
                        ELSE 'desfavorable'
                    END as tipo_variacion
                FROM control_recursos cr
                JOIN proyectos p ON cr.proyecto_id = p.id
                WHERE ABS(cr.variacion * 100.0 / NULLIF(cr.valor_planificado, 0)) >= ?
                AND cr.fecha_control >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
                ORDER BY ABS(cr.variacion * 100.0 / NULLIF(cr.valor_planificado, 0)) DESC";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$umbral]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error obtenerAlertasDesviacion: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtener tendencias por tipo de recurso
     */
    public function obtenerTendenciasPorTipo($proyecto_id = null) {
        $sql = "SELECT 
                    tipo_recurso,
                    COUNT(*) as total_controles,
                    AVG(valor_planificado) as promedio_planificado,
                    AVG(valor_actual) as promedio_actual,
                    AVG(variacion) as promedio_variacion,
                    SUM(CASE WHEN variacion < 0 THEN 1 ELSE 0 END) as favorables,
                    SUM(CASE WHEN variacion > 0 THEN 1 ELSE 0 END) as desfavorables
                FROM control_recursos
                WHERE 1=1";

        $params = [];

        if ($proyecto_id) {
            $sql .= " AND proyecto_id = ?";
            $params[] = $proyecto_id;
        }

        $sql .= " GROUP BY tipo_recurso ORDER BY total_controles DESC";

        try {
            $stmt = $this->db->prepare($sql);
            if ($params) {
                $stmt->execute($params);
            } else {
                $stmt->execute();
            }
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error obtenerTendenciasPorTipo: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtener datos para dashboard de control
     */
    public function obtenerDashboard($proyecto_id = null) {
        $dashboard = [];
        
        try {
            // Estadísticas de control
            $dashboard['estadisticas'] = $this->obtenerEstadisticasControl($proyecto_id);
            
            // Controles recientes
            $dashboard['controles_recientes'] = $this->obtenerControles(['proyecto_id' => $proyecto_id], 10);
            
            // Alertas de desviación
            $dashboard['alertas'] = $this->obtenerAlertasDesviacion(10);
            
            // Reportes recientes
            $dashboard['reportes_recientes'] = $this->obtenerReportesRendimiento(['proyecto_id' => $proyecto_id], 5);
            
            // Tendencias por tipo de recurso
            $dashboard['tendencias'] = $this->obtenerTendenciasPorTipo($proyecto_id);
            
            // Resumen de recursos por proyecto
            $dashboard['recursos_resumen'] = $this->obtenerResumenRecursosProyecto($proyecto_id);
            
        } catch (Exception $e) {
            error_log("ERROR obtenerDashboard: " . $e->getMessage());
            $dashboard['error'] = $e->getMessage();
        }
        
        return $dashboard;
    }

    /**
     * Obtener resumen de recursos por proyecto
     */
    private function obtenerResumenRecursosProyecto($proyecto_id = null) {
        $sql = "SELECT 
                    p.id,
                    p.nombre as proyecto_nombre,
                    COUNT(pr.id) as total_recursos_planificados,
                    SUM(pr.costo_total_estimado) as presupuesto_planificado,
                    COUNT(DISTINCT cr.id) as controles_realizados,
                    COALESCE(SUM(cr.variacion), 0) as variacion_total,
                    COALESCE(AVG(rr.eficiencia_recursos), 0) as eficiencia_promedio
                FROM proyectos p
                LEFT JOIN planificacion_recursos pr ON p.id = pr.proyecto_id
                LEFT JOIN control_recursos cr ON p.id = cr.proyecto_id
                LEFT JOIN reportes_rendimiento rr ON p.id = rr.proyecto_id
                WHERE 1=1";

        $params = [];

        if ($proyecto_id) {
            $sql .= " AND p.id = ?";
            $params[] = $proyecto_id;
        }

        $sql .= " GROUP BY p.id, p.nombre ORDER BY p.nombre";

        try {
            $stmt = $this->db->prepare($sql);
            if ($params) {
                $stmt->execute($params);
            } else {
                $stmt->execute();
            }
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error obtenerResumenRecursosProyecto: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtener usuarios para asignar como responsables
     */
    public function obtenerResponsables() {
        $sql = "SELECT id, nombre, email, rol 
                FROM usuarios 
                WHERE activo = 1 AND rol IN ('gerente', 'administrador')
                ORDER BY nombre";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error obtenerResponsables: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Calcular variación automáticamente
     */
    public function calcularVariacion($valor_planificado, $valor_actual) {
        return $valor_actual - $valor_planificado;
    }

    /**
     * Generar reporte automático de rendimiento
     */
    public function generarReporteAutomatico($proyecto_id, $periodo = 'mensual') {
        // Obtener datos del proyecto
        $sql_proyecto = "SELECT * FROM proyectos WHERE id = ?";
        $stmt = $this->db->prepare($sql_proyecto);
        $stmt->execute([$proyecto_id]);
        $proyecto = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$proyecto) return false;
        
        // Calcular métricas
        $fecha_inicio = date('Y-m-01');
        $fecha_fin = date('Y-m-t');
        
        if ($periodo == 'semanal') {
            $fecha_inicio = date('Y-m-d', strtotime('monday this week'));
            $fecha_fin = date('Y-m-d', strtotime('sunday this week'));
        } elseif ($periodo == 'trimestral') {
            $mes = date('n');
            $trimestre = ceil($mes / 3);
            $fecha_inicio = date('Y-' . (($trimestre - 1) * 3 + 1) . '-01');
            $fecha_fin = date('Y-' . ($trimestre * 3) . '-t');
        }
        
        // Aquí irían los cálculos reales de métricas
        // Por simplicidad, usamos valores de ejemplo
        $datos_reporte = [
            'proyecto_id' => $proyecto_id,
            'periodo' => $periodo,
            'fecha_inicio' => $fecha_inicio,
            'fecha_fin' => $fecha_fin,
            'eficiencia_recursos' => rand(70, 95),
            'cumplimiento_plazos' => rand(75, 98),
            'variacion_presupuesto' => rand(-5000, 5000),
            'productividad_equipo' => rand(65, 90),
            'observaciones' => "Reporte automático generado el " . date('d/m/Y'),
            'generado_por' => $_SESSION['user_id'] ?? 1
        ];
        
        return $this->crearReporteRendimiento($datos_reporte);
    }

    /**
     * Obtener histórico de controles para gráficos
     */
    public function obtenerHistoricoControles($proyecto_id = null, $dias = 30) {
        $sql = "SELECT 
                    DATE(fecha_control) as fecha,
                    tipo_recurso,
                    COUNT(*) as cantidad_controles,
                    AVG(variacion) as variacion_promedio,
                    SUM(CASE WHEN variacion < 0 THEN 1 ELSE 0 END) as favorables,
                    SUM(CASE WHEN variacion > 0 THEN 1 ELSE 0 END) as desfavorables
                FROM control_recursos
                WHERE fecha_control >= DATE_SUB(CURDATE(), INTERVAL ? DAY)";

        $params = [$dias];

        if ($proyecto_id) {
            $sql .= " AND proyecto_id = ?";
            $params[] = $proyecto_id;
        }

        $sql .= " GROUP BY DATE(fecha_control), tipo_recurso ORDER BY fecha DESC";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error obtenerHistoricoControles: " . $e->getMessage());
            return [];
        }
    }
}
?>
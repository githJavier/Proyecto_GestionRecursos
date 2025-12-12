<?php
/**
 * Modelo para la gestión de estimaciones de recursos - Proceso 2 PMBOK
 */
class EstimacionModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    /**
     * Obtener todas las estimaciones con datos relacionados
     */
    public function obtenerEstimaciones($filtros = []) {
        $sql = "SELECT 
                    er.*,
                    pr.descripcion as recurso_descripcion,
                    pr.tipo_recurso,
                    pr.cantidad_estimada as cantidad_planificada,
                    pr.costo_unitario_estimado as costo_planificado_unitario,
                    pr.costo_total_estimado as costo_planificado_total,
                    p.nombre as proyecto_nombre,
                    p.id as proyecto_id,
                    u.nombre as estimador_nombre,
                    u.email as estimador_email,
                    ar.estado as estado_adquisicion,
                    ar.proveedor
                FROM estimacion_recursos er
                JOIN planificacion_recursos pr ON er.planificacion_id = pr.id
                JOIN proyectos p ON pr.proyecto_id = p.id
                JOIN usuarios u ON er.estimador_id = u.id
                LEFT JOIN adquisicion_recursos ar ON er.id = ar.estimacion_id
                WHERE 1=1";

        $params = [];

        // Filtros
        if (!empty($filtros['proyecto_id'])) {
            $sql .= " AND p.id = ?";
            $params[] = $filtros['proyecto_id'];
        }

        if (!empty($filtros['tipo_recurso'])) {
            $sql .= " AND pr.tipo_recurso = ?";
            $params[] = $filtros['tipo_recurso'];
        }

        if (!empty($filtros['metodo_estimacion'])) {
            $sql .= " AND er.metodo_estimacion LIKE ?";
            $params[] = "%{$filtros['metodo_estimacion']}%";
        }

        $sql .= " ORDER BY er.fecha_estimacion DESC, er.id DESC";

        try {
            $stmt = $this->db->prepare($sql);
            if ($params) {
                $stmt->execute($params);
            } else {
                $stmt->execute();
            }
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error obtenerEstimaciones: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtener una estimación específica por ID
     */
    public function obtenerEstimacionPorId($id) {
        $sql = "SELECT 
                    er.*,
                    pr.descripcion as recurso_descripcion,
                    pr.tipo_recurso,
                    pr.cantidad_estimada as cantidad_planificada,
                    pr.costo_unitario_estimado as costo_planificado_unitario,
                    pr.costo_total_estimado as costo_planificado_total,
                    pr.proyecto_id,
                    p.nombre as proyecto_nombre,
                    u.nombre as estimador_nombre,
                    u.email as estimador_email
                FROM estimacion_recursos er
                JOIN planificacion_recursos pr ON er.planificacion_id = pr.id
                JOIN proyectos p ON pr.proyecto_id = p.id
                JOIN usuarios u ON er.estimador_id = u.id
                WHERE er.id = ?";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error obtenerEstimacionPorId: " . $e->getMessage());
            return null;
        }
    }

    /**
    * Obtener recursos planificados disponibles para estimar
    */
    public function obtenerRecursosPlanificados($proyecto_id = null) {
        // MODIFICADO: Eliminar el filtro NOT EXISTS
        $sql = "SELECT 
                    pr.*,
                    p.nombre as proyecto_nombre,
                    COUNT(er.id) as estimaciones_existentes
                FROM planificacion_recursos pr
                JOIN proyectos p ON pr.proyecto_id = p.id
                LEFT JOIN estimacion_recursos er ON pr.id = er.planificacion_id
                WHERE 1=1";  // Cambiado: eliminar NOT EXISTS

        $params = [];

        if ($proyecto_id) {
            $sql .= " AND pr.proyecto_id = ?";
            $params[] = $proyecto_id;
        }

        $sql .= " GROUP BY pr.id
                ORDER BY p.nombre, pr.prioridad DESC";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error obtenerRecursosPlanificados: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Crear una nueva estimación
     */
    public function crearEstimacion($datos) {
        // Calcular costo total automáticamente
        $datos['costo_real_total'] = $datos['cantidad_real'] * $datos['costo_real_unitario'];

        $sql = "INSERT INTO estimacion_recursos (
                    planificacion_id,
                    estimador_id,
                    cantidad_real,
                    costo_real_unitario,
                    costo_real_total,
                    metodo_estimacion,
                    nivel_confianza,
                    observaciones,
                    fecha_estimacion
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                $datos['planificacion_id'],
                $datos['estimador_id'],
                $datos['cantidad_real'],
                $datos['costo_real_unitario'],
                $datos['costo_real_total'],
                $datos['metodo_estimacion'],
                $datos['nivel_confianza'],
                $datos['observaciones'],
                $datos['fecha_estimacion'] ?? date('Y-m-d')
            ]);
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            error_log("Error crearEstimacion: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Actualizar una estimación existente
     */
    public function actualizarEstimacion($id, $datos) {
        // Recalcular costo total
        $datos['costo_real_total'] = $datos['cantidad_real'] * $datos['costo_real_unitario'];

        $sql = "UPDATE estimacion_recursos SET
                    cantidad_real = ?,
                    costo_real_unitario = ?,
                    costo_real_total = ?,
                    metodo_estimacion = ?,
                    nivel_confianza = ?,
                    observaciones = ?,
                    fecha_estimacion = ?
                WHERE id = ?";

        try {
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                $datos['cantidad_real'],
                $datos['costo_real_unitario'],
                $datos['costo_real_total'],
                $datos['metodo_estimacion'],
                $datos['nivel_confianza'],
                $datos['observaciones'],
                $datos['fecha_estimacion'],
                $id
            ]);
        } catch (PDOException $e) {
            error_log("Error actualizarEstimacion: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Eliminar una estimación
     */
    public function eliminarEstimacion($id) {
        // Verificar si ya tiene adquisiciones relacionadas
        $sql_check = "SELECT COUNT(*) as total FROM adquisicion_recursos WHERE estimacion_id = ?";
        $stmt = $this->db->prepare($sql_check);
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result['total'] > 0) {
            return ['success' => false, 'message' => 'No se puede eliminar porque ya tiene adquisiciones relacionadas'];
        }

        $sql = "DELETE FROM estimacion_recursos WHERE id = ?";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id]);
            return ['success' => true, 'message' => 'Estimación eliminada correctamente'];
        } catch (PDOException $e) {
            error_log("Error eliminarEstimacion: " . $e->getMessage());
            return ['success' => false, 'message' => 'Error al eliminar la estimación'];
        }
    }

    /**
     * Obtener estadísticas de estimaciones
     */
    public function obtenerEstadisticas($proyecto_id = null) {
        $sql = "SELECT 
                    COUNT(*) as total_estimaciones,
                    SUM(costo_real_total) as costo_total_estimado,
                    AVG(costo_real_total) as costo_promedio,
                    metodo_estimacion,
                    nivel_confianza,
                    COUNT(CASE WHEN nivel_confianza = 'alto' THEN 1 END) as alto_confianza,
                    COUNT(CASE WHEN nivel_confianza = 'medio' THEN 1 END) as medio_confianza,
                    COUNT(CASE WHEN nivel_confianza = 'bajo' THEN 1 END) as bajo_confianza
                FROM estimacion_recursos er
                JOIN planificacion_recursos pr ON er.planificacion_id = pr.id
                WHERE 1=1";

        $params = [];

        if ($proyecto_id) {
            $sql .= " AND pr.proyecto_id = ?";
            $params[] = $proyecto_id;
        }

        $sql .= " GROUP BY metodo_estimacion, nivel_confianza
                  ORDER BY total_estimaciones DESC";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error obtenerEstadisticas: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtener comparación entre planificación y estimación AGRUPADA POR PROYECTO
     */
    public function obtenerComparacionPlanificacionEstimacion($proyecto_id = null) {
        $sql = "SELECT 
                    p.id as proyecto_id,
                    p.nombre as proyecto_nombre,
                    COUNT(DISTINCT pr.id) as total_recursos_planificados,
                    COUNT(DISTINCT er.id) as total_estimaciones,
                    COALESCE(SUM(pr.costo_total_estimado), 0) as costo_total_planificado,
                    COALESCE(SUM(er.costo_real_total), 0) as costo_total_estimado,
                    CASE 
                        WHEN SUM(pr.costo_total_estimado) > 0 
                        THEN ROUND(((SUM(er.costo_real_total) - SUM(pr.costo_total_estimado)) / SUM(pr.costo_total_estimado) * 100), 2)
                        ELSE 0
                    END as variacion_porcentaje
                FROM proyectos p
                LEFT JOIN planificacion_recursos pr ON pr.proyecto_id = p.id
                LEFT JOIN estimacion_recursos er ON pr.id = er.planificacion_id
                WHERE 1=1";

        $params = [];

        if ($proyecto_id) {
            $sql .= " AND p.id = ?";
            $params[] = $proyecto_id;
        }

        // Agrupar por proyecto y filtrar solo proyectos con estimaciones
        $sql .= " GROUP BY p.id, p.nombre
                HAVING COUNT(er.id) > 0  -- Solo proyectos con estimaciones
                ORDER BY p.nombre";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error obtenerComparacion: " . $e->getMessage());
            return [];
        }
    }
}
?>
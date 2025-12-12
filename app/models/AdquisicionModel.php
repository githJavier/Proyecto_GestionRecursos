<?php
/**
 * Modelo para la gestión de adquisiciones de recursos - Proceso 3 PMBOK
 */
class AdquisicionModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    /**
     * Obtener todas las adquisiciones con datos relacionados
     */
    public function obtenerAdquisiciones($filtros = []) {
        $sql = "SELECT 
                    ar.*,
                    er.costo_real_total,
                    er.cantidad_real,
                    er.metodo_estimacion,
                    pr.descripcion as recurso_descripcion,
                    pr.tipo_recurso,
                    pr.cantidad_estimada,
                    p.nombre as proyecto_nombre,
                    p.id as proyecto_id,
                    u.nombre as solicitante_nombre,
                    u.email as solicitante_email,
                    DATE_FORMAT(ar.fecha_entrega_estimada, '%d/%m/%Y') as fecha_entrega_estimada_formatted,
                    DATE_FORMAT(ar.fecha_entrega_real, '%d/%m/%Y') as fecha_entrega_real_formatted,
                    CASE 
                        WHEN ar.fecha_entrega_real IS NOT NULL THEN 
                            DATEDIFF(ar.fecha_entrega_real, ar.fecha_entrega_estimada)
                        ELSE 
                            DATEDIFF(CURDATE(), ar.fecha_entrega_estimada)
                    END as dias_desviacion
                FROM adquisicion_recursos ar
                JOIN estimacion_recursos er ON ar.estimacion_id = er.id
                JOIN planificacion_recursos pr ON er.planificacion_id = pr.id
                JOIN proyectos p ON pr.proyecto_id = p.id
                LEFT JOIN usuarios u ON er.estimador_id = u.id
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

        if (!empty($filtros['estado'])) {
            $sql .= " AND ar.estado = ?";
            $params[] = $filtros['estado'];
        }

        if (!empty($filtros['proveedor'])) {
            $sql .= " AND ar.proveedor LIKE ?";
            $params[] = "%{$filtros['proveedor']}%";
        }

        $sql .= " ORDER BY ar.created_at DESC, ar.id DESC";

        try {
            $stmt = $this->db->prepare($sql);
            if ($params) {
                $stmt->execute($params);
            } else {
                $stmt->execute();
            }
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error obtenerAdquisiciones: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtener una adquisición específica por ID
     */
    public function obtenerAdquisicionPorId($id) {
        $sql = "SELECT 
                    ar.*,
                    er.costo_real_total,
                    er.cantidad_real,
                    er.metodo_estimacion,
                    er.observaciones as estimacion_observaciones,
                    pr.descripcion as recurso_descripcion,
                    pr.tipo_recurso,
                    pr.cantidad_estimada,
                    pr.costo_total_estimado,
                    p.nombre as proyecto_nombre,
                    p.id as proyecto_id,
                    u.nombre as solicitante_nombre,
                    u.email as solicitante_email
                FROM adquisicion_recursos ar
                JOIN estimacion_recursos er ON ar.estimacion_id = er.id
                JOIN planificacion_recursos pr ON er.planificacion_id = pr.id
                JOIN proyectos p ON pr.proyecto_id = p.id
                LEFT JOIN usuarios u ON er.estimador_id = u.id
                WHERE ar.id = ?";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error obtenerAdquisicionPorId: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Obtener estimaciones disponibles para adquisición
     */
    public function obtenerEstimacionesParaAdquisicion($proyecto_id = null) {
        $sql = "SELECT 
                    er.*,
                    pr.descripcion as recurso_descripcion,
                    pr.tipo_recurso,
                    pr.cantidad_estimada,
                    p.nombre as proyecto_nombre,
                    p.id as proyecto_id,
                    u.nombre as estimador_nombre,
                    COUNT(ar.id) as adquisiciones_existentes
                FROM estimacion_recursos er
                JOIN planificacion_recursos pr ON er.planificacion_id = pr.id
                JOIN proyectos p ON pr.proyecto_id = p.id
                JOIN usuarios u ON er.estimador_id = u.id
                LEFT JOIN adquisicion_recursos ar ON er.id = ar.estimacion_id
                WHERE NOT EXISTS (
                    SELECT 1 
                    FROM adquisicion_recursos ar2 
                    WHERE ar2.estimacion_id = er.id 
                    AND ar2.estado NOT IN ('cancelado')
                )
                AND er.costo_real_total > 0";

        $params = [];

        if ($proyecto_id) {
            $sql .= " AND p.id = ?";
            $params[] = $proyecto_id;
        }

        $sql .= " GROUP BY er.id
                ORDER BY p.nombre, er.fecha_estimacion DESC";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error obtenerEstimacionesParaAdquisicion: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Crear una nueva adquisición
     */
    public function crearAdquisicion($datos) {
        $sql = "INSERT INTO adquisicion_recursos (
                    estimacion_id,
                    proveedor,
                    metodo_adquisicion,
                    fecha_orden,
                    fecha_entrega_estimada,
                    fecha_entrega_real,
                    costo_adquisicion,
                    estado,
                    contrato_ref,
                    created_at
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                $datos['estimacion_id'],
                $datos['proveedor'],
                $datos['metodo_adquisicion'],
                $datos['fecha_orden'],
                $datos['fecha_entrega_estimada'],
                $datos['fecha_entrega_real'] ?? null,
                $datos['costo_adquisicion'],
                $datos['estado'],
                $datos['contrato_ref'] ?? null
            ]);
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            error_log("Error crearAdquisicion: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Actualizar una adquisición existente
     */
    public function actualizarAdquisicion($id, $datos) {
        $sql = "UPDATE adquisicion_recursos SET
                    proveedor = ?,
                    metodo_adquisicion = ?,
                    fecha_orden = ?,
                    fecha_entrega_estimada = ?,
                    fecha_entrega_real = ?,
                    costo_adquisicion = ?,
                    estado = ?,
                    contrato_ref = ?
                WHERE id = ?";

        try {
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                $datos['proveedor'],
                $datos['metodo_adquisicion'],
                $datos['fecha_orden'],
                $datos['fecha_entrega_estimada'],
                $datos['fecha_entrega_real'] ?? null,
                $datos['costo_adquisicion'],
                $datos['estado'],
                $datos['contrato_ref'] ?? null,
                $id
            ]);
        } catch (PDOException $e) {
            error_log("Error actualizarAdquisicion: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Eliminar una adquisición
     */
    public function eliminarAdquisicion($id) {
        $sql = "DELETE FROM adquisicion_recursos WHERE id = ?";
        try {
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log("Error eliminarAdquisicion: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtener estadísticas de adquisiciones
     */
    public function obtenerEstadisticas($proyecto_id = null) {
        $sql = "SELECT 
                    COUNT(*) as total_adquisiciones,
                    SUM(costo_adquisicion) as costo_total_adquisiciones,
                    AVG(costo_adquisicion) as costo_promedio,
                    estado,
                    COUNT(CASE WHEN estado = 'entregado' THEN 1 END) as entregadas,
                    COUNT(CASE WHEN estado = 'pendiente' THEN 1 END) as pendientes,
                    COUNT(CASE WHEN estado = 'ordenado' THEN 1 END) as ordenadas,
                    COUNT(CASE WHEN estado = 'cancelado' THEN 1 END) as canceladas,
                    metodo_adquisicion,
                    COUNT(DISTINCT proveedor) as proveedores_unicos
                FROM adquisicion_recursos ar
                JOIN estimacion_recursos er ON ar.estimacion_id = er.id
                JOIN planificacion_recursos pr ON er.planificacion_id = pr.id
                WHERE 1=1";

        $params = [];

        if ($proyecto_id) {
            $sql .= " AND pr.proyecto_id = ?";
            $params[] = $proyecto_id;
        }

        $sql .= " GROUP BY estado, metodo_adquisicion
                  ORDER BY total_adquisiciones DESC";

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
     * Obtener comparación entre estimación y adquisición
     */
    public function obtenerComparacionEstimacionAdquisicion($proyecto_id = null) {
        $sql = "SELECT 
                    p.id as proyecto_id,
                    p.nombre as proyecto_nombre,
                    COUNT(DISTINCT er.id) as total_estimaciones,
                    COUNT(DISTINCT ar.id) as total_adquisiciones,
                    COALESCE(SUM(er.costo_real_total), 0) as costo_total_estimado,
                    COALESCE(SUM(ar.costo_adquisicion), 0) as costo_total_adquisicion,
                    CASE 
                        WHEN SUM(er.costo_real_total) > 0 
                        THEN ROUND(((SUM(ar.costo_adquisicion) - SUM(er.costo_real_total)) / SUM(er.costo_real_total) * 100), 2)
                        ELSE 0
                    END as variacion_porcentaje
                FROM proyectos p
                LEFT JOIN planificacion_recursos pr ON pr.proyecto_id = p.id
                LEFT JOIN estimacion_recursos er ON pr.id = er.planificacion_id
                LEFT JOIN adquisicion_recursos ar ON er.id = ar.estimacion_id
                WHERE 1=1";

        $params = [];

        if ($proyecto_id) {
            $sql .= " AND p.id = ?";
            $params[] = $proyecto_id;
        }

        $sql .= " GROUP BY p.id, p.nombre
                HAVING COUNT(ar.id) > 0
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

    /**
     * Actualizar estado de una adquisición
     */
    public function actualizarEstado($id, $estado, $fecha_entrega_real = null) {
        $sql = "UPDATE adquisicion_recursos SET 
                    estado = ?,
                    fecha_entrega_real = ?
                WHERE id = ?";

        try {
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                $estado,
                $fecha_entrega_real,
                $id
            ]);
        } catch (PDOException $e) {
            error_log("Error actualizarEstado: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtener adquisiciones próximas a vencer (7 días)
     */
    public function obtenerAdquisicionesProximasAVencer() {
        $sql = "SELECT 
                    ar.*,
                    p.nombre as proyecto_nombre,
                    pr.descripcion as recurso_descripcion,
                    DATEDIFF(fecha_entrega_estimada, CURDATE()) as dias_restantes
                FROM adquisicion_recursos ar
                JOIN estimacion_recursos er ON ar.estimacion_id = er.id
                JOIN planificacion_recursos pr ON er.planificacion_id = pr.id
                JOIN proyectos p ON pr.proyecto_id = p.id
                WHERE ar.estado IN ('pendiente', 'ordenado')
                AND ar.fecha_entrega_estimada >= CURDATE()
                AND DATEDIFF(ar.fecha_entrega_estimada, CURDATE()) <= 7
                ORDER BY ar.fecha_entrega_estimada ASC
                LIMIT 10";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error obtenerAdquisicionesProximasAVencer: " . $e->getMessage());
            return [];
        }
    }
}
?>
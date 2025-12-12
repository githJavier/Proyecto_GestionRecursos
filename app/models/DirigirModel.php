<?php
/**
 * Modelo para la gestión de dirigir equipo - Proceso 5 PMBOK
 */
class DirigirEquipoModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    /**
     * Obtener todas las tareas con datos relacionados
     */
    public function obtenerTareas($filtros = []) {
        $sql = "SELECT 
                    at.*,
                    p.nombre as proyecto_nombre,
                    p.estado as proyecto_estado,
                    rh.usuario_id,
                    u.nombre as recurso_nombre,
                    u.email as recurso_email,
                    rh.rol_proyecto,
                    CASE 
                        WHEN at.fecha_limite < CURDATE() AND at.estado NOT IN ('completada', 'cancelada') 
                        THEN 'atrasada' 
                        ELSE at.estado 
                    END as estado_actual,
                    DATEDIFF(at.fecha_limite, CURDATE()) as dias_restantes,
                    (at.horas_reales * 100.0 / NULLIF(at.horas_estimadas, 0)) as porcentaje_horas
                FROM asignacion_tareas at
                JOIN proyectos p ON at.proyecto_id = p.id
                JOIN recursos_humanos rh ON at.recurso_humano_id = rh.id
                JOIN usuarios u ON rh.usuario_id = u.id
                WHERE 1=1";

        $params = [];

        // Filtros
        if (!empty($filtros['proyecto_id'])) {
            $sql .= " AND at.proyecto_id = ?";
            $params[] = $filtros['proyecto_id'];
        }

        if (!empty($filtros['estado'])) {
            if ($filtros['estado'] == 'atrasada') {
                $sql .= " AND at.fecha_limite < CURDATE() AND at.estado NOT IN ('completada', 'cancelada')";
            } else {
                $sql .= " AND at.estado = ?";
                $params[] = $filtros['estado'];
            }
        }

        if (!empty($filtros['prioridad'])) {
            $sql .= " AND at.prioridad = ?";
            $params[] = $filtros['prioridad'];
        }

        if (!empty($filtros['recurso_humano_id'])) {
            $sql .= " AND at.recurso_humano_id = ?";
            $params[] = $filtros['recurso_humano_id'];
        }

        if (!empty($filtros['fecha_desde'])) {
            $sql .= " AND at.fecha_asignacion >= ?";
            $params[] = $filtros['fecha_desde'];
        }

        if (!empty($filtros['fecha_hasta'])) {
            $sql .= " AND at.fecha_asignacion <= ?";
            $params[] = $filtros['fecha_hasta'];
        }

        $sql .= " ORDER BY 
                    CASE at.prioridad 
                        WHEN 'critica' THEN 1
                        WHEN 'alta' THEN 2
                        WHEN 'media' THEN 3
                        WHEN 'baja' THEN 4
                    END,
                    at.fecha_limite ASC,
                    at.id DESC";

        try {
            $stmt = $this->db->prepare($sql);
            if ($params) {
                $stmt->execute($params);
            } else {
                $stmt->execute();
            }
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            error_log("Error obtenerTareas: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtener una tarea específica por ID
     */
    public function obtenerTareaPorId($id) {
        $sql = "SELECT 
                    at.*,
                    p.nombre as proyecto_nombre,
                    p.descripcion as proyecto_descripcion,
                    rh.usuario_id,
                    u.nombre as recurso_nombre,
                    u.email as recurso_email,
                    rh.rol_proyecto,
                    rh.habilidades,
                    CASE 
                        WHEN at.fecha_limite < CURDATE() AND at.estado NOT IN ('completada', 'cancelada') 
                        THEN 'atrasada' 
                        ELSE at.estado 
                    END as estado_actual
                FROM asignacion_tareas at
                JOIN proyectos p ON at.proyecto_id = p.id
                JOIN recursos_humanos rh ON at.recurso_humano_id = rh.id
                JOIN usuarios u ON rh.usuario_id = u.id
                WHERE at.id = ?";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error obtenerTareaPorId: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Obtener comunicaciones del equipo
     */
    public function obtenerComunicaciones($filtros = [], $limite = null) {
        $sql = "SELECT 
                    c.*,
                    p.nombre as proyecto_nombre,
                    e.nombre as emisor_nombre,
                    e.email as emisor_email,
                    r.nombre as receptor_nombre,
                    r.email as receptor_email,
                    DATE_FORMAT(c.fecha_envio, '%d/%m/%Y %H:%i') as fecha_envio_formatted,
                    CASE 
                        WHEN c.prioridad = 'alta' THEN 'Alta'
                        WHEN c.prioridad = 'normal' THEN 'Normal'
                        WHEN c.prioridad = 'baja' THEN 'Baja'
                    END as prioridad_texto
                FROM comunicaciones c
                JOIN proyectos p ON c.proyecto_id = p.id
                JOIN usuarios e ON c.emisor_id = e.id
                LEFT JOIN usuarios r ON c.receptor_id = r.id
                WHERE 1=1";

        $params = [];

        // Filtros
        if (!empty($filtros['proyecto_id'])) {
            $sql .= " AND c.proyecto_id = ?";
            $params[] = $filtros['proyecto_id'];
        }

        if (!empty($filtros['tipo'])) {
            $sql .= " AND c.tipo = ?";
            $params[] = $filtros['tipo'];
        }

        if (!empty($filtros['emisor_id'])) {
            $sql .= " AND c.emisor_id = ?";
            $params[] = $filtros['emisor_id'];
        }

        if (!empty($filtros['receptor_id'])) {
            $sql .= " AND c.receptor_id = ?";
            $params[] = $filtros['receptor_id'];
        }

        if (!empty($filtros['prioridad'])) {
            $sql .= " AND c.prioridad = ?";
            $params[] = $filtros['prioridad'];
        }

        if (!empty($filtros['leido'])) {
            $sql .= " AND c.leido = ?";
            $params[] = $filtros['leido'];
        }

        $sql .= " ORDER BY c.fecha_envio DESC, c.id DESC";

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
            error_log("Error obtenerComunicaciones: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtener una comunicación específica por ID
     */
    public function obtenerComunicacionPorId($id) {
        $sql = "SELECT 
                    c.*,
                    p.nombre as proyecto_nombre,
                    e.nombre as emisor_nombre,
                    e.email as emisor_email,
                    r.nombre as receptor_nombre,
                    r.email as receptor_email,
                    DATE_FORMAT(c.fecha_envio, '%Y-%m-%d') as fecha_envio_form,
                    DATE_FORMAT(c.fecha_envio, '%H:%i') as hora_envio_form
                FROM comunicaciones c
                JOIN proyectos p ON c.proyecto_id = p.id
                JOIN usuarios e ON c.emisor_id = e.id
                LEFT JOIN usuarios r ON c.receptor_id = r.id
                WHERE c.id = ?";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error obtenerComunicacionPorId: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Obtener recursos humanos para asignar tareas
     */
    public function obtenerRecursosParaTareas($proyecto_id = null) {
        $sql = "SELECT 
                    rh.id,
                    u.nombre,
                    u.email,
                    rh.rol_proyecto,
                    rh.nivel_experiencia,
                    p.nombre as proyecto_nombre,
                    COUNT(at.id) as tareas_actuales,
                    SUM(at.horas_estimadas) as horas_totales_asignadas
                FROM recursos_humanos rh
                JOIN usuarios u ON rh.usuario_id = u.id
                JOIN proyectos p ON rh.proyecto_id = p.id
                LEFT JOIN asignacion_tareas at ON rh.id = at.recurso_humano_id AND at.estado NOT IN ('completada', 'cancelada')
                WHERE u.activo = 1 
                AND p.estado IN ('planificacion', 'en_ejecucion')";

        $params = [];

        if ($proyecto_id) {
            $sql .= " AND rh.proyecto_id = ?";
            $params[] = $proyecto_id;
        }

        $sql .= " GROUP BY rh.id, u.nombre, u.email, rh.rol_proyecto, rh.nivel_experiencia, p.nombre
                ORDER BY p.nombre, u.nombre";

        try {
            $stmt = $this->db->prepare($sql);
            if ($params) {
                $stmt->execute($params);
            } else {
                $stmt->execute();
            }
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error obtenerRecursosParaTareas: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Crear nueva tarea
     */
    public function crearTarea($datos) {
        $this->db->beginTransaction();
        
        try {
            $sql = "INSERT INTO asignacion_tareas (
                        proyecto_id,
                        recurso_humano_id,
                        descripcion_tarea,
                        fecha_asignacion,
                        fecha_limite,
                        horas_estimadas,
                        horas_reales,
                        prioridad,
                        estado,
                        porcentaje_completado
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                $datos['proyecto_id'],
                $datos['recurso_humano_id'],
                $datos['descripcion_tarea'],
                $datos['fecha_asignacion'] ?? date('Y-m-d'),
                $datos['fecha_limite'],
                $datos['horas_estimadas'] ?? 0,
                $datos['horas_reales'] ?? 0,
                $datos['prioridad'] ?? 'media',
                $datos['estado'] ?? 'pendiente',
                $datos['porcentaje_completado'] ?? 0
            ]);
            
            $tarea_id = $this->db->lastInsertId();
            
            // Crear notificación automática para el recurso asignado
            $this->crearNotificacionTarea($tarea_id, $datos['recurso_humano_id'], $datos['descripcion_tarea']);
            
            $this->db->commit();
            return $tarea_id;
            
        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log("Error crearTarea: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Crear notificación automática para nueva tarea
     */
    private function crearNotificacionTarea($tarea_id, $recurso_humano_id, $descripcion_tarea) {
        // Obtener datos del recurso humano
        $sql_recurso = "SELECT rh.usuario_id, p.id as proyecto_id, u.nombre as usuario_nombre 
                       FROM recursos_humanos rh
                       JOIN usuarios u ON rh.usuario_id = u.id
                       JOIN proyectos p ON rh.proyecto_id = p.id
                       WHERE rh.id = ?";
        
        $stmt = $this->db->prepare($sql_recurso);
        $stmt->execute([$recurso_humano_id]);
        $recurso = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$recurso) return;
        
        // Crear comunicación de notificación
        $sql_comunicacion = "INSERT INTO comunicaciones (
                                proyecto_id,
                                emisor_id,
                                receptor_id,
                                tipo,
                                asunto,
                                mensaje,
                                prioridad
                            ) VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        $stmt_com = $this->db->prepare($sql_comunicacion);
        $stmt_com->execute([
            $recurso['proyecto_id'],
            $_SESSION['user_id'] ?? 1, // ID del usuario actual (emisor)
            $recurso['usuario_id'], // ID del receptor
            'notificacion',
            'Nueva Tarea Asignada',
            "Se te ha asignado una nueva tarea: '" . substr($descripcion_tarea, 0, 100) . "...'\n\nID de Tarea: #{$tarea_id}\nFecha de Asignación: " . date('d/m/Y'),
            'alta'
        ]);
    }

    /**
     * Actualizar tarea
     */
    public function actualizarTarea($id, $datos) {
        $sql = "UPDATE asignacion_tareas SET
                    descripcion_tarea = ?,
                    fecha_limite = ?,
                    horas_estimadas = ?,
                    horas_reales = ?,
                    prioridad = ?,
                    estado = ?,
                    porcentaje_completado = ?
                WHERE id = ?";

        try {
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                $datos['descripcion_tarea'],
                $datos['fecha_limite'],
                $datos['horas_estimadas'],
                $datos['horas_reales'],
                $datos['prioridad'],
                $datos['estado'],
                $datos['porcentaje_completado'],
                $id
            ]);
        } catch (PDOException $e) {
            error_log("Error actualizarTarea: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Eliminar tarea
     */
    public function eliminarTarea($id) {
        $sql = "DELETE FROM asignacion_tareas WHERE id = ?";
        try {
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log("Error eliminarTarea: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Crear comunicación
     */
    public function crearComunicacion($datos) {
        $sql = "INSERT INTO comunicaciones (
                    proyecto_id,
                    emisor_id,
                    receptor_id,
                    tipo,
                    asunto,
                    mensaje,
                    prioridad,
                    leido
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        try {
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                $datos['proyecto_id'],
                $datos['emisor_id'],
                $datos['receptor_id'] ?? null,
                $datos['tipo'],
                $datos['asunto'],
                $datos['mensaje'],
                $datos['prioridad'] ?? 'normal',
                $datos['leido'] ?? 0
            ]);
        } catch (PDOException $e) {
            error_log("Error crearComunicacion: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Actualizar comunicación
     */
    public function actualizarComunicacion($id, $datos) {
        $sql = "UPDATE comunicaciones SET
                    tipo = ?,
                    asunto = ?,
                    mensaje = ?,
                    prioridad = ?,
                    leido = ?
                WHERE id = ?";

        try {
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                $datos['tipo'],
                $datos['asunto'],
                $datos['mensaje'],
                $datos['prioridad'],
                $datos['leido'],
                $id
            ]);
        } catch (PDOException $e) {
            error_log("Error actualizarComunicacion: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Eliminar comunicación
     */
    public function eliminarComunicacion($id) {
        $sql = "DELETE FROM comunicaciones WHERE id = ?";
        try {
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log("Error eliminarComunicacion: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Marcar comunicación como leída
     */
    public function marcarComoLeida($id) {
        $sql = "UPDATE comunicaciones SET leido = 1 WHERE id = ?";
        try {
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log("Error marcarComoLeida: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtener estadísticas de tareas
     */
    public function obtenerEstadisticasTareas($proyecto_id = null) {
        $sql = "SELECT 
                    COUNT(*) as total_tareas,
                    COUNT(CASE WHEN estado = 'pendiente' THEN 1 END) as tareas_pendientes,
                    COUNT(CASE WHEN estado = 'en_progreso' THEN 1 END) as tareas_en_progreso,
                    COUNT(CASE WHEN estado = 'completada' THEN 1 END) as tareas_completadas,
                    COUNT(CASE WHEN estado = 'revision' THEN 1 END) as tareas_en_revision,
                    COUNT(CASE WHEN estado = 'atrasada' THEN 1 END) as tareas_atrasadas,
                    COUNT(CASE WHEN fecha_limite < CURDATE() AND estado NOT IN ('completada', 'cancelada') THEN 1 END) as tareas_vencidas,
                    
                    COUNT(CASE WHEN prioridad = 'critica' THEN 1 END) as tareas_criticas,
                    COUNT(CASE WHEN prioridad = 'alta' THEN 1 END) as tareas_altas,
                    COUNT(CASE WHEN prioridad = 'media' THEN 1 END) as tareas_medias,
                    COUNT(CASE WHEN prioridad = 'baja' THEN 1 END) as tareas_bajas,
                    
                    COALESCE(SUM(horas_estimadas), 0) as horas_estimadas_total,
                    COALESCE(SUM(horas_reales), 0) as horas_reales_total,
                    COALESCE(AVG(porcentaje_completado), 0) as porcentaje_promedio,
                    
                    CASE 
                        WHEN COALESCE(SUM(horas_estimadas), 0) > 0 
                        THEN COALESCE(SUM(horas_reales), 0) * 100.0 / SUM(horas_estimadas)
                        ELSE 0 
                    END as eficiencia_horas
                    
                FROM asignacion_tareas at
                JOIN proyectos p ON at.proyecto_id = p.id
                WHERE 1=1";

        $params = [];

        if ($proyecto_id) {
            $sql .= " AND at.proyecto_id = ?";
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
            error_log("Error obtenerEstadisticasTareas: " . $e->getMessage());
            return [
                'total_tareas' => 0,
                'tareas_pendientes' => 0,
                'tareas_en_progreso' => 0,
                'tareas_completadas' => 0,
                'tareas_en_revision' => 0,
                'tareas_atrasadas' => 0,
                'tareas_vencidas' => 0,
                'tareas_criticas' => 0,
                'tareas_altas' => 0,
                'tareas_medias' => 0,
                'tareas_bajas' => 0,
                'horas_estimadas_total' => 0,
                'horas_reales_total' => 0,
                'porcentaje_promedio' => 0,
                'eficiencia_horas' => 0
            ];
        }
    }

    /**
     * Obtener carga de trabajo por recurso
     */
    public function obtenerCargaTrabajoPorRecurso($proyecto_id = null) {
        $sql = "SELECT 
                    rh.id,
                    u.nombre as recurso_nombre,
                    rh.rol_proyecto,
                    p.nombre as proyecto_nombre,
                    COUNT(at.id) as total_tareas,
                    COUNT(CASE WHEN at.estado = 'pendiente' THEN 1 END) as tareas_pendientes,
                    COUNT(CASE WHEN at.estado = 'en_progreso' THEN 1 END) as tareas_en_progreso,
                    COUNT(CASE WHEN at.estado = 'completada' THEN 1 END) as tareas_completadas,
                    COALESCE(SUM(at.horas_estimadas), 0) as horas_estimadas,
                    COALESCE(SUM(at.horas_reales), 0) as horas_reales,
                    COALESCE(AVG(at.porcentaje_completado), 0) as porcentaje_promedio
                FROM recursos_humanos rh
                JOIN usuarios u ON rh.usuario_id = u.id
                JOIN proyectos p ON rh.proyecto_id = p.id
                LEFT JOIN asignacion_tareas at ON rh.id = at.recurso_humano_id
                WHERE 1=1";

        $params = [];

        if ($proyecto_id) {
            $sql .= " AND rh.proyecto_id = ?";
            $params[] = $proyecto_id;
        }

        $sql .= " GROUP BY rh.id, u.nombre, rh.rol_proyecto, p.nombre
                ORDER BY total_tareas DESC, horas_estimadas DESC";

        try {
            $stmt = $this->db->prepare($sql);
            if ($params) {
                $stmt->execute($params);
            } else {
                $stmt->execute();
            }
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error obtenerCargaTrabajoPorRecurso: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtener tareas próximas a vencer
     */
    public function obtenerTareasProximasVencer($dias = 3) {
        $sql = "SELECT 
                    at.*,
                    p.nombre as proyecto_nombre,
                    u.nombre as recurso_nombre,
                    DATEDIFF(at.fecha_limite, CURDATE()) as dias_restantes
                FROM asignacion_tareas at
                JOIN proyectos p ON at.proyecto_id = p.id
                JOIN recursos_humanos rh ON at.recurso_humano_id = rh.id
                JOIN usuarios u ON rh.usuario_id = u.id
                WHERE at.estado NOT IN ('completada', 'cancelada')
                AND at.fecha_limite BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL ? DAY)
                ORDER BY at.fecha_limite ASC, at.prioridad DESC";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$dias]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error obtenerTareasProximasVencer: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtener actividades recientes (combinación de tareas y comunicaciones)
     */
    public function obtenerActividadesRecientes($proyecto_id = null, $limite = 20) {
        // Actividades de tareas
        $sql_tareas = "SELECT 
                        'tarea' as tipo,
                        at.id,
                        CONCAT('Tarea: ', SUBSTRING(at.descripcion_tarea, 1, 50), '...') as descripcion,
                        at.fecha_asignacion as fecha,
                        p.nombre as proyecto_nombre,
                        u.nombre as usuario_nombre,
                        at.estado,
                        at.prioridad,
                        NULL as leido
                    FROM asignacion_tareas at
                    JOIN proyectos p ON at.proyecto_id = p.id
                    JOIN recursos_humanos rh ON at.recurso_humano_id = rh.id
                    JOIN usuarios u ON rh.usuario_id = u.id
                    WHERE 1=1";
        
        // Actividades de comunicaciones
        $sql_comunicaciones = "SELECT 
                                'comunicacion' as tipo,
                                c.id,
                                CONCAT(c.tipo, ': ', SUBSTRING(c.asunto, 1, 50)) as descripcion,
                                c.fecha_envio as fecha,
                                p.nombre as proyecto_nombre,
                                e.nombre as usuario_nombre,
                                'completada' as estado,
                                c.prioridad,
                                c.leido
                            FROM comunicaciones c
                            JOIN proyectos p ON c.proyecto_id = p.id
                            JOIN usuarios e ON c.emisor_id = e.id
                            WHERE 1=1";
        
        $params = [];
        $params_com = [];

        if ($proyecto_id) {
            $sql_tareas .= " AND at.proyecto_id = ?";
            $sql_comunicaciones .= " AND c.proyecto_id = ?";
            $params[] = $proyecto_id;
            $params_com[] = $proyecto_id;
        }

        $sql_tareas .= " ORDER BY fecha DESC LIMIT " . intval($limite);
        $sql_comunicaciones .= " ORDER BY fecha DESC LIMIT " . intval($limite);
        
        $sql = "($sql_tareas) UNION ALL ($sql_comunicaciones) ORDER BY fecha DESC LIMIT ?";
        
        // Combinar parámetros
        $todos_params = array_merge($params, $params_com, [$limite]);

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute($todos_params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error obtenerActividadesRecientes: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Actualizar porcentaje de completado de tarea
     */
    public function actualizarPorcentajeTarea($id, $porcentaje) {
        // Determinar estado basado en porcentaje
        $estado = 'pendiente';
        if ($porcentaje > 0 && $porcentaje < 100) {
            $estado = 'en_progreso';
        } elseif ($porcentaje >= 100) {
            $estado = 'completada';
            $porcentaje = 100;
        }
        
        $sql = "UPDATE asignacion_tareas SET 
                    porcentaje_completado = ?,
                    estado = ?
                WHERE id = ?";
        
        try {
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$porcentaje, $estado, $id]);
        } catch (PDOException $e) {
            error_log("Error actualizarPorcentajeTarea: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Actualizar horas reales de tarea
     */
    public function actualizarHorasReales($id, $horas_reales) {
        $sql = "UPDATE asignacion_tareas SET horas_reales = ? WHERE id = ?";
        try {
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$horas_reales, $id]);
        } catch (PDOException $e) {
            error_log("Error actualizarHorasReales: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtener comunicaciones no leídas del usuario
     */
    public function obtenerComunicacionesNoLeidas($usuario_id) {
        $sql = "SELECT 
                    c.*,
                    p.nombre as proyecto_nombre,
                    e.nombre as emisor_nombre,
                    DATE_FORMAT(c.fecha_envio, '%d/%m/%Y %H:%i') as fecha_envio_formatted
                FROM comunicaciones c
                JOIN proyectos p ON c.proyecto_id = p.id
                JOIN usuarios e ON c.emisor_id = e.id
                WHERE (c.receptor_id = ? OR c.receptor_id IS NULL)
                AND c.leido = 0
                ORDER BY c.fecha_envio DESC";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$usuario_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error obtenerComunicacionesNoLeidas: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtener dashboard de dirigir equipo
     */
    public function obtenerDashboard($proyecto_id = null) {
        error_log("DEBUG Modelo: Iniciando obtenerDashboard, proyecto_id: " . ($proyecto_id ?? 'null'));
        
        $dashboard = [];
        
        try {
            // Estadísticas de tareas
            $dashboard['estadisticas_tareas'] = $this->obtenerEstadisticasTareas($proyecto_id);
            error_log("DEBUG Modelo: estadisticas_tareas obtenidas: " . json_encode($dashboard['estadisticas_tareas']));
            
            // Tareas próximas a vencer
            $dashboard['tareas_proximas'] = $this->obtenerTareasProximasVencer(7);
            error_log("DEBUG Modelo: tareas_proximas obtenidas: " . count($dashboard['tareas_proximas']));
            
            // Actividades recientes
            $dashboard['actividades_recientes'] = $this->obtenerActividadesRecientes($proyecto_id, 10);
            error_log("DEBUG Modelo: actividades_recientes obtenidas: " . count($dashboard['actividades_recientes']));
            
            // Carga de trabajo
            $dashboard['carga_trabajo'] = $this->obtenerCargaTrabajoPorRecurso($proyecto_id);
            error_log("DEBUG Modelo: carga_trabajo obtenida: " . count($dashboard['carga_trabajo']));
            
            // Comunicaciones recientes
            $dashboard['comunicaciones_recientes'] = $this->obtenerComunicaciones(['proyecto_id' => $proyecto_id], 5);
            error_log("DEBUG Modelo: comunicaciones_recientes obtenidas: " . count($dashboard['comunicaciones_recientes']));
            
        } catch (Exception $e) {
            error_log("ERROR obtenerDashboard: " . $e->getMessage());
            $dashboard['error'] = $e->getMessage();
        }
        
        error_log("DEBUG Modelo: Dashboard completo: " . json_encode(array_keys($dashboard)));
        return $dashboard;
    }
}
?>
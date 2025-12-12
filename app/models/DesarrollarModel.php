<?php
/**
 * Modelo para la gestión del desarrollo de equipo - Proceso 4 PMBOK
 */
class DesarrollarEquipoModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    /**
     * Obtener todos los recursos humanos con datos relacionados
     */
    public function obtenerRecursosHumanos($filtros = []) {
        $sql = "SELECT 
                    rh.*,
                    u.nombre as usuario_nombre,
                    u.email as usuario_email,
                    u.rol as usuario_rol,
                    u.departamento as usuario_departamento,
                    p.nombre as proyecto_nombre,
                    p.estado as proyecto_estado,
                    COUNT(c.id) as total_capacitaciones,
                    SUM(CASE WHEN c.estado = 'completada' THEN 1 ELSE 0 END) as capacitaciones_completadas,
                    SUM(CASE WHEN c.estado = 'en_curso' THEN 1 ELSE 0 END) as capacitaciones_en_curso,
                    SUM(c.costo) as costo_total_capacitaciones,
                    SUM(c.duracion_horas) as total_horas_capacitacion,
                    GROUP_CONCAT(
                        CONCAT(
                            c.id, '|',
                            c.tipo_capacitacion, '|',
                            c.descripcion, '|',
                            c.duracion_horas, '|',
                            c.fecha_inicio, '|',
                            c.fecha_fin, '|',
                            c.estado, '|',
                            c.costo, '|',
                            IFNULL(c.certificacion, '')
                        ) SEPARATOR ';;'
                    ) as capacitaciones_detalle_raw
                FROM recursos_humanos rh
                JOIN usuarios u ON rh.usuario_id = u.id
                JOIN proyectos p ON rh.proyecto_id = p.id
                LEFT JOIN capacitaciones c ON rh.id = c.recurso_humano_id
                WHERE 1=1";

        $params = [];

        // Filtros
        if (!empty($filtros['proyecto_id'])) {
            $sql .= " AND p.id = ?";
            $params[] = $filtros['proyecto_id'];
        }

        if (!empty($filtros['nivel_experiencia'])) {
            $sql .= " AND rh.nivel_experiencia = ?";
            $params[] = $filtros['nivel_experiencia'];
        }

        if (!empty($filtros['usuario_id'])) {
            $sql .= " AND rh.usuario_id = ?";
            $params[] = $filtros['usuario_id'];
        }

        if (!empty($filtros['rol_proyecto'])) {
            $sql .= " AND rh.rol_proyecto LIKE ?";
            $params[] = "%{$filtros['rol_proyecto']}%";
        }

        $sql .= " GROUP BY rh.id
                ORDER BY p.nombre, rh.nivel_experiencia DESC, u.nombre";

        try {
            $stmt = $this->db->prepare($sql);
            if ($params) {
                $stmt->execute($params);
            } else {
                $stmt->execute();
            }
            
            $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Procesar capacitaciones_detalle_raw para convertirlas en array
            foreach ($resultados as &$recurso) {
                $capacitaciones_detalle = [];
                
                if (!empty($recurso['capacitaciones_detalle_raw'])) {
                    $capacitaciones_raw = explode(';;', $recurso['capacitaciones_detalle_raw']);
                    
                    foreach ($capacitaciones_raw as $capacitacion_raw) {
                        $datos = explode('|', $capacitacion_raw, 9); // 9 campos esperados
                        
                        if (count($datos) >= 9) {
                            $capacitaciones_detalle[] = [
                                'id' => $datos[0],
                                'tipo_capacitacion' => $datos[1],
                                'descripcion' => $datos[2],
                                'duracion_horas' => $datos[3],
                                'fecha_inicio' => $datos[4],
                                'fecha_fin' => $datos[5],
                                'estado' => $datos[6],
                                'costo' => $datos[7],
                                'certificacion' => $datos[8],
                                'fecha_inicio_formatted' => !empty($datos[4]) ? date('d/m/Y', strtotime($datos[4])) : 'N/A',
                                'fecha_fin_formatted' => !empty($datos[5]) ? date('d/m/Y', strtotime($datos[5])) : 'N/A',
                                'estado_actual' => $datos[6]
                            ];
                        }
                    }
                }
                
                $recurso['capacitaciones_detalle'] = $capacitaciones_detalle;
            }
            
            return $resultados;
            
        } catch (PDOException $e) {
            error_log("Error obtenerRecursosHumanos: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtener un recurso humano específico por ID
     */
    public function obtenerRecursoHumanoPorId($id) {
        $sql = "SELECT 
                    rh.*,
                    u.nombre as usuario_nombre,
                    u.email as usuario_email,
                    u.rol as usuario_rol,
                    u.departamento as usuario_departamento,
                    p.nombre as proyecto_nombre,
                    p.estado as proyecto_estado,
                    p.fecha_inicio,
                    p.fecha_fin_estimada
                FROM recursos_humanos rh
                JOIN usuarios u ON rh.usuario_id = u.id
                JOIN proyectos p ON rh.proyecto_id = p.id
                WHERE rh.id = ?";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error obtenerRecursoHumanoPorId: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Obtener capacitaciones de un recurso humano
     */
    public function obtenerCapacitacionesPorRecurso($recurso_humano_id) {
        $sql = "SELECT 
                    c.*,
                    DATE_FORMAT(c.fecha_inicio, '%d/%m/%Y') as fecha_inicio_formatted,
                    DATE_FORMAT(c.fecha_fin, '%d/%m/%Y') as fecha_fin_formatted,
                    CASE 
                        WHEN c.fecha_fin < CURDATE() AND c.estado NOT IN ('completada', 'cancelada') 
                        THEN 'atrasada' 
                        ELSE c.estado 
                    END as estado_actual
                FROM capacitaciones c
                WHERE c.recurso_humano_id = ?
                ORDER BY c.fecha_inicio DESC, c.id DESC";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$recurso_humano_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error obtenerCapacitacionesPorRecurso: " . $e->getMessage());
            return [];
        }
    }

    // En DesarrollarEquipoModel.php, después del método obtenerCapacitacionesPorRecurso()

    /**
     * Obtener una capacitación específica por ID
     */
    public function obtenerCapacitacionPorId($id) {
        $sql = "SELECT 
                    c.*,
                    rh.usuario_id,
                    rh.proyecto_id,
                    u.nombre as usuario_nombre,
                    p.nombre as proyecto_nombre,
                    DATE_FORMAT(c.fecha_inicio, '%Y-%m-%d') as fecha_inicio_form,
                    DATE_FORMAT(c.fecha_fin, '%Y-%m-%d') as fecha_fin_form
                FROM capacitaciones c
                JOIN recursos_humanos rh ON c.recurso_humano_id = rh.id
                JOIN usuarios u ON rh.usuario_id = u.id
                JOIN proyectos p ON rh.proyecto_id = p.id
                WHERE c.id = ?";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error obtenerCapacitacionPorId: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Obtener capacitaciones con filtros
     */
    public function obtenerCapacitacionesFiltradas($filtros = []) {
        $sql = "SELECT 
                    c.*,
                    rh.usuario_id,
                    u.nombre as usuario_nombre,
                    p.nombre as proyecto_nombre,
                    DATE_FORMAT(c.fecha_inicio, '%d/%m/%Y') as fecha_inicio_formatted,
                    DATE_FORMAT(c.fecha_fin, '%d/%m/%Y') as fecha_fin_formatted,
                    CASE 
                        WHEN c.fecha_fin < CURDATE() AND c.estado NOT IN ('completada', 'cancelada') 
                        THEN 'atrasada' 
                        ELSE c.estado 
                    END as estado_actual
                FROM capacitaciones c
                JOIN recursos_humanos rh ON c.recurso_humano_id = rh.id
                JOIN usuarios u ON rh.usuario_id = u.id
                JOIN proyectos p ON rh.proyecto_id = p.id
                WHERE 1=1";
        
        $params = [];
        
        // Filtros
        if (!empty($filtros['proyecto_id'])) {
            $sql .= " AND p.id = ?";
            $params[] = $filtros['proyecto_id'];
        }
        
        if (!empty($filtros['estado'])) {
            $sql .= " AND c.estado = ?";
            $params[] = $filtros['estado'];
        }
        
        if (!empty($filtros['recurso_humano_id'])) {
            $sql .= " AND c.recurso_humano_id = ?";
            $params[] = $filtros['recurso_humano_id'];
        }
        
        $sql .= " ORDER BY c.fecha_inicio DESC, c.id DESC";
        
        try {
            $stmt = $this->db->prepare($sql);
            if ($params) {
                $stmt->execute($params);
            } else {
                $stmt->execute();
            }
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error obtenerCapacitacionesFiltradas: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtener usuarios disponibles para asignar a proyectos
     */
    public function obtenerUsuariosDisponibles($proyecto_id = null) {
        // Si viene de un proyecto específico, excluir usuarios ya asignados a ESE proyecto
        if ($proyecto_id) {
            $sql = "SELECT 
                        u.id,
                        u.nombre,
                        u.email,
                        u.rol,
                        u.departamento,
                        u.activo,
                        COUNT(rh.id) as proyectos_actuales
                    FROM usuarios u
                    LEFT JOIN recursos_humanos rh ON u.id = rh.usuario_id
                    WHERE u.activo = 1 
                    AND u.rol IN ('gerente', 'miembro_equipo')
                    AND NOT EXISTS (
                        SELECT 1 
                        FROM recursos_humanos rh2 
                        WHERE rh2.usuario_id = u.id 
                        AND rh2.proyecto_id = ?
                    )
                    GROUP BY u.id
                    ORDER BY u.nombre";
            
            try {
                $stmt = $this->db->prepare($sql);
                $stmt->execute([$proyecto_id]);
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                error_log("Error obtenerUsuariosDisponibles: " . $e->getMessage());
                return [];
            }
        } else {
            // Si NO hay proyecto específico, mostrar TODOS los usuarios disponibles
            $sql = "SELECT 
                        u.id,
                        u.nombre,
                        u.email,
                        u.rol,
                        u.departamento,
                        u.activo,
                        COUNT(rh.id) as proyectos_actuales
                    FROM usuarios u
                    LEFT JOIN recursos_humanos rh ON u.id = rh.usuario_id
                    WHERE u.activo = 1 
                    AND u.rol IN ('gerente', 'miembro_equipo')
                    GROUP BY u.id
                    ORDER BY u.nombre";
            
            try {
                $stmt = $this->db->prepare($sql);
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                error_log("Error obtenerUsuariosDisponibles: " . $e->getMessage());
                return [];
            }
        }
    }

    /**
     * Obtener proyectos disponibles para asignar recursos
     */
    public function obtenerProyectosDisponibles($usuario_id = null) {
        // Si viene de un usuario específico, excluir proyectos donde YA está asignado
        if ($usuario_id) {
            $sql = "SELECT 
                        p.id,
                        p.nombre,
                        p.descripcion,
                        p.estado,
                        p.gerente_id,
                        u.nombre as gerente_nombre,
                        COUNT(rh.id) as recursos_asignados,
                        DATEDIFF(p.fecha_fin_estimada, CURDATE()) as dias_restantes
                    FROM proyectos p
                    LEFT JOIN usuarios u ON p.gerente_id = u.id
                    LEFT JOIN recursos_humanos rh ON p.id = rh.proyecto_id
                    WHERE p.estado IN ('planificacion', 'en_ejecucion')
                    AND NOT EXISTS (
                        SELECT 1 
                        FROM recursos_humanos rh2 
                        WHERE rh2.proyecto_id = p.id 
                        AND rh2.usuario_id = ?
                    )
                    GROUP BY p.id
                    ORDER BY p.nombre";
            
            try {
                $stmt = $this->db->prepare($sql);
                $stmt->execute([$usuario_id]);
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                error_log("Error obtenerProyectosDisponibles: " . $e->getMessage());
                return [];
            }
        } else {
            // Si NO hay usuario específico, mostrar TODOS los proyectos activos
            $sql = "SELECT 
                        p.id,
                        p.nombre,
                        p.descripcion,
                        p.estado,
                        p.gerente_id,
                        u.nombre as gerente_nombre,
                        COUNT(rh.id) as recursos_asignados,
                        DATEDIFF(p.fecha_fin_estimada, CURDATE()) as dias_restantes
                    FROM proyectos p
                    LEFT JOIN usuarios u ON p.gerente_id = u.id
                    LEFT JOIN recursos_humanos rh ON p.id = rh.proyecto_id
                    WHERE p.estado IN ('planificacion', 'en_ejecucion')
                    GROUP BY p.id
                    ORDER BY p.nombre";
            
            try {
                $stmt = $this->db->prepare($sql);
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                error_log("Error obtenerProyectosDisponibles: " . $e->getMessage());
                return [];
            }
        }
    }

    /**
     * Asignar recurso humano a proyecto
     */
    public function asignarRecursoHumano($datos) {
        $sql = "INSERT INTO recursos_humanos (
                    usuario_id,
                    proyecto_id,
                    rol_proyecto,
                    habilidades,
                    capacitacion_requerida,
                    nivel_experiencia,
                    fecha_asignacion,
                    horas_asignadas,
                    horas_realizadas
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                $datos['usuario_id'],
                $datos['proyecto_id'],
                $datos['rol_proyecto'],
                $datos['habilidades'] ?? '',
                $datos['capacitacion_requerida'] ?? '',
                $datos['nivel_experiencia'] ?? 'intermedio',
                $datos['fecha_asignacion'] ?? date('Y-m-d'),
                $datos['horas_asignadas'] ?? 0,
                $datos['horas_realizadas'] ?? 0
            ]);
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            error_log("Error asignarRecursoHumano: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Actualizar recurso humano
     */
    public function actualizarRecursoHumano($id, $datos) {
        $sql = "UPDATE recursos_humanos SET
                    rol_proyecto = ?,
                    habilidades = ?,
                    capacitacion_requerida = ?,
                    nivel_experiencia = ?,
                    horas_asignadas = ?,
                    horas_realizadas = ?
                WHERE id = ?";

        try {
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                $datos['rol_proyecto'],
                $datos['habilidades'],
                $datos['capacitacion_requerida'],
                $datos['nivel_experiencia'],
                $datos['horas_asignadas'],
                $datos['horas_realizadas'],
                $id
            ]);
        } catch (PDOException $e) {
            error_log("Error actualizarRecursoHumano: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Eliminar asignación de recurso humano
     */
    public function eliminarRecursoHumano($id) {
        // Verificar si tiene capacitaciones asociadas
        $sql_check = "SELECT COUNT(*) as total FROM capacitaciones WHERE recurso_humano_id = ?";
        $stmt = $this->db->prepare($sql_check);
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result['total'] > 0) {
            return ['success' => false, 'message' => 'No se puede eliminar porque tiene capacitaciones asociadas'];
        }

        $sql = "DELETE FROM recursos_humanos WHERE id = ?";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id]);
            return ['success' => true, 'message' => 'Recurso eliminado correctamente'];
        } catch (PDOException $e) {
            error_log("Error eliminarRecursoHumano: " . $e->getMessage());
            return ['success' => false, 'message' => 'Error al eliminar el recurso'];
        }
    }

    /**
     * Crear capacitación para recurso humano
     */
    public function crearCapacitacion($datos) {
        $this->db->beginTransaction();
        
        try {
            // 1. Insertar la capacitación
            $sql = "INSERT INTO capacitaciones (
                        recurso_humano_id,
                        tipo_capacitacion,
                        descripcion,
                        duracion_horas,
                        fecha_inicio,
                        fecha_fin,
                        estado,
                        costo,
                        certificacion
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                $datos['recurso_humano_id'],
                $datos['tipo_capacitacion'],
                $datos['descripcion'],
                $datos['duracion_horas'],
                $datos['fecha_inicio'],
                $datos['fecha_fin'],
                $datos['estado'] ?? 'pendiente',
                $datos['costo'] ?? 0,
                $datos['certificacion'] ?? ''
            ]);
            
            $capacitacion_id = $this->db->lastInsertId();
            
            // 2. Buscar y limpiar necesidades de capacitación relacionadas
            $this->procesarNecesidadesCapacitacion($datos['recurso_humano_id'], $datos['descripcion']);
            
            $this->db->commit();
            return $capacitacion_id;
            
        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log("Error crearCapacitacion: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Procesar necesidades de capacitación después de crear una
     */
    private function procesarNecesidadesCapacitacion($recurso_humano_id, $descripcion_capacitacion) {
        // Obtener las necesidades de capacitación del recurso
        $sql = "SELECT capacitacion_requerida, habilidades 
                FROM recursos_humanos 
                WHERE id = ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$recurso_humano_id]);
        $recurso = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$recurso || empty($recurso['capacitacion_requerida'])) {
            return;
        }
        
        // Analizar similitud entre la capacitación creada y la necesidad
        $necesidad = strtolower(trim($recurso['capacitacion_requerida']));
        $capacitacion = strtolower(trim($descripcion_capacitacion));
        
        // Palabras clave comunes en capacitaciones
        $palabras_clave = ['capacitación', 'entrenamiento', 'curso', 'formación', 'taller', 
                        'workshop', 'training', 'certificación', 'aprendizaje', 'desarrollo'];
        
        // Verificar similitud
        $es_relacionada = false;
        
        // Método 1: Verificar palabras clave
        foreach ($palabras_clave as $palabra) {
            if (strpos($necesidad, $palabra) !== false && strpos($capacitacion, $palabra) !== false) {
                $es_relacionada = true;
                break;
            }
        }
        
        // Método 2: Verificar similitud de texto (aproximado)
        similar_text($necesidad, $capacitacion, $porcentaje_similitud);
        if ($porcentaje_similitud > 40) {
            $es_relacionada = true;
        }
        
        // Método 3: Verificar si la capacitación menciona la necesidad
        if (strpos($capacitacion, substr($necesidad, 0, 20)) !== false) {
            $es_relacionada = true;
        }
        
        // Si es relacionada, limpiar el campo de capacitación requerida
        if ($es_relacionada) {
            $sql_update = "UPDATE recursos_humanos 
                        SET capacitacion_requerida = '', 
                            habilidades = CONCAT(habilidades, ?)
                        WHERE id = ?";
            
            $nueva_habilidad = ", " . $descripcion_capacitacion;
            $stmt_update = $this->db->prepare($sql_update);
            $stmt_update->execute([$nueva_habilidad, $recurso_humano_id]);
        }
    }


    /**
     * Actualizar capacitación
     */
    public function actualizarCapacitacion($id, $datos) {
        $sql = "UPDATE capacitaciones SET
                    tipo_capacitacion = ?,
                    descripcion = ?,
                    duracion_horas = ?,
                    fecha_inicio = ?,
                    fecha_fin = ?,
                    estado = ?,
                    costo = ?,
                    certificacion = ?
                WHERE id = ?";

        try {
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                $datos['tipo_capacitacion'],
                $datos['descripcion'],
                $datos['duracion_horas'],
                $datos['fecha_inicio'],
                $datos['fecha_fin'],
                $datos['estado'],
                $datos['costo'],
                $datos['certificacion'],
                $id
            ]);
        } catch (PDOException $e) {
            error_log("Error actualizarCapacitacion: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Eliminar capacitación
     */
    public function eliminarCapacitacion($id) {
        $sql = "DELETE FROM capacitaciones WHERE id = ?";
        try {
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log("Error eliminarCapacitacion: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Actualizar estado de capacitación
     */
    public function actualizarEstadoCapacitacion($id, $estado) {
        $sql = "UPDATE capacitaciones SET estado = ? WHERE id = ?";
        try {
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$estado, $id]);
        } catch (PDOException $e) {
            error_log("Error actualizarEstadoCapacitacion: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtener estadísticas de desarrollo de equipo
     */
    public function obtenerEstadisticas($proyecto_id = null) {
        $sql = "SELECT 
                    COUNT(DISTINCT rh.id) as total_recursos,
                    COUNT(DISTINCT c.id) as total_capacitaciones,
                    SUM(c.costo) as costo_total_capacitaciones,
                    AVG(rh.horas_realizadas) as horas_promedio,
                    SUM(rh.horas_realizadas) as horas_totales,
                    nivel_experiencia,
                    COUNT(CASE WHEN nivel_experiencia = 'junior' THEN 1 END) as juniors,
                    COUNT(CASE WHEN nivel_experiencia = 'intermedio' THEN 1 END) as intermedios,
                    COUNT(CASE WHEN nivel_experiencia = 'senior' THEN 1 END) as seniors,
                    COUNT(CASE WHEN nivel_experiencia = 'experto' THEN 1 END) as expertos
                FROM recursos_humanos rh
                LEFT JOIN capacitaciones c ON rh.id = c.recurso_humano_id
                WHERE 1=1";

        $params = [];

        if ($proyecto_id) {
            $sql .= " AND rh.proyecto_id = ?";
            $params[] = $proyecto_id;
        }

        $sql .= " GROUP BY nivel_experiencia
                  ORDER BY total_recursos DESC";

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
     * Obtener recursos que necesitan capacitación (inteligente)
     */
    public function obtenerRecursosNecesitanCapacitacion() {
        $sql = "SELECT 
                    rh.*,
                    u.nombre as usuario_nombre,
                    p.nombre as proyecto_nombre,
                    LENGTH(rh.capacitacion_requerida) as longitud_capacitacion,
                    
                    -- Verificar si ya tiene capacitaciones relacionadas
                    (SELECT COUNT(*) 
                    FROM capacitaciones c 
                    WHERE c.recurso_humano_id = rh.id 
                    AND (
                        -- Buscar similitudes en la descripción
                        LOWER(c.descripcion) LIKE CONCAT('%', SUBSTRING(LOWER(rh.capacitacion_requerida), 1, 20), '%')
                        OR LOWER(c.tipo_capacitacion) LIKE CONCAT('%', SUBSTRING(LOWER(rh.capacitacion_requerida), 1, 20), '%')
                    )
                    ) as capacitaciones_relacionadas
                    
                FROM recursos_humanos rh
                JOIN usuarios u ON rh.usuario_id = u.id
                JOIN proyectos p ON rh.proyecto_id = p.id
                WHERE rh.capacitacion_requerida IS NOT NULL 
                AND rh.capacitacion_requerida != ''
                AND rh.capacitacion_requerida NOT LIKE '%resuelto%'
                AND rh.capacitacion_requerida NOT LIKE '%completado%'
                HAVING capacitaciones_relacionadas = 0
                ORDER BY rh.nivel_experiencia, u.nombre
                LIMIT 10";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error obtenerRecursosNecesitanCapacitacion: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Marcar necesidad de capacitación como resuelta
     */
    public function marcarNecesidadResuelta($recurso_humano_id, $capacitacion_id = null) {
        $sql = "UPDATE recursos_humanos 
                SET capacitacion_requerida = CONCAT('RESUELTO - ', capacitacion_requerida, ' (', DATE_FORMAT(NOW(), '%d/%m/%Y'), ')')
                WHERE id = ? 
                AND capacitacion_requerida IS NOT NULL 
                AND capacitacion_requerida != ''";
        
        try {
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$recurso_humano_id]);
        } catch (PDOException $e) {
            error_log("Error marcarNecesidadResuelta: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtener horas trabajadas por recurso
     */
    public function obtenerHorasTrabajadas($recurso_humano_id = null) {
        $sql = "SELECT 
                    rh.id,
                    u.nombre,
                    p.nombre as proyecto,
                    rh.horas_asignadas,
                    rh.horas_realizadas,
                    ROUND((rh.horas_realizadas / NULLIF(rh.horas_asignadas, 0)) * 100, 1) as porcentaje_completado
                FROM recursos_humanos rh
                JOIN usuarios u ON rh.usuario_id = u.id
                JOIN proyectos p ON rh.proyecto_id = p.id
                WHERE rh.horas_asignadas > 0";

        $params = [];

        if ($recurso_humano_id) {
            $sql .= " AND rh.id = ?";
            $params[] = $recurso_humano_id;
        }

        $sql .= " ORDER BY porcentaje_completado DESC, u.nombre";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error obtenerHorasTrabajadas: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Actualizar horas trabajadas
     */
    public function actualizarHorasTrabajadas($id, $horas_realizadas) {
        $sql = "UPDATE recursos_humanos SET horas_realizadas = ? WHERE id = ?";
        try {
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$horas_realizadas, $id]);
        } catch (PDOException $e) {
            error_log("Error actualizarHorasTrabajadas: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtener estadísticas completas de desarrollo de equipo
     */
    public function obtenerEstadisticasCompletas($proyecto_id = null) {
        $sql = "SELECT 
                    COUNT(DISTINCT rh.id) as total_recursos,
                    COUNT(DISTINCT c.id) as total_capacitaciones,
                    COALESCE(SUM(c.costo), 0) as costo_total_capacitaciones,
                    COALESCE(SUM(c.duracion_horas), 0) as total_horas_capacitacion,
                    COALESCE(AVG(c.duracion_horas), 0) as horas_promedio_capacitacion,
                    COALESCE(SUM(rh.horas_realizadas), 0) as horas_totales_trabajadas,
                    COALESCE(AVG(rh.horas_realizadas), 0) as horas_promedio_trabajadas,
                    
                    -- Distribución por nivel de experiencia
                    COUNT(CASE WHEN rh.nivel_experiencia = 'junior' THEN 1 END) as juniors,
                    COUNT(CASE WHEN rh.nivel_experiencia = 'intermedio' THEN 1 END) as intermedios,
                    COUNT(CASE WHEN rh.nivel_experiencia = 'senior' THEN 1 END) as seniors,
                    COUNT(CASE WHEN rh.nivel_experiencia = 'experto' THEN 1 END) as expertos,
                    
                    -- Estados de capacitación
                    COUNT(CASE WHEN c.estado = 'pendiente' THEN 1 END) as capacitaciones_pendientes,
                    COUNT(CASE WHEN c.estado = 'en_curso' THEN 1 END) as capacitaciones_en_curso,
                    COUNT(CASE WHEN c.estado = 'completada' THEN 1 END) as capacitaciones_completadas,
                    COUNT(CASE WHEN c.estado = 'cancelada' THEN 1 END) as capacitaciones_canceladas,
                    
                    -- Capacitaciones atrasadas
                    COUNT(CASE WHEN c.fecha_fin < CURDATE() AND c.estado NOT IN ('completada', 'cancelada') THEN 1 END) as capacitaciones_atrasadas,
                    
                    -- Porcentaje de horas completadas
                    COALESCE(SUM(rh.horas_realizadas) * 100.0 / NULLIF(SUM(rh.horas_asignadas), 0), 0) as porcentaje_horas_completadas,
                    
                    -- Porcentaje de capacitaciones completadas
                    COUNT(CASE WHEN c.estado = 'completada' THEN 1 END) * 100.0 / NULLIF(COUNT(c.id), 0) as porcentaje_capacitaciones_completadas
                    
                FROM recursos_humanos rh
                LEFT JOIN capacitaciones c ON rh.id = c.recurso_humano_id
                LEFT JOIN proyectos p ON rh.proyecto_id = p.id
                WHERE 1=1";

        $params = [];

        if ($proyecto_id) {
            $sql .= " AND rh.proyecto_id = ?";
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
            error_log("Error obtenerEstadisticasCompletas: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtener distribución de recursos por proyecto
     */
    public function obtenerDistribucionPorProyecto() {
        $sql = "SELECT 
                    p.nombre as proyecto,
                    COUNT(rh.id) as total_recursos,
                    COUNT(c.id) as total_capacitaciones,
                    COALESCE(SUM(rh.horas_realizadas), 0) as horas_trabajadas,
                    COALESCE(SUM(rh.horas_asignadas), 0) as horas_asignadas,
                    COALESCE(SUM(rh.horas_realizadas) * 100.0 / NULLIF(SUM(rh.horas_asignadas), 0), 0) as porcentaje_completado
                FROM proyectos p
                LEFT JOIN recursos_humanos rh ON p.id = rh.proyecto_id
                LEFT JOIN capacitaciones c ON rh.id = c.recurso_humano_id
                WHERE p.estado IN ('planificacion', 'en_ejecucion')
                GROUP BY p.id, p.nombre
                ORDER BY total_recursos DESC";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error obtenerDistribucionPorProyecto: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtener métricas de costo por capacitación
     */
    public function obtenerMetricasCostos() {
        $sql = "SELECT 
                    tipo_capacitacion,
                    COUNT(*) as cantidad,
                    COALESCE(AVG(costo), 0) as costo_promedio,
                    COALESCE(SUM(costo), 0) as costo_total,
                    COALESCE(AVG(duracion_horas), 0) as horas_promedio
                FROM capacitaciones
                GROUP BY tipo_capacitacion
                ORDER BY cantidad DESC";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error obtenerMetricasCostos: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Verificar si una capacitación cubre una necesidad específica
     */
    public function capacitacionCubreNecesidad($recurso_humano_id, $descripcion_capacitacion) {
        $sql = "SELECT capacitacion_requerida 
                FROM recursos_humanos 
                WHERE id = ? 
                AND capacitacion_requerida IS NOT NULL 
                AND capacitacion_requerida != ''";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$recurso_humano_id]);
        $recurso = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$recurso) {
            return false;
        }
        
        $necesidad = $this->limpiarTexto($recurso['capacitacion_requerida']);
        $capacitacion = $this->limpiarTexto($descripcion_capacitacion);
        
        // Métodos de detección múltiple
        $puntuacion = 0;
        
        // 1. Coincidencia exacta parcial
        if (strpos($capacitacion, substr($necesidad, 0, 15)) !== false) {
            $puntuacion += 30;
        }
        
        // 2. Coincidencia inversa
        if (strpos($necesidad, substr($capacitacion, 0, 15)) !== false) {
            $puntuacion += 30;
        }
        
        // 3. Palabras clave comunes
        $palabras_necesidad = $this->extraerPalabrasSignificativas($necesidad);
        $palabras_capacitacion = $this->extraerPalabrasSignificativas($capacitacion);
        $coincidencias = array_intersect($palabras_necesidad, $palabras_capacitacion);
        
        $puntuacion += count($coincidencias) * 10;
        
        // 4. Similitud de texto
        similar_text($necesidad, $capacitacion, $porcentaje);
        $puntuacion += $porcentaje * 0.5;
        
        // Si la puntuación es mayor a 50, consideramos que cubre la necesidad
        return $puntuacion >= 50;
    }

    /**
     * Limpiar texto para comparación
     */
    private function limpiarTexto($texto) {
        $texto = strtolower(trim($texto));
        $texto = preg_replace('/[^\w\sáéíóúüñ]/', ' ', $texto);
        $texto = preg_replace('/\s+/', ' ', $texto);
        return $texto;
    }

    /**
     * Extraer palabras significativas
     */
    private function extraerPalabrasSignificativas($texto) {
        $palabras_comunes = ['el', 'la', 'los', 'las', 'de', 'del', 'en', 'y', 'o', 'a', 'con', 'para', 'por', 'sobre', 'entre', 'que', 'como', 'para', 'una', 'un', 'es', 'son', 'se', 'su', 'sus'];
        
        $palabras = explode(' ', $texto);
        $palabras_significativas = array_filter($palabras, function($palabra) use ($palabras_comunes) {
            return !in_array($palabra, $palabras_comunes) && strlen($palabra) > 3;
        });
        
        return array_unique($palabras_significativas);
    }
}
?>
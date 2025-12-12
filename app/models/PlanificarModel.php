<?php

require_once __DIR__ . '/../config/database.php';

class PlanificarModel {
    
    private $db;
    
     public function __construct() {
        // IMPORTANTE: Usar el namespace completo
        // Ya que ConexionBD está en namespace Config
        $conexion = \Config\ConexionBD::obtenerInstancia();
        $this->db = $conexion->obtenerConexion();
    }
    
    // ========== MÉTODOS PARA PROYECTOS ==========
    
    public function obtenerProyectos() {
        $sql = "SELECT p.*, u.nombre as gerente_nombre 
                FROM proyectos p 
                LEFT JOIN usuarios u ON p.gerente_id = u.id 
                ORDER BY p.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    public function obtenerProyecto($id) {
        $sql = "SELECT p.*, u.nombre as gerente_nombre 
                FROM proyectos p 
                LEFT JOIN usuarios u ON p.gerente_id = u.id 
                WHERE p.id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }
    
    public function crearProyecto($datos) {
        $sql = "INSERT INTO proyectos (nombre, descripcion, fecha_inicio, fecha_fin_estimada, 
                presupuesto_estimado, estado, gerente_id) 
                VALUES (:nombre, :descripcion, :fecha_inicio, :fecha_fin_estimada, 
                :presupuesto_estimado, :estado, :gerente_id)";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($datos);
    }
    
    public function actualizarProyecto($id, $datos) {
        $datos['id'] = $id;
        $sql = "UPDATE proyectos SET 
                nombre = :nombre, 
                descripcion = :descripcion, 
                fecha_inicio = :fecha_inicio, 
                fecha_fin_estimada = :fecha_fin_estimada,
                presupuesto_estimado = :presupuesto_estimado,
                estado = :estado,
                gerente_id = :gerente_id
                WHERE id = :id";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($datos);
    }
    
    // ========== MÉTODOS PARA PLANIFICACIÓN DE RECURSOS ==========
    
    public function obtenerPlanificaciones($proyecto_id = null) {
        $sql = "SELECT pr.*, p.nombre as proyecto_nombre 
                FROM planificacion_recursos pr
                JOIN proyectos p ON pr.proyecto_id = p.id";
        
        if ($proyecto_id) {
            $sql .= " WHERE pr.proyecto_id = :proyecto_id";
        }
        
        $sql .= " ORDER BY pr.prioridad DESC, pr.created_at DESC";
        
        $stmt = $this->db->prepare($sql);
        if ($proyecto_id) {
            $stmt->bindParam(':proyecto_id', $proyecto_id);
        }
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    public function obtenerPlanificacion($id) {
        $sql = "SELECT pr.*, p.nombre as proyecto_nombre 
                FROM planificacion_recursos pr
                JOIN proyectos p ON pr.proyecto_id = p.id
                WHERE pr.id = :id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }
    
    public function crearPlanificacion($datos) {
        // Calcular costo total automáticamente
        $datos['costo_total_estimado'] = $datos['cantidad_estimada'] * $datos['costo_unitario_estimado'];
        
        $sql = "INSERT INTO planificacion_recursos 
                (proyecto_id, tipo_recurso, descripcion, cantidad_estimada, 
                costo_unitario_estimado, costo_total_estimado, prioridad, fase_proyecto) 
                VALUES 
                (:proyecto_id, :tipo_recurso, :descripcion, :cantidad_estimada, 
                :costo_unitario_estimado, :costo_total_estimado, :prioridad, :fase_proyecto)";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($datos);
    }
    
    public function actualizarPlanificacion($id, $datos) {
        // Calcular costo total automáticamente
        $datos['costo_total_estimado'] = $datos['cantidad_estimada'] * $datos['costo_unitario_estimado'];
        $datos['id'] = $id;
        
        $sql = "UPDATE planificacion_recursos SET 
                proyecto_id = :proyecto_id,
                tipo_recurso = :tipo_recurso,
                descripcion = :descripcion,
                cantidad_estimada = :cantidad_estimada,
                costo_unitario_estimado = :costo_unitario_estimado,
                costo_total_estimado = :costo_total_estimado,
                prioridad = :prioridad,
                fase_proyecto = :fase_proyecto
                WHERE id = :id";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($datos);
    }
    
    public function eliminarPlanificacion($id) {
        $sql = "DELETE FROM planificacion_recursos WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
    
    // ========== MÉTODOS PARA REPORTES ==========
    
    public function obtenerResumenPlanificacion($proyecto_id) {
        $sql = "SELECT 
                COUNT(*) as total_recursos,
                SUM(costo_total_estimado) as presupuesto_total,
                SUM(CASE WHEN prioridad = 'alta' THEN 1 ELSE 0 END) as alta_prioridad,
                SUM(CASE WHEN prioridad = 'media' THEN 1 ELSE 0 END) as media_prioridad,
                SUM(CASE WHEN prioridad = 'baja' THEN 1 ELSE 0 END) as baja_prioridad
                FROM planificacion_recursos 
                WHERE proyecto_id = :proyecto_id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':proyecto_id', $proyecto_id);
        $stmt->execute();
        return $stmt->fetch();
    }
    
    public function obtenerRecursosPorTipo($proyecto_id) {
        $sql = "SELECT 
                tipo_recurso,
                COUNT(*) as cantidad,
                SUM(costo_total_estimado) as costo_total
                FROM planificacion_recursos 
                WHERE proyecto_id = :proyecto_id
                GROUP BY tipo_recurso
                ORDER BY costo_total DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':proyecto_id', $proyecto_id);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
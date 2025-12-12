<?php
// PlanificarController.php - SIN NAMESPACE

session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: ../views/login.php');
    exit;
}

// Incluir el modelo
require_once __DIR__ . '/../models/PlanificarModel.php';

class PlanificarController {
    
    private $model;
    private $usuario;
    
    public function __construct() {
        $this->usuario = $_SESSION['usuario'];
        $this->model = new PlanificarModel();
    }
    
    public function index() {
        $accion = $_GET['accion'] ?? 'dashboard';
        
        // Procesar POST
        $this->procesarPost();
        
        // Obtener datos
        $datos = $this->obtenerDatos($accion);
        
        // Variables para la vista
        $mensaje = $_SESSION['mensaje'] ?? '';
        $tipo_mensaje = $_SESSION['tipo_mensaje'] ?? '';
        unset($_SESSION['mensaje'], $_SESSION['tipo_mensaje']);
        
        // Incluir vista
        require_once __DIR__ . '/../views/modulos/planificar/planificar.php';
    }
    
    private function procesarPost() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;
        
        switch ($_POST['accion'] ?? '') {
            case 'crear_proyecto':
                $datos = [
                    'nombre' => trim($_POST['nombre']),
                    'descripcion' => trim($_POST['descripcion']),
                    'fecha_inicio' => $_POST['fecha_inicio'],
                    'fecha_fin_estimada' => $_POST['fecha_fin_estimada'],
                    'presupuesto_estimado' => $_POST['presupuesto_estimado'],
                    'estado' => $_POST['estado'],
                    'gerente_id' => $this->usuario['id']
                ];
                
                if ($this->model->crearProyecto($datos)) {
                    $_SESSION['mensaje'] = '✅ Proyecto creado exitosamente';
                    $_SESSION['tipo_mensaje'] = 'success';
                } else {
                    $_SESSION['mensaje'] = '❌ Error al crear proyecto';
                    $_SESSION['tipo_mensaje'] = 'error';
                }
                break;
                
            case 'editar_proyecto':
                $id = $_POST['id'] ?? 0;
                $datos = [
                    'nombre' => trim($_POST['nombre']),
                    'descripcion' => trim($_POST['descripcion']),
                    'fecha_inicio' => $_POST['fecha_inicio'],
                    'fecha_fin_estimada' => $_POST['fecha_fin_estimada'],
                    'presupuesto_estimado' => $_POST['presupuesto_estimado'],
                    'estado' => $_POST['estado'],
                    'gerente_id' => $this->usuario['id']
                ];
                
                if ($this->model->actualizarProyecto($id, $datos)) {
                    $_SESSION['mensaje'] = '✅ Proyecto actualizado exitosamente';
                    $_SESSION['tipo_mensaje'] = 'success';
                } else {
                    $_SESSION['mensaje'] = '❌ Error al actualizar proyecto';
                    $_SESSION['tipo_mensaje'] = 'error';
                }
                break;
                
            case 'crear_recurso':
                $datos = [
                    'proyecto_id' => $_POST['proyecto_id'],
                    'tipo_recurso' => $_POST['tipo_recurso'],
                    'descripcion' => trim($_POST['descripcion']),
                    'cantidad_estimada' => $_POST['cantidad_estimada'],
                    'costo_unitario_estimado' => $_POST['costo_unitario_estimado'],
                    'prioridad' => $_POST['prioridad'],
                    'fase_proyecto' => $_POST['fase_proyecto']
                ];
                
                if ($this->model->crearPlanificacion($datos)) {
                    $_SESSION['mensaje'] = '✅ Recurso planificado exitosamente';
                    $_SESSION['tipo_mensaje'] = 'success';
                } else {
                    $_SESSION['mensaje'] = '❌ Error al planificar recurso';
                    $_SESSION['tipo_mensaje'] = 'error';
                }
                break;
                
            case 'editar_recurso':
                $id = $_POST['id'] ?? 0;
                $datos = [
                    'proyecto_id' => $_POST['proyecto_id'],
                    'tipo_recurso' => $_POST['tipo_recurso'],
                    'descripcion' => trim($_POST['descripcion']),
                    'cantidad_estimada' => $_POST['cantidad_estimada'],
                    'costo_unitario_estimado' => $_POST['costo_unitario_estimado'],
                    'prioridad' => $_POST['prioridad'],
                    'fase_proyecto' => $_POST['fase_proyecto']
                ];
                
                if ($this->model->actualizarPlanificacion($id, $datos)) {
                    $_SESSION['mensaje'] = '✅ Recurso actualizado exitosamente';
                    $_SESSION['tipo_mensaje'] = 'success';
                } else {
                    $_SESSION['mensaje'] = '❌ Error al actualizar recurso';
                    $_SESSION['tipo_mensaje'] = 'error';
                }
                break;
                
            case 'eliminar_recurso':
                $id = $_POST['id'] ?? 0;
                if ($this->model->eliminarPlanificacion($id)) {
                    $_SESSION['mensaje'] = '✅ Recurso eliminado exitosamente';
                    $_SESSION['tipo_mensaje'] = 'success';
                } else {
                    $_SESSION['mensaje'] = '❌ Error al eliminar recurso';
                    $_SESSION['tipo_mensaje'] = 'error';
                }
                break;
        }
    }
    
    private function obtenerDatos($accion) {
        $datos = [];
        
        switch ($accion) {
            case 'dashboard':
                $datos['proyectos'] = $this->model->obtenerProyectos();
                break;
                
            case 'proyectos':
                $datos['proyectos'] = $this->model->obtenerProyectos();
                break;
                
            case 'crear_proyecto':
                // No necesita datos
                break;
                
            case 'editar_proyecto':
                $id = $_GET['id'] ?? 0;
                $datos['proyecto'] = $this->model->obtenerProyecto($id);
                break;
                
            case 'recursos':
                $proyecto_id = $_GET['proyecto_id'] ?? null;
                $datos['planificaciones'] = $this->model->obtenerPlanificaciones($proyecto_id);
                $datos['proyectos'] = $this->model->obtenerProyectos();
                $datos['proyecto_actual'] = $proyecto_id;
                break;
                
            case 'crear_recurso':
                $datos['proyectos'] = $this->model->obtenerProyectos();
                break;
                
            case 'editar_recurso':
                $id = $_GET['id'] ?? 0;
                $datos['recurso'] = $this->model->obtenerPlanificacion($id);
                $datos['proyectos'] = $this->model->obtenerProyectos();
                break;
                
            case 'reportes':
                $proyecto_id = $_GET['proyecto_id'] ?? null;
                $datos['proyectos'] = $this->model->obtenerProyectos();
                if ($proyecto_id) {
                    $datos['resumen'] = $this->model->obtenerResumenPlanificacion($proyecto_id);
                    $datos['recursos_tipo'] = $this->model->obtenerRecursosPorTipo($proyecto_id);
                    $datos['proyecto_actual'] = $proyecto_id;
                }
                break;
        }
        
        return $datos;
    }
}

// Ejecutar si es llamado directamente
if (basename(__FILE__) === basename($_SERVER['PHP_SELF'])) {
    $controller = new PlanificarController();
    $controller->index();
}
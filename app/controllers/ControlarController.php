<?php
// ControlRecursosController.php

session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: ../login');
    exit;
}

// Incluir configuración de base de datos
require_once __DIR__ . '/../config/database.php';
// Incluir el modelo
require_once __DIR__ . '/../models/ControlarModel.php';

// Usar namespace de ConexionBD
use Config\ConexionBD;

class ControlRecursosController {
    
    private $model;
    private $proyectoModel;
    private $usuario;
    private $db;
    
    public function __construct() {
        $this->usuario = $_SESSION['usuario'];

        // Almacenar user_id en sesión
        $_SESSION['user_id'] = $this->usuario['id'];
        
        // Obtener conexión usando tu clase ConexionBD
        $conexionBD = ConexionBD::obtenerInstancia();
        $this->db = $conexionBD->obtenerConexion();
        
        // Crear instancia del modelo
        $this->model = new ControlRecursosModel($this->db);
        
        // Incluir ProyectoModel si existe
        if (file_exists(__DIR__ . '/../models/ProyectoModel.php')) {
            require_once __DIR__ . '/../models/ProyectoModel.php';
            $this->proyectoModel = new ProyectoModel($this->db);
        } else {
            // Modelo simple si no existe
            $this->proyectoModel = new class($this->db) {
                private $db;
                public function __construct($db) { $this->db = $db; }
                public function obtenerProyectos() {
                    $sql = "SELECT * FROM proyectos ORDER BY nombre";
                    $stmt = $this->db->prepare($sql);
                    $stmt->execute();
                    return $stmt->fetchAll(PDO::FETCH_ASSOC);
                }
            };
        }
    }
    
    public function index() {
        $accion = $_GET['accion'] ?? 'dashboard';
        
        error_log("DEBUG Control Controller: Acción recibida: " . $accion);
        
        // Procesar POST (crear, actualizar, eliminar, etc.)
        $this->procesarPost();
        
        // Obtener datos según la acción
        $datos_vista = $this->obtenerDatos($accion);
        
        // Variables para la vista
        $mensaje = $_SESSION['mensaje'] ?? '';
        $tipo_mensaje = $_SESSION['tipo_mensaje'] ?? '';
        unset($_SESSION['mensaje'], $_SESSION['tipo_mensaje']);
        
        // Pasar usuario a la vista
        $datos_vista['usuario'] = $this->usuario;
        
        // Extraer todas las variables del array
        extract($datos_vista);
        
        // Incluir vista
        require_once __DIR__ . '/../views/modulos/controlar/controlar.php';
    }
    
    private function procesarPost() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;
        
        $accion_post = $_POST['accion'] ?? '';
        
        switch ($accion_post) {
            case 'crear_control':
                $this->crearControl();
                break;
                
            case 'actualizar_control':
                $this->actualizarControl();
                break;
                
            case 'eliminar_control':
                $this->eliminarControl();
                break;
                
            case 'crear_reporte':
                $this->crearReporte();
                break;
                
            case 'actualizar_reporte':
                $this->actualizarReporte();
                break;
                
            case 'eliminar_reporte':
                $this->eliminarReporte();
                break;
                
            case 'generar_reporte_automatico':
                $this->generarReporteAutomatico();
                break;
                
            case 'calcular_variacion':
                $this->calcularVariacion();
                break;
        }
    }
    
    private function crearControl() {
        // Verificar permisos
        if ($this->usuario['rol'] != 'gerente' && $this->usuario['rol'] != 'administrador') {
            $_SESSION['mensaje'] = 'No tienes permisos para crear controles';
            $_SESSION['tipo_mensaje'] = 'error';
            header('Location: ?accion=controles');
            exit();
        }
        
        // Validar datos
        $errores = $this->validarDatosControl($_POST);
        
        if (!empty($errores)) {
            $_SESSION['errores'] = $errores;
            $_SESSION['datos_form'] = $_POST;
            header('Location: ?accion=crear_control');
            exit();
        }
        
        // Calcular variación automáticamente si no se proporciona
        $valor_planificado = floatval($_POST['valor_planificado']);
        $valor_actual = floatval($_POST['valor_actual']);
        $variacion = isset($_POST['variacion']) ? floatval($_POST['variacion']) : 
                     $this->model->calcularVariacion($valor_planificado, $valor_actual);
        
        // Preparar datos
        $datos = [
            'proyecto_id' => $_POST['proyecto_id'],
            'recurso_id' => !empty($_POST['recurso_id']) ? $_POST['recurso_id'] : null,
            'tipo_recurso' => $_POST['tipo_recurso'],
            'tabla_referencia' => $_POST['tabla_referencia'] ?? null,
            'metrica' => $_POST['metrica'],
            'valor_planificado' => $valor_planificado,
            'valor_actual' => $valor_actual,
            'variacion' => $variacion,
            'fecha_control' => $_POST['fecha_control'] ?? date('Y-m-d'),
            'desviacion' => $_POST['desviacion'] ?? null,
            'accion_correctiva' => $_POST['accion_correctiva'] ?? null,
            'responsable_id' => $_POST['responsable_id'] ?? $this->usuario['id']
        ];
        
        // Crear control
        $id = $this->model->crearControl($datos);
        
        if ($id) {
            $_SESSION['mensaje'] = 'Control creado exitosamente';
            $_SESSION['tipo_mensaje'] = 'success';
            
            // Redirigir según el botón presionado
            if (isset($_POST['guardar_y_continuar'])) {
                header('Location: ?accion=crear_control');
            } else {
                header('Location: ?accion=controles');
            }
        } else {
            $_SESSION['mensaje'] = 'Error al crear el control';
            $_SESSION['tipo_mensaje'] = 'error';
            $_SESSION['datos_form'] = $_POST;
            header('Location: ?accion=crear_control');
        }
        exit();
    }
    
    private function actualizarControl() {
        // Verificar permisos
        if ($this->usuario['rol'] != 'gerente' && $this->usuario['rol'] != 'administrador') {
            $_SESSION['mensaje'] = 'No tienes permisos para actualizar controles';
            $_SESSION['tipo_mensaje'] = 'error';
            header('Location: ?accion=controles');
            exit();
        }
        
        $id = $_POST['id'] ?? 0;
        
        if (!$id) {
            $_SESSION['mensaje'] = 'ID de control no válido';
            $_SESSION['tipo_mensaje'] = 'error';
            header('Location: ?accion=controles');
            exit();
        }
        
        // Validar datos
        $errores = $this->validarDatosControl($_POST);
        
        if (!empty($errores)) {
            $_SESSION['errores'] = $errores;
            header("Location: ?accion=editar_control&id={$id}");
            exit();
        }
        
        // Calcular variación automáticamente si no se proporciona
        $valor_planificado = floatval($_POST['valor_planificado']);
        $valor_actual = floatval($_POST['valor_actual']);
        $variacion = isset($_POST['variacion']) ? floatval($_POST['variacion']) : 
                     $this->model->calcularVariacion($valor_planificado, $valor_actual);
        
        // Preparar datos
        $datos = [
            'metrica' => $_POST['metrica'],
            'valor_planificado' => $valor_planificado,
            'valor_actual' => $valor_actual,
            'variacion' => $variacion,
            'fecha_control' => $_POST['fecha_control'],
            'desviacion' => $_POST['desviacion'] ?? null,
            'accion_correctiva' => $_POST['accion_correctiva'] ?? null,
            'responsable_id' => $_POST['responsable_id'] ?? $this->usuario['id']
        ];
        
        // Actualizar control
        $resultado = $this->model->actualizarControl($id, $datos);
        
        if ($resultado) {
            $_SESSION['mensaje'] = 'Control actualizado exitosamente';
            $_SESSION['tipo_mensaje'] = 'success';
            header('Location: ?accion=controles');
        } else {
            $_SESSION['mensaje'] = 'Error al actualizar el control';
            $_SESSION['tipo_mensaje'] = 'error';
            header("Location: ?accion=editar_control&id={$id}");
        }
        exit();
    }
    
    private function eliminarControl() {
        // Verificar permisos
        if ($this->usuario['rol'] != 'gerente' && $this->usuario['rol'] != 'administrador') {
            $_SESSION['mensaje'] = 'No tienes permisos para eliminar controles';
            $_SESSION['tipo_mensaje'] = 'error';
            header('Location: ?accion=controles');
            exit();
        }
        
        $id = $_POST['id'] ?? 0;
        
        if (!$id) {
            $_SESSION['mensaje'] = 'ID de control no válido';
            $_SESSION['tipo_mensaje'] = 'error';
            header('Location: ?accion=controles');
            exit();
        }
        
        $resultado = $this->model->eliminarControl($id);
        
        if ($resultado) {
            $_SESSION['mensaje'] = 'Control eliminado correctamente';
            $_SESSION['tipo_mensaje'] = 'success';
        } else {
            $_SESSION['mensaje'] = 'Error al eliminar el control';
            $_SESSION['tipo_mensaje'] = 'error';
        }
        
        header('Location: ?accion=controles');
        exit();
    }
    
    private function crearReporte() {
        // Verificar permisos
        if ($this->usuario['rol'] != 'gerente' && $this->usuario['rol'] != 'administrador') {
            $_SESSION['mensaje'] = 'No tienes permisos para crear reportes';
            $_SESSION['tipo_mensaje'] = 'error';
            header('Location: ?accion=reportes_rendimiento');
            exit();
        }
        
        // Validar datos
        $errores = $this->validarDatosReporte($_POST);
        
        if (!empty($errores)) {
            $_SESSION['errores'] = $errores;
            $_SESSION['datos_form'] = $_POST;
            header('Location: ?accion=crear_reporte');
            exit();
        }
        
        // Preparar datos
        $datos = [
            'proyecto_id' => $_POST['proyecto_id'],
            'periodo' => $_POST['periodo'],
            'fecha_inicio' => $_POST['fecha_inicio'],
            'fecha_fin' => $_POST['fecha_fin'],
            'eficiencia_recursos' => floatval($_POST['eficiencia_recursos']),
            'cumplimiento_plazos' => floatval($_POST['cumplimiento_plazos']),
            'variacion_presupuesto' => floatval($_POST['variacion_presupuesto']),
            'productividad_equipo' => floatval($_POST['productividad_equipo']),
            'observaciones' => $_POST['observaciones'],
            'generado_por' => $this->usuario['id']
        ];
        
        // Crear reporte
        $resultado = $this->model->crearReporteRendimiento($datos);
        
        if ($resultado) {
            $_SESSION['mensaje'] = 'Reporte creado exitosamente';
            $_SESSION['tipo_mensaje'] = 'success';
            
            // Redirigir según el botón presionado
            if (isset($_POST['guardar_y_continuar'])) {
                header('Location: ?accion=crear_reporte');
            } else {
                header('Location: ?accion=reportes_rendimiento');
            }
        } else {
            $_SESSION['mensaje'] = 'Error al crear el reporte';
            $_SESSION['tipo_mensaje'] = 'error';
            $_SESSION['datos_form'] = $_POST;
            header('Location: ?accion=crear_reporte');
        }
        exit();
    }
    
    private function actualizarReporte() {
        // Verificar permisos
        if ($this->usuario['rol'] != 'gerente' && $this->usuario['rol'] != 'administrador') {
            $_SESSION['mensaje'] = 'No tienes permisos para actualizar reportes';
            $_SESSION['tipo_mensaje'] = 'error';
            header('Location: ?accion=reportes_rendimiento');
            exit();
        }
        
        $id = $_POST['id'] ?? 0;
        
        if (!$id) {
            $_SESSION['mensaje'] = 'ID de reporte no válido';
            $_SESSION['tipo_mensaje'] = 'error';
            header('Location: ?accion=reportes_rendimiento');
            exit();
        }
        
        // Validar datos
        $errores = $this->validarDatosReporte($_POST);
        
        if (!empty($errores)) {
            $_SESSION['errores'] = $errores;
            header("Location: ?accion=editar_reporte&id={$id}");
            exit();
        }
        
        // Preparar datos
        $datos = [
            'periodo' => $_POST['periodo'],
            'fecha_inicio' => $_POST['fecha_inicio'],
            'fecha_fin' => $_POST['fecha_fin'],
            'eficiencia_recursos' => floatval($_POST['eficiencia_recursos']),
            'cumplimiento_plazos' => floatval($_POST['cumplimiento_plazos']),
            'variacion_presupuesto' => floatval($_POST['variacion_presupuesto']),
            'productividad_equipo' => floatval($_POST['productividad_equipo']),
            'observaciones' => $_POST['observaciones']
        ];
        
        // Actualizar reporte
        $resultado = $this->model->actualizarReporteRendimiento($id, $datos);
        
        if ($resultado) {
            $_SESSION['mensaje'] = 'Reporte actualizado exitosamente';
            $_SESSION['tipo_mensaje'] = 'success';
            header('Location: ?accion=reportes_rendimiento');
        } else {
            $_SESSION['mensaje'] = 'Error al actualizar el reporte';
            $_SESSION['tipo_mensaje'] = 'error';
            header("Location: ?accion=editar_reporte&id={$id}");
        }
        exit();
    }
    
    private function eliminarReporte() {
        // Verificar permisos
        if ($this->usuario['rol'] != 'gerente' && $this->usuario['rol'] != 'administrador') {
            $_SESSION['mensaje'] = 'No tienes permisos para eliminar reportes';
            $_SESSION['tipo_mensaje'] = 'error';
            header('Location: ?accion=reportes_rendimiento');
            exit();
        }
        
        $id = $_POST['id'] ?? 0;
        
        if (!$id) {
            $_SESSION['mensaje'] = 'ID de reporte no válido';
            $_SESSION['tipo_mensaje'] = 'error';
            header('Location: ?accion=reportes_rendimiento');
            exit();
        }
        
        $resultado = $this->model->eliminarReporteRendimiento($id);
        
        if ($resultado) {
            $_SESSION['mensaje'] = 'Reporte eliminado correctamente';
            $_SESSION['tipo_mensaje'] = 'success';
        } else {
            $_SESSION['mensaje'] = 'Error al eliminar el reporte';
            $_SESSION['tipo_mensaje'] = 'error';
        }
        
        header('Location: ?accion=reportes_rendimiento');
        exit();
    }
    
    private function generarReporteAutomatico() {
        // Verificar permisos
        if ($this->usuario['rol'] != 'gerente' && $this->usuario['rol'] != 'administrador') {
            $_SESSION['mensaje'] = 'No tienes permisos para generar reportes automáticos';
            $_SESSION['tipo_mensaje'] = 'error';
            header('Location: ?accion=reportes_rendimiento');
            exit();
        }
        
        $proyecto_id = $_POST['proyecto_id'] ?? 0;
        $periodo = $_POST['periodo'] ?? 'mensual';
        
        if (!$proyecto_id) {
            $_SESSION['mensaje'] = 'Debe seleccionar un proyecto';
            $_SESSION['tipo_mensaje'] = 'error';
            header('Location: ?accion=reportes_rendimiento');
            exit();
        }
        
        $resultado = $this->model->generarReporteAutomatico($proyecto_id, $periodo);
        
        if ($resultado) {
            $_SESSION['mensaje'] = 'Reporte automático generado exitosamente';
            $_SESSION['tipo_mensaje'] = 'success';
        } else {
            $_SESSION['mensaje'] = 'Error al generar el reporte automático';
            $_SESSION['tipo_mensaje'] = 'error';
        }
        
        header('Location: ?accion=reportes_rendimiento');
        exit();
    }
    
    private function calcularVariacion() {
        // Solo para uso en AJAX o cálculos en tiempo real
        $valor_planificado = floatval($_POST['valor_planificado'] ?? 0);
        $valor_actual = floatval($_POST['valor_actual'] ?? 0);
        
        $variacion = $this->model->calcularVariacion($valor_planificado, $valor_actual);
        
        header('Content-Type: application/json');
        echo json_encode([
            'variacion' => $variacion,
            'porcentaje' => $valor_planificado != 0 ? ($variacion / $valor_planificado * 100) : 0
        ]);
        exit();
    }
    
    private function obtenerDatos($accion) {
        $datos_vista = [];
        
        // Datos comunes a todas las vistas
        $datos_vista['proyectos'] = $this->proyectoModel->obtenerProyectos();
        $datos_vista['accion'] = $accion;
        $datos_vista['usuario_id'] = $this->usuario['id'] ?? 0;
        $datos_vista['usuario_rol'] = $this->usuario['rol'] ?? 'miembro_equipo';
        $datos_vista['usuario_nombre'] = $this->usuario['nombre'] ?? '';
        
        switch ($accion) {
            case 'dashboard':
                $proyecto_id = $_GET['proyecto_id'] ?? null;
                
                // Obtener dashboard
                $datos_vista['dashboard'] = $this->model->obtenerDashboard($proyecto_id);
                break;
                
            case 'controles':
                $filtros = [];
                
                if (isset($_GET['proyecto_id']) && !empty($_GET['proyecto_id'])) {
                    $filtros['proyecto_id'] = $_GET['proyecto_id'];
                }
                
                if (isset($_GET['tipo_recurso']) && !empty($_GET['tipo_recurso'])) {
                    $filtros['tipo_recurso'] = $_GET['tipo_recurso'];
                }
                
                if (isset($_GET['tipo_variacion']) && !empty($_GET['tipo_variacion'])) {
                    $filtros['tipo_variacion'] = $_GET['tipo_variacion'];
                }
                
                $datos_vista['controles'] = $this->model->obtenerControles($filtros);
                $datos_vista['estadisticas'] = $this->model->obtenerEstadisticasControl();
                $datos_vista['alertas'] = $this->model->obtenerAlertasDesviacion(10);
                break;
                
            case 'crear_control':
                // Verificar permisos
                if ($this->usuario['rol'] != 'gerente' && $this->usuario['rol'] != 'administrador') {
                    $_SESSION['mensaje'] = 'No tienes permisos para crear controles';
                    $_SESSION['tipo_mensaje'] = 'error';
                    header('Location: ?accion=controles');
                    exit();
                }
                
                $datos_vista['recursos'] = $this->model->obtenerRecursosParaControl();
                $datos_vista['tipos_recurso'] = ['humano', 'material', 'equipo', 'financiero', 'tecnologico'];
                $datos_vista['responsables'] = $this->model->obtenerResponsables();
                
                // Si hay errores de validación previos
                if (isset($_SESSION['errores'])) {
                    $datos_vista['errores'] = $_SESSION['errores'];
                    $datos_vista['datos_form'] = $_SESSION['datos_form'];
                    unset($_SESSION['errores'], $_SESSION['datos_form']);
                }
                break;
                
            case 'ver_control':
                $id = $_GET['id'] ?? 0;
                
                if (!$id) {
                    $_SESSION['mensaje'] = 'ID de control no válido';
                    $_SESSION['tipo_mensaje'] = 'error';
                    header('Location: ?accion=controles');
                    exit();
                }
                
                $control = $this->model->obtenerControlPorId($id);
                
                if (!$control) {
                    $_SESSION['mensaje'] = 'Control no encontrado';
                    $_SESSION['tipo_mensaje'] = 'error';
                    header('Location: ?accion=controles');
                    exit();
                }
                
                $datos_vista['control'] = $control;
                break;
                
            case 'editar_control':
                // Verificar permisos
                if ($this->usuario['rol'] != 'gerente' && $this->usuario['rol'] != 'administrador') {
                    $_SESSION['mensaje'] = 'No tienes permisos para editar controles';
                    $_SESSION['tipo_mensaje'] = 'error';
                    header('Location: ?accion=controles');
                    exit();
                }
                
                $id = $_GET['id'] ?? 0;
                
                if (!$id) {
                    $_SESSION['mensaje'] = 'ID de control no válido';
                    $_SESSION['tipo_mensaje'] = 'error';
                    header('Location: ?accion=controles');
                    exit();
                }
                
                $control = $this->model->obtenerControlPorId($id);
                
                if (!$control) {
                    $_SESSION['mensaje'] = 'Control no encontrado';
                    $_SESSION['tipo_mensaje'] = 'error';
                    header('Location: ?accion=controles');
                    exit();
                }
                
                $datos_vista['control'] = $control;
                $datos_vista['recursos'] = $this->model->obtenerRecursosParaControl($control['proyecto_id']);
                $datos_vista['tipos_recurso'] = ['humano', 'material', 'equipo', 'financiero', 'tecnologico'];
                $datos_vista['responsables'] = $this->model->obtenerResponsables();
                
                // Si hay errores de validación previos
                if (isset($_SESSION['errores'])) {
                    $datos_vista['errores'] = $_SESSION['errores'];
                    unset($_SESSION['errores']);
                }
                break;
                
            case 'reportes_rendimiento':
                $filtros = [];
                
                if (isset($_GET['proyecto_id']) && !empty($_GET['proyecto_id'])) {
                    $filtros['proyecto_id'] = $_GET['proyecto_id'];
                }
                
                if (isset($_GET['periodo']) && !empty($_GET['periodo'])) {
                    $filtros['periodo'] = $_GET['periodo'];
                }
                
                $datos_vista['reportes'] = $this->model->obtenerReportesRendimiento($filtros);
                break;
                
            case 'crear_reporte':
                // Verificar permisos
                if ($this->usuario['rol'] != 'gerente' && $this->usuario['rol'] != 'administrador') {
                    $_SESSION['mensaje'] = 'No tienes permisos para crear reportes';
                    $_SESSION['tipo_mensaje'] = 'error';
                    header('Location: ?accion=reportes_rendimiento');
                    exit();
                }
                
                $datos_vista['periodos'] = ['semanal', 'mensual', 'trimestral', 'anual'];
                
                // Si hay errores de validación previos
                if (isset($_SESSION['errores'])) {
                    $datos_vista['errores'] = $_SESSION['errores'];
                    $datos_vista['datos_form'] = $_SESSION['datos_form'];
                    unset($_SESSION['errores'], $_SESSION['datos_form']);
                }
                break;
                
            case 'ver_reporte':
                $id = $_GET['id'] ?? 0;
                
                if (!$id) {
                    $_SESSION['mensaje'] = 'ID de reporte no válido';
                    $_SESSION['tipo_mensaje'] = 'error';
                    header('Location: ?accion=reportes_rendimiento');
                    exit();
                }
                
                $reporte = $this->model->obtenerReportePorId($id);
                
                if (!$reporte) {
                    $_SESSION['mensaje'] = 'Reporte no encontrado';
                    $_SESSION['tipo_mensaje'] = 'error';
                    header('Location: ?accion=reportes_rendimiento');
                    exit();
                }
                
                $datos_vista['reporte'] = $reporte;
                break;
                
            case 'editar_reporte':
                // Verificar permisos
                if ($this->usuario['rol'] != 'gerente' && $this->usuario['rol'] != 'administrador') {
                    $_SESSION['mensaje'] = 'No tienes permisos para editar reportes';
                    $_SESSION['tipo_mensaje'] = 'error';
                    header('Location: ?accion=reportes_rendimiento');
                    exit();
                }
                
                $id = $_GET['id'] ?? 0;
                
                if (!$id) {
                    $_SESSION['mensaje'] = 'ID de reporte no válido';
                    $_SESSION['tipo_mensaje'] = 'error';
                    header('Location: ?accion=reportes_rendimiento');
                    exit();
                }
                
                $reporte = $this->model->obtenerReportePorId($id);
                
                if (!$reporte) {
                    $_SESSION['mensaje'] = 'Reporte no encontrado';
                    $_SESSION['tipo_mensaje'] = 'error';
                    header('Location: ?accion=reportes_rendimiento');
                    exit();
                }
                
                $datos_vista['reporte'] = $reporte;
                $datos_vista['periodos'] = ['semanal', 'mensual', 'trimestral', 'anual'];
                
                // Si hay errores de validación previos
                if (isset($_SESSION['errores'])) {
                    $datos_vista['errores'] = $_SESSION['errores'];
                    unset($_SESSION['errores']);
                }
                break;
                
            case 'analisis':
                $proyecto_id = $_GET['proyecto_id'] ?? null;
                
                // Obtener datos para análisis
                $datos_vista['tendencias'] = $this->model->obtenerTendenciasPorTipo($proyecto_id);
                $datos_vista['historico'] = $this->model->obtenerHistoricoControles($proyecto_id, 90);
                $datos_vista['estadisticas'] = $this->model->obtenerEstadisticasControl($proyecto_id);
                break;
                
            default:
                // Por defecto, dashboard
                $proyecto_id = $_GET['proyecto_id'] ?? null;
                $datos_vista['dashboard'] = $this->model->obtenerDashboard($proyecto_id);
        }
        
        return $datos_vista;
    }
    
    private function validarDatosControl($datos) {
        $errores = [];

        // Validar proyecto_id
        if (empty($datos['proyecto_id'])) {
            $errores['proyecto_id'] = 'Debe seleccionar un proyecto';
        }

        // Validar tipo_recurso
        if (empty($datos['tipo_recurso']) || !in_array($datos['tipo_recurso'], ['humano', 'material', 'equipo', 'financiero', 'tecnologico'])) {
            $errores['tipo_recurso'] = 'Debe seleccionar un tipo de recurso válido';
        }

        // Validar metrica
        if (empty(trim($datos['metrica']))) {
            $errores['metrica'] = 'La métrica es obligatoria';
        }

        // Validar valor_planificado
        if (!isset($datos['valor_planificado']) || !is_numeric($datos['valor_planificado'])) {
            $errores['valor_planificado'] = 'El valor planificado debe ser un número válido';
        }

        // Validar valor_actual
        if (!isset($datos['valor_actual']) || !is_numeric($datos['valor_actual'])) {
            $errores['valor_actual'] = 'El valor actual debe ser un número válido';
        }

        // Validar fecha_control
        if (!empty($datos['fecha_control']) && !strtotime($datos['fecha_control'])) {
            $errores['fecha_control'] = 'La fecha de control no es válida';
        }

        return $errores;
    }
    
    private function validarDatosReporte($datos) {
        $errores = [];

        // Validar proyecto_id
        if (empty($datos['proyecto_id'])) {
            $errores['proyecto_id'] = 'Debe seleccionar un proyecto';
        }

        // Validar periodo
        if (empty($datos['periodo']) || !in_array($datos['periodo'], ['semanal', 'mensual', 'trimestral', 'anual'])) {
            $errores['periodo'] = 'Debe seleccionar un período válido';
        }

        // Validar fechas
        if (empty($datos['fecha_inicio']) || !strtotime($datos['fecha_inicio'])) {
            $errores['fecha_inicio'] = 'La fecha de inicio no es válida';
        }

        if (empty($datos['fecha_fin']) || !strtotime($datos['fecha_fin'])) {
            $errores['fecha_fin'] = 'La fecha de fin no es válida';
        }

        if (strtotime($datos['fecha_inicio']) > strtotime($datos['fecha_fin'])) {
            $errores['fecha_fin'] = 'La fecha de fin debe ser posterior a la fecha de inicio';
        }

        // Validar métricas
        $metricas = ['eficiencia_recursos', 'cumplimiento_plazos', 'variacion_presupuesto', 'productividad_equipo'];
        foreach ($metricas as $metrica) {
            if (!isset($datos[$metrica]) || !is_numeric($datos[$metrica])) {
                $errores[$metrica] = ucfirst(str_replace('_', ' ', $metrica)) . ' debe ser un número válido';
            }
        }

        // Validar observaciones
        if (empty(trim($datos['observaciones']))) {
            $errores['observaciones'] = 'Las observaciones son obligatorias';
        } elseif (strlen(trim($datos['observaciones'])) < 10) {
            $errores['observaciones'] = 'Las observaciones deben tener al menos 10 caracteres';
        }

        return $errores;
    }
}

// Ejecutar si es llamado directamente
if (basename(__FILE__) === basename($_SERVER['PHP_SELF'])) {
    $controller = new ControlRecursosController();
    $controller->index();
}
?>
<?php
// EstimacionController.php

session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: ../login');
    exit;
}

// Incluir configuración de base de datos
require_once __DIR__ . '/../config/database.php';
// Incluir el modelo
require_once __DIR__ . '/../models/EstimacionModel.php';

// Usar namespace de ConexionBD
use Config\ConexionBD;

class EstimacionController {
    
    private $model;
    private $proyectoModel;
    private $usuario;
    private $db;
    
    public function __construct() {
        $this->usuario = $_SESSION['usuario'];

        // AGREGAR ESTA LÍNEA:
        $_SESSION['user_id'] = $this->usuario['id'];  // ← Nueva línea
        
        // Obtener conexión usando tu clase ConexionBD
        $conexionBD = ConexionBD::obtenerInstancia();
        $this->db = $conexionBD->obtenerConexion();
        
        // Crear instancia del modelo
        $this->model = new EstimacionModel($this->db);
        
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
        $accion = $_GET['accion'] ?? 'listar';
        
        // Procesar POST (guardar, actualizar, eliminar)
        $this->procesarPost();
        
        // Obtener datos según la acción
        $datos_vista = $this->obtenerDatos($accion);
        
        // Variables para la vista
        $mensaje = $_SESSION['mensaje'] ?? '';
        $tipo_mensaje = $_SESSION['tipo_mensaje'] ?? '';
        unset($_SESSION['mensaje'], $_SESSION['tipo_mensaje']);
        
        // Pasar usuario a la vista
        $datos_vista['usuario'] = $this->usuario;
        
        // AGREGAR ESTA LÍNEA - Extraer todas las variables del array
        extract($datos_vista);
        
        // Incluir vista
        require_once __DIR__ . '/../views/modulos/estimacion/estimacion.php';
    }
    
    private function procesarPost() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;
        
        $accion_post = $_POST['accion'] ?? '';
        
        switch ($accion_post) {
            case 'guardar':
                $this->guardarEstimacion();
                break;
                
            case 'actualizar':
                $this->actualizarEstimacion();
                break;
                
            case 'eliminar':
                $this->eliminarEstimacion();
                break;
        }
    }
    
    private function guardarEstimacion() {
        // Verificar permisos
        if ($this->usuario['rol'] != 'gerente' && $this->usuario['rol'] != 'administrador') {
            $_SESSION['mensaje'] = 'No tienes permisos para crear estimaciones';
            $_SESSION['tipo_mensaje'] = 'error';
            header('Location: ?accion=listar');
            exit();
        }
        
        // Validar datos
        $errores = $this->validarDatosEstimacion($_POST);
        
        if (!empty($errores)) {
            $_SESSION['errores'] = $errores;
            $_SESSION['datos_form'] = $_POST;
            header('Location: ?accion=crear');
            exit();
        }
        
        // Preparar datos
        $datos = [
            'planificacion_id' => $_POST['planificacion_id'],
            'estimador_id' => $this->usuario['id'],
            'cantidad_real' => $_POST['cantidad_real'],
            'costo_real_unitario' => $_POST['costo_real_unitario'],
            'metodo_estimacion' => $_POST['metodo_estimacion'],
            'nivel_confianza' => $_POST['nivel_confianza'],
            'observaciones' => $_POST['observaciones'] ?? '',
            'fecha_estimacion' => $_POST['fecha_estimacion'] ?? date('Y-m-d')
        ];
        
        // Crear estimación
        $id = $this->model->crearEstimacion($datos);
        
        if ($id) {
            $_SESSION['mensaje'] = 'Estimación creada exitosamente';
            $_SESSION['tipo_mensaje'] = 'success';
            
            // Redirigir según el botón presionado
            if (isset($_POST['guardar_y_continuar'])) {
                header('Location: ?accion=crear');
            } else {
                header('Location: ?accion=listar');
            }
        } else {
            $_SESSION['mensaje'] = 'Error al crear la estimación';
            $_SESSION['tipo_mensaje'] = 'error';
            $_SESSION['datos_form'] = $_POST;
            header('Location: ?accion=crear');
        }
        exit();
    }
    
    private function actualizarEstimacion() {
        // Verificar permisos
        if ($this->usuario['rol'] != 'gerente' && $this->usuario['rol'] != 'administrador') {
            $_SESSION['mensaje'] = 'No tienes permisos para editar estimaciones';
            $_SESSION['tipo_mensaje'] = 'error';
            header('Location: ?accion=listar');
            exit();
        }
        
        $id = $_POST['id'] ?? 0;
        
        if (!$id) {
            $_SESSION['mensaje'] = 'ID de estimación no válido';
            $_SESSION['tipo_mensaje'] = 'error';
            header('Location: ?accion=listar');
            exit();
        }
        
        // Validar datos
        $errores = $this->validarDatosEstimacion($_POST);
        
        if (!empty($errores)) {
            $_SESSION['errores'] = $errores;
            header("Location: ?accion=editar&id={$id}");
            exit();
        }
        
        // Preparar datos
        $datos = [
            'cantidad_real' => $_POST['cantidad_real'],
            'costo_real_unitario' => $_POST['costo_real_unitario'],
            'metodo_estimacion' => $_POST['metodo_estimacion'],
            'nivel_confianza' => $_POST['nivel_confianza'],
            'observaciones' => $_POST['observaciones'] ?? '',
            'fecha_estimacion' => $_POST['fecha_estimacion']
        ];
        
        // Actualizar estimación
        $resultado = $this->model->actualizarEstimacion($id, $datos);
        
        if ($resultado) {
            $_SESSION['mensaje'] = 'Estimación actualizada exitosamente';
            $_SESSION['tipo_mensaje'] = 'success';
            header('Location: ?accion=listar');
        } else {
            $_SESSION['mensaje'] = 'Error al actualizar la estimación';
            $_SESSION['tipo_mensaje'] = 'error';
            header("Location: ?accion=editar&id={$id}");
        }
        exit();
    }
    
    private function eliminarEstimacion() {
        // Verificar permisos
        if ($this->usuario['rol'] != 'gerente' && $this->usuario['rol'] != 'administrador') {
            $_SESSION['mensaje'] = 'No tienes permisos para eliminar estimaciones';
            $_SESSION['tipo_mensaje'] = 'error';
            header('Location: ?accion=listar');
            exit();
        }
        
        $id = $_POST['id'] ?? 0;
        
        if (!$id) {
            $_SESSION['mensaje'] = 'ID de estimación no válido';
            $_SESSION['tipo_mensaje'] = 'error';
            header('Location: ?accion=listar');
            exit();
        }
        
        $resultado = $this->model->eliminarEstimacion($id);
        
        if ($resultado['success']) {
            $_SESSION['mensaje'] = $resultado['message'];
            $_SESSION['tipo_mensaje'] = 'success';
        } else {
            $_SESSION['mensaje'] = $resultado['message'];
            $_SESSION['tipo_mensaje'] = 'error';
        }
        
        header('Location: ?accion=listar');
        exit();
    }
    
    private function obtenerDatos($accion) {
        $datos_vista = [];
        
        // Datos comunes a todas las vistas
        $datos_vista['proyectos'] = $this->proyectoModel->obtenerProyectos();
        $datos_vista['accion'] = $accion;

        // AGREGAR estas líneas para debug:
        $datos_vista['usuario_id'] = $this->usuario['id'] ?? 0;
        $datos_vista['usuario_rol'] = $this->usuario['rol'] ?? 'miembro_equipo';
            
        switch ($accion) {
            case 'listar':
                $filtros = [];
                
                if (isset($_GET['proyecto_id']) && !empty($_GET['proyecto_id'])) {
                    $filtros['proyecto_id'] = $_GET['proyecto_id'];
                }
                
                if (isset($_GET['tipo_recurso']) && !empty($_GET['tipo_recurso'])) {
                    $filtros['tipo_recurso'] = $_GET['tipo_recurso'];
                }
                
                if (isset($_GET['metodo_estimacion']) && !empty($_GET['metodo_estimacion'])) {
                    $filtros['metodo_estimacion'] = $_GET['metodo_estimacion'];
                }
                
                $datos_vista['estimaciones'] = $this->model->obtenerEstimaciones($filtros);
                break;
                
            case 'crear':
                // Verificar permisos
                if ($this->usuario['rol'] != 'gerente' && $this->usuario['rol'] != 'administrador') {
                    $_SESSION['mensaje'] = 'No tienes permisos para crear estimaciones';
                    $_SESSION['tipo_mensaje'] = 'error';
                    header('Location: ?accion=listar');
                    exit();
                }
                
                // DEBUG: Verificar qué datos estamos obteniendo
                error_log("DEBUG: Obteniendo recursos planificados...");
                $recursosPlanificados = $this->model->obtenerRecursosPlanificados();
                error_log("DEBUG: Recursos obtenidos: " . count($recursosPlanificados));
                
                $datos_vista['recursosPlanificados'] = $recursosPlanificados;
                $datos_vista['metodosEstimacion'] = [
                    'Juicio de expertos',
                    'Estimación por analogía',
                    'Estimación paramétrica',
                    'Estimación bottom-up',
                    'Estimación three-point',
                    'Análisis de reserva',
                    'Estimación de costos de calidad'
                ];
                
                $datos_vista['nivelesConfianza'] = ['alto', 'medio', 'baja']; // CORREGIDO: 'baja' no 'bajo'
                
                // Si hay errores de validación previos
                if (isset($_SESSION['errores'])) {
                    $datos_vista['errores'] = $_SESSION['errores'];
                    $datos_vista['datos_form'] = $_SESSION['datos_form'];
                    unset($_SESSION['errores'], $_SESSION['datos_form']);
                }
                break;
                
            case 'ver':
                $id = $_GET['id'] ?? 0;
                
                if (!$id) {
                    $_SESSION['mensaje'] = 'ID de estimación no válido';
                    $_SESSION['tipo_mensaje'] = 'error';
                    header('Location: ?accion=listar');
                    exit();
                }
                
                $estimacion = $this->model->obtenerEstimacionPorId($id);
                
                if (!$estimacion) {
                    $_SESSION['mensaje'] = 'Estimación no encontrada';
                    $_SESSION['tipo_mensaje'] = 'error';
                    header('Location: ?accion=listar');
                    exit();
                }
                
                $datos_vista['estimacion'] = $estimacion;
                break;
                
            case 'editar':
                // Verificar permisos
                if ($this->usuario['rol'] != 'gerente' && $this->usuario['rol'] != 'administrador') {
                    $_SESSION['mensaje'] = 'No tienes permisos para editar estimaciones';
                    $_SESSION['tipo_mensaje'] = 'error';
                    header('Location: ?accion=listar');
                    exit();
                }
                
                $id = $_GET['id'] ?? 0;
                
                if (!$id) {
                    $_SESSION['mensaje'] = 'ID de estimación no válido';
                    $_SESSION['tipo_mensaje'] = 'error';
                    header('Location: ?accion=listar');
                    exit();
                }
                
                $estimacion = $this->model->obtenerEstimacionPorId($id);
                
                if (!$estimacion) {
                    $_SESSION['mensaje'] = 'Estimación no encontrada';
                    $_SESSION['tipo_mensaje'] = 'error';
                    header('Location: ?accion=listar');
                    exit();
                }
                
                // Verificar que el usuario sea el creador o administrador
                if ($this->usuario['id'] != $estimacion['estimador_id'] && $this->usuario['rol'] != 'administrador') {
                    $_SESSION['mensaje'] = 'Solo puedes editar tus propias estimaciones';
                    $_SESSION['tipo_mensaje'] = 'error';
                    header('Location: ?accion=listar');
                    exit();
                }
                
                $datos_vista['estimacion'] = $estimacion;
                $datos_vista['metodosEstimacion'] = [
                    'Juicio de expertos',
                    'Estimación por analogía',
                    'Estimación paramétrica',
                    'Estimación bottom-up',
                    'Estimación three-point',
                    'Análisis de reserva',
                    'Estimación de costos de calidad'
                ];
                
                $datos_vista['nivelesConfianza'] = ['alto', 'medio', 'baja'];
                
                // Si hay errores de validación previos
                if (isset($_SESSION['errores'])) {
                    $datos_vista['errores'] = $_SESSION['errores'];
                    unset($_SESSION['errores']);
                }
                break;
                
            case 'reportes':
                $proyecto_id = $_GET['proyecto_id'] ?? null;
                
                $datos_vista['estadisticas'] = $this->model->obtenerEstadisticas($proyecto_id);
                $datos_vista['comparacion'] = $this->model->obtenerComparacionPlanificacionEstimacion($proyecto_id);
                break;
                
            default:
                // Por defecto, listar
                header('Location: ?accion=listar');
                exit();
        }
        
        return $datos_vista;
    }
    
    private function validarDatosEstimacion($datos) {
        $errores = [];

        // Validar planificación_id
        if (empty($datos['planificacion_id'])) {
            $errores['planificacion_id'] = 'Debe seleccionar un recurso planificado';
        }

        // Validar cantidad real
        if (!isset($datos['cantidad_real']) || !is_numeric($datos['cantidad_real']) || $datos['cantidad_real'] <= 0) {
            $errores['cantidad_real'] = 'La cantidad real debe ser un número mayor a 0';
        }

        // Validar costo unitario
        if (!isset($datos['costo_real_unitario']) || !is_numeric($datos['costo_real_unitario']) || $datos['costo_real_unitario'] < 0) {
            $errores['costo_real_unitario'] = 'El costo unitario debe ser un número válido';
        }

        // Validar método de estimación
        if (empty($datos['metodo_estimacion'])) {
            $errores['metodo_estimacion'] = 'Debe seleccionar un método de estimación';
        }

        // Validar nivel de confianza
        if (empty($datos['nivel_confianza']) || !in_array($datos['nivel_confianza'], ['alto', 'medio', 'baja'])) {
            $errores['nivel_confianza'] = 'Debe seleccionar un nivel de confianza válido';
        }

        return $errores;
    }
}

// Ejecutar si es llamado directamente
if (basename(__FILE__) === basename($_SERVER['PHP_SELF'])) {
    $controller = new EstimacionController();
    $controller->index();
}
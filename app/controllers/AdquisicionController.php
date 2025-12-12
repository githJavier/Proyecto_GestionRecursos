<?php
// AdquisicionController.php

session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: ../login');
    exit;
}

// Incluir configuración de base de datos
require_once __DIR__ . '/../config/database.php';
// Incluir el modelo
require_once __DIR__ . '/../models/AdquisicionModel.php';

// Usar namespace de ConexionBD
use Config\ConexionBD;

class AdquisicionController {
    
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
        $this->model = new AdquisicionModel($this->db);
        
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
        
        // Procesar POST (guardar, actualizar, eliminar, cambiar estado)
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
        require_once __DIR__ . '/../views/modulos/adquirir/adquisicion.php';
    }
    
    private function procesarPost() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;
        
        $accion_post = $_POST['accion'] ?? '';
        
        switch ($accion_post) {
            case 'guardar':
                $this->guardarAdquisicion();
                break;
                
            case 'actualizar':
                $this->actualizarAdquisicion();
                break;
                
            case 'eliminar':
                $this->eliminarAdquisicion();
                break;
                
            case 'cambiar_estado':
                $this->cambiarEstadoAdquisicion();
                break;
        }
    }
    
    private function guardarAdquisicion() {
        // Verificar permisos
        if ($this->usuario['rol'] != 'gerente' && $this->usuario['rol'] != 'administrador') {
            $_SESSION['mensaje'] = 'No tienes permisos para crear adquisiciones';
            $_SESSION['tipo_mensaje'] = 'error';
            header('Location: ?accion=listar');
            exit();
        }
        
        // Validar datos
        $errores = $this->validarDatosAdquisicion($_POST);
        
        if (!empty($errores)) {
            $_SESSION['errores'] = $errores;
            $_SESSION['datos_form'] = $_POST;
            header('Location: ?accion=crear');
            exit();
        }
        
        // Preparar datos
        $datos = [
            'estimacion_id' => $_POST['estimacion_id'],
            'proveedor' => $_POST['proveedor'],
            'metodo_adquisicion' => $_POST['metodo_adquisicion'],
            'fecha_orden' => $_POST['fecha_orden'],
            'fecha_entrega_estimada' => $_POST['fecha_entrega_estimada'],
            'fecha_entrega_real' => !empty($_POST['fecha_entrega_real']) ? $_POST['fecha_entrega_real'] : null,
            'costo_adquisicion' => $_POST['costo_adquisicion'],
            'estado' => $_POST['estado'],
            'contrato_ref' => $_POST['contrato_ref'] ?? ''
        ];
        
        // Crear adquisición
        $id = $this->model->crearAdquisicion($datos);
        
        if ($id) {
            $_SESSION['mensaje'] = 'Adquisición creada exitosamente';
            $_SESSION['tipo_mensaje'] = 'success';
            
            // Redirigir según el botón presionado
            if (isset($_POST['guardar_y_continuar'])) {
                header('Location: ?accion=crear');
            } else {
                header('Location: ?accion=listar');
            }
        } else {
            $_SESSION['mensaje'] = 'Error al crear la adquisición';
            $_SESSION['tipo_mensaje'] = 'error';
            $_SESSION['datos_form'] = $_POST;
            header('Location: ?accion=crear');
        }
        exit();
    }
    
    private function actualizarAdquisicion() {
        // Verificar permisos
        if ($this->usuario['rol'] != 'gerente' && $this->usuario['rol'] != 'administrador') {
            $_SESSION['mensaje'] = 'No tienes permisos para editar adquisiciones';
            $_SESSION['tipo_mensaje'] = 'error';
            header('Location: ?accion=listar');
            exit();
        }
        
        $id = $_POST['id'] ?? 0;
        
        if (!$id) {
            $_SESSION['mensaje'] = 'ID de adquisición no válido';
            $_SESSION['tipo_mensaje'] = 'error';
            header('Location: ?accion=listar');
            exit();
        }
        
        // Validar datos
        $errores = $this->validarDatosAdquisicion($_POST);
        
        if (!empty($errores)) {
            $_SESSION['errores'] = $errores;
            header("Location: ?accion=editar&id={$id}");
            exit();
        }
        
        // Preparar datos
        $datos = [
            'proveedor' => $_POST['proveedor'],
            'metodo_adquisicion' => $_POST['metodo_adquisicion'],
            'fecha_orden' => $_POST['fecha_orden'],
            'fecha_entrega_estimada' => $_POST['fecha_entrega_estimada'],
            'fecha_entrega_real' => !empty($_POST['fecha_entrega_real']) ? $_POST['fecha_entrega_real'] : null,
            'costo_adquisicion' => $_POST['costo_adquisicion'],
            'estado' => $_POST['estado'],
            'contrato_ref' => $_POST['contrato_ref'] ?? ''
        ];
        
        // Actualizar adquisición
        $resultado = $this->model->actualizarAdquisicion($id, $datos);
        
        if ($resultado) {
            $_SESSION['mensaje'] = 'Adquisición actualizada exitosamente';
            $_SESSION['tipo_mensaje'] = 'success';
            header('Location: ?accion=listar');
        } else {
            $_SESSION['mensaje'] = 'Error al actualizar la adquisición';
            $_SESSION['tipo_mensaje'] = 'error';
            header("Location: ?accion=editar&id={$id}");
        }
        exit();
    }
    
    private function eliminarAdquisicion() {
        // Verificar permisos
        if ($this->usuario['rol'] != 'gerente' && $this->usuario['rol'] != 'administrador') {
            $_SESSION['mensaje'] = 'No tienes permisos para eliminar adquisiciones';
            $_SESSION['tipo_mensaje'] = 'error';
            header('Location: ?accion=listar');
            exit();
        }
        
        $id = $_POST['id'] ?? 0;
        
        if (!$id) {
            $_SESSION['mensaje'] = 'ID de adquisición no válido';
            $_SESSION['tipo_mensaje'] = 'error';
            header('Location: ?accion=listar');
            exit();
        }
        
        $resultado = $this->model->eliminarAdquisicion($id);
        
        if ($resultado) {
            $_SESSION['mensaje'] = 'Adquisición eliminada correctamente';
            $_SESSION['tipo_mensaje'] = 'success';
        } else {
            $_SESSION['mensaje'] = 'Error al eliminar la adquisición';
            $_SESSION['tipo_mensaje'] = 'error';
        }
        
        header('Location: ?accion=listar');
        exit();
    }
    
    private function cambiarEstadoAdquisicion() {
        // Verificar permisos
        if ($this->usuario['rol'] != 'gerente' && $this->usuario['rol'] != 'administrador') {
            $_SESSION['mensaje'] = 'No tienes permisos para cambiar estados';
            $_SESSION['tipo_mensaje'] = 'error';
            header('Location: ?accion=listar');
            exit();
        }
        
        $id = $_POST['id'] ?? 0;
        $estado = $_POST['estado'] ?? '';
        $fecha_entrega_real = $_POST['fecha_entrega_real'] ?? null;
        
        if (!$id || !$estado) {
            $_SESSION['mensaje'] = 'Datos incompletos';
            $_SESSION['tipo_mensaje'] = 'error';
            header('Location: ?accion=listar');
            exit();
        }
        
        $resultado = $this->model->actualizarEstado($id, $estado, $fecha_entrega_real);
        
        if ($resultado) {
            $_SESSION['mensaje'] = 'Estado actualizado correctamente';
            $_SESSION['tipo_mensaje'] = 'success';
        } else {
            $_SESSION['mensaje'] = 'Error al actualizar el estado';
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
                
                if (isset($_GET['estado']) && !empty($_GET['estado'])) {
                    $filtros['estado'] = $_GET['estado'];
                }
                
                if (isset($_GET['proveedor']) && !empty($_GET['proveedor'])) {
                    $filtros['proveedor'] = $_GET['proveedor'];
                }
                
                $datos_vista['adquisiciones'] = $this->model->obtenerAdquisiciones($filtros);
                $datos_vista['adquisiciones_proximas'] = $this->model->obtenerAdquisicionesProximasAVencer();
                break;
                
            case 'crear':
                // Verificar permisos
                if ($this->usuario['rol'] != 'gerente' && $this->usuario['rol'] != 'administrador') {
                    $_SESSION['mensaje'] = 'No tienes permisos para crear adquisiciones';
                    $_SESSION['tipo_mensaje'] = 'error';
                    header('Location: ?accion=listar');
                    exit();
                }
                
                $datos_vista['estimaciones'] = $this->model->obtenerEstimacionesParaAdquisicion();
                $datos_vista['metodosAdquisicion'] = [
                    'RFP (Request for Proposal)',
                    'Licitación',
                    'Contrato directo',
                    'Cotización directa',
                    'Subasta inversa',
                    'Compra consolidada',
                    'Alianza estratégica'
                ];
                
                $datos_vista['estados'] = ['pendiente', 'ordenado', 'entregado', 'cancelado'];
                
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
                    $_SESSION['mensaje'] = 'ID de adquisición no válido';
                    $_SESSION['tipo_mensaje'] = 'error';
                    header('Location: ?accion=listar');
                    exit();
                }
                
                $adquisicion = $this->model->obtenerAdquisicionPorId($id);
                
                if (!$adquisicion) {
                    $_SESSION['mensaje'] = 'Adquisición no encontrada';
                    $_SESSION['tipo_mensaje'] = 'error';
                    header('Location: ?accion=listar');
                    exit();
                }
                
                $datos_vista['adquisicion'] = $adquisicion;
                break;
                
            case 'editar':
                // Verificar permisos
                if ($this->usuario['rol'] != 'gerente' && $this->usuario['rol'] != 'administrador') {
                    $_SESSION['mensaje'] = 'No tienes permisos para editar adquisiciones';
                    $_SESSION['tipo_mensaje'] = 'error';
                    header('Location: ?accion=listar');
                    exit();
                }
                
                $id = $_GET['id'] ?? 0;
                
                if (!$id) {
                    $_SESSION['mensaje'] = 'ID de adquisición no válido';
                    $_SESSION['tipo_mensaje'] = 'error';
                    header('Location: ?accion=listar');
                    exit();
                }
                
                $adquisicion = $this->model->obtenerAdquisicionPorId($id);
                
                if (!$adquisicion) {
                    $_SESSION['mensaje'] = 'Adquisición no encontrada';
                    $_SESSION['tipo_mensaje'] = 'error';
                    header('Location: ?accion=listar');
                    exit();
                }
                
                $datos_vista['adquisicion'] = $adquisicion;
                $datos_vista['metodosAdquisicion'] = [
                    'RFP (Request for Proposal)',
                    'Licitación',
                    'Contrato directo',
                    'Cotización directa',
                    'Subasta inversa',
                    'Compra consolidada',
                    'Alianza estratégica'
                ];
                
                $datos_vista['estados'] = ['pendiente', 'ordenado', 'entregado', 'cancelado'];
                
                // Si hay errores de validación previos
                if (isset($_SESSION['errores'])) {
                    $datos_vista['errores'] = $_SESSION['errores'];
                    unset($_SESSION['errores']);
                }
                break;
                
            case 'reportes':
                $proyecto_id = $_GET['proyecto_id'] ?? null;
                
                $datos_vista['estadisticas'] = $this->model->obtenerEstadisticas($proyecto_id);
                $datos_vista['comparacion'] = $this->model->obtenerComparacionEstimacionAdquisicion($proyecto_id);
                break;
                
            default:
                // Por defecto, listar
                header('Location: ?accion=listar');
                exit();
        }
        
        return $datos_vista;
    }
    
    private function validarDatosAdquisicion($datos) {
        $errores = [];

        // Validar estimacion_id
        if (empty($datos['estimacion_id'])) {
            $errores['estimacion_id'] = 'Debe seleccionar una estimación';
        }

        // Validar proveedor
        if (empty(trim($datos['proveedor']))) {
            $errores['proveedor'] = 'El proveedor es obligatorio';
        }

        // Validar método de adquisición
        if (empty($datos['metodo_adquisicion'])) {
            $errores['metodo_adquisicion'] = 'Debe seleccionar un método de adquisición';
        }

        // Validar fecha de orden
        if (empty($datos['fecha_orden'])) {
            $errores['fecha_orden'] = 'La fecha de orden es obligatoria';
        }

        // Validar fecha de entrega estimada
        if (empty($datos['fecha_entrega_estimada'])) {
            $errores['fecha_entrega_estimada'] = 'La fecha de entrega estimada es obligatoria';
        }

        // Validar costo
        if (!isset($datos['costo_adquisicion']) || !is_numeric($datos['costo_adquisicion']) || $datos['costo_adquisicion'] < 0) {
            $errores['costo_adquisicion'] = 'El costo debe ser un número válido mayor o igual a 0';
        }

        // Validar estado
        if (empty($datos['estado']) || !in_array($datos['estado'], ['pendiente', 'ordenado', 'entregado', 'cancelado'])) {
            $errores['estado'] = 'Debe seleccionar un estado válido';
        }

        // Validar fechas lógicas
        if (!empty($datos['fecha_orden']) && !empty($datos['fecha_entrega_estimada'])) {
            if (strtotime($datos['fecha_entrega_estimada']) < strtotime($datos['fecha_orden'])) {
                $errores['fecha_entrega_estimada'] = 'La fecha de entrega estimada no puede ser anterior a la fecha de orden';
            }
        }

        if (!empty($datos['fecha_entrega_real']) && !empty($datos['fecha_orden'])) {
            if (strtotime($datos['fecha_entrega_real']) < strtotime($datos['fecha_orden'])) {
                $errores['fecha_entrega_real'] = 'La fecha de entrega real no puede ser anterior a la fecha de orden';
            }
        }

        return $errores;
    }
}

// Ejecutar si es llamado directamente
if (basename(__FILE__) === basename($_SERVER['PHP_SELF'])) {
    $controller = new AdquisicionController();
    $controller->index();
}
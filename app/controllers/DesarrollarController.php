<?php
// DesarrollarEquipoController.php

session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: ../login');
    exit;
}

// Incluir configuración de base de datos
require_once __DIR__ . '/../config/database.php';
// Incluir el modelo
require_once __DIR__ . '/../models/DesarrollarModel.php';

// Usar namespace de ConexionBD
use Config\ConexionBD;

class DesarrollarEquipoController {
    
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
        $this->model = new DesarrollarEquipoModel($this->db);
        
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
        
        // Procesar POST (asignar, actualizar, eliminar, etc.)
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
        require_once __DIR__ . '/../views/modulos/desarrollar/desarrollar.php';
    }
    
    private function procesarPost() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;
        
        $accion_post = $_POST['accion'] ?? '';
        
        switch ($accion_post) {
            case 'asignar_recurso':
                $this->asignarRecursoHumano();
                break;
                
            case 'actualizar_recurso':
                $this->actualizarRecursoHumano();
                break;
                
            case 'eliminar_recurso':
                $this->eliminarRecursoHumano();
                break;
                
            case 'crear_capacitacion':
                $this->crearCapacitacion();
                break;
                
            case 'actualizar_capacitacion':
                $this->actualizarCapacitacion();
                break;
                
            case 'eliminar_capacitacion':
                $this->eliminarCapacitacion();
                break;
                
            case 'actualizar_horas':
                $this->actualizarHorasTrabajadas();
                break;
                
            case 'cambiar_estado_capacitacion':
                $this->cambiarEstadoCapacitacion();
                break;

            case 'editar_capacitacion':
                $this->editarCapacitacion();
                break;

            case 'marcar_resuelto':
                $this->marcarNecesidadResueltaManual();
                break;
        }
    }
    
    private function asignarRecursoHumano() {
        // Verificar permisos
        if ($this->usuario['rol'] != 'gerente' && $this->usuario['rol'] != 'administrador') {
            $_SESSION['mensaje'] = 'No tienes permisos para asignar recursos';
            $_SESSION['tipo_mensaje'] = 'error';
            header('Location: ?accion=listar');
            exit();
        }
        
        // Validar datos
        $errores = $this->validarDatosRecursoHumano($_POST);
        
        if (!empty($errores)) {
            $_SESSION['errores'] = $errores;
            $_SESSION['datos_form'] = $_POST;
            header('Location: ?accion=asignar');
            exit();
        }
        
        // Preparar datos
        $datos = [
            'usuario_id' => $_POST['usuario_id'],
            'proyecto_id' => $_POST['proyecto_id'],
            'rol_proyecto' => $_POST['rol_proyecto'],
            'habilidades' => $_POST['habilidades'] ?? '',
            'capacitacion_requerida' => $_POST['capacitacion_requerida'] ?? '',
            'nivel_experiencia' => $_POST['nivel_experiencia'],
            'fecha_asignacion' => $_POST['fecha_asignacion'] ?? date('Y-m-d'),
            'horas_asignadas' => $_POST['horas_asignadas'] ?? 0,
            'horas_realizadas' => $_POST['horas_realizadas'] ?? 0
        ];
        
        // Asignar recurso
        $id = $this->model->asignarRecursoHumano($datos);
        
        if ($id) {
            $_SESSION['mensaje'] = 'Recurso asignado exitosamente';
            $_SESSION['tipo_mensaje'] = 'success';
            
            // Redirigir según el botón presionado
            if (isset($_POST['guardar_y_continuar'])) {
                header('Location: ?accion=asignar');
            } else {
                header('Location: ?accion=listar');
            }
        } else {
            $_SESSION['mensaje'] = 'Error al asignar el recurso';
            $_SESSION['tipo_mensaje'] = 'error';
            $_SESSION['datos_form'] = $_POST;
            header('Location: ?accion=asignar');
        }
        exit();
    }
    
    private function actualizarRecursoHumano() {
        // Verificar permisos
        if ($this->usuario['rol'] != 'gerente' && $this->usuario['rol'] != 'administrador') {
            $_SESSION['mensaje'] = 'No tienes permisos para actualizar recursos';
            $_SESSION['tipo_mensaje'] = 'error';
            header('Location: ?accion=listar');
            exit();
        }
        
        $id = $_POST['id'] ?? 0;
        
        if (!$id) {
            $_SESSION['mensaje'] = 'ID de recurso no válido';
            $_SESSION['tipo_mensaje'] = 'error';
            header('Location: ?accion=listar');
            exit();
        }
        
        // Validar datos
        $errores = $this->validarDatosRecursoHumano($_POST);
        
        if (!empty($errores)) {
            $_SESSION['errores'] = $errores;
            header("Location: ?accion=editar_recurso&id={$id}");
            exit();
        }
        
        // Preparar datos
        $datos = [
            'rol_proyecto' => $_POST['rol_proyecto'],
            'habilidades' => $_POST['habilidades'] ?? '',
            'capacitacion_requerida' => $_POST['capacitacion_requerida'] ?? '',
            'nivel_experiencia' => $_POST['nivel_experiencia'],
            'horas_asignadas' => $_POST['horas_asignadas'] ?? 0,
            'horas_realizadas' => $_POST['horas_realizadas'] ?? 0
        ];
        
        // Actualizar recurso
        $resultado = $this->model->actualizarRecursoHumano($id, $datos);
        
        if ($resultado) {
            $_SESSION['mensaje'] = 'Recurso actualizado exitosamente';
            $_SESSION['tipo_mensaje'] = 'success';
            header('Location: ?accion=listar');
        } else {
            $_SESSION['mensaje'] = 'Error al actualizar el recurso';
            $_SESSION['tipo_mensaje'] = 'error';
            header("Location: ?accion=editar_recurso&id={$id}");
        }
        exit();
    }
    
    private function eliminarRecursoHumano() {
        // Verificar permisos
        if ($this->usuario['rol'] != 'gerente' && $this->usuario['rol'] != 'administrador') {
            $_SESSION['mensaje'] = 'No tienes permisos para eliminar recursos';
            $_SESSION['tipo_mensaje'] = 'error';
            header('Location: ?accion=listar');
            exit();
        }
        
        $id = $_POST['id'] ?? 0;
        
        if (!$id) {
            $_SESSION['mensaje'] = 'ID de recurso no válido';
            $_SESSION['tipo_mensaje'] = 'error';
            header('Location: ?accion=listar');
            exit();
        }
        
        $resultado = $this->model->eliminarRecursoHumano($id);
        
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
    
    private function crearCapacitacion() {
        // Verificar permisos
        if ($this->usuario['rol'] != 'gerente' && $this->usuario['rol'] != 'administrador') {
            $_SESSION['mensaje'] = 'No tienes permisos para crear capacitaciones';
            $_SESSION['tipo_mensaje'] = 'error';
            header('Location: ?accion=listar');
            exit();
        }
        
        // Validar datos
        $errores = $this->validarDatosCapacitacion($_POST);
        
        if (!empty($errores)) {
            $_SESSION['errores'] = $errores;
            $_SESSION['datos_form'] = $_POST;
            header('Location: ?accion=crear_capacitacion');
            exit();
        }
        
        // Preparar datos
        $datos = [
            'recurso_humano_id' => $_POST['recurso_humano_id'],
            'tipo_capacitacion' => $_POST['tipo_capacitacion'],
            'descripcion' => $_POST['descripcion'],
            'duracion_horas' => $_POST['duracion_horas'],
            'fecha_inicio' => $_POST['fecha_inicio'],
            'fecha_fin' => $_POST['fecha_fin'],
            'estado' => $_POST['estado'] ?? 'pendiente',
            'costo' => $_POST['costo'] ?? 0,
            'certificacion' => $_POST['certificacion'] ?? ''
        ];
        
        // Crear capacitación
        $id = $this->model->crearCapacitacion($datos);
        
        if ($id) {
            // Verificar si se debe marcar como resuelto automáticamente
            $this->verificarYMarcarNecesidadResuelta($_POST['recurso_humano_id'], $_POST['descripcion']);
            
            $_SESSION['mensaje'] = 'Capacitación creada exitosamente';
            $_SESSION['tipo_mensaje'] = 'success';
            
            // Redirigir según el origen
            if (isset($_POST['origen']) && $_POST['origen'] == 'detalle') {
                header("Location: ?accion=ver&id={$_POST['recurso_humano_id']}");
            } else {
                header('Location: ?accion=capacitaciones');
            }
        } else {
            $_SESSION['mensaje'] = 'Error al crear la capacitación';
            $_SESSION['tipo_mensaje'] = 'error';
            $_SESSION['datos_form'] = $_POST;
            header('Location: ?accion=crear_capacitacion');
        }
        exit();
    }

    private function marcarNecesidadResueltaManual() {
        // Verificar permisos
        if ($this->usuario['rol'] != 'gerente' && $this->usuario['rol'] != 'administrador') {
            $_SESSION['mensaje'] = 'No tienes permisos para marcar necesidades como resueltas';
            $_SESSION['tipo_mensaje'] = 'error';
            header('Location: ?accion=listar');
            exit();
        }
        
        $id = $_POST['id'] ?? 0;
        
        if (!$id) {
            $_SESSION['mensaje'] = 'ID de recurso no válido';
            $_SESSION['tipo_mensaje'] = 'error';
            header('Location: ?accion=listar');
            exit();
        }
        
        $resultado = $this->model->marcarNecesidadResuelta($id);
        
        if ($resultado) {
            $_SESSION['mensaje'] = 'Necesidad de capacitación marcada como resuelta';
            $_SESSION['tipo_mensaje'] = 'success';
        } else {
            $_SESSION['mensaje'] = 'Error al marcar la necesidad como resuelta';
            $_SESSION['tipo_mensaje'] = 'error';
        }
        
        header('Location: ?accion=listar');
        exit();
    }

    /**
     * Verificar y marcar necesidad de capacitación como resuelta
     */
    private function verificarYMarcarNecesidadResuelta($recurso_humano_id, $descripcion_capacitacion) {
        // Obtener información del recurso
        $recurso = $this->model->obtenerRecursoHumanoPorId($recurso_humano_id);
        
        if (!$recurso || empty($recurso['capacitacion_requerida'])) {
            return false;
        }
        
        $necesidad = strtolower(trim($recurso['capacitacion_requerida']));
        $capacitacion = strtolower(trim($descripcion_capacitacion));
        
        // Lista de palabras clave para buscar similitudes
        $palabras_clave_necesidad = $this->extraerPalabrasClave($necesidad);
        $palabras_clave_capacitacion = $this->extraerPalabrasClave($capacitacion);
        
        // Contar coincidencias
        $coincidencias = array_intersect($palabras_clave_necesidad, $palabras_clave_capacitacion);
        
        // Si hay al menos 2 palabras clave en común, marcar como resuelto
        if (count($coincidencias) >= 2) {
            $this->model->marcarNecesidadResuelta($recurso_humano_id);
            return true;
        }
        
        return false;
    }

    /**
     * Extraer palabras clave de un texto
     */
    private function extraerPalabrasClave($texto) {
        // Eliminar palabras comunes
        $palabras_comunes = ['el', 'la', 'los', 'las', 'de', 'del', 'en', 'y', 'o', 'a', 'con', 'para', 'por', 'sobre', 'entre'];
        
        // Limpiar y separar palabras
        $texto = preg_replace('/[^\w\s]/', ' ', $texto);
        $palabras = explode(' ', strtolower($texto));
        
        // Filtrar palabras comunes y vacías
        $palabras_clave = array_filter($palabras, function($palabra) use ($palabras_comunes) {
            return !empty($palabra) && 
                strlen($palabra) > 3 && 
                !in_array($palabra, $palabras_comunes);
        });
        
        return array_unique($palabras_clave);
    }
    
    private function actualizarCapacitacion() {
        // Verificar permisos
        if ($this->usuario['rol'] != 'gerente' && $this->usuario['rol'] != 'administrador') {
            $_SESSION['mensaje'] = 'No tienes permisos para actualizar capacitaciones';
            $_SESSION['tipo_mensaje'] = 'error';
            header('Location: ?accion=capacitaciones');
            exit();
        }
        
        $id = $_POST['id'] ?? 0;
        
        if (!$id) {
            $_SESSION['mensaje'] = 'ID de capacitación no válido';
            $_SESSION['tipo_mensaje'] = 'error';
            header('Location: ?accion=capacitaciones');
            exit();
        }
        
        // Validar datos
        $errores = $this->validarDatosCapacitacion($_POST);
        
        if (!empty($errores)) {
            $_SESSION['errores'] = $errores;
            header("Location: ?accion=editar_capacitacion&id={$id}");
            exit();
        }
        
        // Preparar datos
        $datos = [
            'tipo_capacitacion' => $_POST['tipo_capacitacion'],
            'descripcion' => $_POST['descripcion'],
            'duracion_horas' => $_POST['duracion_horas'],
            'fecha_inicio' => $_POST['fecha_inicio'],
            'fecha_fin' => $_POST['fecha_fin'],
            'estado' => $_POST['estado'],
            'costo' => $_POST['costo'] ?? 0,
            'certificacion' => $_POST['certificacion'] ?? ''
        ];
        
        // Actualizar capacitación
        $resultado = $this->model->actualizarCapacitacion($id, $datos);
        
        if ($resultado) {
            $_SESSION['mensaje'] = 'Capacitación actualizada exitosamente';
            $_SESSION['tipo_mensaje'] = 'success';
            header('Location: ?accion=capacitaciones');
        } else {
            $_SESSION['mensaje'] = 'Error al actualizar la capacitación';
            $_SESSION['tipo_mensaje'] = 'error';
            header("Location: ?accion=editar_capacitacion&id={$id}");
        }
        exit();
    }

    private function editarCapacitacion() {
        // Verificar permisos
        if ($this->usuario['rol'] != 'gerente' && $this->usuario['rol'] != 'administrador') {
            $_SESSION['mensaje'] = 'No tienes permisos para editar capacitaciones';
            $_SESSION['tipo_mensaje'] = 'error';
            header('Location: ?accion=capacitaciones');
            exit();
        }
        
        $id = $_POST['id'] ?? 0;
        
        if (!$id) {
            $_SESSION['mensaje'] = 'ID de capacitación no válido';
            $_SESSION['tipo_mensaje'] = 'error';
            header('Location: ?accion=capacitaciones');
            exit();
        }
        
        // Validar datos
        $errores = $this->validarDatosCapacitacion($_POST);
        
        if (!empty($errores)) {
            $_SESSION['errores'] = $errores;
            $_SESSION['datos_form'] = $_POST;
            header("Location: ?accion=editar_capacitacion&id={$id}");
            exit();
        }
        
        // Preparar datos
        $datos = [
            'tipo_capacitacion' => $_POST['tipo_capacitacion'],
            'descripcion' => $_POST['descripcion'],
            'duracion_horas' => $_POST['duracion_horas'],
            'fecha_inicio' => $_POST['fecha_inicio'],
            'fecha_fin' => $_POST['fecha_fin'],
            'estado' => $_POST['estado'],
            'costo' => $_POST['costo'] ?? 0,
            'certificacion' => $_POST['certificacion'] ?? ''
        ];
        
        // Actualizar capacitación
        $resultado = $this->model->actualizarCapacitacion($id, $datos);
        
        if ($resultado) {
            $_SESSION['mensaje'] = 'Capacitación actualizada exitosamente';
            $_SESSION['tipo_mensaje'] = 'success';
            header('Location: ?accion=capacitaciones');
        } else {
            $_SESSION['mensaje'] = 'Error al actualizar la capacitación';
            $_SESSION['tipo_mensaje'] = 'error';
            $_SESSION['datos_form'] = $_POST;
            header("Location: ?accion=editar_capacitacion&id={$id}");
        }
        exit();
    }
    
    private function eliminarCapacitacion() {
        // Verificar permisos
        if ($this->usuario['rol'] != 'gerente' && $this->usuario['rol'] != 'administrador') {
            $_SESSION['mensaje'] = 'No tienes permisos para eliminar capacitaciones';
            $_SESSION['tipo_mensaje'] = 'error';
            header('Location: ?accion=capacitaciones');
            exit();
        }
        
        $id = $_POST['id'] ?? 0;
        
        if (!$id) {
            $_SESSION['mensaje'] = 'ID de capacitación no válido';
            $_SESSION['tipo_mensaje'] = 'error';
            header('Location: ?accion=capacitaciones');
            exit();
        }
        
        $resultado = $this->model->eliminarCapacitacion($id);
        
        if ($resultado) {
            $_SESSION['mensaje'] = 'Capacitación eliminada correctamente';
            $_SESSION['tipo_mensaje'] = 'success';
        } else {
            $_SESSION['mensaje'] = 'Error al eliminar la capacitación';
            $_SESSION['tipo_mensaje'] = 'error';
        }
        
        header('Location: ?accion=capacitaciones');
        exit();
    }
    
    private function actualizarHorasTrabajadas() {
        // Verificar permisos
        if ($this->usuario['rol'] != 'gerente' && $this->usuario['rol'] != 'administrador') {
            $_SESSION['mensaje'] = 'No tienes permisos para actualizar horas';
            $_SESSION['tipo_mensaje'] = 'error';
            header('Location: ?accion=listar');
            exit();
        }
        
        $id = $_POST['id'] ?? 0;
        $horas = $_POST['horas_realizadas'] ?? 0;
        
        if (!$id || !is_numeric($horas)) {
            $_SESSION['mensaje'] = 'Datos inválidos';
            $_SESSION['tipo_mensaje'] = 'error';
            header('Location: ?accion=listar');
            exit();
        }
        
        $resultado = $this->model->actualizarHorasTrabajadas($id, $horas);
        
        if ($resultado) {
            $_SESSION['mensaje'] = 'Horas actualizadas correctamente';
            $_SESSION['tipo_mensaje'] = 'success';
        } else {
            $_SESSION['mensaje'] = 'Error al actualizar las horas';
            $_SESSION['tipo_mensaje'] = 'error';
        }
        
        header('Location: ?accion=listar');
        exit();
    }
    
    private function cambiarEstadoCapacitacion() {
        // Verificar permisos
        if ($this->usuario['rol'] != 'gerente' && $this->usuario['rol'] != 'administrador') {
            $_SESSION['mensaje'] = 'No tienes permisos para cambiar estados';
            $_SESSION['tipo_mensaje'] = 'error';
            header('Location: ?accion=capacitaciones');
            exit();
        }
        
        $id = $_POST['id'] ?? 0;
        $estado = $_POST['estado'] ?? '';
        
        if (!$id || !$estado) {
            $_SESSION['mensaje'] = 'Datos incompletos';
            $_SESSION['tipo_mensaje'] = 'error';
            header('Location: ?accion=capacitaciones');
            exit();
        }
        
        $resultado = $this->model->actualizarEstadoCapacitacion($id, $estado);
        
        if ($resultado) {
            $_SESSION['mensaje'] = 'Estado actualizado correctamente';
            $_SESSION['tipo_mensaje'] = 'success';
        } else {
            $_SESSION['mensaje'] = 'Error al actualizar el estado';
            $_SESSION['tipo_mensaje'] = 'error';
        }
        
        header('Location: ?accion=capacitaciones');
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
                
                if (isset($_GET['nivel_experiencia']) && !empty($_GET['nivel_experiencia'])) {
                    $filtros['nivel_experiencia'] = $_GET['nivel_experiencia'];
                }
                
                if (isset($_GET['rol_proyecto']) && !empty($_GET['rol_proyecto'])) {
                    $filtros['rol_proyecto'] = $_GET['rol_proyecto'];
                }
                
                $datos_vista['recursos'] = $this->model->obtenerRecursosHumanos($filtros);
                $datos_vista['necesitan_capacitacion'] = $this->model->obtenerRecursosNecesitanCapacitacion();
                break;
                
            case 'asignar':
                // Verificar permisos
                if ($this->usuario['rol'] != 'gerente' && $this->usuario['rol'] != 'administrador') {
                    $_SESSION['mensaje'] = 'No tienes permisos para asignar recursos';
                    $_SESSION['tipo_mensaje'] = 'error';
                    header('Location: ?accion=listar');
                    exit();
                }
                
                $datos_vista['usuarios_disponibles'] = $this->model->obtenerUsuariosDisponibles();
                $datos_vista['proyectos_disponibles'] = $this->model->obtenerProyectosDisponibles();
                $datos_vista['niveles_experiencia'] = ['junior', 'intermedio', 'senior', 'experto'];
                
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
                    $_SESSION['mensaje'] = 'ID de recurso no válido';
                    $_SESSION['tipo_mensaje'] = 'error';
                    header('Location: ?accion=listar');
                    exit();
                }
                
                $recurso = $this->model->obtenerRecursoHumanoPorId($id);
                
                if (!$recurso) {
                    $_SESSION['mensaje'] = 'Recurso no encontrado';
                    $_SESSION['tipo_mensaje'] = 'error';
                    header('Location: ?accion=listar');
                    exit();
                }
                
                $datos_vista['recurso'] = $recurso;
                $datos_vista['capacitaciones'] = $this->model->obtenerCapacitacionesPorRecurso($id);
                $datos_vista['horas_trabajadas'] = $this->model->obtenerHorasTrabajadas($id);
                break;
                
            case 'editar_recurso':
                // Verificar permisos
                if ($this->usuario['rol'] != 'gerente' && $this->usuario['rol'] != 'administrador') {
                    $_SESSION['mensaje'] = 'No tienes permisos para editar recursos';
                    $_SESSION['tipo_mensaje'] = 'error';
                    header('Location: ?accion=listar');
                    exit();
                }
                
                $id = $_GET['id'] ?? 0;
                
                if (!$id) {
                    $_SESSION['mensaje'] = 'ID de recurso no válido';
                    $_SESSION['tipo_mensaje'] = 'error';
                    header('Location: ?accion=listar');
                    exit();
                }
                
                $recurso = $this->model->obtenerRecursoHumanoPorId($id);
                
                if (!$recurso) {
                    $_SESSION['mensaje'] = 'Recurso no encontrado';
                    $_SESSION['tipo_mensaje'] = 'error';
                    header('Location: ?accion=listar');
                    exit();
                }
                
                $datos_vista['recurso'] = $recurso;
                $datos_vista['niveles_experiencia'] = ['junior', 'intermedio', 'senior', 'experto'];
                
                // Si hay errores de validación previos
                if (isset($_SESSION['errores'])) {
                    $datos_vista['errores'] = $_SESSION['errores'];
                    unset($_SESSION['errores']);
                }
                break;
                
            case 'capacitaciones':
                $filtros = [];
                
                if (isset($_GET['proyecto_id']) && !empty($_GET['proyecto_id'])) {
                    $filtros['proyecto_id'] = $_GET['proyecto_id'];
                }
                
                // Obtener todos los recursos para mostrar sus capacitaciones
                $datos_vista['recursos'] = $this->model->obtenerRecursosHumanos($filtros);
                break;
                
            case 'crear_capacitacion':
                // Verificar permisos
                if ($this->usuario['rol'] != 'gerente' && $this->usuario['rol'] != 'administrador') {
                    $_SESSION['mensaje'] = 'No tienes permisos para crear capacitaciones';
                    $_SESSION['tipo_mensaje'] = 'error';
                    header('Location: ?accion=capacitaciones');
                    exit();
                }
                
                $datos_vista['recursos'] = $this->model->obtenerRecursosHumanos();
                $datos_vista['tipos_capacitacion'] = [
                    'Técnica',
                    'Gerencial',
                    'Liderazgo',
                    'Comunicación',
                    'Seguridad',
                    'Calidad',
                    'Metodologías',
                    'Herramientas'
                ];
                
                $datos_vista['estados_capacitacion'] = ['pendiente', 'en_curso', 'completada', 'cancelada'];
                
                // Si viene de un recurso específico
                if (isset($_GET['recurso_id'])) {
                    $datos_vista['recurso_seleccionado'] = $this->model->obtenerRecursoHumanoPorId($_GET['recurso_id']);
                }
                
                // Si hay errores de validación previos
                if (isset($_SESSION['errores'])) {
                    $datos_vista['errores'] = $_SESSION['errores'];
                    $datos_vista['datos_form'] = $_SESSION['datos_form'];
                    unset($_SESSION['errores'], $_SESSION['datos_form']);
                }
                break;

            case 'editar_capacitacion':
                // Verificar permisos
                if ($this->usuario['rol'] != 'gerente' && $this->usuario['rol'] != 'administrador') {
                    $_SESSION['mensaje'] = 'No tienes permisos para editar capacitaciones';
                    $_SESSION['tipo_mensaje'] = 'error';
                    header('Location: ?accion=capacitaciones');
                    exit();
                }
                
                $id = $_GET['id'] ?? 0;
                
                if (!$id) {
                    $_SESSION['mensaje'] = 'ID de capacitación no válido';
                    $_SESSION['tipo_mensaje'] = 'error';
                    header('Location: ?accion=capacitaciones');
                    exit();
                }
                
                // Obtener capacitación específica
                $capacitacion = $this->model->obtenerCapacitacionPorId($id);
                
                if (!$capacitacion) {
                    $_SESSION['mensaje'] = 'Capacitación no encontrada';
                    $_SESSION['tipo_mensaje'] = 'error';
                    header('Location: ?accion=capacitaciones');
                    exit();
                }
                
                $datos_vista['capacitacion'] = $capacitacion;
                $datos_vista['recursos'] = $this->model->obtenerRecursosHumanos();
                $datos_vista['tipos_capacitacion'] = [
                    'Técnica',
                    'Gerencial',
                    'Liderazgo',
                    'Comunicación',
                    'Seguridad',
                    'Calidad',
                    'Metodologías',
                    'Herramientas'
                ];
                
                $datos_vista['estados_capacitacion'] = ['pendiente', 'en_curso', 'completada', 'cancelada'];
                
                // Si hay errores de validación previos
                if (isset($_SESSION['errores'])) {
                    $datos_vista['errores'] = $_SESSION['errores'];
                    $datos_vista['datos_form'] = $_SESSION['datos_form'];
                    unset($_SESSION['errores'], $_SESSION['datos_form']);
                } else {
                    // Usar datos de la capacitación
                    $datos_vista['datos_form'] = [
                        'id' => $capacitacion['id'],
                        'recurso_humano_id' => $capacitacion['recurso_humano_id'],
                        'tipo_capacitacion' => $capacitacion['tipo_capacitacion'],
                        'descripcion' => $capacitacion['descripcion'],
                        'duracion_horas' => $capacitacion['duracion_horas'],
                        'fecha_inicio' => $capacitacion['fecha_inicio_form'] ?? $capacitacion['fecha_inicio'],
                        'fecha_fin' => $capacitacion['fecha_fin_form'] ?? $capacitacion['fecha_fin'],
                        'estado' => $capacitacion['estado'],
                        'costo' => $capacitacion['costo'],
                        'certificacion' => $capacitacion['certificacion']
                    ];
                }
                break;
            case 'reportes':
                $proyecto_id = $_GET['proyecto_id'] ?? null;
                $periodo = $_GET['periodo'] ?? 'mes_actual';
                
                // Obtener estadísticas completas
                $estadisticas_completas = $this->model->obtenerEstadisticasCompletas($proyecto_id);
                
                // Calcular métricas adicionales
                $total_recursos = $estadisticas_completas['total_recursos'] ?? 0;
                $total_capacitaciones = $estadisticas_completas['total_capacitaciones'] ?? 0;
                $costo_total = $estadisticas_completas['costo_total_capacitaciones'] ?? 0;
                $horas_totales = $estadisticas_completas['horas_totales_trabajadas'] ?? 0;
                
                // Calcular porcentajes
                $porcentaje_juniors = $total_recursos > 0 ? ($estadisticas_completas['juniors'] ?? 0) / $total_recursos * 100 : 0;
                $porcentaje_intermedios = $total_recursos > 0 ? ($estadisticas_completas['intermedios'] ?? 0) / $total_recursos * 100 : 0;
                $porcentaje_seniors = $total_recursos > 0 ? ($estadisticas_completas['seniors'] ?? 0) / $total_recursos * 100 : 0;
                $porcentaje_expertos = $total_recursos > 0 ? ($estadisticas_completas['expertos'] ?? 0) / $total_recursos * 100 : 0;
                
                // Calcular retorno estimado (suponiendo 15% mejora por capacitación)
                $horas_mejora_estimada = $horas_totales * 0.15;
                
                // Distribución por nivel
                $distribucion_niveles = [
                    'junior' => $estadisticas_completas['juniors'] ?? 0,
                    'intermedio' => $estadisticas_completas['intermedios'] ?? 0,
                    'senior' => $estadisticas_completas['seniors'] ?? 0,
                    'experto' => $estadisticas_completas['expertos'] ?? 0
                ];
                
                // Capacitaciones por estado
                $capacitaciones_por_estado = [
                    'pendiente' => $estadisticas_completas['capacitaciones_pendientes'] ?? 0,
                    'en_curso' => $estadisticas_completas['capacitaciones_en_curso'] ?? 0,
                    'completada' => $estadisticas_completas['capacitaciones_completadas'] ?? 0,
                    'cancelada' => $estadisticas_completas['capacitaciones_canceladas'] ?? 0
                ];
                
                // Preparar datos para la vista
                $datos_vista['estadisticas'] = [
                    'total_recursos' => $total_recursos,
                    'total_capacitaciones' => $total_capacitaciones,
                    'total_costo_capacitacion' => $costo_total,
                    'total_horas_trabajadas' => $horas_totales,
                    'horas_mejora_estimada' => $horas_mejora_estimada,
                    
                    // Distribuciones
                    'distribucion_niveles' => $distribucion_niveles,
                    'capacitaciones_por_estado' => $capacitaciones_por_estado,
                    
                    // Porcentajes
                    'porcentaje_juniors' => $porcentaje_juniors,
                    'porcentaje_intermedios' => $porcentaje_intermedios,
                    'porcentaje_seniors' => $porcentaje_seniors,
                    'porcentaje_expertos' => $porcentaje_expertos,
                    'porcentaje_horas_completadas' => $estadisticas_completas['porcentaje_horas_completadas'] ?? 0,
                    'porcentaje_capacitaciones_completadas' => $estadisticas_completas['porcentaje_capacitaciones_completadas'] ?? 0,
                    
                    // Promedios
                    'horas_promedio_capacitacion' => $estadisticas_completas['horas_promedio_capacitacion'] ?? 0,
                    'horas_trabajadas_promedio' => $estadisticas_completas['horas_promedio_trabajadas'] ?? 0,
                    
                    // Métricas calculadas
                    'horas_capacitacion_por_recurso' => $total_recursos > 0 ? ($estadisticas_completas['total_horas_capacitacion'] ?? 0) / $total_recursos : 0,
                    'costo_promedio_capacitacion' => $total_capacitaciones > 0 ? $costo_total / $total_capacitaciones : 0,
                    'tasa_completitud_capacitaciones' => $total_capacitaciones > 0 ? ($estadisticas_completas['capacitaciones_completadas'] ?? 0) / $total_capacitaciones * 100 : 0,
                    'eficiencia_equipo' => $estadisticas_completas['porcentaje_horas_completadas'] ?? 0,
                    
                    // Datos adicionales
                    'capacitaciones_atrasadas' => $estadisticas_completas['capacitaciones_atrasadas'] ?? 0
                ];
                
                // Obtener distribución por proyecto
                $datos_vista['distribucion_proyectos'] = $this->model->obtenerDistribucionPorProyecto();
                
                // Obtener métricas de costos
                $datos_vista['metricas_costos'] = $this->model->obtenerMetricasCostos();
                
                // Obtener horas trabajadas para la tabla
                $datos_vista['horas_trabajadas'] = $this->model->obtenerHorasTrabajadas();
                
                // Obtener últimos recursos que necesitan capacitación
                $datos_vista['necesitan_capacitacion'] = $this->model->obtenerRecursosNecesitanCapacitacion();
                
                break;
            
            default:
                // Por defecto, listar
                header('Location: ?accion=listar');
                exit();
        }
        
        return $datos_vista;
    }
    
    private function validarDatosRecursoHumano($datos) {
        $errores = [];

        // Validar usuario_id
        if (empty($datos['usuario_id'])) {
            $errores['usuario_id'] = 'Debe seleccionar un usuario';
        }

        // Validar proyecto_id
        if (empty($datos['proyecto_id'])) {
            $errores['proyecto_id'] = 'Debe seleccionar un proyecto';
        }

        // Validar rol_proyecto
        if (empty(trim($datos['rol_proyecto']))) {
            $errores['rol_proyecto'] = 'El rol en el proyecto es obligatorio';
        }

        // Validar nivel_experiencia
        if (empty($datos['nivel_experiencia']) || !in_array($datos['nivel_experiencia'], ['junior', 'intermedio', 'senior', 'experto'])) {
            $errores['nivel_experiencia'] = 'Debe seleccionar un nivel de experiencia válido';
        }

        // Validar horas asignadas
        if (isset($datos['horas_asignadas']) && (!is_numeric($datos['horas_asignadas']) || $datos['horas_asignadas'] < 0)) {
            $errores['horas_asignadas'] = 'Las horas asignadas deben ser un número válido mayor o igual a 0';
        }

        // Validar horas realizadas
        if (isset($datos['horas_realizadas']) && (!is_numeric($datos['horas_realizadas']) || $datos['horas_realizadas'] < 0)) {
            $errores['horas_realizadas'] = 'Las horas realizadas deben ser un número válido mayor o igual a 0';
        }

        return $errores;
    }
    
    private function validarDatosCapacitacion($datos) {
        $errores = [];

        // Validar recurso_humano_id
        if (empty($datos['recurso_humano_id'])) {
            $errores['recurso_humano_id'] = 'Debe seleccionar un recurso humano';
        }

        // Validar tipo_capacitacion
        if (empty(trim($datos['tipo_capacitacion']))) {
            $errores['tipo_capacitacion'] = 'El tipo de capacitación es obligatorio';
        }

        // Validar descripcion
        if (empty(trim($datos['descripcion']))) {
            $errores['descripcion'] = 'La descripción es obligatoria';
        }

        // Validar duración
        if (!isset($datos['duracion_horas']) || !is_numeric($datos['duracion_horas']) || $datos['duracion_horas'] <= 0) {
            $errores['duracion_horas'] = 'La duración debe ser un número mayor a 0';
        }

        // Validar fechas
        if (empty($datos['fecha_inicio'])) {
            $errores['fecha_inicio'] = 'La fecha de inicio es obligatoria';
        }

        if (empty($datos['fecha_fin'])) {
            $errores['fecha_fin'] = 'La fecha de fin es obligatoria';
        }

        // Validar que fecha_fin sea posterior a fecha_inicio
        if (!empty($datos['fecha_inicio']) && !empty($datos['fecha_fin'])) {
            if (strtotime($datos['fecha_fin']) < strtotime($datos['fecha_inicio'])) {
                $errores['fecha_fin'] = 'La fecha de fin no puede ser anterior a la fecha de inicio';
            }
        }

        // Validar costo
        if (isset($datos['costo']) && (!is_numeric($datos['costo']) || $datos['costo'] < 0)) {
            $errores['costo'] = 'El costo debe ser un número válido mayor o igual a 0';
        }

        return $errores;
    }
}

// Ejecutar si es llamado directamente
if (basename(__FILE__) === basename($_SERVER['PHP_SELF'])) {
    $controller = new DesarrollarEquipoController();
    $controller->index();
}
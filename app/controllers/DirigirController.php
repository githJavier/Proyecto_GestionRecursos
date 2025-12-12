<?php
// DirigirEquipoController.php

session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: ../login');
    exit;
}

// Incluir configuración de base de datos
require_once __DIR__ . '/../config/database.php';
// Incluir el modelo
require_once __DIR__ . '/../models/DirigirModel.php';

// Usar namespace de ConexionBD
use Config\ConexionBD;

class DirigirEquipoController {
    
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
        $this->model = new DirigirEquipoModel($this->db);
        
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
        
        error_log("DEBUG Controller: Acción recibida: " . $accion);
        
        // Procesar POST (crear, actualizar, eliminar, etc.)
        $this->procesarPost();
        
        // Obtener datos según la acción
        $datos_vista = $this->obtenerDatos($accion);
        
        // DEBUG: Verificar datos obtenidos
        error_log("DEBUG Controller: Datos vista obtenidos, accion: " . $accion);
        error_log("DEBUG Controller: Dashboard en datos_vista: " . (isset($datos_vista['dashboard']) ? 'SI' : 'NO'));
        
        // Variables para la vista
        $mensaje = $_SESSION['mensaje'] ?? '';
        $tipo_mensaje = $_SESSION['tipo_mensaje'] ?? '';
        unset($_SESSION['mensaje'], $_SESSION['tipo_mensaje']);
        
        // Pasar usuario a la vista
        $datos_vista['usuario'] = $this->usuario;
        
        // Pasar el modelo a la vista para que pueda usarlo directamente
        $datos_vista['model'] = $this->model;
        
        // Extraer todas las variables del array
        extract($datos_vista);
        
        // DEBUG: Verificar variables extraídas
        error_log("DEBUG Controller: Variables extraídas - accion: " . ($accion ?? 'NULL'));
        error_log("DEBUG Controller: Variables extraídas - dashboard: " . (isset($dashboard) ? 'SI' : 'NO'));
        error_log("DEBUG Controller: Variables extraídas - usuario_rol: " . ($usuario_rol ?? 'NULL'));
        
        // Incluir vista
        require_once __DIR__ . '/../views/modulos/dirigir/dirigir.php';
    }
    
    private function procesarPost() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;
        
        $accion_post = $_POST['accion'] ?? '';
        
        switch ($accion_post) {
            case 'crear_tarea':
                $this->crearTarea();
                break;
                
            case 'actualizar_tarea':
                $this->actualizarTarea();
                break;
                
            case 'eliminar_tarea':
                $this->eliminarTarea();
                break;
                
            case 'actualizar_porcentaje':
                $this->actualizarPorcentajeTarea();
                break;
                
            case 'actualizar_horas_tarea':
                $this->actualizarHorasTarea();
                break;
                
            case 'crear_comunicacion':
                $this->crearComunicacion();
                break;
                
            case 'actualizar_comunicacion':
                $this->actualizarComunicacion();
                break;
                
            case 'eliminar_comunicacion':
                $this->eliminarComunicacion();
                break;
                
            case 'marcar_como_leida':
                $this->marcarComunicacionComoLeida();
                break;
                
            case 'cambiar_estado_tarea':
                $this->cambiarEstadoTarea();
                break;
        }
    }
    
    private function crearTarea() {
        // Verificar permisos
        if ($this->usuario['rol'] != 'gerente' && $this->usuario['rol'] != 'administrador') {
            $_SESSION['mensaje'] = 'No tienes permisos para crear tareas';
            $_SESSION['tipo_mensaje'] = 'error';
            header('Location: ?accion=tareas');
            exit();
        }
        
        // Validar datos
        $errores = $this->validarDatosTarea($_POST);
        
        if (!empty($errores)) {
            $_SESSION['errores'] = $errores;
            $_SESSION['datos_form'] = $_POST;
            header('Location: ?accion=crear_tarea');
            exit();
        }
        
        // Preparar datos
        $datos = [
            'proyecto_id' => $_POST['proyecto_id'],
            'recurso_humano_id' => $_POST['recurso_humano_id'],
            'descripcion_tarea' => $_POST['descripcion_tarea'],
            'fecha_asignacion' => $_POST['fecha_asignacion'] ?? date('Y-m-d'),
            'fecha_limite' => $_POST['fecha_limite'],
            'horas_estimadas' => $_POST['horas_estimadas'] ?? 0,
            'horas_reales' => $_POST['horas_reales'] ?? 0,
            'prioridad' => $_POST['prioridad'] ?? 'media',
            'estado' => $_POST['estado'] ?? 'pendiente',
            'porcentaje_completado' => $_POST['porcentaje_completado'] ?? 0
        ];
        
        // Crear tarea
        $id = $this->model->crearTarea($datos);
        
        if ($id) {
            $_SESSION['mensaje'] = 'Tarea creada exitosamente';
            $_SESSION['tipo_mensaje'] = 'success';
            
            // Redirigir según el botón presionado
            if (isset($_POST['guardar_y_continuar'])) {
                header('Location: ?accion=crear_tarea');
            } else {
                header('Location: ?accion=tareas');
            }
        } else {
            $_SESSION['mensaje'] = 'Error al crear la tarea';
            $_SESSION['tipo_mensaje'] = 'error';
            $_SESSION['datos_form'] = $_POST;
            header('Location: ?accion=crear_tarea');
        }
        exit();
    }
    
    private function actualizarTarea() {
        // Verificar permisos
        if ($this->usuario['rol'] != 'gerente' && $this->usuario['rol'] != 'administrador') {
            $_SESSION['mensaje'] = 'No tienes permisos para actualizar tareas';
            $_SESSION['tipo_mensaje'] = 'error';
            header('Location: ?accion=tareas');
            exit();
        }
        
        $id = $_POST['id'] ?? 0;
        
        if (!$id) {
            $_SESSION['mensaje'] = 'ID de tarea no válido';
            $_SESSION['tipo_mensaje'] = 'error';
            header('Location: ?accion=tareas');
            exit();
        }
        
        // Validar datos
        $errores = $this->validarDatosTarea($_POST);
        
        if (!empty($errores)) {
            $_SESSION['errores'] = $errores;
            header("Location: ?accion=editar_tarea&id={$id}");
            exit();
        }
        
        // Preparar datos
        $datos = [
            'descripcion_tarea' => $_POST['descripcion_tarea'],
            'fecha_limite' => $_POST['fecha_limite'],
            'horas_estimadas' => $_POST['horas_estimadas'],
            'horas_reales' => $_POST['horas_reales'],
            'prioridad' => $_POST['prioridad'],
            'estado' => $_POST['estado'],
            'porcentaje_completado' => $_POST['porcentaje_completado']
        ];
        
        // Actualizar tarea
        $resultado = $this->model->actualizarTarea($id, $datos);
        
        if ($resultado) {
            $_SESSION['mensaje'] = 'Tarea actualizada exitosamente';
            $_SESSION['tipo_mensaje'] = 'success';
            header('Location: ?accion=tareas');
        } else {
            $_SESSION['mensaje'] = 'Error al actualizar la tarea';
            $_SESSION['tipo_mensaje'] = 'error';
            header("Location: ?accion=editar_tarea&id={$id}");
        }
        exit();
    }
    
    private function eliminarTarea() {
        // Verificar permisos
        if ($this->usuario['rol'] != 'gerente' && $this->usuario['rol'] != 'administrador') {
            $_SESSION['mensaje'] = 'No tienes permisos para eliminar tareas';
            $_SESSION['tipo_mensaje'] = 'error';
            header('Location: ?accion=tareas');
            exit();
        }
        
        $id = $_POST['id'] ?? 0;
        
        if (!$id) {
            $_SESSION['mensaje'] = 'ID de tarea no válido';
            $_SESSION['tipo_mensaje'] = 'error';
            header('Location: ?accion=tareas');
            exit();
        }
        
        $resultado = $this->model->eliminarTarea($id);
        
        if ($resultado) {
            $_SESSION['mensaje'] = 'Tarea eliminada correctamente';
            $_SESSION['tipo_mensaje'] = 'success';
        } else {
            $_SESSION['mensaje'] = 'Error al eliminar la tarea';
            $_SESSION['tipo_mensaje'] = 'error';
        }
        
        header('Location: ?accion=tareas');
        exit();
    }
    
    private function actualizarPorcentajeTarea() {
        // Verificar permisos (gerentes, administradores y el propio recurso pueden actualizar)
        $id = $_POST['id'] ?? 0;
        $porcentaje = $_POST['porcentaje'] ?? 0;
        
        if (!$id || !is_numeric($porcentaje)) {
            $_SESSION['mensaje'] = 'Datos inválidos';
            $_SESSION['tipo_mensaje'] = 'error';
            header('Location: ?accion=tareas');
            exit();
        }
        
        // Si es miembro_equipo, verificar que sea su tarea
        if ($this->usuario['rol'] == 'miembro_equipo') {
            // Obtener la tarea para verificar si pertenece al usuario
            $tarea = $this->model->obtenerTareaPorId($id);
            if (!$tarea) {
                $_SESSION['mensaje'] = 'Tarea no encontrada';
                $_SESSION['tipo_mensaje'] = 'error';
                header('Location: ?accion=tareas');
                exit();
            }
            
            // Verificar si el usuario es el asignado a la tarea
            if ($tarea['usuario_id'] != $this->usuario['id']) {
                $_SESSION['mensaje'] = 'No tienes permisos para actualizar esta tarea';
                $_SESSION['tipo_mensaje'] = 'error';
                header('Location: ?accion=tareas');
                exit();
            }
        }
        
        $resultado = $this->model->actualizarPorcentajeTarea($id, $porcentaje);
        
        if ($resultado) {
            $_SESSION['mensaje'] = 'Progreso actualizado correctamente';
            $_SESSION['tipo_mensaje'] = 'success';
        } else {
            $_SESSION['mensaje'] = 'Error al actualizar el progreso';
            $_SESSION['tipo_mensaje'] = 'error';
        }
        
        header('Location: ?accion=tareas');
        exit();
    }
    
    private function actualizarHorasTarea() {
        // Verificar permisos
        if ($this->usuario['rol'] != 'gerente' && $this->usuario['rol'] != 'administrador') {
            $_SESSION['mensaje'] = 'No tienes permisos para actualizar horas';
            $_SESSION['tipo_mensaje'] = 'error';
            header('Location: ?accion=tareas');
            exit();
        }
        
        $id = $_POST['id'] ?? 0;
        $horas = $_POST['horas_reales'] ?? 0;
        
        if (!$id || !is_numeric($horas)) {
            $_SESSION['mensaje'] = 'Datos inválidos';
            $_SESSION['tipo_mensaje'] = 'error';
            header('Location: ?accion=tareas');
            exit();
        }
        
        $resultado = $this->model->actualizarHorasReales($id, $horas);
        
        if ($resultado) {
            $_SESSION['mensaje'] = 'Horas actualizadas correctamente';
            $_SESSION['tipo_mensaje'] = 'success';
        } else {
            $_SESSION['mensaje'] = 'Error al actualizar las horas';
            $_SESSION['tipo_mensaje'] = 'error';
        }
        
        header('Location: ?accion=tareas');
        exit();
    }
    
    private function cambiarEstadoTarea() {
        // Verificar permisos
        if ($this->usuario['rol'] != 'gerente' && $this->usuario['rol'] != 'administrador') {
            $_SESSION['mensaje'] = 'No tienes permisos para cambiar estados';
            $_SESSION['tipo_mensaje'] = 'error';
            header('Location: ?accion=tareas');
            exit();
        }
        
        $id = $_POST['id'] ?? 0;
        $estado = $_POST['estado'] ?? '';
        
        if (!$id || !$estado) {
            $_SESSION['mensaje'] = 'Datos incompletos';
            $_SESSION['tipo_mensaje'] = 'error';
            header('Location: ?accion=tareas');
            exit();
        }
        
        $tarea = $this->model->obtenerTareaPorId($id);
        if (!$tarea) {
            $_SESSION['mensaje'] = 'Tarea no encontrada';
            $_SESSION['tipo_mensaje'] = 'error';
            header('Location: ?accion=tareas');
            exit();
        }
        
        // Actualizar tarea con el nuevo estado
        $datos = [
            'descripcion_tarea' => $tarea['descripcion_tarea'],
            'fecha_limite' => $tarea['fecha_limite'],
            'horas_estimadas' => $tarea['horas_estimadas'],
            'horas_reales' => $tarea['horas_reales'],
            'prioridad' => $tarea['prioridad'],
            'estado' => $estado,
            'porcentaje_completado' => $tarea['porcentaje_completado']
        ];
        
        $resultado = $this->model->actualizarTarea($id, $datos);
        
        if ($resultado) {
            $_SESSION['mensaje'] = 'Estado actualizado correctamente';
            $_SESSION['tipo_mensaje'] = 'success';
        } else {
            $_SESSION['mensaje'] = 'Error al actualizar el estado';
            $_SESSION['tipo_mensaje'] = 'error';
        }
        
        header('Location: ?accion=tareas');
        exit();
    }
    
    private function crearComunicacion() {
        // Todos los usuarios pueden crear comunicaciones
        
        // Validar datos
        $errores = $this->validarDatosComunicacion($_POST);
        
        if (!empty($errores)) {
            $_SESSION['errores'] = $errores;
            $_SESSION['datos_form'] = $_POST;
            header('Location: ?accion=crear_comunicacion');
            exit();
        }
        
        // Preparar datos
        $datos = [
            'proyecto_id' => $_POST['proyecto_id'],
            'emisor_id' => $this->usuario['id'],
            'receptor_id' => !empty($_POST['receptor_id']) ? $_POST['receptor_id'] : null,
            'tipo' => $_POST['tipo'],
            'asunto' => $_POST['asunto'],
            'mensaje' => $_POST['mensaje'],
            'prioridad' => $_POST['prioridad'] ?? 'normal',
            'leido' => 0
        ];
        
        // Crear comunicación
        $resultado = $this->model->crearComunicacion($datos);
        
        if ($resultado) {
            $_SESSION['mensaje'] = 'Comunicación enviada exitosamente';
            $_SESSION['tipo_mensaje'] = 'success';
            
            // Redirigir según el botón presionado
            if (isset($_POST['enviar_y_otra'])) {
                header('Location: ?accion=crear_comunicacion');
            } else {
                header('Location: ?accion=comunicaciones');
            }
        } else {
            $_SESSION['mensaje'] = 'Error al enviar la comunicación';
            $_SESSION['tipo_mensaje'] = 'error';
            $_SESSION['datos_form'] = $_POST;
            header('Location: ?accion=crear_comunicacion');
        }
        exit();
    }
    
    private function actualizarComunicacion() {
        // Verificar permisos (solo el emisor o admin pueden actualizar)
        $id = $_POST['id'] ?? 0;
        
        if (!$id) {
            $_SESSION['mensaje'] = 'ID de comunicación no válido';
            $_SESSION['tipo_mensaje'] = 'error';
            header('Location: ?accion=comunicaciones');
            exit();
        }
        
        $comunicacion = $this->model->obtenerComunicacionPorId($id);
        if (!$comunicacion) {
            $_SESSION['mensaje'] = 'Comunicación no encontrada';
            $_SESSION['tipo_mensaje'] = 'error';
            header('Location: ?accion=comunicaciones');
            exit();
        }
        
        // Verificar permisos
        if ($this->usuario['rol'] != 'administrador' && $comunicacion['emisor_id'] != $this->usuario['id']) {
            $_SESSION['mensaje'] = 'No tienes permisos para actualizar esta comunicación';
            $_SESSION['tipo_mensaje'] = 'error';
            header('Location: ?accion=comunicaciones');
            exit();
        }
        
        // Validar datos
        $errores = $this->validarDatosComunicacion($_POST);
        
        if (!empty($errores)) {
            $_SESSION['errores'] = $errores;
            header("Location: ?accion=editar_comunicacion&id={$id}");
            exit();
        }
        
        // Preparar datos
        $datos = [
            'tipo' => $_POST['tipo'],
            'asunto' => $_POST['asunto'],
            'mensaje' => $_POST['mensaje'],
            'prioridad' => $_POST['prioridad'],
            'leido' => $comunicacion['leido'] // Mantener estado de lectura
        ];
        
        // Actualizar comunicación
        $resultado = $this->model->actualizarComunicacion($id, $datos);
        
        if ($resultado) {
            $_SESSION['mensaje'] = 'Comunicación actualizada exitosamente';
            $_SESSION['tipo_mensaje'] = 'success';
            header('Location: ?accion=comunicaciones');
        } else {
            $_SESSION['mensaje'] = 'Error al actualizar la comunicación';
            $_SESSION['tipo_mensaje'] = 'error';
            header("Location: ?accion=editar_comunicacion&id={$id}");
        }
        exit();
    }
    
    private function eliminarComunicacion() {
        // Verificar permisos (solo el emisor o admin pueden eliminar)
        $id = $_POST['id'] ?? 0;
        
        if (!$id) {
            $_SESSION['mensaje'] = 'ID de comunicación no válido';
            $_SESSION['tipo_mensaje'] = 'error';
            header('Location: ?accion=comunicaciones');
            exit();
        }
        
        $comunicacion = $this->model->obtenerComunicacionPorId($id);
        if (!$comunicacion) {
            $_SESSION['mensaje'] = 'Comunicación no encontrada';
            $_SESSION['tipo_mensaje'] = 'error';
            header('Location: ?accion=comunicaciones');
            exit();
        }
        
        // Verificar permisos
        if ($this->usuario['rol'] != 'administrador' && $comunicacion['emisor_id'] != $this->usuario['id']) {
            $_SESSION['mensaje'] = 'No tienes permisos para eliminar esta comunicación';
            $_SESSION['tipo_mensaje'] = 'error';
            header('Location: ?accion=comunicaciones');
            exit();
        }
        
        $resultado = $this->model->eliminarComunicacion($id);
        
        if ($resultado) {
            $_SESSION['mensaje'] = 'Comunicación eliminada correctamente';
            $_SESSION['tipo_mensaje'] = 'success';
        } else {
            $_SESSION['mensaje'] = 'Error al eliminar la comunicación';
            $_SESSION['tipo_mensaje'] = 'error';
        }
        
        header('Location: ?accion=comunicaciones');
        exit();
    }
    
    private function marcarComunicacionComoLeida() {
        $id = $_POST['id'] ?? 0;
        
        if (!$id) {
            $_SESSION['mensaje'] = 'ID de comunicación no válido';
            $_SESSION['tipo_mensaje'] = 'error';
            header('Location: ?accion=comunicaciones');
            exit();
        }
        
        // Verificar que la comunicación es para este usuario o es general
        $comunicacion = $this->model->obtenerComunicacionPorId($id);
        if (!$comunicacion) {
            $_SESSION['mensaje'] = 'Comunicación no encontrada';
            $_SESSION['tipo_mensaje'] = 'error';
            header('Location: ?accion=comunicaciones');
            exit();
        }
        
        // Solo puede marcar como leída si es el receptor o si la comunicación es general (receptor_id IS NULL)
        if ($comunicacion['receptor_id'] && $comunicacion['receptor_id'] != $this->usuario['id']) {
            $_SESSION['mensaje'] = 'No puedes marcar esta comunicación como leída';
            $_SESSION['tipo_mensaje'] = 'error';
            header('Location: ?accion=comunicaciones');
            exit();
        }
        
        $resultado = $this->model->marcarComoLeida($id);
        
        if ($resultado) {
            $_SESSION['mensaje'] = 'Comunicación marcada como leída';
            $_SESSION['tipo_mensaje'] = 'success';
        } else {
            $_SESSION['mensaje'] = 'Error al marcar la comunicación';
            $_SESSION['tipo_mensaje'] = 'error';
        }
        
        header('Location: ?accion=comunicaciones');
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
        
        error_log("DEBUG obtenerDatos: Acción = " . $accion);
        error_log("DEBUG obtenerDatos: usuario_id = " . $datos_vista['usuario_id']);
        error_log("DEBUG obtenerDatos: usuario_rol = " . $datos_vista['usuario_rol']);
            
        switch ($accion) {
            case 'dashboard':
                $proyecto_id = $_GET['proyecto_id'] ?? null;
                
                error_log("DEBUG obtenerDatos: Llamando obtenerDashboard con proyecto_id = " . ($proyecto_id ?? 'null'));
                
                // Obtener dashboard
                $datos_vista['dashboard'] = $this->model->obtenerDashboard($proyecto_id);
                
                // DEBUG detallado
                if (isset($datos_vista['dashboard'])) {
                    error_log("DEBUG obtenerDatos: Dashboard obtenido - tipo: " . gettype($datos_vista['dashboard']));
                    error_log("DEBUG obtenerDatos: Dashboard es array: " . (is_array($datos_vista['dashboard']) ? 'SI' : 'NO'));
                    if (is_array($datos_vista['dashboard'])) {
                        error_log("DEBUG obtenerDatos: Keys del dashboard: " . implode(', ', array_keys($datos_vista['dashboard'])));
                    }
                } else {
                    error_log("DEBUG obtenerDatos: Dashboard NO obtenido - retornó null");
                }
                break;
                
            case 'tareas':
                $filtros = [];
                
                if (isset($_GET['proyecto_id']) && !empty($_GET['proyecto_id'])) {
                    $filtros['proyecto_id'] = $_GET['proyecto_id'];
                }
                
                if (isset($_GET['estado']) && !empty($_GET['estado'])) {
                    $filtros['estado'] = $_GET['estado'];
                }
                
                if (isset($_GET['prioridad']) && !empty($_GET['prioridad'])) {
                    $filtros['prioridad'] = $_GET['prioridad'];
                }
                
                if (isset($_GET['recurso_humano_id']) && !empty($_GET['recurso_humano_id'])) {
                    $filtros['recurso_humano_id'] = $_GET['recurso_humano_id'];
                }
                
                $datos_vista['tareas'] = $this->model->obtenerTareas($filtros);
                $datos_vista['estadisticas_tareas'] = $this->model->obtenerEstadisticasTareas();
                $datos_vista['tareas_proximas'] = $this->model->obtenerTareasProximasVencer(7);
                break;
                
            case 'crear_tarea':
                // Verificar permisos
                if ($this->usuario['rol'] != 'gerente' && $this->usuario['rol'] != 'administrador') {
                    $_SESSION['mensaje'] = 'No tienes permisos para crear tareas';
                    $_SESSION['tipo_mensaje'] = 'error';
                    header('Location: ?accion=tareas');
                    exit();
                }
                
                $datos_vista['recursos'] = $this->model->obtenerRecursosParaTareas();
                $datos_vista['prioridades'] = ['critica', 'alta', 'media', 'baja'];
                $datos_vista['estados'] = ['pendiente', 'en_progreso', 'revision', 'completada', 'atrasada'];
                
                // Si hay errores de validación previos
                if (isset($_SESSION['errores'])) {
                    $datos_vista['errores'] = $_SESSION['errores'];
                    $datos_vista['datos_form'] = $_SESSION['datos_form'];
                    unset($_SESSION['errores'], $_SESSION['datos_form']);
                }
                break;
                
            case 'ver_tarea':
                $id = $_GET['id'] ?? 0;
                
                if (!$id) {
                    $_SESSION['mensaje'] = 'ID de tarea no válido';
                    $_SESSION['tipo_mensaje'] = 'error';
                    header('Location: ?accion=tareas');
                    exit();
                }
                
                $tarea = $this->model->obtenerTareaPorId($id);
                
                if (!$tarea) {
                    $_SESSION['mensaje'] = 'Tarea no encontrada';
                    $_SESSION['tipo_mensaje'] = 'error';
                    header('Location: ?accion=tareas');
                    exit();
                }
                
                $datos_vista['tarea'] = $tarea;
                break;
                
            case 'editar_tarea':
                // Verificar permisos
                if ($this->usuario['rol'] != 'gerente' && $this->usuario['rol'] != 'administrador') {
                    $_SESSION['mensaje'] = 'No tienes permisos para editar tareas';
                    $_SESSION['tipo_mensaje'] = 'error';
                    header('Location: ?accion=tareas');
                    exit();
                }
                
                $id = $_GET['id'] ?? 0;
                
                if (!$id) {
                    $_SESSION['mensaje'] = 'ID de tarea no válido';
                    $_SESSION['tipo_mensaje'] = 'error';
                    header('Location: ?accion=tareas');
                    exit();
                }
                
                $tarea = $this->model->obtenerTareaPorId($id);
                
                if (!$tarea) {
                    $_SESSION['mensaje'] = 'Tarea no encontrada';
                    $_SESSION['tipo_mensaje'] = 'error';
                    header('Location: ?accion=tareas');
                    exit();
                }
                
                $datos_vista['tarea'] = $tarea;
                $datos_vista['recursos'] = $this->model->obtenerRecursosParaTareas($tarea['proyecto_id']);
                $datos_vista['prioridades'] = ['critica', 'alta', 'media', 'baja'];
                $datos_vista['estados'] = ['pendiente', 'en_progreso', 'revision', 'completada', 'atrasada'];
                
                // Si hay errores de validación previos
                if (isset($_SESSION['errores'])) {
                    $datos_vista['errores'] = $_SESSION['errores'];
                    unset($_SESSION['errores']);
                }
                break;
                
            case 'comunicaciones':
                $filtros = [];
                
                if (isset($_GET['proyecto_id']) && !empty($_GET['proyecto_id'])) {
                    $filtros['proyecto_id'] = $_GET['proyecto_id'];
                }
                
                if (isset($_GET['tipo']) && !empty($_GET['tipo'])) {
                    $filtros['tipo'] = $_GET['tipo'];
                }
                
                if (isset($_GET['prioridad']) && !empty($_GET['prioridad'])) {
                    $filtros['prioridad'] = $_GET['prioridad'];
                }
                
                // Para miembros del equipo, solo ver sus comunicaciones o generales
                if ($this->usuario['rol'] == 'miembro_equipo') {
                    $filtros['receptor_id'] = $this->usuario['id'];
                    // También incluir comunicaciones generales (receptor_id IS NULL)
                }
                
                $datos_vista['comunicaciones'] = $this->model->obtenerComunicaciones($filtros);
                $datos_vista['comunicaciones_no_leidas'] = $this->model->obtenerComunicacionesNoLeidas($this->usuario['id']);
                break;
                
            case 'crear_comunicacion':
                $datos_vista['tipos'] = ['email', 'reunion', 'reporte', 'notificacion', 'mensaje'];
                $datos_vista['prioridades'] = ['alta', 'normal', 'baja'];
                
                // Obtener usuarios para enviar mensajes (solo gerentes y admin pueden enviar a específicos)
                if ($this->usuario['rol'] == 'gerente' || $this->usuario['rol'] == 'administrador') {
                    $datos_vista['usuarios'] = $this->obtenerUsuariosParaComunicacion();
                }
                
                // Si hay errores de validación previos
                if (isset($_SESSION['errores'])) {
                    $datos_vista['errores'] = $_SESSION['errores'];
                    $datos_vista['datos_form'] = $_SESSION['datos_form'];
                    unset($_SESSION['errores'], $_SESSION['datos_form']);
                }
                break;
                
            case 'ver_comunicacion':
                $id = $_GET['id'] ?? 0;
                
                if (!$id) {
                    $_SESSION['mensaje'] = 'ID de comunicación no válido';
                    $_SESSION['tipo_mensaje'] = 'error';
                    header('Location: ?accion=comunicaciones');
                    exit();
                }
                
                $comunicacion = $this->model->obtenerComunicacionPorId($id);
                
                if (!$comunicacion) {
                    $_SESSION['mensaje'] = 'Comunicación no encontrada';
                    $_SESSION['tipo_mensaje'] = 'error';
                    header('Location: ?accion=comunicaciones');
                    exit();
                }
                
                // Verificar permisos de visualización
                if ($this->usuario['rol'] == 'miembro_equipo' && 
                    $comunicacion['receptor_id'] && 
                    $comunicacion['receptor_id'] != $this->usuario['id']) {
                    $_SESSION['mensaje'] = 'No tienes permisos para ver esta comunicación';
                    $_SESSION['tipo_mensaje'] = 'error';
                    header('Location: ?accion=comunicaciones');
                    exit();
                }
                
                $datos_vista['comunicacion'] = $comunicacion;
                
                // Marcar como leída si es el receptor
                if ($comunicacion['receptor_id'] == $this->usuario['id'] && !$comunicacion['leido']) {
                    $this->model->marcarComoLeida($id);
                }
                break;
                
            case 'editar_comunicacion':
                $id = $_GET['id'] ?? 0;
                
                if (!$id) {
                    $_SESSION['mensaje'] = 'ID de comunicación no válido';
                    $_SESSION['tipo_mensaje'] = 'error';
                    header('Location: ?accion=comunicaciones');
                    exit();
                }
                
                $comunicacion = $this->model->obtenerComunicacionPorId($id);
                
                if (!$comunicacion) {
                    $_SESSION['mensaje'] = 'Comunicación no encontrada';
                    $_SESSION['tipo_mensaje'] = 'error';
                    header('Location: ?accion=comunicaciones');
                    exit();
                }
                
                // Verificar permisos
                if ($this->usuario['rol'] != 'administrador' && $comunicacion['emisor_id'] != $this->usuario['id']) {
                    $_SESSION['mensaje'] = 'No tienes permisos para editar esta comunicación';
                    $_SESSION['tipo_mensaje'] = 'error';
                    header('Location: ?accion=comunicaciones');
                    exit();
                }
                
                $datos_vista['comunicacion'] = $comunicacion;
                $datos_vista['tipos'] = ['email', 'reunion', 'reporte', 'notificacion', 'mensaje'];
                $datos_vista['prioridades'] = ['alta', 'normal', 'baja'];
                
                // Obtener usuarios para enviar mensajes
                if ($this->usuario['rol'] == 'gerente' || $this->usuario['rol'] == 'administrador') {
                    $datos_vista['usuarios'] = $this->obtenerUsuariosParaComunicacion();
                }
                
                // Si hay errores de validación previos
                if (isset($_SESSION['errores'])) {
                    $datos_vista['errores'] = $_SESSION['errores'];
                    unset($_SESSION['errores']);
                }
                break;
                
            case 'reportes':
                $proyecto_id = $_GET['proyecto_id'] ?? null;
                $periodo = $_GET['periodo'] ?? 'mes_actual';
                
                // Obtener estadísticas completas
                $datos_vista['estadisticas'] = $this->model->obtenerEstadisticasTareas($proyecto_id);
                $datos_vista['carga_trabajo'] = $this->model->obtenerCargaTrabajoPorRecurso($proyecto_id);
                $datos_vista['tareas_proximas'] = $this->model->obtenerTareasProximasVencer(14);
                break;
                
            default:
                // Por defecto, dashboard
                $proyecto_id = $_GET['proyecto_id'] ?? null;
                error_log("DEBUG obtenerDatos: DEFAULT - Llamando obtenerDashboard");
                $datos_vista['dashboard'] = $this->model->obtenerDashboard($proyecto_id);
                error_log("DEBUG obtenerDatos: DEFAULT - Dashboard obtenido: " . (isset($datos_vista['dashboard']) ? 'SI' : 'NO'));
        }
        
        error_log("DEBUG obtenerDatos: Finalizado - accion: " . $accion . ", dashboard en datos_vista: " . (isset($datos_vista['dashboard']) ? 'SI' : 'NO'));
        return $datos_vista;
    }
    
    private function obtenerUsuariosParaComunicacion() {
        $sql = "SELECT id, nombre, email, rol 
                FROM usuarios 
                WHERE activo = 1 
                ORDER BY nombre";
        
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error obtenerUsuariosParaComunicacion: " . $e->getMessage());
            return [];
        }
    }
    
    private function validarDatosTarea($datos) {
        $errores = [];

        // Validar proyecto_id
        if (empty($datos['proyecto_id'])) {
            $errores['proyecto_id'] = 'Debe seleccionar un proyecto';
        }

        // Validar recurso_humano_id
        if (empty($datos['recurso_humano_id'])) {
            $errores['recurso_humano_id'] = 'Debe seleccionar un recurso';
        }

        // Validar descripcion_tarea
        if (empty(trim($datos['descripcion_tarea']))) {
            $errores['descripcion_tarea'] = 'La descripción de la tarea es obligatoria';
        } elseif (strlen(trim($datos['descripcion_tarea'])) < 5) {
            $errores['descripcion_tarea'] = 'La descripción debe tener al menos 5 caracteres';
        }

        // Validar fecha_limite
        if (empty($datos['fecha_limite'])) {
            $errores['fecha_limite'] = 'La fecha límite es obligatoria';
        } elseif (strtotime($datos['fecha_limite']) < strtotime(date('Y-m-d'))) {
            $errores['fecha_limite'] = 'La fecha límite no puede ser anterior a hoy';
        }

        // Validar horas_estimadas
        if (isset($datos['horas_estimadas']) && (!is_numeric($datos['horas_estimadas']) || $datos['horas_estimadas'] < 0)) {
            $errores['horas_estimadas'] = 'Las horas estimadas deben ser un número válido mayor o igual a 0';
        }

        // Validar horas_reales
        if (isset($datos['horas_reales']) && (!is_numeric($datos['horas_reales']) || $datos['horas_reales'] < 0)) {
            $errores['horas_reales'] = 'Las horas reales deben ser un número válido mayor o igual a 0';
        }

        // Validar porcentaje_completado
        if (isset($datos['porcentaje_completado']) && (!is_numeric($datos['porcentaje_completado']) || $datos['porcentaje_completado'] < 0 || $datos['porcentaje_completado'] > 100)) {
            $errores['porcentaje_completado'] = 'El porcentaje completado debe estar entre 0 y 100';
        }

        return $errores;
    }
    
    private function validarDatosComunicacion($datos) {
        $errores = [];

        // Validar proyecto_id
        if (empty($datos['proyecto_id'])) {
            $errores['proyecto_id'] = 'Debe seleccionar un proyecto';
        }

        // Validar tipo
        if (empty($datos['tipo']) || !in_array($datos['tipo'], ['email', 'reunion', 'reporte', 'notificacion', 'mensaje'])) {
            $errores['tipo'] = 'Debe seleccionar un tipo de comunicación válido';
        }

        // Validar asunto
        if (empty(trim($datos['asunto']))) {
            $errores['asunto'] = 'El asunto es obligatorio';
        } elseif (strlen(trim($datos['asunto'])) < 3) {
            $errores['asunto'] = 'El asunto debe tener al menos 3 caracteres';
        }

        // Validar mensaje
        if (empty(trim($datos['mensaje']))) {
            $errores['mensaje'] = 'El mensaje es obligatorio';
        } elseif (strlen(trim($datos['mensaje'])) < 10) {
            $errores['mensaje'] = 'El mensaje debe tener al menos 10 caracteres';
        }

        return $errores;
    }
}

// Ejecutar si es llamado directamente
if (basename(__FILE__) === basename($_SERVER['PHP_SELF'])) {
    $controller = new DirigirEquipoController();
    $controller->index();
}
?>
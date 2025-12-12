<?php
// Iniciar sesión SIEMPRE al principio
session_start();

// Incluir configuración y modelos
require_once '../config/database.php';
require_once '../models/UsuarioModel.php';

use Config\ConexionBD;
use App\Models\UsuarioModel;

// ============ VERIFICACIÓN DE ACCESO ============
function verificarSesion() {
    if (!isset($_SESSION['usuario'])) {
        header('Location: ../views/login.php');
        exit;
    }
}

// ============ PROCESAR LOGIN ============
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../views/login.php?error=metodo');
    exit;
}

// Obtener datos del formulario
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

// Validaciones básicas
if (empty($email) || empty($password)) {
    header('Location: ../views/login.php?error=vacio');
    exit;
}

// Verificar credenciales
$usuarioModel = new UsuarioModel();
$usuario = $usuarioModel->verificarLogin($email, $password);

if (!$usuario) {
    header('Location: ../views/login.php?error=credenciales');
    exit;
}

if ($usuario['activo'] != 1) {
    header('Location: ../views/login.php?error=inactivo');
    exit;
}

// Guardar en sesión (sin password)
unset($usuario['password']);
$_SESSION['usuario'] = $usuario;

// Actualizar último login
$usuarioModel->actualizarUltimoLogin($usuario['id']);

// Redirigir al dashboard
header('Location: ../views/modulos/dashboard.php');
exit;
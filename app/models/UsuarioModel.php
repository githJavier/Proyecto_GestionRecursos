<?php
namespace app\models;

use config\ConexionBD;

class UsuarioModel {
    
    private $db;
    
    public function __construct() {
        $conexion = ConexionBD::obtenerInstancia();
        $this->db = $conexion->obtenerConexion();
    }
    
    public function verificarLogin($email, $password) {
        $sql = "SELECT id, nombre, email, password, rol, activo 
                FROM usuarios 
                WHERE email = :email 
                LIMIT 1";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        $usuario = $stmt->fetch();
        
        if ($usuario && password_verify($password, $usuario['password'])) {
            // Quitar la contraseña del array antes de retornar
            unset($usuario['password']);
            return $usuario;
        }
        
        return false;
    }
    
    public function actualizarUltimoLogin($id) {
        $sql = "UPDATE usuarios SET ultimo_login = NOW() WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
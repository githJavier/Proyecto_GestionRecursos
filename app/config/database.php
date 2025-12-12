<?php
namespace Config;

use PDO;
use PDOException;

class ConexionBD {

    private static $instancia = null;
    private $conexion;

    private function __construct() {
        $host = "localhost";
        $bd   = "gestion_recursos_pmbok";
        $user = "root";
        $pass = "";

        try {
            $this->conexion = new PDO(
                "mysql:host=$host;dbname=$bd;charset=utf8mb4",
                $user,
                $pass,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]
            );
        } catch (PDOException $e) {
            // Error amigable para desarrollo
            if ($_SERVER['SERVER_NAME'] === 'localhost' || $_SERVER['SERVER_NAME'] === '127.0.0.1') {
                die("<div style='background:#f0f0f0;padding:20px;font-family:Arial;'>
                    <h3 style='color:#d32f2f;'>Error de Conexión a BD</h3>
                    <p><strong>Mensaje:</strong> {$e->getMessage()}</p>
                    <p><strong>Solución:</strong></p>
                    <ol>
                        <li>Verifica que MySQL esté corriendo</li>
                        <li>Base de datos: '$bd' debe existir</li>
                        <li>Usuario: '$user' con contraseña correcta</li>
                    </ol>
                    </div>");
            } else {
                die("Error de conexión con la base de datos.");
            }
        }
    }

    public static function obtenerInstancia() {
        if (self::$instancia === null) {
            self::$instancia = new self();
        }
        return self::$instancia;
    }

    public function obtenerConexion() {
        return $this->conexion;
    }

    // Método simple para ejecutar consultas (opcional, por si lo necesitas)
    public function ejecutar($sql, $params = []) {
        try {
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            error_log("Error SQL: " . $e->getMessage());
            return false;
        }
    }
}
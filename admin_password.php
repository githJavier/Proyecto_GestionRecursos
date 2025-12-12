<?php
// admin_password.php - Verificar o Insertar contraseña Admin

echo "=== HERRAMIENTA CONTRASEÑA ADMIN ===\n\n";

// Conexión directa a MySQL
$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'gestion_recursos_pmbok';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    
    echo "✅ Conexión a BD exitosa\n\n";
    
    // CONTRASEÑA POR DEFECTO
    $password_texto = 'Admin123';
    $password_hash = password_hash($password_texto, PASSWORD_DEFAULT);
    
    echo "🔑 Contraseña en texto: $password_texto\n";
    echo "🔒 Hash generado: $password_hash\n\n";
    
    // OPCIÓN 1: Verificar contraseña actual
    echo "=== VERIFICAR CONTRASEÑA ACTUAL ===\n";
    $sql_verificar = "SELECT id, email, password FROM usuarios WHERE email = 'admin@gestionrecursos.com' LIMIT 1";
    $stmt = $pdo->query($sql_verificar);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($admin) {
        echo "✅ Usuario admin encontrado:\n";
        echo "   ID: {$admin['id']}\n";
        echo "   Email: {$admin['email']}\n";
        echo "   Hash en BD: {$admin['password']}\n\n";
        
        // Verificar si la contraseña funciona
        if (password_verify($password_texto, $admin['password'])) {
            echo "🎉 ¡La contraseña 'Admin123' FUNCIONA correctamente!\n";
        } else {
            echo "❌ La contraseña 'Admin123' NO coincide con el hash en BD.\n";
            echo "   Necesitas actualizar la contraseña.\n\n";
            
            // OPCIÓN 2: Actualizar contraseña
            echo "=== ACTUALIZAR CONTRASEÑA ===\n";
            $sql_actualizar = "UPDATE usuarios SET password = :hash WHERE email = 'admin@gestionrecursos.com'";
            $stmt = $pdo->prepare($sql_actualizar);
            $stmt->execute([':hash' => $password_hash]);
            
            echo "✅ Contraseña actualizada exitosamente\n";
            echo "   Nuevo hash: $password_hash\n";
            echo "   Puedes loguearte con: admin@gestionrecursos.com / Admin123\n";
        }
    } else {
        echo "❌ Usuario admin NO encontrado en la BD\n";
        echo "   Creando usuario admin...\n";
        
        // Insertar usuario admin si no existe
        $sql_insertar = "INSERT INTO usuarios (nombre, email, password, rol) 
                        VALUES ('Administrador', 'admin@gestionrecursos.com', :hash, 'administrador')";
        $stmt = $pdo->prepare($sql_insertar);
        $stmt->execute([':hash' => $password_hash]);
        
        echo "✅ Usuario admin creado exitosamente\n";
        echo "   Email: admin@gestionrecursos.com\n";
        echo "   Password: Admin123\n";
        echo "   Hash: $password_hash\n";
    }
    
    echo "\n=== INSTRUCCIONES ===\n";
    echo "1. Usa estas credenciales para login:\n";
    echo "   📧 Email: admin@gestionrecursos.com\n";
    echo "   🔑 Password: Admin123\n";
    echo "2. Cambia la contraseña después del primer login\n";
    echo "3. Este archivo debe ser ELIMINADO después de usar\n";
    
} catch (PDOException $e) {
    echo "❌ Error de conexión: " . $e->getMessage() . "\n\n";
    echo "=== SOLUCIÓN RÁPIDA ===\n";
    echo "1. Asegúrate que MySQL esté corriendo\n";
    echo "2. Verifica usuario/contraseña MySQL\n";
    echo "3. Crea la BD 'gestion_recursos_pmbok' si no existe\n";
    echo "4. Ejecuta el script SQL de creación de tablas\n";
}

echo "\n=== HASH PARA COPIAR Y PEGAR EN SQL ===\n";
echo "Si necesitas insertar manualmente en MySQL:\n";
echo "UPDATE usuarios SET password = '" . password_hash('Admin123', PASSWORD_DEFAULT) . "' WHERE email = 'admin@gestionrecursos.com';\n";
?><?php
// admin_password.php - Verificar o Insertar contraseña Admin

echo "=== HERRAMIENTA CONTRASEÑA ADMIN ===\n\n";

// Conexión directa a MySQL
$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'gestion_recursos_pmbok';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    
    echo "✅ Conexión a BD exitosa\n\n";
    
    // CONTRASEÑA POR DEFECTO
    $password_texto = 'Admin123';
    $password_hash = password_hash($password_texto, PASSWORD_DEFAULT);
    
    echo "🔑 Contraseña en texto: $password_texto\n";
    echo "🔒 Hash generado: $password_hash\n\n";
    
    // OPCIÓN 1: Verificar contraseña actual
    echo "=== VERIFICAR CONTRASEÑA ACTUAL ===\n";
    $sql_verificar = "SELECT id, email, password FROM usuarios WHERE email = 'admin@gestionrecursos.com' LIMIT 1";
    $stmt = $pdo->query($sql_verificar);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($admin) {
        echo "✅ Usuario admin encontrado:\n";
        echo "   ID: {$admin['id']}\n";
        echo "   Email: {$admin['email']}\n";
        echo "   Hash en BD: {$admin['password']}\n\n";
        
        // Verificar si la contraseña funciona
        if (password_verify($password_texto, $admin['password'])) {
            echo "🎉 ¡La contraseña 'Admin123' FUNCIONA correctamente!\n";
        } else {
            echo "❌ La contraseña 'Admin123' NO coincide con el hash en BD.\n";
            echo "   Necesitas actualizar la contraseña.\n\n";
            
            // OPCIÓN 2: Actualizar contraseña
            echo "=== ACTUALIZAR CONTRASEÑA ===\n";
            $sql_actualizar = "UPDATE usuarios SET password = :hash WHERE email = 'admin@gestionrecursos.com'";
            $stmt = $pdo->prepare($sql_actualizar);
            $stmt->execute([':hash' => $password_hash]);
            
            echo "✅ Contraseña actualizada exitosamente\n";
            echo "   Nuevo hash: $password_hash\n";
            echo "   Puedes loguearte con: admin@gestionrecursos.com / Admin123\n";
        }
    } else {
        echo "❌ Usuario admin NO encontrado en la BD\n";
        echo "   Creando usuario admin...\n";
        
        // Insertar usuario admin si no existe
        $sql_insertar = "INSERT INTO usuarios (nombre, email, password, rol) 
                        VALUES ('Administrador', 'admin@gestionrecursos.com', :hash, 'administrador')";
        $stmt = $pdo->prepare($sql_insertar);
        $stmt->execute([':hash' => $password_hash]);
        
        echo "✅ Usuario admin creado exitosamente\n";
        echo "   Email: admin@gestionrecursos.com\n";
        echo "   Password: Admin123\n";
        echo "   Hash: $password_hash\n";
    }
    
    echo "\n=== INSTRUCCIONES ===\n";
    echo "1. Usa estas credenciales para login:\n";
    echo "   📧 Email: admin@gestionrecursos.com\n";
    echo "   🔑 Password: Admin123\n";
    echo "2. Cambia la contraseña después del primer login\n";
    echo "3. Este archivo debe ser ELIMINADO después de usar\n";
    
} catch (PDOException $e) {
    echo "❌ Error de conexión: " . $e->getMessage() . "\n\n";
    echo "=== SOLUCIÓN RÁPIDA ===\n";
    echo "1. Asegúrate que MySQL esté corriendo\n";
    echo "2. Verifica usuario/contraseña MySQL\n";
    echo "3. Crea la BD 'gestion_recursos_pmbok' si no existe\n";
    echo "4. Ejecuta el script SQL de creación de tablas\n";
}

echo "\n=== HASH PARA COPIAR Y PEGAR EN SQL ===\n";
echo "Si necesitas insertar manualmente en MySQL:\n";
echo "UPDATE usuarios SET password = '" . password_hash('Admin123', PASSWORD_DEFAULT) . "' WHERE email = 'admin@gestionrecursos.com';\n";
?>
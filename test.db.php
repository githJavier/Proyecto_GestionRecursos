<?php
// debug_recursos.php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: ../login');
    exit;
}

require_once __DIR__ . '/app/config/database.php';
use Config\ConexionBD;

$conexionBD = ConexionBD::obtenerInstancia();
$db = $conexionBD->obtenerConexion();

// Consulta de usuarios
$sqlUsuarios = "SELECT 
    u.*,
    COUNT(rh.id) as proyectos_actuales
FROM usuarios u
LEFT JOIN recursos_humanos rh ON u.id = rh.usuario_id
WHERE u.activo = 1 AND u.rol != 'administrador'
GROUP BY u.id
ORDER BY u.nombre";

$stmtUsuarios = $db->prepare($sqlUsuarios);
$stmtUsuarios->execute();
$usuarios = $stmtUsuarios->fetchAll(PDO::FETCH_ASSOC);

// Consulta de proyectos
$sqlProyectos = "SELECT 
    p.*,
    u.nombre as gerente_nombre,
    COUNT(rh.id) as recursos_asignados,
    DATEDIFF(p.fecha_fin_estimada, CURDATE()) as dias_restantes
FROM proyectos p
LEFT JOIN usuarios u ON p.gerente_id = u.id
LEFT JOIN recursos_humanos rh ON p.id = rh.proyecto_id
WHERE p.estado IN ('planificacion', 'en_ejecucion')
GROUP BY p.id
ORDER BY p.nombre";

$stmtProyectos = $db->prepare($sqlProyectos);
$stmtProyectos->execute();
$proyectos = $stmtProyectos->fetchAll(PDO::FETCH_ASSOC);

// Consulta de recursos humanos existentes
$sqlRecursos = "SELECT 
    rh.*,
    u.nombre as usuario_nombre,
    p.nombre as proyecto_nombre
FROM recursos_humanos rh
JOIN usuarios u ON rh.usuario_id = u.id
JOIN proyectos p ON rh.proyecto_id = p.id
ORDER BY p.nombre, u.nombre";

$stmtRecursos = $db->prepare($sqlRecursos);
$stmtRecursos->execute();
$recursosExistentes = $stmtRecursos->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Debug - Recursos</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .success { color: green; }
        .error { color: red; }
    </style>
</head>
<body class="p-8">
    <h1 class="text-2xl font-bold mb-6">Debug - Sistema de Recursos</h1>
    
    <div class="mb-8">
        <h2 class="text-xl font-semibold mb-4">📊 Estadísticas Generales</h2>
        <div class="grid grid-cols-3 gap-4 mb-4">
            <div class="bg-blue-50 p-4 rounded">
                <div class="text-sm text-gray-600">Usuarios Disponibles</div>
                <div class="text-2xl font-bold"><?php echo count($usuarios); ?></div>
            </div>
            <div class="bg-green-50 p-4 rounded">
                <div class="text-sm text-gray-600">Proyectos Activos</div>
                <div class="text-2xl font-bold"><?php echo count($proyectos); ?></div>
            </div>
            <div class="bg-yellow-50 p-4 rounded">
                <div class="text-sm text-gray-600">Recursos Asignados</div>
                <div class="text-2xl font-bold"><?php echo count($recursosExistentes); ?></div>
            </div>
        </div>
    </div>
    
    <div class="mb-8">
        <h2 class="text-xl font-semibold mb-4">👥 Usuarios Disponibles</h2>
        <?php if (empty($usuarios)): ?>
            <p class="text-red-600">❌ No se encontraron usuarios disponibles</p>
            <p class="text-gray-600">Verifica que:</p>
            <ul class="list-disc pl-5 text-gray-600">
                <li>Los usuarios tengan <code>activo = 1</code></li>
                <li>Los usuarios no sean administradores (<code>rol != 'administrador'</code>)</li>
            </ul>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Rol</th>
                            <th>Depto</th>
                            <th>Proyectos Actuales</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($usuarios as $usuario): ?>
                        <tr>
                            <td><?php echo $usuario['id']; ?></td>
                            <td><?php echo htmlspecialchars($usuario['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($usuario['email']); ?></td>
                            <td><?php echo htmlspecialchars($usuario['rol']); ?></td>
                            <td><?php echo htmlspecialchars($usuario['departamento'] ?? 'N/A'); ?></td>
                            <td><?php echo $usuario['proyectos_actuales']; ?></td>
                            <td><?php echo ($usuario['activo'] == 1) ? '✅ Activo' : '❌ Inactivo'; ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
    
    <div class="mb-8">
        <h2 class="text-xl font-semibold mb-4">📋 Proyectos Disponibles</h2>
        <?php if (empty($proyectos)): ?>
            <p class="text-red-600">❌ No se encontraron proyectos disponibles</p>
            <p class="text-gray-600">Verifica que:</p>
            <ul class="list-disc pl-5 text-gray-600">
                <li>Los proyectos estén en estado 'planificacion' o 'en_ejecucion'</li>
                <li>Existan proyectos en la tabla</li>
            </ul>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Estado</th>
                            <th>Gerente</th>
                            <th>Recursos Asignados</th>
                            <th>Días Restantes</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($proyectos as $proyecto): ?>
                        <tr>
                            <td><?php echo $proyecto['id']; ?></td>
                            <td><?php echo htmlspecialchars($proyecto['nombre']); ?></td>
                            <td>
                                <span class="px-2 py-1 rounded text-xs 
                                    <?php echo $proyecto['estado'] == 'en_ejecucion' ? 'bg-green-100 text-green-800' : 
                                            ($proyecto['estado'] == 'planificacion' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800'); ?>">
                                    <?php echo ucfirst($proyecto['estado']); ?>
                                </span>
                            </td>
                            <td><?php echo htmlspecialchars($proyecto['gerente_nombre'] ?? 'N/A'); ?></td>
                            <td><?php echo $proyecto['recursos_asignados']; ?></td>
                            <td><?php echo $proyecto['dias_restantes']; ?> días</td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
    
    <div class="mb-8">
        <h2 class="text-xl font-semibold mb-4">🔗 Recursos Humanos Asignados (Actuales)</h2>
        <?php if (empty($recursosExistentes)): ?>
            <p class="text-yellow-600">⚠️ No hay recursos asignados (esto está bien si es el inicio)</p>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Usuario</th>
                            <th>Proyecto</th>
                            <th>Rol en Proyecto</th>
                            <th>Nivel</th>
                            <th>Horas Asignadas</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($recursosExistentes as $recurso): ?>
                        <tr>
                            <td><?php echo $recurso['id']; ?></td>
                            <td><?php echo htmlspecialchars($recurso['usuario_nombre']); ?></td>
                            <td><?php echo htmlspecialchars($recurso['proyecto_nombre']); ?></td>
                            <td><?php echo htmlspecialchars($recurso['rol_proyecto']); ?></td>
                            <td><?php echo ucfirst($recurso['nivel_experiencia']); ?></td>
                            <td><?php echo $recurso['horas_asignadas']; ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
    
    <div class="bg-gray-100 p-6 rounded-lg">
        <h3 class="text-lg font-semibold mb-4">🔍 Consultas SQL Ejecutadas</h3>
        
        <div class="mb-4">
            <h4 class="font-medium mb-2">Consulta de Usuarios:</h4>
            <pre class="bg-gray-800 text-white p-3 rounded text-sm overflow-x-auto">
<?php echo htmlspecialchars($sqlUsuarios); ?></pre>
            <p class="text-sm text-gray-600 mt-2">Registros encontrados: <?php echo count($usuarios); ?></p>
        </div>
        
        <div class="mb-4">
            <h4 class="font-medium mb-2">Consulta de Proyectos:</h4>
            <pre class="bg-gray-800 text-white p-3 rounded text-sm overflow-x-auto">
<?php echo htmlspecialchars($sqlProyectos); ?></pre>
            <p class="text-sm text-gray-600 mt-2">Registros encontrados: <?php echo count($proyectos); ?></p>
        </div>
        
        <div>
            <h4 class="font-medium mb-2">Posibles Problemas:</h4>
            <ul class="list-disc pl-5 text-gray-700">
                <li>Usuarios inactivos (<code>activo != 1</code>)</li>
                <li>Usuarios con rol 'administrador'</li>
                <li>Proyectos no en estado 'planificacion' o 'en_ejecucion'</li>
                <li>Error de conexión a la base de datos</li>
                <li>Los métodos del Modelo tienen filtros muy restrictivos</li>
            </ul>
        </div>
    </div>
    
    <div class="mt-8 p-4 border-t">
        <a href="?accion=asignar" class="text-blue-600 hover:underline">← Volver a Asignar Recurso</a>
    </div>
</body>
</html>
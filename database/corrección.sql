-- =====================================================================
-- CORRECCIÓN DE LA BASE DE DATOS PARA DIRIGIR EQUIPO
-- =====================================================================

USE gestion_recursos_pmbok;

-- ============================================
-- 1. CORREGIR FECHAS (actualizar a 2025)
-- ============================================

-- Actualizar fechas de proyectos
UPDATE proyectos SET 
    fecha_inicio = DATE_ADD(fecha_inicio, INTERVAL 1 YEAR),
    fecha_fin_estimada = DATE_ADD(fecha_fin_estimada, INTERVAL 1 YEAR),
    created_at = DATE_ADD(created_at, INTERVAL 1 YEAR)
WHERE fecha_inicio < '2024-12-31';

-- Actualizar fechas de tareas
UPDATE asignacion_tareas SET 
    fecha_asignacion = DATE_ADD(fecha_asignacion, INTERVAL 1 YEAR),
    fecha_limite = DATE_ADD(fecha_limite, INTERVAL 1 YEAR);

-- Actualizar fechas de comunicaciones
UPDATE comunicaciones SET 
    fecha_envio = DATE_ADD(fecha_envio, INTERVAL 1 YEAR);

-- Actualizar fechas de capacitaciones
UPDATE capacitaciones SET 
    fecha_inicio = DATE_ADD(fecha_inicio, INTERVAL 1 YEAR),
    fecha_fin = DATE_ADD(fecha_fin, INTERVAL 1 YEAR);

-- Actualizar otras fechas
UPDATE adquisicion_recursos SET 
    fecha_orden = DATE_ADD(fecha_orden, INTERVAL 1 YEAR),
    fecha_entrega_estimada = DATE_ADD(fecha_entrega_estimada, INTERVAL 1 YEAR),
    fecha_entrega_real = DATE_ADD(fecha_entrega_real, INTERVAL 1 YEAR),
    created_at = DATE_ADD(created_at, INTERVAL 1 YEAR);

UPDATE estimacion_recursos SET 
    fecha_estimacion = DATE_ADD(fecha_estimacion, INTERVAL 1 YEAR);

UPDATE reportes_rendimiento SET 
    fecha_inicio = DATE_ADD(fecha_inicio, INTERVAL 1 YEAR),
    fecha_fin = DATE_ADD(fecha_fin, INTERVAL 1 YEAR),
    fecha_generacion = DATE_ADD(fecha_generacion, INTERVAL 1 YEAR);

UPDATE control_recursos SET 
    fecha_control = DATE_ADD(fecha_control, INTERVAL 1 YEAR);

-- ============================================
-- 2. CORREGIR IDs EN ASIGNACIÓN DE TAREAS
-- ============================================

-- Primero, verificar qué IDs de recursos_humanos existen
SELECT id, usuario_id, proyecto_id, rol_proyecto FROM recursos_humanos ORDER BY id;

-- Las tareas actuales usan IDs incorrectos. Vamos a reasignarlas:

-- Eliminar todas las tareas actuales (para recrearlas correctamente)
DELETE FROM asignacion_tareas;

-- Ahora insertar tareas con IDs CORRECTOS de recursos_humanos
-- Proyecto 1: IDs 1-4
INSERT INTO asignacion_tareas (proyecto_id, recurso_humano_id, descripcion_tarea, fecha_asignacion, fecha_limite, horas_estimadas, horas_reales, prioridad, estado, porcentaje_completado) VALUES
-- Carlos Rodríguez (ID 1)
(1, 1, 'Desarrollo del módulo de autenticación con OAuth2', '2025-01-20', '2025-02-05', 40, 42, 'alta', 'completada', 100),
(1, 1, 'Implementación del sistema de notificaciones push', '2025-02-06', '2025-02-28', 35, 30, 'alta', 'en_progreso', 85),

-- Ana Martínez (ID 2)
(1, 2, 'Diseño de interfaz responsive del dashboard principal', '2025-01-22', '2025-02-10', 30, 28, 'media', 'completada', 100),
(1, 2, 'Creación de sistema de diseño y componentes UI', '2025-02-11', '2025-03-05', 40, 15, 'media', 'en_progreso', 40),

-- Luis Fernández (ID 3)
(1, 3, 'Testing integral del módulo de usuarios y roles', '2025-02-01', '2025-02-25', 25, 20, 'alta', 'en_progreso', 80),
(1, 3, 'Automatización de pruebas con Selenium', '2025-02-26', '2025-03-15', 30, 0, 'media', 'pendiente', 0),

-- Jorge Herrera (ID 4)
(1, 4, 'Configuración de servidores de producción AWS', '2025-01-25', '2025-02-15', 35, 40, 'critica', 'completada', 100),
(1, 4, 'Implementación de CI/CD con Docker y Jenkins', '2025-02-16', '2025-03-10', 45, 25, 'alta', 'en_progreso', 55),

-- Proyecto 2: IDs 5-7
-- Ana Martínez (ID 5)
(2, 5, 'Diseño de wireframes para portal web corporativo', '2025-02-05', '2025-02-25', 35, 38, 'alta', 'completada', 100),

-- Carlos Rodríguez (ID 6)
(2, 6, 'Desarrollo frontend con React y TypeScript', '2025-02-15', '2025-03-10', 50, 45, 'alta', 'en_progreso', 90),

-- Sofía Ramírez (ID 7)
(2, 7, 'Investigación de keywords y estrategia SEO', '2025-02-10', '2025-03-05', 30, 15, 'baja', 'en_progreso', 50),

-- Proyecto 3: IDs 8-9
-- Carlos Rodríguez (ID 8)
(3, 8, 'Investigación de frameworks móviles: React Native vs Flutter', '2025-03-12', '2025-03-30', 25, 30, 'media', 'completada', 100),

-- Luis Fernández (ID 9)
(3, 9, 'Plan de testing para aplicación móvil', '2025-03-15', '2025-04-05', 20, 5, 'media', 'en_progreso', 25),

-- Proyecto 4: IDs 10-11
-- Jorge Herrera (ID 10)
(4, 10, 'Migración de bases de datos a AWS RDS', '2025-01-22', '2025-02-15', 50, 55, 'critica', 'completada', 100),

-- Diego Castro (ID 11)
(4, 11, 'Configuración de balanceador de carga AWS ELB', '2025-02-05', '2025-02-28', 35, 40, 'alta', 'completada', 100),

-- Proyecto 5: IDs 12-13
-- Elena Ruiz (ID 12)
(5, 12, 'Análisis exploratorio de datos históricos de ventas', '2025-03-01', '2025-03-31', 60, 45, 'alta', 'en_progreso', 75),
(5, 12, 'Diseño de modelo predictivo con Machine Learning', '2025-04-01', '2025-04-25', 45, 0, 'alta', 'pendiente', 0),

-- Laura Torres (ID 13)
(5, 13, 'Análisis financiero y proyección de ROI', '2025-03-05', '2025-03-20', 30, 32, 'media', 'completada', 100);

-- ============================================
-- 3. AGREGAR MÁS COMUNICACIONES PARA PRUEBAS
-- ============================================

-- Agregar más comunicaciones de prueba
INSERT INTO comunicaciones (proyecto_id, emisor_id, receptor_id, tipo, asunto, mensaje, prioridad, fecha_envio) VALUES
-- Comunicaciones generales (sin receptor específico)
(1, 2, NULL, 'reunion', 'Reunión semanal de seguimiento', 'Agenda reunión para revisar avances del sprint actual.', 'alta', '2025-02-15 09:00:00'),
(2, 7, NULL, 'email', 'Actualización de requerimientos', 'Se han actualizado algunos requerimientos del portal web. Revisar documento adjunto.', 'normal', '2025-02-18 14:30:00'),

-- Comunicaciones específicas
(1, 3, 2, 'mensaje', 'Problema con módulo de autenticación', 'Encontré un bug en el login con Google OAuth. Necesito apoyo para resolverlo.', 'alta', '2025-02-16 11:15:00'),
(4, 10, 7, 'reporte', 'Reporte de migración completada', 'La migración a AWS se completó exitosamente. Adjunto reporte técnico.', 'normal', '2025-02-20 16:45:00'),
(5, 12, 2, 'notificacion', 'Resultados análisis inicial', 'Los primeros resultados del análisis de datos muestran tendencias positivas.', 'baja', '2025-03-02 10:20:00');

-- ============================================
-- 4. AGREGAR TAREAS ATRASADAS PARA PRUEBAS
-- ============================================

-- Crear algunas tareas atrasadas (fechas pasadas)
INSERT INTO asignacion_tareas (proyecto_id, recurso_humano_id, descripcion_tarea, fecha_asignacion, fecha_limite, horas_estimadas, horas_reales, prioridad, estado, porcentaje_completado) VALUES
-- Tareas atrasadas (fechas límite pasadas)
(1, 3, 'Documentación técnica del módulo API', '2025-01-15', '2025-02-01', 20, 0, 'media', 'pendiente', 0),
(2, 6, 'Optimización de rendimiento frontend', '2025-02-01', '2025-02-20', 25, 10, 'alta', 'en_progreso', 40),
(4, 11, 'Auditoría de seguridad AWS', '2025-01-30', '2025-02-15', 30, 5, 'critica', 'en_progreso', 15);

-- ============================================
-- 5. ACTUALIZAR ESTADOS SEGÚN PORCENTAJE
-- ============================================

-- Actualizar estados basados en porcentaje completado
UPDATE asignacion_tareas SET 
    estado = CASE 
        WHEN porcentaje_completado >= 100 THEN 'completada'
        WHEN porcentaje_completado > 0 AND porcentaje_completado < 100 THEN 'en_progreso'
        WHEN fecha_limite < CURDATE() THEN 'atrasada'
        ELSE 'pendiente'
    END;

-- ============================================
-- 6. VERIFICAR DATOS CORREGIDOS
-- ============================================

-- Verificar tareas por estado
SELECT 
    estado,
    COUNT(*) as cantidad,
    SUM(horas_estimadas) as horas_estimadas_total,
    SUM(horas_reales) as horas_reales_total,
    AVG(porcentaje_completado) as porcentaje_promedio
FROM asignacion_tareas 
GROUP BY estado 
ORDER BY 
    CASE estado 
        WHEN 'critica' THEN 1
        WHEN 'alta' THEN 2
        WHEN 'media' THEN 3
        WHEN 'baja' THEN 4
        ELSE 5
    END;

-- Verificar tareas por proyecto
SELECT 
    p.nombre as proyecto,
    COUNT(at.id) as total_tareas,
    COUNT(CASE WHEN at.estado = 'completada' THEN 1 END) as completadas,
    COUNT(CASE WHEN at.estado = 'en_progreso' THEN 1 END) as en_progreso,
    COUNT(CASE WHEN at.estado = 'pendiente' THEN 1 END) as pendientes,
    COUNT(CASE WHEN at.estado = 'atrasada' THEN 1 END) as atrasadas
FROM proyectos p
LEFT JOIN asignacion_tareas at ON p.id = at.proyecto_id
GROUP BY p.id, p.nombre
ORDER BY p.nombre;

-- Verificar comunicaciones
SELECT 
    tipo,
    COUNT(*) as cantidad,
    COUNT(CASE WHEN leido = 0 THEN 1 END) as no_leidas,
    COUNT(CASE WHEN receptor_id IS NULL THEN 1 END) as generales
FROM comunicaciones 
GROUP BY tipo;

-- ============================================
-- 7. ACTUALIZAR VISTAS PARA REFLEJAR CAMBIOS
-- ============================================

-- Eliminar y recrear vista de tareas pendientes
DROP VIEW IF EXISTS vista_tareas_pendientes;

CREATE VIEW vista_tareas_pendientes AS
SELECT 
    at.*,
    p.nombre as proyecto_nombre,
    u.nombre as recurso_nombre,
    u.email as recurso_email,
    rh.rol_proyecto,
    DATEDIFF(at.fecha_limite, CURDATE()) as dias_restantes
FROM asignacion_tareas at
JOIN proyectos p ON at.proyecto_id = p.id
JOIN recursos_humanos rh ON at.recurso_humano_id = rh.id
JOIN usuarios u ON rh.usuario_id = u.id
WHERE at.estado IN ('pendiente', 'en_progreso', 'atrasada')
ORDER BY 
    CASE at.prioridad 
        WHEN 'critica' THEN 1
        WHEN 'alta' THEN 2
        WHEN 'media' THEN 3
        WHEN 'baja' THEN 4
    END,
    at.fecha_limite ASC;

-- ============================================
-- 8. RESUMEN FINAL
-- ============================================

SELECT '=== RESUMEN DE CORRECCIONES APLICADAS ===' as mensaje;
SELECT '1. Fechas actualizadas a 2025' as correccion;
SELECT '2. IDs de recursos_humanos corregidos en asignacion_tareas' as correccion;
SELECT '3. Estados actualizados según porcentaje completado' as correccion;
SELECT '4. Tareas atrasadas agregadas para pruebas' as correccion;
SELECT '5. Más comunicaciones agregadas' as correccion;
SELECT '6. Vista actualizada' as correccion;

SELECT '=== DATOS ACTUALES ===' as mensaje;
SELECT CONCAT('Total tareas: ', COUNT(*)) as datos FROM asignacion_tareas;
SELECT CONCAT('Tareas completadas: ', COUNT(CASE WHEN estado = 'completada' THEN 1 END)) as datos FROM asignacion_tareas;
SELECT CONCAT('Tareas en progreso: ', COUNT(CASE WHEN estado = 'en_progreso' THEN 1 END)) as datos FROM asignacion_tareas;
SELECT CONCAT('Tareas pendientes: ', COUNT(CASE WHEN estado = 'pendiente' THEN 1 END)) as datos FROM asignacion_tareas;
SELECT CONCAT('Tareas atrasadas: ', COUNT(CASE WHEN estado = 'atrasada' THEN 1 END)) as datos FROM asignacion_tareas;
SELECT CONCAT('Total comunicaciones: ', COUNT(*)) as datos FROM comunicaciones;
SELECT CONCAT('Comunicaciones no leídas: ', COUNT(CASE WHEN leido = 0 THEN 1 END)) as datos FROM comunicaciones;
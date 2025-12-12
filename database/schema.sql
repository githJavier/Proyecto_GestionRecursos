-- =====================================================================
-- SISTEMA DE GESTIÓN DE RECURSOS - PMBOK 6
-- Base de datos completa con datos de prueba ampliados
-- =====================================================================

-- =====================================================================
-- CREAR BASE DE DATOS
-- =====================================================================
DROP DATABASE IF EXISTS gestion_recursos_pmbok;
CREATE DATABASE gestion_recursos_pmbok
  CHARACTER SET utf8mb4 
  COLLATE utf8mb4_unicode_ci;

USE gestion_recursos_pmbok;

-- ============================================
-- 1. TABLA DE USUARIOS (Login/Autenticación)
-- ============================================
CREATE TABLE usuarios (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    rol ENUM('administrador', 'gerente', 'miembro_equipo') DEFAULT 'miembro_equipo',
    departamento VARCHAR(100),
    fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP,
    ultimo_login DATETIME NULL,
    activo TINYINT(1) DEFAULT 1,
    INDEX idx_usuarios_email (email),
    INDEX idx_usuarios_rol (rol)
);

-- ============================================
-- 2. TABLAS PARA PROCESO 1: PLANIFICAR GESTIÓN
-- ============================================
CREATE TABLE proyectos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(200) NOT NULL,
    descripcion TEXT,
    fecha_inicio DATE,
    fecha_fin_estimada DATE,
    presupuesto_estimado DECIMAL(15,2) DEFAULT 0.00,
    estado ENUM('planificacion', 'en_ejecucion', 'en_pausa', 'completado', 'cancelado') DEFAULT 'planificacion',
    gerente_id INT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (gerente_id) REFERENCES usuarios(id) ON DELETE SET NULL,
    INDEX idx_proyectos_estado (estado),
    INDEX idx_proyectos_gerente (gerente_id)
);

CREATE TABLE planificacion_recursos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    proyecto_id INT NOT NULL,
    tipo_recurso ENUM('humano', 'material', 'equipo', 'financiero', 'tecnologico') NOT NULL,
    descripcion TEXT,
    cantidad_estimada INT DEFAULT 0,
    costo_unitario_estimado DECIMAL(15,2) DEFAULT 0.00,
    costo_total_estimado DECIMAL(15,2) DEFAULT 0.00,
    prioridad ENUM('alta', 'media', 'baja') DEFAULT 'media',
    fase_proyecto VARCHAR(100),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (proyecto_id) REFERENCES proyectos(id) ON DELETE CASCADE,
    INDEX idx_planificacion_proyecto (proyecto_id),
    INDEX idx_planificacion_tipo (tipo_recurso)
);

-- ============================================
-- 3. TABLAS PARA PROCESO 2: ESTIMAR RECURSOS
-- ============================================
CREATE TABLE estimacion_recursos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    planificacion_id INT NOT NULL,
    estimador_id INT NOT NULL,
    cantidad_real INT DEFAULT 0,
    costo_real_unitario DECIMAL(15,2) DEFAULT 0.00,
    costo_real_total DECIMAL(15,2) DEFAULT 0.00,
    metodo_estimacion VARCHAR(100),
    nivel_confianza ENUM('alto', 'medio', 'bajo'),
    observaciones TEXT,
    fecha_estimacion DATE DEFAULT (CURRENT_DATE),
    FOREIGN KEY (planificacion_id) REFERENCES planificacion_recursos(id) ON DELETE CASCADE,
    FOREIGN KEY (estimador_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    INDEX idx_estimacion_planificacion (planificacion_id)
);

-- ============================================
-- 4. TABLAS PARA PROCESO 3: ADQUIRIR RECURSOS
-- ============================================
CREATE TABLE adquisicion_recursos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    estimacion_id INT NOT NULL,
    proveedor VARCHAR(200),
    metodo_adquisicion VARCHAR(100),
    fecha_orden DATE,
    fecha_entrega_estimada DATE,
    fecha_entrega_real DATE NULL,
    costo_adquisicion DECIMAL(15,2) DEFAULT 0.00,
    estado ENUM('pendiente', 'ordenado', 'entregado', 'cancelado') DEFAULT 'pendiente',
    contrato_ref VARCHAR(100),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (estimacion_id) REFERENCES estimacion_recursos(id) ON DELETE CASCADE,
    INDEX idx_adquisicion_estado (estado)
);

-- ============================================
-- 5. TABLAS PARA PROCESO 4: DESARROLLAR EQUIPO
-- ============================================
CREATE TABLE recursos_humanos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    usuario_id INT NOT NULL,
    proyecto_id INT NOT NULL,
    rol_proyecto VARCHAR(100),
    habilidades TEXT,
    capacitacion_requerida TEXT,
    nivel_experiencia ENUM('junior', 'intermedio', 'senior', 'experto') DEFAULT 'intermedio',
    fecha_asignacion DATE DEFAULT (CURRENT_DATE),
    horas_asignadas INT DEFAULT 0,
    horas_realizadas INT DEFAULT 0,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (proyecto_id) REFERENCES proyectos(id) ON DELETE CASCADE,
    UNIQUE KEY unique_asignacion (usuario_id, proyecto_id),
    INDEX idx_recursos_proyecto (proyecto_id),
    INDEX idx_recursos_usuario (usuario_id)
);

CREATE TABLE capacitaciones (
    id INT PRIMARY KEY AUTO_INCREMENT,
    recurso_humano_id INT NOT NULL,
    tipo_capacitacion VARCHAR(100),
    descripcion TEXT,
    duracion_horas INT DEFAULT 0,
    fecha_inicio DATE,
    fecha_fin DATE,
    estado ENUM('pendiente', 'en_curso', 'completada', 'cancelada') DEFAULT 'pendiente',
    costo DECIMAL(15,2) DEFAULT 0.00,
    certificacion VARCHAR(200),
    FOREIGN KEY (recurso_humano_id) REFERENCES recursos_humanos(id) ON DELETE CASCADE,
    INDEX idx_capacitacion_estado (estado)
);

-- ============================================
-- 6. TABLAS PARA PROCESO 5: DIRIGIR EQUIPO
-- ============================================
CREATE TABLE asignacion_tareas (
    id INT PRIMARY KEY AUTO_INCREMENT,
    proyecto_id INT NOT NULL,
    recurso_humano_id INT NOT NULL,
    descripcion_tarea TEXT NOT NULL,
    fecha_asignacion DATE DEFAULT (CURRENT_DATE),
    fecha_limite DATE,
    horas_estimadas INT DEFAULT 0,
    horas_reales INT DEFAULT 0,
    prioridad ENUM('critica', 'alta', 'media', 'baja') DEFAULT 'media',
    estado ENUM('pendiente', 'en_progreso', 'revision', 'completada', 'atrasada') DEFAULT 'pendiente',
    porcentaje_completado INT DEFAULT 0,
    CHECK (porcentaje_completado BETWEEN 0 AND 100),
    FOREIGN KEY (proyecto_id) REFERENCES proyectos(id) ON DELETE CASCADE,
    FOREIGN KEY (recurso_humano_id) REFERENCES recursos_humanos(id) ON DELETE CASCADE,
    INDEX idx_tareas_estado (estado),
    INDEX idx_tareas_prioridad (prioridad)
);

CREATE TABLE comunicaciones (
    id INT PRIMARY KEY AUTO_INCREMENT,
    proyecto_id INT NOT NULL,
    emisor_id INT NOT NULL,
    receptor_id INT,
    tipo ENUM('email', 'reunion', 'reporte', 'notificacion', 'mensaje') DEFAULT 'mensaje',
    asunto VARCHAR(200),
    mensaje TEXT,
    fecha_envio DATETIME DEFAULT CURRENT_TIMESTAMP,
    leido TINYINT(1) DEFAULT 0,
    prioridad ENUM('alta', 'normal', 'baja') DEFAULT 'normal',
    FOREIGN KEY (proyecto_id) REFERENCES proyectos(id) ON DELETE CASCADE,
    FOREIGN KEY (emisor_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (receptor_id) REFERENCES usuarios(id) ON DELETE SET NULL,
    INDEX idx_comunicacion_proyecto (proyecto_id)
);

-- ============================================
-- 7. TABLAS PARA PROCESO 6: CONTROLAR RECURSOS
-- ============================================
CREATE TABLE control_recursos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    proyecto_id INT NOT NULL,
    recurso_id INT,
    tipo_recurso ENUM('humano', 'material', 'equipo', 'financiero', 'tecnologico'),
    tabla_referencia VARCHAR(50),
    metrica VARCHAR(100),
    valor_planificado DECIMAL(15,2) DEFAULT 0.00,
    valor_actual DECIMAL(15,2) DEFAULT 0.00,
    variacion DECIMAL(15,2) DEFAULT 0.00,
    fecha_control DATE DEFAULT (CURRENT_DATE),
    desviacion TEXT,
    accion_correctiva TEXT,
    responsable_id INT,
    FOREIGN KEY (proyecto_id) REFERENCES proyectos(id) ON DELETE CASCADE,
    FOREIGN KEY (responsable_id) REFERENCES usuarios(id) ON DELETE SET NULL,
    INDEX idx_control_proyecto (proyecto_id),
    INDEX idx_control_fecha (fecha_control)
);

CREATE TABLE reportes_rendimiento (
    id INT PRIMARY KEY AUTO_INCREMENT,
    proyecto_id INT NOT NULL,
    periodo ENUM('semanal', 'mensual', 'trimestral', 'anual') DEFAULT 'mensual',
    fecha_inicio DATE,
    fecha_fin DATE,
    eficiencia_recursos DECIMAL(5,2) DEFAULT 0.00,
    cumplimiento_plazos DECIMAL(5,2) DEFAULT 0.00,
    variacion_presupuesto DECIMAL(15,2) DEFAULT 0.00,
    productividad_equipo DECIMAL(5,2) DEFAULT 0.00,
    observaciones TEXT,
    generado_por INT,
    fecha_generacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (proyecto_id) REFERENCES proyectos(id) ON DELETE CASCADE,
    FOREIGN KEY (generado_por) REFERENCES usuarios(id) ON DELETE SET NULL,
    INDEX idx_reportes_proyecto (proyecto_id)
);

-- =====================================================================
-- DATOS INICIALES DEL SISTEMA - AMPLIADOS
-- =====================================================================

-- ============================================
-- 1. USUARIOS (12 usuarios de prueba)
-- ============================================
INSERT INTO usuarios (nombre, email, password, rol, departamento) VALUES 
('Administrador Sistema', 'admin@gestionrecursos.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'administrador', 'TI'),
('María González', 'maria.gerente@gestionrecursos.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'gerente', 'Gestión de Proyectos'),
('Carlos Rodríguez', 'carlos.desarrollo@gestionrecursos.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'miembro_equipo', 'Desarrollo'),
('Ana Martínez', 'ana.diseno@gestionrecursos.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'miembro_equipo', 'Diseño'),
('Luis Fernández', 'luis.qa@gestionrecursos.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'miembro_equipo', 'QA'),
('Sofía Ramírez', 'sofia.marketing@gestionrecursos.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'miembro_equipo', 'Marketing'),
('Pedro Sánchez', 'pedro.gerente2@gestionrecursos.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'gerente', 'Operaciones'),
('Laura Torres', 'laura.finanzas@gestionrecursos.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'miembro_equipo', 'Finanzas'),
('Jorge Herrera', 'jorge.devops@gestionrecursos.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'miembro_equipo', 'DevOps'),
('Mónica Vega', 'monica.rh@gestionrecursos.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'miembro_equipo', 'Recursos Humanos'),
('Diego Castro', 'diego.soporte@gestionrecursos.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'miembro_equipo', 'Soporte'),
('Elena Ruiz', 'elena.analista@gestionrecursos.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'miembro_equipo', 'Análisis de Datos');

-- ============================================
-- 2. PROYECTOS (5 proyectos de prueba)
-- ============================================
INSERT INTO proyectos (nombre, descripcion, fecha_inicio, fecha_fin_estimada, presupuesto_estimado, estado, gerente_id) VALUES
('Sistema de Gestión PMBOK', 'Implementación del sistema de gestión de recursos basado en PMBOK 6 para toda la organización', '2024-01-15', '2024-06-30', 125000.00, 'en_ejecucion', 2),
('Portal Web Corporativo', 'Desarrollo del portal web responsive con integración de CRM', '2024-02-01', '2024-04-30', 45000.00, 'en_ejecucion', 7),
('App Móvil de Ventas', 'Aplicación móvil para gestión de ventas y pedidos en campo', '2024-03-10', '2024-08-15', 85000.00, 'planificacion', 2),
('Migración a la Nube', 'Migración de infraestructura local a servicios cloud AWS', '2024-01-20', '2024-05-20', 65000.00, 'en_ejecucion', 7),
('Sistema de BI', 'Implementación de sistema de Business Intelligence con dashboards', '2024-04-01', '2024-09-30', 95000.00, 'planificacion', 2);

-- ============================================
-- 3. PLANIFICACIÓN DE RECURSOS (20 registros)
-- ============================================
INSERT INTO planificacion_recursos (proyecto_id, tipo_recurso, descripcion, cantidad_estimada, costo_unitario_estimado, costo_total_estimado, prioridad, fase_proyecto) VALUES
-- Proyecto 1
(1, 'humano', 'Desarrollador PHP Senior', 3, 5500.00, 16500.00, 'alta', 'Desarrollo'),
(1, 'humano', 'Analista de Sistemas', 2, 4500.00, 9000.00, 'alta', 'Análisis'),
(1, 'tecnologico', 'Servidores Cloud AWS', 1, 3500.00, 3500.00, 'alta', 'Infraestructura'),
(1, 'material', 'Laptops de desarrollo', 5, 1800.00, 9000.00, 'media', 'Equipamiento'),
(1, 'financiero', 'Licencias de software', 1, 8000.00, 8000.00, 'media', 'Adquisiciones'),

-- Proyecto 2
(2, 'humano', 'Diseñador UX/UI', 2, 4000.00, 8000.00, 'alta', 'Diseño'),
(2, 'humano', 'Desarrollador Frontend', 3, 5000.00, 15000.00, 'alta', 'Desarrollo'),
(2, 'tecnologico', 'Dominios y SSL', 1, 500.00, 500.00, 'baja', 'Configuración'),
(2, 'material', 'Monitores 4K', 3, 600.00, 1800.00, 'media', 'Equipamiento'),

-- Proyecto 3
(3, 'humano', 'Desarrollador React Native', 2, 6000.00, 12000.00, 'alta', 'Desarrollo'),
(3, 'humano', 'Tester QA', 1, 3500.00, 3500.00, 'media', 'Testing'),
(3, 'tecnologico', 'Servidores de producción', 1, 4500.00, 4500.00, 'alta', 'Producción'),

-- Proyecto 4
(4, 'humano', 'Ingeniero DevOps', 2, 6500.00, 13000.00, 'alta', 'Migración'),
(4, 'tecnologico', 'Servicios AWS EC2', 1, 2800.00, 2800.00, 'alta', 'Infraestructura'),
(4, 'tecnologico', 'Almacenamiento S3', 1, 1200.00, 1200.00, 'media', 'Almacenamiento'),

-- Proyecto 5
(5, 'humano', 'Analista de Datos', 3, 5200.00, 15600.00, 'alta', 'Análisis'),
(5, 'humano', 'Científico de Datos', 1, 7500.00, 7500.00, 'alta', 'Modelado'),
(5, 'tecnologico', 'Servidores BI', 1, 5200.00, 5200.00, 'alta', 'Infraestructura'),
(5, 'financiero', 'Licencias Power BI', 1, 9500.00, 9500.00, 'media', 'Licencias');

-- ============================================
-- 4. RECURSOS HUMANOS (asignaciones)
-- ============================================
INSERT INTO recursos_humanos (usuario_id, proyecto_id, rol_proyecto, habilidades, nivel_experiencia, horas_asignadas) VALUES
-- Proyecto 1
(3, 1, 'Líder Técnico', 'PHP, MySQL, Laravel, API Rest', 'senior', 160),
(4, 1, 'Diseñadora UI', 'Figma, Adobe XD, HTML/CSS', 'intermedio', 120),
(5, 1, 'QA Tester', 'Testing manual, Selenium, Jest', 'intermedio', 80),
(9, 1, 'DevOps Engineer', 'AWS, Docker, CI/CD', 'senior', 100),

-- Proyecto 2
(4, 2, 'Diseñadora Principal', 'UI/UX, Prototyping, User Research', 'senior', 140),
(3, 2, 'Desarrollador FullStack', 'React, Node.js, MongoDB', 'senior', 160),
(6, 2, 'Marketing Specialist', 'SEO, Content Strategy, Analytics', 'intermedio', 60),

-- Proyecto 3
(3, 3, 'Mobile Developer', 'React Native, Firebase, Redux', 'senior', 180),
(5, 3, 'QA Engineer', 'App Testing, Automation, Performance', 'senior', 100),

-- Proyecto 4
(9, 4, 'Cloud Architect', 'AWS, Terraform, Kubernetes', 'experto', 200),
(11, 4, 'Soporte Técnico', 'Networking, Linux, Troubleshooting', 'intermedio', 80),

-- Proyecto 5
(12, 5, 'Data Analyst', 'SQL, Python, Tableau', 'senior', 150),
(8, 5, 'Financial Analyst', 'Excel, Forecasting, Budgeting', 'intermedio', 100);

-- ============================================
-- 5. ESTIMACIÓN DE RECURSOS (10 registros)
-- ============================================
INSERT INTO estimacion_recursos (planificacion_id, estimador_id, cantidad_real, costo_real_unitario, costo_real_total, metodo_estimacion, nivel_confianza, observaciones) VALUES
(1, 2, 3, 5200.00, 15600.00, 'Estimación por analogía', 'medio', 'Basado en proyecto similar realizado el año pasado'),
(2, 2, 2, 4300.00, 8600.00, 'Juicio de expertos', 'alto', 'Consenso con el área de sistemas'),
(3, 7, 1, 3200.00, 3200.00, 'Paramétrica', 'alto', 'Costo mensual según proveedor AWS'),
(6, 7, 2, 3800.00, 7600.00, 'Juicio de expertos', 'medio', 'Contratación por outsourcing'),
(10, 2, 2, 5900.00, 11800.00, 'Estimación bottom-up', 'alto', 'Desglose por tareas específicas'),
(14, 7, 2, 6200.00, 12400.00, 'Paramétrica', 'medio', 'Costo según certificaciones AWS');

-- ============================================
-- 6. ADQUISICIÓN DE RECURSOS (8 registros)
-- ============================================
INSERT INTO adquisicion_recursos (estimacion_id, proveedor, metodo_adquisicion, fecha_orden, fecha_entrega_estimada, costo_adquisicion, estado, contrato_ref) VALUES
(3, 'Amazon Web Services', 'Contrato directo', '2024-01-18', '2024-01-25', 3200.00, 'entregado', 'AWS-CONT-2024-001'),
(1, 'Consultoría TechSoft', 'RFP (Request for Proposal)', '2024-01-20', '2024-02-15', 15600.00, 'ordenado', 'DEV-CONT-2024-003'),
(2, 'Analytics Pro S.A.', 'Contrato directo', '2024-01-22', '2024-02-10', 8600.00, 'entregado', 'ANA-CONT-2024-002'),
(4, 'Diseño Creativo Ltda.', 'Cotización directa', '2024-02-05', '2024-02-28', 7600.00, 'pendiente', 'DIS-CONT-2024-004'),
(5, 'Mobile Dev Solutions', 'Licitación', '2024-03-12', '2024-04-01', 11800.00, 'ordenado', 'MOB-CONT-2024-005'),
(6, 'Cloud Experts Inc.', 'Contrato directo', '2024-01-25', '2024-02-05', 12400.00, 'entregado', 'CLD-CONT-2024-006');

-- ============================================
-- 7. CAPACITACIONES (6 registros)
-- ============================================
INSERT INTO capacitaciones (recurso_humano_id, tipo_capacitacion, descripcion, duracion_horas, fecha_inicio, fecha_fin, estado, costo, certificacion) VALUES
(1, 'Certificación AWS', 'Curso de arquitectura de soluciones AWS', 40, '2024-02-01', '2024-02-29', 'completada', 1500.00, 'AWS Solutions Architect'),
(3, 'Testing Automation', 'Automation testing con Selenium y Java', 30, '2024-03-15', '2024-04-15', 'en_curso', 1200.00, 'Selenium Automation Tester'),
(5, 'Seguridad Informática', 'Curso de seguridad en aplicaciones web', 25, '2024-04-01', '2024-04-26', 'pendiente', 900.00, 'Web Security Specialist'),
(8, 'React Native Avanzado', 'Desarrollo móvil avanzado con React Native', 35, '2024-02-10', '2024-03-20', 'completada', 1800.00, 'React Native Developer'),
(10, 'Kubernetes Administration', 'Administración de clusters Kubernetes', 45, '2024-01-25', '2024-03-10', 'completada', 2200.00, 'CKA Certified');

-- ============================================
-- 8. ASIGNACIÓN DE TAREAS (15 registros)
-- ============================================
INSERT INTO asignacion_tareas (proyecto_id, recurso_humano_id, descripcion_tarea, fecha_asignacion, fecha_limite, horas_estimadas, prioridad, estado, porcentaje_completado) VALUES
-- Proyecto 1
(1, 1, 'Desarrollo del módulo de autenticación', '2024-01-20', '2024-02-05', 40, 'alta', 'completada', 100),
(1, 1, 'Implementación del CRUD de proyectos', '2024-02-06', '2024-02-20', 35, 'alta', 'en_progreso', 75),
(1, 2, 'Diseño de interfaz del dashboard', '2024-01-22', '2024-02-02', 25, 'media', 'completada', 100),
(1, 3, 'Testing del módulo de usuarios', '2024-02-10', '2024-02-25', 20, 'media', 'pendiente', 0),
(1, 4, 'Configuración del servidor de producción', '2024-01-25', '2024-02-15', 30, 'alta', 'en_progreso', 60),

-- Proyecto 2
(2, 5, 'Diseño de wireframes del portal', '2024-02-05', '2024-02-18', 30, 'alta', 'completada', 100),
(2, 6, 'Desarrollo del header responsive', '2024-02-15', '2024-02-28', 25, 'alta', 'en_progreso', 50),
(2, 7, 'Estrategia de SEO para el portal', '2024-02-10', '2024-03-10', 40, 'baja', 'pendiente', 0),

-- Proyecto 3
(3, 8, 'Investigación de tecnologías móviles', '2024-03-12', '2024-03-25', 20, 'media', 'en_progreso', 30),

-- Proyecto 4
(4, 9, 'Migración de base de datos a RDS', '2024-01-22', '2024-02-10', 45, 'critica', 'completada', 100),
(4, 10, 'Configuración de balanceador de carga', '2024-02-05', '2024-02-25', 30, 'alta', 'en_progreso', 70),

-- Proyecto 5
(5, 11, 'Análisis de datos históricos', '2024-03-01', '2024-03-31', 60, 'alta', 'pendiente', 0),
(5, 11, 'Diseño del modelo de datos', '2024-04-01', '2024-04-20', 40, 'alta', 'pendiente', 0);

-- ============================================
-- 9. COMUNICACIONES (8 registros)
-- ============================================
INSERT INTO comunicaciones (proyecto_id, emisor_id, receptor_id, tipo, asunto, mensaje, prioridad) VALUES
(1, 2, 1, 'email', 'Reunión de seguimiento Proyecto PMBOK', 'Necesitamos coordinar reunión para revisar avances del módulo de planificación.', 'alta'),
(1, 1, 2, 'mensaje', 'Avance módulo autenticación', 'He completado el módulo de autenticación con roles y permisos. ¿Podemos revisarlo?', 'normal'),
(2, 7, 5, 'reunion', 'Presentación diseño portal web', 'Agenda reunión para presentar los wireframes finales del portal.', 'alta'),
(4, 7, 9, 'reporte', 'Reporte migración AWS', 'Adjunto reporte de progreso de la migración a la nube.', 'normal'),
(1, 3, 2, 'notificacion', 'Testing completado', 'Se han completado las pruebas del módulo de usuarios sin errores críticos.', 'normal'),
(2, 6, 7, 'email', 'Consulta sobre integración CMS', '¿Qué CMS vamos a utilizar para el portal web? Necesito saber para la integración.', 'alta'),
(3, 2, 8, 'mensaje', 'Actualización plan proyecto móvil', 'He actualizado el cronograma del proyecto de app móvil. Revisa cuando puedas.', 'normal');

-- ============================================
-- 10. CONTROL DE RECURSOS (6 registros)
-- ============================================
INSERT INTO control_recursos (proyecto_id, recurso_id, tipo_recurso, tabla_referencia, metrica, valor_planificado, valor_actual, variacion, desviacion, accion_correctiva, responsable_id) VALUES
(1, 1, 'humano', 'planificacion_recursos', 'Costo desarrollo', 16500.00, 15600.00, -900.00, 'Costo menor al estimado', 'Ajustar presupuesto para otras áreas', 2),
(1, 3, 'tecnologico', 'planificacion_recursos', 'Costo servidores', 3500.00, 3200.00, -300.00, 'Descuento por pago anual', 'Mantener proveedor actual', 7),
(2, 6, 'humano', 'planificacion_recursos', 'Costo diseño', 8000.00, 7600.00, -400.00, 'Negociación exitosa con proveedor', 'Aprovechar ahorro para mejoras', 7),
(4, 14, 'tecnologico', 'planificacion_recursos', 'Costo migración', 13000.00, 12400.00, -600.00, 'Optimización en configuración', 'Revisar otras áreas para optimizar', 7),
(1, NULL, 'financiero', NULL, 'Presupuesto general', 125000.00, 118500.00, -6500.00, 'Ahorro en varias partidas', 'Redistribuir ahorro a contingencias', 2);

-- ============================================
-- 11. REPORTES DE RENDIMIENTO (4 registros)
-- ============================================
INSERT INTO reportes_rendimiento (proyecto_id, periodo, fecha_inicio, fecha_fin, eficiencia_recursos, cumplimiento_plazos, variacion_presupuesto, productividad_equipo, observaciones, generado_por) VALUES
(1, 'mensual', '2024-01-01', '2024-01-31', 85.50, 90.00, -5.20, 78.50, 'Buen progreso en módulos de planificación, retraso leve en testing', 2),
(2, 'mensual', '2024-02-01', '2024-02-29', 92.00, 85.00, -3.80, 88.00, 'Diseño avanzando según cronograma, desarrollo iniciado', 7),
(4, 'mensual', '2024-01-01', '2024-01-31', 88.50, 95.00, -4.60, 91.50, 'Migración en progreso, cumpliendo hitos clave', 7),
(1, 'semanal', '2024-02-05', '2024-02-11', 79.00, 75.00, -2.50, 72.00, 'Semana con retrasos por capacitaciones del equipo', 2);

-- =====================================================================
-- VISTAS ÚTILES PARA CONSULTAS FRECUENTES
-- =====================================================================
CREATE VIEW vista_proyectos_detalle AS
SELECT 
    p.*,
    u.nombre as gerente_nombre,
    u.email as gerente_email,
    COUNT(DISTINCT pr.id) as total_recursos_planificados,
    COUNT(DISTINCT rh.id) as total_recursos_humanos,
    SUM(pr.costo_total_estimado) as presupuesto_planificado_total
FROM proyectos p
LEFT JOIN usuarios u ON p.gerente_id = u.id
LEFT JOIN planificacion_recursos pr ON p.id = pr.proyecto_id
LEFT JOIN recursos_humanos rh ON p.id = rh.proyecto_id
GROUP BY p.id;

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
WHERE at.estado IN ('pendiente', 'en_progreso')
ORDER BY at.prioridad DESC, at.fecha_limite ASC;

CREATE VIEW vista_recursos_proyecto AS
SELECT 
    p.nombre as proyecto,
    pr.tipo_recurso,
    pr.descripcion,
    pr.cantidad_estimada,
    pr.costo_total_estimado,
    pr.prioridad,
    p.estado as estado_proyecto
FROM planificacion_recursos pr
JOIN proyectos p ON pr.proyecto_id = p.id
ORDER BY p.id, pr.prioridad DESC;

-- =====================================================================
-- RESUMEN DE DATOS INSERTADOS
-- =====================================================================
SELECT 'Datos de prueba insertados exitosamente:' as mensaje;
SELECT '12 usuarios' as item;
SELECT '5 proyectos' as item;
SELECT '20 planificaciones de recursos' as item;
SELECT '12 recursos humanos asignados' as item;
SELECT '6 estimaciones de recursos' as item;
SELECT '6 adquisiciones de recursos' as item;
SELECT '5 capacitaciones' as item;
SELECT '13 tareas asignadas' as item;
SELECT '7 comunicaciones' as item;
SELECT '5 controles de recursos' as item;
SELECT '4 reportes de rendimiento' as item;

-- =====================================================================
-- CREDENCIALES DE ACCESO
-- =====================================================================
SELECT '========================================' as separador;
SELECT 'CREDENCIALES DE PRUEBA:' as titulo;
SELECT '========================================' as separador;
SELECT 'Administrador: admin@gestionrecursos.com' as credencial;
SELECT 'Gerente: maria.gerente@gestionrecursos.com' as credencial;
SELECT 'Desarrollador: carlos.desarrollo@gestionrecursos.com' as credencial;
SELECT 'CONTRASEÑA PARA TODOS: Admin123' as password;
SELECT '========================================' as separador;
SELECT 'IMPORTANTE: Cambia las contraseñas en producción' as advertencia;
-- ======================================================
-- CREACIÓN DE BASE DE DATOS
-- ======================================================

CREATE DATABASE IF NOT EXISTS db_comeycalla;
USE db_comeycalla;

-- ======================================================
-- CREACIÓN DE TABLAS
-- ======================================================

-- Tabla de usuarios (camareros)
CREATE TABLE IF NOT EXISTS tbl_usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    nombre_completo VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL
);

-- Tabla de salas (terrazas, comedores, privadas)
CREATE TABLE IF NOT EXISTS tbl_salas (
    id_sala INT AUTO_INCREMENT PRIMARY KEY,
    nombre_sala VARCHAR(50) NOT NULL,
    tipo ENUM('terraza', 'comedor', 'privada') NOT NULL,
    capacidad_total INT NOT NULL
);

-- Tabla de mesas
CREATE TABLE IF NOT EXISTS tbl_mesas (
    id_mesa INT AUTO_INCREMENT PRIMARY KEY,
    id_sala INT NOT NULL,
    num_sillas INT NOT NULL,
    estado ENUM('libre','ocupada') DEFAULT 'libre',
    descripcion VARCHAR(255)
);

-- Tabla de ocupaciones (histórico)
CREATE TABLE IF NOT EXISTS tbl_ocupaciones (
    id_ocupacion INT AUTO_INCREMENT PRIMARY KEY,
    id_mesa INT NOT NULL,
    id_usuario INT NOT NULL,
    fecha_ocupacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_liberacion DATETIME NULL
);

-- ======================================================
-- RELACIONES (FOREIGN KEYS)
-- ======================================================

-- Relación entre mesas y salas
ALTER TABLE tbl_mesas
    ADD CONSTRAINT fk_mesas_salas
    FOREIGN KEY (id_sala) REFERENCES tbl_salas(id_sala);

-- Relación entre ocupaciones y mesas
ALTER TABLE tbl_ocupaciones
    ADD CONSTRAINT fk_ocupaciones_mesas
    FOREIGN KEY (id_mesa) REFERENCES tbl_mesas(id_mesa);

-- Relación entre ocupaciones y usuarios (camareros)
ALTER TABLE tbl_ocupaciones
    ADD CONSTRAINT fk_ocupaciones_usuarios
    FOREIGN KEY (id_usuario) REFERENCES tbl_usuarios(id_usuario);

-- ======================================================
-- DATOS DE EJEMPLO
-- ======================================================

-- Usuarios (camareros)
INSERT INTO tbl_usuarios (username, nombre_completo, password) VALUES
('jgomez', 'Juan Gómez', '1234'),
('mlopez', 'María López', '1234'),
('rcano', 'Raúl Cano', '1234');

-- Salas
INSERT INTO tbl_salas (nombre_sala, tipo, capacidad_total) VALUES
('Terraza 1', 'terraza', 24),
('Terraza 2', 'terraza', 20),
('Terraza 3', 'terraza', 16),
('Comedor 1', 'comedor', 40),
('Comedor 2', 'comedor', 36),
('Sala Privada 1', 'privada', 8),
('Sala Privada 2', 'privada', 10),
('Sala Privada 3', 'privada', 6),
('Sala Privada 4', 'privada', 12);

-- Mesas (ejemplo: 2 mesas por cada sala)
INSERT INTO tbl_mesas (id_sala, num_sillas, estado, descripcion) VALUES
(1, 4, 'libre', 'Mesa junto a la entrada'),
(1, 4, 'ocupada', 'Mesa en la esquina'),
(2, 2, 'libre', 'Mesa con sombra'),
(2, 4, 'libre', 'Mesa con vistas'),
(3, 4, 'libre', 'Mesa central'),
(3, 2, 'ocupada', 'Mesa lateral'),
(4, 6, 'ocupada', 'Mesa familiar'),
(4, 4, 'libre', 'Mesa de dos parejas'),
(5, 4, 'libre', 'Mesa interior'),
(5, 4, 'ocupada', 'Mesa cercana a cocina'),
(6, 8, 'libre', 'Privada para grupos'),
(7, 10, 'libre', 'Privada con terraza'),
(8, 6, 'ocupada', 'Pequeña sala privada'),
(9, 12, 'libre', 'Gran sala privada');

-- Ocupaciones de ejemplo
INSERT INTO tbl_ocupaciones (id_mesa, id_usuario, fecha_ocupacion, fecha_liberacion) VALUES
(2, 1, '2025-11-06 13:00:00', '2025-11-06 14:30:00'),
(6, 2, '2025-11-05 20:00:00', '2025-11-05 22:00:00'),
(7, 3, '2025-11-06 12:00:00', NULL),
(10, 1, '2025-11-06 13:30:00', NULL),
(13, 2, '2025-11-06 14:00:00', NULL);


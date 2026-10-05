-- schema.sql — tabla de donaciones de "Sí, da vida".
-- Primero crea la base de datos (en cPanel: "Bases de datos MySQL"; en tu PC: phpMyAdmin),
-- luego selecciónala en phpMyAdmin e importa este archivo.

CREATE TABLE IF NOT EXISTS donaciones (
    id               INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    comprobante      VARCHAR(20)   NOT NULL UNIQUE,
    nombre           VARCHAR(120)  NOT NULL,
    email            VARCHAR(160)  NOT NULL,
    telefono         VARCHAR(20)   NULL,
    monto            DECIMAL(10,2) NOT NULL,
    tipo             ENUM('unica','mensual')                NOT NULL,
    metodo           ENUM('tarjeta','paypal','yape','plin') NOT NULL,
    estado           ENUM('pendiente','aprobado','rechazado') NOT NULL DEFAULT 'pendiente',
    -- Nunca se guarda el número completo ni el CVV: solo lo necesario para el comprobante.
    tarjeta_ultimos4 CHAR(4)       NULL,
    tarjeta_marca    VARCHAR(20)   NULL,
    -- Número de operación que el donante copia de Yape, para conciliar el pago.
    codigo_operacion VARCHAR(12)   NULL,
    creado_en        TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_estado (estado)
) ENGINE=InnoDB;

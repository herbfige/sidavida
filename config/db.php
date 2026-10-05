<?php
// config/db.php — conexión PDO única para todo el proyecto.
// En producción define estos valores como variables de entorno.

$host    = getenv('DB_HOST') ?: 'localhost';
$db      = getenv('DB_NAME') ?: 'sidavida';
$user    = getenv('DB_USER') ?: 'root';
$pass    = getenv('DB_PASS') ?: '';
$charset = 'utf8mb4';

$dsn = getenv('DB_DSN') ?: "mysql:host=$host;dbname=$db;charset=$charset";
$opciones = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $opciones);
} catch (PDOException $e) {
    error_log($e->getMessage());
    http_response_code(500);
    die('Error de conexión a la base de datos.');
}

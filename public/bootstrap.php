<?php
// public/bootstrap.php — ubica las carpetas privadas (config/ e includes/).
//
// En tu computadora están al lado de public/. En el hosting conviene ponerlas FUERA de
// public_html, en una carpeta "sidavida-app" dentro de tu carpeta de usuario:
//   /home/USUARIO/sidavida-app/          ← config/ e includes/
//   /home/USUARIO/public_html/donacion/  ← el contenido de public/
// Este archivo prueba ambas ubicaciones, así que no hace falta editarlo.

$candidatas = [
    dirname(__DIR__),                         // proyecto local: public/ y config/ son hermanas
    dirname(__DIR__, 2) . '/sidavida-app',    // cPanel: public_html/donacion → ../../sidavida-app
];

foreach ($candidatas as $dir) {
    if (is_file($dir . '/includes/funciones.php')) {
        define('APP_DIR', $dir);
        break;
    }
}

if (!defined('APP_DIR')) {
    http_response_code(500);
    die('No se encontró la carpeta de configuración (sidavida-app).');
}

require_once APP_DIR . '/includes/funciones.php';

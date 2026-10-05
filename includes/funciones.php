<?php
// includes/funciones.php — arranque común de todas las páginas.

date_default_timezone_set('America/Lima');

session_start([
    'cookie_httponly' => true,
    'cookie_secure'   => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
    'cookie_samesite' => 'Lax',
]);

require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/csrf.php';

function e(?string $texto): string {
    return htmlspecialchars((string) $texto, ENT_QUOTES, 'UTF-8');
}

function redirigir(string $url): never {
    header('Location: ' . $url);
    exit;
}

function soles(float $monto): string {
    return 'S/' . number_format($monto, $monto == floor($monto) ? 0 : 2);
}

function soles2(float $monto): string {
    return 'S/' . number_format($monto, 2);
}

// Errores y valores previos de un formulario, guardados en sesión entre el POST y el GET.
function flash_guardar(array $errores, array $valores = []): void {
    $_SESSION['flash'] = ['errores' => $errores, 'valores' => $valores];
}

function flash_leer(): array {
    $flash = $_SESSION['flash'] ?? ['errores' => [], 'valores' => []];
    unset($_SESSION['flash']);
    return $flash;
}

// Estado de la donación en curso (se completa paso a paso).
function donacion(): array {
    return $_SESSION['donacion'] ?? [];
}

function exigir_paso(array $campos): void {
    $d = donacion();
    foreach ($campos as $campo) {
        if (!isset($d[$campo])) {
            redirigir('index.php#donar');
        }
    }
}

function verificar_post(): void {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !csrf_verificar($_POST['csrf_token'] ?? '')) {
        http_response_code(400);
        die('Petición inválida. Vuelve a cargar la página e inténtalo de nuevo.');
    }
}

// Íconos SVG en línea (trazo, heredan el color del texto).
function icono(string $nombre, int $tam = 28): string {
    $trazos = [
        'corazon'  => '<path d="M12 20s-7-4.4-7-10a4 4 0 0 1 7-2.6A4 4 0 0 1 19 10c0 5.6-7 10-7 10Z"/>',
        'manos'    => '<path d="M12 20s-7-4.4-7-10a4 4 0 0 1 7-2.6A4 4 0 0 1 19 10c0 5.6-7 10-7 10Z"/><path d="M8.5 11.5h2l1-1.5 1.5 3 1-1.5h1.5"/>',
        'escudo'   => '<path d="M12 3l7 3v5c0 4.5-3 8-7 10-4-2-7-5.5-7-10V6l7-3Z"/><path d="M9 12l2 2 4-4"/>',
        'info'     => '<circle cx="12" cy="12" r="9"/><path d="M12 11v6M12 7.5v.5"/>',
        'lapiz'    => '<path d="M4 20h4L19 9l-4-4L4 16v4Z"/><path d="M13.5 6.5l4 4"/>',
        'candado'  => '<rect x="5" y="11" width="14" height="10" rx="2"/><path d="M8 11V8a4 4 0 0 1 8 0v3"/>',
        'flecha'   => '<path d="M5 12h14M13 6l6 6-6 6"/>',
        'tarjeta'  => '<rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 10h18M7 15h4"/>',
        'compartir'=> '<circle cx="18" cy="5" r="2.5"/><circle cx="6" cy="12" r="2.5"/><circle cx="18" cy="19" r="2.5"/><path d="M8.2 10.8l7.6-4.4M8.2 13.2l7.6 4.4"/>',
        'check'    => '<path d="M5 12l5 5 9-10"/>',
    ];
    $d = $trazos[$nombre] ?? '';
    return '<svg class="icono" width="' . $tam . '" height="' . $tam . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . $d . '</svg>';
}

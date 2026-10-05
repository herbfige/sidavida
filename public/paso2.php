<?php
// public/paso2.php — valida los datos del donante y pasa al pago.
require_once __DIR__ . '/../includes/funciones.php';
verificar_post();
exigir_paso(['monto', 'tipo']);

$nombre   = trim($_POST['nombre'] ?? '');
$email    = trim($_POST['email'] ?? '');
$telefono = trim($_POST['telefono'] ?? '');
$errores  = [];

if (mb_strlen($nombre) < 3 || mb_strlen($nombre) > 120) {
    $errores[] = 'Escribe tu nombre completo.';
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL) || mb_strlen($email) > 160) {
    $errores[] = 'Escribe un correo electrónico válido.';
}
if ($telefono !== '' && !preg_match('/^\+?[0-9 ]{6,20}$/', $telefono)) {
    $errores[] = 'El teléfono solo puede tener números y espacios.';
}

$valores = ['nombre' => $nombre, 'email' => $email, 'telefono' => $telefono];

if ($errores) {
    flash_guardar($errores, $valores);
    redirigir('datos.php');
}

$_SESSION['donacion'] = array_merge(donacion(), $valores);
redirigir('pago.php');

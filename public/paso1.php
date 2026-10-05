<?php
// public/paso1.php — valida el monto y la frecuencia, y pasa a los datos del donante.
require_once __DIR__ . '/../includes/funciones.php';
verificar_post();

$opcion = $_POST['opcion'] ?? '';
$tipo   = $_POST['tipo'] ?? '';
$errores = [];

if ($opcion === 'otro') {
    $texto = str_replace(',', '.', trim($_POST['otro_monto'] ?? ''));
    $monto = filter_var($texto, FILTER_VALIDATE_FLOAT);
    if ($monto === false || $monto < MONTO_MINIMO || $monto > MONTO_MAXIMO) {
        $errores[] = 'Ingresa un monto entre ' . soles(MONTO_MINIMO) . ' y ' . soles(MONTO_MAXIMO) . '.';
    } else {
        $monto = round($monto, 2);
    }
} elseif (array_key_exists((int) $opcion, MONTOS) && (string) (int) $opcion === $opcion) {
    $monto = (float) $opcion;
} else {
    $errores[] = 'Elige un monto para tu donación.';
}

if (!array_key_exists($tipo, TIPOS_DONACION)) {
    $errores[] = 'Elige si tu donación es única o mensual.';
}

if ($errores) {
    flash_guardar($errores, ['monto' => $_POST['otro_monto'] ?? $opcion, 'tipo' => $tipo]);
    redirigir('index.php#donar');
}

$_SESSION['donacion'] = array_merge(donacion(), ['monto' => $monto, 'tipo' => $tipo]);
redirigir('datos.php');

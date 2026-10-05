<?php
// includes/header.php — espera $titulo y, opcionalmente, $paso (1–3) para la barra de progreso.
$titulo = $titulo ?? ORG_NOMBRE;
$paso   = $paso ?? null;
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($titulo) ?> · <?= e(ORG_NOMBRE) ?></title>
    <link rel="icon" type="image/png" href="assets/img/favicon.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap">
    <link rel="stylesheet" href="assets/style.css">
    <noscript><style>.panel { display: block; }</style></noscript>
</head>
<body>
<header class="topbar">
    <div class="contenedor topbar__fila">
        <a class="logo" href="index.php">
            <img src="assets/img/logo.webp" width="87" height="84" alt="<?= e(ORG_NOMBRE) ?>">
        </a>
        <span class="seguro"><?= icono('candado', 16) ?> Donación segura</span>
    </div>
</header>
<?php if ($paso): ?>
<nav class="pasos contenedor" aria-label="Progreso de la donación">
    <?php foreach ([1 => 'Tu donación', 2 => 'Tus datos', 3 => 'Pago'] as $n => $nombre): ?>
        <div class="pasos__item <?= $n < $paso ? 'hecho' : ($n === $paso ? 'actual' : '') ?>">
            <span class="pasos__num"><?= $n < $paso ? icono('check', 14) : $n ?></span>
            <span class="pasos__nombre"><?= e($nombre) ?></span>
        </div>
    <?php endforeach; ?>
</nav>
<?php endif; ?>
<main>

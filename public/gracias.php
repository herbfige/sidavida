<?php
// public/gracias.php — Paso 4: agradecimiento y resumen de la donación.
require_once __DIR__ . '/bootstrap.php';

$comprobante = $_SESSION['ultima_donacion'] ?? null;
if (!$comprobante) {
    redirigir('index.php');
}

require_once APP_DIR . '/config/db.php';
$stmt = $pdo->prepare('SELECT * FROM donaciones WHERE comprobante = ?');
$stmt->execute([$comprobante]);
$don = $stmt->fetch();
if (!$don) {
    redirigir('index.php');
}

$estados = [
    'aprobado'  => 'Pago aprobado',
    'pendiente' => 'Pendiente de confirmación',
    'rechazado' => 'Pago rechazado',
];

$titulo = '¡Gracias!';
$paso   = null;
require APP_DIR . '/includes/header.php';
?>
<section class="contenedor seccion seccion--estrecha">
    <div class="tarjeta-form gracias">
        <div class="confeti" aria-hidden="true"></div>
        <div class="gracias__corazon"><?= icono('corazon', 56) ?></div>
        <h1 class="form__titulo">¡Gracias por acompañar una vida!</h1>
        <p class="form__sub">Tu apoyo hace posible que más personas accedan a salud, información y dignidad.</p>

        <?php if ($don['estado'] === 'pendiente'): ?>
            <p class="aviso">
                <?php if ($don['metodo'] === 'paypal'): ?>
                    Registramos tu donación. Te escribiremos a <strong><?= e($don['email']) ?></strong> cuando PayPal confirme el pago.
                <?php else: ?>
                    Validaremos tu número de operación y te enviaremos la confirmación a <strong><?= e($don['email']) ?></strong>.
                <?php endif; ?>
            </p>
        <?php endif; ?>

        <dl class="resumen">
            <div><dt>Tu donación:</dt><dd><strong><?= soles2((float) $don['monto']) ?></strong></dd></div>
            <div><dt>Tipo:</dt><dd><?= e(TIPOS_DONACION[$don['tipo']] ?? $don['tipo']) ?></dd></div>
            <div><dt>Método:</dt><dd><?= e(METODOS_PAGO[$don['metodo']] ?? $don['metodo']) ?><?= $don['tarjeta_ultimos4'] ? ' · ' . e($don['tarjeta_marca']) . ' ••••' . e($don['tarjeta_ultimos4']) : '' ?></dd></div>
            <div><dt>Estado:</dt><dd><?= e($estados[$don['estado']] ?? $don['estado']) ?></dd></div>
            <div><dt>Fecha:</dt><dd><?= e(date('d/m/Y', strtotime($don['creado_en']))) ?></dd></div>
            <div><dt>Comprobante:</dt><dd><?= e($don['comprobante']) ?> · enviado a tu correo</dd></div>
        </dl>

        <button class="boton boton--borde boton--ancho" type="button" id="compartir"
                data-texto="Acabo de donar a <?= e(ORG_NOMBRE) ?> para que nadie enfrente el VIH en soledad. ¡Súmate!">
            <?= icono('compartir', 20) ?> Compartir tu apoyo
        </button>
        <p class="legal"><a href="index.php">Volver al inicio</a></p>
    </div>
</section>
<?php require APP_DIR . '/includes/footer.php'; ?>

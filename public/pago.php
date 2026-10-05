<?php
// public/pago.php — Paso 3: elección del método de pago.
require_once __DIR__ . '/bootstrap.php';
exigir_paso(['monto', 'tipo', 'nombre', 'email']);

$flash   = flash_leer();
$errores = $flash['errores'];
$v       = $flash['valores'];
$d       = donacion();
$metodo  = $v['metodo'] ?? 'tarjeta';

$titulo = 'Pago seguro';
$paso   = 3;
require APP_DIR . '/includes/header.php';
?>
<section class="contenedor seccion seccion--estrecha">
    <form class="tarjeta-form" method="post" action="procesar.php" id="form-pago" novalidate>
        <?= csrf_campo() ?>
        <h1 class="form__titulo">Elige tu método de pago</h1>
        <p class="form__sub">Rápido, seguro y confiable.</p>

        <?php if ($errores): ?>
            <div class="alerta" role="alert">
                <?php foreach ($errores as $error): ?><p><?= e($error) ?></p><?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div class="metodos" role="radiogroup" aria-label="Método de pago">
            <label class="metodo">
                <input type="radio" name="metodo" value="tarjeta" <?= $metodo === 'tarjeta' ? 'checked' : '' ?>>
                <span class="metodo__logo metodo__logo--tarjeta"><?= icono('tarjeta', 22) ?></span>
                <span class="metodo__texto"><strong>Tarjeta de crédito/débito</strong><small>Visa, Mastercard, Amex, Diners</small></span>
            </label>
            <label class="metodo">
                <input type="radio" name="metodo" value="paypal" <?= $metodo === 'paypal' ? 'checked' : '' ?>>
                <span class="metodo__logo metodo__logo--paypal">P</span>
                <span class="metodo__texto"><strong>PayPal</strong><small>Paga con tu cuenta PayPal</small></span>
            </label>
            <label class="metodo">
                <input type="radio" name="metodo" value="yape" <?= $metodo === 'yape' ? 'checked' : '' ?>>
                <span class="metodo__logo metodo__logo--yape">yape</span>
                <span class="metodo__texto"><strong>Yape</strong><small>Paga desde tu app</small></span>
            </label>
        </div>

        <div class="panel" data-metodo="tarjeta">
            <label class="campo">
                <span>Número de tarjeta</span>
                <input type="text" name="tarjeta_numero" inputmode="numeric" autocomplete="cc-number"
                       maxlength="23" placeholder="1234 5678 9012 3456">
            </label>
            <div class="campos-fila">
                <label class="campo">
                    <span>Fecha de vencimiento</span>
                    <input type="text" name="tarjeta_vence" inputmode="numeric" autocomplete="cc-exp"
                           maxlength="5" placeholder="MM/AA">
                </label>
                <label class="campo">
                    <span>CVV</span>
                    <input type="password" name="tarjeta_cvv" inputmode="numeric" autocomplete="cc-csc"
                           maxlength="4" placeholder="123">
                </label>
            </div>
            <label class="campo">
                <span>Nombre en la tarjeta</span>
                <input type="text" name="tarjeta_nombre" autocomplete="cc-name" maxlength="80"
                       placeholder="María Fernanda López" value="<?= e($v['tarjeta_nombre'] ?? $d['nombre']) ?>">
            </label>
        </div>

        <div class="panel" data-metodo="paypal">
            <p class="instrucciones">Al confirmar, completarás el pago de <strong><?= soles2($d['monto']) ?></strong> con tu cuenta PayPal.</p>
        </div>

        <div class="panel" data-metodo="yape">
            <ol class="instrucciones">
                <li>Abre tu app Yape y envía <strong><?= soles2($d['monto']) ?></strong> al número <strong><?= e(YAPE_NUMERO) ?></strong> (<?= e(ORG_NOMBRE) ?>).</li>
                <li>Copia el número de operación que aparece en tu constancia y escríbelo aquí.</li>
            </ol>
            <label class="campo">
                <span>Número de operación</span>
                <input type="text" name="operacion_yape" inputmode="numeric" maxlength="12"
                       placeholder="Ej. 01234567" value="<?= e($v['operacion_yape'] ?? '') ?>">
            </label>
        </div>

        <div class="total">
            <div><span>Vas a donar:</span><strong><?= soles2($d['monto']) ?></strong></div>
            <div><span>Tipo:</span><span><?= e(TIPOS_DONACION[$d['tipo']]) ?></span></div>
        </div>

        <button class="boton boton--rojo boton--ancho" type="submit"><?= icono('candado', 20) ?> Donar <?= soles2($d['monto']) ?></button>
        <p class="legal"><?= icono('candado', 14) ?> Pago 100% seguro con encriptación SSL</p>
        <p class="legal"><a href="datos.php">← Volver a mis datos</a></p>
    </form>
</section>
<?php require APP_DIR . '/includes/footer.php'; ?>

<?php
// public/index.php — Paso 1: portada y elección del monto.
require_once __DIR__ . '/bootstrap.php';

$flash   = flash_leer();
$errores = $flash['errores'];
$previo  = $flash['valores'] + donacion();

$montoPrevio = $previo['monto'] ?? MONTO_DESTACADO;
$esOtro      = !array_key_exists((int) $montoPrevio, MONTOS) || (float) $montoPrevio != (int) $montoPrevio;
$tipoPrevio  = $previo['tipo'] ?? 'unica';

$titulo = 'Dona';
$paso   = null;
require APP_DIR . '/includes/header.php';
?>
<section class="hero">
    <div class="contenedor hero__fila">
        <div>
            <h1>Nadie debería enfrentar el VIH en soledad</h1>
            <p>Tu aporte permite acompañar vidas con salud, información y dignidad.</p>
        </div>
        <a class="boton boton--blanco" href="#donar">Dona ahora <?= icono('corazon', 20) ?></a>
    </div>
</section>

<section class="contenedor seccion" id="donar">
    <h2 class="seccion__titulo">Tu apoyo hace esto posible</h2>
    <p class="seccion__sub">Cada donación se transforma en acompañamiento, salud e información.</p>

    <?php if ($errores): ?>
        <div class="alerta" role="alert">
            <?php foreach ($errores as $error): ?><p><?= e($error) ?></p><?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form class="tarjeta-form" method="post" action="paso1.php" id="form-monto">
        <?= csrf_campo() ?>
        <div class="montos">
            <?php foreach (MONTOS as $monto => $info): ?>
                <label class="monto <?= $monto === MONTO_DESTACADO ? 'monto--destacado' : '' ?>">
                    <input type="radio" name="opcion" value="<?= $monto ?>" <?= !$esOtro && (int) $montoPrevio === $monto ? 'checked' : '' ?>>
                    <?php if ($monto === MONTO_DESTACADO): ?><span class="etiqueta">Más elegido</span><?php endif; ?>
                    <span class="monto__valor"><?= soles($monto) ?></span>
                    <span class="monto__titulo"><?= e($info['titulo']) ?></span>
                    <span class="monto__detalle"><?= e($info['detalle']) ?></span>
                    <span class="monto__icono"><?= icono($info['icono']) ?></span>
                </label>
            <?php endforeach; ?>
            <label class="monto monto--otro">
                <input type="radio" name="opcion" value="otro" <?= $esOtro ? 'checked' : '' ?>>
                <span class="monto__valor monto__valor--texto">Otro monto</span>
                <span class="monto__titulo">Tú eliges cuánto aportar</span>
                <span class="monto__detalle">Cada gesto suma.</span>
                <span class="monto__otro-campo">
                    <span>S/</span>
                    <input type="number" name="otro_monto" min="<?= MONTO_MINIMO ?>" max="<?= MONTO_MAXIMO ?>" step="0.01"
                           placeholder="0.00" inputmode="decimal" aria-label="Otro monto en soles"
                           value="<?= $esOtro ? e((string) $montoPrevio) : '' ?>">
                </span>
                <span class="monto__icono"><?= icono('lapiz') ?></span>
            </label>
        </div>

        <fieldset class="tipos">
            <legend class="sr-only">Frecuencia de la donación</legend>
            <label class="tipo">
                <input type="radio" name="tipo" value="unica" <?= $tipoPrevio === 'unica' ? 'checked' : '' ?>>
                <span><strong>Donación única</strong><small>Impacto inmediato</small></span>
            </label>
            <label class="tipo">
                <input type="radio" name="tipo" value="mensual" <?= $tipoPrevio === 'mensual' ? 'checked' : '' ?>>
                <span><strong>Donación mensual <span class="rosa">♥</span></strong><small>Sostienes el acompañamiento</small></span>
            </label>
        </fieldset>

        <p class="nota-impacto" id="nota-impacto"></p>

        <button class="boton boton--rojo boton--ancho" type="submit">Continuar <?= icono('flecha', 20) ?></button>
        <p class="legal"><?= icono('candado', 14) ?> Tu información está protegida</p>
    </form>
</section>

<section class="contenedor">
    <div class="banda">
        <span class="banda__icono"><?= icono('corazon', 44) ?></span>
        <p class="banda__lema">Detectar, acompañar,<br><span>defender, transformar.</span></p>
        <p class="banda__texto">Tu apoyo hace posible que más personas accedan a prevención, diagnóstico, tratamiento y una vida digna. Súmate a esta causa.</p>
        <a class="boton boton--rojo" href="#donar">Dona ahora <?= icono('corazon', 20) ?></a>
    </div>
</section>

<script>
    window.IMPACTO = <?= json_encode(array_map(fn ($i) => $i['titulo'], MONTOS), JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) ?>;
</script>
<?php require APP_DIR . '/includes/footer.php'; ?>

<?php
// public/datos.php — Paso 2: datos del donante.
require_once __DIR__ . '/../includes/funciones.php';
exigir_paso(['monto', 'tipo']);

$flash   = flash_leer();
$errores = $flash['errores'];
$v       = $flash['valores'] + donacion();

$titulo = 'Tus datos';
$paso   = 2;
require __DIR__ . '/../includes/header.php';
?>
<section class="contenedor seccion seccion--estrecha">
    <form class="tarjeta-form" method="post" action="paso2.php" novalidate>
        <?= csrf_campo() ?>
        <h1 class="form__titulo">Estás a un paso de acompañar una vida <span class="morado">💜</span></h1>
        <p class="form__sub">Completa tus datos para continuar.</p>

        <?php if ($errores): ?>
            <div class="alerta" role="alert">
                <?php foreach ($errores as $error): ?><p><?= e($error) ?></p><?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div class="resumen-mini">
            <span><?= e(TIPOS_DONACION[$v['tipo']]) ?></span>
            <strong><?= soles2($v['monto']) ?></strong>
            <a href="index.php#donar">Cambiar</a>
        </div>

        <label class="campo">
            <span>Nombre completo</span>
            <input type="text" name="nombre" maxlength="120" required autocomplete="name"
                   placeholder="Ej. María Fernanda López" value="<?= e($v['nombre'] ?? '') ?>">
        </label>
        <label class="campo">
            <span>Correo electrónico</span>
            <input type="email" name="email" maxlength="160" required autocomplete="email"
                   placeholder="Ej. maria@ejemplo.com" value="<?= e($v['email'] ?? '') ?>">
        </label>
        <label class="campo">
            <span>Teléfono <small>(opcional)</small></span>
            <input type="tel" name="telefono" maxlength="20" autocomplete="tel"
                   placeholder="Ej. 987 654 321" value="<?= e($v['telefono'] ?? '') ?>">
        </label>
        <p class="legal legal--izq">Te enviaremos tu comprobante.</p>

        <button class="boton boton--rojo boton--ancho" type="submit">Continuar <?= icono('flecha', 20) ?></button>
        <p class="legal"><?= icono('candado', 14) ?> Tus datos están protegidos</p>
    </form>
</section>
<?php require __DIR__ . '/../includes/footer.php'; ?>

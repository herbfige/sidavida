<?php
// public/procesar.php — valida el pago, registra la donación y redirige a la página de gracias.
//
// IMPORTANTE: este proyecto no cobra dinero real todavía. Para producción hay que conectar
// una pasarela (por ejemplo Culqi, Niubiz o Izipay para tarjetas, y PayPal Checkout), de modo
// que los datos de la tarjeta vayan directo a la pasarela y nunca pasen por este servidor.
// Aquí solo se valida el formato y se guardan los 4 últimos dígitos y la marca.
require_once __DIR__ . '/bootstrap.php';
verificar_post();
exigir_paso(['monto', 'tipo', 'nombre', 'email']);

$d       = donacion();
$metodo  = $_POST['metodo'] ?? '';
$errores = [];
$ultimos4 = $marca = $operacion = null;
$estado  = 'pendiente';

function luhn_valido(string $numero): bool {
    $suma = 0;
    $doble = false;
    for ($i = strlen($numero) - 1; $i >= 0; $i--) {
        $n = (int) $numero[$i];
        if ($doble) {
            $n *= 2;
            if ($n > 9) $n -= 9;
        }
        $suma += $n;
        $doble = !$doble;
    }
    return $suma % 10 === 0;
}

function marca_tarjeta(string $numero): string {
    return match (true) {
        (bool) preg_match('/^4/', $numero)                 => 'Visa',
        (bool) preg_match('/^(5[1-5]|2[2-7])/', $numero)   => 'Mastercard',
        (bool) preg_match('/^3[47]/', $numero)             => 'Amex',
        (bool) preg_match('/^3(0[0-5]|[68])/', $numero)    => 'Diners',
        default                                            => 'Otra',
    };
}

switch ($metodo) {
    case 'tarjeta':
        $numero = preg_replace('/[\s-]/', '', $_POST['tarjeta_numero'] ?? '');
        $vence  = trim($_POST['tarjeta_vence'] ?? '');
        $cvv    = trim($_POST['tarjeta_cvv'] ?? '');
        $titular = trim($_POST['tarjeta_nombre'] ?? '');

        if (!preg_match('/^\d{13,19}$/', $numero) || !luhn_valido($numero)) {
            $errores[] = 'Revisa el número de tarjeta.';
        }
        if (!preg_match('/^(0[1-9]|1[0-2])\/(\d{2})$/', $vence, $m)) {
            $errores[] = 'La fecha de vencimiento debe tener el formato MM/AA.';
        } elseif (mktime(0, 0, 0, (int) $m[1] + 1, 1, 2000 + (int) $m[2]) <= time()) {
            $errores[] = 'La tarjeta está vencida.';
        }
        if (!preg_match('/^\d{3,4}$/', $cvv)) {
            $errores[] = 'El CVV debe tener 3 o 4 dígitos.';
        }
        if (mb_strlen($titular) < 3) {
            $errores[] = 'Escribe el nombre tal como aparece en la tarjeta.';
        }
        if (!$errores) {
            // Aquí iría el cargo con la pasarela. En modo demostración se aprueba.
            $ultimos4 = substr($numero, -4);
            $marca    = marca_tarjeta($numero);
            $estado   = 'aprobado';
        }
        break;

    case 'paypal':
        // Aquí iría la redirección a PayPal Checkout; queda pendiente hasta su confirmación.
        break;

    case 'yape':
    case 'plin':
        $operacion = trim($_POST['operacion_' . $metodo] ?? '');
        if (!preg_match('/^\d{6,12}$/', $operacion)) {
            $errores[] = 'Escribe el número de operación de tu ' . METODOS_PAGO[$metodo] . ' (solo números).';
        }
        break;

    default:
        $errores[] = 'Elige un método de pago.';
}

if ($errores) {
    // Nunca se devuelven al formulario el número de tarjeta ni el CVV.
    flash_guardar($errores, [
        'metodo'         => $metodo,
        'tarjeta_nombre' => $_POST['tarjeta_nombre'] ?? '',
        'operacion_yape' => $_POST['operacion_yape'] ?? '',
        'operacion_plin' => $_POST['operacion_plin'] ?? '',
    ]);
    redirigir('pago.php');
}

require_once APP_DIR . '/config/db.php';

$comprobante = 'SDV-' . strtoupper(bin2hex(random_bytes(4)));

try {
    $sql = 'INSERT INTO donaciones
                (comprobante, nombre, email, telefono, monto, tipo, metodo, estado,
                 tarjeta_ultimos4, tarjeta_marca, codigo_operacion)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
    $pdo->prepare($sql)->execute([
        $comprobante, $d['nombre'], $d['email'], $d['telefono'] !== '' ? $d['telefono'] : null,
        $d['monto'], $d['tipo'], $metodo, $estado, $ultimos4, $marca, $operacion,
    ]);
} catch (PDOException $e) {
    error_log($e->getMessage());
    flash_guardar(['No pudimos registrar tu donación. Inténtalo de nuevo en unos minutos.'], ['metodo' => $metodo]);
    redirigir('pago.php');
}

unset($_SESSION['donacion']);
$_SESSION['ultima_donacion'] = $comprobante;
redirigir('gracias.php');

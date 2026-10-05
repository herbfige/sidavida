<?php
// config/app.php — datos de la organización y opciones de donación.

const ORG_NOMBRE = 'Sí, da vida';

// Números a los que se envían los pagos por Yape y Plin.
const YAPE_NUMERO = '987 654 321';
const PLIN_NUMERO = '987 654 321';

// Montos sugeridos (en soles) y el impacto que explica cada uno.
const MONTOS = [
    25  => ['titulo' => 'Prueba de VIH + consejería', 'detalle' => 'Ayudas a que alguien sepa y pueda cuidarse.', 'icono' => 'corazon'],
    50  => ['titulo' => 'Acompañamiento emocional',   'detalle' => 'Brindas apoyo cuando más lo necesitan.',     'icono' => 'manos'],
    100 => ['titulo' => 'Acompañamiento integral',    'detalle' => 'Prueba, consejería y apoyo emocional para una persona.', 'icono' => 'escudo'],
];
const MONTO_DESTACADO = 50;   // Lleva la etiqueta "Más elegido".
const TIPO_POR_DEFECTO = 'mensual';
const MONTO_MINIMO    = 1;
const MONTO_MAXIMO    = 10000;

const METODOS_PAGO = [
    'tarjeta' => 'Tarjeta de crédito/débito',
    'paypal'  => 'PayPal',
    'yape'    => 'Yape',
    'plin'    => 'Plin',
];

const TIPOS_DONACION = [
    'unica'   => 'Donación única',
    'mensual' => 'Donación mensual',
];

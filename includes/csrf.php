<?php
// includes/csrf.php — helpers de token CSRF.

function csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_campo(): string {
    return '<input type="hidden" name="csrf_token" value="' . e(csrf_token()) . '">';
}

function csrf_verificar(string $tokenRecibido): bool {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $tokenRecibido);
}

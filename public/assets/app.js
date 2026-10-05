// Sí, da vida — comportamiento del formulario (la validación real se hace en el servidor).
(function () {
    // Paso 1: mensaje de impacto según el monto elegido.
    var formMonto = document.getElementById('form-monto');
    if (formMonto) {
        var nota = document.getElementById('nota-impacto');
        var otro = formMonto.querySelector('input[name=otro_monto]');
        var actualizarNota = function () {
            var elegido = formMonto.querySelector('input[name=opcion]:checked');
            var mensual = formMonto.querySelector('input[name=tipo]:checked');
            var cada = mensual && mensual.value === 'mensual' ? ' cada mes' : '';
            if (!elegido) { nota.textContent = ''; return; }
            if (elegido.value === 'otro') {
                var v = parseFloat(otro.value);
                nota.textContent = v > 0 ? 'Con S/' + v.toFixed(2) + cada + ' sumas a esta causa. ¡Cada gesto cuenta!' : '';
            } else {
                nota.textContent = 'Con S/' + elegido.value + cada + ' brindas: ' + window.IMPACTO[elegido.value].toLowerCase() + '.';
            }
        };
        formMonto.addEventListener('change', function (ev) {
            if (ev.target.value === 'otro') otro.focus();
            actualizarNota();
        });
        otro.addEventListener('input', actualizarNota);
        otro.addEventListener('focus', function () {
            formMonto.querySelector('input[name=opcion][value=otro]').checked = true;
        });
        actualizarNota();
    }

    // Paso 3: mostrar el panel del método elegido y dar formato a la tarjeta.
    var formPago = document.getElementById('form-pago');
    if (formPago) {
        var mostrarPanel = function () {
            var metodo = formPago.querySelector('input[name=metodo]:checked');
            formPago.querySelectorAll('.panel').forEach(function (p) {
                p.classList.toggle('activo', metodo && p.dataset.metodo === metodo.value);
            });
        };
        formPago.addEventListener('change', mostrarPanel);
        mostrarPanel();

        var numero = formPago.querySelector('input[name=tarjeta_numero]');
        numero.addEventListener('input', function () {
            numero.value = numero.value.replace(/\D/g, '').slice(0, 19).replace(/(\d{4})(?=\d)/g, '$1 ');
        });
        var vence = formPago.querySelector('input[name=tarjeta_vence]');
        vence.addEventListener('input', function () {
            var d = vence.value.replace(/\D/g, '').slice(0, 4);
            vence.value = d.length > 2 ? d.slice(0, 2) + '/' + d.slice(2) : d;
        });

        formPago.addEventListener('submit', function () {
            var boton = formPago.querySelector('button[type=submit]');
            boton.disabled = true;
            boton.textContent = 'Procesando…';
        });
    }

    // Paso 4: compartir.
    var compartir = document.getElementById('compartir');
    if (compartir) {
        compartir.addEventListener('click', function () {
            var texto = compartir.dataset.texto;
            var url = location.origin + location.pathname.replace(/gracias\.php$/, 'index.php');
            if (navigator.share) {
                navigator.share({ text: texto, url: url }).catch(function () {});
            } else if (navigator.clipboard) {
                navigator.clipboard.writeText(texto + ' ' + url).then(function () {
                    compartir.textContent = '¡Enlace copiado!';
                });
            }
        });
    }
})();

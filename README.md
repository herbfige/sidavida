# Sí, da vida — página de donaciones

Página web en **PHP + MySQL** para recibir donaciones en 4 pasos:

1. **Tu donación** — montos de S/5, S/10, S/20, S/30 u otro monto; donación única o mensual.
2. **Tus datos** — nombre, correo y teléfono (opcional).
3. **Pago** — tarjeta de crédito/débito, PayPal, Yape o Plin.
4. **¡Gracias!** — resumen con número de comprobante.

## Estructura

```
config/db.php        Conexión PDO (usa variables de entorno DB_HOST, DB_NAME, DB_USER, DB_PASS)
config/app.php       Montos, textos de impacto, números de Yape/Plin, métodos de pago
includes/            Cabecera, pie, funciones comunes y CSRF
public/              Raíz del servidor web (index.php, datos.php, pago.php, gracias.php…)
schema.sql           Base de datos y tabla `donaciones`
```

## Cómo ejecutarlo en tu computadora

1. Instala PHP 8.1+ y MySQL/MariaDB (lo más sencillo es **XAMPP** o **Laragon**).
2. Crea la base de datos importando `schema.sql` en phpMyAdmin, o con:
   ```bash
   mysql -u root -p < schema.sql
   ```
3. Si tu usuario o contraseña de MySQL no son `root` / vacía, edítalos en `config/db.php`
   o defínelos como variables de entorno.
4. Levanta el servidor:
   ```bash
   php -S localhost:8000 -t public
   ```
5. Abre <http://localhost:8000>.

Con XAMPP también puedes copiar la carpeta a `htdocs/sidavida` y abrir
`http://localhost/sidavida/public/`.

## Personalizar

En `config/app.php` cambias los montos y sus descripciones, el monto marcado como
"Más elegido" y los números de celular de **Yape** y **Plin** (ahora tienen un número de ejemplo).

## ⚠️ Antes de recibir dinero real

Este proyecto **todavía no cobra**: valida los datos y registra la donación, pero no está
conectado a ninguna pasarela de pago.

- **Tarjeta**: en modo demostración, toda tarjeta con formato válido queda como `aprobado`.
  Para producción integra una pasarela peruana (Culqi, Niubiz, Izipay o Mercado Pago) usando
  su formulario o librería JS, para que el número de tarjeta vaya directo a la pasarela y
  **nunca pase por tu servidor** (requisito PCI-DSS). La base de datos solo guarda la marca y
  los 4 últimos dígitos, nunca el número completo ni el CVV.
- **PayPal**: queda como `pendiente`. Hay que integrar PayPal Checkout y actualizar el estado
  cuando PayPal confirme el pago.
- **Yape / Plin**: el donante envía el dinero a tu número y escribe el número de operación.
  La donación queda `pendiente` hasta que la verifiques en tu app y cambies su estado.
- **Donación mensual**: se registra el tipo, pero el cobro recurrente debe configurarse en la pasarela.
- Usa siempre **HTTPS** en el sitio publicado.

## Seguridad incluida

- Todas las consultas usan sentencias preparadas (PDO).
- Toda salida en HTML se escapa con `htmlspecialchars()`.
- Token CSRF en todos los formularios.
- Patrón Post/Redirect/Get: recargar la página no duplica la donación.
- Los errores de la base de datos se registran en el log y no se muestran al visitante.

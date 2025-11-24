<?php
// procesar_contacto.php

// 1. Comprobamos que el formulario se ha enviado mediante POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    // Si alguien entra aquí escribiendo la URL a mano, lo devolvemos a la página principal
    header('Location: index.html'); 
    exit;
}

// 2. Recogemos los datos del formulario
// Usamos trim() para quitar espacios en blanco al principio y final
$nombre  = isset($_POST['nombre'])  ? trim($_POST['nombre'])  : '';
$email   = isset($_POST['email'])   ? trim($_POST['email'])   : '';
$mensaje = isset($_POST['mensaje']) ? trim($_POST['mensaje']) : '';
$condiciones_aceptadas = isset($_POST['condiciones']); // checkbox

// 3. Validación básica en PHP
$errores = [];

// Nombre obligatorio
if ($nombre === '') {
    $errores[] = 'El nombre es obligatorio.';
}

// Email obligatorio y con formato válido
if ($email === '') {
    $errores[] = 'El email es obligatorio.';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errores[] = 'El formato del email no es válido.';
}

// Checkbox obligatorio
if (!$condiciones_aceptadas) {
    $errores[] = 'Debes aceptar las condiciones y la política de privacidad.';
}

// 4. Si hay errores, los mostramos y paramos
if (!empty($errores)) {
    // Cerramos PHP y escribimos un HTML muy sencillo
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Error en el formulario</title>
    </head>
    <body>
        <h1>Ha habido algún problema</h1>
        <p>Revisa estos errores:</p>
        <ul>
            <?php foreach ($errores as $error): ?>
                <li><?php echo htmlspecialchars($error); ?></li>
            <?php endforeach; ?>
        </ul>
        <p>
            <a href="javascript:history.back();">Volver al formulario</a>
        </p>
    </body>
    </html>
    <?php
    exit; // Importante para que no siga ejecutando el resto del archivo
}

// 5. Si llegamos aquí, los datos son correctos. Preparamos el correo.

$destinatario = 'mariareyesartcar@gmail.com';
$asunto = 'Nuevo mensaje desde el portfolio';

// Cuerpo del mensaje
$cuerpo  = "Has recibido un nuevo mensaje desde tu portfolio:\n\n";
$cuerpo .= "Nombre: " . $nombre . "\n";
$cuerpo .= "Email: " . $email . "\n\n";
$cuerpo .= "Mensaje:\n" . $mensaje . "\n";

// Cabeceras básicas
// From: puedes poner una dirección del dominio donde alojes la web
$cabeceras  = "From: noreply@tudominio.com\r\n";
$cabeceras .= "Reply-To: " . $email . "\r\n";

// 6. Intentamos enviar el correo
$enviado = mail($destinatario, $asunto, $cuerpo, $cabeceras);

// 7. Mostramos una respuesta sencilla en HTML
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Contacto</title>
</head>
<body>
    <?php if ($enviado): ?>
        <h1>¡Gracias por tu mensaje, <?php echo htmlspecialchars($nombre); ?>!</h1>
        <p>He recibido tu mensaje correctamente. Te responderé lo antes posible.</p>
    <?php else: ?>
        <h1>Ha ocurrido un error al enviar el mensaje</h1>
        <p>Lo siento, el correo no se ha podido enviar. Intenta de nuevo más tarde.</p>
    <?php endif; ?>

    <p><a href="index.html">Volver al portfolio</a></p>
</body>
</html>

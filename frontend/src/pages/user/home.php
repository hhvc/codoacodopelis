<?php
session_start();

// Verificar si hay un mensaje de éxito
$message = isset($_SESSION['login_message']) ? $_SESSION['login_message'] : null;
unset($_SESSION['login_message']); // Limpiar el mensaje después de mostrarlo

// Otro código de inicio de sesión o lógica de la página
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio</title>
</head>
<body>
    <div class="container">
        <?php if ($message): ?>
            <div class="alert alert-success" role="alert">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        <!-- Aquí va el contenido normal de tu página index.php -->
        <h1>Bienvenido a la página de inicio</h1>
    </div>
</body>
</html>

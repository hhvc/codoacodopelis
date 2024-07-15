<?php
require_once __DIR__ . '/config.php';

// Comprobar si la conexión está establecida
if ($pdo) {
    echo "Conexión exitosa a la base de datos.";
} else {
    echo "Error en la conexión a la base de datos.";
}


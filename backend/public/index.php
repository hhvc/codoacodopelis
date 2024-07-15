<?php
// Cargar el archivo de autoload de Composer
require_once __DIR__ . '/../../vendor/autoload.php';

// Cargar la configuración
require_once __DIR__ . '/../../backend/config/config.php';

// Prueba de conexión a la base de datos
require_once __DIR__ . '/../../backend/config/test_connection.php';

// Manejar el ruteo (esto es un ejemplo muy simplificado)
$request = $_SERVER['REQUEST_URI'];

switch ($request) {
    case '/':
        require __DIR__ . '/../../frontend/public/index.html';
        break;
    case '/movies':
        // Aquí deberías incluir la lógica para mostrar la lista de películas
        require __DIR__ . '/../../frontend/public/movies.php';
        break;
    case (preg_match('/\/movies\/[0-9]+/', $request) ? true : false):
        // Aquí deberías incluir la lógica para mostrar los detalles de la película
        require __DIR__ . '/../../frontend/public/movie-details.php';
        break;
    case '/backend/public/api/pelis.php':
        // Aquí incluyes la lógica para manejar las peticiones a /backend/public/api/pelis.php
        require __DIR__ . '/../../backend/public/api/pelis.php';
        break;
    default:
        http_response_code(404);
        require __DIR__ . '/../../frontend/public/404.php';
        break;
}

<?php
// Cargar el archivo de autoload de Composer
require_once __DIR__ . '/../../vendor/autoload.php';

// Cargar variables de entorno desde .env si existe
if (file_exists(__DIR__ . '/../../.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
    $dotenv->load();
}

$host = getenv('DATABASE_HOST') ?: 'localhost';
$dbname = getenv('DATABASE_NAME') ?: 'bdpelis';
$username = getenv('DATABASE_USERNAME') ?: 'root';
$password = getenv('DATABASE_PASSWORD') ?: '';
$port = getenv('DATABASE_PORT') ?: 3306;

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error en la conexión: " . $e->getMessage());
}
?>
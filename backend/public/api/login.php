<?php
require_once '../../../vendor/autoload.php';

use Dotenv\Dotenv;

// Ajuste de la ruta al archivo .env
$dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

$host = $_ENV['DATABASE_HOST'];
$dbname = $_ENV['DATABASE_NAME'];
$username = $_ENV['DATABASE_USERNAME'];
$password = $_ENV['DATABASE_PASSWORD'];

$dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
    die('Error al conectar a la base de datos: ' . $e->getMessage());
}

header('Content-Type: application/json'); // Configuración del encabezado Content-Type

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = trim($_POST['password']);

    // Validar si el email es válido
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Correo electrónico no válido.']);
        exit;
    }

    // Validar si la contraseña no está vacía
    if (empty($password)) {
        echo json_encode(['success' => false, 'message' => 'La contraseña no puede estar vacía.']);
        exit;
    }

    try {
        $stmt = $pdo->prepare('SELECT * FROM usuarios WHERE email = :email');
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error al ejecutar la consulta: ' . $e->getMessage()]);
        exit;
    }

    if ($user && password_verify($password, $user['password'])) {
        session_start();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_rol'] = $user['rol'];

        if ($user['rol'] === 'admin') {
            echo json_encode(['success' => true, 'redirect' => '../../../frontend/src/pages/admin/dashboard.php', 'message' => 'Logueado como Administrador']);
        } else {
            echo json_encode(['success' => true, 'redirect' => '../../../frontend/src/pages/user/home.php', 'message' => 'Logueo exitoso']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Correo o contraseña incorrectos.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método de solicitud no permitido.']);
}
?>


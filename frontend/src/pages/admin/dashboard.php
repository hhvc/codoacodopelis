<?php


require_once '../../../../vendor/autoload.php';
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable('../../../../backend');
$dotenv->load();

session_start();

if (!isset($_SESSION['user_rol']) || $_SESSION['user_rol'] !== 'admin') {
    header('Location: ../../index.php');
    exit();
}

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

// Código de lógica para el CRUD
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    $id = $_POST['id'] ?? null;
    $email = $_POST['email'] ?? null;
    $password = $_POST['password'] ?? null;
    $rol = $_POST['rol'] ?? null;

    switch ($action) {
        case 'create':
            $stmt = $pdo->prepare('INSERT INTO usuarios (email, password, rol) VALUES (:email, :password, :rol)');
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt->execute(['email' => $email, 'password' => $hashedPassword, 'rol' => $rol]);
            break;
        case 'update':
            if ($password) {
                $stmt = $pdo->prepare('UPDATE usuarios SET email = :email, password = :password, rol = :rol WHERE id = :id');
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $stmt->execute(['email' => $email, 'password' => $hashedPassword, 'rol' => $rol, 'id' => $id]);
            } else {
                $stmt = $pdo->prepare('UPDATE usuarios SET email = :email, rol = :rol WHERE id = :id');
                $stmt->execute(['email' => $email, 'rol' => $rol, 'id' => $id]);
            }
            break;
        case 'delete':
            $stmt = $pdo->prepare('DELETE FROM usuarios WHERE id = :id');
            $stmt->execute(['id' => $id]);
            break;
    }
}

$users = $pdo->query('SELECT * FROM usuarios')->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link
      rel="stylesheet"
      href="../../../public/css/bootstrap-5.3.3/bootstrap.css"
    />
    <link
      rel="stylesheet"
      href="../../../public/css/bootstrap-5.3.3/bootstrap-grid.css"
    />
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    <link rel="stylesheet" href="../../../public/css/index.css" />
    <link
      rel="icon"
      href="../../../public/assets/favicon.ico"
      type="image/x-icon"
    />
    <title>Dashboard Admin</title>

</head>
<body>
    <?php include '../../partials/header.php'; ?>

    <div class="container mt-5">
        <h1>Gestión de Usuarios</h1>

        <form action="dashboard.php" method="post" class="mb-4">
            <input type="hidden" name="action" value="create">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Contraseña:</label>
                <input type="password" class="form-control" name="password" required>
            </div>
            <div class="form-group">
                <label for="rol">Rol:</label>
                <select class="form-control" name="rol">
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Crear Usuario</button>
        </form>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo $user['id']; ?></td>
                        <td><?php echo $user['email']; ?></td>
                        <td><?php echo $user['rol']; ?></td>
                        <td>
                            <form action="dashboard.php" method="post" style="display: inline-block;">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
                                <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                            </form>
                            <button class="btn btn-warning btn-sm" onclick="editUser(<?php echo $user['id']; ?>, '<?php echo $user['email']; ?>', '<?php echo $user['rol']; ?>')">Editar</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Modal para editar usuarios -->
        <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editUserModalLabel">Editar Usuario</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="dashboard.php" method="post">
                        <div class="modal-body">
                            <input type="hidden" name="action" value="update">
                            <input type="hidden" name="id" id="editUserId">
                            <div class="form-group">
                                <label for="editEmail">Email:</label>
                                <input type="email" class="form-control" name="email" id="editEmail" required>
                            </div>
                            <div class="form-group">
                                <label for="editPassword">Contraseña (dejar en blanco para mantener la actual):</label>
                                <input type="password" class="form-control" name="password" id="editPassword">
                            </div>
                            <div class="form-group">
                                <label for="editRol">Rol:</label>
                                <select class="form-control" name="rol" id="editRol">
                                    <option value="user">User</option>
                                    <option value="admin">Admin</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>

    <?php include '../../partials/footer.php'; ?>

    <script src="../../assets/js/bootstrap.bundle.min.js"></script>
    <script>
        function editUser(id, email, rol) {
            document.getElementById('editUserId').value = id;
            document.getElementById('editEmail').value = email;
            document.getElementById('editRol').value = rol;
            var editModal = new bootstrap.Modal(document.getElementById('editUserModal'));
            editModal.show();
        }
    </script>
</body>
</html>


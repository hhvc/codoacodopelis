<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../../config/config.php';

header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $input = json_decode(file_get_contents('php://input'), true);

        // Depuración: Verificar la entrada recibida
        file_put_contents('php://stderr', print_r($input, true));

        if (isset($input['email']) && isset($input['password'])) {
            $email = $input['email'];
            $password = password_hash($input['password'], PASSWORD_BCRYPT);

            $stmt = $pdo->prepare('INSERT INTO usuarios (email, password) VALUES (:email, :password)');
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $password);
            $stmt->execute();

            $response = ['success' => true, 'message' => 'User registered successfully'];
            echo json_encode($response);
            exit;
        } else {
            http_response_code(400);
            $response = ['success' => false, 'message' => 'Invalid input'];
            echo json_encode($response);
            exit;
        }
    } else {
        http_response_code(405);
        $response = ['success' => false, 'message' => 'Method not allowed'];
        echo json_encode($response);
        exit;
    }
} catch (Exception $e) {
    http_response_code(500);
    $response = ['success' => false, 'message' => 'Server error: ' . $e->getMessage()];
    echo json_encode($response);
    // Depuración: Capturar la excepción y registrarla
    file_put_contents('php://stderr', print_r($e, true));
    exit;
}
?>





// Manejar la petición POST para insertar una nueva película
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
// Obtener y escapar los valores recibidos por POST para prevenir inyecciones SQL
$postBody = file_get_contents("php://input");
$data = json_decode($postBody, true);
$titulo = $data['titulo'];
$genero = $data['genero'];
$duracion = $data['duracion'];
$imagen = $data['imagen'];

// Verificar si todos los campos necesarios están completos
if ($titulo && $genero && $duracion && $imagen) {
// Construir la consulta SQL para insertar una nueva película en la base de datos
$query = "INSERT INTO peliculas (id_pelicula, titulo, genero, duracion, imagen) VALUES (NULL, '$titulo', '$genero', '$duracion', '$imagen')";

// Ejecutar la consulta SQL y verificar si se realizó correctamente
if ($conn->query($query) === TRUE) {
// Obtener el ID de la película recién insertada
$last_insert_id = $conn->insert_id;
// Devolver código de respuesta 201 (Creado) y el ID de la película creada en formato JSON
http_response_code(201);
echo json_encode(array("message" => $last_insert_id));
} else {
// Si hubo un error al ejecutar la consulta SQL, devolver código de respuesta 500 (Error interno del servidor) con mensaje de error
http_response_code(500);
echo json_encode(array("message" => "Error al crear la película: " . $conn->error));
}
} else {
// Si no se completaron todos los campos necesarios, devolver código de respuesta 400 (Solicitud incorrecta) con mensaje de error
http_response_code(400);
echo json_encode(array("message" => "Debe completar todos los campos"));
}
}

// Manejar la petición GET para obtener todas las películas o una película por su ID
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
// Obtener el parámetro id de la solicitud
/* if (isset($_GET['id'])) {
$idParam = $_GET['id'];
} else {
$idParam = null;
}*/
$idParam = isset($_GET['id']) ? $_GET['id'] : null;

// Construir la consulta SQL para seleccionar todas las películas o una película por su ID
if ($idParam !== null) {
// Si se proporciona un ID, ajustar la consulta para seleccionar solo esa película
$query = "SELECT * FROM peliculas WHERE id_pelicula = ?";
$statement = $conn->prepare($query);
$statement->bind_param("i", $idParam); // "i" indica que el parámetro es un entero (ID de película)
} else {
// Si no se proporciona un ID, seleccionar todas las películas
$query = "SELECT * FROM peliculas";
$statement = $conn->prepare($query);
}

// Ejecutar la consulta preparada
$statement->execute();
$result = $statement->get_result();

// Verificar si se encontraron resultados
if ($result->num_rows > 0) {
// Si se encontraron películas, devolver código de respuesta 200 (OK) con las películas en formato JSON
http_response_code(200);
$peliculas = array();
while ($row = $result->fetch_assoc()) {
$peliculas[] = $row;
}
echo json_encode($peliculas);
} else {
// Si no se encontraron películas, devolver código de respuesta 404 (No encontrado) con mensaje de error
http_response_code(404);
echo json_encode(array("message" => "No se encontraron películas"));
}
}


// Manejar la petición PUT para actualizar una película existente
if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
// Obtener y escapar los valores recibidos por PUT para prevenir inyecciones SQL
$postBody = file_get_contents("php://input");
$data = json_decode($postBody, true);
$idPelicula = $data['idPelicula'];
$titulo = $data['titulo'];
$genero = $data['genero'];
$duracion = $data['duracion'];
$imagen = $data['imagen'];

// Verificar si todos los campos necesarios están completos
if ($idPelicula && $titulo && $genero && $duracion && $imagen) {
// Construir la consulta SQL para actualizar la película en la base de datos
$query = "UPDATE peliculas SET titulo = '$titulo', genero = '$genero', duracion = '$duracion', imagen = '$imagen' WHERE id_pelicula = $idPelicula";

// Ejecutar la consulta SQL y verificar si se realizó correctamente
if ($conn->query($query) === TRUE) {
// Si la actualización fue exitosa, devolver código de respuesta 200 (OK) con mensaje de éxito
http_response_code(200);
echo json_encode(array("message" => "Pelicula actualizada exitosamente."));
} else {
// Si hubo un error al ejecutar la consulta SQL, devolver código de respuesta 500 (Error interno del servidor) con mensaje de error
http_response_code(500);
echo json_encode(array("message" => "Error al actualizar la película: " . $conn->error));
}
} else {
// Si no se completaron todos los campos necesarios, devolver código de respuesta 400 (Solicitud incorrecta) con mensaje de error
http_response_code(400);
echo json_encode(array("message" => "Debe completar todos los campos"));
}
}

// Manejar la petición DELETE para eliminar una película existente
if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
// Obtener el ID de la película a eliminar desde los parámetros de la solicitud
$idParam = $_GET['id'];

// Verificar si se recibió el ID de la película
if ($idParam) {
// Construir la consulta SQL para eliminar la película de la base de datos
$query = "DELETE FROM peliculas WHERE id_pelicula = $idParam";

// Ejecutar la consulta SQL y verificar si se eliminó alguna fila
if ($conn->query($query) === TRUE && $conn->affected_rows > 0) {
// Si se eliminó la película exitosamente, devolver código de respuesta 200 (OK) con mensaje de éxito
http_response_code(200);
echo json_encode(array("message" => "Pelicula eliminada exitosamente."));
} else {
// Si no se encontró la película con el ID proporcionado, devolver código de respuesta 404 (No encontrado) con mensaje de error
http_response_code(404);
echo json_encode(array("message" => "Pelicula no encontrada."));
}
} else {
// Si no se proporcionó el ID de la película, devolver código de respuesta 400 (Solicitud incorrecta) con mensaje de error
http_response_code(400);
echo json_encode(array("message" => "ID de película no proporcionado."));
}
}

// Cerrar la conexión a la base de datos
$conn->close();
?>
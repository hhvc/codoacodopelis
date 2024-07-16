<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../../config/config.php';

header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $input = json_decode(file_get_contents('php://input'), true);

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
        } else if (isset($input['titulo']) && isset($input['genero']) && isset($input['duracion']) && isset($input['imagen'])) {
            $titulo = $input['titulo'];
            $genero = $input['genero'];
            $duracion = $input['duracion'];
            $imagen = $input['imagen'];

            $stmt = $pdo->prepare('INSERT INTO peliculas (titulo, genero, duracion, imagen) VALUES (:titulo, :genero, :duracion, :imagen)');
            $stmt->bindParam(':titulo', $titulo);
            $stmt->bindParam(':genero', $genero);
            $stmt->bindParam(':duracion', $duracion);
            $stmt->bindParam(':imagen', $imagen);
            $stmt->execute();

            $response = ['success' => true, 'message' => 'Movie added successfully'];
            echo json_encode($response);
            exit;
        } else {
            http_response_code(400);
            $response = ['success' => false, 'message' => 'Invalid input'];
            echo json_encode($response);
            exit;
        }
    } else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $idParam = isset($_GET['id']) ? $_GET['id'] : null;

        if ($idParam !== null) {
            $stmt = $pdo->prepare('SELECT * FROM peliculas WHERE id_pelicula = :id');
            $stmt->bindParam(':id', $idParam);
        } else {
            $stmt = $pdo->prepare('SELECT * FROM peliculas');
        }

        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($result) {
            http_response_code(200);
            echo json_encode($result);
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'No movies found']);
        }
    } else if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
        $input = json_decode(file_get_contents('php://input'), true);

        if (isset($input['idPelicula']) && isset($input['titulo']) && isset($input['genero']) && isset($input['duracion']) && isset($input['imagen'])) {
            $idPelicula = $input['idPelicula'];
            $titulo = $input['titulo'];
            $genero = $input['genero'];
            $duracion = $input['duracion'];
            $imagen = $input['imagen'];

            $stmt = $pdo->prepare('UPDATE peliculas SET titulo = :titulo, genero = :genero, duracion = :duracion, imagen = :imagen WHERE id_pelicula = :idPelicula');
            $stmt->bindParam(':idPelicula', $idPelicula);
            $stmt->bindParam(':titulo', $titulo);
            $stmt->bindParam(':genero', $genero);
            $stmt->bindParam(':duracion', $duracion);
            $stmt->bindParam(':imagen', $imagen);
            $stmt->execute();

            $response = ['success' => true, 'message' => 'Movie updated successfully'];
            echo json_encode($response);
        } else {
            http_response_code(400);
            echo json_encode(['message' => 'Invalid input']);
        }
    } else if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
        $idParam = isset($_GET['id']) ? $_GET['id'] : null;

        if ($idParam) {
            $stmt = $pdo->prepare('DELETE FROM peliculas WHERE id_pelicula = :id');
            $stmt->bindParam(':id', $idParam);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                http_response_code(200);
                echo json_encode(['message' => 'Movie deleted successfully']);
            } else {
                http_response_code(404);
                echo json_encode(['message' => 'Movie not found']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['message' => 'Invalid ID']);
        }
    } else {
        http_response_code(405);
        echo json_encode(['message' => 'Method not allowed']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['message' => 'Server error: ' . $e->getMessage()]);
}
?>

<?php
session_start(); // Inicia la sesión

// Verifica si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header("Content-Type: application/json");
    echo json_encode(["error" => "Usuario no autenticado."]);
    exit;
}

require_once '../config/config.php'; // Configuración de la base de datos

$method = $_SERVER['REQUEST_METHOD'];

try {
    switch ($method) {
        case 'GET':
            handleGetRequest($conn);
            break;

        case 'POST':
            handlePostRequest($conn);
            break;

        case 'DELETE':
            handleDeleteRequest($conn);
            break;

        default:
            echo json_encode(["error" => "Método no soportado"]);
            break;
    }
} catch (Exception $e) {
    echo json_encode(["error" => "Ocurrió un error inesperado: " . $e->getMessage()]);
}

$conn->close();

// Función para manejar solicitudes GET
function handleGetRequest($conn) {
    if (isset($_GET['id'])) {
        $id = intval($_GET['id']);
        $sql = "SELECT * FROM eventos_salud WHERE id = ?";
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            echo json_encode(["error" => "Error en la preparación de la consulta: " . $conn->error]);
            return;
        }

        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        echo json_encode($data);
    } else {
        $sql = "SELECT * FROM eventos_salud";
        $result = $conn->query($sql);

        if (!$result) {
            echo json_encode(["error" => "Error al obtener los datos: " . $conn->error]);
            return;
        }

        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        echo json_encode($data);
    }
}

// Función para manejar solicitudes POST
function handlePostRequest($conn) {
    $nombre_evento = filter_input(INPUT_POST, 'nombre_evento', FILTER_SANITIZE_STRING);
    $descripcion = filter_input(INPUT_POST, 'descripcion', FILTER_SANITIZE_STRING);
    $fecha = filter_input(INPUT_POST, 'fecha', FILTER_SANITIZE_STRING);
    $acciones = filter_input(INPUT_POST, 'acciones', FILTER_SANITIZE_STRING);

    if (!$nombre_evento || !$descripcion || !$fecha || !$acciones) {
        echo json_encode(["error" => "Datos incompletos o inválidos."]);
        return;
    }

    $sql = "INSERT INTO eventos_salud (nombre_evento, descripcion, fecha, acciones) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        echo json_encode(["error" => "Error en la preparación de la consulta: " . $conn->error]);
        return;
    }

    $stmt->bind_param("ssss", $nombre_evento, $descripcion, $fecha, $acciones);

    if ($stmt->execute()) {
        echo json_encode(["success" => "Evento registrado exitosamente."]);
    } else {
        echo json_encode(["error" => "Error al registrar el evento: " . $stmt->error]);
    }

    $stmt->close();
}

// Función para manejar solicitudes DELETE
function handleDeleteRequest($conn) {
    if (!isset($_GET['id'])) {
        echo json_encode(["error" => "Falta el ID"]);
        return;
    }

    $id = intval($_GET['id']);
    $sql = "DELETE FROM eventos_salud WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        echo json_encode(["error" => "Error en la preparación de la consulta: " . $conn->error]);
        return;
    }

    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo json_encode(["success" => "Evento eliminado exitosamente."]);
    } else {
        echo json_encode(["error" => "Error al eliminar el evento: " . $stmt->error]);
    }

    $stmt->close();
}
?>

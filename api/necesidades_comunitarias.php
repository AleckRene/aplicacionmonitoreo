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

// Manejo de solicitudes GET
function handleGetRequest($conn) {
    if (isset($_GET['id'])) {
        $id = intval($_GET['id']);
        $sql = "SELECT * FROM necesidades_comunitarias WHERE id = ?";
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            echo json_encode(["error" => "Error en la preparación de la consulta: " . $conn->error]);
            return;
        }

        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        echo json_encode($data ?: ["error" => "No se encontró el registro."]);
    } else {
        $sql = "SELECT * FROM necesidades_comunitarias";
        $result = $conn->query($sql);

        if ($result) {
            $data = [];
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            echo json_encode($data);
        } else {
            echo json_encode(["error" => "Error al obtener los registros: " . $conn->error]);
        }
    }
}

// Manejo de solicitudes POST
function handlePostRequest($conn) {
    $descripcion = filter_input(INPUT_POST, 'descripcion', FILTER_SANITIZE_STRING);
    $acciones = filter_input(INPUT_POST, 'acciones', FILTER_SANITIZE_STRING);
    $area_prioritaria = filter_input(INPUT_POST, 'area_prioritaria', FILTER_SANITIZE_STRING);

    if (!$descripcion || !$acciones || !$area_prioritaria) {
        echo json_encode(["error" => "Datos incompletos o inválidos."]);
        return;
    }

    $sql = "INSERT INTO necesidades_comunitarias (descripcion, acciones, area_prioritaria) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        echo json_encode(["error" => "Error en la preparación de la consulta: " . $conn->error]);
        return;
    }

    $stmt->bind_param("sss", $descripcion, $acciones, $area_prioritaria);

    if ($stmt->execute()) {
        echo json_encode(["success" => "Registro creado exitosamente."]);
    } else {
        echo json_encode(["error" => "Error al crear el registro: " . $stmt->error]);
    }
}

// Manejo de solicitudes DELETE
function handleDeleteRequest($conn) {
    $id = isset($_GET['id']) ? intval($_GET['id']) : null;

    if (!$id) {
        echo json_encode(["error" => "Falta el ID del registro."]);
        return;
    }

    $sql = "DELETE FROM necesidades_comunitarias WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        echo json_encode(["error" => "Error en la preparación de la consulta: " . $conn->error]);
        return;
    }

    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo json_encode(["success" => "Registro eliminado exitosamente."]);
    } else {
        echo json_encode(["error" => "Error al eliminar el registro: " . $stmt->error]);
    }
}
?>

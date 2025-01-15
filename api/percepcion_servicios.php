<?php
// Configuración de la base de datos
session_start(); // Inicia la sesión

// Verifica si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header("Content-Type: application/json");
    echo json_encode(["error" => "Usuario no autenticado."]);
    exit;
}

require_once '../config/config.php';

// Configuración de cabeceras
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$method = $_SERVER['REQUEST_METHOD'];

try {
    switch ($method) {
        case 'GET':
            $sql = "SELECT * FROM percepcion_servicios";
            $result = $conn->query($sql);

            $data = [];
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }
            }
            echo json_encode($data ?: ["message" => "No se encontraron registros"]);
            break;

        case 'POST':
            // Verificar los campos requeridos
            $usuario_id = $_SESSION['user_id']; // ID del usuario autenticado
            $calidad_servicio = filter_input(INPUT_POST, 'calidad_servicio', FILTER_SANITIZE_STRING);
            $servicios_mejorar = filter_input(INPUT_POST, 'servicios_mejorar', FILTER_SANITIZE_STRING);
            $cambios_recientes = filter_input(INPUT_POST, 'cambios_recientes', FILTER_SANITIZE_STRING);

            if (!$calidad_servicio || !$servicios_mejorar || !$cambios_recientes) {
                echo json_encode(["error" => "Faltan campos requeridos o inválidos."]);
                exit;
            }

            $sql = "INSERT INTO percepcion_servicios (usuario_id, calidad_servicio, servicios_mejorar, cambios_recientes) 
                    VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                echo json_encode(["error" => "Error en la preparación de la consulta: " . $conn->error]);
                exit;
            }

            $stmt->bind_param("isss", $usuario_id, $calidad_servicio, $servicios_mejorar, $cambios_recientes);

            if ($stmt->execute()) {
                echo json_encode(["message" => "Registro creado con éxito"]);
            } else {
                echo json_encode(["error" => "Error al crear el registro: " . $stmt->error]);
            }

            $stmt->close();
            break;

        case 'PUT':
            $input = json_decode(file_get_contents("php://input"), true);

            if (!isset($input['id'], $input['calidad_servicio'], $input['servicios_mejorar'], $input['cambios_recientes'])) {
                echo json_encode(["error" => "Faltan campos requeridos o inválidos"]);
                exit;
            }

            $id = intval($input['id']);
            $calidad_servicio = $conn->real_escape_string($input['calidad_servicio']);
            $servicios_mejorar = $conn->real_escape_string($input['servicios_mejorar']);
            $cambios_recientes = $conn->real_escape_string($input['cambios_recientes']);

            $sql = "UPDATE percepcion_servicios 
                    SET calidad_servicio = ?, servicios_mejorar = ?, cambios_recientes = ? 
                    WHERE id = ?";
            $stmt = $conn->prepare($sql);

            if (!$stmt) {
                echo json_encode(["error" => "Error en la preparación de la consulta: " . $conn->error]);
                exit;
            }

            $stmt->bind_param("sssi", $calidad_servicio, $servicios_mejorar, $cambios_recientes, $id);

            if ($stmt->execute()) {
                echo json_encode(["message" => "Registro actualizado con éxito"]);
            } else {
                echo json_encode(["error" => "Error al actualizar el registro: " . $stmt->error]);
            }

            $stmt->close();
            break;

        case 'DELETE':
            if (!isset($_GET['id'])) {
                echo json_encode(["error" => "ID no especificado"]);
                exit;
            }

            $id = intval($_GET['id']);
            $sql = "DELETE FROM percepcion_servicios WHERE id = ?";
            $stmt = $conn->prepare($sql);

            if (!$stmt) {
                echo json_encode(["error" => "Error en la preparación de la consulta: " . $conn->error]);
                exit;
            }

            $stmt->bind_param("i", $id);

            if ($stmt->execute()) {
                echo json_encode(["message" => "Registro eliminado con éxito"]);
            } else {
                echo json_encode(["error" => "Error al eliminar el registro: " . $stmt->error]);
            }

            $stmt->close();
            break;

        default:
            echo json_encode(["error" => "Método no permitido"]);
            break;
    }
} catch (Exception $e) {
    echo json_encode(["error" => "Ocurrió un error inesperado: " . $e->getMessage()]);
}

$conn->close();
?>

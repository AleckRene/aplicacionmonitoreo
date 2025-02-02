<?php
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
            $usuario_id = $_SESSION['user_id'];
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

            // Ejecuta la consulta
            if ($stmt->execute()) {
                echo json_encode(["success" => "Registro exitoso."]);
            } else {
                echo json_encode(["error" => "Error al guardar los datos: " . $stmt->error]);
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

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
            if (isset($_GET['id'])) {
                $id = intval($_GET['id']);
                $sql = "SELECT * FROM indicadores_uso WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $result = $stmt->get_result();
                $data = $result->fetch_assoc();
                echo json_encode($data);
            } else {
                $sql = "SELECT * FROM indicadores_uso";
                $result = $conn->query($sql);
                $data = [];
                while ($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }
                echo json_encode($data);
            }
            break;

        case 'POST':
            // Captura y valida los datos enviados
            $numero_usuarios = filter_input(INPUT_POST, 'numero_usuarios', FILTER_VALIDATE_INT);
            $nivel_actividad = filter_input(INPUT_POST, 'nivel_actividad', FILTER_SANITIZE_STRING);
            $frecuencia_recomendaciones = filter_input(INPUT_POST, 'frecuencia_recomendaciones', FILTER_SANITIZE_STRING);
            $calidad_uso = filter_input(INPUT_POST, 'calidad_uso', FILTER_SANITIZE_STRING);

            if (empty($numero_usuarios) || empty($nivel_actividad) || empty($frecuencia_recomendaciones) || empty($calidad_uso)) {
                echo json_encode(["error" => "Todos los campos son obligatorios."]);
                exit;
            }

            // Prepara la consulta SQL
            $sql = "INSERT INTO indicadores_uso (numero_usuarios, nivel_actividad, frecuencia_recomendaciones, calidad_uso) 
                    VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                echo json_encode(["error" => "Error en la preparación de la consulta: " . $conn->error]);
                exit;
            }

            $stmt->bind_param("isss", $numero_usuarios, $nivel_actividad, $frecuencia_recomendaciones, $calidad_uso);

            if ($stmt->execute()) {
                echo json_encode(["success" => "Registro creado exitosamente."]);
            } else {
                echo json_encode(["error" => "Error al crear el registro: " . $stmt->error]);
            }
            break;

        case 'DELETE':
            if (!isset($_GET['id'])) {
                echo json_encode(["error" => "Falta el ID"]);
                exit;
            }

            $id = intval($_GET['id']);
            $sql = "DELETE FROM indicadores_uso WHERE id = ?";
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                echo json_encode(["error" => "Error en la preparación de la consulta: " . $conn->error]);
                exit;
            }

            $stmt->bind_param("i", $id);

            if ($stmt->execute()) {
                echo json_encode(["message" => "Registro eliminado exitosamente."]);
            } else {
                echo json_encode(["error" => "Error al eliminar el registro: " . $stmt->error]);
            }
            break;

        default:
            echo json_encode(["error" => "Método no soportado"]);
            break;
    }
} catch (Exception $e) {
    echo json_encode(["error" => "Ocurrió un error inesperado: " . $e->getMessage()]);
}

$conn->close();
?>

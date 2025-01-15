<?php
session_start(); // Inicia la sesión

// Verifica si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header("Content-Type: application/json");
    echo json_encode(["error" => "Usuario no autenticado."]);
    exit;
}

require_once '../config/config.php'; // Incluye la configuración de la base de datos

$method = $_SERVER['REQUEST_METHOD'];

try {
    switch ($method) {
        case 'POST':
            // Obtiene el ID del usuario desde la sesión
            $usuario_id = $_SESSION['user_id'];

            // Valida y filtra los datos recibidos
            $accesibilidad_servicios = filter_input(INPUT_POST, 'accesibilidad_servicios', FILTER_VALIDATE_INT);
            $actitud_personal = filter_input(INPUT_POST, 'actitud_personal', FILTER_VALIDATE_INT);
            $tarifas_ocultas = filter_input(INPUT_POST, 'tarifas_ocultas', FILTER_VALIDATE_INT);
            $factores_mejora = filter_input(INPUT_POST, 'factores_mejora', FILTER_SANITIZE_STRING) ?: 'No especificado';
            $disponibilidad_herramientas = filter_input(INPUT_POST, 'disponibilidad_herramientas', FILTER_VALIDATE_INT);

            // Verifica que los campos obligatorios estén completos y válidos
            if (!$usuario_id || !$accesibilidad_servicios || !$actitud_personal || !$tarifas_ocultas || !$disponibilidad_herramientas) {
                echo json_encode(["error" => "Datos incompletos o inválidos."]);
                exit;
            }

            // Prepara la consulta SQL
            $query = "INSERT INTO accesibilidad_calidad (usuario_id, accesibilidad_servicios, actitud_personal, tarifas_ocultas, factores_mejora, disponibilidad_herramientas) 
                      VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($query);

            if (!$stmt) {
                echo json_encode(["error" => "Error en la preparación de la consulta: " . $conn->error]);
                exit;
            }

            // Vincula los parámetros
            $stmt->bind_param("iiiiss", $usuario_id, $accesibilidad_servicios, $actitud_personal, $tarifas_ocultas, $factores_mejora, $disponibilidad_herramientas);

            // Ejecuta la consulta
            if ($stmt->execute()) {
                echo json_encode(["success" => "Registro exitoso."]);
            } else {
                echo json_encode(["error" => "Error al guardar los datos: " . $stmt->error]);
            }

            // Cierra la consulta
            $stmt->close();
            break;

        default:
            // Manejo de métodos no soportados
            header("Content-Type: application/json");
            echo json_encode(["error" => "Método no soportado"]);
            break;
    }
} catch (Exception $e) {
    // Manejo de errores inesperados
    echo json_encode(["error" => "Ocurrió un error inesperado: " . $e->getMessage()]);
}

// Cierra la conexión a la base de datos
$conn->close();
?>

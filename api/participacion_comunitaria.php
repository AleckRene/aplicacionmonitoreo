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
            getParticipacionComunitaria($conn);
            break;

        case 'POST':
            addParticipacionComunitaria($conn);
            break;

        case 'PUT':
            updateParticipacionComunitaria($conn);
            break;

        case 'DELETE':
            deleteParticipacionComunitaria($conn);
            break;

        default:
            echo json_encode(["error" => "Método no soportado"]);
            break;
    }
} catch (Exception $e) {
    echo json_encode(["error" => "Ocurrió un error inesperado: " . $e->getMessage()]);
}

$conn->close();

// Función para obtener datos de la tabla
function getParticipacionComunitaria($conn) {
    $sql = "SELECT * FROM participacion_comunitaria";
    $result = $conn->query($sql);

    if ($result) {
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        echo json_encode($data);
    } else {
        echo json_encode(["error" => "Error al obtener los datos: " . $conn->error]);
    }
}

// Función para agregar un registro
function addParticipacionComunitaria($conn) {
    if (!isset($_POST['nivel_participacion'], $_POST['grupos_comprometidos'], $_POST['estrategias_mejora'])) {
        echo json_encode(["error" => "Faltan campos requeridos"]);
        return;
    }

    $nivel_participacion = filter_input(INPUT_POST, 'nivel_participacion', FILTER_SANITIZE_STRING);
    $grupos_comprometidos = filter_input(INPUT_POST, 'grupos_comprometidos', FILTER_SANITIZE_STRING);
    $estrategias_mejora = filter_input(INPUT_POST, 'estrategias_mejora', FILTER_SANITIZE_STRING);

    // Verificar que los datos no estén vacíos
    if (empty($nivel_participacion) || empty($grupos_comprometidos) || empty($estrategias_mejora)) {
        echo json_encode(["error" => "Datos incompletos o inválidos."]);
        return;
    }

    $sql = "INSERT INTO participacion_comunitaria (nivel_participacion, grupos_comprometidos, estrategias_mejora) 
            VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        echo json_encode(["error" => "Error en la preparación de la consulta: " . $conn->error]);
        return;
    }

    $stmt->bind_param("sss", $nivel_participacion, $grupos_comprometidos, $estrategias_mejora);

    if ($stmt->execute()) {
        echo json_encode(["success" => "Registro creado exitosamente."]);
    } else {
        echo json_encode(["error" => "Error al crear el registro: " . $stmt->error]);
    }

    $stmt->close();
}

// Función para actualizar un registro
function updateParticipacionComunitaria($conn) {
    $input = json_decode(file_get_contents('php://input'), true);

    if (!isset($input['id'], $input['nivel_participacion'], $input['grupos_comprometidos'], $input['estrategias_mejora'])) {
        echo json_encode(["error" => "Faltan campos requeridos"]);
        return;
    }

    $id = intval($input['id']);
    $nivel_participacion = $conn->real_escape_string($input['nivel_participacion']);
    $grupos_comprometidos = $conn->real_escape_string($input['grupos_comprometidos']);
    $estrategias_mejora = $conn->real_escape_string($input['estrategias_mejora']);

    $sql = "UPDATE participacion_comunitaria SET nivel_participacion = ?, grupos_comprometidos = ?, estrategias_mejora = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        echo json_encode(["error" => "Error en la preparación de la consulta: " . $conn->error]);
        return;
    }

    $stmt->bind_param("sssi", $nivel_participacion, $grupos_comprometidos, $estrategias_mejora, $id);

    if ($stmt->execute()) {
        echo json_encode(["success" => "Registro actualizado exitosamente."]);
    } else {
        echo json_encode(["error" => "Error al actualizar el registro: " . $stmt->error]);
    }

    $stmt->close();
}

// Función para eliminar un registro
function deleteParticipacionComunitaria($conn) {
    $input = json_decode(file_get_contents('php://input'), true);

    if (!isset($input['id'])) {
        echo json_encode(["error" => "Falta el ID del registro."]);
        return;
    }

    $id = intval($input['id']);
    $sql = "DELETE FROM participacion_comunitaria WHERE id = ?";
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

    $stmt->close();
}
?>

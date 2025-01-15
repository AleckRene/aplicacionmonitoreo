<?php
header("Content-Type: application/json");
session_start(); // Inicia la sesión
require_once '../config/config.php';

// Verifica que la solicitud sea POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Captura y limpia los datos del formulario
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

    // Validación básica de los campos
    if (empty($email) || empty($password)) {
        echo json_encode(["error" => "Por favor, completa todos los campos."]);
        exit;
    }

    // Consulta a la base de datos para validar usuario
    $query = "SELECT id, name, email, password, roleID FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Verifica si el usuario existe y la contraseña es correcta
    if ($user && password_verify($password, $user['password'])) {
        // Configura las variables de sesión
        $_SESSION['loggedin'] = true; // Indicador de inicio de sesión
        $_SESSION['user_id'] = $user['id']; // ID único del usuario
        $_SESSION['role_id'] = $user['roleID']; // Rol del usuario
        $_SESSION['username'] = $user['name']; // Nombre del usuario
        $_SESSION['email'] = $user['email']; // Email del usuario

        // Responde con éxito
        echo json_encode(["success" => "Inicio de sesión exitoso."]);
        exit;
    } else {
        // Credenciales inválidas
        echo json_encode(["error" => "Correo o contraseña incorrectos."]);
        exit;
    }
} else {
    // Manejo de métodos no permitidos
    echo json_encode(["error" => "Método no permitido."]);
    exit;
}
?>

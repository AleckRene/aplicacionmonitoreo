<?php
session_start(); // Inicia la sesión
require_once '../config/config.php';

// Verifica que la solicitud sea POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Captura y limpia los datos del formulario
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $localidad = filter_input(INPUT_POST, 'localidad', FILTER_SANITIZE_STRING);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

    // Validación básica de los campos
    if (empty($name) || empty($localidad) || empty($password)) {
        header("Location: ../views/login.php?error=Por%20favor,%20completa%20todos%20los%20campos.");
        exit;
    }

    // Consulta a la base de datos para validar usuario
    $query = "SELECT id, name, localidad, password, roleID FROM usuarios WHERE name = ? AND localidad = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $name, $localidad);
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
        $_SESSION['localidad'] = $user['localidad']; // Localidad del usuario

        // Redirige al consentimiento informado
        header("Location: ../views/consentimiento_informado.php");
        exit;
    } else {
        // Credenciales inválidas
        header("Location: ../views/login.php?error=Nombre,%20localidad%20o%20contrase%C3%B1a%20incorrectos.");
        exit;
    }
} else {
    // Manejo de métodos no permitidos
    header("Location: ../views/login.php?error=M%C3%A9todo%20no%20permitido.");
    exit;
}
?>

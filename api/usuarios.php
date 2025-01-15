<?php
include '../config/config.php';

// Inicia la sesión global
session_start();

// Detecta el método HTTP
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'POST':
        // Diferenciar entre registro e inicio de sesión
        $action = $_GET['action'] ?? '';

        if ($action === 'register') {
            // Registro de usuario
            $data = $_POST;

            // Verifica que todos los campos requeridos existan
            if (!isset($data['name'], $data['email'], $data['password'])) {
                header("Location: ../views/register.php?error=Faltan campos requeridos");
                exit;
            }

            $name = $conn->real_escape_string($data['name']);
            $email = $conn->real_escape_string($data['email']);
            $password = password_hash($data['password'], PASSWORD_BCRYPT);

            // Generar automáticamente el roleID en el rango de 1 a 8000
            $roleID = rand(1, 8000);

            // Verifica si el email ya está registrado
            $checkEmail = "SELECT id FROM usuarios WHERE email = ?";
            $stmtCheck = $conn->prepare($checkEmail);
            $stmtCheck->bind_param("s", $email);
            $stmtCheck->execute();
            $resultCheck = $stmtCheck->get_result();

            if ($resultCheck->num_rows > 0) {
                header("Location: ../views/register.php?error=El correo ya está registrado");
                exit;
            }

            // Inserta al nuevo usuario en la base de datos
            $sqlInsert = "INSERT INTO usuarios (name, email, password, roleID) VALUES (?, ?, ?, ?)";
            $stmtInsert = $conn->prepare($sqlInsert);
            $stmtInsert->bind_param("sssi", $name, $email, $password, $roleID);

            if ($stmtInsert->execute()) {
                // Inicia sesión automáticamente después del registro
                $_SESSION['loggedin'] = true;
                $_SESSION['user_id'] = $stmtInsert->insert_id; // ID del usuario registrado
                $_SESSION['username'] = $name;
                $_SESSION['email'] = $email;
                $_SESSION['role_id'] = $roleID;

                // Redirige al dashboard
                header("Location: ../views/dashboard.php?success=Registro exitoso");
                exit;
            } else {
                header("Location: ../views/register.php?error=Error al registrar usuario");
                exit;
            }
        } elseif ($action === 'login') {
            // Inicio de sesión
            $data = $_POST;

            if (!isset($data['email'], $data['password'])) {
                header("Location: ../views/login.php?error=Faltan campos requeridos");
                exit;
            }

            $email = $conn->real_escape_string($data['email']);
            $password = $data['password'];

            $sql = "SELECT * FROM usuarios WHERE email = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();
                if (password_verify($password, $user['password'])) {
                    // Configura la sesión del usuario
                    $_SESSION['loggedin'] = true;
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['role_id'] = $user['roleID'];
                    $_SESSION['username'] = $user['name'];

                    // Redirige al dashboard
                    header("Location: ../views/dashboard.php");
                    exit;
                } else {
                    header("Location: ../views/login.php?error=Contraseña incorrecta");
                    exit;
                }
            } else {
                header("Location: ../views/login.php?error=Usuario no encontrado");
                exit;
            }
        } else {
            header("Location: ../views/login.php?error=Acción no válida");
            exit;
        }

    case 'GET':
        // Obtener información de usuarios
        $sql = "SELECT id, name, email, roleID FROM usuarios";
        $result = $conn->query($sql);

        if ($result) {
            $users = $result->fetch_all(MYSQLI_ASSOC);
            echo json_encode($users);
        } else {
            echo json_encode(["error" => "Error al obtener usuarios: " . $conn->error]);
        }
        break;

    default:
        echo json_encode(["error" => "Método no soportado"]);
        break;
}

$conn->close();
?>

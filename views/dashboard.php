<?php
session_start();

// Validar si el usuario ha iniciado sesión
if (empty($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php?error=Debe iniciar sesión primero");
    exit;
}

// Validar si el usuario ha aceptado el consentimiento informado
if (!isset($_SESSION['consent_accepted']) || $_SESSION['consent_accepted'] !== true) {
    header("Location: consentimiento_informado.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Módulos</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <script>
        // Función para mostrar mensajes para módulos en construcción
        function showUnderConstructionMessage(moduleName) {
            alert(`${moduleName} está en construcción. Por favor, inténtalo más tarde.`);
        }
    </script>
</head>
<body>
    <div class="auth-container">
        <h1>Bienvenido al Dashboard</h1>
        <p>Hola, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong>. Selecciona un módulo para comenzar:</p>

        <!-- Botones de módulos -->
        <div class="module-buttons">
            <button onclick="location.href='consentimiento_informado.php'" class="btn">Consentimiento Informado</button>
            
            <!-- Enlace al módulo general -->
            <a href="modulo_general.php" class="btn active">Módulo General</a>
            
            <!-- Enlace al módulo VIH -->
            <a href="modulo_vih.php" class="btn active">Módulo VIH</a>

            <!-- Módulos en construcción -->
            <a href="#" class="btn disabled" onclick="showUnderConstructionMessage('Módulo TB')">Módulo TB</a>
            <a href="#" class="btn disabled" onclick="showUnderConstructionMessage('Módulo Malaria')">Módulo Malaria</a>
            <a href="#" class="btn disabled" onclick="showUnderConstructionMessage('Módulo Pandemias')">Módulo Pandemias</a>

            <!-- Enlace a los reportes interactivos -->
            <a href="../views/reports/reportes_interactivos.php?modulo=general" class="btn btn-primary" target="_blank">Ver Reportes Interactivos</a>
        </div>

        <!-- Acciones adicionales -->
        <div class="actions">
            <a href="../logout.php" class="btn btn-danger">Cerrar sesión</a>
        </div>
    </div>
</body>
</html>

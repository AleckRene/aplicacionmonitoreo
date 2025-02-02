<?php
// Inicia sesión y verifica si el usuario está logueado
session_start();
if (empty($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php?error=Debe iniciar sesión primero");
    exit;
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Módulo General</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="auth-container">
        <h1>Módulo General</h1>
        <p>Bienvenido, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong>. Aquí puedes gestionar y visualizar las secciones relacionadas con:</p>

        <!-- Botones para secciones -->
        <div class="module-buttons">
            <a href="indicadores_uso.php" class="btn">Indicadores de Uso</a>
            <a href="participacion_comunitaria.php" class="btn">Participación Comunitaria</a>
            <a href="eventos_salud.php" class="btn">Eventos de Salud</a>
            <a href="necesidades_comunitarias.php" class="btn">Necesidades Comunitarias</a>
        </div>

        <!-- Botones para reportes -->
        <div class="reports">
            <h2>Reportes</h2>
            <a href="./reports/reporte_general.php" class="btn btn-secondary">Generar Reporte General</a>
        </div>

        <!-- Volver al Dashboard -->
        <div class="actions">
            <a href="../views/dashboard.php" class="btn btn-primary">Volver al Dashboard</a>
        </div>
    </div>
</body>
</html>

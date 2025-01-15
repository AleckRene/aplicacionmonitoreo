<?php
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
    <title>Módulo VIH</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container">
        <h1>Módulo VIH</h1>
        <p>Este módulo está dedicado a la gestión y monitoreo de los indicadores relacionados con el VIH.</p>

        <div class="module-buttons">
            <button onclick="location.href='accesibilidad_calidad.php'">Accesibilidad y Calidad</button>
            <button onclick="location.href='percepcion_servicios.php'">Percepción de Servicios</button>
        </div>

        <div class="reports">
            <h2>Reportes</h2>
            <a href="./reports/reporte_vih.php" class="btn btn-secondary">Generar Reporte VIH</a>
        </div>
        <!-- Volver al Dashboard -->
        <div class="actions">
            <a href="../views/dashboard.php" class="btn btn-primary">Volver al Dashboard</a>
        </div>
    </div>
</body>
</html>

<?php
session_start(); // Inicia la sesión
if (!isset($_SESSION['user_id'])) {
    header("Location: ../views/login.php"); // Redirige al login si no está autenticado
    exit;
}
include '../config/config.php';

// Consulta para obtener los registros
$query = "SELECT * FROM percepcion_servicios";
$result = $conn->query($query);

// Verificar si hay registros
$records = [];
if ($result && $result->num_rows > 0) {
    $records = $result->fetch_all(MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Percepción de Servicios</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <script src="../assets/js/messages.js"></script>
</head>

<body>
    <div class="container">
        <h1 class="title">Percepción de Servicios</h1>
        <p class="description">Evalúa cómo perciben los servicios de salud en tu comunidad.</p>

        <!-- Sección de Formulario -->
        <form class="form-container" action="../api/percepcion_servicios.php" method="POST">
            <input type="hidden" name="usuario_id" value="1"> <!-- Cambiar según el usuario activo -->

            <div class="form-group">
                <label for="calidad_servicio">Calidad del Servicio</label>
                <select id="calidad_servicio" name="calidad_servicio" required>
                    <option value="">Seleccione una opción</option>
                    <option value="1">Muy mala</option>
                    <option value="2">Mala</option>
                    <option value="3">Regular</option>
                    <option value="4">Buena</option>
                    <option value="5">Muy buena</option>
                </select>
            </div>

    <div class="form-group">
        <label for="servicios_mejorar">Servicios a Mejorar</label>
        <select id="servicios_mejorar" name="servicios_mejorar" required>
            <option value="">Seleccione una opción</option>
            <option value="Atención al cliente">Atención al cliente</option>
            <option value="Tiempos de espera">Tiempos de espera</option>
            <option value="Disponibilidad de recursos">Disponibilidad de recursos</option>
            <option value="Infraestructura">Infraestructura</option>
            <option value="Otros">Otros</option>
        </select>
    </div>

    <div class="form-group">
        <label for="cambios_recientes">Cambios Recientes</label>
        <select id="cambios_recientes" name="cambios_recientes" required>
            <option value="">Seleccione una opción</option>
            <option value="Mejoras en atención">Mejoras en atención</option>
            <option value="Reducción de tiempos de espera">Reducción de tiempos de espera</option>
            <option value="Actualización de infraestructura">Actualización de infraestructura</option>
            <option value="Mayor disponibilidad de recursos">Mayor disponibilidad de recursos</option>
            <option value="No se han observado cambios">No se han observado cambios</option>
        </select>
    </div>

    <button class="btn btn-primary" type="submit">Agregar Percepción</button>
</form>

        <div id="successMessage" class="alert alert-success" style="display: none;">Registro exitoso.
        </div>
        <br>
        <div id="successMessage" class="alert alert-success" style="display: none;"></div>
        <div id="errorMessage" class="alert alert-danger" style="display: none;"></div>
        <!-- Botón para abrir el modal -->
        <button id="openModal" class="btn btn-primary">Ver Registros</button>

        <!-- Modal -->
<div id="modal" class="modal">
    <div class="modal-content">
        <span id="closeModal" class="close">&times;</span>
        <h2>Registros de Percepción</h2>
        <table class="styled-table">
            <thead>
                <tr>
                    <th>Usuario ID</th>
                    <th>Calidad</th>
                    <th>Servicios a Mejorar</th>
                    <th>Cambios Recientes</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($records)): ?>
                    <?php foreach ($records as $record): ?>
                        <tr>
                            <td><?= htmlspecialchars($record['usuario_id']) ?></td>
                            <td><?= htmlspecialchars($record['calidad_servicio']) ?></td>
                            <td><?= htmlspecialchars($record['servicios_mejorar']) ?></td>
                            <td><?= htmlspecialchars($record['cambios_recientes']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="no-data">No hay registros disponibles.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

        <!-- Botón para volver -->
        <div class="actions">
            <a href="../views/modulo_vih.php" class="btn btn-secondary">Volver al Módulo VIH</a>
        </div>
    </div>

    <script src="../assets/js/modal.js"></script>
    <script>
        // Mostrar mensaje de éxito/error basado en la respuesta del servidor
        const urlParams = new URLSearchParams(window.location.search);
        const successMessage = document.getElementById('successMessage');
        const errorMessage = document.getElementById('errorMessage');

        if (urlParams.has('success')) {
            successMessage.style.display = 'block';
        } else if (urlParams.has('error')) {
            errorMessage.style.display = 'block';
        }
    </script>
</body>
</html>

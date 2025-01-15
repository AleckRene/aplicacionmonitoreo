<?php
session_start(); // Inicia la sesión
if (!isset($_SESSION['user_id'])) {
    header("Location: ../views/login.php"); // Redirige al login si no está autenticado
    exit;
}
include '../config/config.php';

// Consulta para obtener los registros
$records = [];
$query = "SELECT * FROM accesibilidad_calidad";
$result = $conn->query($query);

if ($result) {
    if ($result->num_rows > 0) {
        $records = $result->fetch_all(MYSQLI_ASSOC);
    }
} else {
    error_log("Error en la consulta: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accesibilidad y Calidad</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <script src="../assets/js/messages.js"></script>
</head>

<body>
    <div class="container">
        <h1 class="title">Accesibilidad y Calidad</h1>
        <p class="description">Identifica información clave sobre accesibilidad a los servicios y calidad de atención.</p>

        <!-- Formulario -->
        <form class="form-container" action="../api/accesibilidad_calidad.php" method="POST">
            <div class="form-group">
            <label for="accesibilidad">Accesibilidad (Horarios y Tiempos de Traslado):</label>
            <select id="accesibilidad" name="accesibilidad_servicios" required>
                <option value="">Seleccione una opción</option>
                <option value="1">Nada accesibles</option>
                <option value="2">Poco accesibles</option>
                <option value="3">Moderadamente accesibles</option>
                <option value="4">Accesibles</option>
                <option value="5">Muy accesibles</option>
            </select>
    </div>
    <div class="form-group">
        <label for="actitud_personal">Actitud del Personal:</label>
        <select id="actitud_personal" name="actitud_personal" required>
            <option value="">Seleccione una opción</option>
            <option value="1">Muy inapropiada</option>
            <option value="2">Inapropiada</option>
            <option value="3">Neutral</option>
            <option value="4">Apropiada</option>
            <option value="5">Muy apropiada</option>
        </select>
    </div>
    <div class="form-group">
        <label for="tarifas">Tarifas No Oficiales:</label>
        <select id="tarifas" name="tarifas_ocultas" required>
            <option value="">Seleccione una opción</option>
            <option value="1">Nunca</option>
            <option value="2">Raramente</option>
            <option value="3">Ocasionalmente</option>
            <option value="4">Frecuentemente</option>
            <option value="5">Siempre</option>
        </select>
    </div>
    <div class="form-group">
        <label for="factores_mejora">Factores de Mejora:</label>
        <select id="factores_mejora" name="factores_mejora" required>
            <option value="">Seleccione una opción</option>
            <option value="Mejorar horarios de atención">Mejorar horarios de atención</option>
            <option value="Capacitar al personal">Capacitar al personal</option>
            <option value="Eliminar costos ocultos">Eliminar costos ocultos</option>
            <option value="Aumentar disponibilidad de medicamentos">Aumentar disponibilidad de medicamentos</option>
            <option value="Otros">Otros</option>
        </select>
    </div>
    <div class="form-group">
        <label for="disponibilidad">Disponibilidad de Medicamentos:</label>
        <select id="disponibilidad" name="disponibilidad_herramientas" required>
            <option value="">Seleccione una opción</option>
            <option value="1">Muy deficiente</option>
            <option value="2">Deficiente</option>
            <option value="3">Regular</option>
            <option value="4">Buena</option>
            <option value="5">Excelente</option>
        </select>
    </div>
    <button class="btn btn-primary" type="submit">Agregar</button>
        </form>

        <div id="successMessage" class="alert alert-success" style="display: none;">Registro exitoso.
        </div>
        <br>
        <!-- Mensajes de éxito/error -->
        <div id="successMessage" class="alert alert-success" style="display: none;"></div>
        <div id="errorMessage" class="alert alert-danger" style="display: none;"></div>

        <!-- Botón para ver registros -->
        <button id="openModal" class="btn btn-primary">Ver Registros</button>

        <!-- Modal -->
        <div id="modal" class="modal">
            <div class="modal-content">
                <span id="closeModal" class="close">&times;</span>
                <h2>Registros Disponibles</h2>
                <table class="styled-table">
                    <thead>
                        <tr>
                            <th>Accesibilidad Servicios</th>
                            <th>Actitud Personal</th>
                            <th>Tarifas Ocultas</th>
                            <th>Factores de Mejora</th>
                            <th>Disponibilidad de Herramientas</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($records)): ?>
                            <?php foreach ($records as $record): ?>
                                <tr>
                                    <td><?= htmlspecialchars($record['accesibilidad_servicios']) ?></td>
                                    <td><?= htmlspecialchars($record['actitud_personal']) ?></td>
                                    <td><?= htmlspecialchars($record['tarifas_ocultas']) ?></td>
                                    <td><?= htmlspecialchars($record['factores_mejora']) ?></td>
                                    <td><?= htmlspecialchars($record['disponibilidad_herramientas']) ?></td>
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

        <!-- Botón para regresar al módulo VIH -->
        <div class="actions">
            <a href="../views/modulo_vih.php" class="btn btn-primary">Volver al Módulo VIH</a>
        </div>
    </div>

    <script src="../assets/js/modal.js"></script>
    <script>
        const form = document.getElementById('accesibilidadForm');
        form.addEventListener('submit', function(event) {
            event.preventDefault();
            const formData = new FormData(this);
            fetch(this.action, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('successMessage').innerText = data.success;
                    document.getElementById('successMessage').style.display = 'block';
                    form.reset();
                } else if (data.error) {
                    document.getElementById('errorMessage').innerText = data.error;
                    document.getElementById('errorMessage').style.display = 'block';
                }
            })
            .catch(error => {
                document.getElementById('errorMessage').innerText = 'Error al enviar los datos.';
                document.getElementById('errorMessage').style.display = 'block';
            });
        });
    </script>
</body>
</html>
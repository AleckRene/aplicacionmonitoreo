<?php
session_start(); // Inicia la sesión
if (!isset($_SESSION['user_id'])) {
    header("Location: ../views/login.php"); // Redirige al login si no está autenticado
    exit;
}
include '../config/config.php';

// Consulta para obtener los registros
$query = "SELECT * FROM eventos_salud";
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
    <title>Eventos de Salud</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <script src="../assets/js/messages.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const form = document.querySelector("form");
            form.addEventListener("submit", function (event) {
                event.preventDefault();

                const formData = new FormData(form);

                fetch(form.action, {
                    method: "POST",
                    body: formData,
                })
                    .then((response) => response.json())
                    .then((data) => {
                        if (data.success) {
                            document.getElementById("successMessage").textContent = data.success;
                            document.getElementById("successMessage").style.display = "block";
                            form.reset();
                        } else if (data.error) {
                            document.getElementById("errorMessage").textContent = data.error;
                            document.getElementById("errorMessage").style.display = "block";
                        }
                    })
                    .catch((error) => {
                        document.getElementById("errorMessage").textContent = "Error en la solicitud.";
                        document.getElementById("errorMessage").style.display = "block";
                        console.error(error);
                    });
            });
        });
    </script>
</head>

<body>
    <div class="container">
        <h1 class="title">Eventos de Salud</h1>
        <p class="description">Registra eventos de salud recientes en tu comunidad. Ejemplo: Vacunación, jornada de limpieza, búsqueda de pacientes.</p>

        <!-- Sección de Formulario -->
        <form class="form-container" action="../api/eventos_salud.php" method="POST">
    <div class="form-group">
        <label for="nombre_evento">Tipo de Evento:</label>
        <select id="nombre_evento" name="nombre_evento" required>
            <option value="">Seleccione una opción</option>
            <option value="Vacunación comunitaria">Vacunación comunitaria</option>
            <option value="Jornada de limpieza">Jornada de limpieza</option>
            <option value="Campaña de sensibilización">Campaña de sensibilización</option>
            <option value="Otros">Otros</option>
        </select>
    </div>

    <div class="form-group">
        <label for="descripcion">Impacto del Evento:</label>
        <select id="descripcion" name="descripcion" required>
            <option value="">Seleccione una opción</option>
            <option value="Muy alto">Muy alto</option>
            <option value="Alto">Alto</option>
            <option value="Moderado">Moderado</option>
            <option value="Bajo">Bajo</option>
            <option value="Muy bajo">Muy bajo</option>
        </select>
    </div>

    <div class="form-group">
        <label for="fecha">Fecha del Evento:</label>
        <input type="date" id="fecha" name="fecha" required>
    </div>

    <div class="form-group">
        <label for="acciones">Acciones Tomadas:</label>
        <select id="acciones" name="acciones" required>
            <option value="">Seleccione una opción</option>
            <option value="Vacunación">Vacunación</option>
            <option value="Limpieza de áreas">Limpieza de áreas</option>
            <option value="Distribución de materiales">Distribución de materiales</option>
            <option value="Charlas y capacitaciones">Charlas y capacitaciones</option>
            <option value="Otras">Otras</option>
        </select>
    </div>

    <button class="btn btn-primary" type="submit">Registrar Evento</button>
</form>

        <div id="successMessage" class="alert alert-success" style="display: none;">Registro exitoso.
        </div>
        <!-- Mensajes de éxito/error -->
        <div id="successMessage" class="alert alert-success" style="display: none;"></div>
        <div id="errorMessage" class="alert alert-danger" style="display: none;"></div>

        <!-- Botón para abrir el modal -->
        <button id="openModal" class="btn btn-primary">Ver Registros</button>

        <!-- Modal -->
        <div id="modal" class="modal">
            <div class="modal-content">
                <span id="closeModal" class="close">&times;</span>
                <h2>Registros de Eventos de Salud</h2>
                <table class="styled-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Evento</th>
                            <th>Descripción</th>
                            <th>Fecha</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($records)): ?>
                            <?php foreach ($records as $record): ?>
                                <tr>
                                    <td><?= htmlspecialchars($record['id']) ?></td>
                                    <td><?= htmlspecialchars($record['nombre_evento']) ?></td>
                                    <td><?= htmlspecialchars($record['descripcion']) ?></td>
                                    <td><?= htmlspecialchars($record['fecha']) ?></td>
                                    <td><?= htmlspecialchars($record['acciones']) ?></td>
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
            <a href="../views/modulo_general.php" class="btn btn-secondary">Volver al Módulo General</a>
        </div>
    </div>

    <script src="../assets/js/modal.js"></script>
</body>
</html>

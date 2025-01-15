<?php
session_start(); // Inicia la sesión
if (!isset($_SESSION['user_id'])) {
    header("Location: ../views/login.php"); // Redirige al login si no está autenticado
    exit;
}
include '../config/config.php';

// Consulta para obtener los registros
$query = "SELECT * FROM indicadores_uso";
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
    <title>Indicadores de Uso</title>
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
        <h1 class="title">Indicadores de Uso</h1>
        <p class="description">Revisa los indicadores de uso y agrega nueva información.</p>

        <form class="form-container" action="../api/indicadores_uso.php" method="POST">
            <div class="form-group">
                <label for="numeroUsuarios">Número de Usuarios</label>
                <input type="number" id="numeroUsuarios" name="numero_usuarios" required placeholder="Ejemplo: 50">
            </div>
            <div class="form-group">
                <label for="nivelActividad">Nivel de Actividad</label>
                <select id="nivelActividad" name="nivel_actividad" required>
                    <option value="">Seleccione una opción</option>
                    <option value="1">Bajo</option>
                    <option value="2">Moderadamente bajo</option>
                    <option value="3">Moderado</option>
                    <option value="4">Moderadamente alto</option>
                    <option value="5">Alto</option>
                </select>
            </div>
            <div class="form-group">
                <label for="frecuenciaRecomendaciones">Frecuencia de Recomendaciones</label>
                <select id="frecuenciaRecomendaciones" name="frecuencia_recomendaciones" required>
                    <option value="">Seleccione una opción</option>
                    <option value="1">Raramente</option>
                    <option value="2">Ocasionalmente</option>
                    <option value="3">Moderadamente frecuente</option>
                    <option value="4">Frecuente</option>
                    <option value="5">Muy frecuente</option>
                </select>
            </div>
            <div class="form-group">
                <label for="calidadUso">Calidad del Uso</label>
                <select id="calidadUso" name="calidad_uso" required>
                    <option value="">Seleccione una opción</option>
                    <option value="1">Deficiente</option>
                    <option value="2">Aceptable</option>
                    <option value="3">Buena</option>
                    <option value="4">Muy buena</option>
                    <option value="5">Excelente</option>
                </select>
            </div>
            <button class="btn btn-primary" type="submit">Agregar Indicador</button>
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
                <h2>Indicadores Registrados</h2>
                <table class="styled-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Número de Usuarios</th>
                            <th>Nivel de Actividad</th>
                            <th>Frecuencia de Recomendaciones</th>
                            <th>Calidad del Uso</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($records)): ?>
                            <?php foreach ($records as $record): ?>
                                <tr>
                                    <td><?= htmlspecialchars($record['id']) ?></td>
                                    <td><?= htmlspecialchars($record['numero_usuarios']) ?></td>
                                    <td><?= htmlspecialchars($record['nivel_actividad']) ?></td>
                                    <td><?= htmlspecialchars($record['frecuencia_recomendaciones']) ?></td>
                                    <td><?= htmlspecialchars($record['calidad_uso']) ?></td>
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

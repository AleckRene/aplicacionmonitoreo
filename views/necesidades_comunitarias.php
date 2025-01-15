<?php
session_start(); // Inicia la sesión
if (!isset($_SESSION['user_id'])) {
    header("Location: ../views/login.php"); // Redirige al login si no está autenticado
    exit;
}
include '../config/config.php';

// Consulta para obtener los registros
$query = "SELECT * FROM necesidades_comunitarias";
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
    <title>Necesidades Comunitarias</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <script src="../assets/js/messages.js"></script>
</head>

<body>
    <div class="container">
        <h1 class="title">Necesidades Comunitarias</h1>
        <p class="description">Identifica las necesidades y acciones prioritarias en tu comunidad.</p>

        <!-- Sección de Formulario -->
        <form class="form-container" action="../api/necesidades_comunitarias.php" method="POST">
    <div class="form-group">
        <label for="descripcion">Tipo de Necesidad</label>
        <select id="descripcion" name="descripcion" required>
            <option value="">Seleccione una opción</option>
            <option value="Falta de recursos médicos">Falta de recursos médicos</option>
            <option value="Infraestructura dañada">Infraestructura dañada</option>
            <option value="Carencia de agua potable">Carencia de agua potable</option>
            <option value="Acceso limitado a educación">Acceso limitado a educación</option>
            <option value="Otros">Otros</option>
        </select>
    </div>
    <div class="form-group">
        <label for="acciones">Acciones Tomadas</label>
        <select id="acciones" name="acciones" required>
            <option value="">Seleccione una opción</option>
            <option value="Reunión comunitaria">Reunión comunitaria</option>
            <option value="Campaña de recaudación">Campaña de recaudación</option>
            <option value="Limpieza o reparaciones">Limpieza o reparaciones</option>
            <option value="Gestión de recursos externos">Gestión de recursos externos</option>
            <option value="Ninguna">Ninguna</option>
        </select>
    </div>
    <div class="form-group">
        <label for="area_prioritaria">Área Prioritaria</label>
        <select id="area_prioritaria" name="area_prioritaria" required>
            <option value="">Seleccione una opción</option>
            <option value="Salud">Salud</option>
            <option value="Educación">Educación</option>
            <option value="Infraestructura">Infraestructura</option>
            <option value="Medio Ambiente">Medio Ambiente</option>
            <option value="Otro">Otro</option>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Agregar Necesidad</button>
</form>

            <div id="successMessage" class="alert alert-success" style="display: none;">Registro exitoso.
            </div>
            <br>
            <div id="successMessage" class="alert alert-success" style="display: none;"></div>
            <div id="errorMessage" class="alert alert-danger" style="display: none;"></div>
        <br>

        <!-- Botón para abrir el modal -->
        <button id="openModal" class="btn btn-primary">Ver Registros</button>

        <!-- Modal -->
        <div id="modal" class="modal">
            <div class="modal-content">
                <span id="closeModal" class="close">&times;</span>
                <h2>Necesidades Registradas</h2>
                <table class="styled-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Descripción</th>
                            <th>Acciones Tomadas</th>
                            <th>Área Prioritaria</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($records)): ?>
                            <?php foreach ($records as $record): ?>
                                <tr>
                                    <td><?= htmlspecialchars($record['id']) ?></td>
                                    <td><?= htmlspecialchars($record['descripcion']) ?></td>
                                    <td><?= htmlspecialchars($record['acciones']) ?></td>
                                    <td><?= htmlspecialchars($record['area_prioritaria']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="no-data">No hay registros disponibles.</td>
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

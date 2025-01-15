<?php
session_start(); // Inicia la sesión
if (!isset($_SESSION['user_id'])) {
    header("Location: ../views/login.php"); // Redirige al login si no está autenticado
    exit;
}
include '../config/config.php';

// Consulta para obtener los registros
$query = "SELECT * FROM participacion_comunitaria";
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
    <title>Participación Comunitaria</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <script src="../assets/js/messages.js"></script>
</head>

<body>
    <div class="container">
        <h1 class="title">Participación Comunitaria</h1>
        <p class="description">Evalúa la participación de la comunidad en las actividades de monitoreo.</p>

        <!-- Sección de Formulario -->
        <form class="form-container" action="../api/participacion_comunitaria.php" method="POST">
    <div class="form-group">
        <label for="nivel_participacion">Nivel de Participación</label>
        <select id="nivel_participacion" name="nivel_participacion" required>
            <option value="">Seleccione una opción</option>
            <option value="1">Nada activa</option>
            <option value="2">Poco activa</option>
            <option value="3">Moderadamente activa</option>
            <option value="4">Activa</option>
            <option value="5">Muy activa</option>
        </select>
    </div>

    <div class="form-group">
        <label for="grupos_comprometidos">Grupos Comprometidos</label>
        <select id="grupos_comprometidos" name="grupos_comprometidos" required>
            <option value="">Seleccione una opción</option>
            <option value="Juntas comunitarias">Juntas comunitarias</option>
            <option value="Líderes locales">Líderes locales</option>
            <option value="Brigadistas">Brigadistas</option>
            <option value="Población en general">Población en general</option>
            <option value="Otros">Otros</option>
        </select>
    </div>

    <div class="form-group">
        <label for="estrategias_mejora">Estrategias para Mejorar</label>
        <select id="estrategias_mejora" name="estrategias_mejora" required>
            <option value="">Seleccione una opción</option>
            <option value="Capacitaciones">Capacitaciones</option>
            <option value="Reuniones periódicas">Reuniones periódicas</option>
            <option value="Campañas de sensibilización">Campañas de sensibilización</option>
            <option value="Aumentar recursos">Aumentar recursos</option>
            <option value="Ninguna">Ninguna</option>
        </select>
    </div>

    <button class="btn btn-primary" type="submit">Agregar Participación</button>
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
                <h2>Registros de Participación</h2>
                <table class="styled-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nivel de Participación</th>
                            <th>Grupos Comprometidos</th>
                            <th>Estrategias</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($records)): ?>
                            <?php foreach ($records as $record): ?>
                                <tr>
                                    <td><?= htmlspecialchars($record['id']) ?></td>
                                    <td><?= htmlspecialchars($record['nivel_participacion']) ?></td>
                                    <td><?= htmlspecialchars($record['grupos_comprometidos']) ?></td>
                                    <td><?= htmlspecialchars($record['estrategias_mejora']) ?></td>
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

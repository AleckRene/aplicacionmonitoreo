<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="../assets/css/style.css">

</head>
<body>
    <div class="login-container">
        <h1>Iniciar Sesión</h1>
        <!-- Mostrar mensajes -->
        <?php if (isset($_GET['error'])): ?>
            <p class="error-message"><?= htmlspecialchars($_GET['error']) ?></p>
       
        <?php endif; ?>

        <form action="../api/usuarios.php?action=login" method="POST">
            <input type="email" name="email" placeholder="Correo Electrónico" required>
            <input type="password" name="password" placeholder="Contraseña" required>
            <button type="submit">Ingresar</button>
        </form>
        <p>¿No tienes una cuenta? <a href="../views/register.php">Regístrate aquí</a></p>
    </div>
</body>
</html>

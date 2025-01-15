<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <script>
        // Función para limpiar los campos del formulario
        function clearForm() {
            const form = document.querySelector('form');
            form.reset();
        }

        // Redirigir al login después de 3 segundos si el registro es exitoso y limpiar el formulario
        window.onload = () => {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('success')) {
                clearForm(); // Limpiar el formulario
                setTimeout(() => {
                    window.location.href = "../views/login.php";
                }, 3000);
            }
        };
    </script>
</head>
<body>
    <div class="register-container">
        <h1>Registro</h1>
        <!-- Mostrar mensajes de éxito o error -->
        <?php if (isset($_GET['success'])): ?>
            <p class="success-message"><?= htmlspecialchars($_GET['success']) ?></p>
        <?php elseif (isset($_GET['error'])): ?>
            <p class="error-message"><?= htmlspecialchars($_GET['error']) ?></p>
        <?php endif; ?>

        <form action="../api/usuarios.php?action=register" method="POST">
            <input type="text" name="name" placeholder="Nombre" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Contraseña" required>
            <button type="submit">Registrar</button>
        </form>
        <p>¿Ya tienes una cuenta? <a href="../views/login.php">Inicia sesión aquí</a></p>
    </div>
</body>
</html>

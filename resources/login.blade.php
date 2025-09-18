<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Iniciar sesión</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; max-width:600px; margin:auto; }
        .error { color: red; }
        label { display:block; margin-top:8px; }
        input[type="text"], input[type="email"], input[type="password"] { width:100%; padding:8px; box-sizing:border-box; }
        .roles { margin-top:8px; }
        button { margin-top:12px; padding:10px 16px; }
    </style>
</head>
<body>
    <h2>Iniciar sesión</h2>

    <?php 
        // Esta parte asume que el archivo PHP de login te redirigirá aquí con un mensaje de error
        if (isset($_GET['error'])): ?>
        <p class="error"><?= htmlspecialchars($_GET['error']) ?></p>
    <?php endif; ?>

    <form method="POST" action="login.php">
        <label>Correo:</label>
        <input type="email" name="correo" required>

        <label>Contraseña:</label>
        <input type="password" name="contrasena" required>

        <div class="roles">
            <label>Entrar como:</label>
            <label><input type="radio" name="rol" value="cliente" checked> Cliente</label>
            <label><input type="radio" name="rol" value="administrador"> Administrador</label>
        </div>

        <button type="submit">Entrar</button>
    </form>

    <p>¿No tienes cuenta? <a href="registro.php">Regístrate</a></p>
</body>
</html>
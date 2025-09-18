<?php
// =========================
// Configuración de la BD
// =========================
$host = "127.0.0.1";
$user = "root"; // Usuario MySQL
$pass = "";     // Contraseña MySQL
$db   = "skatelifesindatos2";

// Crear conexión
$conn = new mysqli($host, $user, $pass, $db);

// Verificar conexión
if ($conn->connect_error) {
    die("❌ Error de conexión: " . $conn->connect_error);
}

// =========================
// Procesar formulario
// =========================
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Datos de usuario
    $numIde = $_POST['numIde'];
    $nombre = $_POST['nombre'];
    $apellidos = $_POST['apellidos'];
    $fechaNacimiento = $_POST['fechaNacimiento'];
    $direccion = $_POST['direccion'];
    $telefono = $_POST['telefono'];
    $correo = $_POST['correo'];
    $contrasena = $_POST['contrasena'];

    // ⚠️ Cifrar la contraseña antes de guardar (más seguro que texto plano)
    $hashPass = password_hash($contrasena, PASSWORD_BCRYPT);

    // Insertar en tbl_usuario
    $sqlUsuario = "INSERT INTO tbl_usuario (numIde, nombre, apellidos, fechaNacimiento, edad, direccion, telefono, correo, contraseña)
                   VALUES (?, ?, ?, ?, TIMESTAMPDIFF(YEAR, ?, CURDATE()), ?, ?, ?, ?)";

    $stmt = $conn->prepare($sqlUsuario);
    $stmt->bind_param("isssssiss", $numIde, $nombre, $apellidos, $fechaNacimiento, $fechaNacimiento, $direccion, $telefono, $correo, $hashPass);

    if ($stmt->execute()) {
        $idUsuario = $conn->insert_id;

        // Insertar en tbl_cliente
        $tipoUsuario = "Cliente";
        $sqlCliente = "INSERT INTO tbl_cliente (fk_idusuario, tipodeusuario, cli_fecharegistro)
                       VALUES (?, ?, CURDATE())";

        $stmtCliente = $conn->prepare($sqlCliente);
        $stmtCliente->bind_param("is", $idUsuario, $tipoUsuario);

        if ($stmtCliente->execute()) {
            // ✅ Redirigir a pedidos.php después de registrar cliente
            header("Location: pedidos.php");
            exit();
        } else {
            echo "<p style='color:red;'>❌ Error al registrar cliente: " . $stmtCliente->error . "</p>";
        }
    } else {
        echo "<p style='color:red;'>❌ Error al registrar usuario: " . $stmt->error . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Cliente</title>
</head>
<body>
    <h2>Registrar nuevo cliente</h2>
    <form method="POST" action="">
        <label>Número de Identificación:</label>
        <input type="number" name="numIde" required><br><br>

        <label>Nombre:</label>
        <input type="text" name="nombre" required><br><br>

        <label>Apellidos:</label>
        <input type="text" name="apellidos" required><br><br>

        <label>Fecha de Nacimiento:</label>
        <input type="date" name="fechaNacimiento" required><br><br>

        <label>Dirección:</label>
        <input type="text" name="direccion" required><br><br>

        <label>Teléfono:</label>
        <input type="number" name="telefono" required><br><br>

        <label>Correo:</label>
        <input type="email" name="correo" required><br><br>

        <label>Contraseña:</label>
        <input type="password" name="contrasena" required><br><br>

        <button type="submit">Guardar</button>
    </form>

    <hr>
    <h2>Lista de Clientes</h2>
    <ul>
        <?php
        $sql = "SELECT c.pk_idcliente, u.nombre, u.apellidos, u.correo, c.cli_fecharegistro
                FROM tbl_cliente c
                JOIN tbl_usuario u ON c.fk_idusuario = u.pk_idusuario";
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            echo "<li>Cliente #".$row['pk_idcliente']." | ".$row['nombre']." ".$row['apellidos']." | Correo: ".$row['correo']." | Registrado: ".$row['cli_fecharegistro']."</li>";
        }
        ?>
    </ul>
</body>
</html>

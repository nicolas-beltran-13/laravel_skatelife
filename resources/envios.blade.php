<?php
// Configuración de la base de datos
$host = "127.0.0.1";
$user = "root"; // tu usuario de MySQL
$pass = "";     // tu contraseña de MySQL
$db   = "skatelifesindatos2";

// Crear conexión
$conn = new mysqli($host, $user, $pass, $db);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Procesar formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pedido = $_POST['fk_idpedido'];
    $inventario = $_POST['fk_idinventario'];
    $fecha = $_POST['fechaenvio'];
    $prioridad = $_POST['prioridadenvio'];
    $direccion = $_POST['direccionsalida'];
    $estado = $_POST['estadoEnvio'];

    $sql = "INSERT INTO tbl_envio (fk_idpedido, fk_idinventario, fechaenvio, prioridadenvio, direccionsalida, estadoEnvio)
            VALUES ('$pedido', '$inventario', '$fecha', '$prioridad', '$direccion', '$estado')";

    if ($conn->query($sql) === TRUE) {
        // ✅ Redirigir a lista de envíos
        header("Location: envios.php");
        exit();
    } else {
        echo "<p style='color:red;'>❌ Error: " . $conn->error . "</p>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Registrar Envío</title>
</head>
<body>
    <h2>Registrar nuevo envío</h2>
    <form method="POST" action="">
        <label>Pedido:</label>
        <select name="fk_idpedido" required>
            <?php
            $pedidos = $conn->query("SELECT pk_idpedido, direccionentrega FROM tbl_pedido");
            while ($p = $pedidos->fetch_assoc()) {
                echo "<option value='".$p['pk_idpedido']."'>Pedido #".$p['pk_idpedido']." - ".$p['direccionentrega']."</option>";
            }
            ?>
        </select><br><br>

        <label>Inventario:</label>
        <select name="fk_idinventario" required>
            <?php
            $inventarios = $conn->query("SELECT pk_idinventario, proveedor FROM inventario");
            while ($i = $inventarios->fetch_assoc()) {
                echo "<option value='".$i['pk_idinventario']."'>Inventario #".$i['pk_idinventario']." - ".$i['proveedor']."</option>";
            }
            ?>
        </select><br><br>

        <label>Fecha Envío:</label>
        <input type="date" name="fechaenvio" required><br><br>

        <label>Prioridad:</label>
        <select name="prioridadenvio" required>
            <option value="Alta">Alta</option>
            <option value="Media">Media</option>
            <option value="Baja">Baja</option>
        </select><br><br>

        <label>Dirección de salida:</label>
        <input type="text" name="direccionsalida" required><br><br>

        <label>Estado:</label>
        <select name="estadoEnvio" required>
            <option value="0">Pendiente</option>
            <option value="1">Enviado</option>
            <option value="2">Entregado</option>
        </select><br><br>

        <button type="submit">Guardar</button>
    </form>

    <hr>
    <h2>Lista de Envíos</h2>
    <ul>
        <?php
        $result = $conn->query("SELECT e.pk_idenvios, e.fechaenvio, e.prioridadenvio, e.direccionsalida, e.estadoEnvio, p.pk_idpedido 
                                FROM tbl_envio e
                                JOIN tbl_pedido p ON e.fk_idpedido = p.pk_idpedido");
        while ($row = $result->fetch_assoc()) {
            $estadoTexto = ($row['estadoEnvio'] == 0) ? "Pendiente" : (($row['estadoEnvio'] == 1) ? "Enviado" : "Entregado");
            echo "<li>Envío #".$row['pk_idenvios']." | Pedido #".$row['pk_idpedido']." | Fecha: ".$row['fechaenvio']." | Prioridad: ".$row['prioridadenvio']." | Estado: ".$estadoTexto."</li>";
        }
        ?>
    </ul>
</body>
</html>

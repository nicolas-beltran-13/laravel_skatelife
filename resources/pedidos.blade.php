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
    $cliente = $_POST['fk_idcliente'];
    $producto = $_POST['fk_idproducto'];
    $fecha = $_POST['fechaentrega'];
    $estado = $_POST['estadopedido'];
    $pago = $_POST['pagopedido'];
    $direccion = $_POST['direccionentrega'];
    $cantidad = $_POST['cantidad_pedido'];

    $sql = "INSERT INTO tbl_pedido (fk_idcliente, fk_idproducto, fechaentrega, estadopedido, pagopedido, direccionentrega, cantidad_pedido)
            VALUES ('$cliente', '$producto', '$fecha', '$estado', '$pago', '$direccion', '$cantidad')";

    if ($conn->query($sql) === TRUE) {
        // ✅ Redirigir a lista de pedidos
        header("Location: pedidos.php");
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
    <title>Registrar Pedido</title>
</head>
<body>
    <h2>Registrar nuevo pedido</h2>
    <form method="POST" action="">
        <label>Cliente:</label>
        <select name="fk_idcliente" required>
            <?php
            $clientes = $conn->query("SELECT c.pk_idcliente, u.nombre, u.apellidos FROM tbl_cliente c JOIN tbl_usuario u ON c.fk_idusuario = u.pk_idusuario");
            while ($cli = $clientes->fetch_assoc()) {
                echo "<option value='".$cli['pk_idcliente']."'>".$cli['nombre']." ".$cli['apellidos']."</option>";
            }
            ?>
        </select><br><br>

        <label>Producto:</label>
        <select name="fk_idproducto" required>
            <?php
            $productos = $conn->query("SELECT pk_idproducto, nombreproducto FROM tbl_producto");
            while ($p = $productos->fetch_assoc()) {
                echo "<option value='".$p['pk_idproducto']."'>".$p['nombreproducto']."</option>";
            }
            ?>
        </select><br><br>

        <label>Fecha entrega:</label>
        <input type="date" name="fechaentrega" required><br><br>

        <label>Estado del pedido:</label>
        <select name="estadopedido" required>
            <option value="Pendiente">Pendiente</option>
            <option value="En proceso">En proceso</option>
            <option value="Entregado">Entregado</option>
        </select><br><br>

        <label>Pago pedido (ID o monto):</label>
        <input type="number" name="pagopedido" required><br><br>

        <label>Dirección entrega:</label>
        <input type="text" name="direccionentrega" required><br><br>

        <label>Cantidad:</label>
        <input type="number" name="cantidad_pedido" required><br><br>

        <button type="submit">Guardar</button>
    </form>

    <hr>
    <h2>Lista de Pedidos</h2>
    <ul>
        <?php
        $sql = "SELECT p.pk_idpedido, u.nombre, u.apellidos, pr.nombreproducto, p.fechaentrega, p.estadopedido, p.cantidad_pedido, p.direccionentrega
                FROM tbl_pedido p
                JOIN tbl_cliente c ON p.fk_idcliente = c.pk_idcliente
                JOIN tbl_usuario u ON c.fk_idusuario = u.pk_idusuario
                JOIN tbl_producto pr ON p.fk_idproducto = pr.pk_idproducto";
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            echo "<li>Pedido #".$row['pk_idpedido']." | Cliente: ".$row['nombre']." ".$row['apellidos']." | Producto: ".$row['nombreproducto']." | Cantidad: ".$row['cantidad_pedido']." | Entrega: ".$row['fechaentrega']." | Estado: ".$row['estadopedido']." | Dirección: ".$row['direccionentrega']."</li>";
        }
        ?>
    </ul>
</body>
</html>

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
    $administrador = $_POST['fk_idadministrador'];
    $producto = $_POST['fk_idproducto'];
    $tipoMovimiento = $_POST['tipoMovimiento'];
    $fecha = $_POST['fechaMovimiento'];
    $factura = $_POST['numeroFactura'];
    $proveedor = $_POST['proveedor'];

    $sql = "INSERT INTO inventario (fk_idadministrador, fk_idproducto, tipoMovimiento, fechaMovimiento, numeroFactura, proveedor)
            VALUES ('$administrador', '$producto', '$tipoMovimiento', '$fecha', '$factura', '$proveedor')";

    if ($conn->query($sql) === TRUE) {
        // ✅ Redirigir de vuelta a inventario
        header("Location: inventario.php");
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
    <title>Registrar Inventario</title>
</head>
<body>
    <h2>Registrar movimiento de inventario</h2>
    <form method="POST" action="">
        <label>Administrador:</label>
        <select name="fk_idadministrador" required>
            <?php
            $admins = $conn->query("SELECT a.pk_idadministrador, u.nombre, u.apellidos 
                                    FROM tbl_administrador a 
                                    JOIN tbl_usuario u ON a.fk_idusuario = u.pk_idusuario");
            if ($admins->num_rows > 0) {
                while ($a = $admins->fetch_assoc()) {
                    echo "<option value='".$a['pk_idadministrador']."'>".$a['nombre']." ".$a['apellidos']."</option>";
                }
            } else {
                echo "<option disabled>No hay administradores</option>";
            }
            ?>
        </select><br><br>

        <label>Producto:</label>
        <select name="fk_idproducto" required>
            <?php
            $productos = $conn->query("SELECT pk_idproducto, nombreproducto FROM tbl_producto");
            if ($productos->num_rows > 0) {
                while ($p = $productos->fetch_assoc()) {
                    echo "<option value='".$p['pk_idproducto']."'>".$p['nombreproducto']."</option>";
                }
            } else {
                echo "<option disabled>No hay productos</option>";
            }
            ?>
        </select><br><br>

        <label>Tipo de movimiento:</label>
        <select name="tipoMovimiento" required>
            <option value="Entrada">Entrada</option>
            <option value="Salida">Salida</option>
        </select><br><br>

        <label>Fecha movimiento:</label>
        <input type="date" name="fechaMovimiento" required><br><br>

        <label>Número de factura:</label>
        <input type="number" name="numeroFactura" required><br><br>

        <label>Proveedor:</label>
        <input type="text" name="proveedor" required><br><br>

        <button type="submit">Guardar</button>
    </form>

    <hr>
    <h2>Lista de movimientos de inventario</h2>
    <ul>
        <?php
        $sql = "SELECT i.pk_idinventario, p.nombreproducto, i.tipoMovimiento, i.fechaMovimiento, i.proveedor 
                FROM inventario i
                JOIN tbl_producto p ON i.fk_idproducto = p.pk_idproducto";
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            echo "<li>Inventario #".$row['pk_idinventario']." | Producto: ".$row['nombreproducto']." | Movimiento: ".$row['tipoMovimiento']." | Fecha: ".$row['fechaMovimiento']." | Proveedor: ".$row['proveedor']."</li>";
        }
        ?>
    </ul>
</body>
</html>

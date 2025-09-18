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

// Si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombreproducto'];
    $precio = $_POST['precio'];
    $imagen = $_POST['imagen']; 
    $cantidad = $_POST['cantidad'];
    $info = $_POST['informacion'];
    $categoria = $_POST['fk_idcategoria'];

    $sql = "INSERT INTO tbl_producto 
            (nombreproducto, precio, imagen, cantidad, informacion, fk_idcategoria) 
            VALUES ('$nombre', '$precio', '$imagen', '$cantidad', '$info', '$categoria')";

    if ($conn->query($sql) === TRUE) {
        echo "<p style='color:green;'>✅ Producto registrado con éxito</p>";
    } else {
        echo "<p style='color:red;'>❌ Error: " . $conn->error . "</p>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Registrar Producto</title>
</head>
<body>
    <h2>Registrar nuevo producto</h2>
    <form method="POST" action="">
        <label>Nombre:</label>
        <input type="text" name="nombreproducto" required><br><br>

        <label>Precio:</label>
        <input type="number" name="precio" required><br><br>

        <label>Imagen (URL o ruta):</label>
        <input type="text" name="imagen" required><br><br>

        <label>Cantidad:</label>
        <input type="number" name="cantidad" required><br><br>

        <label>Información:</label>
        <input type="text" name="informacion" required><br><br>

        <label>Categoría:</label>
        <select name="fk_idcategoria" required>
            <?php
            $categorias = $conn->query("SELECT pk_idcategoria, NombreCategoria FROM tbl_categoria");
            while ($cat = $categorias->fetch_assoc()) {
                echo "<option value='".$cat['pk_idcategoria']."'>".$cat['NombreCategoria']."</option>";
            }
            ?>
        </select>
        <br><br>

        <button type="submit">Guardar</button>
    </form>

    <hr>
    <h2>Lista de productos</h2>
    <ul>
        <?php
        $sql = "SELECT p.nombreproducto, p.precio, c.NombreCategoria 
                FROM tbl_producto p
                JOIN tbl_categoria c ON p.fk_idcategoria = c.pk_idcategoria";
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            echo "<li>" . $row['nombreproducto'] . 
                 " - $" . $row['precio'] . 
                 " | Categoría: " . $row['NombreCategoria'] . "</li>";
        }
        ?>
    </ul>
</body>
</html>

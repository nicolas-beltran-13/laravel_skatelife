<?php
// Incluir conexión
require_once "conexion.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['NombreCategoria'];
    $talla = $_POST['talla'];
    $colores = $_POST['colores'];
    $modelo = $_POST['modelo'];

    $sql = "INSERT INTO tbl_categoria (NombreCategoria, talla, colores, modelo)
            VALUES (?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $nombre, $talla, $colores, $modelo);

    if ($stmt->execute()) {
        header("Location: productos.php");
        exit();
    } else {
        echo "<p style='color:red;'>❌ Error: " . $stmt->error . "</p>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Registrar Categoría</title>
</head>
<body>
    <h2>Registrar nueva categoría</h2>
    <form method="POST" action="">
        <label>Nombre Categoría:</label>
        <input type="text" name="NombreCategoria" required><br><br>

        <label>Talla:</label>
        <input type="text" name="talla" maxlength="4" required><br><br>

        <label>Colores:</label>
        <input type="text" name="colores" required><br><br>

        <label>Modelo:</label>
        <input type="text" name="modelo" required><br><br>

        <button type="submit">Guardar</button>
    </form>

    <br>
    <a href="admin_panel.php">⬅ Volver al Panel de Administrador</a>
</body>
</html>

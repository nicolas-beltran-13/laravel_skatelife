<?php
$host = "127.0.0.1";
$user = "root";     // Usuario de MySQL
$pass = "";         // Contraseña de MySQL
$db   = "skatelifesindatos2";

// Crear conexión
$conn = new mysqli($host, $user, $pass, $db);

// Verificar conexión
if ($conn->connect_error) {
    die("❌ Error de conexión a la BD: " . $conn->connect_error);
}

// Establecer UTF-8 para caracteres especiales
$conn->set_charset("utf8");
?>

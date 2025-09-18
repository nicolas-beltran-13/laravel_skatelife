<?php
session_start();

// Configuración de la BD
$host = "127.0.0.1";
$user = "root";
$pass = "";
$db   = "skatelifesindatos2";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("❌ Error de conexión: " . $conn->connect_error);
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = isset($_POST['correo']) ? trim($_POST['correo']) : '';
    $contrasena = isset($_POST['contrasena']) ? $_POST['contrasena'] : '';
    $rol = isset($_POST['rol']) ? $_POST['rol'] : 'cliente';

    if ($correo === '' || $contrasena === '') {
        $error = "Rellena correo y contraseña.";
    } else {
        // Buscar usuario por correo
        $sql = "SELECT pk_idusuario, nombre, apellidos, contraseña FROM tbl_usuario WHERE correo = ? LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res && $res->num_rows === 1) {
            $userRow = $res->fetch_assoc();
            $userId = $userRow['pk_idusuario'];
            $hash = $userRow['contraseña'];

            $password_ok = false;

            if (password_verify($contrasena, $hash)) {
                $password_ok = true;
            } else {
                if ($contrasena === $hash) {
                    $password_ok = true;
                }
            }

            if ($password_ok) {
                if ($rol === 'administrador') {
                    $sqlAdmin = "SELECT pk_idadministrador FROM tbl_administrador WHERE fk_idusuario = ? LIMIT 1";
                    $stmtAdmin = $conn->prepare($sqlAdmin);
                    $stmtAdmin->bind_param("i", $userId);
                    $stmtAdmin->execute();
                    $resAdmin = $stmtAdmin->get_result();

                    if ($resAdmin && $resAdmin->num_rows === 1) {
                        $_SESSION['user_id'] = $userId;
                        $_SESSION['user_name'] = $userRow['nombre'] . ' ' . $userRow['apellidos'];
                        $_SESSION['role'] = 'administrador';
                        header("Location: admin_panel.php");
                        exit();
                    } else {
                        $error = "No tienes permisos de administrador.";
                    }
                } else {
                    $_SESSION['user_id'] = $userId;
                    $_SESSION['user_name'] = $userRow['nombre'] . ' ' . $userRow['apellidos'];
                    $_SESSION['role'] = 'cliente';

                    header("Location: pedidos.php");
                    exit();
                }
            } else {
                $error = "Contraseña incorrecta.";
            }
        } else {
            $error = "No existe un usuario con ese correo.";
        }
    }
}
?>
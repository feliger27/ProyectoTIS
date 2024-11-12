<?php
include '../conexion.php'; // Conexión a la base de datos

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = $_POST['token'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Verificar si el token es válido y no ha expirado
    $query = "SELECT * FROM password_resets WHERE token = ? AND expiry > NOW()";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Verificar que las contraseñas coinciden
        if ($new_password == $confirm_password) {
            // Validación de la contraseña (longitud, mayúsculas, números, caracteres especiales)
            if (strlen($new_password) >= 8 && strlen($new_password) <= 16 &&
                preg_match("/[A-Z]/", $new_password) &&
                preg_match("/\d/", $new_password) &&
                preg_match("/[!@#$%^&*()]/", $new_password)) {

                // Actualizar la contraseña del usuario
                $row = $result->fetch_assoc();
                $email = $row['email'];

                // Actualizar la contraseña en la base de datos
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $query_update = "UPDATE usuario SET contrasenia = ? WHERE correo_electronico = ?";
                $stmt_update = $conexion->prepare($query_update);
                $stmt_update->bind_param("ss", $hashed_password, $email);
                $stmt_update->execute();

                // Eliminar el token (para evitar que se use nuevamente)
                $query_delete = "DELETE FROM password_resets WHERE token = ?";
                $stmt_delete = $conexion->prepare($query_delete);
                $stmt_delete->bind_param("s", $token);
                $stmt_delete->execute();

                echo "<div class='alert alert-success'>Contraseña restablecida exitosamente. Ahora puedes <a href='login.php'>iniciar sesión</a>.</div>";
            } else {
                echo "<div class='alert alert-danger'>La nueva contraseña debe tener entre 8 y 16 caracteres e incluir al menos una letra mayúscula, un número y un carácter especial.</div>";
            }
        } else {
            echo "<div class='alert alert-danger'>Las contraseñas no coinciden. Intenta nuevamente.</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>Enlace no válido o caducado.</div>";
    }
}
?>

<form action="restablecer.php" method="POST">
    <input type="hidden" name="token" value="<?php echo $_GET['token']; ?>">
    <input type="password" name="new_password" placeholder="Nueva contraseña" required>
    <input type="password" name="confirm_password" placeholder="Confirmar nueva contraseña" required>
    <button type="submit">Restablecer Contraseña</button>
</form>

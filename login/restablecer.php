<?php
include '../conexion.php'; // Conexión a la base de datos

// Verificar si el token es válido y no ha expirado antes de mostrar el formulario
if (isset($_GET['token'])) {
    $token = $_GET['token'];
    $query = "SELECT * FROM password_resets WHERE token = ? AND expiry > NOW()";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        // Si el token no es válido o ha caducado, muestra un mensaje de error
        echo "<div class='alert alert-danger'>El enlace de restablecimiento de contraseña no es válido o ha caducado.</div>";
        exit; // Salimos para que no se muestre el formulario
    }
} else {
    echo "<div class='alert alert-danger'>Falta el token de restablecimiento de contraseña.</div>";
    exit;
}

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
            if (
                strlen($new_password) >= 8 && strlen($new_password) <= 16 &&
                preg_match("/[A-Z]/", $new_password) &&
                preg_match("/\d/", $new_password) &&
                preg_match("/[!@#$%^&*()_+\-=\[\]{};':\"\\|,.<>\/?]+/", $new_password)
            ) {
                $row = $result->fetch_assoc();
                $email = $row['email'];

                // Actualizar la contraseña en la base de datos
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $query_update = "UPDATE usuario SET contrasenia = ? WHERE correo_electronico = ?";
                $stmt_update = $conexion->prepare($query_update);
                $stmt_update->bind_param("ss", $hashed_password, $email);
                $stmt_update->execute();

                // Eliminar el token
                $query_delete = "DELETE FROM password_resets WHERE token = ?";
                $stmt_delete = $conexion->prepare($query_delete);
                $stmt_delete->bind_param("s", $token);
                $stmt_delete->execute();

                header("Location: login.php?message=success");
                exit;
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
    <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
    <input type="password" name="new_password" placeholder="Nueva contraseña" required>
    <input type="password" name="confirm_password" placeholder="Confirmar nueva contraseña" required>
    <button type="submit">Restablecer Contraseña</button>
</form>




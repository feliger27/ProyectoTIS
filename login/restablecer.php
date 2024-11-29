<?php
include '../conexion.php'; // Conexión a la base de datos

// Obtener el token ya sea del GET (cuando se carga la página) o del POST (cuando se envía el formulario)
$token = $_GET['token'] ?? $_POST['token'] ?? null;

// Verificar si el token es válido y no ha expirado antes de mostrar el formulario
if ($token) {
    $query = "SELECT * FROM password_resets WHERE token = ? AND expiry > NOW()";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo "<div class='alert alert-danger text-center'>El enlace de restablecimiento de contraseña no es válido o ha caducado.</div>";
    } else {
        // Si el token es válido, mostramos el formulario y manejamos la lógica del POST
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $new_password = $_POST['new_password'];
            $confirm_password = $_POST['confirm_password'];

            $query = "SELECT * FROM password_resets WHERE token = ? AND expiry > NOW()";
            $stmt = $conexion->prepare($query);
            $stmt->bind_param("s", $token);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
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
                        echo "<div class='alert alert-danger text-center'>La nueva contraseña debe tener entre 8 y 16 caracteres e incluir al menos una letra mayúscula, un número y un carácter especial.</div>";
                    }
                } else {
                    echo "<div class='alert alert-danger text-center'>Las contraseñas no coinciden. Intenta nuevamente.</div>";
                }
            } else {
                echo "<div class='alert alert-danger text-center'>Enlace no válido o caducado.</div>";
            }
        }
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
    <title>Restablecer Contraseña</title>
</head>

<body>
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="col-md-6">
            <h2 class="text-center mb-4">Restablecer Contraseña</h2>

            <!-- Formulario de restablecimiento de contraseña -->
            <form action="restablecer.php" method="POST">
                <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">

                <div class="form-group mb-3">
                    <label for="new_password">Nueva Contraseña</label>
                    <input type="password" class="form-control" id="new_password" name="new_password" placeholder="Nueva contraseña" required>
                </div>

                <div class="form-group mb-3">
                    <label for="confirm_password">Confirmar Nueva Contraseña</label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirmar nueva contraseña" required>
                </div>

                <div class="form-group text-center mt-4">
                    <button type="submit" class="btn btn-block" style="background-color: #fd7e14; color: white;">Restablecer Contraseña</button>
                </div>

                <div class="text-center mt-3">
                    <a href="login.php" class="btn btn-link">Volver al inicio de sesión</a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

<?php
    }
} else {
    echo "<div class='alert alert-danger text-center'>Falta el token de restablecimiento de contraseña.</div>";
}
?>
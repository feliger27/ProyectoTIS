<?php
include '../conexion.php'; // Conexión a la base de datos
include '../funciones/notificar_usuario/notificar_usuario.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    // Verificar si el correo está registrado
    $query = "SELECT * FROM usuario WHERE correo_electronico = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Generar un token único
        $token = bin2hex(random_bytes(50));
        $expiry = date("Y-m-d H:i:s", strtotime("+24 hours"));

        // Insertar el token y la fecha de expiración en la base de datos
        $query_token = "INSERT INTO password_resets (email, token, expiry) VALUES (?, ?, ?)";
        $stmt = $conexion->prepare($query_token);
        $stmt->bind_param("sss", $email, $token, $expiry);
        $stmt->execute();

        // Llamar a la función para enviar el correo
        if (enviarCorreoRestablecimiento($email, $token)) {
            echo "<div class='alert alert-success'>Revisa tu correo electrónico para restablecer tu contraseña.</div>";
        } else {
            echo "<div class='alert alert-danger'>Error al enviar el correo de restablecimiento.</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>Correo no registrado.</div>";
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

            <!-- Mostrar mensaje de éxito o error si existen -->
            <?php if (!empty($success_message)): ?>
                <div class="alert alert-success mb-3"><?php echo $success_message; ?></div>
            <?php elseif (!empty($error_message)): ?>
                <div class="alert alert-danger mb-3"><?php echo $error_message; ?></div>
            <?php endif; ?>

            <!-- Formulario de restablecimiento de contraseña -->
            <form action="restablecer_contraseña.php" method="POST">
                <div class="form-group">
                    <label for="email">Correo Electrónico</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Introduce tu correo electrónico" required>
                </div>

                <div class="form-group text-center mt-4">
                    <button type="submit" class="btn btn-block" style="background-color: #fd7e14; color: white;">Solicitar restablecimiento</button>
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


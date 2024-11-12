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

<!-- Formulario para solicitar el restablecimiento -->
<form action="restablecer_contraseña.php" method="POST">
    <input type="email" name="email" placeholder="Introduce tu correo electrónico" required>
    <button type="submit">Solicitar restablecimiento</button>
</form>

<?php
include '../conexion.php';
session_start();

$error_message = ''; // Inicializar la variable de mensaje de error

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = stripslashes($_POST['username']);
    $username = mysqli_real_escape_string($conexion, $username);

    $password = stripslashes($_POST['password']);
    $password = mysqli_real_escape_string($conexion, $password);

    // Consulta para obtener el usuario
    $query = "SELECT id_usuario, correo_electronico, contrasenia FROM usuario WHERE correo_electronico = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Verificar la contraseña
        if (password_verify($password, $user['contrasenia'])) {
            // Inicio de sesión exitoso: almacenar datos en la sesión
            $_SESSION['user_id'] = $user['id_usuario'];
            $_SESSION['username'] = $user['correo_electronico'];

            // Cargar permisos en la sesión
            $permissions = [];
            $query_perms = "SELECT p.nombre_permiso FROM permiso p
                            JOIN rol_permiso rp ON p.id_permiso = rp.id_permiso
                            JOIN usuario_rol ur ON rp.id_rol = ur.id_rol
                            WHERE ur.id_usuario = ?";
            $stmt_perms = $conexion->prepare($query_perms);
            $stmt_perms->bind_param("i", $user['id_usuario']);
            $stmt_perms->execute();
            $result_perms = $stmt_perms->get_result();

            while ($perm = $result_perms->fetch_assoc()) {
                $permissions[] = $perm['nombre_permiso'];
            }
            $_SESSION['permissions'] = $permissions; // Guardar permisos en la sesión
            $stmt_perms->close();

            // Redirigir al usuario a la página principal
            header("Location: ../index/index-lobby.php");
            exit();
        } else {
            // Contraseña incorrecta
            $error_message = "<div class='alert alert-danger'>Usuario o contraseña incorrectos.</div>";
        }
    } else {
        // Usuario no encontrado
        $error_message = "<div class='alert alert-danger'>Usuario no encontrado.</div>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
    <title>Login</title>
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="col-md-6">
            <h2 class="text-center mb-4">Inicia Sesión</h2>

            <!-- Mostrar mensaje de error si existe -->
            <?php if (!empty($error_message)): ?>
                <div class="mb-3"><?php echo $error_message; ?></div>
            <?php endif; ?>

            <!-- Formulario de login -->
            <form action="" method="POST">
                <div class="form-group">
                    <label for="username">Correo Electrónico</label>
                    <input type="email" class="form-control" id="username" name="username" required>
                </div>

                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>

                <div class="form-group text-center mt-4">
                    <button type="submit" class="btn btn-block" style="background-color: #fd7e14; color: white;">Entrar</button>
                </div>

                <div class="text-center mt-3">
                    <p>¿No estás registrado aún? <a href="registration.php">Regístrate aquí</a></p>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

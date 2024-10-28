<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
    <title>Registro de Usuario</title>
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="col-md-6">
            <h2 class="text-center mb-4">Regístrate para disfrutar de la experiencia de HamburGeeks</h2>

            <?php
            require('../conexion.php');
            
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                // Limpiar y validar los datos del formulario
                $nombre = stripslashes($_POST['nombre']);
                $nombre = mysqli_real_escape_string($conexion, $nombre);

                $apellido = stripslashes($_POST['apellido']);
                $apellido = mysqli_real_escape_string($conexion, $apellido);
                
                $email = stripslashes($_POST['email']);
                $email = mysqli_real_escape_string($conexion, $email);

                $password = stripslashes($_POST['password']);
                $password = mysqli_real_escape_string($conexion, $password);

                $confirm_password = stripslashes($_POST['confirm_password']);
                $confirm_password = mysqli_real_escape_string($conexion, $confirm_password);

                $telefono = stripslashes($_POST['telefono']);
                $telefono = mysqli_real_escape_string($conexion, $telefono);

                if ($password !== $confirm_password) {
                    echo "<div class='alert alert-danger'>Las contraseñas no coinciden.</div>";
                } else {
                    // Verificar si el correo electrónico ya existe
                    $check_email_query = "SELECT correo_electronico FROM usuario WHERE correo_electronico = ?";
                    $stmt = $conexion->prepare($check_email_query);
                    $stmt->bind_param("s", $email);
                    $stmt->execute();
                    $stmt->store_result();

                    if ($stmt->num_rows > 0) {
                        echo "<div class='alert alert-danger'>Este correo electrónico ya está registrado. Intente con otro o inicie sesión.</div>";
                    } else {
                        // Encriptar la contraseña
                        $password_hash = password_hash($password, PASSWORD_DEFAULT);

                        // Insertar el nuevo usuario en la base de datos
                        $query = "INSERT INTO usuario (nombre, apellido, correo_electronico, contrasenia, telefono) VALUES (?, ?, ?, ?, ?)";
                        $stmt = $conexion->prepare($query);
                        $stmt->bind_param("sssss", $nombre, $apellido, $email, $password_hash, $telefono);

                        if ($stmt->execute()) {
                            $user_id = $stmt->insert_id; // Obtener el ID del usuario recién registrado
                            
                            // Asignar el rol `cliente` al nuevo usuario
                            $rol_cliente_id = 2; // Cambia este número al ID real del rol `cliente`
                            $query_rol = "INSERT INTO usuario_rol (id_usuario, id_rol) VALUES (?, ?)";
                            $stmt_rol = $conexion->prepare($query_rol);
                            $stmt_rol->bind_param("ii", $user_id, $rol_cliente_id);
                            $stmt_rol->execute();
                            $stmt_rol->close();

                            echo "<div class='alert alert-success'>Te has registrado correctamente. Haz click aquí para <a href='login.php'>Iniciar sesión</a>.</div>";
                        } else {
                            echo "<div class='alert alert-danger'>Error: " . $conexion->error . "</div>";
                        }
                    }

                    $stmt->close();
                }
            }
            ?>

            <!-- Formulario de registro -->
            <form action="" method="POST">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirmar contraseña</label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                </div>
                <div class="form-group">
                    <label for="nombre">Nombre</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" required>
                </div>
                <div class="form-group">
                    <label for="apellido">Apellido</label>
                    <input type="text" class="form-control" id="apellido" name="apellido" required>
                </div>
                <div class="form-group">
                    <label for="telefono">Teléfono</label>
                    <input type="tel" class="form-control" id="telefono" name="telefono" required>
                </div>
                <div class="form-group text-center mt-4">
                    <button type="submit" class="btn btn-block" style="background-color: #fd7e14; color: white;">Registrarme</button>
                </div>

                <!-- Enlace para redirigir al inicio de sesión -->
                <div class="text-center mt-3">
                    <p>¿Ya tienes una cuenta? <a href="login.php">Inicia sesión aquí</a></p>
                </div>

                <div class="text-center mt-3">
                    <small>
                        Al pulsar en "registrarme" estás aceptando los 
                        <a href="#">Términos y condiciones</a> y 
                        <a href="#">Política de privacidad de HamburGeeks</a>, 
                        confirmando que eres mayor de 12 años.
                    </small>
                </div>
            </form>

        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>



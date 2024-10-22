


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title>Registro de Usuario</title>
</head>
<body>
    <div class= "container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="col-md-6">
            <h2 class="tex-center mb-4">Registrate para disfrutar de la experiencia de HamburGeeks</h2>

            <?php
            require('conexion.php');
            if ($_SERVER["REQUEST_METHOD"] == "POST") {

            $nombre = stripslashes($_POST['nombre']);
            $nombre = mysqli_real_escape_string($conn, $nombre);

            $apellido = stripslashes($_POST['apellido']);
            $apellido = mysqli_real_escape_string($conn, $apellido);
            
            $email = stripslashes($_POST['email']);
            $email = mysqli_real_escape_string($conn, $email);

            $password = stripslashes($_POST['password']);
            $password = mysqli_real_escape_string($conn, $password);

            $confirm_password = stripslashes($_POST['confirm_password']);
            $confirm_password = mysqli_real_escape_string($conn, $confirm_password);

            $telefono = stripslashes($_POST['telefono']);
            $telefono = mysqli_real_escape_string($conn, $telefono);

            if($password !== $confirm_password){
                echo "<div class= 'alert alert-danger'> Las contraseñas no coinciden.</div>";
            } else {
                $password_hash = password_hash($password, PASSWORD_BCRYPT);

                $query = "INSERT INTO usuario (nombre, apellido, correo_electronico, contrasenia, telefono) VALUES ('$nombre', '$apellido', '$email', '$password_hash', '$telefono')";

                if($conn->query($query) == TRUE) {
                    echo "<div class= 'alert alert-success'> Te has registrado correctamente. Haz click aqui para <a href='login.php'>Iniciar sesión</a>.</div>";
                } else {
                    echo "<div class= 'alert alert-danger'>Error: " .$conn->error . "</div>";
                }
            }
            
            } else {

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
                <div class="text-center mt-3">
                    <small>
                        Al pulsar en "registrarme" estás aceptando los 
                        <a href="#">Términos y condiciones</a> y 
                        <a href="#">Política de privacidad de HamburGeeks</a>, 
                        confirmando que eres mayor de 12 años.
                    </small>
                </div>
            </form>

            <?php } ?>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    
</body>
</html>
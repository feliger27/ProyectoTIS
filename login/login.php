<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
    <title>Login</title>

</head>
<body>
    <div class= "container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
        <div class= "col-md-6">
            <h2 class="text-center mb-4">Inicia Sesión</h2>

            <?php
            require('conexion.php');
            session_start();

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $username = stripslashes($_POST['username']);
                $username = mysqli_real_escape_string($conn, $username);
    
                $password = stripslashes($_POST['password']);
                $password = mysqli_real_escape_string($conn, $password);
    
                // Consulta para obtener el usuario
                $query = "SELECT * FROM usuario WHERE correo_electronico=TRIM('$username')";
                echo $query;
                $result = mysqli_query($conn, $query);
    
                if (!$result) {
                    // Mostrar error si la consulta falla
                    echo "<div class='alert alert-danger'>Error en la consulta a la base de datos: " . mysqli_error($conn) . "</div>";
                } else if (mysqli_num_rows($result) == 1) {
                    $user = mysqli_fetch_assoc($result);
    
                    // Verificar la contraseña
                    if (password_verify($password, $user['contrasenia'])) {
                        // Inicio de sesión exitoso
                        $_SESSION['username'] = $user['correo_electronico'];
                        $_SESSION['role'] = $user['rol_usuario'];
                        header("Location: index.php");
                        exit();
                    } else {
                        // Contraseña incorrecta
                        echo "<div class='alert alert-danger'>Usuario o contraseña incorrectos.</div>";
                    }
                } else {
                    // Usuario no encontrado
                    echo "<div class='alert alert-danger'>Usuario no encontrado.</div>";
                }
               
            }
            ?>

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

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    
</body>
</html>
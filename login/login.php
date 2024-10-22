<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title>Login</title>
</head>
<body>
    <div class= "container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
        <div class= "col-md-6">
            <h2 class="text-center mb-4">Inicia Sesión</h2>

            <?php
            require('conexion.php');
            session_start();

            if($_SERVER["REQUEST_METHOD"] == "POST") {

                $username = stripslashes($_POST['username']);
                $username = mysqli_real_escape_string($conn, $username);

                $password = stripslashes($_POST['password']);
                $password = mysqli_real_escape_string($conn, $password);

                $query = "SELECT * FROM usuario WHERE correo_electronico= '$username'";
                $result = mysqli_query($conn, $query);
                $rows = mysqli_num_rows($result);

                if($rows == 1) {
                    $user = mysqli_fetch_assoc($result);

                    if(password_verify($password, $user['contrasenia'])){
                        $_SESSION['username'] = 'username';
                        header("Location: index.php");
                        exit();
                    } else{
                        echo "<div class='alert alert-danger'>Usuario o contraseña incorrectos.</div>";
                    }
                } else {
                    echo "<div class='alert alert-danger'>Usuario o contraseña incorrectos.</div>";
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
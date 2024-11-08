<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../includes/header.php';
if (!isset($_SESSION['username'])) {
    header("Location: ../login/login.php");
    exit();
}

// Conectar a la base de datos para obtener las hamburguesas
include '../conexion.php';
$query_hamburguesas = "SELECT nombre_hamburguesa, descripcion FROM hamburguesa";
$hamburguesas = mysqli_query($conexion, $query_hamburguesas);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lobby</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <style>
        .specialties {
            margin: 50px 0;
        }
        .specialty-card {
            margin: 15px 0;
        }
        #menu{
            width: 100vw;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="text-center mt-5">
    <img src="../uploads/lobby/menu.jpg" alt="Imagen de Carga" id="menu" class="img-fluid" style="width: 100%; height: auto;">
    </div>

    <!-- Sección de Especialidades -->
    <div class="specialties">
        <h2 class="text-center">Nuestras Especialidades</h2>
        <div class="row">
            <?php while ($hamburguesa = mysqli_fetch_assoc($hamburguesas)): ?>
                <div class="col-md-4 specialty-card">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($hamburguesa['nombre_hamburguesa']) ?></h5>
                            <p class="card-text"><?= htmlspecialchars($hamburguesa['descripcion']) ?></p>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <!-- Sección Sobre Nosotros -->
    <div class="about-us text-center mt-5" style="padding-bottom: 50px;">
            <!-- Ajusta el valor según lo necesites -->
            <h2>Sobre Nosotros</h2>
            <p>Somos una empresa dedicada a ofrecer las mejores hamburguesas de la ciudad, utilizando ingredientes
                frescos y de alta calidad. Nuestro objetivo es brindar una experiencia culinaria única a nuestros
                clientes, donde cada bocado sea memorable.</p>
        </div>
</div>

<?php include '../includes/footer.php'; ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>


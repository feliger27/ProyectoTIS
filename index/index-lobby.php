<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../includes/header.php';
if (!isset($_SESSION['username'])) {
    header("Location: ../login/login.php");
    exit();
}

// Conectar a la base de datos para obtener hamburguesas destacadas
include '../conexion.php';

// Consulta de ejemplo para obtener tres hamburguesas de muestra con la columna 'imagen' corregida
$query_hamburguesas_destacadas = "SELECT nombre_hamburguesa, descripcion, imagen FROM hamburguesa LIMIT 3";
$hamburguesas_destacadas = mysqli_query($conexion, $query_hamburguesas_destacadas);

// Verificación de la consulta
if (!$hamburguesas_destacadas) {
    die("Error en la consulta de hamburguesas destacadas: " . mysqli_error($conexion));
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurante Virtual - Bienvenidos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Fondo del body con imagen y filtro oscuro */
        body {
            background-image: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('../uploads/lobby.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            color: #fff;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        /* Wrapper principal del contenido centrado */
        .content-wrapper {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            text-align: center;
        }

        /* Estilo para la sección de bienvenida */
        .hero-text {
            margin-bottom: 100px;
        }
        .hero-text h1 {
            font-size: 3.5rem;
            font-weight: bold;
            margin: 0;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.7);
        }
        .hero-text p {
            font-size: 1.5rem;
            margin: 10px 0 20px;
        }

        /* Botones de acción */
        .cta-buttons .btn {
            font-size: 1.25rem;
            font-weight: bold;
            padding: 0.75rem 2rem;
            margin: 0 0.5rem;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        .cta-buttons .btn-warning {
            color: #000;
        }
        .cta-buttons .btn:hover {
            transform: scale(1.05);
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.3);
        }

        /* Estilos para las secciones de productos destacados y acompañamientos */
        .products-section {
            width: 100%;
            max-width: 1200px;
            padding: 30px;
            color: #fff;
            text-align: center;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .products-section h2 {
            font-size: 2.5rem;
            font-weight: bold;
            color: #fff;
            margin-bottom: 20px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.7);
        }
        .products-section h3 {
            font-size: 1.8rem;
            font-weight: bold;
            color: #fff;
            margin-bottom: 40px;
            margin-top: 20px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.7);
        }
        .products-section .card {
            border: none;
            background-color: transparent;
            color: #fff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.5);
            transition: transform 0.3s, box-shadow 0.3s;
            max-width: 300px;
            margin: 15px;
            border-radius: 10px;
        }
        .products-section .card:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.7);
        }
        .card-title {
            font-weight: bold;
            font-size: 1.2rem;
        }
        .card-text {
            font-size: 1rem;
        }

        /* Estilos para el botón "Ver Más" en cada tarjeta */
        .products-section .btn-primary {
            background-color: #ffc107;
            border: none;
            color: #000;
            border-radius: 8px;
            transition: background-color 0.3s ease, transform 0.3s;
        }
        .products-section .btn-primary:hover {
            background-color: #e0a800;
            transform: scale(1.05);
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.3);
        }

        /* Efecto hover en el texto de los productos */
        .card-title:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <!-- Contenido Flotante Principal -->
    <div class="content-wrapper">
        <!-- Texto de Bienvenida -->
        <div class="hero-text">
            <h1>Bienvenidos a HamburGeeks</h1>
            <p>¡Explora nuestro menú y realiza tu pedido ahora!</p>
            <div class="cta-buttons">
                <a href="menu.html" class="btn btn-warning">Ver Menú</a>
                <a href="login.html" class="btn btn-light">Iniciar Sesión</a>
            </div>
        </div>
    </div>

    <!-- Sección de Productos Destacados -->
    <section class="products-section">
        <h2>Nuestros Productos Destacados</h2>
        <h3>Hamburguesas</h3>
        <div class="row justify-content-center">
            <?php while ($hamburguesa = mysqli_fetch_assoc($hamburguesas_destacadas)): ?>
                <div class="col-md-4 mb-4 d-flex justify-content-center">
                    <div class="card">
                        <img src="<?php echo '../uploads/hamburguesas/' . $hamburguesa['imagen']; ?>" alt="Imagen de <?php echo $hamburguesa['nombre_hamburguesa']; ?>" class="card-img-top">
                        <div class="card-body text-center">
                            <h5 class="card-title"><?php echo $hamburguesa['nombre_hamburguesa']; ?></h5>
                            <p class="card-text"><?php echo $hamburguesa['descripcion']; ?></p>
                            <a href="detalle-producto.php?nombre=<?php echo urlencode($hamburguesa['nombre_hamburguesa']); ?>" class="btn btn-primary">Ver Más</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>

        <h3>Acompañamientos</h3>
        <div class="row justify-content-center">
            <?php 
            // Ejemplos de acompañamientos
            $acompanamientos = [
                ["nombre" => "Papas Fritas", "descripcion" => "Crujientes papas fritas.", "imagen" => "papas_fritas.jpg"],
                ["nombre" => "Aros de Cebolla", "descripcion" => "Aros de cebolla dorados y crujientes.", "imagen" => "aros_cebolla.jpg"],
                ["nombre" => "Nuggets de Pollo", "descripcion" => "Nuggets de pollo dorados.", "imagen" => "nuggets_pollo.jpg"]
            ];

            foreach ($acompanamientos as $acompanamiento): ?>
                <div class="col-md-4 mb-4 d-flex justify-content-center">
                    <div class="card">
                        <img src="<?php echo '../uploads/acompanamientos/' . $acompanamiento['imagen']; ?>" alt="Imagen de <?php echo $acompanamiento['nombre']; ?>" class="card-img-top">
                        <div class="card-body text-center">
                            <h5 class="card-title"><?php echo $acompanamiento['nombre']; ?></h5>
                            <p class="card-text"><?php echo $acompanamiento['descripcion']; ?></p>
                            <a href="detalle-producto.php?nombre=<?php echo urlencode($acompanamiento['nombre']); ?>" class="btn btn-primary">Ver Más</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

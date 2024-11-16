<?php
include '../conexion.php';

// Obtener el nombre del producto desde la URL
$nombre_producto = isset($_GET['nombre']) ? $_GET['nombre'] : '';

// Consultar la información del producto según el nombre
$query_producto = "SELECT * FROM hamburguesa WHERE nombre_hamburguesa = ?";
$stmt = $conexion->prepare($query_producto);
$stmt->bind_param("s", $nombre_producto);
$stmt->execute();
$result = $stmt->get_result();
$producto = $result->fetch_assoc();

// Verificar si se encontró el producto
if (!$producto) {
    echo "<div class='alert alert-danger'>Producto no encontrado.</div>";
    exit;
}

// Obtener los valores del producto
$nombre_hamburguesa = $producto['nombre_hamburguesa'];
$descripcion = $producto['descripcion'];
$imagen = $producto['imagen'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle del Producto - <?php echo $nombre_hamburguesa; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .product-detail {
            margin-top: 50px;
        }
        .product-detail .image {
            max-width: 100%;
            height: auto;
        }
        .product-detail .content {
            padding-left: 30px;
        }
        .product-detail .title {
            font-size: 2rem;
            font-weight: bold;
        }
        .product-detail .description {
            font-size: 1.2rem;
            margin-top: 20px;
        }
        .product-detail .back-btn {
            margin-top: 30px;
        }
    </style>
</head>
<body>

<div class="container product-detail">
    <div class="row">
        <!-- Imagen del producto -->
        <div class="col-md-6">
            <img src="<?php echo '../uploads/hamburguesas/' . $imagen; ?>" alt="Imagen de <?php echo $nombre_hamburguesa; ?>" class="image">
        </div>
        
        <!-- Detalles del producto -->
        <div class="col-md-6 content">
            <h2 class="title"><?php echo $nombre_hamburguesa; ?></h2>
            <p class="description"><?php echo $descripcion; ?></p>

            <a href="index-lobby.php" class="btn btn-primary back-btn">Volver al Menú</a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$stmt->close();
?>

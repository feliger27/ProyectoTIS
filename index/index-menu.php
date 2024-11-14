<?php
// Conexión a la base de datos
include('../conexion.php');

// Obtener la categoría seleccionada
$filtroCategoria = isset($_GET['categoria']) ? $_GET['categoria'] : '';
$filtroPrecioMin = isset($_GET['precio_min']) ? (int)$_GET['precio_min'] : 0;
$filtroPrecioMax = isset($_GET['precio_max']) ? (int)$_GET['precio_max'] : 10000;
$filtroIngrediente = isset($_GET['ingrediente']) ? $_GET['ingrediente'] : '';

$categorias = [
    "Todas" => "fa-list",
    "Combos" => "fa-box",
    "Hamburguesas" => "fa-hamburger",
    "Acompañamientos" => "fa-utensils",
    "Bebidas" => "fa-coffee",
    "Postres" => "fa-ice-cream"
];

// Determinar la tabla e imagen según la categoría seleccionada
switch ($filtroCategoria) {
    case "Combos":
        $tabla = "combo";
        $imagenPath = "../uploads/combos/";
        break;
    case "Hamburguesas":
        $tabla = "hamburguesa";
        $imagenPath = "../uploads/hamburguesas/";
        break;
    case "Acompañamientos":
        $tabla = "acompaniamiento";
        $imagenPath = "../uploads/acompaniamientos/";
        break;
    case "Bebidas":
        $tabla = "bebida";
        $imagenPath = "../uploads/bebidas/";
        break;
    case "Postres":
        $tabla = "postre";
        $imagenPath = "../uploads/postres/";
        break;
    default:
        $tabla = "";
        $imagenPath = "../uploads/";
        break;
}

if ($tabla) {
    $query = "SELECT id_{$tabla} AS id, nombre_{$tabla} AS nombre, descripcion, precio, imagen FROM $tabla WHERE precio BETWEEN $filtroPrecioMin AND $filtroPrecioMax";
} else {
    $query = "
        SELECT id_combo AS id, nombre_combo AS nombre, descripcion, precio, CONCAT('../uploads/combos/', imagen) AS imagen FROM combo WHERE precio BETWEEN $filtroPrecioMin AND $filtroPrecioMax
        UNION ALL
        SELECT id_hamburguesa AS id, nombre_hamburguesa AS nombre, descripcion, precio, CONCAT('../uploads/hamburguesas/', imagen) AS imagen FROM hamburguesa WHERE precio BETWEEN $filtroPrecioMin AND $filtroPrecioMax
        UNION ALL
        SELECT id_acompaniamiento AS id, nombre_acompaniamiento AS nombre, '' AS descripcion, precio, CONCAT('../uploads/acompaniamientos/', imagen) AS imagen FROM acompaniamiento WHERE precio BETWEEN $filtroPrecioMin AND $filtroPrecioMax
        UNION ALL
        SELECT id_bebida AS id, nombre_bebida AS nombre, '' AS descripcion, precio, CONCAT('../uploads/bebidas/', imagen) AS imagen FROM bebida WHERE precio BETWEEN $filtroPrecioMin AND $filtroPrecioMax
        UNION ALL
        SELECT id_postre AS id, nombre_postre AS nombre, '' AS descripcion, precio, CONCAT('../uploads/postres/', imagen) AS imagen FROM postre WHERE precio BETWEEN $filtroPrecioMin AND $filtroPrecioMax";
}

if ($filtroCategoria === "Hamburguesas" && $filtroIngrediente) {
    $query .= " AND ingredientes LIKE '%$filtroIngrediente%'";
}

$resultado = $conexion->query($query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menú - HamburGeeks</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            background: linear-gradient(to right, #f8fafc, #e2e8f0);
            color: #333;
        }
        .header-section {
            text-align: center;
            padding: 2rem;
            color: #2d3748;
        }
        .header-section h1 {
            font-weight: bold;
            font-size: 2.5rem;
            color: #1a202c;
        }
        .filter-form {
            background-color: #edf2f7;
            border-radius: 10px;
            padding: 1.5rem;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
        }
        .filter-buttons {
            display: flex;
            justify-content: center;
            gap: 1rem;
            flex-wrap: wrap;
        }
        .filter-button {
            background-color: #ff8c00;
            color: #fff;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            font-size: 1rem;
            font-weight: bold;
            border: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: background-color 0.3s;
        }
        .filter-button:hover {
            background-color: #e07b00;
        }
        .card {
            border: none;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }
        .card:hover {
            transform: scale(1.05);
        }
        .card img {
            border-radius: 10px 10px 0 0;
            height: 200px;
            object-fit: cover;
        }
        .card-title {
            color: #1a202c;
            font-weight: 600;
        }
        .card-text {
            font-size: 0.9rem;
            color: #718096;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header-section">
            <h1>Descubre Nuestros Sabores</h1>
            <p>Explora nuestro menú y encuentra algo delicioso para ordenar</p>
        </div>

        <!-- Botones de Filtro -->
        <div class="filter-buttons">
            <?php foreach ($categorias as $categoria => $icono): ?>
                <form action="index-menu.php" method="GET" style="display: inline;">
                    <input type="hidden" name="categoria" value="<?php echo $categoria; ?>">
                    <button type="submit" class="filter-button">
                        <i class="fas <?php echo $icono; ?>"></i> <?php echo $categoria; ?>
                    </button>
                </form>
            <?php endforeach; ?>
        </div>

        <!-- Listado de productos -->
        <div class="row mt-4">
            <?php if ($resultado->num_rows > 0): ?>
                <?php while ($producto = $resultado->fetch_assoc()): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 shadow-sm">
                            <img src="<?php echo file_exists($producto['imagen']) ? $producto['imagen'] : '../uploads/default.jpg'; ?>" class="card-img-top" alt="<?php echo isset($producto['nombre']) ? $producto['nombre'] : 'Producto'; ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo isset($producto['nombre']) ? $producto['nombre'] : 'Producto'; ?></h5>
                                <p class="card-text"><?php echo isset($producto['descripcion']) ? $producto['descripcion'] : 'Descripción no disponible'; ?></p>
                                <p class="text-primary fw-bold">Precio: $<?php echo isset($producto['precio']) ? number_format($producto['precio'], 0, ',', '.') : '0'; ?></p>
                                <?php if (isset($producto['id'])): ?>
                                    <button onclick="agregarAlCarrito(<?php echo $producto['id']; ?>)" class="btn btn-custom w-100">Agregar al Carrito</button>
                                <?php else: ?>
                                    <button class="btn btn-secondary w-100" disabled>No disponible</button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="text-center">No se encontraron productos con los filtros aplicados.</p>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function agregarAlCarrito(idProducto) {
            alert('Producto ' + idProducto + ' agregado al carrito.');
        }
    </script>
</body>
</html>

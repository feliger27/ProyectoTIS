<?php
// Conexión a la base de datos
include('../conexion.php');
include('../includes/header.php');

// Obtener la categoría seleccionada
$filtroCategoria = isset($_GET['categoria']) ? $_GET['categoria'] : 'Todas';

// Lista de categorías con sus iconos
$categorias = [
    "Todas" => "fa-list",
    "Combos" => "fa-box",
    "Hamburguesas" => "fa-hamburger",
    "Acompañamientos" => "fa-utensils",
    "Bebidas" => "fa-coffee",
    "Postres" => "fa-ice-cream"
];

// Ajustar la consulta SQL según la categoría seleccionada
$query = "";
switch ($filtroCategoria) {
    case "Combos":
        $query = "
            SELECT 
                'combo' AS categoria,
                c.id_combo AS id_producto,
                c.nombre_combo AS nombre_producto,
                c.precio AS precio_producto,
                CONCAT('../uploads/combos/', c.imagen) AS imagen_producto,
                1 AS orden
            FROM combo c
            ORDER BY orden";
        break;

    case "Hamburguesas":
        $query = "
            SELECT 
                'hamburguesa' AS categoria,
                h.id_hamburguesa AS id_producto,
                h.nombre_hamburguesa AS nombre_producto,
                h.precio AS precio_producto,
                CONCAT('../uploads/hamburguesas/', h.imagen) AS imagen_producto,
                2 AS orden
            FROM hamburguesa h
            ORDER BY orden";
        break;

    case "Acompañamientos":
        $query = "
            SELECT 
                'acompaniamiento' AS categoria,
                a.id_acompaniamiento AS id_producto,
                a.nombre_acompaniamiento AS nombre_producto,
                a.precio AS precio_producto,
                CONCAT('../uploads/acompaniamientos/', a.imagen) AS imagen_producto,
                3 AS orden
            FROM acompaniamiento a
            ORDER BY orden";
        break;

    case "Bebidas":
        $query = "
            SELECT 
                'bebida' AS categoria,
                b.id_bebida AS id_producto,
                b.nombre_bebida AS nombre_producto,
                b.precio AS precio_producto,
                CONCAT('../uploads/bebidas/', b.imagen) AS imagen_producto,
                4 AS orden
            FROM bebida b
            ORDER BY orden";
        break;

    case "Postres":
        $query = "
            SELECT 
                'postre' AS categoria,
                ps.id_postre AS id_producto,
                ps.nombre_postre AS nombre_producto,
                ps.precio AS precio_producto,
                CONCAT('../uploads/postres/', ps.imagen) AS imagen_producto,
                5 AS orden
            FROM postre ps
            ORDER BY orden";
        break;

    default:
        $query = "
            SELECT 
                'combo' AS categoria,
                c.id_combo AS id_producto,
                c.nombre_combo AS nombre_producto,
                c.precio AS precio_producto,
                CONCAT('../uploads/combos/', c.imagen) AS imagen_producto,
                1 AS orden
            FROM combo c
            UNION ALL
            SELECT 
                'hamburguesa' AS categoria,
                h.id_hamburguesa AS id_producto,
                h.nombre_hamburguesa AS nombre_producto,
                h.precio AS precio_producto,
                CONCAT('../uploads/hamburguesas/', h.imagen) AS imagen_producto,
                2 AS orden
            FROM hamburguesa h
            UNION ALL
            SELECT 
                'acompaniamiento' AS categoria,
                a.id_acompaniamiento AS id_producto,
                a.nombre_acompaniamiento AS nombre_producto,
                a.precio AS precio_producto,
                CONCAT('../uploads/acompaniamientos/', a.imagen) AS imagen_producto,
                3 AS orden
            FROM acompaniamiento a
            UNION ALL
            SELECT 
                'bebida' AS categoria,
                b.id_bebida AS id_producto,
                b.nombre_bebida AS nombre_producto,
                b.precio AS precio_producto,
                CONCAT('../uploads/bebidas/', b.imagen) AS imagen_producto,
                4 AS orden
            FROM bebida b
            UNION ALL
            SELECT 
                'postre' AS categoria,
                ps.id_postre AS id_producto,
                ps.nombre_postre AS nombre_producto,
                ps.precio AS precio_producto,
                CONCAT('../uploads/postres/', ps.imagen) AS imagen_producto,
                5 AS orden
            FROM postre ps
            ORDER BY orden";
        break;
}

// Ejecutar la consulta
$resultado = $conexion->query($query);

// Obtener promociones activas
$hoy = date('Y-m-d H:i:s');
$queryPromociones = "SELECT * FROM promocion WHERE fecha_inicio <= '$hoy' AND fecha_fin >= '$hoy'";
$resultadoPromociones = $conexion->query($queryPromociones);

$promociones = [];
if ($resultadoPromociones->num_rows > 0) {
    while ($promo = $resultadoPromociones->fetch_assoc()) {
        $promociones[] = $promo;
    }
}

// Función para calcular el precio promocional
function calcularPrecioPromocional($precio, $descuento) {
    return $precio - ($precio * $descuento / 100);
}

// Función para buscar una promoción activa para un producto
function obtenerPromocion($productoId, $categoria, $promociones) {
    foreach ($promociones as $promo) {
        if (($categoria === 'hamburguesa' && isset($promo['id_hamburguesa']) && $promo['id_hamburguesa'] == $productoId) ||
            ($categoria === 'bebida' && isset($promo['id_bebida']) && $promo['id_bebida'] == $productoId) ||
            ($categoria === 'acompaniamiento' && isset($promo['id_acompaniamiento']) && $promo['id_acompaniamiento'] == $productoId) ||
            ($categoria === 'postre' && isset($promo['id_postre']) && $promo['id_postre'] == $productoId) ||
            ($categoria === 'combo' && isset($promo['id_combo']) && $promo['id_combo'] == $productoId)) {
            return $promo;
        }
    }
    return null;
}
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
            margin-top: 80px;
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
        .filter-buttons {
            display: flex;
            justify-content: center;
            gap: 1rem;
            flex-wrap: wrap;
        }
        .filter-button {
            background-color: #d35400;
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
            background-color: #e67e22;
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
            width: 100%;
            object-fit: cover;
        }
        .card-title {
            color: #2c3e50;
            font-weight: bold;
            font-size: 1.2rem;
            text-align: center;
        }
        .descripcion-producto {
            font-size: 0.95rem;
            color: #555;
            text-align: center;
            margin: 0.5rem 0;
        }
        .precios-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 10px;
        }
        .precios {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-grow: 1;
            gap: 8px;
        }
        .precio-normal {
            font-size: 1.2rem;
            color: #7f8c8d; /* Gris */
            text-decoration: line-through;
        }
        .precio-promocional {
            font-size: 1.4rem;
            color: #27ae60; /* Verde */
            font-weight: bold;
        }
        .precio-destacado {
            font-size: 1.4rem;
            color: #d35400; /* Naranjo */
            font-weight: bold;
        }
        .icono-carrito {
            background-color: #d35400;
            color: #fff;
            width: 40px;
            height: 40px;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 50%;
            font-size: 1.2rem;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }
        .icono-carrito:hover {
            background-color: #e67e22;
            transform: scale(1.1);
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
        <div class="row my-4">
            <?php if ($resultado->num_rows > 0): ?>
                <?php while ($producto = $resultado->fetch_assoc()): ?>
                    <?php
                    $promocion = obtenerPromocion($producto['id_producto'], $producto['categoria'], $promociones);
                    $precioPromocional = $promocion ? calcularPrecioPromocional($producto['precio_producto'], $promocion['porcentaje_descuento']) : null;
                    ?>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 shadow-sm">
                            <img src="<?php echo file_exists($producto['imagen_producto']) ? $producto['imagen_producto'] : '../uploads/default.jpg'; ?>" class="card-img-top" alt="<?php echo $producto['nombre_producto']; ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $producto['nombre_producto']; ?></h5>
                                <?php if (!empty($producto['descripcion'])): ?>
                                    <p class="descripcion-producto"><?php echo $producto['descripcion']; ?></p>
                                <?php endif; ?>
                                <div class="precios-container">
                                    <div class="precios">
                                        <?php if ($precioPromocional): ?>
                                            <span class="precio-normal">$<?php echo number_format($producto['precio_producto'], 0, ',', '.'); ?></span>
                                            <span class="precio-promocional">$<?php echo number_format($precioPromocional, 0, ',', '.'); ?></span>
                                        <?php else: ?>
                                            <span class="precio-destacado">$<?php echo number_format($producto['precio_producto'], 0, ',', '.'); ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="icono-carrito" onclick="agregarAlCarrito(<?php echo $producto['id_producto']; ?>)">
                                        <i class="fas fa-plus"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="text-center">No se encontraron productos.</p>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function agregarAlCarrito(idProducto) {
            alert('Producto ' + idProducto + ' agregado al carrito.');
        }
    </script>
    <?php include '../includes/footer.php'; ?>
</body>
</html>

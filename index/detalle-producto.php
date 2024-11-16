<?php
include '../conexion.php';
include '../includes/header.php';
// Obtener los parámetros desde la URL
$nombre_producto = isset($_GET['nombre']) ? $_GET['nombre'] : '';
$tipo_producto = isset($_GET['tipo']) ? $_GET['tipo'] : '';

// Verificar que el tipo de producto esté definido
if (empty($tipo_producto)) {
    echo "<div class='alert alert-danger'>Tipo de producto no especificado.</div>";
    exit;
}

// Lógica para determinar el nombre del producto y la ruta de la imagen según el tipo de producto
switch ($tipo_producto) {
    case 'hamburguesa':
        $tabla = 'hamburguesa';
        $columna_nombre = 'nombre_hamburguesa';
        $imagenPath = '../uploads/hamburguesas/';
        break;
    case 'bebida':
        $tabla = 'bebida';
        $columna_nombre = 'nombre_bebida';
        $imagenPath = '../uploads/bebidas/';
        break;
    case 'postre':
        $tabla = 'postre';
        $columna_nombre = 'nombre_postre';
        $imagenPath = '../uploads/postres/';
        break;
    case 'acompaniamiento':
        $tabla = 'acompaniamiento';
        $columna_nombre = 'nombre_acompaniamiento';
        $imagenPath = '../uploads/acompaniamientos/';
        break;
    case 'combo':
        $tabla = 'combo';
        $columna_nombre = 'nombre_combo';
        $imagenPath = '../uploads/combos/';
        break;
    default:
        echo "<div class='alert alert-danger'>Tipo de producto no válido.</div>";
        exit;
}

// Consultar la información del producto según el nombre
$query_producto = "SELECT * FROM $tabla WHERE $columna_nombre = ?";
$stmt = $conexion->prepare($query_producto);
$stmt->bind_param("s", $nombre_producto);
$stmt->execute();
$result = $stmt->get_result();
$producto = $result->fetch_assoc();

if (!$producto) {
    echo "<div class='alert alert-danger'>Producto no encontrado.</div>";
    exit;
}

// Obtener los valores del producto
$nombre_producto = $producto[$columna_nombre];
$descripcion = $producto['descripcion'];
$imagen = $producto['imagen'];

// Si el tipo es combo, obtener los productos incluidos en el combo
$productos_combo = [];
if ($tipo_producto == 'combo') {
    // Consultar los productos incluidos en el combo
    $query_combo = "
    SELECT 
        h.nombre_hamburguesa, SUM(ch.cantidad) AS cantidad_hamburguesa,
        a.nombre_acompaniamiento, SUM(ca.cantidad) AS cantidad_acompaniamiento,
        b.nombre_bebida, SUM(cb.cantidad) AS cantidad_bebida,
        p.nombre_postre, SUM(cp.cantidad) AS cantidad_postre
    FROM combo c
    LEFT JOIN combo_hamburguesa ch ON c.id_combo = ch.id_combo
    LEFT JOIN hamburguesa h ON ch.id_hamburguesa = h.id_hamburguesa
    LEFT JOIN combo_acompaniamiento ca ON c.id_combo = ca.id_combo
    LEFT JOIN acompaniamiento a ON ca.id_acompaniamiento = a.id_acompaniamiento
    LEFT JOIN combo_bebida cb ON c.id_combo = cb.id_combo
    LEFT JOIN bebida b ON cb.id_bebida = b.id_bebida
    LEFT JOIN combo_postre cp ON c.id_combo = cp.id_combo
    LEFT JOIN postre p ON cp.id_postre = p.id_postre
    WHERE c.$columna_nombre = ?
    GROUP BY 
        h.nombre_hamburguesa, a.nombre_acompaniamiento, b.nombre_bebida, p.nombre_postre";


    $stmt_combo = $conexion->prepare($query_combo);
    $stmt_combo->bind_param("s", $nombre_producto);
    $stmt_combo->execute();
    $result_combo = $stmt_combo->get_result();

    // Recoger los productos del combo
    while ($row = $result_combo->fetch_assoc()) {
        $productos_combo[] = $row;
    }
    $stmt_combo->close();
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle del Producto - <?php echo $nombre_producto; ?></title>
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
        <div class="row mt-4">
            <!-- Imagen del producto -->
            <div class="col-md-6 mt-4">
                <img src="<?php echo $imagenPath . $imagen; ?>" alt="Imagen de <?php echo $nombre_producto; ?>"
                    class="image">
            </div>

            <!-- Detalles del producto -->
            <div class="col-md-6 content mt-4">
                <h2 class="title"><?php echo $nombre_producto; ?></h2>
                <p class="description"><?php echo $descripcion; ?></p>

                <?php if ($tipo_producto == 'combo'): ?>
                    <h3>Productos incluidos en este combo:</h3>
                    <ul>
                        <?php
                        // Inicializar un array para llevar las cantidades por producto
                        $productosAgrupados = [
                            'hamburguesas' => [],
                            'acompaniamientos' => [],
                            'bebidas' => [],
                            'postres' => []
                        ];

                        // Agrupar los productos por tipo y acumular las cantidades correctamente
                        foreach ($productos_combo as $producto_item) {
                            if ($producto_item['nombre_hamburguesa']) {
                                // Acumulamos la cantidad de la hamburguesa
                                if (!isset($productosAgrupados['hamburguesas'][$producto_item['nombre_hamburguesa']])) {
                                    $productosAgrupados['hamburguesas'][$producto_item['nombre_hamburguesa']] = 0;
                                }
                                $productosAgrupados['hamburguesas'][$producto_item['nombre_hamburguesa']] += (int) $producto_item['cantidad_hamburguesa']; // Aseguramos que la cantidad sea un número entero
                            }
                            if ($producto_item['nombre_acompaniamiento']) {
                                // Acumulamos la cantidad del acompañamiento
                                if (!isset($productosAgrupados['acompaniamientos'][$producto_item['nombre_acompaniamiento']])) {
                                    $productosAgrupados['acompaniamientos'][$producto_item['nombre_acompaniamiento']] = 0;
                                }
                                $productosAgrupados['acompaniamientos'][$producto_item['nombre_acompaniamiento']] += (int) $producto_item['cantidad_acompaniamiento']; // Aseguramos que la cantidad sea un número entero
                            }
                            if ($producto_item['nombre_bebida']) {
                                // Acumulamos la cantidad de la bebida
                                if (!isset($productosAgrupados['bebidas'][$producto_item['nombre_bebida']])) {
                                    $productosAgrupados['bebidas'][$producto_item['nombre_bebida']] = 0;
                                }
                                $productosAgrupados['bebidas'][$producto_item['nombre_bebida']] += (int) $producto_item['cantidad_bebida']; // Aseguramos que la cantidad sea un número entero
                            }
                            if ($producto_item['nombre_postre']) {
                                // Acumulamos la cantidad del postre
                                if (!isset($productosAgrupados['postres'][$producto_item['nombre_postre']])) {
                                    $productosAgrupados['postres'][$producto_item['nombre_postre']] = 0;
                                }
                                $productosAgrupados['postres'][$producto_item['nombre_postre']] += (int) $producto_item['cantidad_postre']; // Aseguramos que la cantidad sea un número entero
                            }
                        }

                        // Mostrar los productos agrupados y las cantidades correctas
                        foreach ($productosAgrupados as $categoria => $productos) {
                            if (!empty($productos)) {
                                echo "<h4>" . ucfirst($categoria) . ":</h4>";
                                foreach ($productos as $nombre => $cantidad) {
                                    echo "<li>$nombre (Cantidad: $cantidad)</li>";
                                }
                            }
                        }
                        ?>
                    </ul>
                <?php endif; ?>




                <a href="index-menu.php" class="btn btn-primary back-btn">Volver al Menú</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

<?php
$stmt->close();
?>
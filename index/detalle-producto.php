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

// Si el tipo es combo, obtener los productos incluidos en el combo con las cantidades
$productos_combo = [];
if ($tipo_producto == 'combo') {
    $query_combo = "
        SELECT 
            GROUP_CONCAT(DISTINCT CONCAT(h.nombre_hamburguesa, ' (', ch.cantidad, ')') ORDER BY h.nombre_hamburguesa ASC SEPARATOR ', ') AS hamburguesas,
            GROUP_CONCAT(DISTINCT CONCAT(a.nombre_acompaniamiento, ' (', ca.cantidad, ')') ORDER BY a.nombre_acompaniamiento ASC SEPARATOR ', ') AS acompaniamientos,
            GROUP_CONCAT(DISTINCT CONCAT(b.nombre_bebida, ' (', cb.cantidad, ')') ORDER BY b.nombre_bebida ASC SEPARATOR ', ') AS bebidas,
            GROUP_CONCAT(DISTINCT CONCAT(p.nombre_postre, ' (', cp.cantidad, ')') ORDER BY p.nombre_postre ASC SEPARATOR ', ') AS postres
        FROM 
            combo c
        LEFT JOIN combo_hamburguesa ch ON c.id_combo = ch.id_combo
        LEFT JOIN hamburguesa h ON ch.id_hamburguesa = h.id_hamburguesa
        LEFT JOIN combo_acompaniamiento ca ON c.id_combo = ca.id_combo
        LEFT JOIN acompaniamiento a ON ca.id_acompaniamiento = a.id_acompaniamiento
        LEFT JOIN combo_bebida cb ON c.id_combo = cb.id_combo
        LEFT JOIN bebida b ON cb.id_bebida = b.id_bebida
        LEFT JOIN combo_postre cp ON c.id_combo = cp.id_combo
        LEFT JOIN postre p ON cp.id_postre = p.id_postre
        WHERE c.$columna_nombre = ?
    ";

    $stmt_combo = $conexion->prepare($query_combo);
    $stmt_combo->bind_param("s", $nombre_producto);
    $stmt_combo->execute();
    $result_combo = $stmt_combo->get_result();

    // Recoger los productos del combo
    $productos_combo = $result_combo->fetch_assoc();
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
        body {
            background-color: #f8f9fa;
        }

        .product-detail {
            margin-top: 80px;
            /* Aumenta el margen superior para que no esté pegada al encabezado */
        }

        .product-detail .image {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            margin-top: 30px;
            /* Agregar un poco de margen superior */
            margin-left: auto;
            margin-right: auto;
            display: block;
            /* Para centrar la imagen */
        }

        .product-detail .content {
            padding-left: 30px;
        }

        .product-detail .title {
            font-size: 2.5rem;
            font-weight: bold;
            color: #343a40;
        }

        .product-detail .description {
            font-size: 1.2rem;
            margin-top: 20px;
            color: #6c757d;
        }

        .product-detail .back-btn {
            margin-top: 30px;
            background-color: #28a745;
            border-color: #28a745;
        }

        .product-detail .back-btn:hover {
            background-color: #218838;
            border-color: #1e7e34;
        }

        .product-detail .product-details-list {
            margin-top: 30px;
        }

        .product-detail .product-details-list li {
            font-size: 1.1rem;
            color: #495057;
        }

        .product-detail .row {
            display: flex;
            align-items: center;
            /* Esto alineará verticalmente la imagen y el texto */
            justify-content: center;
            /* Centra todo horizontalmente */
        }

        .product-detail .image {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            margin-right: 30px;
            /* Esto agrega un pequeño espacio entre la imagen y el texto */
        }

        .product-detail .content {
            flex: 1;
            /* Esto asegura que el texto ocupe el espacio restante */
            margin-left: 30px;
            /* Para asegurar que el texto no esté pegado a la imagen */
        }
    </style>
</head>

<body>

    <div class="container product-detail">
        <div class="row mt-4">
            <!-- Imagen del producto -->
            <div class="col-md-6 mt-4">
                <img src="<?php echo $imagenPath . $imagen; ?>" alt="Imagen de <?php echo $nombre_producto; ?>"
                    class="image shadow-lg">
            </div>

            <!-- Detalles del producto -->
            <div class="col-md-6 content mt-4">
                <h2 class="title"><?php echo $nombre_producto; ?></h2>
                <p class="description"><?php echo $descripcion; ?></p>

                <!-- Mostrar productos del combo -->
                <?php if ($tipo_producto == 'combo'): ?>
                    <h3 class="mt-4">Productos incluidos en este combo:</h3>
                    <ul class="product-details-list">
                        <?php if ($productos_combo['hamburguesas']): ?>
                            <li><strong>Hamburguesas:</strong> <?php echo $productos_combo['hamburguesas']; ?></li>
                        <?php endif; ?>
                        <?php if ($productos_combo['acompaniamientos']): ?>
                            <li><strong>Acompañamientos:</strong> <?php echo $productos_combo['acompaniamientos']; ?></li>
                        <?php endif; ?>
                        <?php if ($productos_combo['bebidas']): ?>
                            <li><strong>Bebidas:</strong> <?php echo $productos_combo['bebidas']; ?></li>
                        <?php endif; ?>
                        <?php if ($productos_combo['postres']): ?>
                            <li><strong>Postres:</strong> <?php echo $productos_combo['postres']; ?></li>
                        <?php endif; ?>
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
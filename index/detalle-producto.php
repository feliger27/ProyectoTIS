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
        .button-group .btn {
            height: 40px; /* Ajusta la altura según sea necesario */
            padding: 8px 16px; /* Asegura que el padding sea uniforme */
            align-items: center; /* Centra el contenido del botón verticalmente */
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
        .custom-orange-btn {
    background-color: #FF7F00; /* Naranja */
    border-color: #FF7F00; /* Borde naranja */
    color: white; /* Asegurarte de que el texto sea blanco para un buen contraste */
    }

    .custom-orange-btn:hover {
    background-color: #FF8C1A; /* Un tono ligeramente más oscuro para el hover */
    border-color: #FF8C1A;
}


    </style>
</head>

<body>
    <div class="container product-detail">
        <div class="row mt-4">
            <div class="col-md-6 mt-4">
                <img src="<?php echo $imagenPath . $imagen; ?>" alt="Imagen de <?php echo $nombre_producto; ?>" class="image shadow-lg">
            </div>
            <div class="col-md-6 content mt-4">
                <h2 class="title"><?php echo $nombre_producto; ?></h2>
                <p class="description"><?php echo $descripcion; ?></p>
                <div class="button-group mt-3 d-flex justify-content-start">
        <!-- Botón para abrir el modal de reseñas -->
        <button type="button" class="btn custom-orange-btn back-btn me-2" data-bs-toggle="modal" data-bs-target="#resenasModal" id="btnResenas">
        Ver Reseñas
        </button>


        <!-- Botón para volver al menú -->
        <a href="index-menu.php" class="btn btn-primary back-btn">Volver al Menú</a>
    </div>
                
            </div>
        </div>
    </div>

<!-- Modal Mejorado para Mostrar las Reseñas -->
<div class="modal fade" id="resenasModal" tabindex="-1" aria-labelledby="resenasModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <!-- Encabezado del Modal -->
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title d-flex align-items-center" id="resenasModalLabel">
                    <i class="bi bi-star-fill me-2"></i> Reseñas de <?php echo $nombre_producto; ?>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <!-- Cuerpo del Modal -->
            <div class="modal-body" id="modalBody" style="max-height: 500px; overflow-y: auto;">
                <div class="text-center text-muted">Cargando reseñas...</div>
            </div>
            <!-- Pie del Modal -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>



    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const btnResenas = document.getElementById("btnResenas");
            btnResenas.addEventListener("click", function () {
                // Realizar la petición para cargar reseñas
                fetch('../funciones/gestionar_valoraciones/obtener_resenas.php?nombre=<?php echo urlencode($nombre_producto); ?>&tipo=<?php echo urlencode($tipo_producto); ?>')
                    .then(response => response.json())
                    .then(data => {
                        const modalBody = document.getElementById("modalBody");
                        if (data.success) {
                            let html = "";
                            data.reseñas.forEach(resena => {
                                html += `<div class="resena">
                                    <h6>${resena.usuario}</h6>
                                    <p>${resena.comentario}</p>
                                    <small class="text-muted">Puntuación: ${resena.puntuacion}/5</small>
                                    <hr>
                                </div>`;
                            });
                            modalBody.innerHTML = html;
                        } else {
                            modalBody.innerHTML = `<p class="text-danger">No se encontraron reseñas para este producto.</p>`;
                        }
                    })
                    .catch(error => {
                        document.getElementById("modalBody").innerHTML = `<p class="text-danger">Error al cargar las reseñas.</p>`;
                        console.error("Error al cargar las reseñas:", error);
                    });
            });
        });
        
    </script>
    
</body>

</html>

<?php
$stmt->close();
?>
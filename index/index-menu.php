<?php
// Conexión a la base de datos
include('../conexion.php');
include('../includes/header.php'); // Incluyendo el nuevo header

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
                CONCAT('../uploads/combos/', c.imagen) AS imagen_producto
            FROM combo c
            ORDER BY c.id_combo";
        break;

    case "Hamburguesas":
        $query = "
            SELECT 
                'hamburguesa' AS categoria,
                h.id_hamburguesa AS id_producto,
                h.nombre_hamburguesa AS nombre_producto,
                h.precio AS precio_producto,
                CONCAT('../uploads/hamburguesas/', h.imagen) AS imagen_producto
            FROM hamburguesa h
            ORDER BY h.id_hamburguesa";
        break;

    case "Acompañamientos":
        $query = "
            SELECT 
                'acompaniamiento' AS categoria,
                a.id_acompaniamiento AS id_producto,
                a.nombre_acompaniamiento AS nombre_producto,
                a.precio AS precio_producto,
                CONCAT('../uploads/acompaniamientos/', a.imagen) AS imagen_producto
            FROM acompaniamiento a
            ORDER BY a.id_acompaniamiento";
        break;

    case "Bebidas":
        $query = "
            SELECT 
                'bebida' AS categoria,
                b.id_bebida AS id_producto,
                b.nombre_bebida AS nombre_producto,
                b.precio AS precio_producto,
                CONCAT('../uploads/bebidas/', b.imagen) AS imagen_producto
            FROM bebida b
            ORDER BY b.id_bebida";
        break;

    case "Postres":
        $query = "
            SELECT 
                'postre' AS categoria,
                ps.id_postre AS id_producto,
                ps.nombre_postre AS nombre_producto,
                ps.precio AS precio_producto,
                CONCAT('../uploads/postres/', ps.imagen) AS imagen_producto
            FROM postre ps
            ORDER BY ps.id_postre";
        break;

    default:
        $query = "
            SELECT 
                'combo' AS categoria,
                c.id_combo AS id_producto,
                c.nombre_combo AS nombre_producto,
                c.precio AS precio_producto,
                CONCAT('../uploads/combos/', c.imagen) AS imagen_producto
            FROM combo c
            UNION ALL
            SELECT 
                'hamburguesa' AS categoria,
                h.id_hamburguesa AS id_producto,
                h.nombre_hamburguesa AS nombre_producto,
                h.precio AS precio_producto,
                CONCAT('../uploads/hamburguesas/', h.imagen) AS imagen_producto
            FROM hamburguesa h
            UNION ALL
            SELECT 
                'acompaniamiento' AS categoria,
                a.id_acompaniamiento AS id_producto,
                a.nombre_acompaniamiento AS nombre_producto,
                a.precio AS precio_producto,
                CONCAT('../uploads/acompaniamientos/', a.imagen) AS imagen_producto
            FROM acompaniamiento a
            UNION ALL
            SELECT 
                'bebida' AS categoria,
                b.id_bebida AS id_producto,
                b.nombre_bebida AS nombre_producto,
                b.precio AS precio_producto,
                CONCAT('../uploads/bebidas/', b.imagen) AS imagen_producto
            FROM bebida b
            UNION ALL
            SELECT 
                'postre' AS categoria,
                ps.id_postre AS id_producto,
                ps.nombre_postre AS nombre_producto,
                ps.precio AS precio_producto,
                CONCAT('../uploads/postres/', ps.imagen) AS imagen_producto
            FROM postre ps";
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

        .precios-container {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
        }

        .precio-normal {
            font-size: 1.2rem;
            color: #7f8c8d;
            text-decoration: line-through;
        }

        .precio-promocional {
            font-size: 1.4rem;
            color: #27ae60;
            font-weight: bold;
        }

        .precio-destacado {
            font-size: 1.4rem;
            color: #d35400;
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
            position: absolute;
            bottom: 15px;
            right: 15px;
            z-index: 10;
        }

        .icono-carrito:hover {
            background-color: #e67e22;
            transform: scale(1.1);
        }

        .notification {
            background-color: #343a40;
            color: #fff;
            padding: 15px 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 14px;
            font-family: 'Arial', sans-serif;
            max-width: 300px;
            animation: fadeIn 0.5s ease-in-out;
        }

        .notification span {
            flex-grow: 1;
        }

        .notification .close-btn {
            background: none;
            border: none;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
            margin-left: 10px;
        }

        .notification .close-btn:hover {
            color: #e67e22;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header-section">
            <h1>Descubre Nuestros Sabores</h1>
            <p>Explora nuestro menú y encuentra algo delicioso para ordenar</p>
        </div>

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

        <div class="row my-4">
            <?php if ($resultado->num_rows > 0): ?>
                <?php while ($producto = $resultado->fetch_assoc()): ?>
                    <?php
                    $promocion = obtenerPromocion($producto['id_producto'], $producto['categoria'], $promociones);
                    $precioPromocional = $promocion ? calcularPrecioPromocional($producto['precio_producto'], $promocion['porcentaje_descuento']) : null;
                    ?>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <img src="<?php echo file_exists($producto['imagen_producto']) ? $producto['imagen_producto'] : '../uploads/default.jpg'; ?>" class="card-img-top" alt="<?php echo $producto['nombre_producto']; ?>">
                            <div class="card-body text-center">
                                <h5 class="card-title"><?php echo $producto['nombre_producto']; ?></h5>
                                <div class="precios-container">
                                    <?php if ($precioPromocional): ?>
                                        <span class="precio-normal">$<?php echo number_format($producto['precio_producto'], 0, ',', '.'); ?></span>
                                        <span class="precio-promocional">$<?php echo number_format($precioPromocional, 0, ',', '.'); ?></span>
                                    <?php else: ?>
                                        <span class="precio-destacado">$<?php echo number_format($producto['precio_producto'], 0, ',', '.'); ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="icono-carrito" 
                                data-id="<?php echo $producto['id_producto']; ?>" 
                                data-categoria="<?php echo $producto['categoria']; ?>" 
                                onclick="agregarAlCarrito(this)">
                                <i class="fas fa-plus"></i>
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
        document.querySelectorAll('.icono-carrito').forEach(button => {
            button.addEventListener('click', function () {
                const idProducto = this.dataset.id; // Usar 'id' en lugar de 'idProducto'
                const categoria = this.dataset.categoria;

                // Realizar la solicitud AJAX para agregar al carrito
                fetch('../funciones/gestionar_carrito/agregar_carrito.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ idProducto, categoria }) // Enviar datos en formato JSON
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        // Mostrar notificación temporal
                        mostrarNotificacion(data.message);

                        // Actualizar el contador del carrito en el header
                        const cartCountElement = document.getElementById('cart-count');
                        if (cartCountElement) {
                            cartCountElement.textContent = data.cartCount > 0 ? data.cartCount : '';
                        } else if (data.cartCount > 0) {
                            const newCartCount = document.createElement('span');
                            newCartCount.id = 'cart-count';
                            newCartCount.className = 'cart-count';
                            newCartCount.textContent = data.cartCount;
                            document.querySelector('.bi-cart-fill').parentElement.appendChild(newCartCount);
                        }
                    } else {
                        mostrarNotificacion('Error al agregar el producto al carrito.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    mostrarNotificacion('Ocurrió un error al procesar la solicitud.');
                });
            });
        });

        // Función para mostrar notificaciones temporales
        function mostrarNotificacion(mensaje) {
            const container = document.getElementById('notification-container');
            const notification = document.createElement('div');
            notification.className = 'notification';
            notification.innerHTML = `
                <span>${mensaje}</span>
                <button class="close-btn" onclick="this.parentElement.remove()">×</button>
            `;

            container.appendChild(notification);

            // Remover automáticamente la notificación después de 5 segundos
            setTimeout(() => {
                if (notification.parentElement) {
                    notification.remove();
                }
            }, 5000);
        }
    </script>
    <div id="notification-container" style="position: fixed; bottom: 20px; right: 20px; z-index: 1000;"></div>
    <?php include '../includes/footer.php'; ?>
</body>
</html>
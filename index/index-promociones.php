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

$whereCategoria = "";
if ($filtroCategoria !== 'Todas') {
    switch ($filtroCategoria) {
        case 'Combos':
            $whereCategoria = "AND categoria = 'combo'";
            break;
        case 'Hamburguesas':
            $whereCategoria = "AND categoria = 'hamburguesa'";
            break;
        case 'Acompañamientos':
            $whereCategoria = "AND categoria = 'acompaniamiento'";
            break;
        case 'Bebidas':
            $whereCategoria = "AND categoria = 'bebida'";
            break;
        case 'Postres':
            $whereCategoria = "AND categoria = 'postre'";
            break;
    }
}

$query = "
    SELECT * FROM (
        SELECT 
            'combo' AS categoria,
            c.id_combo AS id_producto,
            c.nombre_combo AS nombre_producto,
            c.precio AS precio_producto,
            CONCAT('../uploads/combos/', c.imagen) AS imagen_producto
        FROM combo c
        WHERE c.id_combo IN (SELECT id_combo FROM promocion WHERE fecha_inicio <= '$hoy' AND fecha_fin >= '$hoy')
        UNION ALL
        SELECT 
            'hamburguesa' AS categoria,
            h.id_hamburguesa AS id_producto,
            h.nombre_hamburguesa AS nombre_producto,
            h.precio AS precio_producto,
            CONCAT('../uploads/hamburguesas/', h.imagen) AS imagen_producto
        FROM hamburguesa h
        WHERE h.id_hamburguesa IN (SELECT id_hamburguesa FROM promocion WHERE fecha_inicio <= '$hoy' AND fecha_fin >= '$hoy')
        UNION ALL
        SELECT 
            'acompaniamiento' AS categoria,
            a.id_acompaniamiento AS id_producto,
            a.nombre_acompaniamiento AS nombre_producto,
            a.precio AS precio_producto,
            CONCAT('../uploads/acompaniamientos/', a.imagen) AS imagen_producto
        FROM acompaniamiento a
        WHERE a.id_acompaniamiento IN (SELECT id_acompaniamiento FROM promocion WHERE fecha_inicio <= '$hoy' AND fecha_fin >= '$hoy')
        UNION ALL
        SELECT 
            'bebida' AS categoria,
            b.id_bebida AS id_producto,
            b.nombre_bebida AS nombre_producto,
            b.precio AS precio_producto,
            CONCAT('../uploads/bebidas/', b.imagen) AS imagen_producto
        FROM bebida b
        WHERE b.id_bebida IN (SELECT id_bebida FROM promocion WHERE fecha_inicio <= '$hoy' AND fecha_fin >= '$hoy')
        UNION ALL
        SELECT 
            'postre' AS categoria,
            p.id_postre AS id_producto,
            p.nombre_postre AS nombre_producto,
            p.precio AS precio_producto,
            CONCAT('../uploads/postres/', p.imagen) AS imagen_producto
        FROM postre p
        WHERE p.id_postre IN (SELECT id_postre FROM promocion WHERE fecha_inicio <= '$hoy' AND fecha_fin >= '$hoy')
    ) productos WHERE 1=1 $whereCategoria";

$resultado = $conexion->query($query);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Promociones - HamburGeeks</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        html, body {
            background: linear-gradient(to right, #f8fafc, #e2e8f0);
            color: #333;
            height: 100%;
            display: flex;
            flex-direction: column;
            margin-top: 40px;
        }

        .container {
            flex: 1; /* Hace que este contenedor tome el espacio disponible entre el header y el footer */
        }

        footer {
            background-color: #d35400; /* Color del footer */
            color: #fff; /* Texto blanco */
            text-align: center;
            padding: 1rem 0;
            position: relative; /* Necesario si hay elementos posicionados dentro */
            bottom: 0;
            width: 100%;
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
            position: relative; /* Necesario para posicionar el botón dentro de la tarjeta */
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
            position: absolute; /* Para colocarlo encima del card */
            bottom: 15px; /* Ajustar al borde inferior del card */
            right: 15px; /* Ajustar al borde derecho del card */
            z-index: 10; /* Asegura que el botón esté sobre otros elementos */
        }

        .icono-carrito:hover {
            background-color: #e67e22;
            transform: scale(1.1);
        }

        .notification {
            background-color: #343a40; /* Fondo oscuro */
            color: #fff; /* Texto blanco */
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
            color: #e67e22; /* Naranja del diseño */
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
            <h1>Promociones Exclusivas</h1>
            <p>Descubre nuestras mejores ofertas y descuentos.</p>
        </div>

        <!-- Botones de Filtro -->
        <div class="filter-buttons">
            <?php foreach ($categorias as $categoria => $icono): ?>
                <form action="index-promociones.php" method="GET" style="display: inline;">
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
                            <!-- Enlace que envuelve solo la tarjeta (sin el botón "+") -->
                            <a href="detalle-producto.php?nombre=<?php echo urlencode($producto['nombre_producto']); ?>&tipo=<?php echo $producto['categoria']; ?>" style="text-decoration: none; color: inherit;">
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
                                    </div>
                                </div>
                            </a>
                            <!-- Botón "+" fuera del enlace -->
                            <div class="icono-carrito" onclick="agregarAlCarrito(
                                '<?php echo $producto['id_producto']; ?>',
                                '<?php echo $producto['categoria']; ?>',
                                '<?php echo $producto['nombre_producto']; ?>',
                                '<?php echo $producto['precio_producto']; ?>',
                                '<?php echo $producto['imagen_producto']; ?>'
                            )">
                                <i class="fas fa-plus"></i>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="text-center">No hay promociones disponibles en este momento.</p>
            <?php endif; ?>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
|   <script>
        function agregarAlCarrito(idProducto, categoria, nombre, precio, imagen) {
            // Enviar una solicitud AJAX para agregar el producto al carrito
            fetch('../funciones/gestionar_carrito/agregar_carrito.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    idProducto: idProducto,
                    categoria: categoria,
                    nombre: nombre,
                    precio: precio,
                    imagen: imagen // Incluir el campo imagen
                })
            })
            .then(response => response.json())
            .then(data => {
                // Actualizar el contador del carrito
                const cartCountElement = document.querySelector('.cart-count');
                if (cartCountElement) {
                    cartCountElement.textContent = data.totalProductos;
                } else {
                    const newCartCount = document.createElement('span');
                    newCartCount.classList.add('cart-count');
                    newCartCount.textContent = data.totalProductos;
                    document.querySelector('.cart-icon').appendChild(newCartCount);
                }
                // Mostrar la notificación
                mostrarNotificacion(`${nombre} ha sido agregado al carrito exitosamente`);
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }

        function mostrarNotificacion(mensaje) {
            const container = document.getElementById('notification-container');

            // Crear el elemento de notificación
            const notification = document.createElement('div');
            notification.className = 'notification';
            notification.innerHTML = `
                <span>${mensaje}</span>
                <button class="close-btn" onclick="this.parentElement.remove()">×</button>
            `;

            // Agregar la notificación al contenedor
            container.appendChild(notification);

            // Remover automáticamente después de 5 segundos
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
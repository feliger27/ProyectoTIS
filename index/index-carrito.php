<?php
session_start();

// Verificar si el carrito existe
$carrito = isset($_SESSION['carrito']) ? $_SESSION['carrito'] : [];
?>

<?php include('../includes/header.php'); // Incluir el encabezado ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito - HamburGeeks</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Mantener el footer al pie */
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            margin: 0;
        }

        main {
            margin-top: 100px;
            flex: 1;
        }

        nav.navbar {
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        footer {
            background-color: #343a40;
            color: white;
            padding: 10px 0;
            text-align: center;
            position: relative;
            width: 100%;
        }

        .carrito-container {
            max-width: 900px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .carrito-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .carrito-header h1 {
            font-size: 2rem;
            color: #d35400;
        }

        .producto-imagen {
            width: 70px;
            height: 70px;
            object-fit: cover;
            border-radius: 5px;
        }

        .vaciar-carrito {
            background-color: #e74c3c;
            color: white;
        }

        .vaciar-carrito:hover {
            background-color: #c0392b;
        }
    </style>
</head>
<body>
    <main>
        <div class="container carrito-container">
            <div class="carrito-header">
                <h1>Mi Carrito</h1>
            </div>
            <?php if (!empty($carrito)): ?>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Imagen</th>
                            <th>Nombre</th>
                            <th>Precio</th>
                            <th>Cantidad</th>
                            <th>Subtotal</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $total = 0;
                        foreach ($carrito as $categoria => $productos): 
                            foreach ($productos as $id => $producto): 
                                $subtotal = $producto['precio'] * $producto['cantidad'];
                                $total += $subtotal;

                                // Obtener la ruta de la imagen desde la sesión
                                $rutaImagen = "../uploads/$categoria/" . (isset($producto['imagen']) ? $producto['imagen'] : 'default.jpg');
                        ?>
                            <tr>
                                <td>
                                    <?php if (file_exists($rutaImagen)): ?>
                                        <img src="<?php echo $rutaImagen; ?>" class="producto-imagen" alt="<?php echo $producto['nombre']; ?>">
                                    <?php else: ?>
                                        <img src="../uploads/default.jpg" class="producto-imagen" alt="Imagen por defecto">
                                    <?php endif; ?>
                                </td>
                                <td><?php echo $producto['nombre']; ?></td>
                                <td>$<?php echo number_format($producto['precio'], 0, ',', '.'); ?></td>
                                <td>
                                    <form action="../funciones/gestionar_carrito/actualizar_carrito.php" method="POST" style="display: inline-block;">
                                        <input type="hidden" name="id_producto" value="<?php echo $id; ?>">
                                        <input type="hidden" name="categoria" value="<?php echo $categoria; ?>">
                                        <input type="number" name="cantidad" value="<?php echo $producto['cantidad']; ?>" min="1" style="width: 60px;">
                                        <button type="submit" class="btn btn-sm btn-primary">Actualizar</button>
                                    </form>
                                </td>
                                <td>$<?php echo number_format($subtotal, 0, ',', '.'); ?></td>
                                <td>
                                    <form action="../funciones/gestionar_carrito/eliminar_carrito.php" method="POST" style="display: inline-block;">
                                        <input type="hidden" name="id_producto" value="<?php echo $id; ?>">
                                        <input type="hidden" name="categoria" value="<?php echo $categoria; ?>">
                                        <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4" class="text-end"><strong>Total</strong></td>
                            <td colspan="2">$<?php echo number_format($total, 0, ',', '.'); ?></td>
                        </tr>
                    </tfoot>
                </table>
                <div class="d-flex justify-content-between">
                    <form action="../funciones/gestionar_carrito/vaciar_carrito.php" method="POST">
                        <button type="submit" class="btn vaciar-carrito">Vaciar Carrito</button>
                    </form>
                    <a href="../index/index-menu.php" class="btn btn-success">Seguir Comprando</a>
                </div>
            <?php else: ?>
                <p class="text-center">Tu carrito está vacío. <a href="../index/index-menu.php">Explorar menú</a></p>
            <?php endif; ?>
        </div>
    </main>

    <?php include('../includes/footer.php'); // Footer ?>
</body>
</html>

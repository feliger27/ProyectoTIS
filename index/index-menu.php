<?php
include '../conexion.php'; // Conexión a la base de datos

// Consultas para obtener los productos por categoría
$combos = $conexion->query("SELECT * FROM combo")->fetch_all(MYSQLI_ASSOC);
$hamburguesas = $conexion->query("SELECT * FROM hamburguesa")->fetch_all(MYSQLI_ASSOC);
$acompaniamientos = $conexion->query("SELECT * FROM acompaniamiento")->fetch_all(MYSQLI_ASSOC);
$bebidas = $conexion->query("SELECT * FROM bebida")->fetch_all(MYSQLI_ASSOC);
$postres = $conexion->query("SELECT * FROM postre")->fetch_all(MYSQLI_ASSOC);

include '../includes/header.php'; // Incluye encabezado del sitio
?>

<div class="container my-5">
    <h1 class="text-center mb-4">Menú de Productos</h1>
    <!-- Mensaje de error por stock insuficiente -->
    <?php if (isset($_GET['error']) && $_GET['error'] == 'stock_insuficiente'): ?>
        <div class="alert alert-danger" role="alert">
            No hay suficiente stock disponible para este producto.
        </div>
    <?php endif; ?>

    <!-- Estilo para las categorías -->
    <style>
        .category-section {
            margin-bottom: 3rem;
        }
        .product-card {
            transition: transform 0.2s ease;
        }
        .product-card:hover {
            transform: scale(1.05);
        }
        .product-card img {
            width: 100%;      /* Hace que la imagen ocupe el ancho completo del contenedor */
            height: 200px;    /* Altura fija para todas las imágenes */
            object-fit: cover; /* Corta la imagen si es necesario, manteniendo la proporción */
        }
    </style>

    <!-- Sección de Combos -->
    <div class="category-section">
        <h2 class="text-center text-primary mb-4">Combos</h2>
        <div class="row">
            <?php foreach ($combos as $combo): ?>
            <div class="col-md-4">
                <div class="card product-card h-100 shadow-sm">
                    <img src="../uploads/combos/<?= !empty($combo['imagen']) ? htmlspecialchars($combo['imagen']) : 'default-combo.jpg' ?>" class="card-img-top" alt="<?= htmlspecialchars($combo['nombre_combo']) ?>">
                    <div class="card-body">
                        <h5 class="card-title text-center"><?= htmlspecialchars($combo['nombre_combo']) ?></h5>
                        <p class="card-text text-center fw-bold text-success">$<?= htmlspecialchars($combo['precio']) ?></p>
                        <form action="../funciones/gestionar_carrito/agregar_carrito.php" method="POST" class="text-center">
                            <input type="hidden" name="producto_id" value="<?= $combo['id_combo'] ?>">
                            <input type="hidden" name="tipo_producto" value="combo">
                            <input type="number" name="cantidad" value="1" min="1" class="form-control mb-2 text-center" style="max-width: 80px; margin: 0 auto;">
                            <button type="submit" class="btn btn-primary w-100">Agregar al Carrito</button>
                        </form>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>



    <!-- Sección de Hamburguesas -->
    <div class="category-section">
        <h2 class="text-center text-primary mb-4">Hamburguesas</h2>
        <div class="row">
            <?php foreach ($hamburguesas as $hamburguesa): ?>
            <div class="col-md-4">
                <div class="card product-card h-100 shadow-sm">
                    <img src="../uploads/hamburguesas/<?= htmlspecialchars($hamburguesa['imagen']) ?>" class="card-img-top" alt="<?= htmlspecialchars($hamburguesa['nombre_hamburguesa']) ?>">
                    <div class="card-body">
                        <h5 class="card-title text-center"><?= htmlspecialchars($hamburguesa['nombre_hamburguesa']) ?></h5>
                        <p class="card-text text-center fw-bold text-success">$<?= htmlspecialchars($hamburguesa['precio']) ?></p>
                        <form action="../funciones/gestionar_carrito/agregar_carrito.php" method="POST" class="text-center">
                            <input type="hidden" name="producto_id" value="<?= $hamburguesa['id_hamburguesa'] ?>">
                            <input type="hidden" name="tipo_producto" value="hamburguesa">
                            <input type="number" name="cantidad" value="1" min="1" class="form-control mb-2 text-center" style="max-width: 80px; margin: 0 auto;">
                            <button type="submit" class="btn btn-primary w-100">Agregar al Carrito</button>
                        </form>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Sección de Bebidas -->
    <div class="category-section">
        <h2 class="text-center text-primary mb-4">Bebidas</h2>
        <div class="row">
            <?php foreach ($bebidas as $bebida): ?>
            <div class="col-md-4">
                <div class="card product-card h-100 shadow-sm">
                    <img src="../uploads/bebidas/<?= htmlspecialchars($bebida['imagen']) ?>" class="card-img-top" alt="<?= htmlspecialchars($bebida['nombre_bebida']) ?>">
                    <div class="card-body">
                        <h5 class="card-title text-center"><?= htmlspecialchars($bebida['nombre_bebida']) ?></h5>
                        <p class="card-text text-center fw-bold text-success">$<?= htmlspecialchars($bebida['precio']) ?></p>
                        <form action="../funciones/gestionar_carrito/agregar_carrito.php" method="POST" class="text-center">
                            <input type="hidden" name="producto_id" value="<?= $bebida['id_bebida'] ?>">
                            <input type="hidden" name="tipo_producto" value="bebida">
                            <input type="number" name="cantidad" value="1" min="1" class="form-control mb-2 text-center" style="max-width: 80px; margin: 0 auto;">
                            <button type="submit" class="btn btn-primary w-100">Agregar al Carrito</button>
                        </form>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

<!-- Sección de Postres -->
    <div class="category-section">
        <h2 class="text-center text-primary mb-4">Postres</h2>
        <div class="row">
            <?php foreach ($postres as $postre): ?>
            <div class="col-md-4">
                <div class="card product-card h-100 shadow-sm">
                    <img src="../uploads/postres/<?= htmlspecialchars($postre['imagen']) ?>" class="card-img-top" alt="<?= htmlspecialchars($postre['nombre_postre']) ?>">
                    <div class="card-body">
                        <h5 class="card-title text-center"><?= htmlspecialchars($postre['nombre_postre']) ?></h5>
                        <p class="card-text text-center fw-bold text-success">$<?= htmlspecialchars($postre['precio']) ?></p>
                        <form action="../funciones/gestionar_carrito/agregar_carrito.php" method="POST" class="text-center">
                            <input type="hidden" name="producto_id" value="<?= $postre['id_postre'] ?>">
                            <input type="hidden" name="tipo_producto" value="postre">
                            <input type="number" name="cantidad" value="1" min="1" class="form-control mb-2 text-center" style="max-width: 80px; margin: 0 auto;">
                            <button type="submit" class="btn btn-primary w-100">Agregar al Carrito</button>
                        </form>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <!-- Aquí un ejemplo para la sección de Acompañamientos -->
    <div class="category-section">
        <h2 class="text-center text-primary mb-4">Acompañamientos</h2>
        <div class="row">
            <?php foreach ($acompaniamientos as $acomp): ?>
            <div class="col-md-4">
                <div class="card product-card h-100 shadow-sm">
                    <img src="../uploads/acompaniamientos/<?= htmlspecialchars($acomp['imagen']) ?>" class="card-img-top" alt="<?= htmlspecialchars($acomp['nombre_acompaniamiento']) ?>">
                    <div class="card-body">
                        <h5 class="card-title text-center"><?= htmlspecialchars($acomp['nombre_acompaniamiento']) ?></h5>
                        <p class="card-text text-center fw-bold text-success">$<?= htmlspecialchars($acomp['precio']) ?></p>
                        <form action="../funciones/gestionar_carrito/agregar_carrito.php" method="POST" class="text-center">
                            <input type="hidden" name="producto_id" value="<?= $acomp['id_acompaniamiento'] ?>">
                            <input type="hidden" name="tipo_producto" value="acompaniamiento">
                            <input type="number" name="cantidad" value="1" min="1" class="form-control mb-2 text-center" style="max-width: 80px; margin: 0 auto;">
                            <button type="submit" class="btn btn-primary w-100">Agregar al Carrito</button>
                        </form>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    
    <!-- Repite para bebidas y postres, asegurándote de que la carpeta coincide -->
</div>

<?php include '../includes/footer.php'; ?>

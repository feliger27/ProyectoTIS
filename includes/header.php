<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../funciones/verificadores/verificadores.php'; // Incluir el archivo verificadores

// Conteo de productos en el carrito
$numero_productos = 0;

// Asegurarse de que $_SESSION['carrito'] sea siempre un array válido
if (!isset($_SESSION['carrito']) || !is_array($_SESSION['carrito'])) {
    $_SESSION['carrito'] = []; // Inicializar como array vacío si no está definido
}

// Iterar sobre el carrito para contar los productos
foreach ($_SESSION['carrito'] as $tipo => $productos) {
    if (is_array($productos)) { // Validar que cada tipo de producto sea un array
        foreach ($productos as $producto) {
            $numero_productos += $producto['cantidad'] ?? 0; // Usar un valor predeterminado de 0
        }
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HamburGeeks</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.3/font/bootstrap-icons.min.css">
    <style>
        .navbar {
            background-color: #343a40;
        }

        .navbar-brand {
            font-size: 1.5rem;
            font-weight: bold;
            color: #fff;
        }

        .navbar-nav .nav-link {
            font-size: 1.2rem;
            font-weight: bold;
            color: #fff !important;
            display: flex;
            align-items: center;
            justify-content: center;
            
        }

        .navbar-nav .nav-link:hover {
            color: #FFC107 !important;
            background-color: rgba(255, 193, 7, 0.1);
            border-radius: 8px;
        }

        .navbar-nav .nav-item {
            margin-right: 15px;
            display: flex;
            align-items: center;
        }

        .bi-person-circle,
        .bi-cart-fill {
            font-size: 1.5rem;
            color: #fff;
        }

        .bi-person-circle:hover,
        .bi-cart-fill:hover {
            color: #FFC107;
        }

        .cart-count {
            position: absolute;
            top: -5px;
            right: -10px;
            background-color: #FF0000;
            color: white;
            font-size: 0.8rem;
            font-weight: bold;
            padding: 2px 6px;
            border-radius: 50%;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
        <a class="navbar-brand" href="../index/index-lobby.php">
            <img src="../uploads/HAMBUR_Mesa.png" alt="Logo HamburGeeks" style="height: 60px;">
        </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="../index/index-menu.php">Menú</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../index/index-promociones.php">Promociones</a>
                    </li>
                    <!-- Botón Mantenedores (verificar permiso) -->
                    <?php if (verificarPermisos(['ver_mantenedores'])): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="../index/index-mantenedores.php">Mantenedores</a>
                        </li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <a class="nav-link" href="../index/index-perfil.php">
                            <i class="bi bi-person-circle"></i>
                        </a>
                    </li>
                    <li class="nav-item position-relative">
                        <a class="nav-link" href="../index/index-carrito.php">
                            <i class="bi bi-cart-fill"></i>
                            <?php if ($numero_productos > 0): ?>
                                <span id="cart-count" class="cart-count"><?= $numero_productos ?></span>
                            <?php endif; ?>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
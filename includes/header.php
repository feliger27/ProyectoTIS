<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../funciones/verificadores/verificadores.php'; // Asegúrate de la ruta correcta

// Usa `$_SESSION['permissions']` para almacenar permisos
$permisosUsuario = isset($_SESSION['permissions']) ? $_SESSION['permissions'] : [];
$numero_productos = 0;
if (isset($_SESSION['carrito'])) {
    foreach ($_SESSION['carrito'] as $tipo => $productos) {
        foreach ($productos as $producto) {
            $numero_productos += $producto['cantidad'];
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
    <link rel="stylesheet" href="style.css">
    <style>
        /* Logo sin fondo naranja y tamaño ajustado */
        .logo-container {
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        img {
            width: 1000px;
        }

        /* Estilo del icono del carrito */
        .cart-icon {
            position: relative;
            display: flex;
            align-items: center;
        }

        .cart-count {
            position: absolute;
            top: -5px;
            right: -10px;
            background-color: red;
            color: white;
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 12px;
        }

        /* Barra de navegación */
        .navbar {
            background-color: #343a40; /* Fondo oscuro */
        }

        .navbar-nav .nav-link {
            color: #fff !important; /* Texto blanco */
            padding: 10px 15px;
            text-transform: uppercase;
            font-weight: bold;
        }

        .navbar-nav .nav-link:hover {
            color: #FFA500 !important; /* Color naranja en hover */
            background-color: rgba(255, 165, 0, 0.1); /* Fondo suave al pasar el mouse */
            border-radius: 5px;
        }

        .navbar-nav {
            display: flex;
            align-items: center;
            gap: 20px; /* Espaciado entre los elementos */
        }

        /* Aumentar tamaño del logo sin que se distorsione */
        .logo-container img {
            width: 50px; /* Ajuste el tamaño según lo desees */
            height: auto;
        }

        /* Ajuste de los íconos y elementos para que se alineen verticalmente */
        .navbar-nav .nav-item {
            display: flex;
            align-items: center;
        }

        /* Espaciado adecuado para la barra de navegación */
        .navbar-toggler {
            border-color: #FFA500;
        }

        .navbar-toggler-icon {
            background-color: #FFA500;
        }
    </style>
</head>

<body>

<nav class="navbar navbar-expand-lg navbar-dark fixed-top">
    <div class="container">
        <!-- Logo sin fondo naranja -->
        <div class="logo-container me-3">
            <a href="../index/index-lobby.php">
                <img src="../uploads/hamburgeeks_logo.jpeg" alt="Logo"> <!-- Logo sin fondo naranja -->
            </a>
        </div>
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
                <li class="nav-item">
                    <a class="nav-link" href="../index/index-perfil.php">Mi Cuenta</a>
                </li>
                <!-- Mostrar el botón de Mantenedores solo si el usuario tiene el permiso ver_mantenedores -->
                <?php if (verificarPermisos(['ver_mantenedores'])): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="../index/index-mantenedores.php">Mantenedores</a>
                    </li>
                <?php endif; ?>
                <li class="nav-item">
                    <a class="nav-link cart-icon" href="../index/index-carrito.php">
                        <i class="bi bi-cart" style="font-size: 1.5rem;"></i>
                        <?php if ($numero_productos > 0): ?>
                        <span class="cart-count"><?= $numero_productos ?></span>
                        <?php endif; ?>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>


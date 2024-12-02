<?php
include '../conexion.php';
include '../funciones/verificadores/verificadores.php';
session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .header {
            background-color: #1a1a1a;
            color: #ffa500;
            padding: 15px 0;
        }
        .header h1 {
            margin: 0;
        }
        .logo-container {
            background-color: #ffa500;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            
        }
        .logo-container img {
            max-width: 100%;
            max-height: 100%;
            
        }
        .card {
            background-color: #333;
            border: none;
            color: white;
        }
        .card .btn {
            background-color: #ffa500;
            border: none;
            color: black;
        }
        .card .btn:hover {
            background-color: #e69500;
        }
        .card:hover {
            cursor: pointer;
            background-color: #444;
        }
    </style>
</head>
<body>
    <header class="header text-center">
        <div class="container d-flex justify-content-center align-items-center">
            <div class="logo-container me-3">
                <a href="../index/index-lobby.php">
                    <img src="../uploads/HAMBUR_Mesa3.png" alt="Logo">
                </a>
            </div>
            <h1 class="m-0">Panel de Administración</h1>
        </div>
    </header>

    <div class="container mt-5">
        <div class="row">
            <?php if (verificarPermisos(['ver_aderezos'])): ?>
            <div class="col-md-4 mb-4">
                <a href="../mantenedores/aderezos/listar.php" class="text-decoration-none">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title">Aderezos</h5>
                        </div>
                    </div>
                </a>
            </div>
            <?php endif; ?>

            <?php if (verificarPermisos(['ver_hamburguesas'])): ?>
            <div class="col-md-4 mb-4">
                <a href="../mantenedores/hamburguesas/listar.php" class="text-decoration-none">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title">Hamburguesas</h5>
                        </div>
                    </div>
                </a>
            </div>
            <?php endif; ?>

            <?php if (verificarPermisos(['ver_combos'])): ?>
            <div class="col-md-4 mb-4">
                <a href="../mantenedores/combos/listar.php" class="text-decoration-none">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title">Combos</h5>
                        </div>
                    </div>
                </a>
            </div>
            <?php endif; ?>
        </div>

        <div class="row">
            <?php if (verificarPermisos(['ver_ingredientes'])): ?>
            <div class="col-md-4 mb-4">
                <a href="../mantenedores/ingredientes/listar.php" class="text-decoration-none">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title">Ingredientes</h5>
                        </div>
                    </div>
                </a>
            </div>
            <?php endif; ?>

            <?php if (verificarPermisos(['ver_postres'])): ?>
            <div class="col-md-4 mb-4">
                <a href="../mantenedores/postres/listar.php" class="text-decoration-none">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title">Postres</h5>
                        </div>
                    </div>
                </a>
            </div>
            <?php endif; ?>

            <?php if (verificarPermisos(['ver_bebidas'])): ?>
            <div class="col-md-4 mb-4">
                <a href="../mantenedores/bebidas/listar.php" class="text-decoration-none">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title">Bebidas</h5>
                        </div>
                    </div>
                </a>
            </div>
            <?php endif; ?>
        </div>

        <div class="row">
            <?php if (verificarPermisos(['ver_acompaniamiento'])): ?>
            <div class="col-md-4 mb-4">
                <a href="../mantenedores/acompaniamiento/listar.php" class="text-decoration-none">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title">Acompañamientos</h5>
                        </div>
                    </div>
                </a>
            </div>
            <?php endif; ?>

            <?php if (verificarPermisos(['ver_promociones'])): ?>
            <div class="col-md-4 mb-4">
                <a href="../mantenedores/promociones/listar.php" class="text-decoration-none">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title">Promociones</h5>
                        </div>
                    </div>
                </a>
            </div>
            <?php endif; ?>

            <?php if (verificarPermisos(['ver_usuarios'])): ?>
            <div class="col-md-4 mb-4">
                <a href="../mantenedores/usuarios/listar.php" class="text-decoration-none">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title">Usuarios</h5>
                        </div>
                    </div>
                </a>
            </div>
            <?php endif; ?>
        </div>

        <div class="row">
            <?php if (verificarPermisos(['ver_pedidos'])): ?>
            <div class="col-md-4 mb-4">
                <a href="../mantenedores/pedidos/listar.php" class="text-decoration-none">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title">Pedidos</h5>
                        </div>
                    </div>
                </a>
            </div>
            <?php endif; ?>

            <?php if (verificarPermisos(['ver_stock'])): ?>
            <div class="col-md-4 mb-4">
                <a href="../mantenedores/stock/listar.php" class="text-decoration-none">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title">Stock</h5>
                        </div>
                    </div>
                </a>
            </div>
            <?php endif; ?>

            <?php if (verificarPermisos(['ver_roles'])): ?>
            <div class="col-md-4 mb-4">
                <a href="../mantenedores/roles/listar_roles.php" class="text-decoration-none">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title">Roles</h5>
                        </div>
                    </div>
                </a>
            </div>
            <?php endif; ?>
        </div>

        <div class="row">
            <?php if (verificarPermisos(['ver_permisos'])): ?>
            <div class="col-md-4 mb-4">
                <a href="../mantenedores/permisos/listar_permisos.php" class="text-decoration-none">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title">Permisos</h5>
                        </div>
                    </div>
                </a>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

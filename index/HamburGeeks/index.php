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
            max-width: 120%;
            max-height: 120%;
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
        
    </style>
</head>
<body>
    <header class="header text-center">
        <div class="container d-flex justify-content-center align-items-center">
            <div class="logo-container me-3">
                <img src="logo-hamburgeeks.png" alt="Logo">
            </div>
            <h1 class="m-0">Panel de Administración</h1>
        </div>
    </header>

    <div class="container mt-5">
        <div class="row">
            
            <div class="col-md-4 mb-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Aderezos</h5>
                        <p class="card-text">Gestiona los Aderezos.</p>
                        <a href="mantenedores/aderezos/listar.php" class="btn">Listar Aderezos</a>
                    </div>
                </div>
            </div>

            
            <div class="col-md-4 mb-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Hamburguesas</h5>
                        <p class="card-text">Gestiona las Hamburguesas.</p>
                        <a href="mantenedores/hamburguesas/listar.php" class="btn">Listar Hamburguesas</a>
                    </div>
                </div>
            </div>

            
            <div class="col-md-4 mb-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Combos</h5>
                        <p class="card-text">Gestiona los Combos.</p>
                        <a href="mantenedores/combos/listar.php" class="btn">Listar Combos</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Ingredientes</h5>
                        <p class="card-text">Gestiona los Ingredientes.</p>
                        <a href="mantenedores/ingredientes/listar.php" class="btn">Listar Ingredientes</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Postres</h5>
                        <p class="card-text">Gestiona los Postres.</p>
                        <a href="mantenedores/postres/listar.php" class="btn">Listar Postres</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Bebidas</h5>
                        <p class="card-text">Gestiona las Bebidas.</p>
                        <a href="mantenedores/bebidas/listar.php" class="btn">Listar Bebidas</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Acompañamientos</h5>
                        <p class="card-text">Gestiona los Acompañamientos.</p>
                        <a href="mantenedores/acompaniamiento/listar.php" class="btn">Listar Acompañamientos</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Promociones</h5>
                        <p class="card-text">Gestiona las Promociones.</p>
                        <a href="mantenedores/promociones/listar.php" class="btn">Listar Promociones</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Usuarios</h5>
                        <p class="card-text">Gestiona los Usuarios.</p>
                        <a href="mantenedores/usuarios/listar.php" class="btn">Listar Usuarios</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Pedidos</h5>
                        <p class="card-text">Gestiona los Pedidos.</p>
                        <a href="mantenedores/pedidos/listar.php" class="btn">Listar Pedidos</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Stock</h5>
                        <p class="card-text">Gestiona los Stock.</p>
                        <a href="mantenedores/stock/listar.php" class="btn">Listar Stock</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Roles</h5>
                        <p class="card-text">Gestiona los Roles.</p>
                        <a href="mantenedores/roles/listar_roles.php" class="btn">Listar Roles</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Permisos</h5>
                        <p class="card-text">Gestiona los Permisos.</p>
                        <a href="mantenedores/permisos/listar_permisos.php" class="btn">Listar Permisos</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

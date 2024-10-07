<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-5">Panel de Administración</h1>
        
        <div class="row">
            <!-- Card para Productos -->
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Aderezos</h5>
                        <p class="card-text">Gestiona los Aderezos.</p>
                        <div class="d-flex align-items-center justify-content-center">
                            <a href="mantenedores/aderezos/listar.php" class="btn btn-primary">Listar Aderezos</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card para Combos -->
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Hamburguesas</h5>
                        <p class="card-text">Gestiona las Hamburguesas.</p>
                        <a href="mantenedores/hamburguesas/listar.php" class="btn btn-primary mt-2">Listar Hamburguesas</a>
                    </div>
                </div>
            </div>

            <!-- Card para Sucursales -->
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Combos</h5>
                        <p class="card-text">Gestiona los Combos.</p>
                        <a href="mantenedores/combos/listar.php" class="btn btn-primary mt-2">Listar Combos</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <!-- Card adicional -->
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Ingredientes</h5>
                        <p class="card-text">Gestiona los ingredientes.</p>
                        <a href="mantenedores/ingredientes/listar.php" class="btn btn-primary mt-2">Listar ingredientes</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Postres</h5>
                        <p class="card-text">Gestiona los Postres.</p>
                        <a href="mantenedores/postres/listar.php" class="btn btn-primary mt-2">Listar Postres</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Bebidas</h5>
                        <p class="card-text">Gestiona las Bebidas.</p>
                        <a href="mantenedores/bebidas/listar.php" class="btn btn-primary mt-2">Listar Bebidas</a>
                    </div>
                </div>
            </div>

            <!-- Si deseas agregar más cards, puedes añadir más col-md-4 aquí -->
        </div>
        <div class="row mt-4">
            <!-- Card adicional -->
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Acompañamientos</h5>
                        <p class="card-text">Gestiona los acompañamientos.</p>
                        <a href="mantenedores/acompanamiento/listar.php" class="btn btn-primary mt-2">Listar Acompañamientos</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Promociones</h5>
                        <p class="card-text">Gestiona las Promociones.</p>
                        <a href="mantenedores/promociones/listar.php" class="btn btn-primary mt-2">Listar Promociones</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Bebidas</h5>
                        <p class="card-text">Gestiona las Bebidas.</p>
                        <a href="mantenedores/bebidas/listar.php" class="btn btn-primary mt-2">Listar Bebidas</a>
                    </div>
                </div>
            </div>

            <!-- Si deseas agregar más cards, puedes añadir más col-md-4 aquí -->
        </div>
        
    </div>

    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

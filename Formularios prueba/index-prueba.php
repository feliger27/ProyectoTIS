<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estado del Pedido</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container">
        <div class="card mx-auto mt-5 shadow-lg" style="max-width: 600px;">
            <div class="card-header text-center bg-warning text-dark fw-bold">
                Estado del Pedido 🍔
            </div>
            <div class="card-body">
                <!-- Mensaje de agradecimiento -->
                <div class="mb-4 text-center">
                    <h4 class="text-dark">¡Gracias por preferir Hamburgeeks! 🍔</h4>
                    <p class="text-muted">Estamos trabajando para que disfrutes de tu pedido lo antes posible.</p>
                </div>

                <!-- Información del Pedido -->
                <div class="mb-4 text-center">
                    <h5><strong>Número de Pedido:</strong></h5>
                    <p>#12345</p>
                </div>

                <div class="mb-4 text-center">
                    <h5><strong>Correo Electrónico:</strong></h5>
                    <p>usuario@correo.com</p>
                </div>

                <!-- Estados del Pedido -->
                <div class="mb-4">
                    <h5 class="text-center"><strong>Progreso del Pedido:</strong></h5>
                    <div class="d-flex justify-content-around">
                        <span class="badge bg-warning text-dark fw-bold border border-dark">En preparación 🍳</span>
                        <span class="badge bg-secondary text-light">En reparto 🚚</span>
                        <span class="badge bg-secondary text-light">Entregado ✅</span>
                    </div>
                </div>

                <!-- Botón para Volver -->
                <div class="text-center mt-4">
                    <a href="seguimiento.php" class="btn btn-outline-secondary px-5 py-2 rounded-pill shadow-sm">
                        ⬅️ Volver
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

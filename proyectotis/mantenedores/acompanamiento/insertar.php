<?php
include '../../conexion.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre_acompanamiento = $_POST['nombre_acompanamiento'];
    $cantidad = $_POST['cantidad'];
    $precio = $_POST['precio'];

    $sql = "INSERT INTO acompanamiento (nombre_acompanamiento, cantidad, precio) VALUES (?, ?, ?)";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("sid", $nombre_acompanamiento, $cantidad, $precio);  // "sid" para string, int, decimal
    if ($stmt->execute()) {
        echo "<div class='alert alert-success' role='alert'>Acompañamiento agregado exitosamente.</div>";
    } else {
        echo "<div class='alert alert-danger' role='alert'>Error: " . $stmt->error . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Nuevo Acompañamiento</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center">
        <h1>Agregar Nuevo Acompañamiento</h1>
        <button class="btn btn-secondary" onclick="window.location.href='listar.php'">Volver</button>
    </div>
    <form action="insertar.php" method="POST" class="mt-4">
        <div class="mb-3">
            <label for="nombre_acompanamiento" class="form-label">Nombre del Acompañamiento</label>
            <input type="text" name="nombre_acompanamiento" id="nombre_acompanamiento" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="cantidad" class="form-label">Cantidad (Stock)</label>
            <input type="number" name="cantidad" id="cantidad" class="form-control" required min="0">
        </div>

        <div class="mb-3">
            <label for="precio" class="form-label">Precio</label>
            <input type="text" name="precio" id="precio" class="form-control" required>
        </div>
        
        <button type="submit" class="btn btn-primary">Guardar Acompañamiento</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


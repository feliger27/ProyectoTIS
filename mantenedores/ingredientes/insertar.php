<?php
include '../../conexion.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre_ingrediente = $_POST['nombre_ingrediente'];
    $cantidad = $_POST['cantidad'];

    $sql = "INSERT INTO ingrediente (nombre_ingrediente, cantidad) 
            VALUES ('$nombre_ingrediente', '$cantidad')";

    if ($conexion->query($sql) === TRUE) {
        echo "<div class='container mt-3'>
                <div class='alert alert-success alert-dismissible fade show' role='alert'>
                    Ingrediente agregado exitosamente.
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>
              </div>";
    } else {
        echo "<div class='container mt-3'>
                <div class='alert alert-danger alert-dismissible fade show' role='alert'>
                    Error: " . $stmt->error . "
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>
              </div>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Nuevo Ingrediente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center">
        <h1>Agregar Nuevo Ingrediente</h1>
        <button class="btn btn-secondary" onclick="window.location.href='listar.php'">Volver</button>
    </div>
    <form action="insertar.php" method="POST" class="mt-4">
        <div class="mb-3">
            <label for="nombre_ingrediente" class="form-label">Nombre del Ingrediente</label>
            <input type="text" name="nombre_ingrediente" id="nombre_ingrediente" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="cantidad" class="form-label">Cantidad (Stock)</label>
            <input type="number" name="cantidad" id="cantidad" class="form-control" required min="0">
        </div>

        <button type="submit" class="btn btn-primary">Guardar Ingrediente</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

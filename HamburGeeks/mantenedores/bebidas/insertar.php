<?php
include '../../conexion.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre_bebida = $_POST['nombre_bebida'];
    $cantidad = $_POST['cantidad'];
    $precio = $_POST['precio'];

    // Usamos consulta preparada
    $sql = "INSERT INTO bebida (nombre_bebida, cantidad, precio) 
            VALUES (?, ?, ?)";
    $stmt = $conexion->prepare($sql);

    if ($stmt) {
        // Asignamos los valores a los parámetros
        $stmt->bind_param("sii", $nombre_bebida, $cantidad, $precio);
        
        // Ejecutamos la consulta
        if ($stmt->execute()) {
            echo "<div class='container mt-3'>
                    <div class='alert alert-success alert-dismissible fade show' role='alert'>
                        Bebida agregada exitosamente.
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
    } else {
        echo "<div class='container mt-3'>
                <div class='alert alert-danger alert-dismissible fade show' role='alert'>
                    Error en la preparación de la consulta.
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
    <title>Agregar Nueva Bebida</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center">
        <h1>Agregar Nueva Bebida</h1>
        <button class="btn btn-secondary" onclick="window.location.href='listar.php'">Volver</button>
    </div>
    <form action="insertar.php" method="POST" class="mt-4">
        <div class="mb-3">
            <label for="nombre_bebida" class="form-label">Nombre de la Bebida</label>
            <input type="text" name="nombre_bebida" id="nombre_bebida" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="cantidad" class="form-label">Cantidad (Stock)</label>
            <input type="number" name="cantidad" id="cantidad" class="form-control" required min="0">
        </div>
        <div class="mb-3">
            <label for="precio" class="form-label">Precio</label>
            <input type="number" step="0.01" name="precio" id="precio" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Guardar Bebida</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

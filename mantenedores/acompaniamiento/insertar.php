<?php
include '../../conexion.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre_acompaniamiento = $_POST['nombre_acompaniamiento'];
    $cantidad = $_POST['cantidad'];
    $precio = $_POST['precio'];
    $imagen_nombre = null; // Nombre de la imagen en caso de que no se suba una.

    // Manejo de la carga de imagen
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == UPLOAD_ERR_OK) {
        $nombre_tmp = $_FILES['imagen']['tmp_name'];
        $imagen_nombre = basename($_FILES['imagen']['name']);
        $ruta_destino = '../../uploads/acompaniamientos/' . $imagen_nombre;

        // Mover la imagen a la carpeta de destino
        if (!move_uploaded_file($nombre_tmp, $ruta_destino)) {
            echo "<div class='container mt-3'>
                    <div class='alert alert-danger alert-dismissible fade show' role='alert'>
                        Error al subir la imagen.
                        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                    </div>
                  </div>";
            $imagen_nombre = null;
        }
    }

    // Inserción en la base de datos
    $sql = "INSERT INTO acompaniamiento (nombre_acompaniamiento, cantidad, precio, imagen) VALUES (?, ?, ?, ?)";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("sids", $nombre_acompaniamiento, $cantidad, $precio, $imagen_nombre);  // "sids" para string, int, decimal, string
    if ($stmt->execute()) {
        echo "<div class='container mt-3'>
                <div class='alert alert-success alert-dismissible fade show' role='alert'>
                    Acompañamiento agregado exitosamente.
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
    <title>Agregar Nuevo Acompañamiento</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center">
        <h1>Agregar Nuevo Acompañamiento</h1>
        <button class="btn btn-secondary" onclick="window.location.href='listar.php'">Volver</button>
    </div>
    <form action="insertar.php" method="POST" class="mt-4" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="nombre_acompaniamiento" class="form-label">Nombre del Acompañamiento</label>
            <input type="text" name="nombre_acompaniamiento" id="nombre_acompaniamiento" class="form-control" required>
        </div>
        
        <div class="mb-3">
            <label for="cantidad" class="form-label">Cantidad (Stock)</label>
            <input type="number" name="cantidad" id="cantidad" class="form-control" required min="0">
        </div>

        <div class="mb-3">
            <label for="precio" class="form-label">Precio</label>
            <input type="text" name="precio" id="precio" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="imagen" class="form-label">Imagen del Acompañamiento</label>
            <input type="file" name="imagen" id="imagen" class="form-control">
        </div>
        
        <button type="submit" class="btn btn-primary">Guardar Acompañamiento</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


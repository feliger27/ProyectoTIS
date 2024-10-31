<?php
include '../../conexion.php';

// Verificamos que se ha pasado un ID
if (!isset($_GET['id'])) {
    header("Location: listar.php");
    exit;
}

$id_postre = $_GET['id'];

// Obtenemos la información del postre
$sql = "SELECT * FROM postre WHERE id_postre = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id_postre);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    header("Location: listar.php");
    exit;
}

$row = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre_postre = $_POST['nombre_postre'];
    $cantidad = $_POST['cantidad'];
    $precio = $_POST['precio'];
    $nombre_imagen = $row['imagen']; // Imagen actual por defecto

    // Comprobamos si se ha subido una nueva imagen
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == UPLOAD_ERR_OK) {
        $nombre_imagen = $_FILES['imagen']['name'];
        $ruta_temporal = $_FILES['imagen']['tmp_name'];
        $ruta_destino = "../../uploads/postres/" . $nombre_imagen;

        // Movemos la imagen a la carpeta de destino
        if (!move_uploaded_file($ruta_temporal, $ruta_destino)) {
            echo "<div class='container mt-3'>
                    <div class='alert alert-danger alert-dismissible fade show' role='alert'>
                        Error al subir la imagen.
                        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                    </div>
                  </div>";
            exit;
        }
    }

    // Actualizamos el registro del postre
    $sql = "UPDATE postre SET nombre_postre = ?, cantidad = ?, precio = ?, imagen = ? WHERE id_postre = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("sidss", $nombre_postre, $cantidad, $precio, $nombre_imagen, $id_postre);

    if ($stmt->execute()) {
        echo "<div class='container mt-3'>
                <div class='alert alert-success alert-dismissible fade show' role='alert'>
                    Postre editado exitosamente.
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
    <title>Editar Postre</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center">
        <h1>Editar Postre</h1>
        <button class="btn btn-secondary" onclick="window.location.href='listar.php'">Volver</button>
    </div>
    <form action="editar.php?id=<?php echo $id_postre; ?>" method="POST" enctype="multipart/form-data" class="mt-4">
        <div class="mb-3">
            <label for="nombre_postre" class="form-label">Nombre del Postre</label>
            <input type="text" name="nombre_postre" id="nombre_postre" class="form-control" value="<?php echo $row['nombre_postre']; ?>" required>
        </div>

        <div class="mb-3">
            <label for="cantidad" class="form-label">Cantidad (Stock)</label>
            <input type="number" name="cantidad" id="cantidad" class="form-control" value="<?php echo $row['cantidad']; ?>" required min="0">
        </div>

        <div class="mb-3">
            <label for="precio" class="form-label">Precio</label>
            <input type="number" step="0.01" name="precio" id="precio" class="form-control" value="<?php echo $row['precio']; ?>" required>
        </div>

        <div class="mb-3">
            <label for="imagen" class="form-label">Imagen del Postre</label>
            <?php if (!empty($row['imagen'])): ?>
                <div class="mb-2">
                    <img src="../../uploads/postres/<?php echo $row['imagen']; ?>" alt="Imagen Actual" style="width: 100px; height: 100px; object-fit: cover;">
                </div>
            <?php endif; ?>
            <input type="file" name="imagen" id="imagen" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Actualizar Postre</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


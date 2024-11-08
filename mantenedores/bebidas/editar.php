<?php
include '../../conexion.php'; 

// Verificamos que se ha pasado un ID
if (!isset($_GET['id'])) {
    header("Location: listar.php");
    exit;
}

$id_bebida = $_GET['id'];
// Obtenemos la información de la bebida
$sql = "SELECT * FROM bebida WHERE id_bebida = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id_bebida);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    header("Location: listar.php");
    exit;
}

$row = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Si se pulsa el botón de eliminar imagen
    if (isset($_POST['eliminar_imagen'])) {
        $imagen = $row['imagen'];
        if (!empty($imagen) && file_exists("../../uploads/bebidas/" . $imagen)) {
            unlink("../../uploads/bebidas/" . $imagen); // Eliminar archivo
        }
        $sql = "UPDATE bebida SET imagen = NULL WHERE id_bebida = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $id_bebida);
        $stmt->execute();
        $row['imagen'] = null; // Actualizar el valor de la imagen en el array $row
        echo "<div class='alert alert-success'>Imagen eliminada correctamente.</div>";
    } else {
        $nombre_bebida = $_POST['nombre_bebida'];
        $cantidad = $_POST['cantidad'];
        $precio = $_POST['precio'];
        // Comprobamos si se ha subido una nueva imagen
        if ($_FILES['imagen']['error'] == UPLOAD_ERR_OK) {
            $nombre_imagen = $_FILES['imagen']['name'];
            $ruta_temporal = $_FILES['imagen']['tmp_name'];
            $ruta_destino = "../../uploads/bebidas/" . $nombre_imagen;
            if (move_uploaded_file($ruta_temporal, $ruta_destino)) {
                // Actualizamos el registro con la nueva imagen
                $sql = "UPDATE bebida SET nombre_bebida = ?, cantidad = ?, precio = ?, imagen = ? WHERE id_bebida = ?";
                $stmt = $conexion->prepare($sql);
                $stmt->bind_param("sidsi", $nombre_bebida, $cantidad, $precio, $nombre_imagen, $id_bebida);
            } else {
                echo "<div class='alert alert-danger'>Error al subir la imagen.</div>";
                exit;
            }
        } else {
            // Si no hay nueva imagen, se mantiene la actual
            $sql = "UPDATE bebida SET nombre_bebida = ?, cantidad = ?, precio = ? WHERE id_bebida = ?";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("sidi", $nombre_bebida, $cantidad, $precio, $id_bebida);
        }

        if ($stmt->execute()) {
            echo "<div class='alert alert-success'>Bebida editada exitosamente.</div>";
        } else {
            echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Bebida</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center">
        <h1>Editar Bebida</h1>
        <button class="btn btn-secondary" onclick="window.location.href='listar.php'">Volver</button>
    </div>
    <form action="editar.php?id=<?php echo $id_bebida; ?>" method="POST" enctype="multipart/form-data" class="mt-4">
        <div class="mb-3">
            <label for="nombre_bebida" class="form-label">Nombre de la Bebida</label>
            <input type="text" name="nombre_bebida" id="nombre_bebida" class="form-control" value="<?php echo $row['nombre_bebida']; ?>" required>
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
            <label for="imagen" class="form-label">Imagen de la Bebida</label>
            <?php if (!empty($row['imagen'])): ?>
                <div class="mb-2">
                    <img src="../../uploads/bebidas/<?php echo $row['imagen']; ?>" alt="Imagen Actual" style="width: 100px; height: 100px; object-fit: cover;">
                </div>
            <?php endif; ?>
            <input type="file" name="imagen" id="imagen" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Actualizar Bebida</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>




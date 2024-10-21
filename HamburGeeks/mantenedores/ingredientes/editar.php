<?php
include '../../conexion.php'; 

// Verificar si se ha proporcionado el ID del ingrediente
if (isset($_GET['id'])) {
    $id_ingrediente = $_GET['id'];

    // Consultar el ingrediente por su ID
    $sql = "SELECT * FROM ingrediente WHERE id_ingrediente = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id_ingrediente);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verificar si se encontró el ingrediente
    if ($result->num_rows > 0) {
        $ingrediente = $result->fetch_assoc();
    } else {
        echo "<div class='alert alert-danger' role='alert'>Ingrediente no encontrado.</div>";
        exit();
    }
} else {
    echo "<div class='alert alert-danger' role='alert'>ID de ingrediente no especificado.</div>";
    exit();
}

// Procesar la edición del ingrediente
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre_ingrediente = $_POST['nombre_ingrediente'];
    $cantidad = $_POST['cantidad'];

    // Actualizar el ingrediente en la base de datos
    $sql = "UPDATE ingrediente SET nombre_ingrediente = ?, cantidad = ? WHERE id_ingrediente = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("sii", $nombre_ingrediente, $cantidad, $id_ingrediente);

    if ($stmt->execute()) {
        echo "<div class='container mt-3'>
                <div class='alert alert-success alert-dismissible fade show' role='alert'>
                    Ingrediente editado exitosamente.
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
    <title>Editar Ingrediente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center">
        <h1>Editar Ingrediente</h1>
        <button class="btn btn-secondary" onclick="window.location.href='listar.php'">Volver</button>
    </div>
    <form action="" method="POST" class="mt-4">
        <div class="mb-3">
            <label for="nombre_ingrediente" class="form-label">Nombre del Ingrediente</label>
            <input type="text" name="nombre_ingrediente" id="nombre_ingrediente" class="form-control" value="<?php echo htmlspecialchars($ingrediente['nombre_ingrediente']); ?>" required>
        </div>

        <div class="mb-3">
            <label for="cantidad" class="form-label">Cantidad (Stock)</label>
            <input type="number" name="cantidad" id="cantidad" class="form-control" value="<?php echo htmlspecialchars($ingrediente['cantidad']); ?>" required min="0">
        </div>

        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
include '../../conexion.php'; 

// Verificamos que se ha pasado un ID
if (!isset($_GET['id'])) {
    header("Location: listar.php");
    exit;
}

$id_acompaniamiento = $_GET['id'];
// Obtenemos la información del acompañamiento
$sql = "SELECT * FROM acompaniamiento WHERE id_acompaniamiento = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id_acompaniamiento);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    header("Location: listar.php");
    exit;
}

$row = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre_acompaniamiento = $_POST['nombre_acompaniamiento'];
    $cantidad = $_POST['cantidad'];
    $precio = $_POST['precio'];  // Agregado para actualizar el precio

    $sql = "UPDATE acompaniamiento SET nombre_acompaniamiento = ?, cantidad = ?, precio = ? WHERE id_acompaniamiento = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("sidi", $nombre_acompaniamiento, $cantidad, $precio, $id_acompaniamiento); // "sidi" para string, int, decimal, int
    if ($stmt->execute()) {
        echo "<div class='container mt-3'>
                <div class='alert alert-success alert-dismissible fade show' role='alert'>
                    Acompañamiento editado exitosamente.
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
    <title>Editar Acompañamiento</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center">
        <h1>Editar Acompañamiento</h1>
        <button class="btn btn-secondary" onclick="window.location.href='listar.php'">Volver</button>
    </div>
    <form action="editar.php?id=<?php echo $id_acompaniamiento; ?>" method="POST" class="mt-4">
        <div class="mb-3">
            <label for="nombre_acompaniamiento" class="form-label">Nombre del Acompañamiento</label>
            <input type="text" name="nombre_acompaniamiento" id="nombre_acompaniamiento" class="form-control" value="<?php echo $row['nombre_acompaniamiento']; ?>" required>
        </div>

        <div class="mb-3">
            <label for="cantidad" class="form-label">Cantidad (Stock)</label>
            <input type="number" name="cantidad" id="cantidad" class="form-control" value="<?php echo $row['cantidad']; ?>" required min="0">
        </div>

        <div class="mb-3">
            <label for="precio" class="form-label">Precio</label>
            <input type="text" name="precio" id="precio" class="form-control" value="<?php echo $row['precio']; ?>" required>
        </div>

        <button type="submit" class="btn btn-primary">Actualizar Acompañamiento</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

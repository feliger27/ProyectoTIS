<?php
include '../../conexion.php'; 

// Verificamos que se ha pasado un ID
if (!isset($_GET['id'])) {
    header("Location: listar.php");
    exit;
}

$id_promocion = $_GET['id'];

// Obtenemos la información de la promoción
$sql = "SELECT * FROM promocion WHERE id_promocion = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id_promocion);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    header("Location: listar.php");
    exit;
}

$row = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $codigo_promocion = $_POST['codigo_promocion'];
    $descripcion = $_POST['descripcion'];
    $fecha_inicio = $_POST['fecha_inicio'];
    $fecha_fin = $_POST['fecha_fin'];
    $porcentaje_descuento = $_POST['porcentaje_descuento'];
    $condiciones = $_POST['condiciones'];

    $sql = "UPDATE promocion SET codigo_promocion = ?, descripcion = ?, fecha_inicio = ?, fecha_fin = ?, porcentaje_descuento = ?, condiciones = ? WHERE id_promocion = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ssssisi", $codigo_promocion, $descripcion, $fecha_inicio, $fecha_fin, $porcentaje_descuento, $condiciones, $id_promocion);
    if ($stmt->execute()) {
        echo "<div class='container mt-3'>
                <div class='alert alert-success alert-dismissible fade show' role='alert'>
                    Promocion editada exitosamente.
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
    <title>Editar Promoción</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center">
        <h1>Editar Promoción</h1>
        <button class="btn btn-secondary" onclick="window.location.href='listar.php'">Volver</button>
    </div>
    <form action="editar.php?id=<?php echo $id_promocion; ?>" method="POST" class="mt-4">
        <div class="mb-3">
            <label for="codigo_promocion" class="form-label">Código de Promoción</label>
            <input type="text" name="codigo_promocion" id="codigo_promocion" class="form-control" value="<?php echo $row['codigo_promocion']; ?>" required>
        </div>

        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <input type="text" name="descripcion" id="descripcion" class="form-control" value="<?php echo $row['descripcion']; ?>" required>
        </div>

        <div class="mb-3">
            <label for="fecha_inicio" class="form-label">Fecha de Inicio</label>
            <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control" value="<?php echo $row['fecha_inicio']; ?>" required>
        </div>

        <div class="mb-3">
            <label for="fecha_fin" class="form-label">Fecha de Fin</label>
            <input type="date" name="fecha_fin" id="fecha_fin" class="form-control" value="<?php echo $row['fecha_fin']; ?>" required>
        </div>

        <div class="mb-3">
            <label for="porcentaje_descuento" class="form-label">Porcentaje de Descuento</label>
            <input type="number" name="porcentaje_descuento" id="porcentaje_descuento" class="form-control" value="<?php echo $row['porcentaje_descuento']; ?>" required min="0" max="100">
        </div>

        <div class="mb-3">
            <label for="condiciones" class="form-label">Condiciones</label>
            <input type="text" name="condiciones" id="condiciones" class="form-control" value="<?php echo $row['condiciones']; ?>" required>
        </div>

        <button type="submit" class="btn btn-primary">Actualizar Promoción</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

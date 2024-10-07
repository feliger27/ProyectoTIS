<?php
include '../../conexion.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $codigo_promocion = $_POST['codigo_promocion'];
    $descripcion = $_POST['descripcion'];
    $fecha_inicio = $_POST['fecha_inicio'];
    $fecha_fin = $_POST['fecha_fin'];
    $porcentaje_descuento = $_POST['porcentaje_descuento'];
    $condiciones = $_POST['condiciones'];

    $sql = "INSERT INTO promocion (codigo_promocion, descripcion, fecha_inicio, fecha_fin, porcentaje_descuento, condiciones) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ssssis", $codigo_promocion, $descripcion, $fecha_inicio, $fecha_fin, $porcentaje_descuento, $condiciones);  // "isssis" para int, string, string, string, int, string
    if ($stmt->execute()) {
        echo "<div class='alert alert-success' role='alert'>Promoción agregada exitosamente.</div>";
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
    <title>Agregar Nueva Promoción</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center">
        <h1>Agregar Nueva Promoción</h1>
        <button class="btn btn-secondary" onclick="window.location.href='listar.php'">Volver</button>
    </div>
    <form action="insertar.php" method="POST" class="mt-4">
        <div class="mb-3">
            <label for="codigo_promocion" class="form-label">Código de la Promoción</label>
            <input type="text" name="codigo_promocion" id="codigo_promocion" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea name="descripcion" id="descripcion" class="form-control" rows="3"></textarea>
        </div>

        <div class="mb-3">
            <label for="fecha_inicio" class="form-label">Fecha de Inicio</label>
            <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="fecha_fin" class="form-label">Fecha de Fin</label>
            <input type="date" name="fecha_fin" id="fecha_fin" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="porcentaje_descuento" class="form-label">Porcentaje de Descuento</label>
            <input type="number" name="porcentaje_descuento" id="porcentaje_descuento" class="form-control" required min="0" max="100">
        </div>

        <div class="mb-3">
            <label for="condiciones" class="form-label">Condiciones</label>
            <textarea name="condiciones" id="condiciones" class="form-control" rows="3"></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Guardar Promoción</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
include '../../conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre_promocion = $_POST['nombre_promocion'];
    $descripcion_promocion = $_POST['descripcion_promocion'];
    $fecha_inicio = $_POST['fecha_inicio'];
    $fecha_fin = $_POST['fecha_fin'];
    $porcentaje_descuento = $_POST['porcentaje_descuento'];
    $id_producto = $_POST['id_producto'] ?: null;
    $tipo_producto = $_POST['tipo_producto'];

    // Determinar el campo id_producto para la tabla promocion
    $campo_id = null;
    if ($tipo_producto == "hamburguesa") $campo_id = 'id_hamburguesa';
    elseif ($tipo_producto == "postre") $campo_id = 'id_postre';
    elseif ($tipo_producto == "bebida") $campo_id = 'id_bebida';
    elseif ($tipo_producto == "acompaniamiento") $campo_id = 'id_acompaniamiento';
    elseif ($tipo_producto == "combo") $campo_id = 'id_combo';


    // Convertir las fechas a formato Y-m-d H:i:s para la base de datos
    $fecha_inicio = date('Y-m-d H:i:s', strtotime($fecha_inicio));
    $fecha_fin = date('Y-m-d H:i:s', strtotime($fecha_fin));

    // Verificar si las fechas son correctas
    if (!$fecha_inicio || !$fecha_fin) {
        echo "<div class='container mt-3'>
                <div class='alert alert-danger alert-dismissible fade show' role='alert'>
                    Error: Las fechas no son válidas.
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>
              </div>";
        exit;
    }
    // Preparar la consulta para insertar la promoción
    $sql = "INSERT INTO promocion (nombre_promocion, descripcion_promocion, fecha_inicio, fecha_fin, porcentaje_descuento, $campo_id) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ssssii", $nombre_promocion, $descripcion_promocion, $fecha_inicio, $fecha_fin, $porcentaje_descuento, $id_producto);

    if ($stmt->execute()) {
        echo "<div class='container mt-3'>
                <div class='alert alert-success alert-dismissible fade show' role='alert'>
                    Promoción agregada exitosamente.
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
    <title>Insertar Promoción</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center">
        <h1>Agregar Nueva Promoción</h1>
        <button class="btn btn-secondary" onclick="window.location.href='listar.php'">Volver</button>
    </div>
    <form action="insertar_promocion.php" method="POST" id="formPromocion">
        <div class="mb-3">
            <label for="nombre_promocion" class="form-label">Nombre de la Promoción</label>
            <input type="text" name="nombre_promocion" id="nombre_promocion" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="descripcion_promocion" class="form-label">Descripción</label>
            <textarea name="descripcion_promocion" id="descripcion_promocion" class="form-control" required></textarea>
        </div>

        <div class="mb-3">
            <label for="fecha_inicio" class="form-label">Fecha de Inicio</label>
            <input type="datetime-local" name="fecha_inicio" id="fecha_inicio" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="fecha_fin" class="form-label">Fecha de Fin</label>
            <input type="datetime-local" name="fecha_fin" id="fecha_fin" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="porcentaje_descuento" class="form-label">Porcentaje de Descuento</label>
            <input type="number" name="porcentaje_descuento" id="porcentaje_descuento" class="form-control" min="1" max="100" required>
        </div>

        <div class="mb-3">
            <label for="tipo_producto" class="form-label">Seleccionar Tipo de Producto</label>
            <select name="tipo_producto" id="tipo_producto" class="form-select" required>
                <option value="">Seleccionar tipo</option>
                <option value="hamburguesa">Hamburguesa</option>
                <option value="postre">Postre</option>
                <option value="bebida">Bebida</option>
                <option value="acompaniamiento">Acompañamiento</option>
                <option value="combo">combo</option>
            </select>
        </div>

        <div class="mb-3" id="productoSeleccionadoDiv" style="display: none;">
            <label for="id_producto" class="form-label">Seleccionar Producto</label>
            <select name="id_producto" id="id_producto" class="form-select">
                <!-- Opciones dinámicas cargadas con AJAX -->
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Agregar Promoción</button>
    </form>
</div>

<script>
$(document).ready(function() {
    $('#tipo_producto').on('change', function() {
        const tipoProducto = $(this).val();
        $('#productoSeleccionadoDiv').hide();
        $('#id_producto').empty().append('<option value="">Seleccionar producto</option>');

        if (tipoProducto) {
            $.ajax({
                url: 'obtener_productos.php',
                type: 'GET',
                data: { tipo: tipoProducto },
                dataType: 'json',
                success: function(data) {
                    if (data.length > 0) {
                        data.forEach(function(producto) {
                            $('#id_producto').append(`<option value="${producto.id}">${producto.nombre}</option>`);
                        });
                        $('#productoSeleccionadoDiv').show();
                    }
                }
            });
        }
    });
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>







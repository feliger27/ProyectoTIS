<?php
include '../../conexion.php';

// Obtener el ID de la promoción desde la URL
$id_promocion = $_GET['id'];

// Consultar los datos actuales de la promoción
$sql = "SELECT * FROM promocion WHERE id_promocion = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id_promocion);
$stmt->execute();
$result = $stmt->get_result();
$promocion = $result->fetch_assoc();

// Verificar el tipo de producto seleccionado actualmente
$tipo_producto = '';
$id_producto = null;
if ($promocion['id_hamburguesa'] !== null) {
    $tipo_producto = 'hamburguesa';
    $id_producto = $promocion['id_hamburguesa'];
} elseif ($promocion['id_postre'] !== null) {
    $tipo_producto = 'postre';
    $id_producto = $promocion['id_postre'];
} elseif ($promocion['id_bebida'] !== null) {
    $tipo_producto = 'bebida';
    $id_producto = $promocion['id_bebida'];
} elseif ($promocion['id_acompaniamiento'] !== null) {
    $tipo_producto = 'acompaniamiento';
    $id_producto = $promocion['id_acompaniamiento'];
}
 elseif ($promocion['id_combo'] !== null) {
    $tipo_producto = 'combo';
    $id_producto = $promocion['id_combo'];
}

// Procesar la actualización al enviar el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre_promocion = $_POST['nombre_promocion'];
    $descripcion_promocion = $_POST['descripcion_promocion'];
    $fecha_inicio = $_POST['fecha_inicio'];
    $fecha_fin = $_POST['fecha_fin'];
    $porcentaje_descuento = $_POST['porcentaje_descuento'];
    $id_producto = $_POST['id_producto'] ?: null;
    $tipo_producto = $_POST['tipo_producto'];

    // Determinar el campo id_producto para actualizar en la tabla promocion
    $sql_update = "UPDATE promocion SET nombre_promocion=?, descripcion_promocion=?, fecha_inicio=?, fecha_fin=?, porcentaje_descuento=?, 
                   id_hamburguesa=NULL, id_postre=NULL, id_bebida=NULL, id_acompaniamiento=NULL, id_combo=NULL, ";
    $campo_id = null;

    if ($tipo_producto == "hamburguesa") $campo_id = 'id_hamburguesa';
    elseif ($tipo_producto == "postre") $campo_id = 'id_postre';
    elseif ($tipo_producto == "bebida") $campo_id = 'id_bebida';
    elseif ($tipo_producto == "acompaniamiento") $campo_id = 'id_acompaniamiento';
    elseif ($tipo_producto == "combo") $campo_id = 'id_combo';

    $sql_update .= "$campo_id=? WHERE id_promocion=?";
    $stmt = $conexion->prepare($sql_update);
    $stmt->bind_param("ssssiii", $nombre_promocion, $descripcion_promocion, $fecha_inicio, $fecha_fin, $porcentaje_descuento, $id_producto, $id_promocion);

    if ($stmt->execute()) {
        echo "<div class='container mt-3'>
                <div class='alert alert-success alert-dismissible fade show' role='alert'>
                    Promoción actualizada exitosamente.
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center">
        <h1>Editar Promoción</h1>
        <button class="btn btn-secondary" onclick="window.location.href='listar.php'">Volver</button>
    </div>
    <form action="editar_promocion.php?id=<?php echo $id_promocion; ?>" method="POST" id="formPromocion">
        <div class="mb-3">
            <label for="nombre_promocion" class="form-label">Nombre de la Promoción</label>
            <input type="text" name="nombre_promocion" id="nombre_promocion" class="form-control" required value="<?php echo $promocion['nombre_promocion']; ?>">
        </div>

        <div class="mb-3">
            <label for="descripcion_promocion" class="form-label">Descripción</label>
            <textarea name="descripcion_promocion" id="descripcion_promocion" class="form-control" required><?php echo $promocion['descripcion_promocion']; ?></textarea>
        </div>

        <div class="mb-3">
            <label for="fecha_inicio" class="form-label">Fecha de Inicio</label>
            <input type="datetime-local" name="fecha_inicio" id="fecha_inicio" class="form-control" required value="<?php echo date('Y-m-d\TH:i', strtotime($promocion['fecha_inicio'])); ?>">
        </div>

        <div class="mb-3">
            <label for="fecha_fin" class="form-label">Fecha de Fin</label>
            <input type="datetime-local" name="fecha_fin" id="fecha_fin" class="form-control" required value="<?php echo date('Y-m-d\TH:i', strtotime($promocion['fecha_fin'])); ?>">
        </div>

        <div class="mb-3">
            <label for="porcentaje_descuento" class="form-label">Porcentaje de Descuento</label>
            <input type="number" name="porcentaje_descuento" id="porcentaje_descuento" class="form-control" min="1" max="100" required value="<?php echo $promocion['porcentaje_descuento']; ?>">
        </div>

        <div class="mb-3">
            <label for="tipo_producto" class="form-label">Seleccionar Tipo de Producto</label>
            <select name="tipo_producto" id="tipo_producto" class="form-select" required>
                <option value="">Seleccionar tipo</option>
                <option value="hamburguesa" <?php if ($tipo_producto == 'hamburguesa') echo 'selected'; ?>>Hamburguesa</option>
                <option value="postre" <?php if ($tipo_producto == 'postre') echo 'selected'; ?>>Postre</option>
                <option value="bebida" <?php if ($tipo_producto == 'bebida') echo 'selected'; ?>>Bebida</option>
                <option value="acompaniamiento" <?php if ($tipo_producto == 'acompaniamiento') echo 'selected'; ?>>Acompañamiento</option>
                <option value="combo" <?php if ($tipo_producto == 'combo') echo 'selected'; ?>>Combo</option>
            </select>
        </div>

        <div class="mb-3" id="productoSeleccionadoDiv" style="display: <?php echo $tipo_producto ? 'block' : 'none'; ?>">
            <label for="id_producto" class="form-label">Seleccionar Producto</label>
            <select name="id_producto" id="id_producto" class="form-select">
                <!-- Opciones dinámicas cargadas con AJAX -->
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Actualizar Promoción</button>
    </form>
</div>

<script>
$(document).ready(function() {
    const tipoProducto = "<?php echo $tipo_producto; ?>";
    const idProductoSeleccionado = "<?php echo $id_producto; ?>";

    function cargarProductos(tipo) {
        $.ajax({
            url: 'obtener_productos.php',
            type: 'GET',
            data: { tipo: tipo },
            dataType: 'json',
            success: function(data) {
                $('#id_producto').empty().append('<option value="">Seleccionar producto</option>');
                data.forEach(function(producto) {
                    const selected = producto.id == idProductoSeleccionado ? 'selected' : '';
                    $('#id_producto').append(`<option value="${producto.id}" ${selected}>${producto.nombre}</option>`);
                });
                $('#productoSeleccionadoDiv').show();
            }
        });
    }

    if (tipoProducto) {
        cargarProductos(tipoProducto);
    }

    $('#tipo_producto').on('change', function() {
        const tipo = $(this).val();
        $('#id_producto').empty();
        if (tipo) {
            cargarProductos(tipo);
        } else {
            $('#productoSeleccionadoDiv').hide();
        }
    });
});
</script>

</body>
</html>


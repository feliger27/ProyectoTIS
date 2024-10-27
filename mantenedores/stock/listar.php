<?php
include '../../conexion.php'; 

// Consultas para obtener el stock de cada tipo de producto
$ingredientes_query = "SELECT * FROM ingrediente";
$postres_query = "SELECT * FROM postre";
$acompañamientos_query = "SELECT * FROM acompaniamiento";
$bebidas_query = "SELECT * FROM bebida";
$aderezos_query = "SELECT * FROM aderezo";

$ingredientes = $conexion->query($ingredientes_query);
$postres = $conexion->query($postres_query);
$acompañamientos = $conexion->query($acompañamientos_query);
$bebidas = $conexion->query($bebidas_query);
$aderezos = $conexion->query($aderezos_query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mantenedor de Stock</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .table-container {
            display: none; /* Ocultar todas las tablas por defecto */
        }
    </style>
</head>
<body>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center">
        <h1>Stock</h1>
        <button class="btn btn-secondary" onclick="window.location.href='../../index.php'">Volver</button>
    </div>
    <button class="btn btn-outline-primary" onclick="window.open('reportes.php', '_blank')">PDF</button>
    
    <!-- Tabla de Ingredientes -->
    <h2 class="mt-4" style="cursor:pointer;" onclick="toggleTable('ingrediente')">Ingredientes</h2>
    <button class="btn btn-outline-primary mb-2" onclick="window.open('reportes.php?tipo=ingrediente', '_blank')">PDF</button>
    <div id="ingrediente" class="table-container">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Cantidad</th>
                    <th>Umbral</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $ingredientes->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id_ingrediente']; ?></td>
                        <td><?php echo $row['nombre_ingrediente']; ?></td>
                        <td>
                            <input type="number" id="cantidad-ingrediente-<?php echo $row['id_ingrediente']; ?>" 
                                   value="<?php echo $row['cantidad']; ?>" class="form-control" style="width: 80px;">
                        </td>
                        <td>
                            <input type="number" id="umbral-ingrediente-<?php echo $row['id_ingrediente']; ?>" 
                                   value="<?php echo $row['umbral_reabastecimiento']; ?>" class="form-control" style="width: 80px;">
                        </td>
                        <td>
                            <button class="btn btn-primary" 
                                    onclick="actualizarCantidad(<?php echo $row['id_ingrediente']; ?>, 'ingrediente')">Guardar</button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Tabla de Postres -->
    <h2 class="mt-4" style="cursor:pointer;" onclick="toggleTable('postre')">Postres</h2>
    <button class="btn btn-outline-primary mb-2" onclick="window.open('reportes.php?tipo=postre', '_blank')">PDF</button>
    <div id="postre" class="table-container">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Cantidad</th>
                    <th>Umbral</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $postres->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id_postre']; ?></td>
                        <td><?php echo $row['nombre_postre']; ?></td>
                        <td>
                            <input type="number" id="cantidad-postre-<?php echo $row['id_postre']; ?>" 
                                   value="<?php echo $row['cantidad']; ?>" class="form-control" style="width: 80px;">
                        </td>
                        <td>
                            <input type="number" id="umbral-postre-<?php echo $row['id_postre']; ?>" 
                                   value="<?php echo $row['umbral_reabastecimiento']; ?>" class="form-control" style="width: 80px;">
                        </td>
                        <td>
                            <button class="btn btn-primary" 
                                    onclick="actualizarCantidad(<?php echo $row['id_postre']; ?>, 'postre')">Guardar</button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Tabla de Acompañamientos -->
    <h2 class="mt-4" style="cursor:pointer;" onclick="toggleTable('acompaniamiento')">Acompañamientos</h2>
    <button class="btn btn-outline-primary mb-2" onclick="window.open('reportes.php?tipo=acompaniamiento', '_blank')">PDF</button>
    <div id="acompaniamiento" class="table-container">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Cantidad</th>
                    <th>Umbral</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $acompañamientos->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id_acompaniamiento']; ?></td>
                        <td><?php echo $row['nombre_acompaniamiento']; ?></td>
                        <td>
                            <input type="number" id="cantidad-acompaniamiento-<?php echo $row['id_acompaniamiento']; ?>" 
                                   value="<?php echo $row['cantidad']; ?>" class="form-control" style="width: 80px;">
                        </td>
                        <td>
                            <input type="number" id="umbral-acompaniamiento-<?php echo $row['id_acompaniamiento']; ?>" 
                                   value="<?php echo $row['umbral_reabastecimiento']; ?>" class="form-control" style="width: 80px;">
                        </td>
                        <td>
                            <button class="btn btn-primary" 
                                    onclick="actualizarCantidad(<?php echo $row['id_acompaniamiento']; ?>, 'acompaniamiento')">Guardar</button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Tabla de Bebidas -->
    <h2 class="mt-4" style="cursor:pointer;" onclick="toggleTable('bebida')">Bebidas</h2>
    <button class="btn btn-outline-primary mb-2" onclick="window.open('reportes.php?tipo=bebida', '_blank')">PDF</button>
    <div id="bebida" class="table-container">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Cantidad</th>
                    <th>Umbral</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $bebidas->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id_bebida']; ?></td>
                        <td><?php echo $row['nombre_bebida']; ?></td>
                        <td>
                            <input type="number" id="cantidad-bebida-<?php echo $row['id_bebida']; ?>" 
                                   value="<?php echo $row['cantidad']; ?>" class="form-control" style="width: 80px;">
                        </td>
                        <td>
                            <input type="number" id="umbral-bebida-<?php echo $row['id_bebida']; ?>" 
                                   value="<?php echo $row['umbral_reabastecimiento']; ?>" class="form-control" style="width: 80px;">
                        </td>
                        <td>
                            <button class="btn btn-primary" 
                                    onclick="actualizarCantidad(<?php echo $row['id_bebida']; ?>, 'bebida')">Guardar</button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Tabla de Aderezos -->
    <h2 class="mt-4" style="cursor:pointer;" onclick="toggleTable('aderezo')">Aderezos</h2>
    <button class="btn btn-outline-primary mb-2" onclick="window.open('reportes.php?tipo=aderezo', '_blank')">PDF</button>
    <div id="aderezo" class="table-container">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Cantidad</th>
                    <th>Umbral</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $aderezos->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id_aderezo']; ?></td>
                        <td><?php echo $row['nombre_aderezo']; ?></td>
                        <td>
                            <input type="number" id="cantidad-aderezo-<?php echo $row['id_aderezo']; ?>" 
                                   value="<?php echo $row['cantidad']; ?>" class="form-control" style="width: 80px;">
                        </td>
                        <td>
                            <input type="number" id="umbral-aderezo-<?php echo $row['id_aderezo']; ?>" 
                                   value="<?php echo $row['umbral_reabastecimiento']; ?>" class="form-control" style="width: 80px;">
                        </td>
                        <td>
                            <button class="btn btn-primary" 
                                    onclick="actualizarCantidad(<?php echo $row['id_aderezo']; ?>, 'aderezo')">Guardar</button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
function toggleTable(tableId) {
    $('#' + tableId).toggle(); // Alternar la visibilidad de la tabla
}

function actualizarCantidad(id, tabla) {
    const nuevaCantidad = document.getElementById(`cantidad-${tabla}-${id}`).value;
    const nuevoUmbral = document.getElementById(`umbral-${tabla}-${id}`).value;

    // Verifica que la cantidad y el umbral sean números válidos antes de enviar
    if (isNaN(nuevaCantidad) || nuevaCantidad === '' || nuevaCantidad < 0 || isNaN(nuevoUmbral) || nuevoUmbral === '' || nuevoUmbral < 0) {
        alert('Cantidad o umbral no válido (deben ser números positivos)');
        return;
    }
    let mensaje = '';
    if (nuevaCantidad != document.getElementById(`cantidad-${tabla}-${id}`).defaultValue) {
        mensaje += 'Cantidad actualizada. ';
    }
    if (nuevoUmbral != document.getElementById(`umbral-${tabla}-${id}`).defaultValue) {
        mensaje += 'Umbral actualizado. ';
    }

    $.ajax({
        type: "POST",
        url: "editar.php",
        data: {
            id: id,
            cantidad: nuevaCantidad,
            umbral_reabastecimiento: nuevoUmbral,
            tabla: tabla
        },
        success: function(response) {
            alert(mensaje.trim() || 'Sin cambios realizados.'); // Muestra el mensaje correspondiente
        },
        error: function(xhr, status, error) {
            console.error(error);
            alert('Error al intentar actualizar la cantidad y el umbral');
        }
    });
} 
</script>

</body>
</html>














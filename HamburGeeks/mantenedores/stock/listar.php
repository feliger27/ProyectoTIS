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

    <!-- Tabla de Ingredientes -->
    <h2 class="mt-4" style="cursor:pointer;" onclick="toggleTable('ingredientes')">Ingredientes</h2>
    <div id="ingredientes" class="table-container">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th style="width: 30%;">ID</th>
                    <th style="width: 40%;">Nombre</th>
                    <th style="width: 20%;">Cantidad</th>
                    <th style="width: 10%;">Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $ingredientes->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id_ingrediente']; ?></td>
                        <td><?php echo $row['nombre_ingrediente']; ?></td>
                        <td>
                            <input type="number" id="cantidad-ingredientes-<?php echo $row['id_ingrediente']; ?>" 
                                   value="<?php echo $row['cantidad']; ?>" class="form-control" style="width: 80px;">
                        </td>
                        <td>
                            <button class="btn btn-primary" 
                                    onclick="actualizarCantidad(<?php echo $row['id_ingrediente']; ?>, 'ingredientes')">Guardar</button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Tabla de Postres -->
    <h2 class="mt-4" style="cursor:pointer;" onclick="toggleTable('postres')">Postres</h2>
    <div id="postres" class="table-container">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th style="width: 30%;">ID</th>
                    <th style="width: 40%;">Nombre</th>
                    <th style="width: 20%;">Cantidad</th>
                    <th style="width: 10%;">Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $postres->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id_postre']; ?></td>
                        <td><?php echo $row['nombre_postre']; ?></td>
                        <td>
                            <input type="number" id="cantidad-postres-<?php echo $row['id_postre']; ?>" 
                                   value="<?php echo $row['cantidad']; ?>" class="form-control" style="width: 80px;">
                        </td>
                        <td>
                            <button class="btn btn-primary" 
                                    onclick="actualizarCantidad(<?php echo $row['id_postre']; ?>, 'postres')">Guardar</button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Tabla de Acompañamientos -->
    <h2 class="mt-4" style="cursor:pointer;" onclick="toggleTable('acompaniamientos')">Acompañamientos</h2>
    <div id="acompaniamientos" class="table-container">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th style="width: 30%;">ID</th>
                    <th style="width: 40%;">Nombre</th>
                    <th style="width: 20%;">Cantidad</th>
                    <th style="width: 10%;">Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $acompañamientos->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id_acompaniamiento']; ?></td>
                        <td><?php echo $row['nombre_acompaniamiento']; ?></td>
                        <td>
                            <input type="number" id="cantidad-acompaniamientos-<?php echo $row['id_acompaniamiento']; ?>" 
                                   value="<?php echo $row['cantidad']; ?>" class="form-control" style="width: 80px;">
                        </td>
                        <td>
                            <button class="btn btn-primary" 
                                    onclick="actualizarCantidad(<?php echo $row['id_acompaniamiento']; ?>, 'acompaniamientos')">Guardar</button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Tabla de Bebidas -->
    <h2 class="mt-4" style="cursor:pointer;" onclick="toggleTable('bebidas')">Bebidas</h2>
    <div id="bebidas" class="table-container">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th style="width: 30%;">ID</th>
                    <th style="width: 40%;">Nombre</th>
                    <th style="width: 20%;">Cantidad</th>
                    <th style="width: 10%;">Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $bebidas->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id_bebida']; ?></td>
                        <td><?php echo $row['nombre_bebida']; ?></td>
                        <td>
                            <input type="number" id="cantidad-bebidas-<?php echo $row['id_bebida']; ?>" 
                                   value="<?php echo $row['cantidad']; ?>" class="form-control" style="width: 80px;">
                        </td>
                        <td>
                            <button class="btn btn-primary" 
                                    onclick="actualizarCantidad(<?php echo $row['id_bebida']; ?>, 'bebidas')">Guardar</button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Tabla de Aderezos -->
    <h2 class="mt-4" style="cursor:pointer;" onclick="toggleTable('aderezos')">Aderezos</h2>
    <div id="aderezos" class="table-container">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th style="width: 30%;">ID</th>
                    <th style="width: 40%;">Nombre</th>
                    <th style="width: 20%;">Cantidad</th>
                    <th style="width: 10%;">Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $aderezos->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id_aderezo']; ?></td>
                        <td><?php echo $row['nombre_aderezo']; ?></td>
                        <td>
                            <input type="number" id="cantidad-aderezos-<?php echo $row['id_aderezo']; ?>" 
                                   value="<?php echo $row['cantidad']; ?>" class="form-control" style="width: 80px;">
                        </td>
                        <td>
                            <button class="btn btn-primary" 
                                    onclick="actualizarCantidad(<?php echo $row['id_aderezo']; ?>, 'aderezos')">Guardar</button>
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

    // Verifica que la cantidad sea un número válido antes de enviar
    if (isNaN(nuevaCantidad) || nuevaCantidad === '') {
        alert('Cantidad no válida');
        return;
    }

    // Depurar: mostrar el valor en la consola del navegador
    console.log(`ID: ${id}, Nueva Cantidad: ${nuevaCantidad}, Tabla: ${tabla}`);

    $.ajax({
        type: "POST",
        url: "editar.php",
        data: {
            id: id,
            cantidad: nuevaCantidad,
            tabla: tabla
        },
        success: function(response) {
            alert(response); // Muestra la respuesta del servidor
        },
        error: function(xhr, status, error) {
            console.error(error);
            alert('Error al intentar actualizar la cantidad');
        }
    });
}

</script>

</body>
</html>






</script>

</body>
</html>










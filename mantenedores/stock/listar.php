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
        <button class="btn btn-secondary" onclick="window.location.href='../../index/index.php'">Volver</button>
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
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $ingredientes->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id_ingrediente']; ?></td>
                        <td><?php echo $row['nombre_ingrediente']; ?></td>
                        <td>
                            <input type="number" class="form-control stock-input"
                                   id="cantidad-ingrediente-<?php echo $row['id_ingrediente']; ?>"
                                   value="<?php echo $row['cantidad']; ?>" data-id="<?php echo $row['id_ingrediente']; ?>"
                                   data-type="ingrediente" style="width: 80px;">
                        </td>
                        <td>
                            <input type="number" class="form-control umbral-input"
                                   id="umbral-ingrediente-<?php echo $row['id_ingrediente']; ?>"
                                   value="<?php echo $row['umbral_reabastecimiento']; ?>"
                                   data-id="<?php echo $row['id_ingrediente']; ?>" data-type="ingrediente"
                                   style="width: 80px;">
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
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $postres->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id_postre']; ?></td>
                        <td><?php echo $row['nombre_postre']; ?></td>
                        <td>
                            <input type="number" class="form-control stock-input"
                                   id="cantidad-postre-<?php echo $row['id_postre']; ?>"
                                   value="<?php echo $row['cantidad']; ?>" data-id="<?php echo $row['id_postre']; ?>"
                                   data-type="postre" style="width: 80px;">
                        </td>
                        <td>
                            <input type="number" class="form-control umbral-input"
                                   id="umbral-postre-<?php echo $row['id_postre']; ?>"
                                   value="<?php echo $row['umbral_reabastecimiento']; ?>"
                                   data-id="<?php echo $row['id_postre']; ?>" data-type="postre" style="width: 80px;">
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
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $aderezos->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id_aderezo']; ?></td>
                        <td><?php echo $row['nombre_aderezo']; ?></td>
                        <td>
                            <input type="number" class="form-control stock-input"
                                   id="cantidad-aderezo-<?php echo $row['id_aderezo']; ?>"
                                   value="<?php echo $row['cantidad']; ?>" data-id="<?php echo $row['id_aderezo']; ?>"
                                   data-type="aderezo" style="width: 80px;">
                        </td>
                        <td>
                            <input type="number" class="form-control umbral-input"
                                   id="umbral-aderezo-<?php echo $row['id_aderezo']; ?>"
                                   value="<?php echo $row['umbral_reabastecimiento']; ?>"
                                   data-id="<?php echo $row['id_aderezo']; ?>" data-type="aderezo" style="width: 80px;">
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
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $bebidas->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id_bebida']; ?></td>
                        <td><?php echo $row['nombre_bebida']; ?></td>
                        <td>
                            <input type="number" class="form-control stock-input"
                                   id="cantidad-bebida-<?php echo $row['id_bebida']; ?>"
                                   value="<?php echo $row['cantidad']; ?>" data-id="<?php echo $row['id_bebida']; ?>"
                                   data-type="bebida" style="width: 80px;">
                        </td>
                        <td>
                            <input type="number" class="form-control umbral-input"
                                   id="umbral-bebida-<?php echo $row['id_bebida']; ?>"
                                   value="<?php echo $row['umbral_reabastecimiento']; ?>"
                                   data-id="<?php echo $row['id_bebida']; ?>" data-type="bebida" style="width: 80px;">
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
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $acompañamientos->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id_acompaniamiento']; ?></td>
                        <td><?php echo $row['nombre_acompaniamiento']; ?></td>
                        <td>
                            <input type="number" class="form-control stock-input"
                                   id="cantidad-acompaniamiento-<?php echo $row['id_acompaniamiento']; ?>"
                                   value="<?php echo $row['cantidad']; ?>"
                                   data-id="<?php echo $row['id_acompaniamiento']; ?>" data-type="acompaniamiento"
                                   style="width: 80px;">
                        </td>
                        <td>
                            <input type="number" class="form-control umbral-input"
                                   id="umbral-acompaniamiento-<?php echo $row['id_acompaniamiento']; ?>"
                                   value="<?php echo $row['umbral_reabastecimiento']; ?>"
                                   data-id="<?php echo $row['id_acompaniamiento']; ?>" data-type="acompaniamiento"
                                   style="width: 80px;">
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

</div>

</body>
</html>


    <script>
        $(document).ready(function () {
            // Detectar cambios en los campos de cantidad y umbral
            $('.stock-input, .umbral-input').on('change', function () {
                var id = $(this).data('id');
                var tipo = $(this).data('type');
                var cantidad = $('#cantidad-' + tipo + '-' + id).val();
                var umbral = $('#umbral-' + tipo + '-' + id).val();

                // Realizar la actualización mediante AJAX
                actualizarStock(id, tipo, cantidad, umbral);
            });
        });

        function actualizarStock(id, tipo, cantidad, umbral) {
            $.ajax({
                type: "POST",
                url: "editar_stock.php", // El archivo que procesará la actualización
                data: {
                    id: id,
                    tipo: tipo,
                    cantidad: cantidad,
                    umbral: umbral
                },
                success: function (response) {
                    //alert('Stock actualizado exitosamente');
                },
                error: function (xhr, status, error) {
                    console.error(error);
                    alert('Error al actualizar el stock');
                }
            });
        }

        // Toggle para mostrar/ocultar las tablas
        function toggleTable(tableId) {
            $('#' + tableId).toggle(); // Alternar la visibilidad de la tabla
        }
    </script>

</body>

</html>
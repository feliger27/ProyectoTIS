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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            font-family: 'Arial', sans-serif;
        }

        .tab-content {
            margin-top: 20px;
        }

        .table-container {
            margin-bottom: 30px;
        }

        .header-btn {
            margin-top: 20px;
        }

        .table th, .table td {
            vertical-align: middle;
        }

        .stock-input, .umbral-input {
            width: 80px;
        }

        .btn-pdf {
            margin-top: 20px;
            background-color: #007bff;
            color: white;
        }

        .nav-link {
            cursor: pointer;
        }
    </style>
</head>

<body>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center">
        <h1>Gestión de Stock <button class="btn btn-outline-primary" onclick="window.open('reportes.php', '_blank')">PDF</button></h1>
        <button class="btn btn-secondary" onclick="window.location.href='../../index/index.php'">Volver</button>
    </div>
    <!-- Pestañas -->
    <ul class="nav nav-tabs" id="productTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <a class="nav-link active" id="ingrediente-tab" data-bs-toggle="tab" href="#ingrediente" role="tab">Ingredientes</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="postre-tab" data-bs-toggle="tab" href="#postre" role="tab">Postres</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="aderezo-tab" data-bs-toggle="tab" href="#aderezo" role="tab">Aderezos</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="bebida-tab" data-bs-toggle="tab" href="#bebida" role="tab">Bebidas</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="acompaniamiento-tab" data-bs-toggle="tab" href="#acompaniamiento" role="tab">Acompañamientos</a>
        </li>
    </ul>

    <!-- Contenido de las pestañas -->
    <div class="tab-content" id="productTabsContent">
        <!-- Ingredientes -->
        <div class="tab-pane fade show active" id="ingrediente" role="tabpanel">
            <div class="d-flex justify-content-between">
                <h2>Ingredientes</h2>
                <button class="btn btn-outline-primary mb-2" onclick="window.open('reportes.php?tipo=ingrediente', '_blank')">Generar PDF</button>
            </div>
            <div class="table-container table-responsive">
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
                                           value="<?php echo $row['cantidad']; ?>" data-id="<?php echo $row['id_ingrediente']; ?> "
                                           data-type="ingrediente">
                                </td>
                                <td>
                                    <input type="number" class="form-control umbral-input"
                                           id="umbral-ingrediente-<?php echo $row['id_ingrediente']; ?>"
                                           value="<?php echo $row['umbral_reabastecimiento']; ?>"
                                           data-id="<?php echo $row['id_ingrediente']; ?>" data-type="ingrediente">
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Postres -->
        <div class="tab-pane fade" id="postre" role="tabpanel">
            <div class="d-flex justify-content-between">
                <h2>Postres</h2>
                <button class="btn btn-outline-primary mb-2" onclick="window.open('reportes.php?tipo=postre', '_blank')">Generar PDF</button>
            </div>
            <div class="table-container table-responsive">
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
                                           data-type="postre">
                                </td>
                                <td>
                                    <input type="number" class="form-control umbral-input"
                                           id="umbral-postre-<?php echo $row['id_postre']; ?>"
                                           value="<?php echo $row['umbral_reabastecimiento']; ?>"
                                           data-id="<?php echo $row['id_postre']; ?>" data-type="postre">
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Aderezos -->
        <div class="tab-pane fade" id="aderezo" role="tabpanel">
            <div class="d-flex justify-content-between">
                <h2>Aderezos</h2>
                <button class="btn btn-outline-primary mb-2" onclick="window.open('reportes.php?tipo=aderezo', '_blank')">Generar PDF</button>
            </div>
            <div class="table-container table-responsive">
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
                                           data-type="aderezo">
                                </td>
                                <td>
                                    <input type="number" class="form-control umbral-input"
                                           id="umbral-aderezo-<?php echo $row['id_aderezo']; ?>"
                                           value="<?php echo $row['umbral_reabastecimiento']; ?>"
                                           data-id="<?php echo $row['id_aderezo']; ?>" data-type="aderezo">
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Bebidas -->
        <div class="tab-pane fade" id="bebida" role="tabpanel">
            <div class="d-flex justify-content-between">
                <h2>Bebidas</h2>
                <button class="btn btn-outline-primary mb-2" onclick="window.open('reportes.php?tipo=bebida', '_blank')">Generar PDF</button>
            </div>
            <div class="table-container table-responsive">
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
                                           data-type="bebida">
                                </td>
                                <td>
                                    <input type="number" class="form-control umbral-input"
                                           id="umbral-bebida-<?php echo $row['id_bebida']; ?>"
                                           value="<?php echo $row['umbral_reabastecimiento']; ?>"
                                           data-id="<?php echo $row['id_bebida']; ?>" data-type="bebida">
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Acompañamientos -->
        <div class="tab-pane fade" id="acompaniamiento" role="tabpanel">
            <div class="d-flex justify-content-between">
                <h2>Acompañamientos</h2>
                <button class="btn btn-outline-primary mb-2" onclick="window.open('reportes.php?tipo=acompaniamiento', '_blank')">Generar PDF</button>
            </div>
            <div class="table-container table-responsive">
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
                                           value="<?php echo $row['cantidad']; ?>" data-id="<?php echo $row['id_acompaniamiento']; ?>"
                                           data-type="acompaniamiento">
                                </td>
                                <td>
                                    <input type="number" class="form-control umbral-input"
                                           id="umbral-acompaniamiento-<?php echo $row['id_acompaniamiento']; ?>"
                                           value="<?php echo $row['umbral_reabastecimiento']; ?>"
                                           data-id="<?php echo $row['id_acompaniamiento']; ?>" data-type="acompaniamiento">
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

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
            url: "editar_stock.php",
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
        $('#' + tableId).toggle(); 
    }
</script>

</body>
</html>


<?php
include '../../conexion.php';
require_once('../../libs/fpdf.php');  // Asegúrate de cambiar la ruta según tu estructura de directorios

// Configuración regional para español
setlocale(LC_TIME, 'es_ES.UTF-8');

$mes_filtro = isset($_POST['mes_filtro']) ? $_POST['mes_filtro'] : null;

// Consulta para obtener los pedidos con la información del usuario, promoción, total y fecha del pedido
$sql = "SELECT p.id_pedido, u.nombre AS nombre_usuario, u.apellido AS apellido_usuario, pr.descripcion_promocion AS promocion,
               p.total, p.fecha_pedido, p.estado_pedido, u.correo_electronico
        FROM pedido p
        INNER JOIN usuario u ON p.id_usuario = u.id_usuario
        LEFT JOIN promocion pr ON p.id_promocion = pr.id_promocion";
if ($mes_filtro) {
    $sql .= " WHERE MONTH(p.fecha_pedido) = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $mes_filtro);
} else {
    $stmt = $conexion->prepare($sql);
}

$stmt->execute();
$result = $stmt->get_result();
$stmt->close();

if (isset($_POST['download_pdf']) && $mes_filtro) {
    // Crear instancia de FPDF
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(40, 10, 'Reporte de Pedidos para el Mes: ' . strftime('%B', mktime(0, 0, 0, $mes_filtro, 1)));
    $pdf->Ln(20);

    // Encabezados de la tabla
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(20, 10, 'ID', 1);
    $pdf->Cell(50, 10, 'Cliente', 1);
    $pdf->Cell(50, 10, 'Promoción', 1);
    $pdf->Cell(30, 10, 'Total', 1);
    $pdf->Cell(40, 10, 'Fecha Pedido', 1);
    $pdf->Ln();

    // Datos de los pedidos
    $pdf->SetFont('Arial', '', 10);
    while ($row = $result->fetch_assoc()) {
        $pdf->Cell(20, 10, $row['id_pedido'], 1);
        $pdf->Cell(50, 10, $row['nombre_usuario'] . ' ' . $row['apellido_usuario'], 1);
        $pdf->Cell(50, 10, $row['promocion'], 1);
        $pdf->Cell(30, 10, '$' . number_format($row['total'], 2), 1);
        $pdf->Cell(40, 10, date('d-m-Y H:i', strtotime($row['fecha_pedido'])), 1);
        $pdf->Ln();
    }

    $pdf->Output('D', 'Reporte_Mes_' . $mes_filtro . '.pdf');
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Pedidos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center">
            <h1>Listado de Pedidos</h1>
            <button class="btn btn-secondary" onclick="window.location.href='../../index/index-mantenedores.php'">Volver</button>
        </div>

        <!-- Formulario para filtrar por mes y descargar PDF -->
        <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <div class="mb-3">
                <label for="mes_filtro" class="form-label">Filtrar por mes:</label>
                <select id="mes_filtro" name="mes_filtro" class="form-select" onchange="this.form.submit()">
                    <option value="">Seleccione un mes</option>
                    <?php for ($i = 1; $i <= 12; $i++): ?>
                        <option value="<?= $i; ?>" <?= (int)$mes_filtro === $i ? 'selected' : ''; ?>>
                            <?= strftime('%B', mktime(0, 0, 0, $i, 1)); ?>
                        </option>
                    <?php endfor; ?>
                </select>
            </div>
            <?php if ($mes_filtro): ?>
                <button type="submit" name="download_pdf" class="btn btn-primary">Descargar PDF</button>
            <?php endif; ?>
        </form>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Cliente</th>
                    <th>Promoción</th>
                    <th>Total</th>
                    <th>Fecha y Hora del Pedido</th>
                    <th>Estado del Pedido</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['id_pedido']; ?></td>
                <td><?php echo $row['nombre_usuario'] . ' ' . $row['apellido_usuario']; ?></td>
                <td><?php echo $row['promocion'] ? $row['promocion'] : 'Sin Promoción'; ?></td>
                <td>$<?php echo number_format($row['total'], 2); ?></td>
                <td><?php echo date('d-m-Y H:i', strtotime($row['fecha_pedido'])); ?></td>
                <td><?php echo ucfirst($row['estado_pedido']); ?></td>
                <td>
                    <a href="editar.php?id=<?php echo $row['id_pedido']; ?>" class="btn btn-primary btn-sm">Editar</a>
                    <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#eliminarModal"
                        data-id="<?php echo $row['id_pedido']; ?>">Eliminar</button>
                </td>
            </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr>
            <td colspan="7" class="text-center">No se encontraron pedidos.</td>
        </tr>
    <?php endif; ?>
</tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

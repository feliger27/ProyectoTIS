<?php
include '../../conexion.php';
require_once('../../libs/fpdf.php');  // Asegúrate de cambiar la ruta según tu estructura de directorios

// Configuración regional para español
setlocale(LC_TIME, 'es_ES.UTF-8');

$mes_filtro = isset($_POST['mes_filtro']) ? $_POST['mes_filtro'] : null;

// Consulta para obtener los pedidos con la información del usuario, total, y fecha del pedido ajustada a los nuevos campos
$sql = "SELECT p.id_pedido, u.nombre AS nombre_usuario, u.apellido AS apellido_usuario,
               p.monto_total, p.fecha_pedido, p.estado_pedido, u.correo_electronico
        FROM pedido p
        INNER JOIN usuario u ON p.id_usuario = u.id_usuario";
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

// Consulta para contar los pedidos por mes en el año actual
$sql_count_pedidos = "SELECT MONTH(fecha_pedido) AS mes, COUNT(*) AS cantidad_pedidos
                      FROM pedido
                      WHERE YEAR(fecha_pedido) = YEAR(CURRENT_DATE())
                      GROUP BY mes
                      ORDER BY mes";
$result_count_pedidos = $conexion->query($sql_count_pedidos);

$pedidos_por_mes = array_fill(1, 12, 0); // Asegúrate de tener datos para todos los meses

while ($fila = $result_count_pedidos->fetch_assoc()) {
    $pedidos_por_mes[$fila['mes']] = $fila['cantidad_pedidos'];
}

// Consulta para obtener las ventas por mes
$sql_ventas_mes = "SELECT MONTH(p.fecha_pedido) AS mes, SUM(p.monto_total) AS ventas_totales
                   FROM pedido p
                   WHERE YEAR(p.fecha_pedido) = YEAR(CURRENT_DATE()) 
                   GROUP BY mes
                   ORDER BY mes";
$result_ventas_mes = $conexion->query($sql_ventas_mes);

// Si se solicita el reporte PDF
if (isset($_POST['download_pdf']) && $mes_filtro) {
    // Crear instancia de FPDF
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 12);
    $meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
    $pdf->Cell(40, 10, 'Reporte de Pedidos para el Mes: ' . $meses[$mes_filtro - 1]);
    $pdf->Ln(20);

    // Encabezados de la tabla
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(20, 10, 'ID', 1);
    $pdf->Cell(50, 10, 'Cliente', 1);
    $pdf->Cell(30, 10, 'Total', 1);
    $pdf->Cell(40, 10, 'Fecha Pedido', 1);
    $pdf->Cell(40, 10, 'Estado', 1);
    $pdf->Ln();

    // Datos de los pedidos
    $pdf->SetFont('Arial', '', 10);
    while ($row = $result->fetch_assoc()) {
        $pdf->Cell(20, 10, $row['id_pedido'], 1);
        $pdf->Cell(50, 10, $row['nombre_usuario'] . ' ' . $row['apellido_usuario'], 1);
        $pdf->Cell(30, 10, '$' . number_format($row['monto_total'], 2), 1);
        $pdf->Cell(40, 10, date('d-m-Y H:i', strtotime($row['fecha_pedido'])), 1);
        $pdf->Cell(40, 10, $row['estado_pedido'], 1);
        $pdf->Ln();
    }

    // Mostrar las ventas por mes en el PDF
    $ventas_mes = array_fill(0, 12, 0);  // Inicializar un array con 12 valores de ventas por mes en cero
    while ($row = $result_ventas_mes->fetch_assoc()) {
        $ventas_mes[$row['mes'] - 1] = $row['ventas_totales'];
    }

    // Añadir ventas por mes al reporte PDF
    $pdf->Ln(10);
    $pdf->Cell(0, 10, 'Ventas Totales por Mes:', 0, 1);
    foreach ($ventas_mes as $index => $ventas) {
        $pdf->Cell(0, 10, $meses[$index] . ': $' . number_format($ventas, 2), 0, 1);
    }

    // Generar el archivo PDF
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                <?php 
        // Reemplazar la variable $mes_filtro con los nombres de los meses en español
                for ($i = 1; $i <= 12; $i++): 
            // Asegúrate de que 'strftime' está dando el nombre del mes en español
            $meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
            ?>
                <option value="<?= $i; ?>" <?= (int)$mes_filtro === $i ? 'selected' : ''; ?>>
    <?= $meses[$i - 1]; ?>
</option>
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
                <td>$<?php echo number_format($row['monto_total'], 2); ?></td>
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
            <td colspan="6" class="text-center">No se encontraron pedidos.</td>
        </tr>
    <?php endif; ?>
</tbody>
        </table>
        <h3>Pedidos por Mes</h3>
        <canvas id="pedidosPorMes"></canvas>
        <script>
    var ctx = document.getElementById('pedidosPorMes').getContext('2d');
    var pedidosPorMesChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
            datasets: [{
                label: 'Número de Pedidos',
                data: <?php echo json_encode(array_values($pedidos_por_mes)); ?>,
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    

</body>
</html>


<?php
require '../../libs/fpdf.php';
include '../../conexion.php';

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);

// Título del Reporte
if (isset($_GET['tipo'])) {
    $tipo = $_GET['tipo'];
    $tablas_validas = ['ingrediente', 'postre', 'acompaniamiento', 'bebida', 'aderezo'];

    if (in_array($tipo, $tablas_validas)) {
        $titulo = ucfirst($tipo) . ' - Reporte del Estado del Inventario';
        $pdf->Cell(190, 10, $titulo, 1, 1, 'C');
        $pdf->Ln(10);

        // Encabezados de la tabla
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(40, 10, 'ID Producto', 1);
        $pdf->Cell(70, 10, 'Nombre', 1);
        $pdf->Cell(30, 10, 'Cantidad', 1);
        $pdf->Cell(50, 10, 'Umbral', 1);
        $pdf->Ln();

        // Consulta para obtener los datos del inventario
        $productos_query = "SELECT id_{$tipo} AS id, nombre_{$tipo} AS nombre, cantidad, umbral_reabastecimiento FROM $tipo";
        $result = $conexion->query($productos_query);

        $pdf->SetFont('Arial', '', 12);
        while ($row = $result->fetch_assoc()) {
            // Cambia el color si la cantidad está por debajo del umbral
            if ($row['cantidad'] < $row['umbral_reabastecimiento']) {
                $pdf->SetTextColor(255, 0, 0); // Texto rojo para bajo stock
            } else {
                $pdf->SetTextColor(0, 0, 0); // Texto negro para stock suficiente
            }

            // Imprimir celdas con el color actual
            $pdf->Cell(40, 10, $row['id'], 1);
            $pdf->Cell(70, 10, $row['nombre'], 1);
            $pdf->Cell(30, 10, $row['cantidad'], 1);
            $pdf->Cell(50, 10, $row['umbral_reabastecimiento'], 1);
            $pdf->Ln();

            // Restablecer el color de texto a negro para el siguiente producto
            $pdf->SetTextColor(0, 0, 0); // Asegúrate de que el color se restablezca después de cada fila
        }
    } else {
        $pdf->Cell(190, 10, 'Tipo de producto no válido', 1, 1, 'C');
    }
} else {
    // Reporte general del inventario
    $pdf->Cell(190, 10, 'Reporte del Estado del Inventario', 1, 1, 'C');
    $pdf->Ln(10);

    // Array de categorías y consultas
    $categorias = [
        'Ingredientes' => "SELECT id_ingrediente AS id, nombre_ingrediente AS nombre, cantidad, umbral_reabastecimiento FROM ingrediente",
        'Postres' => "SELECT id_postre AS id, nombre_postre AS nombre, cantidad, umbral_reabastecimiento FROM postre",
        'Acompañamientos' => "SELECT id_acompaniamiento AS id, nombre_acompaniamiento AS nombre, cantidad, umbral_reabastecimiento FROM acompaniamiento",
        'Bebidas' => "SELECT id_bebida AS id, nombre_bebida AS nombre, cantidad, umbral_reabastecimiento FROM bebida",
        'Aderezos' => "SELECT id_aderezo AS id, nombre_aderezo AS nombre, cantidad, umbral_reabastecimiento FROM aderezo"
    ];

    $pdf->SetFont('Arial', '', 12);

    foreach ($categorias as $categoria => $query) {
        // Encabezado de cada categoría
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(190, 10, $categoria, 1, 1, 'C');
        $pdf->SetFont('Arial', '', 12);

        // Encabezados de la tabla
        $pdf->Cell(40, 10, 'ID Producto', 1);
        $pdf->Cell(70, 10, 'Nombre', 1);
        $pdf->Cell(30, 10, 'Cantidad', 1);
        $pdf->Cell(50, 10, 'Umbral', 1);
        $pdf->Ln();

        $result = $conexion->query($query);

        while ($row = $result->fetch_assoc()) {
            // Cambia el color si la cantidad está por debajo del umbral
            if ($row['cantidad'] < $row['umbral_reabastecimiento']) {
                $pdf->SetTextColor(255, 0, 0); // Texto rojo para bajo stock
            } else {
                $pdf->SetTextColor(0, 0, 0); // Texto negro para stock suficiente
            }
        
            // Imprimir celdas con el color actual
            $pdf->Cell(40, 10, $row['id'], 1);
            $pdf->Cell(70, 10, $row['nombre'], 1);
            $pdf->Cell(30, 10, $row['cantidad'], 1);
            $pdf->Cell(50, 10, $row['umbral_reabastecimiento'], 1);
            $pdf->Ln();
        
            // Restablecer el color de texto a negro para el siguiente producto
            $pdf->SetTextColor(0, 0, 0); // Asegúrate de que el color se restablezca después de cada fila
        }
    }
}

$pdf->Output('I', 'reportes.pdf');
?>



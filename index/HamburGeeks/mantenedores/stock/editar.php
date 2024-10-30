<?php
include '../../conexion.php'; // Incluir la conexión a la base de datos

// Verificar si se han recibido los parámetros necesarios
if (isset($_POST['id'], $_POST['cantidad'], $_POST['tabla'])) {
    $id = intval($_POST['id']);  // Asegúrate de que sea un número entero
    $cantidad = intval($_POST['cantidad']); // Asegúrate de que sea un número entero
    $tabla = $_POST['tabla'];

    // Verificar que la cantidad sea un número válido
    if ($cantidad <= 0) {
        echo "Cantidad no válida";
        exit;
    }

    // Dependiendo de la tabla, hacer la actualización correspondiente
    $query = "";
    switch ($tabla) {
        case 'ingredientes':
            $query = "UPDATE ingrediente SET cantidad = ? WHERE id_ingrediente = ?";
            break;
        case 'postres':
            $query = "UPDATE postre SET cantidad = ? WHERE id_postre = ?";
            break;
        case 'acompaniamientos':
            $query = "UPDATE acompaniamiento SET cantidad = ? WHERE id_acompaniamiento = ?";
            break;
        case 'bebidas':
            $query = "UPDATE bebida SET cantidad = ? WHERE id_bebida = ?";
            break;
        case 'aderezos':
            $query = "UPDATE aderezo SET cantidad = ? WHERE id_aderezo = ?";
            break;
        default:
            echo "Tipo de producto no válido";
            exit;
    }

    // Preparar y ejecutar la consulta
    $stmt = $conexion->prepare($query);
    $stmt->bind_param('ii', $cantidad, $id);
    
    if ($stmt->execute()) {
        echo "Stock actualizado correctamente";
    } else {
        echo "Error al actualizar el stock";
    }

    $stmt->close();
} else {
    echo "Parámetros insuficientes";
}
?>




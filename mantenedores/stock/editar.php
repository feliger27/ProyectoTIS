<?php
include '../../conexion.php'; // Incluir la conexión a la base de datos

// Verificar si se han recibido los parámetros necesarios
if (isset($_POST['id'], $_POST['tabla'])) {
    $id = intval($_POST['id']);  // Asegúrate de que sea un número entero
    $tabla = $_POST['tabla'];
    $cantidad = isset($_POST['cantidad']) ? intval($_POST['cantidad']) : null;
    $umbral_reabastecimiento = isset($_POST['umbral_reabastecimiento']) ? intval($_POST['umbral_reabastecimiento']) : null;

    // Validar el nombre de la tabla con un switch para evitar riesgos de inyección SQL
    switch ($tabla) {
        case 'ingrediente':
        case 'postre':
        case 'acompaniamiento':
        case 'bebida':
        case 'aderezo':
            // Tabla válida
            break;
        default:
            echo "Tabla no válida.";
            exit;
    }

    // Construir la consulta de actualización dependiendo de los parámetros recibidos
    $updates = [];
    $types = "";
    $params = [];

    if ($cantidad !== null) {
        $updates[] = "cantidad = ?";
        $types .= "i";
        $params[] = $cantidad;
    }
    if ($umbral_reabastecimiento !== null) {
        $updates[] = "umbral_reabastecimiento = ?";
        $types .= "i";
        $params[] = $umbral_reabastecimiento;
    }

    if (!empty($updates)) {
        $sql = "UPDATE $tabla SET " . implode(", ", $updates) . " WHERE id_{$tabla} = ?";
        $types .= "i";
        $params[] = $id;

        $stmt = $conexion->prepare($sql);
        $stmt->bind_param($types, ...$params);

        if ($stmt->execute()) {
            echo "Actualización realizada correctamente.";
        } else {
            echo "Error al actualizar el registro: " . $conexion->error;
        }
    } else {
        echo "No se realizaron cambios.";
    }
} else {
    echo "Datos incompletos.";
}
?>





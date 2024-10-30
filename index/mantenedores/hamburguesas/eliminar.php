<?php
include '../../conexion.php'; // Incluir la conexión a la base de datos

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_hamburguesa = $_POST['id_hamburguesa'];

    // Validar que el ID sea un número positivo
    if (!filter_var($id_hamburguesa, FILTER_VALIDATE_INT) || $id_hamburguesa <= 0) {
        echo "<div class='alert alert-danger' role='alert'>ID de hamburguesa no válido.</div>";
        exit;
    }

    echo "Eliminando hamburguesa ID: " . $id_hamburguesa; 
    $conexion->begin_transaction();

    try {
        // Primero, eliminamos las relaciones en la tabla Hamburguesa_Aderezo
        $sql_aderezos = "DELETE FROM hamburguesa_aderezo WHERE id_hamburguesa = ?";
        $stmt_aderezos = $conexion->prepare($sql_aderezos);
        $stmt_aderezos->bind_param("i", $id_hamburguesa);
        $stmt_aderezos->execute();

        // Ahora eliminamos las relaciones en la tabla Hamburguesa_Ingrediente
        $sql_ingredientes = "DELETE FROM hamburguesa_ingrediente WHERE id_hamburguesa = ?";
        $stmt_ingredientes = $conexion->prepare($sql_ingredientes);
        $stmt_ingredientes->bind_param("i", $id_hamburguesa);
        $stmt_ingredientes->execute();

        // Finalmente, eliminamos la hamburguesa en la tabla Hamburguesa
        $sql_hamburguesa = "DELETE FROM hamburguesa WHERE id_hamburguesa = ?";
        $stmt_hamburguesa = $conexion->prepare($sql_hamburguesa);
        $stmt_hamburguesa->bind_param("i", $id_hamburguesa);
        $stmt_hamburguesa->execute();

        $conexion->commit();

        // Redirigir a listar.php con un mensaje de éxito
        header("Location: listar.php?eliminado=true&id=" . $id_hamburguesa);
        exit;
    } catch (Exception $e) {
        $conexion->rollback();
        echo "<div class='alert alert-danger' role='alert'>Error al eliminar la hamburguesa: " . $e->getMessage() . "</div>";
    }
} else {
    echo "<div class='alert alert-danger' role='alert'>Método no permitido.</div>";
}
?>




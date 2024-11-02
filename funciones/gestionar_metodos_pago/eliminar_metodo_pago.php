<?php
include '../../conexion.php';
session_start();

// Verificar que el usuario esté autenticado
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/login.php");
    exit();
}

// Verificar que se haya pasado un id de pago
if (!isset($_GET['id_pago'])) {
    $_SESSION['mensaje_error'] = "ID de método de pago no especificado.";
    header("Location: ../../index/index-perfil.php");
    exit();
}

$id_pago = $_GET['id_pago'];
$user_id = $_SESSION['user_id'];

// Iniciar la transacción
$conexion->begin_transaction();

try {
    // Primero, eliminar la relación entre usuario y método de pago
    $stmt = $conexion->prepare("DELETE FROM usuario_metodo_pago WHERE id_pago = ? AND id_usuario = ?");
    $stmt->bind_param("ii", $id_pago, $user_id);
    $stmt->execute();
    $stmt->close();

    // Después, eliminar el método de pago en la tabla `metodo_pago`
    $stmt = $conexion->prepare("DELETE FROM metodo_pago WHERE id_pago = ?");
    $stmt->bind_param("i", $id_pago);
    $stmt->execute();
    $stmt->close();

    // Confirmar la transacción
    $conexion->commit();

    $_SESSION['mensaje_exito'] = "Método de pago eliminado exitosamente.";
    header("Location: ../../index/index-perfil.php");
    exit();
} catch (Exception $e) {
    // En caso de error, deshacer los cambios
    $conexion->rollback();
    $_SESSION['mensaje_error'] = "Error al eliminar el método de pago: " . $e->getMessage();
    header("Location: ../../index/index-perfil.php");
    exit();
}
?>

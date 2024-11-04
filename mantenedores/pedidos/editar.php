<?php
include '../../conexion.php';

if (isset($_GET['id'])) {
    $id_pedido = $_GET['id'];

    // Obtener el pedido actual
    $sql = "SELECT * FROM pedido WHERE id_pedido = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param('i', $id_pedido);
    $stmt->execute();
    $result = $stmt->get_result();
    $pedido = $result->fetch_assoc();

    // Obtener usuarios y promociones para el formulario
    $usuarios = $conexion->query("SELECT id_usuario, nombre FROM usuario");
    $promociones = $conexion->query("SELECT id_promocion, codigo_promocion FROM promocion");

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Recibir datos del formulario
        $id_promocion = $_POST['id_promocion'] ?: null; // Permitir que el valor sea null si no se selecciona promoción
        $total = $_POST['total'];

        // Actualizar el pedido
        $sql_update = "UPDATE pedido SET id_promocion = ?, monto = ? WHERE id_pedido = ?";
        $stmt_update = $conexion->prepare($sql_update);
        $stmt_update->bind_param('sii', $id_promocion, $total, $id_pedido);

        if ($stmt_update->execute()) {
            header("Location: listar.php?actualizado=1");
            exit();
        } else {
            echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                    Error: " . $stmt_update->error . "
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>";
        }
    }
} else {
    header('Location: listar.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Pedido</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center">
            <h1>Editar Pedido</h1>
            <button class="btn btn-secondary" onclick="window.location.href='listar.php'">Volver</button>
        </div>
        <form method="POST" class="mt-4">
            <div class="mb-3">
                <label for="id_promocion" class="form-label">Promoción</label>
                <select class="form-select" id="id_promocion" name="id_promocion">
                    <option value="" <?php if (empty($pedido['id_promocion'])) echo 'selected'; ?>>Sin Promoción</option>
                    <?php while ($promocion = $promociones->fetch_assoc()): ?>
                        <option value="<?php echo $promocion['id_promocion']; ?>" <?php if ($pedido['id_promocion'] == $promocion['id_promocion']) echo 'selected'; ?>>
                            <?php echo $promocion['codigo_promocion']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="total" class="form-label">Total</label>
                <input type="number" class="form-control" id="total" name="total" value="<?php echo $pedido['monto']; ?>" required>
            </div>

            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            <a href="listar.php" class="btn btn-secondary">Cancelar</a>
        </form>

        <?php if (isset($_GET['actualizado']) && $_GET['actualizado'] == 1): ?>
            <div class="alert alert-success mt-3" role="alert">Pedido actualizado exitosamente.</div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

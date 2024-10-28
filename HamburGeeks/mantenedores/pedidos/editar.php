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
        $id_usuario = $_POST['id_usuario'];
        $id_promocion = $_POST['id_promocion'];
        $total = $_POST['total'];
        $estado_pedido = $_POST['estado_pedido'];

        // Actualizar el pedido
        $sql_update = "UPDATE pedido SET id_usuario = ?, id_promocion = ?, total = ?, estado_pedido = ? WHERE id_pedido = ?";
        $stmt_update = $conexion->prepare($sql_update);
        $stmt_update->bind_param('iiisi', $id_usuario, $id_promocion, $total, $estado_pedido, $id_pedido);

        if ($stmt->execute()) {
            echo "<div class='container mt-3'>
                <div class='alert alert-success alert-dismissible fade show' role='alert'>
                    Pedido editado exitosamente.
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>
              </div>";
        } else {
            echo "<div class='container mt-3'>
                <div class='alert alert-danger alert-dismissible fade show' role='alert'>
                    Error: " . $stmt->error . "
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>
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
            <label for="id_usuario" class="form-label">Usuario</label>
            <select class="form-select" id="id_usuario" name="id_usuario" required>
                <?php while ($usuario = $usuarios->fetch_assoc()): ?>
                    <option value="<?php echo $usuario['id_usuario']; ?>" <?php if ($pedido['id_usuario'] == $usuario['id_usuario']) echo 'selected'; ?>>
                        <?php echo $usuario['nombre']; ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="id_promocion" class="form-label">Promoción</label>
            <select class="form-select" id="id_promocion" name="id_promocion" required>
                <?php while ($promocion = $promociones->fetch_assoc()): ?>
                    <option value="<?php echo $promocion['id_promocion']; ?>" <?php if ($pedido['id_promocion'] == $promocion['id_promocion']) echo 'selected'; ?>>
                        <?php echo $promocion['codigo_promocion']; ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="total" class="form-label">Total</label>
            <input type="number" class="form-control" id="total" name="total" value="<?php echo $pedido['total']; ?>" required>
        </div>

        <div class="mb-3">
            <label for="estado_pedido" class="form-label">Estado del Pedido</label>
            <select class="form-select" id="estado_pedido" name="estado_pedido" required>
                <option value="en_preparacion" <?php if ($pedido['estado_pedido'] == 'en_preparacion') echo 'selected'; ?>>En preparación</option>
                <option value="en_reparto" <?php if ($pedido['estado_pedido'] == 'en_reparto') echo 'selected'; ?>>En reparto</option>
                <option value="entregado" <?php if ($pedido['estado_pedido'] == 'entregado') echo 'selected'; ?>>Entregado</option>
            </select>
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


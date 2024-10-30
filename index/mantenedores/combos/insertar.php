<?php
include '../../conexion.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir datos del formulario
    $nombre_combo = $_POST['nombre_combo'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $hamburguesa_id = $_POST['hamburguesa_id'];
    $acompaniamiento_id = $_POST['acompaniamiento_id'];
    $bebida_id = $_POST['bebida_id'];
    $postre_id = $_POST['postre_id'];

    // Inserción del nuevo combo
    $sql = "INSERT INTO combo (nombre_combo, descripcion, precio) VALUES (?, ?, ?)";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ssd", $nombre_combo, $descripcion, $precio);

    if ($stmt->execute()) {
        // Obtener el ID del combo insertado
        $id_combo = $conexion->insert_id;

        // Insertar en las tablas de relación
        if (!empty($hamburguesa_id)) {
            $sql_hamburguesa = "INSERT INTO combo_hamburguesa (id_combo, id_hamburguesa) VALUES (?, ?)";
            $stmt_hamburguesa = $conexion->prepare($sql_hamburguesa);
            $stmt_hamburguesa->bind_param("ii", $id_combo, $hamburguesa_id);
            $stmt_hamburguesa->execute();
        }

        if (!empty($acompaniamiento_id)) {
            $sql_acompaniamiento = "INSERT INTO combo_acompaniamiento (id_combo, id_acompaniamiento) VALUES (?, ?)";
            $stmt_acompaniamiento = $conexion->prepare($sql_acompaniamiento);
            $stmt_acompaniamiento->bind_param("ii", $id_combo, $acompaniamiento_id);
            $stmt_acompaniamiento->execute();
        }

        if (!empty($bebida_id)) {
            $sql_bebida = "INSERT INTO combo_bebida (id_combo, id_bebida) VALUES (?, ?)";
            $stmt_bebida = $conexion->prepare($sql_bebida);
            $stmt_bebida->bind_param("ii", $id_combo, $bebida_id);
            $stmt_bebida->execute();
        }

        if (!empty($postre_id)) {
            $sql_postre = "INSERT INTO combo_postre (id_combo, id_postre) VALUES (?, ?)";
            $stmt_postre = $conexion->prepare($sql_postre);
            $stmt_postre->bind_param("ii", $id_combo, $postre_id);
            $stmt_postre->execute();
        }

        echo "<div class='container mt-3'>
                <div class='alert alert-success alert-dismissible fade show' role='alert'>
                    Combo agregado exitosamente.
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
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Nuevo Combo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center">
        <h1>Agregar Nuevo Combo</h1>
        <button class="btn btn-secondary" onclick="window.location.href='listar.php'">Volver</button>
    </div>
    <form action="insertar.php" method="POST" class="mt-4">
        <div class="mb-3">
            <label for="nombre_combo" class="form-label">Nombre del Combo</label>
            <input type="text" name="nombre_combo" id="nombre_combo" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea name="descripcion" id="descripcion" class="form-control" required></textarea>
        </div>

        <div class="mb-3">
            <label for="precio" class="form-label">Precio</label>
            <input type="number" step="0.01" name="precio" id="precio" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="hamburguesa_id" class="form-label">Seleccionar Hamburguesa</label>
            <select name="hamburguesa_id" id="hamburguesa_id" class="form-select">
                <option value="">Ninguna</option>
                <?php
                // Obtener hamburguesas
                $sql_hamburguesas = "SELECT * FROM hamburguesa";
                $result_hamburguesas = $conexion->query($sql_hamburguesas);
                while ($row_hamburguesa = $result_hamburguesas->fetch_assoc()) {
                    echo "<option value='" . $row_hamburguesa['id_hamburguesa'] . "'>" . $row_hamburguesa['nombre_hamburguesa'] . "</option>";
                }
                ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="acompaniamiento_id" class="form-label">Seleccionar Acompañamiento</label>
            <select name="acompaniamiento_id" id="acompaniamiento_id" class="form-select">
                <option value="">Ninguno</option>
                <?php
                // Obtener acompañamientos
                $sql_acompaniamientos = "SELECT * FROM acompaniamiento";
                $result_acompaniamientos = $conexion->query($sql_acompaniamientos);
                while ($row_acompaniamiento = $result_acompaniamientos->fetch_assoc()) {
                    echo "<option value='" . $row_acompaniamiento['id_acompaniamiento'] . "'>" . $row_acompaniamiento['nombre_acompaniamiento'] . "</option>";
                }
                ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="bebida_id" class="form-label">Seleccionar Bebida</label>
            <select name="bebida_id" id="bebida_id" class="form-select">
                <option value="">Ninguna</option>
                <?php
                // Obtener bebidas
                $sql_bebidas = "SELECT * FROM bebida";
                $result_bebidas = $conexion->query($sql_bebidas);
                while ($row_bebida = $result_bebidas->fetch_assoc()) {
                    echo "<option value='" . $row_bebida['id_bebida'] . "'>" . $row_bebida['nombre_bebida'] . "</option>";
                }
                ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="postre_id" class="form-label">Seleccionar Postre</label>
            <select name="postre_id" id="postre_id" class="form-select">
                <option value="">Ninguno</option>
                <?php
                // Obtener postres
                $sql_postres = "SELECT * FROM postre";
                $result_postres = $conexion->query($sql_postres);
                while ($row_postre = $result_postres->fetch_assoc()) {
                    echo "<option value='" . $row_postre['id_postre'] . "'>" . $row_postre['nombre_postre'] . "</option>";
                }
                ?>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Guardar Combo</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


<?php
include '../../conexion.php'; 

// Verificamos que se ha pasado un ID
if (!isset($_GET['id'])) {
    header("Location: listar.php");
    exit;
}

$id_combo = $_GET['id'];

// Obtener los datos actuales del combo
$sql = "SELECT * FROM combo WHERE id_combo = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id_combo);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    header("Location: listar.php");
    exit;
}

$combo = $result->fetch_assoc();

// Consultas para obtener los IDs de hamburguesas, acompañamientos, bebidas y postres asociados al combo
$sqlHamburguesas = "SELECT id_hamburguesa FROM combo_hamburguesa WHERE id_combo = ?";
$sqlAcompanamientos = "SELECT id_acompanamiento FROM combo_acompanamiento WHERE id_combo = ?";
$sqlBebidas = "SELECT id_bebida FROM combo_bebida WHERE id_combo = ?";
$sqlPostres = "SELECT id_postre FROM combo_postre WHERE id_combo = ?";

function obtener_ids_asociados($conexion, $sql, $id_combo) {
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id_combo);
    $stmt->execute();
    $result = $stmt->get_result();
    $ids = [];
    while ($row = $result->fetch_assoc()) {
        $ids[] = $row[key($row)];
    }
    return $ids;
}

$hamburguesasSeleccionadas = obtener_ids_asociados($conexion, $sqlHamburguesas, $id_combo);
$acompanamientosSeleccionados = obtener_ids_asociados($conexion, $sqlAcompanamientos, $id_combo);
$bebidasSeleccionadas = obtener_ids_asociados($conexion, $sqlBebidas, $id_combo);
$postresSeleccionados = obtener_ids_asociados($conexion, $sqlPostres, $id_combo);

// Procesar el formulario cuando se envía
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre_combo = $_POST['nombre_combo'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $hamburguesas = $_POST['hamburguesas'] ?? [];
    $acompanamientos = $_POST['acompanamientos'] ?? [];
    $bebidas = $_POST['bebidas'] ?? [];
    $postres = $_POST['postres'] ?? [];

    // Actualizar los datos del combo
    $sql = "UPDATE combo SET nombre_combo = ?, descripcion = ?, precio = ? WHERE id_combo = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ssdi", $nombre_combo, $descripcion, $precio, $id_combo);
    
    if ($stmt->execute()) {
        // Actualizar las relaciones de muchos a muchos
        $conexion->query("DELETE FROM combo_hamburguesa WHERE id_combo = $id_combo");
        $conexion->query("DELETE FROM combo_acompanamiento WHERE id_combo = $id_combo");
        $conexion->query("DELETE FROM combo_bebida WHERE id_combo = $id_combo");
        $conexion->query("DELETE FROM combo_postre WHERE id_combo = $id_combo");

        foreach ($hamburguesas as $id_hamburguesa) {
            if ($id_hamburguesa != 0) {
                $conexion->query("INSERT INTO combo_hamburguesa (id_combo, id_hamburguesa) VALUES ($id_combo, $id_hamburguesa)");
            }
        }
        foreach ($acompanamientos as $id_acompanamiento) {
            if ($id_acompanamiento != 0) {
                $conexion->query("INSERT INTO combo_acompanamiento (id_combo, id_acompanamiento) VALUES ($id_combo, $id_acompanamiento)");
            }
        }
        foreach ($bebidas as $id_bebida) {
            if ($id_bebida != 0) {
                $conexion->query("INSERT INTO combo_bebida (id_combo, id_bebida) VALUES ($id_combo, $id_bebida)");
            }
        }
        foreach ($postres as $id_postre) {
            if ($id_postre != 0) {
                $conexion->query("INSERT INTO combo_postre (id_combo, id_postre) VALUES ($id_combo, $id_postre)");
            }
        }

        echo "<div class='alert alert-success'>Combo actualizado exitosamente.</div>";
    } else {
        echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Combo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center">
            <h1>Editar Combo</h1>
            <button class="btn btn-secondary" onclick="window.location.href='listar.php'">Volver</button>
        </div>
    <form action="editar.php?id=<?php echo $id_combo; ?>" method="POST">

        <div class="mb-3">
            <label for="nombre_combo" class="form-label">Nombre del Combo</label>
            <input type="text" name="nombre_combo" class="form-control" value="<?php echo $combo['nombre_combo']; ?>" required>
        </div>

        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea name="descripcion" class="form-control" required><?php echo $combo['descripcion']; ?></textarea>
        </div>

        <div class="mb-3">
            <label for="precio" class="form-label">Precio</label>
            <input type="number" name="precio" class="form-control" value="<?php echo $combo['precio']; ?>" required min="0" step="0.01">
        </div>

        <!-- Selección de hamburguesas -->
        <div class="mb-3">
            <label class="form-label">Hamburguesas</label>
            <select name="hamburguesas[]" class="form-select" multiple>
                <option value="0">Ninguna</option> <!-- Opción Ninguna -->
                <?php
                $result = $conexion->query("SELECT * FROM hamburguesa");
                while ($row = $result->fetch_assoc()) {
                    $selected = in_array($row['id_hamburguesa'], $hamburguesasSeleccionadas) ? 'selected' : '';
                    echo "<option value='{$row['id_hamburguesa']}' $selected>{$row['nombre_hamburguesa']}</option>";
                }
                ?>
            </select>
        </div>

        <!-- Selección de acompañamientos -->
        <div class="mb-3">
            <label class="form-label">Acompañamientos</label>
            <select name="acompanamientos[]" class="form-select" multiple>
                <option value="0">Ninguno</option> <!-- Opción Ninguno -->
                <?php
                $result = $conexion->query("SELECT * FROM acompanamiento");
                while ($row = $result->fetch_assoc()) {
                    $selected = in_array($row['id_acompanamiento'], $acompanamientosSeleccionados) ? 'selected' : '';
                    echo "<option value='{$row['id_acompanamiento']}' $selected>{$row['nombre_acompanamiento']}</option>";
                }
                ?>
            </select>
        </div>

        <!-- Selección de bebidas -->
        <div class="mb-3">
            <label class="form-label">Bebidas</label>
            <select name="bebidas[]" class="form-select" multiple>
                <option value="0">Ninguna</option> <!-- Opción Ninguna -->
                <?php
                $result = $conexion->query("SELECT * FROM bebida");
                while ($row = $result->fetch_assoc()) {
                    $selected = in_array($row['id_bebida'], $bebidasSeleccionadas) ? 'selected' : '';
                    echo "<option value='{$row['id_bebida']}' $selected>{$row['nombre_bebida']}</option>";
                }
                ?>
            </select>
        </div>

        <!-- Selección de postres -->
        <div class="mb-3">
            <label class="form-label">Postres</label>
            <select name="postres[]" class="form-select" multiple>
                <option value="0">Ninguno</option> <!-- Opción Ninguno -->
                <?php
                $result = $conexion->query("SELECT * FROM postre");
                while ($row = $result->fetch_assoc()) {
                    $selected = in_array($row['id_postre'], $postresSeleccionados) ? 'selected' : '';
                    echo "<option value='{$row['id_postre']}' $selected>{$row['nombre_postre']}</option>";
                }
                ?>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


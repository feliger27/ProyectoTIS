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

// Función para obtener productos y cantidades asociadas
function obtener_productos_y_cantidades($conexion, $tabla, $id_combo, $id_campo) {
    $sql = "SELECT $id_campo, cantidad FROM $tabla WHERE id_combo = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id_combo);
    $stmt->execute();
    $result = $stmt->get_result();
    $productos = [];
    while ($row = $result->fetch_assoc()) {
        $productos[] = $row;
    }
    return $productos;
}

$hamburguesasSeleccionadas = obtener_productos_y_cantidades($conexion, "combo_hamburguesa", $id_combo, "id_hamburguesa");
$acompaniamientosSeleccionados = obtener_productos_y_cantidades($conexion, "combo_acompaniamiento", $id_combo, "id_acompaniamiento");
$bebidasSeleccionadas = obtener_productos_y_cantidades($conexion, "combo_bebida", $id_combo, "id_bebida");
$postresSeleccionados = obtener_productos_y_cantidades($conexion, "combo_postre", $id_combo, "id_postre");

// Procesar el formulario cuando se envía
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre_combo = $_POST['nombre_combo'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $hamburguesas = $_POST['hamburguesa_id'] ?? [];
    $cantidadesHamburguesas = $_POST['cantidad_hamburguesa'] ?? [];
    $acompaniamientos = $_POST['acompaniamiento_id'] ?? [];
    $cantidadesAcompaniamientos = $_POST['cantidad_acompaniamiento'] ?? [];
    $bebidas = $_POST['bebida_id'] ?? [];
    $cantidadesBebidas = $_POST['cantidad_bebida'] ?? [];
    $postres = $_POST['postre_id'] ?? [];
    $cantidadesPostres = $_POST['cantidad_postre'] ?? [];

    // Actualizar los datos del combo
    $sql = "UPDATE combo SET nombre_combo = ?, descripcion = ?, precio = ? WHERE id_combo = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ssdi", $nombre_combo, $descripcion, $precio, $id_combo);
    
    if ($stmt->execute()) {
        // Actualizar las relaciones de muchos a muchos
        $conexion->query("DELETE FROM combo_hamburguesa WHERE id_combo = $id_combo");
        $conexion->query("DELETE FROM combo_acompaniamiento WHERE id_combo = $id_combo");
        $conexion->query("DELETE FROM combo_bebida WHERE id_combo = $id_combo");
        $conexion->query("DELETE FROM combo_postre WHERE id_combo = $id_combo");

        // Insertar las nuevas relaciones con cantidades solo si están seleccionadas
        foreach ($hamburguesas as $index => $id_hamburguesa) {
            if (!empty($id_hamburguesa) && !empty($cantidadesHamburguesas[$index])) {
                $cantidad = $cantidadesHamburguesas[$index];
                $conexion->query("INSERT INTO combo_hamburguesa (id_combo, id_hamburguesa, cantidad) VALUES ($id_combo, $id_hamburguesa, $cantidad)");
            }
        }

        foreach ($acompaniamientos as $index => $id_acompaniamiento) {
            if (!empty($id_acompaniamiento) && !empty($cantidadesAcompaniamientos[$index])) {
                $cantidad = $cantidadesAcompaniamientos[$index];
                $conexion->query("INSERT INTO combo_acompaniamiento (id_combo, id_acompaniamiento, cantidad) VALUES ($id_combo, $id_acompaniamiento, $cantidad)");
            }
        }

        foreach ($bebidas as $index => $id_bebida) {
            if (!empty($id_bebida) && !empty($cantidadesBebidas[$index])) {
                $cantidad = $cantidadesBebidas[$index];
                $conexion->query("INSERT INTO combo_bebida (id_combo, id_bebida, cantidad) VALUES ($id_combo, $id_bebida, $cantidad)");
            }
        }

        foreach ($postres as $index => $id_postre) {
            if (!empty($id_postre) && !empty($cantidadesPostres[$index])) {
                $cantidad = $cantidadesPostres[$index];
                $conexion->query("INSERT INTO combo_postre (id_combo, id_postre, cantidad) VALUES ($id_combo, $id_postre, $cantidad)");
            }
        }

        echo "<div class='container mt-3'>
                <div class='alert alert-success alert-dismissible fade show' role='alert'>
                    Combo editado exitosamente.
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

        <?php
        function render_product_section($type, $productosSeleccionados, $conexion) {
            $id_column = "id_" . $type;
            echo "<div class='mb-3'>";
            echo "<label class='form-label'>" . ucfirst($type) . "s (opcional)</label>";
            echo "<div id='{$type}-container'>";

            // Si ya hay productos seleccionados, mostrarlos
            foreach ($productosSeleccionados as $index => $producto) {
                echo "<div class='d-flex align-items-center mb-2'>";
                echo "<select name='{$type}_id[]' class='form-select {$type}-select' onchange='toggleQuantity(this)'>";
                echo "<option value=''>Seleccionar " . ucfirst($type) . "</option>";
                $sql = "SELECT * FROM $type";
                $result = $conexion->query($sql);
                while ($row = $result->fetch_assoc()) {
                    $selected = $row[$id_column] == $producto[$id_column] ? 'selected' : '';
                    echo "<option value='" . $row[$id_column] . "' $selected>" . $row["nombre_" . $type] . "</option>";
                }
                echo "</select>";
                echo "<input type='number' name='cantidad_{$type}[]' class='form-control ms-2' value='" . $producto['cantidad'] . "' min='1' style='display:block;'>";
                echo "<button type='button' class='btn btn-sm btn-danger ms-2' onclick='removeItem(this)'>Eliminar</button>";
                echo "</div>";
            }
            echo "</div>";
            echo "<button type='button' class='btn btn-sm btn-primary mt-2' onclick=\"addItem('$type')\">Agregar " . ucfirst($type) . "</button>";
            echo "</div>";
        }

        render_product_section("hamburguesa", $hamburguesasSeleccionadas, $conexion);
        render_product_section("acompaniamiento", $acompaniamientosSeleccionados, $conexion);
        render_product_section("bebida", $bebidasSeleccionadas, $conexion);
        render_product_section("postre", $postresSeleccionados, $conexion);
        ?>

        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Almacenamos todas las opciones en variables de JavaScript para cada tipo
const allOptions = {
    hamburguesa: `<?php
        $options = "<option value=''>Seleccionar Hamburguesa</option>";
        $sql = "SELECT * FROM hamburguesa";
        $result = $conexion->query($sql);
        while ($row = $result->fetch_assoc()) {
            $options .= "<option value='" . $row['id_hamburguesa'] . "'>" . $row['nombre_hamburguesa'] . "</option>";
        }
        echo $options;
    ?>`,
    acompanamiento: `<?php
        $options = "<option value=''>Seleccionar Acompañamiento</option>";
        $sql = "SELECT * FROM acompaniamiento";
        $result = $conexion->query($sql);
        while ($row = $result->fetch_assoc()) {
            $options .= "<option value='" . $row['id_acompaniamiento'] . "'>" . $row['nombre_acompaniamiento'] . "</option>";
        }
        echo $options;
    ?>`,
    bebida: `<?php
        $options = "<option value=''>Seleccionar Bebida</option>";
        $sql = "SELECT * FROM bebida";
        $result = $conexion->query($sql);
        while ($row = $result->fetch_assoc()) {
            $options .= "<option value='" . $row['id_bebida'] . "'>" . $row['nombre_bebida'] . "</option>";
        }
        echo $options;
    ?>`,
    postre: `<?php
        $options = "<option value=''>Seleccionar Postre</option>";
        $sql = "SELECT * FROM postre";
        $result = $conexion->query($sql);
        while ($row = $result->fetch_assoc()) {
            $options .= "<option value='" . $row['id_postre'] . "'>" . $row['nombre_postre'] . "</option>";
        }
        echo $options;
    ?>`
};

function addItem(type) {
    const container = document.getElementById(`${type}-container`);
    const newRow = document.createElement("div");
    newRow.className = "d-flex align-items-center mb-2 " + type + "-row";

    // Usar las opciones almacenadas en JavaScript
    newRow.innerHTML = `
        <select name="${type}_id[]" class="form-select ${type}-select" onchange="toggleQuantity(this)">
            ${allOptions[type]}
        </select>
        <input type="number" name="cantidad_${type}[]" class="form-control ms-2" placeholder="Cantidad" min="1" value="1" style="display: none;">
        <button type="button" class="btn btn-sm btn-danger ms-2" onclick="removeItem(this)">Eliminar</button>
    `;
    container.appendChild(newRow);
}

function toggleQuantity(select) {
    const quantityInput = select.nextElementSibling;
    quantityInput.style.display = select.value ? "block" : "none";
}

function removeItem(button) {
    button.parentElement.remove();
}

function capitalize(word) {
    return word.charAt(0).toUpperCase() + word.slice(1);
}
</script>
</body>
</html>

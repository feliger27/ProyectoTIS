<?php
include '../../conexion.php';

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

    // Inserción del nuevo combo
    $sql = "INSERT INTO combo (nombre_combo, descripcion, precio) VALUES (?, ?, ?)";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ssd", $nombre_combo, $descripcion, $precio);

    if ($stmt->execute()) {
        // Obtener el ID del combo insertado
        $id_combo = $conexion->insert_id;

        // Insertar hamburguesas solo si se han seleccionado
        foreach ($hamburguesas as $index => $id_hamburguesa) {
            if (!empty($id_hamburguesa) && !empty($cantidadesHamburguesas[$index])) {
                $cantidad = $cantidadesHamburguesas[$index];
                $conexion->query("INSERT INTO combo_hamburguesa (id_combo, id_hamburguesa, cantidad) VALUES ($id_combo, $id_hamburguesa, $cantidad)");
            }
        }

        // Insertar acompañamientos solo si se han seleccionado
        foreach ($acompaniamientos as $index => $id_acompaniamiento) {
            if (!empty($id_acompaniamiento) && !empty($cantidadesAcompaniamientos[$index])) {
                $cantidad = $cantidadesAcompaniamientos[$index];
                $conexion->query("INSERT INTO combo_acompaniamiento (id_combo, id_acompaniamiento, cantidad) VALUES ($id_combo, $id_acompaniamiento, $cantidad)");
            }
        }

        // Insertar bebidas solo si se han seleccionado
        foreach ($bebidas as $index => $id_bebida) {
            if (!empty($id_bebida) && !empty($cantidadesBebidas[$index])) {
                $cantidad = $cantidadesBebidas[$index];
                $conexion->query("INSERT INTO combo_bebida (id_combo, id_bebida, cantidad) VALUES ($id_combo, $id_bebida, $cantidad)");
            }
        }

        // Insertar postres solo si se han seleccionado
        foreach ($postres as $index => $id_postre) {
            if (!empty($id_postre) && !empty($cantidadesPostres[$index])) {
                $cantidad = $cantidadesPostres[$index];
                $conexion->query("INSERT INTO combo_postre (id_combo, id_postre, cantidad) VALUES ($id_combo, $id_postre, $cantidad)");
            }
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
    <form action="insertar.php" method="POST" class="mt-4" id="comboForm">
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

        <!-- Hamburguesas -->
        <div class="mb-3">
            <label class="form-label">Hamburguesas (opcional)</label>
            <div id="hamburguesa-container">
                <div class="d-flex align-items-center mb-2 hamburguesa-row">
                    <select name="hamburguesa_id[]" class="form-select hamburguesa-select" onchange="toggleQuantity(this)">
                        <option value="">Seleccionar Hamburguesa</option>
                        <?php
                        $sql_hamburguesas = "SELECT * FROM hamburguesa";
                        $result_hamburguesas = $conexion->query($sql_hamburguesas);
                        while ($row_hamburguesa = $result_hamburguesas->fetch_assoc()) {
                            echo "<option value='" . $row_hamburguesa['id_hamburguesa'] . "'>" . $row_hamburguesa['nombre_hamburguesa'] . "</option>";
                        }
                        ?>
                    </select>
                    <input type="number" name="cantidad_hamburguesa[]" class="form-control ms-2" placeholder="Cantidad" min="1" value="1" style="display: none;">
                </div>
            </div>
            <button type="button" class="btn btn-sm btn-primary mt-2" onclick="addItem('hamburguesa')">Agregar Hamburguesa</button>
        </div>

        <!-- Acompañamientos -->
        <div class="mb-3">
            <label class="form-label">Acompañamientos (opcional)</label>
            <div id="acompaniamiento-container">
                <div class="d-flex align-items-center mb-2 acompanamiento-row">
                    <select name="acompaniamiento_id[]" class="form-select acompanamiento-select" onchange="toggleQuantity(this)">
                        <option value="">Seleccionar Acompañamiento</option>
                        <?php
                        $sql_acompaniamientos = "SELECT * FROM acompaniamiento";
                        $result_acompaniamientos = $conexion->query($sql_acompaniamientos);
                        while ($row_acompaniamiento = $result_acompaniamientos->fetch_assoc()) {
                            echo "<option value='" . $row_acompaniamiento['id_acompaniamiento'] . "'>" . $row_acompaniamiento['nombre_acompaniamiento'] . "</option>";
                        }
                        ?>
                    </select>
                    <input type="number" name="cantidad_acompaniamiento[]" class="form-control ms-2" placeholder="Cantidad" min="1" value="1" style="display: none;">
                </div>
            </div>
            <button type="button" class="btn btn-sm btn-primary mt-2" onclick="addItem('acompaniamiento')">Agregar Acompañamiento</button>
        </div>

        <!-- Bebidas -->
        <div class="mb-3">
            <label class="form-label">Bebidas (opcional)</label>
            <div id="bebida-container">
                <div class="d-flex align-items-center mb-2 bebida-row">
                    <select name="bebida_id[]" class="form-select bebida-select" onchange="toggleQuantity(this)">
                        <option value="">Seleccionar Bebida</option>
                        <?php
                        $sql_bebidas = "SELECT * FROM bebida";
                        $result_bebidas = $conexion->query($sql_bebidas);
                        while ($row_bebida = $result_bebidas->fetch_assoc()) {
                            echo "<option value='" . $row_bebida['id_bebida'] . "'>" . $row_bebida['nombre_bebida'] . "</option>";
                        }
                        ?>
                    </select>
                    <input type="number" name="cantidad_bebida[]" class="form-control ms-2" placeholder="Cantidad" min="1" value="1" style="display: none;">
                </div>
            </div>
            <button type="button" class="btn btn-sm btn-primary mt-2" onclick="addItem('bebida')">Agregar Bebida</button>
        </div>

        <!-- Postres -->
        <div class="mb-3">
            <label class="form-label">Postres (opcional)</label>
            <div id="postre-container">
                <div class="d-flex align-items-center mb-2 postre-row">
                    <select name="postre_id[]" class="form-select postre-select" onchange="toggleQuantity(this)">
                        <option value="">Seleccionar Postre</option>
                        <?php
                        $sql_postres = "SELECT * FROM postre";
                        $result_postres = $conexion->query($sql_postres);
                        while ($row_postre = $result_postres->fetch_assoc()) {
                            echo "<option value='" . $row_postre['id_postre'] . "'>" . $row_postre['nombre_postre'] . "</option>";
                        }
                        ?>
                    </select>
                    <input type="number" name="cantidad_postre[]" class="form-control ms-2" placeholder="Cantidad" min="1" value="1" style="display: none;">
                </div>
            </div>
            <button type="button" class="btn btn-sm btn-primary mt-2" onclick="addItem('postre')">Agregar Postre</button>
        </div>

        <button type="submit" class="btn btn-primary">Guardar Combo</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function addItem(type) {
    const container = document.getElementById(`${type}-container`);
    const newRow = document.createElement("div");
    newRow.className = "d-flex align-items-center mb-2 " + type + "-row";
    
    // Obtener productos ya seleccionados
    const selectedValues = Array.from(container.querySelectorAll(`.${type}-select`))
        .map(select => select.value)
        .filter(value => value !== "");

    // Generar opciones sin duplicados
    const options = Array.from(document.querySelector(`.${type}-select`).options)
        .filter(option => !selectedValues.includes(option.value) || option.value === "")
        .map(option => `<option value="${option.value}">${option.text}</option>`)
        .join("");

    newRow.innerHTML = `
        <select name="${type}_id[]" class="form-select ${type}-select" onchange="toggleQuantity(this)">
            ${options}
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

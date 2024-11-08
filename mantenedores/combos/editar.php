<?php
include '../../conexion.php';

// Verificar que se ha pasado un ID de combo
if (!isset($_GET['id'])) {
    header("Location: listar.php");
    exit;
}

$id_combo = $_GET['id'];

// Obtener los datos del combo
$sql_combo = "SELECT * FROM combo WHERE id_combo = ?";
$stmt = $conexion->prepare($sql_combo);
$stmt->bind_param("i", $id_combo);
$stmt->execute();
$result_combo = $stmt->get_result();

if ($result_combo->num_rows == 0) {
    header("Location: listar.php");
    exit;
}

$combo = $result_combo->fetch_assoc();

// Obtener los elementos actuales del combo por categoría
function obtener_elementos($conexion, $tabla, $id_combo, $id_campo) {
    $sql = "SELECT $id_campo AS id, cantidad FROM $tabla WHERE id_combo = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id_combo);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

$hamburguesasSeleccionadas = obtener_elementos($conexion, 'combo_hamburguesa', $id_combo, 'id_hamburguesa');
$acompaniamientosSeleccionados = obtener_elementos($conexion, 'combo_acompaniamiento', $id_combo, 'id_acompaniamiento');
$bebidasSeleccionadas = obtener_elementos($conexion, 'combo_bebida', $id_combo, 'id_bebida');
$postresSeleccionados = obtener_elementos($conexion, 'combo_postre', $id_combo, 'id_postre');

// Obtener todos los elementos posibles para las listas desplegables
$hamburguesas = $conexion->query("SELECT id_hamburguesa, nombre_hamburguesa FROM hamburguesa")->fetch_all(MYSQLI_ASSOC);
$acompaniamientos = $conexion->query("SELECT id_acompaniamiento, nombre_acompaniamiento FROM acompaniamiento")->fetch_all(MYSQLI_ASSOC);
$bebidas = $conexion->query("SELECT id_bebida, nombre_bebida FROM bebida")->fetch_all(MYSQLI_ASSOC);
$postres = $conexion->query("SELECT id_postre, nombre_postre FROM postre")->fetch_all(MYSQLI_ASSOC);

// Guardar cambios en el combo
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

    // Actualizar el combo
    $sql = "UPDATE combo SET nombre_combo = ?, descripcion = ?, precio = ? WHERE id_combo = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ssdi", $nombre_combo, $descripcion, $precio, $id_combo);
    $stmt->execute();

    // Eliminar las relaciones existentes del combo en cada categoría antes de reinsertar
    $conexion->query("DELETE FROM combo_hamburguesa WHERE id_combo = $id_combo");
    $conexion->query("DELETE FROM combo_acompaniamiento WHERE id_combo = $id_combo");
    $conexion->query("DELETE FROM combo_bebida WHERE id_combo = $id_combo");
    $conexion->query("DELETE FROM combo_postre WHERE id_combo = $id_combo");

    // Función para insertar elementos en una categoría
    function insertar_elementos($conexion, $id_combo, $items, $quantities, $table, $idField) {
        $sql = "INSERT INTO $table (id_combo, $idField, cantidad) VALUES (?, ?, ?)";
        $stmt = $conexion->prepare($sql);
        foreach ($items as $index => $item_id) {
            if (!empty($item_id) && isset($quantities[$index]) && $quantities[$index] > 0) {
                $quantity = $quantities[$index];
                $stmt->bind_param("iii", $id_combo, $item_id, $quantity);
                $stmt->execute();
            }
        }
    }

    // Insertar hamburguesas
    insertar_elementos($conexion, $id_combo, $hamburguesas, $cantidadesHamburguesas, 'combo_hamburguesa', 'id_hamburguesa');

    // Insertar acompañamientos
    insertar_elementos($conexion, $id_combo, $acompaniamientos, $cantidadesAcompaniamientos, 'combo_acompaniamiento', 'id_acompaniamiento');

    // Insertar bebidas
    insertar_elementos($conexion, $id_combo, $bebidas, $cantidadesBebidas, 'combo_bebida', 'id_bebida');

    // Insertar postres
    insertar_elementos($conexion, $id_combo, $postres, $cantidadesPostres, 'combo_postre', 'id_postre');

    // Redirigir de vuelta al listado después de guardar
    header("Location: listar.php");
    exit;
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Combo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
        function agregarElemento(categoria) {
            const contenedor = document.getElementById(`${categoria}-container`);
            const selects = contenedor.querySelectorAll('select');
            const cantidades = contenedor.querySelectorAll('input[type="number"]');

            const todasLlenas = Array.from(selects).every(select => select.value) &&
                                Array.from(cantidades).every(input => input.value > 0);

            if (!todasLlenas) {
                alert("Por favor, complete todas las selecciones y cantidades antes de agregar otro elemento.");
                return;
            }

            const nuevoElemento = document.createElement('div');
            nuevoElemento.classList.add('input-group', 'mb-2');

            const selectHTML = document.createElement('select');
            selectHTML.name = `${categoria}_id[]`;
            selectHTML.classList.add('form-select');
            selectHTML.innerHTML = `<option value="" selected disabled>Seleccione un ${categoria.slice(0, -1)}</option>`;

            const options = contenedor.querySelector('select').options;
            for (let i = 1; i < options.length; i++) {
                const newOption = document.createElement('option');
                newOption.value = options[i].value;
                newOption.textContent = options[i].textContent;
                selectHTML.appendChild(newOption);
            }

            const cantidadHTML = document.createElement('input');
            cantidadHTML.type = 'number';
            cantidadHTML.name = `cantidad_${categoria}[]`;
            cantidadHTML.classList.add('form-control');
            cantidadHTML.placeholder = "Cantidad";

            const eliminarBtn = document.createElement('button');
            eliminarBtn.type = "button";
            eliminarBtn.className = "btn btn-danger ms-2";
            eliminarBtn.textContent = "Eliminar";
            eliminarBtn.onclick = () => nuevoElemento.remove();

            nuevoElemento.appendChild(selectHTML);
            nuevoElemento.appendChild(cantidadHTML);
            nuevoElemento.appendChild(eliminarBtn);

            selectHTML.addEventListener('change', function() {
                actualizarOpciones(categoria);
            });

            contenedor.appendChild(nuevoElemento);
            actualizarOpciones(categoria);
        }

        function actualizarOpciones(categoria) {
            const contenedor = document.getElementById(`${categoria}-container`);
            const selects = contenedor.querySelectorAll('select');
            const seleccionados = Array.from(selects).map(select => select.value).filter(Boolean);

            selects.forEach(select => {
                Array.from(select.options).forEach(option => {
                    option.disabled = seleccionados.includes(option.value) && option.value !== select.value;
                });
            });
        }

        function validarFormulario() {
            const categorias = ['hamburguesas', 'acompaniamientos', 'bebidas', 'postres'];
            for (const categoria of categorias) {
                const contenedor = document.getElementById(`${categoria}-container`);
                const selects = contenedor.querySelectorAll('select');
                const cantidades = contenedor.querySelectorAll('input[type="number"]');

                for (let i = 0; i < selects.length; i++) {
                    if (selects[i].value && (!cantidades[i].value || cantidades[i].value <= 0)) {
                        alert(`Por favor, especifique una cantidad para cada ${categoria.slice(0, -1)} seleccionado.`);
                        return false;
                    }
                }
            }
            return true;
        }
    </script>
</head>
<body class="container py-5">
    <h1 class="mb-4">Editar Combo</h1>
    <form action="editar.php?id=<?= $id_combo ?>" method="POST" onsubmit="return validarFormulario()">
        <div class="mb-3">
            <label for="nombre_combo" class="form-label">Nombre del Combo:</label>
            <input type="text" name="nombre_combo" class="form-control" value="<?= $combo['nombre_combo'] ?>" required>
        </div>

        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción:</label>
            <textarea name="descripcion" class="form-control" rows="3" required><?= $combo['descripcion'] ?></textarea>
        </div>

        <div class="mb-3">
            <label for="precio" class="form-label">Precio:</label>
            <input type="number" name="precio" class="form-control" step="0.01" value="<?= $combo['precio'] ?>" required>
        </div>

        <!-- Hamburguesas -->
        <h2 class="my-4">Hamburguesas</h2>
        <div id="hamburguesas-container" class="mb-3">
            <?php if (empty($hamburguesasSeleccionadas)): ?>
                <div class="input-group mb-2">
                    <select name="hamburguesa_id[]" class="form-select" onchange="actualizarOpciones('hamburguesas')">
                        <option value="" selected disabled>Seleccione una Hamburguesa</option>
                        <?php foreach ($hamburguesas as $opcion): ?>
                            <option value="<?= $opcion['id_hamburguesa'] ?>"><?= $opcion['nombre_hamburguesa'] ?></option>
                        <?php endforeach; ?>
                    </select>
                    <input type="number" name="cantidad_hamburguesa[]" class="form-control" placeholder="Cantidad">
                </div>
            <?php else: ?>
                <?php foreach ($hamburguesasSeleccionadas as $index => $hamburguesa): ?>
                    <div class="input-group mb-2">
                        <select name="hamburguesa_id[]" class="form-select" onchange="actualizarOpciones('hamburguesas')">
                            <option value="" selected disabled>Seleccione una Hamburguesa</option>
                            <?php foreach ($hamburguesas as $opcion): ?>
                                <option value="<?= $opcion['id_hamburguesa'] ?>" <?= $opcion['id_hamburguesa'] == $hamburguesa['id'] ? 'selected' : '' ?>>
                                    <?= $opcion['nombre_hamburguesa'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <input type="number" name="cantidad_hamburguesa[]" class="form-control" placeholder="Cantidad" value="<?= $hamburguesa['cantidad'] ?>">
                        <?php if ($index > 0): ?>
                            <button type="button" class="btn btn-danger ms-2" onclick="this.parentElement.remove()">Eliminar</button>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <button type="button" class="btn btn-secondary" onclick="agregarElemento('hamburguesas')">Agregar Hamburguesa</button>

        <!-- Acompañamientos -->
        <h2 class="my-4">Acompañamientos</h2>
        <div id="acompaniamientos-container" class="mb-3">
            <?php if (empty($acompaniamientosSeleccionados)): ?>
                <div class="input-group mb-2">
                    <select name="acompaniamiento_id[]" class="form-select" onchange="actualizarOpciones('acompaniamientos')">
                        <option value="" selected disabled>Seleccione un Acompañamiento</option>
                        <?php foreach ($acompaniamientos as $opcion): ?>
                            <option value="<?= $opcion['id_acompaniamiento'] ?>"><?= $opcion['nombre_acompaniamiento'] ?></option>
                        <?php endforeach; ?>
                    </select>
                    <input type="number" name="cantidad_acompaniamiento[]" class="form-control" placeholder="Cantidad">
                </div>
            <?php else: ?>
                <?php foreach ($acompaniamientosSeleccionados as $index => $acompaniamiento): ?>
                    <div class="input-group mb-2">
                        <select name="acompaniamiento_id[]" class="form-select" onchange="actualizarOpciones('acompaniamientos')">
                            <option value="" selected disabled>Seleccione un Acompañamiento</option>
                            <?php foreach ($acompaniamientos as $opcion): ?>
                                <option value="<?= $opcion['id_acompaniamiento'] ?>" <?= $opcion['id_acompaniamiento'] == $acompaniamiento['id'] ? 'selected' : '' ?>>
                                    <?= $opcion['nombre_acompaniamiento'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <input type="number" name="cantidad_acompaniamiento[]" class="form-control" placeholder="Cantidad" value="<?= $acompaniamiento['cantidad'] ?>">
                        <?php if ($index > 0): ?>
                            <button type="button" class="btn btn-danger ms-2" onclick="this.parentElement.remove()">Eliminar</button>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <button type="button" class="btn btn-secondary" onclick="agregarElemento('acompaniamientos')">Agregar Acompañamiento</button>

        <!-- Bebidas -->
        <h2 class="my-4">Bebidas</h2>
        <div id="bebidas-container" class="mb-3">
            <?php if (empty($bebidasSeleccionadas)): ?>
                <div class="input-group mb-2">
                    <select name="bebida_id[]" class="form-select" onchange="actualizarOpciones('bebidas')">
                        <option value="" selected disabled>Seleccione una Bebida</option>
                        <?php foreach ($bebidas as $opcion): ?>
                            <option value="<?= $opcion['id_bebida'] ?>"><?= $opcion['nombre_bebida'] ?></option>
                        <?php endforeach; ?>
                    </select>
                    <input type="number" name="cantidad_bebida[]" class="form-control" placeholder="Cantidad">
                </div>
            <?php else: ?>
                <?php foreach ($bebidasSeleccionadas as $index => $bebida): ?>
                    <div class="input-group mb-2">
                        <select name="bebida_id[]" class="form-select" onchange="actualizarOpciones('bebidas')">
                            <option value="" selected disabled>Seleccione una Bebida</option>
                            <?php foreach ($bebidas as $opcion): ?>
                                <option value="<?= $opcion['id_bebida'] ?>" <?= $opcion['id_bebida'] == $bebida['id'] ? 'selected' : '' ?>>
                                    <?= $opcion['nombre_bebida'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <input type="number" name="cantidad_bebida[]" class="form-control" placeholder="Cantidad" value="<?= $bebida['cantidad'] ?>">
                        <?php if ($index > 0): ?>
                            <button type="button" class="btn btn-danger ms-2" onclick="this.parentElement.remove()">Eliminar</button>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <button type="button" class="btn btn-secondary" onclick="agregarElemento('bebidas')">Agregar Bebida</button>

        <!-- Postres -->
        <h2 class="my-4">Postres</h2>
        <div id="postres-container" class="mb-3">
            <?php if (empty($postresSeleccionados)): ?>
                <div class="input-group mb-2">
                    <select name="postre_id[]" class="form-select" onchange="actualizarOpciones('postres')">
                        <option value="" selected disabled>Seleccione un Postre</option>
                        <?php foreach ($postres as $opcion): ?>
                            <option value="<?= $opcion['id_postre'] ?>"><?= $opcion['nombre_postre'] ?></option>
                        <?php endforeach; ?>
                    </select>
                    <input type="number" name="cantidad_postre[]" class="form-control" placeholder="Cantidad">
                </div>
            <?php else: ?>
                <?php foreach ($postresSeleccionados as $index => $postre): ?>
                    <div class="input-group mb-2">
                        <select name="postre_id[]" class="form-select" onchange="actualizarOpciones('postres')">
                            <option value="" selected disabled>Seleccione un Postre</option>
                            <?php foreach ($postres as $opcion): ?>
                                <option value="<?= $opcion['id_postre'] ?>" <?= $opcion['id_postre'] == $postre['id'] ? 'selected' : '' ?>>
                                    <?= $opcion['nombre_postre'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <input type="number" name="cantidad_postre[]" class="form-control" placeholder="Cantidad" value="<?= $postre['cantidad'] ?>">
                        <?php if ($index > 0): ?>
                            <button type="button" class="btn btn-danger ms-2" onclick="this.parentElement.remove()">Eliminar</button>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <button type="button" class="btn btn-secondary" onclick="agregarElemento('postres')">Agregar Postre</button>

        <button type="submit" class="btn btn-primary mt-4">Guardar Cambios</button>
    </form>
</body>
</html>
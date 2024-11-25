<?php
include '../../conexion.php';

// Consultar nombres y IDs de cada categoría
$hamburguesas = $conexion->query("SELECT id_hamburguesa, nombre_hamburguesa FROM hamburguesa")->fetch_all(MYSQLI_ASSOC);
$acompaniamientos = $conexion->query("SELECT id_acompaniamiento, nombre_acompaniamiento FROM acompaniamiento")->fetch_all(MYSQLI_ASSOC);
$bebidas = $conexion->query("SELECT id_bebida, nombre_bebida FROM bebida")->fetch_all(MYSQLI_ASSOC);
$postres = $conexion->query("SELECT id_postre, nombre_postre FROM postre")->fetch_all(MYSQLI_ASSOC);

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

    // Procesamiento de la imagen
    $nombre_imagen = '';
    if ($_FILES['imagen']['error'] == UPLOAD_ERR_OK) {
        $nombre_imagen = $_FILES['imagen']['name'];
        $ruta_temporal = $_FILES['imagen']['tmp_name'];
        $ruta_destino = "../../uploads/combos/" . $nombre_imagen;

        if (!move_uploaded_file($ruta_temporal, $ruta_destino)) {
            echo "<div class='alert alert-danger' role='alert'>Error al subir la imagen.</div>";
            exit;
        }
    }

    // Inserción del nuevo combo
    $sql = "INSERT INTO combo (nombre_combo, descripcion, precio, imagen) VALUES (?, ?, ?, ?)";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ssds", $nombre_combo, $descripcion, $precio, $nombre_imagen);

    if ($stmt->execute()) {
        $id_combo = $stmt->insert_id;

        // Inserción de cada elemento en su tabla correspondiente
        $sqlItems = [
            "combo_hamburguesa" => ["id_hamburguesa", $hamburguesas, $cantidadesHamburguesas],
            "combo_acompaniamiento" => ["id_acompaniamiento", $acompaniamientos, $cantidadesAcompaniamientos],
            "combo_bebida" => ["id_bebida", $bebidas, $cantidadesBebidas],
            "combo_postre" => ["id_postre", $postres, $cantidadesPostres]
        ];

        foreach ($sqlItems as $table => [$idField, $items, $quantities]) {
            $sql = "INSERT INTO $table (id_combo, $idField, cantidad) VALUES (?, ?, ?)";
            $stmt = $conexion->prepare($sql);
            foreach ($items as $index => $item_id) {
                $quantity = $quantities[$index];
                $stmt->bind_param("iii", $id_combo, $item_id, $quantity);
                $stmt->execute();
            }
        }

        header("Location: listar.php");
    } else {
        echo "Error al crear el combo.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Agregar Nuevo Combo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
        function agregarElemento(categoria) {
            const contenedor = document.getElementById(`${categoria}-container`);
            const selects = contenedor.querySelectorAll('select');
            const cantidades = contenedor.querySelectorAll('input[type="number"]');

            // Verificar que todas las casillas actuales estén llenas
            const todasLlenas = Array.from(selects).every(select => select.value) &&
                Array.from(cantidades).every(input => input.value > 0);

            if (todasLlenas) {
                const selectHTML = contenedor.querySelector('select').outerHTML;
                const cantidadHTML = contenedor.querySelector('input[type="number"]').outerHTML;
                const nuevoElemento = document.createElement('div');
                nuevoElemento.classList.add('input-group', 'mb-2');
                nuevoElemento.innerHTML = selectHTML + cantidadHTML;

                // Botón de eliminar
                const eliminarBtn = document.createElement('button');
                eliminarBtn.type = "button";
                eliminarBtn.className = "btn btn-danger ms-2";
                eliminarBtn.textContent = "Eliminar";
                eliminarBtn.onclick = () => nuevoElemento.remove();

                nuevoElemento.appendChild(eliminarBtn);
                contenedor.appendChild(nuevoElemento);

                // Establecer cantidad a 1 por defecto y evitar menos de 1
                const nuevoSelect = nuevoElemento.querySelector('select');
                const nuevaCantidad = nuevoElemento.querySelector('input[type="number"]');
                nuevaCantidad.value = 1;
                nuevaCantidad.min = 1;
                nuevoSelect.addEventListener('change', () => {
                    nuevaCantidad.value = 1;
                    actualizarOpciones(categoria);
                });
                actualizarOpciones(categoria);
            } else {
                alert("Seleccione un elemento y especifique su cantidad en todas las filas antes de agregar otro.");
            }
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
                    if (selects[i].value && (!cantidades[i].value || cantidades[i].value < 1)) {
                        alert(`Por favor, especifique una cantidad válida para cada ${categoria.slice(0, -1)} seleccionado.`);
                        return false;
                    }
                }
            }
            return true;
        }
    </script>
</head>

<body class="container py-5">
    <div class="d-flex justify-content-between align-items-center">
        <h1>Agregar Nuevo Combo</h1>
        <button class="btn btn-secondary" onclick="window.location.href='listar.php'">Volver</button>
    </div>
    <form action="insertar.php" method="POST" onsubmit="return validarFormulario()" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="nombre_combo" class="form-label">Nombre del Combo:</label>
            <input type="text" name="nombre_combo" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción:</label>
            <textarea name="descripcion" class="form-control" rows="3" required></textarea>
        </div>

        <div class="mb-3">
            <label for="precio" class="form-label">Precio:</label>
            <input type="number" name="precio" class="form-control" step="0.01" required>
        </div>

        <!-- Hamburguesas -->
        <h2 class="my-4">Hamburguesas</h2>
        <div id="hamburguesas-container" class="mb-3">
            <div class="input-group mb-2">
                <select name="hamburguesa_id[]" class="form-select" onchange="actualizarOpciones('hamburguesas')">
                    <option value="" selected disabled>Seleccione una Hamburguesa</option>
                    <?php foreach ($hamburguesas as $hamburguesa): ?>
                        <option value="<?= $hamburguesa['id_hamburguesa'] ?>"><?= $hamburguesa['nombre_hamburguesa'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <input type="number" name="cantidad_hamburguesa[]" class="form-control" placeholder="Cantidad" value="1"
                    min="1">
            </div>
        </div>
        <button type="button" class="btn btn-secondary" onclick="agregarElemento('hamburguesas')">Agregar
            Hamburguesa</button>

        <!-- Acompañamientos -->
        <h2 class="my-4">Acompañamientos</h2>
        <div id="acompaniamientos-container" class="mb-3">
            <div class="input-group mb-2">
                <select name="acompaniamiento_id[]" class="form-select"
                    onchange="actualizarOpciones('acompaniamientos')">
                    <option value="" selected disabled>Seleccione un Acompañamiento</option>
                    <?php foreach ($acompaniamientos as $acompaniamiento): ?>
                        <option value="<?= $acompaniamiento['id_acompaniamiento'] ?>">
                            <?= $acompaniamiento['nombre_acompaniamiento'] ?></option>
                    <?php endforeach; ?>
                </select>
                <input type="number" name="cantidad_acompaniamiento[]" class="form-control" placeholder="Cantidad"
                    value="1" min="1">
            </div>
        </div>
        <button type="button" class="btn btn-secondary" onclick="agregarElemento('acompaniamientos')">Agregar
            Acompañamiento</button>

        <!-- Bebidas -->
        <h2 class="my-4">Bebidas</h2>
        <div id="bebidas-container" class="mb-3">
            <div class="input-group mb-2">
                <select name="bebida_id[]" class="form-select" onchange="actualizarOpciones('bebidas')">
                    <option value="" selected disabled>Seleccione una Bebida</option>
                    <?php foreach ($bebidas as $bebida): ?>
                        <option value="<?= $bebida['id_bebida'] ?>"><?= $bebida['nombre_bebida'] ?></option>
                    <?php endforeach; ?>
                </select>
                <input type="number" name="cantidad_bebida[]" class="form-control" placeholder="Cantidad" value="1"
                    min="1">
            </div>
        </div>
        <button type="button" class="btn btn-secondary" onclick="agregarElemento('bebidas')">Agregar Bebida</button>

        <!-- Postres -->
        <h2 class="my-4">Postres</h2>
        <div id="postres-container" class="mb-3">
            <div class="input-group mb-2">
                <select name="postre_id[]" class="form-select" onchange="actualizarOpciones('postres')">
                    <option value="" selected disabled>Seleccione un Postre</option>
                    <?php foreach ($postres as $postre): ?>
                        <option value="<?= $postre['id_postre'] ?>"><?= $postre['nombre_postre'] ?></option>
                    <?php endforeach; ?>
                </select>
                <input type="number" name="cantidad_postre[]" class="form-control" placeholder="Cantidad" value="1"
                    min="1">
            </div>
        </div>
        <button type="button" class="btn btn-secondary" onclick="agregarElemento('postres')">Agregar Postre</button>

        <!-- Campo de imagen opcional -->
        <div class="mb-3">
            <label for="imagen" class="form-label">Imagen:</label>
            <input type="file" name="imagen" id="imagen" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary mt-4">Guardar Combo</button>
    </form>
</body>

</html>
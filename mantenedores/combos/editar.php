<?php
if (!isset($_GET['id_combo'])) {
    die("Error: No se proporcionó el ID del combo.");
}

$id_combo = $_GET['id_combo'];

include '../../conexion.php';

// Consultar los datos del combo
$comboQuery = $conexion->prepare("SELECT * FROM combo WHERE id_combo = ?");
$comboQuery->bind_param("i", $id_combo);
$comboQuery->execute();
$combo = $comboQuery->get_result()->fetch_assoc();

if (!$combo) {
    die("Error: No se encontró el combo con ese ID.");
}

// Consultar los productos para las selecciones
$hamburguesas = $conexion->query("SELECT id_hamburguesa, nombre_hamburguesa FROM hamburguesa")->fetch_all(MYSQLI_ASSOC);
$acompaniamientos = $conexion->query("SELECT id_acompaniamiento, nombre_acompaniamiento FROM acompaniamiento")->fetch_all(MYSQLI_ASSOC);
$bebidas = $conexion->query("SELECT id_bebida, nombre_bebida FROM bebida")->fetch_all(MYSQLI_ASSOC);
$postres = $conexion->query("SELECT id_postre, nombre_postre FROM postre")->fetch_all(MYSQLI_ASSOC);

// Obtener los elementos del combo
$hamburguesasSeleccionadas = $conexion->query("SELECT id_hamburguesa, cantidad FROM combo_hamburguesa WHERE id_combo = $id_combo")->fetch_all(MYSQLI_ASSOC);
$acompaniamientosSeleccionados = $conexion->query("SELECT id_acompaniamiento, cantidad FROM combo_acompaniamiento WHERE id_combo = $id_combo")->fetch_all(MYSQLI_ASSOC);
$bebidasSeleccionadas = $conexion->query("SELECT id_bebida, cantidad FROM combo_bebida WHERE id_combo = $id_combo")->fetch_all(MYSQLI_ASSOC);
$postresSeleccionados = $conexion->query("SELECT id_postre, cantidad FROM combo_postre WHERE id_combo = $id_combo")->fetch_all(MYSQLI_ASSOC);

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

    if ($_FILES['imagen']['error'] == UPLOAD_ERR_OK) {
        $nombre_imagen = $_FILES['imagen']['name'];
        $ruta_temporal = $_FILES['imagen']['tmp_name'];
        $ruta_destino = "../../uploads/combos/" . $nombre_imagen;

        if (move_uploaded_file($ruta_temporal, $ruta_destino)) {
            // Actualizar la hamburguesa con la nueva imagen
            $sql = "UPDATE combo SET nombre_combo = ?, descripcion = ?, precio = ?, imagen = ? WHERE id_combo = ?";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("ssdsi", $nombre_combo, $descripcion, $precio, $nombre_imagen, $id_combo);
        } else {
            echo "<div class='container mt-3'>
                <div class='alert alert-danger alert-dismissible fade show' role='alert'>
                    Error al subir la imagen.
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>
              </div>";
            exit;
        }
    } else {
        // Si no se sube una nueva imagen, mantener la imagen actual
        $sql = "UPDATE combo SET nombre_combo = ?, descripcion = ?, precio = ? WHERE id_combo = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("ssdi", $nombre_combo, $descripcion, $precio, $id_combo);
    }

    if ($stmt->execute()) {
        // Eliminar los registros previos de elementos del combo
        $conexion->query("DELETE FROM combo_hamburguesa WHERE id_combo = $id_combo");
        $conexion->query("DELETE FROM combo_acompaniamiento WHERE id_combo = $id_combo");
        $conexion->query("DELETE FROM combo_bebida WHERE id_combo = $id_combo");
        $conexion->query("DELETE FROM combo_postre WHERE id_combo = $id_combo");

        // Insertar los nuevos elementos
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

        // Redirigir al listar
        header("Location: listar.php");
        exit();
    } else {
        echo "Error al actualizar el combo.";
    }
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

            if (todasLlenas) {
                const selectHTML = contenedor.querySelector('select').outerHTML;
                const cantidadHTML = contenedor.querySelector('input[type="number"]').outerHTML;
                const nuevoElemento = document.createElement('div');
                nuevoElemento.classList.add('input-group', 'mb-2');
                nuevoElemento.innerHTML = selectHTML + cantidadHTML;

                const eliminarBtn = document.createElement('button');
                eliminarBtn.type = "button";
                eliminarBtn.className = "btn btn-danger ms-2";
                eliminarBtn.textContent = "Eliminar";
                eliminarBtn.onclick = () => nuevoElemento.remove();

                nuevoElemento.appendChild(eliminarBtn);

                // Insertar el nuevo producto por encima del botón
                contenedor.insertBefore(nuevoElemento, contenedor.lastElementChild);

                // Establecer la primera opción de cada select como "Seleccione producto"
                const nuevosSelects = nuevoElemento.querySelectorAll('select');
                nuevosSelects.forEach(select => {
                    // Crear y añadir la opción "Seleccione producto" al principio del select
                    const option = document.createElement('option');
                    option.value = "";
                    option.text = "Seleccione producto";
                    select.prepend(option);  // Insertamos la opción como la primera

                    select.selectedIndex = 0; // Aseguramos que la opción predeterminada sea la de seleccionar
                });

                // Actualizar las opciones para cada select
                nuevosSelects.forEach(select => {
                    select.addEventListener('change', function () {
                        actualizarOpciones(categoria);
                    });
                });
                actualizarOpciones(categoria);
            } else {
                alert("Seleccione un elemento y especifique su cantidad en todas las filas antes de agregar otro.");
            }
        }
        function eliminarElemento(elementId) {
            const element = document.getElementById(elementId);
            if (element) {
                element.remove();
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
                    if (selects[i].value && (!cantidades[i].value || cantidades[i].value <= 0)) {
                        alert(`Por favor, especifique una cantidad para cada ${categoria.slice(0, -1)} seleccionado.`);
                        return false;
                    }
                }
            }
            return true;
        }

        document.addEventListener('DOMContentLoaded', function () {
            const cantidades = document.querySelectorAll('input[type="number"]');
            cantidades.forEach(input => {
                input.addEventListener('input', function () {
                    if (this.value < 1) {
                        this.value = 1;
                    }
                });
            });
        });
    </script>
</head>

<body class="container py-5">
    <div class="d-flex justify-content-between align-items-center">
        <h1>Editar Combo</h1>
        <button class="btn btn-secondary" onclick="window.location.href='listar.php'">Volver</button>
    </div>
    <form action="editar.php?id_combo=<?php echo $id_combo; ?>" method="POST" enctype="multipart/form-data"
        onsubmit="return validarFormulario()">
        <div class="mb-3">
            <label for="nombre_combo" class="form-label">Nombre del Combo</label>
            <input type="text" class="form-control" id="nombre_combo" name="nombre_combo"
                value="<?php echo $combo['nombre_combo']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea class="form-control" id="descripcion" name="descripcion"
                required><?php echo $combo['descripcion']; ?></textarea>
        </div>
        <div class="mb-3">
            <label for="precio" class="form-label">Precio</label>
            <input type="number" class="form-control" id="precio" name="precio" value="<?php echo $combo['precio']; ?>"
                required>
        </div>
        <div class="mb-3">
            <label for="imagen" class="form-label">Imagen del Combo:</label>
            <?php if (!empty($combo['imagen'])): ?>
                <div class="mb-2">
                    <img src="../../uploads/combos/<?php echo $combo['imagen']; ?>" alt="Imagen Actual"
                        style="width: 100px; height: 100px; object-fit: cover;">
                </div>
            <?php endif; ?>
            <input type="file" name="imagen" class="form-control">
        </div>

        <!-- Sección de Hamburguesas -->
        <div id="hamburguesas-container" class="mb-4">
            <h3>Hamburguesas</h3>
            <?php foreach ($hamburguesasSeleccionadas as $hamburguesa): ?>
                <div class="input-group mb-2">
                    <select class="form-select" name="hamburguesa_id[]">
                        <?php foreach ($hamburguesas as $item): ?>
                            <option value="<?php echo $item['id_hamburguesa']; ?>" <?php echo $hamburguesa['id_hamburguesa'] == $item['id_hamburguesa'] ? 'selected' : ''; ?>>
                                <?php echo $item['nombre_hamburguesa']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <input type="number" class="form-control" name="cantidad_hamburguesa[]"
                        value="<?php echo $hamburguesa['cantidad']; ?>" min="1" required>
                    <button type="button" class="btn btn-danger ms-2" onclick="this.parentElement.remove()">Eliminar</button>
                </div>
            <?php endforeach; ?>
            <button type="button" class="btn btn-secondary mb-3" onclick="agregarElemento('hamburguesas')">Agregar Hamburguesa</button>
        </div>

        <!-- Sección de Acompañamientos -->
        <div id="acompaniamientos-container" class="mb-4">
            <h3>Acompañamientos</h3>
            <?php foreach ($acompaniamientosSeleccionados as $acompaniamiento): ?>
                <div class="input-group mb-2">
                    <select class="form-select" name="acompaniamiento_id[]">
                        <?php foreach ($acompaniamientos as $item): ?>
                            <option value="<?php echo $item['id_acompaniamiento']; ?>" <?php echo $acompaniamiento['id_acompaniamiento'] == $item['id_acompaniamiento'] ? 'selected' : ''; ?>>
                                <?php echo $item['nombre_acompaniamiento']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <input type="number" class="form-control" name="cantidad_acompaniamiento[]"
                        value="<?php echo $acompaniamiento['cantidad']; ?>" min="1" required>
                    <button type="button" class="btn btn-danger ms-2" onclick="this.parentElement.remove()">Eliminar</button>
                </div>
            <?php endforeach; ?>
            <button type="button" class="btn btn-secondary mb-3" onclick="agregarElemento('acompaniamientos')">Agregar Acompañamiento</button>
        </div>

        <!-- Sección de Bebidas -->
        <div id="bebidas-container" class="mb-4">
            <h3>Bebidas</h3>
            <?php foreach ($bebidasSeleccionadas as $bebida): ?>
                <div class="input-group mb-2">
                    <select class="form-select" name="bebida_id[]">
                        <?php foreach ($bebidas as $item): ?>
                            <option value="<?php echo $item['id_bebida']; ?>" <?php echo $bebida['id_bebida'] == $item['id_bebida'] ? 'selected' : ''; ?>>
                                <?php echo $item['nombre_bebida']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <input type="number" class="form-control" name="cantidad_bebida[]"
                        value="<?php echo $bebida['cantidad']; ?>" min="1" required>
                    <button type="button" class="btn btn-danger ms-2" onclick="this.parentElement.remove()">Eliminar</button>
                </div>
            <?php endforeach; ?>
            <button type="button" class="btn btn-secondary mb-3" onclick="agregarElemento('bebidas')">Agregar Bebida</button>
        </div>

        <!-- Sección de Postres -->
        <div id="postres-container" class="mb-4">
            <h3>Postres</h3>
            <?php foreach ($postresSeleccionados as $postre): ?>
                <div class="input-group mb-2">
                    <select class="form-select" name="postre_id[]">
                        <?php foreach ($postres as $item): ?>
                            <option value="<?php echo $item['id_postre']; ?>" <?php echo $postre['id_postre'] == $item['id_postre'] ? 'selected' : ''; ?>>
                                <?php echo $item['nombre_postre']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <input type="number" class="form-control" name="cantidad_postre[]"
                        value="<?php echo $postre['cantidad']; ?>" min="1" required>
                    <button type="button" class="btn btn-danger ms-2" onclick="this.parentElement.remove()">Eliminar</button>
                </div>
            <?php endforeach; ?>
            <button type="button" class="btn btn-secondary mb-3" onclick="agregarElemento('postres')">Agregar Postre</button>
        </div>

        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
    </form>
</body>

</html>
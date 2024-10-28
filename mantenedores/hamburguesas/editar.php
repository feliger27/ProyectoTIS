<?php
include '../../conexion.php';   // Incluir la conexión a la base de datos

if (isset($_GET['id'])) {
    $id_hamburguesa = $_GET['id'];
    // Seleccionamos la hamburguesa específica
    $sql = "SELECT * FROM hamburguesa WHERE id_hamburguesa = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id_hamburguesa);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $hamburguesa = $result->fetch_assoc();
    } else {
        echo "<div class='alert alert-danger' role='alert'>Error: Hamburguesa no encontrada.</div>";
        exit;  
    }

    // Obtener los ingredientes seleccionados de esta hamburguesa
    $sql_ingredientes = "SELECT id_ingrediente, cantidad FROM hamburguesa_ingrediente WHERE id_hamburguesa = ?";
    $stmt_ingredientes = $conexion->prepare($sql_ingredientes);
    $stmt_ingredientes->bind_param("i", $id_hamburguesa);
    $stmt_ingredientes->execute();
    $result_ingredientes = $stmt_ingredientes->get_result();
    $ingredientes_seleccionados = [];
    while ($row = $result_ingredientes->fetch_assoc()) {
        $ingredientes_seleccionados[$row['id_ingrediente']] = $row['cantidad'];
    }

    // Obtener los aderezos seleccionados de esta hamburguesa
    $sql_aderezos = "SELECT id_aderezo FROM hamburguesa_aderezo WHERE id_hamburguesa = ?";
    $stmt_aderezos = $conexion->prepare($sql_aderezos);
    $stmt_aderezos->bind_param("i", $id_hamburguesa);
    $stmt_aderezos->execute();
    $result_aderezos = $stmt_aderezos->get_result();
    $aderezos_seleccionados = [];
    while ($row = $result_aderezos->fetch_assoc()) {
        $aderezos_seleccionados[] = $row['id_aderezo'];
    }
} else {
    echo "<div class='alert alert-danger' role='alert'>Error: ID de hamburguesa no proporcionado.</div>";
    exit;  
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_hamburguesa = $_POST['id_hamburguesa'];
    $nombre_hamburguesa = $_POST['nombre_hamburguesa'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $ingredientes = isset($_POST['ingredientes']) ? $_POST['ingredientes'] : []; // Ingredientes seleccionados
    $aderezos = isset($_POST['aderezos']) ? $_POST['aderezos'] : []; // Array de aderezos

    // Validar que haya al menos un ingrediente con cantidad mayor a 0
    $tiene_ingredientes = false;
    foreach ($ingredientes as $cantidad) {
        if ($cantidad > 0) {
            $tiene_ingredientes = true;
            break;
        }
    }

    if (!$tiene_ingredientes) {
        echo "<div class='container mt-3'>
                <div class='alert alert-danger alert-dismissible fade show' role='alert'>
                    Debes seleccionar al menos un ingrediente con cantidad mayor a 0.
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>
            </div>";
    } else {
        // Actualizar la hamburguesa
        $sql = "UPDATE hamburguesa 
                SET nombre_hamburguesa = ?, descripcion = ?, precio = ? 
                WHERE id_hamburguesa = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("ssdi", $nombre_hamburguesa, $descripcion, $precio, $id_hamburguesa);
        
        if ($stmt->execute()) {
            // Primero eliminamos los ingredientes antiguos
            $sql_delete_ingredientes = "DELETE FROM hamburguesa_ingrediente WHERE id_hamburguesa = ?";
            $stmt_delete_ingredientes = $conexion->prepare($sql_delete_ingredientes);
            $stmt_delete_ingredientes->bind_param("i", $id_hamburguesa);
            $stmt_delete_ingredientes->execute();

            // Insertamos los nuevos ingredientes
            foreach ($ingredientes as $id_ingrediente => $cantidad) {
                if ($cantidad > 0) { // Solo insertamos si la cantidad es mayor a 0
                    $sql_ingrediente = "INSERT INTO hamburguesa_ingrediente (id_hamburguesa, id_ingrediente, cantidad) 
                                        VALUES (?, ?, ?)";
                    $stmt_ingrediente = $conexion->prepare($sql_ingrediente);
                    $stmt_ingrediente->bind_param("iii", $id_hamburguesa, $id_ingrediente, $cantidad);
                    $stmt_ingrediente->execute();
                }
            }

            // Primero eliminamos los aderezos antiguos
            $sql_delete_aderezos = "DELETE FROM hamburguesa_aderezo WHERE id_hamburguesa = ?";
            $stmt_delete_aderezos = $conexion->prepare($sql_delete_aderezos);
            $stmt_delete_aderezos->bind_param("i", $id_hamburguesa);
            $stmt_delete_aderezos->execute();

            // Insertamos los nuevos aderezos
            foreach ($aderezos as $id_aderezo) {
                $sql_aderezo = "INSERT INTO hamburguesa_aderezo (id_hamburguesa, id_aderezo) 
                                VALUES (?, ?)";
                $stmt_aderezo = $conexion->prepare($sql_aderezo);
                $stmt_aderezo->bind_param("ii", $id_hamburguesa, $id_aderezo);
                $stmt_aderezo->execute();
            }

            echo "<div class='container mt-3'>
                <div class='alert alert-success alert-dismissible fade show' role='alert'>
                    Hamburguesa editada exitosamente.
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
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Hamburguesa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center">
        <h1>Editar Hamburguesa</h1>
        <button class="btn btn-secondary" onclick="window.location.href='listar.php'">Volver</button>
    </div>
    <form action="editar.php?id=<?php echo $id_hamburguesa; ?>" method="POST" class="mt-4">
        <input type="hidden" name="id_hamburguesa" value="<?php echo $hamburguesa['id_hamburguesa']; ?>">
        
        <div class="mb-3">
            <label for="nombre_hamburguesa" class="form-label">Nombre de la Hamburguesa:</label>
            <input type="text" name="nombre_hamburguesa" id="nombre_hamburguesa" class="form-control" value="<?php echo $hamburguesa['nombre_hamburguesa']; ?>" required>
        </div>

        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción:</label>
            <textarea name="descripcion" id="descripcion" class="form-control" rows="3" required><?php echo $hamburguesa['descripcion']; ?></textarea>
        </div>

        <div class="mb-3">
            <label for="precio" class="form-label">Precio:</label>
            <input type="number" step="0.01" name="precio" id="precio" class="form-control" value="<?php echo $hamburguesa['precio']; ?>" required>
        </div>

        <div class="mb-3">
            <label for="ingredientes" class="form-label">Selecciona Ingredientes y Cantidades:</label>
            <?php
            $sql_ingredientes = "SELECT id_ingrediente, nombre_ingrediente FROM ingrediente";
            $result_ingredientes = $conexion->query($sql_ingredientes);

            if ($result_ingredientes->num_rows > 0) {
                while($row = $result_ingredientes->fetch_assoc()) {
                    $cantidad_actual = isset($ingredientes_seleccionados[$row['id_ingrediente']]) ? $ingredientes_seleccionados[$row['id_ingrediente']] : 0;
                    echo "<div class='mb-2'>";
                    echo "<label>{$row['nombre_ingrediente']}</label>";
                    echo "<input type='number' name='ingredientes[{$row['id_ingrediente']}]' min='0' value='$cantidad_actual' class='form-control' placeholder='Cantidad'>";
                    echo "</div>";
                }
            } else {
                echo "<p>No hay ingredientes disponibles.</p>";
            }
            ?>
        </div>

        <div class="mb-3">
            <label for="aderezos" class="form-label">Selecciona Aderezos:</label>
            <div style="max-height: 150px; overflow-y: auto; border: 1px solid #ced4da; padding: 10px; border-radius: 0.25rem;">
                <?php
                $sql_aderezos = "SELECT id_aderezo, nombre_aderezo FROM aderezo";
                $result_aderezos = $conexion->query($sql_aderezos);

                if ($result_aderezos->num_rows > 0) {
                    while ($row = $result_aderezos->fetch_assoc()) {
                    $checked = in_array($row['id_aderezo'], $aderezos_seleccionados) ? 'checked' : '';
                    echo "<div class='form-check'>";
                    echo "<input type='checkbox' name='aderezos[]' class='form-check-input' id='aderezo{$row['id_aderezo']}' value='{$row['id_aderezo']}' $checked>";
                    echo "<label class='form-check-label' for='aderezo{$row['id_aderezo']}'>{$row['nombre_aderezo']}</label>";
                    echo "</div>";
                }
                } else {
                    echo "<p>No hay aderezos disponibles</p>";
                }
            ?>
            </div>
        </div>


        <button type="submit" class="btn btn-primary">Guardar cambios</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>








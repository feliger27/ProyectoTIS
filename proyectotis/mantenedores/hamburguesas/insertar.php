<?php
include '../../conexion.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recogemos los datos del formulario
    $nombre_hamburguesa = $_POST['nombre_hamburguesa'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $ingredientes = $_POST['ingredientes']; // Array de ingredientes con cantidades
    $aderezos = $_POST['aderezos']; // Array de aderezos

    // Insertamos la nueva hamburguesa en la tabla Hamburguesa
    $sql = "INSERT INTO hamburguesa (nombre_hamburguesa, descripcion, precio) 
            VALUES (?, ?, ?)";
    
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ssd", $nombre_hamburguesa, $descripcion, $precio);  // "ssd" para string, string, decimal
    if ($stmt->execute()) {
        $id_hamburguesa = $stmt->insert_id; // Obtenemos el id de la hamburguesa recién insertada

        // Insertamos cada ingrediente en la tabla Hamburguesa_Ingrediente con su cantidad
        foreach ($ingredientes as $id_ingrediente => $cantidad) {
            if ($cantidad > 0) {  // Solo insertamos si la cantidad es mayor a 0
                $sql_ingrediente = "INSERT INTO hamburguesa_ingrediente (id_hamburguesa, id_ingrediente, cantidad)
                                    VALUES (?, ?, ?)";
                $stmt_ingrediente = $conexion->prepare($sql_ingrediente);
                $stmt_ingrediente->bind_param("iii", $id_hamburguesa, $id_ingrediente, $cantidad); // "iii" para int, int, int
                $stmt_ingrediente->execute();
            }
        }

        // Insertamos cada aderezo en la tabla Hamburguesa_Aderezo
        foreach ($aderezos as $id_aderezo) {
            $sql_aderezo = "INSERT INTO hamburguesa_aderezo (id_hamburguesa, id_aderezo)
                            VALUES (?, ?)";
            $stmt_aderezo = $conexion->prepare($sql_aderezo);
            $stmt_aderezo->bind_param("ii", $id_hamburguesa, $id_aderezo); // "ii" para int, int
            $stmt_aderezo->execute();
        }

        echo "<div class='alert alert-success' role='alert'>Hamburguesa, ingredientes y aderezos agregados exitosamente</div>";
    } else {
        echo "<div class='alert alert-danger' role='alert'>Error: " . $sql . "<br>" . $conexion->error . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Hamburguesa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center">
        <h1>Agregar Nueva Hamburguesa</h1>
        <button class="btn btn-secondary" onclick="window.location.href='listar.php'">Volver</button>
    </div>
    <form action="insertar.php" method="POST" class="mt-4">
        <div class="mb-3">
            <label for="nombre_hamburguesa" class="form-label">Nombre de la Hamburguesa:</label>
            <input type="text" name="nombre_hamburguesa" id="nombre_hamburguesa" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción:</label>
            <textarea name="descripcion" id="descripcion" class="form-control" rows="3" required></textarea>
        </div>
        
        <div class="mb-3">
            <label for="precio" class="form-label">Precio:</label>
            <input type="number" step="0.01" name="precio" id="precio" class="form-control" required>
        </div>

        <!-- Ingredientes con cantidades -->
        <div class="mb-3">
            <label for="ingredientes" class="form-label">Selecciona Ingredientes y Cantidades:</label>
            <?php
            $sql_ingredientes = "SELECT id_ingrediente, nombre_ingrediente FROM ingrediente";
            $result_ingredientes = $conexion->query($sql_ingredientes);

            if ($result_ingredientes->num_rows > 0) {
                while($row = $result_ingredientes->fetch_assoc()) {
                    echo "<div class='mb-2'>";
                    echo "<label>{$row['nombre_ingrediente']}</label>";
                    echo "<input type='number' name='ingredientes[{$row['id_ingrediente']}]' min='0' value='0' class='form-control' placeholder='Cantidad'>";
                    echo "</div>";
                }
            } else {
                echo "<p>No hay ingredientes disponibles</p>";
            }
            ?>
        </div>

        <!-- Aderezos con selección múltiple -->
        <div class="mb-3">
            <label for="aderezos" class="form-label">Selecciona Aderezos:</label>
            <div style="max-height: 150px; overflow-y: auto; border: 1px solid #ced4da; padding: 10px; border-radius: 0.25rem;">
                <?php
                $sql_aderezos = "SELECT id_aderezo, nombre_aderezo FROM aderezo";
                $result_aderezos = $conexion->query($sql_aderezos);

                if ($result_aderezos->num_rows > 0) {
                    while ($row = $result_aderezos->fetch_assoc()) {
                        echo "<div class='form-check'>";
                        echo "<input type='checkbox' name='aderezos[]' class='form-check-input' id='aderezo{$row['id_aderezo']}' value='{$row['id_aderezo']}'>";
                        echo "<label class='form-check-label' for='aderezo{$row['id_aderezo']}'>{$row['nombre_aderezo']}</label>";
                        echo "</div>";
                    }
                } else {
                    echo "<p>No hay aderezos disponibles</p>";
                }
                ?>
            </div>
        </div>


        <div class="mb-3">
            <button type="submit" class="btn btn-primary">Guardar Hamburguesa</button>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>






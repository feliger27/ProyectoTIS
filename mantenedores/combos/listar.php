<?php
include '../../conexion.php';

// Obtener todos los combos con sus detalles, organizando los elementos por categoría
$sql = "
    SELECT 
        c.id_combo, 
        c.nombre_combo, 
        c.descripcion, 
        c.precio,
        GROUP_CONCAT(DISTINCT CONCAT(h.nombre_hamburguesa, ' (', ch.cantidad, ')') ORDER BY h.nombre_hamburguesa ASC SEPARATOR ', ') AS hamburguesas,
        GROUP_CONCAT(DISTINCT CONCAT(a.nombre_acompaniamiento, ' (', ca.cantidad, ')') ORDER BY a.nombre_acompaniamiento ASC SEPARATOR ', ') AS acompaniamientos,
        GROUP_CONCAT(DISTINCT CONCAT(b.nombre_bebida, ' (', cb.cantidad, ')') ORDER BY b.nombre_bebida ASC SEPARATOR ', ') AS bebidas,
        GROUP_CONCAT(DISTINCT CONCAT(p.nombre_postre, ' (', cp.cantidad, ')') ORDER BY p.nombre_postre ASC SEPARATOR ', ') AS postres
    FROM 
        combo c
    LEFT JOIN combo_hamburguesa ch ON c.id_combo = ch.id_combo
    LEFT JOIN hamburguesa h ON ch.id_hamburguesa = h.id_hamburguesa
    LEFT JOIN combo_acompaniamiento ca ON c.id_combo = ca.id_combo
    LEFT JOIN acompaniamiento a ON ca.id_acompaniamiento = a.id_acompaniamiento
    LEFT JOIN combo_bebida cb ON c.id_combo = cb.id_combo
    LEFT JOIN bebida b ON cb.id_bebida = b.id_bebida
    LEFT JOIN combo_postre cp ON c.id_combo = cp.id_combo
    LEFT JOIN postre p ON cp.id_postre = p.id_postre
    GROUP BY c.id_combo, c.nombre_combo, c.descripcion, c.precio
";

$result = $conexion->query($sql);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de Combos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-5">
    <h1 class="mb-4">Listado de Combos</h1>
    
    <a href="insertar.php" class="btn btn-success mb-3">Agregar Nuevo Combo</a>
    
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Precio</th>
                <th>Hamburguesas</th>
                <th>Acompañamientos</th>
                <th>Bebidas</th>
                <th>Postres</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($combo = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($combo['id_combo']) ?></td>
                    <td><?= htmlspecialchars($combo['nombre_combo']) ?></td>
                    <td><?= htmlspecialchars($combo['descripcion']) ?></td>
                    <td>$<?= number_format($combo['precio'], 2) ?></td>
                    <td><?= $combo['hamburguesas'] ?: 'N/A' ?></td>
                    <td><?= $combo['acompaniamientos'] ?: 'N/A' ?></td>
                    <td><?= $combo['bebidas'] ?: 'N/A' ?></td>
                    <td><?= $combo['postres'] ?: 'N/A' ?></td>
                    <td>
                        <a href="editar.php?id=<?= $combo['id_combo'] ?>" class="btn btn-primary btn-sm">Editar</a>
                        <a href="eliminar.php?id=<?= $combo['id_combo'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Está seguro de que desea eliminar este combo?');">Eliminar</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>

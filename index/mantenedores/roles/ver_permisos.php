<?php
include '../../conexion.php';

// Obtener el ID del rol desde el parámetro GET
$roleId = $_GET['id'];

// Ajusta el nombre de las columnas según los nombres en tu tabla `rol_permiso`
$query = "SELECT p.nombre_permiso AS permiso_nombre FROM permiso p
          JOIN rol_permiso rp ON p.id_permiso = rp.id_permiso
          WHERE rp.id_rol = ?";
$stmt = $conexion->prepare($query);
$stmt->bind_param("i", $roleId);
$stmt->execute();
$result = $stmt->get_result();

// Generar el HTML de la lista de permisos
if ($result->num_rows > 0) {
    echo "<ul>";
    while ($row = $result->fetch_assoc()) {
        echo "<li>" . htmlspecialchars($row['permiso_nombre']) . "</li>";
    }
    echo "</ul>";
} else {
    echo "No hay permisos asignados a este rol.";
}
$stmt->close();
?>

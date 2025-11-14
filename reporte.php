<?php 
require_once __DIR__ . '/includes/conexion.php';
$db = new Database();
$result = $db->obtenerInscriptos();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Inscriptos</title>
    <link rel="stylesheet" href="css/estilos.css">
  

</head>
<body>
<div class="contenedor">
    <h2>Reporte de Inscriptos</h2>

    <a class="btn" href="index.php">← Volver al formulario</a>

    <table>
        <tr>
            <th>ID</th>
            <th>Nombre completo</th>
            <th>Edad</th>
            <th>Sexo</th>
            <th>País</th>
            <th>Nacionalidad</th>
            <th>Correo</th>
            <th>Celular</th>
            <th>Temas de interés</th>
            <th>Observaciones</th>
            <th>Fecha formulario</th>
        </tr>
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($fila = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $fila['id_inscriptor']; ?></td>
                    <td><?php echo htmlspecialchars($fila['nombre'] . " " . $fila['apellido']); ?></td>
                    <td><?php echo $fila['edad']; ?></td>
                    <td><?php echo $fila['sexo']; ?></td>
                    <td><?php echo htmlspecialchars($fila['nombre_pais']); ?></td>
                    <td><?php echo htmlspecialchars($fila['nacionalidad']); ?></td>
                    <td><?php echo htmlspecialchars($fila['correo']); ?></td>
                    <td><?php echo htmlspecialchars($fila['celular']); ?></td>
                    <td><?php echo htmlspecialchars($fila['temas_interes']); ?></td>
                    <td><?php echo nl2br(htmlspecialchars($fila['observaciones'])); ?></td>
                    <td><?php echo $fila['fecha_formulario']; ?></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="11">No hay inscriptos registrados.</td></tr>
        <?php endif; ?>
    </table>
</div>
</body>
</html>

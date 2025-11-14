<?php
require_once __DIR__ . '/includes/conexion.php';

$db = new Database();

// Tomamos el ID enviado por GET
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$datos = null;

if ($id > 0) {
    $sql = "SELECT i.*, p.nombre_pais 
            FROM inscriptores i
            INNER JOIN paises p ON i.id_pais_residencia = p.id_pais
            WHERE i.id_inscriptor = ?";

    $stmt = $db->getConnection()->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $datos = $resultado->fetch_assoc();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro Exitoso</title>

    <!-- Usa tu CSS cálido existente -->
    <link rel="stylesheet" href="css/estilos.css">

    <style>
        .success-box {
            max-width: 700px;
            margin: 40px auto;
            background: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.2);
            text-align: center;
        }
        .success-icon {
            font-size: 60px;
            color: #27ae60;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background: #F2C57C;
            color: #5A3E2B;
        }
    </style>
</head>
<body>

<div class="success-box">
    <div class="success-icon">✓</div>
    <h1>¡Registro Exitoso!</h1>

    <?php if ($datos): ?>
        <p>El registro se ha guardado correctamente.</p>

        <table>
            <tr><th colspan="2">Datos Registrados</th></tr>

            <tr><td>Nombre:</td><td><?= htmlspecialchars($datos['nombre']); ?></td></tr>
            <tr><td>Apellido:</td><td><?= htmlspecialchars($datos['apellido']); ?></td></tr>
            <tr><td>Edad:</td><td><?= htmlspecialchars($datos['edad']); ?></td></tr>
            <tr><td>Sexo:</td><td><?= htmlspecialchars($datos['sexo']); ?></td></tr>
            <tr><td>País de Residencia:</td><td><?= htmlspecialchars($datos['nombre_pais']); ?></td></tr>
            <tr><td>Nacionalidad:</td><td><?= htmlspecialchars($datos['nacionalidad']); ?></td></tr>
            <tr><td>Correo:</td><td><?= htmlspecialchars($datos['correo']); ?></td></tr>
            <tr><td>Celular:</td><td><?= htmlspecialchars($datos['celular']); ?></td></tr>
            <tr><td>Temas de interés:</td><td><?= htmlspecialchars($datos['temas_interes']); ?></td></tr>
            <tr><td>Observaciones:</td><td><?= nl2br(htmlspecialchars($datos['observaciones'])); ?></td></tr>
            <tr><td>Fecha del formulario:</td><td><?= $datos['fecha_formulario']; ?></td></tr>

        </table>

    <?php else: ?>
        <div style="background:#f8d7da; padding:12px; border-radius:4px;">
            No se encontraron datos para este registro.
        </div>
    <?php endif; ?>

    <div class="botones" style="margin-top:25px;">
        <a href="index.php" class="btn">Nuevo Registro</a>
        <a href="reporte.php" class="btn">Ver Reportes</a>
    </div>
</div>

</body>
</html>

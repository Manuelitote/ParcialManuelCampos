<?php
require_once __DIR__ . '/includes/conexion.php';

$db = new Database();
$resultPaises = $db->obtenerPaises();
$resultAreas = $db->obtenerAreas();

/* ==========================
   VALORES INICIALES
========================== */
$nombre = "";
$apellido = "";
$edad = "";
$sexo = "";
$pais = "";
$nacionalidad = "";
$correo = "";
$celular = "";
$observaciones = "";
$temasSeleccionados = [];
$mensaje = "";
$tipoMensaje = "";

/* ==========================
   PROCESAMIENTO DEL FORMULARIO
========================== */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $errores = [];

    $nombre = trim($_POST['nombre'] ?? "");
    $apellido = trim($_POST['apellido'] ?? "");
    $edad = intval($_POST['edad'] ?? 0);
    $sexo = $_POST['sexo'] ?? "";
    $pais = intval($_POST['pais'] ?? 0);
    $nacionalidad = trim($_POST['nacionalidad'] ?? "");
    $correo = trim($_POST['correo'] ?? "");
    $celular = trim($_POST['celular'] ?? "");
    $observaciones = trim($_POST['observaciones'] ?? "");
    $temasSeleccionados = $_POST['temas'] ?? [];

    if ($nombre === "") $errores[] = "El nombre es obligatorio.";
    if ($apellido === "") $errores[] = "El apellido es obligatorio.";
    if ($edad <= 0) $errores[] = "La edad debe ser mayor que 0.";
    if ($sexo === "") $errores[] = "Debe seleccionar el sexo.";
    if ($pais <= 0) $errores[] = "Debe seleccionar un país.";
    if ($nacionalidad === "") $errores[] = "La nacionalidad es obligatoria.";

    if ($correo === "") $errores[] = "El correo es obligatorio.";
    elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) $errores[] = "Correo no válido.";

    if ($celular === "") $errores[] = "El celular es obligatorio.";

    $nombre = ucwords(strtolower($nombre));
    $apellido = ucwords(strtolower($apellido));

    $temasTexto = !empty($temasSeleccionados)
        ? implode(", ", $temasSeleccionados)
        : "";

    if (empty($errores)) {

        $datos = [
            'nombre' => $nombre,
            'apellido' => $apellido,
            'edad' => $edad,
            'sexo' => $sexo,
            'id_pais_residencia' => $pais,
            'nacionalidad' => $nacionalidad,
            'correo' => $correo,
            'celular' => $celular,
            'temas_interes' => $temasTexto,
            'observaciones' => $observaciones,
            'fecha_formulario' => date('Y-m-d H:i:s')
        ];

        if ($db->insertarInscriptor($datos)) {
            $nuevoId = $db->getInsertId();
            header("Location: exito.php?id=" . $nuevoId);
            exit;
        } else {
            $mensaje = "Ocurrió un error al guardar.";
            $tipoMensaje = "error";
        }

    } else {
        $mensaje = implode("<br>", $errores);
        $tipoMensaje = "error";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Formulario de Inscripción - iTECH</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>

<div class="contenedor">
    <h1>Formulario de Inscripción al Evento Tecnológico</h1>

    <?php if ($mensaje !== ""): ?>
        <div class="mensaje <?php echo $tipoMensaje; ?>"><?php echo $mensaje; ?></div>
    <?php endif; ?>

    <form action="" method="post">

        <label>Nombre:</label>
        <input type="text" name="nombre" value="<?php echo $nombre; ?>">

        <label>Apellido:</label>
        <input type="text" name="apellido" value="<?php echo $apellido; ?>">

        <label>Edad:</label>
        <input type="number" name="edad" value="<?php echo $edad; ?>">

        <label>Sexo:</label>
        <div style="margin-bottom:8px;">
            <label><input type="radio" name="sexo" value="M" <?php echo ($sexo == "M") ? "checked" : ""; ?>> Masculino</label>
            <label><input type="radio" name="sexo" value="F" <?php echo ($sexo == "F") ? "checked" : ""; ?>> Femenino</label>
            <label><input type="radio" name="sexo" value="Otro" <?php echo ($sexo == "Otro") ? "checked" : ""; ?>> Otro</label>
        </div>

        <label>País de residencia:</label>
        <select name="pais">
            <option value="0">Seleccione un país</option>
            <?php while ($fila = $resultPaises->fetch_assoc()): ?>
                <option value="<?php echo $fila['id_pais']; ?>" <?php echo ($pais == $fila['id_pais']) ? "selected" : ""; ?>>
                    <?php echo $fila['nombre_pais']; ?>
                </option>
            <?php endwhile; ?>
        </select>

        <label>Nacionalidad:</label>
        <input type="text" name="nacionalidad" value="<?php echo $nacionalidad; ?>">

        <label>Correo electrónico:</label>
        <input type="email" name="correo" value="<?php echo $correo; ?>">

        <label>Celular:</label>
        <input type="text" name="celular" value="<?php echo $celular; ?>">

        <label>Temas tecnológicos:</label>
        <div class="campo-checkbox">
            <?php while ($area = $resultAreas->fetch_assoc()): ?>
                <label>
                    <input type="checkbox" name="temas[]" value="<?php echo $area['nombre_area']; ?>"
                        <?php echo in_array($area['nombre_area'], $temasSeleccionados) ? "checked" : ""; ?>>
                    <?php echo $area['nombre_area']; ?>
                </label>
            <?php endwhile; ?>
        </div>

        <label>Observaciones:</label>
        <textarea name="observaciones"><?php echo $observaciones; ?></textarea>

        <label>Fecha:</label>
        <input type="text" value="<?php echo date('d/m/Y'); ?>" disabled>

        <div class="botones">
            <button type="submit">Guardar</button>
            <a class="btn" href="reporte.php">Ver reporte</a>
        </div>

    </form>

    <footer>
        &copy; <?php echo date('Y'); ?> iTECH. All rights reserved.
    </footer>
</div>

</body>
</html>

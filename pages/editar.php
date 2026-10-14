
<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');

require_once "../config/database.php";

function escapar($valor) {
    return htmlspecialchars((string)$valor, ENT_QUOTES, "UTF-8");
}

$estados = ["Pendiente", "En proceso", "Resuelta"];
$prioridades = ["Baja", "Media", "Alta", "Crítica"];

$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if (!$id || $id < 1) {
    exit("Identificador de incidencia no válido.");
}

$mensaje = "";

// Guardar los cambios enviados desde el formulario
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nombre = trim($_POST["nombre_reporta"] ?? "");
    $laboratorio = trim($_POST["laboratorio"] ?? "");
    $equipo = trim($_POST["equipo_afectado"] ?? "");
    $descripcion = trim($_POST["descripcion"] ?? "");
    $prioridad = $_POST["prioridad"] ?? "";
    $estado = $_POST["estado"] ?? "";

    if (
        $nombre === "" ||
        $laboratorio === "" ||
        $equipo === "" ||
        $descripcion === "" ||
        !in_array($prioridad, $prioridades, true) ||
        !in_array($estado, $estados, true)
    ) {
        $mensaje = "Completa todos los campos correctamente.";
    } else {
        $sql = "UPDATE incidencias
                SET nombre_reporta = ?,
                    laboratorio = ?,
                    equipo_afectado = ?,
                    descripcion = ?,
                    prioridad = ?,
                    estado = ?
                WHERE id = ?";

        $stmt = $conexion->prepare($sql);
        $stmt->bind_param(
            "ssssssi",
            $nombre,
            $laboratorio,
            $equipo,
            $descripcion,
            $prioridad,
            $estado,
            $id
        );

        if ($stmt->execute()) {
            $stmt->close();
            header("Location: listar.php?actualizada=1");
            exit;
        } else {
            $mensaje = "No se pudieron guardar los cambios.";
        }

        $stmt->close();
    }
}

// Obtener los datos actuales de la incidencia
$stmt = $conexion->prepare(
    "SELECT * FROM incidencias WHERE id = ?"
);
$stmt->bind_param("i", $id);
$stmt->execute();

$resultado = $stmt->get_result();
$incidencia = $resultado->fetch_assoc();
$stmt->close();

if (!$incidencia) {
    exit("La incidencia solicitada no existe.");
}

// Conservar los datos del formulario si hubo errores
if ($_SERVER["REQUEST_METHOD"] === "POST" && $mensaje !== "") {
    $incidencia["nombre_reporta"] = $nombre;
    $incidencia["laboratorio"] = $laboratorio;
    $incidencia["equipo_afectado"] = $equipo;
    $incidencia["descripcion"] = $descripcion;
    $incidencia["prioridad"] = $prioridad;
    $incidencia["estado"] = $estado;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar incidencia</title>

    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/registrar.css">
</head>
<body>

<main class="contenedor">

    <h1>Editar incidencia #<?= (int)$id ?></h1>

    <p class="subtitulo">
        Modifica la información de la incidencia.
    </p>

    <?php if ($mensaje !== ""): ?>
        <div class="mensaje error">
            <?= escapar($mensaje) ?>
        </div>
    <?php endif; ?>

    <form method="POST">

        <label for="nombre_reporta">Nombre de quien reporta</label>
        <input
            type="text"
            id="nombre_reporta"
            name="nombre_reporta"
            maxlength="100"
            value="<?= escapar($incidencia["nombre_reporta"]) ?>"
            required
        >

        <label for="laboratorio">Laboratorio</label>
        <input
            type="text"
            id="laboratorio"
            name="laboratorio"
            maxlength="100"
            value="<?= escapar($incidencia["laboratorio"]) ?>"
            required
        >

        <label for="equipo_afectado">Equipo afectado</label>
        <input
            type="text"
            id="equipo_afectado"
            name="equipo_afectado"
            maxlength="150"
            value="<?= escapar($incidencia["equipo_afectado"]) ?>"
            required
        >

        <label for="descripcion">Descripción del problema</label>
        <textarea
            id="descripcion"
            name="descripcion"
            required
        ><?= escapar($incidencia["descripcion"]) ?></textarea>

        <label for="prioridad">Prioridad</label>
        <select id="prioridad" name="prioridad" required>
            <?php foreach ($prioridades as $opcion): ?>
                <option
                    value="<?= escapar($opcion) ?>"
                    <?= $incidencia["prioridad"] === $opcion ? "selected" : "" ?>
                >
                    <?= escapar($opcion) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="estado">Estado</label>
        <select id="estado" name="estado" required>
            <?php foreach ($estados as $opcion): ?>
                <option
                    value="<?= escapar($opcion) ?>"
                    <?= $incidencia["estado"] === $opcion ? "selected" : "" ?>
                >
                    <?= escapar($opcion) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button type="submit">Guardar cambios</button>

    </form>

    <a class="enlace" href="listar.php">Volver al listado</a>

</main>

</body>
</html>

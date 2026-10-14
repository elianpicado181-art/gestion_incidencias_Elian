
<?php
require_once "../config/auth.php";
error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once "../config/database.php";

function escapar($valor) {
    return htmlspecialchars((string)$valor, ENT_QUOTES, "UTF-8");
}

$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if (!$id || $id < 1) {
    exit("Identificador de incidencia no válido.");
}

// Buscar la incidencia antes de eliminarla
$stmt = $conexion->prepare(
    "SELECT id, laboratorio, equipo_afectado
     FROM incidencias
     WHERE id = ?"
);
$stmt->bind_param("i", $id);
$stmt->execute();

$resultado = $stmt->get_result();
$incidencia = $resultado->fetch_assoc();
$stmt->close();

if (!$incidencia) {
    exit("La incidencia no existe o ya fue eliminada.");
}

$mensaje = "";

// Eliminar únicamente cuando se confirma
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (($_POST["confirmar"] ?? "") === "si") {
        $stmt = $conexion->prepare(
            "DELETE FROM incidencias WHERE id = ?"
        );
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            $stmt->close();
            header("Location: listar.php?eliminada=1");
            exit;
        } else {
            $mensaje = "No se pudo eliminar la incidencia.";
            $stmt->close();
        }
    } else {
        header("Location: listar.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar incidencia</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/registrar.css">
</head>
<body>

<main class="contenedor">
    <h1>Confirmar eliminación</h1>

    <p>
        ¿Seguro que deseas eliminar esta incidencia?
    </p>

    <p>
        <strong>Laboratorio:</strong>
        <?= escapar($incidencia["laboratorio"]) ?>
    </p>

    <p>
        <strong>Equipo afectado:</strong>
        <?= escapar($incidencia["equipo_afectado"]) ?>
    </p>

    <?php if ($mensaje !== ""): ?>
        <div class="mensaje error">
            <?= escapar($mensaje) ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <button type="submit" name="confirmar" value="si">
            Sí, eliminar
        </button>

        <button type="submit" name="confirmar" value="no">
            Cancelar
        </button>
    </form>

    <a class="enlace" href="listar.php">Volver al listado</a>
</main>

</body>
</html>

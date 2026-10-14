
<?php
require_once "../config/database.php";

$mensaje = "";
$tipo_mensaje = "";

$nombre_reporta = "";
$laboratorio = "";
$equipo_afectado = "";
$descripcion = "";
$prioridad = "Media";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nombre_reporta = trim($_POST["nombre_reporta"] ?? "");
    $laboratorio = trim($_POST["laboratorio"] ?? "");
    $equipo_afectado = trim($_POST["equipo_afectado"] ?? "");
    $descripcion = trim($_POST["descripcion"] ?? "");
    $prioridad = $_POST["prioridad"] ?? "Media";

    $prioridades_validas = ["Baja", "Media", "Alta", "Crítica"];

    if (
        $nombre_reporta === "" ||
        $laboratorio === "" ||
        $equipo_afectado === "" ||
        $descripcion === "" ||
        !in_array($prioridad, $prioridades_validas, true)
    ) {
        $mensaje = "Completa todos los campos correctamente.";
        $tipo_mensaje = "error";
    } else {

        $sql = "INSERT INTO incidencias
                (nombre_reporta, laboratorio, equipo_afectado,
                 descripcion, prioridad)
                VALUES (?, ?, ?, ?, ?)";

        $stmt = $conexion->prepare($sql);

        $stmt->bind_param(
            "sssss",
            $nombre_reporta,
            $laboratorio,
            $equipo_afectado,
            $descripcion,
            $prioridad
        );

        if ($stmt->execute()) {
            $mensaje = "Incidencia registrada correctamente.";
            $tipo_mensaje = "exito";

            $nombre_reporta = "";
            $laboratorio = "";
            $equipo_afectado = "";
            $descripcion = "";
            $prioridad = "Media";
        } else {
            $mensaje = "No se pudo registrar la incidencia.";
            $tipo_mensaje = "error";
        }

        $stmt->close();
    }
}

function escapar($valor) {
    return htmlspecialchars($valor, ENT_QUOTES, "UTF-8");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar incidencia</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 30px 15px;
            font-family: Arial, sans-serif;
            background: #f1f5f9;
            color: #1e293b;
        }

        .contenedor {
            max-width: 700px;
            margin: auto;
            padding: 30px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        }

        h1 {
            margin-top: 0;
            color: #1d4ed8;
            text-align: center;
        }

        .subtitulo {
            text-align: center;
            color: #64748b;
            margin-bottom: 25px;
        }

        label {
            display: block;
            margin: 17px 0 7px;
            font-weight: bold;
        }

        input, select, textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            font-size: 15px;
        }

        textarea {
            min-height: 110px;
            resize: vertical;
        }

        input:focus, select:focus, textarea:focus {
            outline: 2px solid #93c5fd;
            border-color: #2563eb;
        }

        button {
            width: 100%;
            margin-top: 24px;
            padding: 13px;
            background: #2563eb;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background: #1d4ed8;
        }

        .mensaje {
            padding: 12px;
            margin-bottom: 18px;
            border-radius: 6px;
        }

        .exito {
            background: #dcfce7;
            color: #166534;
        }

        .error {
            background: #fee2e2;
            color: #991b1b;
        }

        .enlace {
            display: block;
            margin-top: 20px;
            text-align: center;
            color: #2563eb;
            text-decoration: none;
        }
    </style>
</head>

<body>

<div class="contenedor">

    <h1>Registrar incidencia</h1>

    <p class="subtitulo">
        Sistema de Gestión de Incidencias Tecnológicas
    </p>

    <?php if ($mensaje !== ""): ?>
        <div class="mensaje <?= escapar($tipo_mensaje) ?>">
            <?= escapar($mensaje) ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="">

        <label for="nombre_reporta">
            Nombre de quien reporta
        </label>
        <input
            type="text"
            id="nombre_reporta"
            name="nombre_reporta"
            maxlength="100"
            value="<?= escapar($nombre_reporta) ?>"
            required
        >

        <label for="laboratorio">Laboratorio</label>
        <input
            type="text"
            id="laboratorio"
            name="laboratorio"
            maxlength="100"
            placeholder="Ej. Laboratorio 1"
            value="<?= escapar($laboratorio) ?>"
            required
        >

        <label for="equipo_afectado">Equipo afectado</label>
        <input
            type="text"
            id="equipo_afectado"
            name="equipo_afectado"
            maxlength="150"
            placeholder="Ej. Computadora 05, teclado o proyector"
            value="<?= escapar($equipo_afectado) ?>"
            required
        >

        <label for="descripcion">Descripción del problema</label>
        <textarea
            id="descripcion"
            name="descripcion"
            required
        ><?= escapar($descripcion) ?></textarea>

        <label for="prioridad">Prioridad</label>
        <select id="prioridad" name="prioridad" required>
            <?php foreach (["Baja", "Media", "Alta", "Crítica"] as $opcion): ?>
                <option
                    value="<?= escapar($opcion) ?>"
                    <?= $prioridad === $opcion ? "selected" : "" ?>
                >
                    <?= escapar($opcion) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button type="submit">Registrar incidencia</button>

    </form>

    <a class="enlace" href="../index.php">
        Volver al inicio
    </a>

</div>

</body>
</html>


<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');

require_once "../config/database.php";

// Escapar valores para mostrarlos de forma segura.
function escapar($valor) {
    return htmlspecialchars((string)$valor, ENT_QUOTES, "UTF-8");
}

// Comprobar si hay un administrador autenticado.
$es_admin = isset($_SESSION["admin_id"]);

// Obtener filtros.
$laboratorio = trim($_GET["laboratorio"] ?? "");
$estado = $_GET["estado"] ?? "";
$prioridad = $_GET["prioridad"] ?? "";

// Opciones permitidas.
$estados_validos = ["Pendiente", "En proceso", "Resuelta"];
$prioridades_validas = ["Baja", "Media", "Alta", "Crítica"];

// Validar los filtros.
if (!in_array($estado, $estados_validos, true)) {
    $estado = "";
}

if (!in_array($prioridad, $prioridades_validas, true)) {
    $prioridad = "";
}

// Construir consulta con filtros.
$sql = "SELECT * FROM incidencias WHERE 1=1";
$parametros = [];
$tipos = "";

if ($laboratorio !== "") {
    $sql .= " AND laboratorio LIKE ?";
    $parametros[] = "%" . $laboratorio . "%";
    $tipos .= "s";
}

if ($estado !== "") {
    $sql .= " AND estado = ?";
    $parametros[] = $estado;
    $tipos .= "s";
}

if ($prioridad !== "") {
    $sql .= " AND prioridad = ?";
    $parametros[] = $prioridad;
    $tipos .= "s";
}

$sql .= " ORDER BY fecha_registro DESC";

// Preparar consulta.
$stmt = $conexion->prepare($sql);

if (!$stmt) {
    exit("Error al preparar la consulta.");
}

// Vincular los filtros.
if (!empty($parametros)) {
    $stmt->bind_param($tipos, ...$parametros);
}

// Ejecutar consulta.
if (!$stmt->execute()) {
    exit("Error al consultar las incidencias.");
}

$resultado = $stmt->get_result();
$total = $resultado->num_rows;

// Clases visuales para prioridades y estados.
$clases_prioridad = [
    "Baja" => "prioridad-baja",
    "Media" => "prioridad-media",
    "Alta" => "prioridad-alta",
    "Crítica" => "prioridad-critica"
];

$clases_estado = [
    "Pendiente" => "estado-pendiente",
    "En proceso" => "estado-en-proceso",
    "Resuelta" => "estado-resuelta"
];
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Consultar incidencias</title>

    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/listar.css">
</head>

<body>

<main class="contenedor-listado">

    <header class="encabezado-listado">
        <h1>Incidencias registradas</h1>
        <p>Consulta y administra los reportes tecnológicos.</p>

        <?php if ($es_admin): ?>
            <p>
                Sesión iniciada como:
                <strong><?= escapar($_SESSION["admin_usuario"] ?? "Administrador") ?></strong>
            </p>
            <a class="boton-secundario" href="../logout.php">
                Cerrar sesión
            </a>
        <?php else: ?>
            <p>
                <a class="boton-secundario" href="../login.php">
                    Iniciar sesión como administrador
                </a>
            </p>
        <?php endif; ?>
    </header>

    <!-- Mensajes de operaciones -->
    <?php if (isset($_GET["actualizada"])): ?>
        <div class="mensaje exito">
            Incidencia actualizada correctamente.
        </div>
    <?php endif; ?>

    <?php if (isset($_GET["eliminada"])): ?>
        <div class="mensaje exito">
            Incidencia eliminada correctamente.
        </div>
    <?php endif; ?>

    <!-- Filtros -->
    <form method="GET" class="filtros">

        <div>
            <label for="laboratorio">Laboratorio</label>
            <input
                type="text"
                id="laboratorio"
                name="laboratorio"
                placeholder="Ej. Laboratorio 1"
                value="<?= escapar($laboratorio) ?>"
            >
        </div>

        <div>
            <label for="estado">Estado</label>
            <select id="estado" name="estado">
                <option value="">Todos</option>

                <?php foreach ($estados_validos as $opcion): ?>
                    <option
                        value="<?= escapar($opcion) ?>"
                        <?= $estado === $opcion ? "selected" : "" ?>
                    >
                        <?= escapar($opcion) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div>
            <label for="prioridad">Prioridad</label>
            <select id="prioridad" name="prioridad">
                <option value="">Todas</option>

                <?php foreach ($prioridades_validas as $opcion): ?>
                    <option
                        value="<?= escapar($opcion) ?>"
                        <?= $prioridad === $opcion ? "selected" : "" ?>
                    >
                        <?= escapar($opcion) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="acciones-filtro">
            <button type="submit">Filtrar</button>

            <a class="boton-secundario" href="listar.php">
                Limpiar
            </a>
        </div>

    </form>

    <!-- Total de incidencias -->
    <p class="total">
        Incidencias encontradas:
        <strong><?= $total ?></strong>
    </p>

    <!-- Tabla de incidencias -->
    <div class="tabla-contenedor">

        <table class="tabla-incidencias">

            <thead>
                <tr>
                    <th>ID</th>
                    <th>Reportado por</th>
                    <th>Laboratorio</th>
                    <th>Equipo</th>
                    <th>Descripción</th>
                    <th>Prioridad</th>
                    <th>Estado</th>
                    <th>Fecha de registro</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>

                <?php if ($total > 0): ?>

                    <?php while ($incidencia = $resultado->fetch_assoc()): ?>

                        <tr>
                            <td><?= (int)$incidencia["id"] ?></td>

                            <td>
                                <?= escapar($incidencia["nombre_reporta"]) ?>
                            </td>

                            <td>
                                <?= escapar($incidencia["laboratorio"]) ?>
                            </td>

                            <td>
                                <?= escapar($incidencia["equipo_afectado"]) ?>
                            </td>

                            <td>
                                <?= escapar($incidencia["descripcion"]) ?>
                            </td>

                            <td>
                                <span class="etiqueta <?= escapar($clases_prioridad[$incidencia["prioridad"]] ?? "") ?>">
                                    <?= escapar($incidencia["prioridad"]) ?>
                                </span>
                            </td>

                            <td>
                                <span class="etiqueta <?= escapar($clases_estado[$incidencia["estado"]] ?? "") ?>">
                                    <?= escapar($incidencia["estado"]) ?>
                                </span>
                            </td>

                            <td>
                                <?= escapar($incidencia["fecha_registro"]) ?>
                            </td>

                            <!-- Acciones solo para administradores -->
                            <td>
                                <?php if ($es_admin): ?>
                                    <div class="acciones">

                                        <a
                                            class="btn-editar"
                                            href="editar.php?id=<?= (int)$incidencia["id"] ?>"
                                        >
                                            Editar
                                        </a>

                                        <a
                                            class="btn-eliminar"
                                            href="eliminar.php?id=<?= (int)$incidencia["id"] ?>"
                                        >
                                            Eliminar
                                        </a>

                                    </div>
                                <?php else: ?>
                                    <span>Solo administrador</span>
                                <?php endif; ?>
                            </td>
                        </tr>

                    <?php endwhile; ?>

                <?php else: ?>

                    <tr>
                        <td colspan="9" class="sin-resultados">
                            No se encontraron incidencias.
                        </td>
                    </tr>

                <?php endif; ?>

            </tbody>

        </table>

    </div>

    <a class="enlace volver" href="../index.php">
        Volver al inicio
    </a>

</main>

<script src="../js/script.js"></script>

</body>
</html>

<?php
$stmt->close();
$conexion->close();
?>

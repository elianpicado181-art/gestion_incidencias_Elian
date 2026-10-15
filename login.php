
<?php
session_start();
require_once "config/database.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $usuario = trim($_POST["usuario"] ?? "");
    $contrasena = $_POST["contrasena"] ?? "";

    if ($usuario === "" || $contrasena === "") {
        $error = "Completa todos los campos.";
    } else {
        $sql = "SELECT id, usuario, contrasena
                FROM usuarios
                WHERE usuario = ?
                LIMIT 1";

        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("s", $usuario);
        $stmt->execute();

        $resultado = $stmt->get_result();
        $admin = $resultado->fetch_assoc();

        if ($admin && password_verify($contrasena, $admin["contrasena"])) {
            session_regenerate_id(true);

            $_SESSION["admin_id"] = $admin["id"];
            $_SESSION["admin_usuario"] = $admin["usuario"];

            header("Location: index.php");
            exit;
        } else {
            $error = "Usuario o contraseña incorrectos.";
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

    <title>Inicio de sesión | Gestión de Incidencias</title>

    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/login.css">
</head>

<body>

    <main class="login">

        <h1>Acceso administrativo</h1>

        <p class="ayuda">
            Sistema de Gestión de Incidencias Tecnológicas
        </p>

        <?php if ($error !== ""): ?>
            <div class="error">
                <?= escapar($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">

            <label for="usuario">Usuario</label>

            <input
                type="text"
                id="usuario"
                name="usuario"
                maxlength="50"
                autocomplete="username"
                required
            >

            <label for="contrasena">Contraseña</label>

            <input
                type="password"
                id="contrasena"
                name="contrasena"
                autocomplete="current-password"
                required
            >

            <button type="submit">
                Iniciar sesión
            </button>

        </form>

        <div class="opcion-visitante">

            <p class="ayuda">
                ¿No eres administrador?
            </p>

            <a href="index.php" class="boton-visitante">
                Continuar como visitante
            </a>

        </div>

        <p class="ayuda">
            El acceso administrativo es exclusivo para personal autorizado.
        </p>

    </main>

</body>
</html>

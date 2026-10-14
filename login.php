```php
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

    <style>
        .login {
            max-width: 430px;
            margin: 60px auto;
            padding: 30px;
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            box-shadow: 0 12px 35px rgba(15, 23, 42, .09);
        }

        .login h1 {
            margin-top: 0;
            color: #1e3a8a;
            text-align: center;
        }

        .login button {
            width: 100%;
            margin-top: 24px;
        }

        .login .error {
            margin-top: 15px;
            padding: 12px;
            border-radius: 6px;
            background: #fee2e2;
            color: #991b1b;
        }

        .login .ayuda {
            color: #64748b;
            font-size: 13px;
            text-align: center;
        }

        .opcion-visitante {
            margin-top: 24px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
            text-align: center;
        }

        .boton-visitante {
            display: inline-block;
            padding: 12px 20px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            color: #1e3a8a;
            background: #fff;
            text-decoration: none;
            font-weight: 600;
            transition: background .2s, color .2s;
        }

        .boton-visitante:hover {
            background: #1e3a8a;
            color: #fff;
        }
    </style>
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
```
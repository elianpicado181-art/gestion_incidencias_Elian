
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Gestión de Incidencias Tecnológicas</title>

    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/inicio.css">

    <style>
        .acceso-administrativo {
            margin-top: 28px;
            padding-top: 22px;
            border-top: 1px solid #e2e8f0;
            text-align: center;
        }

        .acceso-administrativo p {
            color: #64748b;
            font-size: 14px;
            margin-bottom: 14px;
        }

        .boton-admin {
            display: inline-block;
            padding: 11px 22px;
            border: 1px solid #1e3a8a;
            border-radius: 7px;
            color: #1e3a8a;
            background: #ffffff;
            text-decoration: none;
            font-weight: 600;
            transition: background 0.2s, color 0.2s;
        }

        .boton-admin:hover {
            background: #1e3a8a;
            color: #ffffff;
        }
    </style>
</head>
<body>

    <main class="panel">

        <header class="encabezado">
            <h1>Gestión de Incidencias Tecnológicas</h1>

            <p>
                Sistema de registro y seguimiento de fallas
                tecnológicas en los laboratorios de computación.
            </p>
        </header>

        <h2 class="titulo-seccion">Panel principal</h2>

        <section class="tarjetas">

            <article class="tarjeta">
                <h3>Registrar incidencia</h3>

                <p>
                    Reporta problemas relacionados con computadoras,
                    teclados, proyectores y otros equipos tecnológicos.
                </p>

                <a class="boton" href="pages/registrar.php">
                    Ingresar como visitante
                </a>
            </article>

            <article class="tarjeta">
                <h3>Consultar incidencias</h3>

                <p>
                    Consulta las incidencias registradas y revisa
                    su prioridad y estado de atención.
                </p>

                <a class="boton" href="pages/listar.php">
                    Ver incidencias
                </a>
            </article>

        </section>

        <section class="acceso-administrativo">
            <h3>¿Eres administrador?</h3>

            <p>
                Inicia sesión para editar, eliminar y gestionar
                las incidencias reportadas.
            </p>

            <a class="boton-admin" href="login.php">
                Acceso administrativo
            </a>
        </section>

        <footer class="pie">
            Sistema académico de gestión de incidencias tecnológicas
        </footer>

    </main>

</body>
</html>

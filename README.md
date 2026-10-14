# Sistema de Gestión de Incidencias Tecnológicas

## 1. Descripción del proyecto

El Sistema de Gestión de Incidencias Tecnológicas es una aplicación web desarrollada para registrar y dar seguimiento a los problemas tecnológicos que se presentan en los laboratorios de computación de una institución universitaria.

Permite centralizar los reportes, identificar los equipos afectados y consultar el estado de atención de cada incidencia.

## 2. Objetivo general

Desarrollar una aplicación web que facilite el registro, consulta y seguimiento de incidencias tecnológicas, mejorando el control y la organización de los problemas reportados en los laboratorios de computación.

## 3. Funcionalidades principales

- Registro de incidencias tecnológicas.
- Consulta de las incidencias registradas.
- Edición y eliminación de incidencias por parte del administrador.
- Clasificación por prioridad: Baja, Media, Alta y Crítica.
- Seguimiento del estado: Pendiente, En proceso y Resuelta.
- Filtros por laboratorio, estado y prioridad.
- Registro automático de la fecha de creación.
- Inicio de sesión administrativo.
- Acceso de visitantes para registrar y consultar incidencias.

## 4. Tecnologías utilizadas

- **HTML5:** estructura de las páginas.
- **CSS3:** diseño y presentación visual.
- **PHP:** procesamiento y lógica del sistema.
- **MySQL:** almacenamiento de los datos.
- **MySQLi:** conexión y consultas a la base de datos.
- **XAMPP:** entorno de desarrollo local.
- **Git y GitHub:** control de versiones y publicación del proyecto.

## 5. Requisitos para ejecutar el proyecto

- Tener instalado XAMPP.
- Tener Apache y MySQL habilitados.
- Contar con un navegador web actualizado.
- Disponer de los archivos del proyecto y del respaldo SQL de la base de datos.

## 6. Instalación y configuración

**Paso 1. Copiar el proyecto**

Copiar la carpeta `gestion_incidencias_Elian` dentro del directorio `htdocs` de XAMPP.

**Paso 2. Iniciar los servicios**

Abrir el panel de control de XAMPP e iniciar Apache y MySQL.

**Paso 3. Configurar la base de datos**

1. Ingresar a `http://localhost/phpmyadmin/`.
2. Crear la base de datos `gestion_incidencias_elian`, si todavía no existe.
3. Seleccionar la base de datos e importar el archivo SQL incluido en el proyecto.
4. Verificar que la base de datos y sus tablas se hayan creado correctamente.

**Paso 4. Verificar la conexión**

Comprobar que el archivo `config/database.php` tenga los datos de conexión correspondientes a la configuración local de MySQL.

**Paso 5. Ejecutar el sistema**

Abrir en el navegador:

`http://localhost/gestion_incidencias_Elian/`

Desde la página principal se puede ingresar como visitante o acceder al inicio de sesión administrativo.

## 7. Autor

**Desarrollado por:** Elian Picado Fonseca

**Carrera:** Ingeniería en Sistemas

**Institución:** Universidad Internacional San Isidro Labrador (UISIL)

**Proyecto académico.**

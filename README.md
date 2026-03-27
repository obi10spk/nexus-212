[README.md](https://github.com/user-attachments/files/26304858/README.md)
[README.md](https://github.com/user-attachments/files/26303476/README.md)
# 212th Databank System - v1.0

Sistema centralizado de gestión para el Batallón de Ataque 212th. Diseñado para automatizar el control de personal, entrenamientos y expedientes disciplinarios.

## 🚀 Requisitos de Instalación
* **Servidor Web:** Apache (XAMPP / WAMP recomendado).
* **Lenguaje:** PHP 7.4 o superior.
* **Base de Datos:** MySQL / MariaDB.
* **Iconografía:** FontAwesome 6.0 (Cargado vía CDN).

## 🛠️ Configuración Inicial
1.  Importar el archivo SQL (proporcionado en la carpeta de documentación) en tu PHPMyAdmin.
2.  Configurar las credenciales de acceso en `ficheros/conexion.php`:
    ```php
    $conexion = mysqli_connect("localhost", "tu_usuario", "tu_pass", "tu_bd");
    ```
3.  Asegurarse de que el directorio tiene permisos de escritura para que PHP pueda procesar los formularios.

## 📦 Módulos Operativos
- **Suboficiales (SGT+):** Control de entrenamientos y misiones asistidas.
- **Cuerpo de Cabos:** Gestión de expedientes individuales y liderazgo.
- **Élites:** Seguimiento de capacitaciones y estados de rendimiento (Píldoras de colores).
- **Avisos:** Registro de infracciones y comportamiento.
- **Conquista:** Generador de reportes visuales.

## 🔐 Seguridad
El sistema utiliza un sistema de cookies (`acceso_212`) generado en el `index.html`. Sin esta cookie, el acceso a los archivos `.php` dentro de las carpetas de gestión está restringido para evitar intrusiones.

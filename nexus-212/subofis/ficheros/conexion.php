<?php
// Datos de Alwaysdata
$host = "mysql-unitybatallon212.alwaysdata.net"; // Cambia 'tuusuario'
$user = "unitybatallon212";               // Tu usuario de DB
$pass = "codycanarion212";             // Tu clave de la DB
$db   = "unitybatallon212_bd";

$conexion = mysqli_connect($host, $user, $pass, $db);

if (!$conexion) {
    die("ERROR: No se pudo conectar al núcleo de datos.");
}
?>
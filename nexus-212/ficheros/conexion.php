<?php
// Datos de Alwaysdata
$host = "mysql-unitybatallon212.alwaysdata.net"; 
$user = "unitybatallon212";               
$pass = "codycanarion212";             
$db   = "unitybatallon212_bd";

$conexion = mysqli_connect($host, $user, $pass, $db);

if (!$conexion) {
    die("ERROR: No se pudo conectar al núcleo de datos.");
}
?>
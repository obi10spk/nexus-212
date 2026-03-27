<?php
include '../ficheros/conexion.php';

$r = $_POST['rango'];
$n = $_POST['nombre'];
$d = $_POST['discord'];
$f = date('Y-m-d');

$sql = "INSERT INTO miembros_sgt (rango_actual, nombre, id_discord, fecha_rango_actual, ent_mes_anterior, ent_mes_actual, mis_mes_anterior, mis_mes_actual, resumen_mensual) 
        VALUES ('$r', '$n', '$d', '$f', 0, 0, 0, 0, '-')";

mysqli_query($conexion, $sql);
?>
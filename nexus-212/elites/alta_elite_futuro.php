<?php
include '../ficheros/conexion.php';
$g = $_POST['grupo'];
$n = $_POST['nombre'];

$sql = "INSERT INTO futuros_elites (grupo, nombre, mes_anterior, mes_actual, resumen_mes) 
        VALUES ('$g', '$n', '-', '-', '-')";
mysqli_query($conexion, $sql);
?>
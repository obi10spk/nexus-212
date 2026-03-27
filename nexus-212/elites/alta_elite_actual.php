<?php
include '../ficheros/conexion.php';
$g = $_POST['grupo'];
$nic = $_POST['nom_ic'];
$nd = $_POST['nom_dis'];

// Insertamos con valores iniciales por defecto (SI en activo y guion en el resto)
$sql = "INSERT INTO miembros_elites (grupo, nombre_ic, nombre_dis, activo, mes_anterior, mes_actual, resumen_mes) 
        VALUES ('$g', '$nic', '$nd', 'SI', '-', '-', '-')";
mysqli_query($conexion, $sql);
?>
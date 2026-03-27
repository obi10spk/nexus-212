<?php
include '../ficheros/conexion.php';
$n = $_POST['nombre']; $t = $_POST['tipo']; $m = $_POST['motivo']; $f = date('Y-m-d');
mysqli_query($conexion, "INSERT INTO avisos (fecha, nombre, tipo, motivo) VALUES ('$f', '$n', '$t', '$m')");
?>
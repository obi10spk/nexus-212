<?php
include '../ficheros/conexion.php';
$id = $_POST['id'];
mysqli_query($conexion, "DELETE FROM futuros_elites WHERE id = '$id'");
?>
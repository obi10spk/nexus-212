<?php
include '../ficheros/conexion.php';
$id = $_POST['id'];
mysqli_query($conexion, "DELETE FROM miembros_elites WHERE id = '$id'");
?>
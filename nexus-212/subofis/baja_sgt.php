<?php
include '../ficheros/conexion.php';
$id = $_POST['id_eliminar'];
mysqli_query($conexion, "DELETE FROM miembros_sgt WHERE id = '$id'");
?>
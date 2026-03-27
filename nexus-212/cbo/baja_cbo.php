<?php
include '../ficheros/conexion.php';

if (isset($_POST['id_eliminar'])) {
    $id = mysqli_real_escape_string($conexion, $_POST['id_eliminar']);
    
    $sql = "DELETE FROM miembros_cbo WHERE id = '$id'";
    
    if (mysqli_query($conexion, $sql)) {
        echo "Baja procesada";
    } else {
        echo "Error en la ejecución de la baja";
    }
}
?>
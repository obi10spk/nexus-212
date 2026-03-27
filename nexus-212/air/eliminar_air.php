<?php
include '../ficheros/conexion.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_eliminar'])) {
    $id = $_POST['id_eliminar'];
    $query = "DELETE FROM miembros_air WHERE id = $id";
    mysqli_query($conexion, $query);
    header("Location: air_gestion.php?status=deleted");
}
?>


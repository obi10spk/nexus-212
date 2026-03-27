<?php
include '../ficheros/conexion.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ids = $_POST['id']; $fechas = $_POST['fecha']; $nombres = $_POST['nombre'];
    $tipos = $_POST['tipo']; $motivos = $_POST['motivo'];

    for ($i = 0; $i < count($ids); $i++) {
        $sql = "UPDATE avisos SET fecha='{$fechas[$i]}', nombre='{$nombres[$i]}', tipo='{$tipos[$i]}', motivo='{$motivos[$i]}' WHERE id='{$ids[$i]}'";
        mysqli_query($conexion, $sql);
    }
    header("Location: index.php");

    mysqli_query($conexion, "UPDATE control_cambios SET ultima_modificacion = NOW() WHERE seccion = 'avisos'");

    header("Location: index.php?status=ok");
}

?>
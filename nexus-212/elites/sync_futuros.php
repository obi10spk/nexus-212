<?php
include '../ficheros/conexion.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ids = $_POST['id'];
    $nombres = $_POST['nombre'];
    $ants = $_POST['ant'];
    $actus = $_POST['actu'];
    $res = $_POST['res'];

    for ($i = 0; $i < count($ids); $i++) {
        $sql = "UPDATE futuros_elites SET 
                nombre = '{$nombres[$i]}', 
                mes_anterior = '{$ants[$i]}', 
                mes_actual = '{$actus[$i]}', 
                resumen_mes = '{$res[$i]}' 
                WHERE id = '{$ids[$i]}'";
        mysqli_query($conexion, $sql);
    }
    header("Location: elites_futuros.php?status=ok");
    mysqli_query($conexion, "UPDATE control_cambios SET ultima_modificacion = NOW() WHERE seccion = 'elites_fut'");

    header("Location: elites_futuros.php?status=ok");

}


?>
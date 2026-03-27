<?php
include '../ficheros/conexion.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ids = $_POST['id'];
    $nics = $_POST['nom_ic'];
    $nds = $_POST['nom_dis'];
    $acts = $_POST['activo'];
    $ants = $_POST['ant'];
    $actus = $_POST['actu'];
    $res = $_POST['res'];

    for ($i = 0; $i < count($ids); $i++) {
        $sql = "UPDATE miembros_elites SET 
                nombre_ic = '{$nics[$i]}', 
                nombre_dis = '{$nds[$i]}', 
                activo = '{$acts[$i]}', 
                mes_anterior = '{$ants[$i]}', 
                mes_actual = '{$actus[$i]}', 
                resumen_mes = '{$res[$i]}' 
                WHERE id = '{$ids[$i]}'";
        mysqli_query($conexion, $sql);
    }
    header("Location: elites_actuales.php?status=ok");
    mysqli_query($conexion, "UPDATE control_cambios SET ultima_modificacion = NOW() WHERE seccion = 'elites_act'");

    header("Location: elites_actuales.php?status=ok");
}

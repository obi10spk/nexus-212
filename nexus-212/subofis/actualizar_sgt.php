<?php
include '../ficheros/conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ids = $_POST['id'];
    $rangos = $_POST['rango_act'];
    $fechas = $_POST['f_rango_act'];
    $nombres = $_POST['nombre'];
    $discords = $_POST['discord'];
    $steams = $_POST['steam'];
    $e_ants = $_POST['e_ant'];
    $e_acts = $_POST['e_act'];
    $m_ants = $_POST['m_ant'];
    $m_acts = $_POST['m_act'];
    $r_ants = $_POST['rango_ant'];
    $resumenes = $_POST['resumen'];

    for ($i = 0; $i < count($ids); $i++) {
        $sql = "UPDATE miembros_sgt SET 
                rango_actual = '{$rangos[$i]}', 
                fecha_rango_actual = '{$fechas[$i]}', 
                nombre = '{$nombres[$i]}', 
                id_discord = '{$discords[$i]}', 
                steam_64 = '{$steams[$i]}', 
                ent_mes_anterior = '{$e_ants[$i]}', 
                ent_mes_actual = '{$e_acts[$i]}', 
                mis_mes_anterior = '{$m_ants[$i]}', 
                mis_mes_actual = '{$m_acts[$i]}', 
                rango_anterior = '{$r_ants[$i]}', 
                resumen_mensual = '{$resumenes[$i]}' 
                WHERE id = '{$ids[$i]}'";
        mysqli_query($conexion, $sql);
    }
    header("Location: sgt_gestion.php?save=1");
}

mysqli_query($conexion, "UPDATE control_cambios SET ultima_modificacion = NOW() WHERE seccion = 'subofis'");

    header("Location: sgt_gestion.php?status=success");
?>

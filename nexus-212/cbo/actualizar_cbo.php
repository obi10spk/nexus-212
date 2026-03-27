<?php
include '../ficheros/conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ids = $_POST['id'];
    $rangos = $_POST['rango_act'];
    $nombres = $_POST['nombre'];
    $discords = $_POST['discord'];
    $ascensos = $_POST['ascenso'];
    $liderados = $_POST['liderados'];
    $asistidas = $_POST['asistidas'];
    $resumenes = $_POST['resumen'];

    // 1. ACTUALIZACIÓN DE MIEMBROS
    for ($i = 0; $i < count($ids); $i++) {
        $resumen_limpio = mysqli_real_escape_string($conexion, $resumenes[$i]);
        $nombre_limpio = mysqli_real_escape_string($conexion, $nombres[$i]);

        $sql = "UPDATE miembros_cbo SET 
                rango_actual = '{$rangos[$i]}', 
                nombre = '$nombre_limpio', 
                id_discord = '{$discords[$i]}', 
                ultimo_ascenso = '{$ascensos[$i]}', 
                ent_liderados = '{$liderados[$i]}', 
                misiones_asistidas = '{$asistidas[$i]}', 
                resumen_mensual = '$resumen_limpio' 
                WHERE id = '{$ids[$i]}'";
        
        mysqli_query($conexion, $sql);
    }

    // 2. ACTUALIZACIÓN DEL CRONÓMETRO (Sección 'cbo')
    mysqli_query($conexion, "UPDATE control_cambios SET ultima_modificacion = NOW() WHERE seccion = 'cbo'");

    // 3. REGRESO CON ROMPE-CACHÉ
    header("Location: cbo_gestion.php?v=" . time());
    exit();
}
?>
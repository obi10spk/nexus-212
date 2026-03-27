<?php
include '../ficheros/conexion.php';
session_start();



if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recogemos los arrays de la tabla
    $ids = $_POST['id'];
    $nombres = $_POST['nombre'];
    $discords = $_POST['discord'];
    $avisos1 = $_POST['aviso1'];
    $avisos2 = $_POST['aviso2'];
    $resumenes = $_POST['resumen'];

    // Iniciamos el bucle de actualización táctica
    for ($i = 0; $i < count($ids); $i++) {
        $id = mysqli_real_escape_string($conexion, $ids[$i]);
        $nom = mysqli_real_escape_string($conexion, $nombres[$i]);
        $dis = mysqli_real_escape_string($conexion, $discords[$i]);
        $res = mysqli_real_escape_string($conexion, $resumenes[$i]);

        // LÓGICA DE FECHAS (Si el campo está vacío, enviamos NULL a SQL)
        $a1 = !empty($avisos1[$i]) ? "'" . mysqli_real_escape_string($conexion, $avisos1[$i]) . "'" : "NULL";
        $a2 = !empty($avisos2[$i]) ? "'" . mysqli_real_escape_string($conexion, $avisos2[$i]) . "'" : "NULL";

        // Ejecutamos la actualización de la fila
        $query = "UPDATE miembros_air SET 
                  nombre = '$nom', 
                  id_discord = '$dis', 
                  aviso_1 = $a1, 
                  aviso_2 = $a2, 
                  resumen = '$res' 
                  WHERE id = $id";

        if (!mysqli_query($conexion, $query)) {
            echo "Error en la transmisión de datos para el ID $id: " . mysqli_error($conexion);
            exit;
        }
    }
    mysqli_query($conexion, "UPDATE control_cambios SET ultima_modificacion = NOW() WHERE seccion = 'air'");

    header("Location: air_gestion.php?status=ok");
    // Volvemos a la gestión con señal de éxito
    header("Location: air_gestion.php?status=updated");
    exit;


} else {
    // Si intentan entrar sin enviar el formulario, denegamos el acceso
    header("Location: air_gestion.php");
    exit;
}


?>


<?php
include '../ficheros/conexion.php';

// Verificamos que lleguen los datos
if (isset($_POST['nombre'])) {
    $rango = mysqli_real_escape_string($conexion, $_POST['rango']);
    $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
    $discord = mysqli_real_escape_string($conexion, $_POST['discord']);
    $fecha_hoy = date('Y-m-d');

    // Insertamos siguiendo la estructura de tu imagen
    $sql = "INSERT INTO miembros_cbo (rango_actual, nombre, id_discord, ultimo_ascenso, ent_liderados, misiones_asistidas, resumen_mensual) 
            VALUES ('$rango', '$nombre', '$discord', '$fecha_hoy', 0, 0, 'Pendiente')";

    if (mysqli_query($conexion, $sql)) {
        echo "Alta procesada con éxito";
    } else {
        // Esto te dirá el error exacto si vuelve a fallar
        echo "Error: " . mysqli_error($conexion);
    }
}
?>
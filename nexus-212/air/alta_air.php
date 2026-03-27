<?php
include '../ficheros/conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $categoria = $_POST['categoria'];
    $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
    $discord = mysqli_real_escape_string($conexion, $_POST['discord']);
    
    // Insertamos al nuevo miembro. Avisos en NULL por defecto.
    $query = "INSERT INTO miembros_air (categoria, nombre, id_discord, aviso_1, aviso_2, resumen) 
              VALUES ('$categoria', '$nombre', '$discord', NULL, NULL, '-')";
    
    if(mysqli_query($conexion, $query)) {
        header("Location: air_gestion.php?status=success");
    } else {
        echo "Error en la transmisión de datos: " . mysqli_error($conexion);
    }
}
?>


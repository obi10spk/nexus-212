<?php
include 'conexion.php';
header('Content-Type: application/json');

// Recibimos la contraseña enviada desde el JS
$data = json_decode(file_get_contents('php://input'), true);
$password_cliente = isset($data['pass']) ? $data['pass'] : '';

// Consultamos la contraseña guardada en la base de datos
$sql = "SELECT password_maestra FROM seguridad_acceso WHERE id = 1";
$resultado = mysqli_query($conexion, $sql);
$fila = mysqli_fetch_assoc($resultado);

if ($fila && $password_cliente === $fila['password_maestra']) {
    // ÉXITO: La contraseña coincide
    echo json_encode(["success" => true]);
} else {
    // ERROR: Intento de intrusión
    echo json_encode(["success" => false, "mensaje" => "CÓDIGO DE ENCRIPTACIÓN INCORRECTO"]);
}
?>
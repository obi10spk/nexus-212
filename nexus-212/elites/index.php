<?php
session_start();
// Seguridad de acceso
if (!isset($_COOKIE['acceso_212']) || $_COOKIE['acceso_212'] !== 'autorizado') {
    header("Location: ../index.html"); exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>212 | Selección de Élite</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background: #fff; font-family: Calibri, sans-serif; display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100vh; margin: 0; }
        .BarraNavegar { background: #000; padding: 0 40px; color: #fff; border-bottom: 3px solid #FF9B00; position: fixed; top: 0; width: 100%; display: flex; justify-content: space-between; align-items: center; height: 60px; box-sizing: border-box; }
        .L2 { color: #FF9B00; font-weight: bold; }
        
        .menu-container { display: flex; gap: 30px; }
        .card-elite {
            width: 300px; height: 200px; border: 3px solid #000; display: flex; flex-direction: column;
            align-items: center; justify-content: center; cursor: pointer; transition: 0.3s;
            text-decoration: none; color: #000; font-weight: bold; text-transform: uppercase;
        }
        .card-actuales { background: #FFD966; } /* Amarillo Elite */
        .card-futuros { background: #f8d7da; border-color: #c0392b; } /* Rojo Capacitación */
        .card-elite:hover { transform: scale(1.05); box-shadow: 10px 10px 0px #FF9B00; }
        .card-elite i { font-size: 50px; margin-bottom: 15px; }
    </style>
</head>
<body>
    <header class="BarraNavegar">
        <div><b>212th | </b><span class="L2">ELITE DATABANK</span></div>
        <label onclick="location.href='../index.html'" style="color:#FF9B00; cursor:pointer; font-weight:bold;">VOLVER</label>
    </header>

    <div class="menu-container">
        <a href="elites_actuales.php" class="card-elite card-actuales">
            <i class="fas fa-users"></i>
            Élites Aspirantes
        </a>
        <a href="elites_futuros.php" class="card-elite card-futuros">
            <i class="fas fa-graduation-cap"></i>
            Capacitaciones
        </a>
    </div>
</body>
</html>
<?php
include '../ficheros/conexion.php';
session_start();

$query = "SELECT * FROM futuros_elites ORDER BY FIELD(grupo, 'ASE', 'APE', 'RNE', 'ARC'), nombre ASC";
$resultado = mysqli_query($conexion, $query);
$aspirantes = [];

// Consulta para obtener la fecha
$res_tiempo = mysqli_query($conexion, "SELECT ultima_modificacion FROM control_cambios WHERE seccion = 'elites_fut'");
$dato_tiempo = mysqli_fetch_assoc($res_tiempo);
$ultima_pila = date("d/m/Y | H:i:s", strtotime($dato_tiempo['ultima_modificacion']));
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>212 | Capacitaciones</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background: #fff; font-family: Calibri, sans-serif; margin: 0; }
        .BarraNavegar { background: #000; padding: 0 40px; color: #fff; border-bottom: 3px solid #FF9B00; display: flex; justify-content: space-between; align-items: center; height: 60px; }
        .contenedor-excel { padding: 20px; display: flex; flex-direction: column; align-items: center; }
        .tabla-excel { border-collapse: collapse; width: 100%; max-width: 900px; border: 1.5px solid #000; }
        .tabla-excel th { background: #FF0000; color: #fff; border: 1px solid #000; padding: 10px; }
        .tabla-excel td { border: 1px solid #000; padding: 5px; text-align: center; }
        
        .grp-ASE { background: #A9D08E; } .grp-APE { background: #FFE699; } .grp-RNE { background: #9BC2E6; } .grp-ARC { background: #F4B084; }
        
        .input-celda { width: 100%; border: none; text-align: center; background: transparent; }
        .btn-sync { background: #FF9B00; border: 2px solid #000; padding: 15px 50px; font-weight: bold; cursor: pointer; margin-top: 20px; }
        
        .centro-mando { display: flex; justify-content: center; gap: 20px; margin-top: 30px; width: 100%; }
        .col-mando { flex: 0 0 300px; border: 1.5px solid #000; padding: 15px; background: #fff; }
        /* Colores de estados para Futuros Élites */
        .status-Muy-Bien { background-color: #065f46 !important; color: #ffffff !important;  }
        .status-Bien { background-color: #026b35 !important; color: #000000 !important;  }
        .status-Mal { background-color: #ff0000 !important; color: #000000 !important; }
        .status-Inactivo { background-color: #333333 !important; color: #92400e !important;  }
        .status-Reduccion { background-color: #def50e !important; color: #374151 !important;  }
        .status-nada { background-color: #f9f9f9 !important; color: #000000; }

.select-pill {
    width: 90%;
    border: none;
    background: transparent;
    font-weight: bold;
    text-align: center;
    cursor: pointer;
    padding: 4px;
    font-family: inherit;
    appearance: none; /* Quita la flecha por defecto para look más limpio */
}
    </style>
</head>
<body>
    <header class="BarraNavegar">
        <div><b>CAPACITACIONES | </b><span style="color:#FF9B00">212th</span></div>
        <label onclick="location.href='index.php'" style="color:#FF9B00; cursor:pointer; font-weight:bold;">VOLVER</label>
    </header>

    <div class="contenedor-excel">
        <form action="sync_futuros.php" method="POST" style="width:100%; display:flex; flex-direction:column; align-items:center;">
            <table class="tabla-excel">
                <thead>
                    <tr>
                        <th>Grupo</th><th>Nombre Aspirante</th><th>Mes Ant.</th><th>Mes Act.</th><th>Resumen</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($resultado)): $aspirantes[] = $row; ?>
                    <tr>
                        <input type="hidden" name="id[]" value="<?php echo $row['id']; ?>">
                        <td class="grp-<?php echo $row['grupo']; ?>"><?php echo $row['grupo']; ?></td>
                        <td><input type="text" name="nombre[]" class="input-celda" value="<?php echo $row['nombre']; ?>"></td>
                        <td><input type="text" name="ant[]" class="input-celda" value="<?php echo $row['mes_anterior']; ?>"></td>
                        <td><input type="text" name="actu[]" class="input-celda" value="<?php echo $row['mes_actual']; ?>"></td>
                        <td class="status-<?php echo str_replace(' ', '-', $row['resumen_mes']); ?>">
    <select name="res_fut[]" class="select-pill" onchange="this.parentElement.className='status-'+this.value.replace(/\s+/g, '-')">
        <option value="-" <?php if($row['resumen_mes'] == '-') echo 'selected'; ?>>-</option>
        <option value="Muy Bien" <?php if($row['resumen_mes'] == 'Muy Bien') echo 'selected'; ?>>Muy Bien</option>
        <option value="Bien" <?php if($row['resumen_mes'] == 'Bien') echo 'selected'; ?>>Bien</option>
        <option value="Mal" <?php if($row['resumen_mes'] == 'Mal') echo 'selected'; ?>>Mal</option>
        <option value="Inactivo" <?php if($row['resumen_mes'] == 'Inactivo') echo 'selected'; ?>>Inactivo</option>
        <option value="Reduccion" <?php if($row['resumen_mes'] == 'Reduccion') echo 'selected'; ?>>Reduccion</option>
    </select>
</td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <div style="
                            background: #f8f9fa; 
                            border-left: 4px solid #FF9B00; 
                            padding: 10px 20px; 
                            margin: 10px 0; 
                            font-size: 12px; 
                            color: #555; 
                            font-weight: bold; 
                            display: inline-block;
                            box-shadow: 2px 2px 5px rgba(0,0,0,0.05);
                                                                        ">
                <i class="fas fa-history"></i> ÚLTIMO CAMBIO: 
                <span style="color: #000; margin-left: 5px;"><?php echo $ultima_pila; ?></span>
                </div>
            <button type="submit" class="btn-sync">GUARDAR CAPACITACIONES</button>
        </form>

        <div class="centro-mando">
            <div class="col-mando" style="border-top: 5px solid #27ae60;">
                <h4 style="margin:0 0 10px 0;">NUEVO ELITE</h4>
                <select id="a_grupo" style="width:100%; margin-bottom:5px;">
                    <option value="ASE">ASE</option><option value="APE">APE</option><option value="RNE">RNE</option><option value="ARC">ARC</option>
                </select>
                <input type="text" id="a_nombre" placeholder="Nombre IC" style="width:100%; margin-bottom:5px;">
                <button type="button" onclick="ejecutarAlta()" style="width:100%; background:#27ae60; color:#fff; border:none; padding:10px; font-weight:bold; cursor:pointer;">REGISTRAR</button>
            </div>
            <div class="col-mando" style="border-top: 5px solid #c0392b;">
                <h4 style="margin:0 0 10px 0;">RETIRAR ELITE</h4>
                <select id="id_baja" style="width:100%; margin-bottom:10px;">
                    <option value="">Seleccionar...</option>
                    <?php foreach($aspirantes as $a) echo "<option value='{$a['id']}'>{$a['nombre']}</option>"; ?>
                </select>
                <button type="button" onclick="ejecutarBaja()" style="width:100%; background:#c0392b; color:#fff; border:none; padding:10px; font-weight:bold; cursor:pointer;">ELIMINAR</button>
            </div>
        </div>
    </div>

    <script>
    function ejecutarAlta() {
        const g = document.getElementById('a_grupo').value;
        const n = document.getElementById('a_nombre').value;
        fetch('alta_elite_futuro.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: `grupo=${g}&nombre=${n}`
        }).then(() => location.reload());
    }
    function ejecutarBaja() {
        const id = document.getElementById('id_baja').value;
        if(id && confirm("¿Retirar aspirante?")) {
            fetch('baja_elite_futuro.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: `id=${id}`
            }).then(() => location.reload());
        }
    }
    </script>
</body>
</html>
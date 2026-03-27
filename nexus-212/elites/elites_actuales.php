<?php
include '../ficheros/conexion.php';
session_start();

$query = "SELECT * FROM miembros_elites ORDER BY FIELD(grupo, 'AS', 'AP', 'RN'), nombre_ic ASC";
$resultado = mysqli_query($conexion, $query);
$miembros = [];

// Consulta para obtener la fecha
$res_tiempo = mysqli_query($conexion, "SELECT ultima_modificacion FROM control_cambios WHERE seccion = 'elites_act'");
$dato_tiempo = mysqli_fetch_assoc($res_tiempo);
$ultima_pila = date("d/m/Y | H:i:s", strtotime($dato_tiempo['ultima_modificacion']));

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>212 | Élites Actuales</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background: #fff; font-family: Calibri, sans-serif; margin: 0; }
        .BarraNavegar { background: #000; padding: 0 40px; color: #fff; border-bottom: 3px solid #FF9B00; display: flex; justify-content: space-between; align-items: center; height: 60px; }
        .contenedor-excel { padding: 20px; display: flex; flex-direction: column; align-items: center; }
        .tabla-excel { border-collapse: collapse; width: 100%; max-width: 1100px; border: 1.5px solid #000; }
        .tabla-excel th { background: #FFD966; border: 1px solid #000; padding: 10px; }
        .tabla-excel td { border: 1px solid #000; padding: 5px; text-align: center; }
        
        /* Colores de Grupo */
        .grp-AS { background: #A9D08E; } .grp-AP { background: #FFE699; } .grp-RN { background: #9BC2E6; }
        
        .input-celda { width: 100%; border: none; text-align: center; background: transparent; }
        
        /* Panel Nexus */
        .centro-mando { display: flex; justify-content: center; gap: 20px; margin-top: 30px; width: 100%; }
        .col-mando { flex: 0 0 300px; border: 1.5px solid #000; padding: 15px; background: #fff; }
        .btn-sync { background: #FF9B00; border: 2px solid #000; padding: 15px 50px; font-weight: bold; cursor: pointer; margin-top: 20px;}
        .btn-mando { width: 100%; padding: 10px; border: none; color: #fff; font-weight: bold; cursor: pointer; margin-top: 5px; }
        .status-Bien { background-color: #009447 !important; color: #030303 !important;}
.status-Mal { background-color: #fc0000 !important; color: #000000 !important;}
.status-Regular { background-color: #9c7d00 !important; color: #000000 !important; }
.status-Inactivo { background-color: #4d4d4d !important; color: #9c0006 !important;}
.status-nada { background-color: #f9f9f9 !important; color: #000000; }

.select-pill-elite {
    width: 95%;
    border: none;
    background: transparent;
    font-weight: bold;
    text-align: center;
    cursor: pointer;
    padding: 5px;
    font-family: inherit;
    appearance: none;
}
    </style>
</head>
<body>
    <header class="BarraNavegar">
        <div><b>ÉLITES | </b><span style="color:#FF9B00">ACTUALES</span></div>
        <label onclick="location.href='index.php'" style="color:#FF9B00; cursor:pointer; font-weight:bold;">VOLVER</label>
    </header>

    <div class="contenedor-excel">
        <form action="sync_actuales.php" method="POST" style="width:100%; display:flex; flex-direction:column; align-items:center;">
            <table class="tabla-excel">
                <thead>
                    <tr>
                        <th>Grupo</th><th>Nombre IC</th><th>Nombre Discord</th><th>Activo</th><th>Mes Ant.</th><th>Mes Act.</th><th>Resumen</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($resultado)): $miembros[] = $row; ?>
                    <tr>
                        <input type="hidden" name="id[]" value="<?php echo $row['id']; ?>">
                        <td class="grp-<?php echo $row['grupo']; ?>"><?php echo $row['grupo']; ?></td>
                        <td><input type="text" name="nom_ic[]" class="input-celda" value="<?php echo $row['nombre_ic']; ?>"></td>
                        <td><input type="text" name="nom_dis[]" class="input-celda" value="<?php echo $row['nombre_dis']; ?>"></td>
                        <td><input type="text" name="activo[]" class="input-celda" value="<?php echo $row['activo']; ?>"></td>
                        <td><input type="text" name="ant[]" class="input-celda" value="<?php echo $row['mes_anterior']; ?>"></td>
                        <td><input type="text" name="actu[]" class="input-celda" value="<?php echo $row['mes_actual']; ?>"></td>
                        <td class="status-<?php echo ($row['resumen_mes'] == '-' || empty($row['resumen_mes'])) ? 'nada' : $row['resumen_mes']; ?>">
    <select name="res[]" class="select-pill-elite" onchange="this.parentElement.className='status-'+this.value">
        <option value="-" <?php if($row['resumen_mes'] == '-') echo 'selected'; ?>>-</option>
        <option value="Bien" <?php if($row['resumen_mes'] == 'Bien') echo 'selected'; ?>>Bien</option>
        <option value="Regular" <?php if($row['resumen_mes'] == 'Regular') echo 'selected'; ?>>Regular</option>
        <option value="Mal" <?php if($row['resumen_mes'] == 'Mal') echo 'selected'; ?>>Mal</option>
        <option value="Inactivo" <?php if($row['resumen_mes'] == 'Inactivo') echo 'selected'; ?>>Inactivo</option>
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
            <button type="submit" class="btn-sync">GUARDAR CAMBIOS ASPIRANTES</button>
        </form>

        <div class="centro-mando">
            <div class="col-mando" style="border-top: 5px solid #27ae60;">
                <h4 style="margin:0 0 10px 0;">ALTA ASPIRANTE</h4>
                <select id="a_grupo" style="width:100%; margin-bottom:5px;">
                    <option value="AS">AS</option><option value="AP">AP</option><option value="RN">RN</option>
                </select>
                <input type="text" id="a_nom_ic" placeholder="Nombre IC" style="width:100%; margin-bottom:5px;">
                <input type="text" id="a_nom_dis" placeholder="Discord" style="width:100%; margin-bottom:5px;">
                <button type="button" onclick="ejecutarAlta()" class="btn-mando" style="background:#27ae60;">INCORPORAR</button>
            </div>
            <div class="col-mando" style="border-top: 5px solid #c0392b;">
                <h4 style="margin:0 0 10px 0;">BAJA ASPIRANTE</h4>
                <select id="id_baja" style="width:100%; margin-bottom:10px;">
                    <option value="">Seleccionar...</option>
                    <?php foreach($miembros as $m) echo "<option value='{$m['id']}'>{$m['nombre_ic']}</option>"; ?>
                </select>
                <button type="button" onclick="ejecutarBaja()" class="btn-mando" style="background:#c0392b;">ELIMINAR</button>
            </div>
        </div>
    </div>

    <script>
    function ejecutarAlta() {
        const g = document.getElementById('a_grupo').value;
        const nic = document.getElementById('a_nom_ic').value;
        const nd = document.getElementById('a_nom_dis').value;
        fetch('alta_elite_actual.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: `grupo=${g}&nom_ic=${nic}&nom_dis=${nd}`
        }).then(() => location.reload());
    }
    function ejecutarBaja() {
        const id = document.getElementById('id_baja').value;
        if(id && confirm("¿Eliminar Élite?")) {
            fetch('baja_elite_actual.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: `id=${id}`
            }).then(() => location.reload());
        }
    }
    </script>
</body>
</html>
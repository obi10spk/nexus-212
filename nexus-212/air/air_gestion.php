<?php
include '../ficheros/conexion.php';
session_start();


// 1. CONSULTA JERÁRQUICA ACTUALIZADA (Comandantes > Oficiales > Suboficiales > Tropas)
$query = "SELECT * FROM miembros_air ORDER BY FIELD(categoria, 'Comandantes', 'Oficiales', 'Suboficiales', 'Tropas'), nombre ASC";
$resultado = mysqli_query($conexion, $query);
$lista_bajas = mysqli_query($conexion, $query); // Para el desplegable de expulsión

// Consulta para obtener la fecha
$res_tiempo = mysqli_query($conexion, "SELECT ultima_modificacion FROM control_cambios WHERE seccion = 'air'");
$dato_tiempo = mysqli_fetch_assoc($res_tiempo);
$ultima_pila = date("d/m/Y | H:i:s", strtotime($dato_tiempo['ultima_modificacion']));
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>212 | AIR Division</title>
    <link rel="stylesheet" href="../ficheros/estilo_menu.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* ESTÉTICA INMACULADA 212 */
        body { background: #fff; font-family: Calibri, sans-serif; margin: 0; padding-bottom: 50px; }
        .BarraNavegar { background: #000; border-bottom: 3px solid #FF9B00; display: flex; justify-content: space-between; align-items: center; height: 60px; padding: 0 20px; position: sticky; top: 0; z-index: 1000; }
        
        /* LAYOUT DUAL (PC/MÓVIL) */
        .layout-air { display: flex; gap: 20px; padding: 20px; align-items: flex-start; flex-wrap: wrap; }
        .seccion-tabla { flex: 3; min-width: 320px; width: 100%; }
        .seccion-info { flex: 1; display: flex; flex-direction: column; gap: 20px; min-width: 280px; position: sticky; top: 80px; }

        /* TABLA EXCEL STYLE */
        .tabla-wrapper { width: 100%; overflow-x: auto; border: 2px solid #000; }
        .tabla-air { width: 100%; border-collapse: collapse; font-size: 13px; min-width: 850px; background: #fff; }
        .tabla-air th { background: #000; color: #fff; padding: 12px; text-transform: uppercase; letter-spacing: 1px; }
        .tabla-air td { border: 1px solid #ccc; padding: 10px; text-align: center; }
        
        .cat-header { background: #f2f2f2 !important; font-weight: bold; text-align: left !important; padding-left: 15px !important; border-left: 6px solid #FF9B00 !important; font-size: 14px; color: #333; }

        /* INPUTS INVISIBLES */
        .input-tabla { width: 100%; border: none; background: transparent; text-align: center; font-family: inherit; font-size: 13px; color: #000; padding: 5px; }
        .input-tabla:focus { background: #fff3cd; outline: none; }

        /* BOTONES DE AVISO "LIVE" */
        .btn-aviso { padding: 6px 10px; border: 1px solid #000; cursor: pointer; font-size: 10px; font-weight: bold; text-transform: uppercase; width: 75px; }
        .btn-add { background: #fff; color: #000; }
        .btn-remove { background: #ffc7ce; color: #9c0006; border-color: #9c0006; }
        .fecha-aviso { display: block; font-size: 11px; font-weight: bold; color: #d35400; margin-top: 4px; }

        /* PANELES LATERALES */
        .panel-lateral { border: 2px solid #000; padding: 15px; background: #fff; box-shadow: 6px 6px 0px rgba(0,0,0,0.05); }
        .panel-lateral h3 { margin: 0 0 12px 0; font-size: 14px; border-bottom: 2px solid #FF9B00; padding-bottom: 5px; text-transform: uppercase; display: flex; align-items: center; gap: 8px; }
        
        /* TERMINALES DE ALTA Y BAJA */
        .t-alta { border-top: 5px solid #27ae60; }
        .t-baja { border-top: 5px solid #c0392b; }
        .input-panel { width: 100%; padding: 10px; border: 1px solid #000; margin-bottom: 8px; box-sizing: border-box; }
        .btn-accion { width: 100%; padding: 12px; border: none; color: #fff; font-weight: bold; text-transform: uppercase; cursor: pointer; transition: 0.2s; }
        .btn-verde { background: #27ae60; }
        .btn-rojo { background: #c0392b; }

        .btn-guardar-todo { background: #FF9B00; color: #000; border: 2px solid #000; padding: 20px; width: 100%; font-weight: bold; cursor: pointer; text-transform: uppercase; font-size: 16px; margin-top: 20px; }
        .btn-guardar-todo:hover { background: #000; color: #FF9B00; }

        /* RESPONSIVE */
        @media (max-width: 1024px) {
            .seccion-info { position: static; width: 100%; }
            .layout-air { flex-direction: column; }
        }
    </style>
</head>
<body>

<header class="BarraNavegar">
    <div id="cabecera"><span style="color:#fff; font-size:18px; font-weight:bold;">212TH | </span><span style="color:#FF9B00; font-size:18px; font-weight:bold;">AIR DIVISION</span></div>
    <label onclick="location.href='../index.html'" style="color:#FF9B00; cursor:pointer; font-weight:bold; font-size: 13px;"><i class="fas fa-arrow-left"></i> VOLVER AL MANDO</label>
</header>

<div class="layout-air">
    
    <div class="seccion-tabla">
        <form action="actualizar_air.php" method="POST">
            <div class="tabla-wrapper">
                <table class="tabla-air">
                    <thead>
                        <tr>
                            <th style="background:#00FF00; color:#000; width:120px;">Categoría</th>
                            <th>Nombre Tropas</th>
                            <th>ID Discord</th>
                            <th style="width:110px;">1º Aviso</th>
                            <th style="width:110px;">2º Aviso</th>
                            <th>Resumen / Notas</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $current_cat = "";
                        while($row = mysqli_fetch_assoc($resultado)): 
                            if($current_cat != $row['categoria']):
                                $current_cat = $row['categoria'];
                                echo "<tr><td colspan='6' class='cat-header'><i class='fas fa-chevron-right'></i> $current_cat</td></tr>";
                            endif;
                        ?>
                        <tr>
                            <input type="hidden" name="id[]" value="<?php echo $row['id']; ?>">
                            <td style="font-weight:bold; color:#666; font-size:11px;"><?php echo $row['categoria']; ?></td>
                            <td><input type="text" name="nombre[]" class="input-tabla" value="<?php echo $row['nombre']; ?>" style="font-weight:bold;"></td>
                            <td><input type="text" name="discord[]" class="input-tabla" value="<?php echo $row['id_discord']; ?>" style="color:#2980b9;"></td>
                            
                            <td id="td_a1_<?php echo $row['id']; ?>" style="<?php echo $row['aviso_1'] ? 'background:#fff3cd;' : ''; ?>">
                                <div id="ui_a1_<?php echo $row['id']; ?>">
                                    <?php if($row['aviso_1']): ?>
                                        <button type="button" class="btn-aviso btn-remove" onclick="gestionarAviso(<?php echo $row['id']; ?>, 1, 'quitar')">Quitar</button>
                                        <span class="fecha-aviso"><?php echo date('d/m/Y', strtotime($row['aviso_1'])); ?></span>
                                    <?php else: ?>
                                        <button type="button" class="btn-aviso btn-add" onclick="gestionarAviso(<?php echo $row['id']; ?>, 1, 'poner')">Añadir</button>
                                    <?php endif; ?>
                                </div>
                                <input type="hidden" name="aviso1[]" id="val_a1_<?php echo $row['id']; ?>" value="<?php echo $row['aviso_1']; ?>">
                            </td>

                            <td id="td_a2_<?php echo $row['id']; ?>" style="<?php echo $row['aviso_2'] ? 'background:#fff3cd;' : ''; ?>">
                                <div id="ui_a2_<?php echo $row['id']; ?>">
                                    <?php if($row['aviso_2']): ?>
                                        <button type="button" class="btn-aviso btn-remove" onclick="gestionarAviso(<?php echo $row['id']; ?>, 2, 'quitar')">Quitar</button>
                                        <span class="fecha-aviso"><?php echo date('d/m/Y', strtotime($row['aviso_2'])); ?></span>
                                    <?php else: ?>
                                        <button type="button" class="btn-aviso btn-add" onclick="gestionarAviso(<?php echo $row['id']; ?>, 2, 'poner')">Añadir</button>
                                    <?php endif; ?>
                                </div>
                                <input type="hidden" name="aviso2[]" id="val_a2_<?php echo $row['id']; ?>" value="<?php echo $row['aviso_2']; ?>">
                            </td>

                            <td>
                            <select name="resumen[]" class="select-resumen" style="
                            width: 100%; 
                            border: none; 
        background: transparent; 
        font-weight: bold; 
        text-align: center; 
        cursor: pointer;
        color: <?php 
            // Color dinámico según el estado actual
            if($row['resumen'] == 'ACTIVO') echo '#27ae60';
            elseif($row['resumen'] == 'INACTIVO') echo '#c0392b';
            elseif($row['resumen'] == 'AVISO') echo '#f39c12';
            else echo '#666'; 
        ?>;">
        
        <option value="-" <?php if($row['resumen'] == '-') echo 'selected'; ?>>- Seleccionar -</option>
        <option value="ACTIVO" style="background-color: #27ae60;" <?php if($row['resumen'] == 'ACTIVO') echo 'selected'; ?>>ACTIVO</option>
        <option value="AVISO" style="background-color: #f39c12;" <?php if($row['resumen'] == 'AVISO') echo 'selected'; ?>>AVISO</option>
        <option value="INACTIVO" style="background-color: #c0392b;" <?php if($row['resumen'] == 'INACTIVO') echo 'selected'; ?>>INACTIVO</option>
    </select>
</td>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
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
            <button type="submit" class="btn-guardar-todo">Guardar cambios</button>
        </form>
    </div>

    <div class="seccion-info">
        <div class="panel-lateral">
            <h3><i class="fas fa-gavel"></i> Reglas de AIR</h3>
            <ul style="padding-left:15px; font-size:12px; line-height:1.6;">
                <li>Activity check penúltimo fin de semana del mes.</li>
                <li>Mínimo 1 TRY mensual reglamentario.</li>
                <li style="color:#c0392b; font-weight:bold;">2º Aviso sin respuesta = EXPULSIÓN.</li>
            </ul>
        </div>

        <div class="panel-lateral">
            <h3><i class="fas fa-book" style="color:#FF9B00;"></i> Protocolos AIR</h3>
            <ul style="padding-left:18px; font-size:12px; line-height:1.7; margin:0;">
                <li>Activity check penúltimo fin de semana del mes.</li>
                <li>Mínimo 1 TRY mensual obligatorio.</li>
                <li style="color:#c0392b; font-weight:bold;">2º Aviso sin respuesta = EXPULSIÓN.</li>
            </ul>
        </div>

        <div class="panel-lateral t-alta">
            <h3><i class="fas fa-user-plus" style="color:#27ae60;"></i> Alta de Personal</h3>
            <form action="alta_air.php" method="POST">
                <select name="categoria" class="input-panel" required>
                    <option value="Tropas">Tropas</option>
                    <option value="Suboficiales">Suboficiales</option>
                    <option value="Oficiales">Oficiales</option>
                    <option value="Comandantes">Comandantes</option>
                </select>
                <input type="text" name="nombre" class="input-panel" placeholder="Nombre del Soldado" required>
                <input type="text" name="discord" class="input-panel" placeholder="ID Discord">
                <button type="submit" class="btn-accion btn-verde">Incorporar a la AIR</button>
            </form>
        </div>

        <div class="panel-lateral t-baja">
            <h3><i class="fas fa-user-slash" style="color:#c0392b;"></i> Expulsión Directa</h3>
            <form action="eliminar_air.php" method="POST" onsubmit="return confirm('¿Confirmar expulsión definitiva?')">
                <select name="id_eliminar" class="input-panel" required>
                    <option value="">Seleccionar personal...</option>
                    <?php 
                    mysqli_data_seek($lista_bajas, 0);
                    while($b = mysqli_fetch_assoc($lista_bajas)): ?>
                        <option value="<?php echo $b['id']; ?>"><?php echo "[".$b['categoria']."] ".$b['nombre']; ?></option>
                    <?php endwhile; ?>
                </select>
                <button type="submit" class="btn-accion btn-rojo">Ejecutar Licenciamiento</button>
            </form>
        </div>

    </div>
</div>

<script>
function gestionarAviso(id, num, accion) {
    const hiddenInput = document.getElementById('val_a' + num + '_' + id);
    const container = document.getElementById('ui_a' + num + '_' + id);
    const cell = document.getElementById('td_a' + num + '_' + id);
    
    if (accion === 'poner') {
        const hoy = new Date().toISOString().split('T')[0];
        const fechaShow = hoy.split('-').reverse().slice(0,2).join('/'); // DD/MM
        
        hiddenInput.value = hoy;
        cell.style.backgroundColor = "#fff3cd";
        container.innerHTML = `
            <button type="button" class="btn-aviso btn-remove" onclick="gestionarAviso(${id}, ${num}, 'quitar')">Quitar</button>
            <span class="fecha-aviso">${fechaShow} (Pendiente)</span>
        `;
    } else {
        hiddenInput.value = "";
        cell.style.backgroundColor = "transparent";
        container.innerHTML = `
            <button type="button" class="btn-aviso btn-add" onclick="gestionarAviso(${id}, ${num}, 'poner')">Añadir</button>
        `;
    }
}
</script>
</body>
</html>


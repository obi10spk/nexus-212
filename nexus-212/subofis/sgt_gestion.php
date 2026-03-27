<?php
include '../ficheros/conexion.php';
session_start();

// 1. SEGURIDAD DE ACCESO
if (!isset($_COOKIE['acceso_212']) || $_COOKIE['acceso_212'] !== 'autorizado') {
    header("Location: ../index.html"); 
    exit();
}

// 2. SISTEMA DE JERARQUÍA REGLAMENTARIO
$orden = "'MRL', 'CMD B', 'CMD MYR', 'CMD 1º', 'CMD 2º', 'CPT MYR', 'CPT 1º', 'CPT 2º', 'CPT', 'TTE 1º', 'TTE 2º', 'TTE', 'STTE', 'SGT MYR', 'SGT 1º', 'SGT 2º', 'SGT'";
$query = "SELECT * FROM miembros_sgt ORDER BY FIELD(rango_actual, $orden) ASC, nombre ASC";
$resultado = mysqli_query($conexion, $query);

// 3. INICIALIZACIÓN DE TOTALES (Para evitar errores de variable indefinida)
$t_ent_ant = 0; $t_ent_act = 0;
$t_mis_ant = 0; $t_mis_act = 0;

// Almacenamos los datos en un array para poder usarlos dos veces (tabla y select de bajas)
$miembros = [];
while($fila = mysqli_fetch_assoc($resultado)) {
    $miembros[] = $fila;
    // Sumamos los totales mientras procesamos la base de datos
    $t_ent_ant += (int)$fila['ent_mes_anterior'];
    $t_ent_act += (int)$fila['ent_mes_actual'];
    $t_mis_ant += (int)$fila['mis_mes_anterior'];
    $t_mis_act += (int)$fila['mis_mes_actual'];
}


// Consulta para obtener la fecha
$res_tiempo = mysqli_query($conexion, "SELECT ultima_modificacion FROM control_cambios WHERE seccion = 'subofis'");
$dato_tiempo = mysqli_fetch_assoc($res_tiempo);
$ultima_pila = ($dato_tiempo) ? date("d/m/Y | H:i:s", strtotime($dato_tiempo['ultima_modificacion'])) : "00/00/0000 | 00:00:00";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>212 | Databank SGT+</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* --- ESTILO BASE --- */
        body { background: #fff; color: #000; margin: 0; font-family: Calibri, sans-serif; }
        
        /* --- HEADER --- */
        .BarraNavegar { 
            background: #000; padding: 0 40px; color: #fff; border-bottom: 3px solid #FF9B00;
            display: flex; justify-content: space-between; align-items: center; height: 60px;
        }
        .L2 { color: #FF9B00; font-weight: bold; }
        .label-volver { color: #FF9B00; cursor: pointer; font-weight: bold; font-size: 12px; transition: 0.3s; }
        .label-volver:hover { color: #fff; }

        /* --- CONTENEDOR TABLA --- */
        .contenedor-excel { display: flex; flex-direction: column; align-items: center; padding: 20px; }
        .titulo-excel { 
            background: #fce4d6; border: 2px solid #000; padding: 10px; 
            font-weight: bold; margin-bottom: 20px; width: 90%; text-align: center; text-transform: uppercase;
            font-size: 30px;
        }

        .res-Bien { background-color: #c6efce !important; color: #006100; }      /* Verde */
        .res-Mal { background-color: #ffc7ce !important; color: #9c0006; }       /* Rojo */
        .res-Inactivo { background-color: #fff2cc !important; color: #996600; }  /* Amarillo/Oro */
        .res-Nuevo { background-color: #c9daf8 !important; color: #1155cc; }     /* Azul */
        .res-Reduccion { background-color: #d0e0e3 !important; color: #45818e; } /* Cian/Agua */    
        .res-Regular { background-color: #fce5cd !important; color: #b45f06; }    /* Naranja */
        .res-nada { background-color: #ffffff !important; color: #000000; }      /* Blanco (-) */

        .select-resumen {
            width: 100%;
            border: none;
            background: transparent;
            font-weight: bold;
            text-align: center;
            cursor: pointer;
            font-family: inherit;
            appearance: none; /* Limpia la flecha para un look más "pill" */
        }

        .tabla-scroll { width: 100%; overflow-x: auto; border: 1px solid #000; }
        .tabla-excel { border-collapse: collapse; width: 100%; min-width: 1100px; }
        .tabla-excel th, .tabla-excel td { border: 1px solid #000; padding: 5px; text-align: center; font-size: 18px; }
        
        .header-main { background: #FF9B00; }
        .header-entrenos { background: #ff0000; color: #fff; }
        .header-roles { background: #4472c4; color: #fff; }
        
        .input-celda { width: 100%; border: none; text-align: center; background: transparent; font-family: inherit; }

        /* --- CENTRO DE MANDO UNIFICADO (ESTILO IMAGEN) --- */
        .centro-mando-unificado { 
            display: flex; justify-content: center; align-items: flex-start; 
            gap: 20px; max-width: 1400px; margin: 30px auto; padding: 0 10px;
        }

        /* LADOS: ALTA Y BAJA */
        .col-formulario { 
            flex: 0 0 320px; background: #fff; border: 1.5px solid #000; 
            padding: 20px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); 
        }
        .panel-alta { border-top: 5px solid #27ae60; }
        .panel-baja { border-top: 5px solid #c0392b; }

        .titulo-mando { 
            display: flex; align-items: center; gap: 10px; margin-bottom: 15px;
            font-size: 13px; font-weight: bold; text-transform: uppercase; 
            border-bottom: 2px solid #FF9B00; padding-bottom: 8px;
        }

        /* CENTRO: TOTALES Y BOTÓN */
        .col-central { flex: 0 0 400px; display: flex; flex-direction: column; align-items: center; gap: 20px; }
        .bloque-totales-compacto { display: flex; gap: 15px; width: 100%; justify-content: center; }
        .cuadro-total-mini { 
            background: #fff; border: 2px solid #000; padding: 10px; width: 160px; 
            text-align: center; box-shadow: 4px 4px 0px rgba(0,0,0,0.05);
        }

        /* INPUTS Y BOTONES */
        .input-mando, .select-mando { width: 100%; padding: 10px; border: 1.5px solid #333; margin-bottom: 10px; box-sizing: border-box; }
        .btn-accion-mando { width: 100%; padding: 12px; border: none; color: #fff; font-weight: bold; text-transform: uppercase; cursor: pointer; }
        .btn-verde { background: #27ae60; }
        .btn-rojo { background: #c0392b; }
        .btn-sincronizar-principal { 
            width: 100%; background: #FF9B00; color: #000; border: 2px solid #000; 
            padding: 15px; font-weight: 900; cursor: pointer; text-transform: uppercase;
        }
        .btn-sincronizar-principal:hover { background: #000; color: #FF9B00; }

        /* COLORES RESUMEN */
        .res-Bien { background: #c6efce; } .res-Regular { background: #ffeb9c; } .res-Inactivo { background: #ffc7ce; }
    </style>
</head>
<body>

<header class="BarraNavegar">
    <div><b>212th | </b><span class="L2">DATABANK</span></div>
    <label class="label-volver" onclick="location.href='../index.html'"><i class="fas fa-arrow-left"></i> VOLVER AL HQ</label>
</header>

<div class="contenedor-excel">
    <div class="titulo-excel">TABLA DE ACTIVIDADES BATALLÓN DE ATAQUE 212th</div>

    <form action="actualizar_sgt.php" method="POST" style="width:100%;">
        <div class="tabla-scroll">
            <table class="tabla-excel">
                <thead>
                    <tr class="header-main">
                        <th>Rango actual</th><th><- Fecha</th><th>Nombre</th><th>ID Discord</th><th>STEAM 64</th>
                        <th colspan="2" class="header-entrenos">Entrenamientos</th>
                        <th colspan="2" class="header-roles">Roles PJ/OPs</th>
                        <th style="background:#a5a5a5;">Rango Ant.</th><th style="background:#a5a5a5;">Resumen</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($miembros as $row): ?>
                    <tr>
                        <input type="hidden" name="id[]" value="<?php echo $row['id']; ?>">
                        <td><input type="text" name="rango_act[]" class="input-celda" value="<?php echo $row['rango_actual']; ?>"></td>
                        <td><input type="date" name="f_rango_act[]" class="input-celda" value="<?php echo $row['fecha_rango_actual']; ?>"></td>
                        <td><input type="text" name="nombre[]" class="input-celda" style="font-weight:bold;" value="<?php echo $row['nombre']; ?>"></td>
                        <td><input type="text" name="discord[]" class="input-celda" value="<?php echo $row['id_discord']; ?>"></td>
                        <td><input type="text" name="steam[]" class="input-celda" value="<?php echo $row['steam_64']; ?>"></td>
                        <td style="background:#f2dcda;"><input type="number" name="e_ant[]" class="input-celda" value="<?php echo $row['ent_mes_anterior']; ?>"></td>
                        <td style="background:#f2dcda;"><input type="number" name="e_act[]" class="input-celda" value="<?php echo $row['ent_mes_actual']; ?>" style="font-weight:bold; color:red;"></td>
                        <td style="background:#d9e1f2;"><input type="number" name="m_ant[]" class="input-celda" value="<?php echo $row['mis_mes_anterior']; ?>"></td>
                        <td style="background:#d9e1f2;"><input type="number" name="m_act[]" class="input-celda" value="<?php echo $row['mis_mes_actual']; ?>" style="font-weight:bold; color:blue;"></td>
                        <td><input type="text" name="rango_ant[]" class="input-celda" value="<?php echo $row['rango_anterior']; ?>"></td>
                        <td class="res-<?php echo ($row['resumen_mensual'] == '-') ? 'nada' : $row['resumen_mensual']; ?>">
                            <select name="resumen[]" class="select-resumen" onchange="this.parentElement.className='res-'+(this.value == '-' ? 'nada' : this.value)">
                                <option value="-" <?php if($row['resumen_mensual'] == '-') echo 'selected'; ?>>-</option>
                                <option value="Bien" <?php if($row['resumen_mensual'] == 'Bien') echo 'selected'; ?>>Bien</option>
                                <option value="Mal" <?php if($row['resumen_mensual'] == 'Mal') echo 'selected'; ?>>Mal</option>
                                <option value="Inactivo" <?php if($row['resumen_mensual'] == 'Inactivo') echo 'selected'; ?>>Inactivo</option>
                                <option value="Nuevo" <?php if($row['resumen_mensual'] == 'Nuevo') echo 'selected'; ?>>Nuevo</option>
                                <option value="Reduccion" <?php if($row['resumen_mensual'] == 'Reduccion') echo 'selected'; ?>>Reduccion</option>
                                 <option value="Regular" <?php if($row['resumen_mensual'] == 'Regular') echo 'selected'; ?>>Regular</option>
                            </select>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="centro-mando-unificado">
            
            <div class="col-formulario panel-alta">
                <h3 class="titulo-mando"><i class="fas fa-user-plus" style="color:#27ae60;"></i> ALTA DE PERSONAL</h3>
                <select id="a_rango" class="select-mando">
                    <option value="MRL">Mariscal (MRL)</option>
                    <option value="CMD B">Comandante Batallón (CMD B)</option>
                    <option value="CMD MYR">Comandante Mayor (CMD MYR)</option>
                    <option value="CMD 1º">Comandante 1º (CMD 1º)</option>
                    <option value="CMD">Comandante (CMD)</option>
                    <option value="CPT MYR">Capitán Mayor (CPT MYR)</option>
                    <option value="CPT 1º">Capitán 1º (CPT 1º)</option>
                    <option value="CPT">Capitán (CPT)</option>
                    <option value="TTE 1º">Teniente 1º (TTE 1º)</option>
                    <option value="TTE 2º">Teniente 2º (TTE 2º)</option>
                    <option value="TTE">Teniente (TTE)</option>
                    <option value="STTE">Subteniente (STTE)</option>
                    <option value="SGT MYR">Sargento Mayor (SGT MYR)</option>
                    <option value="SGT 1º">Sargento 1º (SGT 1º)</option>
                    <option value="SGT 2º">Sargento 2º (SGT 2º)</option>
                    <option value="SGT">Sargento (SGT)</option>
                </select>
                <input type="text" id="a_nombre" placeholder="Nombre del Soldado" class="input-mando">
                <input type="text" id="a_discord" placeholder="ID Discord" class="input-mando">
                <button type="button" onclick="ejecutarAlta()" class="btn-accion-mando btn-verde">INCORPORAR A LA 212</button>
            </div>

            <div class="col-central">
                <div class="bloque-totales-compacto">
                    <div class="cuadro-total-mini">
                        <small style="color:red; font-weight:bold;">TOTAL ENTRENAMIENTOS</small>
                        <div style="display:flex; justify-content:space-around; margin-top:8px;">
                            <div><small>ANT.</small><br><b><?php echo $t_ent_ant; ?></b></div>
                            <div style="border-left:1px solid #ddd; padding-left:10px;"><small>ACT.</small><br><b style="color:red;"><?php echo $t_ent_act; ?></b></div>
                        </div>
                    </div>
                    <div class="cuadro-total-mini">
                        <small style="color:#2980b9; font-weight:bold;">TOTAL ROLES PJ/OPS</small>
                        <div style="display:flex; justify-content:space-around; margin-top:8px;">
                            <div><small>ANT.</small><br><b><?php echo $t_mis_ant; ?></b></div>
                            <div style="border-left:1px solid #ddd; padding-left:10px;"><small>ACT.</small><br><b style="color:#2980b9;"><?php echo $t_mis_act; ?></b></div>
                        </div>
                    </div>
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
                <button type="submit" class="btn-sincronizar-principal"><i class="fas fa-sync-alt"></i> GUARDAR CAMBIOS DATABANK</button>
                
            </div>
            

            <div class="col-formulario panel-baja">
                <h3 class="titulo-mando"><i class="fas fa-user-slash" style="color:#c0392b;"></i> EXPULSIÓN DIRECTA</h3>
                <select id="id_baja" class="select-mando">
                    <option value="">Seleccionar personal...</option>
                    <?php foreach($miembros as $m): ?>
                        <option value="<?php echo $m['id']; ?>"><?php echo $m['nombre']; ?></option>
                    <?php endforeach; ?>
                </select>
                <div style="height: 52px;"></div> <button type="button" onclick="ejecutarBaja()" class="btn-accion-mando btn-rojo">EJECUTAR LICENCIAMIENTO</button>
            </div>

        </div>
    </form>
</div>

<script>
function ejecutarAlta() {
    const r = document.getElementById('a_rango').value;
    const n = document.getElementById('a_nombre').value;
    const d = document.getElementById('a_discord').value;
    if(!n) return alert("El nombre es obligatorio");
    
    fetch('alta_sgt.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `rango=${r}&nombre=${n}&discord=${d}`
    }).then(() => location.reload());
}

function ejecutarBaja() {
    const id = document.getElementById('id_baja').value;
    if(!id) return alert("Selecciona a un soldado");
    if(confirm("¿Confirmar baja definitiva?")) {
        fetch('baja_sgt.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: `id_eliminar=${id}`
        }).then(() => location.reload());
    }
}
</script>
</body>
</html>
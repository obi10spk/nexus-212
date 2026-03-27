<?php
include '../ficheros/conexion.php';
session_start();

// JERARQUÍA REGLAMENTARIA
$orden_cbo = "'CBOM', 'CBO 1º', 'CBO 2º', 'CBO'";
$query = "SELECT * FROM miembros_cbo ORDER BY FIELD(rango_actual, $orden_cbo) ASC, nombre ASC";
$resultado = mysqli_query($conexion, $query);

$total_liderados = 0;
$total_asistencias = 0;

// Capturamos los miembros en un array para el desplegable de bajas sin repetir consulta
$lista_miembros = [];

// Consulta para obtener la fecha
$res_tiempo = mysqli_query($conexion, "SELECT ultima_modificacion FROM control_cambios WHERE seccion = 'cbo'");
$dato_tiempo = mysqli_fetch_assoc($res_tiempo);
$ultima_pila = date("d/m/Y | H:i:s", strtotime($dato_tiempo['ultima_modificacion']));
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>212 | Expedientes CBO+</title>
    <link rel="stylesheet" href="../ficheros/estilo_menu.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* --- TUS ESTILOS ORIGINALES --- */
        body { background-color: #ffffff !important; color: #000; margin: 0; font-family: Calibri, sans-serif; }
        .BarraNavegar { background: #000; padding: 0 20px; color: #fff; border-bottom: 3px solid #FF9B00; display: flex; justify-content: space-between; align-items: center; height: 60px; }
        .L1 { font-size: 20px; font-weight: bold; }
        .L2 { color: #FF9B00; font-size: 20px; font-weight: bold; }
        .label-volver { color: #FF9B00; font-weight: bold; cursor: pointer; text-transform: uppercase; font-size: 13px; display: flex; align-items: center; gap: 8px; }
        .contenedor-excel { display: flex; flex-direction: column; align-items: center; padding: 30px 10px; width: 100%; box-sizing: border-box; }
        .titulo-excel { background-color: #fce4d6; border: 2px solid #000; padding: 10px 20px; font-weight: bold; font-style: italic; margin-bottom: 30px; text-transform: uppercase; text-align: center; width: 95%; max-width: 800px; }
        .tabla-scroll { width: 100%; overflow-x: auto; border: 1px solid #000; margin-bottom: 20px; }
        .tabla-excel { border-collapse: collapse; background: #fff; font-size: 12px; min-width: 1000px; width: 100%; }
        .tabla-excel th, .tabla-excel td { border: 1px solid #000; padding: 6px 4px; text-align: center; }
        .header-rango { background-color: #FF9B00; font-weight: bold; }
        .header-ascenso { background-color: #00ff00; font-weight: bold; }
        .header-stats { background-color: #a5a5a5; font-weight: bold; }
        .input-celda { width: 100%; border: none; background: transparent; text-align: center; font-family: inherit; color: #000; }
        .btn-expediente { background: #fdf2e9; color: #000; border: 1px solid #FF9B00; padding: 5px 12px; cursor: pointer; font-size: 10px; font-weight: bold; text-transform: uppercase; border-radius: 2px; transition: 0.2s; }
        .btn-expediente:hover { background: #FF9B00; color: #fff; }
        .overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); z-index: 10000; backdrop-filter: blur(2px); }
        .modal-expediente { display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; border: 3px solid #000; padding: 0; z-index: 10001; width: 90%; max-width: 550px; box-shadow: 15px 15px 0px rgba(0,0,0,0.1); }
        .modal-header { background: #000; color: #fff; padding: 15px; display: flex; justify-content: space-between; align-items: center; }
        .modal-body { padding: 20px; }
        .modal-body label { display: block; font-weight: bold; color: #FF9B00; font-size: 11px; margin-bottom: 8px; text-transform: uppercase; }
        .modal-body textarea { width: 100%; height: 250px; border: 2px solid #000; padding: 15px; font-family: Calibri, sans-serif; font-size: 14px; outline: none; resize: none; background: #f9f9f9; box-sizing: border-box; }
        .btn-modal { padding: 12px; border: 2px solid #000; font-weight: bold; cursor: pointer; text-transform: uppercase; flex: 1; }
        .btn-save { background: #FF9B00; color: #000; }
        .btn-clear { background: #eee; color: #666; }

        /* --- ESTILOS NUEVOS: ALTA Y BAJA (NEXUS STYLE) --- */
        .centro-mando-unificado { display: flex; justify-content: center; align-items: flex-start; gap: 20px; width: 100%; margin-top: 30px; }
        .col-formulario { flex: 0 0 320px; background: #fff; border: 1.5px solid #000; padding: 20px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .panel-alta { border-top: 5px solid #27ae60; }
        .panel-baja { border-top: 5px solid #c0392b; }
        .titulo-mando { display: flex; align-items: center; gap: 10px; margin: 0 0 15px 0; font-size: 13px; font-weight: bold; text-transform: uppercase; border-bottom: 2px solid #FF9B00; padding-bottom: 8px; }
        .input-mando, .select-mando { width: 100%; padding: 10px; border: 1.5px solid #333; margin-bottom: 10px; font-size: 13px; box-sizing: border-box; }
        .btn-accion-mando { width: 100%; padding: 12px; border: none; color: #fff; font-weight: bold; text-transform: uppercase; cursor: pointer; font-size: 13px; }
        .btn-verde { background: #27ae60; }
        .btn-rojo { background: #c0392b; }
        
        .col-central { flex: 0 0 420px; display: flex; flex-direction: column; align-items: center; gap: 20px; }
        .panel-stats { display: flex; gap: 15px; width: 100%; justify-content: center; }
        .caja-stat { background: #fff; border: 2px solid #000; padding: 10px; width: 180px; text-align: center; box-shadow: 4px 4px 0px rgba(0,0,0,0.05); }
        .btn-sync { background-color: #FF9B00; color: #000; border: 2px solid #000; padding: 15px; width: 100%; font-weight: bold; cursor: pointer; text-transform: uppercase; transition: 0.3s; }
        .btn-sync:hover { background: #000; color: #FF9B00; }
    </style>
</head>
<body>

<div id="overlay" class="overlay" onclick="cerrarExpediente()"></div>
<div id="modalExpediente" class="modal-expediente">
    <div class="modal-header">
        <span id="tituloExpediente" style="font-weight:bold; letter-spacing:1px;">EXPEDIENTE TÁCTICO</span>
        <i class="fas fa-times" onclick="cerrarExpediente()" style="cursor:pointer; color:#FF9B00;"></i>
    </div>
    <div class="modal-body">
        <label>Historial de Conducta / Notas de Misión</label>
        <textarea id="areaExpediente" placeholder="Escriba aquí para generar un nuevo informe o editar el existente..."></textarea>
        <div style="display: flex; gap: 10px; margin-top: 15px;">
            <button type="button" class="btn-modal btn-save" onclick="aplicarCambios()">
                <i class="fas fa-check"></i> Aplicar al Registro
            </button>
            <button type="button" class="btn-modal btn-clear" onclick="document.getElementById('areaExpediente').value = ''">
                <i class="fas fa-eraser"></i> Limpiar
            </button>
        </div>
    </div>
</div>

<header class="BarraNavegar">
    <div id="cabecera"><span class="L1">212TH | </span><span class="L2">CBO+ DOSSIER</span></div>
    <div class="opciones-header">
        <label class="label-volver" onclick="location.href='../index.html'"><i class="fas fa-arrow-left"></i> VOLVER</label>
    </div>
</header>

<div class="contenedor-excel">
    <div class="titulo-excel">TABLA DE ACTIVIDADES CUERPO DE CABOS 212TH</div>

    <form action="actualizar_cbo.php" method="POST" style="width:100%; display:flex; flex-direction:column; align-items:center;">
        <div class="tabla-scroll">
            <table class="tabla-excel">
                <thead>
                    <tr>
                        <th class="header-rango">Rango actual</th>
                        <th class="header-rango">Nombre</th>
                        <th class="header-rango">ID Discord</th>
                        <th class="header-ascenso">Último ascenso</th>
                        <th class="header-stats">Ent/OP Liderados</th>
                        <th class="header-stats">Misiones asistidas</th>
                        <th class="header-rango">Expediente</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($resultado)): 
                        $lista_miembros[] = $row; // Guardamos para la baja
                        $total_liderados += (int)$row['ent_liderados'];
                        $total_asistencias += (int)$row['misiones_asistidas'];
                    ?>
                    <tr>
                        <input type="hidden" name="id[]" value="<?php echo $row['id']; ?>">
                        <td><input type="text" name="rango_act[]" class="input-celda" value="<?php echo $row['rango_actual']; ?>"></td>
                        <td><input type="text" name="nombre[]" class="input-celda" style="font-weight:bold;" value="<?php echo $row['nombre']; ?>"></td>
                        <td><input type="text" name="discord[]" class="input-celda" value="<?php echo $row['id_discord']; ?>"></td>
                        <td style="background:#e2efda;"><input type="date" name="ascenso[]" class="input-celda" value="<?php echo $row['ultimo_ascenso']; ?>"></td>
                        <td><input type="number" name="liderados[]" class="input-celda" value="<?php echo $row['ent_liderados']; ?>"></td>
                        <td><input type="number" name="asistidas[]" class="input-celda" value="<?php echo $row['misiones_asistidas']; ?>"></td>
                        <td>
    <button type="button" class="btn-expediente" onclick="abrirExpediente(<?php echo $row['id']; ?>, '<?php echo htmlspecialchars($row['nombre']); ?>')">
        <i class="fas fa-folder-open"></i> VER / EDITAR
    </button>
    
    <input type="hidden" 
           name="resumen[]" 
           id="hid_res_<?php echo $row['id']; ?>" 
           value="<?php echo htmlspecialchars($row['resumen_mensual'] ?? 'Sin antecedentes'); ?>">
</td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <div class="centro-mando-unificado">
            
            <div class="col-formulario panel-alta">
                <h4 class="titulo-mando"><i class="fas fa-user-plus" style="color:#27ae60;"></i> ALTA DE CABO</h4>
                <select id="a_rango" class="select-mando">
                    <option value="CBO">CBO</option>
                    <option value="CBO 2º">CBO 2º</option>
                    <option value="CBO 1º">CBO 1º</option>
                    <option value="CBOM">CBOM</option>
                </select>
                <input type="text" id="a_nombre" placeholder="Nombre del Cabo" class="input-mando">
                <input type="text" id="a_discord" placeholder="ID Discord" class="input-mando">
                <button type="button" onclick="ejecutarAlta()" class="btn-accion-mando btn-verde">INCORPORAR AL CUERPO</button>
            </div>

            <div class="col-central">
                <div class="panel-stats">
                    <div class="caja-stat" style="border-top: 5px solid #FF9B00;">
                        <h4 style="margin:0; font-size:11px; color:#666;">LIDERADAS TOTALES</h4>
                        <span style="font-size:24px; font-weight:bold;"><?php echo $total_liderados; ?></span>
                    </div>
                    <div class="caja-stat" style="border-top: 5px solid #FF9B00;">
                        <h4 style="margin:0; font-size:11px; color:#666;">ASISTENCIAS CBO</h4>
                        <span style="font-size:24px; font-weight:bold;"><?php echo $total_asistencias; ?></span>
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
                <button type="submit" class="btn-sync"><i class="fas fa-sync-alt"></i> Guardar Datos</button>
            </div>

            <div class="col-formulario panel-baja">
                <h4 class="titulo-mando"><i class="fas fa-user-slash" style="color:#c0392b;"></i> EXPULSIÓN DIRECTA</h4>
                <select id="id_baja" class="select-mando">
                    <option value="">Seleccionar miembro...</option>
                    <?php foreach($lista_miembros as $m): ?>
                        <option value="<?php echo $m['id']; ?>"><?php echo $m['nombre']; ?></option>
                    <?php endforeach; ?>
                </select>
                <div style="height: 52px;"></div> <button type="button" onclick="ejecutarBaja()" class="btn-accion-mando btn-rojo">EJECUTAR LICENCIAMIENTO</button>
            </div>

        </div>
    </form>
</div>

<script>
let caboIdActual = null;

function abrirExpediente(id, nombre) {
    caboIdActual = id;
    // Buscamos el texto que está guardado en el input oculto de ese Cabo
    const textoPrevio = document.getElementById('hid_res_' + id).value;
    
    // Ponemos el nombre en el título del mini menú
    document.getElementById('tituloExpediente').innerText = "EXPEDIENTE: " + nombre;
    
    // Metemos el texto en el área de escritura
    document.getElementById('areaExpediente').value = textoPrevio;
    
    // Mostramos el menú
    document.getElementById('modalExpediente').style.display = 'block';
    document.getElementById('overlay').style.display = 'block';
}

function aplicarCambios() {
    const textoNuevo = document.getElementById('areaExpediente').value;
    
    // Guardamos lo que has escrito en el input oculto para que se envíe al pulsar "Sincronizar"
    document.getElementById('hid_res_' + caboIdActual).value = textoNuevo;
    
    // Feedback visual: cambiamos el botón para saber que hay cambios sin guardar
    const btn = document.querySelector(`button[onclick*="abrirExpediente(${caboIdActual}"]`);
    btn.style.background = "#fff3cd";
    btn.style.color = "#856404";
    btn.innerHTML = '<i class="fas fa-save"></i> CON CAMBIOS';

    cerrarExpediente();
}

function cerrarExpediente() {
    document.getElementById('modalExpediente').style.display = 'none';
    document.getElementById('overlay').style.display = 'none';
}

/* --- FUNCIONES DE ALTA Y BAJA --- */
function ejecutarAlta() {
    const n = document.getElementById('a_nombre').value;
    const r = document.getElementById('a_rango').value;
    const d = document.getElementById('a_discord').value;
    if(!n) return alert("Nombre obligatorio");
    
    fetch('alta_cbo.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `rango=${r}&nombre=${n}&discord=${d}`
    }).then(() => location.reload());
}

function ejecutarBaja() {
    const id = document.getElementById('id_baja').value;
    if(!id) return alert("Selecciona a alguien");
    if(confirm("¿Confirmar baja definitiva?")) {
        fetch('baja_cbo.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: `id_eliminar=${id}`
        }).then(() => location.reload());
    }
}
</script>

</body>
</html>
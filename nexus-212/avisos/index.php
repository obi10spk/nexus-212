<?php
include '../ficheros/conexion.php';
session_start();

// 1. SEGURIDAD
if (!isset($_COOKIE['acceso_212']) || $_COOKIE['acceso_212'] !== 'autorizado') {
    header("Location: ../index.html"); exit();
}

// 2. CONSULTA CORREGIDA
$query = "SELECT * FROM avisos ORDER BY fecha DESC, id DESC";
$resultado = mysqli_query($conexion, $query);

$sancionados = [];

// Consulta para obtener la fecha
$res_tiempo = mysqli_query($conexion, "SELECT ultima_modificacion FROM control_cambios WHERE seccion = 'avisos'");
$dato_tiempo = mysqli_fetch_assoc($res_tiempo);
$ultima_pila = date("d/m/Y | H:i:s", strtotime($dato_tiempo['ultima_modificacion']));
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>212 | Control Disciplinario</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background: #fff; color: #000; margin: 0; font-family: Calibri, sans-serif; }
        .BarraNavegar { background: #000; padding: 0 40px; color: #fff; border-bottom: 3px solid #FF9B00; display: flex; justify-content: space-between; align-items: center; height: 60px; }
        .L2 { color: #FF9B00; font-weight: bold; }
        .contenedor-excel { display: flex; flex-direction: column; align-items: center; padding: 20px; }
        .titulo-excel { background: #fce4d6; border: 2px solid #000; padding: 10px; font-weight: bold; width: 90%; text-align: center; text-transform: uppercase; }
        .tabla-scroll { width: 100%; overflow-x: auto; border: 1px solid #000; margin-top: 10px; }
        .tabla-excel { border-collapse: collapse; width: 100%; min-width: 1000px; }
        .tabla-excel th, .tabla-excel td { border: 1px solid #000; padding: 8px; text-align: center; }
        .header-main { background: #FF9B00; font-weight: bold; }
        .input-celda { width: 100%; border: none; text-align: center; background: transparent; font-family: inherit; }

        /* ESTILOS DE GRAVEDAD */
        .res-Leve { background-color: #c6efce !important; color: #006100; }
        .res-Grave { background-color: #ffeb9c !important; color: #9c5700; }
        .res-Muy-Grave { background-color: #ffc7ce !important; color: #9c0006; }

        /* PANEL INFERIOR NEXUS */
        .centro-mando-unificado { display: flex; justify-content: center; align-items: flex-start; gap: 20px; margin-top: 30px; width: 100%; }
        .col-formulario { flex: 0 0 320px; background: #fff; border: 1.5px solid #000; padding: 20px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .panel-alta { border-top: 5px solid #27ae60; }
        .panel-baja { border-top: 5px solid #c0392b; }
        .titulo-mando { display: flex; align-items: center; gap: 10px; margin-bottom: 15px; font-size: 13px; font-weight: bold; text-transform: uppercase; border-bottom: 2px solid #FF9B00; padding-bottom: 8px; }
        .input-mando, .select-mando { width: 100%; padding: 10px; border: 1.5px solid #333; margin-bottom: 10px; box-sizing: border-box; }
        .btn-mando { width: 100%; padding: 12px; border: none; color: #fff; font-weight: bold; text-transform: uppercase; cursor: pointer; }
        .btn-verde { background: #27ae60; }
        .btn-rojo { background: #c0392b; }
        .btn-sync { background: #FF9B00; border: 2px solid #000; padding: 15px; width: 100%; font-weight: bold; cursor: pointer; text-transform: uppercase; }
    </style>
</head>
<body>

<header class="BarraNavegar">
    <div><b>212th | </b><span class="L2">SISTEMA DE ADVERTENCIAS</span></div>
    <label style="color:#FF9B00; font-weight:bold; cursor:pointer;" onclick="location.href='../index.html'"><i class="fas fa-arrow-left"></i> VOLVER</label>
</header>

<div class="contenedor-excel">
    <div class="titulo-excel">REGISTRO DISCIPLINARIO DE INFACCIONES</div>

    <form action="actualizar_avisos.php" method="POST" style="width:100%;">
        <div class="tabla-scroll">
            <table class="tabla-excel">
                <thead>
                    <tr class="header-main">
                        <th>Fecha</th><th>Nombre del Infractor</th><th>Gravedad</th><th>Motivo / Razón</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($resultado)): 
                        $sancionados[] = $row;
                        $clase_bg = "res-".str_replace(' ', '-', $row['tipo']);
                    ?>
                    <tr>
                        <input type="hidden" name="id[]" value="<?php echo $row['id']; ?>">
                        <td><input type="date" name="fecha[]" class="input-celda" value="<?php echo $row['fecha']; ?>"></td>
                        <td><input type="text" name="nombre[]" class="input-celda" style="font-weight:bold;" value="<?php echo $row['nombre']; ?>"></td>
                        <td class="<?php echo $clase_bg; ?>">
                            <select name="tipo[]" class="input-celda" style="font-weight:bold;" onchange="this.parentElement.className='res-'+this.value.replace(' ', '-')">
                                <option value="Leve" <?php if($row['tipo']=='Leve') echo 'selected'; ?>>Leve</option>
                                <option value="Grave" <?php if($row['tipo']=='Grave') echo 'selected'; ?>>Grave</option>
                                <option value="Muy Grave" <?php if($row['tipo']=='Muy Grave') echo 'selected'; ?>>Muy Grave</option>
                            </select>
                        </td>
                        <td><input type="text" name="motivo[]" class="input-celda" value="<?php echo $row['motivo']; ?>"></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <div class="centro-mando-unificado">
            <div class="col-formulario panel-alta">
                <h4 class="titulo-mando"><i class="fas fa-gavel"></i> NUEVO AVISO</h4>
                <input type="text" id="a_nombre" placeholder="Nombre del Soldado" class="input-mando">
                <select id="a_tipo" class="select-mando">
                    <option value="Leve">Advertencia Leve</option>
                    <option value="Grave">Falta Grave</option>
                    <option value="Muy Grave">Falta Muy Grave</option>
                </select>
                <input type="text" id="a_motivo" placeholder="Motivo" class="input-mando">
                <button type="button" onclick="ejecutarAlta()" class="btn-mando btn-verde">REGISTRAR</button>
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
            <div style="flex: 0 0 350px;">
                <button type="submit" class="btn-sync">GUARDAR CAMBIOS</button>
            </div>

            <div class="col-formulario panel-baja">
                <h4 class="titulo-mando"><i class="fas fa-eraser"></i> RETIRAR AVISO</h4>
                <select id="id_baja" class="select-mando">
                    <option value="">Seleccionar sanción...</option>
                    <?php foreach($sancionados as $s): ?>
                        <option value="<?php echo $s['id']; ?>"><?php echo "[".$s['tipo']."] ".$s['nombre']; ?></option>
                    <?php endforeach; ?>
                </select>
                <div style="height: 42px;"></div>
                <button type="button" onclick="ejecutarBaja()" class="btn-mando btn-rojo">LIMPIAR</button>
            </div>
        </div>
    </form>
</div>

<script>
function ejecutarAlta() {
    const n = document.getElementById('a_nombre').value;
    const t = document.getElementById('a_tipo').value;
    const m = document.getElementById('a_motivo').value;
    if(!n || !m) return alert("Completa los datos");
    fetch('alta_aviso.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `nombre=${encodeURIComponent(n)}&tipo=${encodeURIComponent(t)}&motivo=${encodeURIComponent(m)}`
    }).then(() => location.reload());
}

function ejecutarBaja() {
    const id = document.getElementById('id_baja').value;
    if(!id) return alert("Selecciona uno");
    if(confirm("¿Eliminar sanción?")) {
        fetch('baja_aviso.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: `id_eliminar=${id}`
        }).then(() => location.reload());
    }
}
</script>
</body>
</html>
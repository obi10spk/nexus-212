<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>212 | Generador de Conquista</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* --- ESTILO BASE --- */
        body { background: #fff; color: #000; margin: 0; font-family: Calibri, sans-serif; }
        
        .BarraNavegar { 
            background: #000; padding: 0 40px; color: #fff; border-bottom: 3px solid #FF9B00;
            display: flex; justify-content: space-between; align-items: center; height: 60px;
        }
        .L2 { color: #FF9B00; font-weight: bold; }

        .contenedor-form { padding: 40px; max-width: 1400px; margin: auto; }
        
        /* --- BLOQUES DE COMPAÑÍA --- */
        .unidad-block { 
            margin-bottom: 50px; border: 2.5px solid #000; padding: 20px; 
            background: #fff; box-shadow: 10px 10px 0px rgba(0,0,0,0.05);
        }
        .unidad-block h2 { 
            background: #FF9B00; color: #000; padding: 12px; margin: -20px -20px 20px -20px; 
            text-transform: uppercase; font-style: italic; font-weight: 900;
        }

        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        th { background: #fce4d6; border: 1px solid #000; padding: 8px; font-size: 13px; text-transform: uppercase; }
        td { border: 1px solid #000; padding: 0; }
        
        .hora-col { background: #FF9B00; font-weight: bold; width: 90px; text-align: center; }
        input.celda { width: 100%; border: none; padding: 10px; text-align: center; font-size: 12px; background: transparent; outline: none; }
        input.celda:focus { background: #fff2cc; }

        /* --- DESPLEGABLES REDONDEADOS (LOOK GOOGLE SHEETS) --- */
        .fila-objetivo { background: #f9f9f9; }
        .select-horario-tabla {
            width: 90%; padding: 6px; border: none; background: #e8eaed; 
            border-radius: 15px; font-weight: bold; text-align: center; 
            cursor: pointer; margin: 5px; font-family: inherit; appearance: none;
        }
        .select-horario-tabla:hover { background: #dadce0; }

        /* --- PANEL DE CONTROL INFERIOR (NEXUS STYLE) --- */
        .centro-mando-unificado { 
            display: flex; justify-content: center; align-items: stretch; 
            gap: 20px; margin-top: 40px; 
        }
        .col-formulario { 
            flex: 1; max-width: 350px; background: #fff; border: 1.5px solid #000; 
            padding: 20px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); 
        }
        .panel-alta { border-top: 5px solid #27ae60; }
        .panel-baja { border-top: 5px solid #c0392b; }

        .titulo-mando { 
            display: flex; align-items: center; gap: 10px; margin-bottom: 15px;
            font-size: 13px; font-weight: bold; text-transform: uppercase; 
            border-bottom: 2px solid #FF9B00; padding-bottom: 8px;
        }

        .btn-mando { width: 100%; padding: 12px; border: none; color: #fff; font-weight: bold; text-transform: uppercase; cursor: pointer; margin-top: 10px; }
        .btn-verde { background: #27ae60; }
        .btn-rojo { background: #c0392b; }

        .btn-generar-pdf { 
            flex: 1; max-width: 400px; background: #000; color: #FF9B00; 
            border: 2.5px solid #000; font-weight: 900; font-size: 18px; 
            cursor: pointer; text-transform: uppercase; transition: 0.3s;
        }
        .btn-generar-pdf:hover { background: #FF9B00; color: #000; }
    </style>
</head>
<body>

<header class="BarraNavegar">
    <div><b>212TH | </b><span class="L2">CONQUISTA GENERATOR</span></div>
    <label onclick="location.href='../index.html'" style="color:#FF9B00; cursor:pointer; font-weight:bold; text-transform:uppercase; font-size:12px;"> <i class="fas fa-arrow-left"></i> VOLVER</label>
</header>

<div class="contenedor-form">
    <h1 style="text-align: center; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 40px;">Generador de Despliegue: Conquista Galáctica</h1>
    
    <form action="generar_pdf.php" method="POST">
        
        <?php 
        $unidades = ['212th', 'AIR', 'GH'];
        $dias = ['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo'];
        $horas = ['16:00', '17:00', '18:00', '19:00', '20:00', '21:00', '22:00'];

        foreach ($unidades as $u): ?>
            <div class="unidad-block" id="block-<?php echo $u; ?>">
                <h2>Compañía: <?php echo $u; ?></h2>
                <table id="tabla-<?php echo $u; ?>">
                    <thead>
                        <tr>
                            <th>Hora</th>
                            <?php foreach ($dias as $d) echo "<th>$d</th>"; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($horas as $h): ?>
                            <tr>
                                <td class="hora-col"><?php echo $h; ?></td>
                                <?php foreach ($dias as $d): ?>
                                    <td><input type="text" name="data[<?php echo $u; ?>][<?php echo $h; ?>][<?php echo $d; ?>]" class="celda" placeholder="-"></td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr class="fila-objetivo">
                            <td style="border:none; text-align:right; font-weight:bold; padding-right:10px; font-size:11px;">OBJETIVO:</td>
                            <?php foreach ($dias as $d): ?>
                                <td>
                                    <select name="objetivo[<?php echo $u; ?>][<?php echo $d; ?>]" class="select-horario-tabla">
                                        <option value="-">-</option>
                                        <?php foreach ($horas as $h_opt) echo "<option value='$h_opt'>$h_opt</option>"; ?>
                                    </select>
                                </td>
                            <?php endforeach; ?>
                        </tr>
                    </tfoot>
                </table>
            </div>
        <?php endforeach; ?>


    </form>
</div>

</body>
</html>
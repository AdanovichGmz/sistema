<?php
error_reporting(0);
require_once("dompdf/dompdf_config.inc.php");
include '../saves/conexion.php';
$numodt = $_GET['fecha'];
$userid = $_GET['user'];
function getComida($idtiraje, $section)
{
    include '../saves/conexion.php';
    $query         = "SELECT TIME_TO_SEC(breaktime) AS real_comida FROM breaktime WHERE id_tiraje=$idtiraje AND seccion='$section' AND radios='Comida'";
    $tiempo_comida = mysqli_fetch_assoc($mysqli->query($query));
    return $tiempo_comida['real_comida'];
}

function getStandar($elem,$maquina)
{
    include '../saves/conexion.php';
    $idmaquina=($maquina==21||$maquina==20)? 10 : $maquina;
    $id_elem = mysqli_fetch_assoc($mysqli->query("SELECT id_elemento FROM elementos WHERE nombre_elemento='$elem' "));
    $elem=$id_elem['id_elemento'];
    $cuerito="SELECT piezas_por_hora FROM estandares WHERE id_elemento=$elem AND id_maquina=$idmaquina ";
    $estandar= mysqli_fetch_assoc($mysqli->query("SELECT piezas_por_hora FROM estandares WHERE id_elemento=$elem AND id_maquina=$idmaquina "));

    return $estandar['piezas_por_hora'];
}

$query = "SELECT
   t.*,
   m.nommaquina,
   o.numodt,
   o.producto,
   u.logged_in,
   (SELECT nombre_elemento FROM elementos WHERE id_elemento = o.producto) AS element,
   ((t.entregados - t.merma_entregada) - t.defectos) AS calidad,
   (SELECT piezas_por_hora FROM estandares WHERE id_elemento = o.producto AND id_maquina = 10) AS estandar,
   TIME_TO_SEC(tiempoTiraje) AS seconds_tiraje,
   TIME_TO_SEC(timediff(horafin_tiraje, horadeldia_tiraje)) AS dispon_tiro,
   TIME_TO_SEC(timediff(horafin_ajuste, horadeldia_ajuste)) AS dispon_ajuste,
   (SELECT TIME_TO_SEC(breaktime) FROM breaktime WHERE id_tiraje = t.idtiraje AND seccion = 'ajuste' AND radios = 'Comida') AS comida_ajuste,
   (SELECT TIME_TO_SEC(horadeldiaam) FROM breaktime WHERE id_tiraje = t.idtiraje AND seccion = 'ajuste' AND radios = 'Comida') AS ini_comida_ajuste,
   (SELECT TIME_TO_SEC(hora_fin_comida) FROM breaktime WHERE id_tiraje = t.idtiraje AND seccion='ajuste' AND radios='Comida' AND id_usuario =$userid) AS fin_comida_ajuste,
   (SELECT TIME_TO_SEC(breaktime) FROM breaktime WHERE id_tiraje = t.idtiraje AND seccion='tiro' AND radios='Comida' AND id_usuario =$userid) AS comida_tiro,
   (SELECT TIME_TO_SEC(horadeldiaam) FROM breaktime WHERE id_tiraje = t.idtiraje AND seccion='tiro' AND radios='Comida' AND id_usuario=$userid) AS ini_comida_tiro,
   (SELECT TIME_TO_SEC(hora_fin_comida) FROM breaktime WHERE id_tiraje=t.idtiraje AND seccion='tiro' AND radios='Comida' AND id_usuario=$userid) AS fin_comida_tiro,
   TIME_TO_SEC(tiempo_ajuste) AS seconds_ajuste,
   (SELECT TIME_TO_SEC(tiempo_muerto) FROM tiempo_muerto WHERE id_tiraje=t.idtiraje AND seccion='ajuste') AS seconds_muertos,
   (SELECT TIME_TO_SEC(tiempo_muerto) FROM tiempo_muerto WHERE id_tiraje=t.idtiraje AND seccion='tiraje') AS seconds_muertos_tiro 
FROM
   tiraje t 
   LEFT JOIN
      maquina m 
      ON m.idmaquina = t.id_maquina 
   LEFT JOIN
      login u 
      ON u.id = t.id_user 
   LEFT JOIN
      ordenes o 
      ON o.idorden = t.id_orden 
WHERE
   fechadeldia_ajuste = '$numodt' 
   AND t.id_user =$userid 
ORDER BY
   idtiraje ASC";

$asa_query = "SELECT *, TIME_TO_SEC(tiempo) AS tiempo_asaichi,TIME_TO_SEC(timediff(hora_fin,horadeldia)) AS dispon_asaichi, (SELECT TIME_TO_SEC(tiempo_muerto) FROM tiempo_muerto WHERE seccion='asaichi' AND fecha='$numodt' AND id_user=$userid) AS tmuerto_asa FROM asaichi WHERE fechadeldia='$numodt' AND id_usuario=$userid";

$resss     = $mysqli->query($query);
if (!$resss) {
  printf($mysqli->error); 
  echo $query;
}
$asa_resss = $mysqli->query($asa_query);
$getuser   = mysqli_fetch_assoc($mysqli->query("SELECT logged_in FROM login WHERE id=$userid"));



?>
<?php

?>
<html>
<head>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="../js/jquery.table2excel.js"></script>
<style>
@page {
            margin: 1.3em 1.3em;
            
        }
table {
    font-family: arial, sans-serif;
    border-collapse: collapse;
    width: 100%;
}

thead{
  background: #1A1F25;
  color: #fff;
  text-transform: uppercase;
  font-size: 7px!important;
  padding: 1px!important;
}

td, th {
    border: 1px solid #E1E0E5;
    text-align: center;
    padding: 2px;
    font-size: 9px;
}


.inhead{
  display: inline-block;
  width: 68%;
   font-family: arial, sans-serif;
   
   height: 50px;
  

}
.inhead table{
  width: 100%;
  text-align: center;

}
.inhead th{
 padding: 2px;
 text-align: center;
 border:1px solid #E1E0E5!important;

}
.inhead td{
border:1px solid #E1E0E5!important;
 text-align: center;

}
.logo{
  display: inline-block;
  width: 12%;
   font-family: arial, sans-serif;
   height: 50px;
   position: relative;
   

}
.logo img{
  position: absolute;
  top:-7px;
}
.title{
  display: inline-block;
  width: 20%;
  font-size: 12px;
  text-align: center;
   font-family: arial, sans-serif;
  font-weight: bold;
   height: 50px;
   line-height: 50px;
   vertical-align: middle;
   

}

.header{
  width: 100%;
  margin: 0 auto;
  padding-bottom: 5px;
  padding-top: 5px;
  height: 50px;
  
}
.header img{
  width: 100%;
}
#last {
  text-align: right!important;
}
#last img{
  width: 50%;
}
.botom-stats{
  display: inline-block;
  border:1px solid #E1E0E5;
  position: relative;
  font-family: arial, sans-serif;
}
.botom-stats div{
  position: relative;
  font-size: 12px;
}
.botom-stats td,th{
  font-size: 10px;
  font-weight: normal;
  border-top:none;
}
.botom-stats td{
  border-bottom:1px dashed #E1E0E5;
  border-left: none;
  border-right: none;
}
.botom-stats th{
  border-bottom:1px dashed #E1E0E5;
  border-right:1px dashed #E1E0E5;
}
.extra{
  border-right: 1px dashed #E1E0E5!important;
}
.extrath{
  border-bottom: none!important;
}
.sub-head{
  padding: 0!important;
  font-size: 6px!important;
}
.alertime{
  font-weight: bold;
}
</style>
</head>

<body>


<table id="table2excel" style="display: none;">
<thead>
<tr >
    <th class="sub-head">Hora Inicio</th>
    <th class="sub-head">Hora Fin</th>
    <th rowspan="" >ODT</th>
    <th rowspan="" >Producto</th>
    <th rowspan="" >STD</th>
    <th class="sub-head">Tiempo Disponible</th>
    <th class="sub-head">Tiempo Disponible Acumulado</th>
    <th class="sub-head">Tiempo Muerto</th>
    <th class="sub-head">Tiempo Muerto Acumulado</th>
    <th class="sub-head">Tiempo Real</th>
    <th class="sub-head">Tiempo Real Acumulado</th>
    <th class="sub-head">Produccion Esperada</th>
    <th class="sub-head">Produccion Esperada Acumulado</th>
    <th class="sub-head">Produccion Real</th>
    <th class="sub-head">Produccion Real Acumulada</th>
    <th class="sub-head">Merma</th>
    <th class="sub-head">Merma Acumulada</th>
    <th class="sub-head">Calidad a la Primera</th>
    <th class="sub-head">Calidad a la Primera Acumulada</th>
    <th class="sub-head">Defectos</th>
    <th class="sub-head">Defectos Acumulados</th>
    <th class="sub-head">Alertas</th>
    
    
  </tr>
  </thead>
  <tbody>
  <?php
$i             = 0;
$sum_esper     = 0;
$sum_merm      = 0;
$sum_real      = 0;
$sum_tiraje    = 0;
$sum_ajuste    = 0;
$sum_muerto    = 0;
$sum_defectos  = 0;
$sum_calidad   = 0;
$sum_dispon    = 0;
$sum_recibidos = 0;
$comida_exist  = '';
$comida_exist2 = '';
$asa_exist     = ($asa_resss->num_rows > 0) ? true : false;
while ($asa = mysqli_fetch_assoc($asa_resss)) {
    if ($i == 0) {
        if ($asa_exist) {
            $transcur[$i] = strtotime($asa['horadeldia']) - strtotime("08:45:00");
            $sum_muerto += $transcur[$i];
            $sum_dispon += $transcur[$i];
        }
    } 
?>
  <tr>
     <td><?= substr($asa['horadeldia'], 0, -3); ?></td>                     
    <td><?= substr($asa['hora_fin'], 0, -3); ?></td>
    <td>  </td>
    <td> Asaiichi </td>
    <!-- <td <?= ($row['is_virtual'] == 'true') ? 'style="color:red;"' : '' ?>><?= ($row['is_virtual'] == 'true') ? $row['odt_virtual'] : $row['numodt']; ?> </td> -->
    <td>0</td>
    <?php
    $sum_tiraje += $asa['tiempo_asaichi'];
    
?>
    <td><?= gmdate("H:i", $asa['dispon_asaichi']); ?></td>
   <?php
    $sum_dispon += $asa['dispon_asaichi'];
?>
    <td><?= gmdate("H:i", $sum_dispon); ?></td>
    <?php
    //$sum_muerto += $asa['tmuerto_asa'];
    
?>
    <td><?= gmdate("H:i", $sum_muerto); ?></td>
    <td><?= gmdate("H:i", $sum_muerto); 
      $sum_muerto += $asa['tmuerto_asa'];
    ?></td>
    <td><?= gmdate("H:i", $asa['tiempo_asaichi']); ?></td>

    <td><?= gmdate("H:i", $sum_tiraje) ?></td>
   
    <td>0</td>
    <td>0</td>
    <td>0</td>
    <td>0</td>
    <td>0</td>
    <td>0</td>
    <td>0</td>
    <td>0</td>
    <td>0</td>
    <td>0</td>
    <td>--</td>
    
    
    <!--
   
    <td><?= round($row['desempenio'], 2); ?>%</td> -->
  </tr>
  <?php
}
?>
  <?php

while ($row = mysqli_fetch_assoc($resss)):
    if ($i == 0) {
        if (!$asa_exist) {
            $transcur = strtotime($row['horadeldia_ajuste']) - strtotime("08:45:00");
            $sum_muerto += $transcur;
            $sum_dispon += $transcur;
        }
        
    }
    //$comida_exist = (!empty($row['comida_ajuste'])) ? 'Comida ' . gmdate("H:i", $row['ini_comida_ajuste']) . "-" . gmdate("H:i", $row['fin_comida_ajuste']) : '';
    $comida_exist = (!empty($row['comida_ajuste'])) ? '<tr><td>&nbsp</td><td>&nbsp</td><td>&nbsp</td><td>&nbsp</td><td>&nbsp</td><td>&nbsp</td><td>&nbsp</td><td>&nbsp</td><td>&nbsp</td><td>&nbsp</td><td>&nbsp</td><td style="color:#fff;background:#4D4D4D;"> COMIDA ' . gmdate("H:i", $row['ini_comida_ajuste']) . "-" . gmdate("H:i", $row['fin_comida_ajuste']).' </td><td>&nbsp</td><td>&nbsp</td><td>&nbsp</td><td>&nbsp</td><td>&nbsp</td><td>&nbsp</td><td>&nbsp</td><td>&nbsp</td><td>&nbsp</td><td>&nbsp</td><td>&nbsp</td><td>&nbsp</td></tr>' : '';
    //$sum_muerto+=$row['comida_ajuste'];
    $sum_esper += $row['produccion_esperada'];
    $sum_merm += $row['merma_entregada'];
    $sum_real += $row['entregados'] - $row['merma_entregada'];
    $sum_recibidos += $row['cantidad'];
    $sum_ajuste += $row['seconds_ajuste'];
    $sum_muerto += $row['seconds_muertos_tiro'];
    $sum_defectos += $row['defectos'];
    $sum_calidad += $row['calidad'];
    $comida = (!empty($row['comida_ajuste'])) ? $row['dispon_ajuste']-$row['comida_ajuste'] : $row['dispon_ajuste'];
    $sum_dispon += $comida; 
    
    $processID=($row['id_maquina']==20||$row['id_maquina']==21)? 10:(($row['id_maquina']==22)? 9 : $row['id_maquina']);
    if (is_null($row['estandar'])) {
        
        if ($processID == 10) {
            $tiraje_estandar = 420;
        } else {
            $tiraje_estandar = 600;
        }
    } else {
        $tiraje_estandar = $row['estandar'];
    }
    $idtiro      = $row['idtiraje'];
     $alertaquery = $mysqli->query("SELECT *,TIME_TO_SEC(horadeldiaam)  AS inicio,TIME_TO_SEC(horafin_alerta) AS fin,  TIME_TO_SEC(tiempoalertamaquina) AS alert_real,TIME_TO_SEC(timediff(horafin_alerta ,horadeldiaam)) AS dispon_alertajuste FROM alertageneralajuste WHERE id_tiraje=$idtiro AND es_tiempo_muerto='false'");
    $alertaquerymuerto = $mysqli->query("SELECT *,TIME_TO_SEC(horadeldiaam)  AS inicio,TIME_TO_SEC(horafin_alerta) AS fin,  TIME_TO_SEC(tiempoalertamaquina) AS alert_real,TIME_TO_SEC(timediff(horafin_alerta ,horadeldiaam)) AS dispon_alertajuste FROM alertageneralajuste WHERE id_tiraje=$idtiro AND es_tiempo_muerto='true'");
    $alertaTiroMuerto  = $mysqli->query("SELECT *,TIME_TO_SEC(horadeldiaam)  AS inicio,TIME_TO_SEC(horafin_alerta) AS fin,  TIME_TO_SEC(tiempoalertamaquina) AS alert_real,TIME_TO_SEC(timediff(horafin_alerta ,horadeldiaam)) AS dispon_alertatiro FROM alertamaquinaoperacion WHERE id_tiraje=$idtiro AND es_tiempo_muerto='true'");
     $alertaTiro  = $mysqli->query("SELECT *,TIME_TO_SEC(horadeldiaam)  AS inicio,TIME_TO_SEC(horafin_alerta) AS fin,  TIME_TO_SEC(tiempoalertamaquina) AS alert_real,TIME_TO_SEC(timediff(horafin_alerta ,horadeldiaam)) AS dispon_alertatiro FROM alertamaquinaoperacion WHERE id_tiraje=$idtiro AND es_tiempo_muerto='false'");
    
    
    while ($alertaAjuste = mysqli_fetch_assoc($alertaquery)) {
        
        $alert[$i][]      = ($alertaAjuste['radios'] == 'Otro') ? $alertaAjuste['observaciones'] . " <span class='alertime'>" . gmdate("H:i", $alertaAjuste['inicio']) . "-" . gmdate("H:i", $alertaAjuste['fin']) . "</span>" : $alertaAjuste['radios'] . " <span class='alertime'>" . gmdate("H:i", $alertaAjuste['inicio']) . "-" . gmdate("H:i", $alertaAjuste['fin']) . "</span>";
      
    }
    while ($alertaAjusteM = mysqli_fetch_assoc($alertaquerymuerto)) {
        
        $alertA_Sum[$i][] = $alertaAjusteM['alert_real'];
        $alertM[$i][]      = ($alertaAjusteM['radios'] == 'Otro') ? $alertaAjusteM['observaciones'] . " <span class='alertime'>" . gmdate("H:i", $alertaAjusteM['inicio']) . "-" . gmdate("H:i", $alertaAjusteM['fin']) . "</span>" : $alertaAjusteM['radios'] . " <span class='alertime'>" . gmdate("H:i", $alertaAjusteM['inicio']) . "-" . gmdate("H:i", $alertaAjusteM['fin']) . "</span>";
    }

    if (isset($alertA_Sum[$i])) {
      //$sum_muerto += array_sum($alertA_Sum[$i]);
    }
   
    $alertaqueryTinta = $mysqli->query("SELECT TIME_TO_SEC(tiempoalertamaquina)  AS tiempotinta FROM alertageneralajuste WHERE id_tiraje=$idtiro AND radios='Preparar Tinta' ");
while ($tinta = mysqli_fetch_assoc($alertaqueryTinta)) {
  $PTinta[$i][]=$tinta['tiempotinta'];
}
 if (isset($PTinta[$i])) {
      $sum_tiraje += array_sum($PTinta[$i]);
    }  
    
    
    
    
    
    
?>
<!-- ********** Inicia TR Ajuste ********** -->
                          <tr>
     <td><?= substr($row['horadeldia_ajuste'], 0, -3); ?></td>                     
    <td><?= substr($row['horafin_ajuste'], 0, -3); ?></td>
    <td> </td>
    <td <?= ($row['is_virtual'] == 'true') ? 'style="color:red;"' : '' ?> >Ajuste </td>
    <!-- <td <?= ($row['is_virtual'] == 'true') ? 'style="color:red;"' : '' ?>><?= ($row['is_virtual'] == 'true') ? $row['odt_virtual'] : $row['numodt']; ?> </td> -->
    <td>0</td>
    <td><?= gmdate("H:i", (!empty($row['comida_ajuste'])) ? $row['dispon_ajuste'] - $row['comida_ajuste'] : $row['dispon_ajuste']); ?></td>
   
    <td><?= gmdate("H:i", $sum_dispon); ?></td>
    <?php
    //$sum_muerto += $row['seconds_muertos'];
    $haytinta=(isset($PTinta[$i]))? array_sum($PTinta[$i]) : 0;
    $tcomida=(!empty($row['comida_ajuste']))?$row['comida_ajuste']:0;
    $formulaajuste[$i]=(($row['id_maquina']==9||$row['id_maquina']==22)? ($row['dispon_ajuste']-1500 )-$tcomida: (($row['id_maquina']==16 )? ($row['dispon_ajuste']-3600)-$tcomida : ($row['dispon_ajuste']-1200)-$tcomida));
    $extraerMuerto[$i]=($formulaajuste[$i]<=0)? 0 : $formulaajuste[$i];
    $sum_muerto +=$extraerMuerto[$i];
   // gmdate("H:i", $row['seconds_muertos'] + ((isset($alertA_Sum))?array_sum($alertA_Sum[$i]) : 0)+$extraerMuerto); Asi estaba antes
?>
    <td><?= gmdate("H:i", $extraerMuerto[$i]); ?></td>
    <td><?= gmdate("H:i", $sum_muerto); ?></td>
    <td><?= gmdate("H:i", ($formulaajuste[$i]<=0)? $row['seconds_ajuste']+$haytinta : (($row['id_maquina']==9||$row['id_maquina']==22)? 1500 : (($row['id_maquina']==16)? 3600 : 1200))+$haytinta ); ?></td>
  <?php
    $sum_tiraje += ($formulaajuste[$i]<=0)? $row['seconds_ajuste'] : (($row['id_maquina']==9||$row['id_maquina']==22 )? 1500 : (($row['id_maquina']==16)? 3600 : 1200));
     
?>
    <td><?= gmdate("H:i", $sum_tiraje); ?></td>
   
    <td>0</td>
    <td><?= $sum_esper ?></td>
    <td>0</td>
    <td><?= $sum_real ?></td>
    <td>0</td>
    <td><?= $sum_merm ?></td>
    <td>0</td>
    <td><?= $sum_calidad ?></td>
    <td>0</td>
    <td><?= $sum_defectos ?></td>
    <?php
    if (!empty($alert)||!empty($alertM)) {
?>
    <td colspan="3"><?= implode(' | ', $alert[$i])." ".implode(' | ', $alertM[$i]) ?></td>
    <?php
    } else {
?>
    <td>--</td>
    
    
    <?php
    }
?>
    <!--
   
    <td><?= round($row['desempenio'], 2); ?>%</td> -->
  </tr>
  <!-- ********** Termina TR Ajuste ********** -->
  <?php echo $comida_exist ?>
<?php
    //$sum_muerto+=$row['comida_tiro'];
    //$comida_exist2 = (!empty($row['comida_tiro'])) ? 'Comida ' . gmdate("H:i", $row['ini_comida_tiro']) . "-" . gmdate("H:i", $row['fin_comida_tiro']) : '';
    $comida_exist2 = (!empty($row['comida_tiro'])) ? '<tr><td>&nbsp</td><td>&nbsp</td><td>&nbsp</td><td>&nbsp</td><td>&nbsp</td><td>&nbsp</td><td>&nbsp</td><td>&nbsp</td><td>&nbsp</td><td>&nbsp</td><td>&nbsp</td><td tyle="color:#fff;background:#4D4D4D;"> COMIDA ' . gmdate("H:i", $row['ini_comida_tiro']) . "-" . gmdate("H:i", $row['fin_comida_tiro']).' </td><td>&nbsp</td><td>&nbsp</td><td>&nbsp</td><td>&nbsp</td><td>&nbsp</td><td>&nbsp</td><td>&nbsp</td><td>&nbsp</td><td>&nbsp</td><td>&nbsp</td><td>&nbsp</td><td>&nbsp</td></tr>' : '';
    
    
    while ($alertaT = mysqli_fetch_assoc($alertaTiroMuerto)) {
        
        $alertTiro[$i][]=($alertaT['radios'] == 'Otro') ? $alertaT['observaciones'] . " <span class='alertime'>" . gmdate("H:i", $alertaT['inicio']) . "-" . gmdate("H:i", $alertaT['fin']) . "</span>" : $alertaT['radios'] . " <span class='alertime'>" . gmdate("H:i", $alertaT['inicio']) . "-" . gmdate("H:i", $alertaT['fin']) . "</span>";
          
        $alertT_Sum[$i][] = $alertaT['alert_real'];
        
    }

    if (isset($alertT_Sum)) {
      $sum_muerto += array_sum($alertT_Sum[$i]);
    }

    while ($tintaT = mysqli_fetch_assoc($alertaTiro)) {
       $AtintaT[$i][]=($tintaT['radios'] == 'Otro') ? $tintaT['observaciones'] . " <span class='alertime'>" . gmdate("H:i", $tintaT['inicio']) . "-" . gmdate("H:i", $tintaT['fin']) . "</span>" : $tintaT['radios'] . " <span class='alertime'>" . gmdate("H:i", $tintaT['inicio']) . "-" . gmdate("H:i", $tintaT['fin']) . "</span>";
  $PTintaT[$i][]=$tintaT['alert_real'];
}
 if (isset($PTintaT[$i])) {
      $sum_tiraje += array_sum($PTintaT[$i]);
    }  
    
    
?>
<!-- ********** Inicia TR Tiro ********** -->
      <tr style=" background-color: #EBEBEB;">
     <td><?= substr($row['horadeldia_tiraje'], 0, -3); ?></td>                     
    <td><?= substr($row['horafin_tiraje'], 0, -3); ?></td>
    <td> <?= ($row['is_virtual'] == 'true') ? $row['odt_virtual'] : $row['numodt'] ?> </td>
    <td <?= ($row['is_virtual'] == 'true') ? 'style="color:red;"' : '' ?> ><?= ($row['is_virtual'] == 'true') ? $row['elemento_virtual'] : $row['element']; ?> </td>
    <!-- <td <?= ($row['is_virtual'] == 'true') ? 'style="color:red;"' : '' ?>><?= ($row['is_virtual'] == 'true') ? $row['odt_virtual'] : $row['numodt']; ?> </td> -->
    <td><?=getStandar(($row['is_virtual'] == 'true')? $row['elemento_virtual'] : $row['element'] ,$row['id_maquina']); ?></td>
    <td><?= gmdate("H:i", (!empty($row['comida_tiro'])) ? $row['dispon_tiro'] - $row['comida_tiro'] : $row['dispon_tiro']) ?></td>
     <?php
    $comida2 = (!empty($row['comida_tiro'])) ? $row['dispon_tiro'] - $row['comida_tiro'] : $row['dispon_tiro'];
    $sum_dispon += $comida2;
?>
    <?php
    $sum_muerto += $row['seconds_muertos_tiro'];
?>
   <?php
    $sum_tiraje += $row['seconds_tiraje'];
?>
    <td><?= gmdate("H:i", $sum_dispon); ?></td>
    <td><?= gmdate("H:i", ((isset($alertT_Sum))? array_sum($alertT_Sum[$i]) : 0) ); ?></td>
    <td><?= gmdate("H:i", $sum_muerto); ?></td>
    <td><?= gmdate("H:i", $row['seconds_tiraje']); ?></td>
    <td><?= gmdate("H:i", $sum_tiraje); ?></td>
    <td><?= $row['produccion_esperada']; ?></td>
    <td><?= $sum_esper ?></td>
    <td><?= $row['entregados'] - $row['merma_entregada']; ?></td>
    <td><?= $sum_real ?></td>
    <td><?= $row['merma_entregada']; ?></td>
    <td><?= $sum_merm ?></td>
    <td><?= $row['calidad']; ?></td>
    <td><?= $sum_calidad ?></td>
    <td><?= $row['defectos']; ?></td>
    <td><?= $sum_defectos ?></td>
    <?php
    if (!empty($alertTiro)||!empty($AtintaT)) {
?>
    <td colspan="3"> <?= implode(' | ', $alertTiro[$i])." ". implode(' | ',$AtintaT[$i])  ?></td>
    <?php

    } else {
?>
    <td>--</td>
  
    
    <?php
    }
?>
   
  </tr>
  <!-- ********** Termina TR Tiro ********** -->
   <?php echo $comida_exist2 ?>          
  <?php
    $i++;
endwhile;
?>
  
  </tbody>
</table>
<?php
$treal = $sum_tiraje;


?>


</body>
</html>



<script>
  $("button").click(function(){
  $("#table2excel").table2excel({
    // exclude CSS class
    exclude: ".noExl",
    name: "Worksheet Name",
    filename: "SomeFile" //do not include extension
  }); 
});


  $(document).ready(function() {
     $("#table2excel").table2excel({
    // exclude CSS class
    exclude: ".noExl",
    name: "Worksheet Name",
    filename: "SomeFile" //do not include extension
  });
 });
</script>
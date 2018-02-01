<?php
error_reporting(0);
require_once("dompdf/dompdf_config.inc.php");
include '../saves/conexion.php';
$numodt = $_POST['fecha'];
$users= array('0' =>14 ,'1' =>16 ,'2' =>8 ,'3' =>11 ,'4' =>13 );
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

?>
<?php
ob_start();
?>
<html>
<head>
<style>
@page {
            margin: 1.3em 1.3em;
            
        }
table {
    font-family: arial, sans-serif;
    border-collapse: collapse;
    width: 100%;
}
#resumes{
    border: 1px solid #E1E0E5;
}
#resumes thead{

  font-size: 13px!important;
  padding: 5px!important;
}

#resumes td{
  border-style: dashed;
  font-size: 10px;
}

#datos thead{
  background: #1A1F25;
  color: #fff;
  text-transform: uppercase;
  font-size: 7px!important;
  padding: 1px!important;
}

 td,  th {
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
<?php 
foreach ($users as $key => $userid) {
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
   horadeldia_ajuste ASC";

$asa_query = "SELECT *, TIME_TO_SEC(tiempo) AS tiempo_asaichi,TIME_TO_SEC(timediff(hora_fin,horadeldia)) AS dispon_asaichi, (SELECT TIME_TO_SEC(tiempo_muerto) FROM tiempo_muerto WHERE seccion='asaichi' AND fecha='$numodt' AND id_user=$userid) AS tmuerto_asa FROM asaichi WHERE fechadeldia='$numodt' AND id_usuario=$userid";

$resss     = $mysqli->query($query);
if (!$resss) {
  printf($mysqli->error); 
  echo $query;
}
$asa_resss = $mysqli->query($asa_query);
$getuser   = mysqli_fetch_assoc($mysqli->query("SELECT logged_in FROM login WHERE id=$userid"));


$style=(($resss->num_rows>16)? 'style="padding: 2px;font-size: 7px;"' :'');

?>
<body>
<div class="header">
  <div class="logo"><img src="../img/logoDerecha.png">
  </div><div class="title">REPORTE DIARIO ETE
 
 </div> <div class="inhead">

  <table >
  <tr><th>OPERADOR</th>
    <th>MAQUINA</th>
    <th>TURNO</th>
    <th>FECHA</th>
    </tr>
    <tr>
      <td><?= $getuser['logged_in'] ?></td>
      <td></td>
      <td></td>
      <td><?= $numodt ?></td>
    </tr>
  </table>
  </div>
</div>

<table id="datos">
<thead><tr>
    <th colspan="2"  >Hora</th>
    
    <th rowspan="2"  >ODT</th>
    <th rowspan="2" >Producto</th>
    
    <th rowspan="2" >STD</th>
    <th   colspan="2">Tiempo Disponible</th>
    <th   colspan="2">Tiempo Muerto</th>
    <th   colspan="2">Tiempo Real</th>
    <th   colspan="2">Produccion Esperada</th>
    <th   colspan="2">Produccion Real</th>
    <th  colspan="2">Merma</th>
    <th   colspan="2">Calidad a la Primera</th>
    <th   colspan="2">Defectos</th>
    <th rowspan="2" >Porque no se hizo bien a la primera?</th>
    <th rowspan="2" >Porque se hizo mas lento?</th>
    <th rowspan="2" >Porque se perdio tiempo?</th>
    
  </tr>
<tr >
    <th class="sub-head">Inicio</th>
    <th class="sub-head">Fin</th>
    <th class="sub-head">REAL</th>
    <th class="sub-head">ACUM</th>
    <th class="sub-head">REAL</th>
    <th class="sub-head">ACUM</th>
    
    <th class="sub-head">REAL</th>
    <th class="sub-head">ACUM</th>
    <th class="sub-head">REAL</th>
    <th class="sub-head">ACUM</th>
    <th class="sub-head">REAL</th>
    <th class="sub-head">ACUM</th>
    <th class="sub-head">REAL</th>
    <th class="sub-head">ACUM</th>
    <th class="sub-head">REAL</th>
    <th class="sub-head">ACUM</th>
    <th class="sub-head">REAL</th>
    <th class="sub-head">ACUM</th>
    
  </tr>
  </thead>
  <tbody>
  <?php
  $ii[$key]=0;
$i             = $ii[$key];
$sum_esper[$key]     = 0;
$sum_merm[$key]      = 0;
$sum_real[$key]      = 0;
$sum_tiraje[$key]    = 0;
$sum_ajuste[$key]    = 0;
$sum_muerto[$key]    = 0;
$sum_defectos[$key]  = 0;
$sum_calidad[$key]   = 0;
$sum_dispon[$key]    = 0;
$sum_recibidos[$key] = 0;
$comida_exist[$key]  = '';
$comida_exist2[$key] = '';
$asa_exist     = ($asa_resss->num_rows > 0) ? true : false;
while ($asa = mysqli_fetch_assoc($asa_resss)) {
    if ($i == 0) {
        if ($asa_exist) {
            

            $transcur[$key][$i] = strtotime($asa['hora_fin'])-((strtotime($asa['horadeldia'])<strtotime("08:45:00"))? strtotime("08:45:00"): strtotime($asa['horadeldia']));
            $exceed[$key][$i]=strtotime($asa['hora_fin'])-strtotime("09:00:00");
            $dispasa[$key][$i]=strtotime($asa['hora_fin'])-strtotime("08:45:00");
            $sum_muerto[$key] += $exceed[$key][$i];
            $asareal[$key][$i]=strtotime($asa['hora_fin'])-((strtotime($asa['horadeldia'])<strtotime("08:45:00"))? strtotime("08:45:00"): strtotime($asa['horadeldia']))-$exceed[$key][$i];
            //$sum_muerto += $transcur[$i];
            //$sum_dispon += $transcur[$i];

        }
    }

?>
  <tr>
     <td <?=$style ?>><?=substr($asa['horadeldia'], 0, -3); ?></td>                     
    <td <?=$style ?>><?= substr($asa['hora_fin'], 0, -3); ?></td>
    <td <?=$style ?>>  </td>
    <td <?=$style ?>> Asaiichi </td>
    <!-- <td <?= ($row['is_virtual'] == 'true') ? 'style="color:red;"' : '' ?>><?= ($row['is_virtual'] == 'true') ? $row['odt_virtual'] : $row['numodt']; ?> </td> -->
    <td <?=$style ?>>0</td>
    <?php
    $sum_tiraje[$key] += $asareal[$key][$i];
    
?>
    <td <?=$style ?>><?= gmdate("H:i", (strtotime($asa['hora_fin'])-((strtotime($asa['horadeldia'])<strtotime("08:45:00"))? strtotime("08:45:00"): strtotime($asa['horadeldia']))) ); ?></td>
   <?php
    $sum_dispon[$key] += $dispasa[$key][$i];
?>
    <td <?=$style ?>><?= gmdate("H:i", $sum_dispon[$key]); ?></td>
    <?php
    //$sum_muerto[$key] += $asa['tmuerto_asa'];
    
?>
    <td <?=$style ?>><?= gmdate("H:i", $sum_muerto[$key]); ?></td>
    <td <?=$style ?>><?= gmdate("H:i", $sum_muerto[$key]); 
      //$sum_muerto[$key] += $asa['tmuerto_asa'];
    ?></td>
    <td <?=$style ?>><?= gmdate("H:i", $asareal[$key][$i]); ?></td>

    <td <?=$style ?>><?= gmdate("H:i", $sum_tiraje[$key]) ?></td>
   
    <td <?=$style ?>>0</td>
    <td <?=$style ?>>0</td>
    <td <?=$style ?>>0</td>
    <td <?=$style ?>>0</td>
    <td <?=$style ?>>0</td>
    <td <?=$style ?>>0</td>
    <td <?=$style ?>>0</td>
    <td <?=$style ?>>0</td>
    <td <?=$style ?>>0</td>
    <td <?=$style ?>>0</td>
    <td <?=$style ?>></td>
    <td <?=$style ?>></td>
    <td <?=$style ?>></td>
    
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
            $sum_muerto[$key] += $transcur;
            $sum_dispon[$key] += $transcur;
        }
        
    }
    //$comida_exist = (!empty($row['comida_ajuste'])) ? 'Comida ' . gmdate("H:i", $row['ini_comida_ajuste']) . "-" . gmdate("H:i", $row['fin_comida_ajuste']) : '';
    $comida_exist[$key] = (!empty($row['comida_ajuste'])) ? '<tr><td colspan="24" style="color:#fff;background:#4D4D4D;"> COMIDA ' . gmdate("H:i", $row['ini_comida_ajuste']) . "-" . gmdate("H:i", $row['fin_comida_ajuste']).' </td></tr>' : '';
    //$sum_muerto[$key]+=$row['comida_ajuste'];
    $sum_esper[$key] += $row['produccion_esperada'];
    $sum_merm[$key] += $row['merma_entregada'];
    $sum_real[$key] += $row['entregados'] - $row['merma_entregada'];
    $sum_recibidos[$key] += $row['cantidad'];
    $sum_ajuste[$key] += $row['seconds_ajuste'];
    $sum_muerto[$key] += $row['seconds_muertos_tiro'];
    $sum_defectos[$key] += $row['defectos'];
    $sum_calidad[$key] += $row['calidad'];
    $comida = (!empty($row['comida_ajuste'])) ? $row['dispon_ajuste']-$row['comida_ajuste'] : $row['dispon_ajuste'];
    $sum_dispon[$key] += $comida; 
    
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
        
        $alert[$key][$i][]      = ($alertaAjuste['radios'] == 'Otro') ? $alertaAjuste['observaciones'] . " <span class='alertime'>" . gmdate("H:i", $alertaAjuste['inicio']) . "-" . gmdate("H:i", $alertaAjuste['fin']) . "</span>" : $alertaAjuste['radios'] . " <span class='alertime'>" . gmdate("H:i", $alertaAjuste['inicio']) . "-" . gmdate("H:i", $alertaAjuste['fin']) . "</span>";
      
    }
    while ($alertaAjusteM = mysqli_fetch_assoc($alertaquerymuerto)) {
        
        $alertA_Sum[$key][$i][] = $alertaAjusteM['alert_real'];
        $alertM[$key][$i][]      = ($alertaAjusteM['radios'] == 'Otro') ? $alertaAjusteM['observaciones'] . " <span class='alertime'>" . gmdate("H:i", $alertaAjusteM['inicio']) . "-" . gmdate("H:i", $alertaAjusteM['fin']) . "</span>" : $alertaAjusteM['radios'] . " <span class='alertime'>" . gmdate("H:i", $alertaAjusteM['inicio']) . "-" . gmdate("H:i", $alertaAjusteM['fin']) . "</span>";
    }

    if (isset($alertA_Sum[$key][$i])) {
      //$sum_muerto[$key] += array_sum($alertA_Sum[$key][$i]);
    }
   
    $alertaqueryTinta = $mysqli->query("SELECT TIME_TO_SEC(tiempoalertamaquina)  AS tiempotinta FROM alertageneralajuste WHERE id_tiraje=$idtiro AND radios='Preparar Tinta' ");
while ($tinta = mysqli_fetch_assoc($alertaqueryTinta)) {
  $PTinta[$key][$i][]=$tinta['tiempotinta'];
}
 if (isset($PTinta[$key][$i])) {
      $sum_tiraje[$key] += array_sum($PTinta[$key][$i]);
    }  
    
    
    
    
    
    
?>
<!-- ********** Inicia TR Ajuste ********** -->
                          <tr>
     <td <?=$style ?>><?= substr($row['horadeldia_ajuste'], 0, -3); ?></td>                     
    <td <?=$style ?>><?= substr($row['horafin_ajuste'], 0, -3); ?></td>
    <td <?=$style ?>> </td>
    <td  <?=$style ?><?= ($row['is_virtual'] == 'true') ? 'style="color:red;"' : '' ?> >Ajuste </td>
    <!-- <td <?= ($row['is_virtual'] == 'true') ? 'style="color:red;"' : '' ?>><?= ($row['is_virtual'] == 'true') ? $row['odt_virtual'] : $row['numodt']; ?> </td> -->
    <td <?=$style ?>>0</td>
    <td <?=$style ?>><?= gmdate("H:i", (!empty($row['comida_ajuste'])) ? $row['dispon_ajuste'] - $row['comida_ajuste'] : $row['dispon_ajuste']); ?></td>
   
    <td <?=$style ?>><?= gmdate("H:i", $sum_dispon[$key]); ?></td>
    <?php
    //$sum_muerto[$key] += $row['seconds_muertos'];
    $haytinta=(isset($PTinta[$key][$i]))? array_sum($PTinta[$key][$i]) : 0;
    $tcomida=(!empty($row['comida_ajuste']))?$row['comida_ajuste']:0;
    $formulaajuste[$key][$i]=(($row['id_maquina']==9||$row['id_maquina']==22)? ($row['dispon_ajuste']-1500 )-$tcomida: (($row['id_maquina']==16 )? ($row['dispon_ajuste']-3600)-$tcomida : ($row['dispon_ajuste']-1200)-$tcomida));
    $extraerMuerto[$key][$i]=($formulaajuste[$key][$i]<=0)? 0 : $formulaajuste[$key][$i];
    $sum_muerto[$key] +=$extraerMuerto[$key][$i];
   // gmdate("H:i", $row['seconds_muertos'] + ((isset($alertA_Sum))?array_sum($alertA_Sum[$key][$i]) : 0)+$extraerMuerto); Asi estaba antes
?>
    <td <?=$style ?>><?= gmdate("H:i", $extraerMuerto[$key][$i]); ?></td>
    <td <?=$style ?>><?= gmdate("H:i", $sum_muerto[$key]); ?></td>
    <td <?=$style ?>><?= gmdate("H:i", ($formulaajuste[$key][$i]<=0)? $row['seconds_ajuste']+$haytinta : (($row['id_maquina']==9||$row['id_maquina']==22)? 1500 : (($row['id_maquina']==16)? 3600 : 1200))+$haytinta ); ?></td>
  <?php
    $sum_tiraje[$key] += ($formulaajuste[$key][$i]<=0)? $row['seconds_ajuste'] : (($row['id_maquina']==9||$row['id_maquina']==22 )? 1500 : (($row['id_maquina']==16)? 3600 : 1200));
     
?>
    <td <?=$style ?>><?= gmdate("H:i", $sum_tiraje[$key]); ?></td>
   
    <td <?=$style ?>>0</td>
    <td <?=$style ?>><?= $sum_esper[$key] ?></td>
    <td <?=$style ?>>0</td>
    <td <?=$style ?>><?= $sum_real[$key] ?></td>
    <td <?=$style ?>>0</td>
    <td <?=$style ?>><?= $sum_merm[$key] ?></td>
    <td <?=$style ?>>0</td>
    <td <?=$style ?>><?= $sum_calidad[$key] ?></td>
    <td <?=$style ?>>0</td>
    <td <?=$style ?>><?= $sum_defectos[$key] ?></td>
    <?php
    if (!empty($alert[$key][$i])||!empty($alertM[$key][$i])) {
?>
    <td <?=$style ?> colspan="3"><?= implode(' | ', $alert[$key][$i])." ".implode(' | ', $alertM[$key][$i]) ?></td>
    <?php
    } else {
?>
    <td <?=$style ?>></td>
    <td <?=$style ?>></td>
    <td <?=$style ?>></td>
    
    <?php
    }
?>
    <!--
   
    <td><?= round($row['desempenio'], 2); ?>%</td> -->
  </tr>
  <!-- ********** Termina TR Ajuste ********** -->
  <?php echo $comida_exist[$key] ?>
<?php
    //$sum_muerto[$key]+=$row['comida_tiro'];
    //$comida_exist2[$key] = (!empty($row['comida_tiro'])) ? 'Comida ' . gmdate("H:i", $row['ini_comida_tiro']) . "-" . gmdate("H:i", $row['fin_comida_tiro']) : '';
    $comida_exist2[$key] = (!empty($row['comida_tiro'])) ? '<tr><td colspan="24" style="color:#fff;background:#4D4D4D;"> COMIDA ' . gmdate("H:i", $row['ini_comida_tiro']) . "-" . gmdate("H:i", $row['fin_comida_tiro']).' </td></tr>' : '';
    
    
    while ($alertaT = mysqli_fetch_assoc($alertaTiroMuerto)) {
        
        $alertTiro[$key][$i][]=($alertaT['radios'] == 'Otro') ? $alertaT['observaciones'] . " <span class='alertime'>" . gmdate("H:i", $alertaT['inicio']) . "-" . gmdate("H:i", $alertaT['fin']) . "</span>" : $alertaT['radios'] . " <span class='alertime'>" . gmdate("H:i", $alertaT['inicio']) . "-" . gmdate("H:i", $alertaT['fin']) . "</span>";
          
        $alertT_Sum[$key][$i][] = $alertaT['alert_real'];
        
    }

    if (isset($alertT_Sum)) {
      $sum_muerto[$key] += array_sum($alertT_Sum[$key][$i]);
    }

    while ($tintaT = mysqli_fetch_assoc($alertaTiro)) {
       $AtintaT[$key][$i][]=($tintaT['radios'] == 'Otro') ? $tintaT['observaciones'] . " <span class='alertime'>" . gmdate("H:i", $tintaT['inicio']) . "-" . gmdate("H:i", $tintaT['fin']) . "</span>" : $tintaT['radios'] . " <span class='alertime'>" . gmdate("H:i", $tintaT['inicio']) . "-" . gmdate("H:i", $tintaT['fin']) . "</span>";
  $PTintaT[$key][$i][]=$tintaT['alert_real'];
}
 if (isset($PTintaT[$key][$i])) {
      $sum_tiraje[$key] += array_sum($PTintaT[$key][$i]);
    }  
    
    
?>
<!-- ********** Inicia TR Tiro ********** -->
      <tr style=" background-color: #EBEBEB;">
     <td <?=$style ?>><?= substr($row['horadeldia_tiraje'], 0, -3); ?></td>                     
    <td <?=$style ?>><?= substr($row['horafin_tiraje'], 0, -3); ?></td>
    <td <?=$style ?>> <?= ($row['is_virtual'] == 'true') ? $row['odt_virtual'] : $row['numodt'] ?> </td>
    <td  <?=$style ?><?= ($row['is_virtual'] == 'true') ? 'style="color:red;"' : '' ?> ><?= ($row['is_virtual'] == 'true') ? $row['elemento_virtual'] : $row['element']; ?> </td>
    <!-- <td <?= ($row['is_virtual'] == 'true') ? 'style="color:red;"' : '' ?>><?= ($row['is_virtual'] == 'true') ? $row['odt_virtual'] : $row['numodt']; ?> </td> -->
    <td <?=$style ?>><?=getStandar(($row['is_virtual'] == 'true')? $row['elemento_virtual'] : $row['element'] ,$row['id_maquina']); ?></td>
    <td <?=$style ?>><?= gmdate("H:i", (!empty($row['comida_tiro'])) ? $row['dispon_tiro'] - $row['comida_tiro'] : $row['dispon_tiro']) ?></td>
     <?php
    $comida2 = (!empty($row['comida_tiro'])) ? $row['dispon_tiro'] - $row['comida_tiro'] : $row['dispon_tiro'];
    $sum_dispon[$key] += $comida2;
?>
    <?php
    $sum_muerto[$key] += $row['seconds_muertos_tiro'];
?>
   <?php
    $sum_tiraje[$key] += $row['seconds_tiraje'];
?>
    <td <?=$style ?>><?= gmdate("H:i", $sum_dispon[$key]); ?></td>
    <td <?=$style ?>><?= gmdate("H:i", ((isset($alertT_Sum))? array_sum($alertT_Sum[$key][$i]) : 0) ); ?></td>
    <td <?=$style ?>><?= gmdate("H:i", $sum_muerto[$key]); ?></td>
    <td <?=$style ?>><?= gmdate("H:i", $row['seconds_tiraje']); ?></td>
    <td <?=$style ?>><?= gmdate("H:i", $sum_tiraje[$key]); ?></td>
    <td <?=$style ?>><?= $row['produccion_esperada']; ?></td>
    <td <?=$style ?>><?= $sum_esper[$key] ?></td>
    <td <?=$style ?>><?= $row['entregados'] - $row['merma_entregada']; ?></td>
    <td <?=$style ?>><?= $sum_real[$key] ?></td>
    <td <?=$style ?>><?= $row['merma_entregada']; ?></td>
    <td <?=$style ?>><?= $sum_merm[$key] ?></td>
    <td <?=$style ?>><?= $row['calidad']; ?></td>
    <td <?=$style ?>><?= $sum_calidad[$key] ?></td>
    <td <?=$style ?>><?= $row['defectos']; ?></td>
    <td <?=$style ?>><?= $sum_defectos[$key] ?></td>
    <?php
    if (!empty($alertTiro)||!empty($AtintaT)) {
?>
    <td <?=$style ?> colspan="3"> <?= implode(' | ', $alertTiro[$key][$i])." ". implode(' | ',$AtintaT[$key][$i]).(($row['cancelado']=='true')? 'TIRAJE CANCELADO':'') ?></td>
    <?php

    } else {
      if ($row['cancelado']=='true') {
      
?>
    <td colspan="3">TIRAJE CANCELADO</td>
        
    <?php
    }else{?>

    <td></td>
    <td></td>
    <td></td>

 <?php   }
}
?>
   
  </tr>
  <!-- ********** Termina TR Tiro ********** -->
   <?php echo $comida_exist2[$key] ?>          
  <?php
    $i++;
endwhile;
if ($resss->num_rows==0){
  echo "<tr ><td colspan='24' style='padding:20px;'>NO SE ENCONTRO INFORMACION PARA ESTE OPERADOR EN ESTE DIA</td></tr> ";
}
?>
  
  </tbody>
</table>
<?php
$treal = $sum_tiraje[$key];


?>


<br>
<?php
$dispon      = $treal / $sum_dispon[$key];
$dispon_tope = ($dispon * 100 > 100) ? 100 : $dispon * 100;
$desempenio  = ($sum_real[$key] + $sum_merm[$key]) / $sum_esper[$key];
$desemp_tope = ($desempenio * 100 > 100) ? 100 : $desempenio * 100;
$calidad      = ($sum_calidad[$key]) / $sum_real[$key];
$calidad_tope = ($calidad * 100 > 100) ? 100 : $calidad * 100;
 $final=round((($dispon_tope / 100) * ($desemp_tope / 100) * ($calidad_tope / 100)) * 100);
?>
<table id="resumes">
  <thead>
    <tr>
      <th colspan="2">DISPONIBILIDAD= <?= round($dispon_tope, 2) ?>%</th>
      <th colspan="4">DESEMPEÑO= <?= round($desemp_tope, 2) ?>%</th>
      <th colspan="2">CALIDAD= <?= round($calidad_tope, 2) ?>%</th>
      <th>ETE</th>
    </tr>
  </thead>
  <tbody>
  <tr>
    <td>TIEMPO REAL</td>
    <td><?= gmdate("H:i", $treal) ?></td>
    <td>PRODUCCION REAL</td>
    <td><?= $sum_real[$key] ?></td>
    
    <td>MERMA</td>
    <td><?= $sum_merm[$key] ?></td>
    <td>CALIDAD A LA PRIMERA</td>
    <td><?= $sum_calidad[$key] ?></td>
    <td rowspan="2" style="font-size: 30px;"><?= (is_nan($final))? '0':$final ?>%</td>
    </tr>
    <tr>
      <td>TIEMPO DISPONIBLE</td>
      <td><?= gmdate("H:i", $sum_dispon[$key]) ?></td>
      <td colspan="2">PRODUCCION ESPERADA</td>
      <td colspan="2"><?= $sum_esper[$key] ?></td>
      <td>PRODUCCION REAL</td>
      <td><?= $sum_real[$key] ?></td>
      
    </tr>
  </tbody>
</table>

</body>
<?php } ?>
</html>



<?php
$html= ob_get_clean();



$dompdf = new DOMPDF();
$dompdf->load_html($html);
$dompdf->set_paper('letter', 'landscape');
$dompdf->render();
$dompdf->stream("reporte_de_orden.pdf", array(
    'Attachment' => 0
));

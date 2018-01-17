<?php
      require('../saves/conexion.php');
     error_reporting(0);
     
 $numodt = $_POST['fecha'];
$userid = $_POST['iduser'];

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
<div class="conttabla2">
    
    <div class="datagrid">
<table id="datos" class="order-table table hoverable lightable">
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
    <th rowspan="2">Alertas</th>
    
    
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
?><tbody>
  <tr>
     <td ><?= substr($asa['horadeldia'], 0, -3); ?></td>                     
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
  </tbody>
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
    $comida_exist = (!empty($row['comida_ajuste'])) ? '<tr><td colspan="24" style="color:#fff;background:#ccc;"> COMIDA ' . gmdate("H:i", $row['ini_comida_ajuste']) . "-" . gmdate("H:i", $row['fin_comida_ajuste']).' </td></tr>' : '';
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
<tbody>
                          <tr>
     <td class="editable"  onClick="showEdit(this);"><?= substr($row['horadeldia_ajuste'], 0, -3); ?><div class="tooltiptext toolleft"><div class="tinput"><input id="time-<?=$row['idtiraje']; ?>" type="time" step="2"  name="" value="<?=$row['horadeldia_ajuste']?>"></div><div class="toolbutton save" title="Guardar" onclick="saveToDatabase('inicio','horadeldia_ajuste','<?=$row['idtiraje']; ?>')"></div><div class="toolbutton cancel" title="Cancelar"></div></div></td>                     
    <td class="editable"  onClick="showEdit(this);"><?= substr($row['horafin_ajuste'], 0, -3); ?><div class="tooltiptext toolleft"><div class="tinput"><input id="tfin-<?=$row['idtiraje']; ?>" type="time" step="2"  name="" value="<?=$row['horafin_ajuste']?>"></div><div class="toolbutton save" title="Guardar" onclick="saveToDatabase('fin','horafin_ajuste','<?=$row['idtiraje']; ?>')"></div><div class="toolbutton cancel" title="Cancelar"></div></div></td>
    <td> </td>
    <td <?= ($row['is_virtual'] == 'true') ? 'style=""' : '' ?> >Ajuste </td>
    <!-- <td <?= ($row['is_virtual'] == 'true') ? 'style=""' : '' ?>><?= ($row['is_virtual'] == 'true') ? $row['odt_virtual'] : $row['numodt']; ?> </td> -->
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
    <?php 
    $ajus_time[$i]=explode(':', $row['tiempo_ajuste']);
    $a_hour=$ajus_time[$i][0];
    $a_min=$ajus_time[$i][1];
    $a_sec=substr($ajus_time[$i][2], 0, -7);
    
    ?>
    <td class="editable"  onClick="showEdit(this);"><?= gmdate("H:i", ($formulaajuste[$i]<=0)? $row['seconds_ajuste']+$haytinta : (($row['id_maquina']==9||$row['id_maquina']==22)? 1500 : (($row['id_maquina']==16)? 3600 : 1200))+$haytinta ); ?>
      <div class="tooltiptext toolleft toolcifras"><div class="tinput"><input id="hour-<?=$row['idtiraje']; ?>" type="number"  min="0" max="99" class="cifra"  value="<?=$a_hour ?>"> : </div><div class="tinput"><input id="min-<?=$row['idtiraje']; ?>" type="number" class="cifra" min="0" max="99" value="<?=$a_min ?>"> : </div><div class="tinput"><input id="sec-<?=$row['idtiraje']; ?>" type="number"  min="0" max="99" class="cifra"  value="<?=$a_sec ?>"></div><div class="toolbutton save" title="Guardar" onclick="saveToDatabase('time','tiempo_ajuste','<?=$row['idtiraje']; ?>')"></div><div class="toolbutton cancel" title="Cancelar"></div></div>
    </td>
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
    <td style="font-size: 8px; " class="lightbox"><?= implode(' | ', $alert[$i])." ".implode(' | ', $alertM[$i]) ?></td>
    <?php
    } else {
?>
    <td></td>
    
    
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
    $comida_exist2 = (!empty($row['comida_tiro'])) ? '<tr><td colspan="24" style="color:#fff;background:#ccc;"> COMIDA ' . gmdate("H:i", $row['ini_comida_tiro']) . "-" . gmdate("H:i", $row['fin_comida_tiro']).' </td></tr>' : '';
    
    
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
 $elemtosend=($row['is_virtual'] == 'true')? $row['elemento_virtual'] : $row['element'];  
    
?>
<!-- ********** Inicia TR Tiro ********** -->
      <tr >
     <td class="editable" onClick="showEdit(this);"><?= substr($row['horadeldia_tiraje'], 0, -3); ?><div class="tooltiptext toolleft"><div class="tinput"><input id="timet-<?=$row['idtiraje']; ?>" type="time" step="2"  name="" value="<?=$row['horadeldia_tiraje']?>"></div><div class="toolbutton save" title="Guardar" onclick="saveToDatabase('iniciot','horadeldia_tiraje','<?=$row['idtiraje']; ?>')"></div><div class="toolbutton cancel" title="Cancelar"></div></div></td>                     
    <td class="editable" onClick="showEdit(this);"><?= substr($row['horafin_tiraje'], 0, -3); ?><div class="tooltiptext toolleft"><div class="tinput"><input id="tfint-<?=$row['idtiraje']; ?>" type="time" step="2"  name="" value="<?=$row['horafin_tiraje']?>"></div><div class="toolbutton save" title="Guardar" onclick="saveToDatabase('fint','horafin_tiraje','<?=$row['idtiraje']; ?>')"></div><div class="toolbutton cancel" title="Cancelar"></div></div></td>

<?php if ($row['is_virtual'] == 'true') { ?>
  <td class="editable" onClick="showEdit(this);"> <?= $row['odt_virtual'] ?><div class="tooltiptext toolleft"><div class="tinput"><input id="odt-<?=$row['idtiraje']; ?>" type="text" value="<?=$row['odt_virtual']?>"></div><div class="toolbutton save" title="Guardar" onclick="saveToDatabase('odt','odt_virtual','<?=$row['idtiraje']; ?>')"></div><div class="toolbutton cancel" title="Cancelar"></div></div> </td>

<?php }else{ ?>

   <td class="editable" onClick="showEdit(this);"> <?=$row['numodt'] ?><div class="tooltiptext toolleft"><div class="tinput"><input id="odt-<?=$row['idtiraje']; ?>" type="text" value="<?=$row['numodt']?>"></div><div class="toolbutton save" title="Guardar" onclick="saveToDatabase('odt','numodt','<?=$row['idtiraje']; ?>')"></div><div class="toolbutton cancel" title="Cancelar"></div></div>  </td> 

<?php } ?>

    
   

<?php if ($row['is_virtual'] == 'true') { ?>
  <td style="color:red;" > <?= $row['elemento_virtual'] ?> </td>

<?php }else{ ?>

    <td > <?= $row['element'] ?> </td>

<?php } ?>



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
    $t_time[$i]=explode(':', $row['tiempoTiraje']);
    $t_hour=$t_time[$i][0];
    $t_min=$t_time[$i][1];
    $t_sec=substr($t_time[$i][2], 0, -7);

?>
    <td><?= gmdate("H:i", $sum_dispon); ?></td>
    <td><?= gmdate("H:i", ((isset($alertT_Sum))? array_sum($alertT_Sum[$i]) : 0) ); ?></td>
    <td><?= gmdate("H:i", $sum_muerto); ?></td>
    <td class="editable"  onClick="showEdit(this);"><?= gmdate("H:i", $row['seconds_tiraje']); ?><div class="tooltiptext toolleft toolcifras"><div class="tinput"><input id="thour-<?=$row['idtiraje']; ?>" type="number"  class="cifra"  value="<?=$t_hour ?>"> : </div><div class="tinput"><input id="tmin-<?=$row['idtiraje']; ?>" type="number" class="cifra" value="<?=$t_min ?>"> : </div><div class="tinput"><input id="tsec-<?=$row['idtiraje']; ?>" type="number"  class="cifra"  value="<?=$t_sec ?>"></div><div class="toolbutton save" title="Guardar" onclick="saveToDatabase('ttime','tiempoTiraje','<?=$row['idtiraje']; ?>')"></div><div class="toolbutton cancel" title="Cancelar"></div></div></td>
    <td><?= gmdate("H:i", $sum_tiraje); ?></td>
    <td><?= $row['produccion_esperada']; ?></td>
    <td><?= $sum_esper ?></td>
    <td class="editable" onClick="showEdit(this);"><?= $row['entregados'] - $row['merma_entregada']; ?><div class="tooltiptext toolreal"><p>Buenos</p><div class="tinput"><input id="buen-<?=$row['idtiraje']; ?>" type="number" value="<?= $row['entregados'] - $row['merma_entregada']?>"></div><p>Merma</p><div class="tinput"><input id="merm-<?=$row['idtiraje']; ?>" type="number" value="<?=$row['merma_entregada']?>"></div><div class="toolbutton save" title="Guardar" onclick="saveToDatabase('real','entregados','<?=$row['idtiraje']; ?>')"></div><div class="toolbutton cancel" title="Cancelar"></div></div></td>
    <td><?= $sum_real ?></td>
    <td><?= $row['merma_entregada']; ?></td>
    <td><?= $sum_merm ?></td>
    <td><?= $row['calidad']; ?></td>
    <td><?= $sum_calidad ?></td>
    <td class="editable" onClick="showEdit(this);"><?= $row['defectos']; ?><div class="tooltiptext toolright"><div class="tinput"><input id="def-<?=$row['idtiraje']; ?>" type="number" value="<?=$row['defectos']?>"></div><div class="toolbutton save" title="Guardar" onclick="saveToDatabase('defectos','defectos','<?=$row['idtiraje']; ?>')"></div><div class="toolbutton cancel" title="Cancelar"></div></div></td>
    <td><?= $sum_defectos ?></td>
    <?php
    if (!empty($alertTiro)||!empty($AtintaT)) {
?>
    <td style="font-size: 8px; "> <?= implode(' | ', $alertTiro[$i])." ". implode(' | ',$AtintaT[$i])  ?></td>
    <?php

    } else {
?>
    <td>--</td>
    
    
    <?php
    }
?>
   
  </tr>
 </tbody>
  <!-- ********** Termina TR Tiro ********** -->
   <?php echo $comida_exist2 ?>          
  <?php
    $i++;
endwhile;
if ($resss->num_rows==0){
  echo "<tr ><td colspan='24' style='padding:20px;'>NO SE ENCONTRO INFORMACION PARA ESTE OPERADOR EN ESTE DIA</td></tr> ";
}
?>
  
  
</table>
<?php
$treal = $sum_tiraje;


?>

<br>
<?php
$dispon      = $treal / $sum_dispon;
$dispon_tope = ($dispon * 100 > 100) ? 100 : $dispon * 100;
$desempenio  = ($sum_real + $sum_merm) / $sum_esper;
$desemp_tope = ($desempenio * 100 > 100) ? 100 : $desempenio * 100;
$calidad      = ($sum_calidad) / $sum_real;
$calidad_tope = ($calidad * 100 > 100) ? 100 : $calidad * 100;
 $final=round((($dispon_tope / 100) * ($desemp_tope / 100) * ($calidad_tope / 100)) * 100);
?>
<table id="resumes"  class="order-table table hoverable lightable">
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
    <td><?= $sum_real ?></td>
    
    <td>MERMA</td>
    <td><?= $sum_merm ?></td>
    <td>CALIDAD A LA PRIMERA</td>
    <td><?= $sum_calidad ?></td>
    <td rowspan="2" style="font-size: 30px;"><?= (is_nan($final))? '0':$final ?>%</td>
    </tr>
    <tr>
      <td>TIEMPO DISPONIBLE</td>
      <td><?= gmdate("H:i", $sum_dispon) ?></td>
      <td colspan="2">PRODUCCION ESPERADA</td>
      <td colspan="2"><?= $sum_esper ?></td>
      <td>PRODUCCION REAL</td>
      <td><?= $sum_real ?></td>
      
    </tr>
  </tbody>
</table>

</div>
</div>
<div class="overlay"></div>

      <script type="text/javascript">
 
      var globeerror=false;
      var count=0;
      $(document).ready(function(){
 
        $('.lightbox').click(function(){
          $('.backdrop').animate({'opacity':'.50'}, 300, 'linear');
          $('.box').animate({'opacity':'1.00'}, 300, 'linear');
          $('.backdrop, .box').css('display', 'block');
        });
 
        $('.close').click(function(){
          close_box();
          close_box3();
        });
 
        $('.backdrop').click(function(){
          close_box();
        });
        $('.lightbox2').click(function(){
          $('.backdrop').animate({'opacity':'.50'}, 300, 'linear');
          $('.box2').animate({'opacity':'1.00'}, 300, 'linear');
          $('.backdrop, .box2').css('display', 'block');
        });
        $('.lightbox3').click(function(){
          $('.backdrop').animate({'opacity':'.50'}, 300, 'linear');
          $('.box3').animate({'opacity':'1.00'}, 300, 'linear');
          $('.backdrop, .box3').css('display', 'block');
        });
        $('.close2').click(function(){
          close_box2();
        });
 
        $('.backdrop').click(function(){
          close_box2();
          close_box3();
        });
      });
 
      function close_box()
      {
        
        $('.backdrop, .box').css('display', 'none');
      }
  function close_box2()
      {
       
        $('.backdrop, .box2').css('display', 'none');
      }
      function close_box3()
      {
        
        $('.backdrop, .box3').css('display', 'none');
      }

      $(".timeinput").keyup(function () {
    if (this.value.length == this.maxLength) {
      $(this).next('.timeinput').focus().select();
    }
});
$(".cancel").click(function (e) {
   globeerror=false;
   count=0;
   $('.globe-error').remove();
    e.stopPropagation()
      var div=$(this).parent('div');
      div.hide();
      console.log('cerrado');
      $('.overlay').animate({'opacity':'0'}, 300, 'linear', function(){
          $('.overlay').css('display', 'none');
        });
  
});


$(".overlay").click(function (e) {

  globeerror=true;
   if (globeerror&&count==0) {
    $('.tooltiptext').append('<span class="globe-error">¿Deseas guardar los cambios?</span>');
    count++;
   }

       
  
});
$(".cifra").click(function () {
  $(this).select();
});
$(".cifra").keyup(function () {
  console.log('len: '+this.value.length);
  

  var number = parseInt($(this).val());
  var value=$(this).val();
        if(number > 60){
           $(this).val("00");

    $(this).select();
        }
      /*  if (value.length==1) {

    $(this).val('0'+value);
    $(this).select();

  } */
        if (this.value.length==2 && this.value >0) {
   
          //$(this).closest('div').next().find(':input').first().focus();
        }
  
});


$(".cifra").change(function (e) {
  var value=$(this).val();
  console.log('cifra: '+value.length);
  if (value.length==1) {

    $(this).val('0'+value);

  }
    
  
});

    </script>
    </div>

    </div>
    
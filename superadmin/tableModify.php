<?php


include '../saves/conexion.php';

$fecha = $_POST['fecha'];
$userid = $_POST['iduser'];

function getAjusteAlerts($idtiro){
  include '../saves/conexion.php';
  $query="SELECT * FROM alertageneralajuste WHERE id_tiraje=".$idtiro;
  $alerts='';
  $getAlerts=$mysqli->query($query);
  while ($row=mysqli_fetch_assoc($getAlerts)) {
    $alerts.=((empty($row['radios'])||$row['radios']=='Otro')? $row['observaciones']:$row['radios']).' <span>'.substr($row['horadeldiaam'], 0, -3).'-'.substr($row['horafin_alerta'], 0, -3).'</span> | ';
  }

  echo $alerts;
}
function getTiroAlerts($idtiro){
  include '../saves/conexion.php';
  $query="SELECT * FROM alertamaquinaoperacion WHERE id_tiraje=".$idtiro;
  $alerts='';
  $getAlerts=$mysqli->query($query);
  while ($row=mysqli_fetch_assoc($getAlerts)) {
    $alerts.=((empty($row['radios'])||$row['radios']=='Otro')? $row['observaciones']:$row['radios']).' <span>'.substr($row['horadeldiaam'], 0, -3).'-'.substr($row['horafin_alerta'], 0, -3).'</span> | ';
  }
  echo $alerts;
}
function getMuertoAjuste($idtiro){
  include '../saves/conexion.php';
  $alert="SELECT SUM(TIME_TO_SEC(tiempoalertamaquina))AS muerto FROM alertageneralajuste WHERE es_tiempo_muerto='true' AND id_tiraje=".$idtiro;
  $dead="SELECT SUM(TIME_TO_SEC(tiempo_muerto))AS muerto2 FROM tiempo_muerto WHERE id_tiraje=".$idtiro;
  $tiempo_alerta=mysqli_fetch_assoc($mysqli->query($alert));
  $tiempo_muerto=mysqli_fetch_assoc($mysqli->query($dead));
  $total_muerto=$tiempo_alerta['muerto']+$tiempo_muerto['muerto2'];
  return $total_muerto;
}
function getMuertoTiro($idtiro){
  include '../saves/conexion.php';
  $alert="SELECT SUM(TIME_TO_SEC(tiempoalertamaquina))AS muerto FROM alertamaquinaoperacion WHERE es_tiempo_muerto='true' AND id_tiraje=".$idtiro;
  $tiempo_alerta=mysqli_fetch_assoc($mysqli->query($alert));
  $total_muerto=(empty($tiempo_alerta['muerto']))? 0: $tiempo_alerta['muerto'];
  return $total_muerto;
}

$query = "SELECT t.*,
TIME_FORMAT(t.horadeldia_ajuste, '%H:%i') AS inicio_ajuste,
TIME_FORMAT(t.horafin_ajuste, '%H:%i') AS fin_ajuste,
TIME_FORMAT(t.horadeldia_tiraje, '%H:%i') AS inicio_tiraje,
TIME_FORMAT(t.horafin_tiraje, '%H:%i') AS fin_tiraje,
(SELECT nombre_elemento FROM elementos WHERE id_elemento=t.producto )AS nombre_producto,
t.odt_virtual,
(SELECT numodt FROM ordenes WHERE idorden=t.id_orden )AS real_odt,
(SELECT piezas_por_hora FROM estandares WHERE id_elemento=t.producto AND id_proceso=t.id_proceso)AS estandar_real,
(SELECT piezas_por_hora FROM estandares WHERE id_elemento=t.id_elem_virtual AND id_proceso=t.id_proceso)AS estandar_virtual,
TIME_FORMAT(SEC_TO_TIME(TIME_TO_SEC(timediff(t.horafin_tiraje, t.horadeldia_tiraje))-IFNULL((SELECT TIME_TO_SEC(breaktime) FROM breaktime WHERE id_tiraje=t.idtiraje AND radios='Comida' AND seccion='tiro'),0)), '%H:%i')  AS tiempo_disponible_tiro,
TIME_FORMAT(SEC_TO_TIME(TIME_TO_SEC(timediff(t.horafin_ajuste, t.horadeldia_ajuste))-IFNULL((SELECT TIME_TO_SEC(breaktime) FROM breaktime WHERE id_tiraje=t.idtiraje AND radios='Comida' AND seccion='ajuste'),0)), '%H:%i')  AS tiempo_disponible_ajuste,
TIME_FORMAT(t.tiempoTiraje, '%H:%i') AS tiempo_real_tiraje,
TIME_FORMAT(t.tiempo_ajuste, '%H:%i') AS tiempo_real_ajuste,
t.buenos AS produccion_real,
t.merma_entregada AS merma,
TIME_TO_SEC(timediff(t.horadeldia_ajuste, '08:45:00'))AS desface,
(@s := @s + t.produccion_esperada) AS sum_esperada,
(@e := @e + t.buenos) AS sum_prod_real,
(@f := @f + t.merma_entregada) AS sum_merma,
(@g := @g + (t.buenos-t.merma_entregada)-t.defectos) AS sum_calidad,
(@h := @h + t.defectos) AS sum_defectos,
TIME_TO_SEC(t.horadeldia_ajuste)AS sorting,
TIME_TO_SEC(timediff(t.horafin_tiraje, t.horadeldia_tiraje))-IFNULL((SELECT TIME_TO_SEC(breaktime) FROM breaktime WHERE id_tiraje=t.idtiraje AND radios='Comida' AND seccion='tiro'),0)AS segundos_tiro,
TIME_TO_SEC(timediff(t.horafin_ajuste, t.horadeldia_ajuste))-IFNULL((SELECT TIME_TO_SEC(breaktime) FROM breaktime WHERE id_tiraje=t.idtiraje AND radios='Comida' AND seccion='ajuste' ),0)AS segundos_ajuste,
TIME_TO_SEC(t.tiempoTiraje)AS segundos_reales_tiro,
TIME_TO_SEC(t.tiempo_ajuste)AS segundos_reales_ajuste,
(t.buenos-t.merma_entregada)-t.defectos AS calidad,
(SELECT nombre_proceso FROM procesos_catalogo WHERE id_proceso=t.id_proceso)AS proceso,
(SELECT breaktime FROM breaktime WHERE id_tiraje = t.idtiraje AND seccion = 'ajuste' AND radios = 'Comida') AS comida_ajuste, 
(SELECT horadeldiaam FROM breaktime WHERE id_tiraje = t.idtiraje AND seccion = 'ajuste' AND radios = 'Comida') AS ini_comida_ajuste, 
(SELECT hora_fin_comida FROM breaktime WHERE id_tiraje = t.idtiraje AND seccion='ajuste' AND radios='Comida' AND id_usuario =t.id_user) AS fin_comida_ajuste, 
(SELECT breaktime FROM breaktime WHERE id_tiraje = t.idtiraje AND seccion='tiro' AND radios='Comida' AND id_usuario =t.id_user) AS comida_tiro, 
(SELECT horadeldiaam FROM breaktime WHERE id_tiraje = t.idtiraje AND seccion='tiro' AND radios='Comida' AND id_usuario=t.id_user) AS ini_comida_tiro, 
(SELECT hora_fin_comida FROM breaktime WHERE id_tiraje=t.idtiraje AND seccion='tiro' AND radios='Comida' AND id_usuario=t.id_user) AS fin_comida_tiro
FROM (SELECT @s := 0) dm,(SELECT @e := 0) de,(SELECT @f := 0) df,(SELECT @g := 0) dg,(SELECT @h := 0) dh,tiraje t WHERE fechadeldia_ajuste = '$fecha' AND t.id_user =$userid ORDER BY sorting ASC";

$real=mysqli_fetch_assoc($mysqli->query("SELECT TIME_FORMAT(SEC_TO_TIME((SUM(TIME_TO_SEC(tiempoTiraje))+SUM(TIME_TO_SEC(tiempo_ajuste))+IFNULL((SELECT SUM(TIME_TO_SEC(tiempoalertamaquina)) FROM alertageneralajuste WHERE fechadeldiaam = '$fecha' AND id_usuario =$userid AND es_tiempo_muerto='false'),0)+IFNULL((SELECT SUM(TIME_TO_SEC(tiempo)) FROM asaichi WHERE fechadeldia = '$fecha' AND id_usuario=$userid),0)-IFNULL(
(SELECT SUM(TIME_TO_SEC(breaktime)) FROM breaktime WHERE fechadeldiaam = '$fecha' AND id_usuario =$userid AND radios='Sanitario'),0))), '%H:%i') AS t_real,(SUM(TIME_TO_SEC(tiempoTiraje))+SUM(TIME_TO_SEC(tiempo_ajuste))+IFNULL((SELECT SUM(TIME_TO_SEC(tiempoalertamaquina)) FROM alertageneralajuste WHERE fechadeldiaam = '$fecha' AND id_usuario =$userid AND es_tiempo_muerto='false'),0)-IFNULL(
(SELECT SUM(TIME_TO_SEC(breaktime)) FROM breaktime WHERE fechadeldiaam = '$fecha' AND id_usuario =$userid AND radios='Sanitario'),0))+IFNULL((SELECT SUM(TIME_TO_SEC(tiempo)) FROM asaichi WHERE fechadeldia= '$fecha' AND id_usuario=$userid),0) AS sec_t_real FROM tiraje WHERE fechadeldia_ajuste = '$fecha' AND id_user =$userid"));



$disponible=mysqli_fetch_assoc($mysqli->query("SELECT TIME_FORMAT(SEC_TO_TIME(((SUM(TIME_TO_SEC(horafin_tiraje) - TIME_TO_SEC(horadeldia_tiraje))+SUM(TIME_TO_SEC(horafin_ajuste)-TIME_TO_SEC(horadeldia_ajuste)))-IFNULL(
(SELECT SUM(TIME_TO_SEC(breaktime)) FROM breaktime WHERE fechadeldiaam = '$fecha' AND id_usuario =$userid),0))+IFNULL((SELECT SUM(TIME_TO_SEC(tiempo_muerto)) FROM tiempo_muerto WHERE fecha= '$fecha' AND id_user =$userid AND seccion='desfase'),0)), '%H:%i') AS disponible, ((SUM(TIME_TO_SEC(horafin_tiraje) - TIME_TO_SEC(horadeldia_tiraje))+SUM(TIME_TO_SEC(horafin_ajuste)-TIME_TO_SEC(horadeldia_ajuste)))-IFNULL(
(SELECT SUM(TIME_TO_SEC(breaktime)) FROM breaktime WHERE fechadeldiaam = '$fecha' AND id_usuario =$userid),0))+IFNULL((SELECT SUM(TIME_TO_SEC(tiempo_muerto)) FROM tiempo_muerto WHERE fecha='$fecha' AND id_user=$userid AND seccion='desfase'),0) AS sec_disponible FROM tiraje WHERE fechadeldia_ajuste = '$fecha' AND id_user =$userid"));





$sumatorias=mysqli_fetch_assoc($mysqli->query("SELECT SUM(buenos)AS sum_prod_real,SUM(merma_entregada)AS sum_merma,SUM(produccion_esperada)AS sum_prod_esperada, SUM(buenos)-SUM(defectos)AS sum_calidad_primera FROM tiraje WHERE fechadeldia_ajuste = '$fecha' AND id_user =$userid"));

$asa_query = "SELECT *, TIME_TO_SEC(tiempo) AS tiempo_asaichi,TIME_TO_SEC(timediff(hora_fin,horadeldia)) AS dispon_asaichi, (SELECT TIME_TO_SEC(tiempo_muerto) FROM tiempo_muerto WHERE seccion='asaichi' AND fecha='$fecha' AND id_user=$userid) AS tmuerto_asa FROM asaichi WHERE fechadeldia='$fecha' AND id_usuario=$userid";

$resultado= $mysqli->query($query);


if (!$resultado) {
  printf($mysqli->error);
}
while ($row=mysqli_fetch_assoc($resultado) ){
 $tiros[]=$row;
}

$sum_disponible=0;
$sum_tiempo_real=0;
$sum_muerto=0;

$comida_exist='';
?>

 
<div class="conttabla2">
    
    <div class="datagrid"> 
<table id="datos" class="order-table table hoverable lightable">
<thead>
  <tr class="theader">
    <th colspan="2">Hora</th>
    <th rowspan="2">ODT</th>
    <th rowspan="2">Proceso</th>
    <th rowspan="2">Producto</th>
    <th rowspan="2">STD</th>
    <th colspan="2">Tiempo Disponible</th>
    <th colspan="2">Tiempo Muerto</th>
    <th colspan="2">Tiempo Real</th>
    <th colspan="2">Produccion Esperada</th>
    <th colspan="2">Produccion Real</th>
    <th colspan="2">Merma</th>
    <th colspan="2">Calidad a la Primera</th>
    <th colspan="2">Defectos</th>
    <th rowspan="2">Alertas</th>
    <th rowspan="2"></th>
  </tr>
  <tr class="theader">
    <th>Inicio</th>
    <th>Fin</th>
    <th>Real</th>
    <th>Acum</th>
    <th>Real</th>
    <th>Acum</th>
    <th>Real</th>
    <th>Acum</th>
    <th>Real</th>
    <th>Acum</th>
    <th>Real</th>
    <th>Acum</th>
    <th>Real</th>
    <th>Acum</th>
   <th>Real</th>
    <th>Acum</th>
    <th>Real</th>
    <th>Acum</th>
  </tr>
  </thead>

  <?php 
    
    if($resultado->num_rows>0){
    foreach ($tiros as $key => $tiro) {
            if ($key=='0') {
                   
              $sum_disponible+=$tiro['desface'];
            }
   ?>
   <tbody>
   <!-- ********** Inicia TR Ajuste ********** -->
  <tr>
    
     <td class="editable"  onClick="showEdit(this);"><?= $tiro['inicio_ajuste']; ?><div class="tooltiptext toolleft"><div class="tagtitle"><span>ODT:</span> <?=($tiro['is_virtual'] == 'true') ? $tiro['odt_virtual'] : $tiro['real_odt'] ?></div><div class="tinput"><input id="time-<?=$tiro['idtiraje']; ?>" type="time" step="2"  name="" value="<?=preg_replace('/\s+/', '', $tiro['horadeldia_ajuste'])?>"></div><div class="toolbutton save" title="Guardar" onclick="saveToDatabase('inicio','horadeldia_ajuste','<?=$tiro['idtiraje']; ?>')"></div><div class="toolbutton cancel" title="Cancelar"></div></div></td>

     <td class="editable"  onClick="showEdit(this);"><?= $tiro['fin_ajuste']; ?><div class="tooltiptext toolleft"><div class="tagtitle"><span>ODT:</span> <?=($tiro['is_virtual'] == 'true') ? $tiro['odt_virtual'] : $tiro['real_odt'] ?></div><div class="tinput"><input id="tfin-<?=$tiro['idtiraje']; ?>" type="time" step="2"  name="" value="<?=preg_replace('/\s+/', '', $tiro['horafin_ajuste'])?>"></div><div class="toolbutton save" title="Guardar" onclick="saveToDatabase('fin','horafin_ajuste','<?=$tiro['idtiraje']; ?>')"></div><div class="toolbutton cancel" title="Cancelar"></div></div></td>

    <?php if ($tiro['is_virtual'] == 'true') { ?>
    <td class="editable" onClick="showEdit(this);" rowspan="2"> <?= $tiro['odt_virtual'] ?><div class="tooltiptext toolleft"><div class="tagtitle"><span>ODT:</span> <?= $tiro['odt_virtual'] ?></div><div class="tinput"><input id="odt-<?=$tiro['idtiraje']; ?>" type="text" value="<?=$tiro['odt_virtual']?>"></div><div class="toolbutton save" title="Guardar" onclick="saveToDatabase('odt','odt_virtual','<?=$tiro['idtiraje']; ?>')"></div><div class="toolbutton cancel" title="Cancelar"></div></div> </td>

    <?php }else{ ?>

    <td class=""  rowspan="2"> <?=$tiro['real_odt'] ?><div class="tooltiptext toolleft"><div class="tagtitle"><span>ODT:</span> <?= $tiro['real_odt'] ?></div><div class="tinput"><input id="odt-<?=$tiro['idtiraje']; ?>" type="text" value="<?=$tiro['real_odt']?>"></div><div class="toolbutton save" title="Guardar" onclick="saveToDatabase('odt','numodt','<?=$tiro['idtiraje']; ?>')"></div><div class="toolbutton cancel" title="Cancelar"></div></div>  </td> 

    <?php } ?>

    
    <td rowspan="2"><?=$tiro['proceso']; ?></td>
    <td>Ajuste</td>
    
    <td>0</td>
    <td><?=$tiro['tiempo_disponible_ajuste']; ?></td>
    <?php $sum_disponible+=$tiro['segundos_ajuste'] ?>
    <td><?=gmdate("H:i", $sum_disponible) ?></td>
    <?php 
    $muerto_ajuste=getMuertoAjuste($tiro['idtiraje']);
    $sum_muerto+=$muerto_ajuste; ?>
    <td><?=gmdate("H:i",$muerto_ajuste) ?></td>
    <td><?=gmdate("H:i",$sum_muerto) ?></td>
    <td><?=$tiro['tiempo_real_ajuste']; ?></td>
   <?php $sum_tiempo_real+=$tiro['segundos_reales_ajuste'] ?>
    <td><?=gmdate("H:i", $sum_tiempo_real) ?></td>
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
    <td><?=getAjusteAlerts($tiro['idtiraje']) ?></td>
    <td class="delete-td" rowspan="<?=($comida_exist!='')? '3' : '2' ?>"><div  class="delete-tiro" data-tiroid="<?=$tiro['idtiraje'] ?>" onclick="deleteTiro(<?=$tiro['idtiraje'] ?>)"></div></td>
    </tr>
  <!-- ********** Inicia TR Tiro ********** -->
  <tr>
  <td class="editable" onClick="showEdit(this);"><?= $tiro['inicio_tiraje']; ?><div class="tooltiptext toolleft"><div class="tagtitle"><span>ODT:</span> <?=($tiro['is_virtual'] == 'true') ? $tiro['odt_virtual'] : $tiro['real_odt'] ?></div><div class="tinput"><input id="timet-<?=$tiro['idtiraje']; ?>" type="time" step="2"  name="" value="<?= preg_replace('/\s+/', '', $tiro['horadeldia_tiraje']) ?>"></div><div class="toolbutton save" title="Guardar" onclick="saveToDatabase('iniciot','horadeldia_tiraje','<?=$tiro['idtiraje']; ?>')"></div><div class="toolbutton cancel" title="Cancelar"></div></div></td> 

  <td class="editable" onClick="showEdit(this);"><?= $tiro['fin_tiraje']; ?><div class="tooltiptext toolleft"><div class="tagtitle"><span>ODT:</span> <?=($tiro['is_virtual'] == 'true') ? $tiro['odt_virtual'] : $tiro['real_odt'] ?></div><div class="tinput"><input id="tfint-<?=$tiro['idtiraje']; ?>" type="time" step="2"  name="" value="<?=preg_replace('/\s+/', '', $tiro['horafin_tiraje'])?>"></div><div class="toolbutton save" title="Guardar" onclick="saveToDatabase('fint','horafin_tiraje','<?=$tiro['idtiraje']; ?>')"></div><div class="toolbutton cancel" title="Cancelar"></div></div></td>
    
    <td><?=($tiro['is_virtual']=='true')? $tiro['elemento_virtual']: $tiro['nombre_producto']?></td>
    <td><?=($tiro['is_virtual']=='true')? $tiro['estandar_virtual']: $tiro['estandar_real']?></td>
    <td><?=$tiro['tiempo_disponible_tiro']; ?></td>
     <?php $sum_disponible+=$tiro['segundos_tiro'] ?>
    <td><?=gmdate("H:i", $sum_disponible) ?></td>
    <?php 
    $muerto_tiro=getMuertoTiro($tiro['idtiraje']);
    $sum_muerto+=$muerto_tiro; ?>
    <td><?=gmdate("H:i",$muerto_tiro) ?></td>
    <td><?=gmdate("H:i",$sum_muerto) ?></td>
       <?php
    
    $t_time=explode(':', $tiro['tiempoTiraje']);
    $t_hour=(!empty($tiro['tiempoTiraje']))? $t_time[0]:'00';
    $t_min=(!empty($tiro['tiempoTiraje']))? $t_time[1]:'00';
    $t_sec=(!empty($tiro['tiempoTiraje']))? substr($t_time[2], 0, -7):'00';

?>

    <td class="editable"  onClick="showEdit(this);"><?=  $tiro['tiempo_real_tiraje']; ?><div class="tooltiptext toolleft toolcifras"><div class="tagtitle"><span>ODT:</span> <?=($tiro['is_virtual'] == 'true') ? $tiro['odt_virtual'] : $tiro['real_odt'] ?></div><div class="tinput"><input id="thour-<?=$tiro['idtiraje']; ?>" type="number"  class="cifra"  value="<?=$t_hour ?>"> : </div><div class="tinput"><input id="tmin-<?=$tiro['idtiraje']; ?>" type="number" class="cifra" value="<?=$t_min ?>"> : </div><div class="tinput"><input id="tsec-<?=$tiro['idtiraje']; ?>" type="number"  class="cifra"  value="<?=$t_sec ?>"></div><div class="toolbutton save" title="Guardar" onclick="saveToDatabase('ttime','tiempoTiraje','<?=$tiro['idtiraje']; ?>')"></div><div class="toolbutton cancel" title="Cancelar"></div></div></td>

    
    <?php $sum_tiempo_real+=$tiro['segundos_reales_tiro'] ?>
    <td><?=gmdate("H:i", $sum_tiempo_real) ?></td>
    <td><?=round($tiro['produccion_esperada']); ?></td>
    <td><?=round($tiro['sum_esperada']); ?></td>

    

    <td class="editable" onClick="showEdit(this);"><?= round($tiro['produccion_real'],2); ?><div class="tooltiptext toolreal"><div class="tagtitle"><span>ODT:</span> <?=($tiro['is_virtual'] == 'true') ? $tiro['odt_virtual'] : $tiro['real_odt'] ?></div><p>Buenos</p><div class="tinput"><input id="buen-<?=$tiro['idtiraje']; ?>" type="number" value="<?= $tiro['buenos'] ?>"></div><p>Merma</p><div class="tinput"><input id="merm-<?=$tiro['idtiraje']; ?>" type="number" value="<?=$tiro['merma_entregada']?>"></div><div class="toolbutton save" title="Guardar" onclick="saveToDatabase('real','entregados','<?=$tiro['idtiraje']; ?>')"></div><div class="toolbutton cancel" title="Cancelar"></div></div></td>


    <td><?=round($tiro['sum_prod_real'],2); ?></td>
    <td><?=round($tiro['merma'],2); ?></td>
    <td><?=round($tiro['sum_merma'],2); ?></td>
    <td><?=round($tiro['calidad'],2); ?></td>
    <td><?=round($tiro['sum_calidad'],2); ?></td>
    


     <td class="editable" onClick="showEdit(this);"><?= $tiro['defectos']; ?><div class="tooltiptext toolright"><div class="tagtitle"><span>ODT:</span> <?=($tiro['is_virtual'] == 'true') ? $tiro['odt_virtual'] : $tiro['real_odt'] ?></div><div class="tinput"><input id="def-<?=$tiro['idtiraje']; ?>" type="number" value="<?=$tiro['defectos']?>"></div><div class="toolbutton save" title="Guardar" onclick="saveToDatabase('defectos','defectos','<?=$tiro['idtiraje']; ?>')"></div><div class="toolbutton cancel" title="Cancelar"></div></div></td>

    <td><?=$tiro['sum_defectos']; ?></td>
    <td><?=($tiro['cancelado']=='true')? 'TIRAJE CANCELADO':(($tiro['tipo_ejecucion']=='pendiente')? 'EL CAMBIO QUEDO PENDIENTE':(($tiro['tipo_ejecucion']=='retomado')? 'EL CAMBIO FUE RETOMADO':getTiroAlerts($tiro['idtiraje']))) ?></td>
    
  </tr>
  <?php if(!empty($tiro['comida_ajuste'])||!empty($tiro['comida_tiro'])){ ?>
  <tr>
    <td colspan="24">COMIDA</td>
  </tr>
  <?php } ?>
  </tbody>
 <?php } }else{
   echo "<tbody><tr><td colspan='24'>No se encontro informacion para este operario en el dia seleccionado</td></tr></tbody>";     
    }
$dispon=(($disponible['sec_disponible']<=0)? 0: ($real['sec_t_real']/$disponible['sec_disponible'])*100);
$dispon_tope= ($dispon>100)?100:$dispon;
$desemp=( ($sumatorias['sum_prod_esperada']<=0)? 0: (($sumatorias['sum_prod_real']+$sumatorias['sum_merma'])/$sumatorias['sum_prod_esperada'])*100);
$desemp_tope=($desemp>100)?100:$desemp;
$calidad=(($sumatorias['sum_prod_real']<=0)? 0: ($sumatorias['sum_calidad_primera']/$sumatorias['sum_prod_real'])*100);
$calidad_tope=($calidad>100)?100:$calidad;
$final=(($calidad_tope/100)*($desemp_tope/100)*($dispon_tope/100))*100;
 ?>
</table>

<table id="resumes"  class="order-table table hoverable lightable">
  <thead>
    <tr class="resume-head">
      <td colspan="2">DISPONIBILIDAD= <?= round($dispon_tope, 2) ?>%</td>
      <td colspan="4">DESEMPEÑO= <?= round($desemp_tope, 2) ?>%</td>
      <td colspan="2">CALIDAD= <?= round($calidad_tope, 2) ?>%</td>
      <td>ETE</td>
    </tr>
  </thead>
  <tbody>
  <tr>
    <td>TIEMPO REAL</td>
    <td><?= $real['t_real'] ?></td>
    <td>PRODUCCION REAL</td>
    <td><?= round($sumatorias['sum_prod_real'],2) ?></td>
    <td>MERMA</td>
    <td><?=round($sumatorias['sum_merma'],2) ?></td>
    <td>CALIDAD A LA PRIMERA</td>
    <td><?= round($sumatorias['sum_calidad_primera'],2) ?></td>
    <td rowspan="2" style="font-size: 30px;"><?= (is_nan($final))? '0':round($final,2) ?>%</td>
    </tr>
    <tr>
      <td>TIEMPO DISPONIBLE</td>
      <td><?= $disponible['disponible'] ?></td>
      <td colspan="2">PRODUCCION ESPERADA</td>
      <td colspan="2"><?= round($sumatorias['sum_prod_esperada']) ?></td>
      <td>PRODUCCION REAL</td>
      <td><?= round($sumatorias['sum_prod_real'],2) ?></td>
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
          $('.newtiro-modal').animate({'opacity':'1.00'}, 300, 'linear');
          $('.backdrop, ').css('display', 'block');
        });
 
        $('.close3').click(function(){
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
        
        $('.backdrop, .box,.newtiro-modal').css('display', 'none');
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


function deleteTiro(id){
  console.log(id);
}

function deleteTiro(id){
  var user=$('#filterElem').val();
  var fecha=$('#datepicker').val();
    $('<div></div>').appendTo('body')
                    .html('<div><h6>Estas seguro de querer borrar este tiro?</h6></div>')
                    .dialog({
                        modal: true, title: 'Eliminar tiro', zIndex: 10000, autoOpen: true,
                        width: 'auto', resizable: false,
                        buttons: {
                            Si: function () {
                                // $(obj).removeAttr('onclick');                                
                                // $(obj).parents('.Parent').remove();
                                
                                $.ajax({
                                    url: "updates.php",
                                    type: "POST",
                                    data:{form:'delete-tiro',id:id},
                                    success: function(data){
                                     $.ajax({
                                        url: "tableModify.php",
                                        type: "POST",
                                        data:{iduser:user,fecha:fecha},
                                        success: function(data){
                                        $('.div-tabla').html(data);
                                        
                                        }        
                                       });


                                    }        
                                   });
                                
                                $(this).dialog("close");
                            },
                            No: function () {                                                             
                            
                                $(this).dialog("close");
                            }
                        },
                        close: function (event, ui) {
                            $(this).remove();
                        }
                    });
    };
    </script>
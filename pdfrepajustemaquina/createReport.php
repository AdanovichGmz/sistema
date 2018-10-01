<?php
error_reporting(0);
require_once("dompdf2/autoload.inc.php");
include '../saves/conexion.php';
use Dompdf\Dompdf;
$fecha = $_POST['id'];
$userid = $_POST['iduser'];
$apell=($userid==14)? '':(($userid==13)?'':'');
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
TIME_TO_SEC(timediff(t.horadeldia_ajuste, '08:45:00.000000'))AS desfase,
(@s := @s + t.produccion_esperada) AS sum_esperada,
(@e := @e + t.buenos) AS sum_prod_real,
(@f := @f + t.merma_entregada) AS sum_merma,
(@g := @g + (t.buenos)-t.defectos) AS sum_calidad,
(@h := @h + t.defectos) AS sum_defectos,
TIME_TO_SEC(t.horadeldia_ajuste)AS sorting,
TIME_TO_SEC(timediff(t.horafin_tiraje, t.horadeldia_tiraje))-IFNULL((SELECT TIME_TO_SEC(breaktime) FROM breaktime WHERE id_tiraje=t.idtiraje AND radios='Comida' AND seccion='tiro'),0)AS segundos_tiro,
TIME_TO_SEC(timediff(t.horafin_ajuste, t.horadeldia_ajuste))-IFNULL((SELECT TIME_TO_SEC(breaktime) FROM breaktime WHERE id_tiraje=t.idtiraje AND radios='Comida' AND seccion='ajuste' ),0)AS segundos_ajuste,
TIME_TO_SEC(t.tiempoTiraje)AS segundos_reales_tiro,
TIME_TO_SEC(t.tiempo_ajuste)AS segundos_reales_ajuste,
(t.buenos)-t.defectos AS calidad,
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

$asa_query = "SELECT *, TIME_TO_SEC(tiempo) AS tiempo_asaichi,TIME_TO_SEC(timediff(hora_fin,horadeldia)) AS dispon_asaichi,TIME_FORMAT(horadeldia, '%H:%i') AS inicio_asa,TIME_FORMAT(hora_fin, '%H:%i') AS fin_asa,TIME_FORMAT(tiempo, '%H:%i') AS tiempo_real_asa,(SELECT TIME_TO_SEC(tiempo_muerto) FROM tiempo_muerto WHERE seccion='asaichi' AND fecha='$fecha' AND id_user=$userid) AS tmuerto_asa FROM asaichi WHERE fechadeldia='$fecha' AND id_usuario=$userid";
$getAsa=$mysqli->query($asa_query);

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
?>
<?php
ob_start();
?>

  <style>
  @page{
    margin:1.3em
}
 #info{
    font-family:Arial,Helvetica,sans-serif;
    border-collapse:collapse;
    width:100%;
    text-align:center;
    font-size:9px
}
 #info td{
    border:1px solid #ccc;
    padding:2px
}
 #info tbody:nth-child(even){
    background-color:#EBEBEB
}


.theader{
    text-transform:uppercase;
    text-align:center;
    background-color:#000;
    color:#fff;
    font-size:7px
}
#resumen{
    font-family:Arial,Helvetica,sans-serif;
    border-collapse:collapse;
    width:100%;
    text-align:center;
    font-size:8px
}
#resumen td{
    border:1px solid #ccc;
    padding:2px
}

.resume-head{
    text-transform:uppercase;
    text-align:center;
    font-size:10px;
    
}
#head{
    font-family:Arial,Helvetica,sans-serif;
    border-collapse:collapse;
    width:100%;
    text-align:center;
    font-size:9px
}
#head td{
    border:1px solid #ccc;
    padding:1px
}
.head-header{
    text-transform:uppercase;
    text-align:center;
    font-size:8px;

}
#head img{
    width:100px
}

</style>

<table id="head">
<tr class="head-header">
  <td rowspan="2" style="width: 120px;"><img src="../img/logoDerecha.png"></td>
  <td rowspan="2">REPORTE DIARIO ETE</td>
  <td>OPERADOR</td>
  <td>MAQUINA</td>
  <td>TURNO</td>
  <td>FECHA</td>
</tr>
 <?php $getUser=mysqli_fetch_assoc($mysqli->query("SELECT logged_in FROM usuarios WHERE id=".$userid)) ?>
  <tr>
   <td><?=$getUser['logged_in'].' '.$apell ?></td>
   <td></td>
   <td></td>
   <td><?=$fecha ?></td>
    </tr>
  </tbody>
</table>
<div style="height: 8px;"></div>
<table id="info">
<thead>
  <tr class="theader">
    <td colspan="2">Hora</td>
    <td rowspan="2">ODT</td>
    <td rowspan="2">Proceso</td>
    <td rowspan="2">Producto</td>
    <td rowspan="2">STD</td>
    <td colspan="2">Tiempo Disponible</td>
    <td colspan="2">Tiempo Muerto</td>
    <td colspan="2">Tiempo Real</td>
    <td colspan="2">Produccion Esperada</td>
    <td colspan="2">Produccion Real</td>
    <td colspan="2">Merma</td>
    <td colspan="2">Calidad a la Primera</td>
    <td colspan="2">Defectos</td>
    <td rowspan="2">Alertas</td>
  </tr>
  <tr class="theader">
    <td>Inicio</td>
    <td>Fin</td>td
    <td>Real</td>
    <td>Acum</td>
    <td>Real</td>
    <td>Acum</td>
    <td>Real</td>
    <td>Acum</td>
    <td>Real</td>
    <td>Acum</td>
    <td>Real</td>
    <td>Acum</td>
    <td>Real</td>
    <td>Acum</td>
   <td>Real</td>
    <td>Acum</td>
    <td>Real</td>
    <td>Acum</td>
  </tr>
  </thead>

  <?php if ($getAsa->num_rows>0) {
  while ($rowAsa=mysqli_fetch_assoc($getAsa)) {
  $sum_disponible+=$rowAsa['dispon_asaichi'];
  $sum_tiempo_real+=$rowAsa['tiempo_asaichi'];


   ?>
   <tbody>
    <tr>
    <td><?=$rowAsa['inicio_asa']; ?></td>
    <td><?=$rowAsa['fin_asa']; ?></td>
    
    <td colspan="4">Asaichii</td>
    
    <td><?=gmdate("H:i",$rowAsa['dispon_asaichi']); ?></td>
    <td><?=gmdate("H:i", $sum_disponible) ?></td>
    <?php 
   
    $sum_muerto+=$rowAsa['tmuerto_asa']; ?>
    <td><?=gmdate("H:i",$rowAsa['tmuerto_asa']) ?></td>
    <td><?=gmdate("H:i",$sum_muerto) ?></td>
    <td><?=gmdate("H:i",$rowAsa['tiempo_asaichi']) ?></td>
   
    <td><?=gmdate("H:i",$sum_tiempo_real) ?></td>
    
    <td colspan="11"></td>

  </tr>
  </tbody>

<?php } } ?>

  <?php foreach ($tiros as $key => $tiro) {
    if ($key=='0') {
      $sum_disponible+=$tiro['desfase'];
    }
   ?>
   <tbody>
  <tr>
    <td><?=$tiro['inicio_ajuste']; ?></td>
    <td><?=$tiro['fin_ajuste']; ?></td>
    <td rowspan="2"><?=($tiro['is_virtual']=='true')? $tiro['odt_virtual']: $tiro['real_odt']?></td>
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

  </tr>
  
  <tr>
    <td><?=$tiro['inicio_tiraje']; ?></td>
    <td><?=$tiro['fin_tiraje']; ?></td>
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
    <td><?=$tiro['tiempo_real_tiraje']; ?></td>
    <?php $sum_tiempo_real+=$tiro['segundos_reales_tiro'] ?>
    <td><?=gmdate("H:i", $sum_tiempo_real) ?></td>
    <td><?=round($tiro['produccion_esperada']); ?></td>
    <td><?=round($tiro['sum_esperada']); ?></td>
    <td><?=round($tiro['produccion_real'],1); ?></td>
    <td><?=round($tiro['sum_prod_real'],1); ?></td>
    <td><?=round($tiro['merma'],1); ?></td>
    <td><?=round($tiro['sum_merma'],1); ?></td>
    <td><?=round($tiro['calidad'],1); ?></td>
    <td><?=round($tiro['sum_calidad'],1); ?></td>
    <td><?=$tiro['defectos']; ?></td>
    <td><?=$tiro['sum_defectos']; ?></td>
    <td><?=($tiro['cancelado']=='true')? 'TIRAJE CANCELADO':getTiroAlerts($tiro['idtiraje']) ?></td>
    
  </tr>
  <?php if(!empty($tiro['comida_ajuste'])||!empty($tiro['comida_tiro'])){ ?>
  <tr>
    <td colspan="23">COMIDA <?=(!empty($tiro['comida_ajuste'])?)$tiro['ini_comida_ajuste'].'-'.$tiro['fin_comida_ajuste'] : ((!empty($tiro['comida_ajuste']))? $tiro['ini_comida_tiro'].'-'.$tiro['fin_comida_tiro'] :'');
 ?></td>
  </tr>
  <?php } ?>
  </tbody>
 <?php } 
$dispon=(($disponible['sec_disponible']<=0)? 0: ($real['sec_t_real']/$disponible['sec_disponible'])*100);
$dispon_tope= ($dispon>100)?100:$dispon;
$desemp=( ($sumatorias['sum_prod_esperada']<=0)? 0: (($sumatorias['sum_prod_real']+$sumatorias['sum_merma'])/$sumatorias['sum_prod_esperada'])*100);
$desemp_tope=($desemp>100)?100:$desemp;
$calidad=(($sumatorias['sum_prod_real']<=0)? 0: ($sumatorias['sum_calidad_primera']/$sumatorias['sum_prod_real'])*100);
$calidad_tope=($calidad>100)?100:$calidad;
$final=(($calidad_tope/100)*($desemp_tope/100)*($dispon_tope/100))*100;
 ?>
</table>
<div style="height: 8px;"></div>
<table id="resumen">
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
    <td><?= $sumatorias['sum_prod_real'] ?></td>
    <td>MERMA</td>
    <td><?=$sumatorias['sum_merma'] ?></td>
    <td>CALIDAD A LA PRIMERA</td>
    <td><?= $sumatorias['sum_calidad_primera'] ?></td>
    <td rowspan="2" style="font-size: 30px;"><?= (is_nan($final))? '0':round($final,2) ?>%</td>
    </tr>
    <tr>
      <td>TIEMPO DISPONIBLE</td>
      <td><?= $disponible['disponible'] ?></td>
      <td colspan="2">PRODUCCION ESPERADA</td>
      <td colspan="2"><?= round($sumatorias['sum_prod_esperada']) ?></td>
      <td>PRODUCCION REAL</td>
      <td><?= $sumatorias['sum_prod_real'] ?></td>
    </tr>
  </tbody>
</table>

<?php
 

$html = ob_get_clean();

$dompdf = new Dompdf();
$dompdf->load_html($html);
$dompdf->set_paper('letter', 'landscape');
$dompdf->render();
$dompdf->stream("reporte_de_orden.pdf", array(
    'Attachment' => 0
)); 
?>
<?php
 
function isActive($machineID){
    require('../saves/conexion.php');
    $today=date("d-m-Y");
    $check=$mysqli->query("SELECT * FROM operacion_estatus WHERE fecha='$today' AND maquina=$machineID");  
if ($check->num_rows==0) {
 return false;
}else{
  return true;
}
  }
function personalData($estacion,$userID,$userName,$isMember){
    date_default_timezone_set("America/Mexico_City");
require('../saves/conexion.php');
$today     = date("d-m-Y");

if ($isMember=='true') {
  $stationInfo=mysqli_fetch_assoc($mysqli->query("SELECT s.*,(SELECT orden_actual FROM sesiones WHERE id_sesion=s.id_sesion_equipo)AS orden_actual FROM sesion_equipo s WHERE miembro=$userID AND fecha='$today'"));
}else{
$stationInfo=mysqli_fetch_assoc($mysqli->query("SELECT * FROM sesiones WHERE estacion=$estacion AND fecha='$today' "));
}

$userInfo=mysqli_fetch_assoc($mysqli->query("SELECT * FROM usuarios WHERE id=".$userID)); 
 $real=mysqli_fetch_assoc($mysqli->query("SELECT TIME_FORMAT(SEC_TO_TIME((SUM(TIME_TO_SEC(tiempoTiraje))+SUM(TIME_TO_SEC(tiempo_ajuste))+IFNULL((SELECT SUM(TIME_TO_SEC(tiempoalertamaquina)) FROM alertageneralajuste WHERE fechadeldiaam = '$today' AND id_usuario =$userID AND es_tiempo_muerto='false'),0)+IFNULL((SELECT SUM(TIME_TO_SEC(tiempo)) FROM asaichi WHERE fechadeldia = '$today' AND id_usuario=$userID),0)-IFNULL(
(SELECT SUM(TIME_TO_SEC(breaktime)) FROM breaktime WHERE fechadeldiaam = '$today' AND id_usuario =$userID AND radios='Sanitario'),0))), '%H:%i') AS t_real,(SUM(TIME_TO_SEC(tiempoTiraje))+SUM(TIME_TO_SEC(tiempo_ajuste))+IFNULL((SELECT SUM(TIME_TO_SEC(tiempoalertamaquina)) FROM alertageneralajuste WHERE fechadeldiaam = '$today' AND id_usuario =$userID AND es_tiempo_muerto='false'),0)-IFNULL(
(SELECT SUM(TIME_TO_SEC(breaktime)) FROM breaktime WHERE fechadeldiaam = '$today' AND id_usuario =$userID AND radios='Sanitario'),0))+IFNULL((SELECT SUM(TIME_TO_SEC(tiempo)) FROM asaichi WHERE fechadeldia= '$today' AND id_usuario=$userID),0) AS sec_t_real FROM tiraje WHERE fechadeldia_ajuste = '$today' AND id_user =$userID"));

$disponible=mysqli_fetch_assoc($mysqli->query("SELECT TIME_FORMAT(SEC_TO_TIME(((SUM(TIME_TO_SEC(horafin_tiraje) - TIME_TO_SEC(horadeldia_tiraje))+SUM(TIME_TO_SEC(horafin_ajuste)-TIME_TO_SEC(horadeldia_ajuste)))-IFNULL(
(SELECT SUM(TIME_TO_SEC(breaktime)) FROM breaktime WHERE fechadeldiaam = '$today' AND id_usuario =$userID),0))+IFNULL((SELECT SUM(TIME_TO_SEC(tiempo_muerto)) FROM tiempo_muerto WHERE fecha= '$today' AND id_user =$userID AND seccion='desfase'),0)), '%H:%i') AS disponible, ((SUM(TIME_TO_SEC(horafin_tiraje) - TIME_TO_SEC(horadeldia_tiraje))+SUM(TIME_TO_SEC(horafin_ajuste)-TIME_TO_SEC(horadeldia_ajuste)))-IFNULL(
(SELECT SUM(TIME_TO_SEC(breaktime)) FROM breaktime WHERE fechadeldiaam = '$today' AND id_usuario =$userID),0))+IFNULL((SELECT SUM(TIME_TO_SEC(tiempo_muerto)) FROM tiempo_muerto WHERE fecha='$today' AND id_user=$userID AND seccion='desfase'),0) AS sec_disponible FROM tiraje WHERE fechadeldia_ajuste = '$today' AND id_user =$userID"));

 

$sumatorias=mysqli_fetch_assoc($mysqli->query("SELECT SUM(buenos)AS sum_prod_real,SUM(merma_entregada)AS sum_merma,SUM(produccion_esperada)AS sum_prod_esperada, SUM(buenos)-SUM(defectos)AS sum_calidad_primera FROM tiraje WHERE fechadeldia_ajuste = '$today' AND id_user =$userID"));
 
$dispon=(($disponible['sec_disponible']<=0)? 0: ($real['sec_t_real']/$disponible['sec_disponible'])*100);
$dispon_tope= ($dispon>100)?100:$dispon;
$desemp=( ($sumatorias['sum_prod_esperada']<=0)? 0: (($sumatorias['sum_prod_real']+$sumatorias['sum_merma'])/$sumatorias['sum_prod_esperada'])*100);
$desemp_tope=($desemp>100)?100:$desemp;
$calidad=(($sumatorias['sum_prod_real']<=0)? 0: ($sumatorias['sum_calidad_primera']/$sumatorias['sum_prod_real'])*100);
$calidad_tope=($calidad>100)?100:$calidad;
$final=(($calidad_tope/100)*($desemp_tope/100)*($dispon_tope/100))*100;

  
  
      $grafica='<script type="text/javascript">var userid=document.getElementById('.$userID.');drawChart(userid,'.$dispon_tope.','.$desemp_tope.','.$calidad_tope.');
  </script>';
  $foto=(empty($userInfo['foto']))? 'images/default.jpg':$userInfo['foto'];  
 $active=($stationInfo['active']=='false'||empty($stationInfo['active']))? 'disabled':'';
    $outtime=($stationInfo['en_tiempo']=='false')? 'outtime':'';
    $credencial='<div class="personal '.$active.'" data-name="'.$userName.'">
    <div class='.$outtime.'></div> <div class="ete-photo '.$stationInfo['actividad_actual'].'"><div class="person-photo" style=background:url("../'.$foto.'")></div><div class="santa"></div>
    <div class="ete-num">'.round($final).'%</div>

    </div>
    <div class="machinename">'.(($stationInfo['actividad_actual']=='comida')? "comida/wc" : $stationInfo['actividad_actual']).'</div>
    <div class="ete-stat " style="position:relative;overflow:visible;">
      <table>
      <thead>
      <tr class="trh">
        <td>Orden Actual:</td>
        <td class="">'.$stationInfo['orden_actual'].'</td>
        
        </tr></thead>
        <tbody>
        <tr style="font-size: 10px;"><td>&nbsp</td>
        <td>&nbsp</td>
        </tr>
        
        </tbody>
      </table>
      <div id="'.$userID.'" style="overflow: visible;position:absolute;bottom:0;width: 97%; height: 75%;"></div>
    </div></div>'.$grafica.'';
  echo $credencial;
}
require('../saves/conexion.php');
$getUsers=$mysqli->query("SELECT u.*,(SELECT id_estacion FROM usuarios_estaciones WHERE id_usuario=u.id)AS id_estacion FROM usuarios u WHERE u.app_active='true' OR team_member='true' ");
if (!$getUsers) {
  printf($mysqli->error);
}
$x =$getUsers->num_rows;
$i=0;
  ?>


<div class="prod-container">

  
<?php while ($row=mysqli_fetch_assoc($getUsers)) {
  if ( ($i % 6) == 0 ){
echo '<div class="carousel-page" '.((isset($_POST['display'])&&$i==0)? 'style="display:block"':'').'>';
}           
  personalData($row['id_estacion'],$row['id'],$row['logged_in'],$row['team_member']);
  if (((($i +1) % 6) == 0) || (($i+1)==$x)){
echo '</div>';
}
  $i++;
} ?>
 
   
 
</div>
<script>
  var wind = $(window).height();
$('.personal').height((wind/2)-25);
var grafics=((wind/2)-25)-140;
$('.ete-stat').height(grafics);



</script>
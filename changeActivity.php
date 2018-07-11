<?php
error_reporting(0);
require('classes/functions.class.php');
$log = new Functions();
session_start();
date_default_timezone_set("America/Mexico_City"); 
 require('saves/conexion.php');
 $tiro=$_POST['tiro'];
$ontime=$_POST['ontime'];
$section=$_POST['section'];
$hour=date(" H:i:s", time());
$today=date("d-m-Y");
$proceso=$_SESSION['processID'];
$sesion=$_SESSION['stat_session'];
$station_id=$_SESSION['stationID'];
 $machineID=$_SESSION['processID'];
$userID=$_SESSION['idUser'];
$tiempo=$_POST['tiempo'];
if ($_POST['save_change']=='true'){
 
$_SESSION['pending_exist']='true';



if ($ontime=='false') {
              $getAlerts=$mysqli->query("SELECT SUM(TIME_TO_SEC(tiempoalertamaquina)) AS total_ajuste FROM alertageneralajuste WHERE id_tiraje=".$tiro);
              $alerts=mysqli_fetch_assoc($getAlerts);

              $seconds_reg = strtotime("1970-01-01 $tiempo UTC");
              $total_muerto=($seconds_reg+$alerts['total_ajuste'])-$_SESSION['ajusteStandard'];

              $muerto_format=gmdate("H:i:s",$total_muerto);

            $tiempoajuste="'".gmdate("H:i:s",$_SESSION['ajusteStandard'])."'";
                
             $deadquery     = "INSERT INTO tiempo_muerto (id_tiempo_muerto, tiempo_muerto,fecha,id_estacion,id_user,numodt,id_orden, seccion,hora_del_dia,id_tiraje,id_sesion) VALUES (null,'$tiempo','$today','$machineID',$userID,null,null,'ajuste','$hour',$tiro,".$_SESSION['stat_session'].")";
            
            $deadresultado = $mysqli->query($deadquery);
            if (!$deadresultado) {
              $log->lwrite($deadquery,date("d-m-Y").'_ERROR_DE_COLA');
            }
            
            }else{

              $getAlerts=$mysqli->query("SELECT SUM(TIME_TO_SEC(tiempoalertamaquina)) AS total_ajuste FROM alertageneralajuste WHERE id_tiraje=".$tiro);
              $alerts=mysqli_fetch_assoc($getAlerts);
              $time_negative = strtotime("1970-01-01 $tiempo UTC");
              $time_positive=$_SESSION['ajusteStandard']-$time_negative;
              
              $total_tiempo=($time_positive+$alerts['total_ajuste']);

              if ($total_tiempo>$_SESSION['ajusteStandard']) {
 
                $filter_time=$total_tiempo-$_SESSION['ajusteStandard'];
                $muerto_format=gmdate("H:i:s",$filter_time);

                $deadquery     = "INSERT INTO tiempo_muerto (id_tiempo_muerto, tiempo_muerto,fecha,id_estacion,id_user,numodt,id_orden, seccion,hora_del_dia,id_tiraje,id_sesion) VALUES (null,'$muerto_format','$today','$machineID',$userID,null,null,'ajuste','$hour',$tiro,".$_SESSION['stat_session'].")";
                $dead_saved= $mysqli->query($deadquery);
                if (!$dead_saved) { 
                 $log->lwrite($deadquery,date("d-m-Y").'_ERROR_DE_COLA');
               }
                
                $tiempoajuste=  "'".gmdate("H:i:s",$_SESSION['ajusteStandard'])."'";

              }else{

                $tiempoajuste= "'".gmdate("H:i:s",$total_tiempo)."'";
              }

          }

$query="INSERT INTO `en_cola` (`id_cola`, `id_tiro`, `en_tiempo`, `seccion`, `hora`, `fecha`, `sesion`, `estatus`,proceso,tiempo) VALUES (NULL, $tiro, '$ontime', '$section', '$hour', '$today', $sesion, 1,$proceso,$tiempoajuste)";
$queued=$mysqli->query($query);
$mysqli->query("UPDATE tiraje SET tiempo_ajuste=$tiempoajuste, horafin_ajuste='$hour',tiempoTiraje='00:00:00.000000', horafin_tiraje='$hour',horadeldia_tiraje='$hour',tipo_ejecucion='pendiente' WHERE idtiraje=$tiro");

if (!$queued) {

 printf($mysqli->error);
 $log->lwrite($query,date("d-m-Y").'_ERROR_DE_COLA');
 $log->lwrite(printf($mysqli->error),date("d-m-Y").'_ERROR_DE_COLA');
 $log->lclose();

}else{
   $init_tiraje  = "INSERT INTO tiraje(id_estacion,id_proceso,horadeldia_ajuste, fechadeldia_ajuste,id_user,id_sesion) VALUES ($station_id,$proceso,'$hour','$today', ".$_SESSION['idUser'].", $sesion)";
            
                                $resultado = $mysqli->query($init_tiraje);
                                if($resultado){
                                $lastTiraje=$mysqli->insert_id;
                                $mysqli->query("UPDATE sesiones SET tiro_actual=$lastTiraje, inicio_ajuste='$hour',actividad_actual='ajuste' WHERE id_sesion=".$sesion);
}else{
  $log->lwrite(printf($mysqli->error),date("d-m-Y").'_ERROR_DE_COLA');
   $log->lwrite($init_tiraje,date("d-m-Y").'_ERROR_DE_COLA');
 $log->lclose();
}

}

}else{

  $query2="UPDATE tiraje SET pedido=0,buenos=0,piezas_ajuste=0,defectos=0,merma=0,merma_entregada=0,entregados=0,produccion_esperada=0,desempenio=0,tiempo_ajuste='".$_POST['tiempo']."',tiempoTiraje='00:00:00.000000', cancelado=1, horafin_tiraje='".date(" H:i:s", time())."',horafin_ajuste='".date(" H:i:s", time())."',horadeldia_tiraje='".date(" H:i:s", time())."',fechadeldia_tiraje='".date("d-m-Y")."' WHERE idtiraje=".$_POST['tiro'];

$cancelado=$mysqli->query($query2);

if ($cancelado) {
 $init_tiraje  = "INSERT INTO tiraje(id_estacion,id_proceso,horadeldia_ajuste, fechadeldia_ajuste,id_user,id_sesion) VALUES ($station_id,$proceso,'$hour','$today', ".$_SESSION['idUser'].", $sesion)";
            
                                $resultado = $mysqli->query($init_tiraje);
                                if($resultado){
                                $lastTiraje=$mysqli->insert_id;
                                $mysqli->query("UPDATE sesiones SET tiro_actual=$lastTiraje, inicio_ajuste='$hour',actividad_actual='ajuste' WHERE id_sesion=".$sesion);
}else{
  $log->lwrite(printf($mysqli->error),date("d-m-Y").'_ERROR_DE_COLA');
   $log->lwrite($init_tiraje,date("d-m-Y").'_ERROR_DE_COLA');
 $log->lclose();
}
}else{
   $log->lwrite(printf($mysqli->error),date("d-m-Y").'_ERROR_DE_COLA');
   $log->lwrite($query2,date("d-m-Y").'_ERROR_DE_COLA');
 $log->lclose();
}
 

}






?>
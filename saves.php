<?php
ini_set("session.gc_maxlifetime","7200");  
session_start();
date_default_timezone_set("America/Mexico_City");     
        
 $virtual   = $_SESSION['is_virtual'];
require('saves/conexion.php');
require('classes/functions.class.php');

function logpost($post){
  $info='';
  foreach ($post as $key => $value) {
    $info.=$key.": ".$value." | ";
  }
  return $info;

}

  $log = new Functions();
  $section=$_POST['section'];
  /*********** Guardando Asaichi ***********/
   if ($section=='asaichi') {
    $log->lwrite(implode(' | ', $_POST),date("d-m-Y").'_ASAICHIS');
            $log->lclose();
   
       $tiempo=$_POST['tiempo'];
        $mac=$_POST['mac'];
        $logged_in=$_SESSION['id'];
        $horadeldia=$_POST['horadeldia'];
        $fechadeldia=$_POST['fechadeldia'];
        $ontime      = $_POST['ontime'];
        $machineID=$_SESSION['machineID'];
        $stationName=$_SESSION['stationName'];
        $today=date("d-m-Y");
        $horafin=date(" H:i:s", time());
        if ($ontime=='false') {
           
            $tiempoasa='"00:15:00.000000"';
             
             $mysqli->query("INSERT INTO tiempo_muerto (id_tiempo_muerto, tiempo_muerto,fecha,id_maquina,id_user,numodt,id_orden, seccion,hora_del_dia,id_tiraje) VALUES (null,'$tiempo','$fechadeldia',null,$logged_in,null,null,1,'$horadeldia',null)");
          }else{
            $tiempoasa= ' TIMEDIFF("00:15:00.000000","'.$tiempo.'")';
          }
          $log->lwrite('tiempo: '.$tiempo,'ASA');
          $log->lwrite('tiempo asa: '.$tiempoasa,'ASA');
          $log->lwrite(printf($mysqli->error),'ASA');
        $query="INSERT INTO asaichi (tiempo, id_maquina, id_usuario, horadeldia,hora_fin, fechadeldia) VALUES ($tiempoasa,null,".$_SESSION['idUser'].",'$horadeldia','$horafin','$fechadeldia')";
        $resultado=$mysqli->query($query);
        if ($resultado) {
         $mysqli->query("UPDATE sesiones SET inicio_ajuste='$horadeldia' WHERE id_sesion=".$_SESSION['stat_session']);
        }else{
          
          $log->lwrite('asaichi',date("d-m-Y").'_ERRORES_ASAICHI');
          $log->lwrite(printf($mysqli->error),date("d-m-Y").'_ERRORES_ASAICHI');
          $log->lwrite($query,date("d-m-Y").'_ERRORES_ASAICHI');
          $log->lclose();
        }
        $change_status=$mysqli->query("UPDATE operacion_estatus SET asaichi_cumplido=1 WHERE fecha='$today' AND maquina=$machineID ");

     }
     /*********** Termina Guardando Asaichi ***********/

     /*********** Guardando Ajuste ***********/
     elseif ($section=='ajuste') {
     
      
       $tiempo      = $_POST['tiempo'];
       $ontime      = $_POST['ontime'];
        $nommaquina  = $_SESSION['stationName'];
        $stationID  = $_SESSION['stationID'];
        $userID   = $_SESSION['idUser'];
       $is_virtual=$_POST['is_virtual'];
        $orderodts=$_POST['orderodts'];
        $horadeldia  = $_POST['horadeldia'];
        $fechadeldia = $_POST['fechadeldia'];
        $machineID=$_SESSION['processID'];
        $stationName=$_SESSION['stationName'];
        $tirajeActual=$_POST['actual_tiro'];
        $horafinajuste=date("H:i:s",time());
        $today=date("d-m-Y");
        $changestatus=$mysqli->query("UPDATE sesiones SET en_tiempo=1,tiempo_alert_ajuste=NULL, tiempo_comida=NULL WHERE fecha='$today' AND estacion=$stationID AND proceso=".$_SESSION['processID']." AND operador=".$_SESSION['idUser']);
        //$odetes= explode(',',$_POST['orderodts'])
        $log->lwrite(logpost($_POST),date("d-m-Y").'_AJUSTES_'.$_SESSION['logged_in']);
        $log->lwrite('hora_fin_ajuste: '.$horafinajuste,date("d-m-Y").'_AJUSTES_'.$_SESSION['logged_in']);
        $log->lwrite('--------------------------------',date("d-m-Y").'_AJUSTES_'.$_SESSION['logged_in']);
        $log->lclose();
        $numodt=$_POST['numodt'];
       
        
          if ($ontime=='false') {
              $getAlerts=$mysqli->query("SELECT SUM(TIME_TO_SEC(tiempoalertamaquina)) AS total_ajuste FROM alertageneralajuste WHERE id_tiraje=".$tirajeActual);
              $alerts=mysqli_fetch_assoc($getAlerts);

              $seconds_reg = strtotime("1970-01-01 $tiempo UTC");
              $total_muerto=($seconds_reg+$alerts['total_ajuste'])-$_SESSION['ajusteStandard'];

              $muerto_format=gmdate("H:i:s",$total_muerto);
 $log->lwrite('--------------',$fechadeldia.'_SUMATORIA_MUERTO'.$_SESSION['logged_in']);
               $log->lwrite('no On Time, tmuerto: '.$total_muerto,$fechadeldia.'_SUMATORIA_MUERTO'.$_SESSION['logged_in']);
                $log->lwrite('--------------',$fechadeldia.'_SUMATORIA_MUERTO'.$_SESSION['logged_in']);
               $log->lwrite('tiempo alerts: '.$alerts['total_ajuste'],$fechadeldia.'_SUMATORIA_MUERTO'.$_SESSION['logged_in']);
               $log->lclose();
            $tiempoajuste="'".gmdate("H:i:s",$_SESSION['ajusteStandard'])."'";
                 $log->lwrite($tiempoajuste,date("d-m-Y").'_ONTIME_FALSE_'.$_SESSION['logged_in']);
            $log->lclose();
             $deadquery     = "INSERT INTO tiempo_muerto (id_tiempo_muerto, tiempo_muerto,fecha,id_estacion,id_user,numodt,id_orden, seccion,hora_del_dia,id_tiraje,id_sesion) VALUES (null,'$tiempo','$fechadeldia','$machineID',$userID,null,null,'ajuste','$horadeldia',$tirajeActual,".$_SESSION['stat_session'].")";
            $log->lwrite($deadquery,date("d-m-Y").'_TIEMPO_MUERTO');
            $deadresultado = $mysqli->query($deadquery);
            if (!$deadresultado) {
              $log->lwrite(printf($mysqli->error),date("d-m-Y").'_ERROR_TIEMPO_MUERTO'.$_SESSION['logged_in']);
              $log->lwrite($deadquery,date("d-m-Y").'_ERROR_TIEMPO_MUERTO'.$_SESSION['logged_in']);
              $log->lwrite('--------------',date("d-m-Y").'_ERROR_TIEMPO_MUERTO'.$_SESSION['logged_in']);
              
              $log->lclose();
            }
            
            }else{

              $getAlerts=$mysqli->query("SELECT SUM(TIME_TO_SEC(tiempoalertamaquina)) AS total_ajuste FROM alertageneralajuste WHERE id_tiraje=".$tirajeActual);
              $alerts=mysqli_fetch_assoc($getAlerts);
              $time_negative = strtotime("1970-01-01 $tiempo UTC");
              $time_positive=$_SESSION['ajusteStandard']-$time_negative;
              
              $total_tiempo=($time_positive+$alerts['total_ajuste']);

               $log->lwrite('--------------',$fechadeldia.'_SUMATORIA_MUERTO'.$_SESSION['logged_in']);
               $log->lwrite('tiempo positivo: '.$time_positive,$fechadeldia.'_SUMATORIA_MUERTO'.$_SESSION['logged_in']);
                 $log->lwrite('tiempo total: '.$total_tiempo,$fechadeldia.'_SUMATORIA_MUERTO'.$_SESSION['logged_in']);
                  $log->lclose();

              if ($total_tiempo>$_SESSION['ajusteStandard']) {
 
                $filter_time=$total_tiempo-$_SESSION['ajusteStandard'];
                $muerto_format=gmdate("H:i:s",$filter_time);
$log->lwrite('Se paso de muerto: '.$muerto_format,$fechadeldia.'_SUMATORIA_MUERTO'.$_SESSION['logged_in']);
                  $log->lclose();
                $deadquery     = "INSERT INTO tiempo_muerto (id_tiempo_muerto, tiempo_muerto,fecha,id_estacion,id_user,numodt,id_orden, seccion,hora_del_dia,id_tiraje,id_sesion) VALUES (null,'$muerto_format','$fechadeldia','$machineID',$userID,null,null,'ajuste','$horadeldia',$tirajeActual,".$_SESSION['stat_session'].")";
                $dead_saved= $mysqli->query($deadquery);
                if (!$dead_saved) { 
                 $log->lwrite('--------------',$fechadeldia.'_ERROR_SUMATORIA_MUERTO'.$_SESSION['logged_in']);
                 $log->lwrite($deadquery,$fechadeldia.'_ERROR_SUMATORIA_MUERTO'.$_SESSION['logged_in']);
                  $log->lclose();
               }
                
                $tiempoajuste=  "'".gmdate("H:i:s",$_SESSION['ajusteStandard'])."'";

              }else{

                $tiempoajuste= "'".gmdate("H:i:s",$total_tiempo)."'";
              }

              

          }

          $timeA=date("H:i:s",time()); 

          if ($is_virtual=='virtual') {
             $log->lwrite('entro a virtual= true ',$fechadeldia.'_RASTREANDO_ERROR_'.$_SESSION['logged_in']);
             $log->lwrite('virtual: '.$virtual,$fechadeldia.'_RASTREANDO_ERROR_'.$_SESSION['logged_in']);
              $log->lwrite(json_encode($_POST),$fechadeldia.'_RASTREANDO_ERROR_'.$_SESSION['logged_in']);

               $log->lwrite('------------------',$fechadeldia.'_RASTREANDO_ERROR_'.$_SESSION['logged_in']);
                  $log->lclose();
            $virtOdt=$_POST['odtvirtual'];
          $virtElem=$_POST['elemvirtual'];
            $query     = "UPDATE tiraje SET tiempo_ajuste=$tiempoajuste, horafin_ajuste='$horafinajuste', horadeldia_tiraje='$horafinajuste',  is_virtual=1,odt_virtual='$virtOdt',elemento_virtual='$virtElem' WHERE idtiraje=$tirajeActual";
          }else{

            $log->lwrite('entro a virtual= false ',$fechadeldia.'_RASTREANDO_ERROR_'.$_SESSION['logged_in']);
            $log->lwrite('virtual: '.$virtual,$fechadeldia.'_RASTREANDO_ERROR_'.$_SESSION['logged_in']);
              $log->lwrite(json_encode($_POST),$fechadeldia.'_RASTREANDO_ERROR_'.$_SESSION['logged_in']);
              
               $log->lwrite('------------------',$fechadeldia.'_RASTREANDO_ERROR_'.$_SESSION['logged_in']);
                  $log->lclose();
            $query     = "UPDATE tiraje SET tiempo_ajuste=$tiempoajuste, horafin_ajuste='$timeA', horadeldia_tiraje='$timeA', id_orden=$numodt WHERE idtiraje=$tirajeActual";
          }



           

            
           
 
            $resultadoA = $mysqli->query($query);
            
            
             
             $setTiraje=$mysqli->query("UPDATE personal_process SET last_tiraje=$tirajeActual WHERE status='actual' AND proceso_actual='$stationName' ");
              
            if (!$resultadoA) {
             $log->lwrite(printf($mysqli->error),date("d-m-Y").'_ERROR_TIRO_AJUSTE'.$_SESSION['logged_in']);
              $log->lwrite($query,date("d-m-Y").'_ERROR_TIRO_AJUSTE'.$_SESSION['logged_in']);
               $log->lwrite(json_encode($_POST),date("d-m-Y").'_ERROR_TIRO_AJUSTE'.$_SESSION['logged_in']);
              $log->lwrite('--------------',date("d-m-Y").'_ERROR_TIRO_AJUSTE'.$_SESSION['logged_in']);
           
            $log->lclose();
        }
       

        $init_query="UPDATE sesiones SET inicio_tiro='$horafinajuste' WHERE fecha='$today' AND estacion=".$_SESSION['stationID']." AND proceso=".$_SESSION['processID']." AND operador=".$_SESSION['idUser'];
        $init_tiro=$mysqli->query($init_query);

        if (!$init_tiro) {
            $log->lwrite('-------------error iniciar siguiente tiro',date("d-m-Y").'_INIT_TIRO_ERROR_'.$_SESSION['logged_in']);
            $log->lwrite(printf($mysqli->error),date("d-m-Y").'_INIT_TIRO_ERROR_'.$_SESSION['logged_in']);
            $log->lwrite($init_query,date("d-m-Y").'_INIT_TIRO_ERROR_'.$_SESSION['logged_in']);
            
            $log->lclose();
        } 
      
       
     } 
     /*********** Termina Guardando Ajuste ***********/

     /*********** Guardando Tiraje ***********/
      elseif ($section=='tiraje') {
                $log->lwrite($_SESSION['logged_in']." ".logpost($_POST),date("d-m-Y").'_TIRAJES_'.$_SESSION['logged_in']);
          
            $planillas=(!empty($_POST['planillas']))? $_POST['planillas'] : 'null';  
            $isvirtual=(isset($_POST['isvirtual'])? $_POST['isvirtual'] :'false');
            $producto=(isset($_POST['producto'])) ?$_POST['producto'] : '';
            $numodt=(isset($_POST['numodt'])) ?$_POST['numodt'] : '';
            $logged_in=$_POST['logged_in'];
            
            $table_mac=(isset($_POST['table-machine']))? $_POST['table-machine'] : 1;
            $odt=(isset($_POST['odt']))? $_POST['odt'] : '';
            $pedido=(isset($_POST['pedido'])) ?(($planillas=='null')? $_POST['pedido'] : $_POST['pedido']) : '';
            $cantidad=(isset($_POST['cantidad'])) ?$_POST['cantidad'] : '';
            $buenos=(isset($_POST['buenos']))? (($planillas=='null')? $_POST['buenos']-$_POST['merma-entregada'] : ($_POST['buenos']-$_POST['merma-entregada'])/$planillas) : '';
            $defectos=(isset($_POST['defectos'])) ?$_POST['defectos'] : '';
            $merma=(isset($_POST['merma']))? (($planillas=='null')? $_POST['merma'] : $_POST['merma']/$planillas) : 0;
            $ajuste=$_POST['piezas-ajuste'];
            $entregados=(isset($_POST['entregados']))? (($planillas=='null')? $_POST['entregados'] : $_POST['entregados']/$planillas) : (($planillas=='null')? $_POST['buenos'] : $_POST['buenos']/$planillas);
            $tiempoTiraje=$_POST['tiempoTiraje'];
            $fechadeldia=$_POST['fechadeldia'];
            $horadeldia=$_POST['hour'];
            $merma_entregada=(!empty($_POST['planillas']))? $_POST['merma-entregada']/$_POST['planillas'] : $_POST['merma-entregada'];
            $passmerma=$merma-($merma_entregada+$defectos+$ajuste);
            $element=$_POST['element'];
            $horainiciotiro=$_POST['horainiciotiro'];
            $horafintiraje=date("H:i:s",time());
            $log->lwrite('horafin_tiraje: '.$horafintiraje,date("d-m-Y").'_TIRAJES_'.$_SESSION['logged_in']);
            
            $today=date("d-m-Y");
      $log->lwrite( $isvirtual,'UPDATING');
           $horaTiraje     = date(" H:i:s", time());
            $userID = $_SESSION['idUser'];
            
            $seconds = strtotime("1970-01-01 $tiempoTiraje UTC");
            
           
            $machineID = $_SESSION['stationID'];
            $stationName = $_SESSION['stationName'];
           

             $processID=$_SESSION['processID'];
            $standar_query2 = "SELECT * FROM estandares WHERE id_proceso=$processID AND id_elemento= $element";
            


            $getstandar     = mysqli_fetch_assoc($mysqli->query($standar_query2));
            $estandar       = $getstandar['piezas_por_hora'];
            //calculando desempeño para pieza actual
            if (is_null($estandar)) {
              $getstandar     = mysqli_fetch_assoc($mysqli->query("SELECT * FROM estandares WHERE id_proceso=$processID AND id_elemento=144"));
              $tiraje_estandar=($seconds*$getstandar['piezas_por_hora'])/3600;
            }else{
              $tiraje_estandar=($seconds*$estandar)/3600;
            }
            
            if ($tiraje_estandar>0) {
              $predesemp=($entregados*100)/$tiraje_estandar;
             $tiraje_desemp=($predesemp>100)? 100 : $predesemp;
             $log->lwrite('si vale algo','desemp');
            }else{
              $tiraje_desemp=0;
              $log->lwrite('no vale nada','desemp');
            }
            $log->lwrite('$tiraje_estandar '.$tiraje_estandar,'desemp');
            $log->lwrite($standar_query2,'desemp');
            $log->lclose();
            $prodEsperada=round($tiraje_estandar);
            
            $hora=$_POST['hour'];
            $tiro_query="SELECT tiro_actual FROM sesiones WHERE fecha='$today' AND operador='".$_SESSION['idUser']."' AND estacion=".$_SESSION['stationID']." AND proceso=".$_SESSION['processID'];
           $LastT=mysqli_fetch_assoc( $mysqli->query($tiro_query));
           $getLast=$LastT['tiro_actual'];

           

            $query="UPDATE tiraje set producto='$producto', pedido='$pedido', cantidad=$cantidad, buenos=$buenos, defectos=$defectos, merma=$merma,piezas_ajuste=$ajuste, merma_entregada=$merma_entregada, entregados=$entregados, tiempoTiraje='$tiempoTiraje', fechadeldia_tiraje='$fechadeldia', horafin_tiraje='$horafintiraje',id_user=$logged_in,produccion_esperada=$prodEsperada,desempenio=$tiraje_desemp WHERE idtiraje=$getLast";

           
           // print_r($mysqli);
            $log->lwrite($query,date("d-m-Y").'_TIRAJES_'.$_SESSION['logged_in']);
            $log->lwrite('--------------------------------',date("d-m-Y").'_TIRAJES_'.$_SESSION['logged_in']);
            $log->lclose();

           if ($_SESSION['processID']==1) {
            $recib_query="UPDATE ordenes SET cantrecibida=$entregados WHERE idorden=$numodt";
            $exec=$mysqli->query($recib_query);
            if ($exec) {
              $log->lwrite("Cant Recibida ".$entregados." para orden ".$numodt,'CORTE_HECHO');
           $log->lclose();
            }else{
              $log->lwrite("error al cortar ".$recib_query,'CORTE_HECHO');
           $log->lclose();
            }

           
           }

            $resultado=$mysqli->query($query);
           
            if ($resultado) {
           
            $cleanqueryp="DELETE FROM personal_process WHERE proceso_actual='$stationName' AND status='actual'";
            $cleanp=$mysqli->query($cleanqueryp);
            if ($cleanp) {
             $sqlp="SELECT * FROM personal_process WHERE proceso_actual='$stationName' order by orden_display asc";
            $ordsp=$mysqli->query($sqlp);
            }
           
            $userID=$_SESSION['idUser'];
        
        $mysqli->query("UPDATE sesiones SET orden_actual='--' WHERE operador =$userID AND fecha='$today' AND estacion=".$_SESSION['stationID']." AND proceso=".$_SESSION['processID']);
              


          

            //termina guardando personal******************************************************

            }else{
                                              
                       $log->lwrite('error al completar tiraje',date("d-m-Y").'_ERROR_TIRO_'.$_SESSION['logged_in']);

                       $log->lwrite('tiro actual: '.$getLast,date("d-m-Y").'_ERROR_TIRO_'.$_SESSION['logged_in']);
                       $log->lwrite('orden'.$numodt,date("d-m-Y").'_ERROR_TIRO_'.$_SESSION['idUser']);
                        $log->lwrite(printf($mysqli->error),date("d-m-Y").'_ERROR_TIRO_'.$_SESSION['logged_in']);
                        $log->lwrite($query,date("d-m-Y").'_ERROR_TIRO_'.$_SESSION['idUser']);
                        $log->lwrite('-------------------',date("d-m-Y").'_ERROR_TIRO_'.$_SESSION['logged_in']);
                        $log->lclose();
                      }
           
           




     } 
     /*********** Termina Guardando Tiraje ***********/

     /*********** Guardando Encuesta ***********/
      elseif ($section=='encuesta') {

        print_r($_POST);
       
        
        $horadeldia=$_POST['horadeldia'];
        $fechadeldia=$_POST['fechadeldia'];
        $desempeno=$_POST['desempeno'];
        $problema= (isset($_POST['problema'])) ?$_POST['problema'] : '';
        $calidad=$_POST['calidad'];
        $problema2=(isset($_POST['problema2'])) ?$_POST['problema2'] : '';
        $odt=$_POST['odt'];
        $observaciones=$_POST['observaciones'];
        $lastOrder=$_POST['idorden'];

        
        $userID = $_SESSION['idUser'];
        
        $machineID = $_SESSION['stationID'];
        $stationName = $_SESSION['stationName'];

        $today=date("d-m-Y");
        $next=date(" H:i:s", time());


        $log->lwrite(json_encode($_POST),date("d-m-Y").'_RASTREO_'.$_SESSION['logged_in']);
         $log->lwrite('-------------------',date("d-m-Y").'_RASTREO_'.$_SESSION['logged_in']);
                                      $log->lclose();

        /************* Siguiente tiraje ************/
         $init_tiraje     = "INSERT INTO tiraje(id_estacion,id_proceso,horadeldia_ajuste, fechadeldia_ajuste,id_user,id_sesion) VALUES (".$_SESSION['stationID'].",".$_SESSION['processID'].",'$next','$today', ".$_SESSION['idUser'].", ".$_SESSION['stat_session'].")";
            
                                $next_tiraje = $mysqli->query($init_tiraje);
                                if ($next_tiraje) {
                              $nTiraje=$mysqli->insert_id;

                               $setNext="UPDATE sesiones SET inicio_ajuste='$next',parte='--',tiro_actual=$nTiraje, tiempo_alert=null,tiempo_comida=null WHERE operador =$userID AND fecha='$today' AND estacion=".$machineID." AND proceso=".$_SESSION['processID'];

                              $mysqli->query($setNext);


                              $log->lwrite($setNext,date("d-m-Y").'_NEXT_TIRO_'.$_SESSION['logged_in']);
                              $log->lwrite('next tiraje'.$nTiraje,date("d-m-Y").'_NEXT_TIRO_'.$_SESSION['logged_in']);
                              $log->lwrite($setNext,date("d-m-Y").'_NEXT_TIRO_'.$_SESSION['logged_in']);
                              $log->lwrite('-------------------',date("d-m-Y").'_NEXT_TIRO_'.$_SESSION['logged_in']);
                                      $log->lclose();
                               
                                }else{
                                     $log->lwrite('error al insertar sig tiraje',date("d-m-Y").'_ERROR_NEXT_TIRO_'.$_SESSION['logged_in']);

                                      $log->lwrite('tiro next: '.$nTiraje,date("d-m-Y").'_ERROR_NEXT_TIRO_'.$_SESSION['logged_in']);
                                     
                                      $log->lwrite(printf($mysqli->error),date("d-m-Y").'_ERROR_NEXT_TIRO_'.$_SESSION['logged_in']);
                                      $log->lwrite($init_tiraje,date("d-m-Y").'_ERROR_NEXT_TIRO_'.$_SESSION['idUser']);
                                      $log->lwrite('-------------------',date("d-m-Y").'_ERROR_NEXT_TIRO_'.$_SESSION['logged_in']);
                                      $log->lclose();
                                } 
      /************* Termina Siguiente tiraje ************/   



        


        $query="INSERT INTO encuesta (id_usuario, id_estacion, horadeldia, fechadeldia, desempeno, problema, calidad, problema2, observaciones) VALUES ('$userID','$machineID','$horadeldia','$fechadeldia','$desempeno','$problema','$calidad','$problema2','$observaciones')";

       


        $resultado=$mysqli->query($query);
        if ( $resultado) {
          /*
          function is_in_array($needle, $haystack) {
            foreach ($needle as $stack) {if (in_array($stack, $haystack)) { return true;} }
            return false;
        } */
        if ($_SESSION['is_virtual']=='false') {
         
        function is_in_array($needle, $haystack) {

            foreach ($needle as $stack) {

                if (in_array($stack, $haystack)) {
                     return true;
                }
            }

            return false;
        }

        $process=$_SESSION['processName'];
           $pro=$_POST['process'];  
        $queryavance="UPDATE procesos SET estatus=1, avance=4 WHERE id_proceso=$pro ";
        $mysqli->query($queryavance);

        $query_deliv="SELECT avance FROM procesos WHERE numodt='$odt' AND id_orden=$lastOrder ";
        $deliv=$mysqli->query($query_deliv);
        if (!$deliv) {
         printf($mysqli->error);
         echo $query_deliv;
         $log->lwrite('encuesta multi','multi-error');
                       $log->lwrite('error al actualizar avance','multi-error');
                       $log->lwrite('orden'.$lastOrder,'multi-error');
                        $log->lwrite(printf($mysqli->error),'multi-error');
                        $log->lwrite($query_deliv,'multi-error');
                        $log->lclose();
        }
        

        while($arrd=mysqli_fetch_array($deliv)) {
          $deliver[] = $arrd['avance'];
          
        }

        $b = array('inicio','en pausa','retomado');
        echo $query_deliv;

        $is_complete=is_in_array($b, $deliver);
        $log->lwrite('------------------------------------------','COMPLETANDO');
        $log->lwrite('maquina: '.$stationName.' orden: '.$lastOrder.' numodt: '.$odt,'COMPLETANDO');
        $log->lwrite('faltantes: '.implode('|', $deliver),'COMPLETANDO');
        $log->lclose();
        if ($is_complete==false) {
          $log->lwrite('ya no hay pendientes, se ha completado la orden '.$lastOrder,'COMPLETANDO');
        $log->lclose();
          $querydeliv="UPDATE ordenes SET entregado='true' WHERE idorden=$lastOrder";
        $mysqli->query($querydeliv);
        }
        $checforODT=$mysqli->query("SELECT entregado FROM ordenes WHERE numodt='$odt' AND entregado='false'");
        if ($checforODT->num_rows==0) {
         $compquery ="INSERT INTO `ordenes_completadas` (`id_complete_orden`, `odt`, `fecha_completado`, `hora_completado`) VALUES (NULL, '$odt', '".date("d-m-Y")."', '".date(" H:i:s", time())."')";
          $completeODT=$mysqli->query($compquery);
          if (!$completeODT) {
           $log->lwrite(printf($mysqli->error()),'COMPLETANDO');
           $log->lwrite($compquery,'COMPLETANDO');
        $log->lclose();
          }

         $log->lwrite('********************************','COMPLETANDO');
        $log->lwrite('se completaron todas ordenes de la ODT: '.$odt,'COMPLETANDO');
        $log->lclose();
        }

        $queryOrden="SELECT o.*,p.id_proceso,(SELECT orden_display FROM orden_estatus WHERE id_orden=o.idorden AND id_proceso=p.id_proceso) AS orden_display,(SELECT status FROM orden_estatus WHERE id_orden=o.idorden AND id_proceso=p.id_proceso) AS status FROM ordenes o INNER JOIN procesos p ON p.id_orden=o.idorden WHERE nombre_proceso='$process' HAVING status='actual'";
        $asoc=($mysqli->query($queryOrden));
        //$ordenActual = $getAct['numodt'];

        while($getAct=mysqli_fetch_assoc($asoc)){
          $getActODT[] = $getAct['numodt'];
          $ordenActual[] = $getAct['idorden'];
          
        }

        if( !session_id() )
        {
            session_start();
            

        }
        

        }

         }else{
                    printf("Errormessage: %s\n", $mysqli->error);
                  }

        
       
     }
     /*********** Termina Guardando Encuesta ***********/
     elseif ($section=='restart') {
               
        

        

     }  

?>


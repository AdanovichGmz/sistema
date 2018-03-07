<?php
ini_set("session.gc_maxlifetime","7200");  
session_start();
date_default_timezone_set("America/Mexico_City");     
        

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
    $log->lwrite(implode(' | ', $_POST),'ASAICHIS_'.date("d-m-Y"));
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
             
             $mysqli->query("INSERT INTO tiempo_muerto (id_tiempo_muerto, tiempo_muerto,fecha,id_maquina,id_user,numodt,id_orden, seccion,hora_del_dia,id_tiraje) VALUES (null,'$tiempo','$fechadeldia','$machineID',$logged_in,null,null,1,'$horadeldia',null)");
          }else{
            $tiempoasa= ' TIMEDIFF("00:15:00.000000","'.$tiempo.'")';
          }
          $log->lwrite('tiempo: '.$tiempo,'ASA');
          $log->lwrite('tiempo asa: '.$tiempoasa,'ASA');
          $log->lwrite(printf($mysqli->error),'ASA');
        $query="INSERT INTO asaichi (tiempo, id_maquina, id_usuario, horadeldia,hora_fin, fechadeldia) VALUES ($tiempoasa,$machineID,$logged_in,'$horadeldia','$horafin','$fechadeldia')";
        $resultado=$mysqli->query($query);
        if ($resultado) {
          echo "Todo bien";
        }else{
          
          $log->lwrite('asaichi','multi-error');
          $log->lwrite(printf($mysqli->error),'multi-error');
          $log->lwrite($query,'multi-error');
          $log->lclose();
        }
        $change_status=$mysqli->query("UPDATE operacion_estatus SET asaichi_cumplido=1 WHERE fecha='$today' AND maquina=$machineID ");

     }
     /*********** Termina Guardando Asaichi ***********/

     /*********** Guardando Ajuste ***********/
     elseif ($section=='ajuste') {
      print_r($_POST);
      
       $tiempo      = $_POST['tiempo'];
       $ontime      = $_POST['ontime'];
        $nommaquina  = $_SESSION['stationName'];
        $stationID  = $_SESSION['stationID'];
        $userID   = $_SESSION['idUser'];
        $orderodts=$_POST['orderodts'];
        $horadeldia  = $_POST['horadeldia'];
        $fechadeldia = $_POST['fechadeldia'];
        $machineID=$_SESSION['processID'];
        $stationName=$_SESSION['stationName'];
        $tirajeActual=$_POST['actual_tiro'];
        $horafinajuste=date("H:i:s",time());
        $today=date("d-m-Y");
        $changestatus=$mysqli->query("UPDATE sesiones SET en_tiempo=1,tiempo_alert_ajuste=NULL, tiempo_comida=NULL WHERE fecha='$today' AND estacion=$stationID AND proceso=".$_SESSION['processID']);
        //$odetes= explode(',',$_POST['orderodts'])
        $log->lwrite(logpost($_POST),'AJUSTES_'.$_SESSION['logged_in']."_".date("d-m-Y"));
        $log->lwrite('hora_fin_ajuste: '.$horafinajuste,'AJUSTES_'.$_SESSION['logged_in']."_".date("d-m-Y"));
        $log->lwrite('--------------------------------','AJUSTES_'.$_SESSION['logged_in']."_".date("d-m-Y"));
        $log->lclose();
        if ($_POST['numodt']=='virtual') {
          $virtOdt=$_POST['odtvirtual'];
          $virtElem=$_POST['elemvirtual'];
            if ($ontime=='false') {
                $tiempoajuste=gmdate("H:i:s",$_SESSION['ajusteStandard']);
            
            }else{
            
                 $tiempoajuste= ' TIMEDIFF("'.gmdate("H:i:s",$_SESSION['ajusteStandard']).'.000000","'.$tiempo.'")';
           
          }
            
            $query     = "UPDATE tiraje SET tiempo_ajuste=$tiempoajuste, horafin_ajuste='$horafinajuste', horadeldia_tiraje='$horafinajuste',  is_virtual=1,odt_virtual='$virtOdt',elemento_virtual='$virtElem' WHERE idtiraje=$tirajeActual";
            
            $resultado = $mysqli->query($query);
                       
             $setTiraje=$mysqli->query("UPDATE personal_process SET last_tiraje=$tirajeActual WHERE status='actual' AND proceso_actual='$stationName' ");
            if ($resultado) {
              echo "tiraje virtual insertado";
            }else{
              printf($mysqli->error);
            }
             if ($ontime=='false') {
               $deadquery     = "INSERT INTO tiempo_muerto (id_tiempo_muerto, tiempo_muerto,fecha,id_maquina,id_user,numodt,id_orden, seccion,hora_del_dia,id_tiraje) VALUES (null,'$tiempo','$fechadeldia','$machineID',$userID,'$orderodts',null,'ajuste','$horadeldia',$tirajeActual)";
            $log->lwrite($deadquery,'TIEMPO_MUERTO');
            $deadresultado = $mysqli->query($deadquery);
            if ($deadresultado) {
              $log->lwrite('se registro un tiempo muerto','TIEMPO_MUERTO');
              
              $log->lclose();
            }
            }
         } else{

        $actuals_query="SELECT  os.status, o.idorden,o.numodt, os.proceso_actual FROM personal_process os INNER JOIN ordenes o on os.id_orden=o.idorden WHERE status='actual' AND proceso_actual='$stationName'";
        $resultodt=$mysqli->query($actuals_query);

        //$numodt      = (isset($_POST['numodt'])) ? explode(',', substr($_POST['numodt'], 0, -1)) : '';
        while($arr=mysqli_fetch_assoc($resultodt)){
          $numodt[] = $arr['idorden'];
          $odt[]=$arr['numodt'];
        }
        $odetes=implode(",", $odt);
        foreach ($numodt as $odt) {
          if ($ontime=='false') {
                $tiempoajuste=gmdate("H:i:s",$_SESSION['ajusteStandard']);
            
            }else{
                 $tiempoajuste= ' TIMEDIFF("'.gmdate("H:i:s",$_SESSION['ajusteStandard']).'.000000","'.$tiempo.'")';
          }
            $query     = "UPDATE tiraje SET tiempo_ajuste=$tiempoajuste, horafin_ajuste='$horafinajuste', horadeldia_tiraje='$horafinajuste', id_orden=$odt WHERE idtiraje=$tirajeActual";
           
 
            $resultado = $mysqli->query($query);
             
             $setTiraje=$mysqli->query("UPDATE personal_process SET last_tiraje=$tirajeActual WHERE status='actual' AND proceso_actual='$stationName' ");
              if ($ontime=='false') {
               $deadquery     = "INSERT INTO tiempo_muerto (id_tiempo_muerto, tiempo_muerto,fecha,id_maquina,id_user,numodt,id_orden, seccion,hora_del_dia,id_tiraje) VALUES (null,'$tiempo','$fechadeldia','$machineID',$userID,'$orderodts',null,'ajuste','$horadeldia',$tirajeActual)";
            $log->lwrite($deadquery,'TIEMPO_MUERTO');
            $deadresultado = $mysqli->query($deadquery);
            if ($deadresultado) {
              $log->lwrite('se registro un tiempo muerto','TIEMPO_MUERTO');
              
              $log->lclose();
            }
            }
            
            if ($resultado) {
        } else {
            printf($mysqli->error);
             $log->lwrite('ajuste','multi-error');
             $log->lwrite('error al insertar ajuste','multi-error');
             $log->lwrite('orden'.$odt,'multi-error');
              $log->lwrite(printf($mysqli->error),'multi-error');
              $log->lwrite($query,'multi-error');
              $log->lclose();
        }
        }

        $init_tiro=$mysqli->query("UPDATE sesiones SET inicio_tiro='$horafinajuste' WHERE fecha='$today' AND estacion=".$_SESSION['stationID']." AND proceso=".$_SESSION['processID']);
      }
       
     } 
     /*********** Termina Guardando Ajuste ***********/

     /*********** Guardando Tiraje ***********/
      elseif ($section=='tiraje') {
                $log->lwrite($_SESSION['logged_in']." ".logpost($_POST),'TIRAJES_'.$_SESSION['logged_in']."_".date("d-m-Y"));
          
            $planillas=(!empty($_POST['planillas']))? $_POST['planillas'] : 'null';  
            $isvirtual=(isset($_POST['isvirtual'])? $_POST['isvirtual'] :'false');
            $producto=(isset($_POST['producto'])) ?$_POST['producto'] : '';
            $numodt=(isset($_POST['numodt'])) ?$_POST['numodt'] : '';
            $logged_in=$_POST['logged_in'];
            
            $table_mac=(isset($_POST['table-machine']))? $_POST['table-machine'] : 1;
            $odt=(isset($_POST['odt']))? $_POST['odt'] : '';
            $pedido=(isset($_POST['pedido'])) ?(($planillas=='null')? $_POST['pedido'] : $_POST['pedido']/$planillas) : '';
            $cantidad=(isset($_POST['cantidad'])) ?$_POST['cantidad'] : '';
            $buenos=(isset($_POST['buenos'])) ? (($planillas=='null')? $_POST['buenos'] : $_POST['buenos']/$planillas) : '';
            $defectos=(isset($_POST['defectos'])) ?$_POST['defectos'] : '';
            $merma=(isset($_POST['merma']))? $_POST['merma'] : 0;
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
            $log->lwrite('horafin_tiraje: '.$horafintiraje,'TIRAJES_'.$_SESSION['logged_in']."_".date("d-m-Y"));
            $log->lwrite('--------------------------------','TIRAJES_'.$_SESSION['logged_in']."_".date("d-m-Y"));
            $log->lclose();

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
            
                if($isvirtual=='true'){
                  $elem_v=$_POST['element_v'];
                  if ($table_mac==2) {
                    $tiraje_estandar=($seconds*300)/3600;
                    $log->lwrite( "IMPRESION EN MESA",'UPDATING');
                  }
                  else{
                  if (!empty($estandar)) {
                    $tiraje_estandar=($seconds*$estandar)/3600;
                    $log->lwrite( "ESTANDAR: ".$estandar,'UPDATING');
                  }
                  else{
                   if ($processID==10) {
                    $tiraje_estandar=($seconds*480)/3600;
                  }else{
                    $tiraje_estandar=($seconds*600)/3600;
                  } }
                }


                  if ($tiraje_estandar>0) {
                    $predesemp=($entregados*100)/$tiraje_estandar;
                   $tiraje_desemp=($predesemp>100)? 100 : $predesemp;
                   $log->lwrite('si vale algo','desemp');
                  }else{
                    $tiraje_desemp=0;
                    $log->lwrite('no vale nada','desemp');
                  }

                    $log->lwrite(  "ESPERADO: ".$tiraje_estandar,'UPDATING');
                  $prodEsperada=round($tiraje_estandar);
                  
                  $hora=$_POST['hour'];
                   $LastT=mysqli_fetch_assoc( $mysqli->query("SELECT last_tiraje FROM personal_process WHERE status='actual' AND proceso_actual='$stationName' "));
                  $getLast=$LastT['last_tiraje'];

                  $query="UPDATE tiraje set producto='$producto', pedido='$pedido', cantidad=$cantidad, buenos=$buenos, defectos=$defectos, merma=$merma,piezas_ajuste=$ajuste, merma_entregada=$merma_entregada, entregados=$entregados, tiempoTiraje='$tiempoTiraje', fechadeldia_tiraje='$fechadeldia', horafin_tiraje='$horafintiraje', id_user=$logged_in,produccion_esperada=$prodEsperada,desempenio=$tiraje_desemp WHERE idtiraje=$getLast";
                  $log->lwrite("Ultimo ID de ".$stationName.": ".$getLast,'LAST_ID');
           $log->lclose();

            $resultado=$mysqli->query($query);
             if ($machineID==1) {
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
            if (!$resultado) {
              
              $log->lwrite(printf($mysqli->error),'UPDATING');

            }
             $log->lwrite($query,'UPDATING');
              $log->lclose();
            $cleanPersonal=$mysqli->query("DELETE FROM personal_process WHERE proceso_actual='$stationName'");
            
               if (!$cleanPersonal) {
               printf($mysqli->error);             }
               if (!$resultado) {
               printf($mysqli->error);             }
                    
                }else{
            $getstandar     = mysqli_fetch_assoc($mysqli->query($standar_query2));
            $estandar       = $getstandar['piezas_por_hora'];
            //calculando desempeño para pieza actual
            if (is_null($estandar)) {
              
              if ($processID==10) {
                    $tiraje_estandar=($seconds*420)/3600;
                  }else{
                    $tiraje_estandar=($seconds*600)/3600;
                  }
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
            
           $LastT=mysqli_fetch_assoc( $mysqli->query("SELECT last_tiraje FROM personal_process WHERE status='actual' AND proceso_actual='$stationName' "));
           $getLast=$LastT['last_tiraje'];

            $query="UPDATE tiraje set producto='$producto', pedido='$pedido', cantidad=$cantidad, buenos=$buenos, defectos=$defectos, merma=$merma,piezas_ajuste=$ajuste, merma_entregada=$merma_entregada, entregados=$entregados, tiempoTiraje='$tiempoTiraje', fechadeldia_tiraje='$fechadeldia', horafin_tiraje='$horafintiraje',id_user=$logged_in,produccion_esperada=$prodEsperada,desempenio=$tiraje_desemp WHERE idtiraje=$getLast";
           // print_r($mysqli);
            $log->lwrite("Ultimo ID de ".$stationName.": ".$getLast,'LAST_ID');
           $log->lclose();

           if ($machineID==1) {
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
            $querymerma="UPDATE ordenes SET merma_entregada=$merma_entregada, merma_recibida=$passmerma WHERE idorden=$numodt";
            $mysqli->query($querymerma);
            if ( $resultado) {
            //$mysqli->query("UPDATE ordenes SET proceso_completado='true' WHERE idorden=$numodt");  
           

            //include("encuesta.php");
            //guardando personal******************************************************
            $cleanqueryp="DELETE FROM personal_process WHERE proceso_actual='$stationName' AND status='actual'";
            $cleanp=$mysqli->query($cleanqueryp);
            if ($cleanp) {
             $sqlp="SELECT * FROM personal_process WHERE proceso_actual='$stationName' order by orden_display asc";
            $ordsp=$mysqli->query($sqlp);
            }
            //checamos si hay aun partes pendientes a completar para esta ODT
            if ($ordsp->num_rows>0) {
              
            $ip=1;
            while($arrp=mysqli_fetch_array($ordsp)) {
              $resultsp[$ip] = $arrp;
              $ip++;
            }
            $i3p=1;
            foreach ($resultsp as $row2p) {
              $idp=$row2p['id_orden'];
              $old_statusp=$row2p['status'];
              $idprsp=$row2p['id_proceso'];
                if ($old_statusp=='siguiente') {
                 $statusp='actual';
                }
                 elseif ($old_statusp=='preparacion') {
                  $statusp='siguiente';
                }
                elseif ($old_statusp=='programado1') {
                  $statusp='preparacion';
                }
                else{ 
                  $progNump=$i3p-3;
                  $statusp='programado'.$progNump;
                }
             //$update3p ="UPDATE personal_process SET orden_display = $i3p , status='$statusp' WHERE id_orden= $idp AND id_proceso=$idprsp";
            //$updp= $mysqli->query($update3p);

             $updp=true;
            if ($updp) {
              $cleanquery="DELETE FROM orden_estatus WHERE proceso_actual='$stationName' AND status='actual'";
            $clean=$mysqli->query($cleanquery);
           
            }else{
              prinf($mysqli->error);
              $log->lwrite('tiraje','multi-error');
             $log->lwrite('error al establecer estatus','multi-error');
             $log->lwrite('orden'.$idp,'multi-error');
              $log->lwrite(printf($mysqli->error),'multi-error');
              $log->lwrite($update3p,'multi-error');
              $log->lclose();

            }
            $i3p++;
            }
          }else{ //si no hay mas partes pendientes para esta orden, la marcamos como completada
            $userID=$_SESSION['idUser'];
        $today=date("d-m-Y");
        $mysqli->query("UPDATE sesiones SET orden_actual='--' WHERE operador =$userID AND fecha='$today' AND estacion=".$_SESSION['stationID']." AND proceso=".$_SESSION['processID']);
              


          }

            //termina guardando personal******************************************************



            }else{
                        printf($mysqli->error);
                        echo $query;
                        $log->lwrite('tiraje single','multi-error');
                       $log->lwrite('error al completar ajustes con tirajes','multi-error');
                       $log->lwrite('orden'.$numodt,'multi-error');
                        $log->lwrite(printf($mysqli->error),'multi-error');
                        $log->lwrite($query,'multi-error');
                        $log->lclose();
                      }
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



        /************* Siguiente tiraje ************/
         $init_tiraje     = "INSERT INTO tiraje(id_estacion,horadeldia_ajuste, fechadeldia_ajuste,id_user,id_sesion) VALUES (".$_SESSION['stationID'].",'$next','$today', ".$_SESSION['idUser'].", ".$_SESSION['stat_session'].")";
            
                                $next_tiraje = $mysqli->query($init_tiraje);
                                if ($next_tiraje) {
                                   $nTiraje=$mysqli->insert_id;

                               $setNext="UPDATE sesiones SET inicio_ajuste='$next',parte='--',tiro_actual=$nTiraje, tiempo_alert=null,tiempo_comida=null WHERE operador =$userID AND fecha='$today' AND estacion=".$machineID." AND proceso=".$_SESSION['processID'];

                              $mysqli->query($setNext);

                               
                                }else{
                                    printf($mysqli->error);
                                    echo "no se inserto el siguiente tiro";
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
        if(@$_SESSION['logged_in'] != true){
            echo '
            <script>
                alert("La sesion se cerro inseperadamente, favor de iniciar sesion otra vez");
                self.location.replace("index.php");
            </script>';
        }else{
        //echo $_SERVER['HTTP_HOST'];
        //header("Location: http://{$_SERVER['SERVER_NAME']}/unify/index2.php");
        }

        

         }else{
                    printf("Errormessage: %s\n", $mysqli->error);
                  }

        
       
     }
     /*********** Termina Guardando Encuesta ***********/
     elseif ($section=='restart') {
               
        

        

     }  

?>


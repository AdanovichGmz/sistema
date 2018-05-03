<?php
error_reporting(0);
require('classes/functions.class.php');
$log = new Functions();
session_start();
date_default_timezone_set("America/Mexico_City"); 
 require('saves/conexion.php');
if ($_POST['choose']=='station') {
 

}elseif ($_POST['choose']=='process') {

	$time=date("H:i:s",time());  
 $_SESSION['stationID']=$_POST['station'];
 $_SESSION['stationName']=$_POST['station_name'];
 $_SESSION['processName']=$_POST['pro_name'];
 $_SESSION['processID']=$_POST['option'];

$response['post']=$_POST['station_name'];


$today=date("d-m-Y");
                $check_session=$mysqli->query("SELECT * FROM sesiones WHERE fecha='$today' AND proceso=".$_POST['option']." AND estacion=".$_SESSION['stationID']." AND operador=".$_SESSION['idUser']);
                $datas=mysqli_fetch_assoc($check_session);
               
                if ($check_session->num_rows>0) {
                   $_SESSION['stat_session']=$datas['id_sesion'];
                   $mysqli->query("UPDATE sesiones SET active=1 WHERE id_sesion=".$datas['id_sesion']);
                   $virtual=mysqli_fetch_assoc($mysqli->query("SELECT is_virtual FROM tiraje WHERE idtiraje=".$datas['tiro_actual']));
                    $_SESSION['is_virtual']= $virtual['is_virtual'];
                           if ($datas['actividad_actual']=='ajuste'){
                            
                                $response['page']='index2.php';
                                 $response['proceed']='true';
                            
                        }

                        elseif ($datas['actividad_actual']=='tiro'){
                          $pname=$_POST['pro_name'];
                            
                            $isVirtual=mysqli_fetch_assoc($mysqli->query("SELECT elemento_virtual FROM personal_process WHERE status='actual' AND proceso_actual='$pname' "));
                            if ($isVirtual['elemento_virtual']!=null) {
                               
                               $response['page']='index3_5.php';
                               $response['proceed']='true';
                            }else{
                                
                                $response['page']='index3.php';
                                $response['proceed']='true';
                            }
                            }
                            else{
                                
                                $response['page']='index2.php';
                                $response['proceed']='true';
                                                     
                            } 
                        }
                        else{
                           

                          
                            if(date("w")==1||date("w")==4){
                               
                                if (strtotime($time)>=strtotime('09:00:00')) {
                                     $logged_in=$_SESSION['idUser'];
                                     $diference= gmdate("H:i:s",strtotime($time)-strtotime('08:45:00'));
                                     $pro_id=$_POST['option'];
                                     $station_id=$_POST['station'];
                                $op_query=$mysqli->query("INSERT INTO sesiones(operador,estacion,proceso,actividad_actual,active,en_tiempo,asaichi_cumplido,fecha,inicio_ajuste) VALUES($logged_in,$station_id,$pro_id,2,1,1,1,'$today','$time')");
                                if ($op_query) {
                                $_SESSION['stat_session']=$mysqli->insert_id;
                                $_SESSION['is_virtual']='false';
                                    $init_tiraje     = "INSERT INTO tiraje(id_estacion,id_proceso,horadeldia_ajuste, fechadeldia_ajuste,id_user,id_sesion) VALUES ($station_id,".$_SESSION['processID'].",'$time','$today', $logged_in, ".$_SESSION['stat_session'].")";
            
                                $resultado = $mysqli->query($init_tiraje);
                                if ($resultado) {
                                $lastTiraje=$mysqli->insert_id;

                                $mysqli->query("UPDATE sesiones SET tiro_actual=$lastTiraje WHERE fecha='$today' AND estacion=".$_SESSION['stationID']." AND proceso=".$_SESSION['processID']." AND operador=".$_SESSION['idUser']);
                                $deadtime="INSERT INTO `tiempo_muerto` (`id_tiempo_muerto`, `tiempo_muerto`, `fecha`, `id_estacion`, `id_user`, `numodt`, `id_orden`, `seccion`, `hora_del_dia`, `id_tiraje`, `id_sesion`) VALUES (NULL, '$diference', '$today', $station_id, $logged_in, NULL, NULL, 4, '$time', $lastTiraje, ".$_SESSION['stat_session'].")";
                                $guardandoAlMuerto=$mysqli->query($deadtime);
                                if (!$guardandoAlMuerto) {
                                   $log->lwrite($deadtime,$today.'_'.$_SESSION['logged_in'].'ERROR_ICHI');
                                $log->lclose();
                                }

                               
                                }else{

                                    $response['error']=printf($mysqli->error);

                                }
                                $response['page']='index2.php';
                                $response['proceed']='true';
                            }else{
                                
                                $response['proceed']='false';
                                $response['error']=printf($mysqli->error);
                            }
                                }else{

                                   $logged_in=$_SESSION['idUser'];
                                 
                                $pro_id=$process['id_proceso'];
                                $station_id=$station['id_estacion'];
                                $init_sesion="INSERT INTO sesiones(operador,estacion,proceso,actividad_actual,active,en_tiempo,asaichi_cumplido,fecha,inicio_ajuste) VALUES($logged_in,$station_id,".$myProcess['id_proceso'].",1,1,1,1,'$today','$time')";
                                $op_query=$mysqli->query($init_sesion);
                                if ($op_query) {
                                    $_SESSION['stat_session']=$mysqli->insert_id;
                                    $_SESSION['is_virtual']='false';
                                    $init_tiraje     = "INSERT INTO tiraje(id_estacion,id_proceso,horadeldia_ajuste, fechadeldia_ajuste,id_user,id_sesion) VALUES ($station_id,".$_SESSION['processID'].",'$time','$today', $logged_in, ".$_SESSION['stat_session'].")";
            
                                $resultado = $mysqli->query($init_tiraje);
                                if ($resultado) {
                                   $lastTiraje=$mysqli->insert_id;

                                $mysqli->query("UPDATE sesiones SET tiro_actual=$lastTiraje WHERE fecha='$today' AND estacion=".$_SESSION['stationID']." AND proceso=".$_SESSION['processID']." AND operador=$logged_in");

                                if (strtotime($time)>=strtotime('08:45:00')) {
                                
                                 $diference= gmdate("H:i:s",strtotime($time)-strtotime('08:45:00'));
                            $deadtime="INSERT INTO `tiempo_muerto` (`id_tiempo_muerto`, `tiempo_muerto`, `fecha`, `id_estacion`, `id_user`, `numodt`, `id_orden`, `seccion`, `hora_del_dia`, `id_tiraje`, `id_sesion`) VALUES (NULL, '$diference', '$today', $station_id, $logged_in, NULL, NULL, 4, '$time', $lastTiraje, ".$_SESSION['stat_session'].")";
                                $guardandoAlMuerto=$mysqli->query($deadtime);
                                if (!$guardandoAlMuerto) {
                                   $log->lwrite($deadtime,$today.'_'.$_SESSION['logged_in'].'ERROR_DESFASE');
                                $log->lclose();
                                }
                            }

                               
                                }else{
                                   
                                   
                                     $log->lwrite($init_tiraje,'ERROR_ICHI');
                                    $log->lclose();
                                }

                                 
                            }else{
                                
                                 $log->lwrite($init_sesion,'ERROR_ICHI');
                                    $log->lclose();
                                
                            }
                                    
                                    $response['page']='asaichii.php';
                                }
                                
                            }else{
                              $logged_in=$_SESSION['idUser'];
                                    $pro_id=$_POST['option'];
                                     $station_id=$_POST['station'];
                            $op_query=$mysqli->query("INSERT INTO sesiones(operador,estacion,proceso,actividad_actual,active,en_tiempo,asaichi_cumplido,fecha,inicio_ajuste) VALUES($logged_in,$station_id,$pro_id,2,1,1,1,'$today','$time')");
                            if ($op_query) {
                              $_SESSION['stat_session']=$mysqli->insert_id;
                              $_SESSION['is_virtual']='false';
                              $init_tiraje     = "INSERT INTO tiraje(id_estacion,id_proceso,horadeldia_ajuste, fechadeldia_ajuste,id_user,id_sesion) VALUES ($station_id,".$_SESSION['processID'].",'$time','$today', $logged_in, ".$_SESSION['stat_session'].")";
            
                                $resultado = $mysqli->query($init_tiraje);
                                if ($resultado) {
                                   $lastTiraje=$mysqli->insert_id;

                                $mysqli->query("UPDATE sesiones SET tiro_actual=$lastTiraje WHERE fecha='$today' AND estacion=".$_SESSION['stationID']." AND proceso=".$_SESSION['processID']);
                                if (strtotime($time)>=strtotime('08:45:00')) {
                                
                                 $diference= gmdate("H:i:s",strtotime($time)-strtotime('08:45:00'));
                            $deadtime="INSERT INTO `tiempo_muerto` (`id_tiempo_muerto`, `tiempo_muerto`, `fecha`, `id_estacion`, `id_user`, `numodt`, `id_orden`, `seccion`, `hora_del_dia`, `id_tiraje`, `id_sesion`) VALUES (NULL, '$diference', '$today', $station_id, $logged_in, NULL, NULL, 4, '$time', $lastTiraje, ".$_SESSION['stat_session'].")";
                                $guardandoAlMuerto=$mysqli->query($deadtime);
                                if (!$guardandoAlMuerto) {
                                   $log->lwrite($deadtime,$today.'_'.$_SESSION['logged_in'].'ERROR_DESFASE');
                                $log->lclose();
                                }
                            }

                               
                                }else{
                                    $response['error']=printf($mysqli->error);
                                }
                              $response['page']='index2.php';
                              $response['proceed']='true';

                            }else{
                                
                                $response['proceed']='false';
                                $response['error']=printf($mysqli->error);
                            }

                                
                            }

                            
                        } 





echo json_encode($response);





}



?>
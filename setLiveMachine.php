<?php
error_reporting(0);
require('classes/functions.class.php');
$log = new Functions();
session_start();
date_default_timezone_set("America/Mexico_City"); 
 require('saves/conexion.php');

$current_process=$_SESSION['processID'];
$current_station=$_SESSION['stationID'];

if ($_POST['choose']=='station') {
 

}elseif ($_POST['choose']=='process') {

 $time=date("H:i:s",time());  
 $_SESSION['stationID']=$_POST['station'];
 $_SESSION['stationName']=$_POST['station_name'];
 $_SESSION['processName']=$_POST['pro_name'];
 $_SESSION['processID']=$_POST['option'];

$response['post']=$_POST['station_name'];


$today=date("d-m-Y");
                $check_session=$mysqli->query("SELECT * FROM sesiones WHERE fecha='$today' AND proceso=".$current_process." AND estacion=".$current_station." AND operador=".$_SESSION['idUser']);
                $datas=mysqli_fetch_assoc($check_session);
               
                
                  $log->lwrite('si existe sesion ','QUE_PASA');
                  $log->lwrite('active: '.$datas['active'],'QUE_PASA');
                  $log->lwrite('opcion: '.$_POST['option'],'QUE_PASA');
                  $log->lwrite('sesion: '.$datas['id_sesion'],'QUE_PASA');
                  $log->lclose();

                  if ($datas['active']=='true') {
                    
                  $_SESSION['stat_session']=$datas['id_sesion'];

                  $mysqli->query("UPDATE sesiones SET proceso=".$_POST['option']." WHERE id_sesion=".$datas['id_sesion']);
                  $mysqli->query("UPDATE tiraje SET id_proceso=".$_POST['option']." WHERE idtiraje=".$datas['tiro_actual']);

                  $pname=$_POST['pro_name'];
                            
                             
                  $response['page']='index2.php';
                  $response['proceed']='true';
                            

                  }else{

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
                            
                                $response['page']='index3.php';
                                $response['proceed']='true';
                            
                            }
                            else{
                                
                                $response['page']='index2.php';
                                $response['proceed']='true';
                                                     
                            } 

                  }
                  
                        





echo json_encode($response);





}elseif ($_POST['choose']=='pending') {

  $time=date("H:i:s",time());
  $response['post']=$_POST['station_name'];
  $today=date("d-m-Y");

  $getPending=mysqli_fetch_assoc($mysqli->query("SELECT *,TIME_TO_SEC(tiempo)AS tiempo_cola FROM en_cola WHERE id_cola=".$_POST['cola']));
  $getActivity=$mysqli->query("SELECT * FROM sesiones WHERE id_sesion=".$getPending['sesion']);
    
$activity=mysqli_fetch_assoc($getActivity); 
                  
 $_SESSION['stationID']=$_POST['station'];
 $_SESSION['stationName']=$_POST['station_name'];
 $_SESSION['processName']=$_POST['pro_name'];
 $_SESSION['processID']=$_POST['option'];
 $_SESSION['stat_session']=$getPending['sesion'];               
 $_SESSION['pending_exist']='true';
 $_SESSION['pendingID']=$_POST['cola'];                 
 $_SESSION['tiempo_cola']=$getPending['tiempo_cola'];
 $_SESSION['proceso_cola']=$_POST['option'];

 $mysqli->query("UPDATE sesiones SET proceso=".$_POST['option']." WHERE id_sesion=".$getPending['sesion']);
$mysqli->query("UPDATE tiraje SET id_proceso=".$_POST['option']." WHERE idtiraje=".$activity['tiro_actual']);             
  $response['page']='index2.php';
  $response['proceed']='true';
   $log->lwrite('option: '.$_POST['option'],'COLA');
   $log->lwrite('proceso_cola: '.$_SESSION['proceso_cola'],'COLA');
                  $log->lclose();                         

                        





echo json_encode($response);





}



?>
<?php
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
                $check=$mysqli->query("SELECT * FROM sesiones WHERE fecha='$today' AND proceso=".$_POST['option']);
                $datas=mysqli_fetch_assoc($check);
               
                if ($check->num_rows>0) {
                           if ($datas['actividad_actual']=='ajuste'){
                            
                                $response['page']='index2.php';
                                 $response['proceed']='true';
                            
                        }

                        elseif ($datas['actividad_actual']=='tiro'){
                            
                            $isVirtual=mysqli_fetch_assoc( $mysqli->query("SELECT elemento_virtual FROM personal_process WHERE status='actual' AND estacion=".$_POST['station']." "));
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
                            if(date("w")==1||date("w")==3||date("w")==5){
                                $hora_actual=date(" H:i:s", time());
                                if (strtotime($hora_actual)>=strtotime('09:00:00')) {
                                     $logged_in=$_SESSION['idUser'];
                                     $pro_id=$_POST['option'];
                                     $station_id=$_POST['station'];
                                $op_query=$mysqli->query("INSERT INTO sesiones(operador,estacion,proceso,actividad_actual,active,en_tiempo,asaichi_cumplido,fecha,inicio_ajuste) VALUES($logged_in,$station_id,$pro_id,2,1,1,1,'$today','$time')");
                                if ($op_query) {
                                
                                $response['page']='index2.php';
                                $response['proceed']='true';
                            }else{
                                
                                $response['proceed']='false';
                                $response['error']=printf($mysqli->error);
                            }
                                }else{
                                    
                                    $response['page']='asaichii.php';
                                }
                                
                            }else{
                                 $logged_in=$_SESSION['idUser'];
                                     $pro_id=$_POST['option'];
                                     $station_id=$_POST['station'];
                            $op_query=$mysqli->query("INSERT INTO sesiones(operador,estacion,proceso,actividad_actual,active,en_tiempo,asaichi_cumplido,fecha,inicio_ajuste) VALUES($logged_in,$station_id,$pro_id,2,1,1,1,'$today','$time')");
                            if ($op_query) {
                              
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
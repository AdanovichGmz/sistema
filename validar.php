
<?php
error_reporting(0);
require('classes/functions.class.php');
$log = new Functions();
ini_set("session.gc_maxlifetime","7200");  
session_start();
require("saves/conexion.php");
date_default_timezone_set("America/Mexico_City");
$username=$_POST['usuario'];
$pass=$_POST['pass'];
//$nmac=$_POST['maquina'];
$time=date("H:i:s",time());

$sql2=mysqli_query($mysqli,"SELECT * FROM usuarios WHERE usuario='$username'");
if($f=mysqli_fetch_assoc($sql2)){
    if($pass==$f['pasadmin']){
        $_SESSION['idUser']=$f['id'];
        $_SESSION['logged_in']=$f['id'];
        $_SESSION['rol']=$f['rol'];

       
        echo "<script>location.href='admin/reporteindex.php'</script>";

    }else if($pass==$f['sudopass']){
        $_SESSION['idUser']=$f['id'];
        $_SESSION['logged_in']=$f['logged_in'];
        $_SESSION['rol']=$f['rol'];

        
        echo "<script>location.href='superadmin/'</script>";

    }else  if($pass==$f['password']){
        $_SESSION['idUser']=$f['id'];
        $_SESSION['logged_in']=$f['logged_in'];
        $_SESSION['rol']=$f['rol'];
        $_SESSION['MM_Foto_user'] = $f['foto'];
       
        $getStations=$mysqli->query("SELECT * FROM usuarios_estaciones WHERE id_usuario=".$f['id']);
        if($f['is_team']=='true'){           
        $station = mysqli_fetch_assoc($getStations);
       $mystation=mysqli_fetch_assoc($mysqli->query("SELECT * FROM estaciones WHERE id_estacion=".$station['id_estacion']));
        $_SESSION['stationID']=$station['id_estacion'];
        $_SESSION['stationName']=$mystation['nombre_estacion'];
        $getMyProcess=$mysqli->query("SELECT * FROM team_leaders WHERE id_usuario=".$f['id']);
        $myProcess = mysqli_fetch_assoc($getMyProcess); 
        $_SESSION['processID']=$myProcess['id_proceso'];
        $getProcess="SELECT * FROM procesos_catalogo WHERE id_proceso=".$myProcess['id_proceso'];
                $catalog_process=mysqli_fetch_assoc($mysqli->query($getProcess));
            $_SESSION['processName']=$catalog_process['nombre_proceso'];

        header("Location: encuadernacion/");

        }
        else{
        if ($getStations->num_rows>1) {
            $_SESSION['environment']='taller';
        header("Location: options.php");

        }else{

        $station = mysqli_fetch_assoc($getStations);
        $getMyProcess=$mysqli->query("SELECT * FROM estaciones_procesos WHERE id_estacion=".$station['id_estacion']);
        $myProcess = mysqli_fetch_assoc($getMyProcess); 

        if ($getMyProcess->num_rows>1) {
            $_SESSION['environment']='taller';
              header("Location: options.php");
           }else{

                $getProcess="SELECT * FROM procesos_catalogo WHERE id_proceso=".$myProcess['id_proceso'];
                $catalog_process=mysqli_fetch_assoc($mysqli->query($getProcess));

                
                $mystation=mysqli_fetch_assoc($mysqli->query("SELECT * FROM estaciones WHERE id_estacion=".$station['id_estacion']));

                $_SESSION['stationID']=$station['id_estacion'];
                $_SESSION['stationName']=$mystation['nombre_estacion'];
                $_SESSION['processName']=$catalog_process['nombre_proceso'];
                $_SESSION['processID']=$catalog_process['id_proceso'];
                $_SESSION['pending_exist']='false';   
                $today=date("d-m-Y");
                $check_session=$mysqli->query("SELECT * FROM sesiones WHERE fecha='$today' AND estacion=".$station['id_estacion']." AND proceso=".$catalog_process['id_proceso']." AND operador=".$f['id']);
                $datas=mysqli_fetch_assoc($check_session);
               
                if ($check_session->num_rows>0) {
                    $_SESSION['stat_session']=$datas['id_sesion'];
                    $virtual=mysqli_fetch_assoc($mysqli->query("SELECT is_virtual FROM tiraje WHERE idtiraje=".$datas['tiro_actual']));
                    $_SESSION['is_virtual']= $virtual['is_virtual'];
                    $mysqli->query("UPDATE sesiones SET active=1 WHERE id_sesion=".$datas['id_sesion']);
                           if ($datas['actividad_actual']=='ajuste'){
                                $_SESSION['environment']='taller';
                                header("Location: index2.php");
                            
                        }

                        elseif ($datas['actividad_actual']=='tiro'){
                            
                            $isVirtual=mysqli_fetch_assoc( $mysqli->query("SELECT elemento_virtual FROM personal_process WHERE status='actual' AND estacion=".$station['id_estacion']." "));
                                $_SESSION['environment']='taller';

                                header("Location: index3.php");
                            
                        }
                            
                        else{
                                $_SESSION['environment']='taller';
                                header("Location: index2.php");
                                                     
                            } 
                        }
            else{
                    if(date("w")==1||date("w")==4){
                               
                                if (strtotime($time)>=strtotime('09:00:00')) {
                                $logged_in=$_SESSION['idUser'];
                                 $diference= gmdate("H:i:s",strtotime($time)-strtotime('08:45:00'));
                                 
                                $pro_id=$process['id_proceso'];
                                $station_id=$station['id_estacion'];

                                $init_sesion="INSERT INTO sesiones(operador,estacion,proceso,actividad_actual,active,en_tiempo,asaichi_cumplido,fecha,inicio_ajuste) VALUES($logged_in,$station_id,".$myProcess['id_proceso'].",2,1,1,1,'$today','$time')";
                                $op_query=$mysqli->query($init_sesion);
                                if ($op_query) {
                                    $_SESSION['stat_session']=$mysqli->insert_id;
                                    $_SESSION['is_virtual']='false';

                                    $init_tiraje     = "INSERT INTO tiraje(id_estacion,id_proceso,horadeldia_ajuste, fechadeldia_ajuste,id_user,id_sesion) VALUES ($station_id,".$_SESSION['processID'].",'$time','$today', $logged_in, ".$_SESSION['stat_session'].")";
            
                                $resultado = $mysqli->query($init_tiraje);
                                if ($resultado) {
                                   $lastTiraje=$mysqli->insert_id;

                                $mysqli->query("UPDATE sesiones SET tiro_actual=$lastTiraje WHERE fecha='$today' AND estacion=".$_SESSION['stationID']." AND proceso=".$_SESSION['processID']." AND operador=$logged_in");

                                $deadtime="INSERT INTO `tiempo_muerto` (`id_tiempo_muerto`, `tiempo_muerto`, `fecha`, `id_estacion`, `id_user`, `numodt`, `id_orden`, `seccion`, `hora_del_dia`, `id_tiraje`, `id_sesion`) VALUES (NULL, '$diference', '$today', $station_id, $logged_in, NULL, NULL, 4, '$time', $lastTiraje, ".$_SESSION['stat_session'].")";
                                $guardandoAlMuerto=$mysqli->query($deadtime);
                                if (!$guardandoAlMuerto) {
                                   $log->lwrite($deadtime,$today.'_'.$_SESSION['logged_in'].'ERROR_ICHI');
                                $log->lclose();
                                }

                                $_SESSION['environment']='taller';
                                header("Location: index2.php");
                                }else{
                                    printf($mysqli->error);
                                    echo "No se inserto el tiraje";
                                     $log->lwrite($init_tiraje,'ERROR_ICHI');
          $log->lclose();
                                }

                                 
                            }else{
                                printf($mysqli->error);
                                 $log->lwrite($init_sesion,'ERROR_ICHI');
          $log->lclose();
                                
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
                            $_SESSION['environment']='taller';
                               header("Location: asaichii.php");
                                }else{
                                    printf($mysqli->error);
                                    echo "No se inserto el tiraje";
                                     $log->lwrite($init_tiraje,'ERROR_ICHI');
          $log->lclose();
                                }

                                 
                            }else{
                                printf($mysqli->error);
                                 $log->lwrite($init_sesion,'ERROR_ICHI');
          $log->lclose();
                                
                            }
                                    
                                }
                                
                    }else{
                         

                            $logged_in=$_SESSION['idUser'];
                                  
                                     $station_id=$station['id_estacion'];
                                     $in_query="INSERT INTO sesiones(operador,estacion,proceso,actividad_actual,active,en_tiempo,asaichi_cumplido,fecha,inicio_ajuste) VALUES($logged_in,$station_id,".$myProcess['id_proceso'].",2,1,1,1,'$today','$time')";
                            $op_query=$mysqli->query($in_query);
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
                            $_SESSION['environment']='taller';
                                header("Location: index2.php");
                                }else{
                                    printf($mysqli->error);
                                    echo "No se inserto el tiraje";
                                }
                                $_SESSION['environment']='taller';
                              header("Location: index2.php");

                            }else{
                                printf($mysqli->error);
                                echo $in_query;
                            }

                                
                            }

                            
                        } 





                //header("Location: index2.php");


           }  

      

        }
    }



        

        
     
    
 

        ////header("Location: asaichii.php");
        }else{
        echo '<script>alert("CONTRASEÑA INCORRECTA")</script> ';

        echo "<script>location.href='index.php'</script>";
        }
}else{

        echo '<script>alert("ESTE USUARIO NO EXISTE, VERIFIQUE CON SU ADMINISTRADOR DE USUARIOS")</script> ';

    echo "<script>location.href='index.php'</script>";

}





?>
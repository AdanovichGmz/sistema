
<?php


ini_set("session.gc_maxlifetime","7200");  
session_start();
require("saves/conexion.php");
date_default_timezone_set("America/Mexico_City");
$username=$_POST['usuario'];
$pass=$_POST['pass'];
//$nmac=$_POST['maquina'];
$time=date("H:i:s",time());

$sql2=mysqli_query($mysqli,"SELECT * FROM usuarios WHERE usuario='$username'");
if($f2=mysqli_fetch_assoc($sql2)){
    if($pass==$f2['pasadmin']){
        $_SESSION['idUser']=$f2['id'];
        $_SESSION['logged_in']=$f2['id'];
        $_SESSION['rol']=$f2['rol'];

       
        echo "<script>location.href='admin/reporteindex.php'</script>";

    }
}

$sql3=mysqli_query($mysqli,"SELECT * FROM usuarios WHERE usuario='$username'");
if($f3=mysqli_fetch_assoc($sql3)){
    if($pass==$f3['sudopass']){
        $_SESSION['idUser']=$f3['id'];
        $_SESSION['logged_in']=$f3['logged_in'];
        $_SESSION['rol']=$f3['rol'];

        
        echo "<script>location.href='superadmin/'</script>";

    }
}


$sql=mysqli_query($mysqli,"SELECT * FROM usuarios WHERE usuario='$username'");
if($f=mysqli_fetch_assoc($sql)){
    if($pass==$f['password']){
        $_SESSION['idUser']=$f['id'];
        $_SESSION['logged_in']=$f['logged_in'];
        $_SESSION['rol']=$f['rol'];
        $_SESSION['MM_Foto_user'] = $f['foto'];
       
        $getStations=$mysqli->query("SELECT * FROM usuarios_estaciones WHERE id_usuario=".$f['id']);
        $station = mysqli_fetch_assoc($getStations);

        if ($getStations->num_rows>1) {

        $_SESSION['stationID']=$f['id_estacion'];
        $_SESSION['stationName']=$machine['nombre_estacion']; 
        header("Location: options.php");

        }else{

         $getMyProcess=$mysqli->query("SELECT * FROM estaciones_procesos WHERE id_estacion=".$station['id_estacion']);
        $myProcess = mysqli_fetch_assoc($getMyProcess); 

        if ($getMyProcess->num_rows>1) {
              header("Location: options.php");
           } else{

                $getProcess="SELECT * FROM procesos_catalogo WHERE id_proceso=".$myProcess['id_proceso'];
                $personal_proces=mysqli_fetch_assoc($mysqli->query($getProcess));

                $process = mysqli_fetch_assoc($getMyProcess); 
                $mystation=mysqli_fetch_assoc($mysqli->query("SELECT * FROM estaciones WHERE id_estacion=".$station['id_estacion']));

                $_SESSION['stationID']=$station['id_estacion'];
                $_SESSION['stationName']=$mystation['nombre_estacion'];
                $_SESSION['processName']=$personal_proces['nombre_proceso'];
                $_SESSION['processID']=$personal_proces['id_proceso']; 
                $today=date("d-m-Y");
                $check=$mysqli->query("SELECT * FROM sesiones WHERE fecha='$today' AND estacion=".$station['id_estacion']." AND proceso=".$personal_process['id_proceso']);
                $datas=mysqli_fetch_assoc($check);
               
                if ($check->num_rows>0) {
                           if ($datas['actividad_actual']=='ajuste'){
                            
                                header("Location: index2.php");
                            
                        }

                        elseif ($datas['actividad_actual']=='tiro'){
                            
                            $isVirtual=mysqli_fetch_assoc( $mysqli->query("SELECT elemento_virtual FROM personal_process WHERE status='actual' AND estacion=".$station['id_estacion']." "));
                            if ($isVirtual['elemento_virtual']!=null) {
                               header("Location: index3_5.php");
                            }else{
                                header("Location: index3.php");
                            }
                            }
                            else{

                                header("Location: index2.php");
                                                     
                            } 
                        }
                        else{
                            if(date("w")==1||date("w")==3||date("w")==5){
                                $hora_actual=date(" H:i:s", time());
                                if (strtotime($hora_actual)>=strtotime('09:00:00')) {
                                $logged_in=$_SESSION['idUser'];
                                 
                                     $pro_id=$process['id_proceso'];
                                     $station_id=$station['id_estacion'];
                                $op_query=$mysqli->query("INSERT INTO sesiones(operador,estacion,proceso,actividad_actual,active,en_tiempo,asaichi_cumplido,fecha,inicio_ajuste) VALUES($logged_in,$station_id,".$myProcess['id_proceso'].",2,1,1,1,'$today','$time')");
                                if ($op_query) {
                                    $_SESSION['stat_session']=$mysqli->insert_id;
                                    $init_tiraje     = "INSERT INTO tiraje(id_estacion,horadeldia_ajuste, fechadeldia_ajuste,id_user,id_sesion) VALUES ($station_id,'$hora_actual','$today', $logged_in, ".$_SESSION['stat_session'].")";
            
                                $resultado = $mysqli->query($init_tiraje);
                                if ($resultado) {
                                   $lastTiraje=$mysqli->insert_id;

                                $mysqli->query("UPDATE sesiones SET tiro_actual=$lastTiraje WHERE fecha='$today' AND estacion=".$_SESSION['stationID']." AND proceso=".$_SESSION['processID']);

                                header("Location: index2.php");
                                }else{
                                    printf($mysqli->error);
                                    echo "No se inserto el tiraje";
                                }

                                 
                            }else{
                                printf($mysqli->error);
                                
                            }
                                }else{
                                    header("Location: asaichii.php");
                                }
                                
                            }else{
                                  $logged_in=$_SESSION['idUser'];
                                  
                                     $station_id=$station['id_estacion'];
                                     $in_query="INSERT INTO sesiones(operador,estacion,proceso,actividad_actual,active,en_tiempo,asaichi_cumplido,fecha,inicio_ajuste) VALUES($logged_in,$station_id,".$myProcess['id_proceso'].",2,1,1,1,'$today','$time')";
                            $op_query=$mysqli->query($in_query);
                            if ($op_query) {
                                $_SESSION['stat_session']=$mysqli->insert_id;
                                    $init_tiraje     = "INSERT INTO tiraje(id_estacion,horadeldia_ajuste, fechadeldia_ajuste,id_user,id_sesion) VALUES ($station_id,'$hora_actual','$today', $logged_in, ".$_SESSION['stat_session'].")";
            
                                $resultado = $mysqli->query($init_tiraje);
                                if ($resultado) {
                                   $lastTiraje=$mysqli->insert_id;

                                $mysqli->query("UPDATE sesiones SET tiro_actual=$lastTiraje WHERE fecha='$today' AND estacion=".$_SESSION['stationID']." AND proceso=".$_SESSION['processID']);

                                header("Location: index2.php");
                                }else{
                                    printf($mysqli->error);
                                    echo "No se inserto el tiraje";
                                }
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
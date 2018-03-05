<?php
session_start();
date_default_timezone_set("America/Mexico_City"); 
require('saves/conexion.php');
require('classes/functions.class.php');
$log = new Functions();
function logpost($post){
	$info='';
  foreach ($post as $key => $value) {
    $info.=$key.": ".$value." | ";
  }
  return $info;

}  
$radios=(isset($_POST['radios']))? $_POST['radios'] : 'Otro';
$observaciones=$_POST['observaciones'];
$tiro=$_POST['tiro'];
//foreach ($_POST['opcion'] as $opcion); 
$inicioAlerta=$_POST['inicioAlerta'];
$tiempoalertamaquina=$_POST['tiempoalertamaquina'];


$horafin=date(" H:i:s", time());
$horadeldiaam=$_POST['horadeldiaam'];
$fechadeldiaam=$_POST['fechadeldiaam'];


$userID =$_SESSION['idUser'];
$getMachine = $_SESSION['stationName'];
$machineID = $_SESSION['stationID'];
 $log->lwrite($_POST['logged_in'].": ".logpost($_POST),'ALERTAS_OPERACION_'.date("d-m-Y"));
$log->lwrite("Hora fin alerta: ".$horafin,'ALERTAS_OPERACION_'.date("d-m-Y"));
$log->lwrite("---------------------------",'ALERTAS_OPERACION_'.date("d-m-Y"));

$query="INSERT INTO alertamaquinaoperacion (radios, observaciones, tiempoalertamaquina, id_estacion, id_usuario, horadeldiaam,  horafin_alerta, fechadeldiaam,id_tiraje) VALUES ('$radios','$observaciones','$tiempoalertamaquina',$machineID,$userID,'$inicioAlerta', '$horafin', '$fechadeldiaam',$tiro)";

$acum_alert=mysqli_fetch_assoc($mysqli->query("SELECT tiempo_alert FROM sesiones WHERE operador=$userID AND fecha='$fechadeldiaam' AND estacion=".$_SESSION['stationID']." AND proceso=".$_SESSION['processID']));

$tiempo_alert=$acum_alert['tiempo_alert']+strtotime("1970-01-01 $tiempoalertamaquina UTC");

$changestatus=$mysqli->query("UPDATE sesiones SET tiempo_alert='$tiempo_alert' WHERE operador=$userID AND fecha='$fechadeldiaam' AND estacion=".$_SESSION['stationID']." AND proceso=".$_SESSION['processID']);


	if ($changestatus) {
		echo "tiempo alerta guardado";
	}else{
		printf($mysqli->error);
	}

$resultado=$mysqli->query($query);
if ( $resultado) {
print_r($_POST);
 }else{
            printf("ERROR!!! %s\n", $mysqli->error);
            echo $query;
          }
/*

require('saves/conexion.php');
$odt=$_POST['odt'];
$nohay=$_POST['nohay'];
$seperdio=$_POST['seperdio'];
$arrenfalso=$_POST['arrenfalso'];
//$opcion = $_POST['opcion'];
foreach ($_POST['opcion'] as $opcion); 
$observaciones=$_POST['observaciones'];
$tiempoalertamaquina=$_POST['tiempoalertamaquina'];
$nombremaquinaajuste=$_POST['nombremaquinaajuste'];
$logged_in=$_POST['logged_in'];
$horadeldiaam=$_POST['horadeldiaam'];
$fechadeldiaam=$_POST['fechadeldiaam'];




$query="INSERT INTO alertaMaquinaOperacion (odt, nohay, seperdio, arrenfalso, opcion, observaciones, tiempoalertamaquina, nombremaquinaajuste, logged_in, horadeldiaam, fechadeldiaam) VALUES ('$odt','$nohay','$seperdio','$arrenfalso','$opcion','$observaciones','$tiempoalertamaquina','$nombremaquinaajuste','$logged_in','$horadeldiaam','$fechadeldiaam')";


$resultado=$mysqli->query($query);
//echo "Tus datos fueron enviados correctamente <b>".$_POST['logged_in']."</b>";
echo $query; */
?>


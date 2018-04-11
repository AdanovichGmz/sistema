<?php
session_start();


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
date_default_timezone_set("America/Mexico_City"); 
$event=$_POST['radios'];
//foreach ($_POST['opcion'] as $opcion); 

$breaktime=$_POST['breaktime'];

$timeInSeconds = strtotime($breaktime) - strtotime('TODAY');
if ($event=='Comida') {

if ($timeInSeconds<900) {
	$radios='Sanitario';
	echo "comida a baño";
	echo "Segundos comida ".$timeInSeconds;
}else{
	$radios=$event;	
}}
else{
	$radios=$event;
}
 $log->lwrite($_POST['logged_in'].": ".logpost($_POST),'COMIDAS_'.date("d-m-Y"));
 

$maquina=$_SESSION['stationID'];
$specific=(isset($_POST['specific']))? $_POST['specific']:'';
$logged_in=$_POST['logged_in'];
$horadeldiaam=$_POST['inicioAlertaEat'];
$fechadeldiaam=$_POST['fechadeldiaam'];

$userID = $_SESSION['idUser'];
$seccion=$_POST['curr-section'];
$stationID = $_SESSION['stationID'];
$tiro=mysqli_fetch_assoc($mysqli->query("SELECT tiro_actual FROM sesiones WHERE id_sesion=".$_SESSION['stat_session']));
$tiraje=$tiro['tiro_actual'];
$horafin=date(" H:i:s", time());
$log->lwrite("Hora fin comida: ".$horafin,'COMIDAS_'.date("d-m-Y"));
$log->lwrite("---------------------------",'COMIDAS_'.date("d-m-Y"));
$log->lclose();
echo "hora fin: ".$horafin;
$query="INSERT INTO breaktime (radios,otra_actividad, breaktime, id_estacion, id_usuario,id_tiraje,seccion, horadeldiaam, hora_fin_comida, fechadeldiaam, vdate) VALUES ('$radios','$specific','$breaktime',$stationID,$userID,$tiraje,'$seccion', '$horadeldiaam','$horafin', '$fechadeldiaam',now())";


$resultado=$mysqli->query($query);
//echo "Tus datos fueron enviados correctamente <b>".$_POST['logged_in']."</b>";

//print_r($_POST) ;
if ( $resultado) {
print_r($_POST);
$acum_break=mysqli_fetch_assoc($mysqli->query("SELECT tiempo_comida FROM sesiones WHERE operador=$userID AND fecha='$fechadeldiaam' AND estacion=".$_SESSION['stationID']." AND proceso=".$_SESSION['processID']));

$tiempo_break=$acum_break['tiempo_comida']+strtotime("1970-01-01 $breaktime UTC");

$changestatus=$mysqli->query("UPDATE sesiones SET tiempo_comida=$tiempo_break WHERE operador=$userID AND fecha='$fechadeldiaam' AND estacion=".$_SESSION['stationID']." AND proceso=".$_SESSION['processID']);


  if ($changestatus) {
    echo "tiempo alerta guardado";
  }else{
    printf($mysqli->error);
  }
 }else{	print_r($_POST);
            printf("Errormessage: %s\n", $mysqli->error);
            echo $query;
          }


?>

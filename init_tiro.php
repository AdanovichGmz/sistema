<?php
date_default_timezone_set("America/Mexico_City");
session_start();
require('saves/conexion.php');
require('classes/functions.class.php');
$today=date("d-m-Y");

$log = new Functions();
$init=$_POST['init'];
if ($init=='init') {
	$hora=$_POST['hora'];
$fecha=$_POST['fecha'];
$userID = $_SESSION['idUser'];
$stationID = $_SESSION['stationID'];


$query     = "INSERT INTO tiraje(id_estacion,horadeldia_ajuste, fechadeldia_ajuste,id_user,id_sesion) VALUES ($stationID,'$hora','$fecha', $userID, ".$_SESSION['stat_session'].")";
            
$resultado = $mysqli->query($query);

 $lastTiraje=$mysqli->insert_id;

$mysqli->query("UPDATE sesiones SET tiro_actual=$lastTiraje WHERE fecha='$today' AND estacion=".$_SESSION['stationID']." AND proceso=".$_SESSION['processID']);


 $log->lwrite('Tiraje recien insertado: '.$lastTiraje.' usuario: '.$userID,'INIT_TIRO');
 
 $log->lwrite(implode(' | ', $_POST),'INIT_TIRO');
 $log->lclose();
if ( $resultado) { ?>
<input type="hidden" name="actual_tiro" id="actual_tiro" value="<?=$lastTiraje ?>">
<?php

 }else{	print_r($_POST);
            printf("Errormessage: %s\n", $mysqli->error);
            echo $query;
          }

}elseif ($init=='reinit') {
	/*
	$hora=date(" H:i:s", time());
	$tiraje=$_POST['tiraje'];
	$query="UPDATE tiraje SET horadeldia_ajuste='$hora' WHERE idtiraje=$tiraje";
	$resultado=$mysqli->query($query);
	$hora = array('hora' =>$hora);
	if ($resultado) {
		echo json_encode($hora);
	}*/
}



?>

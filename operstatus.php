<?php 

 session_start();
date_default_timezone_set("America/Mexico_City"); 
require('classes/functions.class.php');
$log = new Functions();
require('saves/conexion.php');
 $stationID=$_SESSION['stationID'];
 $stationName=$_SESSION['stationName'];
  $logged_in=$_SESSION['idUser'];
  $section=$_POST['section'];
  $logged_in=$_SESSION['idUser'];
  $pro_id=$_SESSION['processID'];
$time=date("H:i:s",time());                                    
$today=date("d-m-Y");


if ($section=='asaichii') {
	$check=$mysqli->query("SELECT * FROM operacion_estatus WHERE fecha='$today' AND maquina=$stationID");
print_r($check);
if ($check->num_rows==0) {
	$op_query=$mysqli->query("INSERT INTO operacion_estatus(operador,maquina,actividad_actual,en_tiempo,fecha) VALUES($logged_in,$stationID,1,1,'$today')");
	if ($op_query) {
		echo "se inserto el operstatus ".$section;
	}else{
		printf($mysqli->error);
	}
}
}
elseif ($section=='ajuste') {

	$check=$mysqli->query("SELECT * FROM sesiones WHERE fecha='$today' AND operador=".$logged_in);

if ($check->num_rows==0) {

	//$log->lwrite('aca es donde se inserto','AQUI_FUE');
 
    //$log->lclose();
	$op_query=$mysqli->query("INSERT INTO sesiones(operador,estacion,proceso,actividad_actual,active,en_tiempo,asaichi_cumplido,fecha) VALUES($logged_in,$stationID,$pro_id,2,1,1,1,'$today')");

	if ($op_query) {
		echo "se inserto el operstatus ".$section;
	}else{
		printf($mysqli->error);
	}
}else{

	$changestatus=$mysqli->query("UPDATE sesiones SET actividad_actual=2 WHERE fecha='$today' AND estacion=".$_SESSION['stationID']." AND proceso=".$_SESSION['processID']);

	if ($changestatus) {
		echo "estatus cambiado a ajuste";
	}else{
		printf($mysqli->error);
	}
}
	
}
elseif ($section=='tiro') {
	$check=$mysqli->query("SELECT * FROM sesiones WHERE fecha='$today'AND estacion=".$_SESSION['stationID']." AND proceso=".$_SESSION['processID']);

if ($check->num_rows==0) {
	$op_query=$mysqli->query("INSERT INTO operacion_estatus(operador,maquina,actividad_actual,en_tiempo,fecha) VALUES($logged_in,$stationID,1,1,'$today')");
	if ($op_query) {
		echo "se inserto el operstatus ".$section;
	}else{
		printf($mysqli->error);
	}

}else{
	$changestatus=$mysqli->query("UPDATE sesiones SET actividad_actual=3 WHERE fecha='$today' AND estacion=".$_SESSION['stationID']." AND proceso=".$_SESSION['processID']);
	if ($changestatus) {
		echo "estatus cambiado a tiro";
	}else{
		printf($mysqli->error);
	}
}
	
}
elseif ($section=='comida') {
	
	$changestatus=$mysqli->query("UPDATE sesiones SET actividad_actual=6 WHERE fecha='$today' AND estacion=".$_SESSION['stationID']." AND proceso=".$_SESSION['processID']);
	if ($changestatus) {
		echo "estatus cambiado a comida";
	}else{
		printf($mysqli->error);
	}

}
elseif ($section=='paro') {

	$changestatus=$mysqli->query("UPDATE operacion_estatus SET actividad_actual=5 WHERE fecha='$today' AND maquina=$stationID ");

	
}
elseif ($section=='alerta') {


	$changestatus=$mysqli->query("UPDATE sesiones SET actividad_actual=4 WHERE fecha='$today' AND estacion=".$_SESSION['stationID']." AND proceso=".$_SESSION['processID']);


	if ($changestatus) {
		echo "estatus cambiado a alerta";
	}else{
		printf($mysqli->error);
	}
}
elseif ($section=='outtime') {

	$changestatus=$mysqli->query("UPDATE sesiones SET en_tiempo=2 WHERE fecha='$today' AND estacion=$stationID AND proceso=".$_SESSION['processID']);

	if ($changestatus) {
		echo "estatus cambiado a no en tiempo";
	}else{
		printf($mysqli->error);
	}
}
elseif ($section=='intime') {

	$changestatus=$mysqli->query("UPDATE operacion_estatus SET en_tiempo=1 WHERE fecha='$today' AND maquina=$stationID ");

	if ($changestatus) {
		echo "estatus cambiado a en tiempo";
	}else{
		printf($mysqli->error);
	}
}
elseif ($section=='5s') {

	$changestatus=$mysqli->query("UPDATE sesiones SET lista_diaria='true' WHERE id_sesion=".$_SESSION['stat_session']);

	if ($changestatus) {
		echo "estatus cambiado a en tiempo";
	}else{
		printf($mysqli->error);
	}
}
?>
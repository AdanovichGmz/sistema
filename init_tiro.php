<?php
date_default_timezone_set("America/Mexico_City");
session_start();
require('saves/conexion.php');
$init=$_POST['init'];
if ($init=='init') {
	$hora=$_POST['hora'];
$fecha=$_POST['fecha'];
$userID = $_SESSION['id'];
$machineID = $_SESSION['machineID'];


$query     = "INSERT INTO tiraje (id_maquina,horadeldia_ajuste, fechadeldia_ajuste,id_user) VALUES ($machineID,'$hora','$fecha', $userID)";
            
            $resultado = $mysqli->query($query);
//echo "Tus datos fueron enviados correctamente <b>".$_POST['logged_in']."</b>";
 $lastTiraje=$mysqli->insert_id;
//print_r($_POST) ;
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

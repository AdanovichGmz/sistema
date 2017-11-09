<?php
session_start();
require('saves/conexion.php');
date_default_timezone_set("America/Mexico_City"); 
$radios=$_POST['radios'];

//foreach ($_POST['opcion'] as $opcion); 

$breaktime=$_POST['breaktime'];
$maquina=$_POST['maquina'];
$specific=(isset($_POST['specific']))? $_POST['specific']:'';
$logged_in=$_POST['logged_in'];
$horadeldiaam=$_POST['inicioAlertaEat'];
$fechadeldiaam=$_POST['fechadeldiaam'];

$userID = $_SESSION['id'];
$seccion=$_POST['curr-section'];
$machineID = $_SESSION['machineID'];
$tiraje=$_POST['act_tiro'];
$horafin=date(" H:i:s", time());
echo "hora fin: ".$horafin;
$query="INSERT INTO breaktime (radios,otra_actividad, breaktime, id_maquina, id_usuario,id_tiraje,seccion, horadeldiaam, hora_fin_comida, fechadeldiaam, vdate) VALUES ('$radios','$specific','$breaktime',$machineID,$userID,$tiraje,'$seccion', '$horadeldiaam','$horafin', '$fechadeldiaam',now())";


$resultado=$mysqli->query($query);
//echo "Tus datos fueron enviados correctamente <b>".$_POST['logged_in']."</b>";

//print_r($_POST) ;
if ( $resultado) {
print_r($_POST);
 }else{	print_r($_POST);
            printf("Errormessage: %s\n", $mysqli->error);
            echo $query;
          }


?>

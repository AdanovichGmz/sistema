<?php
session_start();
date_default_timezone_set("America/Mexico_City");  
$hora=date(" H:i:s", time());
$today=date("d-m-Y");
$ThatTime ="17:30:00";
require('saves/conexion.php');
$responsable=mysqli_fetch_assoc($mysqli->query("SELECT lista_diaria FROM sesiones WHERE id_sesion=".$_SESSION['stat_session']));

?>

<iframe <?=(time()>strtotime($ThatTime)&&$responsable['lista_diaria']=='false')? '' : 'style="display:none;"'?> src="http://192.168.1.202/5s/tablets.php?usuario=<?=$_SESSION['logged_in'] ?>" class="quiz-container">
  
</iframe>
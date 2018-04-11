<?php
session_start();
require('../saves/conexion.php');
$prod_real=(isset($_POST['prod_real']))? true : false;
$std_change=(isset($_POST['std_change']))? true : false;
$logged=$_SESSION['logged_in'];
$oper=(isset($_POST['operador']))? $_POST['operador'] : 'null';
$date_report=(isset($_POST['fecha']))? "'".$_POST['fecha']."'" : 'null';
$concept=(isset($_POST['concepto']))? "'".$_POST['concepto']."'" : 'null';
$time=date("H:i:s",time());
$date=date("d-m-Y");


if ($prod_real) {
	$entregados=$_POST["entregados"]+$_POST["merma"];
	$query="UPDATE tiraje set merma_entregada = '".$_POST["merma"]."', entregados= $entregados, buenos= $entregados,   WHERE  idtiraje=".$_POST["id"];
	$merma=$_POST["merma"];
	$result = $mysqli->query("UPDATE tiraje set merma_entregada =$merma, entregados= $entregados, buenos= $entregados   WHERE  idtiraje=".$_POST["id"]);
if ($result) {
	echo "<div class='successs'><div></div><span>Exito: </span>Datos guardados!</div>";
	$log=$mysqli->query("INSERT INTO `registro_modificacion` (`id_registro`, `usuario`, `fecha_registro`, `hora_registro`, `operador`, `fecha`, `concepto`, `informacion`) VALUES (NULL, '$logged', '$date', '$time', $oper, $date_report, 'produccion real', 'buenos: ".$_POST["entregados"]." merma : ".$_POST["merma"]."')");
	if (!$log) {
		printf($mysqli->error);
	}
}else{
	//printf($mysqli->error);
	//echo $query;
	echo "<div class='fail'><div></div><span>Error: </span>los datos no se guardaron</div>";
}

}else{

	$result = $mysqli->query("UPDATE tiraje set " . $_POST["column"] . " = '".$_POST["editval"]."' WHERE  idtiraje=".$_POST["id"]);
if ($result) {
	if ($std_change) {

	$calcstd=mysqli_fetch_assoc($mysqli->query("SELECT t.*,(SELECT proceso FROM sesiones WHERE id_sesion=t.id_sesion) AS proceso,TIME_TO_SEC(timediff(horafin_tiraje, horadeldia_tiraje)) AS dispon_tiro FROM tiraje t WHERE idtiraje=".$_POST["id"]));
	if($calcstd['is_virtual'] == 'true'){
		$processID=$calcstd["proceso"];
		$std=mysqli_fetch_assoc($mysqli->query("SELECT piezas_por_hora FROM estandares WHERE id_elemento=(SELECT id_elemento FROM elementos WHERE nombre_elemento= '".$calcstd["elemento_virtual"]."') AND id_maquina=".$processID));

		
		 if (!empty($std['piezas_por_hora'])) {
                    $tiraje_estandar=($calcstd['dispon_tiro']*$std['piezas_por_hora'])/3600; 
                  }
                  else{
                   if ($processID==10) {
                    $tiraje_estandar=($calcstd['dispon_tiro']*480)/3600;
                  }else{
                    $tiraje_estandar=($calcstd['dispon_tiro']*600)/3600;
                  } }

       $setStd=$mysqli->query("UPDATE tiraje SET produccion_esperada=".round($tiraje_estandar)." WHERE idtiraje=".$_POST["id"]);
       if ($setStd) {
       echo "<div class='successs'><div></div><span>Exito: </span>Datos guardados!</div>";
       $log=$mysqli->query("INSERT INTO `registro_modificacion` (`id_registro`, `usuario`, `fecha_registro`, `hora_registro`, `operador`, `fecha`, `concepto`, `informacion`) VALUES (NULL, '$logged', '$date', '$time', $oper, $date_report, '" . $_POST["column"] . "', '".$_POST["editval"]."')");
	if (!$log) {
		printf($mysqli->error);
	}
       }else{
       	echo "<div class='fail'><div></div><span>Error: </span>los datos no se guardaron</div>";
       }



		echo $std['piezas_por_hora'];
	}else{
		$el=(isset($calcstd["element"]))? $calcstd["element"] :0;
		$processID=$calcstd["proceso"];
		$std=mysqli_fetch_assoc($mysqli->query("SELECT piezas_por_hora FROM estandares WHERE id_elemento=(SELECT id_elemento FROM elementos WHERE nombre_elemento= '".$el."') AND id_proceso=".$processID));
		
		
	}
}else{
	echo "<div class='successs'><div></div><span>Exito: </span>Datos guardados!</div>";
	
	$log=$mysqli->query("INSERT INTO `registro_modificacion` (`id_registro`, `usuario`, `fecha_registro`, `hora_registro`, `operador`, `fecha`, `concepto`, `informacion`) VALUES (NULL, '$logged', '$date', '$time', $oper, $date_report, '" . $_POST["column"] . "', '".$_POST["editval"]."')");
	if (!$log) {
		printf($mysqli->error);
		
	}
}

	
}else{
	echo "<div class='fail'><div></div><span>Error: </span>los datos no se guardaron</div>";
	//printf($mysqli->error);
}

}


?>
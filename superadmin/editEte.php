<?php
require('../saves/conexion.php');
$prod_real=(isset($_POST['prod_real']))? true : false;
$std_change=(isset($_POST['std_change']))? true : false;




if ($prod_real) {
	$entregados=$_POST["entregados"]+$_POST["merma"];
	$query="UPDATE tiraje set merma_entregada = '".$_POST["merma"]."', entregados= $entregados, buenos= $entregados,   WHERE  idtiraje=".$_POST["id"];
	$merma=$_POST["merma"];
	$result = $mysqli->query("UPDATE tiraje set merma_entregada =$merma, entregados= $entregados, buenos= $entregados   WHERE  idtiraje=".$_POST["id"]);
if ($result) {
	echo "<div class='successs'>Datos guardados!</div>";
}else{
	printf($mysqli->error);
	echo $query;
}

}else{

	$result = $mysqli->query("UPDATE tiraje set " . $_POST["column"] . " = '".$_POST["editval"]."' WHERE  idtiraje=".$_POST["id"]);
if ($result) {
	if ($std_change) {

	$calcstd=mysqli_fetch_assoc($mysqli->query("SELECT *,TIME_TO_SEC(timediff(horafin_tiraje, horadeldia_tiraje)) AS dispon_tiro FROM tiraje WHERE idtiraje=".$_POST["id"]));
	if($calcstd['is_virtual'] == 'true'){
		$processID=($calcstd["id_maquina"]==20||$calcstd["id_maquina"]==21)? 10:(($calcstd["id_maquina"]==22)? 9 : $calcstd["id_maquina"] );
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
       echo "<div class='successs'>Datos guardados!</div>";
       }else{
       	echo "<div class='successs'>Ocurrio un error</div>";
       }



		echo $std['piezas_por_hora'];
	}else{
		$el=(isset($calcstd["element"]))? $calcstd["element"] :0;
		$processID=($calcstd["id_maquina"]==20||$calcstd["id_maquina"]==21)? 10:(($calcstd["id_maquina"]==22)? 9 : $calcstd["id_maquina"] );
		$std=mysqli_fetch_assoc($mysqli->query("SELECT piezas_por_hora FROM estandares WHERE id_elemento=(SELECT id_elemento FROM elementos WHERE nombre_elemento= '".$el."') AND id_maquina=".$processID));
		echo $std['piezas_por_hora'];
	}
}else{
	echo "<div class='successs'>Datos guardados!</div>";
}

	
}else{
	printf($mysqli->error);
}

}


?>
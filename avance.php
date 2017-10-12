<?php
require('saves/conexion.php');
if (isset($_POST['maquina'])) {
$machineID=$_POST['maquina'];
$element=$_POST['elemento'];

             $processID=($machineID==20||$machineID==21)? 10:$machineID;
$tiempoTiraje=(isset($_POST['tiempo']))? $_POST['tiempo'] :'00:00:00';
$seconds = strtotime("1970-01-01 $tiempoTiraje UTC");
 $standar_query2 = "SELECT * FROM estandares WHERE id_maquina=$processID AND id_elemento= $element";
            
            $getstandar     = mysqli_fetch_assoc($mysqli->query($standar_query2));
            $estandar       = $getstandar['piezas_por_hora'];
            //calculando desempeño para pieza actual
            $tiraje_estandar=($seconds*$estandar)/3600;
            
             $prodEsperada=($tiraje_estandar>0)? round($tiraje_estandar):'<label style="color:red; font-size:11px;">Indefinido</label>';
 ?>

 <span>Produccion Esperada: <?=$prodEsperada?></span>
 <?php 
}else{
 ?>
<span>Produccion Esperada: 0</span>
 <?php
}
?>


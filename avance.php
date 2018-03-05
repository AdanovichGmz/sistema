<?php
require('saves/conexion.php');
if (isset($_POST['proceso'])) {

$element=$_POST['elemento'];
$table_mac=(isset($_POST['tabm']))? $_POST['tabm'] : 1;


$processID=$_POST['proceso'];

$tiempoTiraje=(isset($_POST['tiempo']))? $_POST['tiempo'] :'00:00:00';
$seconds = strtotime("1970-01-01 $tiempoTiraje UTC");
if ($table_mac==2) {
  $def_estandar=300;
} else{
  if ($element!='') {
	 

 $standar_query2 = "SELECT * FROM estandares WHERE id_proceso=$processID AND id_elemento= $element";
            
            $getstandar     = mysqli_fetch_assoc($mysqli->query($standar_query2));
            $get_estandar       = $getstandar['piezas_por_hora'];
           
            if (is_null($get_estandar)) {
            	if ($processID==10) {
                    $def_estandar=420;
                  }else{
                    $def_estandar=600;
                    
                  }
            }else{
            	$def_estandar=$get_estandar;
            }
}else{
	if ($processID==10) {
                    $def_estandar=420;
                  }else{
                    $def_estandar=600;

                  }
}
}

            
           
            //calculando desempeño para pieza actual
            $tiraje_estandar=($seconds*$def_estandar)/3600;
            
             $prodEsperada=($tiraje_estandar>0)? round($tiraje_estandar):'<label style="color:red; font-size:11px;">Indefinido</label>';
 ?>

 <div class="live-indicator">Produccion Esperada: <?=$prodEsperada?></div>
 <?php 
}else{
 ?>
<div class="live-indicator">Produccion Esperada: 0</div>
 <?php
}
?>


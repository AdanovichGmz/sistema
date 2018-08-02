<?php 

  require('saves/conexion.php');


 $getStations=$mysqli->query("SELECT * FROM opciones WHERE tipo_opcion='alerta_ajuste' AND id_proceso=".$_SESSION['processID']); ?>
<div class="">

 <?php
 while ($option=mysqli_fetch_assoc($getStations)) {
  

?>

<div class=" radio-menu face no-explain">
                    <input type="radio" name="radios" id="radios-<?=$option['id_opcion'] ?>" value="<?=$option['valor'] ?>">
                    <?=$option['valor'] ?>
</div>
               
               
<?php  
 } 
 ?>
</div>
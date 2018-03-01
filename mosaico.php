
  <?php 

 require('saves/conexion.php');
 
$processID = $_POST['processID'];

  
$getElementStandar=$mysqli->query("SELECT * FROM estandares e INNER JOIN elementos el ON e.id_elemento=el.id_elemento WHERE e.id_proceso=$processID ORDER BY nombre_elemento ASC");
  $c=0;
  while ($e_row=mysqli_fetch_assoc($getElementStandar)) { ?>
    <div class="elem-button <?='c'.$c ?>" data-name="<?=$e_row['nombre_elemento'] ?>" data-id="<?=$e_row['id_elemento'] ?>"><p><?=$e_row['nombre_elemento'] ?></p></div>
  <?php $c++; } ?>
  <div style="display: none;" class="elem-button other"><p>Otro</p></div>
<?php
 
?>
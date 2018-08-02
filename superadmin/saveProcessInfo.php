 <?php
require('../saves/conexion.php');


$section=$_POST['section'];


if ($section=='info') {
  $nombre=(!empty($_POST['name']))? "'".$_POST['name']."'":'NULL';
$precio_tiros=(!empty($_POST['precio_tiros']))? $_POST['precio_tiros']:'NULL';
$precio_cambio=(!empty($_POST['precio_cambio']))? $_POST['precio_cambio']:'NULL';
$ajuste=(!empty($_POST['ajuste']))? $_POST['ajuste']*60:'0';

$piezas=(!empty($_POST['piezas']))? $_POST['piezas']:'0';
$proceso=(!empty($_POST['proceso']))? $_POST['proceso']:'NULL';
  $fails=0;
  $update=$mysqli->query("UPDATE procesos_catalogo SET nombre_proceso=$nombre, precio=$precio_tiros, precio_cambio=$precio_cambio WHERE id_proceso=".$proceso);
$mysqli->query("DELETE FROM estandares WHERE id_elemento=144 AND id_proceso=".$proceso);
if (!$update) { $fails++; }
  $default=$mysqli->query("INSERT INTO `estandares` (`id_estandard`, `ajuste_standard`, `piezas_por_hora`, `id_elemento`, `id_proceso`) VALUES (NULL, $ajuste, $piezas, 144, $proceso)");
  if (!$default) { $fails++; }

  if ($fails>0) {
  $datas='data-clas="fail" data-type="Error:" data-message="No pudimos guardar algunos datos" ';
}else{
  $datas='data-clas="successs" data-type="Exito:" data-message="Datos guardados correctamente!" ';
}
?>
<input id="tipo" type="hidden" name="tipo_opcion" value="" <?=$datas; ?> >


<?php

}elseif ($section=='alerts') { 
$processes=$_POST['processes'];
$tipo_opcion=$_POST['type_alert'];
$errors=0;

foreach ($processes as $key => $process) {
  $mysqli->query("DELETE FROM opciones WHERE tipo_opcion='$tipo_opcion' AND id_proceso=".$process);
  if (isset($_POST['options-'.$process])) {
   
  $options=$_POST['options-'.$process];
  foreach ($options as $key2 => $option) {

   $query="INSERT INTO `opciones`(`id_opcion`, `valor`, `tipo_opcion`, `id_proceso`) VALUES (NULL, '$option', '$tipo_opcion', $process)";
   $inserted=$mysqli->query($query);
   if (!$inserted) {
    $errors++;
     //printf($mysqli->error);
   }

  }
}
  
}
if ($errors>0) {
  $datas='data-clas="fail" data-type="Error:" data-message="No pudimos guardar algunos datos" data-error="'.printf($mysqli->error).' " ';

}else{
  $datas='data-clas="successs" data-type="Exito:" data-message="Datos guardados correctamente!" ';
}


$procesos=$mysqli->query("SELECT * FROM procesos_catalogo ORDER BY nombre_proceso ASC");

  ?>
 <input id="tipo" type="hidden" name="tipo_opcion" value="" <?=$datas; ?> >



<?php 
}


?>







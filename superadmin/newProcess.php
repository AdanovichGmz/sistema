<?php 

require('../saves/conexion.php');
print_r($_POST);

$costo_tiros=$_POST['costo_tiros'];
$costo_cambios=$_POST['costo_cambios'];
$cambios_minimos=$_POST['cambios_minimos'];
$tiempo_ajuste=$_POST['tiempo_ajuste']*60;
$piezas_hora=$_POST['piezas_hora'];
$nombre=$_POST['nombre'];
$area=$_POST['area'];

$query="INSERT INTO `procesos_catalogo` (`id_proceso`, `nombre_proceso`, `precio`, `precio_tiros_largos`, `precio_cambio`, `cambios_minimos`, `area`) VALUES (NULL, '$nombre', $costo_tiros, NULL, $costo_cambios, $cambios_minimos, '$area')";

$inserted=$mysqli->query($query);

if ($inserted) {
  echo "todo bien";
}else{
  echo "algo salio mal";

  printf($mysqli->error);
}


?>
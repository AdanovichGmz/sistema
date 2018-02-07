<?php 
 require('../saves/conexion.php');
$getProcess=$mysqli->query("SELECT * FROM procesos WHERE avance NOT IN('completado')");

while ($row=mysqli_fetch_assoc($getProcess)) {
 $fechaProg=$row['fecha_fin'];
 $dStart = new DateTime($fechaProg);
   $dEnd  = new DateTime(date("Y-m-d"));
   $dDiff = $dStart->diff($dEnd);
   if ($dDiff->days<=0) {
     //echo "ODT: ".$row['numodt'].' '.$row['nombre_proceso'].' NO SE HA REALIZADO'.'<br>';
     $estatus=3;
   }elseif ($dDiff->days==1) {
    //echo "ODT: ".$row['numodt'].' '.$row['nombre_proceso'].' TARDE'.'<br>';
    $estatus=2;
   }else{
    //echo "ODT: ".$row['numodt'].' '.$row['nombre_proceso'].' EN TIEMPO'.'<br>';
    $estatus=4;
   }

   $update=$mysqli->query("UPDATE procesos SET estatus=$estatus WHERE id_proceso=".$row['id_proceso']);
   if (!$update) {
    printf($mysqli->error);
   }
 
}



?>
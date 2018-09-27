 <?php  
require('../saves/conexion.php');
  
$id=$_POST['id'];

$query="DELETE FROM procesos_catalogo WHERE id_proceso=".$id;

$deleted=$mysqli->query($query);

if ($deleted) {
  $query2="DELETE FROM estaciones_procesos WHERE id_proceso=".$id;
  $deleted2=$mysqli->query($query2);
  if ($deleted2) {
    $query3="DELETE FROM actividades_procesos WHERE id_proceso=".$id;
    $deleted3=$mysqli->query($query3);
    if ($deleted3) {
     echo "eliminado correctamente";
    }
    
  }
}else{
  echo $query;
  printf($mysqli->error);
  echo "Uh Oh! algo salio mal";
}
 
 
 
 ?>
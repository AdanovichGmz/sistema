 <?php
 
require('../saves/conexion.php');

$query=$mysqli->query("SELECT * FROM usuarios_estaciones WHERE id_usuario=".$_POST['oper']);
$procesos='';
while ($row=mysqli_fetch_assoc($query)) {
 $query2=$mysqli->query("SELECT es.*,(SELECT nombre_proceso FROM procesos_catalogo WHERE id_proceso=es.id_proceso) AS nombre FROM estaciones_procesos es WHERE id_estacion=".$row['id_estacion']);
 while ($row2=mysqli_fetch_assoc($query2)) {
   $procesos.='<p><input type="radio" name="proceso" id="radio-'.$row2['id'].'" value="'.$row2['id_proceso'].'"><label for="radio-'.$row2['id'].'" >'.$row2['nombre'].'</label> <input type="hidden" name="estacion" value="'.$row['id_estacion'].'" >';
 }

}
        
        echo $procesos;
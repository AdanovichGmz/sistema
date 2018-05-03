 <?php
require('../saves/conexion.php');
$target=$_POST['target'];
print_r($_POST);
 print_r($_FILES);
if ($target=='info') {
 $nombre=(isset($_POST['nombre']))? "'".$_POST['nombre']."'" : 'null';
$usuario=(isset($_POST['usuario']))? "'".$_POST['usuario']."'" : 'null';
$password=(isset($_POST['password']))? "'".$_POST['password']."'" : 'null';
$sueldo=(isset($_POST['sueldo']))? "'".$_POST['sueldo']."'" : 'null';
$apellido=(isset($_POST['apellido']))? "'".$_POST['apellido']."'" : 'null';
$mysqli->query("UPDATE usuarios SET logged_in=$nombre, usuario=$usuario, sueldo=$sueldo,apellido=$apellido, password=$password WHERE id=".$_POST['iduser']);
}elseif ($target=='photo') {
// unlink($Your_file_path);
  print_r($_POST);
  print_r($_FILES);
}elseif ($target=='procesos') {
	
	foreach ($_POST['estaciones'] as $key => $station) {
		$processes=$_POST['procesos-'.$station];
		$mysqli->query("DELETE FROM estaciones_procesos WHERE id_estacion=".$station);
		foreach ($processes as $key => $process) {
			$resultado=$mysqli->query("INSERT INTO estaciones_procesos(id_estacion,id_proceso) VALUES($station,$process)");
			if (!$resultado) {
				printf($mysqli->error);
			}
		}
	}
}









?>







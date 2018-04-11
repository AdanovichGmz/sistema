 <?php
require('../saves/conexion.php');
$target=$_POST['target'];

if ($target=='info') {
 $nombre=(isset($_POST['nombre']))? "'".$_POST['nombre']."'" : 'null';
$usuario=(isset($_POST['usuario']))? "'".$_POST['usuario']."'" : 'null';
$password=(isset($_POST['password']))? "'".$_POST['password']."'" : 'null';

$mysqli->query("UPDATE login SET logged_in=$nombre, usuario=$usuario, password=$password WHERE id=".$_POST['iduser']);
}elseif ($target=='photo') {
// unlink($Your_file_path);
  print_r($_POST);
  print_r($_FILES);
}









?>







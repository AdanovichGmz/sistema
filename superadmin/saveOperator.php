 <?php  
require('../saves/conexion.php');
session_start();

    $nombre=(isset($_POST['nombre']))? "'".$_POST['nombre']."'":'NULL'; 
    $apellido=(isset($_POST['apellido']))? "'".$_POST['apellido']."'":'NULL'; 
    $usuario=(isset($_POST['usuario']))? "'".$_POST['usuario']."'":'NULL'; 
    $password=(isset($_POST['password']))? "'".$_POST['password']."'":'NULL'; 
    $sueldo=(isset($_POST['sueldo']))? "'".$_POST['sueldo']."'":'NULL'; 
    $remuneracion=(isset($_POST['remuneracion']))? "'".$_POST['remuneracion']."'":'NULL';
    $miembro=(isset($_POST['miembro']))? "'".$_POST['miembro']."'":'NULL';
    $procesos=isset($_POST['procesos'])?$_POST['procesos']:array();
    
    $target_dir = "images/";

    echo "<pre>";
    print_r($_POST);
    echo "</pre>";
  /*if (empty($_FILES["up-file"]["name"])) {
  $_SESSION['messages']="<div class='fail'><div></div><span>Error: </span></div>";
  $_SESSION['notification']='warning';
          $_SESSION['result']='ERROR:';
    header("Location: operators.php");
} else{ */

$file=$_FILES["foto"]["name"];    
$upfile=(isset($file))? "'".str_replace(" ","_",$file)."'":null;
$target_file = "../".$target_dir . basename(str_replace(" ","_",$file));
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

if (!empty($file)) {
  if($imageFileType != "jpg"&&$imageFileType != "png"&&$imageFileType != "jpeg"&&$imageFileType != "pdf") {
   
    $_SESSION['messages'].="<div class='fail'><div></div><span>Error: </span> El archivo ".$file." no es valido</div>";


 
    $uploadOk = 0;
}
 if (file_exists($target_file)) {
   
       $uploadOk = 0; 
       $_SESSION['messages'].="<div class='fail'><div></div><span>Error: </span> El archivo ".$file." ya existe</div>";   
}

$filename=str_replace(" ","_",$file);

if ($uploadOk == 0) {
    $_SESSION['notification']='fail';
          $_SESSION['result']='ERROR:';
          header("Location: operators.php");
          
    

} else {
    if (move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file)) {

      $foto="images/".$filename;

        $query="INSERT INTO `usuarios` (`id`, `logged_in`, `password`, `usuario`, `rol`, `foto`, `app_active`, `sueldo`, `apellido`, `team_member`, `remuneracion`) VALUES (NULL, $nombre, $password, $usuario, '2', '$foto', 'true', $sueldo, $apellido, $miembro, $remuneracion)";


        //$query="INSERT INTO usuarios (odt, cliente, archivo,Tienda,user) VALUES ('$odt','$comments','$filename','$store','$user')";
        $inserted=$mysqli->query($query);
        
        if ($inserted){
         
          $new_user=$mysqli->insert_id;

          $query2="INSERT INTO estaciones(id_estacion,nombre_estacion)VALUES(NULL,$usuario)";

          $insert_station=$mysqli->query($query2);
          if ($insert_station) {
          $new_station=$mysqli->insert_id;
          $query3="INSERT INTO usuarios_estaciones(id, id_usuario,id_estacion)VALUES(NULL,$new_user,$new_station)";
          $u_e_inserted=$mysqli->query($query3);

          if ($u_e_inserted) {

            foreach ($procesos as $proceso) {
              $query_process="INSERT INTO estaciones_procesos(id,id_estacion,id_proceso) VALUES(NULL,$new_station,$proceso)";
              $mysqli->query($query_process);
            }
           
          $_SESSION['messages'].="<div class='successs'><div></div><span>Listo: </span> Datos guardados correctamente</div>";

          $_SESSION['notification']='success';
          $_SESSION['result']='LISTO:';
          header("Location: operators.php");
          }else{

          $_SESSION['messages'].="<div class='fail'><div></div><span>Error: </span> Los datos no se guardaron</div>";

          $_SESSION['notification']='success';
          $_SESSION['result']='LISTO:';
          header("Location: operators.php");
          }

          }else{
            echo $query2;
            printf($mysqli->error);
          }


        }else{
          printf($mysqli->error);
        }
    } else {
      echo "<pre>";
      print_r($_FILES);
      echo "</pre>";
        echo $target_file;
        echo "Ocurrio un error a la hora de subir la foto.";
    }
}
}else{


        $query="INSERT INTO `usuarios` (`id`, `logged_in`, `password`, `usuario`, `rol`, `foto`, `app_active`, `sueldo`, `apellido`, `team_member`, `remuneracion`) VALUES (NULL, $nombre, $password, $usuario, '2', NULL, 'true', $sueldo, $apellido, $miembro, $remuneracion)";


        //$query="INSERT INTO usuarios (odt, cliente, archivo,Tienda,user) VALUES ('$odt','$comments','$filename','$store','$user')";
        $inserted=$mysqli->query($query);
        
        if ($inserted){
         
          $new_user=$mysqli->insert_id;

          $query2="INSERT INTO estaciones(id_estacion,nombre_estacion)VALUES(NULL,$usuario)";

          $insert_station=$mysqli->query($query2);
          if ($insert_station) {
          $new_station=$mysqli->insert_id;
          $query3="INSERT INTO usuarios_estaciones(id, id_usuario,id_estacion)VALUES(NULL,$new_user,$new_station)";
          $u_e_inserted=$mysqli->query($query3);

          if ($u_e_inserted) {

            foreach ($procesos as $proceso) {
              $query_process="INSERT INTO estaciones_procesos(id,id_estacion,id_proceso) VALUES(NULL,$new_station,$proceso)";
              $mysqli->query($query_process);
            }
           
          $_SESSION['messages'].="<div class='successs'><div></div><span>Listo: </span> Datos guardados correctamente</div>";

          $_SESSION['notification']='success';
          $_SESSION['result']='LISTO:';
          header("Location: operators.php");
          }else{

          $_SESSION['messages'].="<div class='fail'><div></div><span>Error: </span> Los datos no se guardaron</div>";

          $_SESSION['notification']='success';
          $_SESSION['result']='LISTO:';
          header("Location: operators.php");
          }

          }else{
            echo $query2;
            printf($mysqli->error);
          }


        }else{
          printf($mysqli->error);
        }
}





//}
 
 ?>
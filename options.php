<?php 

  require('saves/conexion.php');
session_start();
if(@$_SESSION['logged_in'] != true){
      echo '
     <script>
        alert("No has iniciado sesion");
        self.location.replace("index.php");
    </script>';
    }else{

 $getStations=$mysqli->query("SELECT ue.*,e.nombre_estacion FROM usuarios_estaciones ue INNER JOIN estaciones e ON ue.id_estacion=e.id_estacion WHERE id_usuario=".$_SESSION['idUser']);
 

?><!DOCTYPE html>
<html>
<head>
    <title></title>
   <script src="./jquery-1.11.2.min.js"></script>
   <style>
       body{
        padding: 0;
        margin: 0;
        background:rgba(44,151,222, 0.90);
       font-family: "Helvetica";
       }
       p{
        color: #fff;
       }

       .options{
         position: absolute;
    width: 100%;
     top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
text-align: center;
font-size: 25px;
       }
      .option,.process{
        width: 200px;
        height: 200px;
        text-align: center;
        color: #fff;
        position: relative;
        border-radius: 4px;
        margin: 20px;
        
        display: inline-block;
        font-size: 25px;
        border:solid 1px #fff;
        vertical-align: top;
      } 
      .option span,.process span{
        position: absolute;
top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
      }
       .process:hover{
        background: #1B4DA5;
      }
   </style>
</head>
<body>
<div class="options">
<p>¿Que desea realizar?</p>
<?php 
if ($getStations->num_rows>1) {

while ($row = mysqli_fetch_assoc($getStations)) {
 ?>

    <div data-option="<?=$row['id_estacion'] ?>" class="option"><?=$row['nombre_estacion'] ?></div>
  
   <?php } } else{ 

$station=mysqli_fetch_assoc($getStations);
    $getProcess=$mysqli->query("SELECT ep.*,p.nombre_proceso FROM estaciones_procesos ep INNER JOIN procesos_catalogo p ON ep.id_proceso=p.id_proceso WHERE ep.id_estacion=".$station['id_estacion']);
while ($row = mysqli_fetch_assoc($getProcess)) {
    ?>
<div data-option="<?=$row['id_proceso'] ?>" data-sname="<?=$station['nombre_estacion'] ?>" data-pname="<?=$row['nombre_proceso'] ?>" data-station="<?=$row['id_estacion'] ?>" class="process"><span><?=$row['nombre_proceso'] ?></span></div>

    <?php } } ?>
</div>
</body>
</html>
<script>
    $( ".option").click(function() {
                                  
        var option=$(this).data('option')     
                                                      
          $.ajax({
                                    url: "setMachine.php",
                                    type: "POST",
                                    data:{option:option,choose:'station'},
                                    success: function(data){
                                     window.location.replace("index2.php");


                                    }        
          });                                      
                                             
    });
    $( ".process").click(function() {
                                  
        var option=$(this).data('option');     
         var station=$(this).data('station'); 
         var station_name=$(this).data('sname'); 
         var pro_name=$(this).data('pname');
         console.log('picadillo');                                              
          $.ajax({
                                    url: "setMachine.php",
                                    type: "POST",
                                    data:{option:option,choose:'process',station:station,station_name:station_name,pro_name:pro_name},
                                    dataType:"json",
                                    success: function(data){
                                      console.log('no hay nada2');
                                      if (data.proceed=='true') {
                                        window.location.replace(data.page);

                                      }else if(data.proceed=='false'){
                                        console.log(data.error);
                                      }else{
                                        console.log('no hay nada');
                                        console.log(data);
                                        
                                      }
                                     


                                    }        
                                   });                                      
                                             
    });
</script>
<?php
}
?>
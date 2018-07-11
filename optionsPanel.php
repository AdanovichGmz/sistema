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

 $getPendings=$mysqli->query("SELECT * FROM en_cola WHERE sesion=".$_SESSION['stat_session']);
 

function getPendingsByProcess($process){
  require('saves/conexion.php');
  $pendings=$mysqli->query("SELECT * FROM en_cola WHERE sesion=".$_SESSION['stat_session']." AND proceso=".$process);
  return $pendings->num_rows;

}


 $num_pendings=$getPendings->num_rows;
while ($row1=mysqli_fetch_assoc($getPendings)) {
  $pendings[$row1['proceso']]=$row1;
}
if ($getPendings->num_rows==0) {
  $pendings[]='';
}

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
       .options{
         position: absolute;
    width: 100%;
     top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
text-align: center;
font-size: 25px;
       }
       p{
        color: #fff
       }
      .option,.process{
        width: 200px;
        height: 200px;
        text-align: center;
        color: #fff;
       
        border-radius: 4px;
        margin: 20px;
        
        display: inline-block;
        font-size: 25px;
        position: relative;
        font-family: "Helvetica";
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
      .pending-indicator{
        position: absolute;
        top: 10px;
        right: -10px;
        line-height: 12px;
        padding: 10px;
        background: red;
        color: #fff;
        font-size: 15px;
        border-radius: 4px;
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
<div data-option="<?=$row['id_proceso'] ?>" data-sname="<?=$station['nombre_estacion'] ?>" data-pname="<?=$row['nombre_proceso'] ?>" data-station="<?=$row['id_estacion'] ?>" <?=(array_key_exists($row['id_proceso'], $pendings))?'data-cola="'.$pendings[$row['id_proceso']]['id_cola'].'"':'' ?> class="process <?=(array_key_exists($row['id_proceso'], $pendings))?'pending':'normal' ?>"><span><?=$row['nombre_proceso'] ?></span>
  <?php if(array_key_exists($row['id_proceso'], $pendings)){ ?>
  <div class="pending-indicator"><?=getPendingsByProcess($row['id_proceso']); ?> Cambio Pendiente</div>
   <?php  }?>
</div>

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
    $( ".normal").click(function() {
                                  
        var option=$(this).data('option');     
         var station=$(this).data('station'); 
         var station_name=$(this).data('sname'); 
         var pro_name=$(this).data('pname');
         
                                                             
          $.ajax({
                                    url: "setLiveMachine.php",
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
    $( ".pending").click(function() {
                                  
        var option=$(this).data('option');     
         var station=$(this).data('station'); 
         var station_name=$(this).data('sname'); 
         var pro_name=$(this).data('pname');
         var cola=$(this).data('cola');
                                                             
          $.ajax({
                                    url: "setLiveMachine.php",
                                    type: "POST",
                                    data:{option:option,choose:'pending',station:station,station_name:station_name,pro_name:pro_name,cola:cola},
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
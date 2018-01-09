 <?php
date_default_timezone_set("America/Mexico_City");
if( !session_id() )
{
    session_start();
    

}
if(@$_SESSION['logged_in'] != true){
    echo '
     <script>
        alert("No has iniciado sesion");
        self.location.replace("../index.php");
    </script>';
}else{
    echo '';
}

    ?>

  <?php
  function personalData($idmaquina,$maquina,$photo){
require('../saves/conexion.php');
$process=($maquina=='Serigrafia2'||$maquina=='Serigrafia3')?'Serigrafia':$maquina;
             $processID=($idmaquina==20||$idmaquina==21)? 10:$idmaquina;
    $query1 = "SELECT o.*,p.proceso,p.id_proceso,pp.*,(SELECT nombre_elemento FROM elementos WHERE id_elemento=o.producto) AS nombre_elemento FROM ordenes o INNER JOIN procesos p ON p.id_orden=o.idorden INNER JOIN personal_process pp ON pp.id_orden=o.idorden WHERE proceso_actual='$maquina' AND nombre_proceso='$process' AND status='actual' ";
    
    $actual = $mysqli->query($query1);
     
    $query2 = "SELECT o.*,p.proceso,p.id_proceso,pp.*,(SELECT nombre_elemento FROM elementos WHERE id_elemento=o.producto) AS nombre_elemento FROM ordenes o INNER JOIN procesos p ON p.id_orden=o.idorden INNER JOIN personal_process pp ON pp.id_orden=o.idorden WHERE proceso_actual='$maquina' AND nombre_proceso='$process' AND status='siguiente'";
    
    $siguiente = $mysqli->query($query2);
    $resultado02_5 = $mysqli->query($query2);
    $query3 = "SELECT o.*,p.proceso,p.id_proceso,pp.*,(SELECT nombre_elemento FROM elementos WHERE id_elemento=o.producto) AS nombre_elemento FROM ordenes o INNER JOIN procesos p ON p.id_orden=o.idorden INNER JOIN personal_process pp ON pp.id_orden=o.idorden WHERE proceso_actual='$maquina' AND nombre_proceso='$process' AND status='preparacion' ";
    
    $preparacion = $mysqli->query($query3);

     $row = mysqli_fetch_object($actual);
     $row2 =mysqli_fetch_assoc($actual);
     
      $act = (!empty($row->numodt))? $row->numodt : '--';
      $nom_act=(!empty($row->producto))? $row->nombre_elemento : '';
      $element   = $idmaquina;
    
      
      $row2 = mysqli_fetch_object($siguiente);
      $sig = (!empty($row2->numodt))? $row2->numodt : '--';
      $nom_sig=(!empty($row2->producto))? $row2->nombre_elemento : '';
            
          
      $row3 = mysqli_fetch_object($preparacion);
      $prep = (!empty($row3->numodt))? $row3->numodt : '--';
      $nom_prep=(!empty($row3->producto))? $row3->nombre_elemento : '';

       /****************** Calcular ETE ******************/
    $today     = date("d-m-Y");
   
     $etequery1 = "SELECT COALESCE((SELECT  IFNULL(SUM( TIME_TO_SEC( tiempoTiraje) ),0)  FROM tiraje WHERE id_maquina=$idmaquina AND fechadeldia_tiraje = '$today' AND tiempoTiraje IS NOT NULL)+(SELECT  IFNULL(SUM( TIME_TO_SEC( tiempo_ajuste)),0) FROM tiraje WHERE id_maquina=$idmaquina AND fechadeldia_ajuste = '$today' AND tiempoTiraje IS NOT NULL)+(SELECT  IFNULL(SUM( TIME_TO_SEC( tiempoalertamaquina) ),0)  FROM alertamaquinaoperacion WHERE id_maquina=$idmaquina AND fechadeldiaam = '$today') + (SELECT  IFNULL(SUM( TIME_TO_SEC(tiempoalertamaquina) ),0) FROM alertageneralajuste WHERE id_maquina=$idmaquina AND fechadeldiaam = '$today')) as tiempo_real";
    //obtenemos el tiempo muerto sumando las idas al sanitario
    $etequery2 = "SELECT  IFNULL(SUM( TIME_TO_SEC( breaktime)),0)+(SELECT IFNULL(SUM(TIME_TO_SEC(tiempo_muerto)),0) FROM tiempo_muerto WHERE id_maquina=$idmaquina AND fecha = '$today') AS tiempo_muerto  FROM breaktime WHERE id_maquina=$idmaquina AND radios='Sanitario' AND fechadeldiaam = '$today'";
    
    //obtenemos la calidad a la primera operando entregados-defectos*100/cantidadpedida  
    $etequery3 = "SELECT COALESCE((SELECT SUM( entregados ) FROM tiraje WHERE id_maquina=$idmaquina AND fechadeldia_tiraje = '$today')/ (SELECT SUM(cantidad) FROM tiraje WHERE id_maquina=$idmaquina AND fechadeldia_tiraje = '$today' AND tiempoTiraje IS NOT NULL))*100 as calidad_primera";
    //obtenemos desempeño operando entregados+merma
    $etequery4 = "SELECT SUM(desempenio) AS desemp ,COUNT(desempenio) AS tirajes,SUM(produccion_esperada) AS esper FROM `tiraje` WHERE fechadeldia_tiraje='$today' AND id_maquina=$idmaquina AND tiempoTiraje IS NOT NULL";
    $etequery5 = "SELECT COALESCE((SELECT SUM(entregados) FROM tiraje WHERE id_maquina=$idmaquina AND fechadeldia_tiraje = '$today' AND tiempoTiraje IS NOT NULL)) as desempenio";
    //obtenemos el elemento o producto
    $getelement = mysqli_fetch_assoc($resultado02_5);
    $element    = $getelement['producto'];
    $begin      = new DateTime('09:00');
    $current    = new DateTime(date('H:i'));
    //obtenemos el tiempo transcurrido desde el inicio del dia hasta el momento actual
    $interval   = $begin->diff($current);
    //echo $interval->format("%H:%I");
    $time_diff  = $interval->format("%H:%I:%S");
    $timeArr    = array_reverse(explode(":", $time_diff));
    $seconds    = 0;
    foreach ($timeArr as $key => $value) {
        if ($key > 2)
            break;
        $seconds += pow(60, $key) * $value;
    }
    //obtenemos el estandar de piezas por hora para el elemento y proceso actual
   
  
    
    $getdeadTime = mysqli_fetch_assoc($mysqli->query($etequery2));
    $deadTime    = $getdeadTime['tiempo_muerto'];
    
    $gettotalTime = mysqli_fetch_assoc($mysqli->query($etequery1));
    $totalTime    = $gettotalTime['tiempo_real'] - $getdeadTime['tiempo_muerto'];
    
    $getQuality = mysqli_fetch_assoc($mysqli->query($etequery3));
    $Quality    = $getQuality['calidad_primera'];
    $getdesemp= $mysqli->query($etequery4);
    $getEfec       = mysqli_fetch_assoc($getdesemp);
    //obtenemos el porcentaje de estandar segundos*estandar/1hora
   
    
    $desempenio =($getEfec['tirajes']>0)? ($getEfec['desemp']*100)/($getEfec['tirajes']*100) : 0;
    //echo $etequery3;
    //$realtime   = ($totalTime * 1) / 3600;
    
    $dispon     =($seconds>14400)? ($totalTime * 100) / ($seconds-3600) : ($totalTime * 100) / $seconds;
    //$disponible = round($dispon, 1);
    $disponible = round($dispon);
    
    $real       = mysqli_fetch_assoc($mysqli->query($etequery5));
    $esperada       = mysqli_fetch_assoc($mysqli->query("SELECT COALESCE((SELECT SUM(produccion_esperada) FROM tiraje WHERE id_maquina=$idmaquina AND fechadeldia_tiraje = '$today')) as p_esperada"));
    $merma       = mysqli_fetch_assoc($mysqli->query("SELECT COALESCE((SELECT SUM(merma_entregada) FROM tiraje WHERE id_maquina=$idmaquina AND fechadeldia_tiraje = '$today')) as merma"));


    //echo "<p style='color:#fff;'>dispon ".$dispon." calidad ".$Quality." desempeño ".$desempenio." prod esperada ".$getEfec['esper']." real ".$real['desempenio']." calidad ".$Quality." tiempo hasta ahora: ".$seconds."</p>";
    $getEte     = (($dispon / 100) * ($Quality / 100) * ($desempenio / 100)) * 100;
    $showpercent=100 - $getEte;
  /****************** Calcular ETE ******************/
          

    
    $credencial='<div class="ete-photo"><div class="person-photo" style=background:url("../images/'.$photo.'.jpg")></div>
    <div class="ete-num">'.$maquina.'&nbsp</div>
    </div>
    <div class="ete-stat">
      <table>
      <thead>
      <tr class="trh">
        <td>Fecha: </td>
        <td class=""><input id="'.$idmaquina.'" required="true" value="" name="id" /></td>
        
        </tr></thead>
        <tbody>
        <tr style="font-size: 10px;">
        <td>&nbsp</td>
        <td ><input type="submit" class="genpdf btn btn-primary" value="Generar Reporte PDF"> </td>
        </tr>
        
        </tbody>
      </table>
    </div>';
  echo $credencial;
}


  ?>

 

  
<!DOCTYPE html>

<html lang="en" dir="ltr" xmlns:fb="http://ogp.me/ns/fb#">
<head>

    
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <title>Reporte Ordenes</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous" />
    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous" />
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
  


    <link href="../css/estilosadmin.css" rel="stylesheet" />

   
  <link rel="stylesheet" href="../fonts/style.css">
  <script src="http://code.jquery.com/jquery-latest.js"></script>
  <script src="../js/main.js"></script>


  
  <link rel="stylesheet" type="text/css" href="../css/jquery-ui-1.7.2.custom.css" />
  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/jquery-ui.min.js"></script>
  <script type="text/javascript">
   jQuery(function($){
  $.datepicker.regional['es'] = {
    closeText: 'Cerrar',
    prevText: '&#x3c;Ant',
    nextText: 'Sig&#x3e;',
    currentText: 'Hoy',
    monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio',
    'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
    monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun',
    'Jul','Ago','Sep','Oct','Nov','Dic'],
    dayNames: ['Domingo','Lunes','Martes','Mi&eacute;rcoles','Jueves','Viernes','S&aacute;bado'],
    dayNamesShort: ['Dom','Lun','Mar','Mi&eacute;','Juv','Vie','S&aacute;b'],
    dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','S&aacute;'],
    weekHeader: 'Sm',
    dateFormat: 'dd/mm/yy',
    firstDay: 1,
    isRTL: false,
    showMonthAfterYear: false,
    yearSuffix: ''};
  $.datepicker.setDefaults($.datepicker.regional['es']);
});    

        $(document).ready(function() {
           $("#datepicker").datepicker();
            $( "#21" ).datepicker();
$( "#20" ).datepicker();
$( "#10" ).datepicker();
$( "#9" ).datepicker();
$( "#16" ).datepicker();
        });
   </script>
  <style>
  @font-face {
  font-family: 'monse';
  src:  url('../fonts/Montserrat-Regular.otf');
 
}
@font-face {
  font-family: 'monse-black';
  src:  url('../fonts/Montserrat-Black.otf');
 
}
@font-face {
  font-family: 'monse-bold';
  src:  url('../fonts/Montserrat-Bold.otf');
 
}
@font-face {
  font-family: 'monse-medium';
  src:  url('../fonts/Montserrat-Medium.otf');
 
}
    @font-face {
  font-family: 'aharon';
  src:  url('../fonts/ahronbd.ttf');
 
}
@font-face {
  font-family: 'monlight';
  src:  url('../fonts/MontseLight.otf');
 
}
.genpdf{
  padding: 10px;
  margin: 15px;
  font-size: 15px;
  border-radius: 3px;

}

    .prod-container{
      width: 100%;
      text-align: center;
    }
    .personal{
      width: 31%;
    background-color: #fff;
    height: 270px;
    display: inline-block;
    border-radius: 5px;
    margin: 10px;
    vertical-align: top;
    position: relative;
    font-family: "monse";
        border: 1px solid #E6E8E7;

    }
    .personal:hover{
     box-shadow: 0px 0px 8px 0px rgba(0, 0, 0, 0.78);
-moz-box-shadow: 0px 0px 8px 0px rgba(0, 0, 0, 0.78);
-webkit-box-shadow: 0px 0px 8px 0px rgba(0, 0, 0, 0.78);
    }
    .person-photo{
      width: 90px;
      height: 90px;
      background: #383838;
      position: absolute;
      top: 10px;
      left: 10px;
      background-size: contain!important;
      border: 1px solid #E6E8E7;
    }
    .ete-photo{
      height: 110px;
      width: 100%;
     background: #E0DDDD
      position: relative;
      border-top-left-radius:5px;
      border-top-right-radius:5px;
     

    }
    .ete-num{
      width: 250px;
      height: 90px;
      position: absolute;
      top: 10px;
      right: 18px;
      line-height: 90px;
      font-size: 30px;
      color: #545A8E;
      text-align: right;
    }
    .ete-stat{
      width: 100%;
      position: relative;
      height: 160px;
      font-family: "monlight";
      
    }
    .ete-stat table{
      width: 100%;
      color: #00927B;
      font-size: 24px;
      

    }
    .ete-stat thead{
     font-size: 18px;
     color: #979999;
      
    }
    .ete-stat td{
      width: 33%;
    }
    .trh{
      height: 50px;
      background:#F9F9F9;
      border-bottom: 1px solid #E6E8E7;
      border-top: 1px solid #E6E8E7;  
    }
    .middletd{
      border-right: 1px dashed #E6E8E7;
      border-left: 1px dashed #E6E8E7;
    }
    .trb{
      line-height: 24px;
    }
    .nombre_elemento {
    font-size: 13px;
    width: 100%;
}
  </style>
</head>
<body style="">

<?php include("topbar.php");  ?>

<div class="prod-container">
  <div class="personal">
    <?=personalData(10,'Serigrafia','12'); ?>
  </div>
  <div class="personal">
    <?=personalData(20,'Serigrafia','10'); ?>
  </div>
  <div class="personal">
    <?=personalData(21,'Serigrafia','15'); ?>
  </div>
  
  <div class="personal">
    <?=personalData(16,'HotStamping','7'); ?>
  </div>
  <div class="personal">
    <?=personalData(9,'Suaje','default'); ?>
  </div>
  <div class="personal" style="opacity: 0.5">
    <?=personalData(8,'--','default'); ?>
  </div>
 
</div>
</body>
</html>
<script>
  $( function() {
    $( "#21" ).datepicker("option",'dateFormat', 'dd-mm-yy' );
     $( "#20" ).datepicker("option",'dateFormat', 'dd-mm-yy' );
      $( "#10" ).datepicker("option",'dateFormat', 'dd-mm-yy' );
       $( "#9" ).datepicker("option",'dateFormat', 'dd-mm-yy' );
        $( "#16" ).datepicker("option",'dateFormat', 'dd-mm-yy' );
  } );
  </script>
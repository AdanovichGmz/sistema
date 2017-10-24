<?php
date_default_timezone_set("America/Mexico_City");
error_reporting(0);
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
require('saves/conexion.php');
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
    $etequery3 = "SELECT COALESCE((SELECT SUM( buenos ) FROM tiraje WHERE id_maquina=$idmaquina AND fechadeldia_tiraje = '$today')/ (SELECT SUM(cantidad) FROM tiraje WHERE id_maquina=$idmaquina AND fechadeldia_tiraje = '$today' AND tiempoTiraje IS NOT NULL))*100 as calidad_primera";
    //obtenemos desempeño operando entregados+merma
    $etequery4 = "SELECT SUM(produccion_esperada) AS prod_esperada, SUM(buenos) AS prod_real  ,COUNT(desempenio) AS tirajes,SUM(produccion_esperada) AS esper FROM `tiraje` WHERE fechadeldia_tiraje='$today' AND id_maquina=$idmaquina AND tiempoTiraje IS NOT NULL";
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
    
    if ($getEfec['prod_esperada']>0) {
      $desempenio =($getEfec['prod_real']/$getEfec['prod_esperada'])*100;
    }else{
      $desempenio=0;
    }
    
    //echo $etequery3;
    //$realtime   = ($totalTime * 1) / 3600;
    $roundDesemp=($desempenio>100)? 100 : $desempenio;
    $dispon     =($seconds>14400)? ($totalTime * 100) / ($seconds-3600) : ($totalTime * 100) / $seconds;
    //$disponible = round($dispon, 1);
    $disponible = round($dispon);
    
    $real       = mysqli_fetch_assoc($mysqli->query($etequery5));
   

    //echo "<p style='color:#fff;'>dispon ".$dispon." calidad ".$Quality." desempeño ".$desempenio." prod esperada ".$getEfec['esper']." real ".$real['desempenio']." calidad ".$Quality." tiempo hasta ahora: ".$seconds."</p>";
    $roundQuality=($Quality>100)? 100 : $Quality;
    $getEte     = (($dispon / 100) * ($roundQuality / 100) * ($roundDesemp / 100)) * 100;
    $showpercent=100 - $getEte;
  /****************** Calcular ETE ******************/
          

    
    $credencial='<div class="ete-photo"><div class="person-photo" style=background:url("images/'.$photo.'.jpg")></div>
    <div class="ete-num">'.round($getEte).'%</div>
    </div>
    <div class="machinename">'.$maquina.'</div>
    <div class="ete-stat">
      <table>
      <thead>
      <tr class="trh">
        <td>Actual</td>
        <td class="">Siguiente</td>
        <td>Preparacion</td>
        </tr></thead>
        <tbody>
        <tr style="font-size: 10px;"><td>&nbsp</td>
        <td>&nbsp</td>
        <td>&nbsp</td></tr>
        <tr class="trb">
          <td>'.$act." <div class='nombre_elemento'>".$nom_act.'</div></td>
          <td class="middletd">'.$sig." <div class='nombre_elemento'>".$nom_sig.'</div></td>
          <td>'.$prep." <div class='nombre_elemento'>".$nom_prep.'</div></td>
        </tr>
        </tbody>
      </table>
    </div>';
  echo $credencial;
}


  ?>


<div class="prod-container">
 <div class="personal">
    <?=personalData(10,'Serigrafia','12'); ?>
  </div>
  <div class="personal">
    <?=personalData(20,'Serigrafia2','10'); ?>
  </div>
  
   <div class="personal">
    <?=personalData(21,'Serigrafia3','15'); ?>
  </div>
  <div class="personal disabled">
    <?=personalData(4,'Original','default'); ?>
  </div>
  <div class="personal disabled">
    <?=personalData(5,'Placa','default'); ?>
  </div>
  <div class="personal disabled">
    <?=personalData(9,'Suaje','default'); ?>
  </div>
 
</div>
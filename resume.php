
<?php
ini_set("session.gc_maxlifetime","7200");  
date_default_timezone_set("America/Mexico_City");
if (isset($_COOKIE['ajuste'])) {
    setcookie('ajuste', true, time() - 3600);
    unset($_COOKIE['ajuste']);
}
if (!isset($_COOKIE['tiraje'])) {
    setcookie('tiraje', true, time() + 3600);
}
require('saves/conexion.php');
if (!session_id()) {
    session_start();
}
if (@$_SESSION['logged_in'] != true) {
    echo '
    <script>
        alert("No has iniciado sesion");
        self.location.replace("index.php");
    </script>';
} else {
    //echo $_SESSION['machineName'];

    $mac=(isset($_SESSION['mac']))?$_SESSION['mac'] : system($cmd) ;

    $machineName=$_SESSION['machineName'];
    $machineID = $_SESSION['machineID'];
    $pause_exist = false;
    $getPaused = "SELECT *,TIME_TO_SEC(tiempo_pausa) AS seconds FROM procesos WHERE  nombre_proceso='$machineName' AND avance='en pausa'";
    $getretaking       = "SELECT *,TIME_TO_SEC(tiempo_pausa) AS seconds FROM procesos WHERE  nombre_proceso='$machineName' AND avance='retomado'";

    $paused = $mysqli->query($getPaused);
    $retaking       = $mysqli->query($getretaking);
        if ($paused->num_rows > 0) {
            $pause_exist    = true;
           
            $usID           = $_SESSION['id'];
            $recoOrden      = mysqli_fetch_assoc($paused);
            $OrderODT   = $recoOrden['numodt'];
            $orderID[] = $recoOrden['id_orden'];
            //$secondspaused  = $recoOrden['seconds'];
            //$fecha_pausa    = $recoOrden['fecha_pausa'];
            $horaAjuste     = date(" H:i:s", time());
            $newtiraje      = "INSERT INTO tiraje(id_maquina,id_orden,id_user,horadeldia_ajuste) VALUES ($machineID,$OrderID, $usID,'$horaAjuste')";
            $inserting      = $mysqli->query($newtiraje);
            if (!$inserting) {
                echo $newtiraje . "<br>";
                printf($mysqli->error);
            }
        } elseif ($retaking->num_rows > 0)  {
            
            //$secondspaused  = 'false';
            $recoOrden      = mysqli_fetch_assoc($retaking);
            $OrderODT   = $recoOrden['numodt'];
            $orderID[] = $recoOrden['id_orden'];
            $singleID=$recoOrden['id_orden'];
            $getretakingTiro    = "SELECT * FROM tiraje WHERE id_orden=$singleID ORDER BY idtiraje DESC";
            $retakingTiro       = mysqli_fetch_assoc($mysqli->query($getretakingTiro));
            $horaAjuste     = $retakingTiro['horadeldia_ajuste'];
        }else{

             $getid="SELECT * FROM personal_process WHERE status='actual' AND proceso_actual='$machineName'";
              $id=mysqli_fetch_assoc($mysqli->query($getid));
            

            $orderID = (isset($_GET['order']))? explode(",", $_GET['order'] ) : explode(",", $id['id_orden']);
           
            $singleID=$orderID[0];
            $userID      = $_SESSION['id'];
            $getAjuste    = "SELECT horadeldia_ajuste FROM tiraje WHERE id_orden=$singleID AND id_maquina=$machineID";

            $Ajuste       = mysqli_fetch_assoc($mysqli->query($getAjuste));
            $horaAjuste     = $Ajuste['horadeldia_ajuste'];
            foreach ($orderID as $order) {
              $getOdt="SELECT numodt FROM ordenes WHERE idorden=$order";
              $odt=mysqli_fetch_assoc($mysqli->query($getOdt));
            $odetesArr[]=$odt['numodt'];
            }
            $odetes=implode(",", $odetesArr);
            

        }


    
    
    $_GET['mivariable'] = $machineName;
    $query0             = "SELECT o.*,p.proceso,p.id_proceso,pp.orden_display,pp.status FROM ordenes o INNER JOIN procesos p ON p.id_orden=o.idorden INNER JOIN personal_process pp ON pp.id_orden=o.idorden WHERE proceso_actual='$machineName' AND status='actual'";
    
    $resultado0 = $mysqli->query($query0);
    
    $query01 = "SELECT o.*,p.proceso,p.id_proceso,pp.orden_display,pp.status FROM ordenes o INNER JOIN procesos p ON p.id_orden=o.idorden INNER JOIN personal_process pp ON pp.id_orden=o.idorden WHERE proceso_actual='$machineName' AND status='actual'";
    
    $resultado01 = $mysqli->query($query01);
    
    
    $query02 = "SELECT o.*,p.proceso,p.id_proceso,pp.orden_display,pp.status FROM ordenes o INNER JOIN procesos p ON p.id_orden=o.idorden INNER JOIN personal_process pp ON pp.id_orden=o.idorden WHERE proceso_actual='$machineName' AND status='actual'";
    
    $resultado02   = $mysqli->query($query02);
    $resultado02_5 = $mysqli->query($query02);
    
    
    $query1 = "SELECT o.*,p.proceso,p.id_proceso,(SELECT orden_display FROM orden_estatus WHERE id_orden=o.idorden AND id_proceso=p.id_proceso) AS orden_display,(SELECT status FROM orden_estatus WHERE id_orden=o.idorden AND id_proceso=p.id_proceso) AS status FROM ordenes o INNER JOIN procesos p ON p.id_orden=o.idorden WHERE nombre_proceso='$machineName' AND o.idorden=$singleID";
    
    $resultado1 = $mysqli->query($query1);
    
    
    $query2 = "SELECT o.*,p.proceso,p.id_proceso,(SELECT orden_display FROM personal_process WHERE id_orden=o.idorden AND id_proceso=p.id_proceso) AS orden_display,(SELECT status FROM personal_process WHERE id_orden=o.idorden AND id_proceso=p.id_proceso) AS status FROM ordenes o INNER JOIN procesos p ON p.id_orden=o.idorden WHERE nombre_proceso='$machineName' HAVING status='siguiente' ";
    
    $resultado2 = $mysqli->query($query2);
    
    $query3 = "SELECT o.*,p.proceso,p.id_proceso,(SELECT orden_display FROM personal_process WHERE id_orden=o.idorden AND id_proceso=p.id_proceso) AS orden_display,(SELECT status FROM personal_process WHERE id_orden=o.idorden AND id_proceso=p.id_proceso) AS status FROM ordenes o INNER JOIN procesos p ON p.id_orden=o.idorden WHERE nombre_proceso='$machineName' HAVING status='preparacion'";
    
    $resultado3 = $mysqli->query($query3);
?>
<!-- ************************ Contenido ******************** -->
  
<!DOCTYPE html>

<html lang="en" dir="ltr" xmlns:fb="http://ogp.me/ns/fb#">
<head>

    <?php
    $today     = date("d-m-Y");
    //obtenemos el tiempo real sumando tiempoTiraje + tiempo_ajuste +tiempoalertamaquina + tiempoajuste
    $etequery1 = "SELECT COALESCE((SELECT  IFNULL(SUM( TIME_TO_SEC( tiempoTiraje) ),0)  FROM tiraje WHERE id_maquina=$machineID AND fechadeldia_tiraje = '$today')+(SELECT  IFNULL(SUM( TIME_TO_SEC( tiempo_ajuste)),0) FROM tiraje WHERE id_maquina=$machineID AND fechadeldia_ajuste = '$today')+(SELECT  IFNULL(SUM( TIME_TO_SEC( tiempoalertamaquina) ),0)  FROM alertamaquinaoperacion WHERE id_maquina=$machineID AND fechadeldiaam = '$today') + (SELECT  IFNULL(SUM( TIME_TO_SEC(tiempoalertamaquina) ),0) FROM alertageneralajuste WHERE id_maquina=$machineID AND fechadeldiaam = '$today')) as tiempo_real";
    //obtenemos el tiempo muerto sumando las idas al sanitario
    $etequery2 = "SELECT  IFNULL(SUM( TIME_TO_SEC( breaktime) ),0) AS tiempo_muerto  FROM breaktime WHERE id_maquina=$machineID AND radios='Sanitario' AND fechadeldiaam = '$today'";
    //obtenemos la calidad a la primera operando entregados-defectos*100/cantidadpedida  
    $etequery3 = "SELECT COALESCE((SELECT SUM( entregados ) FROM tiraje WHERE id_maquina=$machineID AND fechadeldia_tiraje = '$today')/ (SELECT SUM(cantidad) FROM tiraje WHERE id_maquina=$machineID AND fechadeldia_tiraje = '$today'))*100 as calidad_primera";
    //obtenemos desempeño operando entregados+merma
    $etequery4 = "SELECT SUM(desempenio) AS desemp ,COUNT(desempenio) AS tirajes,SUM(produccion_esperada) AS esper FROM `tiraje` WHERE fechadeldia_tiraje='$today' AND id_maquina=$machineID";
    $etequery5 = "SELECT COALESCE((SELECT SUM(entregados) FROM tiraje WHERE id_maquina=$machineID AND fechadeldia_tiraje = '$today')) as desempenio";
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
    $standar_query2 = "SELECT * FROM estandares WHERE id_maquina=$machineID AND id_elemento= $element";
    
    $getstandar     = mysqli_fetch_assoc($mysqli->query($standar_query2));
    $estandar       = $getstandar['piezas_por_hora'];
    
    
    $getdeadTime = mysqli_fetch_assoc($mysqli->query($etequery2));
    $deadTime    = $getdeadTime['tiempo_muerto'];
    
    $gettotalTime = mysqli_fetch_assoc($mysqli->query($etequery1));
    $totalTime    = $gettotalTime['tiempo_real'] - $getdeadTime['tiempo_muerto'];
    
    $getQuality = mysqli_fetch_assoc($mysqli->query($etequery3));
    $Quality    = $getQuality['calidad_primera'];
    $getdesemp= $mysqli->query($etequery4);
    $getEfec       = mysqli_fetch_assoc($getdesemp);
    //obtenemos el porcentaje de estandar segundos*estandar/1hora
    $estandar_prod = (($seconds-3600) * $estandar) / 3600;
    
    $desempenio =($getEfec['tirajes']>0)? ($getEfec['desemp']*100)/($getEfec['tirajes']*100) : 0;
    //echo $etequery3;
    //$realtime   = ($totalTime * 1) / 3600;
    
    $dispon     =($seconds>14400)? ($totalTime * 100) / ($seconds-3600) : ($totalTime * 100) / $seconds;
    //$disponible = round($dispon, 1);
    $disponible = round($dispon);
    
    $real       = mysqli_fetch_assoc($mysqli->query($etequery5));
    $esperada       = mysqli_fetch_assoc($mysqli->query("SELECT COALESCE((SELECT SUM(produccion_esperada) FROM tiraje WHERE id_maquina=$machineID AND fechadeldia_tiraje = '$today')) as p_esperada"));
    $merma       = mysqli_fetch_assoc($mysqli->query("SELECT COALESCE((SELECT SUM(merma_entregada) FROM tiraje WHERE id_maquina=$machineID AND fechadeldia_tiraje = '$today')) as merma"));


    //echo "<p style='color:#fff;'>dispon ".$dispon." calidad ".$Quality." desempeño ".$desempenio." prod esperada ".$getEfec['esper']." real ".$real['desempenio']." calidad ".$Quality." tiempo hasta ahora: ".$seconds."</p>";
    $getEte     = (($dispon / 100) * ($Quality / 100) * ($desempenio / 100)) * 100;
    $showpercent=100 - $getEte;
    
?>
    <!-- bar chart -->
    <script type="text/javascript" src="https://www.google.com/jsapi"></script> 
  <!-- pie -->
       <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Type', 'ETE'],['<?="ETE ".round($getEte)."%" ?>', <?php
    echo $getEte;
?>],['<?="MUDA ".round(($showpercent<0)? 0 : $showpercent)."%" ?>', <?=($showpercent<0)? 0 : $showpercent;
     
?>] ]);
        var options = {chartArea: {width: '90%',  height: '90%'},
                       
                       pieSliceTextStyle: {color: 'white', fontSize: 16},
                       
                       legend: 'none',
                    pieSliceText: 'label',
                       is3D:false,                                               
                      // enableInteractivity: false,
                       colors: ['#05BDE3','#1F242A' ],
                                           
                       backgroundColor: 'transparent'
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart'));

        chart.draw(data, options);
      }

    </script>
    <script type="text/javascript">
    google.load("visualization", "1", {packages:["corechart"]});
    google.setOnLoadCallback(drawChart);
    function drawChart() {
      var data = google.visualization.arrayToDataTable([
          ['valor', 'porcentaje'],
         <?php
    echo "['DISPONIBILIDAD'," . $dispon . "],";
    echo "['DESEMPEÑO'," . $desempenio  . "],";
    echo "['CALIDAD'," . $Quality . "],";
    
?> ]);
        var options = { // api de google chats, son estilos css puestos desde js
            chartArea: {width: '100%', height: '90%'},
            width: "100%", 
            height: "100%",
            chartArea: {left: 25, top: 10, width: "100%", height: "80%"},
            legend: 'none',
            enableInteractivity: true,                                               
            fontSize: 11,
            hAxis: {
                    textStyle: {
                      color: '#ffffff'
                    }
                  },
            vAxis: {
                textStyle: {
                      color: '#ffffff'
                    },
            viewWindowMode:'explicit',
            viewWindow: {
              max:100,
              min:0
            }
        },

            colors: ['#05BDE3'],    
            backgroundColor: 'transparent'
        };
      var chart = new google.visualization.ColumnChart(document.getElementById("columnchart"));
      chart.draw(data,options);
  }
  </script>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?php
    echo $machineName;
?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous" />
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <!-- reloj -->   
    <link href="compiled/flipclock.css" rel="stylesheet" />
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script src="compiled/flipclock.js"></script>
    <script src="js/easytimer.min.js"></script>
    <link rel="stylesheet" href="//kendo.cdn.telerik.com/2016.3.914/styles/kendo.common-material.min.css" />
    <link rel="stylesheet" href="//kendo.cdn.telerik.com/2016.3.914/styles/kendo.material.min.css" />
    <link rel="stylesheet" href="//kendo.cdn.telerik.com/2016.3.914/styles/kendo.material.mobile.min.css" />

    <script src="//kendo.cdn.telerik.com/2016.3.914/js/kendo.all.min.js"></script>
  <link href="css/corte.css" rel="stylesheet" />
    <link href="css/estiloshome.css" rel="stylesheet" />
    <link href="css/general-styles.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script src="js/jsGrafica.js"></script>
    <script src="js/graficabarras.js"></script>
    <script src="js/divdespegable.js"></script>
   
    <script src="js/test.js"></script>
    
    <script src="js/clock.js"></script>

    <script language="javascript">// <![CDATA[

// ]]></script>


<script language="javascript">
         function limpiar() {
           setTimeout('document.fo3.reset()',20);
      return false;
       
}
</script> 

<style type="text/css">
  #cantpedido{
    width: 170px;
    height: 82px;
  text-align: left;
    position: absolute;
    right: 15px;
    top: 0;
  
  }
  #cantpedido div{
    width: 100%;
    color: #fff;
        font-size: 14px;
   text-align: left;
    font-family: "monse-bold";

  }
  #cantpedido input{
    width: 160px;
    padding: 2px;
    text-align: center;
    border-radius: 3px;
    border: 1px solid #B2BDC8;
    background: #F3F5F7;
    font-size: 40px!important;

    color: #606062;
    font-family: "monse-bold";
  }
  .darkinput{
    background: #343434!important;
    color: #fff!important;
        border: 1px solid #5C5C5C!important;
  }
  .diferentbutton{
    cursor: pointer;
    margin-top: 80px;
  }
  .diferentbutton div{
    display: inline-block;
    padding: 0!important;
    margin: 0!important;
  }
  .diferentbutton div:first-child{
    width: 80px;
    height: 80px;
    background: #B05326;
    position: relative;
    border-top-left-radius:4px;
    border-bottom-left-radius:4px;


  }
  .diferentbutton div:first-child img{
    width: 50px;
    position: absolute;
    top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);

  }
  .diferentbutton div:last-child{
    width: 170px;
    height: 80px;
    background: #FC7736;
    color: #fff;
     border-top-right-radius:4px;
    border-bottom-right-radius:4px;
    position: relative;
  }
  .diferentbutton div:last-child p{
    top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
    position: absolute;
    font-family: "monse-bold";
    font-size: 20px;

  }

  .orderform{
    width: 200px;
    height: 300px;
    display: inline-block;
    margin: 10px;
    text-align: center;
    border-radius:6px;
    
    border: 1px solid #CCCCCC;
  }
  .orderform input{
    width: 100%;
    padding: 6px 12px;
    margin: 4px 0;
    display: inline-block;
    border: 1px solid #ccc!important;
    border-radius: 4px;
    box-sizing: border-box;
    font-size: 24px;
  }
  .headbar{
    width: 100%;
    background: #ccc;
    height: 50px;
    line-height: 50px;
    color: #fff;
    font-family: "monse-bold";
    font-size: 22px;
    border-top-left-radius:4px;
    border-top-right-radius:4px;
  }
  .orderform p{
    margin: 0!important;
  }
  .formbody{
    width: 90%;
    position: relative;
    margin:0 auto;

  }
  ul{
        margin-bottom: 4px!important
  }
  table {
    border-collapse: collapse;
    width: 95%;
    margin: 0 auto;

}

th, td {
    padding: 8px;
   
    font-size: 20px;
    color: #CECECE;
    border-bottom: 1px solid #4A4747;
}
table td:first-child{
     text-align: left;
}
table td:last-child{
     text-align: right;
}
</style>

</head>
<body style="">
<input type="hidden" id="display-ete" value="<?php
    echo "getete " . $getEte . " dispon " . $dispon . " quality " . $Quality . " efec " . $desempenio;
?>">
<input type='hidden' id='pausedorder' value="<?= (isset($secondspaused)) ? $secondspaused : 'false' ?>">
 
  <?php
    $valorQuePasa  = (isset($_GET['mivariable'])) ? $_GET['mivariable'] : $recoverMac;
    $valorQuePasa2 = (isset($_GET['mivariable'])) ? $_GET['mivariable'] : $recoverMac;
    
    
    $machine    = "SELECT * FROM maquina m INNER JOIN asaichi a ON m.idmaquina = a.id_maquina WHERE nommaquina = '$machineName' and DATE(vdate) = DATE(NOW()) ORDER BY vdate DESC";
    //echo $valorQuePasa;
    $deamachine = $mysqli->query($machine);
    
?>         

<?php
    if ($row = mysqli_fetch_object($deamachine)) {
        $mach_name = $row->nommaquina;
    }
?>

    <div class="msj">
        <img src="images/msj.fw.png" />
    </div>
<div class="contegral">
   
      <ul>
  <li> <div id="divPHOTO" class="user-photo"></div></li>
  <li><span><?php
    echo "Bienvenido: " . $_SESSION['logged_in'];
?></span></li>
  <li><span>Area: <?php
    echo $machineName;
?></span></li>

    <input type="hidden" id="realtime">
    <input type="hidden" id="mach" value="<?=$machineID ?>"> 
     <input type="hidden" id="el" value="<?=$element ?>">         
  <li style="float:right"></li>
   <li style="float:right"><span id="hora" ></span></li>
    <li style="float:right ;"></li>
              
</ul>
        
<div class="statistics">
<div class="stat-panel" style="">
 <div class="stat-head2"><div class="efectivity2">DISPONIBILIDAD
</div></div>
 <div class="stat-percent"><div class="efectivity3"><?php
    echo round($dispon);
?>%
</div></div>
<div class="stat-body">
    <table>
  <tr>
    <td width="60"></td>
    <td width="40"></td>
    
  </tr>
  <tr>
    <td width="60">TIEMPO REAL</td>
    <td width="40"><?=gmdate('H:i:s',$gettotalTime['tiempo_real']) ?></td>
    
  </tr>
  <tr>
    <td width="60">TIEMPO DISPONIBLE</td>
    <td width="40"><?= gmdate('H:i:s',$seconds)?></td>
    
  </tr>
  
</table>
</div>
  
  </div>
  <div class="stat-panel" style="">
 <div class="stat-head2"><div class="efectivity2">DESEMPEÑO
</div></div>
 <div class="stat-percent"><div class="efectivity3"><?php
    echo round($desempenio);
?>%
</div></div>
<div class="stat-body">
    <table>
  <tr>
    <td width="60"></td>
    <td width="40"></td>
    
  </tr>
  <tr>
    <td width="60">PRODUCCION ESPERADA</td>
    <td width="40"><?=$esperada['p_esperada'] ?></td>
    
  </tr>
  <tr>
    <td width="60">MERMA</td>
    <td width="40"><?=$merma['merma'] ?></td>
    
  </tr>
  
</table>
</div>
  
  </div>
  <div class="stat-panel" style="">
 <div class="stat-head2"><div class="efectivity2">CALIDAD
</div></div>
 <div class="stat-percent"><div class="efectivity3"><?php
    echo round($Quality);
?>%
</div></div>
<div class="stat-body">
    <table>
  <tr>
    <td width="60"></td>
    <td width="40"></td>
    
  </tr>
  <tr>
    <td width="60">CALIDAD A LA PRIMERA</td>
    <td width="40">200</td>
    
  </tr>
  <tr>
    <td width="60">PRODUCCION REAL</td>
    <td width="40"><?=$real['desempenio'] ?></td>
    
  </tr>
  
</table>
</div>
  
  </div>






</div>
<div style="width: 100%; margin: 0 auto;text-align: center;">
    <div class="left-panel">
        <div style="position: absolute; width: 90%; top: 50%;left: 50%;transform: translate(-50%, -50%);"><a href="logout.php">
             <div id="parts" class="square-button-small red ">
                          <img src="images/exit-door.png">
                        </div></a>
                       
        </div>
    </div>
     <div class="right-panel">
         <div class="stat-head"><div class="efectivity">ETE
</div>
</div>
<div class="stat-body2">
    <span>90%</span>
</div>
     </div>
</div>
 



 
 
        
             
     
                                    <script>

                        $(document).ready(function(){
                        $('#divPHOTO').css("background-image", "url('<?php
    echo $_SESSION['MM_Foto_user'];
?>')");  
                        console.log('<?php
    echo $_SESSION['MM_Foto_user'];
?>');
                    });
                                </script>

      
 
</div>

<!-- ********************** Termina Panel comida ******************** -->

  <script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
 
 
</body>

</html>

<!-- ************************ Contenido ******************** -->

<?php
    
}


?>

<!-- ********************** Ventana de pausar ordenes ******************** -->



  <script src="js/tiraje.js"></script>
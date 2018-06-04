
<?php
error_reporting(0);
ini_set('session.gc_maxlifetime', 30*60);
date_default_timezone_set("America/Mexico_City");
require('saves/conexion.php');

    session_start();

if ($_SESSION['logged_in'] != true) {
    echo '
    <script>
        alert("No has iniciado sesion");
        self.location.replace("index.php");
    </script>';
} else {
   
    $stationName=$_SESSION['stationName'];
    $stationID = $_SESSION['stationID'];
    $processName= $_SESSION['processName'];
    $processID= $_SESSION['processID'];
    $id_sesion=$_SESSION['stat_session'];
    $userID      = $_SESSION['idUser'];
    $today=date("d-m-Y");
    $getOperation="SELECT * FROM sesiones WHERE operador=$userID AND estacion=$stationID AND fecha='$today' AND proceso=".$_SESSION['processID'];
     $operation=mysqli_fetch_assoc($mysqli->query($getOperation));
 

            $orderID = $operation['id_orden'];

            $idtiro=$operation['tiro_actual'];
           $today=date("d-m-Y");
            $singleID=$orderID[0];
            $userID      = $_SESSION['idUser'];
            $getAjuste    = "SELECT horadeldia_ajuste,elemento_virtual,planillas,TIME_TO_SEC(horadeldia_tiraje) AS iniciotiro FROM tiraje WHERE idtiraje=$idtiro";
            
            $Ajuste       = mysqli_fetch_assoc($mysqli->query($getAjuste));
            $hora_Ajuste     = $Ajuste['horadeldia_ajuste'];
           
            $odetes=$operation['orden_actual'];
    

    
    
    $query0 = "SELECT o.*,p.proceso,p.id_proceso,pp.*,(SELECT nombre_elemento FROM elementos WHERE id_elemento=o.producto) AS nombre_elemento FROM ordenes o INNER JOIN procesos p ON p.id_orden=o.idorden INNER JOIN personal_process pp ON pp.id_orden=o.idorden WHERE proceso_actual='$stationName' AND nombre_proceso='$processName' AND status='actual' AND p.nombre_proceso='$processName'";

    
    $resultado0 = mysqli_fetch_assoc($mysqli->query($query0));
    
   
        $cpedido = $resultado0['cantpedido'];
  
    
    $query2 = "SELECT o.*,p.proceso,p.id_proceso,pp.* FROM ordenes o INNER JOIN procesos p ON p.id_orden=o.idorden INNER JOIN personal_process pp ON pp.id_orden=o.idorden WHERE proceso_actual='$stationName' AND nombre_proceso='$processName' AND status='siguiente' AND p.nombre_proceso='$processName'";
    
    $resultado2 = $mysqli->query($query2);
    
    $query3 = "SELECT o.*,p.proceso,p.id_proceso,pp.* FROM ordenes o INNER JOIN procesos p ON p.id_orden=o.idorden INNER JOIN personal_process pp ON pp.id_orden=o.idorden WHERE proceso_actual='$stationName' AND nombre_proceso='$processName' AND status='preparacion' AND p.nombre_proceso='$processName'";
    
    $resultado3 = $mysqli->query($query3);
?>
<!-- ************************ Contenido ******************** -->
  
<!DOCTYPE html>

<html lang="en" dir="ltr" xmlns:fb="http://ogp.me/ns/fb#">
<head>
<meta name="mobile-web-app-capable" content="yes">
    <?php
    $today     = date("d-m-Y");

     $real=mysqli_fetch_assoc($mysqli->query("SELECT TIME_FORMAT(SEC_TO_TIME((SUM(TIME_TO_SEC(tiempoTiraje))+SUM(TIME_TO_SEC(tiempo_ajuste))+IFNULL((SELECT SUM(TIME_TO_SEC(tiempoalertamaquina)) FROM alertageneralajuste WHERE fechadeldiaam = '$today' AND id_usuario =$userID AND es_tiempo_muerto='false'),0)+IFNULL((SELECT SUM(TIME_TO_SEC(tiempo)) FROM asaichi WHERE fechadeldia = '$today' AND id_usuario=$userID),0)-IFNULL(
(SELECT SUM(TIME_TO_SEC(breaktime)) FROM breaktime WHERE fechadeldiaam = '$today' AND id_usuario =$userID AND radios='Sanitario'),0))), '%H:%i') AS t_real,(SUM(TIME_TO_SEC(tiempoTiraje))+SUM(TIME_TO_SEC(tiempo_ajuste))+IFNULL((SELECT SUM(TIME_TO_SEC(tiempoalertamaquina)) FROM alertageneralajuste WHERE fechadeldiaam = '$today' AND id_usuario =$userID AND es_tiempo_muerto='false'),0)-IFNULL(
(SELECT SUM(TIME_TO_SEC(breaktime)) FROM breaktime WHERE fechadeldiaam = '$today' AND id_usuario =$userID AND radios='Sanitario'),0))+IFNULL((SELECT SUM(TIME_TO_SEC(tiempo)) FROM asaichi WHERE fechadeldia= '$today' AND id_usuario=$userID),0) AS sec_t_real FROM tiraje WHERE fechadeldia_ajuste = '$today' AND id_user =$userID"));



$disponible=mysqli_fetch_assoc($mysqli->query("SELECT TIME_FORMAT(SEC_TO_TIME(((SUM(TIME_TO_SEC(horafin_tiraje) - TIME_TO_SEC(horadeldia_tiraje))+SUM(TIME_TO_SEC(horafin_ajuste)-TIME_TO_SEC(horadeldia_ajuste)))-IFNULL(
(SELECT SUM(TIME_TO_SEC(breaktime)) FROM breaktime WHERE fechadeldiaam = '$today' AND id_usuario =$userID),0))+IFNULL((SELECT SUM(TIME_TO_SEC(tiempo_muerto)) FROM tiempo_muerto WHERE fecha= '$today' AND id_user =$userID AND seccion='desfase'),0)), '%H:%i') AS disponible, ((SUM(TIME_TO_SEC(horafin_tiraje) - TIME_TO_SEC(horadeldia_tiraje))+SUM(TIME_TO_SEC(horafin_ajuste)-TIME_TO_SEC(horadeldia_ajuste)))-IFNULL(
(SELECT SUM(TIME_TO_SEC(breaktime)) FROM breaktime WHERE fechadeldiaam = '$today' AND id_usuario =$userID),0))+IFNULL((SELECT SUM(TIME_TO_SEC(tiempo_muerto)) FROM tiempo_muerto WHERE fecha='$today' AND id_user=$userID AND seccion='desfase'),0) AS sec_disponible FROM tiraje WHERE fechadeldia_ajuste = '$today' AND id_user =$userID"));

 $element    =($resultado0['elemento_virtual']!=null)? $resultado0['id_elemento_virtual'] : $operation['parte'];


$sumatorias=mysqli_fetch_assoc($mysqli->query("SELECT SUM(buenos)AS sum_prod_real,SUM(merma_entregada)AS sum_merma,SUM(produccion_esperada)AS sum_prod_esperada, SUM(buenos)-SUM(defectos)AS sum_calidad_primera FROM tiraje WHERE fechadeldia_ajuste = '$today' AND id_user =$userID"));
 


$disponibilidad=($real['sec_t_real']/$disponible['sec_disponible'])*100;
$dispon_tope= ($disponibilidad>100)?100:$disponibilidad;
$desemp=(($sumatorias['sum_prod_real']+$sumatorias['sum_merma'])/$sumatorias['sum_prod_esperada'])*100;
$desemp_tope=($desemp>100)?100:$desemp;
$calidad=($sumatorias['sum_calidad_primera']/$sumatorias['sum_prod_real'])*100;
$calidad_tope=($calidad>100)?100:$calidad;
$final=(($calidad_tope/100)*($desemp_tope/100)*($dispon_tope/100))*100;
$showpercent=100-$final;
    
?>
    <!-- bar chart -->
    <script type="text/javascript" src="js/libs/google_api.js"></script> 
  <!-- pie -->
       <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Type', 'ETE'],['<?="ETE ".round($final)."%" ?>', <?php
    echo $final;
?>],['<?="MUDA ".round(($showpercent<0)? 0 : $showpercent)."%" ?>', <?=($showpercent<0)? 0 : $showpercent;
     
?>] ]);
        var options = {chartArea: {width: '90%',  height: '90%'},
                       
                       pieSliceTextStyle: {color: 'white', fontSize: 16},
                       
                       legend: 'none',
                    pieSliceText: 'label',
                       is3D:false,                                               
                      // enableInteractivity: false,
                       colors: ['#84b547','#2d2d2d' ],
                                           
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
    echo "['DISPONIBILIDAD'," .round($dispon_tope,2)  . "],";
    echo "['DESEMPEÑO'," . round($desemp_tope,2)  . "],";
    echo "['CALIDAD'," . round($calidad_tope,2) . "],";
    
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
    echo $processName;
?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
    
    <!-- reloj -->   
    <link href="compiled/flipclock.css" rel="stylesheet" />
     <script src="js/libs/jquery.min.js"></script>
     
<script src="js/libs/kendo.all.min.js"></script>
    <script src="compiled/flipclock.js"></script>
    <script src="js/easytimer.min.js"></script>
     <link rel="stylesheet" href="css/bootstrap.min.css" />
    <!-- Optional theme -->
    <link rel="stylesheet" href="css/bootstrap-theme.min.css" />
    <!-- Latest compiled and minified JavaScript -->
    <script src="js/libs/bootstrap.min.js"></script>
  
      <link href="css/general-styles.css" rel="stylesheet" />
    <!-- LOADER -->
    <link rel="stylesheet" href="css/normalize.min.css">

<link rel="stylesheet" href="css/3.3.6/bootstrap.min.css" />

    
  <link href="css/corte.css?v=4" rel="stylesheet" />
    <link href="css/estiloshome.css?v=4" rel="stylesheet" />
    <link href="css/tiraje.css" rel="stylesheet" />
   
   
   
   
    <script src="js/test.js"></script>
    
    <script src="js/clock.js"></script>

    <script language="javascript">// <![CDATA[
// ]]></script>
 
<link rel="stylesheet" href="css/softkeys-0.0.1.css?v=2">
<script language="javascript">
         function limpiar() {
           setTimeout('document.fo3.reset()',20);
      return false;
       
}
</script> 

<style type="text/css">

</style>

</head>
<body style="">
<input type="hidden" id="actiro" value="<?=$operation['tiro_actual'] ?>">
<input type="hidden" id="iniciotiro" value="<?=$Ajuste['iniciotiro'] ?>">


<input type='hidden' id='pausedorder' value="<?= (isset($secondspaused)) ? $secondspaused : 'false' ?>">
 
 


    <div class="msj">
        <img src="images/msj.fw.png" />
    </div>
<div class="contegral">
   
      <ul>
  <!-- <li> <div id="divPHOTO" class="user-photo"></div></li> -->
  <li><span class="person-title" style="font-size:20px;"><?php
    echo $_SESSION['logged_in'].' | '.$processName;
?></span></li>

  <li><div class="live-indicator">Tiros: <?=round($sumatorias['sum_prod_real'],1) ?></div></li>
<li><div class="live-indicator">Merma: <?=round($sumatorias['sum_merma'],1) ?></div></li>
    <input type="hidden" id="realtime">
    <input type="hidden" id="mach" value="<?=$processID ?>"> 
     <input type="hidden" id="el" value="<?=$element ?>">         
  <li style="float:right"></li>
   <li style="float:right"><span id="hora" ></span></li>
    <li style="float:right ;" id="avancerealtime"><?php include 'avance.php';
?></li>
              
</ul>
        
<div class="statistics">
  <div class="stat-panel">
  <div class="stat-head"><div class="efectivity"><?php
    echo round($final);
?>%</div></div>
    <table class="orders gree">
 
  <tr class="trhead">
    <td class="orders-td2" >ACTUAL:</td>
    
  </tr>
  <tr>
    
    <td class="orders-td" style="color:#2c97de"> <?php if (count($orderID) > 1) {
        echo $odetes;
   
     } else{
        if ($operation['parte']!=null) {
        
        $displayElement=($_SESSION['is_virtual']=='false')? $resultado0['nombre_elemento'] : $Ajuste['elemento_virtual'];
        $size=(strlen($displayElement)>17)?'font-size: 17px;':'';
        echo $operation['orden_actual'] . " <span  ".$size."'>" . $displayElement . "</span>";
    } else {
        echo "--";
    }
     }
    
?></td>
  </tr>
  
</table>
<table class="orders gree">
 
  <tr class="trhead">
    <td class="orders-td2">SIGUIENTE:</td>
    
  </tr>
  <tr>
    
    <td class="orders-td"><?php
    if ($row2 = mysqli_fetch_object($resultado2)) {
        $prodsig          = $row2->producto;
        $sigelement_query = "SELECT nombre_elemento FROM elementos WHERE id_elemento=$prodsig";
        $get_sigelem      = mysqli_fetch_assoc($mysqli->query($sigelement_query));
        $sigelement       = $get_sigelem['nombre_elemento'];
        $size=(strlen($sigelement)>17)?'font-size: 17px;':'';
        echo $row2->numodt . " <span style='color:#2FE3BF;".$size."'>" . $sigelement . "</span>";
    } else {
        $sigquery=$mysqli->query("SELECT * FROM odt_flujo WHERE status='siguiente' AND proceso='$stationName'");
        if ($rowsig = mysqli_fetch_object($sigquery)) {
            echo $rowsig->numodt;
        }else{
          echo "--";  
        }
        
    }
?>
   </td>
  </tr>
  
</table>
<table class="orders gree">
 
  <tr class="trhead">
    <td class="orders-td2">PREPARACIÓN:</td>
    
  </tr>
  <tr>
     
    <td class="orders-td" ><?php
    if ($row3 = mysqli_fetch_object($resultado3)) {
        $prodprep          = $row3->producto;
        $prepelement_query = "SELECT nombre_elemento FROM elementos WHERE id_elemento=$prodprep";
        $get_prepelem      = mysqli_fetch_assoc($mysqli->query($prepelement_query));
        $prepelement       = $get_prepelem['nombre_elemento'];
        $size=(strlen($prepelement)>17)?'font-size: 17px;':'';
        echo $row3->numodt . " <span style='color:#2FE3BF; ".$size."'>" . $prepelement . "</span>";
    } else {
        $prepquery=$mysqli->query("SELECT * FROM odt_flujo WHERE status='preparacion' AND proceso='$stationName'");
        if ($rowprep = mysqli_fetch_object($prepquery)) {
            echo $rowprep->numodt;
        }else{
          echo "--";  
        }
        
    }
?>
      
    </td>
   
    
  </tr>
</table>
  </div>
  <div class="stat-panel" style="">
  <?php //print_r($_POST); 
?>
  <div style="width: 100%;height: 100%; position: absolute;">
   <div class="grafica">
                   <!-- <div id="graficajs" class="panelabajo"></div> -->
                        <div id="piechart" style="width: 100%; height: 100%;" ></div>        
                    </div>
                    </div>
  </div>
  <div class="stat-panel" style="">

    
                <!-- <div id="_GraficaInter"></div> -->
                <div id="columnchart" style="top:10px;width: 100%; height: 300px; position:absolute; "></div>

            

  </div>
</div>
 <form name="fvalida" id="fvalida" method="POST" onsubmit="saveTiro()">
 <input type="hidden" name="element" value="<?=$element ?>">
 <input type="hidden" id="table-machine" name="table-machine" value="<?=(isset($_REQUEST['mm']))? $_REQUEST['mm'] : 1 ?>">
  <input type="hidden" name="section" value="tiraje">
 <input type="hidden" name="hour" value="<?= $hora_Ajuste; ?>"> 
 <input type="hidden" name="horainiciotiro" value="<?=date(" H:i:s", time()); ?>">
<div class="statistics">
  <div class="left-sec" style="position: relative;">
      <div class="timersmall">
          <div id="tirajeTime" data-inicio="<?=(strtotime(date("H:i:s",time()))-strtotime($operation['inicio_tiro']))-(((empty($operation['tiempo_alert']))? 0: $operation['tiempo_alert'])+((empty($operation['tiempo_comida']))? 0: $operation['tiempo_comida']))  ?>">
          <div id="timersmall"><span class="valuesTiraje">00:00:00</span></div>
          </div>
          </div>
          
  
          <div id="cantpedido">
            
            
          </div>
         
          <div class="button-panel" id="leftbuttons">
                        <a href="#" onClick="endSesion()"> <img src=""  href="#" class="img-responsive"  />
                        <div class="square-button-h red">
                          <img src="images/sal.png">
                        </div></a>
                        <div class="square-button-h green stop eatpanel goeat" onclick="saveoperComida()">
                          <img src="images/dinner2.png">
                        </div>
                        
                        <div class="square-button-h blue " id="saving">
                          <img src="images/saving.png">
                        </div>
                        
                        <div class="square-button-h yellow goalert" onclick="derecha(); saveoperAlert();">
                          <img src="images/warning.png">
                        </div>
                        <div  class="square-button-h prple" onclick="pauseConfirm();">
                          <img src="images/cantir.png">
                        </div>
                       <div style="display: none;" class="square-button-h prple" onclick="pauseConfirm();">
                          <div class="square-text"> PAUSAR Y CONTINUAR MAÑANA</div>
                        </div>
                        
                        </div>
              
  </div>
  <div class="right-sec">

  <div style="width: 100%; text-align: center;">

     <table id="former">
  <input  type="hidden" id="qty" name="qty" value="single" />
  <tr>
    <td class="title-form">CANTIDAD DE PEDIDO</td>
    <td class="title-form">BUENOS</td>
  </tr>
  <tr>
    <?php
            
                $cantrecib = $resultado0['cantrecibida'];
                
                $merm = ($resultado0['merma_recibida'] != null) ? $cantrecib - $cpedido : $cantrecib - $cpedido;
            
?>
    <td class=""><input type="number" class="getkeyboard inactive"  id="pedido"  name="pedido" value="<?=$cpedido ?>" readonly onclick="getKeys(this.id,'pedido')" onkeyup="opera();"  ></td>
   
   
   <td class=""><input id="buenos" class="getkeyboard inactive" onclick="getKeys(this.id,'buenos')"  name="buenos" type="number"  name="" onkeyup="opera();" readonly style="margin-right: 10px;" required="required"></td>
    
    
  </tr>
  <tr>
    <td class="title-form">CANTIDAD RECIBIDA</td>
    <td class="title-form">PIEZAS DE AJUSTE</td>
  </tr>
  <tr>
    <td class=""> <input type="number" id="cantidad" readonly onclick="getKeys(this.id,'cantidad')"  class="getkeyboard inactive" name="cantidad" value="<?= $cantrecib ?>"  onkeyup="opera();">
    <!-- <input id="merma" class="" name="merma" type="number"   value="<?= $merm ?>"  style="width: 75px;margin-right: 10px;" required="required"> --> </td>
    <td class=""><input  id="piezas-ajuste" readonly class="getkeyboard inactive" name="piezas-ajuste" type="number"  onclick="getKeys(this.id,'piezas-ajuste')"  style="margin-right: 10px;" onkeyup="GetDefectos()" > </td>
  </tr>
  <tr>
    <td class="title-form">MERMA</td>
    <td class="title-form">DEFECTOS</td>
  </tr>
  <tr>
    <td class=""><input class="inactive" value="" readonly id="merma-entregada" onclick="getKeys(this.id,'merma-entregada')" name="merma-entregada" type="number"    style="margin-right: 10px;"></td>
      <td class=""><input id="defectos"  onclick="getKeys(this.id,'defectos')" readonly class="getkeyboard inactive" name="defectos" type="number" value=""    ><!--<input id="entregados" name="entregados" type="number" value="" required="true"  style="">--></td>
  </tr>
</table>
                            <input hidden name="planillas" value="<?= $Ajuste['planillas'] ?>"/>                            
                            <input hidden id="producto" name="producto" class=" diseños" value="<?= $resultado0['producto'] ?>"/>
                             <input hidden id="numodt" name="numodt" class="diseños" value="<?= $orderID ?>"/>
                             <input hidden id="odt" name="odt" class=" diseños" value="<?= $resultado0['numodt'] ?>"/>
                      <input hidden id="numproceso"  class=" diseños" value="<?= $resultado0['id_proceso'] ?>"/>
                             
</div>

         <input hidden class="diseños"  type="text" name="tiempoTiraje" id="tiempoTiraje" />
                   <input hidden type="text"  name="logged_in" id="logged_in" value="<?php
    echo "" . $_SESSION['idUser'];
?>" />
                   <input hidden  name="horadeldia" id="horadeldia" value="<?php
    echo date(" H:i:s", time() - 28800);
?>" />
                   <input hidden  name="fechadeldia" id="fechadeldia" value="<?php
    echo date("d-m-Y");
?>" />
                   
                    


                          <input id="formbutton" type="submit" style="visibility: hidden;"></button>
       
</form>
</div>
    
    </div>
<div class="backdrop"></div>
<!-- ********************** Ventanita loader ******************** -->
<div class="box">
  <div class="saveloader">
  
    <img src="images/loader.gif">
    <p style="text-align: center; font-weight: bold;">Guardando..</p>
  </div>
  <div class="savesucces" style="display: none;">
  
    <img src="images/success.png">
    <p style="text-align: center; font-weight: bold;">Listo!</p>
  </div>
  </div>
<!-- ********************** Termina Ventanita loader ******************** -->

<!-- ********************** Panel Alerta Maquina ******************** -->
  
   <div id="panelder">
    <div id="panelizqui">
       
   </div>
      <div class="container">
          
            <div id="estilo">

             <form id="alerta-tiro" name="alerta-tiro" method="post"  class="form-horizontal"  >
                <input type="hidden" id="actiro" name="tiro" value="<?=$operation['tiro_actual'] ?>">
                <input type="hidden" id="inicioAlerta" name="inicioAlerta">
                <input hidden type="text"  name="logged_in" id="logged_in" value="<?="" . $_SESSION['logged_in'];
?>" />
                <input hidden  name="horadeldiaam" id="horadeldiaam" value="<?=date(" H:i:s", time());
?>" />
                <input hidden name="fechadeldiaam" id="fechadeldiaam" value="<?=date("d-m-Y");
?>" />
                
               
                <fieldset>

                 <!-- Form Name -->
                <legend>ALERTA MAQUINA</legend>
                 <!-- Multiple Radios -->
                <div class="form-group" style="width:80% ;margin:0 auto;">
                <?php
    include "alertsTiraje.php";
?>
                
                </div>

               

                <!-- Textarea -->
                <div class="form-group" id="explanation" style="width:81%;margin:30px auto;text-align: center; color:black;">
                    <textarea placeholder="Observaciones.." class="comments" id="observaciones" name="observaciones"></textarea>
                     <p id="explain-error" style="display: none;">Porfavor agrega una explicacion ↑</p>
                
                </div>
                <div class="form-group">
                  <div class="button-panel-small">
                       
                        <div style="display: none;" class="square-button-small red derecha stopalert start reset" onclick="saveOperstatus()">
                          <img src="images/ex.png">
                        </div>
                        <div id="savealerta" class="square-button-small  blue " onclick="saveAlert();">
                          <img src="images/saving.png">
                        </div>
                        
                          
                        </div>
                </div>
                <!-- reloj -->
                
                 <div class="reloj-container2">  
        <div class="timersmall">
                                    <div id="alertajuste" style="text-align: center;">
                                    <div id="timersmall"><span class="valuesAlert">00:00:00</span></div>
                                    <input type="hidden"  name="tiempoalertamaquina" id="timealerta"  />
                                   
                                    
                                </div>
                                </div>
    </div>
                <input id="eviar" type="image" name="eviar" value="Guardar" src="images/btnguardar.fw.png" class="img-responsive derecha stopalert start reset2 " style="display: none;">

               </fieldset>    
                
             </form>
          <!-- <div id="result"></div>     
          -->  
           
      <div class="reloj-container2">
      
        <div  id="relojajuste" class="relojajuste" ></div>
      </div>
      </div>
   </div>
 </div>
 <!-- ********************** Termina Panel Alerta Maquina ******************** -->
 
  <!-- ********************** Inicia Panel comida ******************** -->
   <div id="panelbrake">
    <div id="panelbrake2"></div>
      <div class="container-fluid" style="text-align: center;">
          
          <div id="estilo" style="text-align: center;">
             <form id="fo4" name="fo4"  method="post" class="form-horizontal" >
                <fieldset style="position: relative;left: -15px;">
                 <input type="hidden" name="act_tiro" value="<?=$operation['tiro_actual'] ?>">
                 <input type="hidden" name="curr-section" value="tiro">
                 <input type="hidden" id="inicioAlertaEat" name="inicioAlertaEat">
                <input hidden type="text"  name="logged_in" id="logged_in" value="<?="" . $_SESSION['logged_in'];
?>" />
                <input hidden name="horadeldiaam" id="horadeldiaam" value="<?=date(" H:i:s", time());
?>" />
                <input hidden name="fechadeldiaam" id="fechadeldiaam" value="<?=date("d-m-Y");
?>" />
                   
                  
                
                   <input type="hidden" id="timeeat" name="breaktime">
               
                   <!-- Multiple Radios (inline) -->
                   <div class="form-group" style="width:80% ;margin:200px auto 40px auto;">
                <label class="col-md-4 control-label" for="radios" style="display: none;"></label>

              <div class=" radio-menu face  eatpanel" onclick="showLoad();submitEat();saveOperstatus();">
                <input type="radio" class="" name="radios" id="radios-0" value="Comida">
                    COMIDA</div>
               <div class=" radio-menu face eatpanel" onclick="showLoad();submitEat();saveOperstatus();">
               <input type="radio" name="radios" id="radios-1" value="Sanitario" >
                   SANITARIO
                    
                    </div>
                    
                
                </div>
                   </br>
                   </br>
                   </br>
                
               </fieldset>    
                
             </form>
      
      <div class="reloj-container2" style="text-align: center;">
    <div class="timersmall">
                                    <div id="horacomida" style="text-align: center;">
                                    <div id="timersmall" style="text-align: center;">
                                    <span class="valuesEat">00:00:00</span>
                                    </div>
                                    
                                   
                                    
                                </div>
                                
                                </div>
    </div>
                                    <script>
                       
                                </script>

      </div>
   </div>
</div>

<!-- ********************** Termina Panel comida ******************** -->
  <!-- ********************** Inicia Panel teclado ******************** -->
   <div id="panelkeyboard">
    <div class="cerrarkey">
      <div id="close-down" class="square-button-micro red  ">
                          <img src="images/ex.png">
                        </div>
    </div>
    <div class="keycontainer">
      <div id="softk" style="width: 80%;margin: 0 auto; text-align: center;" class="softkeys" data-target="input[name='buenos']"></div>
    </div>
    
</div>

<!-- ********************** Termina Panel teclado ******************** -->
  <script src="js/libs/jquery-ui.js"></script>
 <script>
 $('.radio-menu').click(function() {
  $('.face-osc').removeClass('face-osc');
  $(this).addClass('face-osc').find('input').prop('checked', true)    
});                         
                         $( "#saving").click(function() {
                          var buenos=$('#buenos').val();
                          var merma=$('#merma').val();
                          var entre=$('#entregados').val();
                          var ajuste=$('#piezas-ajuste').val();
                          var defectos=$('#defectos').val();
                         if (parseInt(entre)<0||parseInt(ajuste)<0||parseInt(defectos)<0) { alert('No puedes enviar valores negativos');}
                         
                          else{

                              if (buenos!=''&&ajuste!='') {
                                    timer.pause();
                            $('#tiempoTiraje').val(timer.getTimeValues().toString()); 
                            $('#close-down').click(); 
                             $( "#formbutton" ).click();
                             showLoad();
                           }else{
                            if (buenos==''){
                              $('#buenos').addClass("errror").attr("placeholder", "?").effect( "shake" );
                            }
                            if (ajuste==''){
                              $('#piezas-ajuste').addClass("errror").attr("placeholder", "?").effect( "shake" );
                            }
                           }
                                                
                         }
                                             
                                            });
                         
                    </script>

<script type="text/javascript">
 
  function pauseOrder(){
    var input_buenos=$('#pausebuenos').val();
    var input_ajuste=$('#pauseajuste').val();
  if (input_buenos=='') {
    $('#buenos-messaje').show();
  }
  if (input_ajuste=='') {
    $('#ajuste-messaje').show();
  }
    else{
    timer.pause();
    var tpausa=timer.getTimeValues().toString();
    var recib=<?= (isset($cantrecib)) ? (($cantrecib != null) ? $cantrecib : 0) : 0; ?>;
    var merm=<?= (isset($cantrecib)) ? $cantrecib - $cpedido : 0 ?>;
    var pedido=<?= (isset($cpedido)) ? $cpedido : 0 ?>;
    var fech=$('#fechadeldia').val();
    var hor=$('#horadeldia').val();
    var buen=$('#pausebuenos').val();
    var defec;
    
    
    var ajus=$('#pauseajuste').val();
    var prod=$('#producto').val();
    timer.stop();
    if (ajus>2) {
                              defec =(parseInt(ajus)-2);
                            }else{
                              defec =0;
                            }
    var merm_entreg=((parseInt(buen)-parseInt(pedido))-parseInt(ajus))-parseInt(defec);
    var entreg=(parseInt(ajus)+parseInt(merm_entreg))+parseInt(buen);
   
    var idord=$('#numodt').val();
    var proceso=$('#numproceso').val();
    console.log(idord);
    $.ajax({  
                             type:"POST",
                             url:"pauseOrder.php",   
                             data:{id_orden:idord,proceso:proceso,tpausa:tpausa,cantrecib:recib,cantpedido:pedido,merma:merm,buenos:buen,ajuste:ajus,defectos:defec,entregados:entreg,fecha:fech,hora:hor,producto:prod,mermaent:merm_entreg},  
                               
                             success:function(data){
                               
                                  $('.boxPause').html(data);
                                  setTimeout(function() {   
                   location.href = 'logout.php';
                }, 1000);
                                  
                             }  
                        });
  }
  }  

  
    </script>
 
</body>

</html>

<!-- ************************ Contenido ******************** -->

<?php
    
}
?>

<!-- ********************** Ventana de pausar ordenes ******************** -->
<div class="boxPause">
  <p></p>
  <p style="font-size:26px; font-weight: bold;">¿Seguro que deseas cancelar este tiro?</p>
  <p style="display: none;"><input type="number" id="pausebuenos" placeholder="Buenos"></p>
  <p id="buenos-messaje" style="display: none;">Debes ingresar la cantidad de buenos</p>
  <p style="display: none;"><input type="number" id="pauseajuste" placeholder="Piezas de ajuste"></p>
  <p id="ajuste-messaje" style="display: none;">Debes ingresar las piezas de ajuste</p>
  <div class="confirmbutton blue" onclick="cancelTiro()">SI</div><div class="confirmbutton red" onclick="close_box()">NO</div>
  
  </div>
<!-- ********************** Termina Ventana de pausar ordenes ******************** -->
 <script src="js/libs/2.1.4.jquery.min.js"></script>
<script>
  
</script>
<script src="js/softkeys-0.0.1.js"></script>

  <script src="js/tiraje.js?v=16"></script>

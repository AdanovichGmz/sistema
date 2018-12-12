<?php
error_reporting(0);
ini_set('session.gc_maxlifetime', 30*60); 
date_default_timezone_set("America/Mexico_City");

session_start();
   
    if(@$_SESSION['logged_in'] != true){
      echo '
     <script>
        alert("No has iniciado sesion");
        self.location.replace("index.php");
    </script>';
    }else{
              
require('saves/conexion.php');

$stationName=$_SESSION['stationName'];
$stationID = $_SESSION['stationID'];
$processName=$_SESSION['processName'];
$processID = $_SESSION['processID'];
$today=date("d-m-Y");

$getActivity=$mysqli->query("SELECT s.*,(SELECT nombre_elemento FROM elementos WHERE id_elemento=s.parte)AS nombre_elemento,TIME_TO_SEC(inicio_ajuste) AS segundos_incio FROM sesiones s WHERE s.estacion=$stationID AND s.fecha='$today' AND s.proceso=".$_SESSION['processID']." AND s.operador=".$_SESSION['idUser']);
    
$activity=mysqli_fetch_assoc($getActivity); 
 $getMyProcess=$mysqli->query("SELECT * FROM estaciones_procesos WHERE id_estacion=".$_SESSION['stationID']);




    
$orderSet=mysqli_fetch_assoc($mysqli->query("SELECT * FROM personal_process WHERE status='actual' AND sesion=".$_SESSION['stat_session']));     
  
$e_ajuste=mysqli_fetch_assoc($mysqli->query("SELECT ajuste_standard FROM estandares WHERE id_elemento=144 AND id_proceso=".$processID));
$_SESSION['ajusteStandard']=$e_ajuste['ajuste_standard'];

$responsable=mysqli_fetch_assoc($mysqli->query("SELECT responsable_5s FROM usuarios u WHERE id=".$_SESSION['idUser']));
$cumplido=mysqli_fetch_assoc($mysqli->query("SELECT lista_diaria FROM sesiones WHERE id_sesion=".$_SESSION['stat_session']));
if (isset($_SESSION['pending_exist'])) {
  if ($_SESSION['pending_exist']=='true'&& $processID==$_SESSION['proceso_cola']) {
  $tiempo_cola=$_SESSION['tiempo_cola'];
}else{
  $tiempo_cola=0;
}
}else{
  $tiempo_cola=0;
}

$getElementStandar=$mysqli->query("SELECT * FROM estandares e INNER JOIN elementos el ON e.id_elemento=el.id_elemento WHERE e.id_proceso=$processID ORDER BY nombre_elemento ASC");

$p=1;
if ( $p==1) {

?>
<!-- *********************** CONTENIDO ********************* -->
<!DOCTYPE html>

<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>AJUSTE <?=$processName ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
    <?php include 'head.php'; ?>
    <link href="css/estiloshome.css?v=4" rel="stylesheet" />
    <link href="css/ajuste.css?v=3" rel="stylesheet" />
    <link rel="stylesheet" href="css/softkeys-small.css?v=2">
</head>

<style type="text/css">

       .clock{
        transform: scale(1.5);
-ms-transform: scale(1.5); 
-webkit-transform: scale(1.5); 
-o-transform: scale(1.5);
-moz-transform: scale(1.5);
      }  

#load{
  width: 100%; text-align: center; 
}

         .congral2{
            width: 100%;
            height: 100%;

        }

        .vform{
          width: 40%;
          display: inline-block;
          text-align: center;
          vertical-align: top;
        }

   .disabled{
    opacity: 0.1;
    cursor: not-allowed;
   }     

  #virtualodt{
    text-transform:uppercase;
        width: 90%;
    font-size: 25px;
    padding: 10px;
    text-align: center;
    border-radius: 4px;
    border: 1px solid #D2D3D5;
   
    
  }
  .vform p{
    font-size: 20px!important;
    font-weight: bold;
  }
  .vform p:last-child{
    color: red;
  }
  
    #virtualelem{
    
        width: 90%;
    font-size: 25px;
    padding: 10px;
    text-align: center;
    border-radius: 4px;
    border: 1px solid #D2D3D5;
   
  }


#result {
  width:280px;
  padding:10px;
  border:1px solid #bfcddb;
  margin:auto;
  margin-top:10px;
  text-align:center;
}

 #success-msj{
    color: #BB1B1B!important;
    font-family: "monse-medium"!important;

}   
.backdrop,.backdrop-change
    {
      position:absolute;
      top:0px;
      left:0px;
      width:100%;
      height:100%;
      background:#000;
      opacity: .0;
      filter:alpha(opacity=0);
      z-index:50;
      display:none;
    }
 
 
    .box
    {
      position:absolute;
      top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
      width:150px;
      height: 150px;
      
      background:#ffffff;
      z-index:51;
      padding:10px;
      -webkit-border-radius: 5px;
      -moz-border-radius: 5px;
      border-radius: 5px;
      -moz-box-shadow:0px 0px 5px #444444;
      -webkit-box-shadow:0px 0px 5px #444444;
      box-shadow:0px 0px 5px #444444;
      display:none;
    }
    .change-confirm{
      position:absolute;
      top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
      width:550px;
      background:#ffffff;
      z-index:51;
      padding:10px;
      -webkit-border-radius: 5px;
      -moz-border-radius: 5px;
      border-radius: 3px;
      -moz-box-shadow:0px 0px 5px #444444;
      -webkit-box-shadow:0px 0px 5px #444444;
      box-shadow:0px 0px 5px #444444;
       display:none;
    }
    .change-cont{
      width: 90%;
      margin: 0 auto;
    }
    .change-confirm p{
      font-size: 30px;
      margin: 30px auto;
    }
    .change-confirm table{
      width: 100%;
      margin-bottom: 20px;
    }
    .change-confirm td{
      padding: 5px;
    }
    .change-confirm button{
      border:none;
      width: 150px;
      padding: 20px 0;
      font-size: 20px;
      
      border-radius: 3px;
    }
    .change-confirm button:hover{
      background: #4C89DC;
      color: #fff;
    }
  .setElement
    {
      position:absolute;
      top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
      width:100%;
      height: 100%;
      background: #E5E9EC; 
        display: none;
      z-index:9999999999;
      padding:10px;
      -webkit-border-radius: 5px;
      -moz-border-radius: 5px;
      border-radius: 5px;
    
      
    }
    .close
    {
      float:right;
      margin-right:6px;
      cursor:pointer;
    }
    .saveloader{
      width: 100%;
      text-align: center;
      position: relative;
    }
    .saveloader img{
      width: 100%;
    }
    .saveloader p{
     margin-top: -20px;
    }
     .savesucces{
      width: 100%;
      text-align: center;
      position: relative;
    }
    .savesucces img{
      width: 60%;
      margin-top: 10px;
      margin-bottom: 20px;
    }
    .savesucces p{
     
    }
    .elem-button{
      width: 190px;
      height: 80px;
      background: #302F37;
      color: #DADADA;
      
     
      display: inline-block;
      vertical-align: top;
      border-radius: 4px;
      margin:3px 1px;
      cursor: pointer;
      position: relative;
    }
    .qty-button,.real-qty-button{
      margin: 15px;
       width: 150px;
      height: 150px;
      background: #302F37;
      color: #DADADA;
      
     
      display: inline-block;
      vertical-align: top;
      border-radius: 4px;
     
      cursor: pointer;
      position: relative;
    }
    .qty-button p,.real-qty-button p{
      font-size: 45px!important;
       margin: 0 auto;
      width: 90%;
    text-align: center;
   font-weight: bold;
    position: relative;
    top: 50%;
    -ms-transform: translateY(-50%);
    -webkit-transform: translateY(-50%);
    transform: translateY(-50%);
    }
     .maquinamesa{
      margin: 15px;
       width: 150px;
      height: 150px;
      background: #302F37;
      color: #DADADA;
      
     
      display: inline-block;
      vertical-align: top;
      border-radius: 4px;
     
      cursor: pointer;
      position: relative;
    }
    .maquinamesa p{
      font-size: 25px!important;
       margin: 0 auto;
      width: 90%;
    text-align: center;
   font-weight: bold;
    position: relative;
    top: 80%;
    -ms-transform: translateY(-50%);
    -webkit-transform: translateY(-50%);
    transform: translateY(-50%);
    color: #fff!important;
    }
    .maquinamesa img{
      position: absolute;
      width: 70%;
     top: 40%;
        left: 50%;
        transform: translate(-50%, -50%);
    }
    .elem-button:hover{
      background:#161618;
    }
    .elem-button p {
      margin: 0 auto;
      width: 90%;
    text-align: center;
    font-size: 20px;
    position: relative;
    top: 50%;
    -ms-transform: translateY(-50%);
    -webkit-transform: translateY(-50%);
    transform: translateY(-50%);
}
.other{
  background:#1D1C21!important;
}
legend{
  padding:10px;
  color:#CECECE;
  border:none; 
}
        
@media only screen and (min-width:481px) and (max-width:768px) and (orientation: portrait) {
    .contegral{
        display:none;
    }
        body {
             background-image:none;
        }
    .msj {
    display:block;
    width: 100%;
    height: 100%;
    background-repeat: no-repeat;
    top: 40%;
    left: 10%;
    position: absolute;
    z-index:122;
    }
}
#timer2 span {
    line-height: 140px;
    font-size: 110px;
    font-weight: bold;
    color: #fff;
}
.c0{background:#CB4335 !important;}
.c1{background:#A93226 !important;}

.c2{background:#2E86C1 !important;}
.c3{background:#884EA0 !important;}

.c4{background:#2471A3 !important;}
.c5{background:#7D3C98 !important;}
.c6{background:#17A589 !important;}
.c7{background:#138D75 !important;}
.c8{background:#28B463 !important;}
.c9{background:#229954 !important;}

.c10{background:#F1C40F !important;color:#933209;}
.c11{background:#D4AC0D !important;}
.c12{background:#D68910 !important;}
.c13{background:#CA6F1E !important;}
.c14{background:#BA4A00 !important;}
.c15{background:#D0D3D4 !important;color:#4D4D4D;}
.c16{background:#A6ACAF !important;color:#4D4D4D;}
.c17{background:#839192 !important;}
.c18{background:#707B7C !important;}
.c19{background: #616A6B !important;}
.c20{background: #2E4053!important;}
.c21{background:#273746 !important;}
.c22{background:#283747 !important;}
.c23{background:#212F3D !important;}

.c24{background:#616A6B !important;}
@media screen and (min-device-width:768px) and (max-device-width:1024px) and (orientation: landscape) {
 .msj {
 display: none;
 }
}
#explain-error{
  color:red;
  font-size: 25px;
  font-weight: bold;
}
    </style>
<style>
  .panelbottom legend{
    height: 97px;
}
.elem span{
  color: #305E24;
    
    
    font-size: 12px;
    
    line-height: 14px;
    font-weight: bold;
    width: 140px;
}
</style>
<body onload="">
<div id="formulario"></div>
    <input type="hidden" id="operario" value="<?=$_SESSION['idUser'] ?>">
     <input type="hidden" id="idmachine" value="<?=$stationID ?>">
     <input type="hidden" id="idproces" value="<?=$processID ?>">
    <input type="hidden" id="order" value="<?= (isset($ordenActual))? implode(",", $ordenActual)  : ((isset($stoppedOrderID))? $stoppedOrderID : '') ;?>">
    <div class="msj">
        <img src="images/msj.fw.png" />
    </div>
         <div class="congral2">               
            <div class="cont2 center-block">
                <form name="nuevo_registro" id="nuevo_registro" method="POST">
                  <input type="hidden" name="section" value="ajuste">
                  <input type="hidden" id="is_virtual" name="is_virtual" value="<?= (!empty($orderSet['id_elemento_virtual']))? 'virtual': ((!empty($orderSet['id_orden']))? $orderSet['id_orden'] : '') ;?>">
                 <input hidden type="text" name="logged_in" id="logged_in" value="<?php echo "". $_SESSION['logged_in'] ?>" />
                <input type="hidden" id="orderID" name="numodt" class=" diseños" value="<?= (!empty($orderSet['id_elemento_virtual']))? 'virtual': ((!empty($orderSet['id_orden']))? $orderSet['id_orden'] : '') ;?>"  />
                <input type="hidden" id="orderODT" name="orderodts" class=" diseños" value="<?= (isset($activity['orden_actual']))? $activity['orden_actual']  : '' ;?>"/>
                 <input hidden type="text" name="horadeldia" id="horadeldia" value="<?php echo date("H:i:s",time()); ?>" />
                 <input hidden type="text" name="fechadeldia" id="fechadeldia" value="<?php echo date("d-m-Y"); ?>" />
                      
                    <div class="modal-content" style="">
                    <div id="actual_tiraje" style="display: none;"></div>
                        <div class="modal-header">
                            <!--<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>-->
                            <div class="text-center" style="font-size:18pt; text-transform: uppercase;">AJUSTE <?=$processName ?></div>

                            <?php if (!isset($getActODT)&&!empty($getActODT)) {?>
                            <div class="text-center2" id="currentOrder" style="font-size:18pt; color:#E9573E;">NO HAS SELECCIONADO UNA ORDEN</div>
                            <?php } else{ ?>
                              <div class="text-center2" id="currentOrder" style="font-size:18pt;">ODT EN PROCESO: <?= $activity['orden_actual']." ".$activity['nombre_elemento']  ?></div>
                           <?php } ?>
                   
                    <p id="success-msj" style="display: none;">Datos guardados correctamente</p>
                        </div>
                        <div class="modal-body">
                        <div class="button-panel" >
                        <div id="parts" class="square-button purple abajo">
                          <img src="images/elegir.png">
                        </div>
                        <div id="stop" class="square-button blue " onclick="<?=($processID==10)? 'saveAjusteSerigrafia()' : 'saveAjuste()' ?>" >
                          <img src="images/guard.png">
                        </div>
                        
                        <div class="square-button green stop eatpanel goeat" onclick="saveoperComida();">
                          <img src="images/dinner2.png">
                        </div>
                        <div class="square-button yellow  goalert" onclick="derecha();saveoperAlert();">
                          <img src="images/alerts.png">
                        </div>
                        <div class="square-button violet <?=($_SESSION['pending_exist']=='true'&& $processID==$_SESSION['proceso_cola'])? 'disabled':'' ?>" <?=($_SESSION['pending_exist']=='true'&& $processID==$_SESSION['proceso_cola'])? '':'id="change"' ?> <?=($getMyProcess->num_rows>1)? (($_SESSION['pending_exist']=='true'&& $processID==$_SESSION['proceso_cola'])? '':''):'style="display:none;"' ?> >
                          <img src="images/change.png">
                        </div>
                        
                        </div>
                        </div>

                        <div class="timer-container">
                                    <div id="chronoExample">
                                    <div id="timer" data-inicio="<?=((strtotime(date("H:i:s",time()))-strtotime($activity['inicio_ajuste']))-(((empty($activity['tiempo_alert_ajuste']))? 0: $activity['tiempo_alert_ajuste'])+((empty($activity['tiempo_comida']))? 0: $activity['tiempo_comida'])))+$tiempo_cola  ?>" data-estandar="<?=$e_ajuste['ajuste_standard'] ?>" data-perro="<?=gmdate("H:i:s",$tiempo_cola) ?>"><span class="values">00:00:00</span></div>
                                    <input type="hidden" id="elemvirtual" name="elemvirtual" value="<?= (isset($activity['nombre_elemento']))? $activity['nombre_elemento'] : '' ;?>">
                                     <input type="hidden" id="idelemvirtual" name="idelemvirtual" value="<?= (isset($activity['parte']))? $activity['parte'] : '' ;?>">
                                    <input type="hidden" id="odtvirtual" name="odtvirtual" value="<?= (isset($activity['orden_actual']))? $activity['orden_actual']  : '' ;?>">
                                    <input type="hidden" name="actual_tiro" value="<?=$activity['tiro_actual'] ?>">
                                    <input type="hidden" id="timee" name="tiempo">
                                    <input type="hidden" id="ontime" name="ontime" value="true">
                                </div>
                                <div id="chronoExample2">
                                    <div id="timer2"><span class="values">00:00:00</span></div>
                                   
                                   
                                   
                       
                                </div>
                                </div>
                        
                                         

                       

</form>

                        <div class="modal-footer">
                        <form id="pauseorder" action="opp.php" method="post">
                          <input type="hidden" value="<?= (isset($getAct['idorden']))? $getAct['idorden'] : ((isset($stoppedOrderID))? $stoppedOrderID : '') ;?>" name="numodt">
                          <input type="hidden" name="action" value="pause">
                          <input type="hidden" name="pausetime" id="pausetime">
                          <input type="hidden" name="pausetime">
                        </form>
                        
                            <div class="row "> <a href="logout.php" > 
                                 <div class="pause red"><div class="pauseicon"><img src="images/exit-door.png"></div><div class="pausetext  exit-session">SALIR</div></div></a>
                                 
                                 <div class="endOfDay blue" onclick="endOfDay()"><div class="pauseicon"><img src="images/reloj.png"></div><div class="pausetext">FIN DEL DIA</div></div>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
       <div class="backdrop"></div>
       <div class="backdrop-change"></div>
       <?php 
     
        if ($responsable['responsable_5s']=='true') {
          include '5s.php';
          if ($_SESSION['idUser']=='15') {
            
          ?>
          <script>
            var intervalHandle = setInterval(function () {
    var date = new Date();
    var cumplido='<?=$cumplido['lista_diaria'] ?>';
    console.log('date: '+cumplido);
    if ((date.getHours() >= 13 && date.getMinutes() >= 40&&cumplido=='false') ) {
      console.log('ya es tiempo');
      $('.quiz-container').show();
      clearInterval(intervalHandle);          
    } 
}, 1000);
          </script>

      <?php }
      else{ ?>

<script>
            var intervalHandle = setInterval(function () {
    var date = new Date();
    var cumplido='<?=$cumplido['lista_diaria'] ?>';
    console.log('date: '+cumplido);
    if ((date.getHours() >= 17 && date.getMinutes() >= 30&&cumplido=='false') ) {
      console.log('ya es tiempo');
      $('.quiz-container').show();
      clearInterval(intervalHandle);          
    } 
}, 1000);
          </script>
    <?php  }   }
       ?>

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
  <div class="change-confirm">
  <div class="change-cont">
    <p>¿Deseas continuar este cambio mas tarde?</p>
    <table>
      <tr>
        <td>
          <button class="save">SI</button>
        </td>
        <td>
          <button class="not-save">NO</button>
        </td>
        <td>
          <button onclick="close_change();">CANCELAR</button>
        </td>
      </tr>
    </table>
  </div>
  
  </div>
  <div class="setElement">
  <div id="elems-container" style="width: 100%;margin:0 auto; height: 100%; overflow: auto;">
  <?php 
  $c=0;
  while ($e_row=mysqli_fetch_assoc($getElementStandar)) { ?>
    <div class="elem-button <?='c'.$c ?>" data-name="<?=$e_row['nombre_elemento'] ?>" data-id="<?=$e_row['id_elemento'] ?>"><p><?=$e_row['nombre_elemento'] ?></p></div>
  <?php $c++; } ?>
  <div  class="elem-button other" data-id="144"><p>Otro</p></div>
  </div>
    
  </div>
   <div id="panelbottom">
       <div id="panelbottom2"></div> 
       <div class="row ">
                <legend style="font-size:18pt; font-family: 'monse-bold';"><div class="odtsearch">
                <div id="" style="text-transform: uppercase;line-height: 65px;width: 185px;position: absolute;left: 15px;top: 3px;"   class="rect-button-small radio-menu-small2 face" onclick="addOrder();getKeys('virtualodt','pedido')">
                          REGISTRAR 
                          <p class="suborder" >ORDEN</p>
                        </div>
  <input type="text" id="getodt" name="getodt" readonly="true" onclick="getKeys(this.id,'pedido')" onkeyup="gatODT()" placeholder="Buscar ODT"> 
</div><div id="close-down"  class="square-button-micro red abajo ">
                          <img src="images/ex.png">
                        </div></legend>
                        <p id="elementerror" style="display: none;">ELIGE UN ELEMENTO PARA CONTINUAR</p>
                        <div id="odtresult">
                          <div style="width: 95%; margin:0 auto; position: relative;">
                
                   <div class="form-group" id="tareasdiv">
                  <div class="button-panel-small2" >
                  <form id="tareas" action="opp.php" method="post" >
                  <input type="hidden" name="machine" value="<?=$stationName; ?>">
                  <input type="hidden" name="init" value="false">
                 
                  <?php
                    
                    $getodt=(isset($getActODT))? implode(",", $getActODT) : '' ;
                      $query = "  SELECT pp.*,(SELECT producto FROM ordenes WHERE idorden=pp.id_orden) AS producto,p.nombre_proceso,p.reproceso FROM personal_process pp INNER JOIN procesos p ON pp.id_proceso=p.id_proceso WHERE proceso_actual='$stationName' AND nombre_proceso='$processName' AND avance NOT IN('completado') order by orden_display asc";
                      $query2 = "  SELECT  o.idorden AS id_orden,o.numodt AS num_odt,o.fechafin,o.fechareg,o.producto,p.id_proceso,p.avance,p.reproceso,(SELECT orden_display FROM personal_process WHERE id_orden=o.idorden AND id_proceso=p.id_proceso) AS orden_display,(SELECT status FROM personal_process WHERE id_orden=o.idorden AND id_proceso=p.id_proceso) AS status FROM ordenes o LEFT JOIN procesos p ON p.id_orden=o.idorden WHERE nombre_proceso='$processName' AND o.numodt='$getodt' AND avance NOT IN('completado') order by fechafin asc LIMIT 12";
                      $initquery="SELECT COUNT(*) AS conteo FROM personal_process WHERE proceso_actual='$stationName'";
                      $initial = mysqli_fetch_assoc($mysqli->query($initquery));
                      $init=$initial['conteo'];
                      
                      $result=$mysqli->query(($init>0)? $query : $query2);
                      if (!$result) {
                        echo $query2;
                       printf($mysqli->error);
                      }
                      if ($result->num_rows==0) {
                       echo '<p style="font-size:18pt; color:#E9573E;font-family: monse-bold; text-align:center;">NO HAS SELECCIONADO UNA ORDEN<p>';
                       
                      }
                      else{
                        $i=1;
                      while ($valores=mysqli_fetch_array($result)) {
                        $prod=$valores['producto'];
                      $element_query="SELECT * FROM elementos WHERE id_elemento=$prod";
                      $get_elem=mysqli_fetch_assoc($mysqli->query($element_query));
                      $element=$get_elem['nombre_elemento'];
                     ?>
                        <div id="<?=$i ?>" data-name="<?=$get_elem['nombre_elemento'] ?>" data-element="<?=$get_elem['id_elemento'] ?>" style="text-transform: uppercase;"  class="rect-button-small radio-menu-small face   <?=($valores['status']=='actual')? 'face-osc': '' ; ?>" onclick="showLoad();">
                       


                        <input type="checkbox" <?=($valores['status']=='actual')? 'checked': '' ; ?> name="chosen" value="<?=$valores['id_proceso']; ?>">
                        <input type="hidden" name="products[<?=$valores['id_proceso'] ?>]" value="<?=$valores['producto']; ?>">
                        <input type="hidden" name="odetes[<?=$valores['id_proceso'] ?>]" value="<?=$valores['num_odt']; ?>">
                       <input type="hidden" name="ordenes[<?=$valores['id_proceso'] ?>]"  value="<?=$valores['id_orden'] ?>">
                       <input type="hidden" name="procesos[<?=$valores['id_proceso'] ?>]"  value="<?=$valores['id_proceso'] ?>">



                          <p class="elem" <?=($element=='Desconocido')? 'style="font-size:15px;"':''; ?> ><?php echo  trim($element); ?><br><span><?= $valores['reproceso']?></span></p>
                          <p class="product" style="display: none;"><?= $valores['num_odt']?></p>
                        </div>
                        
                        <?php $i++; } }?>
                          </form>
                        </div> 
                </div>
                </div>
                        </div>
                
                   <div id="resultaado"></div> 
                <div class="form-group">
                <div id="resultaado"></div>
                 
                </div>
   </div></div>
   <div id="panelder">
   <div id="panelder2"></div>
      <div class="container-fluid">
          <div id="estilo">
             <form id="fo4" name="fo4" action="saveAjsute.php" method="post" class="form-horizontal" onSubmit="return limpiar()" >
                <fieldset>
                <input hidden type="text" name="tiempoalertamaquina" id="tiempoalertamaquina" />
                <input hidden type="text"  name="logged_in" id="logged_in" value="<?php echo "". $_SESSION['logged_in'] ?>" />
                <input hidden  name="horadeldiaam" id="horadeldiaam" value="<?=date(" H:i:s",time()); ?>" />
                <input hidden   id="segundosdeldia" value="<?= strtotime(date(" H:i:s",time())) - strtotime('TODAY'); ?>" />
                <input hidden name="fechadeldiaam" id="fechadeldiaam" value="<?php echo date("d-m-Y"); ?>" />
                <input  hidden name="maquina" id="maquina" value=""  />
                 <!-- Form Name -->
                <legend style="font-size:18pt; font-family: 'monse-bold';">ALERTA AJUSTE</legend>
               <div class="form-group" style="width:81% ;margin:0 auto;">
               <input type="hidden" id="inicioAlerta" name="inicioAlerta">
                <label class="col-md-4 control-label" for="radios" style="display: none;"></label>
                <?php include 'ajuste_options.php' ?>
               
                </div>
                <!-- Textarea -->
                <div class="form-group" style="width:81%;margin:0 auto; text-align: center; color:black;">
                    <textarea placeholder="Observaciones.." class="comments" id="observaciones" name="observaciones"></textarea>
                    <p id="explain-error" style="display: none;">Porfavor agrega una explicacion ↑</p>
                
                </div>
                <!-- Button (Double) -->
                
               </fieldset>
             </form>
    
    <div  style="width: 60%; display: inline-block;vertical-align: top">
      <div class="reloj-container2">  
        <div class="timersmall">
                                    <div id="alertajuste">
                                    <div id="timersmall"><span class="valuesAlert">00:00:00</span></div>
                                </div>
                                </div>
    </div>
    
    </div><div  style="width: 20%; display: inline-block;vertical-align: top">
      <div class="form-group">
                  <div class="button-panel-small">
                       
                        <div style="display: none;" class="square-button-micro2 red derecha stopalert start reset" onclick="saveOperstatus()">
                          <img src="images/ex.png">
                        </div>
                        <div id="save-ajuste" class="square-button-micro2   blue" onclick="saveOperstatus();">
                          <img src="images/saving.png">
                        </div>
                        
                          
                        </div>
                </div>
    </div>
      </div>
   </div>
    </div>
    <div id="panelbrake">
    <div id="panelbrake2"></div>
      <div class="container-fluid">
          <div id="estilo">
             <form id="fo3" name="fo3" action="saveeat.php" method="post" class="form-horizontal" onSubmit=" return limpiar();" >
                <fieldset style="position: relative;left: -15px;"> 
                <input type="hidden" id="act_tiro" name="act_tiro" value="<?=$activity['tiro_actual'] ?>">
                <input type="hidden" name="curr-section" value="ajuste">  
                <input type="hidden" id="inicioAlertaEat" name="inicioAlertaEat">             
                <input hidden type="text"  name="logged_in" id="logged_in" value="<?php echo "". $_SESSION['logged_in'] ?>" />
                <input hidden name="horadeldiaam" id="horadeldiaeat" value="<?php echo date(" H:i:s",time()); ?>" />
                 <input type="hidden" id="inicioAlerta" name="inicioAlerta">
                <input hidden name="fechadeldiaam" id="fechadeldiaam" value="<?php echo date("d-m-Y"); ?>" />
                <input hidden name="maquina" id="maquina" value="<?=$stationName?>"  />
                  <!-- Form Name -->
                 <legend style="font-size:18pt; font-family: 'monse-bold';"></legend>
                
                   <input type="hidden" id="timeeat" name="breaktime">
                   <!-- Multiple Radios (inline) -->
                   <div class="form-group" style="width:80% ;margin:60px auto;">
                <label class="col-md-4 control-label" for="radios" style="display: none;"></label>
                <input type="hidden" id="s-radios" name="radios">
              <div class="radio-menu face eatpanel" onclick="submitEat('Comida');showLoad();saveOperstatus();">
                <input type="radio" class=""  id="radios-0"  >
                    COMIDA</div>
               <div class="radio-menu face eatpanel" onclick="submitEat('Sanitario');showLoad();saveOperstatus();">
               <input type="radio"  id="radios-1" >
                   SANITARIO
                    </div>
                    <!-- 
                    <div class="radio-menu face eatpanel" onclick="submitEat('Otro');showLoad();">
                <input type="radio" class=""  id="radios-3"  >
                    OTRA ACTIVIDAD</div> -->

                </div>
                <div class="form-group" style="text-align: center; color:black;">
                    <textarea style="display: none;" placeholder="Especifique.." name="specific" id="specific"></textarea>
                
                </div>
                   </br>
                   </br>
                   </br>
                <!-- Button (Double) -->
                
               </fieldset>    
                
             </form>
      <div class="reloj-container2">
    <div class="timersmall">
                                    <div id="horacomida">
                                    <div id="timersmall"><span class="valuesEat">00:00:00</span></div> 
                                </div>
                                </div>
    </div>
    </div>
   </div>
</div>
   
  <!-- ********************** Inicia Panel teclado ******************** -->
   <div id="panelkeyboard2">
    
    <div class="keycontainer">
      <div id="softk" class="softkeys" data-target="input[name='getodt']"></div>
    </div>
    
      <div id="close-down-key" class="square-button-micro red " style="display: none;">
                          <img src="images/ex.png">
                        </div>
    
    
</div>

<!-- ********************** Termina Panel teclado ******************** --> 


</body>
</html>

<!-- *********************** END CONTENIDO ********************** -->

<?php
 }else{
            printf("Errormessage: %s\n", $mysqli->error);
          }

        }
?>

<?php 
 require('saves/conexion.php');

$datos=(isset($_POST["datos"]) ? $_POST["datos"] : null);


if (!empty($update)) {
 $mysqli->query(isset($update));
}
  
?>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script>
  function addOrder(){
//$("#panelkeyboard2").animate({ bottom: '-=58%' }, 200);     
 // kb=false;
  var form='<form id="virtualform"><div class="vform"><p>Numero de ODT:</p>'+
  ' <input type="hidden" name="entorno" value="virtual">'+
  ' <input type="hidden" name="machine" value="<?=$stationName; ?>">'+
  ' <input type="text" readonly required="true" name="virtualodt" id="virtualodt" >'+
  '<p id="podt" style="display:none">Rellena este campo</p></div>'+
  '<div class="vform"><p>Parte:</p>'+
            '<input type="text" readonly required="true" name="virtualelem" id="virtualelem" >'+
            
            '<input type="hidden"  name="idelem" id="idelem" >'+
            '<p id="pelem" style="display:none">Rellena este campo</p></div>'+
           
            '<input type="button" id="saving" style="display: none;"></form>';


   $('#odtresult').html(form);
   $('#virtualodt').focus();
}
/*
$(document).ready(function() { 
var hora=$('#horadeldia').val();
var fecha=$('#fechadeldia').val();
var segundosdeldia=$('#segundosdeldia').val();


  if (localStorage.getItem('horaincio')&&localStorage.getItem('station')==$('#idmachine').val()&&localStorage.getItem('process')==$('#idproces').val()) {
   
    console.log('No se insertara un nuevo tiro porque ya existe');
    var tiroactual='<input type="hidden" name="actual_tiro" id="actual_tiro" value="'+localStorage.getItem('tiroactual')+'">';
   $('#actual_tiraje').html(tiroactual);
   $('#horadeldia').val(localStorage.getItem('horaincio'));
  }else{
    $.ajax({  
                      
                     type:"POST",
                     url:"init_tiro.php",   
                     data:{hora:hora,fecha:fecha,init:'init'},  
                       
                     success:function(data){ 
                       $('#actual_tiraje').html(data);
                       $('#tiro').html(data);
                       console.log(data);
                        var utc = new Date().toJSON().slice(0,10).replace(/-/g,'/');
                       var tiroactual=$('#actual_tiro').val();
                       localStorage.setItem('fecha', utc);
                       
                       
                       localStorage.setItem('segundosincio', segundosdeldia);  
                       localStorage.setItem('tiroactual', tiroactual); 
                       $('#act_tiro').val(tiroactual);
                     }  
                });
  } 



 });*/
 

 $(document).ready(function() { 
  window.ParentFunction = function ParentFunction(){
    $.ajax({  
                      
                     type:"POST",
                     url:"operstatus.php",   
                     data:{section:'5s'},  
                       
                     success:function(data){ 
                       //$('#elems-container').html(data);
                    
                     }  
                });
       
    setTimeout(function(){$('.quiz-container').hide(); }, 1500);
    
    } 
  
});


function endOfDay(){
  var lastiro=$('#actual_tiro').val();
  var now = new Date();
  var hour = now.getHours();
  var day = now.getDay();
  var minutes = now.getMinutes();
  if(hour >= 10){
     window.location.replace("resume.php?tiro="+lastiro);
  }else{
  alert('Favor de picarle aqui despues de las 6pm');
}
      
}

$(document).on("click", "#virtualelem", function () {
    selectElement();
});



$(document).on("click", ".other", function () {
  $("#virtualelem").attr("placeholder", "Escribe la parte..");
  $("#idelem").val('144');
    getKeys('virtualelem','cosa');
});




$(document).on("click", ".elem-button", function () {
  var id=$(this).data("id");
  var name=$(this).data("name");
  if (id==17) {
    var planillas='<br><br><br><br><br><br><p style="font-size:25px;font-weight: bold;">PLANILLAS DE:</p>'+
    '<div class="qty-button" data-id="17" data-name="Boleto" data-plans="1"><p>1</p></div>'+
    '<div class="qty-button" data-id="17" data-name="Boleto" data-plans="2"><p>2</p></div>'+
    '<div class="qty-button" data-id="17" data-name="Boleto" data-plans="3"><p>3</p></div>'+
    '<div class="qty-button" data-id="17" data-name="Boleto" data-plans="4"><p>4</p></div>'+
    '<div class="qty-button" data-id="17" data-name="Boleto" data-plans="5"><p>5</p></div>'+
    '<div class="qty-button" data-id="17" data-name="Boleto" data-plans="6"><p>6</p></div>'+
'<div class="qty-button" data-id="17" data-name="Boleto" data-plans="7"><p>7</p></div>'+
    '<div class="qty-button" data-id="17" data-name="Boleto" data-plans="8"><p>8</p></div>'+
    '<div class="qty-button" data-id="17" data-name="Boleto" data-plans="9"><p>9</p></div>'+
    '<div class="qty-button" data-id="17" data-name="Boleto" data-plans="10"><p>10</p></div>'+
    '<div class="qty-button" data-id="17" data-name="Boleto" data-plans="12"><p>12</p></div>';
    $('#elems-container').html(planillas);
   
  }
  else if (id==84) {
    var planillas='<br><br><br><br><br><br><p style="font-size:25px;font-weight: bold;">PLANILLAS DE:</p>'+
    '<div class="qty-button" data-id="84" data-name="Mapa" data-plans="1"><p>1</p></div>'+
    '<div class="qty-button" data-id="84" data-name="Mapa" data-plans="2"><p>2</p></div>'+
    '<div class="qty-button" data-id="84" data-name="Mapa" data-plans="3"><p>3</p></div>'+
    '<div class="qty-button" data-id="84" data-name="Mapa" data-plans="4"><p>4</p></div>'+
    '<div class="qty-button" data-id="84" data-name="Mapa" data-plans="5"><p>5</p></div>'+
    '<div class="qty-button" data-id="84" data-name="Mapa" data-plans="6"><p>6</p></div>'+
   '<div class="qty-button" data-id="84" data-name="Mapa" data-plans="7"><p>7</p></div>'+
    '<div class="qty-button" data-id="84" data-name="Mapa" data-plans="8"><p>8</p></div>'+
    '<div class="qty-button" data-id="84" data-name="Mapa" data-plans="9"><p>9</p></div>'+
    '<div class="qty-button" data-id="84" data-name="Mapa" data-plans="10"><p>10</p></div>'+
    '<div class="qty-button" data-id="84" data-name="Mapa" data-plans="11"><p>11</p></div>'+
    '<div class="qty-button" data-id="84" data-name="Mapa" data-plans="12"><p>12</p></div>';
    $('#elems-container').html(planillas);
   
  }else if (id==123||id==124||id==125||id==4||id==8||id==25) {
    var planillas='<br><br><br><br><br><br><p style="font-size:25px;font-weight: bold;">PLANILLAS DE:</p>'+
    '<div class="qty-button" data-id="'+id+'" data-name="'+name+'" data-plans="1"><p>1</p></div>'+
    '<div class="qty-button" data-id="'+id+'" data-name="'+name+'" data-plans="2"><p>2</p></div>'+
     '<div class="qty-button" data-id="'+id+'" data-name="'+name+'" data-plans="3"><p>3</p></div>'+
    '<div class="qty-button" data-id="'+id+'" data-name="'+name+'" data-plans="4"><p>4</p></div>'+
    '<div class="qty-button" data-id="'+id+'" data-name="'+name+'" data-plans="5"><p>5</p></div>'+
    '<div class="qty-button" data-id="'+id+'" data-name="'+name+'" data-plans="6"><p>6</p></div>'+
     '<div class="qty-button" data-id="'+id+'" data-name="'+name+'" data-plans="7"><p>7</p></div>'+
    '<div class="qty-button" data-id="'+id+'" data-name="'+name+'" data-plans="8"><p>8</p></div>'+
     '<div class="qty-button" data-id="'+id+'" data-name="'+name+'" data-plans="9"><p>9</p></div>'+
    '<div class="qty-button" data-id="'+id+'" data-name="'+name+'" data-plans="10"><p>10</p></div>'+
     '<div class="qty-button" data-id="'+id+'" data-name="'+name+'" data-plans="11"><p>11</p></div>'+
    '<div class="qty-button" data-id="'+id+'" data-name="'+name+'" data-plans="12"><p>12</p></div>';
    $('#elems-container').html(planillas);
   
  }else{
     $('#virtualelem').val(name);
$('#idelem').val(id);
  
    close_Elements();
  }
 

});

$(document).ready(function () {
  var ismobile= isMobileDevice();
    var link = document.getElementById('styles2');
    if (ismobile==false) {
      link.setAttribute('href', 'css/general-styles-monitor.css');
    }

});

    


function isMobileDevice() {
    return (typeof window.orientation !== "undefined") || (navigator.userAgent.indexOf('IEMobile') !== -1);
}

$(document).on("click", ".real-qty-button", function () {

 var id=$(this).data("id");

  var name=$(this).data("name");
  var plans=$(this).data("plans");
   console.log('hola: '+plans);
  $('#plans').remove();
      $('#tareas').append('<input type="hidden" name="plans" id="plans" value="'+plans+'">');
    close_Elements();
    sendOrder();
    $('#close-down').click(); 

$.ajax({  
                      
                     type:"POST",
                     url:"mosaico.php",   
                     data:{processID:<?=$processID ?>},  
                       
                     success:function(data){ 
                       $('#elems-container').html(data);
                    
                     }  
                });
  });
$(document).on("click", ".qty-button", function () {
  var id=$(this).data("id");
  var name=$(this).data("name");
  var plans=$(this).data("plans");
   
    $('#virtualelem').val(name);
    $('#idelem').val(id);
    $('#plans').remove();
    $('#virtualform').append('<input type="hidden" name="plans" id="plans" value="'+plans+'">');
    close_Elements();
$.ajax({  
                      
                     type:"POST",
                     url:"mosaico.php",   
                     data:{processID:<?=$processID ?>},  
                       
                     success:function(data){ 
                       $('#elems-container').html(data);
                    
                     }  
                });

});

$(document).on("click", "#observaciones", function () {
  if (isMobileDevice()==false) {
    getKeys(this.id,'observaciones');
  }

});


function close_change()
      {
        $('.backdrop-change, .change-confirm').animate({'opacity':'0'}, 300, 'linear', function(){
          $('.backdrop-change, .change-confirm').css('display', 'none');
        });
      }

$(document).on("click", ".save", function () {
    var ontime=$('#ontime').val();

  if (ontime=='true') {
        timer.pause();
    $('#timee').val(timer.getTimeValues().toString());
    var tiempo=$('#timee').val();
  }else{
    deadTimer.pause();
    $('#timee').val(deadTimer.getTimeValues().toString());
    var tiempo=$('#timee').val();
  }

  $.ajax({        
                     type:"POST",
                     url:"changeActivity.php",   
                     data:{save_change:"true",ontime:ontime,tiempo:tiempo,tiro:<?=$activity['tiro_actual'] ?>,section:'ajuste',},  
                       
                     success:function(data){ 
                      console.log(data);
                     window.location.replace("optionsPanel.php");
                     }  
  });
  
});
$(document).on("click", ".not-save", function () {
    var ontime=$('#ontime').val();

  if (ontime=='true') {
        timer.pause();
    $('#timee').val(timer.getTimeValues().toString());
    var tiempo=$('#timee').val();
  }else{
    deadTimer.pause();
    $('#timee').val(deadTimer.getTimeValues().toString());
    var tiempo=$('#timee').val();
  }

  $.ajax({        
                     type:"POST",
                     url:"changeActivity.php",   
                     data:{save_change:"false",ontime:ontime,tiempo:tiempo,tiro:<?=$activity['tiro_actual'] ?>,section:'ajuste',},  
                       
                     success:function(data){ 
                      console.log(data);
                     window.location.replace("optionsPanel.php");
                     }  
  });
  
});

       
$(document).on("click", "#change", function () {
  $('.backdrop-change, .change-confirm').animate({'opacity':'.50'}, 300, 'linear');
  $('.change-confirm').animate({'opacity':'1.00'}, 300, 'linear');
  $('.backdrop-change, .change-confirm').css('display', 'block');
});

</script>
<script src="js/softkeys-0.0.1.js?v=2"></script>
<script src="js/ajuste.js?v=37"></script>
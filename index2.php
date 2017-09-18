

<?php
ini_set("session.gc_maxlifetime","7200");  
date_default_timezone_set("America/Mexico_City");
 if( !session_id() )
    {
        session_start();
    }
    if(@$_SESSION['logged_in'] != true){
      echo '
     <script>
        alert("No has iniciado sesion");
        self.location.replace("index.php");
    </script>';
    }else{
      if (isset($_COOKIE['tiraje'])){
    setcookie('tiraje', true,  time()-3600);
    unset ($_COOKIE['tiraje']);
    }
  if (!isset($_COOKIE['ajuste'])){
    setcookie('ajuste', true,  time()+1800);
    }        
require('saves/conexion.php');


//cuando cierran sesion
$ip=getenv("REMOTE_ADDR"); 
$cmd = "arp  $ip | grep $ip | awk '{ print $3 }'"; 
$recoverSession=(!empty($_POST))? 'false' : 'true' ;

$mac=(isset($_SESSION['mac']))?$_SESSION['mac'] : system($cmd) ;

$machineName=$_SESSION['machineName'];
$machineID = $_SESSION['machineID'];


  
  $pausedOrder=$mysqli->query("SELECT *,TIME_TO_SEC(tiempo_pausa) AS seconds FROM procesos WHERE  nombre_proceso='$machineName' AND avance='en pausa'");
  //verificar si hay una orden en pausa 
  if ($pausedOrder->num_rows>0) {
     $getOrder = mysqli_fetch_assoc($pausedOrder);
    $getActODT[] = $getOrder['numodt'];
    $ordenActual[] = $getOrder['id_orden'];
    header('Location:http:'.dirname($_SERVER['PHP_SELF']).'/index3.php');
    echo "<script>console.log('orden pausa');</script>";

  }else{

  $retaking=$mysqli->query("SELECT *,TIME_TO_SEC(tiempo_pausa) AS seconds FROM procesos WHERE  nombre_proceso='$machineName' AND avance='retomado'");
    
    if ($retaking->num_rows>0) {
      $getOrder = mysqli_fetch_assoc($retaking);
    $getActODT[] = $getOrder['numodt'];
    $ordenActual[] = $getOrder['id_orden'];
    echo "<script>console.log('orden retomada');</script>";


    } else{

      $queryOrden="SELECT * FROM odt_flujo WHERE status='actual' AND proceso='$machineName'";
      $asoc=$mysqli->query($queryOrden);
     
      while($get_Act=mysqli_fetch_assoc($asoc)){
       
      $getID=mysqli_fetch_assoc($mysqli->query("SELECT pp.*,(SELECT producto FROM ordenes WHERE idorden=pp.id_orden) AS element,(SELECT nombre_elemento FROM elementos WHERE id_elemento=element) AS nom_element FROM personal_process pp WHERE proceso_actual='$machineName' AND status='actual'"));
        $getActODT[] = $get_Act['numodt'];
        $ordenActual[] =(isset($getID['id_orden']))? $getID['id_orden'] : '';
        $parteDeOrden=(isset($getID['nom_element']))? $getID['nom_element'] : '';


}
    echo "<script>console.log('orden normal');</script>";


    }
  }





$p=1;
if ( $p==1) {

?>
<!-- *********************** CONTENIDO ********************* -->
<!DOCTYPE html>

<html>
<?php include 'head.php'; ?>
<style>
  legend{
    height: 97px;
}
</style>
<body onload="setTimeout('alerttime()',2000000);">
<div id="formulario"></div>
    <input type="hidden" id="mac" value="<?=$mac ?>">
    <input type="hidden" id="order" value="<?= (isset($ordenActual))? implode(",", $ordenActual)  : ((isset($stoppedOrderID))? $stoppedOrderID : '') ;?>">
    <div class="msj">
        <img src="images/msj.fw.png" />
    </div>
         <div class="congral2">               
            <div class="cont2 center-block">
                <form name="nuevo_registro" id="nuevo_registro" method="POST">
                  <input type="hidden" name="section" value="ajuste">
                 <input hidden type="text" name="logged_in" id="logged_in" value="<?php echo "". $_SESSION['logged_in'] ?>" />
                <input type="hidden" id="orderID" name="numodt" class=" diseños" value="<?= (isset($ordenActual))? implode(",", $ordenActual)  : ((isset($stoppedOrderID))? $stoppedOrderID : '') ;?>"/>
                <input type="hidden" id="orderODT" name="orderodts" class=" diseños" value="<?= (isset($getActODT))? implode(",", $getActODT)  : '' ;?>"/>
                 <input hidden type="text" name="horadeldia" id="horadeldia" value="<?php echo date("H:i:s",time()); ?>" />
                 <input hidden type="text" name="fechadeldia" id="fechadeldia" value="<?php echo date("d-m-Y"); ?>" />
                     <input hidden type="text" name="recover" value="<?php echo $recoverSession; ?>" />  
                    <div class="modal-content" style="">
                        <div class="modal-header">
                            <!--<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>-->
                            <div class="text-center" style="font-size:18pt; text-transform: uppercase;">AJUSTE <?php echo (isset($machineName))? $machineName : $mrecovered ; ?></div>
                            <?php if (!isset($getActODT)) {?>
                            <div class="text-center2" id="currentOrder" style="font-size:18pt; color:#E9573E;">NO HAS SELECCIONADO UNA ORDEN</div>
                            <?php } else{ ?>
                              <div class="text-center2" id="currentOrder" style="font-size:18pt;">EN PROCESO: <?= implode(",", $getActODT)." ".$parteDeOrden  ?></div>
                           <?php } ?>
                   
                    <p id="success-msj" style="display: none;">Datos guardados correctamente</p>
                        </div>
                        <div class="modal-body">
                        <div class="button-panel" >
                        <div id="parts" class="square-button purple abajo">
                          <img src="images/elegir.png">
                        </div>
                        <div id="stop" class="square-button blue " onclick="saveAjuste()" >
                          <img src="images/guard.png">
                        </div>
                        <div class="square-button yellow derecha goalert">
                          <img src="images/alerts.png">
                        </div>
                        <div class="square-button green stop eatpanel goeat">
                          <img src="images/other.png">
                        </div>
                        
                        
                        
                        </div>
                        </div>
                        <div class="timer-container">
                                    <div id="chronoExample">
                                    <div id="timer"><span class="values">00:00:00</span></div>
                                    
                                    <input type="hidden" id="timee" name="tiempo">
                                    
                                </div>
                                </div>
                        
                          <?php
                          $valorQuePasa3 = (isset($mac))? $mac : $recoverMac; // variable que viene de otra pagina por el metodo get
                           $valorQuePasa4 = (isset($mac))? $mac : $mrecoveredId;
                            $valorQuePasa5 = (isset($mac))? $mac : $mrecoveredId;
                          ?>                

                         <input hidden name="nommaquina" id="nommaquina" value="<?php echo $valorQuePasa3; ?>"  />

</form>

                        <div class="modal-footer">
                        <form id="pauseorder" action="opp.php" method="post">
                          <input type="hidden" value="<?= (isset($getAct['idorden']))? $getAct['idorden'] : ((isset($stoppedOrderID))? $stoppedOrderID : '') ;?>" name="numodt">
                          <input type="hidden" name="action" value="pause">
                          <input type="hidden" name="pausetime" id="pausetime">
                          <input type="hidden" name="pausetime">
                        </form>
                            <div class="row "> <a href="logout.php" > 
                                 <div class="pause red"><div class="pauseicon"><img src="images/exit-door.png"></div><div class="pausetext">SALIR</div></div></a>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
       <div class="backdrop"></div>

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
   <div id="panelbottom">
       <div id="panelbottom2"></div> 
       <div class="row ">
                <legend style="font-size:18pt; font-family: 'monse-bold';"><div class="odtsearch">
  <input type="text" id="getodt" onkeyup="gatODT()" placeholder="Buscar ODT"> 
</div><div id="close-down"  class="square-button-micro red abajo ">
                          <img src="images/ex.png">
                        </div></legend>
                        <p id="elementerror" style="display: none;">ELIGE UN ELEMENTO PARA CONTINUAR</p>
                        <div id="odtresult">
                          <div style="width: 95%; margin:0 auto; position: relative;">
                
                   <div class="form-group" id="tareasdiv">
                  <div class="button-panel-small2" >
                  <form id="tareas" action="opp.php" method="post" >
                  <input type="hidden" name="machine" value="<?=$machineName; ?>">
                  
                 
                  <?php
                    $getodt=(isset($getActODT))? implode(",", $getActODT) : $_GET['odt'] ;
                      $query = "  SELECT pp.*,(SELECT producto FROM ordenes WHERE idorden=pp.id_orden) AS producto FROM personal_process pp WHERE proceso_actual='$machineName' order by orden_display asc";
                      $query2 = "  SELECT  o.idorden AS id_orden,o.numodt AS num_odt,o.fechafin,o.fechareg,o.producto,p.id_proceso,p.avance,(SELECT orden_display FROM personal_process WHERE id_orden=o.idorden AND id_proceso=p.id_proceso) AS orden_display,(SELECT status FROM personal_process WHERE id_orden=o.idorden AND id_proceso=p.id_proceso) AS status FROM ordenes o LEFT JOIN procesos p ON p.id_orden=o.idorden WHERE nombre_proceso='$machineName' AND o.numodt='$getodt' AND avance NOT IN('completado') order by fechafin asc LIMIT 12";
                      $initquery="SELECT COUNT(*) AS conteo FROM personal_process WHERE proceso_actual='$machineName'";
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
                      $element_query="SELECT nombre_elemento FROM elementos WHERE id_elemento=$prod";
                      $get_elem=mysqli_fetch_assoc($mysqli->query($element_query));
                      $element=$get_elem['nombre_elemento'];
                     ?>
                        <div id="<?=$i ?>" style="text-transform: uppercase;"  class="rect-button-small radio-menu-small face abajo   <?=($valores['status']=='actual')? 'face-osc': '' ; ?>" onclick="showLoad(); selectOrders(this.id,'<?=$valores['num_odt'] ?>')">
                        <input type="checkbox" <?=($valores['status']=='actual')? 'checked': '' ; ?> name="odetes[]" value="<?=$valores['num_odt']; ?>">
                        <input type="checkbox" <?=($valores['status']=='actual')? 'checked': '' ; ?> name="datos[]"  value="<?=$valores['id_orden'] ?>"  >
                        
                        
                        <input type="checkbox" <?=($valores['status']=='actual')? 'checked': '' ; ?> name="idpro[]"  value="<?=$valores['id_proceso'] ?>"  >
                          <p class="elem" ><?php echo  trim($element); ?></p>
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
                <input hidden  name="horadeldiaam" id="horadeldiaam" value="<?php echo date(" H:i:s",time()); ?>" />
                <input hidden name="fechadeldiaam" id="fechadeldiaam" value="<?php echo date("d-m-Y"); ?>" />
                <input  hidden name="maquina" id="maquina" value="<?php echo $valorQuePasa4; ?>"  />
                 <!-- Form Name -->
                <legend style="font-size:18pt; font-family: 'monse-bold';">ALERTA AJUSTE</legend>
               <div class="form-group" style="width:80% ;margin:0 auto;">
                <label class="col-md-4 control-label" for="radios" style="display: none;"></label>
                <?php if ($_SESSION['machineName']=='Serigrafia') { ?>
               

                <div class="two-columns">
                  <div class=" radio-menu face">
                    <input type="radio" name="radios" id="radios-0" value="Preparar Tinta">
                    Preparar Tinta
                    </div>
                <div class=" radio-menu face">
                    <input type="radio" name="radios" id="radios-1" value="Impresion Mesa">
                    Impresion Mesa
                    </div>
                </div>
                <div class="two-columns">
                <div class=" radio-menu face">
                    <input type="radio" name="radios" id="radios-2" value="Marco mal revelado">
                    Marco mal revelado
                    </div>
                <div class=" radio-menu face">
                    <input type="radio" name="radios" id="radios-3" value="Marco con poro">
                    Marco con poro
                    </div>
                
                    
                </div>
                <div class="two-columns">
                  <div class=" radio-menu face">
                    <input type="radio" name="radios" id="radios-4" value="ODT confusa">
                    ODT confusa
                    </div>
                    <div class=" radio-menu face">
                    <input type="radio" name="radios" id="radios-5" value="Tirar basura">
                    Tirar basura
                    </div>
                </div>
                <div class="two-columns">
                  <div class=" radio-menu face">
                    <input type="radio" name="radios" id="radios-6" value="Otro">
                    Otro
                    </div>
                </div>
                <?php }else{ ?>
                 <div class="two-columns">
                  <div class=" radio-menu face">
                    <input type="radio" name="radios" id="radios-0" value="ODT Confusa">
                    ODT Confusa
                    </div>
                <div class=" radio-menu face">
                    <input type="radio" name="radios" id="radios-1" value="ODT Faltante">
                    ODT Faltante
                    </div>
                     <div class=" radio-menu face">
                    <input type="radio" name="radios" id="radios-6" value="Otro">
                    Otro
                    </div>
                </div>
                <div class="two-columns">
                <div class=" radio-menu face">
                    <input type="radio" name="radios" id="radios-2" value="Cambio de Cuchilla">
                    Cambio de Cuchilla
                    </div>
                <div class=" radio-menu face">
                    <input type="radio" name="radios" id="radios-3" value="Pieza de Plancha">
                    Pieza de Plancha
                    </div>
                <div class=" radio-menu face">
                    <input type="radio" name="radios" id="radios-4" value="Exceso de Dimensiones">
                    Exceso de Dimensiones
                    </div>

                </div>
                <?php } ?>
                </div>
                <!-- Textarea -->
                <div class="form-group" style="text-align: center; color:black;">
                    <textarea placeholder="Observaciones.." class="comments" id="observaciones" name="observaciones"></textarea>
                
                </div>
                <!-- Button (Double) -->
                <div class="form-group">
                  <div class="button-panel-small">
                       
                        <div class="square-button-small red derecha stopalert start reset">
                          <img src="images/ex.png">
                        </div>
                        <div id="save-ajuste" class="square-button-small derecha  blue" onclick="showLoad();">
                          <img src="images/saving.png">
                        </div>
                        
                          
                        </div>
                </div>
               </fieldset>
             </form>
    <div class="reloj-container2">  
        <div class="timersmall">
                                    <div id="alertajuste">
                                    <div id="timersmall"><span class="valuesAlert">00:00:00</span></div>
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
                <input hidden type="text"  name="logged_in" id="logged_in" value="<?php echo "". $_SESSION['logged_in'] ?>" />
                <input hidden name="horadeldiaam" id="horadeldiaam" value="<?php echo date(" H:i:s",time()); ?>" />
                <input hidden name="fechadeldiaam" id="fechadeldiaam" value="<?php echo date("d-m-Y"); ?>" />
                <input hidden name="maquina" id="maquina" value="<?php echo $valorQuePasa5; ?>"  />
                  <!-- Form Name -->
                 <legend style="font-size:18pt; font-family: 'monse-bold';">Comida</legend>
                
                   <input type="hidden" id="timeeat" name="breaktime">
                   <!-- Multiple Radios (inline) -->
                   <div class="form-group" style="width:80% ;margin:0 auto;">
                <label class="col-md-4 control-label" for="radios" style="display: none;"></label>
                <input type="hidden" id="s-radios" name="radios">
              <div class="radio-menu face eatpanel" onclick="submitEat('Comida');showLoad();">
                <input type="radio" class=""  id="radios-0"  >
                    COMIDA</div>
               <div class="radio-menu face eatpanel" onclick="submitEat('Sanitario');showLoad();">
               <input type="radio"  id="radios-1" >
                   SANITARIO
                    </div>
                </div>
                   </br>
                   </br>
                   </br>
                <!-- Button (Double) -->
                <div class="form-group">
                  <div class="button-panel-small">
                        <div   class="square-button-small red eatpanel stopeat start reseteat2 ">
                          <img src="images/ex.png">
                        </div>
                        </div>
                </div>
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

<script src="js/ajuste.js"></script>
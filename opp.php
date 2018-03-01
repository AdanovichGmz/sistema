<?php 
require('classes/functions.class.php');
session_start();
  $log = new Functions();
 require('saves/conexion.php');
$entorno=(isset($_POST["entorno"]))? $_POST["entorno"] : 'personal';
$stationName=$_SESSION['stationName'];
$stationID=$_SESSION['stationID'];
$maqID=$_SESSION["processID"]; 
$processName=$_SESSION["processName"]; 
$today=date("d-m-Y");
if ($entorno=='general') {
  $datos=(isset($_POST["odt"]))? explode(",",$_POST["odt"] ):''; 

$getodt=$_POST["odt"];

$old_odt=mysqli_fetch_assoc($mysqli->query("SELECT num_odt FROM personal_process WHERE proceso_actual='$maqID' AND status='actual' "));

?> <form id="tareas" action="opp.php" method="post" >
                  <input type="hidden" name="machine" value="<?=$stationName; ?>">
                  <input type="hidden" name="init" value="true">
                <input type="hidden" name="anterior" value="<?=$old_odt['num_odt'] ?>">
                 <input type='hidden' id='returning' name="returning" value="<?=implode(",", $datos); ?>">
                 
                  <?php
                    
                     $process=$_SESSION['processName'];
                    $q="SELECT  o.idorden AS id_orden,o.numodt AS num_odt,o.fechafin,o.fechareg,o.producto,p.id_proceso,p.avance,p.reproceso,(SELECT orden_display FROM personal_process WHERE id_orden=o.idorden AND id_proceso=p.id_proceso) AS orden_display,(SELECT status FROM personal_process WHERE id_orden=o.idorden AND id_proceso=p.id_proceso) AS status FROM ordenes o LEFT JOIN procesos p ON p.id_orden=o.idorden WHERE nombre_proceso='$process' AND o.numodt='$getodt' AND avance NOT IN('completado') order by fechafin asc LIMIT 12";
                      $query = $mysqli->query($q);

                      $i=1;
                      
                      while ($valores = mysqli_fetch_array($query)) {
                        
                      $prod=$valores['producto'];
                      $element_query="SELECT * FROM elementos WHERE id_elemento=$prod";
                      $get_elem=mysqli_fetch_assoc($mysqli->query($element_query));
                      $element=$get_elem['nombre_elemento'];
                     ?>
                        <div id="<?=$i ?>" style="text-transform: uppercase;" data-name="<?=$get_elem['nombre_elemento'] ?>" data-element="<?=$get_elem['id_elemento'] ?>" class="rect-button-small radio-menu-small face    <?=($valores['status']=='actual')? 'face-osc': '' ; ?>" onclick="showLoad();">
                        <input type="checkbox" <?=($valores['status']=='actual')? 'checked': '' ; ?> name="chosen" value="<?=$valores['id_proceso']; ?>">
                        
                        <input type="hidden" name="odetes[<?=$valores['id_proceso'] ?>]" value="<?=$valores['num_odt']; ?>">
                       <input type="hidden" name="ordenes[<?=$valores['id_proceso'] ?>]"  value="<?=$valores['id_orden'] ?>">
                       <input type="hidden" name="procesos[<?=$valores['id_proceso'] ?>]"  value="<?=$valores['id_proceso'] ?>">
                       <input type="hidden" name="products[<?=$valores['id_proceso'] ?>]" value="<?=$valores['producto']; ?>">
                        
                          <p class="elem" <?=($element=='Desconocido')? 'style="font-size:15px;"':''; ?> ><?php echo  trim($element); ?><br><span><?= $valores['reproceso']?></span></p>
                          <p class="product" style="display: none;"><?= $valores['num_odt']?></p>
                        </div>
                       
                        <?php $i++; }if($query->num_rows==0){echo '<p style="font-weight:bold;color:#005076;margin-top:20px;font-size:20px;">TRABAJO TERMINADO PARA ESTA ORDEN</p>';} ?>
                        
                          <input type='hidden' id='returning2' name="returning2" value="<?php echo  (isset($element))? trim($element) :'';; ?>">
                         
</form>
                        </div> 
 <?php # termina general

}elseif ($entorno=='virtual') {
 
 $vodt=strtoupper ($_POST['virtualodt']);
 $velem=ucfirst($_POST['virtualelem']);
 $videlem=(!empty($_POST['idelem']))? $_POST['idelem']:'null';
 $plans=(isset($_POST['plans']))? $_POST['plans']:'null';
 $stationName=$_POST['machine'];
 
  $process=($stationName=='Serigrafia2'||$stationName=='Serigrafia3')?'Serigrafia':(($stationName=='Suaje2')? 'Suaje' : (($stationName=='HotStamping2')? 'HotStamping' : $stationName) );
 
 $clean=$mysqli->query("DELETE FROM personal_process WHERE proceso_actual='$stationName'");
 if ($clean) {
   $addvirtual=$mysqli->query("INSERT INTO `personal_process` (`id_pp`, `id_orden`, `num_odt`, `proceso_actual`, `id_proceso`, `status`, `orden_display`, `elemento_virtual`,id_elemento_virtual,planillas_de) VALUES (NULL, NULL, '$vodt', '$stationName', NULL, 'actual', 1, '$velem',$videlem,$plans)");
   //$inquery="INSERT INTO `personal_process` (`id_pp`, `id_orden`, `num_odt`, `proceso_actual`, `id_proceso`, `status`, `orden_display`, `elemento_virtual`,id_elemento_virtual) VALUES (NULL, NULL, '$vodt', '$stationName', NULL, 'actual', 1, '$velem',$videlem)";
   if ($addvirtual) {
    $getvirtual=$mysqli->query("SELECT * FROM personal_process WHERE proceso_actual='$stationName' ORDER BY orden_display ASC");
    while ($valores = mysqli_fetch_array($getvirtual)) {
     
    $vi=1;
   ?>
    <div id="<?=$vi ?>" style="text-transform: uppercase;"  class="rect-button-small radio-menu-small face    <?=($valores['status']=='actual')? 'face-osc': '' ; ?>" onclick="showLoad();">
                        <input type="checkbox" <?=($valores['status']=='actual')? 'checked': '' ; ?> name="odetes[]" value="<?=$valores['num_odt']; ?>">
                        <input type="checkbox" <?=($valores['status']=='actual')? 'checked': '' ; ?> name="datos[]"  value="<?=$valores['id_orden'] ?>"  >
                        
                       
                       
                          <p class="elem" style="<?=(strlen(trim($valores['elemento_virtual']))>16)? 'font-size: 15px; line-height: 18px!important;' : ''; ?>"><?= trim($valores['elemento_virtual']); ?><br><span><?= $valores['reproceso']?></span></p>
                          <p class="product" style="display: none;"><?= $valores['num_odt']?></p>
                        </div>
                         <input type='hidden' id='returning' name="returning" value="<?=$valores['num_odt']; ?>">
                        <input type='hidden' id='returning2' name="returning2" value="<?=$valores['elemento_virtual']; ?>">
                          <input type='hidden' id='returning3' name="returning3"    value="virtual">
                          <input type='hidden' id='returning4' name="returning3"    value="<?=$videlem ?>">
   <?php
   $vi++;
 }
   }else{
    echo "ocurrio un error     ";
    printf($mysqli->error);
    //echo $inquery;
   }
 }



}else{

$p_actual=$_SESSION['stationName'];


$ordenes=$_POST['ordenes'];
$odetes=$_POST['odetes'];
$procesos=$_POST['procesos'];
$chosen=$_POST['chosen'];
$products=$_POST['products'];
$o_chosen=$ordenes[$chosen];
$odt_chosen=$odetes[$chosen];
$p_chosen=$procesos[$chosen];
$prod_chosen=$products[$chosen];
unset($ordenes[$chosen]);
unset($odetes[$chosen]);
unset($procesos[$chosen]);

$clean="DELETE FROM personal_process WHERE proceso_actual='$p_actual' ";
$mysqli->query($clean);

$query_chosen="INSERT INTO personal_process(id_pp,id_orden,num_odt,proceso_actual, id_proceso,status,orden_display) VALUES(null,$o_chosen,'$odt_chosen','$p_actual',$p_chosen, 'actual',1)";
$insert_chosen=$mysqli->query($query_chosen);
if ($insert_chosen) {
  $query_session="UPDATE sesiones SET orden_actual='$odt_chosen',parte=$prod_chosen,id_orden=$o_chosen WHERE fecha='$today' AND estacion=$stationID ";
$set_session=$mysqli->query($query_session);
}else{
  printf($mysqli->error);
}

$i2=2;
foreach ($procesos as $key => $proceso) {
  $id=$ordenes[$proceso];
  $numodt=$odetes[$proceso];


 $queryst="INSERT INTO personal_process(id_pp,id_orden,num_odt,proceso_actual, id_proceso,status,orden_display) VALUES(null,$id,'$numodt','$p_actual',$proceso, 'programado',$i2)";
 $i2++;
 $insert=$mysqli->query($queryst);
if (!$insert) {
  printf($mysqli->error);
}
 }
$query = "SELECT pp.*,(SELECT producto FROM ordenes WHERE idorden=pp.id_orden) AS producto,p.nombre_proceso,p.reproceso FROM personal_process pp INNER JOIN procesos p ON pp.id_proceso=p.id_proceso WHERE proceso_actual='$stationName' AND nombre_proceso='$processName' AND avance NOT IN('completado') order by orden_display asc";
$result=$mysqli->query($query);
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
                       <input type='hidden' id='returning' name="returning" value="<?=$valores['num_odt']; ?>">
                       <input type='hidden' id='returning2' name="returning" value="<?=trim($element) ?>">
                         <input type='hidden' id='returning3' name="returning" value="<?=$valores['id_orden'] ?>">


                          <p class="elem" <?=($element=='Desconocido')? 'style="font-size:15px;"':''; ?> ><?=trim($element); ?><br><span><?= $valores['reproceso']?></span></p>
                          <p class="product" style="display: none;"><?= $valores['num_odt']?></p>
                        </div>




<?php
$i++;
}
?>
<script>
  var message='hola perro';
</script>
<?php

}
}




 

          
?>


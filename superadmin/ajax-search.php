<?php
require('../saves/conexion.php');
$limit = 200;
if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; };  
$start_from = ($page-1) * $limit;  
$term = $_REQUEST['condition']; 

if ($term!='') {
	$sql = "SELECT idorden,numodt FROM ordenes WHERE   numodt LIKE '%" . $term . "%' ORDER BY idorden LIMIT $start_from, $limit"; 
 } else{
 	$sql = "SELECT idorden,numodt FROM ordenes WHERE entregado NOT IN('true') ORDER BY fechafin DESC LIMIT 0, $limit"; 
  
 }
$rs_result = $mysqli->query($sql); 


?>

<?php  

while ($fila = mysqli_fetch_array($rs_result) ){
                  $orders[$fila['numodt']]=$fila;
                 }
                 //print_r($orders);
      ?>
            <table id="bodyt">
                          <tbody>
                  <?php 
                  if (!empty($orders)) {
                 foreach ($orders as $fila){ 

                  ?>
                          <tr id="<?php echo $fila["idorden"];?>" onclick='rellenar("<?= $fila["numodt"];?>");' >
                          <td > <?=$fila["numodt"];?> </td> 
                          <td ><?=getStatus($fila['numodt'],'Original');?> </td>
                          <td ><?=getStatus($fila['numodt'],'Positivo');?> </td>
                          <td ><?=getStatus($fila['numodt'],'Placa');?> </td>
                          <td ><?=getStatus($fila['numodt'],'Placa_HS');?> </td>
                          <td > <?=getStatus($fila['numodt'],'LaminaOff');?> </td>
                          <td ><?=getStatus($fila['numodt'],'Corte');?> </td>
                          <td ><?=getStatus($fila['numodt'],'Revelado');?></td>
                          <td > <?=getStatus($fila['numodt'],'Laser');?> </td>
                          <td ><?=getStatus($fila['numodt'],'Suaje');?> </td>
                          <td ><?=getStatus($fila['numodt'],'Serigrafia');?> </td>
                          <td ><?=getStatus($fila['numodt'],'Offset');?> </td>
                          <td ><?=getStatus($fila['numodt'],'Digital');?> </td>
                          <td ><?=getStatus($fila['numodt'],'LetterPres');?> </td>
                          <td > <?=getStatus($fila['numodt'],'Plastificado');?> </td>
                          <td ><?=getStatus($fila['numodt'],'Encuadernacion');?> </td>
                          <td ><?=getStatus($fila['numodt'],'HotStamping');?> </td>
                          <td ><?=getStatus($fila['numodt'],'Grabado');?> </td>
                          <td > <?=getStatus($fila['numodt'],'Pleca');?> </td>
                          <td > <?=getStatus($fila['numodt'],'Acabado');?> </td>
                          </tr>
                    <?php } }else{
              echo "<p style='color:#fff;width:835px;background:#393C41;height:37px;text-align:center;line-height:37px;'>No se encontraron resultados</p>";
             } ?>
                          </tbody>
                      </table>
                  
              
              
             <?php
              
           function getStatus($id_process,$process){
                    require('../saves/conexion.php');
                    
                    $subquery="SELECT estatus FROM procesos WHERE numodt='$id_process' AND nombre_proceso='$process' ORDER BY estatus DESC";
                    $getting=$mysqli->query($subquery);
                  //$getLast = mysqli_fetch_assoc($getting);
                  $bars='';
                  //$lastId=$getLast['estatus'];
                  $divide=($getting->num_rows>0)? 100/$getting->num_rows: 100;
                 
                  while ( $row=mysqli_fetch_assoc($getting)) {
                    if ($row['estatus']=='En Tiempo') {
                      $bars.='<div style="width:'.$divide.'%" class="progressing ontime"></div>';
                    }elseif ($row['estatus']=='Tarde') {
                      $bars.='<div style="width:'.$divide.'%" class="progressing tolate"></div>';
                    }elseif ($row['estatus']=='No se ha Realizado') {
                      $bars.='<div style="width:'.$divide.'%" class="progressing norealized"></div>';
                    }

                    
                  }

                  if ($getting->num_rows==0) {
                    $qty=(($getting->num_rows>1)?" <div class='proqty'>".$getting->num_rows."</div>" : '');
                   $semaforo=$qty.' <img width="15" src="../images/blanco.png"/>';
                   return $semaforo;
                  }else{
                     $qty=(($getting->num_rows>1)?" <div class='proqty'>".$getting->num_rows."</div>" : '');
                   $semaforo='<div class="sem-light">'.$qty.$bars.'</div>';
                   return $semaforo;
                  }
                

                  }
            ?>
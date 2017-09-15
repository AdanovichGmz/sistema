<?php 
 require('saves/conexion.php');
$entorno=(isset($_POST["entorno"]))? $_POST["entorno"] : 'personal';
$machineName=(isset($_POST['machine']))? $_POST['machine'] :'';
if ($entorno=='general') {
  $datos=(isset($_POST["odt"]))? explode(",",$_POST["odt"] ):''; 


$maqID=$_POST["machine"]; 

$odetes=(isset($_POST["odt"]))? explode(",",$_POST["odt"] ):'';
foreach ($datos as $dato) {
  $results4[]=$dato;
}
$getodt=$odetes[0];
 $times=count($datos);


$sql="SELECT o.numodt FROM ordenes o INNER JOIN procesos p WHERE p.nombre_proceso='$maqID'  GROUP BY o.numodt";
$sql2="SELECT * FROM odt_flujo WHERE proceso='$maqID' order by orden_display ASC";




$initquery="SELECT COUNT(*) AS conteo FROM odt_flujo WHERE proceso='$maqID'";
                      $initial = mysqli_fetch_assoc($mysqli->query($initquery));
                      $init=$initial['conteo'];
                      $fin=($init>0)? $sql2 : $sql;
                      $result=$mysqli->query($fin);
                      $i=1;
                     
                 
while($arr=mysqli_fetch_assoc($result)){
  $results[$i] = $arr;
  $i++;
}
//echo count($results);
function searchArrayKeyVal($sKey, $id, $array) {
   foreach ($array as $key => $val) {
       if ($val[$sKey] == $id) {
           return $key;
       }
   }
   return false;
}
 function move_to_top($array, $key) {
    $temp = array($key => $array[$key]);
    unset($array[$key]);
    $array = $temp + $array;
  }

$queryclean="DELETE FROM odt_flujo WHERE proceso='$maqID'";
$mysqli->query($queryclean);

$i2=1;
foreach ($results as $row) {
$numodt=$row['numodt'];
 
 $ord=$i2;
 
$queryst="INSERT INTO odt_flujo(id_flujo,numodt,proceso,status,orden_display) VALUES(null,'$numodt','$maqID','programado',$ord)";

if (!$mysqli->query($queryst)) {
  printf($mysqli->error);
}
 

$i2++;
}




$i0=0;
$sql2="SELECT * FROM odt_flujo WHERE proceso='$maqID' order by orden_display asc";
$ords2=$mysqli->query($sql2);

while($arr2=mysqli_fetch_assoc($ords2)) {
  
  $results2[$i0] = $arr2;
  $i0++;
}


$total=count($results2);

//print_r($datos);

$actuales = array_slice($results2, 0, $times);
$siguientes = array_slice($results2, $times, $total);




foreach ($datos as $orden) {
  $arrayKey = searchArrayKeyVal("numodt", $orden, $results2);
if ($arrayKey!==false) {
    $theKey= $arrayKey;
     $temp[]=$results2[$theKey];
     $keys[]=$arrayKey;
} else {
    echo "No se encontro ".$orden."  ";
}
}

foreach ($keys as $key) {
 unset($results2[$key]);
}



$results3=array_merge($temp,$results2);

 
/*
unset($results3[$theKey]);
array_unshift($results3, $temp);
 */
//Guardando orden de display
$i3=1;

foreach ($results3 as $row2) {
 
  $id=$row2['id_flujo'];
 
   
 $update3 = "UPDATE odt_flujo SET orden_display = $i3 WHERE id_flujo= $id ";
$mysqli->query($update3);
$i3++;
}

//Guardando estatus siguientes
$i4=1;

foreach ($results2 as $row3) {
 
  $id=$row3['id_flujo'];
  
    if ($i4==1) {
      $status='siguiente';
    }
    elseif ($i4==2) {
     $status='preparacion';
    }     
    else{ 
      $progNum=$i4-2;
      $status='programado'.$progNum;
    }
 $update3 = "UPDATE odt_flujo SET status='$status' WHERE id_flujo= $id";
$mysqli->query($update3);
$i4++;
}
//Guardando estatus actuales
$i5=1;
foreach ($temp as $row4) {
 
  $id=$row4['id_flujo'];
  
 $update3 = "UPDATE odt_flujo SET status='actual' WHERE id_flujo= $id ";
$mysqli->query($update3);
$i5++;
}
?> <form id="tareas" action="opp.php" method="post" >
                  <input type="hidden" name="machine" value="<?=$machineName; ?>">

 <input type="hidden" name="machine" value="<?=$maqID; ?>">
                 <input type='hidden' id='returning' name="returning" value="<?=implode(",", $odetes); ?>">
                 
                  <?php
                  
                      $query = $mysqli->query("SELECT  o.idorden AS id_orden,o.numodt AS num_odt,o.fechafin,o.fechareg,o.producto,p.id_proceso,p.avance,(SELECT orden_display FROM personal_process WHERE id_orden=o.idorden AND id_proceso=p.id_proceso) AS orden_display,(SELECT status FROM personal_process WHERE id_orden=o.idorden AND id_proceso=p.id_proceso) AS status FROM ordenes o LEFT JOIN procesos p ON p.id_orden=o.idorden WHERE nombre_proceso='$machineName' AND o.numodt='$getodt' AND avance NOT IN('completado') order by fechafin asc LIMIT 12");
                      $i=1;
                      while ($valores = mysqli_fetch_array($query)) {
                        
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
                       
                        <?php $i++; } ?>
                        
                          <input type='hidden' id='returning2' name="returning2" value="<?php echo  (isset($element))? trim($element) :'';; ?>">
                         
</form>
                        </div> 
 <?php # termina general
}else{
$datos=$_POST["datos"]; 


$maqID=$_POST["machine"]; 
$idpro=$_POST["idpro"];
$odetes=$_POST['odetes'];
foreach ($datos as $dato) {
  $results4[]=$dato;
}
$getodt=$odetes[0];
 $times=count($datos);


$sql="SELECT o.idorden,o.numodt,o.orden,p.id_proceso FROM ordenes o INNER JOIN procesos p ON p.id_orden=o.idorden WHERE nombre_proceso='$maqID' AND o.numodt='$getodt' order by idorden asc";

$sql2="SELECT o.idorden,o.numodt,o.orden,p.id_proceso,(SELECT status FROM personal_process WHERE id_orden=o.idorden AND id_proceso=p.id_proceso) AS status,(SELECT orden_display FROM personal_process WHERE id_orden=o.idorden AND id_proceso=p.id_proceso) AS display FROM ordenes o INNER JOIN procesos p ON p.id_orden=o.idorden WHERE nombre_proceso='$maqID' AND o.numodt='$getodt'  order by display ASC";

$initquery="SELECT COUNT(*) AS conteo FROM personal_process WHERE proceso_actual='$maqID'";
                      $initial = mysqli_fetch_assoc($mysqli->query($initquery));
                      $init=$initial['conteo'];
                      $fin=($init>0)? $sql2 : $sql;
                      $result=$mysqli->query($fin);
                      $i=1;
                     
                 
while($arr=mysqli_fetch_assoc($result)){
  $results[$i] = $arr;
  $i++;
}

function searchArrayKeyVal($sKey, $id, $array) {
   foreach ($array as $key => $val) {
       if ($val[$sKey] == $id) {
           return $key;
       }
   }
   return false;
}
 function move_to_top($array, $key) {
    $temp = array($key => $array[$key]);
    unset($array[$key]);
    $array = $temp + $array;
  }

$queryclean="DELETE FROM personal_process WHERE proceso_actual='$maqID'";
$mysqli->query($queryclean);

$i2=1;
foreach ($results as $row) {
$numodt=$row['numodt'];
  $id=$row['idorden'];
 $ord=($row['orden']!=null)?$row['orden'] : $i2;
 $idpr=$row['id_proceso'];
$queryst="INSERT INTO personal_process(id_pp,id_orden,num_odt,proceso_actual,id_proceso,status,orden_display) VALUES(null,$id,'$numodt','$maqID',$idpr,'programado',$ord)";

if (!$mysqli->query($queryst)) {
  printf($mysqli->error);
}
 

$i2++;
}




$i0=0;
$sql2="SELECT * FROM personal_process WHERE proceso_actual='$maqID' order by orden_display asc";
$ords2=$mysqli->query($sql2);

while($arr2=mysqli_fetch_assoc($ords2)) {
  
  $results2[$i0] = $arr2;
  $i0++;
}


$total=count($results2);

//print_r($datos);

$actuales = array_slice($results2, 0, $times);
$siguientes = array_slice($results2, $times, $total);




foreach ($datos as $orden) {
  $arrayKey = searchArrayKeyVal("id_orden", $orden, $results2);
if ($arrayKey!==false) {
    $theKey= $arrayKey;
     $temp[]=$results2[$theKey];
     $keys[]=$arrayKey;
} else {
    echo "No se encontro ".$orden."  ";
}
}

foreach ($keys as $key) {
 unset($results2[$key]);
}



$results3=array_merge($temp,$results2);

 
/*
unset($results3[$theKey]);
array_unshift($results3, $temp);
 */
//Guardando orden de display
$i3=1;
foreach ($results3 as $row2) {
 
  $id=$row2['id_orden'];
  $idprs=$row2['id_proceso'];
   
 $update3 = "UPDATE personal_process SET orden_display = $i3 WHERE id_orden= $id AND id_proceso=$idprs";
$mysqli->query($update3);
$i3++;
}

//Guardando estatus siguientes
$i4=1;
foreach ($results2 as $row3) {
 
  $id=$row3['id_orden'];
  $idprs=$row3['id_proceso'];
    if ($i4==1) {
      $status='siguiente';
    }
    elseif ($i4==2) {
     $status='preparacion';
    }     
    else{ 
      $progNum=$i4-2;
      $status='programado'.$progNum;
    }
 $update3 = "UPDATE personal_process SET status='$status' WHERE id_orden= $id AND id_proceso=$idprs";
$mysqli->query($update3);
$i4++;
}
//Guardando estatus actuales
$i5=1;
foreach ($temp as $row4) {
 
  $id=$row4['id_orden'];
  $idprs=$row4['id_proceso'];
    
 $update3 = "UPDATE personal_process SET status='actual' WHERE id_orden= $id AND id_proceso=$idprs";
$mysqli->query($update3);
$i5++;
}

    $resultado=true;
    if ( $resultado) {
?>
            
                
                  <input type="hidden" name="machine" value="<?=$maqID; ?>">
                 <input type='hidden' id='returning' name="returning" value="<?=implode(",", $odetes); ?>">
                 
                  <?php
                  
                      $query = $mysqli->query("SELECT  o.idorden,o.numodt AS odt,o.fechafin,o.fechareg,o.producto,p.id_proceso,p.avance,(SELECT orden_display FROM personal_process WHERE id_orden=o.idorden AND id_proceso=p.id_proceso) AS orden_display,(SELECT status FROM personal_process WHERE id_orden=o.idorden AND id_proceso=p.id_proceso) AS status FROM ordenes o INNER JOIN procesos p ON p.id_orden=o.idorden WHERE nombre_proceso='$maqID' AND o.numodt='$getodt' AND avance NOT IN('completado') order by orden_display asc LIMIT 12");
                      $i=1;
                      while ($valores = mysqli_fetch_array($query)) {
                        
                      $prod=$valores['producto'];
                      $element_query="SELECT nombre_elemento FROM elementos WHERE id_elemento=$prod";
                      $get_elem=mysqli_fetch_assoc($mysqli->query($element_query));
                      $element=(isset($get_elem['nombre_elemento']))? $get_elem['nombre_elemento'] : '';
                     ?>
                        <div id="<?=$i ?>" style="text-transform: uppercase;"  class="rect-button-small radio-menu-small face abajo   <?=($valores['status']=='actual')? 'face-osc': '' ; ?>" onclick="showLoad(); selectOrders(this.id,'<?=$valores['odt'] ?>')">
                        <input type="checkbox" <?=($valores['status']=='actual')? 'checked': '' ; ?> name="odetes[]" value="<?=$valores['odt']; ?>">
                        <input type="checkbox" <?=($valores['status']=='actual')? 'checked': '' ; ?> name="datos[]"  value="<?=$valores['idorden'] ?>"  >
                        
                       
                        <input type="checkbox" <?=($valores['status']=='actual')? 'checked': '' ; ?> name="idpro[]"  value="<?=$valores['id_proceso'] ?>"  >
                          <p class="elem" ><?php echo  trim($element); ?></p>
                          <p class="product" style="display: none;"><?= $valores['odt']?></p>
                        </div>
                       
                        <?php $i++; } 
                        $getActual=mysqli_fetch_assoc($mysqli->query("SELECT pp.*,(SELECT producto FROM ordenes WHERE idorden=pp.id_orden) AS element,(SELECT nombre_elemento FROM elementos WHERE id_elemento=element) AS nom_element FROM personal_process pp WHERE proceso_actual='$machineName' AND status='actual'"));
                       
                        ?>
                        
                          <input type='hidden' id='returning2' name="returning2" value="<?=$getActual['nom_element']; ?>">
                          <input type='hidden' id='returning3' name="returning3"    value="<?=$getActual['id_orden'] ?>">

<?php
 }else{
            printf("Errormessage: %s\n", $mysqli->error);
          }
        }

          
?>


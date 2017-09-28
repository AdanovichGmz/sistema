<?php


include '../saves/conexion.php';
$numodt=$_POST['fecha'];


 $query="SELECT t.*,m.nommaquina,o.producto,o.numodt,o.idorden,u.logged_in,(SELECT nombre_elemento FROM elementos WHERE id_elemento=o.producto) AS element,(SELECT piezas_por_hora FROM estandares WHERE id_elemento=o.producto AND id_maquina= 10) AS estandar,TIME_TO_SEC(tiempoTiraje) AS seconds FROM tiraje t INNER JOIN maquina m ON m.idmaquina=t.id_maquina INNER JOIN login u ON u.id=t.id_user INNER JOIN ordenes o ON o.idorden=t.id_orden WHERE fechadeldia_tiraje='$numodt' ORDER BY nommaquina ASC";
   
       
        $resss=$mysqli->query($query);
        
?>


<table id="texcel">
<thead><tr>
<th>Id Orden</th>
    <th>Maquina</th>
    <th>Usuario</th>
    <th>ODT</th>
    <th>Elemento</th>
    <th>Tiempo Ajuste</th>
    
    <th>Tiempo Tiraje</th>
    <th>Hora Ajuste</th>
    <th>Hora Tiraje</th>
    <th>Sec</th>
    <th>Estandar por hora</th>
    <th>Cantidad Pedida</th>
    <th>Produccion Esperada</th>
    <th>Produccion Real</th>
    <th>Desempeño</th>
  </tr></thead>
  <tbody>
  <?php 
                          while($row=mysqli_fetch_assoc($resss)):  ?>
                          <tr>
                          <td><?= $row['idorden'];?></td>
    <td><?= $row['nommaquina'];?></td>
    <td><?= $row['logged_in'];?> </td>
    <td><?= $row['numodt'];?> </td>
    <td><?= $row['element'];?> </td>
    <td><?= substr($row['tiempo_ajuste'],0,-7);?></td>
    <td><?= substr($row['tiempoTiraje'],0,-7);?></td>
    <td><?= $row['horadeldia_ajuste'];?></td>
    <td><?= $row['horadeldia_tiraje'];?></td>
    <td><?= $row['seconds'];?></td>
    <td><?= ($row['estandar']!='')?$row['estandar'] :'Indefinido';?></td>
    <td><?= $row['cantidad'];?></td>
    <td><?= $row['produccion_esperada'];?></td>
    <td><?= $row['entregados'];?></td>
    <td><?= round($row['desempenio'],2);?>%</td>
  </tr>
  <?php endwhile; ?>
  
  </tbody>
</table>


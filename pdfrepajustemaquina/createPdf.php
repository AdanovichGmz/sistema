<?php

require_once("dompdf/dompdf_config.inc.php");
include '../saves/conexion.php';
$numodt=$_POST['id'];


 $query="SELECT t.*,m.nommaquina,o.numodt,o.producto,u.logged_in,(SELECT nombre_elemento FROM elementos WHERE id_elemento=o.producto) AS element,(SELECT piezas_por_hora FROM estandares WHERE id_elemento=o.producto AND id_maquina= 10) AS estandar,TIME_TO_SEC(tiempoTiraje) AS seconds FROM tiraje t LEFT JOIN maquina m ON m.idmaquina=t.id_maquina LEFT JOIN login u ON u.id=t.id_user LEFT JOIN ordenes o ON o.idorden=t.id_orden WHERE fechadeldia_tiraje='$numodt' ORDER BY nommaquina ASC";
   
       
        $resss=$mysqli->query($query);
        
?>
<?php ob_start();?>
<html>
<head>
<style>
@page {
            margin: 1.3em 1.3em;
            
        }
table {
    font-family: arial, sans-serif;
    border-collapse: collapse;
    width: 100%;
}

thead{
  background: #1A1F25;
  color: #fff;
}

td, th {
    border: 1px solid #E1E0E5;
    text-align: left;
    padding: 8px;
    font-size: 10px;
}

tbody tr:nth-child(even) {
    background-color: #EBEBEB;
}
.inhead{
  display: inline-block;
  width: 19%;
   font-family: arial, sans-serif;
   

}
.header{
  width: 100%;
  margin: 0 auto;
  padding-bottom: 10px;
  padding-top: 10px;
  border-bottom: 1px solid #E1E0E5;
  border-top: 1px solid #E1E0E5;
}
.header img{
  width: 70%;
}
#last {
  text-align: right!important;
}
#last img{
  width: 50%;
}
</style>
</head>

<body>
<div class="header">
  <div class="inhead"><img src="../img/logoIzquierda.png"></div>
  <div class="inhead">
    <p style="font-weight: bold;">Reporte Diario</p>
    <p>Fecha: <?php echo $numodt;?></p>

  </div>
  <div class="inhead"></div>
  <div class="inhead"></div>
  <div class="inhead" id="last"></div>
</div>
<div style="height: 15px;"></div>
<table>
<thead><tr>
    <th>Maquina</th>
    <th>Usuario</th>
    <th>ODT</th>
    <th>Elemento</th>
    <th>Tiempo Ajuste</th>
    <th>Tiempo Tiraje</th>
    <th>Sec</th>
    <th>Estandar por hora</th>
    <th>Cantidad Pedida</th>
    <th>Prod. Esperada</th>
    <th>Prod. Real</th>
    <th>Desempeño</th>
  </tr></thead>
  <tbody>
  <?php 
                          while($row=mysqli_fetch_assoc($resss)):  ?>
                          <tr>
    <td><?= $row['nommaquina'];?></td>
    <td><?= $row['logged_in'];?> </td>
    <td <?=($row['is_virtual']=='true')? 'style="color:red;"':'' ?>><?=($row['is_virtual']=='true')? $row['odt_virtual'] : $row['numodt'];?> </td>
    <td <?=($row['is_virtual']=='true')? 'style="color:red;"':'' ?> ><?=($row['is_virtual']=='true')? $row['elemento_virtual'] : $row['element'];?> </td>
    <td><?= substr($row['tiempo_ajuste'],0,-7);?></td>
    <td><?= substr($row['tiempoTiraje'],0,-7);?></td>
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


</body>
</html>



<?php
$html = ob_get_clean();

$dompdf = new DOMPDF();
$dompdf->load_html($html);
//$dompdf->set_paper('letter', 'landscape');
$dompdf->render();
$dompdf->stream("reporte_de_orden.pdf", array('Attachment' => 0));


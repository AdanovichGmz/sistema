<?php

require_once("dompdf2/autoload.inc.php");
include '../saves/conexion.php';
use Dompdf\Dompdf;
$fecha = $_POST['id'];

$query = "SELECT ep.id_proceso,(SELECT nombre_proceso FROM procesos_catalogo WHERE id_proceso=ep.id_proceso)AS nom_proceso FROM estaciones_procesos ep GROUP BY id_proceso";

$res=$mysqli->query($query);
if (!$res) {
  printf($mysqli->error);

}

while ($row=mysqli_fetch_assoc($res)) {
$query2="SELECT SUM(entregados)AS produccion_dia,(SELECT nombre_proceso FROM procesos_catalogo WHERE id_proceso=t.id_proceso)AS nombre_proceso, SUM(produccion_esperada)AS objetivo, SUM(produccion_esperada)-SUM(entregados) AS diferencia FROM tiraje t WHERE fechadeldia_ajuste='$fecha' AND id_proceso=".$row['id_proceso'];
  $res2=$mysqli->query($query2);

$dayInfo=mysqli_fetch_assoc($res2);




  $process[$row['id_proceso']]['produccion_dia']=(empty($dayInfo['produccion_dia']))? 0:$dayInfo['produccion_dia'];
  $process[$row['id_proceso']]['objetivo']=(empty($dayInfo['objetivo']))? 0:$dayInfo['objetivo'];
  $process[$row['id_proceso']]['diferencia']=(empty($dayInfo['diferencia']))? 0:$dayInfo['diferencia'];
  $process[$row['id_proceso']]['nombre_proceso']=$row['nom_proceso'];

}

?>

<?php
ob_start();
?>

  <style>
  @page{
    margin:1.3em
}
 #info{
    font-family:Arial,Helvetica,sans-serif;
    border-collapse:collapse;
    width:100%;
    text-align:center;
    font-size:12px
}
 #info td{
    border:1px solid #ccc;
    padding:2px
}
 #info tr:nth-child(even){
    background-color:#EBEBEB
}


.theader{
    text-transform:uppercase;
    text-align:center;
    background-color:#000;
    color:#fff;
    font-size:10px
}
#resumen{
    font-family:Arial,Helvetica,sans-serif;
    border-collapse:collapse;
    width:100%;
    text-align:center;
    font-size:12px
}
#resumen td{
    border:1px solid #ccc;
    padding:2px
}

.resume-head{
    text-transform:uppercase;
    text-align:center;
    font-size:10px;
    
}
#head{
    font-family:Arial,Helvetica,sans-serif;
    border-collapse:collapse;
    width:100%;
    text-align:center;
    font-size:9px
}
#head td{
    border:1px solid #ccc;
    padding:1px
}
.head-header{
    text-transform:uppercase;
    text-align:center;
    font-size:8px;

}
#head img{
    width:100px
}

</style>

<table id="head">
<tr class="head-header">
  <td rowspan="2" style="width: 120px;"><img src="../img/logoDerecha.png"></td>
  <td rowspan="2">REPORTE LEAN</td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
</tr>
 
  <tr>
   <td></td>
   <td></td>
   <td></td>
   <td></td>
    </tr>
  </tbody>
</table>
<div style="height: 8px;"></div>
   
<table id="info">
<thead>
  <tr class="theader">
    <th >Producto</th>
    <th >Produccion por dia</th>
    <th >Objetivo</th>
    <th >Diferencia</th>
    <th>Fecha</th>
    <th >Produccion Acumulada</th>
    
  </tr>
  
  </thead>

  
   <tbody>
  <?php 
    foreach ($process as $key => $pro) { ?>
      <tr>
        <td><?=$pro['nombre_proceso'] ?></td>
        <td><?=$pro['produccion_dia'] ?></td>
        <td><?=$pro['objetivo'] ?></td>
        <td><?=$pro['diferencia'] ?></td>
        <td><?=$_POST['id'] ?></td>
        <td>--</td>
      </tr>

   <?php  }
   
   ?>
  </tbody>
</table>

<?php
 

$html = ob_get_clean();

$dompdf = new Dompdf();
$dompdf->load_html($html);
$dompdf->set_paper('letter', 'landscape');
$dompdf->render();
$dompdf->stream("reporte_de_orden.pdf", array(
    'Attachment' => 0
)); 
?>
<?php

require_once("dompdf/dompdf_config.inc.php");
include '../saves/conexion.php';
$numodt=$_POST['id'];
$userid=$_POST['iduser'];

 $query="SELECT t.*,m.nommaquina,o.numodt,o.producto,u.logged_in,(SELECT nombre_elemento FROM elementos WHERE id_elemento=o.producto) AS element,(t.entregados-t.merma)-t.defectos AS calidad,(SELECT piezas_por_hora FROM estandares WHERE id_elemento=o.producto AND id_maquina= 10) AS estandar,TIME_TO_SEC(tiempoTiraje) AS seconds_tiraje,TIME_TO_SEC(tiempo_ajuste) AS seconds_ajuste,(SELECT TIME_TO_SEC(tiempo_muerto) FROM tiempo_muerto WHERE id_tiraje=t.idtiraje AND seccion='ajuste') AS seconds_muertos,(SELECT TIME_TO_SEC(tiempo_muerto) FROM tiempo_muerto WHERE id_tiraje=t.idtiraje AND seccion='tiraje') AS seconds_muertos_tiro  FROM tiraje t LEFT JOIN maquina m ON m.idmaquina=t.id_maquina LEFT JOIN login u ON u.id=t.id_user LEFT JOIN ordenes o ON o.idorden=t.id_orden WHERE fechadeldia_tiraje='$numodt' AND t.id_user=$userid ORDER BY nommaquina ASC";
   
       
        $resss=$mysqli->query($query);
        $getuser=mysqli_fetch_assoc($mysqli->query("SELECT logged_in FROM login WHERE id=$userid"));


        
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
    text-align: center;
    padding: 4px;
    font-size: 9px;
}

tbody tr:nth-child(even) {
    background-color: #EBEBEB;
}
.inhead{
  display: inline-block;
  width: 69%;
   font-family: arial, sans-serif;
   
   height: 50px;
  

}
.inhead table{
  width: 100%;
  text-align: center;

}
.inhead th{
 padding: 2px;
 text-align: center;

}
.inhead td{

 text-align: center;

}
.logo{
  display: inline-block;
  width: 5%;
   font-family: arial, sans-serif;
   background: yellow;
   

}
.title{
  display: inline-block;
  width: 25%;
  text-align: center;
   font-family: arial, sans-serif;
  font-weight: bold;
   height: 50px;
   line-height: 50px;
   vertical-align: middle;
   

}

.header{
  width: 100%;
  margin: 0 auto;
  padding-bottom: 5px;
  padding-top: 5px;
  
}
.header img{
  width: 100%;
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
  <div class="logo"><img src="../img/logoIzquierda.png">
  </div><div class="title">REPORTE DIARIO ETE
 
 </div> <div class="inhead">
  <table>
  <tr><th>OPERADOR</th>
    <th>MAQUINA</th>
    <th>TURNO</th>
    <th>FECHA</th>
    </tr>
    <tr>
      <td><?=$getuser['logged_in'] ?></td>
      <td></td>
      <td></td>
      <td><?=$numodt ?></td>
    </tr>
  </table>
  </div>
</div>

<table>
<thead><tr>
    <th >Inicio</th>
    <th >Fin</th>
    <th>Producto</th>
    
    <th>STD</th>
    <th colspan="2">Tiempo Disponible</th>
    <th colspan="2">Tiempo Muerto</th>
    <th colspan="2">Tiempo Real</th>
    <th colspan="2">Produccion Esperada</th>
    <th colspan="2">Produccion Real</th>
    <th colspan="2">Merma</th>
    <th colspan="2">Calidad a la Primera</th>
    <th colspan="2">Defectos</th>
    <th>Porque no se hizo bien a la primera?</th>
    <th>Porque se hizo mas lento?</th>
    <th>Porque se perdio tiempo?</th>
  </tr></thead>
  <tbody>
  <?php 
  $i=0;
  $sum_esper=0;
  $sum_merm=0;
  $sum_real=0;
  $sum_tiraje=0;
   $sum_ajuste=0;
   $sum_muerto=0;
   $sum_defectos=0;
  $sum_calidad=0;
                          while($row=mysqli_fetch_assoc($resss)):  
                            $sum_esper+=$row['produccion_esperada'];
                          $sum_merm+=$row['merma_entregada'];
                          $sum_real+=$row['entregados']-$row['merma_entregada'];
                          $sum_tiraje+=$row['seconds_tiraje'];
                           $sum_ajuste+=$row['seconds_ajuste'];
                            $sum_muerto+=$row['seconds_muertos_tiro'];
                            $sum_defectos+=$row['defectos'];
                            $sum_calidad+=$row['calidad'];
                            $processID=($row['id_maquina']==20||$row['id_maquina']==21)? 10:$row['id_maquina'];
                            if (is_null($row['estandar'])) {
              
                            if ($processID==10) {
                                  $tiraje_estandar=420;
                                }else{
                                  $tiraje_estandar=600;
                                }
                          }else{
                            $tiraje_estandar=$row['estandar'];
                          }
                          ?>
                          <tr>
     <td><?= substr($row['horadeldia_tiraje'],0,-3);?></td>                     
    <td></td>
    <td <?=($row['is_virtual']=='true')? 'style="color:red;"':'' ?> ><?=($row['is_virtual']=='true')? $row['elemento_virtual'] : $row['element'];?> </td>
    <!-- <td <?=($row['is_virtual']=='true')? 'style="color:red;"':'' ?>><?=($row['is_virtual']=='true')? $row['odt_virtual'] : $row['numodt'];?> </td> -->
    <td><?= $tiraje_estandar;?></td>
    <td></td>
    <td></td>
    <td><?=gmdate("H:i", $row['seconds_muertos_tiro']); ?></td>
    <td><?=gmdate("H:i", $sum_muerto); ?></td>
    <td><?= gmdate("H:i", $row['seconds_tiraje']); ?></td>
    <td><?= gmdate("H:i", $sum_tiraje); ?></td>
    <td><?= $row['produccion_esperada'];?></td>
    <td><?=$sum_esper ?></td>
    <td><?= $row['entregados']-$row['merma_entregada'];?></td>
    <td><?=$sum_real ?></td>
    <td><?= $row['merma_entregada'];?></td>
    <td><?=$sum_merm ?></td>
    <td><?= $row['calidad'];?></td>
    <td><?=$sum_calidad ?></td>
    <td><?= $row['defectos'];?></td>
    <td><?=$sum_defectos ?></td>
    <td>--</td>
    <td>--</td>
    <td>--</td>
   
  </tr>
  <tr>
     <td><?= substr($row['horadeldia_ajuste'],0,-3);?></td>                     
    <td></td>
    <td <?=($row['is_virtual']=='true')? 'style="color:red;"':'' ?> >Ajuste </td>
    <!-- <td <?=($row['is_virtual']=='true')? 'style="color:red;"':'' ?>><?=($row['is_virtual']=='true')? $row['odt_virtual'] : $row['numodt'];?> </td> -->
    <td>0</td>
    <td></td>
    <td></td>
    <?php $sum_muerto+=$row['seconds_muertos']; ?>
    <td><?=gmdate("H:i", $row['seconds_muertos']); ?></td>
    <td><?=gmdate("H:i", $sum_muerto); ?></td>
    <td><?= gmdate("H:i", $row['seconds_ajuste']);?></td>
    <?php $sum_tiraje+=$row['seconds_ajuste']; ?>
    <td><?= gmdate("H:i", $sum_tiraje);
    ?></td>
    <td>0</td>
    <td><?=$sum_esper ?></td>
    <td>0</td>
    <td><?=$sum_real ?></td>
    <td>0</td>
    <td><?=$sum_merm ?></td>
    <td>0</td>
    <td><?=$sum_calidad ?></td>
    <td>0</td>
    <td><?=$sum_defectos ?></td>
    <td>--</td>
    <td>--</td>
    <td>--</td>
    <!--
   
    <td><?= round($row['desempenio'],2);?>%</td> -->
  </tr>
  <?php 
$i++;
   endwhile; ?>
  
  </tbody>
</table>


</body>
</html>



<?php
$html = ob_get_clean();

$dompdf = new DOMPDF();
$dompdf->load_html($html);
$dompdf->set_paper('letter', 'landscape');
$dompdf->render();
$dompdf->stream("reporte_de_orden.pdf", array('Attachment' => 0));


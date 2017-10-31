<?php
error_reporting(0);
require_once("dompdf/dompdf_config.inc.php");
include '../saves/conexion.php';
$numodt=$_POST['id'];
$userid=$_POST['iduser'];

 $query="SELECT t.*, m.nommaquina,o.numodt,o.producto,u.logged_in,(SELECT nombre_elemento FROM elementos WHERE id_elemento=o.producto) AS element,((t.entregados-t.merma_entregada)-t.defectos) AS calidad,(SELECT piezas_por_hora FROM estandares WHERE id_elemento=o.producto AND id_maquina= 10) AS estandar,TIME_TO_SEC(tiempoTiraje) AS seconds_tiraje,TIME_TO_SEC(timediff(horafin_tiraje,horadeldia_tiraje)) AS dispon_tiro,TIME_TO_SEC(timediff(horafin_ajuste,horadeldia_ajuste)) AS dispon_ajuste,TIME_TO_SEC(tiempo_ajuste) AS seconds_ajuste,(SELECT TIME_TO_SEC(tiempo_muerto) FROM tiempo_muerto WHERE id_tiraje=t.idtiraje AND seccion='ajuste') AS seconds_muertos,(SELECT TIME_TO_SEC(tiempo_muerto) FROM tiempo_muerto WHERE id_tiraje=t.idtiraje AND seccion='tiraje') AS seconds_muertos_tiro  FROM tiraje t LEFT JOIN maquina m ON m.idmaquina=t.id_maquina LEFT JOIN login u ON u.id=t.id_user LEFT JOIN ordenes o ON o.idorden=t.id_orden WHERE fechadeldia_tiraje='$numodt' AND t.id_user=$userid ORDER BY horadeldia_ajuste ASC";
   
       
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
 border:1px solid #E1E0E5!important;

}
.inhead td{
border:1px solid #E1E0E5!important;
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
.botom-stats{
  display: inline-block;
  border:1px solid #E1E0E5;
  position: relative;
  font-family: arial, sans-serif;
}
.botom-stats div{
  position: relative;
  font-size: 12px;
}
.botom-stats td,th{
  font-size: 10px;
  font-weight: normal;
  border-top:none;
}
.botom-stats td{
  border-bottom:1px dashed #E1E0E5;
  border-left: none;
  border-right: none;
}
.botom-stats th{
  border-bottom:1px dashed #E1E0E5;
  border-right:1px dashed #E1E0E5;
}
.extra{
  border-right: 1px dashed #E1E0E5!important;
}
.extrath{
  border-bottom: none!important;
}
</style>
</head>

<body>
<div class="header">
  <div class="logo"><img src="../img/logoIzquierda.png">
  </div><div class="title">REPORTE DIARIO ETE
 
 </div> <div class="inhead">

  <table >
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
  $sum_dispon=0;
  $sum_recibidos=0;
                          while($row=mysqli_fetch_assoc($resss)):
                            if ($i==0) {
                              $transcur=strtotime($row['horadeldia_ajuste'])-strtotime("09:00:00"); 
                              $sum_muerto+=$transcur; 
                            }
                          
                            $sum_esper+=$row['produccion_esperada'];
                          $sum_merm+=$row['merma_entregada'];
                          $sum_real+=$row['entregados']-$row['merma_entregada'];
                          $sum_recibidos+=$row['cantidad'];
                           $sum_ajuste+=$row['seconds_ajuste'];
                            $sum_muerto+=$row['seconds_muertos_tiro'];
                            $sum_defectos+=$row['defectos'];
                            $sum_calidad+=$row['calidad'];
                            $sum_dispon+=$row['dispon_ajuste'];
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
                          $idtiro=$row['idtiraje'];
                          $alertaquery=$mysqli->query("SELECT *,TIME_TO_SEC(horadeldiaam)  AS inicio,TIME_TO_SEC(horafin_alerta) AS fin,  TIME_TO_SEC(tiempoalertamaquina) AS alert_real,TIME_TO_SEC(timediff(horafin_alerta ,horadeldiaam)) AS dispon_alertajuste FROM alertageneralajuste WHERE id_tiraje=$idtiro");
                          $alertaTiroAjuste=mysqli_fetch_assoc($mysqli->query("SELECT *,TIME_TO_SEC(horadeldiaam)  AS inicio,TIME_TO_SEC(horafin_alerta) AS fin,  TIME_TO_SEC(tiempoalertamaquina) AS alert_real,TIME_TO_SEC(timediff(horafin_alerta ,horadeldiaam)) AS dispon_alertajuste FROM alertamaquinaoperacion WHERE id_tiraje=$idtiro"));
                          

                          ?>
                          <tr>
     <td><?= substr($row['horadeldia_ajuste'],0,-3);?></td>                     
    <td><?= substr($row['horafin_ajuste'],0,-3);?></td>
    <td <?=($row['is_virtual']=='true')? 'style="color:red;"':'' ?> >Ajuste </td>
    <!-- <td <?=($row['is_virtual']=='true')? 'style="color:red;"':'' ?>><?=($row['is_virtual']=='true')? $row['odt_virtual'] : $row['numodt'];?> </td> -->
    <td>0</td>
    <td><?=gmdate("H:i",$row['dispon_ajuste']);  ?></td>
   
    <td><?=gmdate("H:i",$sum_dispon);  ?></td>
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
    while ($alertaAjuste=mysqli_fetch_array($alertaquery)) { ?>
     <tr class="alert">
     <td><?=gmdate("H:i", $alertaAjuste['inicio']) ?></td>                     
    <td><?=gmdate("H:i", $alertaAjuste['fin']) ?></td>
    <td>Alerta</td>
    <!-- <td <?=($row['is_virtual']=='true')? 'style="color:red;"':'' ?>><?=($row['is_virtual']=='true')? $row['odt_virtual'] : $row['numodt'];?> </td> -->
    <td>0</td>
    
   
    <td><?=gmdate("H:i", $alertaAjuste['dispon_alertajuste']) ?></td>
    <?php $sum_dispon+= $alertaAjuste['dispon_alertajuste'];?>
    <td><?=gmdate("H:i",$sum_dispon);  ?></td>
    <td>00:00</td>
  
    <td><?=gmdate("H:i", $sum_muerto); ?></td>
     <td><?=gmdate("H:i", $alertaAjuste['alert_real']) ?></td>
   <?php $sum_tiraje+=$alertaAjuste['alert_real']; ?>
    <td><?=gmdate("H:i", $sum_tiraje)  ?></td>
   
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
    <td colspan="3"><?=$alertaAjuste['observaciones'] ?></td>
    
    <!--
   
    <td><?= round($row['desempenio'],2);?>%</td> -->
  </tr>
    <?php }
     ?>                     <tr style=" background-color: #EBEBEB;">
     <td><?= substr($row['horadeldia_tiraje'],0,-3);?></td>                     
    <td><?= substr($row['horafin_tiraje'],0,-3);?></td>
    <td <?=($row['is_virtual']=='true')? 'style="color:red;"':'' ?> ><?=($row['is_virtual']=='true')? $row['elemento_virtual'] : $row['element'];?> </td>
    <!-- <td <?=($row['is_virtual']=='true')? 'style="color:red;"':'' ?>><?=($row['is_virtual']=='true')? $row['odt_virtual'] : $row['numodt'];?> </td> -->
    <td><?= $tiraje_estandar;?></td>
    <td><?=gmdate("H:i", $row['dispon_tiro'])  ?></td>
     <?php $sum_dispon+=$row['dispon_tiro'] ?>
    <?php $sum_muerto+=$row['seconds_muertos_tiro']; ?>
   <?php $sum_tiraje+=$row['seconds_tiraje']; ?>
    <td><?= gmdate("H:i",$sum_dispon);?></td>
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
  <?php
    if (!is_null($alertaTiroAjuste)) { ?>
     <tr class="alert">
     <td><?=gmdate("H:i", $alertaTiroAjuste['inicio']) ?></td>                     
    <td><?=gmdate("H:i", $alertaTiroAjuste['fin']) ?></td>
    <td>Alerta</td>
    <!-- <td <?=($row['is_virtual']=='true')? 'style="color:red;"':'' ?>><?=($row['is_virtual']=='true')? $row['odt_virtual'] : $row['numodt'];?> </td> -->
    <td>0</td>
    
   
    <td><?=gmdate("H:i", $alertaTiroAjuste['dispon_alertajuste']) ?></td>
    <?php $sum_dispon+= $alertaTiroAjuste['dispon_alertajuste'];?>
    <td><?=gmdate("H:i",$sum_dispon);  ?></td>
    <td>00:00</td>
  
    <td><?=gmdate("H:i", $sum_muerto); ?></td>
     <td><?=gmdate("H:i", $alertaTiroAjuste['alert_real']) ?></td>
   <?php $sum_tiraje+=$alertaTiroAjuste['alert_real']; ?>
    <td><?=gmdate("H:i", $sum_tiraje)  ?></td>
   
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
    <td colspan="3"><?=$alertaTiroAjuste['observaciones'] ?></td>
    
    <!--
   
    <td><?= round($row['desempenio'],2);?>%</td> -->
  </tr>
    <?php }
     ?>             
  <?php 
$i++;
   endwhile; ?>
  
  </tbody>
</table>
<?php $treal=$sum_tiraje-$sum_muerto; 
if ($sum_dispon>19800) {
  $sum_dispon-=3600;
}
if (isset($transcur)) {
 $sum_dispon+=$transcur;
}
?>
<div style="width: 103%; padding-top: 10px;margin: 0 auto!important">
  <div class="botom-stats" style="width: 24%;">
    <div style="width: 100%;height: 23px; border-bottom: 1px solid #E1E0E5; line-height:23px;text-align: center; vertical-align: middle;">
     <?php $dispon=$treal/$sum_dispon; ?>
      DISPONIBILIDAD= <?=round($dispon*100,2) ?>%
    </div><div style="width: 100%;">
      <table>
        <tr>
          <th>TIEMPO REAL</th>

          <td><?=gmdate("H:i",$treal) ?></td>
        </tr>
        <tr>
          <th class="extrath">TIEMPO DISPONIBLE</th>
          <td class="extrath"><?=gmdate("H:i",$sum_dispon) ?></td>
        </tr>
      </table>
    </div>

  </div><div class="botom-stats" style="width: 39%;">
    <div style="width: 100%;height: 23px; border-bottom: 1px solid #E1E0E5; line-height:23px;text-align: center; vertical-align: middle;">
     <?php $desempenio=($sum_real+$sum_merm)/$sum_esper; ?>
     DESEMPEÑO= <?=round($desempenio*100,2) ?>%
    </div><div style="width: 100%;">
      <table>
        
        <tr>
          <td class="extra">PRODUCCION REAL</td>
          <td class="extra"><?=$sum_real ?></td>
           <td class="extra">MERMA</td>
          <td><?=$sum_merm ?></td>
        </tr>
        <tr>
          <th class="extrath" colspan="2" style="border-right: 1px dashed #E1E0E5!important;">PRODUCCION ESPERADA</th>
          <th class="extrath" style="border:none!important;" colspan="2"><?=$sum_esper ?></th>
        </tr>
      </table>
    </div>
  </div><div class="botom-stats" style="width: 24%;">
    <div style="width: 100%;height: 23px; border-bottom: 1px solid #E1E0E5; line-height:23px;text-align: center; vertical-align: middle;">
    <?php $calidad=($sum_calidad+$sum_merm)/$sum_recibidos; ?>
      CALIDAD= <?=round($calidad*100,2) ?>%
    </div><div style="width: 100%;">
      <table>
        <tr>
          <th>CALIDAD A LA PRIMERA</th>
          <td><?=$sum_calidad ?></td>
        </tr>
        <tr>
          <th class="extrath">PRODUCCION REAL</th>
          <td class="extra" style="border:none!important;"><?=$sum_real ?></td>
        </tr>
      </table>
    </div>
  </div><div class="botom-stats" style="width: 9%;">
    <div style="width: 100%;height: 23px; border-bottom: 1px solid #E1E0E5; line-height:23px;text-align: center; vertical-align: middle;">
      ETE
    </div><div style="width: 100%; position: relative;">
      <table>
        <tr>
          <th style="color: #fff!important; border:none!important;">&nbsp</th>
          
        </tr>
         <tr>
          <th style="color: #fff!important; border:none!important;">&nbsp</th>
          
        </tr>
      </table>
      <div style="position: absolute;top: 0.5%;left: 20%; transform: translate(-50%, -50%);font-size: 30px;"><?=round(($dispon*$desempenio*$calidad)*100) ?>%</div>
    </div>
  </div>
</div>
</body>
</html>



<?php
$html = ob_get_clean();

$dompdf = new DOMPDF();
$dompdf->load_html($html);
$dompdf->set_paper('letter', 'landscape');
$dompdf->render();
$dompdf->stream("reporte_de_orden.pdf", array('Attachment' => 0));


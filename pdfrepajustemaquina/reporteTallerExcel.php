<?php

require_once("dompdf2/autoload.inc.php");
include '../saves/conexion.php';


$getUsers=$mysqli->query("SELECT * FROM usuarios WHERE app_active='true'");

$inicio=$_POST['dias'][0];
$fin=$_POST['dias'][5];
$sec_tiros='';
$sec_defectos='';
$sec_precios='';
$workQuery="SELECT t.id_proceso,(SELECT nombre_proceso FROM procesos_catalogo WHERE id_proceso=t.id_proceso) AS nom_proceso,(SELECT precio FROM procesos_catalogo WHERE id_proceso=t.id_proceso) AS precio FROM tiraje t WHERE id_proceso IS NOT NULL AND fechadeldia_ajuste BETWEEN '$inicio' AND '$fin'  GROUP BY id_proceso";

 $workProcess=$mysqli->query($workQuery);
$it=0;
while ($procs=mysqli_fetch_assoc($workProcess)) {
  $workProc[$it]['nombre_proceso']=$procs['nom_proceso'];
  $workProc[$it]['id_proceso']=$procs['id_proceso'];
  $workProc[$it]['precio']=$procs['precio'];
  $it++;
}
$rowspan=25+(count($workProc)*6);
while ($_user=mysqli_fetch_assoc($getUsers)) {
  $users[]=$_user;
}

$table='';
foreach ($users as $key => $user) {
  $dias=$_POST['dias'];
  $tbody='<tbody>';
  $total_dispon=0;
  $total_desemp=0;
  $total_calidad=0;
  $total_ete=0;
  $total_prod=0;
  $total_defec=0;
  $total_merma=0;
  $gran_total=0;
  $total_cambios=0;
  $promedio_dispon=0;
  $promedio_desemp=0;
  $promedio_calidad=0;
  $promedio_ete=0;
  $dispons=0;
  $desemps=0;
  $calidads=0;
  $etes=0;


  foreach ($dias as $d_key => $dia) {
   
  $userid= $user['id']; 
  $fecha=$dia;


  $getTiros=$mysqli->query("SELECT * FROM tiraje WHERE fechadeldia_ajuste='$fecha' AND cancelado='false' AND buenos IS NOT NULL AND id_user=".$userid);
  $tiroInfo=mysqli_fetch_assoc($getTiros);
  $cuero="SELECT TIME_FORMAT(MIN(horadeldia_tiraje), '%H:%i') AS inicial,TIME_FORMAT(MAX(horafin_tiraje), '%H:%i') AS final FROM tiraje WHERE fechadeldia_ajuste='$fecha' AND id_user=".$userid;

  $minMax=mysqli_fetch_assoc($mysqli->query($cuero));

  $real=mysqli_fetch_assoc($mysqli->query("SELECT TIME_FORMAT(SEC_TO_TIME((SUM(TIME_TO_SEC(tiempoTiraje))+SUM(TIME_TO_SEC(tiempo_ajuste))+IFNULL((SELECT SUM(TIME_TO_SEC(tiempoalertamaquina)) FROM alertageneralajuste WHERE fechadeldiaam = '$fecha' AND id_usuario =$userid AND es_tiempo_muerto='false'),0)+IFNULL((SELECT SUM(TIME_TO_SEC(tiempo)) FROM asaichi WHERE fechadeldia = '$fecha' AND id_usuario=$userid),0)-IFNULL(
(SELECT SUM(TIME_TO_SEC(breaktime)) FROM breaktime WHERE fechadeldiaam = '$fecha' AND id_usuario =$userid AND radios='Sanitario'),0))), '%H:%i') AS t_real,(SUM(TIME_TO_SEC(tiempoTiraje))+SUM(TIME_TO_SEC(tiempo_ajuste))+IFNULL((SELECT SUM(TIME_TO_SEC(tiempoalertamaquina)) FROM alertageneralajuste WHERE fechadeldiaam = '$fecha' AND id_usuario =$userid AND es_tiempo_muerto='false'),0)-IFNULL(
(SELECT SUM(TIME_TO_SEC(breaktime)) FROM breaktime WHERE fechadeldiaam = '$fecha' AND id_usuario =$userid AND radios='Sanitario'),0))+IFNULL((SELECT SUM(TIME_TO_SEC(tiempo)) FROM asaichi WHERE fechadeldia= '$fecha' AND id_usuario=$userid),0) AS sec_t_real FROM tiraje WHERE fechadeldia_ajuste = '$fecha' AND id_user =$userid"));


  $disponible=mysqli_fetch_assoc($mysqli->query("SELECT TIME_FORMAT(SEC_TO_TIME(((SUM(TIME_TO_SEC(horafin_tiraje) - TIME_TO_SEC(horadeldia_tiraje))+SUM(TIME_TO_SEC(horafin_ajuste)-TIME_TO_SEC(horadeldia_ajuste)))-IFNULL(
(SELECT SUM(TIME_TO_SEC(breaktime)) FROM breaktime WHERE fechadeldiaam = '$fecha' AND id_usuario =$userid),0))+IFNULL((SELECT SUM(TIME_TO_SEC(tiempo_muerto)) FROM tiempo_muerto WHERE fecha= '$fecha' AND id_user =$userid AND seccion='desfase'),0)), '%H:%i') AS disponible, ((SUM(TIME_TO_SEC(horafin_tiraje) - TIME_TO_SEC(horadeldia_tiraje))+SUM(TIME_TO_SEC(horafin_ajuste)-TIME_TO_SEC(horadeldia_ajuste)))-IFNULL(
(SELECT SUM(TIME_TO_SEC(breaktime)) FROM breaktime WHERE fechadeldiaam = '$fecha' AND id_usuario =$userid),0))+IFNULL((SELECT SUM(TIME_TO_SEC(tiempo_muerto)) FROM tiempo_muerto WHERE fecha='$fecha' AND id_user=$userid AND seccion='desfase'),0) AS sec_disponible FROM tiraje WHERE fechadeldia_ajuste = '$fecha' AND id_user =$userid"));


  $sumatorias=mysqli_fetch_assoc($mysqli->query("SELECT SUM(buenos)AS sum_prod_real,SUM(merma_entregada)AS sum_merma,SUM(entregados)AS sum_entregados,SUM(produccion_esperada)AS sum_prod_esperada,SUM(defectos)AS sum_defectos, (SUM(buenos)-SUM(merma_entregada))-SUM(defectos)AS sum_calidad_primera FROM tiraje WHERE fechadeldia_ajuste = '$fecha' AND id_user =$userid"));
  $dispon=(($disponible['sec_disponible']<=0)? 0: ($real['sec_t_real']/$disponible['sec_disponible'])*100);
  $dispon_tope= ($dispon>100)?100:$dispon;
  $desemp=( ($sumatorias['sum_prod_esperada']<=0)? 0: (($sumatorias['sum_prod_real']+$sumatorias['sum_merma'])/$sumatorias['sum_prod_esperada'])*100);
  $desemp_tope=($desemp>100)?100:$desemp;
  $calidad=(($sumatorias['sum_prod_real']<=0)? 0: ($sumatorias['sum_calidad_primera']/$sumatorias['sum_prod_real'])*100);
  $calidad_tope=($calidad>100)?100:$calidad;
  $final=(($calidad_tope/100)*($desemp_tope/100)*($dispon_tope/100))*100;

  $rell='';

  $total_dispon+=$dispon_tope;
  $total_desemp+=$desemp_tope;
  $total_calidad+=$calidad_tope;
  $total_ete+=$final;
  $total_prod+=$sumatorias['sum_prod_real'];
  $total_defec+=$sumatorias['sum_defectos'];
  $total_merma+=$sumatorias['sum_merma'];
  $gran_total+=$sumatorias['sum_entregados'];
  $total_cambios+=$getTiros->num_rows;
  $promedio_dispon+=$dispon_tope;
  $promedio_desemp+=$desemp_tope;
  $promedio_calidad+=$calidad_tope;
  $promedio_ete+=$final;


  ($dispon_tope>0)? $dispons++:'';
  ($desemp_tope>0)? $desemps++:'';
  ($calidad_tope>0)? $calidads++:'';
  ($final>0)? $etes++:'';
  $subtd='';
  $subtd2='';
  $subtd3='';
  $subtd4='';
  $subtd5='';
  $subtd6='';
  $subtd7='';
  $subtd8='';
  $subtd9='';
  $subtd10='';

  $tbody.='<tr>';
  $tbody.='<td>'.$user['logged_in'].'</td>';
  $tbody.='<td>'.$dia.'</td>';
  $tbody.='<td>'.round($dispon_tope,1).'%</td>';
  $tbody.='<td>'.round($desemp_tope,1).'%</td>';
  $tbody.='<td>'.round($calidad_tope,1).'</td>';
  $tbody.='<td>'.round($final,1).'</td>';
  $tbody.='<td>'.$minMax['inicial'].'</td>';
  $tbody.='<td>'.$minMax['final'].'</td>';
  $tbody.='<td>'.round($sumatorias['sum_prod_real'],1).'</td>';

  foreach ($workProc as $key => $tdSum1) {
    $getByProcess=mysqli_fetch_assoc($mysqli->query("SELECT SUM(buenos)AS total_buenos,  id_proceso FROM tiraje WHERE fechadeldia_ajuste='$dia' AND buenos IS NOT NULL AND id_user=".$user['id']."  AND id_proceso=".$tdSum1['id_proceso']." GROUP BY id_proceso"));
    $getChanges=$mysqli->query("SELECT buenos FROM tiraje WHERE fechadeldia_ajuste='$dia' AND buenos IS NOT NULL AND id_user=".$user['id']." AND cancelado='false'  AND id_proceso=".$tdSum1['id_proceso']);

        $subtd.="<td> ".(($getByProcess['id_proceso']==$tdSum1['id_proceso'])? $getChanges->num_rows:'')."</td><td>".(($getByProcess['id_proceso']==$tdSum1['id_proceso'])? round($getByProcess['total_buenos'],1):'').'</td>';
      }

  $tbody.=$subtd;

  

  $tbody.='<td>'.$sumatorias['sum_defectos'].'</td>';
  
   foreach ($workProc as $key => $tdSum2) {
     $getByProcess2=mysqli_fetch_assoc($mysqli->query("SELECT SUM(defectos)AS total_defectos,  id_proceso FROM tiraje WHERE fechadeldia_ajuste='$dia' AND buenos IS NOT NULL AND id_user=".$user['id']."  AND id_proceso=".$tdSum2['id_proceso']." GROUP BY id_proceso"));
   

        $subtd2.="<td> ".(($getByProcess2['id_proceso']==$tdSum2['id_proceso'])? $getByProcess2['total_defectos']:'')."</td>";
      }

  $tbody.=$subtd2;

  $tbody.='<td>'.$getTiros->num_rows.'</td>';
  $tbody.='<td>'.round($sumatorias['sum_prod_real'],1).'</td>';
  $tbody.='<td>'.round($sumatorias['sum_merma'],1).'</td>';
  $tbody.='<td>'.round($sumatorias['sum_entregados'],1).'</td>';

  foreach ($workProc as $key => $tdSum3) {
    $getByProcess3=mysqli_fetch_assoc($mysqli->query("SELECT SUM(buenos)AS total_buenos,SUM(defectos)AS total_defectos,  id_proceso FROM tiraje WHERE fechadeldia_ajuste='$dia' AND buenos IS NOT NULL AND id_user=".$user['id']."  AND id_proceso=".$tdSum3['id_proceso']." GROUP BY id_proceso"));

        $subtd3.="<td> ".(($getByProcess3['id_proceso']==$tdSum3['id_proceso'])? round(($getByProcess3['total_buenos']-$getByProcess3['total_defectos']),1):'')."</td>";
      }

  $tbody.=$subtd3;

  foreach ($workProc as $key => $tdSum4) {

        $subtd4.="<td> ".(($tiroInfo['id_proceso']==$tdSum4['id_proceso'])? '$'.round($tdSum4['precio'],2):'')."</td>";
      }

  $tbody.=$subtd4;

  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.$rell.'</td>';


  foreach ($workProc as $key => $tdSum5) {
     $getByProcess5=mysqli_fetch_assoc($mysqli->query("SELECT SUM(buenos)AS total_buenos,SUM(defectos)AS total_defectos,  id_proceso FROM tiraje WHERE fechadeldia_ajuste='$dia' AND buenos IS NOT NULL AND id_user=".$user['id']."  AND id_proceso=".$tdSum5['id_proceso']." GROUP BY id_proceso"));

        $subtd5.="<td> ".(($getByProcess5['id_proceso']==$tdSum5['id_proceso'])? '$'.round((($getByProcess5['total_buenos']-$getByProcess5['total_defectos'])*$tdSum5['precio']),1):'')."</td>";
      }

  $tbody.=$subtd5;


  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.$rell.'</td>';
  
  
  $tbody.='</tr>';

  //fila resultados
  

  }//termina foreach dias
  $tbody.='<tr class="results">';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.(($dispons>0)? round(($promedio_dispon/$dispons),1):'').'%</td>';
  $tbody.='<td>'.(($desemps>0)? round(($promedio_desemp/$desemps),1):'').'%</td>';
  $tbody.='<td>'.(($calidads>0)? round(($promedio_calidad/$calidads),1):'').'%</td>';
  $tbody.='<td>'.(($etes>0)? round(($promedio_ete/$etes),1):'').'%</td>';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.round($total_prod,1).'</td>';

foreach ($workProc as $key => $tdSum6) {
        $subtd6.="<td>".(($tiroInfo['id_proceso']==$tdSum6['id_proceso'])? $total_cambios:'')."</td><td>".(($tiroInfo['id_proceso']==$tdSum6['id_proceso'])? $total_prod:'')."</td>";
      }

  $tbody.=$subtd6;

 
  
  $tbody.='<td>'.$total_defec.'</td>';
  
foreach ($workProc as $key => $tdSum7) {
        $subtd7.="<td>".(($tiroInfo['id_proceso']==$tdSum7['id_proceso'])? '':'')."</td>";
      }

  $tbody.=$subtd7;



  $tbody.='<td>'.round($total_cambios,1).'</td>';
  $tbody.='<td>'.round($total_prod,1).'</td>';
  $tbody.='<td>'.round($total_merma,1).'</td>';
  $tbody.='<td>'.round($gran_total,1).'</td>';

foreach ($workProc as $key => $tdSum8) {
        $subtd8.="<td>".(($tiroInfo['id_proceso']==$tdSum8['id_proceso'])? $total_prod-$total_defec:'')."</td>";
      }

  $tbody.=$subtd8;
foreach ($workProc as $key => $tdSum9) {
        $subtd9.="<td>".(($tiroInfo['id_proceso']==$tdSum9['id_proceso'])? '$'.round($tdSum9['precio'],2):'')."</td>";
      }

  $tbody.=$subtd9;


  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.$rell.'</td>';
  
foreach ($workProc as $key => $tdSum10) {
        $subtd10.="<td>".(($tiroInfo['id_proceso']==$tdSum10['id_proceso'])? '$'.round((($total_prod-$total_defec)*$tdSum10['precio']),2):'$0.00')."</td>";
      }

  $tbody.=$subtd10;



  $tbody.='<td>$'.round((($total_prod-$total_defec)*$tdSum10['precio']),2).'</td>';
  $tbody.='<td>$'.$user['sueldo'].'</td>';
  $tbody.='<td>$'.round((($total_prod-$total_defec)*$tdSum10['precio'])-$user['sueldo'],2).'</td>';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.$rell.'</td>';
  
 

  $tbody.='</tr>';
  $tbody.='</tbody>';
  $table.=$tbody;

}//termina foreach users


?>
<script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<script type="text/javascript" src="../js/libs/js-xlsx/xlsx.core.min.js"></script>
  <script type="text/javascript" src="../js/libs/FileSaver/FileSaver.min.js"></script>
 <script type="text/javascript" src="../js/libs/tableExport.js"></script>
 <script>
  
    function doExport(selector, params) {
      var options = {
        //ignoreRow: [1,11,12,-2],
        //ignoreColumn: [0,-1],
        //pdfmake: {enabled: true},
        tableName: 'Table name'
      };

      jQuery.extend(true, options, params);

      $(selector).tableExport(options);
    }

     $(document).ready(function(event) {
      doExport('#info', {type: 'xlsx'});
});

 </script>
  <style>
  @page{
    margin:1.3em
}
 #info{
    font-family:Arial,Helvetica,sans-serif;
    border-collapse:collapse;
    width:100%;
    text-align:center;
   font-size:7px;
}
 #info td{
    border:1px solid #ccc;
    padding:1px

}
 #info tbody:nth-child(even){
    background-color:#EBEBEB
}


.theader{
    text-transform:uppercase;
    text-align:center;
    background-color:#000;
    color:#fff;
    font-size:5px
}
#resumen{
    font-family:Arial,Helvetica,sans-serif;
    border-collapse:collapse;
    width:100%;
    text-align:center;
    font-size:8px
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
.results{
  background: #a9a6a6;
}
</style>


<div style="height: 8px;"></div>
<table id="info">
<thead>
  <tr class="theader">
    <td colspan="<?=$rowspan; ?>">ETE TALLER</td>
    
  </tr>
  <tr class="theader">
    <td>Operario</td>
    <td>Fecha</td>
    <td>Dispon</td>
    <td>Desempeño</td>
    <td>Calidad</td>
    <td>Ete</td>
    <td>Inicio</td>
    <td>Fin</td>
    <td>Prod</td>
    <?php 
      foreach ($workProc as $key => $work) {
        echo "<td colspan='2'>".substr($work['nombre_proceso'], 0, 4)."</td>";
      }

    ?>
    <td>Total Def</td>
   <?php 
      foreach ($workProc as $key => $workDef) {
        echo "<td> Def. ".substr($workDef['nombre_proceso'], 0, 4)."</td>";
      }

    ?>
    <td>total de cambios</td>
    <td>tiros</td>
    <td>merma</td>
    <td>gran total</td>
    <?php 
      foreach ($workProc as $key => $work3) {
        echo "<td>".substr($work3['nombre_proceso'], 0, 4)."</td>";
      }

    ?>
    <?php 
      foreach ($workProc as $key => $workPrice) {
        echo "<td> Precio ".substr($workPrice['nombre_proceso'], 0, 4)."</td>";
      }

    ?>
    <td>cambio grabado</td>
    <td>cambio hs</td>
    <td>cambio sua</td>
    <td>cambio ple</td>
    <?php 
      foreach ($workProc as $key => $work4) {
        echo "<td>".substr($work4['nombre_proceso'], 0, 4)."</td>";
      }

    ?>
    <td>total</td>
   <td>sueldo</td>
    <td>diferencia</td>
    <td>renum por tiros</td>
    <td>renum por cambios</td>
    <td>por defectos</td>
    <td>a pagar</td>
    
    
  </tr>
  </thead>

  
  <?=  $table; ?>
 
</table>
<div style="height: 8px;"></div>


<?php

 $html = ob_get_clean();

$dompdf = new Dompdf();
$dompdf->load_html($html);
$dompdf->set_paper('legal', 'landscape');
$dompdf->render();
$dompdf->stream("reporte_de_orden.pdf", array(
    'Attachment' => 0
));


?>
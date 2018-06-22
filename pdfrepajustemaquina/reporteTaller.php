<?php

require_once("dompdf2/autoload.inc.php");
include '../saves/conexion.php';
use Dompdf\Dompdf;

$getUsers=$mysqli->query("SELECT * FROM usuarios WHERE app_active='true' ORDER BY report_sorting");

$inicio=$_POST['dias'][0];
$fin=$_POST['dias'][5];
$sec_tiros='';
$sec_defectos='';
$sec_precios='';

while ($_user=mysqli_fetch_assoc($getUsers)) {
  $users[]=$_user;
}

$table='';
$u=0;
if (isset($_POST['pdf'])) {
foreach ($users as $key => $user){

    $workQuery="SELECT t.id_proceso,(SELECT nombre_proceso FROM procesos_catalogo WHERE id_proceso=t.id_proceso) AS nom_proceso,(SELECT precio FROM procesos_catalogo WHERE id_proceso=t.id_proceso) AS precio FROM tiraje t WHERE id_proceso IS NOT NULL AND STR_TO_DATE(fechadeldia_ajuste , '%d-%m-%Y') BETWEEN STR_TO_DATE('$inicio' , '%d-%m-%Y') AND STR_TO_DATE('$fin' , '%d-%m-%Y') AND id_user=".$user['id']."  GROUP BY id_proceso";

$workProc=array();

$workProcess=$mysqli->query($workQuery);
$it=0;
while ($procs=mysqli_fetch_assoc($workProcess)) {
  $workProc[$it]['nombre_proceso']=$procs['nom_proceso'];
  $workProc[$it]['id_proceso']=$procs['id_proceso'];
  $workProc[$it]['precio']=$procs['precio'];
  
  
  $it++;
}

$rowspan=23+(count($workProc)*6);


  $dias=$_POST['dias'];
  
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
$tbody='<div class="t-container"><table class="info"><thead><tr class="theader oper"><td colspan="'.$rowspan.'">'.$user['logged_in'].' '.$user['apellido'].'</td></tr>';
$tbody.='<tr class="theader">';
   
    $tbody.='<td>FECHA</td>';
    $tbody.='<td>DISPON</td>';
    $tbody.='<td>DESEMPEÑO</td>';
    $tbody.='<td>CALIDAD</td>';
    $tbody.='<td>ETE</td>';
    $tbody.='<td>INICIO</td>';
    $tbody.='<td>FIN</td>';
    $tbody.='<td>PROD</td>';
   
      foreach ($workProc as $key => $work) {
        $tbody.="<td>Cambios ".$work['nombre_proceso']."</td>";
        $tbody.="<td>Tiros ".$work['nombre_proceso']."</td>";
      }

    
    
    
      foreach ($workProc as $key => $workDef) {
        $tbody.="<td style='display:none'> DEFECTOS ".$workDef['nombre_proceso']."</td>";
      }

    
    $tbody.='<td>TOTAL DE CAMBIOS</td>';
    $tbody.='<td>CAMBIOS LARGOS</td>';
    $tbody.='<td>TIROS LARGOS</td>';
    $tbody.='<td>TIROS</td>';
    $tbody.='<td>MERMA</td>';
    $tbody.='<td>GRAN TOTAL</td>';
    $tbody.='<td>TOTAL DEFECTOS</td>';
    $tbody.='<td>TOTAL MENOS DEFECTOS</td>';
    
     
    
   
      foreach ($workProc as $key => $workPrice) {
        $tbody.="<td style='display:none'> COSTO ".substr($workPrice['nombre_proceso'], 0, 4)."</td>";
      }

      foreach ($workProc as $key => $work4) {
        $tbody.="<td style='display:none'>TOTAL COSTO ".substr($work4['nombre_proceso'], 0, 4)."</td>";
      }
    $tbody.='<td style="display:none">COSTO TIROS LARGOS</td>';
    $tbody.='<td style="display:none">TOTAL COSTO TIROS LARGOS</td>';
    $tbody.='<td style="display:none">TOTAL</td>';
    $tbody.='<td style="display:none">SUELDO</td>';
    $tbody.='<td style="display:none">DIFERENCIA</td>';
    if ($user['id']==16||$user['id']==14||$user['id']==8) {
    $tbody.='<td>remun POR TIROS</td>';
    }else{
    $tbody.='<td>remun POR CAMBIOS</td>';
    }

    
   
    $tbody.='<td>POR DEFECTOS</td>';
    $tbody.='<td>A PAGAR</td>';
    
    
  $tbody.='</tr></thead>';

$sum_largos=0;

$tbody.='<tbody>';
$clargos=0;
  foreach ($dias as $d_key => $dia) {
   
  $userid= $user['id']; 
  $fecha=$dia;


  $getTiros=$mysqli->query("SELECT * FROM tiraje WHERE fechadeldia_ajuste='$fecha' AND entregados<500 AND buenos IS NOT NULL AND buenos NOT IN (0) AND id_user=".$userid);
  $tiroInfo=mysqli_fetch_assoc($getTiros);
  $cuero="SELECT TIME_FORMAT(SEC_TO_TIME(MIN((TIME_TO_SEC(horadeldia_ajuste)))), '%H:%i') AS inicial,TIME_FORMAT(SEC_TO_TIME(MAX((TIME_TO_SEC(horafin_tiraje)))), '%H:%i') AS final FROM tiraje WHERE fechadeldia_ajuste='$fecha' AND id_user=".$userid;

  $minMax=mysqli_fetch_assoc($mysqli->query($cuero));

  $real=mysqli_fetch_assoc($mysqli->query("SELECT TIME_FORMAT(SEC_TO_TIME((SUM(TIME_TO_SEC(tiempoTiraje))+SUM(TIME_TO_SEC(tiempo_ajuste))+IFNULL((SELECT SUM(TIME_TO_SEC(tiempoalertamaquina)) FROM alertageneralajuste WHERE fechadeldiaam = '$fecha' AND id_usuario =$userid AND es_tiempo_muerto='false'),0)+IFNULL((SELECT SUM(TIME_TO_SEC(tiempo)) FROM asaichi WHERE fechadeldia = '$fecha' AND id_usuario=$userid),0)-IFNULL(
(SELECT SUM(TIME_TO_SEC(breaktime)) FROM breaktime WHERE fechadeldiaam = '$fecha' AND id_usuario =$userid AND radios='Sanitario'),0))), '%H:%i') AS t_real,(SUM(TIME_TO_SEC(tiempoTiraje))+SUM(TIME_TO_SEC(tiempo_ajuste))+IFNULL((SELECT SUM(TIME_TO_SEC(tiempoalertamaquina)) FROM alertageneralajuste WHERE fechadeldiaam = '$fecha' AND id_usuario =$userid AND es_tiempo_muerto='false'),0)-IFNULL(
(SELECT SUM(TIME_TO_SEC(breaktime)) FROM breaktime WHERE fechadeldiaam = '$fecha' AND id_usuario =$userid AND radios='Sanitario'),0))+IFNULL((SELECT SUM(TIME_TO_SEC(tiempo)) FROM asaichi WHERE fechadeldia= '$fecha' AND id_usuario=$userid),0) AS sec_t_real FROM tiraje WHERE fechadeldia_ajuste = '$fecha' AND id_user =$userid"));

  $disponible=mysqli_fetch_assoc($mysqli->query("SELECT TIME_FORMAT(SEC_TO_TIME(((SUM(TIME_TO_SEC(horafin_tiraje) - TIME_TO_SEC(horadeldia_tiraje))+SUM(TIME_TO_SEC(horafin_ajuste)-TIME_TO_SEC(horadeldia_ajuste)))-IFNULL(
(SELECT SUM(TIME_TO_SEC(breaktime)) FROM breaktime WHERE fechadeldiaam = '$fecha' AND id_usuario =$userid),0))+IFNULL((SELECT SUM(TIME_TO_SEC(tiempo_muerto)) FROM tiempo_muerto WHERE fecha= '$fecha' AND id_user =$userid AND seccion='desfase'),0)), '%H:%i') AS disponible, ((SUM(TIME_TO_SEC(horafin_tiraje) - TIME_TO_SEC(horadeldia_tiraje))+SUM(TIME_TO_SEC(horafin_ajuste)-TIME_TO_SEC(horadeldia_ajuste)))-IFNULL(
(SELECT SUM(TIME_TO_SEC(breaktime)) FROM breaktime WHERE fechadeldiaam = '$fecha' AND id_usuario =$userid),0))+IFNULL((SELECT SUM(TIME_TO_SEC(tiempo_muerto)) FROM tiempo_muerto WHERE fecha='$fecha' AND id_user=$userid AND seccion='desfase'),0) AS sec_disponible FROM tiraje WHERE fechadeldia_ajuste = '$fecha' AND id_user =$userid"));

  $sumatorias=mysqli_fetch_assoc($mysqli->query("SELECT SUM(buenos)AS sum_prod_real,SUM(merma_entregada)AS sum_merma,SUM(buenos)+SUM(merma_entregada)AS sum_entregados,SUM(produccion_esperada)AS sum_prod_esperada,SUM(defectos)AS sum_defectos, SUM(buenos)-SUM(defectos)AS sum_calidad_primera FROM tiraje WHERE fechadeldia_ajuste = '$fecha' AND id_user =$userid AND buenos IS NOT NULL AND buenos NOT IN (0) AND entregados<500"));
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
  
  $tbody.='<td>'.$dia.'</td>';
  $tbody.='<td>'.round($dispon_tope,1).'%</td>';
  $tbody.='<td>'.round($desemp_tope,1).'%</td>';
  $tbody.='<td>'.round($calidad_tope,1).'%</td>';
  $tbody.='<td style="background:#ccc">'.round($final,1).'%</td>';
  $tbody.='<td>'.$minMax['inicial'].'</td>';
  $tbody.='<td>'.$minMax['final'].'</td>';
  $tbody.='<td style="background:#ccc">'.round($sumatorias['sum_prod_real'],1).'</td>';
  
  foreach ($workProc as $key => $tdSum1) {
    $getByProcess=mysqli_fetch_assoc($mysqli->query("SELECT SUM(buenos)AS total_buenos, COUNT(*)AS cambios, id_proceso FROM tiraje WHERE fechadeldia_ajuste='$dia' AND entregados<500 AND buenos IS NOT NULL AND buenos NOT IN (0) AND id_user=".$user['id']."  AND id_proceso=".$tdSum1['id_proceso']." GROUP BY id_proceso"));
   

        $subtd.="<td> ".(($getByProcess['id_proceso']==$tdSum1['id_proceso'])? $getByProcess['cambios']:'')."</td><td style='background:#ccc;border-right:solid 1px #000;'>".(($getByProcess['id_proceso']==$tdSum1['id_proceso'])? round($getByProcess['total_buenos'],1):'').'</td>';

      }

  $tbody.=$subtd;

  

  
  
   foreach ($workProc as $key => $tdSum2) {
     $getByProcess2=mysqli_fetch_assoc($mysqli->query("SELECT SUM(defectos)AS total_defectos,  id_proceso FROM tiraje WHERE fechadeldia_ajuste='$dia' AND buenos IS NOT NULL AND id_user=".$user['id']."  AND id_proceso=".$tdSum2['id_proceso']." GROUP BY id_proceso"));
   

        $subtd2.="<td style='display:none'> ".(($getByProcess2['id_proceso']==$tdSum2['id_proceso'])? $getByProcess2['total_defectos']:'')."</td>";
      }

  $tbody.=$subtd2;

  $l_query="SELECT SUM(entregados)-SUM(defectos)AS largos FROM tiraje WHERE entregados>=500 AND fechadeldia_ajuste='$dia' AND buenos IS NOT NULL AND id_user=".$user['id'];
  $tlargos=$mysqli->query($l_query);
  $tirosLargos=mysqli_fetch_assoc($tlargos);
  $sum_largos+=$tirosLargos['largos'];
  if (!empty($tirosLargos['largos'])) {
    $clargos+=$tlargos->num_rows;
  }

  $tbody.='<td>'.$getTiros->num_rows.'</td>';
   $tbody.='<td>'.((!empty($tirosLargos['largos']))? $tlargos->num_rows: '0').'</td>';
  $tbody.='<td style="background:#ccc;border-right:solid 1px #000;">'.round($tirosLargos['largos'],1).'</td>';
  $tbody.='<td style="background:#ccc;">'.round($sumatorias['sum_prod_real'],1).'</td>';
  $tbody.='<td>'.round($sumatorias['sum_merma'],1).'</td>';
  $tbody.='<td>'.round($sumatorias['sum_entregados'],1).'</td>';


$getDefs=mysqli_fetch_assoc($mysqli->query("SELECT SUM(buenos)+SUM(merma_entregada)AS total_buenos,SUM(defectos)AS total_defectos FROM tiraje WHERE fechadeldia_ajuste='$dia' AND buenos IS NOT NULL AND id_user=".$user['id']." GROUP BY id_proceso"));
$tbody.='<td>'.$sumatorias['sum_defectos'].'</td>';
$tbody.="<td> ".round(($getDefs['total_buenos']-$getDefs['total_defectos']),1)."</td>";



  $tbody.=$subtd3;

  foreach ($workProc as $key => $tdSum4) {

  
     $getByProcess4=mysqli_fetch_assoc($mysqli->query("SELECT id_proceso FROM tiraje WHERE fechadeldia_ajuste='$dia' AND buenos IS NOT NULL AND id_user=".$user['id']."  AND id_proceso=".$tdSum4['id_proceso']." GROUP BY id_proceso"));

     $subtd4.="<td style='display:none'> ".(($getByProcess4['id_proceso']==$tdSum4['id_proceso'])? '$'.round($tdSum4['precio'],2):'')."</td>";

       
      }

  $tbody.=$subtd4;

  foreach ($workProc as $key => $tdSum5) {
     $getByProcess5=mysqli_fetch_assoc($mysqli->query("SELECT SUM(entregados)AS total_buenos,SUM(defectos)AS total_defectos,  id_proceso FROM tiraje WHERE fechadeldia_ajuste='$dia' AND entregados<500 AND buenos NOT IN (0) AND buenos IS NOT NULL AND id_user=".$user['id']."  AND id_proceso=".$tdSum5['id_proceso']." GROUP BY id_proceso"));

        $subtd5.="<td style='display:none'> ".(($getByProcess5['id_proceso']==$tdSum5['id_proceso'])? '$'.round((($getByProcess5['total_buenos']-$getByProcess5['total_defectos'])*$tdSum5['precio']),1):'')."</td>";
      }

  $tbody.=$subtd5;

  $tbody.='<td style="display:none">'.(($tirosLargos['largos']>0)? '$0.20': '').'</td>';
  $tbody.='<td style="display:none">'.(($tirosLargos['largos']>0)? '$'.$tirosLargos['largos']*0.20: '').'</td>';

  $tbody.='<td style="display:none">'.$rell.'</td>';
  $tbody.='<td style="display:none">'.$rell.'</td>';
  $tbody.='<td style="display:none">'.$rell.'</td>';
  $tbody.='<td style="display:none">'.$rell.'</td>';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.$rell.'</td>';
  
  
  $tbody.='</tr>';

  //fila resultados
  
  }//termina foreach dias
  $tbody.='<tr class="results">';
  
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.(($dispons>0)? round(($promedio_dispon/$dispons),1):'').'%</td>';
  $tbody.='<td>'.(($desemps>0)? round(($promedio_desemp/$desemps),1):'').'%</td>';
  $tbody.='<td>'.(($calidads>0)? round(($promedio_calidad/$calidads),1):'').'%</td>';
  $tbody.='<td>'.(($etes>0)? round(($promedio_ete/$etes),1):'').'%</td>';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.round($total_prod,1).'</td>';

foreach ($workProc as $key => $tdSum6) {
      $tch="SELECT SUM(buenos)AS total_buenos, COUNT(*)AS cambios,  id_proceso FROM tiraje WHERE STR_TO_DATE(fechadeldia_ajuste , '%d-%m-%Y') BETWEEN STR_TO_DATE('$inicio' , '%d-%m-%Y') AND STR_TO_DATE('$fin' , '%d-%m-%Y')  AND cancelado=2 AND buenos IS NOT NULL AND buenos NOT IN (0) AND entregados<500 AND id_user=".$user['id']."  AND id_proceso=".$tdSum6['id_proceso']." GROUP BY id_proceso";
        $getTotalChanges=mysqli_fetch_assoc($mysqli->query($tch));

        $subtd6.="<td>". (($getTotalChanges['id_proceso']==$tdSum6['id_proceso'])?$getTotalChanges['cambios'] :'' )."</td><td>".(($getTotalChanges['id_proceso']==$tdSum6['id_proceso'])?$getTotalChanges['total_buenos'] :'')."</td>";

      }

  $tbody.=$subtd6;
  

  
foreach ($workProc as $key => $tdSum7) {
        $subtd7.="<td style='display:none'>".(($tiroInfo['id_proceso']==$tdSum7['id_proceso'])? '':'')."</td>";
      }

  $tbody.=$subtd7;
  $tbody.='<td>'.round($total_cambios,1).'</td>';
  $tbody.='<td>'.$clargos.'</td>';
   $tbody.='<td>'.round($sum_largos,1).'</td>';
  $tbody.='<td>'.round($total_prod,1).'</td>';
  $tbody.='<td>'.round($total_merma,1).'</td>';
  $tbody.='<td>'.round($gran_total,1).'</td>';


$total_def=($total_prod+$total_merma)-$total_defec;
 $tbody.='<td>'.$total_defec.'</td>';
  $tbody.="<td>".$total_def."</td>";

  $tbody.=$subtd8;
foreach ($workProc as $key => $tdSum9) {
        $subtd9.="<td style='display:none'>".(($tiroInfo['id_proceso']==$tdSum9['id_proceso'])? '$'.round($tdSum9['precio'],2):'')."</td>";
      }

  $tbody.=$subtd9;

 $precios=0;
foreach ($workProc as $key => $tdSum10) {
       
        $getByProcess10=mysqli_fetch_assoc($mysqli->query("SELECT SUM(buenos)AS total_buenos,(SELECT precio FROM procesos_catalogo WHERE id_proceso=".$tdSum10['id_proceso'].")AS s_price,SUM(defectos)AS total_defectos,  id_proceso FROM tiraje WHERE STR_TO_DATE(fechadeldia_ajuste , '%d-%m-%Y') BETWEEN STR_TO_DATE('$inicio' , '%d-%m-%Y') AND STR_TO_DATE('$fin' , '%d-%m-%Y') AND buenos IS NOT NULL AND id_user=".$user['id']." AND entregados<500
AND buenos NOT IN (0) AND id_proceso=".$tdSum10['id_proceso']." GROUP BY id_proceso"));

        $subtd10.="<td style='display:none'> ".(($getByProcess10['id_proceso']==$tdSum10['id_proceso'])? '$'.round((($getByProcess10['total_buenos']-$getByProcess10['total_defectos'])*$getByProcess10['s_price']),1):'')."</td>";
        $precios+=($getByProcess10['total_buenos']-$getByProcess10['total_defectos'])*$tdSum10['precio'];
      }

  $tbody.=$subtd10;
$diferencia=($precios+($sum_largos*0.20))-$user['sueldo'];
 

  $tbody.='<td style="display:none">'.$rell.'</td>';
  $tbody.='<td style="display:none">$'.round($sum_largos*0.20,1).'</td>';

  $tbody.='<td style="display:none">$'.round($precios+($sum_largos*0.20),1).'</td>';
  $tbody.='<td style="display:none">$'.$user['sueldo'].'</td>';
  $tbody.='<td style="display:none">$'.round($diferencia,1).'</td>';
  if ($user['id']==16||$user['id']==14||$user['id']==8) {
     $renum_tiros=(count($workProc)==1)? (($workProc[0]['id_proceso']==10)? (($total_def>7500)? $diferencia:'0.00'):(($diferencia>0)? $diferencia:'0.00')):(($diferencia>0)? $diferencia:'0.00');

   $tbody.='<td>$'.round($renum_tiros,1).'</td>';
    }else{

      
        if ($user['id']==15) {
         
        if ($total_cambios>30) {
          $cambio_21=$total_cambios-30;
          $renum_cambios=$cambio_21*40;
      }else{
        $renum_cambios=0;
      }
        }else{
        if ($total_cambios>20) {
          $cambio_21=$total_cambios-20;
          $renum_cambios=$cambio_21*40;
      }else{
        $renum_cambios=0;
      }
           
        }
       

      

    $tbody.='<td>$'.round($renum_cambios,1).'</td>';
    }
  
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td style="display:none">'.$rell.'</td>';
 

  $tbody.='</tr>';
  $tbody.='</tbody></table></div>';
  
  $table.=$tbody;

  $u++;

}//termina foreach users

?>
<?php
ob_start();
?>
<script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<script type="text/javascript" src="../js/libs/js-xlsx/xlsx.core.min.js"></script>
<script type="text/javascript" src="../js/libs/FileSaver/FileSaver.min.js"></script>
 <script type="text/javascript" src="../js/libs/tableExport.js"></script>
  <style>
  @page{
    margin:1.3em
    page-break-inside: auto;
}
.info{
    font-family:Arial,Helvetica,sans-serif;
    border-collapse:collapse;
    width:100%;
    text-align:center;
    font-size:10px
   
}
.info td{
    
    padding:1px
}
.info th{
  font-weight: bold;
}
.info tbody>tr:nth-child(even){
   /* background-color:#EBEBEB */
}
.oper{
    font-size:12px!important;
    text-align:left!important
}
.theader{
    text-transform:uppercase;
    text-align:center;
    
    
    font-size:7px
}
.oper td{
    padding:1px 2px!important
    
}

.t-container{
  page-break-inside: avoid;
  padding-bottom: 15px; 
}

.results td{
  border-top: solid 1px #000!important;
  border-bottom: solid 1px #000!important;  
  font-weight: bold;
}


</style>


<div style="height: 8px;"></div>

  <?= $table; ?>
 
<div style="height: 8px;"></div>

<?php

  
  $html = ob_get_clean();


$dompdf = new Dompdf();
$dompdf->load_html($html);
$dompdf->set_paper('letter', 'landscape');
$dompdf->render();
$dompdf->stream("reporte_de_orden.pdf", array(
    'Attachment' => 0
));


}
elseif (isset($_POST['xlsx'])) { 
  $workQuery="SELECT t.id_proceso,(SELECT nombre_proceso FROM procesos_catalogo WHERE id_proceso=t.id_proceso) AS nom_proceso,(SELECT precio FROM procesos_catalogo WHERE id_proceso=t.id_proceso) AS precio FROM tiraje t WHERE id_proceso IS NOT NULL AND STR_TO_DATE(fechadeldia_ajuste , '%d-%m-%Y') BETWEEN STR_TO_DATE('$inicio' , '%d-%m-%Y') AND STR_TO_DATE('$fin' , '%d-%m-%Y')  GROUP BY id_proceso";

 $workProcess=$mysqli->query($workQuery);
$it=0;
while ($procs=mysqli_fetch_assoc($workProcess)) {
  $workProc[$it]['nombre_proceso']=$procs['nom_proceso'];
  $workProc[$it]['id_proceso']=$procs['id_proceso'];
  $workProc[$it]['precio']=$procs['precio'];
  $it++;
}
$rowspan=27+(count($workProc)*6);

foreach ($users as $key => $user) {
  $dias=$_POST['dias'];
  $sum_largos=0;
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

  $getTiros=$mysqli->query("SELECT * FROM tiraje WHERE fechadeldia_ajuste='$fecha' AND cancelado='false' AND entregados<500 AND buenos IS NOT NULL AND buenos IS NOT NULL AND id_user=".$userid);
  $tiroInfo=mysqli_fetch_assoc($getTiros);
  $cuero="SELECT TIME_FORMAT(MIN(horadeldia_tiraje), '%H:%i') AS inicial,TIME_FORMAT(MAX(horafin_tiraje), '%H:%i') AS final FROM tiraje WHERE fechadeldia_ajuste='$fecha' AND id_user=".$userid;

  $minMax=mysqli_fetch_assoc($mysqli->query($cuero));

  $real=mysqli_fetch_assoc($mysqli->query("SELECT TIME_FORMAT(SEC_TO_TIME((SUM(TIME_TO_SEC(tiempoTiraje))+SUM(TIME_TO_SEC(tiempo_ajuste))+IFNULL((SELECT SUM(TIME_TO_SEC(tiempoalertamaquina)) FROM alertageneralajuste WHERE fechadeldiaam = '$fecha' AND id_usuario =$userid AND es_tiempo_muerto='false'),0)+IFNULL((SELECT SUM(TIME_TO_SEC(tiempo)) FROM asaichi WHERE fechadeldia = '$fecha' AND id_usuario=$userid),0)-IFNULL(
(SELECT SUM(TIME_TO_SEC(breaktime)) FROM breaktime WHERE fechadeldiaam = '$fecha' AND id_usuario =$userid AND radios='Sanitario'),0))), '%H:%i') AS t_real,(SUM(TIME_TO_SEC(tiempoTiraje))+SUM(TIME_TO_SEC(tiempo_ajuste))+IFNULL((SELECT SUM(TIME_TO_SEC(tiempoalertamaquina)) FROM alertageneralajuste WHERE fechadeldiaam = '$fecha' AND id_usuario =$userid AND es_tiempo_muerto='false'),0)-IFNULL(
(SELECT SUM(TIME_TO_SEC(breaktime)) FROM breaktime WHERE fechadeldiaam = '$fecha' AND id_usuario =$userid AND radios='Sanitario'),0))+IFNULL((SELECT SUM(TIME_TO_SEC(tiempo)) FROM asaichi WHERE fechadeldia= '$fecha' AND id_usuario=$userid),0) AS sec_t_real FROM tiraje WHERE fechadeldia_ajuste = '$fecha' AND id_user =$userid"));

  $disponible=mysqli_fetch_assoc($mysqli->query("SELECT TIME_FORMAT(SEC_TO_TIME(((SUM(TIME_TO_SEC(horafin_tiraje) - TIME_TO_SEC(horadeldia_tiraje))+SUM(TIME_TO_SEC(horafin_ajuste)-TIME_TO_SEC(horadeldia_ajuste)))-IFNULL(
(SELECT SUM(TIME_TO_SEC(breaktime)) FROM breaktime WHERE fechadeldiaam = '$fecha' AND id_usuario =$userid),0))+IFNULL((SELECT SUM(TIME_TO_SEC(tiempo_muerto)) FROM tiempo_muerto WHERE fecha= '$fecha' AND id_user =$userid AND seccion='desfase'),0)), '%H:%i') AS disponible, ((SUM(TIME_TO_SEC(horafin_tiraje) - TIME_TO_SEC(horadeldia_tiraje))+SUM(TIME_TO_SEC(horafin_ajuste)-TIME_TO_SEC(horadeldia_ajuste)))-IFNULL(
(SELECT SUM(TIME_TO_SEC(breaktime)) FROM breaktime WHERE fechadeldiaam = '$fecha' AND id_usuario =$userid),0))+IFNULL((SELECT SUM(TIME_TO_SEC(tiempo_muerto)) FROM tiempo_muerto WHERE fecha='$fecha' AND id_user=$userid AND seccion='desfase'),0) AS sec_disponible FROM tiraje WHERE fechadeldia_ajuste = '$fecha' AND id_user =$userid"));

  $sumatorias=mysqli_fetch_assoc($mysqli->query("SELECT SUM(buenos)AS sum_prod_real,SUM(merma_entregada)AS sum_merma,SUM(buenos)+SUM(merma_entregada)AS sum_entregados,SUM(produccion_esperada)AS sum_prod_esperada,SUM(defectos)AS sum_defectos, SUM(buenos)-SUM(defectos)AS sum_calidad_primera FROM tiraje WHERE fechadeldia_ajuste = '$fecha' AND id_user =$userid"));
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
     $getByProcess=mysqli_fetch_assoc($mysqli->query("SELECT SUM(buenos)AS total_buenos, COUNT(*)AS cambios, id_proceso FROM tiraje WHERE fechadeldia_ajuste='$dia' AND entregados<500 AND buenos IS NOT NULL AND buenos NOT IN (0) AND id_user=".$user['id']."  AND id_proceso=".$tdSum1['id_proceso']." GROUP BY id_proceso"));
   

        $subtd.="<td> ".(($getByProcess['id_proceso']==$tdSum1['id_proceso'])? $getByProcess['cambios']:'')."</td><td>".(($getByProcess['id_proceso']==$tdSum1['id_proceso'])? round($getByProcess['total_buenos'],1):'').'</td>';
      }

  $tbody.=$subtd;

  

  $tbody.='<td>'.$sumatorias['sum_defectos'].'</td>';
  
   foreach ($workProc as $key => $tdSum2) {
    $getByProcess2=mysqli_fetch_assoc($mysqli->query("SELECT SUM(defectos)AS total_defectos,  id_proceso FROM tiraje WHERE fechadeldia_ajuste='$dia' AND buenos IS NOT NULL AND id_user=".$user['id']."  AND id_proceso=".$tdSum2['id_proceso']." GROUP BY id_proceso"));
   
      $subtd2.="<td> ".(($getByProcess2['id_proceso']==$tdSum2['id_proceso'])? $getByProcess2['total_defectos']:'')."</td>";
      }

  $tbody.=$subtd2;

  $tirosLargos=mysqli_fetch_assoc($mysqli->query("SELECT SUM(buenos)-SUM(defectos)AS largos FROM tiraje WHERE buenos>=500 AND fechadeldia_ajuste='$dia' AND buenos IS NOT NULL AND id_user=".$user['id']));
  $sum_largos+=$tirosLargos['largos'];

  $tbody.='<td>'.$getTiros->num_rows.'</td>';
  $tbody.='<td>'.round($tirosLargos['largos'],1).'</td>';
  $tbody.='<td>'.round($sumatorias['sum_prod_real'],1).'</td>';
  $tbody.='<td>'.round($sumatorias['sum_merma'],1).'</td>';
  $tbody.='<td>'.round($sumatorias['sum_entregados'],1).'</td>';

  foreach ($workProc as $key => $tdSum3) {
    $getByProcess3=mysqli_fetch_assoc($mysqli->query("SELECT SUM(buenos)+SUM(merma_entregada)AS total_buenos,SUM(defectos)AS total_defectos,  id_proceso FROM tiraje WHERE fechadeldia_ajuste='$dia' AND buenos IS NOT NULL AND id_user=".$user['id']."  AND id_proceso=".$tdSum3['id_proceso']." GROUP BY id_proceso"));

        $subtd3.="<td> ".(($getByProcess3['id_proceso']==$tdSum3['id_proceso'])? round(($getByProcess3['total_buenos']-$getByProcess3['total_defectos']),1):'')."</td>";
      }

  $tbody.=$subtd3;

  foreach ($workProc as $key => $tdSum4) {

    
     $getByProcess4=mysqli_fetch_assoc($mysqli->query("SELECT id_proceso FROM tiraje WHERE fechadeldia_ajuste='$dia' AND buenos IS NOT NULL AND id_user=".$user['id']."  AND id_proceso=".$tdSum4['id_proceso']." GROUP BY id_proceso"));

     $subtd4.="<td> ".(($getByProcess4['id_proceso']==$tdSum4['id_proceso'])? '$'.round($tdSum4['precio'],2):'')."</td>";


      }

  $tbody.=$subtd4;

  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.$rell.'</td>';


  foreach ($workProc as $key => $tdSum5) {
    
     $getByProcess5=mysqli_fetch_assoc($mysqli->query("SELECT SUM(buenos)AS total_buenos,SUM(defectos)AS total_defectos,  id_proceso FROM tiraje WHERE fechadeldia_ajuste='$dia' AND entregados<500 AND buenos NOT IN (0) AND buenos IS NOT NULL AND id_user=".$user['id']."  AND id_proceso=".$tdSum5['id_proceso']." GROUP BY id_proceso"));

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
         $getTotalChanges=mysqli_fetch_assoc($mysqli->query("SELECT SUM(buenos)AS total_buenos, COUNT(*)AS cambios,  id_proceso FROM tiraje WHERE STR_TO_DATE(fechadeldia_ajuste , '%d-%m-%Y') BETWEEN STR_TO_DATE('$inicio' , '%d-%m-%Y') AND STR_TO_DATE('$fin' , '%d-%m-%Y') AND entregados<500 AND buenos IS NOT NULL AND buenos NOT IN (0) AND id_user=".$user['id']."  AND id_proceso=".$tdSum6['id_proceso']." GROUP BY id_proceso"));

        $subtd6.="<td>". (($getTotalChanges['id_proceso']==$tdSum6['id_proceso'])? $getTotalChanges['cambios'] :'' )."</td><td>".(($getTotalChanges['id_proceso']==$tdSum6['id_proceso'])? $getTotalChanges['total_buenos'] :'')."</td>";

      }

  $tbody.=$subtd6;
  $tbody.='<td>'.$total_defec.'</td>';
foreach ($workProc as $key => $tdSum7) {
        $subtd7.="<td>".(($tiroInfo['id_proceso']==$tdSum7['id_proceso'])? '':'')."</td>";
      }
  $tbody.=$subtd7;
  $tbody.='<td>'.round($total_cambios,1).'</td>';
  $tbody.='<td>'.round($sum_largos,1).'</td>';
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
  $precios=0;
foreach ($workProc as $key => $tdSum10) {
       
          $getByProcess10=mysqli_fetch_assoc($mysqli->query("SELECT SUM(buenos)AS total_buenos,(SELECT precio FROM procesos_catalogo WHERE id_proceso=".$tdSum10['id_proceso'].")AS s_price,SUM(defectos)AS total_defectos,  id_proceso FROM tiraje WHERE STR_TO_DATE(fechadeldia_ajuste , '%d-%m-%Y') BETWEEN STR_TO_DATE('$inicio' , '%d-%m-%Y') AND STR_TO_DATE('$fin' , '%d-%m-%Y') AND buenos IS NOT NULL AND id_user=".$user['id']." AND entregados<500
AND buenos NOT IN (0) AND id_proceso=".$tdSum10['id_proceso']." GROUP BY id_proceso"));

        $subtd10.="<td> ".(($getByProcess10['id_proceso']==$tdSum10['id_proceso'])? '$'.round((($getByProcess10['total_buenos']-$getByProcess10['total_defectos'])*$tdSum10['precio']),1):'')."</td>";
        $precios+=($getByProcess10['total_buenos']-$getByProcess10['total_defectos'])*$tdSum10['precio'];


      }
  $tbody.=$subtd10;


  $diferencia=($precios+($sum_largos*0.20))-$user['sueldo'];
  $renum_tiros=(count($workProc)==1)? (($workProc[0]['id_proceso']==10)? (($total_def>7500)? $diferencia:'0.00'):(($diferencia>0)? $diferencia:'0.00')):(($diferencia>0)? $diferencia:'0.00');

  
  $tbody.='<td>$'.round($sum_largos*0.20,1).'</td>';

  $tbody.='<td>$'.round($precios+($sum_largos*0.20),1).'</td>';
  $tbody.='<td>$'.$user['sueldo'].'</td>';
  $tbody.='<td>$'.round($diferencia,1).'</td>';
 
  
  $tbody.='<td>'.round($renum_tiros,1).'</td>';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='</tr>';
  $tbody.='<tr><td colspan="'.$rowspan.'"></td></tr>';
  $tbody.='</tbody>';
  $table.=$tbody;




}//termina foreach users


  ?>
  <?php
ob_start();
?>
<script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<script type="text/javascript" src="../js/libs/js-xlsx/xlsx.core.min.js"></script>
  <script type="text/javascript" src="../js/libs/FileSaver/FileSaver.min.js"></script>
 <script type="text/javascript" src="../js/libs/tableExport.js"></script>
 <input type="hidden" id="fechas" value="<?='REPORTE_TALLER_'.$inicio.'_'.$fin ?>">
 <style>
   body{
    background: #EFEFEF;
    position: relative;
    font: normal medium/1.4 sans-serif;
   }
  
   #info{
    font-family:Arial,Helvetica,sans-serif;
    border-collapse:collapse;
    width:100%;
    text-align:center;
   font-size:7px;
   display: none;
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
   .message{
   width: 300px;
    text-align: center;
    color: #999999;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
   }
 </style>
 <div class="message">
   <p><strong>¡FELICIDADES!</strong></p> tu reporte se ha generado en unos segundos comenzara a descargarse
 </div>
<table id="info" >

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
      foreach ($workProc as $key => $work){
        echo "<td colspan='2'>".substr($work['nombre_proceso'], 0, 4)."</td>";
      }

    ?>
    <td>TIROS LARGOS</td>
    <td>M2 LAMINADO</td>
    <td>TOTAL DEFECTOS</td>
   <?php 
      foreach ($workProc as $key => $workDef){
        echo "<td> Def. ".substr($workDef['nombre_proceso'], 0, 4)."</td>";
      }

    ?>
    <td>DEFECTOS LAMINADO</td>
    <td>DEFECTOS M2 LAMINADO</td>
    <td>TOTAL DE CAMBIOS</td>
    <td>TIROS</td>
    
    <td>MERMA</td>
    <td>GRAN TOTAL</td>
    <?php 
      foreach ($workProc as $key => $work3){
        echo "<td>".$work3['nombre_proceso']."</td>";
      }

    ?>
    
    <td>TIROS LARGOS</td>
    <td>M2 LAMINADO</td>
    <?php 
      foreach ($workProc as $key => $work4){
        echo "<td>CAMBIO ".$work4['nombre_proceso']."</td>";
      }
    ?>
    <?php 
      foreach ($workProc as $key => $work4){
        echo "<td>".$work4['nombre_proceso']."</td>";
      }
    ?>
    <td>TIROS LARGOS</td>
    <td>M2 LANINADO</td>
    <td>TOTAL</td>
   <td>SUELDO</td>
    <td>DIFERENCIA</td>
    <td>REMUN POR TIROS</td>
    <td>REMUN POR CAMBIOS</td>
    <td>POR DEFECTOS</td>
    <td>A PAGAR</td>
   </tr>
  </thead>

  <?=  $table; ?>
 
</table>

<script>
  
    function doExport(selector, params) {
      var options = {
        //ignoreRow: [1,11,12,-2],
        //ignoreColumn: [0,-1],
        //pdfmake: {enabled: true},
        tableName: 'REPORTE_TALLER'
      };

      jQuery.extend(true, options, params);
      var name=$("#fechas").val();
      $(selector).tableExport(options,name);
    }

     $(document).ready(function(event) {
      doExport('#info', {type: 'xlsx'});
});

 </script>
<?php }


?>
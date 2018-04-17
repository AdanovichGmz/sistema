<?php

require_once("dompdf2/autoload.inc.php");
include '../saves/conexion.php';
use Dompdf\Dompdf;

$getUsers=$mysqli->query("SELECT * FROM usuarios WHERE app_active='true'");

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

  foreach ($dias as $d_key => $dia) {
   
  $userid= $user['id']; 
  $fecha=$dia;

  $getTiros=$mysqli->query("SELECT * FROM tiraje WHERE fechadeldia_ajuste='$fecha' AND id_user=".$userid);
$cuero="SELECT TIME_FORMAT(MIN(horadeldia_tiraje), '%H:%i') AS inicial,TIME_FORMAT(MAX(horafin_tiraje), '%H:%i') AS final  FROM tiraje WHERE fechadeldia_ajuste='$fecha' AND id_user=".$userid;
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

  $rell='--';

   $total_dispon+=$dispon_tope;
  $total_desemp+=$desemp_tope;
  $total_calidad+=$calidad_tope;
  $total_ete+=$final;
  $total_prod+=$sumatorias['sum_prod_real'];
  $total_defec+=$sumatorias['sum_defectos'];
  $total_merma+=$sumatorias['sum_merma'];
  $gran_total+=$sumatorias['sum_entregados'];
  $total_cambios+=$getTiros->num_rows;

  $tbody.='<tr>';
  $tbody.='<td>'.$user['logged_in'].'</td>';
  $tbody.='<td>'.$dia.'</td>';
  $tbody.='<td>'.round($dispon_tope).'%</td>';
  $tbody.='<td>'.round($desemp_tope).'%</td>';
  $tbody.='<td>'.round($calidad_tope).'</td>';
  $tbody.='<td>'.round($final).'</td>';
  $tbody.='<td>'.$minMax['inicial'].'</td>';
  $tbody.='<td>'.$minMax['final'].'</td>';
  $tbody.='<td>'.$sumatorias['sum_prod_real'].'</td>';
  $tbody.='<td>'.$getTiros->num_rows.'</td>';
  $tbody.='<td>'.round($sumatorias['sum_prod_real']).'</td>';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.$sumatorias['sum_defectos'].'</td>';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.$getTiros->num_rows.'</td>';
  $tbody.='<td>'.$sumatorias['sum_prod_real'].'</td>';
  $tbody.='<td>'.$sumatorias['sum_merma'].'</td>';
  $tbody.='<td>'.$sumatorias['sum_entregados'].'</td>';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.$rell.'</td>';
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
  $tbody.='<td>'.round($total_dispon).'</td>';
  $tbody.='<td>'.round($total_desemp).'</td>';
  $tbody.='<td>'.round($total_calidad).'</td>';
  $tbody.='<td>'.round($total_ete).'</td>';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.round($total_prod).'</td>';
  $tbody.='<td>'.$total_cambios.'</td>';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.$total_defec.'</td>';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.$total_cambios.'</td>';
  $tbody.='<td>'.$total_prod.'</td>';
  $tbody.='<td>'.$total_merma.'</td>';
  $tbody.='<td>'.$gran_total.'</td>';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.$rell.'</td>';
  $tbody.='<td>'.$rell.'</td>';

  $tbody.='</tr>';
  $tbody.='</tbody>';
  $table.=$tbody;

}//termina foreach users


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
    font-size:5px
}
 #info td{
    border:1px solid #ccc;
    padding:2px
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
    <td colspan="68">ETE TALLER</td>
    
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
    <td colspan="2">Ser</td>
    <td colspan="2">Grab</td>
    <td colspan="2">Ple</td>
    <td colspan="2">Sua</td>
    <td colspan="2">Lpress</td>
   <td colspan="2">Tiros largos</td>
    <td colspan="2">m2 Laminado</td>
    <td>Defectos</td>
    <td>Def Ser</td>
    <td>Def Grab</td>
    <td>Def ple</td>
    <td>Def sua</td>
    <td>def lam</td>td
    <td>Def lpress</td>
    <td>def t. largos</td>
    <td>Def m2 lam</td>
    <td>total de cambios</td>
    <td>merma</td>
    <td>gran total</td>
    <td>ser</td>
    <td>grab/hs</td>
    <td>ple</td>
    <td>sua</td>
    <td>lpress</td>
    <td>tiros largos</td>
   <td>m2 laminado</td>
    <td>ser</td>
    <td>grab/hs</td>
    <td>ple</td>
    <td>sua</td>
    <td>lpress</td>
    <td>tiros largos</td>
    <td>m2 laminado</td>td
    <td>cambio grabado</td>
    <td>cambio hs</td>
    <td>cambio sua</td>
    <td>cambio ple</td>
    <td>ser</td>
    <td>grab/hs</td>
    <td>ple</td>
    <td>sua</td>
    <td>lpress</td>
    <td>tiros largos</td>
    <td>m2 laminado</td>
    <td>total</td>
   <td>sueldo</td>
    <td>diferencia</td>
    <td>renum por tiros</td>
    <td>renum por cambios</td>
    <td>por defectos</td>
    <td>a pagar</td>
    <td>observaciones</td>
    
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
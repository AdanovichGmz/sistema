
<div class="members-container">
<?php 

$operarios=$sessions_model->getSessionOperarios();
$x =count($operarios);
$i=0;

  

foreach ($operarios as $key => $operario){
  if ( ($i % 6) == 0 ){
echo '<div class="carousel-page" '.(($i==0)? 'style="display:block;"':'').'>';

} 
  $currentActivity=$sessions_model->getOperActivity($operario['id']);
  $ete=$ete_model->getEteByUser($operario['id'],TODAY);
  $sessionExist=$sessions_model->checkSessionByUser($operario['id']);
  if ($sessionExist) {
    $sessionID=$sessions_model->getSessionIdByUser($operario['id']);
    $sessionStatus=$sessions_model->getSessionStatusById($sessionID);
  }else{
    $sessionID='';
  }
 ?>
<!-- credencial --> 
<div class="member <?=(!$sessionExist)? 'disabled':(($sessionStatus['active']=='false')? 'disabled':'') ?>" id="member-<?=$operario['id'] ?>">
<div class="member-content <?=$currentActivity ?>" data-id="<?=$operario['id']?>">
  <div  class="<?=($sessionExist)? (($sessionStatus['en_tiempo']=='false')? 'time-over':''):'' ?>"></div>
  <div class="member-header">
  <div class="member-photo">
    <img src="<?php echo URL; ?>public/<?=((!empty($operario['foto']))? $operario['foto'] :'images/default.jpg')?>">
  </div>
  <div class="member-name-timer">
    <p><?=$operario['logged_in'] ?></p>
    <div class="personal-timer">
<span id="<?=$operario['id'] ?>-timer">00:00:00</span>  
</div>
  </div>  
</div>
<div class="info-section">
<div class="ete-user">
 <?=round($ete['ete']) ?>%
</div>
  <div class="timer-band">
  <?=($sessionExist)?  ((isset($sessionStatus['orden_actual']))? 'ODT: '.strtoupper($sessionStatus['orden_actual']) : 'Sin ODT') :'Sin ODT' ?>
</div>
</div>

<div class="member-body">
 <div id="<?=$operario['id']?>" style="top:5px;width: 98%;left: 1px; height: 120px; position:absolute;"></div>  
</div>
</div>
</div>  
<!-- credencial --> 
<script>
var userid=document.getElementById(<?=$operario['id'] ?>);
var timer_<?=$operario['id'] ?> = new Timer();

 timer_<?=$operario['id'] ?>.addEventListener('secondsUpdated', function (e) {
    $('#'+<?=$operario['id'] ?>+'-timer').html(timer_<?=$operario['id'] ?>.getTimeValues().toString());
});
  drawChart(userid,<?=$ete['disponibilidad'] ?>,<?=$ete['desempenio'] ?>,<?=$ete['calidad'] ?>);
  
  
  <?php

  if ($currentActivity=='tiro') {
    echo "timer_".$operario['id'].'.start({startValues: {seconds:'.$process_model->getTiroElapsedTime($sessionID).'}});';

  }elseif ($currentActivity=='alerta') {
    echo "timer_".$operario['id'].'.start({startValues: {seconds:'.$process_model->getAlertElapsedTime($sessionID).'}});';

  }
elseif ($currentActivity=='comida') {
    echo "timer_".$operario['id'].'.start({startValues: {seconds:'.$process_model->getLunchElapsedTime($sessionID).'}});';

  }elseif ($currentActivity=='ajuste') {
    $ajusteElapsed=$process_model->getAjusteElapsedTime($sessionID);
    $standard=$process_model->getAjusteStandard($sessionStatus['proceso']);
    if ($sessionStatus['en_tiempo']=='true') {
        
        $starting=$standard['ajuste_standard']-$ajusteElapsed['time'];

        echo "timer_".$operario['id'].'.start({countdown: true,startValues: {seconds:'.$starting.'}});';
        
    }else{

      $starting=$ajusteElapsed['time']-$standard['ajuste_standard'];
        echo "timer_".$operario['id'].'.start({startValues: {seconds:'.$starting.'}});';

    }
    

  }

  
  ?>
</script>
<?php 
 if (((($i +1) % 6) == 0) || (($i+1)==$x)){
echo '</div>';

}
$i++;
} ?>
</div>
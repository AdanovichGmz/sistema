<?php

$ete=$ete_model->getEteByUser($_POST['user'],TODAY);
$userInfo=$login_model->getUserInfo($_POST['user']);
$tiro=$_SESSION['teamSession'][$_POST['user']]['memberTiroActual'];
$proceso=$_SESSION['teamSession'][$_POST['user']]['memberProcessID'];

?>
 
<div class="tiro-panel">
<ul class="topbar">
<li style="font-weight: bold;"><a class="active" href="javascript:void(0)"><?=$userInfo['logged_in']; ?></a></li>
<li ><a class="active" href="javascript:void(0)">Proceso: <span><?= $process_model->getProcessName($_SESSION['teamSession'][$_POST['user']]['memberProcessID']) ?></span></a></li>
  <li><a  href="javascript:void(0)">Produccion: <span><?=$ete_model->getBuenos($_POST['user'],$_SESSION['sessionID']); ?></span></a></li>
  <li><a href="javascript:void(0)">Merma: <span><?=$ete_model->getMerma($_POST['user'],$_SESSION['sessionID']); ?></span> </a></li>
  <li style="float:right" ><div class="close-modal"></div></li>
  
</ul>
<div class="graphics-container">
<div class="graphic">
<div class="graphic-content">
  <div  id="disponibilidad"></div>
  </div>
</div>
 <div class="graphic">
  <div class="graphic-content">
    <div  id="desempenio"></div>
  </div>
</div>
<div class="graphic">
  <div class="graphic-content">
    <div  id="calidad"></div>
  </div>
</div> 
</div>
<div class="controls-container">
<div class="control-buttons">
  <div class="maintimer" data-inicio="<?=$process_model->getTiroElapsedTime($_SESSION['teamSession'][$_POST['user']]['memberSessionID']) ?>"><span class="values">00:00:00</span></div>
  <div class="button-container">

<div id="save-tiro" class="m-button blue ">
                          <img src="<?php echo URL; ?>public/img/guard.png">
</div>
<div id="lunch" data-msession="<?=$_SESSION['teamSession'][$_POST['user']]['memberSessionID'] ?>" data-user="<?=$_POST['user'] ?>" class="m-button green">
                          <img src="<?php echo URL; ?>public/img/dinner2.png">
</div>
<div id="alert" data-msession="<?=$_SESSION['teamSession'][$_POST['user']]['memberSessionID'] ?>" data-user="<?=$_POST['user'] ?>" class="m-button yellow">
                          <img src="<?php echo URL; ?>public/img/alerts.png">
</div>
<div id="change-activity" data-msession="<?=$_SESSION['teamSession'][$_POST['user']]['memberSessionID'] ?>" data-user="<?=$_POST['user'] ?>" class="m-button purple">
                          <img src="<?php echo URL; ?>public/img/change.png">
</div>
<?php if ($userInfo['team_admin']=='true') { ?>

<div id="admin_team" data-msession="<?=$_SESSION['teamSession'][$_POST['user']]['memberSessionID'] ?>" data-user="<?=$_POST['user'] ?>" class="m-button violet">
  <img src="<?php echo URL; ?>public/img/admin_opers.png">
</div>
  
<?php } ?>


  
</div>
</div>
 <div class="control-inputs">
 <form id="tiro-values" method="POST">
 <input type="hidden" name="tiempo-tiraje" id="tiempo-tiraje">
 <input type="hidden" name="user" id="user" value="<?=$_POST['user'] ?>">
 <input type="hidden" name="proceso" value="<?=$proceso ?>">
 <input type="hidden" name="tiro-actual" value="<?=$tiro ?>">
 <table>
   <tr>
     <td>CANTIDAD DE PEDIDO</td>
     <td>BUENOS</td>
   </tr>
   <tr>
     <td><input type="number" name="pedido" id="pedido" onkeyup="getMerma()" onclick="getNumKeys(this.id,'pedido')"></td>
     <td><input type="number" name="buenos" id="buenos" onkeyup="getMerma()" onclick="getNumKeys(this.id,'buenos')"></td>
   </tr>
   <tr>
     <td>CANTIDAD RECIBIDA</td>
     <td>PIEZAS DE AJUSTE</td>
   </tr>
   <tr>
     <td><input type="number" name="recibidos" id="recibidos" onclick="getNumKeys(this.id,'recibidos')"></td>
     <td><input type="number" name="ajuste" id="ajuste" onclick="getNumKeys(this.id,'ajuste')" onkeyup="getDefectos()"></td>
   </tr>
   <tr>
     <td>MERMA</td>
     <td>DEFECTOS</td>
   </tr>
   <tr>
     <td><input type="number" name="merma" id="merma" onclick="getNumKeys(this.id,'merma')"></td>
     <td><input type="number" name="defectos" id="defectos" onclick="getNumKeys(this.id,'defectos')"></td>
   </tr>
 </table>
</form>
  
</div> 
</div>
  
</div>


<div id="teclado">
  <?php include 'teclado.php' ?>
</div>

<script>
  var timer = new Timer();
  $( document ).ready(function() {
    
    $("#disponibilidad").circliful({
            animationStep: 5,
            foregroundBorderWidth: 15,
            backgroundBorderWidth: 25,
            percent: <?=$ete['disponibilidad'] ?>,
            halfCircle: 1,
             backgroundColor: "#ededed",
            foregroundColor:'#FFC813',
            text: 'Disponibilidad',
            textBelow: true
        });
      $("#desempenio").circliful({
            animationStep: 5,
            foregroundBorderWidth: 15,
            backgroundBorderWidth: 25,
            percent: <?=$ete['desempenio'] ?>,
            halfCircle: 1,
            backgroundColor: "#ededed",
            foregroundColor:'#A3CD3B',
            text: 'Desempeño',
            textBelow: true
        });
        $("#calidad").circliful({
            animationStep: 5,
            foregroundBorderWidth: 15,
            backgroundBorderWidth: 25,
             backgroundColor: "#ededed",
             foregroundColor:'#0296D6',
            percent: <?=$ete['calidad'] ?>,
            halfCircle: 1,
            
            text: 'Calidad',
            textBelow: true
        });
        });
        timer.addEventListener('secondsUpdated', function (e) {
        $('.maintimer .values').html(timer.getTimeValues().toString());
        } );
        timer.addEventListener('started', function (e) {
        $('.maintimer .values').html(timer.getTimeValues().toString());
        });
        var starting=$('.maintimer').data('inicio');
        timer.start({startValues: {seconds: starting}});
 


$( ".close-modal" ).click(function() {
    
    timer.stop();
});
  
</script>
<?php
$options= $process_model->getTiroAlertOptions($_SESSION['teamSession'][$_POST['user']]['memberProcessID']);
$inicio_alerta=$process_model->getAlertElapsedTime($_SESSION['teamSession'][$_POST['user']]['memberSessionID']);
$hora_inicio=$process_model->getStartingHourAlert($_SESSION['teamSession'][$_POST['user']]['memberSessionID']);
$tiro=$_SESSION['teamSession'][$_POST['user']]['memberTiroActual'];
/*
echo "<pre>";
print_r($_SESSION);
echo "</pre>";
*/
?>
<style>
  .box{
    background: #FDE700;
  }
</style>
          <div class="container">
          <div class="closer-alert"></div>
          <h1 class="alert-title">ALERTA</h1>
          
          <p class="alert-p">Elige una opción o agrega una observacion</p>
             <form id="alert-form"  method="post" >
                <input type="hidden" name="tiempo-alerta" id="timealerta">
                <input type="hidden" name="tiro" value="<?=$tiro ?>">
                <input type="hidden" name="member_session" value="<?=$_SESSION['teamSession'][$_POST['user']]['memberSessionID'] ?>">
                <input type="hidden" name="hora-inicio" value="<?=$hora_inicio ?>">
                <input type="hidden" id="usuario" name="user" value="<?=$_POST['user'] ?>">
               <div class="alert-options">
              
                <?php foreach ($options as $option) { ?>
                	<div class=" radio-menu face no-explain">
                    <input type="radio" name="radios" id="radios-<?=$option->id_opcion ?>" value="<?=$option->valor ?>">
                    <?=$option->valor ?>
				</div>
                <?php } ?>
                <textarea placeholder="Observaciones.." class="comments" id="observaciones" name="observaciones" onclick="getKeys(this.id,'observaciones')"></textarea>
                    <p id="explain-error" style="display: none;">Porfavor agrega una explicacion ↑</p>
               
                </div>
                              
             </form>
    <div style="text-align: center;margin-bottom: 30px;">
    <div  style="display: inline-block;vertical-align: top">
      
        
                                    <div class="alert-timer" data-inicio="<?=$inicio_alerta; ?>"><span class="values">00:00:00</span></div>
                                </div>
                                
    <div  style="display: inline-block;vertical-align: top">
     
                  <div class="button-panel-small">
                       
                        <div style="display: none;" class="square-button-micro2 red derecha stopalert start reset" onclick="saveOperstatus()">
                          <img src="<?php echo URL; ?>public/img/ex.png">
                        </div>
                        <div id="save-alerta" class="square-button-micro2   blue" >
                          <img src="<?php echo URL; ?>public/img/saving.png">
                        </div>
                                                 
                        </div>
                
    </div>
    </div>
    </div>
      </div>
      <script>
        var timerAlert = new Timer();
timerAlert.addEventListener('secondsUpdated', function (e) {
        $('.alert-timer .values').html(timerAlert.getTimeValues().toString());
        } );
        timerAlert.addEventListener('started', function (e) {
        $('.alert-timer .values').html(timerAlert.getTimeValues().toString());
        });
        var starting=$('.alert-timer').data('inicio');
        timerAlert.start({startValues: {seconds: starting}});
      </script>

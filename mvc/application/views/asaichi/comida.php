<?php

$inicio_alerta=$process_model->getAjusteLunchElapsedTime();
$hora_inicio=$process_model->getAjusteStartingLunch();

/*
echo "<pre>";
print_r($_SESSION);
echo "</pre>";
*/
?>
<style>
  .big-lightbox{
    background: #ededed!important;
  }
</style>
          <div class="container">
          <div class="closer-alert" style="display: none;"></div>
          <h1 class="alert-title">HORA DE COMER</h1>
          
          
             <form id="lunch-form"  method="post" >
                <input type="hidden" name="tiempo-comida" id="timelunch">
                <input type="hidden" name="section" value="ajuste">
                
                  <input type="hidden" name="tiro" value="<?=$_SESSION['tiroActual'] ?>">
                <input type="hidden" id="usuario" name="user" value="<?=$_SESSION['idUser'] ?>">
                
                <input type="hidden" name="hora-inicio" value="<?=$hora_inicio ?>">
                
               <div class="alert-options">
              
               
                  <div class=" radio-menu face no-explain">
                    <input type="radio" name="radios" id="radios-1" value="Sanitario">
                    WC
        </div>
         <div class=" radio-menu face no-explain">
                    <input type="radio" name="radios" id="radios-1" value="Comida">
                    Comida
        </div>       
               
               
                </div>
                              
             </form>
             <br>
    <div style="text-align: center;margin-bottom: 30px;">
    <div  style="display: inline-block;vertical-align: top">
      
        
                                    <div class="alert-timer" data-inicio="<?=$inicio_alerta; ?>"><span class="values">00:00:00</span></div>
                                </div>
                                
    <div  style="display: inline-block;vertical-align: top">
     
                  <div class="button-panel-small">
                       
                        <div style="display: none;" class="square-button-micro2 red derecha stopalert start reset" onclick="saveOperstatus()">
                          <img src="<?php echo URL; ?>public/img/ex.png">
                        </div>
                        <div id="save-lunch" class="square-button-micro2   blue" >
                          <img src="<?php echo URL; ?>public/img/saving.png">
                        </div>
                                                 
                        </div>
                
    </div>
    </div>
    </div>
      </div>
      <script>
        var timerLunch = new Timer();
timerLunch.addEventListener('secondsUpdated', function (e) {
        $('.alert-timer .values').html(timerLunch.getTimeValues().toString());
        } );
       
        var starting=$('.alert-timer').data('inicio');
        timerLunch.start({startValues: {seconds: starting}});
      </script>

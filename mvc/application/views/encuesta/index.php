<?php
$options_lento= $process_model->getEncuestaOptions('encuesta_lento');
$options_bien= $process_model->getEncuestaOptions('encuesta_bien');
$inicio_alerta=$process_model->getAlertElapsedTime($_SESSION['sessionID']);

/*
echo "<pre>";
print_r($_SESSION);
echo "</pre>"; 
*/

?>
<style>
  body{
    background: #ededed!important;
  }
  #observaciones{
    border-color: #ccc;
    width: 406px!important;
  }
  .checked{
    background: #2188FF!important;
    border-color: #005CC5!important;
  }
  h1{
    margin: 5px auto;
    color: #606062!important;
  }
  .alert-options{
    margin:30px auto;
  }
</style>
                   <div class="container">
          
          <h1 class="alert-title" >ENCUESTA</h1>
          
          <div class="encuesta-container">
             <form id="encuesta-form"  method="post" >
             <p class="encuesta-p">¿Lo hice mas lento?</p>
                
                 <input type="hidden" name="tiro" value="<?=$_SESSION['tiroActual'] ?>">
                <input type="hidden" id="usuario" name="user" value="<?=$_SESSION['user']['id'] ?>">
                
                
               <div class="encuesta-options">
              
                
                <div class=" radio-encuesta no-explain">
                    <input type="radio" name="lento" value="SI">
                    SI
               </div>
               <div class=" radio-encuesta no-explain" onclick="showModal('lentos')">
                    <input type="radio" name="lento" value="NO">
                    NO
               </div>
                
                </div>
                <div class="encuesta-modal" id="lentos" >
                 <h1>¿Por que?</h1>
                <div class="alert-options">
                   <?php foreach ($options_lento as $option) { ?>
                  <div class=" radio-menu face no-explain">
                    <input type="radio" name="radios-lento"  value="<?=$option->valor ?>">
                    <?=$option->valor ?>
              </div>
                <?php } ?>
                </div>
                </div>
                <br>
                <div class="encuesta-options" id="preg-2" style="display: none;">
                <p class="encuesta-p">¿Lo hice bien a la primera?</p>
                <div class=" radio-encuesta2 no-explain">
                    <input type="radio" name="bien" value="SI">
                    SI
               </div>
               <div class=" radio-encuesta2 no-explain" onclick="showModal('males')">
                    <input type="radio" name="bien" value="NO">
                    NO
               </div>
                </div>
                <div class="encuesta-modal" id="males">
                <h1>¿Por que?</h1>
                <div class="alert-options">
                  <?php foreach ($options_bien as $option_b) { ?>
                  <div class=" radio-menu face no-explain">
                    <input type="radio" name="radios-mal" value="<?=$option_b->valor ?>">
                    <?=$option_b->valor ?>
              </div>
                <?php } ?>
                </div>
                </div>
                <br>
                </div>
                <div id="fin" style="display: none;">
                <div class="encuesta-options">
                <textarea placeholder="Observaciones.." class="comments" id="observaciones" name="observaciones"></textarea>
                    <p id="explain-error" style="display: none;">Porfavor agrega una explicacion ↑</p>
               
                </div>
                 <div style="text-align: center;margin-bottom: 30px;">
    <div  style="display: inline-block;vertical-align: top">
      
        
                                    
                                
    <div  style="display: inline-block;vertical-align: top">
     
                  <div class="button-panel-small">
                       
                        <div style="display: none;" class="square-button-micro2 red derecha stopalert start reset" onclick="saveOperstatus()">
                          <img src="<?php echo URL; ?>public/img/ex.png">
                        </div>
                        <div id="save-alerta" class="square-button-micro2   blue" >
                          <img src="<?php echo URL; ?>public/img/guard.png">
                        </div>
                                                 
                  </div>
                
    </div>
    </div>
    </div>
    </div>
                              
             </form>
   
      </div>
      <div class="backdrop"></div>
    <script>
  $('.radio-menu').click(function() {
  $('.checked').removeClass('checked');
  $(this).addClass('checked').find('input').prop('checked', true); 
  closeModal();   
});

   $('.radio-encuesta').click(function() {
  $('.enc-checked').removeClass('enc-checked');
  $(this).addClass('enc-checked').find('input').prop('checked', true);
  $('#preg-2').show();    
});
   $('.radio-encuesta2').click(function() {
  $('.enc-checked2').removeClass('enc-checked2');
  $(this).addClass('enc-checked2').find('input').prop('checked', true);  
  $('#fin').show();  
});

   function showModal(id){
    $('.backdrop').animate({'opacity':'.50'}, 300, 'linear');
    $('.backdrop').css('display', 'block');
    $('#'+id).animate({'opacity':'1.00'}, 300, 'linear');
          $('#'+id).css('display', 'block');   
   }

   function closeModal(){
 
   $('.backdrop, .encuesta-modal').animate({'opacity':'0'}, 300, 'linear', function(){
          $('.backdrop, .encuesta-modal').css('display', 'none');
        });  
    
}
jQuery214(document).on("click", "#save-alerta", function () {


$('.backdrop').animate({'opacity':'.50'}, 300, 'linear');
$('.backdrop').css('display', 'block');
$('.loader').show();

  $.ajax({  
                      
          type:"POST",
          url:"<?php echo URL; ?>encuesta/saveEncuesta/",   
          data:$('#encuesta-form').serialize(), 
          success:function(data){
            console.log(data);
            location.href = '<?php echo URL.$url; ?>/';
                    
          }

          }); 
  
});
$('.radio-menu').click(function() {
  $('.checked').removeClass('checked');
  $(this).addClass('checked').find('input').prop('checked', true); 
  closeModal();   
});
    </script>
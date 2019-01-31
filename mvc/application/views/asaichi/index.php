<?php
$actividad=$sessions_model->getSessionStatus();

?>
<link rel="stylesheet" href="<?php echo URL; ?>public/js/css/softkeys-0.0.1.css">
<script src="<?php echo URL; ?>public/js/softkeys-0.0.1.js"></script>
<body>

<h1 style="text-align: center;">ASAICHI</h1>
  <div class="big-button-container">
  <form id="ajuste-form">

<div id="save_asaichi" class="big-button blue ">
                          <img src="<?= URL; ?>public/img/guard.png">
</div>


<input type="hidden" id="tiempo" name="tiempo">
<input type="hidden" name="hora_inicio" value="<?=$_SESSION['inicio_asaichi'] ?>">
</form> 
</div>
<input type="hidden" id="actividad" value="<?=$actividad['actividad_actual'] ?>">
<div id="timer" data-inicio="<?=$process_model->getAsaichiElapsedTime($_SESSION['sessionID']) ?>" data-estandar="900"><span class="values timer-display">00:00:00</span>

</div>

<div class="backdrop"></div>


</body>

<script>
var elapsed=$('#timer').data('inicio');
var estandar=$('#timer').data('estandar');
var timer = new Timer();
 timer.addEventListener('secondsUpdated', function (e) {
    $('#timer .timer-display').html(timer.getTimeValues().toString());
});

$(document).ready(function(){
  timer.start({countdown: true,startValues: {seconds:estandar-elapsed
}});


 });
  jQuery214(document).on("click", "#save_asaichi", function () {
    
    
    timer.pause();
    $('#tiempo').val(timer.getTimeValues().toString());
    timer.stop();
    $.ajax({  
                      
          type:"POST",
          url:"<?php echo URL; ?>asaichi/finishAsaichi/",   
          data:jQuery214('#ajuste-form').serialize(), 
          success:function(data){
            console.log(data);
          location.href = '<?php echo URL.$url; ?>/';
          }

          });
   
    
});



function closeBigBox(){

          $('.big-lightbox').animate({'opacity':'0'}, 300, 'linear');
          $('.big-lightbox').css('display','none');
}




</script>
<?php
$actividad=$sessions_model->getSessionStatus();

?>
<link rel="stylesheet" href="<?php echo URL; ?>public/js/css/softkeys-0.0.1.css">
<script src="<?php echo URL; ?>public/js/softkeys-0.0.1.js"></script>
<body>
<ul class="topbar">
<li style="" class=" burger drop-menu"><a  href="javascript:void(0)">Opciones</a></li>
<li style="float:right"><a href="javascript:void(0)">Orden actual: <span><?=$_SESSION['odt']; ?></span> </a></li>
</ul>
<br><br><br>
<h1 style="text-align: center;">AJUSTE</h1>
  <div class="big-button-container">
  <form id="ajuste-form">
<div id="select-order" style="display: none;" class="big-button purple">
                          <img src="<?= URL; ?>public/img/elegir.png">
</div>
<div id="save_ajuste" class="big-button blue ">
                          <img src="<?= URL; ?>public/img/guard.png">
</div>
<div id="lunch" class="big-button green" >
                          <img src="<?= URL; ?>public/img/dinner2.png">
</div>
<div id="alert" class="big-button yellow" >
                          <img src="<?= URL; ?>public/img/alerts.png">
</div>
<input type="hidden" id="tiempo" name="tiempo">
</form> 
</div>
<input type="hidden" id="actividad" value="<?=$actividad['actividad_actual'] ?>">
<div id="timer" data-inicio="<?=$process_model->getAjusteElapsedTime($_SESSION['sessionID']) ?>" data-estandar="900"><span class="values timer-display">00:00:00</span>

</div>
<div class="big-lightbox">
  
</div>
<div class="box">
  
</div>
<div class="backdrop"></div>
<div class="backdrop-change"></div>


<div id="panelkeyboard2">
    
    <div class="keycontainer">
      <div id="softk" class="softkeys" data-target="input[name='getodt']"></div>
    </div>    
    <div id="close-down-key" class="square-button-micro red " style="display: none;">
                          <img src="images/ex.png">
    </div>
    
    
</div>
<div class="mega-menu">
  <div class="mega-menu-cover"></div>
  <ul class="topbar">
  <li ><a href="javascript:void(0)">Elige una opción:</a></li>
  
  
  <li style="float:right"><div  class="close-mega-menu"></div></li>

</ul>
<div class="mega-menu-content">
  <div class="mega-menu-button" id="add-members">
    <span >AGREGAR INTEGRANTES AL EQUIPO</span>
  </div>
  <div class="mega-menu-button">
    <span>CAMBIAR ODT</span>
  </div>
  <div class="mega-menu-button">
    <span>CANCELAR TIRO</span>
  </div>
  <a href="<?=BASE_URL ?>/logout.php">
  <div class="mega-menu-button">
    <span>CERRAR SESION</span>
  </div></a>
</div>
  

</div>

<div id="key-ajuste">
    
    <div class="keycontainer">
      <div id="softk" class="softkeys" data-target="input[name='getodt']"></div>
    </div>
    
      <div id="close-down-key" class="square-button-micro red " style="display: none;">
                          <img src="images/ex.png">
                        </div>
    
    
</div>
</body>

<script>
var elapsed=$('#timer').data('inicio');
var estandar=$('#timer').data('estandar');
var timer = new Timer();
 timer.addEventListener('secondsUpdated', function (e) {
    $('#timer .timer-display').html(timer.getTimeValues().toString());
});

$(document).ready(function(){
  timer.start({countdown: true,startValues: {seconds:estandar
}});

  <?php 
  
  if ($actividad['actividad_actual']=='alerta') {
    echo "jQuery214('#alert').click()";
  }elseif($actividad['actividad_actual']=='comida'){
   echo "jQuery214('#lunch').click()";
    } ?>


 });
  jQuery214(document).on("click", "#save_ajuste", function () {
    console.log('le picaron');
    
    timer.pause();
    $('#tiempo').val(timer.getTimeValues().toString());
    timer.stop();
    $.ajax({  
                      
          type:"POST",
          url:"<?php echo URL; ?>ajuste/finishAjuste/",   
          data:jQuery214('#ajuste-form').serialize(), 
          success:function(data){
            console.log(data);
           location.href = '<?php echo URL; ?>tiro/';
          }

          });
   
    
});


jQuery214(document).on("click", "#add-members", function () {
  
  var actividad='actividad';
 
 $.ajax({            
          type:"POST",
          url:"<?php echo URL; ?>ajuste/addMembers/",   
          data:{actividad:actividad}, 
          success:function(data){

          $('.big-lightbox').html(data);          
          $('.big-lightbox').animate({'opacity':'1.00'}, 300, 'linear');
          $('.big-lightbox').css('display','block');
          menu();           
          }
});

});
function closeBigBox(){

          $('.big-lightbox').animate({'opacity':'0'}, 300, 'linear');
          $('.big-lightbox').css('display','none');
}
jQuery214(document).on("click", "#alert", function (){

    var actividad=jQuery214('#actividad').val();

     $.ajax({  
                      
          type:"POST",
          url:"<?php echo URL; ?>ajuste/startAlert/",   
          data:{actividad:actividad,section:'ajuste'}, 
          success:function(data){

          $('.big-lightbox').html(data);
          
          $('.big-lightbox').animate({'opacity':'1.00'}, 300, 'linear');
          $('.big-lightbox').css('display', 'block');           
          }

          });
    //location.href = '<?php echo URL; ?>tiro/';
    
});
jQuery214(document).on("click", "#lunch", function () {

    var actividad=jQuery214('#actividad').val();
    console.log('lunchie');
     $.ajax({  
                      
          type:"POST",
          url:"<?php echo URL; ?>ajuste/startLunchTime/",   
          data:{actividad:actividad}, 
          success:function(data){

          $('.big-lightbox').html(data);
          
          $('.big-lightbox').animate({'opacity':'1.00'}, 300, 'linear');
          $('.big-lightbox').css('display', 'block');           
          }

          });
    //location.href = '<?php echo URL; ?>tiro/';
    
});
jQuery214(document).on("click", ".drop-menu", function () {
  menu();
});
jQuery214(document).on("click", ".close-mega-menu", function () {
  menu();
});
var drop=false;

function menu(){
      if (drop == false) {

            $(".mega-menu-cover").animate({ bottom: '+=60%' }, 200);
            $(".mega-menu").animate({ top: '+=40%' }, 200);
            drop = true;
        }
        else {
            $(".mega-menu-cover").animate({ bottom: '-=60%' }, 200);
            $(".mega-menu").animate({ top: '-=40%' }, 200);
            drop = false;
        }  
    }


jQuery214(document).on("click", "#save-alerta", function () {
  var user=jQuery214('#usuario').val();

timerAlert.pause();
$('#timealerta').val(timerAlert.getTimeValues().toString());

timerAlert.stop();
//var stopFunction='stop'+user;
//var startFunction='start'+user;
//eval(stopFunction + "()");
$.ajax({  
                      
          type:"POST",
          url:"<?php echo URL; ?>ajuste/saveAlert/",   
          data:jQuery214('#alert-form').serialize(), 
          success:function(data){
            console.log(data);
           // var elapsed=jQuery214('.maintimer').data('inicio');
           closeBigBox();
            //eval(startFunction + "("+elapsed+")");
         
                    }

          });

});

jQuery214(document).on("click", "#save-lunch", function () {
  var user=jQuery214('#usuario').val();

timerLunch.pause();
$('#timelunch').val(timerLunch.getTimeValues().toString());

timerLunch.stop();
//var stopFunction='stop'+user;
//var startFunction='start'+user;
//eval(stopFunction + "()");
$.ajax({  
                      
          type:"POST",
          url:"<?php echo URL; ?>ajuste/saveLunch/",   
          data:jQuery214('#lunch-form').serialize(), 
          success:function(data){
            console.log(data);
            closeBigBox();
            //var elapsed=jQuery214('.maintimer').data('inicio');
           
            //eval(startFunction + "("+elapsed+")");
         
                    }

          });

});

jQuery214(document).on("click", ".process", function () {
 
   
  $('.p-selected').removeClass('p-selected');       
  $(this).addClass('p-selected');
  var target=$(this).data('target');
  $('.process').hide();
  $('.no-childs').hide();
  
  $('#'+target).show();
      
    

});

</script>
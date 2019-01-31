<?php 
  $standard=$process_model->getAjusteStandard($_SESSION['processID']);
  $transcur=$process_model->getAjusteElapsedTime($_SESSION['sessionID']);
  $deadElapsed=($transcur['time'])-($standard['ajuste_standard']);
  

?>
<body>
<div id="time-over"></div>
<input type="hidden" id="odt-selected" value="<?=(!empty($workingInfo['id_orden']))? $workingInfo['id_orden']:((!empty($workingInfo['elemento_virtual']))? $workingInfo['elemento_virtual']:'') ?>">
<ul class="topbar">
<li style="font-weight: bold;"><a class="active" href="#">AJUSTE</a></li>
<li ><a class="active" href="#">Operario: <span><?=$_SESSION['user']['logged_in']; ?></span></a></li>
  <li style="display: none;"><a  href="#">Produccion: <span><?=$ete_model->getBuenos($_SESSION['user']['id'],$_SESSION['sessionID']); ?></span></a></li>
  <li style="display: none;"><a href="#">Merma: <span><?=$ete_model->getMerma($_SESSION['user']['id'],$_SESSION['sessionID']); ?></span> </a></li>
  <li style="float:right" ></li>
  
</ul>

<div class="big-button-container">
<div class="odt-display"><p id="current-odt">ODT: <?=(!empty($workingInfo['id_orden']))? $workingInfo['num_odt']:((!empty($workingInfo['elemento_virtual']))? $workingInfo['num_odt']:'--') ?></p>
<p id="current-part"><?=(!empty($workingInfo['id_orden']))? $process_model->getElementNameByOrder($workingInfo['id_orden']):((!empty($workingInfo['elemento_virtual']))? $workingInfo['elemento_virtual']:'--') ?></p></div>
<form id="ajuste-form">
<div id="select-order"  class="big-button purple">
                          <img src="<?= URL; ?>public/img/elegir.png">
</div>
<div id="save_ajuste" class="big-button blue ">
                          <img src="<?= URL; ?>public/img/guard.png">
</div>
<div id="lunch" class="big-button green" >
                          <img src="<?= URL; ?>public/img/dinner2.png">
</div>
<div id="alert" class="big-button yellow">
                          <img src="<?= URL; ?>public/img/alerts.png">
</div>
<?php if ($_SESSION['multi_process']=='true') { ?>
<div class="big-button violet " id="change">
                          <img src="<?= URL; ?>public/img/change.png">
</div>
<?php } ?>
<input type="hidden" id="tiempo" name="tiempo">
</form> 
</div>
<input type="hidden" id="ontime" name="ontime" value="<?=$seccion['en_tiempo'] ?>">
<input type="hidden" id="actividad" value="<?=$seccion['actividad_actual'] ?>">
<div id="timer" data-inicio="<?=$transcur['time'] ?>" data-estandar="<?=$standard['ajuste_standard'] ?>"><span class="values timer-display">00:00:00</span>

</div>

<div class="big-lightbox">
  <?php 
  $c=0;
  foreach ($elements as $element) { ?>
    <div class="elem-button <?='c'.$c ?>" data-name="<?=$element['nombre_elemento'] ?>" data-id="<?=$element['id_elemento'] ?>"><span><?=$element['nombre_elemento'] ?></span></div>
<?php $c++;  }
   ?>
   <div class="other-element">
     <span>OTRO</span>
   </div>
</div>
<div class="big-lightbox2" <?=($seccion['actividad_actual']=='alerta'&&$seccion['seccion_alert']=='ajuste')?'style="opacity: 1; display: block;"':(($seccion['actividad_actual']=='comida'&&$seccion['seccion_comida']=='ajuste')?'style="opacity: 1; display: block;"':'' ) ?>>
   <?=($seccion['actividad_actual']=='alerta'&&$seccion['seccion_alert']=='ajuste')? include 'alerta.php':(($seccion['actividad_actual']=='comida'&&$seccion['seccion_comida']=='ajuste')? include 'comida.php':'') ?>
</div>
<div class="backdrop-change"></div>


<div id="panelkeyboard2" class="keyboard">
    
    <div class="keycontainer">
      <div id="softk" class="softkeys" data-target="input[name='getodt']"></div>
    </div>
    
      <div id="close-down-key" class="square-button-micro red " style="display: none;">
                          <img src="images/ex.png">
                        </div>
</div>

<ul class="footer">
  <li><a class="active" href="#"><span class="blue">FIN DEL DIA</span></a></li>
  <li style="float:right" ><a class="active" href="<?= URL ?>logout/"><span class="red">SALIR</span></a></li>
</ul>
<?php include 'odt_selector.php'; ?>
<div class="loader">
  <img src="<?php echo URL; ?>public/img/whloader.gif">
</div>
<div class="backdrop"></div>
</body>

<script>
var p = false;
var elapsed=$('#timer').data('inicio');
var estandar=parseInt($('#timer').data('estandar'));
var timer = new Timer();
var deadTimer= new Timer();
var kb=false;
var inicio=parseInt($('#timer').data('inicio'));
 

 timer.addEventListener('secondsUpdated', function (e) {
    $('#timer .timer-display').html(timer.getTimeValues().toString());
});
timer.addEventListener('started', function (e) {
    $('#timer .timer-display').html(timer.getTimeValues().toString());
});
timer.addEventListener('reset', function (e) {
    $('#timer .timer-display').html(timer.getTimeValues().toString());
});
     
  deadTimer.addEventListener('secondsUpdated', function (e) {
    $('#timer .timer-display').html(deadTimer.getTimeValues().toString());
});
  deadTimer.addEventListener('started', function (e) {
      $('#timer .timer-display').html(deadTimer.getTimeValues().toString());
  });    

 
 
 jQuery214(document).on("click", "#select-order", function () {
      console.log('hola');
        if (p == false) {

            
            jQuery214("#panelbottom").animate({ bottom: '+=100%' }, 200);
            p = true;
        }
        else {
            
            jQuery214("#panelbottom").animate({ bottom: '-=100%' }, 200);
            p = false;
        }
});

 


jQuery214(document).on("click", ".elem-button", function () {
  var elemID=jQuery214(this).data('id');
  var elemName=jQuery214(this).data('name');
  jQuery214('.big-lightbox').hide();
  jQuery214('#virtualelem').val(elemName);
  jQuery214('#elem-id').val(elemID);  

});

jQuery214(document).on("click", ".other-element", function () {
  
  jQuery214('.big-lightbox').hide();
   
  jQuery214('#elem-id').val('144');  
  getKeys('virtualelem','virtualelem'); 

});

jQuery214(document).on("click", "#close-down", function () {
  if (p == true){
            
            jQuery214("#panelbottom").animate({ bottom: '-=100%' }, 200);
            p = false;
        }
  if (kb == true) {          
          
            $("#panelkeyboard2").animate({ bottom: '-=60%' }, 200);
            kb = false;
        }
  $('.create-odt-form').hide();
 });

$(document).ready(function(){
  
  

  if ($('#ontime').val()=='true') {
    
    if (inicio>estandar){
      
       var deathTime=inicio-estandar;
        deadTimer.start({startValues: {seconds: deathTime}});
        
        
        $.ajax({      
                  type:"POST",
                  url:"<?=URL ?>/ajuste/timeOver",
                  data:{status:'time_over'}, 
                  success:function(data){
                  console.log(data);
                  }  
                });
        if (jQuery214('#actividad').val()=='alerta'||jQuery214('#actividad').val()=='comida') {
          deadTimer.stop();
        }
      
}else{

  var starting=estandar-inicio;
      timer.start({countdown: true,startValues: {seconds:starting}});
  if (jQuery214('#actividad').val()=='alerta'||jQuery214('#actividad').val()=='comida') {
          timer.stop();
        }
}

  }else{
    var deathTime=inicio-estandar;
  deadTimer.start({startValues: {seconds:deathTime
}});
  timeOver();
  if (jQuery214('#actividad').val()=='alerta'||jQuery214('#actividad').val()=='comida') {
          deadTimer.stop();
        }
  }

 });





timer.addEventListener('targetAchieved', function (e) {  
    timer.stop();
    deadTimer.start();
    timeOver();
    $('#ontime').val('false');
   $.ajax({      
                     type:"POST",
                     url:"<?=URL ?>/ajuste/timeOver",   
                     data:{status:'time_over'},
                     success:function(data){ 
                          console.log(data);
                     }  
  });
}); 

function timeOver(){  
  animacion = function(){  
  document.getElementById('time-over').classList.toggle('fade');
}
setInterval(animacion, 550);

} 
  jQuery214(document).on("click", "#save_ajuste", function () {
    if ($('#odt-selected').val()=='') {
      alert('no has seleccionado una orden!!!');
      $('#select-order').click();
    }else{
      

    if (jQuery214('#ontime').val()=='true') {
              timer.pause();
    $('#tiempo').val(timer.getTimeValues().toString());
    timer.stop();
            }else{
              deadTimer.pause();
              
    $('#tiempo').val(deadTimer.getTimeValues().toString());
    deadTimer.stop();
            }

    $('.backdrop').animate({'opacity':'.50'}, 300, 'linear');
    $('.backdrop').css('display', 'block');
    $('.loader').show();
    $.ajax({  
                      
          type:"POST",
          url:"<?php echo URL; ?>ajuste/finishAjuste/",   
          data:jQuery214('#ajuste-form').serialize(), 
          success:function(data){
            //console.log(data);

           location.href = '<?php echo URL; ?>tiro/';
          }

          });
    }
    
    
   
    
});

  jQuery214(document).on("click", "#create-odt", function () {
    getKeys('virtualodt','odt');
    jQuery214('.create-odt-form').show();
});





function closeBigBox(){

          $('.big-lightbox').animate({'opacity':'0'}, 300, 'linear');
          $('.big-lightbox').css('display','none');
          $('.big-lightbox2').animate({'opacity':'0'}, 300, 'linear');
          $('.big-lightbox2').css('display','none');
}
jQuery214(document).on("click", "#alert", function (){
   if (jQuery214('#ontime').val()=='true') {
              timer.pause();
            }else{
              deadTimer.pause();
            }
    var actividad=jQuery214('#actividad').val();

     $.ajax({  
                      
          type:"POST",
          url:"<?php echo URL; ?>ajuste/startAlert/",   
          data:{actividad:actividad,section:'ajuste'}, 
          success:function(data){
            console.log(data);
          $('.big-lightbox2').html(data);
          
          $('.big-lightbox2').animate({'opacity':'1.00'}, 300, 'linear');
          $('.big-lightbox2').css('display', 'block');           
          }

          });

    
});
jQuery214(document).on("click", "#virtualelem", function () {
  $('.big-lightbox').show();

});
jQuery214(document).on("click", "#lunch", function () {

     if (jQuery214('#ontime').val()=='true') {
              timer.pause();
            }else{
              deadTimer.pause();
            }

    var actividad=jQuery214('#actividad').val();
    
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
var totalTimeValues=timerAlert.getTotalTimeValues().seconds;
timerAlert.stop();

$.ajax({  
                      
          type:"POST",
          url:"<?php echo URL; ?>ajuste/saveAlert/",   
          data:jQuery214('#alert-form').serialize(), 
          dataType:"json",
          success:function(data){
            

            if (jQuery214('#actividad').val()=='alerta') {
               
                if (jQuery214('#ontime').val()=='false') {
                  var ini=parseInt(jQuery214('#timer').data('inicio'));
                  var std=parseInt(jQuery214('#timer').data('estandar'));
                  var deathT=(ini-totalTimeValues)-std;
                  deadTimer.start({startValues: {seconds: deathT}});
                }else{
                  var std=parseInt(jQuery214('#timer').data('estandar'));
                  var strt=std-(data.elapsed-totalTimeValues);
                  console.log(data);
                  timer.start({countdown: true,startValues: {seconds:strt}});
                }
               
            }else{

              if (jQuery214('#ontime').val()=='true') {
              
              timer.start();
                  }else{
              deadTimer.start();
                }
            }
             
            

           closeBigBox();

         
                    }

          });

});

jQuery214(document).on("click", "#save-lunch", function () {
  var user=jQuery214('#usuario').val();

timerLunch.pause();
$('#timelunch').val(timerLunch.getTimeValues().toString());
var totalTimeValues=timerLunch.getTotalTimeValues().seconds;
timerLunch.stop();

$.ajax({  
                      
          type:"POST",
          url:"<?php echo URL; ?>ajuste/saveLunch/",   
          data:jQuery214('#lunch-form').serialize(),
          dataType:"json", 
          success:function(data){
            
            closeBigBox();
            if (jQuery214('#actividad').val()=='comida') {
               
                if (jQuery214('#ontime').val()=='false') {
                  var ini=parseInt(jQuery214('#timer').data('inicio'));
                  var std=parseInt(jQuery214('#timer').data('estandar'));
                  var deathT=(ini-totalTimeValues)-std;
                  deadTimer.start({startValues: {seconds: deathT}});
                }else{
                  var std=parseInt(jQuery214('#timer').data('estandar'));
                  var strt=std-(data.elapsed);
                  console.log(data);
                  console.log('totalTimeValues: '+totalTimeValues);
                  timer.start({countdown: true,startValues: {seconds:strt}});
                }
               
            }else{
              if (jQuery214('#ontime').val()=='true') {
              timer.start();
            }else{
              deadTimer.start();
            }
}
           
   
                    }

          });

});


function getKeys(id,name) {
      $('#'+id).select();
      $('.input-active').removeClass('input-active');
      $('#'+id).addClass('input-active');
      $('#softk').attr('data-target', 'input[name="'+name+'"]');
        if (kb == false) {          
          
            $("#panelkeyboard2").animate({ bottom: '+=60%' }, 200);
            kb = true;
        }
        var bguardar;
        
        $('#softk').empty();     
         $('.softkeys').softkeys({
                    target :  $('#'+id),
                    layout : [
                        [
                            
                            ['1','!'],
                            ['2','@'],
                            ['3','#'],
                            ['4','$'],
                            ['5','%'],
                            ['6','^'],
                            ['7','&amp;'],
                            ['8','*'],
                            ['9','('],
                            ['0',')']
                        ],
                    [
                            'q','w','e','r','t','y','u','i','o','p'
                            
                        ],
                        [
                            
                            'a','s','d','f','g','h','j','k','l','ñ'
                            
                            
                            
                        ],[
                            
                            'z','x','c','v','b','n','m','←'],['__','GUARDAR']
                            
                            ],

                    id:'softkeys'
                });
              
                $('#hidekey').parent('.softkeys__btn').addClass('hidder'); 
    $('#savekey').parent('.softkeys__btn').addClass('saver').attr('id', 'saver');;            
$('#borrar-letras').parent('.softkeys__btn').addClass('large');
            $('#borrar-softkeys').parent('.softkeys__btn').addClass('large');
            if (id=='virtualodt'||id=='virtualelem') { $('.savebutton').show();}else{$('.savebutton').hide();}
    }  

jQuery214(document).on("click", ".radio-menu", function () {
  $('.selected').removeClass('selected');
  $(this).addClass('selected').find('input').prop('checked', true);    
});

jQuery214(document).on("click", "#save-odt", function () {
  console.log('odt guardada');
});
jQuery214(document).on("submit", "#virtual-odt-form", function (e) {
  e.preventDefault();
   if ($('#virtualodt').val()=='') {
    $('#msg_odt').show();
  }
  else if ($('#virtualelem').val()==''){
    $('#msg_elem').show();
  }
  else{

    var elemid=jQuery214('#virtualelem').val();
        var vodt=jQuery214('#virtualodt').val();
   
    $.ajax({  
                      
                     type:"POST",
                     url:"<?php echo URL; ?>ajuste/setOrderPart/",   
                     data:$('#virtual-odt-form').serialize(),  
                    //dataType:"json",
                     success:function(data){ 
                  console.log(data);    
                  jQuery214('#odt-selected').val(vodt);
                  $('#odt-results').html(data);
               $('#current-odt').html('ODT: '+vodt); 
               $('#current-part').html(elemid);   
                     }  
                });
  }
});


function searchOrder(value){
  
   $.ajax({          
                     type:"POST",
                     url:"<?php echo URL; ?>ajuste/searchOrder/",   
                     data:{odt:value},  
                    //dataType:"json",
                     success:function(data){
                  jQuery214('#odt-results').html(data);
               
                     }  
                });
}

jQuery214(document).on("click", ".order-option", function (e) {
  var odt=jQuery214(this).data('numodt');
  $.ajax({  
                      
                     type:"POST",
                     url:"<?php echo URL; ?>ajuste/getOrderContent/",   
                     data:{odt:odt},  
                    //dataType:"json",
                     success:function(data){ 
                      
                  jQuery214('#odt-results').html(data);
               
                     }  
                });
});

jQuery214(document).on("click", ".part-option", function (e) {
  var odt=jQuery214(this).data('numodt');
  var id_orden=jQuery214(this).data('idorden');
  var id_elem=jQuery214(this).data('idelem');
  var name_elem=jQuery214(this).data('elem');
  var id_proceso=jQuery214(this).data('idpro');

  
  $.ajax({  
                      
                     type:"POST",
                     url:"<?php echo URL; ?>ajuste/setOrderPart/",   
                     data:{odt:odt,id_orden:id_orden,id_proceso:id_proceso,is_virtual:'false',producto:id_elem},  
                    //dataType:"json",
                     success:function(data){ 
                  jQuery214('#odt-results').html(data);
                  jQuery214('#odt-selected').val(odt);
                  jQuery214('#current-odt').html('ODT: '+odt);
                  jQuery214('#current-part').html(name_elem);
                  jQuery214('#close-down').click();
                  
               
                     }  
  });
});

jQuery214(document).on("click", "#change", function (e) {
  var on_time=jQuery214('#ontime').val();
  

  if (on_time=='true') {
              timer.pause();
    var e_time=timer.getTimeValues().toString();
    timer.stop();
    
            }else{
              deadTimer.pause();
              
    var e_time=deadTimer.getTimeValues().toString();
    deadTimer.stop();
    
      }
 

  $.ajax({  
                      
                     type:"POST",
                     url:"<?php echo URL; ?>ajuste/taskSwitching/",   
                     data:{on_time:on_time,elapsed:e_time},
                     
                    //dataType:"json",
                     success:function(data){ 
                  console.log(data);
               
                     }  
  });
});

</script>
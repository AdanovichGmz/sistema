  <!-- bar chart -->
    <script type="text/javascript" src="<?php echo URL; ?>public/js/libs/google_api.js"></script>   
    <script type="text/javascript">
/*
    google.load("visualization", "1", {packages:["corechart"]});
    google.setOnLoadCallback(drawChart);*/

    function drawChart(iduser,dispon,desemp,calidad) {

      /*
      //antigua grafica
      console.log('el id es: '+iduser);
      var data = google.visualization.arrayToDataTable([
          ['valor', 'porcentaje'],['DISPONIBILIDAD',dispon, 'Foo text'],['DESEMPEÑO',desemp, 'Foo text'],['CALIDAD',calidad, 'Foo text'],]);
      
      
        var options = { // api de google chats, son estilos css puestos desde js
            
            width: "100%", 
            height: "100%",
            chartArea: {left: 25, top: 10, width: "100%", height: "80%"},
            legend: 'none',
            enableInteractivity: true,                                               
            fontSize: 12,
            hAxis: {
                    textStyle: {
                      color: '#00927B'
                    }
                  },
            vAxis: {
                textStyle: {
                      color: '#00927B'
                    },
            viewWindowMode:'explicit',
            viewWindow: {
              max:100,
              min:0
            }
        },
            colors: ['#05BDE3'],    
            backgroundColor: 'transparent'
        };
      var chart = new google.visualization.ColumnChart(iduser);
      chart.draw(data,options);

      */
      //nueva grafica
      /*
      var data = new google.visualization.DataTable();
  data.addColumn('string', 'Name');
  data.addColumn('number', 'Value');
  data.addColumn({type: 'string', role: 'annotation'});
  
  data.addRows([
    ['DISPONIBILIDAD', dispon, 'DISPONIBILIDAD'],
    ['DESEMPEÑO', desemp, 'DESEMPEÑO'],
    ['CALIDAD', calidad, 'CALIDAD']
  ]);

  var view = new google.visualization.DataView(data);
  view.setColumns([0, 1, 1, 2]);

  var chart = new google.visualization.ComboChart(iduser);

  chart.draw(view, {
    height:"100%",
    width: "100%",
    enableInteractivity: false,
    chartArea: {left: 25, top: 10, width: "100%", height: "80%"},
     hAxis: {
                    textStyle: {
                      color: '#00927B'
                    }
                  },
            vAxis: {
                textStyle: {
                      color: '#00927B'
                    }},
              viewWindowMode:'explicit',
            viewWindow: {
              max:100,
              min:0
            }
        ,
    colors: ['#05BDE3'],    
    backgroundColor: 'transparent',
    series: {
      0: {
        type: 'bars'
      },
      1: {
        type: 'line',
        color: 'grey',
        lineWidth: 0,
        legend: false,
        pointSize: 0,
        visibleInLegend: false
      }
    },
    vAxis: {
      maxValue: 100,
      minValue: 0

    }
  });*/
  }
  </script>   
  <script src="<?php echo URL; ?>public/js/libs/jquery.min.js"></script>
  <script src="<?php echo URL; ?>public/js/easytimer.min.js"></script>
 <!-- <script src="<?php echo URL; ?>public/js/1_11_3_jquery.min.js"></script> -->  
  <script src="<?php echo URL; ?>public/js/jquery.circliful.js"></script>
<script src="<?php echo URL; ?>public/js/libs/2.1.4.jquery.min.js"></script>
    <script>
    var jQuery214=$.noConflict(true);
  </script>
  <script src="<?php echo URL; ?>public/js/softkeys-0.0.1.js?v=2"></script>
<link rel="stylesheet" href="<?php echo URL; ?>public/js/css/softkeys-0.0.1.css?v=3">
<body>
<ul class="topbar">
  <li class="burger"><a class="active drop-menu" href="javascript:void(0)">Mas opciones</a></li>
  
  <li><a href="javascript:void(0)">Orden actual: <span><?=$_SESSION['odt']; ?></span> </a></li>
  <li>
    <a style="color: #fff;" href="javascript:void(0)">1</a>
  </li>
  <li><div id="team-lunch-time">HORA DE COMIDA</div></li>
  <li><div id="team-alert">ALERTA</div></li>
  <li style="float:right"><div id="finish-change" class="save">TERMINAR CAMBIO</div></li>

</ul>
<div class="members-container">
<?php 

$members=$sessions_model->getTeamMembersBySession($_SESSION['sessionID']);
foreach ($members as $key => $member){
  $currentActivity=$sessions_model->getMemberActivity($member['id']);
  $ete=$ete_model->getEteByUser($member['id'],TODAY);
 ?>
<!-- credencial --> 
<div class="member <?=(!$sessions_model->checkSessionByUser($member['id']))? 'disabled':'' ?>" id="member-<?=$member['id'] ?>">
<div class="member-content <?=$currentActivity ?>" data-id="<?=$member['id']?>">
  <div class="member-header">
  <div class="member-photo">
    <img src="<?php echo URL; ?>public/<?=((!empty($member['foto']))? $member['foto'] :'images/default.jpg')?>">
  </div>
  <div class="member-name-timer">
    <p><?=$member['logged_in'] ?></p>
    <div class="personal-timer">
<span id="<?=$member['id'] ?>-timer">00:00:00</span>  
</div>
  </div>  
</div>
<div class="timer-band">
  <?=(isset($_SESSION['teamSession'][$member['id']]))?  ((isset($_SESSION['randomTasks'][$member['id']]))? ucwords($_SESSION['randomTasks'][$member['id']]) : $process_model->getProcessName($_SESSION['teamSession'][$member['id']]['memberProcessID'])) :'Sin asignar' ?>
</div>
<div class="member-body">
 <div id="<?=$member['id']?>" style="top:5px;width: 98%;left: 1px; height: 120px; position:absolute;"></div>  
</div>
</div>
</div>  
<!-- credencial --> 
<script>
var userid=document.getElementById(<?=$member['id'] ?>);
var timer_<?=$member['id'] ?> = new Timer();
 timer_<?=$member['id'] ?>.addEventListener('secondsUpdated', function (e) {
    $('#'+<?=$member['id'] ?>+'-timer').html(timer_<?=$member['id'] ?>.getTimeValues().toString());
});
  drawChart(userid,<?=$ete['disponibilidad'] ?>,<?=$ete['desempenio'] ?>,<?=$ete['calidad'] ?>);
  function start<?=$member['id'] ?>(elapsed){
    if (elapsed=='undefined') {
       timer_<?=$member['id'] ?>.start();
     }else{
       timer_<?=$member['id'] ?>.start({startValues: {seconds:elapsed
}});
     }
   
  }
  function stop<?=$member['id'] ?>(){
    timer_<?=$member['id'] ?>.stop();
  }
  <?php

  if ($currentActivity=='tiro') {
    echo "timer_".$member['id'].'.start({startValues: {seconds:'.$process_model->getTiroElapsedTime($_SESSION['teamSession'][$member['id']]['memberSessionID']).'}});';

  }elseif ($currentActivity=='alerta') {
    echo "timer_".$member['id'].'.start({startValues: {seconds:'.$process_model->getAlertElapsedTime($_SESSION['teamSession'][$member['id']]['memberSessionID']).'}});';

  }
elseif ($currentActivity=='comida') {
    echo "timer_".$member['id'].'.start({startValues: {seconds:'.$process_model->getLunchElapsedTime($_SESSION['teamSession'][$member['id']]['memberSessionID']).'}});';

  }
  ?>
</script>
<?php } ?>
</div>
<div class="mega-menu">
  <div class="mega-menu-cover"></div>
  <ul class="topbar">
  <li ><a href="javascript:void(0)">Elige una opción:</a></li>
  
  
  <li style="float:right"><div  class="close-mega-menu"></div></li>

</ul>
<div class="mega-menu-content">
  <div class="mega-menu-button" id="add-members">
    <span>AGREGAR INTEGRANTES AL EQUIPO</span>
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

</body>
<div class="big-lightbox">
  
</div>
<div class="box">
  
</div>
<div class="backdrop"></div>
<div id="key-operarios" class="keyboard">
<ul class="topbar">
  
  <li style="float:right"><div  class="close-bottom-key"></div></li>
</ul>
    
    <div class="keycontainer">
      <div id="softk" class="softkeys" data-target="input[name='getodt']"></div>
    </div>
    
      <div id="close-down-key" class="square-button-micro red " style="display: none;">
                          <img src="images/ex.png">
                        </div>
    
    
</div>
<div class="loader"></div>
<script src="<?php echo URL; ?>public/js/libs/jquery-ui.js"></script>
 <script>  
 /*  
$( ".member-content" ).click(function() {
    
    var functionName='start'+$(this).data('id');
    eval(functionName + "()");
  
}); */
jQuery214(document).on("click", ".member-content", function () {

  var user= $(this).data('id');
  $('.backdrop').animate({'opacity':'.50'}, 300, 'linear');
$('.backdrop').css('display', 'block');
$('.loader').show();
console.log('usuario picado: '+user);
  $.ajax({  
                      
          type:"POST",
          url:"<?php echo URL; ?>tiro/userInterface/",   
          data:{user:user}, 
          success:function(data){
            $('.loader').hide();
          $('.box').html(data);          
          $('.box').animate({'opacity':'1.00'}, 300, 'linear');
          $('.box').css('display', 'block');           
          }

          }); 
  
});

function closeModal(){
 r =false;
   $('.backdrop, .box').animate({'opacity':'0'}, 300, 'linear', function(){
          $('.backdrop, .box').css('display', 'none');
        });  
    if (kb==true) {
      closeKeyboard();
    }
}

jQuery214(document).on("click", ".formradio", function () {
   $('.selected').removeClass('selected');
  $(this).find('label').addClass('selected');
});
jQuery214(document).on("click", ".normal", function () {

                                  
        var option=$(this).data('option');     
         var station=$(this).data('station'); 
         var station_name=$(this).data('sname'); 
         var pro_name=$(this).data('pname');
         var user=$(this).data('user');
                                                             
          $.ajax({
                                    url: "<?php echo URL; ?>tiro/setProcess/",
                                    type: "POST",
                                    data:{option:option,is_random:'false',choose:'process',station:station,station_name:station_name,pro_name:pro_name,user:user},
                                    
                                    success: function(data){
                                      console.log(data);

                                      location.reload();
                                      /*
                                      $('#member-'+user).removeClass('disabled');
                                      $('#member-'+user+' .member-content').addClass('tiro');
                                      $('.op-close-modal').click();
                                      $('#member-'+user+' .timer-band').html(pro_name);
                                      */                                      
                                      //$('.box').html(data);                                      
                                     // var functionName='start'+user;
                                    //eval(functionName + "()");
                                    
                                    }        
    });                                      
                                             
    });

jQuery214(document).on("click", "#saver", function (){
  
  var option=$(this).data('option');     
         
         var pro_name=$('#custom-task').val();
         var user=$('#custom-task-user').val();
                                                             
          $.ajax({
                                    url: "<?php echo URL; ?>tiro/setProcess/",
                                    type: "POST",
                                    data:jQuery214('#other-form').serialize(),
                                    
                                    success: function(data){
                                      location.reload();                                      
                                      //$('.box').html(data);                                      
                                     // var functionName='start'+user;
                                    //eval(functionName + "()");
                                    
                                    }        
    }); 


});

jQuery214(document).on("click", ".process", function () {
 
   
  $('.p-selected').removeClass('p-selected');       
  $(this).addClass('p-selected');
  var target=$(this).data('target');
  $('.process').hide();
  $('.no-childs').hide();
  $('.other').hide();
  
  $('#'+target).show();
      
    

});

jQuery214(document).on("click", ".other", function (){
    
    $('#task-form').hide();
     $('#other-form').show();
    getKeysCustom('custom-task','custom-task');


});

function getKeysCustom(id,name) {
      $('#'+id).select();      
      jQuery214('#softk').attr('data-target', 'input[name="'+name+'"]');
        if (kb == false) {
            $("#key-operarios").animate({ bottom: '+=60%' }, 200);
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
                            
                            'z','x','c','v','b','n','m','←'],
                            ['__','GUARDAR']
                            ],

                    id:'softkeys'
                });
              
                jQuery214('#hidekey').parent('.softkeys__btn').addClass('hidder'); 
    jQuery214('#savekey').parent('.softkeys__btn').addClass('saver').attr('id', 'saver');;            
jQuery214('#borrar-letras').parent('.softkeys__btn').addClass('large');
            jQuery214('#borrar-softkeys').parent('.softkeys__btn').addClass('large');
            
    }



r = false;
var kb = false;
function getNumKeys(id,name) {
      $('#'+id).focus();
      jQuery214('.input-active').removeClass('input-active');
      jQuery214('#'+id).addClass('input-active');
      jQuery214('#softk').attr('data-target', 'input[name="'+name+'"]');
        if (r == false) {

            
            $("#teclado").animate({ left: '+=60%' }, 200);
            r = true;
        }
        else {
            
            
            r = true;
        } 
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
                            ['0',')'],
                           
                            
                            '←',
                            'GUARDAR'
                        ]
                    ],
                    id:'softkeys'
                });
               
    jQuery214('#savekey').parent('.softkeys__btn').addClass('saver');            
jQuery214('#borrar-letras').parent('.softkeys__btn').addClass('large');
            jQuery214('#borrar-softkeys').parent('.softkeys__btn').addClass('large');
    }

function getKeys(id,name) {
      $('#'+id).select();      
      jQuery214('#softk').attr('data-target', 'input[name="'+name+'"]');
        if (kb == false) {
          $("body").animate({ bottom: '+=20%' }, 200);
            $("#key-operarios").animate({ bottom: '+=60%' }, 200);
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
                            
                            'z','x','c','v','b','n','m','←'],
                            ['__','ALERT']
                            ],

                    id:'softkeys'
                });
              
                jQuery214('#hidekey').parent('.softkeys__btn').addClass('hidder'); 
    jQuery214('#savekey').parent('.softkeys__btn').addClass('saver').attr('id', 'saver');;            
jQuery214('#borrar-letras').parent('.softkeys__btn').addClass('large');
            jQuery214('#borrar-softkeys').parent('.softkeys__btn').addClass('large');
            
    }

    function getNumericKeys(id,name,photo,user,concept) {
       $('#'+id).focus();
      jQuery214('.input-active').removeClass('input-active');
      jQuery214('#'+id).addClass('input-active');
      jQuery214('#softk2').attr('data-target', 'input[name="'+name+'"]');
        if (r == false) {
            
            $("#teclado3").animate({ left: '+=40%' }, 200);
            r = true;
        }
        else {            
            
            r = true;
        } 
        $('#softk2').empty(); 
         $('#selected-photo').html('<img src="<?=URL?>public/'+photo+'">');
         $('#selected-name').html(user);
         $('#selected-concept').html(concept);      
         $('.softkeys2').softkeys({
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
                            ['0',')'],                                             
                            '←'
                        ]
                    ],
                    id:'softkeys'
                });
               
    jQuery214('#savekey').parent('.softkeys__btn').addClass('saver');            
jQuery214('#borrar-letras').parent('.softkeys__btn').addClass('large');
            jQuery214('#borrar-softkeys').parent('.softkeys__btn').addClass('large');
           
    }

jQuery214(document).on("click", "#alert", function () {
    var user=jQuery214(this).data('user');
    var member_session=jQuery214(this).data('msession');
    $('#member-'+user+' .member-content').removeClass('tiro');
    var stopFunction='stop'+user;
    var startFunction='start'+user;
    eval(stopFunction + "()");
      $.ajax({  
                      
          type:"POST",
          url:"<?php echo URL; ?>tiro/startAlert/",   
          data:{user:user,member_session:member_session}, 
          success:function(data){
          closeModal();
          //$('.box').html(data);
          $('#member-'+user+' .member-content').addClass('alerta');
          eval(startFunction + "()");
          

           
          }

          });
});

jQuery214(document).on("click", "#save-team", function () {
    //var user=jQuery214(this).data('user');
    //var member_session=jQuery214(this).data('msession');
    //$('#member-'+user+' .member-content').removeClass('tiro');
    //var stopFunction='stop'+user;
    //var startFunction='start'+user;
    //eval(stopFunction + "()");    

      $.ajax({                       
          type:"POST",
          url:"<?php echo URL; ?>tiro/saveTeamTiros/",   
          data:jQuery214('#t-tiros-form').serialize(), 
          success:function(data){
          console.log(data);          
          location.reload();
           
          }

          });
});



function getDefectos(){
  
                  var defect;
                  var ajuste=$('#ajuste').val();
                  console.log('ajuste: '+ajuste);
                  if (parseInt(ajuste)>2) {
                               defect=parseInt(ajuste)-2;
                              $('#defectos').val(defect);
                  } else if(ajuste==0){
                    $('#defectos').val(0);
                  }else if(ajuste==''){
                    $('#defectos').val(0);
                  }else{
                   $('#defectos').val(0); 
                  }

}
function getMerma(){
  
                  var defect;
                  var pedido=$('#pedido').val();
                  var buenos=$('#buenos').val();
                  console.log('ajuste: '+ajuste);

                  var merma=parseInt(buenos)-parseInt(pedido);

                  if (merma>0) {
                    $('#merma').val(merma);
                  }else{
                    $('#merma').val(0);
                  }
                 

}


jQuery214(document).on("click", "#return", function () {
  location.reload();
});

jQuery214(document).on("click", ".close-modal", function () {
  
    timer.stop();
  closeModal();
});
jQuery214(document).on("click", ".op-close-modal", function () {
    closeModal();
});

jQuery214(document).on("click", ".closer-alert", function () {
timerAlert.stop();
closeModal();


});
jQuery214(document).on("click", ".closer-lunch", function () {
timerLunch.stop();
closeModal();


});
jQuery214(document).on("click", "#save-alerta", function () {
  var user=jQuery214('#usuario').val();

  if (kb==true) {
      closeKeyboard();
    }

timerAlert.pause();
$('#timealerta').val(timerAlert.getTimeValues().toString());
$('#member-'+user+' .member-content').removeClass('alerta');
$('#member-'+user+' .member-content').addClass('tiro');
timerAlert.stop();
var stopFunction='stop'+user;
var startFunction='start'+user;
eval(stopFunction + "()");
$.ajax({  
                      
          type:"POST",
          url:"<?php echo URL; ?>tiro/saveAlert/",   
          data:jQuery214('#alert-form').serialize(), 
          success:function(data){
            $('.box').html(data);
            var elapsed=jQuery214('.maintimer').data('inicio');
           
            eval(startFunction + "("+elapsed+")");
         
                    }

          });

});

jQuery214(document).on("click", "#close-down", function () {
$("#teclado").animate({ left: '-=60%' }, 200);     
  r=false;
});
jQuery214(document).on("click", "#close-down2", function () {
$("#teclado3").animate({ left: '-=40%' }, 200);     
  r=false;
});




jQuery214(document).on("click", "#save-tiro", function () {

    var  buenos=jQuery214('#buenos').val();
    var  ajuste=jQuery214('#ajuste').val();
    var  recibidos=jQuery214('#recibidos').val();
    var  pedido=jQuery214('#pedido').val();

        if (buenos!=''&&ajuste!=''&&recibidos!=''&&pedido!=''){
          var subtotal=parseInt(buenos)+parseInt(ajuste);
          /*if (subtotal!=parseInt(recibidos)) {
            if (subtotal>parseInt(recibidos)) {
              alert('ERROR: la cantidad recibida no puede ser menor que los buenos )'+buenos+') mas las piezas de ajuste ('+ajuste+')');
            }if (subtotal<parseInt(recibidos)) {
              alert('ERROR: no pueden haber '+buenos+' buenos y '+ajuste+' de ajuste si se recibieron '+recibidos+' piezas');
            }
            
          }else{ */
          var user= jQuery214('#user').val();
          timer.pause();
          $('#tiempo-tiraje').val(timer.getTimeValues().toString());
          $('#member-'+user+' .member-content').removeClass('tiro');
          var stopFunction='stop'+user;
          var startFunction='start'+user;
          eval(stopFunction + "()");
          timer.stop();
          $.ajax({  
                      
          type:"POST",
          url:"<?php echo URL; ?>tiro/finishTiro/",   
          data:jQuery214('#tiro-values').serialize(), 
          success:function(data){
            
         $('.box').html(data);
         //eval(startFunction + "()");
                    }

          });
        //}
        }else{
                if (buenos==''){
                    $('#buenos').addClass("not_pass").attr("placeholder", "?").effect( "shake" );
                }
                if (ajuste==''){
                    $('#ajuste').addClass("not_pass").attr("placeholder", "?").effect( "shake" );
                }
                if (recibidos==''){
                    $('#recibidos').addClass("not_pass").attr("placeholder", "?").effect( "shake" );
                }
                if (pedido==''){
                    $('#pedido').addClass("not_pass").attr("placeholder", "?").effect( "shake" );
                }

                            

  }

  
 



});
jQuery214(document).on("click", ".radio-menu", function () {
  $('.selected').removeClass('selected');
  $(this).addClass('selected').find('input').prop('checked', true);    
});


jQuery214(document).on("click", "#change-team", function () {
  var user= jQuery214(this).data('user');

  $.ajax({  
                      
          type:"POST",
          url:"<?php echo URL; ?>tiro/teamOptions/",   
          data:{user:user}, 
          success:function(data){
            
         $('.box').html(data);
         //eval(startFunction + "()");
                    }

          });    
});
jQuery214(document).on("click", "#lunch", function () {

  var user= jQuery214(this).data('user');
  var msession= jQuery214(this).data('msession');
  timer.stop();
  $('#member-'+user+' .member-content').removeClass('tiro');
$('#member-'+user+' .member-content').addClass('comida');
var stopFunction='stop'+user;
var startFunction='start'+user;
eval(stopFunction + "()");
  $.ajax({  
                      
          type:"POST",
          url:"<?php echo URL; ?>tiro/lunchTime/",   
          data:{user:user}, 
          success:function(data){
         closeModal();   
         //$('.box').html(data);
         eval(startFunction + "()");
                    }

          });    
});
jQuery214(document).on("click", "#savekey", function () {
  
  jQuery214('#save-tiro').click();
});


jQuery214(document).on("click", "#save-lunch", function () {
  var user=jQuery214('#usuario').val();

timerLunch.pause();
$('#timelunch').val(timerLunch.getTimeValues().toString());
$('#member-'+user+' .member-content').removeClass('comida');
$('#member-'+user+' .member-content').addClass('tiro');
timerLunch.stop();
var stopFunction='stop'+user;
var startFunction='start'+user;
eval(stopFunction + "()");
$.ajax({  
                      
          type:"POST",
          url:"<?php echo URL; ?>tiro/saveLunch/",   
          data:jQuery214('#lunch-form').serialize(), 
          success:function(data){
            $('.box').html(data);
            var elapsed=jQuery214('.maintimer').data('inicio');
           
            eval(startFunction + "("+elapsed+")");
         
                    }

          });

});

jQuery214(document).on("click", "#change-activity", function () {
var user=jQuery214(this).data('user');

//timerAlert.pause();
//$('#timelunch').val(timerAlert.getTimeValues().toString());
$('#member-'+user+' .member-content').removeClass('comida');
$('#member-'+user+' .member-content').addClass('tiro');


$.ajax({             
          type:"POST",
          url:"<?php echo URL; ?>tiro/activitySwitch/",   
          data:{user:user}, 
          success:function(data){
            $('.box').html(data);
            //var elapsed=jQuery214('.maintimer').data('inicio');
            //eval(startFunction + "("+elapsed+")");         
                    }
          });
});
jQuery214(document).on("click", ".free", function () {
var leader=jQuery214(this).data('leader');
var user=jQuery214(this).data('user');
$.ajax({  
                      
          type:"POST",
          url:"<?php echo URL; ?>tiro/teamExchange/",   
          data:{user:user}, 
          success:function(data){
            closeModal();            
            //jQuery214('#member-'+user).remove();
            //var elapsed=jQuery214('.maintimer').data('inicio');
            //eval(startFunction + "("+elapsed+")");
                    }

          });

});

jQuery214(document).on("click", "#finish-change", function () {
var tiros=jQuery214('.tiro').length;
var comida=jQuery214('.comida').length;
var alerta=jQuery214('.alerta').length;
console.log('tiradores: '+tiros);
var qty;
var actives=tiros+comida+alerta;
if (actives>0) {
  if (actives>1) {qty='s'}else{qty=''}
  
  alert('Todavia hay '+actives+' operario'+qty+' tirando');
}else{
  $.ajax({  
                      
          type:"POST",
          url:"<?php echo URL; ?>tiro/finishCambio/",   
          data:{user:'user'}, 
          success:function(data){
          
            console.log(data);
            location.href = '<?php echo URL; ?>inicio/';
         
                    }

          });
}


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

    function closeBigBox(){

          $('.big-lightbox').animate({'opacity':'0'}, 300, 'linear');
          $('.big-lightbox').css('display','none');
}

    jQuery214(document).on("click", "#add-members", function () {
  
  var actividad='actividad';
 
 $.ajax({            
          type:"POST",
          url:"<?php echo URL; ?>tiro/addMembers/",   
          data:{actividad:actividad}, 
          success:function(data){

          $('.big-lightbox').html(data);          
          $('.big-lightbox').animate({'opacity':'1.00'}, 300, 'linear');
          $('.big-lightbox').css('display','block');
          menu();           
          }
});

});

  jQuery214(document).on("click", "#admin_team", function () {
  jQuery214('.close-modal').click();
  var actividad='actividad';
 
 $.ajax({            
          type:"POST",
          url:"<?php echo URL; ?>tiro/adminTeam/",   
          data:{actividad:actividad}, 
          success:function(data){

          $('.big-lightbox').html(data);          
          $('.big-lightbox').animate({'opacity':'1.00'}, 300, 'linear');
          $('.big-lightbox').css('display','block');
          menu();           
          }
});

});

    function closeKeyboard(){
    if (kb==true) {
      $("#key-operarios").animate({ bottom: '-=60%' }, 200);
       $("body").animate({ bottom: '-=20%' }, 200);
     kb=false;
    }
     
  }

  jQuery214(document).on("click", ".close-bottom-key", function () {
    closeKeyboard();
});

  jQuery214(document).on("click", "#savealert", function () {
    $('#save-alerta').click();
});

jQuery214(document).on("click", "#team-alert", function () {
console.log('una alerta para dominarlos a todos');
  $.ajax({            
          type:"POST",
          url:"<?php echo URL; ?>tiro/startTeamAlert/",   
          data:{actividad:'actividad'},
          dataType:"json", 
          success:function(data){
            console.log(data);
            if (data.response=='success'){
              location.reload();
            }else
            alert('algo salio mal, por favor hablale a los de sistemas');
          }
});
    
});



 </script>
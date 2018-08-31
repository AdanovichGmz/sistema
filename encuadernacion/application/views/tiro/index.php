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
  <script src="<?php echo URL; ?>public/js/softkeys-0.0.1.js?"></script>
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
  <?=(isset($_SESSION['teamSession'][$member['id']]))? preg_replace('/\s+/', '', $process_model->getProcessName($_SESSION['teamSession'][$member['id']]['memberProcessID'])) :'Sin asignar' ?>
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
console.log('usuario picado: '+user);
  $.ajax({  
                      
          type:"POST",
          url:"<?php echo URL; ?>tiro/userInterface/",   
          data:{user:user}, 
          success:function(data){

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
                                    data:{option:option,choose:'process',station:station,station_name:station_name,pro_name:pro_name,user:user},
                                    
                                    success: function(data){
                                      
                                      $('#member-'+user).removeClass('disabled');
                                      $('#member-'+user+' .member-content').addClass('tiro');
                                      $('.box').html(data);
                                      var functionName='start'+user;
                                    eval(functionName + "()");
                                    
                                    }        
    });                                      
                                             
    });
r = false;
function getKeys(id,name) {
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

jQuery214(document).on("click", "#return", function () {
  closeBigBox();
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
jQuery214(document).on("click", "#save-alerta", function () {
  var user=jQuery214('#usuario').val();

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

jQuery214(document).on("click", "#save-tiro", function () {
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
console.log('tiradores: '+tiros);
var qty;
if (tiros>0) {
  if (tiros>1) {qty='s'}else{qty=''}
  
  alert('Todavia hay '+tiros+' operario'+qty+' tirando');
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
 </script>
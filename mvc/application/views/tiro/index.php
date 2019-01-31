  <!-- bar chart -->
    <script type="text/javascript" src="<?php echo URL; ?>public/js/libs/google_api.js"></script>   
    <script type="text/javascript">

   google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawPieChart);
      google.setOnLoadCallback(drawColumnChart);
      function drawPieChart() {
        var data = google.visualization.arrayToDataTable([
          ['Type', 'ETE'],['<?="ETE ".round($ete['ete'])."%" ?>', <?=round($ete['ete'])
?>],['<?="MUDA ".round(($muda<0)? 0 : $muda)."%" ?>', <?=($muda<0)? 0 : $muda;
     
?>] ]);
      var muda_color;
     
        muda_color='#2d2d2d';
      
        var options = {chartArea: {width: '90%',  height: '90%'},
                       
                       pieSliceTextStyle: {color: 'white', fontSize: 16},
                       
                       legend: 'none',
                    pieSliceText: 'label',
                       is3D:false,                                               
                      // enableInteractivity: false,
                       colors: ['#05BDE3',muda_color ],
                                           
                       backgroundColor: 'transparent'
        };
        var chart = new google.visualization.PieChart(document.getElementById('piechart'));
        chart.draw(data, options);
      }

     
    function drawColumnChart() {
      var data = google.visualization.arrayToDataTable([
          ['valor', 'porcentaje'],
         <?php
    echo "['DISPONIBILIDAD'," .round($dispon_tope,2)  . "],";
    echo "['DESEMPEÑO'," . round($desemp_tope,2)  . "],";
    echo "['CALIDAD'," . round($calidad_tope,2) . "],";
    
?> ]);
      var nomen_color;
      
        nomen_color='#ffffff';
      
        var options = { // api de google chats, son estilos css puestos desde js
            chartArea: {width: '100%', height: '90%'},
            width: "100%", 
            height: "100%",
            chartArea: {left: 25, top: 10, width: "100%", height: "80%"},
            legend: 'none',
            enableInteractivity: true,                                               
            fontSize: 11,
            hAxis: {
                    textStyle: {
                      color: nomen_color
                    }
                  },
            vAxis: {
                textStyle: {
                      color: nomen_color
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
      var chart = new google.visualization.ColumnChart(document.getElementById("columnchart"));
      chart.draw(data,options);
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
<input type="hidden" id="actividad_actual" value="<?=$seccion['actividad_actual'] ?>">
<input type="hidden" id="multi_process" value="<?=$_SESSION['multi_process'] ?>">
<div class="tiro-panel">
<ul class="topbar">
<li style="font-weight: bold;"><a class="active" href="#"><span><?=$userInfo['logged_in']; ?></span></a></li>
<li ><a class="active" href="#"> <span> <?= preg_replace('/\s+/', '', $process_model->getProcessName($_SESSION['processSelected'])) ?></span></a></li>
  <li><a  href="#"><span>Tiros: <?=$tiros; ?></span></a></li>
  <li><a href="#"><span>Merma: <?=$merma; ?></span> </a></li>
  
  
</ul>
<div class="graphics-container">
<div class="graphic">
<div class="graphic-content">
  <div class="stat-head"><div class="efectivity"><?=round($ete['ete']) ?>%</div></div>
    <table class="orders-table gree">
  <tr class="trhead">
    <td class="orders-td2" >ACTUAL:</td>
  </tr>
  <tr>
    <td class="orders-td" style="color:#2c97de">
      <?=$current_task; ?>
    </td>
  </tr>
</table>
<table class="orders-table gree">
  <tr class="trhead">
    <td class="orders-td2">SIGUIENTE:</td>
  </tr>
  <tr>
    <td class="orders-td">
    --
   </td>
  </tr>
</table>
<table class="orders-table gree">
  <tr class="trhead">
    <td class="orders-td2">PREPARACIÓN:</td>
  </tr>
  <tr>
    <td class="orders-td" >
      --
    </td>
  </tr>
</table>
  </div>
</div>
 <div class="graphic">
  <div class="graphic-content">
    <div  id="piechart" style="height: 100%;"></div>
  </div>
</div>
<div class="graphic">
  <div class="graphic-content">
    <div  id="columnchart" style="height: 100%; margin-top: 10px;"></div>
  </div>
</div> 
</div>
<div class="controls-container">
<div class="control-buttons">
  <div class="maintimer" id="maintimer" data-inicio="<?=$process_model->getTiroElapsedTime($_SESSION['sessionID']) ?>"><span class="values timer-display">00:00:00</span></div>
  <div class="prod-esperada">
    <div>PRODUCCION ESPERADA</div>
    <p id="p-esperada" data-pph="<?=$process_model->getPiezasPorHora($working['idproducto']); ?>">0</p>
  </div>
  <div class="button-container">
  <a href="<?php echo URL; ?>logout">
<div  class="m-button red ">
                          <img src="<?php echo URL; ?>public/img/sal.png">
</div></a>
<div id="lunch" data-msession="<?=$_SESSION['sessionID'] ?>" data-user="<?=$_SESSION['user']['id'] ?>" class="m-button green">
                          <img src="<?php echo URL; ?>public/img/dinner2.png">
</div>
<div id="save-tiro" class="m-button blue ">
                          <img src="<?php echo URL; ?>public/img/guard.png">
</div>

<div id="alert" data-msession="<?=$_SESSION['sessionID'] ?>" data-user="<?=$_SESSION['user']['id'] ?>" class="m-button yellow">
                          <img src="<?php echo URL; ?>public/img/alerts.png">
</div>
<div class="m-button purple" data-msession="<?=$_SESSION['sessionID'] ?>" data-user="<?=$_SESSION['user']['id'] ?>">
                          <img src="<?php echo URL; ?>public/img/cantir.png">
                        </div>

  
</div>
</div>
 <div class="control-inputs">
 <form id="tiro-values" method="POST">
 <input type="hidden" name="tiempo-tiraje" id="tiempo-tiraje">
 <input type="hidden" name="user" id="user" value="<?=$_SESSION['user']['id'] ?>">
 <input type="hidden" name="proceso" value="<?=$proceso ?>">
 <input type="hidden" name="tiro-actual" value="<?=$_SESSION['tiroActual'] ?>">
 <table>
   <tr>
     <td>CANTIDAD DE PEDIDO</td>
     <td>BUENOS</td>
   </tr>
   <tr>
     <td><input type="number" readonly name="pedido" id="pedido" onclick="getKeys(this.id,'pedido')" onkeyup="opera();"></td>
     <td><input type="number" readonly name="buenos" id="buenos" onclick="getKeys(this.id,'buenos')" onkeyup="opera();"></td>
   </tr>
   <tr>
     <td>CANTIDAD RECIBIDA</td>
     <td>PIEZAS DE AJUSTE</td>
   </tr>
   <tr>
     <td><input type="number" readonly name="recibidos" id="recibidos" onclick="getKeys(this.id,'recibidos')" onkeyup="opera();"></td>
     <td><input type="number" readonly name="ajuste" id="ajuste" onclick="getKeys(this.id,'ajuste')" onkeyup="getDefectos();"></td>
   </tr>
   <tr>
     <td>MERMA</td>
     <td>DEFECTOS</td>
   </tr>
   <tr>
     <td><input type="number" readonly name="merma" id="merma" onclick="getKeys(this.id,'merma')"></td>
     <td><input type="number" readonly name="defectos" id="defectos" onclick="getKeys(this.id,'defectos')"></td>
   </tr>
 </table>
</form>
  
</div> 
</div>
  
</div>
<div id="teclado">
  <?php include 'teclado.php' ?>
</div>

</body>
<div class="big-lightbox" <?=($seccion['actividad_actual']=='alerta'&&$seccion['seccion_alert']=='tiro')?'style="opacity: 1; display: block;"':(($seccion['actividad_actual']=='comida'&&$seccion['seccion_comida']=='tiro')?'style="opacity: 1; display: block;"':'' ) ?>>
   <?=($seccion['actividad_actual']=='alerta'&&$seccion['seccion_alert']=='tiro')? include 'alerta.php':(($seccion['actividad_actual']=='comida'&&$seccion['seccion_comida']=='tiro')? include 'comida.php':'') ?>
</div>
<div class="box">
  
</div>
<div class="loader">
  <img src="<?php echo URL; ?>public/img/whloader.gif">
</div>
<div class="backdrop"></div>
<script src="<?php echo URL; ?>public/js/libs/jquery-ui.js"></script>
 <script>  
 

var elapsed=$('#maintimer').data('inicio');

var timer = new Timer();
timer.addEventListener('secondsUpdated', function (e) {
    $('#maintimer .timer-display').html(timer.getTimeValues().toString());
});

$(document).ready(function(){
  
timer.start({startValues: {seconds:elapsed}});
  if ($('#actividad_actual').val()!='tiro') {
    timer.pause();
  }
   

setInterval(function() {
  var pph=parseInt($('#p-esperada').data('pph'));
var transcur=parseInt(hmsToSecondsOnly($('.timer-display').html()))/60;

  var esperada=(pph*transcur)/60;
  $('#p-esperada').html(Math.round(esperada));
    
}, 2 * 1000);


});
function closeModal(){
 r =false;
   $('.backdrop, .box').animate({'opacity':'0'}, 300, 'linear', function(){
          $('.backdrop, .box').css('display', 'none');
        });
}
function hmsToSecondsOnly(str) {
    var p = str.split(':'),
        s = 0, m = 1;

    while (p.length > 0) {
        s += m * parseInt(p.pop(), 10);
        m *= 60;
    }

    return s;
}
jQuery214(document).on("click", ".formradio", function () {
   $('.selected').removeClass('selected');
  $(this).find('label').addClass('selected');
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
    timer.pause();
      $.ajax({  
                      
          type:"POST",
          url:"<?php echo URL; ?>tiro/startAlert/",   
          data:{seccion:'tiro'}, 
          success:function(data){
          console.log(data);
          //$('.box').html(data);
           $('.big-lightbox').html(data);
          
          $('.big-lightbox').animate({'opacity':'1.00'}, 300, 'linear');
          $('.big-lightbox').css('display', 'block'); 
          }

          });
});


jQuery214(document).on("click", "#save-alerta", function () {
  var user=jQuery214('#usuario').val();

timerAlert.pause();
$('#timealerta').val(timerAlert.getTimeValues().toString());
$('#member-'+user+' .member-content').removeClass('alerta');
$('#member-'+user+' .member-content').addClass('tiro');
timerAlert.stop();

$.ajax({  
                      
          type:"POST",
          url:"<?php echo URL; ?>tiro/saveAlert/",   
          data:jQuery214('#alert-form').serialize(), 
          success:function(data){
            console.log(data);
           timer.start();
            closeBigBox();
         
                    }

          });

});

jQuery214(document).on("click", "#close-down", function () {
$("#teclado").animate({ left: '-=60%' }, 200);
jQuery214('.input-active').removeClass('input-active');    
  r=false;
});

jQuery214(document).on("click", "#save-tiro", function () {
    var buenos=$('#buenos').val();
                          var merma=$('#merma').val();
                          
                          var recibidos=$('#recibidos').val();
                          
                          var ajuste=$('#ajuste').val();
                          var pedido=$('#pedido').val();
                          var defectos=$('#defectos').val();

  if (buenos!=''&&ajuste!=''&&recibidos!=''&&pedido!='') {
    var user= jQuery214('#user').val();
      timer.pause();
      $('#tiempo-tiraje').val(timer.getTimeValues().toString());

      $('.backdrop').animate({'opacity':'.50'}, 300, 'linear');
          $('.backdrop').css('display', 'block');
          $('.loader').show();
      timer.stop();
      $.ajax({  
                            
                type:"POST",
                url:"<?php echo URL; ?>tiro/finishCambio/",   
                data:jQuery214('#tiro-values').serialize(), 
                success:function(data){
                  console.log(data);
                  location.href = '<?php echo URL; ?>encuesta/';
                  
                }

          });
}else{
                            if (buenos==''){
                            $('#buenos').addClass("errror").attr("placeholder", "?").effect( "shake" );
                            }
                            if (ajuste==''){
                              $('#ajuste').addClass("errror").attr("placeholder", "?").effect( "shake" );
                            }
                              if (recibidos==''){
                              $('#recibidos').addClass("errror").attr("placeholder", "?").effect( "shake" );
                            }
                            if (pedido==''){
                              $('#pedido').addClass("errror").attr("placeholder", "?").effect( "shake" );
                            }

                            

                           }
 

});

jQuery214(document).on("click", ".radio-menu", function () {
  $('.selected').removeClass('selected');
  $(this).addClass('selected').find('input').prop('checked', true);    
});


jQuery214(document).on("click", "#lunch", function () {

  var user= jQuery214(this).data('user');
  var msession= jQuery214(this).data('msession');
  timer.pause();


  $.ajax({  
                      
          type:"POST",
          url:"<?php echo URL; ?>tiro/lunchTime/",   
          data:{user:user}, 
          success:function(data){
          $('.big-lightbox').html(data);
          
          $('.big-lightbox').animate({'opacity':'1.00'}, 300, 'linear');
          $('.big-lightbox').css('display', 'block'); 

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

timerLunch.stop();

$.ajax({  
                      
          type:"POST",
          url:"<?php echo URL; ?>tiro/saveLunch/",   
          data:jQuery214('#lunch-form').serialize(), 
          success:function(data){
            console.log(data);
            
            timer.start();
            closeBigBox();
         
                    }

          });

});




function closeBigBox(){

          $('.big-lightbox').animate({'opacity':'0'}, 300, 'linear');
          $('.big-lightbox').css('display','none');
          $('.big-lightbox2').animate({'opacity':'0'}, 300, 'linear');
          $('.big-lightbox2').css('display','none');
}

function opera(){ 
     
                           var buenos = document.getElementById('buenos').value;
                           
                            var ajuste = document.getElementById('ajuste').value;
                            var pedido= document.getElementById('pedido').value;
                            if (ajuste>2) {
                              defectos =(parseInt(ajuste)-2);
                            }else{
                              defectos =0;
                            }
                            mermaent=parseInt(buenos)-parseInt(pedido);
                             if (mermaent<0) {
                              mermaent =0;
                            }
                            entregados=(parseInt(ajuste)+parseInt(mermaent))+parseInt(buenos);
                           
                            document.getElementById("defectos").value = defectos;
                            document.getElementById("merma").value = mermaent;
                            
                           
}

function getDefectos(){ console.log('hola defectuoso');
                            var defect;
                            var ajuste= $('#ajuste').val();
                            if (parseInt(ajuste)>2) {
                               defect=parseInt(ajuste)-2;
                              $('#defectos').val(defect);
                            }else if(ajuste==''){
                              $('#defectos').val(0);
                            }else{
                              $('#defectos').val(0);
                            }
                        }
  
 </script>
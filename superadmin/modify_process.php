 <?php
 date_default_timezone_set("America/Mexico_City");
if( !session_id() )
{
    session_start();
    

}
if(@$_SESSION['logged_in'] != true){
    echo '
     <script>
        alert("No has iniciado sesion");
        self.location.replace("../index.php");
    </script>';
}else{
    echo '';
}
    ?>


     <?php
     require('../saves/conexion.php');
  

  $prods=$mysqli->query("SELECT * FROM elementos ORDER BY nombre_elemento ASC");
  $proceso_info=$mysqli->query("SELECT * FROM procesos_catalogo WHERE id_proceso=".$_GET['process']);
  //$default=$mysqli->query('SELECT * FROM estandares WHERE id_elemento=144 AND id_proceso='.)
  $proceso=mysqli_fetch_assoc($proceso_info);
  $def_query="SELECT * FROM estandares WHERE id_elemento=144 AND id_proceso=".$_GET['process'];
$default=$mysqli->query($def_query);
$def=mysqli_fetch_assoc($default);
  
  $ops=$mysqli->query("SELECT * FROM usuarios WHERE app_active='true' ORDER BY logged_in ASC");


    ?>



  
<!DOCTYPE html>

<html lang="en" dir="ltr" xmlns:fb="http://ogp.me/ns/fb#">
<head>

    
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <title>REPORTE ETE</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
    <script src="../js/jquery.min2_1_4.js"></script>
    <script>
    var jQuery214=$.noConflict(true);
  </script>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous" />
    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous" />
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script> 
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script>
<link rel="stylesheet" href="../css/choosen.css">

    <link href="../css/estilosadmin.css" rel="stylesheet" />

   
      <link rel="stylesheet" href="../fonts/style.css">
  <script src="http://code.jquery.com/jquery-latest.js"></script>
  <script src="../js/main.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/js/select2.full.min.js"></script>

<script src="../js/choosen.js"></script>



<script type="text/javascript">


(function(document) {
  'use strict';

  var LightTableFilter = (function(Arr) {

    var _input;

    function _onInputEvent(e) {
      _input = e.target;
      var tables = document.getElementsByClassName(_input.getAttribute('data-table'));
      Arr.forEach.call(tables, function(table) {
        Arr.forEach.call(table.tBodies,  _filter);
      });
    }

    function _filter(row) {
      
      var text = row.textContent.toLowerCase(), val = _input.value.toLowerCase();
      row.style.display = text.indexOf(val) === -1 ? 'none' : '';
    }

    return {
      init: function() {
        var inputs = document.getElementsByClassName('light-table-filter');
        Arr.forEach.call(inputs, function(input) {
          input.oninput = _onInputEvent;
        });
      }
    };
  })(Array.prototype);

  document.addEventListener('readystatechange', function() {
    if (document.readyState === 'complete') {
      LightTableFilter.init();
    }
  });

})(document);
</script>

  <link rel="stylesheet" type="text/css" href="../css/jquery-ui-1.7.2.custom.css" />
<link rel="stylesheet" type="text/css" href="../css/process.css" />
</head>
<body style=";">

<?php include("topbar.php");  ?>

<div class="" id="main-content">
<div class="left-controls">
  <div class="left-content">
  <div class="left-title" style="font-size: 30px;">
    <?=$proceso['nombre_proceso'] ?>
  </div>


    <div id='cssmenu'>
<ul>
   <li class='active ajax-link' data-target="info" data-process="<?=$_GET['process'] ?>" data-title="Configuracion del proceso"><a href='#'><span>Configuracion</span></a></li>
   <li class='ajax-link' data-target="estandares" data-process="<?=$_GET['process'] ?>" data-title="Estandares del proceso"><a href='#'><span>Estandares</span></a>
      
   </li>
   <li class='has-sub' ><a href='#'><span>Alertas</span></a>
      <ul>
         <li class="ajax-link" data-target="alerts" data-tipo="alerta_ajuste" data-process="<?=$_GET['process'] ?>" data-title="Opciones de Alerta"><a href='#'><span>Ajuste</span></a></li>
         <li class='last ajax-link' data-target="alerts" data-tipo="alerta_tiro" data-process="<?=$_GET['process'] ?>" data-title="Opciones de Alerta"><a href='#'><span>Tiro</span></a></li>
      </ul>
   </li>
   <li class='has-sub' ><a href='#'><span>Encuesta</span></a>
      <ul>
         <li class="ajax-link" data-target="alerts" data-tipo="encuesta_lento" data-process="<?=$_GET['process'] ?>" data-title="Opciones de Encuesta"><a href='#'><span>Lo hice mas Lento</span></a></li>
         <li class='last ajax-link' data-target="alerts" data-tipo="encuesta_bien" data-process="<?=$_GET['process'] ?>" data-title="Opciones de Encuesta"><a href='#'><span>Lo hice bien a la primera</span></a></li>
      </ul>
   </li>
   <li class='ajax-link last' data-target="usuarios" data-process="<?=$_GET['process'] ?>" data-title="Estandares por proceso"><a href='#'><span>Operarios</span></a>
      
   </li>
   
</ul>
</div>

  </div>
</div><div class="right-controls">
  <div class="right-content">
  <div class="r-header">
  <div class="r-header-title">
    Configuracion del proceso
  </div>
  <div class="r-head-control">
    <input type="text" id="filterElem" name="dateFilter" placeholder="Filtrar por proceso..">
 
  </div>
    <div class="r-head-control">
      <button id="main-submit" class="btn btn-info">Guardar Cambios</button>
    </div>
  </div>
  
    <div class="r-wraper">
   <form method="post" id="main-form">
   <input type="hidden" name="section" value="info">
   <input type="hidden" name="proceso" value="<?=$_GET['process'] ?>">
   <table class="table-form">
     <tr>
       <td>Nombre del Proceso:</td>
       <td><input type="text" name="name" value="<?=$proceso['nombre_proceso'] ?>"></td>
     </tr>
      <tr>
       <td>Precio por Tiros:</td>
       <td><input type="text" name="precio_tiros" value="<?=$proceso['precio'] ?>"></td>
     </tr>
      <tr>
       <td>Precio por Cambios:</td>
       <td><input type="text" name="precio_cambio" value="<?=$proceso['precio_cambio'] ?>"></td>
     </tr>
     <tr>
       <td>Cambios Minimos</td>
       <td><input type="text" name="cambios_minimos" value="<?=$proceso['cambios_minimos'] ?>"></td>
     </tr>
      <tr>
       <td>Tiempo de ajuste predeterminado</td>
       <td><input type="text" name="ajuste" value="<?=$def['ajuste_standard']/60 ?>"></td>
     </tr>
     <tr>
       <td>Piezas por hora predeterminadas</td>
       <td><input type="text" name="piezas" value="<?=$def['piezas_por_hora'] ?>"></td>
     </tr>
   </table>
   
   
   </form>
   <div class="form-loader">
     <img src="../images/loader.gif">
   </div>   
    </div>

  </div>
</div>  
</div>
 
 <div class="popup">
   <div class="fail" id="message"><div></div><span>Exito: </span>Datos guardados!</div>
 </div>
 <div class="backdrop"></div>
 <div class="white-backdrop"></div>
 <div class="newtiro-modal">
<div class="close"></div>
  <div class="form-stuff">

<form id="newStandard" method="POST" >

  <div style="">
  <input type="hidden" id="ajuste-h" name="ajuste">
  <input type="hidden" id="piezas-h" name="piezas">
   <input type="hidden"  name="add-new" value="insert">
    <input type="hidden"  name="proceso" value="<?=$_GET['process'] ?>">
  <p>Elige uno o mas productos:</p>
  <table class="filter-table">
    <tr>
  <th style="font-size: 8px;">Seleccionar Todos</th>
  <th rowspan="2"><input type="text
  " id="elements-filter" placeholder="Filtrar elementos"></th>
  
</tr>
<tr>
  <th><input type="checkbox" id="select_all"></th>

</tr>
  </table>
  <div class="products">
    
  </div>
  
  
  </div>

  <div class="in-line">
     <p>Tiempo de ajuste:</p>
  <input type="number" required id="ajuste">
  </div><div class="in-line rig">
     <p>Piezas por hora:</p>
  <input type="number" required id="piezas">
  </div>

  
  <input type="submit" name="" value="GUARDAR">
  </form>
</div>
</div>
</body>
</html>

<script>
 
  $(".chosen").chosen();
  
  </script>

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/jquery-ui.min.js"></script>
<script>
var section=jQuery214('.active');

 $(document).ready(function () {

  var height= $(window).height();
  $('#main-content').height(height-60);

});



jQuery214(document).on("click", ".ajax-link", function () {
  var target=jQuery214(this).data('target');
  section=jQuery214(this);
  if (target=='alerts') {
    var type_option=jQuery214(this).data('tipo');
  }else{
    var type_option='false';
  }
  
  $('.v-active').removeClass('v-active');
  jQuery214(this).addClass('v-active');
  var proces=jQuery214(this).data('process');

  $('.r-header-title').html(jQuery214(this).data('title'));
  $('#main-form').hide();
   $('.form-loader').show();

   $.ajax({
        url: "edit_process.php",
        type: "POST",
        data:{option:target,process:proces,tipo:type_option},
        success: function(data){
        
        
        $('#main-form').html(data);
        if (target=='estandares') {
          
          $('.table-body').height($('.r-wraper').height()-170);
        }
        
        $('.form-loader').hide();
        $('#main-form').show();
        }        
       });
 
});



jQuery214(document).on("click", ".formradio", function (){
   $('.selected').removeClass('selected');
  $(this).find('label').addClass('selected');
});
jQuery214(document).on("keyup", "#fin-ajuste", function () {
   $('#in-tiro').val($(this).val());
  
});
jQuery214(document).on("click", "#main-submit", function () {
  $("#main-form").submit();
  console.log('se submiteo');
});  
$('#main-form').submit(function(e){
   e.preventDefault();
   $('#main-form').hide();
   $('.form-loader').show();
  $.ajax({
        url: "saveProcessInfo.php",
        type: "POST",
        data:$("#main-form").serialize(),
        success: function(data){
        
        $('#main-form').html(data);
        section.click();
        var message=jQuery214('#tipo').data('message');
        var tipo=jQuery214('#tipo').data('type');
        var clas=jQuery214('#tipo').data('clas');
        console.log('tipo: '+message);
        $('#message').removeClass().addClass(clas).html('<div></div><span>'+tipo+'</span>'+message+'</div>');
        $('.form-loader').hide();
        
         $('.popup').show().fadeIn( "slow" );
         $('#main-form').show().fadeIn("slow"); 
        setTimeout(function() {   
                  $('.popup').fadeOut( "slow" );
                }, 2000);
        
       
       
        }        
       });
});
$('#newStandard').submit(function(e){
   e.preventDefault();
   $('.white-backdrop').show();
   
  $.ajax({
        url: "dispon_prods.php",
        type: "POST",
        data:$(this).serialize(),
        success: function(data){
        
         $('.popup').html(data).show().fadeIn( "slow" );
         setTimeout(function() {   
                  $('.popup').fadeOut( "slow" );
                }, 2000);
         $("#cssmenu").find("[data-target='estandares']").click();
         $('.white-backdrop').hide();
         $('.close').click();
         $('#newStandard')[0].reset();
        }        
       });
});


jQuery214(document).on("click", ".edit-option", function (){
   var process_id=jQuery214(this).data('process');
   $('#main-form').hide();
   $('.form-loader').show();
  $.ajax({
        url: "edit_process.php",
        type: "POST",
        data:{option:process_id},
        success: function(data){
         $('#main-form').html(data);
$('.form-loader').hide();
$('#main-form').show().fadeIn("slow"); 

        }        
       });
});
jQuery214(document).on("keyup", "#ajuste", function (){
  jQuery214('#ajuste-h').val(jQuery214(this).val());
});
jQuery214(document).on("keyup", "#piezas", function (){
  jQuery214('#piezas-h').val(jQuery214(this).val());
});



    /*
    function saveToDatabase(editableObj,column,id) {

      $(editableObj).removeClass('editing');
      $.ajax({
        url: "editEte.php",
        type: "POST",
        data:'column='+column+'&editval='+editableObj.innerHTML+'&id='+id,
        success: function(data){
        console.log(data);
        }        
       });
    } */
    
   


  $("#filterElem").keyup( function() {
    var value = $(this).val().toLowerCase();
    $("#process-table tr").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });
  jQuery214(document).on("keyup", "#element-filter", function (){
  
    var value = $(this).val().toLowerCase();
    $("#table-elements tr").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });
jQuery214(document).on("keyup", "#elements-filter", function (){
  
    var value = $(this).val().toLowerCase();
    $("#dispon-elements tr").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });

jQuery214(document).on("change", "#select_all", function (){

     jQuery214('#dispon-elements :checkbox').prop('checked', this.checked);
   });

$(".quit-option").click( function() {

    $(this).closest("tr").remove();
});

jQuery214(document).on("click", "#selectAll", function (e){

    

     $('#standard-table').find(':checkbox').each(function(){
      console.log(this);
        jQuery214(this).click();

    }); 
});

jQuery214(document).on("click", ".add-standard", function (e){

var proceso=jQuery214(this).data('id');

   $('.backdrop').animate({'opacity':'.50'}, 300, 'linear');
$('.backdrop, ').css('display', 'block');
 $.ajax({
        url: "dispon_prods.php",
        type: "POST",
        data:{proceso:proceso},
        success: function(data){
         $('.products').html(data);

    $('.newtiro-modal').show();
    

        }        
       });


    


});
$('.close').click(function(){
    $('.newtiro-modal').hide();
    $('.reporte-semanal').hide();
       $('.backdrop').css('display', 'none');
       $('#procesosradio').empty();
    
   });
$('.backdrop').click(function(){
  $('.newtiro-modal').hide();
  $('.reporte-semanal').hide();
       $('.backdrop').css('display', 'none');
       $('#procesosradio').empty();
});


$(document).ready(function(){
    $('.submenu').click(function(){
        $(this).children('ul').toggle();
    });
});

( function( $ ) {
$( document ).ready(function() {
$(document).ready(function(){

$('#cssmenu > ul > li ul').each(function(index, e){
  var count = $(e).find('li').length;
  var content = '<span class=\"cnt\">' + '▼' + '</span>';
  $(e).closest('li').children('a').append(content);
});
$('#cssmenu ul ul li:odd').addClass('odd');
$('#cssmenu ul ul li:even').addClass('even');
$('#cssmenu > ul > li > a').click(function() {
  $('#cssmenu li').removeClass('active');
  $(this).closest('li').addClass('active'); 
  var checkElement = $(this).next();
  if((checkElement.is('ul')) && (checkElement.is(':visible'))) {
    $(this).closest('li').removeClass('active');
    checkElement.slideUp('normal');
  }
  if((checkElement.is('ul')) && (!checkElement.is(':visible'))) {
    $('#cssmenu ul ul:visible').slideUp('normal');
    checkElement.slideDown('normal');
  }
  if($(this).closest('li').find('ul').children().length == 0) {
    return true;
  } else {
    return false; 
  }   
});

});

});
} )( jQuery );


jQuery214(document).on("click", "#submit-actions", function (e){

  if (jQuery214('#actions').val()=='delete') {


     $('<div></div>').appendTo('body')
                    .html('<div><h6>Estas seguro de querer borrar estos estandares?</h6></div>')
                    .dialog({
                        modal: true, title: 'Eliminar Estandares', zIndex: 10000, autoOpen: true,
                        width: 'auto', resizable: false,
                        buttons: {
                            Si: function () {
                                // $(obj).removeAttr('onclick'); 
                                // $(obj).parents('.Parent').remove();
                                $.ajax({
                                  url: "dispon_prods.php",
                                  type: "POST",
                                  data:$('#main-form').serialize(),
                                  success: function(data){
                                    console.log(data);
                                  $("#cssmenu").find("[data-target='estandares']").click();
                                  }        
                                 });
                                
                                $(this).dialog("close");
                            },
                            No: function () {                                                             
                            
                                $(this).dialog("close");
                            }
                        },
                        close: function (event, ui) {
                            $(this).remove();
                        }
                    });



  }else if (jQuery214('#actions').val()=='edit'){

      var proceso=jQuery214(this).data('id');

      $('.backdrop').animate({'opacity':'.50'}, 300, 'linear');
      $('.backdrop, ').css('display', 'block');
       $.ajax({
              url: "dispon_prods.php",
              type: "POST",
              data:$('#main-form').serialize(),
              success: function(data){
               $('.products').html(data);

      $('.newtiro-modal').show();
    

        }        
       });

     

  }

     

    
    

   
});

    </script>

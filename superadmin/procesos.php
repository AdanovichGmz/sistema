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
  $procesos=$mysqli->query("SELECT * FROM procesos_catalogo ORDER BY id_proceso DESC");
  
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

<style>
  .box{
 z-index: 9999;
  opacity: 0;
  position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
   
    background:#fff;
    border-radius: 4px;
    moz-box-shadow: 0px 0px 5px #444444;
    -webkit-box-shadow: 0px 0px 5px #444444;
    box-shadow: 0px 0px 5px #444444;
}
.form-stuff{
  width: 480px;
  background:#fff;
  padding: 20px;
  border-radius: 4px;
}
.form-stuff input[type=text],.form-stuff input[type=time],.form-stuff input[type=number]{
  padding: 4px;
  width: 100%;
  border-radius: 3px;
  background: #fff;
  border: solid 1px #ccc;
  

}
.close{
  opacity: 1!important;
  width: 25px;
  height: 25px;
  background-color:#000!important;
  background-image: url(../images/ex2.png);
  background-size: contain;
  color: #fff!important;
  border-radius: 60px;
  right: -17px!important;
  top: -12px!important;
  line-height: 25px;
  text-align: center;
  position: absolute;
  border:solid 2px #fff;
  -moz-box-shadow:0px 0px 5px #444444;
      -webkit-box-shadow:0px 0px 5px #444444;
      box-shadow:0px 0px 5px #444444;
}
.form-stuff input[type=submit]{
  width: 100%;
  border: none;border-radius: 3px;
  padding: 4px;
  margin-top:18px;
  background:#05BDE3;
  color:#fff;
  font-weight: bold;
 

}
.form-stuff p{
  margin-bottom: 2px;
}
.form-stuff select{
  padding: 4px!important;


}
.in-line{
  width: 50%;
  display: inline-block;
}
.in-line input[type=text],.in-line input[type=time],.in-line input[type=number]{
  width: 95%!important;
}
.rig{
  text-align: right;
}
.rig p{
  padding-left: 10px;
  text-align: left;
}
</style>

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
<div class="full-controls">
  <div class="right-content">
  <div class="r-header">
  <div class="r-header-title">
    Lista de Procesos Disponibles
  </div>
  <div class="r-head-control">
    <input type="text" id="filterElem" name="dateFilter" placeholder="Filtrar por proceso..">
 
  </div>
    <div class="r-head-control">
      <button id="main-submit" class="btn btn-info" >Agregar Proceso</button>
    </div>
  </div>
  
    <div class="r-wraper">
   
   <?php $procesos=$mysqli->query("SELECT * FROM procesos_catalogo ORDER BY id_proceso ASC");

  ?>


<input type="hidden" name="tipo_opcion" value="">

<div class="tables">

   
<div class="datagrid option-table">
<table  class="order-table table hoverable lightable">
<thead>
<tr>
    
    
    <th style="text-align: left;">NOMBRE DEL PROCESO</th>
    <th>PRECIO POR TIROS</th>
    <th>PRECIO POR CAMBIOS</th>
    <th>CAMBIOS MINIMOS</th>
    <th style="width:180px"></th>
    <th style="width:100px"></th>
    

  </tr>

  </thead><tbody id="process-table">
  <?php while ($pr=mysqli_fetch_assoc($procesos)) { ?>
    
   <tr class="options-<?=$pr['id_proceso'] ?>">
    <td  style="text-align: left;"><?=$pr['nombre_proceso'] ?></td>
    <td><?=(!empty($pr['precio']))? '$'.$pr['precio']:'--' ?></td>
    <td><?=(!empty($pr['precio_cambio']))? '$'.$pr['precio_cambio']:'--' ?></td>
    <td><?=(!empty($pr['cambios_minimos']))? $pr['cambios_minimos']:'--' ?></td>
    <td><a href="modify_process.php?process=<?=$pr['id_proceso'] ?>"  class="edit-option">Editar Propiedades</a></td>  
    <td><a href="#" data-id="<?=$pr['id_proceso'] ?>" class="quit-option">Eliminar</a></td>
    
    <input type="hidden" name="options-<?=$pr['id_proceso'] ?>[]" value="<?=$pr['nombre_proceso'] ?>"> 
   </tr>
  
  <?php } 
if ($procesos->num_rows==0){
  echo "<tr ><td colspan='2' style='padding:20px;'>NO SE HAN AGREGADO OPCIONES PARA ESTE PROCESO</td></tr> ";
}

  ?>
  </tbody>
 
  
  
  
</table>
<br>
</div>
</div>

<script>
  $(".add-dinam").click(function () {
  var id=jQuery214(this).data('id');
    
  var empty=jQuery214(this).data('empty');
  console.log('empty '+empty);
  if (empty==true) {
    console.log('si esta empty');
    $('#table-'+id).append('<input type="hidden" name="processes['+id+']" value="'+id+'" >');
    jQuery214(this).data('empty', 'false');
  }
  
   
    var tr='<tr class="options-'+id+'"><td><input type="text"  required name="options-'+id+'[]" placeholder="Escribe una opcion.."></td><td><a href="#" class="quit-option">Eliminar</a></td></tr>'; 
    

    $('#table-'+id).append(tr);

});
  <?php if (!empty($_POST)) { ?>
   $(".quit-option").live("click", function() {
    $(this).closest("tr").remove();
});
<?php } ?>


</script>
   
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
</body>
<div class="backdrop"></div>
<div class="box">
  <div class="close" onclick="closeModal()"></div>
  <div class="form-stuff">

<form id="newTiro" method="POST" >
<p>Nombre del Proceso:</p>
  <input type="text" required  name="nombre">
<p>Area:</p>
  <select name="area">
    <option>Encuadernacion</option>
    <option>Taller</option>
  </select>  


  <div class="in-line">
     <p>Costo por Tiros:</p>
  <input type="number" required   name="costo_tiros">
  </div><div class="in-line rig">
     <p>Costo por Cambios:</p>
  <input type="number" required   name="costo_cambios">
  </div>

  <div class="in-line">
     <p>Cambios Minimos:</p>
  <input type="number"   id="in-tiro" name="cambios_minimos">
  </div><div class="in-line rig">
     <p>Tiempo de ajuste (minutos):</p>
  <input type="number"  required  name="tiempo_ajuste">
  </div>

  <div class="in-line">
     <p>Piezas por Hora:</p>
  <input type="number" required  name="piezas_hora">
  </div>
  
 
  
  <input type="submit" name="" value="GUARDAR">
  </form>
</div>
</div>
</html>

<script>
 
  $(".chosen").chosen();
  
  </script>

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/jquery-ui.min.js"></script>
<script>

 $(document).ready(function () {

  var height= $(window).height();
  $('#main-content').height(height-60);

});



jQuery214(document).on("click", ".v-li", function () {
  var type_option=jQuery214(this).data('target');
   $('.v-active').removeClass('v-active');
          jQuery214(this).addClass('v-active');

  $('.r-header-title').html(jQuery214(this).data('title'));
  $('#main-form').hide();
   $('.form-loader').show();

   $.ajax({
        url: "edit_process.php",
        type: "POST",
        data:{option:type_option},
        success: function(data){
        
        
        $('#main-form').html(data);
        
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
   $('.backdrop').animate({'opacity':'.50'}, 300, 'linear');
$('.backdrop, ').css('display', 'block');
    $('.box').show();
    $('.box').css('opacity','1');
  
});  
$('#main-form').submit(function(e){
   e.preventDefault();
   $('#main-form').hide();
   $('.form-loader').show();
  $.ajax({
        url: "save_options.php",
        type: "POST",
        data:$("#main-form").serialize(),
        success: function(data){
        
        $('#main-form').html(data);
        
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
/*
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
*/

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
  jQuery214(document).on("click", ".quit-option", function () {

    
  var id=jQuery214(this).data('id');
    $('<div></div>').appendTo('body')
                    .html('<div><h6>Estas seguro de querer borrar este proceso?</h6></div>')
                    .dialog({
                        modal: true, title: 'Eliminar proceso', zIndex: 10000, autoOpen: true,
                        width: 'auto', resizable: false,
                        buttons: {
                            Si: function () {
                                // $(obj).removeAttr('onclick');                                
                                // $(obj).parents('.Parent').remove();
                                
                                $.ajax({
                                    url: "delete_process.php",
                                    type: "POST",
                                    data:{id:id},
                                    success: function(data){
                                     
                                      console.log(data);


                                    }        
                                   }); 

                                $(this).dialog("close");

                                location.reload();
                                
                                
                            },
                            No: function () {                                                             
                            
                                $(this).dialog("close");
                            }
                        },
                        close: function (event, ui) {
                            $(this).remove();
                        }
                    });
    });
  
   

 function closeModal(){
  $('.box').hide();
    
       $('.backdrop').css('display', 'none');
 }  


 

$('#newTiro').submit(function(){
  event.preventDefault();


 
  console.log('se trato de enviar');
   $.ajax({
        url: "newProcess.php",
        type: "POST",

        data:jQuery214(this).serialize(),
        success: function(data){
          console.log(data);
          location.reload();
        
          
        }        
       });

  
});

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


jQuery214(document).on("click", "#selectAll", function (e){

    

     $('#standard-table').find(':checkbox').each(function(){
      console.log(this);
        jQuery214(this).click();

    }); 
});


    </script>

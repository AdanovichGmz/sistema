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
  $procesos=$mysqli->query("SELECT * FROM procesos_catalogo ORDER BY nombre_proceso ASC");
  
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
      <button id="main-submit" class="btn btn-info">Agregar Proceso</button>
    </div>
  </div>
  
    <div class="r-wraper">
   
   <?php $procesos=$mysqli->query("SELECT * FROM procesos_catalogo ORDER BY nombre_proceso ASC");

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
    <th style="width:100px"></th>
    <th style="width:180px"></th>

  </tr>

  </thead><tbody id="process-table">
  <?php while ($pr=mysqli_fetch_assoc($procesos)) { ?>
    
   <tr class="options-<?=$pr['id_proceso'] ?>">
    <td  style="text-align: left;"><?=$pr['nombre_proceso'] ?></td>
    <td>$<?=(!empty($pr['precio']))? $pr['precio']:'0.0' ?></td>
    <td>$<?=(!empty($pr['precio_cambio']))? $pr['precio_cambio']:'0.0' ?></td>
    <td><a href="#" class="quit-option">Eliminar</a></td>
    <td><a href="modify_process.php?process=<?=$pr['id_proceso'] ?>"  class="edit-option">Editar Propiedades</a></td>  
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
  $("#main-form").submit();
  console.log('se submiteo');
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
    
   

   


 

$('#newTiro').submit(function(){
  event.preventDefault();



var odt=$('#odt').val();
var operario=$('#operario').val();
var producto=$('#producto').val();
var fecha=$('#fecha').val();
var entorno=$('.entorno').val();
var in_ajuste=$('#in-ajuste').val();
var fin_ajuste=$('#fin-ajuste').val();
var pedido=$('#pedido').val();
var recibido=$('#recibido').val();
var buenos=$('#buenos').val();
var piezas=$('#piezas').val();
var in_tiro=$('#in-tiro').val();
var fin_tiro=$('#fin-tiro').val();
var proceso=$('#newTiro input[name="proceso"]:checked').val();
console.log('el proceso es: '+proceso);

 if (proceso == null){
    $('.error-procesos-radio').show();
}else{
  console.log('se trato de enviar');
   $.ajax({
        url: "newTiro.php",
        type: "POST",

        data:{odt:odt,operario:operario,producto:producto,fecha:fecha,entorno:entorno,in_ajuste:in_ajuste,fin_ajuste:fin_ajuste,pedido:pedido,recibido:recibido,buenos:buenos,piezas:piezas,in_tiro:in_tiro,fin_tiro:fin_tiro,proceso:proceso},
        success: function(data){
          console.log(data);
          $('.error-procesos-radio').hide();
          $('.close').click();
          $('#newTiro')[0].reset();
           $('.popup').show().fadeIn( "slow" );
          $('.popup').html('<div class="successs"><div></div><span>Exito: </span>El cambio se guardo correctamente!</div>');
        setTimeout(function() {   
                  $('.popup').fadeOut( "slow" );
                }, 2000);
        
          $.ajax({
        url: "tableModify.php",
        type: "POST",
        data:{iduser:operario,fecha:fecha},
        success: function(data){
        $('.div-tabla').html(data);
        $('#procesosradio').empty();
        }        
       });
        }        
       });
}

  
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
$(".quit-option").click( function() {

    $(this).closest("tr").remove();
});

jQuery214(document).on("click", "#selectAll", function (e){

    

     $('#standard-table').find(':checkbox').each(function(){
      console.log(this);
        jQuery214(this).click();

    }); 
});


    </script>

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



<style type="text/css">
  body {
  font: normal medium/1.4 sans-serif;
}
ul{
  margin-bottom: 0!important
}
.top-form{
  height: auto!important;
}
.lightable {
  
  background-color: #F9F9F9;
  
}
.lightable tbody tr:nth-child(odd) {
  background-color: #fff;
}
   
tbody{
  border:none!important;
}

#buscar{
  width: 300px;
  font-size: 18px;
  color:black;
  background: #383838 ;
  padding-left: 20px ;
  text-align: center;
  border-radius: 5px;
  padding: 10px;
  margin:10px; 
}
td{
  vertical-align: middle !important;
}
.conttabla2{
      width: 98%; 
    height: 80%; 
    position: absolute; 
    overflow: auto; 
    
    left: 1%;
    color: white;
}
.light-table-filter{
  width: 90%!important;
  margin-top: 25px;

}
#datepicker{
  width: 100px!important;
  margin: 5px auto;
  padding: 4.5px 3px!important;
  text-align: center;
}
.left-form2{
  width: 19%!important;
  margin: 3px;
}
.left-form{
 width: 30%!important; 
}
th{
  text-transform: uppercase;
}
.lightable th,.lightable td{
  padding:5px 10px;
  font-size: 11px;
  text-align: center;
  vertical-align: middle!important;
}
.editing{
  background: #05BDE3!important;
  color: #fff!important;
}
.editable{
  position: relative;
  
  background:#FFF8C4;
    
    color: #6A6867;
    font-weight: bolder;
}
.editable:hover{

   
    
    cursor: cell;
}
.e-tooltip{
  display: none;
  border: solid;
        border-color: #333 transparent;
        border-width: 6px 6px 0 6px;
        bottom: 20px;
        content: "";
        left: 50%;
        position: absolute;
        z-index: -99;
}
.tooltiptext {
    display: none;
    width: 210px;
    background: #fff;
    
    text-align: center;
    border-radius: 6px;
    height: 50px;
    padding: 5px 0;
    position: absolute;
    z-index: 999999;
    bottom: 100%;
    
    margin-left: -18px;
    border: 1px solid #ccc;
    border: 1px solid rgba(0,0,0,0.3);
    border-radius: 3px;
    -webkit-box-shadow: 0 5px 10px rgba(0,0,0,0.3);
    box-shadow: 0 5px 10px rgba(0,0,0,0.3);
    
}
.toolreal{
  min-height: 155px!important;
  width: 150px!important;
  bottom: -60px!important;
    left: 60px!important;

}
.toolreal p{
  width: 82%;
  margin:0 auto;
  text-align: left;
}
.toolreal::after{
  
  top: 50%!important;
    left: 15%!important;
    margin-left: -32px!important;
  -moz-transform:rotate(90deg);
    -webkit-transform:rotate(90deg);
    -o-transform:rotate(90deg);
    -ms-transform:rotate(90deg);
  -ms-filter: 
}
.toolleft{
  left: 50%;
  

}
.toolleft::after{
  
    left: 15%;
   
}
.toolright{
  right: 50%;
  

}
.toolright::after{
  
    right: 5%;
   
}
.tooltiptext input{
  color: #000;
  border-radius: 3px;
  border: 1px solid #ccc;
  font-size: 13px;
  width: 124px;
  text-align: center;
}
.tinput{
  height: 30px;
  display: inline-block;
  margin: 3px;
  line-height: 30px;
  vertical-align: top;
}
.tooltiptext::after {
    content: "";
    position: absolute;
    top: 100%;
   
    margin-left: -5px;
    border-width: 5px;
    border-style: solid;
    border-color: #fff transparent transparent transparent;
}
.toolbutton{
  width: 30px;
  height: 30px;
  display: inline-block;
  margin:5px 3px;
  border-radius: 3px;
  cursor: pointer;
}
.cancel{
  border: 1px solid #ccc;
  background-color: #fff;
background-image: url(../images/cancel.png);
background-position: center;
background-size: contain;
background-repeat: no-repeat;
 
} 
.save{
  border: 1px solid #357ebd;
  
  background-color: #428bca;
background-image: url(../images/saves.png);
background-position: center;
background-size: contain;
background-repeat: no-repeat;
} 
.cancel:hover{
  background-color: #ebebeb;
    border-color: #adadad;
}
.save:hover{
  background-color: #3276b1;
    border-color: #285e8e;
}
.without::-webkit-datetime-edit-ampm-field {
   display: none;
 }
 .cifra{
  width: 40px!important;
 }
 .toolcifras{
   width: 250px!important;
 }
 .left-form2 input{
      border-radius: 3px;
    border: 1px solid #ccc;
    padding: 4px 15px;
 }
 .popup{
    display: none;
    position: absolute;
    bottom: 50px;
    right: 30px;
    z-index: 999999;
  
 }
 .successs{
   color: #3A6028;
    width: 500px;
    background: #96DF73;
    border:solid 1px #82B968;
    z-index: 51;
    padding: 10px;
    -webkit-border-radius: 3px;
    -moz-border-radius: 3px;
    border-radius: 3px;
    position: relative;
 }
 .successs div{
  width: 24px;
  height: 24px;
  position: absolute;
  top: 9px;
  left: 10px;
  background-image: url(../images/palomita.png);
  background-size: contain;
  background-repeat: no-repeat;
 }
 .successs span{
  font-weight: bolder;
  margin-left: 35px;
 }
 .fail{
   color: #323232;
    width: 500px;
    background: #EF403D;
    border:solid 1px #B32B29;
    z-index: 51;
    padding: 10px;
    -webkit-border-radius: 3px;
    -moz-border-radius: 3px;
    border-radius: 3px;
    position: relative;
 }
 .fail div{
  width: 24px;
  height: 24px;
  position: absolute;
  top: 9px;
  left: 10px;
  background-image: url(../images/equis.png);
  background-size: contain;
  background-repeat: no-repeat;
 }
 .fail span{
  font-weight: bolder;
  margin-left: 35px;
 }
 .light-table-filter{
  background-image: url('../images/search.png');
  background-size: 32px;
  background-repeat: no-repeat;
  background-position: right;
 }
 .datagrid{
  position: relative;
 }
 .overlay{
  background: #272B34;
  opacity: 0.5;
  width: 100%;
  height: 100%;
  position: absolute;
  z-index: 999998;
  display: none;
 }
 .globe-error{
  position: absolute;
  padding: 5px;
  width: 100px;
  background: #FFF8C4;
    border: solid 1px #F7DEAE;
  font-size: 8px;
  color: #626461;
  text-align: center!important;
  border-radius: 10px;
   -webkit-box-shadow: 0 5px 10px rgba(0,0,0,0.5);
    box-shadow: 0 5px 10px rgba(0,0,0,0.5);
 }
 .globe-error:after,
.globe-error::after {
position: absolute;
top: 5px;
left: -5px;
content: '';
width: 0;
height: 0;
border-right: solid 5px rgba(255, 248, 196,1);
border-bottom: solid 5px transparent;
border-top: solid 5px transparent;
}
.tagtitle{
  background:#fff;
  position: absolute;
  top:-20px;
  left: 5px;
  border-radius: 50px;
  color: #999999;
  padding: 3px 8px;
  text-align: center;
  -webkit-box-shadow: 0 3px 5px rgba(0,0,0,0.3);
    box-shadow: 0 3px 5px rgba(0,0,0,0.3);
  z-index: 999997;
  border: solid 1px #ccc;
}
.tagtitle span{
  font-weight: bolder;
}
.delete-tiro{
  cursor: pointer;
  margin: 0 auto;
  width: 25px;
  height: 25px;
  border-radius: 3px;
  background-color: #E9573E;
  background-image: url(../images/trash2.png);
  background-size: contain;
  background-repeat: no-repeat;
}
.delete-td{
  z-index:  999997;
}
#usererror,#fechaerror{
  color: red!important;
  position: absolute;
  background: #FFF8C4;
  padding: 10px;
  border-radius: 3px;
  moz-box-shadow: 0px 0px 2px #444444;
  -webkit-box-shadow: 0px 0px 2px #444444;
  box-shadow: 0px 0px 2px #444444;
}
#filterElem{
  width: 200px!important;
    padding: 6px!important;
}
.form-stuff{
  width: 480px;
  background:#fff;
  padding: 20px;
  border-radius: 4px;
}
.ui-datepicker{
  position: absolute!important;
  z-index: 99999;
}
.newtiro-modal{
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
.reporte-semanal{
 z-index: 9999;
  opacity: 0;
  position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
   display: none;
    background:#fff;
    border-radius: 4px;
    moz-box-shadow: 0px 0px 5px #444444;
    -webkit-box-shadow: 0px 0px 5px #444444;
    box-shadow: 0px 0px 5px #444444;
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
.w-message{
  width: 300px;
  text-align: center;
  color: #999999;
  position: absolute;
  top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}
#procesosradio{
  
}
.error-procesos-radio{
  color: red;
  display: none;
}
.formradio{
  display: inline-block;
  vertical-align: top;
  margin:5px 10px 5px 0;
}
.formradio input{
  display: none;
}
.formradio label{
  padding: 8px;
  border:solid 1px #ccc;
  cursor: pointer;
  border-radius: 3px;
  color:#ccc;
  font-weight: normal;
}
.selected{
  background: #E9573E;
  color:#fff!important;
  border-color: #E9573E!important;
}
.entorno{
  display: none;
}
iframe{
  width: 100%;
  height: 540px;
  border:none;
}
.left-controls{
  display: inline-block;
  vertical-align: top;
  width: 20%;
  height: 100%;
}
.right-controls{
  display: inline-block;
  vertical-align: top;
  width: 80%;
  height: 100%;
}
.left-content{
  width: 95%;
  height: 100%;
  background: #2A3F54;
 
  border-right:solid 1px #ccc;
  
}
.right-content{
  width: 99%;
  height: 96%;
  background: #fff;
  border-radius: 4px;
  border:solid 1px #ccc;
  margin:10px 0;
  overflow: hidden;
}
.v-nav{
  width: 100%;

}
.v-nav li{
  padding: 13px;
  border-bottom: solid 1px #546371;
  cursor: pointer;
  color: #D0E2F4;

}
.v-nav li:hover{
  background: #35495D;

}
.v-active{
  background: #35495D!important;
  color: #fff!important;
}

.left-title{
  width: 100%;
  padding: 15px;
  color: #fff;
  font-weight: bold;
  text-align: center;
  margin-bottom: 20px;
}
.r-wraper{
  position: relative;
  width: 98%;
  height: 85%;
  margin: 10px auto;
  overflow-y: auto;

}
.form-loader{
  display: none;
  position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 100px;
    height: 100px;
}
.form-loader img{
  width: 100%;
}

.tables td{
  padding: 12px!important;
  font-size: 14px;
}
.tables th{
  
  font-size: 14px;
}
.r-header{
  width: 100%;
  text-align: right;
  border-bottom: solid 1px #ccc;
  /*
   -webkit-box-shadow: 0 2px 2px rgba(0,0,0,0.1);
    box-shadow: 0 2px 2px rgba(0,0,0,0.1);
*/
}
.r-head-control{
  padding: 15px;
  display: inline-block;
  vertical-align: top;

}
.option-table{
  width: 94%;
  margin: 0 auto;

}
.r-head-control select{
  margin: 0 !important;
}
.add{
    background: #FB9731;
    border-radius: 2px;
    border: solid 1px #ED6502;
    color: #fff;
    font-weight: bold;
    font-size: 12px;
    padding: 8px;
}

.quit-option{
    background: #F37A42;
    border-radius: 2px;
    border: solid 1px #D24403;
    color: #fff;
    font-weight: bold;
    font-size: 12px;
    padding: 8px;
    margin: 5px;
}
.table input{
  padding: 5px;
  border-radius: 3px;
  font-size: 14px;
  border:solid 1px #ccc;
  width: 70%;
}
.quit-option:hover{
  text-decoration: none;
  color: #fff;
}
.r-header-title{
  float: left;
  padding: 15px;
  color: #666666;
  font-size: 15px;
  margin-top: 10px;
}


@media screen and (max-width:1024px) {
  th{
    font-size: 8px;
  }
}

  </style>

  <link rel="stylesheet" type="text/css" href="../css/jquery-ui-1.7.2.custom.css" />

</head>
<body style=";">

<?php include("topbar.php");  ?>

<div class="" id="main-content">
<div class="left-controls">
  <div class="left-content">
  <div class="left-title">
    Agregar o modificar opciones de alertas y encuestas
  </div>

    <ul class="v-nav">
      <li class="v-active v-li" data-target="alerta_ajuste" data-title="Opciones de Alerta en el Ajuste por Proceso">
        Opciones en ajuste
      </li>
      <li class="v-li" data-target="alerta_tiro" data-title="Opciones de Alerta en el Tiro por Proceso">
        Opciones en tiro
      </li>
      <li class="v-li" data-target="encuesta" data-title="Opciones de Encuesta por Proceso">
        Opciones en encuesta
      </li>
    </ul>

  </div>
</div><div class="right-controls">
  <div class="right-content">
  <div class="r-header">
  <div class="r-header-title">
    Opciones de Alerta en el Ajuste por Proceso
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
   <?php include 'edit_options.php'; ?>
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
        url: "edit_options.php",
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
    $(".r-wraper .tables").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });
$(".quit-option").click( function() {

    $(this).closest("tr").remove();
});
    </script>

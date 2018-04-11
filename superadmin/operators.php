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
        function getProcess($id){
          if (!empty($id)) {
            require('../saves/conexion.php');
        $maq_query="SELECT nommaquina FROM maquina WHERE idmaquina=$id";
        
        $getmaq=mysqli_fetch_assoc($mysqli->query($maq_query));
        $maq=$getmaq['nommaquina'];
        return $maq;
          }else{
            return '';
          }
        
      }
      function getElement($id){
         require('../saves/conexion.php');
        $elem_query="SELECT nombre_elemento FROM elementos WHERE id_elemento=$id";
        
        $getelem=mysqli_fetch_assoc($mysqli->query($elem_query));
        $elem=$getelem['nombre_elemento'];
        return $elem;
      }
      function getMinutes($seconds){

      } 
  
     $maquinas="SELECT nommaquina, idmaquina FROM maquina";
    $n_maquinas=$mysqli->query($maquinas);
    $usuarios="SELECT * FROM elementos ORDER BY nombre_elemento ASC";
      $n_usuarios=$mysqli->query($usuarios);


    $elem_filter="SELECT * FROM elementos ORDER BY nombre_elemento ASC";
    $filter=$mysqli->query($elem_filter);

    $maq_filter="SELECT idmaquina,nommaquina FROM maquina";
    $filter2=$mysqli->query($maq_filter);


    $prods=$mysqli->query("SELECT * FROM elementos ORDER BY nombre_elemento ASC");

  $procs=$mysqli->query("SELECT * FROM maquina ORDER BY nommaquina ASC");
  $ops=$mysqli->query("SELECT * FROM usuarios ORDER BY logged_in ASC");
    ?>



  
<!DOCTYPE html>

<html lang="en" dir="ltr" xmlns:fb="http://ogp.me/ns/fb#">
<head>

    
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <title>REPORTE ETE</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
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
  margin-top: 20px;
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
  padding:5px 3px!important;
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
}
#filterElem{
  width: 100px!important;
    padding: 6px 2px!important;
}
.form-stuff{
  width: 48%;
  background:#fff;
  vertical-align: top;
  display: inline-block;
 
  border-radius: 4px;
 
  
}
.form-middle{
  width:4%;
  display: inline-block;
  vertical-align: top;
}
.col1{
  display: inline-block;
  
}
.col2{
  display: inline-block;
  
}
.col1 img{
  width: 80%;
  margin: 0 auto;
}
.ui-datepicker{
  position: absolute!important;
  z-index: 99999;
}
.newtiro-modal{
 z-index: 9999;
  opacity: 0;
  position: absolute;
  width: 90%;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    padding: 15px;
    background:#EFEFEF;
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
p{
  margin: 10px auto;
  color: #B3B1B1;
}
.rig p{
  padding-left: 10px;
  text-align: left;
}

.stations {
    font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
    border-collapse: collapse;
    width: 90%;
    text-align: center;
    margin: 10px auto;
}

.stations td, .stations th {
    
    padding: 10px;
}
.stations tr{
  border-top: 1px solid #ccc; 
}



.stations tr:hover {background-color: #ddd;}

.stations th {
    padding:5px;
    text-align: center;
    
    color: #ccc;
}
.edit-oper{
  cursor: pointer;
}

.station{
  width: 80%;
  margin: 10px 30px;
  border-radius: 3px;
  border:solid 1px #ccc;
  position: relative;
  text-align: center;
}
.station-title{
  width: 100%;
  text-indent: 10px;
  background: #ccc;
  color: #fff;
  height: 30px;
  line-height: 30px;
  text-align: left;
  position: relative;
}
.add-button{
  padding: 5px;
  border-radius: 60px;
  border:solid 1px #ccc;
  text-align: center;
  color: #ccc;
  margin:10px auto;
  width: 150px;
  cursor: pointer;
  font-size: 12px;

}
.oper-photo{
  width: 200px;
  height: 200px;
  margin: 0 0 10px 0;
  background-repeat: no-repeat;
  background-size: cover;
  border-radius: 3px;
  border:solid 1px #ccc;
}
.add-button:hover{
  background:#ccc;
  color:#fff;
}
#station-controls{
  width: 20%;
  height: 400px;
  border-radius: 4px;
  border:solid 1px #ccc;
  position: fixed;
  right: 50px;
  top: 75px; 
}
#station-controls table{
  width: 100%;
  text-align: center;
}
#station-controls td{
  padding: 8px;
}
#estaciones{
  position: relative;
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



 <div class="top-form">

  <div class="left-form2">
 
   <p style="margin-bottom: 2px!important;">Elige operario y fecha</p>
   <input type="hidden" name="activef" value="ok">
   <div class=""><select id="filterElem" name="dateFilter">
   <option disabled="true" selected="true">Operarios</option>
 <option value="11">Alfonso</option>
 <option value="16">Armando</option>
  <option value="17">Christian Acevedo</option>
  <option value="13">Christian</option>
  <option value="8">Eduardo</option>
  <option value="2" style="display: none;">Adan</option>
  
<option value="15">Ramon</option>
  

   </select>
<p id="usererror" style="display: none;">Por favor elige un usuario</p>
 <input id="datepicker" class="" placeholder="Fecha.." required="true" value="" name="id" />
  <p id="fechaerror" style="display: none;">Por favor elige una fecha</p>
   </div>
   
 
 </div><div  class="left-form2"><button style="margin-top: 25px;" type="button" id="newstandar"  class="btn btn-primary">TRAER INFORMACION</button></div><div class="left-form2"><div><input type="search" class="light-table-filter" data-table="order-table" placeholder="Filtrar"></div></div><div class="left-form2">
 
   <button style="margin-top: 25px;" type="button" id="addtiro"  class="btn btn-info new-modal disabled">AGREGAR TIRO</button>
   
  
 
 </div><div  class="left-form2"><form action="../pdfrepajustemaquina/createPdf.php" method="post" target="_blank"><input type="hidden" required id="date" name="id"><input type="hidden" id="user" required name="iduser"><button style="margin-top: 25px;" type="submit" id="getPdf"  class="btn btn-success disabled">GENERAR PDF</button></form></div>

</div>
   
<div class="div-tabla">
<?php include("tableOperators.php");  ?>
</div>
<div class="newtiro-modal" id="operator-info">
<div class="close"></div>
  <div class="form-stuff">

<form id="newTiro" method="POST" >
<p>Nombre:</p>
  <input type="text" required id="odt" name="odt">
  <div style="">
  
   <input type="hidden" id="operario" readonly name="odt">

  <p>Areas:</p>
  
  <select class="chosen" required id="producto" name="producto">
  <option disabled selected>Elije un producto</option>
    <?php while ($row4=mysqli_fetch_assoc($prods)) { ?>
<option value="<?=$row4['id_elemento'] ?>"><?=$row4['nombre_elemento'] ?></option>
    <?php } ?>
  </select>
  </div>
<table class="stations">
<tr>
  <th colspan="2">Nombre estacion</th>
</tr>
  <tr>
    <th>Procesos</th>
    
    <th></th>
  </tr>
  <tr>
    <td>LetterPress</td>
    
    <td><a href="#">Quitar</a></td>
  </tr>
  <tr>
    <td>Suaje</td>
    
    <td><a href="#">Quitar</a></td>
  </tr>
</table>
<p>Fecha:</p>
  <input readonly type="text" id="fecha" name="fecha">
<div class="in-line entorno">
     <label for="mesa">Mesa:</label>
  <input type="radio" id="mesa" value="mesa"  class="entorno" name="entorno">
  </div><div class="in-line rig entorno">
     
  <input type="radio" id="maqui" checked value="maquina" class="entorno" name="entorno">
  <label for="maqui">Maquina:</label>
  </div>

  <div class="in-line">
     <p>Inicio ajuste:</p>
  <input type="time" required step="2" id="in-ajuste" name="in-ajuste">
  </div><div class="in-line rig">
     <p>Fin ajuste:</p>
  <input type="time" required step="2" id="fin-ajuste" name="fin-ajuste">
  </div>

  <div class="in-line">
     <p>Pedido:</p>
  <input type="number" required id="pedido" name="pedido">
  </div><div class="in-line rig">
     <p>Recibidos:</p>
  <input type="number" required id="recibido" name="recibido">
  </div>
  
<div class="in-line">
     <p>Buenos:</p>
  <input type="number" required id="buenos" name="buenos">
  </div><div class="in-line rig">
     <p>Piezas de ajuste:</p>
  <input type="number" required id="piezas" name="piezas">
  </div>



  

  <div class="in-line">
     <p>Inicio tiraje:</p>
  <input type="time" step="2" required id="in-tiro" name="in-tiro">
  </div><div class="in-line rig">
     <p>Fin tiraje:</p>
  <input type="time" step="2" required id="fin-tiro" name="fin-tiro">
  </div>
 
  
  <input type="submit" name="" value="GUARDAR">
  </form>
</div>
</div>

<div class="backdrop"></div>
<div class="box"><div class="close"></div>



  </div>
</div>







 
  
  


   <div class="box2"><div class="close2"></div>
  <div class="modal-form">
  <p id="orderdelete" style="text-align: center; font-weight: bold;"></p>
    <form id="delete_form" method="post" onsubmit="deleteRow();">
    <input type="hidden" value="delete" name="form" >
   <input type="hidden" id="dfi" name="idstandard" >
    
    
    <input type="submit" value="BORRAR">
    <input type="button" value="CANCELAR" class="close2">
  </form>
  </div>
  </div>

  <div class="box3"><div class="close"></div>
  <div class="modal-form">
  <p id="orderupdate" style="text-align: center; font-weight: bold;"></p>
    <form id="update_form2" method="post" onsubmit="updateRow2();">
    <input type="hidden" value="edit"  name="form" >
   <input type="hidden" id="fi2" name="idstandard" >
    <input type="text" id="proces" name="nommaquina" readonly="true">
    
    <input type="text" id="fpu" name="ajuste" placeholder="Tiempo Estandar de Ajuste">
    <input type="text" id="fou" name="piezas" placeholder="Piezas por Minuto">
    <input type="text" id="elem" name="elemento" readonly="true">
  
    <input type="submit" value="Comer">
  </form>
  </div>



  </div>
  <div class="popup"></div>
</body>
</html>

<script>
  

  $(".chosen").chosen();
  
  </script>

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/jquery-ui.min.js"></script>
<script>
$('.new-modal').click(function(){
   $('.backdrop').animate({'opacity':'.50'}, 300, 'linear');
$('.backdrop, ').css('display', 'block');
    $('.newtiro-modal').show();
    $('.newtiro-modal').css('opacity','1');
    
   });
$('.close').click(function(){
    $('.newtiro-modal').hide();
       $('.backdrop').css('display', 'none');
    
   });
$('.backdrop').click(function(){
  $('.newtiro-modal').hide();
       $('.backdrop').css('display', 'none');
});
$(document).ready(function(){
      
 });
     $('.newtiro-modal').hide();
     function showEdit(editableObj) {
      $('.overlay').animate({'opacity':'0.50'}, 300, 'linear');
      $('.overlay').css('display', 'block');
      $('.tooltiptext').hide();
     $(editableObj).find( ".tooltiptext" ).show();
    } 


  $(".edit-oper").click(function () {
    var iduser=$(this).attr('data-oper');

    $.ajax({
        url: "operatorInfo.php",
        type: "POST",
        data:{id_user:iduser},
        success: function(data){
        $('#operator-info').html(data);
        }        
       });
});  

 
 
 
$("#newstandar").click(function () {
  var user=$('#filterElem').val();
  var fecha=$('#datepicker').val();
  var go;
  $('#fecha').val(fecha);
  $('#operario').val(user);
  console.log('user '+user);
  if (user=='Operarios') {
    go=false;

    $('#usererror').show();
     setTimeout(function() {   
                  $('#usererror').hide();
                }, 2000);
   
  }else{go=true;}
  if (fecha=='') {
    go2=false;
   $('#fechaerror').show();
   setTimeout(function() {   
                  $('#fechaerror').hide();
                }, 2000);
  }else{go2=true;}

  if (go && go2) {

    $('#date').val(fecha);
    $('#user').val(user);
    document.getElementById('getPdf').style.pointerEvents = 'auto';
    $('#getPdf').removeClass('disabled'); 
    document.getElementById('addtiro').style.pointerEvents = 'auto';
    $('#addtiro').removeClass('disabled');
 console.log('si paso '+user);
  $.ajax({
        url: "tableModify.php",
        type: "POST",
        data:{iduser:user,fecha:fecha},
        success: function(data){
        $('.div-tabla').html(data);
        }        
       });
  }
    
  
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
    function saveToDatabase(concept,column,id) {
       $('.overlay').hide();
      var user=$('#filterElem').val();
    var fecha=$('#datepicker').val();
     globeerror=false;
     count=0;
     $('.globe-error').remove();
      //$(editableObj).removeClass('editing');
      if (concept=='inicio') {
        var value=($('#time-'+id).val().length==5)? $('#time-'+id).val()+':00':$('#time-'+id).val();
        var datas='column='+column+'&editval='+value+'&id='+id+'&fecha='+fecha+'&operador='+user+'&concepto='+concept;
      }
      else if (concept=='fin') {
        var value=($('#tfin-'+id).val().length==5)? $('#tfin-'+id).val()+':00':$('#tfin-'+id).val();

        var datas='column='+column+'&editval='+value+'&id='+id+'&fecha='+fecha+'&operador='+user+'&concepto='+concept;
      }else if (concept=='iniciot') {
         var value=($('#timet-'+id).val().length==5)? $('#timet-'+id).val()+':00':$('#timet-'+id).val();
        var datas='column='+column+'&editval='+value+'&id='+id+'&std_change=true'+'&fecha='+fecha+'&operador='+user+'&concepto='+concept;
      }else if (concept=='fint') {
         var value=($('#tfint-'+id).val().length==5)? $('#tfint-'+id).val()+':00':$('#tfint-'+id).val();
        var datas='column='+column+'&editval='+value+'&id='+id+'&std_change=true'+'&fecha='+fecha+'&operador='+user+'&concepto='+concept;
      }else if(concept=='real'){
        var merma=$('#merm-'+id).val();
        var entregados=$('#buen-'+id).val();
        var datas='prod_real=true&merma='+merma+'&entregados='+entregados+'&id='+id+'&fecha='+fecha+'&operador='+user+'&concepto='+concept;
      }else if(concept=='odt'){
        var value=$('#odt-'+id).val();
         var datas='column='+column+'&editval='+value+'&id='+id+'&fecha='+fecha+'&operador='+user+'&concepto='+concept;
      }else if(concept=='defectos'){
        var value=$('#def-'+id).val();
         var datas='column='+column+'&editval='+value+'&id='+id+'&fecha='+fecha+'&operador='+user+'&concepto='+concept;
      }else if(concept=='time'){
        var hour=$('#hour-'+id).val();
        var min=$('#min-'+id).val();
        var sec=$('#sec-'+id).val();
        var value=hour+':'+min+':'+sec;
        var datas='column='+column+'&editval='+value+'&id='+id+'&fecha='+fecha+'&operador='+user+'&concepto='+concept;
      }else if(concept=='ttime'){
        var hour=$('#thour-'+id).val();
        var min=$('#tmin-'+id).val();
        var sec=$('#tsec-'+id).val();
        var value=hour+':'+min+':'+sec;
        var datas='column='+column+'&editval='+value+'&id='+id+'&fecha='+fecha+'&operador='+user+'&concepto='+concept;
      }else{
        var value=$('#tfin-'+id).val();
        var datas='column='+column+'&editval='+value+'&id='+id+'&fecha='+fecha+'&operador='+user+'&concepto='+concept;
      }
     
      
      console.log('valor: '+value);
      $.ajax({
        url: "editEte.php",
        type: "POST",
        data:datas,
        success: function(data){
          $('.popup').show().fadeIn( "slow" );
          $('.popup').html(data);
        setTimeout(function() {   
                  $('.popup').fadeOut( "slow" );
                }, 2000);
        
         $.ajax({
        url: "tableModify.php",
        type: "POST",
        data:{iduser:user,fecha:fecha},
        success: function(data){
        $('.div-tabla').html(data);
        }        
       });


        }        
       });
    }
    function editTime(editableObj,column,id) {
      $(editableObj).css("background","#fff");
      $.ajax({
        url: "saveedit.php",
        type: "POST",
        data:'column='+column+'&editval='+editableObj.innerHTML+'&id='+id,
        success: function(data){
          $(editableObj).css("background","#FDFDFD");
        }        
       });
    }

   (function($) {  

            $(function(){
                  $( "#datepicker" ).datepicker({ dateFormat: 'dd-mm-yy' });
                  $( "#datepicker2" ).datepicker({ dateFormat: 'dd-mm-yy' });
            })
  })(jQuery);


   (function($){
  $.datepicker.regional['es'] = {
    closeText: 'Cerrar',
    prevText: '&#x3c;Ant',
    nextText: 'Sig&#x3e;',
    currentText: 'Hoy',
    monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio',
    'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
    monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun',
    'Jul','Ago','Sep','Oct','Nov','Dic'],
    dayNames: ['Domingo','Lunes','Martes','Mi&eacute;rcoles','Jueves','Viernes','S&aacute;bado'],
    dayNamesShort: ['Dom','Lun','Mar','Mi&eacute;','Juv','Vie','S&aacute;b'],
    dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','S&aacute;'],
    weekHeader: 'Sm',
    dateFormat: 'dd/mm/yy',
    firstDay: 1,
    isRTL: false,
    showMonthAfterYear: false,
    yearSuffix: ''};
  $.datepicker.setDefaults($.datepicker.regional['es']);
})(jQuery);



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


  console.log('se trato de enviar');
   $.ajax({
        url: "newTiro.php",
        type: "POST",

        data:{odt:odt,operario:operario,producto:producto,fecha:fecha,entorno:entorno,in_ajuste:in_ajuste,fin_ajuste:fin_ajuste,pedido:pedido,recibido:recibido,buenos:buenos,piezas:piezas,in_tiro:in_tiro,fin_tiro:fin_tiro},
        success: function(data){
          $('.close').click();
          $('#newTiro')[0].reset();
          console.log(data);
          $.ajax({
        url: "tableModify.php",
        type: "POST",
        data:{iduser:operario,fecha:fecha},
        success: function(data){
        $('.div-tabla').html(data);
        }        
       });
        }        
       });
});
    </script>

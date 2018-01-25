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


    <link href="../css/estilosadmin.css" rel="stylesheet" />

   
      <link rel="stylesheet" href="../fonts/style.css">
  <script src="http://code.jquery.com/jquery-latest.js"></script>
  <script src="../js/main.js"></script>




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
  width: 90%!important;
  margin: 0 auto;
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
@media screen and (max-width:1024px) {
  th{
    font-size: 8px;
  }
}

  </style>
  <link rel="stylesheet" type="text/css" href="../css/jquery-ui-1.7.2.custom.css" />
   <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/jquery-ui.min.js"></script>
  <script type="text/javascript">
   jQuery(function($){
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
});    

        $(document).ready(function() {
           $("#datepicker").datepicker();
        });
   </script>

</head>
<body style=";">

<?php include("topbar.php");  ?>



 <div class="top-form">

  <div class="left-form2">
 
   <p style="margin-bottom: 2px!important;">Elige el operario</p>
   <input type="hidden" name="activef" value="ok">
   <div class=""><select id="filterElem" name="dateFilter">
   <option disabled="true" selected="true">Operarios</option>
 
  <option value="14">Arturo</option>
  <option value="16">Armando</option>
  <option value="8">Eduardo</option>
  <option value="2">Adan</option>
  <option value="11">Alfonso</option>
  <option value="13">Christian</option>

   </select>
<p id="usererror" style="display: none;">Por favor elige un usuario</p>
   </div>
   
 
 </div><div class="left-form2">
 
   <p style="margin-bottom: 2px!important;">Fecha</p>
   
   <input id="datepicker" class="" required="true" value="" name="id" />
  <p id="fechaerror" style="display: none;">Por favor elige una fecha</p>
 
 </div><div  class="left-form2"><button style="margin-top: 25px;" type="button" id="newstandar"  class="btn btn-primary">TRAER INFORMACION</button></div><div class="left-form2"><div><input type="search" class="light-table-filter" data-table="order-table" placeholder="Filtrar"></div></div><div  class="left-form2"><form action="../pdfrepajustemaquina/createPdf.php" method="post" target="_blank"><input type="hidden" required id="date" name="id"><input type="hidden" id="user" required name="iduser"><button style="margin-top: 25px;" type="submit" id="getPdf"  class="btn btn-success disabled">GENERAR PDF</button></form></div>

</div>
   
<div class="div-tabla">

</div>

</div>







 
  <div class="backdrop"></div>
  <div class="box"><div class="close">x</div>
  <div class="modal-form">
  <p id="order" style="text-align: center; font-weight: bold;"></p>
    <form id="update_form" method="post" onsubmit="updateRow();">
    <input type="hidden" value="edit" id="form" name="form" >
   <input type="hidden" id="fi" name="idstandard" >
    
    
    <select  name="nommaquina" id="fm">
      <option disabled="true" selected="true" value="none">Proceso</option>
     <?php while($rowf=mysqli_fetch_assoc($n_maquinas)){ ?>
     <option value="<?=$rowf['idmaquina']?>"><?php echo $rowf['nommaquina']; ?></option>
     <?php } ?>
   </select>
    </select>
    <input type="text" id="fp" name="ajuste" placeholder="Tiempo Estandar de Ajuste">
    <input type="text" id="fo" name="piezas" placeholder="Piezas por Minuto">
    <select  name="elemento" id="fu">
      <option disabled="true" >Elemento</option>
     
   </select>
  
    <input type="submit" value="Guardar">
  </form>
  </div>



  </div>


   <div class="box2"><div class="close2">x</div>
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

  <div class="box3"><div class="close">x</div>
  <div class="modal-form">
  <p id="orderupdate" style="text-align: center; font-weight: bold;"></p>
    <form id="update_form2" method="post" onsubmit="updateRow2();">
    <input type="hidden" value="edit"  name="form" >
   <input type="hidden" id="fi2" name="idstandard" >
    <input type="text" id="proces" name="nommaquina" readonly="true">
    
    <input type="text" id="fpu" name="ajuste" placeholder="Tiempo Estandar de Ajuste">
    <input type="text" id="fou" name="piezas" placeholder="Piezas por Minuto">
    <input type="text" id="elem" name="elemento" readonly="true">
  
    <input type="submit" value="Guardar">
  </form>
  </div>



  </div>
  <div class="popup"></div>
</body>
</html>
<script>
$(document).ready(function(){
      document.getElementById('getPdf').style.pointerEvents = 'none';
 });
    
     function showEdit(editableObj) {
      $('.overlay').animate({'opacity':'0.50'}, 300, 'linear');
      $('.overlay').css('display', 'block');
      $('.tooltiptext').hide();
     $(editableObj).find( ".tooltiptext" ).show();
    } 

$("#newstandar").click(function () {
  var user=$('#filterElem').val();
  var fecha=$('#datepicker').val();
  var go;
  console.log('user '+user);
  if (user=='Usuarios') {
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

  $( function() {
    $( "#datepicker" ).datepicker("option",'dateFormat', 'dd-mm-yy' );
  } );
    </script>
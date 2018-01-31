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
 


  ?>

 

  
<!DOCTYPE html>

<html lang="en" dir="ltr" xmlns:fb="http://ogp.me/ns/fb#">
<head>

    
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <title>Reporte Ordenes</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous" />
    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous" />
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
  


    <link href="../css/estilosadmin.css" rel="stylesheet" />

   
  <link rel="stylesheet" href="../fonts/style.css">
  <script src="http://code.jquery.com/jquery-latest.js"></script>
  <script src="../js/main.js"></script>


  
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
  <style>
  @font-face {
  font-family: 'monse';
  src:  url('../fonts/Montserrat-Regular.otf');
 
}
@font-face {
  font-family: 'monse-black';
  src:  url('../fonts/Montserrat-Black.otf');
 
}
@font-face {
  font-family: 'monse-bold';
  src:  url('../fonts/Montserrat-Bold.otf');
 
}
@font-face {
  font-family: 'monse-medium';
  src:  url('../fonts/Montserrat-Medium.otf');
 
}
    @font-face {
  font-family: 'aharon';
  src:  url('../fonts/ahronbd.ttf');
 
}
@font-face {
  font-family: 'monlight';
  src:  url('../fonts/MontseLight.otf');
 
}
body{
  position: relative!important;
}
p{
  margin: 2px auto!important;
  color: #ccc;
}
.form-stuff{
  width: 500px;
  background:#fff;
  padding: 20px;
  border:solid 1px #ccc;
  border-radius: 4px;
  margin: 0 auto;
}
.form-stuff input[type=text]{
  padding: 4px;
  width: 100%;
  border-radius: 3px;
  background: #fff;
  border: solid 1px #ccc;

}
.in-line{
  width: 50%;
  display: inline-block;
}
.in-line input{
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
</head>
<body style="">

<?php include("topbar.php");  ?>

<div class="form-stuff">
<p>ODT:</p>
  <input type="text" name="">
  
  <div class="in-line">
     <p>Proceso:</p>
  <input type="text" name="">
  </div><div class="in-line rig">
     <p>Operario:</p>
  <input type="text" name="">
  </div>

  <p>Producto:</p>
  <input type="text" name="">
<p>Fecha:</p>
  <input type="text" name="">


  <div class="in-line">
     <p>Inicio ajuste:</p>
  <input type="text" name="">
  </div><div class="in-line rig">
     <p>Fin ajuste:</p>
  <input type="text" name="">
  </div>

  <div class="in-line">
     <p>Pedido:</p>
  <input type="text" name="">
  </div><div class="in-line rig">
     <p>Recibidos:</p>
  <input type="text" name="">
  </div>
  
<div class="in-line">
     <p>Buenos:</p>
  <input type="text" name="">
  </div><div class="in-line rig">
     <p>Piezas de ajuste:</p>
  <input type="text" name="">
  </div>



  

  <div class="in-line">
     <p>Inicio tiraje:</p>
  <input type="text" name="">
  </div><div class="in-line rig">
     <p>Fin tiraje:</p>
  <input type="text" name="">
  </div>
 
  
  <input type="submit" name="" value="GUARDAR">
</div>

</body>
</html>
<script>
  $( function() {
    $( "#datepicker" ).datepicker("option",'dateFormat', 'dd-mm-yy' );
    
  } );
  </script>
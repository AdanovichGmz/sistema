 <?php
 require('../saves/conexion.php');
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
 $prods=$mysqli->query("SELECT * FROM elementos ORDER BY nombre_elemento ASC");

  $procs=$mysqli->query("SELECT * FROM maquina ORDER BY nommaquina ASC");
  $ops=$mysqli->query("SELECT * FROM login ORDER BY logged_in ASC");
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
<link rel="stylesheet" href="../css/choosen.css">

  
  <link rel="stylesheet" type="text/css" href="../css/jquery-ui-1.7.2.custom.css" />
  

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/js/select2.full.min.js"></script>

<script src="../js/choosen.js"></script>
  <script type="text/javascript">
    

      
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
  color: #444;
}
.form-stuff{
  width: 500px;
  background:#fff;
  padding: 20px;
  border:solid 1px #ccc;
  border-radius: 4px;
  margin: 0 auto;
}
.form-stuff input[type=text],input[type=time],input[type=number]{
  padding: 4px;
  width: 100%;
  border-radius: 3px;
  background: #fff;
  border: solid 1px #ccc;

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
.form-stuff select{
  padding: 4px!important;


}
.in-line{
  width: 50%;
  display: inline-block;
}
.in-line input[type=text],input[type=time],input[type=number]{
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
<form action="newTiro" method="POST">
<p>ODT:</p>
  <input type="text" name="odt">
  
  <p>Operario:</p>
   <select class="chosen" name="operario" id="operario">
  <option disabled selected>Elije un operario</option>
    <?php while ($row3=mysqli_fetch_assoc($ops)) { ?>
<option value="<?=$row3['id'] ?>"><?=$row3['logged_in'] ?></option>
    <?php } ?>
  </select>

  <p>Producto:</p>
  
  <select class="chosen" name="producto">
  <option disabled selected>Elije un producto</option>
    <?php while ($row=mysqli_fetch_assoc($prods)) { ?>
<option value="<?=$row['id_elemento'] ?>"><?=$row['nombre_elemento'] ?></option>
    <?php } ?>
  </select>
<p>Fecha:</p>
  <input id="datepicker" type="text" name="fecha">
<div class="in-line entorno">
     <label for="mesa">Mesa:</label>
  <input type="radio" id="mesa" value="mesa" name="entorno">
  </div><div class="in-line rig entorno">
     
  <input type="radio" id="maqui" value="maquina" name="entorno">
  <label for="maqui">Maquina:</label>
  </div>

  <div class="in-line">
     <p>Inicio ajuste:</p>
  <input type="time" step="2" name="in-ajuste">
  </div><div class="in-line rig">
     <p>Fin ajuste:</p>
  <input type="time" step="2" name="fin-ajuste">
  </div>

  <div class="in-line">
     <p>Pedido:</p>
  <input type="number" name="pedido">
  </div><div class="in-line rig">
     <p>Recibidos:</p>
  <input type="number" name="recibido">
  </div>
  
<div class="in-line">
     <p>Buenos:</p>
  <input type="number" name="buenos">
  </div><div class="in-line rig">
     <p>Piezas de ajuste:</p>
  <input type="number" name="piezas">
  </div>



  

  <div class="in-line">
     <p>Inicio tiraje:</p>
  <input type="time" step="2" name="in-tiro">
  </div><div class="in-line rig">
     <p>Fin tiraje:</p>
  <input type="time" step="2" name="fin-tiro">
  </div>
 
  
  <input type="submit" name="" value="GUARDAR">
  </form>
</div>

</body>
</html>
<script>
  

  $(".chosen").chosen();
  
  </script>
  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/jquery-ui.min.js"></script>
  
<script>
  
  (function($) {  

            $(function(){
                  $( "#datepicker" ).datepicker({ dateFormat: 'dd-mm-yy' });
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



 

</script>
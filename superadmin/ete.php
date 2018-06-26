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
  

$getUsers=$mysqli->query('SELECT * FROM usuarios WHERE app_active=1');
$getIds=$mysqli->query('SELECT id FROM usuarios WHERE app_active=1');

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
.genpdf{
  padding: 10px;
  margin: 15px;
  font-size: 15px;
  border-radius: 3px;

}

    .prod-container{
      width: 100%;
      text-align: center;
    }
    .personal{
      width: 31%;
    background-color: #fff;
    height: 270px;
    display: inline-block;
    border-radius: 5px;
    margin: 10px;
    vertical-align: top;
    position: relative;
    font-family: "monse";
        border: 1px solid #E6E8E7;

    }
    .personal:hover{
     box-shadow: 0px 0px 8px 0px rgba(0, 0, 0, 0.78);
-moz-box-shadow: 0px 0px 8px 0px rgba(0, 0, 0, 0.78);
-webkit-box-shadow: 0px 0px 8px 0px rgba(0, 0, 0, 0.78);
    }
    .person-photo{
      width: 90px;
      height: 90px;
      background: #383838;
      position: absolute;
      top: 10px;
      left: 10px;
      background-size: contain!important;
      border: 1px solid #E6E8E7;
    }
    .ete-photo{
      height: 110px;
      width: 100%;
     background: #E0DDDD
      position: relative;
      border-top-left-radius:5px;
      border-top-right-radius:5px;
     

    }
    .ete-num{
      width: 250px;
      height: 90px;
      position: absolute;
      top: 10px;
      right: 18px;
      line-height: 90px;
      font-size: 30px;
      color: #545A8E;
      text-align: right;
    }
    .ete-stat{
      width: 100%;
      position: relative;
      height: 160px;
      font-family: "monlight";
      
    }
    .ete-stat table{
      width: 100%;
      color: #00927B;
      font-size: 24px;
      

    }
    .ete-stat thead{
     font-size: 18px;
     color: #979999;
      
    }
    .ete-stat td{
      width: 33%;
    }
    .trh{
      height: 50px;
      background:#F9F9F9;
      border-bottom: 1px solid #E6E8E7;
      border-top: 1px solid #E6E8E7;  
    }
    .middletd{
      border-right: 1px dashed #E6E8E7;
      border-left: 1px dashed #E6E8E7;
    }
    .trb{
      line-height: 24px;
    }
    .nombre_elemento {
    font-size: 13px;
    width: 100%;
}
  </style>
</head>
<body style="">

<?php include("topbar.php");  ?>

<div class="prod-container">
<?php while ($row=mysqli_fetch_assoc($getUsers)) {
  ?>

<div class="personal">
    <div class="ete-photo"><div class="person-photo" style="background:url(../<?=$row['foto'] ?>)"></div>
    <div class="ete-num"><?=$row['logged_in'] ?>&nbsp</div>
    </div>
    <div class="ete-stat">
    <form method="post" action="../pdfrepajustemaquina/createReport.php" target="_blank" >
      <table>
      <thead>
      <tr class="trh">
        <td width="50%">Fecha: </td>
        <td width="50%" class=""><input id="<?=$row['id'] ?>" required="true" value="" style="width:90%;" name="id" /></td>
        <input type="hidden" name="iduser" value="<?=$row['id'] ?>" >
        </tr></thead>
        <tbody>
        <tr style="font-size: 10px; text-align:center">
        <td><input type="button" data-user="<?=$row['id'] ?>" class="ex-rep genpdf btn btn-success" value="Reporte Excel"> </td>
        <td ><input type="submit" class="genpdf btn btn-primary" value="Reporte PDF"> </td>
        </tr>
        
        </tbody>
        </form>
      </table>
    </div>
    
  </div>


<?php } ?>

 
</div>
</body>
</html>
<script>

 $(document).ready(function() {
          
<?php while ($row2=mysqli_fetch_assoc($getIds)) {
          ?>

    $( "#<?=$row2['id'] ?>" ).datepicker({ dateFormat: 'dd-mm-yy' });

    <?php } ?>


        });
 
      

$( ".ex-rep" ).click(function() {
  var user=this.getAttribute("data-user");
   var mach=this.getAttribute("data-machine");
   var fecha=$('#'+mach).val();
 if (fecha=='') {alert('No has seleccionado una fecha')}else{
  
 }
  $(location).attr('href', '../pdfrepajustemaquina/createExcel.php?fecha='+fecha+'&user='+user).attr('target','_blank');
  });

  </script>
  <?php 
  date_default_timezone_set("America/Mexico_City");
  session_start(); ?>   
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
  
  <style>
  @font-face{font-family:'monse';src:url(../fonts/Montserrat-Regular.otf)}@font-face{font-family/:monse-black;src:url(fonts/Montserrat-Black.otf)}@font-face{font-family:'monse-bold';src:url(../fonts/Montserrat-Bold.otf)}@font-face{font-family:'monse-medium';src:url(../fonts/Montserrat-Medium.otf)}@font-face{font-family:'aharon';src:url(../fonts/ahronbd.ttf)}@font-face{font-family:'monlight';src:url(../fonts/MontseLight.otf)}.machinename{width:100%;height:30px;position:relative;color:#3F4553;line-height:30px;font-weight:400;font-size:20px;text-transform:uppercase}.prod-container{width:100%;text-align:center}.personal{width:31%;background-color:#fff;display:inline-block;border-radius:5px;margin:10px;vertical-align:top;position:relative;font-family:"monse"}.personal:hover{box-shadow:0 0 8px 0 rgba(0,0,0,0.78);-moz-box-shadow:0 0 8px 0 rgba(0,0,0,0.78);-webkit-box-shadow:0 0 8px 0 rgba(0,0,0,0.78)}.person-photo{width:90px;height:90px;position:absolute;top:10px;left:10px;background-size:contain!important;border:1px solid #E6E8E7}.ete-photo{height:110px;width:100%;position:relative;border-top-left-radius:3px;border-top-right-radius:3px;z-index:100;background:#F9F9F9}.ete-num{width:250px;height:90px;position:absolute;top:10px;right:18px;line-height:90px;font-size:70px;color:#272B34;text-align:right}.ete-stat{width:100%;position:relative;height:160px;font-family:"monlight"}.ete-stat table{width:100%;color:#fff;font-size:29px}.ete-stat thead{font-size:25px;color:#272B34}.ete-stat td{width:33%}.trh{height:50px;background:#F9F9F9;font-weight:bolder}.middletd{border-right:1px dashed #E6E8E7;border-left:1px dashed #E6E8E7}.trb{line-height:24px}.nombre_elemento{font-size:13px;width:100%}.prod-container{background:#E5E9EC!important}.disabled{opacity:.4}#cuerpito{background:#E5E9EC!important}@media screen and (max-width: 1025px){.ete-stat table{font-size:10px}.ete-stat thead{font-size:18px}}@media screen and (max-width: 768px){.personal{width:45%}}.fade{width:100%;height:100%;background-color:#E9573E;transition:opacity .6s ease-in-out;position:absolute;border-radius:5px}@media screen and (max-width: 412px){.personal{width:90%}}.ajuste{background:#31A1CF!important}.ajuste div{color:#fff!important}.alerta{background:#FFCF6B!important}.tiro{background:#67BE4B!important}.tiro div{color:#fff!important}.santa{background-image:url(images/san.png);background-repeat:no-repeat;background-size:contain;background-position:center;position:absolute;display:none;width:60px;height:60px;top:-10px;left:35px}
  .carousel-page{
    width: 100%;
    height: 100%;
    
  }
  .skdslider ul.slides li
{
   display: none;
}
  </style>
   <script type="text/javascript" src="https://www.google.com/jsapi"></script> 
  <script >
    google.load("visualization", "1", {packages:["corechart"]});
    google.setOnLoadCallback(drawChart);
    function drawChart(iduser,dispon,desemp,calidad) {
      //antigua grafica
      console.log('el id es: '+iduser);
      var data = google.visualization.arrayToDataTable([
          ['valor', 'porcentaje'],['DISPONIBILIDAD',dispon],['DESEMPEÑO',desemp],['CALIDAD',calidad],]);
      
      
        var options = { // api de google chats, son estilos css puestos desde js
            
            width: "100%", 
            height: "100%",
            chartArea: {left: 25, top: 10, width: "100%", height: "80%"},
            legend: 'none',
            enableInteractivity: true,                                               
            fontSize: 12,
            hAxis: {
                    textStyle: {
                      color: '#00927B'
                    }
                  },
            vAxis: {
                textStyle: {
                      color: '#00927B'
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
      var chart = new google.visualization.ColumnChart(iduser);
      chart.draw(data,options);

      
      
  }
  </script>
</head>
<body id="cuerpito">
<?php include 'estatus_content.php'; ?>
</body>
</html>
<script>
<?php 
require('../saves/conexion.php');
$today=date("d-m-Y");
  $turn_on=$mysqli->query("SELECT * FROM usuarios_estaciones");
if (!$turn_on) {
  printf($mysqli->error);
}
?>
$(document).ready(function(){
  var one = $(window).height();
  $('body').height(one);
  $('.personal').height((one/2)-25);
var grafics2=((wind/2)-25)-140;
$('.ete-stat').height(grafics2);
  
 alerttime();
 /*
setInterval(function() {
            
              //$('#cuerpito').hide().fadeIn('slow'); 
                  $('#cuerpito').load('estatus_content.php', function(resp, status, xhr) {<?php while ( $turn_row=mysqli_fetch_assoc($turn_on)) {
 echo "draw".$turn_row['id_estacion']."Chart();";
} ?>});
                          //$('#cuerpito').show().fadeIn(3000);
                 console.log('hora de ajax');          
                }, 60000); */



setInterval(function() {
  console.log('hora de recargar');
  location.reload();
}, 50000000);

                });
   
      function alerttime(){
  
  animacion = function(){
  
  $('.outtime').toggleClass('fade');
 
}
setInterval(animacion, 550);

}  

var myIndex = 0;
carousel();

function carousel() {
    var i;
    var x = document.getElementsByClassName("carousel-page");

    for (i = 0; i < x.length; i++){
       x[i].style.display = "none";  
    }
    myIndex++;
    if (myIndex > x.length){myIndex = 1
       $.ajax({  
                      
          type:"POST",
          url:"estatus_content.php",   
          data:{display:'block'}, 
          success:function(data){
            $('#cuerpito').html(data);
          }

          });
    }    
    x[myIndex-1].style.display = "block";  
    setTimeout(carousel, 5000);

}

</script>
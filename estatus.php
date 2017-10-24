     
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
  @font-face {
  font-family: 'monse';
  src:  url('fonts/Montserrat-Regular.otf');
 
}
@font-face {
  font-family: 'monse-black';
  src:  url('fonts/Montserrat-Black.otf');
 
}
@font-face {
  font-family: 'monse-bold';
  src:  url('fonts/Montserrat-Bold.otf');
 
}
@font-face {
  font-family: 'monse-medium';
  src:  url('fonts/Montserrat-Medium.otf');
 
}
    @font-face {
  font-family: 'aharon';
  src:  url('fonts/ahronbd.ttf');
 
}
@font-face {
  font-family: 'monlight';
  src:  url('fonts/MontseLight.otf');
 
}
.machinename{
  width: 100%;
  height: 30px;
  position:relative;
  color:#B7D8F3;
  line-height: 30px;
  font-weight: normal;
}
    .prod-container{
      width: 100%;
      text-align: center;
    }
    .personal{
      width: 31%;
    background-color: #333333;
    height: 22em;
    display: inline-block;
    border-radius: 5px;
    margin: 10px;
    vertical-align: top;
    position: relative;
    font-family: "monse";


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
      border: 1px solid #777878;
    }
    .ete-photo{
      height: 110px;
      width: 100%;
      background: #212121;
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
      font-size: 50px;
      color: #fff;
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
      color: #fff;
      font-size: 29px;
      

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
      background:#393939;
      border-bottom: 1px solid #444444;
      border-top: 1px solid #444444;  
    }
    .middletd{
      border-right: 1px dashed #444444;
      border-left: 1px dashed #444444;
    }
    .trb{
      line-height: 24px;
    }
    .nombre_elemento{
      font-size: 13px;
      width: 100%;
    }
    .prod-container{
      background: #1D1A1D!important;
    }
.disabled{
  opacity: 0.3
}
#cuerpito{
  background: #000;
}
    @media screen and (max-width: 768px) {
 
 .personal{
 width:45%;
 }

}


@media screen and (max-width: 412px) {
 
 .personal{
 width:90%;
 }

}


  </style>
</head>
<body id="cuerpito">

<?php include 'estatus_content.php' ?>


</body>
</html>
<script>
$(document).ready(function(){ 
setInterval(function() {
            
              $('#cuerpito').hide().fadeIn('slow'); 
                  $('#cuerpito').load('estatus_content.php').show().fadeIn(3000);;
                          $('#cuerpito').show().fadeIn(3000);
                          console.log('se reargo');
                }, 61000);


                });
   
       
</script>
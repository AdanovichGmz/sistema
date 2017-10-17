<?php
if( !session_id() )
{
    session_start();
}
if(@$_SESSION['logged_in'] == true){
   header('Location:http:'.dirname($_SERVER['PHP_SELF']).'/index2.php');
}else{
    ?>


<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title></title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <!-- Latest compiled and minified CSS -->
   <link rel="stylesheet" href="css/bootstrap.min.css" />
<link rel="stylesheet" href="css/bootstrap-theme.min.css" />
<link rel="stylesheet" href="css/3.3.6/bootstrap.min.css" />
    <link href="css/estilos.css" rel="stylesheet" />

    <link rel="stylesheet" type="text/css" href="style.css" />
    <script type="text/javascript" src="./main.js"></script>
    <script type="text/javascript" src="./llqrcode.js"></script>

    
</head>
<body>
    
    
<style >/*
    select{
        width: 100%;
    background: #fff;
    border-radius: 2px;
    padding: 7px;
    border: none;
    margin-top: 15px;
    text-align: center!important;
    font-size: 30px;
    font-family: "monse-medium";
    }
    */
    p{
        color: red;
        font-size: 20px;
    }
</style>

   
   

    

    <div class="container">
        <div class="login-box">
        <div class="login-inner">
          <form id="logg" action="validar.php" method="post">
        <div class="login-logo">
             <img src="images/logo-blanco.png" >
        </div>
        
        <select name="mac" required="true" id="area" class="login-input" style="text-align: center!important;">
        <option   disabled="true" selected="true" value="">Selecciona el area</option>
            
            <option value="b0:34:95:01:ec:2b">SUAJE</option>
            <option value="f0:db:f8:11:97:bc">SERIGRAFIA</option>
            <option value="2c:f0:ee:3d:53:99">SERIGRAFIA2</option>
            <option value="90:b9:31:ed:0f:6b">SERIGRAFIA3</option>
           
           
        </select>
        <p id="selecarea" style="display: none;">Falta seleccionar el area ↑</p> 
            <input id="usuario" name="usuario" type="text" placeholder="USUARIO" class="login-input" required="" />
            <input id="password" name="pass" type="password" placeholder="CONTRASEÑA" class="login-input" required="" />
            <input type="button" id="singlebutton" value="ENTRAR" name="singlebutton" class="login-button">
            </form>
        </div>
            
        </div>

    </div> 
   
    <script src="./jquery-1.11.2.min.js"></script>
</body>
<script type="text/javascript">
    $(document).ready(function(event) {
        $( "#singlebutton").click(function() {
           var area= $( "#area option:selected" ).text();
           console.log(area);
           if (area=='Selecciona el area') {
            $('#selecarea').show();
           } else{
            $( "#logg" ).submit();
           }                                
                                                      
                                                   
                                             
    });
    });
</script>
</html>
<?php } ?>
<?php
if( !session_id() )
{
    session_start();
}
if(@$_SESSION['logged_in'] == true){
  if ($_SESSION['rol']==3) {
   header('Location:http:'.dirname($_SERVER['PHP_SELF']).'/superadmin');
  }else{
    header('Location:http:'.dirname($_SERVER['PHP_SELF']).'/index2.php');
  }
   
}else{
    ?>


<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title></title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <!-- Latest compiled and minified CSS -->
   <link rel="stylesheet" href="../css/bootstrap.min.css" />
<link rel="stylesheet" href="../css/bootstrap-theme.min.css" />
<link rel="stylesheet" href="../css/3.3.6/bootstrap.min.css" />
    <link href="../css/estilos.css" rel="stylesheet" />
<link href="../css/estiloshome.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="style.css" />
    <script type="text/javascript" src="../main.js"></script>
   

    
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
    body{
      position: relative!important;
    }
    p{
        color: red;
        font-size: 20px;
    }
    @media screen and (min-width:1025px) {
  .login-box{
    width: 400px;
    min-height: 310px;
    font-size: 13px;
  }
  .login-logo{
    width: 80%;
    height: 150px;
    margin:0 auto;
  }
  .login-input{
    font-size: 13px;
  }
  .login-button{
    font-size: 13px;
  }
  .login-logo img{
    padding-top: 35px;
  }
}
</style>

   
   

    

    <div class="container">
        <div class="login-box">
        <div class="login-inner">
          <form id="logg" action="../validar.php" method="post">
        <div class="login-logo">
             <img src="../images/logo-blanco.png" >
        </div>
        
        
            <input id="usuario" name="usuario" type="text"   placeholder="USUARIO" class="login-input" required="" />
            <input id="password" name="pass" type="password" placeholder="CONTRASEÑA" class="login-input"  required="" />
            <input type="button" id="singlebutton" value="ENTRAR" name="singlebutton" class="login-button">
            </form>
        </div>
            
        </div>

    </div> 
   <div id="panelkeyboard3">
    
    <div class="keycontainer">
      <div id="softk" class="softkeys" data-target="input[name='getodt']"></div>
    </div>
    
      <div id="close-down-key" class="square-button-micro red " style="display: none;">
                          <img src="../images/ex.png">
                        </div>
    
    
</div>
    <script src="../jquery-1.11.2.min.js"></script>
</body>
<script type="text/javascript">
var kb=false;
    $(document).ready(function(event) {
        $( "#singlebutton").click(function() {
                                          
                                                      
             $( "#logg" ).submit();                                      
                                             
    });
    });
    $(function() {
        console.log('fecha: '+localStorage.getItem('fecha')); 
      var currentdate = new Date().toJSON().slice(0,10).replace(/-/g,'/');
  console.log("fecha actual: "+currentdate);
console.log("fecha guardada: "+localStorage.getItem('fecha'));
if (localStorage.getItem('fecha')!==currentdate) {
  localStorage.removeItem('horaincio');
  localStorage.removeItem('tiroactual');
  localStorage.removeItem('segundosincio');
  localStorage.removeItem('fecha');
  console.log('se eliminaron los estorages');
}
   });
     $(document).keypress(function(e) {
    if(e.which == 13) {
      event.preventDefault();
      $('#singlebutton').click();
}
});


 
</script>
</html>
<?php } ?>


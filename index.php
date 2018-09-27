<?php
if( !session_id() )
{
    session_start();
}
if(@$_SESSION['logged_in'] == true){
  if ($_SESSION['rol']==3) {
   header('Location:http:'.dirname($_SERVER['PHP_SELF']).'/superadmin');
  }else{
    if ($_SESSION['environment']=='encuadernacion') {
      header('Location:http:'.dirname($_SERVER['PHP_SELF']).'/encuadernacion/');
    }else{
      header('Location:http:'.dirname($_SERVER['PHP_SELF']).'/index2.php');
    }
    
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
   <link rel="stylesheet" href="css/bootstrap.min.css" />
<link rel="stylesheet" href="css/bootstrap-theme.min.css" />
<link rel="stylesheet" href="css/3.3.6/bootstrap.min.css" />
    <link href="css/estilos.css" rel="stylesheet" />
<link href="css/estiloshome.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="style.css" />
    <script type="text/javascript" src="./main.js"></script>
    <script type="text/javascript" src="./llqrcode.js"></script>
<link rel="stylesheet" href="css/softkeys-small.css">
    
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
      background: #ededed;
      background-image: none!important;
      position: relative!important;
    }
    p{
        color: red;
        font-size: 20px;
    }
    
.active{
  background: #FFF8C4!important;
  color: #6A6867!important;
  border:2px solid #6A6867;
}
.desktopkey{
  background: rgba(44, 151, 222, 0.9)!important;
  box-shadow: none!important;
}
.desktopkey .softkeys__btn{
  background: none!important;
  border-color: #fff!important;
}
.desktopkey .softkeys__btn:hover{
  background: #fff!important;
  color: #579DFF!important;
}
.desktopkey .softkeys__btn:hover span{

  color: #579DFF!important;
}
</style>

   
   

    

    <div class="container">
        <div class="login-box">
        <div class="login-inner">
          <form id="logg" action="validar.php" method="post">
        <div class="login-logo">
             <img src="images/logo-blanco.png" >
        </div>
        
        
            <input id="usuario" name="usuario" type="text" readonly onclick="getKeys(this.id,'pedido')" placeholder="USUARIO" class="login-input" required="" />
            <input id="password" name="pass" type="password" placeholder="CONTRASEÑA" class="login-input" readonly onclick="getKeys(this.id,'pedido')" required="" />
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
                          <img src="images/ex.png">
                        </div>
    
    
</div>
    <script src="./jquery-1.11.2.min.js"></script>
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


function getKeys(id,name) {
      $('#'+id).select();
      $('.active').removeClass('active');
      $('#'+id).addClass('active');
      $('#softk').attr('data-target', 'input[name="'+name+'"]');
        if (kb == false) {
          
          $(".login-box").animate({ top: '-=30%' }, 200);
            $("#panelkeyboard3").animate({ bottom: '+=50%' }, 200);
            kb = true;
        }
        var bguardar;
        
        $('#softk').empty();     
         $('.softkeys').softkeys({
                    target :  $('#'+id),
                    layout : [
                        [
                            
                            ['1','!'],
                            ['2','@'],
                            ['3','#'],
                            ['4','$'],
                            ['5','%'],
                            ['6','^'],
                            ['7','&amp;'],
                            ['8','*'],
                            ['9','('],
                            ['0',')']
                        ],
                    [
                            'q','w','e','r','t','y','u','i','o','p'
                            
                        ],
                        [
                            
                            'a','s','d','f','g','h','j','k','l','ñ'
                            
                            
                            
                        ],[
                            
                            'z','x','c','v','b','n','m','←']
                            
                            ],

                    id:'softkeys'
                });
              
                $('#hidekey').parent('.softkeys__btn').addClass('hidder'); 
    $('#savekey').parent('.softkeys__btn').addClass('saver').attr('id', 'saver');;            
$('#borrar-letras').parent('.softkeys__btn').addClass('large');
            $('#borrar-softkeys').parent('.softkeys__btn').addClass('large');
            if (id=='virtualodt'||id=='virtualelem') { $('.savebutton').show();}else{$('.savebutton').hide();}
    }  

  
$(document).ready(function () {
  var ismobile= isMobileDevice();
    var link = document.getElementById('panelkeyboard3');
    if (ismobile==false) {
      
      $('#panelkeyboard3').addClass('desktopkey');
      
    }

});

    


function isMobileDevice() {
    return (typeof window.orientation !== "undefined") || (navigator.userAgent.indexOf('IEMobile') !== -1);
}  
</script>
</html>
<?php } ?>

<script src="js/softkeys-0.0.1.js"></script>
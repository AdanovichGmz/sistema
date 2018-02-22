<?php 

  require('saves/conexion.php');
    
?><!DOCTYPE html>
<html>
<head>
    <title></title>
   <script src="./jquery-1.11.2.min.js"></script>
   <style>
       body{
        padding: 0;
        margin: 0;
        background:#ccc;
       
       }
       .options{
         position: absolute;
    width: 600px;
     top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
text-align: center;
font-size: 25px;
       }
      .option{
        width: 200px;
        height: 200px;
        text-align: center;
        color: #fff;
        background:#000;
        border-radius: 4px;
        margin: 20px;
        line-height: 200px;
        display: inline-block;
        font-size: 25px;
      } 
   </style>
</head>
<body>
<div class="options">
<p>¿Que desea realizar?</p>
    <div data-option="Suaje" class="option">Suaje</div>
   <div data-option="LetterPress" class="option">Letter Press</div>
</div>
</body>
</html>
<script>
    $( ".option").click(function() {
         console.log('perro')                           
        var option=$(this).data('option')     
                                                      
          $.ajax({
                                    url: "setMachine.php",
                                    type: "POST",
                                    data:{option:option},
                                    success: function(data){
                                     window.location.replace("index2.php");


                                    }        
                                   });                                      
                                             
    });
</script>

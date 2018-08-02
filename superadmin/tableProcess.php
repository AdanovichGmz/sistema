<?php
 require('../saves/conexion.php');
 error_reporting(0);   
     
 $fecha = $_POST['fecha'];
$userid = $_POST['iduser'];


$query = "SELECT * FROM procesos_catalogo";




$resss     = $mysqli->query($query);




?>
<style>
    .user-photo{
        width: 50px;
        margin: 0 auto;
    }
</style>
<div class="conttabla2">
    
    <div class="datagrid">
<table id="datos" class="order-table table hoverable lightable">
<thead><tr>
    
    <th>Proceso</th>
    <th>Precio por tiro</th>
    <th>Precio por cambio</th>
    

  </tr>

  </thead>
  <?php while ( $row=mysqli_fetch_assoc($resss)) { ?>
    <tbody>
    
    <td><?=$row['nombre_proceso'] ?></td>
    <td><?=$row['precio'] ?></td>
    <td><?=$row['precio_cambio'] ?></td>  
    <!--
    <td style="width: 70px;"><img class="edit-oper new-modal" data-oper="<?=$row['id'] ?>" src="../images/e.png" alt="" width="30" height="30" ></td>  -->
    
  </tbody>
  <?php } ?>
  
  
  <?php

if ($resss->num_rows==0){
  echo "<tr ><td colspan='24' style='padding:20px;'>NO SE ENCONTRO INFORMACION PARA ESTE OPERADOR EN ESTE DIA</td></tr> ";
}
?>
  
  
</table>
<?php
$treal = $sum_tiraje;


?>

<br>


</div>
</div>
<div class="overlay"></div>

      <script type="text/javascript">
 
      var globeerror=false;
      var count=0;
      $(document).ready(function(){

        $('.lightbox').click(function(){
          $('.backdrop').animate({'opacity':'.50'}, 300, 'linear');
          $('.newtiro-modal').animate({'opacity':'1.00'}, 300, 'linear');
          $('.backdrop, ').css('display', 'block');
        });
 
        $('.close3').click(function(){
          close_box();
          close_box3();
        });
 
        $('.backdrop').click(function(){
          close_box();
        });
        $('.lightbox2').click(function(){
          $('.backdrop').animate({'opacity':'.50'}, 300, 'linear');
          $('.box2').animate({'opacity':'1.00'}, 300, 'linear');
          $('.backdrop, .box2').css('display', 'block');
        });
        $('.lightbox3').click(function(){
          $('.backdrop').animate({'opacity':'.50'}, 300, 'linear');
          $('.box3').animate({'opacity':'1.00'}, 300, 'linear');
          $('.backdrop, .box3').css('display', 'block');
        });
        $('.close2').click(function(){
          close_box2();
        });
 
        $('.backdrop').click(function(){
          close_box2();
          close_box3();
        });
      });
 
      function close_box()
      {
        
        $('.backdrop, .box,.newtiro-modal').css('display', 'none');
      }
  function close_box2()
      {
       
        $('.backdrop, .box2').css('display', 'none');
      }
      function close_box3()
      {
        
        $('.backdrop, .box3').css('display', 'none');
      }

      $(".timeinput").keyup(function () {
    if (this.value.length == this.maxLength) {
      $(this).next('.timeinput').focus().select();
    }
});
$(".cancel").click(function (e) {
   globeerror=false;
   count=0;
   $('.globe-error').remove();
    e.stopPropagation()
      var div=$(this).parent('div');
      div.hide();
      console.log('cerrado');
      $('.overlay').animate({'opacity':'0'}, 300, 'linear', function(){
          $('.overlay').css('display', 'none');
        });
  
});


$(".overlay").click(function (e) {

  globeerror=true;
   if (globeerror&&count==0) {
    $('.tooltiptext').append('<span class="globe-error">¿Deseas guardar los cambios?</span>');
    count++;
   }

       
  
});
$(".cifra").click(function () {
  $(this).select();
});
$(".cifra").keyup(function () {
  console.log('len: '+this.value.length);
  

  var number = parseInt($(this).val());
  var value=$(this).val();
        if(number > 60){
           $(this).val("00");

    $(this).select();
        }
      /*  if (value.length==1) {

    $(this).val('0'+value);
    $(this).select();

  } */
        if (this.value.length==2 && this.value >0) {
   
          //$(this).closest('div').next().find(':input').first().focus();
        }
  
});


$(".cifra").change(function (e) {
  var value=$(this).val();
  console.log('cifra: '+value.length);
  if (value.length==1) {

    $(this).val('0'+value);

  }
    
  
});


function deleteTiro(id){
  console.log(id);
}

function deleteTiro(id){
  var user=$('#filterElem').val();
  var fecha=$('#datepicker').val();
    $('<div></div>').appendTo('body')
                    .html('<div><h6>Estas seguro de querer borrar este tiro?</h6></div>')
                    .dialog({
                        modal: true, title: 'Eliminar tiro', zIndex: 10000, autoOpen: true,
                        width: 'auto', resizable: false,
                        buttons: {
                            Si: function () {
                                // $(obj).removeAttr('onclick');                                
                                // $(obj).parents('.Parent').remove();
                                
                                $.ajax({
                                    url: "updates.php",
                                    type: "POST",
                                    data:{form:'delete-tiro',id:id},
                                    success: function(data){
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
                                
                                $(this).dialog("close");
                            },
                            No: function () {                                                             
                            
                                $(this).dialog("close");
                            }
                        },
                        close: function (event, ui) {
                            $(this).remove();
                        }
                    });
    };
    </script>
    </div>

    </div>
    
<?php


include '../saves/conexion.php';

$fecha = $_POST['fecha'];

$query = "SELECT ep.id_proceso,(SELECT nombre_proceso FROM procesos_catalogo WHERE id_proceso=ep.id_proceso)AS nom_proceso FROM estaciones_procesos ep GROUP BY id_proceso";

$res=$mysqli->query($query);
if (!$res) {
  printf($mysqli->error);

}

while ($row=mysqli_fetch_assoc($res)) {
$query2="SELECT SUM(entregados)AS produccion_dia,(SELECT nombre_proceso FROM procesos_catalogo WHERE id_proceso=t.id_proceso)AS nombre_proceso, SUM(produccion_esperada)AS objetivo, SUM(produccion_esperada)-SUM(entregados) AS diferencia FROM tiraje t WHERE fechadeldia_ajuste='$fecha' AND id_proceso=".$row['id_proceso'];
  $res2=$mysqli->query($query2);

$dayInfo=mysqli_fetch_assoc($res2);




  $process[$row['id_proceso']]['produccion_dia']=(empty($dayInfo['produccion_dia']))? 0:$dayInfo['produccion_dia'];
  $process[$row['id_proceso']]['objetivo']=(empty($dayInfo['objetivo']))? 0:$dayInfo['objetivo'];
  $process[$row['id_proceso']]['diferencia']=(empty($dayInfo['diferencia']))? 0:$dayInfo['diferencia'];
  $process[$row['id_proceso']]['nombre_proceso']=$row['nom_proceso'];

}


?>

 
<div class="conttabla2">
    
    <div class="datagrid"> 
<table id="datos" class="order-table table hoverable lightable">
<thead>
  <tr class="theader">
    <th >Producto</th>
    <th >Produccion por dia</th>
    <th >Objetivo</th>
    <th >Diferencia</th>
    <th>Fecha</th>
    <th >Produccion Acumulada</th>
    
  </tr>
  
  </thead>

  
   <tbody>
  <?php 
    foreach ($process as $key => $pro) { ?>
      <tr>
        <td><?=$pro['nombre_proceso'] ?></td>
        <td><?=$pro['produccion_dia'] ?></td>
        <td><?=$pro['objetivo'] ?></td>
        <td><?=$pro['diferencia'] ?></td>
        <td><?=$_POST['fecha'] ?></td>
        <td>--</td>
      </tr>

   <?php  }
   
   ?>
  </tbody>
</table>


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
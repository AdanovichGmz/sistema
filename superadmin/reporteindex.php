 <?php
if (!session_id()) {
    session_start();
    
    
}
if (@$_SESSION['logged_in'] != true) {
    echo '
    <script>
        alert("No has iniciado sesion");
        self.location.replace("../index.php");
    </script>';
} else {
    echo '';
}
?>

<!DOCTYPE html>

<html lang="en" dir="ltr" xmlns:fb="http://ogp.me/ns/fb#">
<head>

    
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <title>Index</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous" />
    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous" />
    <!-- Latest compiled and minified JavaScript 
    <script src="../js/search.js"></script> -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <script type="text/javascript" charset="utf8" src="http://ajax.aspnetcdn.com/ajax/jQuery/jquery-2.0.3.js"></script>
    <link href="../css/estilosadmin.css?v=2" rel="stylesheet" />
    <link rel="stylesheet" href="../fonts/style.css">
    <script src="http://code.jquery.com/jquery-latest.js"></script>
    <script src="../js/main.js"></script>

    

<style>
body{
  
}
  .bkloader{
    position: absolute;
    text-align: center;
    width: 160px;
    height: 160px;
    opacity: 1!important;
    top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
  }
  .bkloader img{
    width: 100%;
  }
  #buscar{
  width: 240px;
  font-size: 18px;
  color:black;
  background: #181820;
  padding-left: 20px ;
  text-align: center;
  border-radius: 5px;
  padding: 10px;
  margin:10px;
  z-index: 49;
  position: absolute;
  right: 0; 

}
#buscar input{
  border:none;
  padding: 4px;
  border-radius: 3px;

}
@media screen and (max-width: 1294px) {
  .focos{
    margin-left: 10px;
  }
  
  }
@media screen and (max-width: 1219px) {
  .nom-contain{
    display: none;
  }
  .nom-contain,.focos-contain{
    float: none;
  }
  }
</style>

  
</head>
<body style="background:#1F242A;">

<?php
include("topbar.php");
?>

  
 


<div class="nom-contain">
<div class="search-focos">
<form id="ajaxsearch" onsubmit="getSearch();">
<table id="sear">
  <tr>
    <td style="text-align: right;width: 65%"><input  id="searching" class="light-table-filter" data-table="order-table" placeholder="Ingresa una ODT.." ></td>
    <td style="text-align: left;width: 35%"><input class="btn btn-primary" type="submit" name="" value="BUSCAR"></td>
  </tr>
</table>
   </form>
</div>
<table id="nom">
  <tr>
    <td class="l">En Tiempo</td>
    <td class="r"><img width="15" src="../images/verde.jpg"/></td>
  </tr>
  <tr>
    <td class="l">Tarde</td>
    <td class="r"><img width="15" src="../images/amarillo.jpg"/></td>
  </tr>
   <tr>
    <td class="l">No se ha Realizado</td>
    <td class="r"><img width="15" src="../images/rojo.jpg"/></td>
  </tr>
   <tr>
    <td class="l">Programado</td>
    <td class="r"><img width="15" src="../images/azul.jpg"/></td>
  </tr>
   <tr>
    <td class="l">No Aplica</td>
    <td class="r"><img width="15" src="../images/blanco.jpg"/></td>
  </tr>
</table>

</div>  
<div class="focos-contain">     
      <div class="focos">
      <?php
include 'semaforo.php';
?>
      </div>
      <div class="paginatorajax">
      <nav><ul class="pagination">
<?php
if (!empty($total_pages)):
    for ($i = 1; $i <= $total_pages; $i++):
        if ($i == 1):
?>
            <li class='active'  id="<?php
            echo $i;
?>"><a href='pagination.php?page=<?php
            echo $i;
?>'><?php
            echo $i;
?></a></li> 
            <?php
        else:
?>
            <li id="<?php
            echo $i;
?>"><a href='pagination.php?page=<?php
            echo $i;
?>'><?php
            echo $i;
?></a></li>
        <?php
        endif;
?>          
<?php
    endfor;
endif;
?>
</ul></nav>
</div> 

</div>
 <div class="backdrop"><div class="bkloader"><img src="../images/loaderw.gif"></div></div>
  <div class="big-box-large"><div class="close">x</div>
  <div class="big-box-large-in"></div>
  </div>
<?php
include 'editOrder.php';
?>
<script type="text/javascript">
$('.tv-body:odd').css('background-color', '#333333');
</script>
<script>
/*
                        function startTime() {
                            today = new Date();
                            h = today.getHours();
                            m = today.getMinutes();
                            s = today.getSeconds();
                            m = checkTime(m);
                            s = checkTime(s);
                            document.getElementById('hora').innerHTML = h + ":" + m + ":" + s;
                            t = setTimeout('startTime()', 500);
                        }
                        function checkTime(i) {
                            if (i < 10) {
                                i = "0" + i;
                            }
                            return i;
                        }
                        window.onload = function() {
                            startTime();
                        } */
                    </script>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="/resources/demos/style.css">
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript">
      var i = 2;
      function close_box()
      {
        $('.backdrop, .big-box-large').animate({'opacity':'0'}, 300, 'linear', function(){
          $('.backdrop, .big-box-large').css('display', 'none');

        });
        $('.backdrop, .big-box-large2').animate({'opacity':'0'}, 300, 'linear', function(){
          $('.backdrop, .big-box-large2').css('display', 'none');
          
        });
      }


      $(document).ready(function(){
        $('.backdrop').click(function() {
        while(i > 2) {
        $('.field:last').remove();
        i--;
        }
        $('.controls').css('visibility','hidden');
          $('.iteration').html('1').hide();
        });
         $('.close').click(function() {
        while(i > 2) {
        $('.field:last').remove();
        i--;
        }
        });
        $('.submit').click(function(){
        var answers = [];
        $.each($('.field'), function() {
        answers.push($(this).val());
        });
        if(answers.length == 0) {
        answers = "none";
        }
        alert(answers);
        return false;
        });

        $('.pagination').pagination({
        items: <?php
echo $total_records;
?>,
        itemsOnPage: <?php
echo $limit;
?>,
        cssStyle: 'light-theme',
    currentPage : 1,
    onPageClick : function(pageNumber) {
      jQuery(".body").html('<p style="width:837px;text-align:center; color:#fff;margin-top:150px;">Cargando...</p>');
      jQuery(".body").load("pagination.php?page=" + pageNumber);
    }
    });

});

$( function() {
    $( "#datepicker" ).datepicker({ dateFormat: 'yy-mm-dd'});
  } );
$( function() {
    $( "#datepicker2" ).datepicker({ dateFormat: 'yy-mm-dd'});
  } );

        function removeProcess(){
         
        if(i > 2) {
        $('.field:last').remove();
        i--;
        }
        }

        function checking(idorden,id){

          $('#odete'+idorden).prop("disabled", false).val(idorden);
          $('#'+id+' .controls').css('visibility','visible');
          var $checkbox = $('#'+id).find('input:checkbox');
          var $checkboxicon = $('#'+id).find('div:first');
        $checkbox.prop('checked', !$checkbox.prop('checked'));
        $checkboxicon.toggleClass('checkicon-on');
        var ischk=$checkbox.is(':checked')
        var len=$("#inputs-"+idorden).find('input[type=checkbox]:checked').length;
        if (len<1) {
          $('#odete'+idorden).prop("disabled", true).val('');
        }
        
        if (!ischk) {
          $('#'+id+' .controls').css('visibility','hidden');
          $('#iteration-'+id).html('1').hide();
          $('.inc-proc'+id).remove();
            $checkboxicon.removeClass('checkicon-on-green');
        $checkboxicon.removeClass('checkicon-on-yellow');
        $checkboxicon.removeClass('checkicon-on-red');
        $checkboxicon.removeClass('checkicon-on-blue');
          console.log('.inc-proc'+id);
        }
        i++;
        }
        function showTooltip(id){
          $('#'+id).toggleClass('error-div')
        }

        function lessProcess(id){
           var itr=$('#iteration-'+id).html();
           if (itr>1) {
             $('#iteration-'+id).show();
          $('#iteration-'+id).html(parseInt(itr)-1);
          $('.inc-proc'+id+':last').remove();
           }
         

        }
         function moreProcess(id,idor,proceso){
          
          var itr=$('#iteration-'+id).html();
          $('#iteration-'+id).show();
          var times=parseInt(itr)+1;
          $('#iteration-'+id).html(times);
          $('<input class="temporal inc-proc'+id+'" type=hidden name=procesos_'+idor+'[] value="'+proceso+'"/>').fadeIn('slow').appendTo('#'+id);
        /*
          $('<input class="temporal inc-proc'+id+'" type=hidden name=procesos_'+idor+'[] value="'+proceso+'-'+times+'"/>').fadeIn('slow').appendTo('#'+id);
        */
          
        }

        $('.addOrder').click(function(){
          $('#action').val('insert');
        });


         function addOrder(){
         var minimo=$('#new-order input[type=checkbox]:checked').length; 
          if(minimo>=1){
          $('#new-order').hide();
          $('#saveload').show();
          $('#loader-container').show();
                    event.preventDefault();
                    
                        $.ajax({  
                              
                             type:"POST",
                             url:"newOrder.php",   
                             data:$('#new-order').serialize(),  
                               
                             success:function(data){
                              document.getElementById('new-order').reset();
                      $('.checkicon-on').removeClass('checkicon-on');
                      $('.controls').css('visibility','hidden');
          $('.iteration').html('1').hide();
                      $('.temporal').remove();
                              $('#saveload').hide();
                              $('#loader-container').hide();
                               $('#new-order').show();
                                  //$('#update-form')[0].reset();  
                                  $('.close').click();  
                                  $('.focos').html(data);  
                             }  
                        });  
                } else{
                  $('#minimo').show();
                  setTimeout(function() {   
                  $('#minimo').hide();
                }, 3000);
                  event.preventDefault();
                }   
              }
              /*

               function editOrder(){  
          $('#update-order').hide();
          $('#saveload2').show();
                    event.preventDefault();
                    
                        $.ajax({  
                              
                             type:"POST",
                             url:"newOrder.php",   
                             data:$('#update-order').serialize(),  
                               
                             success:function(data){
                              document.getElementById('update-order').reset();
                      $('.checkicon-on').removeClass('checkicon-on');
                      $('.controls').css('visibility','hidden');
          $('.iteration').html('0').hide();
                      $('.temporal').remove();
                              $('#saveload').hide();
                               $('#new-order').show();
                                  //$('#update-form')[0].reset();  
                                  $('.close').click();  
                                  $('.focos').html(data);  
                             }  
                        });  
                    
              }
             
     

      setInterval(function(){
      $('.focos').load('semaforo.php');

 },5000);  */
    </script>

</body>
</html>
 <script type="text/javascript" src="../js/jquery.simplePagination.js"></script>
 <script type="text/javascript">
function getSearch(){
       
       
   }

   $(document).on("submit", "#ajaxsearch", function (e) {
    e.preventDefault();
        var inputVal = $('#searching').val();
       $('.body').html('<p style="width:837px;text-align:center; color:#fff;margin-top:150px;">Cargando...</p>');
          if (inputVal.length) {
            var datas=inputVal;
          }else{
            datas=null;
          }
          console.log(datas);
            $.ajax({ 
              type:"POST",
              url:"ajax-search.php",   
              data:{condition:datas},
              success:function(data){
                $('.body').html(data);  
                             }  
                        });
});
</script>
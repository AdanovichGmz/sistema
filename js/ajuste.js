/******************** index2.php ********************/

$(document).ready(function(event) {
   // Esta primera parte crea un loader no es necesaria
    $().ajaxStart(function() {
        $('#loading').show();
        $('#resultaado').hide();
    }).ajaxStop(function() {
        $('#loading').hide();
        $('#resultaado').fadeIn('slow');
    });
   // Interceptamos el evento submit
    
    $('#form, #fat').submit(function() {
  // Enviamos el formulario usando AJAX
        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: $(this).serialize(),
            // Mostramos un mensaje con la respuesta de PHP
            success: function(data) {
                //$('#currentOrder').html('ORDEN ACTUAL: '+data);
               
               
                $('.saveloader').hide();
                $('.savesucces').show();
                 setTimeout(function() {   
                   close_box();
                }, 1000);
                
            }
        })        
        return false;
    }); 
/*
    $( "#stop" ).click(function(e) {
      var orderexist=$('#orderID').val();
      if (orderexist!=='') {
        $( "#nuevo_registro" ).submit();
        e.preventDefault();

      } else{
        alert('Debes seleccionar una orden');
      }
                                        

                                            }); */
});

function alerttime(){
  animacion = function(){
  
  document.getElementById('formulario').classList.toggle('fade');
}
setInterval(animacion, 550);
}


                                             $( "#save-ajuste").click(function() {
                                             
                                                      $( "#fo4" ).submit();
                                                   
                                             
                                            });

                                             $('.radio-menu').click(function() {
                                              $('.face-osc').removeClass('face-osc');
                                              $(this).addClass('face-osc').find('input').prop('checked', true)    
                                            });

                                             $('.radio-menu-small').click(function() {
                                              $('.face-osc').find('input').prop('checked', false);
                                              $('.face-osc').removeClass('face-osc');
                                              $(this).addClass('face-osc').find('input').prop('checked', true);
                                              sendOrder();
                                            });

                                              $( document ).ajaxStop(function() {

                                              $('.radio-menu-small').click(function() {
                                                $('.face-osc').find('input').prop('checked', false);
                                              $('.face-osc').removeClass('face-osc');
                                              $(this).addClass('face-osc').find('input').prop('checked', true);
                                              sendOrder();
                                              $('#close-down').click();    
                                            });

                                               

                                              });

                                             function selectOrders(id){
                                              
                                             }
                                             
                                             
                                            

                                            $( document ).ajaxStop(function() {

                                              $('.radio-menu-small').click(function() {
                                               
                                            });

                                                 

                                              });
                                            var element = document.getElementById('nommaquina');
    
 element.addEventListener("change", function(){ 
    var maquina = document.getElementById('maquina');
      
     
     maquina.value = this.options[this.selectedIndex].text;
    
     
 });

 $('.backdrop').click(function(){
          close_box();
        });

  function submitEat(suceso){
    $('#s-radios').val(suceso);
    $( "#fo3" ).submit();
  }
  function close_box()
      {
        $('.backdrop, .box, .boxorder').animate({'opacity':'0'}, 300, 'linear', function(){
          $('.backdrop, .box, .boxorder').css('display', 'none');
        });
      }
  function showLoad(){
        $('.backdrop, .box').animate({'opacity':'.50'}, 300, 'linear');
          $('.box').animate({'opacity':'1.00'}, 300, 'linear');
          $('.backdrop, .box').css('display', 'block');
      }
      function sendOrder(id){
        
       $.ajax({
            type: 'POST',
            url: 'opp.php',
            data: $('#tareas').serialize(),
            // Mostramos un mensaje con la respuesta de PHP
            success: function(data) {
                $('#tareas').html(data);
               var curorder= $('#returning').val();
               var curid= $('#returning2').val();
               var orid= $('#returning3').val();
               $('#orderID').val(orid);
              $('#order').val(orid);
               
               $('#currentOrder').html('EN PROCESO: '+curorder+" "+curid);
                $('.saveloader').hide();
                $('.savesucces').show();
                 setTimeout(function() {   
                   close_box();
                }, 1000); 
            }
        })
       
      }

      var timer = new Timer();
 var timerEat = new Timer();
 var timerAlert = new Timer();
$(document).ready(function(){
timer.start();
});
       

$('#nuevo_registro').submit(function () {
    timer.pause();
    $('#timee').val(timer.getTimeValues().toString());
    //$('#timee').val(timer.getTimeValues().toString());
});
/*$('#chronoExample .stopButton').click(function () {
    timer.stop();

});*/
timer.addEventListener('secondsUpdated', function (e) {
    $('#chronoExample .values').html(timer.getTimeValues().toString());
});
timer.addEventListener('started', function (e) {
    $('#chronoExample .values').html(timer.getTimeValues().toString());
});
     
     

   $('.goeat').click(function () {
    timerEat.start();
    //$('#timee').val(timerEat.getTimeValues().toString());
    timerEat.addEventListener('secondsUpdated', function (e) {
    $('#horacomida .valuesEat').html(timerEat.getTimeValues().toString());
    });
    timerEat.addEventListener('started', function (e) {
    $('#horacomida .valuesEat').html(timerEat.getTimeValues().toString());
});
});  

   $('#fo3').submit(function () {
     timerEat.pause();
    $('#timeeat').val(timerEat.getTimeValues().toString());
    timerEat.stop();
   });

   $('.stopeat').click(function () {
    
    timerEat.stop();
   });

   $('.goalert').click(function () {
    timerAlert.start();
    //$('#timee').val(timerAlert.getTimeValues().toString());
    timerAlert.addEventListener('secondsUpdated', function (e) {
    $('#alertajuste .valuesAlert').html(timerAlert.getTimeValues().toString());
    });
    timerAlert.addEventListener('started', function (e) {
    $('#alertajuste .valuesAlert').html(timerAlert.getTimeValues().toString());
});
});  

   $('#fo4').submit(function () {
     timerAlert.pause();
    $('#tiempoalertamaquina').val(timerAlert.getTimeValues().toString());
    timerAlert.stop();
   });

   $('.stopalert').click(function () {
    
    timerAlert.stop();
   });

   $(document).ready(function() {
   // Esta primera parte crea un loader no es necesaria
    $().ajaxStart(function() {
        $('#loading').show();
        $('#result').hide();
    }).ajaxStop(function() {
        $('#loading').hide();
        $('#result').fadeIn('slow');
    });
   // Interceptamos el evento submit
    $('#form, #fat, #fo3, #fo4').submit(function() {
      
  // Enviamos el formulario usando AJAX
        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: $(this).serialize(),
            // Mostramos un mensaje con la respuesta de PHP
            success: function(data) {
                $('#result').html(data);
                $('.saveloader').hide();
                $('.savesucces').show();
                 setTimeout(function() {   
                   close_box();
                }, 1000);
                console.log(data);

            }
        })        
        
    }); 
})

   function limpiar() {
           setTimeout('document.fo3.reset()',2);
      return false;
}
 

 function saveAjuste(){
  var mac=$('#mac').val();
    var order=$('#order').val();
    if($('#orderID').val()==''){
      $('#parts').click();
      $('#elementerror').show();
      setTimeout(function() {   
                   $('#elementerror').hide();
                }, 5000);
    }else{
       timer.pause();
    $('#timee').val(timer.getTimeValues().toString());
     $.ajax({  
                      
                     type:"POST",
                     url:"saves.php",   
                     data:$('#nuevo_registro').serialize(),  
                       
                     success:function(data){ 
                       
                          //$('#update-form')[0].reset();  
                          //$('.close').click(); 
                          window.location.replace("index3.php?mac="+mac+"&order="+order);
                          console.log(data);
                     }  
                }); 
    }

     
 }


 function gatODT(){
    var odt=$('#getodt').val();
     $.ajax({  
                      
                     type:"POST",
                     url:"getODTS.php",   
                     data:{numodt:odt},  
                       
                     success:function(data){ 
                          
                          $('#odtresult').html(data);
                          
                     }  
                });
  }
function sendODT(odt,machine){
    $.ajax({  
                      
                     type:"POST",
                     url:"opp.php",   
                     data:{entorno:'general',odt:odt,machine:machine},  
                       
                     success:function(data){ 
                       $('#odtresult').html(data);   
                         
                     }  
                });
    
  }  
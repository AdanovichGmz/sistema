<?php

$workers=$sessions_model->getTeamMembersBySession($_SESSION['sessionID']);
/*
echo "<pre>";
print_r($_SESSION);
echo "</pre>"; */

?>
<style>
	.active{
		font-weight: bold;
	}
</style>

<script src="<?php echo URL; ?>public/js/libs/2.1.4.jquery.min.js"></script>
    <script>
    var jQuery214=$.noConflict(true);
  </script>
  <link rel="stylesheet" href="<?php echo URL; ?>public/js/css/softkeys-small.css">
<script src="<?php echo URL; ?>public/js/softkeys-0.0.1.js"></script>
  <form id="t-tiros-form">
<ul class="topbar">
  <li><a class="active" href="javascript:void(0)">Operarios activos en esta mesa</a></li>
  
  <li style="float:right"><div id="save-team">GUARDAR<div> </li>
  <li style="float:right"><div id="return">VOLVER<div> </li>
</ul>

<div class="workers-container">
<table class="team-control">
  <thead>

    <th colspan="2" width="20%">Operario</th>
    <th width="20%">Proceso</th>
    <th width="15%">Cantidad de Pedido</th>
    <th width="15%">Buenos</th>
    <th width="15%">Cantidad Recibida</th>
    <th width="15%">Defectos</th>
    
  </thead>
  <tbody>
    <?php foreach ($workers as $worker) { ?>
<tr>
<input type="hidden" name="members[]" value="<?=$worker['id'] ?>">
<input type="hidden" name="members[<?=$worker['id'] ?>]" value="<?=$worker['id'] ?>">
<td style="position: relative;text-align: center;"><div class="adm-worker-photo">
  <img src="<?= URL; ?>public/<?=((!empty($worker['foto']))? $worker['foto'] :'images/default.jpg')?>">
</div></td>
<td style="font-weight: bold;"><?= $worker['logged_in']; ?></td>
<td><?=(isset($_SESSION['teamSession'][$worker['id']]))?  $process_model->getProcessName($_SESSION['teamSession'][$worker['id']]['memberProcessID']) :'Sin asignar' ?></td>
<td><input type="number" id="<?=$worker['id'] ?>-pedidos" onclick="getNumericKeys(this.id,'adm-pedidos','<?=((!empty($worker['foto']))? $worker['foto'] :'images/default.jpg')?>','<?= $worker['logged_in']; ?>','Cantidad de Pedido')" name="adm-pedidos[<?=$worker['id'] ?>]"></td>
<td><input type="number" id="<?=$worker['id'] ?>-buenos" onclick="getNumericKeys(this.id,'adm-buenos','<?=((!empty($worker['foto']))? $worker['foto'] :'images/default.jpg')?>','<?= $worker['logged_in']; ?>','Buenos')" name="adm-buenos[<?=$worker['id'] ?>]"></td>
<td><input type="number" id="<?=$worker['id'] ?>-recibidos" onclick="getNumericKeys(this.id,'adm-recibidos','<?=((!empty($worker['foto']))? $worker['foto'] :'images/default.jpg')?>','<?= $worker['logged_in']; ?>','Cantidad Recibida')" name="adm-recibidos[<?=$worker['id'] ?>]"></td>
<td><input type="number" id="<?=$worker['id'] ?>-defectos" onclick="getNumericKeys(this.id,'adm-defectos','<?=((!empty($worker['foto']))? $worker['foto'] :'images/default.jpg')?>','<?= $worker['logged_in']; ?>','Defectos')" name="adm-defectos[<?=$worker['id'] ?>]"></td>

</tr>


  
<?php } ?>
  </tbody>
</table>
<input type="hidden" name="sesion" value="<?=$_SESSION['sessionID'] ?>">



</div>
</form>	

<div class="full-box">
	
</div>
<div class="backdrop"></div>
<div id="key-operarios" class="keyboard">
<ul class="topbar">
  
  <li style="float:right"><div  class="close-bottom-key"></div></li>
</ul>
    
    <div class="keycontainer">
      <div id="softk" class="softkeys" data-target="input[name='getodt']"></div>
    </div>
    
      <div id="close-down-key" class="square-button-micro red " style="display: none;">
                          <img src="images/ex.png">
                        </div>
    
    
</div>
<div id="teclado3">
  <div class="cerrarkey">
      <div id="close-down2" class="square-button-micro red  ">
                          <img src="<?= URL; ?>public/img/ex.png">
                        </div>
    </div>

    <div class="user-selected">
    <table>
      <tr>
        <td >
          <div id="selected-photo" class="adm-worker-photo">
  
      </div>
        </td>
        <td id="selected-name"></td>
      </tr>
    </table>
      
    </div>
    <div id="selected-concept">
      
    </div>
    <div class="keycontainer">
      <div id="softk2" style="width: 90%;margin: 0 auto; text-align: center;" class="softkeys2" data-target="input[name='buenos']"></div>
    </div>
</div>
<script>
kb=false;
	jQuery214(document).on("click", ".off", function () {

		var $checkbox = $(this).find('input:checkbox');
		var user=jQuery214(this).data('user');
		$('.backdrop').animate({'opacity':'.50'}, 300, 'linear');
$('.backdrop').css('display', 'block');
 $checkbox.prop('checked', !$checkbox.prop('checked'));

        if ($checkbox.prop('checked')) {
			$(this).parent( ".worker-content" ).addClass('w-selected');
		}else{
			$(this).parent( ".worker-content" ).removeClass('w-selected');
		}
		$.ajax({  
                      
          type:"POST",
          url:"<?php echo URL; ?>inicio/assignTasks/",   
          data:{user:user}, 
          success:function(data){

          $('.box').empty();
          $('.box').html(data);
          
          $('.box').animate({'opacity':'1.00'}, 300, 'linear');
          $('.box').css('display', 'block');
         

          }

          });
 
  

});
jQuery214(document).on("click", "#admin-team", function () {
	
		var choosen=jQuery214('.choosen').length;
		if (choosen==0) {
			alert('Selecciona por lo menos un operario');
		}else{
			

	 $.ajax({  
                      
          type:"POST",
          url:"<?php echo URL; ?>tiro/pushMembers/",   
          data:jQuery214('#team').serialize(), 
          success:function(data){
          
          // $('.full-box').html(data);
          //$('.members-container').append(data);
          location.reload();
          //closeBigBox();
           //$('.full-box').animate({'opacity':'1.00'}, 300, 'linear');
           //$('.full-box').css('display', 'block');	
         

           
          }

          });
		}
		
	
	
});




jQuery214(document).on("click", ".picable", function () {

                                
        var name_p=jQuery214(this).data('pname'); 
         var user=$('#task-user').val();

         var $checkbox = $(this).find('input:checkbox');
    $checkbox.prop('checked', !$checkbox.prop('checked'));

          $.ajax({  
                      
          type:"POST",
          url:"<?php echo URL; ?>inicio/prepareTasks/",   
          data:jQuery214('#task-form').serialize(), 
          dataType:"json",
          success:function(data){
            console.log(data); 
            console.log('regreso'+data.response);
          if (data.response=='taken') {
            alert('a este usuario lo acaban de agarrar en el otro equipo');
            closeModal();
            jQuery214('#worker-'+user).remove();

          }else if(data.response=='success'){
            jQuery214('#worker-'+user).addClass('choosen');
            jQuery214('#worker-'+user+' .tasks').html(name_p);
            jQuery214('#worker-'+user+' .worker-click').removeClass('off').addClass('on');
            var $checkbox = jQuery214('#worker-'+user).find('input:checkbox');
    $checkbox.prop('checked', !$checkbox.prop('checked'));
            closeModal();
          }
          //$('#task-'+user).append(task);
          }
          });
         

                                             
    });



 function closeModal(){ 
   $('.backdrop, .box').animate({'opacity':'0'}, 300, 'linear', function(){
          $('.backdrop, .box').css('display', 'none');
        });
}

jQuery214(document).on("click", ".close-modal", function () {

closeModal();
});
jQuery214(document).on("click", ".process", function () {
	var $checkbox = $(this).find('input:checkbox');
		$checkbox.prop('checked', !$checkbox.prop('checked'));

        if ($checkbox.prop('checked')) {
			$(this).addClass('p-selected');
		}else{
			$(this).removeClass('p-selected');
		}


});

jQuery214(document).on("click", ".elem-process", function () {
 
   
  var $checkbox = $(this).find('input:checkbox');
    $checkbox.prop('checked', !$checkbox.prop('checked'));
    

        if ($checkbox.prop('checked')) {
      $(this).addClass('pr-selected');
    }else{
      $(this).removeClass('pr-selected');
    }
      
    

});
jQuery214(document).on("click", ".worker-info", function () {


});

jQuery214(document).on("click", ".on", function () {
	var user=jQuery214(this).data('user');
		jQuery214(this).removeClass('on').addClass('off');
		var $checkbox = jQuery214('#worker-'+user).find('input:checkbox');
		$checkbox.prop('checked', !$checkbox.prop('checked'));
		jQuery214('#worker-'+user).removeClass('choosen');

});

function getKeys(id,name) {
      $('#'+id).select();      
      jQuery214('#softk').attr('data-target', 'input[name="'+name+'"]');
        if (kb == false) {
            $("#key-operarios").animate({ bottom: '+=60%' }, 200);
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
                            //['__','GUARDAR']
                            ],

                    id:'softkeys'
                });
              
                jQuery214('#hidekey').parent('.softkeys__btn').addClass('hidder'); 
    jQuery214('#savekey').parent('.softkeys__btn').addClass('saver').attr('id', 'saver');;            
jQuery214('#borrar-letras').parent('.softkeys__btn').addClass('large');
            jQuery214('#borrar-softkeys').parent('.softkeys__btn').addClass('large');
            
    }



  function closeKeyboard(){
    if (kb==true) {
      $("#key-operarios").animate({ bottom: '-=60%' }, 200);
     kb=false;
    }
     
  }

  jQuery214(document).on("click", ".close-bottom-key", function () {
    closeKeyboard();
});

</script>
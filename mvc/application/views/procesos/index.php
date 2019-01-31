
<style>
	.box{
		background: rgba(44,151,222, 0.90);
		color:#fff;
	}
</style>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<ul class="topbar">
<li style="font-weight: bold;"><a class="active" href="#"><?=$_SESSION['user']['logged_in']; ?></a></li>

  
 

  
</ul>
<div class="options-container">
<p>Por favor elige el proceso a realizar</p>
<div class="options">
<form id="task-form">
<input type="hidden" id="task-user" name="user" value="<?=$_SESSION['user']['id'] ?>">


<?php 
	
	$count=count($processes);
	foreach ($processes as $key => $row) {
		
?>  
<div  data-station="<?=$_SESSION['stationID'] ?>" data-process="<?=$row['id_proceso'] ?>" class="process <?=($count>21)? 'fit-process':'' ?>">
 <?php 
$pendings=$sessions_model->getPendingsByProcess($row['id_proceso']);

 if(!empty($pendings)){
 $count=count($pendings);

  ?>
  <div class="pending-indicator"><?=$count ?> Cambio<?=($count>1)?'s':'' ?> Pendiente<?=($count>1)?'s':'' ?></div>
   <?php  }?>
<span><?=$row['nombre_proceso'] ?></span>

   <input type="checkbox" name="tasks[]" value="<?=$row['id_proceso'] ?>">
</div>

<?php 
	}
 ?>
</form>
</div>
</div>

<script>
	jQuery214(document).on("click", ".process", function () {
		var proc=jQuery214(this).data('process');
		var station=jQuery214(this).data('station');
		$.ajax({  
                      
          type:"POST",
          url:"<?php echo URL.$url ?>",   
          data:{station:station,process_id:proc}, 
          success:function(data){
            console.log(data);
            location.href = '<?php echo URL; ?>ajuste/';
         
                    }

          });


});
</script>
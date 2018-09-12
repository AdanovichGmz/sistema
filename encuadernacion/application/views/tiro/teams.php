<?php


?>

<style>
	.box{
		background: rgba(44,151,222, 0.90);
		color:#fff;
	}
</style>
<ul class="topbar">
<li style="font-weight: bold;"><a class="active" href="javascript:void(0)"><?=$_POST['user']; ?></a></li>

  
 
  <li style="float:right" ><div class="op-close-modal"></div></li>
  
</ul>
<div class="options-container">
<div class="options">
<?php if (count($teams)<=1) { ?>
	<p>Por el momento no hay otros equipos activos</p>
<?php }else{
?>
<p>Seleccione un equipo para trasladarse</p>
<?php } ?>
<?php 
	
	foreach ($teams as $key => $row) {
		
?>  
<div <?=($key==$_SESSION['sessionID'])? 'style="display:none;"':'' ?> data-leader="<?=$row['logged_in'] ?>" data-user="<?=$_POST['user']; ?>"  data-session="<?=$key ?>" class="process <?=($row['id']==$_SESSION['idUser'])? 'used':'free' ?>"><span>Equipo de <?=$row['logged_in'] ?></span>
  
</div>

<?php 
	}
 ?>
</div>
</div>
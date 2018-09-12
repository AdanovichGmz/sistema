<?php
$stations= $process_model->getProcesByUser($_POST['user']);
$userInfo=$login_model->getUserInfo($_POST['user']);

?>

<style>
	.box{
		background: rgba(44,151,222, 0.90);
		color:#fff;
	}
</style>
<ul class="topbar">
<li style="font-weight: bold;"><a class="active" href="javascript:void(0)"><?=$userInfo['logged_in']; ?></a></li>
<li><div id="change-team" data-msession="<?=$_SESSION['teamSession'][$_POST['user']]['memberSessionID'] ?>" data-user="<?=$_POST['user'] ?>" class="icon-button i-change violet"><div class="b-icon"></div><div class="b-text"><span>Cambiar de Equipo</span></div>
  
</div></li>
<li style="float:right" ><div class="op-close-modal"></div></li>
</ul>
<div class="options-container">
<div class="options">


<?php foreach ($stations as $key1 => $station) {
	$processes=$station;
	$count=count($processes);
	foreach ($processes as $key2 => $row) {
		$pendings=$process_model->getPendingsByUser($_POST['user']);
?>  
<div data-option="<?=$row['id_proceso'] ?>" data-user="<?=$_POST['user'] ?>" data-sname="<?=$key1 ?>" data-pname="<?=$row['nombre_proceso'] ?>" data-station="<?=$row['id_estacion'] ?>" <?=(array_key_exists($row['id_proceso'], $pendings))?'data-cola="'.$pendings[$row['id_proceso']]['id_cola'].'"':'' ?> class="process <?=($count>21)? 'fit-process':'' ?> <?=(array_key_exists($row['id_proceso'], $pendings))?'pending':'normal' ?>"><span><?=$row['nombre_proceso'] ?></span>
  <?php if(array_key_exists($row['id_proceso'], $pendings)){ ?>
  <div class="pending-indicator"><?=getPendingsByProcess($row['id_proceso']); ?> Cambio Pendiente</div>
   <?php  }?>
</div>

<?php 
	}
} ?>

</div>
</div>
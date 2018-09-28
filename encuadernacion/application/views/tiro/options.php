<?php

//$stations= $process_model->getProcesByUser($_POST['user']);
$tasks= $process_model->getEncuadernacionTasks();
$userInfo=$login_model->getUserInfo($_POST['user']);

?>

<style>
	.box{
		background: rgba(44,151,222, 0.90);
		color:#fff;
	}
</style>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<ul class="topbar">
<li style="font-weight: bold;"><a class="active" href="#"><?=$userInfo['logged_in']; ?></a></li>

  <li><div id="assign-tasks">GUARDAR<!--<div--> </div></li>
 
  <li style="float:right" ><div class="op-close-modal"></div></li>
  
</ul>
<div class="options-container">
<div class="options">
<form id="task-form">
<input type="hidden" id="task-user" name="user" value="<?=$_POST['user'] ?>">


<?php 
	
	//$count=count($processes);
	foreach ($tasks as $key1 => $task) {
		//$pendings=$process_model->getPendingsByUser($_POST['user']);
		$pendings=array();
		$count=count($task['childs']);
?> 
<?php if ($task['has_child']=='true') { ?>
<div  data-user="<?=$_POST['user'] ?>" data-target="process-task-<?=$key1 ?>"   class="process ">
<?php }else{ ?> 
<div class=" normal no-childs" data-option="<?=$task['id_proceso'] ?>" data-user="<?=$_POST['user'] ?>" data-sname="<?=$key1 ?>" data-pname="<?=$task['name'] ?>" data-station="28">
<?php } ?> 

<span><?=$task['name'] ?></span>
  
   
</div>
<div class="process-container" id="process-task-<?=$key1 ?>">
<?php 
if ($task['has_child']=='true') {

foreach ($task['childs'] as $key2 =>$child){ ?>
<div class="elem-process normal <?=($count>30)? 'fit-process':'' ?>" data-option="<?=$child['id_proceso'] ?>" data-user="<?=$_POST['user'] ?>" data-sname="<?=$key1 ?>" data-pname="<?=$child['n_proceso'] ?>" data-station="28">
<span>
	<?=$child['n_proceso'];?>

</span>

</div>
<?php } }?>
  	
</div>

<?php 
	
}
 ?>
</form>
</div>
</div>
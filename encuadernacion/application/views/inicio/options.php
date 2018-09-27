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

  <li><div id="assign-tasks">ASIGNAR<!--<div--> </div></li>
 
  <li style="float:right" ><div class="close-modal"></div></li>
  
</ul>
<div class="options-container">
<div class="options">
<form id="task-form">
<input type="hidden" id="task-user" name="user" value="<?=$_POST['user'] ?>">


<?php 
	
	//$count=count($processes);
	foreach ($tasks as $key2 => $task) {
		//$pendings=$process_model->getPendingsByUser($_POST['user']);
		$pendings=array();
		$count=count($task['childs']);
?>  
<div  data-user="<?=$_POST['user'] ?>" data-target="process-task-<?=$key2 ?>"   class="process "><span><?=$task['name'] ?></span>
  
   
</div>
<div class="process-container" id="process-task-<?=$key2 ?>">
<?php 
if ($task['has_child']=='true') {

foreach ($task['childs'] as $child){ ?>
<div class="elem-process <?=($count>30)? 'fit-process':'' ?>">
<span>
	<?=$child['n_proceso'];?>

</span>
<input type="checkbox" name="tasks[]" value="<?=$child['id_proceso'] ?>">
</div>
<?php } }?>
  	
</div>

<?php 
	
}
 ?>
</form>
</div>
</div>
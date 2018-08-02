<?php
require('../saves/conexion.php');
$proceso=$_POST['proceso'];
if (isset($_POST['add-new'])) {

	if ($_POST['add-new']=='insert') {
		if (isset($_POST['update'])) {
			$count=count($_POST['products']);
	$fails=0;
	$ajuste=$_POST['ajuste']*60;
$piezas=$_POST['piezas'];
	foreach ($_POST['products'] as $key => $estandar) {
		$updates=$mysqli->query("UPDATE estandares SET ajuste_standard=$ajuste, piezas_por_hora=$piezas WHERE id_estandard=".$estandar);
		if (!$updates) {
			$fails++;
		}
	}
	if ($fails>0) {
  
  echo '<div class="fail" id="message"><div></div><span>Error: </span>No pudimos guardar algunos datos</div>';

  

}else{
  echo '<div class="successs" id="message"><div></div><span>Exito: </span>Datos modificados correctamente!</div>';
}
		}
		else {
			
	$tiempo=$_POST['ajuste']*60;
	$piezas=$_POST['piezas'];
	$i=0;
	$count=count($_POST['products']);
	
	foreach ($_POST['products'] as $key => $product) {
		$insert=$mysqli->query("INSERT INTO `estandares` (`id_estandard`, `ajuste_standard`, `piezas_por_hora`, `id_elemento`, `id_proceso`) VALUES (NULL, $tiempo, $piezas, $product, $proceso)");
		if ($insert) {
			$i++;
		}
		
	}
	if ($i==$count) { ?>
	<div class="successs" id="message"><div></div><span>Exito: </span>Datos guardados!</div>
		
	<?php 
}else{ print_r($_POST) ?>
<div class="fail" id="message"><div></div><span>Error: </span>No pudimos guardar algunos datos</div>

<?php 
}
}
}elseif ($_POST['add-new']=='modify') {
	
	if ($_POST['action']=='edit') {
		
		?>

<input type="hidden" name="update" value="true">
<table id="dispon-elements">


<?php foreach ($_POST['estandares'] as $key => $standard){ 
	$product=mysqli_fetch_assoc($mysqli->query("SELECT * FROM estandares es INNER JOIN elementos el ON el.id_elemento=es.id_elemento WHERE es.id_estandard=".$standard));

	?>
	<tr>
		<td><input type="checkbox" name="products[]" onclick="return false;" readonly checked value="<?= $product['id_estandard']?>"></td>
		<td><?= $product['nombre_elemento']?></td>
	</tr>
<?php } ?>
	
</table>
<p style="color:#ccc;text-align: right;padding: 6px;font-size: 12px;"><?=count($_POST['estandares']) ?> Elementos a modificar</p>
			
		<?php 
		
	}elseif ($_POST['action']=='delete') {
		foreach ($_POST['estandares'] as $key => $estandard) {
			$mysqli->query("DELETE FROM estandares WHERE id_estandard=".$estandard);
		}
	}
	
}

}else{

if (isset($_POST['action'])) {
	
?>


<?php 
}else{ 

$getProducts=$mysqli->query("SELECT * FROM elementos WHERE id_elemento NOT IN(SELECT id_elemento FROM estandares WHERE id_proceso=$proceso) ORDER BY nombre_elemento ASC");

	?>

<table id="dispon-elements">


<?php while ($product=mysqli_fetch_assoc($getProducts) ) { ?>
	<tr>
		<td><input type="checkbox" name="products[]" value="<?= $product['id_elemento']?>"></td>
		<td><?= $product['nombre_elemento']?></td>
	</tr>
<?php } ?>
	
</table>
<p style="color:#ccc;text-align: right;padding: 6px;font-size: 12px;"><?=$getProducts->num_rows; ?> Elementos pendientes</p>

<?php	
}

} ?>
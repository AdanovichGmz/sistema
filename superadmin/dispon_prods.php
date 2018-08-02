<?php
require('../saves/conexion.php');
$proceso=$_POST['proceso'];
if (isset($_POST['add-new'])) {

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
<?php } ?>
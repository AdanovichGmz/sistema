 <?php
require('../saves/conexion.php');
if (!empty($_POST)) {
	$target=$_POST['option'];
	
	$process=$_POST['process'];
	
	$query="SELECT * FROM procesos_catalogo WHERE id_proceso=".$process;
$proces_info=$mysqli->query($query);
$def_query="SELECT * FROM estandares WHERE id_elemento=144 AND id_proceso=".$process;
$default=$mysqli->query($def_query);

$info=mysqli_fetch_assoc($proces_info);
$def=mysqli_fetch_assoc($default);
	if ($target=='estandares') {
	

?>
<input type="hidden" name="tipo_opcion" value="<?=$target ?>">
<?php	

$query2 = "SELECT * FROM estandares es INNER JOIN elementos el ON el.id_elemento=es.id_elemento WHERE es.id_elemento NOT IN(144) AND es.id_proceso=".$info['id_proceso']." ORDER BY nombre_elemento ASC";
$resss     = $mysqli->query($query2);

?>
<div class="mini-table">
<input type="hidden" name="add-new" value="modify">
<input type="hidden" name="proceso" value="<?=$info['id_proceso'] ?>">
<div class="table-controls">
	<table>
		<tr>
			<th style="text-align: left;"><a href="#" id="selectAll">Seleccionar Todos</a></th>
			<th></th>
			<th>Acciones:</th>
			<th><select id="actions" name="action">
				<option value="edit">Editar</option>
				<option value="delete">Eliminar</option>
			</select></th>
			<th><button type="button" id="submit-actions" data-id="<?=$info['id_proceso'] ?>" class="btn">Enviar</button></th>
		</tr>
	</table>
</div>
<div class="table-header">
<div></div><div><span>ELEMENTO</span></div><div><span>TIEMPO DE AJUSTE</span></div><div><span>PIEZAS POR HORA</span> </div>
</div>

<?php if ($resss->num_rows>0) {
	
 ?>

 <input type="hidden" name="processes[<?=$info['id_proceso'] ?>]" value="<?=$info['id_proceso'] ?>">
<?php } ?>
<div class="proces-info">
	
</div>    
<div class="table-body">
<table id="standard-table" class="order-table table hoverable lightable">

  <tbody id="table-elements">
  <?php while ($row=mysqli_fetch_assoc($resss)) { ?>
    
    <tr class="options-<?=$info['id_proceso'] ?>">
    <td><input type="checkbox" name="estandares[]" value="<?=$row['id_estandard'] ?>"></td>
    <td id="name-<?=$row['id_elemento'] ?>" style="text-align: left;"><?=$row['nombre_elemento'] ?></td>
    <td id="minutes-<?=$row['id_elemento'] ?>" style="text-align: center;"><?=round($row['ajuste_standard']/60,2) ?> <span class="mins">Minutos<span></td>
    <td id="pieces-<?=$row['id_elemento'] ?>" style="text-align: center;"><?=$row['piezas_por_hora'] ?></td>
    
    <input type="hidden" name="options-<?=$info['id_proceso'] ?>[]" value="<?=$row['id_elemento'] ?>"> 
   </tr>
  
  <?php } 
if ($resss->num_rows==0){
  echo "<tr ><td colspan='5' style='padding:20px;'>NO SE HAN AGREGADO ESTANDARES PARA ESTE PROCESO</td></tr> ";
}

  ?>
  </tbody>
 
</table>

<br>

</div>
<div class="table-footer">
<input type="text" id="element-filter"  name="dateFilter" placeholder="Filtrar elemento..">
  	<button type="button" class="add add-standard" data-id="<?=$info['id_proceso'] ?>" onclick="return false;" data-empty="<?=($resss->num_rows==0)? 'true':'false' ?>">+ Agregar estandard</button>
  </div>
</div>

<script>
	$(".add-dinam").click(function () {
	var id=jQuery214(this).data('id');
		
  var empty=jQuery214(this).data('empty');
  console.log('empty '+empty);
  if (empty==true) {
  	console.log('si esta empty');
  	$('#table-'+id).append('<input type="hidden" name="processes['+id+']" value="'+id+'" >');
  	jQuery214(this).data('empty', 'false');
  }
  
   
    var tr='<tr class="options-'+id+'"><td><input type="text"  required name="options-'+id+'[]" placeholder="Escribe una opcion.."></td><td><a href="#" class="quit-option">Eliminar</a></td></tr>'; 
    

    $('#table-'+id).append(tr);

});
	<?php if (!empty($_POST)) { ?>
	 $(".quit-option").live("click", function() {
    $(this).closest("tr").remove();
});
<?php } ?>


</script>
<?php
}elseif($target=='info'){  ?>
<input type="hidden" name="section" value="info">
<input type="hidden" name="proceso" value="<?=$info['id_proceso'] ?>">
<table class="table-form">
     <tr>
       <td>Nombre del Proceso:</td>
       <td><input type="text" name="name" value="<?=$info['nombre_proceso'] ?>"></td>
     </tr>
      <tr>
       <td>Precio por Tiros:</td>
       <td><input type="text" name="precio_tiros" value="<?=$info['precio'] ?>"></td>
     </tr>
      <tr>
       <td>Precio por Cambios:</td>
       <td><input type="text" name="precio_cambio" value="<?=$info['precio_cambio'] ?>"></td>
     </tr>
     <tr>
       <td>Cambios Minimos</td>
       <td><input type="text" name="cambios_minimos" value="<?=$info['cambios_minimos'] ?>"></td>
     </tr>
      <tr>
       <td>Tiempo de ajuste predeterminado</td>
       <td><input type="text" name="ajuste" value="<?=$def['ajuste_standard']/60 ?>"></td>
     </tr>
     <tr>
       <td>Piezas por hora predeterminadas</td>
       <td><input type="text" name="piezas" value="<?=$def['piezas_por_hora'] ?>"></td>
     </tr>
   </table>

<?php 
} elseif ($target=='alerts') {
	$query = "SELECT * FROM opciones WHERE tipo_opcion='".$_POST['tipo']."' AND id_proceso=".$process." ORDER BY id_opcion ASC";
	$resss     = $mysqli->query($query);

	?>
<div class="tables">
<input type="hidden" name="section" value="alerts">
<input type="hidden" name="type_alert" value="<?=$_POST['tipo'] ?>">
<input type="hidden" name="proceso" value="<?=$info['id_proceso'] ?>">
<input type="hidden" name="processes[<?=$process ?>]" value="<?=$process ?>">
<?php if ($resss->num_rows>0) {
  
 ?>

<?php } ?>    
<div class="datagrid option-table">
<table  class="order-table table hoverable lightable">
<thead>
<tr>
    
    
    <th style=""> <?=($_POST['tipo']=='alerta_ajuste')? 'Opciones de alerta en Ajuste':(($_POST['tipo']=='alerta_tiro')? 'Opciones de alerta en Tiro':(($_POST['tipo']=='encuesta_lento')?'¿Lo hice mas lento?':'¿Lo hice bien a la primera?')) ?></th>
    <th style="width:145px"></th>
    

  </tr>

  </thead><tbody id="table-<?=$process ?>">
  <?php while ( $row=mysqli_fetch_assoc($resss)) { ?>
    
    <tr class="options-<?=$process ?>">
    <td><?=$row['valor'] ?></td>
    
    <td><a href="#" class="quit-option">Quitar</a></td> 
    <input type="hidden" name="options-<?=$process ?>[]" value="<?=$row['valor'] ?>"> 
   </tr>
  
  <?php } 
if ($resss->num_rows==0){
  echo "<tr ><td colspan='2' style='padding:20px;'>NO SE HAN AGREGADO OPCIONES PARA ESTE PROCESO</td></tr> ";
}

  ?>
  </tbody>
  <tbody>
  <tr>
    <td></td>
    <td><button type="button" class="add add-dinam" data-id="<?=$process ?>" onclick="return false;" data-empty="<?=($resss->num_rows==0)? 'true':'false' ?>">+ Agregar opcion</button></td>
  </tr>
  </tbody>
</table>

<br>
</div>

</div>
<script>
	 $(".add-dinam").click(function () {
  var id=jQuery214(this).data('id');
    
  var empty=jQuery214(this).data('empty');
  console.log('empty '+empty);
  if (empty==true) {
    console.log('si esta empty');
    $('#table-'+id).append('<input type="hidden" name="processes['+id+']" value="'+id+'" >');
    jQuery214(this).data('empty', 'false');
  }
  
   
    var tr='<tr class="options-'+id+'"><td><input type="text"  required name="options-'+id+'[]" placeholder="Escribe una opcion.."></td><td><a href="#" class="quit-option">Eliminar</a></td></tr>'; 
    

    $('#table-'+id).append(tr);

});
	 $(".quit-option").live("click", function() {
    $(this).closest("tr").remove();
});
</script>
	
<?php }elseif ($target=='operarios') {

	?>



<?php	
} 
}else{

}

?>






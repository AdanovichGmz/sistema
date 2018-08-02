 <?php
require('../saves/conexion.php');

$processes=$_POST['processes'];
$tipo_opcion=$_POST['tipo_opcion'];
$errors=0;

foreach ($processes as $key => $process) {
  $mysqli->query("DELETE FROM opciones WHERE tipo_opcion='$tipo_opcion' AND id_proceso=".$process);
  if (isset($_POST['options-'.$process])) {
   
  $options=$_POST['options-'.$process];
  foreach ($options as $key2 => $option) {

   $query="INSERT INTO `opciones`(`id_opcion`, `valor`, `tipo_opcion`, `id_proceso`) VALUES (NULL, '$option', '$tipo_opcion', $process)";
   $inserted=$mysqli->query($query);
   if (!$inserted) {
    $errors++;
     //printf($mysqli->error);
   }

  }
}
  
}
if ($errors>0) {
  $datas='data-clas="fail" data-type="Error:" data-message="No pudimos guardar algunos datos" ';
}else{
  $datas='data-clas="successs" data-type="Exito:" data-message="Datos guardados correctamente!" ';
}

$target=$_POST['tipo_opcion'];
$procesos=$mysqli->query("SELECT * FROM procesos_catalogo ORDER BY nombre_proceso ASC");
?>
<input id="tipo" type="hidden" name="tipo_opcion" value="<?=$target ?>" <?=$datas; ?> >
<?php 
while ($pr=mysqli_fetch_assoc($procesos)) {
$query = "SELECT * FROM opciones WHERE tipo_opcion='$target' AND id_proceso=".$pr['id_proceso']." ORDER BY id_opcion ASC";
$resss     = $mysqli->query($query);
?>
<div class="tables">

<?php if ($resss->num_rows>0) {
  
 ?>

 <input type="hidden" name="processes[<?=$pr['id_proceso'] ?>]" value="<?=$pr['id_proceso'] ?>">
<?php } ?>    
<div class="datagrid option-table">
<table  class="order-table table hoverable lightable">
<thead>
<tr>
    
    
    <th style="text-align: left;"> <?=$pr['nombre_proceso'] ?></th>
    <th style="width:145px"></th>
    

  </tr>

  </thead><tbody id="table-<?=$pr['id_proceso'] ?>">
  <?php while ( $row=mysqli_fetch_assoc($resss)) { ?>
    
    <tr class="options-<?=$pr['id_proceso'] ?>">
    <td><?=$row['valor'] ?></td>
    
    <td><a href="#" class="quit-option">Quitar</a></td> 
    <input type="hidden" name="options-<?=$pr['id_proceso'] ?>[]" value="<?=$row['valor'] ?>"> 
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
    <td><button type="button" class="add add-dinam" data-id="<?=$pr['id_proceso'] ?>" onclick="return false;" data-empty="<?=($resss->num_rows==0)? 'true':'false' ?>">+ Agregar opcion</button></td>
  </tr>
  </tbody>
  
  
  
</table>


<br>


</div>

</div>

<?php
} ?>
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







<?php

require('../saves/conexion.php');
  $query="SELECT * FROM procesos_catalogo ORDER BY nombre_proceso ASC";
  $process=$mysqli->query($query);

?>

<div class="close" id="closer"></div>
<div class="new-user-form">
<form id="new-user-form" action="saveOperator.php" method="post"  enctype="multipart/form-data">
<div class="n-u-left">
<div class="title">Informacion del operario</div>
  <table>
  <tr>
    <td>
      Nombre:
    </td>
    <td>
      <input type="text" name="nombre">
    </td>
  </tr>
 
  <tr>
    <td>
      Apellido:
    </td>
    <td>
      <input type="text" name="apellido">
    </td>
  </tr>
  <tr>
    <td>
      Nombre de Usuario:
    </td>
    <td>
      <input type="text" name="usuario">
    </td>
  </tr>
  <tr>
    <td>
      Contraseña:
    </td>
    <td>
      <input type="text" name="password">
    </td>
  </tr>
  <tr>
    <td>
      Sueldo:
    </td>
    <td>
      <input type="text" name="sueldo">
    </td>
  </tr>
  <tr>
    <td>
      Remuneracion por:
    </td>
    <td>
      <select name="remuneracion">
        <option value="tiros">Tiros</option>
        <option value="cambios">Cambios</option>
      </select>
    </td>
  </tr>
  <tr>
    <td>
      Miembro de una mesa:
    </td>
    <td>
      <select name="miembro">
        <option value="true">Si</option>
        <option value="false">No</option>
      </select>
    </td>
  </tr>
  <tr>
    <td>
      Foto:
    </td>
    <td>
      <input type="file" name="foto">
    </td>
  </tr>
</table>
</div>
<div class="n-u-right">
<div class="title">Procesos que realizará</div>
<div class="process-container">
  <table id="process-table" class="order-table hoverable lightable">
  <?php while ($row=mysqli_fetch_assoc($process)) { ?>
  <tr>
    <td><input type="checkbox" name="procesos[]" value="<?=$row['id_proceso'] ?>"></td>
    <td><?=$row['nombre_proceso'] ?></td>
  </tr>
<?php  } ?>
</table>
</div>

  
</div>
<div style="width: 100%; margin: 10px auto;padding: 20px; text-align: center;border-top: solid 1px #ccc;">
<button class="btn btn-primary" id="new-user-submit">GUARDAR OPERARIO</button>
  
</div>
  </form>
</div>
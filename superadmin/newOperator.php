<?php

require('../saves/conexion.php');
  $query="SELECT * FROM procesos_catalogo ORDER BY nombre_proceso ASC";
  $process=$mysqli->query($query);

?>

<style>
#t-procesos{
  width:100%;
  table-layout: fixed;
  border-collapse: collapse;
}
.tbl-header{
  background:#fff;
 }
.tbl-content{
  height:300px;
  overflow-x:auto;
  background: #fff;
  margin-top: 0px;
  
}
#t-procesos th{
  padding: 8px;
  text-align: left;
  font-weight: 500;
  font-size: 12px;
  color: #00927B;
  text-transform: uppercase;
}
#t-procesos td{
  padding: 8px;
  text-align: left;
  vertical-align:middle;
  font-weight: 300;
  font-size: 12px;
  border-collapse: collapse;
  border: 1px solid #E6E8E7;
  color: #00927B;
}
#t-procesos th:first-child{
  text-align: center;
}
#t-procesos td:first-child{
  text-align: center;
}
#t-procesos  tbody tr:nth-child(odd) {
    background: #F9F9F9;
}

.process-controls{
  width: 100%;
  padding: 10px;
  text-align: right;
  background: #fff;
  border-bottom: solid 1px #ccc;
  margin-top: 18px;
}

#p-filter{
      width: 250px;
    padding: 5px;
    border-radius: 3px;
    border: solid 1px #ccc;
}

::-webkit-scrollbar {
    width: 6px;
} 
::-webkit-scrollbar-track {
    -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3); 
} 
::-webkit-scrollbar-thumb {
    -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3); 
}
</style>

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
      <input type="number" name="sueldo">
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
<div class="process-header"></div>






<div class="process-controls">
  <input type="search" id="p-filter" class="p-filter" data-table="p-table" placeholder="Filtrar proceso">
</div>
<div class="tbl-header">
    <table id="t-procesos" cellpadding="0" cellspacing="0" border="0">
      <thead>
        <tr>
          <th><input type="checkbox" id="check-all"><label for="check-all"> Seleccionar todos</label> </th>
          <th>Proceso</th>
          <th>Area</th>
          
        </tr>
      </thead>
    </table>
  </div>
  <div class="tbl-content">
    <table id="t-procesos" class="p-table" cellpadding="0" cellspacing="0" border="0">
      <tbody id="b-procesos">
       <?php while ($row=mysqli_fetch_assoc($process)) { ?>
  <tr>
    <td><input type="checkbox" name="procesos[]" value="<?=$row['id_proceso'] ?>"></td>
    <td><?=$row['nombre_proceso'] ?></td>
    <td>Encuaderna</td>
  </tr>
<?php  } ?>
       
      </tbody>
    </table>
  </div>







  
</div>
<div style="width: 100%; margin: 10px auto;padding: 20px; text-align: center;border-top: solid 1px #ccc;">
<button class="btn btn-primary" id="new-user-submit">GUARDAR OPERARIO</button>
  
</div>
  </form>
</div>
<script>
  jQuery214(window).on("load resize ", function() {
  var scrollWidth = $('.tbl-content').width() - $('.tbl-content table').width();
  jQuery214('.tbl-header').css({'padding-right':scrollWidth});
}).resize();



</script>
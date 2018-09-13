 <?php  
require('../saves/conexion.php');
  
$query="SELECT * FROM usuarios WHERE id=".$_POST['id_user'];


$userData=mysqli_fetch_assoc($mysqli->query($query));

$getStations=$mysqli->query("SELECT * FROM usuarios_estaciones WHERE id_usuario=".$userData['id']);

$procesos=$mysqli->query("SELECT * FROM procesos_catalogo");

?>
<style>
  .edit-user{
    width: 100%;
    position: relative;

  }
  .user-control{
    display: inline-block;
    vertical-align: top;
    width: 20%;
    height: 550px;
  }
.user-content{
  display: inline-block;
  vertical-align: top;
  width: 80%;
  height: 550px;
}
.left-bar{
  border:1px solid #E6E8E7
  border-radius: 4px;
  background:#fff;
  width: 95%;
  height:100%;
  border-radius: 4px;
  vertical-align: top;
  overflow:hidden;
}
.right-bar{
  border:1px solid #E6E8E7
  width: 100%;
  background:#fff;
  border-radius: 4px;
  border-radius: 4px;
  height: 100%;
  overflow-y: auto;
}
.user-photo{
  width: 100%;
  position:relative;
  cursor: pointer;

  
}
.user-photo img{
  width: 100%;
}

.left-menu{
  text-align: left;
  padding:12px 0;
  color: #00927B;
  cursor: pointer;
  border-bottom: 1px solid #E6E8E7;
  text-indent: 12px;
}
.left-menu:hover{
  color:#fff;
  background:#05BDE3;
}
.sections{
  width: 97%;
  height: 100%;
  margin:0 auto;
  display: none;
}
.selected2{
  background:#FFF8C4;
  color:#6A6867;
}

.show{
  display: block;
}
.form-table{
  width: 600px;
  margin: 10px auto

}
.form-table td{
  
  text-align: right;
}
.title-field{
  padding: 10px 25px;
 width: 30%; 
}
.input-field{
  padding: 10px 5px;
 
  text-align: left!important;
  width: 70%;
}
.input-field input{
  width: 100%;
  padding: 8px;
  border-top: none;
  border-left: none;
  border-right: none;
   border-bottom: solid 1px #ccc;
}
.section-title{
  width: 95%;
  text-align: left;
  text-indent: 10px;
  font-size: 20px;
  color:#4D4D4D;
  border-bottom: solid 1px #ccc;
  padding:10px 0;
  margin:0 auto; 
}
.change-photo{
  width: 50%;
  border:dashed 1px #fff;
  border-radius: 3px;
  padding: 10px;
  position: absolute;
  top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        color:#fff;
        text-align: center;
        z-index: 999999;
        display: none;
}
.backdrop-photo{
  display: none;
  background:rgb(0,0,0);
  position: absolute;
  opacity: 0.4;
  width: 100%;
  height: 100%;
  top: 0
}
.table-button{
  width: 100%;
  padding: 10px 0;
  border-radius: 2px;
  border:none;
  color: #fff;
  moz-box-shadow: 0px 0px 3px #444444;
    -webkit-box-shadow: 0px 0px 3px #444444;
    box-shadow: 0px 0px 3px #444444;

}
#personal-info{
  display: none;
}
.red{
background:#4CAF50;
}
.green{
background:#E51C23;
}
.right-action{
  float: right;
  color:#05BDE3;
  width: 200px;
 padding: 10px;
  font-size: 14px;
  cursor: pointer;
  vertical-align: middle;

}
#sending,#sending2{
  width: 100%;
  height: 449px;
  position: relative;
  display: none;
}
#sending img,#sending2 img{
  width: 130px;
  position: absolute;
   top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
}

.right-action:hover{
  text-decoration: underline;
}
.image-loader{
  display: none;
  width: 80px;
  height: 60px;
  position: absolute;
  background-image: url(../images/loaderw.gif);
  background-size: contain;
  background-repeat: no-repeat;
  top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
}
.station select{
  width: 90%!important;
 
}

#add-dinam{
  background:#FB9731;
  border-radius: 2px;
  border:solid 1px #ED6502;
  color: #fff;
  font-weight: bold;
  font-size: 12px;
  padding: 8px;
}
.stations a{
  background:#F37A42;
  border-radius: 2px;
  border:solid 1px #D24403;
  color: #fff;
  font-weight: bold;
  font-size: 12px;
  padding: 8px;
  margin: 5px;
}
.stations a:hover{
  text-decoration: none;

}
#stations-cont{
  width: 80%;
  text-align: left;
  display: inline-block;
}

</style>
<div class="close" id="closer"></div>

<div class="edit-user">
<div class="user-control">
<div class="left-bar">
  <div class="user-photo">
    <img src="../<?=(!empty($userData['foto']))? $userData['foto'] : 'images/default.jpg' ?>">
    <div class="change-photo"> Cambiar foto
    
    </div>
    <div class="image-loader"></div>
    <div class="backdrop-photo"></div>
    <form  id="photo-form">
      <input type="file" id="foto" name="foto" style="display: none;">
      <input type="hidden" name="target" value="photo">
    </form>
    
  </div>

  <div class="left-menu selected2" data-target="perfil">Perfil</div>
  <div class="left-menu" data-target="estaciones">Estaciones y Procesos</div>
  <div class="left-menu" data-target="estadisticas">Estadisticas</div>
</div>
</div><div class="user-content">

  <div class="right-bar">
    <div class="sections show" id="perfil">
     <div class="section-title">Informacion del operario</div>
    <form method="POST" id="user-info">
   
      <table class="form-table">
        <tr>
          <td class="title-field">Nombre:</td>
          <td class="input-field" style="width:170px"><input type="text" class="changed" name="nombre" value="<?=$userData['logged_in'] ?>"><input type="hidden" id="iduser" name="iduser" value="<?=$userData['id'] ?>"><input type="hidden" name="target" value="info"></td>
          <td class="title-field">Apellido:</td>
          <td class="input-field" style="width:170px"><input type="text" class="changed" name="apellido" value="<?=$userData['apellido'] ?>"></td>
        </tr>
        
        <tr>
          <td class="title-field">Usuario:</td>
          <td class="input-field" colspan="3"><input type="text" class="changed" name="usuario" value="<?=$userData['usuario'] ?>"></td>
        </tr>
        <tr>
          <td class="title-field">Contraseña:</td>
          <td class="input-field" colspan="3"><input type="text" class="changed" name="password" value="<?=$userData['password'] ?>"></td>
        </tr>
        <!--
        <tr>
          <td class="title-field">Hora de entrada:</td>
          <td class="input-field" colspan="3"><input type="time" class="changed" name="entrada" value=""></td>
        </tr>
        <tr>
          <td class="title-field">Hora de salida:</td>
          <td class="input-field" colspan="3"><input type="time" class="changed" name="salida" value=""></td>
        </tr> -->
        <tr>
          <td class="title-field">Sueldo: $</td>
          <td class="input-field" colspan="3"><input type="text" class="changed" name="sueldo" value="<?=$userData['sueldo'] ?>"></td>
        </tr>
        <tr>
          <td class="title-field">Remuneracion por:</td>
          <td class="input-field" colspan="3"><select  class="changed" name="remun">
          <option <?=($userData['remuneracion']=='tiros')? 'selected':'' ?> value="tiros">Tiros</option>
          <option <?=($userData['remuneracion']=='cambios')? 'selected':'' ?> value="cambios">Cambios</option>
          </select></td>
        </tr>
        <?php if ($userData['remuneracion']=='cambios') {
         
         ?>

        <tr style="display: none;">
          <td class="title-field">Cambios minimos:</td>
          <td class="input-field" colspan="3"><input type="text" class="changed" name="cambios_minimos" value="<?=$userData['cambios_minimos'] ?>"></td>
        </tr>
         <tr style="display: none;">
          <td class="title-field">Costo por cambio: $</td>
          <td class="input-field" colspan="3"><input type="text" class="changed" name="precio_cambio" value="<?=$userData['precio_cambio'] ?>"></td>
        </tr>

        <?php } ?>
      </table>      
      <table style="width: 400px;margin: 15px auto;text-align: center;">
        <tr id="personal-info">
          <td style="padding: 10px;"><button type="submit" class="table-button red" name="" >Guardar</button></td>
          <td style="padding: 10px;"><button type="button" class="table-button green" name="" >Cancelar</button></td>
          </tr>
      </table>
      </form>
      <div id="sending">
        <img src="../images/loader.gif">
      </div>
    </div>
    <div class="sections " id="estaciones">
    <form method="POST" id="est-proc">
    <input type="hidden" name="target" value="procesos">

    <div class="section-title">Estaciones y Procesos
    <div class="right-action">+ Asignar Nueva Estación</div>
    </div>
               <?php while ($row=mysqli_fetch_assoc($getStations)) { 
            $estation=mysqli_fetch_assoc($mysqli->query("SELECT * FROM estaciones WHERE id_estacion=".$row['id_estacion']));
            $getProcess=$mysqli->query("SELECT * FROM estaciones_procesos ep INNER JOIN procesos_catalogo pc ON ep.id_proceso=pc.id_proceso WHERE id_estacion=".$row['id_estacion']." ORDER BY nombre_proceso ASC");

            ?>
            <div id="stations-cont">
            <div class="station"> 
            <div class="station-title"><?=$estation['nombre_estacion'] ?></div>
              <input type="hidden" value="<?=$estation['id_estacion'] ?>" name="estaciones[]">
        <table class="stations">
        <thead>
        <tr>
          <th colspan="2">Procesos</th>
          </tr>
          </thead>
          <tbody id="proceses">
        <?php while ($process=mysqli_fetch_assoc($getProcess)) { ?>
          
          <tr>
            <td><?=$process['nombre_proceso'] ?><input type="hidden" value="<?=$process['id_proceso'] ?>" name="procesos-<?=$estation['id_estacion'] ?>[]"></td>            
            <td style="text-align: right;"><a href="#" class="remove-row">Eliminar</a></td>
          </tr>
        <?php } ?>
        </tbody>
        <tbody>
          <tr>
            <td></td>
            
            <td style="text-align: right;"><button id="add-dinam" data-station="<?=$estation['id_estacion'] ?>" type="button">+ Agregar proceso</button></td>
          </tr>
        </tbody>
        </table>
        
        </div>
</div>
        <?php } ?>
<div id="station-controls">
  <table>
    <tr>
      <td>
        <button type="submit" class="table-button red" name="">Guardar Cambios</button>
      </td>
    </tr>
    <tr>
      <td>
        <button type="button" class="table-button green" name="">Cancelar</button>
      </td>
    </tr>
  </table>
</div>

       </form>
       <div id="sending2">
        <img src="../images/loader.gif">
      </div>
      
    </div>
    <div class="sections " id="estadisticas">
      <div class="section-title">Estadisticas del operario</div>
    </div>
    

  </div>
  
</div>
  
</div> 

<script>

   $(".left-menu").click(function () {
    var target=$(this).attr('data-target');
    $('.show').removeClass('show');
    $('.selected2').removeClass('selected2');
    $('#'+target).addClass('show');
    $(this).addClass('selected2');


});

$(".user-photo").hover(function () {
   
    $('.backdrop-photo').show();
    $('.change-photo').show();
  
});
$(".user-photo").mouseleave(function () {
   
    $('.backdrop-photo').hide();
    $('.change-photo').hide();
   


});

$(".changed").keyup(function () {   
    $('#personal-info').show();
    

});
$(".changed").change(function () {   
    $('#personal-info').show();
    

});

$(".change-photo").click(function () {   
    $('#foto').click();

});

$("#foto").change(function () {   
   $('#photo-form').submit();

});


$("#procesmenu").change(function () { 

   console.log($(this).val());

});

$('#photo-form').submit(function (e) {
  
  $('.image-loader').show();
   $('.backdrop-photo').show();
    e.preventDefault();
    var iduser=$('#iduser').val();
  $.ajax({
        url: "editOperator.php",
        type: "POST",
       data:  $(this).serializeArray(),
        
        
        
        success: function(data){
        console.log(data);
          /*
        $.ajax({
        url: "operatorInfo.php",
        type: "POST",
        data:{id_user:iduser},
        success: function(data){
        $('#operator-info').html(data);
        }        
       });
        */
        }        
       });  

});

$('#user-info').submit(function (e) {
  $(this).hide();
  $('#sending').show();
    e.preventDefault();
    var iduser=$('#iduser').val();
  $.ajax({
        url: "editOperator.php",
        type: "POST",
        data:$(this).serialize(),
        success: function(data){
          console.log(data);
        $.ajax({
        url: "operatorInfo.php",
        type: "POST",
        data:{id_user:iduser},
        success: function(data){
        $('#operator-info').html(data);
        }        
       });
        }        
       });  

});

$('#est-proc').submit(function (e) {
  $(this).hide();
  console.log('picandole');
  $('#sending2').show();
    e.preventDefault();
    var iduser=$('#iduser').val();
  $.ajax({
        url: "editOperator.php",
        type: "POST",
        data:$(this).serialize(),
        success: function(data){
          console.log('contesto ajax');
           
          
        $.ajax({
        
        url: "operatorInfo.php",
        type: "POST",
        data:{id_user:iduser},
        success: function(data){
        $('#operator-info').html(data);
        $('div[data-target="estaciones"]').click();
        } 

       }); 
        }        
       });  

});

$("#add-dinam").click(function () {
  var station=$(this).attr('data-station');
    var options='<option selected disabled>Elige un proceso</option><?php while ($row4=mysqli_fetch_assoc($procesos)) { 
echo '<option class="new-proces" value="'.$row4['id_proceso'].'">'.$row4['nombre_proceso'].'</option>';
     }; ?>';
   
    var row='<tr><td><select  required  id="procesmenu" name="procesos-'+station+'[]">'+options+'</select></td><td style="text-align:right;"><a href="#" class="remove-row">Eliminar</a></td></tr>' 

    $('#proceses').append(row);

});


 
 $(".remove-row").live("click", function() {
    $(this).closest("tr").remove();
});

</script>
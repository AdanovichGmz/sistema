<?php
date_default_timezone_set("America/Mexico_City");
require('../saves/conexion.php');

session_start();
$querysesion="SELECT * FROM sesiones WHERE operador=".$_POST['user'];
$getsesion=$mysqli->query($querysesion);
$sesion=mysqli_fetch_assoc($getsesion);

if ($getsesion->num_rows==0) {
  
?>
<div class="modal-body">
  <div class="option">
    <div>Cartera</div>
  </div>
   <div class="option">
    <div>Möhleskhines</div>
  </div>
   <div class="option">
    <div>Perforadora</div>
  </div>
   <div class="option">
    <div>Prensa de cajon</div>
  </div>
   <div class="option">
    <div>Pegado de esquinas</div>
  </div>
   <div class="option">
    <div>Ranuradora</div>
  </div>
</div>

<?php

}else{



if ($sesion['actividad_actual']=='tiro') {
 
?>



<?php
}elseif ($sesion['actividad_actual']=='alerta') { ?>




<?php
}elseif ($sesion['actividad_actual']=='comida') { ?>




<?php
}elseif ($sesion['actividad_actual']=='ajuste') { ?>

<div class="modal-body">
  <div class="option">
    <div>Cartera</div>
  </div>
   <div class="option">
    <div>Möhleskhines</div>
  </div>
   <div class="option">
    <div>Perforadora</div>
  </div>
   <div class="option">
    <div>Prensa de cajon</div>
  </div>
   <div class="option">
    <div>Pegado de esquinas</div>
  </div>
   <div class="option">
    <div>Ranuradora</div>
  </div>
</div>

<?php
}
}
?>
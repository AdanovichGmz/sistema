<div id="buscador2"></div> 
       <div class="row ">
                <legend style="font-size:18pt; font-family: 'monse-bold';"><div class="odtsearch">
                <div id="" style="text-transform: uppercase;line-height: 65px;width: 185px;position: absolute;left: 15px;top: 3px;"   class="rect-button-small radio-menu-small2 face" onclick="addOrder();getKeys('virtualodt','pedido')">
                          REGISTRAR 
                          <p class="suborder" >ORDEN</p>
                </div>
  <input type="text" id="getodt" name="getodt" readonly="true" onclick="getKeys(this.id,'pedido')" onkeyup="gatODT()" placeholder="Buscar ODT"> 
</div><div id="close-down"  class="square-button-micro red abajo ">
                          <img src="images/ex.png">
                        </div></legend>
                        <p id="elementerror" style="display: none;">ELIGE UN ELEMENTO PARA CONTINUAR</p>
                        <div id="odtresult">
                          <div style="width: 95%; margin:0 auto; position: relative;">
                
                   <div class="form-group" id="tareasdiv">
                  <div class="button-panel-small2" >
                  <form id="tareas" action="opp.php" method="post" >
                  <input type="hidden" name="machine" value="<?=$_SESSION['stationName']; ?>">
                  <input type="hidden" name="init" value="false">
                 
                  <?php
                    	
                    if ($process_model->odtExist()>0) {
                    	$odts=$process_model->getWorkingOdts();


                    	foreach ($odts as $odt) {
                    	$element=$process_model->getElementById($odt->producto);

                    	?>

                    	<div id="<?=$i ?>" data-name="<?=$element->nombre_elemento ?>" data-element="<?=$element->id_elemento ?>" style="text-transform: uppercase;"  class="rect-button-small radio-menu-small face   <?=($odt->status=='actual')? 'face-osc': '' ; ?>" onclick="showLoad();">
                        <input type="checkbox" <?=($odt->status=='actual')? 'checked': '' ; ?> name="chosen" value="<?=$odt->id_proceso; ?>">
                        <input type="hidden" name="products[<?=$odt->id_proceso ?>]" value="<?=$odt->producto; ?>">
                        <input type="hidden" name="odetes[<?=$odt->id_proceso ?>]" value="<?=$odt->num_odt; ?>">
                       <input type="hidden" name="ordenes[<?=$odt->id_proceso ?>]"  value="<?=$odt->id_orden ?>">
                       <input type="hidden" name="procesos[<?=$odt->id_proceso ?>]"  value="<?=$odt->id_proceso ?>">



                          <p class="elem" <?=($element->nombre_elemento=='Desconocido')? 'style="font-size:15px;"':''; ?> ><?php echo  trim($element->nombre_elemento); ?><br><span><?= $odt->reproceso?></span></p>
                          <p class="product" style="display: none;"><?= $odt->num_odt ?></p>
                        </div>

                    	<?php

                    	}
                    	
                    }else{
				echo '<p style="font-size:18pt; color:#E9573E;font-family: monse-bold; text-align:center;">NO HAS SELECCIONADO UNA ORDEN<p>';
                    }
                    
                      
                      
                      
                      
                     ?>
                         </form>
                        </div> 
                </div>
                </div>
                        </div>
                
                   <div id="resultaado"></div> 
                <div class="form-group">
                <div id="resultaado"></div>
                 
                </div>
   </div>
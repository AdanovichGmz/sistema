<div id="panelbottom">
       
       <div>
       <div class="odt-head">
       	<div class="rect-button" id="create-odt"><span>REGISTRAR ORDEN</span></div>
       	<div class="odt-search" ><input type="text" id="odt" name="odt" onclick="getKeys(this.id,'odt')" onkeyup="searchOrder(this.value)" placeholder="Buscar ODT" readonly></div>
       	<div id="close-down"  class="square-button-micro red " >
           <img src="<?=URL ?>/public/images/ex.png">
        </div>
       </div>
       	<div class="odt-body">
       	<form method="post" id="virtual-odt-form">
       	<input type="hidden" id="elem-id" name="elem-id">
       	<input type="hidden" name="is_virtual" value="true">
       		<table class="create-odt-form">
       			<td>ODT:</td>
       			<td><input type="text" name="virtualodt" id="virtualodt" onclick="getKeys(this.id,'virtualodt')" readonly></td>
       			<td>Elemento:</td>
       			<td><input type="text" name="virtualelem" id="virtualelem" readonly></td>
       		</table>
       		<input type="submit" id="save-odt" style="display: none;" name="send-odt">
       		</form>
       	</div>
       </div>
       <div id="odt-results">
             <?=$tasksInProcess; ?>
       </div>
</div>
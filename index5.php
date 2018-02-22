<!DOCTYPE html>
<html>
<head>
<title>TIRAJE</title>
 <link href="css/tiro.css" rel="stylesheet" />
</head>
<body>

<ul id="topbar">
  <li><span>Arturo | Serigrafia</span></li>
  <li></li>
  <li></li>
  <li></li>
</ul>
<div class="main-container">
  <div class="section">
  <div class="graphics">
   </div><div class="graphics g-center">
    </div><div class="graphics"></div>
</div>
<div class="section2">
  <div class="clock-buttons">
  <div id="tirajeTime">
          <div id="timersmall"><span class="valuesTiraje">00:53:55</span></div>
          </div>
  <div class="button-panel" >
                        <a href="#" onclick="endSesion()"> <img src="" href="#" class="img-responsive">
                        <div class="square-button-h red">
                          <img src="images/sal.png">
                        </div></a><div class="square-button-h middle green stop eatpanel goeat" onclick="saveoperComida()">
                          <img src="images/dinner2.png">
                        </div><div class="square-button-h blue " id="saving">
                          <img src="images/saving.png">
                        </div><div class="square-button-h middle yellow goalert" onclick="derecha(); saveoperAlert();">
                          <img src="images/warning.png">
                        </div><div class="square-button-h prple" onclick="pauseConfirm();">
                          <img src="images/cantir.png">
                        </div>
                      
                        
                        </div>  
  </div><div class="inputs">
    <table id="former">
  <input type="hidden" id="qty" name="qty" value="single">
  <tbody><tr>
    <td class="title-form">CANTIDAD DE PEDIDO</td>
    <td class="title-form">BUENOS</td>
  </tr>
  <tr>
        <td class=""><input type="number" class="getkeyboard inactive" id="pedido" name="pedido" value="150" readonly="" onclick="getKeys(this.id,'pedido')" onkeyup="opera();"></td>
   
   
   <td class=""><input id="buenos" class="getkeyboard inactive" onclick="getKeys(this.id,'buenos')" name="buenos" type="number" onkeyup="opera();" readonly="" style="margin-right: 10px;" required="required"></td>
    
    
  </tr>
  <tr>
    <td class="title-form">CANTIDAD RECIBIDA</td>
    <td class="title-form">PIEZAS DE AJUSTE</td>
  </tr>
  <tr>
    <td class=""> <input type="number" id="cantidad" readonly="" onclick="getKeys(this.id,'cantidad')" class="getkeyboard inactive" name="cantidad" value="" onkeyup="opera();">
    </td>
    <td class=""><input id="piezas-ajuste" readonly="" class="getkeyboard inactive" name="piezas-ajuste" type="number" onclick="getKeys(this.id,'piezas-ajuste')" style="margin-right: 10px;" onkeyup="GetDefectos()"> </td>
  </tr>
  <tr>
    <td class="title-form">MERMA</td>
    <td class="title-form">DEFECTOS</td>
  </tr>
  <tr>
    <td class=""><input class="inactive" value="" readonly="" id="merma-entregada" onclick="getKeys(this.id,'merma-entregada')" name="merma-entregada" type="number" style="margin-right: 10px;"></td>
      <td class=""><input id="defectos" onclick="getKeys(this.id,'defectos')" readonly="" class="getkeyboard inactive" name="defectos" type="number" value=""></td>
  </tr>
</tbody></table>
  </div>
</div>

</div>

</body>
</html>
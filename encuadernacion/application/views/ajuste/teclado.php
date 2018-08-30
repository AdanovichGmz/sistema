
<p>ya iniciaste sesion</p>
<?php
echo "<pre>";
print_r($_SESSION);
echo "</pre>";
?>
<div class="big-button-container">
<div id="parts" class="big-button purple abajo">
                          <img src="<?= URL; ?>public/img/elegir.png">
</div>
<div id="stop" class="big-button blue " onclick="saveAjusteSerigrafia()">
                          <img src="<?= URL; ?>public/img/guard.png">
</div>
<div class="big-button green stop eatpanel goeat" onclick="saveoperComida();">
                          <img src="<?= URL; ?>public/img/dinner2.png">
</div>
<div class="big-button yellow  goalert" onclick="derecha();saveoperAlert();">
                          <img src="<?= URL; ?>public/img/alerts.png">
</div>

  
</div>
<div id="timer" data-inicio="0" data-estandar="1800" data-perro="00:00:00"><span class="values">00:00:00</span></div>
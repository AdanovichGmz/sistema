<?php
 $dStart = new DateTime('25-01-2018');
   $dEnd  = new DateTime('30-01-2018');
   $dDiff = $dStart->diff($dEnd);
   //echo $dDiff->format('%R');
   echo $dDiff->days.' dias de diferencia entre 25-01-2018 y 30-01-2018';

?>
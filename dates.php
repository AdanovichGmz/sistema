<?php
 $dStart = new DateTime('03-02-2018');
   $dEnd  = new DateTime(date("d-m-Y"));
   $dDiff = $dStart->diff($dEnd);
   //echo $dDiff->format('%R');
   echo $dDiff->days.' dias de diferencia entre 03-02-2018 y '.date("d-m-Y");

?>
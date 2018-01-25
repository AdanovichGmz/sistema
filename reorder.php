<?php 


 
function reorder($table,$orderfield,$idfield,$id=null,$pos=null,$newpos=null) {
  require('saves/conexion.php');
    if($pos!=$newpos) {
        if($newpos>$pos) {
            $mysqli->query("UPDATE ".$table." SET ".$orderfield."=".$orderfield."-1 WHERE ".$orderfield."<= '".$newpos."' AND $idfield<>'".$id."'");
            $mysqli->query("UPDATE ".$table." SET ".$orderfield."=".$orderfield."+1 WHERE ".$orderfield."> '".$newpos."' AND $idfield<>'".$id."'");
        } else {
            $mysqli->query("UPDATE ".$table." SET ".$orderfield."=".$orderfield."-1 WHERE ".$orderfield."< '".$newpos."' AND $idfield<>'".$id."'");
            $mysqli->query("UPDATE ".$table." SET ".$orderfield."=".$orderfield."+1 WHERE ".$orderfield.">= '".$newpos."' AND $idfield<>'".$id."'");
        }
    }
    if($pos!=$newpos || ($pos==null && $newpos == null && $id==null) ) {
        $rs = $mysqli->query("SELECT $orderfield,$idfield FROM ".$table." ORDER BY ".$orderfield." ASC");
        $p = 0;
        if (!$rs) {
          printf($mysqli->error);
        }
        
        while($r=mysqli_fetch_array($rs)) {

            $p++;
            $mysqli->query("UPDATE ".$table." SET ".$orderfield."='".$p."' WHERE ".$idfield."= '".$r[$idfield]."'");
        }
    }
}

$reor=reorder("personal_process","orden_display","id_pp",5984,5,1);
if ($reor) {
  echo "ya se hizo";
}else{
  echo "no se pudo mi chavo";
}
?>


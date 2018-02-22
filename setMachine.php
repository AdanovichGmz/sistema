<?php
session_start();
if ($_POST['option']=='Suaje') {
 $_SESSION['machineID']=22;
        $_SESSION['machineName']='Suaje2';
}elseif ($_POST['option']=='LetterPress') {
  $_SESSION['machineID']=13;
        $_SESSION['machineName']='LetterPress';
}



?>
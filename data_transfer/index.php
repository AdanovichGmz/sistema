<?php

date_default_timezone_set("America/Mexico_City"); 
$mysqli=new mysqli("localhost","root","","sistema"); 
mysqli_set_charset($mysqli,"utf8");
if(mysqli_connect_errno()){
    echo 'Conexion Fallida : ', mysqli_connect_error();
    exit();
}

$getData="SELECT t.* FROM"







?>
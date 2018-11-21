<?php
date_default_timezone_set("America/Mexico_City"); 

error_reporting(E_ALL);
ini_set("display_errors", 1);

define('URL', 'http://192.168.1.202/sistema/encuadernacion/');
define('BASE_URL', 'http://192.168.1.202/sistema/');
define('DB_TYPE', 'mysql');
define('DB_HOST', 'localhost');
define('DB_NAME', 'produccion');
define('DB_USER', 'root');
//define('DB_PASS', '');
define('DB_PASS', 'historias1');
define('TODAY', date("d-m-Y"));
define('REGION', date_default_timezone_set("America/Mexico_City"));


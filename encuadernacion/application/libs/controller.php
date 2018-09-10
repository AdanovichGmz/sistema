<?php


class Controller
{
   
    
     
    public $db = null;

   
    function __construct()
    {
        $this->openDatabaseConnection();
    }

   
    private function openDatabaseConnection()
    {
        
        $options = array(PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ, PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING,PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'");

        
        $this->db = new PDO(DB_TYPE . ':host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS, $options);
    }

   
    public function loadModel($model_name)
    {
        require 'application/models/' . strtolower($model_name) . '.php';
        
        return new $model_name($this->db);
    }
    public function loadClass($controller_name)
    {
        require 'application/classes/' . strtolower($controller_name) . '.php';
        
        return new $controller_name();
    }
    public function loadController($controller_name)
    {
        require 'application/controller/' . strtolower($controller_name) . '.php';
        
        return new $controller_name();
    }
}

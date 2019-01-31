<?php


class Inicio extends Controller
{
    
    public function index(){
        session_start();
        
        $login= $this->loadController('login');
        $sessions_model = $this->loadModel('SessionsModel');
        $cambio_model = $this->loadModel('CambioModel');
        $process_model = $this->loadModel('ProcessModel');
        $login_model = $this->loadModel('LoginModel');

if($login->isLoged()){
 
  header("Location:".URL.'procesos');

    }else{
        header("Location:".URL.'login');
    }



    }

    
    
   
    public function assignTasks(){
        session_start();
        $sessions_model = $this->loadModel('SessionsModel');
        $login_model = $this->loadModel('LoginModel');
        $process_model=$this->loadModel('ProcessModel');
        $stations= $process_model->getProcesByUser($_POST['user']);
        require 'application/views/inicio/options.php';
    }

    
    
    public function chooseODT(){
        session_start();
        $sessions_model = $this->loadModel('SessionsModel');
        $login_model = $this->loadModel('LoginModel');
        $process_model=$this->loadModel('ProcessModel');
        require 'application/views/inicio/ODT.php';    
        
    }
    
}

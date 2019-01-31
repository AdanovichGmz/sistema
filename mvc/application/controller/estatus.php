<?php


class Estatus extends Controller{
    
    public function index(){
        session_start();
        $sessions_model = $this->loadModel('SessionsModel');
        $process_model = $this->loadModel('ProcessModel');
        $login_model=$this->loadModel('LoginModel');
        $login= $this->loadController('login');
        $ete_model = $this->loadModel('EteModel');
      

        require 'application/views/templates/head.php';
        require 'application/views/estatus/index.php';

    }
    
    
        public function reload(){
        session_start();
        $sessions_model = $this->loadModel('SessionsModel');
        $process_model = $this->loadModel('ProcessModel');
        $login_model=$this->loadModel('LoginModel');
        $login= $this->loadController('login');
        $ete_model = $this->loadModel('EteModel');
      

       
        require 'application/views/estatus/estatus_content.php';

    }

     

    

    


        
    
   
   
}

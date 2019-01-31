<?php


class Encuesta extends Controller{
    
    public function index(){
        session_start();
        $sessions_model = $this->loadModel('SessionsModel');
        $process_model = $this->loadModel('ProcessModel');
        $login_model=$this->loadModel('LoginModel');
        $login= $this->loadController('login');
        $ete_model = $this->loadModel('EteModel');
        $url=($_SESSION['multi_process']=='true')? 'procesos':'ajuste';
    if($login->isLoged()){

        $seccion=$sessions_model->getSessionStatus();
        if ($seccion['actividad_actual']=='encuesta'){
            require 'application/views/templates/head.php';
            require 'application/views/encuesta/index.php';
        }else{
            header("Location:".URL.$seccion['actividad_actual']);
        }

    }else{
         header("Location:".URL.'login');
    }
        
    }

    public function saveEncuesta(){
        session_start();
        $sessions_model = $this->loadModel('SessionsModel');
        $process_model = $this->loadModel('ProcessModel');
        $login_model=$this->loadModel('LoginModel');
        $login= $this->loadController('login');
        
       if ($sessions_model->addEncuesta($_POST)) {
        if ($_SESSION['multi_process']=='true') {
           $sessions_model->changeToInicio();
        }else{
            $sessions_model->changeToAjuste();
        }
         
         $_SESSION['ajusteStarted']='true';
       }

    
        
    }
    
    
    
   
   
}

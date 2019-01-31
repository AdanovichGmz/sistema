<?php


class Asaichi extends Controller
{
    
    public function index(){

        session_start();
        $sessions_model = $this->loadModel('SessionsModel');
        $process_model = $this->loadModel('ProcessModel');
        $ete_model = $this->loadModel('EteModel');

        $seccion=$sessions_model->getSessionStatus();
        if ($_SESSION['multi_process']=='true') {
            $url='procesos';
        }else{
            $url='ajuste';
        }

   if (isset($_SESSION['asaichiStarted'])) {
           
            if ($_SESSION['asaichiStarted']=='true') {
            require 'application/views/templates/head.php';
            require 'application/views/asaichi/index.php';
            }elseif($_SESSION['ajusteStarted']=='false'){
                if ($sessions_model->initAsaichi()) {

        $_SESSION['asaichiStarted']='true';
        require 'application/views/templates/head.php';
        require 'application/views/asaichi/index.php';
        }else{
            echo '<div class="error-message">No se pudo iniciar el asaichi, por favor hablale a los de sistemas</div>';
        }
            }

        }else{


        if ($sessions_model->initAsaichi()) {

        $_SESSION['asaichiStarted']='true';
        $_SESSION['inicio_asaichi']=date("H:i:s", time());
        
        require 'application/views/templates/head.php';
        require 'application/views/asaichi/index.php';
        }else{
            echo '<div class="error-message">No se pudo iniciar el asaichi, por favor hablale a los de sistemas</div>';
        }

        }

   

        
        
        
        
       
    }
    public function finishAsaichi(){

        session_start();
        print_r($_POST);
        $sessions_model = $this->loadModel('SessionsModel');
        $process_model = $this->loadModel('ProcessModel');
        
            if ($sessions_model->saveAsaichi($_SESSION['user']['id'],$_POST['tiempo'],$_SESSION['sessionID'],$_POST['hora_inicio'])){
                $_SESSION['asaichi_completed']='true';
                 echo "todo bien";
            }else{
            echo "hijole algo salio mal";
        }
        
        
       
    }

    
}

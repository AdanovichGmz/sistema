<?php


class Tiro extends Controller{
    
    public function index(){
        session_start();
        $sessions_model = $this->loadModel('SessionsModel');
        $process_model = $this->loadModel('ProcessModel');
        $login_model=$this->loadModel('LoginModel');
        $login= $this->loadController('login');
        $ete_model = $this->loadModel('EteModel');


    if($login->isLoged()){


        $seccion=$sessions_model->getSessionStatus();
        if ($seccion['actividad_actual']=='tiro'||($seccion['actividad_actual']=='alerta'&&$seccion['seccion_alert']=='tiro')||($seccion['actividad_actual']=='comida'&&$seccion['seccion_comida']=='tiro')) {

            $working=$sessions_model->getWorkingInfo();
            $current_task=(!empty($working))? $working['num_odt']." ".$working['parte']:'--' ;
            
            $ete=$ete_model->getEteByUser($_SESSION['user']['id'],TODAY);
            $userInfo=$login_model->getUserInfo($_SESSION['user']['id']);
            $tiro=$_SESSION['sessionID'];
            $proceso=$_SESSION['processSelected'];
            //echo "<pre>";
            //print_r($ete);
            //echo "</pre>";
            $muda=100-$ete['ete'];
            $dispon_tope=($ete['disponibilidad']>100)? 100: $ete['disponibilidad'];
            $desemp_tope=($ete['desempenio']>100)? 100: $ete['desempenio'];
            $calidad_tope=($ete['calidad']>100)? 100: $ete['calidad'];
            
            $merma=$ete_model->getMerma($_SESSION['user']['id'],$_SESSION['sessionID']);
            $tiros=$ete_model->getBuenos($_SESSION['user']['id'],$_SESSION['sessionID']);
            require 'application/views/templates/head.php';
            require 'application/views/tiro/index.php';
        }elseif ($seccion['actividad_actual']=='ajuste'){
            header("Location:".URL.'ajuste');
        }elseif ($seccion['actividad_actual']=='inicio'){
            header("Location:".URL.'procesos');
        }

       

    }else{
         header("Location:".URL.'login');
    }
        
    }
    
    
    
    public function startAlert(){
        session_start();
        $sessions_model = $this->loadModel('SessionsModel');
        $process_model=$this->loadModel('ProcessModel');
        $alert_starded=$sessions_model->putUserOnAlert($_SESSION['sessionID'],$_POST['seccion']);
        if ($alert_starded) {
           require 'application/views/tiro/alerta.php';
        }else{
            echo "<p style='padding:30px;color:red;'>No se pudo iniciar la alerta por favor comunicate con sistemas</p>";
        }
        
    }
    
    public function saveAlert(){
        session_start();
        $sessions_model = $this->loadModel('SessionsModel');
        $process_model=$this->loadModel('ProcessModel');


         $cambio_model = $this->loadModel('CambioModel');
        
         $login_model = $this->loadModel('LoginModel');
         $ete_model = $this->loadModel('EteModel');

        $opcion=(isset($_POST['radios']))? $_POST['radios']:'Otro';
        $observaciones=$_POST['observaciones'];
        $tiempo=$_POST['tiempo-alerta'];
        
        $estacion=$_SESSION['stationID'];
        $usuario=$_POST['user'];
        $horaInicio=$_POST['hora-inicio'];
        $tiro=$_POST['tiro'];

       $alert_saved=$sessions_model->addAlertTiempoMuerto($opcion,$observaciones,$tiempo,$estacion,$usuario,$horaInicio,$tiro);
        if ($alert_saved) {
            
            $sessions_model->endAlertTiro($tiempo);
           echo "todo bien";
           
        }else{
            echo "<p style='padding:30px;color:red;'>No se pudo iniciar la alerta por favor hablale a los de sistemas</p>";
        }
        
    }
    public function saveLunch(){
        session_start();
        $sessions_model = $this->loadModel('SessionsModel');
        $process_model=$this->loadModel('ProcessModel');
        $cambio_model = $this->loadModel('CambioModel');
        $login_model = $this->loadModel('LoginModel');
        $ete_model = $this->loadModel('EteModel');

        $opcion=(isset($_POST['radios']))? $_POST['radios']:'Otro';
        $section=$_POST['section'];
        $tiempo=$_POST['tiempo-comida'];
        
        $estacion=$_SESSION['stationID'];
        $usuario=$_POST['user'];
        $horaInicio=$_POST['hora-inicio'];
        $tiro=$_POST['tiro'];

        $alert_saved=$sessions_model->addLunchTime($opcion,$tiempo,$estacion,$usuario,$horaInicio,$tiro,$section);
        if ($alert_saved) {
            $sessions_model->endTiroLunch($tiempo);
            
           
        }else{
            echo "<p style='padding:30px;color:red;'>No se pudo iniciar la alerta por favor hablale a los de sistemas</p>";
        }
        
    }
    
    public function finishCambio(){
        session_start();
        $log=new Logs();
        $sessions_model = $this->loadModel('SessionsModel');
        $process_model=$this->loadModel('ProcessModel');
        $cambio_model=$this->loadModel('CambioModel');
        $login_model=$this->loadModel('LoginModel');
        print_r($_POST);
        $completed=$cambio_model->completingTiro($_POST,$process_model);
        if ($completed) {
            $sessions_model->putUserOnAjuste($_SESSION['sessionID'] );
            $process_model->setCurrentOdt('--');
            $cambio_model->newCambio();
            $cambio_model->setCurrentCambio();
            $cambio_model->removeActualTask();
            if ($_SESSION['multi_process']=='true') {
                $process_model->setInicio();
            }
            $process_model->setEncuesta();
             
        }else{
            $log->lwrite('No se completo el cambio',TODAY.'_ERROR_TIRO_'.$_SESSION['user']['logged_in']);
            $log->lwrite('Archivo: '.__FILE__,TODAY.'_ERROR_TIRO_'.$_SESSION['user']['logged_in']);            
            $log->lwrite('metodo: finishCambio()',TODAY.'_ERROR_TIRO_'.$_SESSION['user']['logged_in']);
              $log->lwrite($_SESSION['errors'],TODAY.'_ERROR_TIRO_'.$_SESSION['user']['logged_in']);
        }
  

    }
    public function cancellingCambio(){
        session_start();
        $log=new Logs();
        $sessions_model = $this->loadModel('SessionsModel');
        $process_model=$this->loadModel('ProcessModel');
        $cambio_model=$this->loadModel('CambioModel');
        $login_model=$this->loadModel('LoginModel');
        
        $cancelled=$cambio_model->cancellCambio();

        if ($cancelled) {

            $sessions_model->putUserOnAjuste($_SESSION['sessionID'] );
            $process_model->setCurrentOdt('--');
            $cambio_model->newCambio();
            $cambio_model->setCurrentCambio();
            $cambio_model->removeActualTask();
            if ($_SESSION['multi_process']=='true'){
                $process_model->setInicio();
            }

        }
        
        



    }
    


     public function lunchTime(){
        session_start();
        $sessions_model = $this->loadModel('SessionsModel');
        $process_model=$this->loadModel('ProcessModel');
        $cambio_model=$this->loadModel('CambioModel');
        $login_model=$this->loadModel('LoginModel');
       
        $user=$_POST['user'];
       

        $lunched=$sessions_model->putUserOnLunchTime($_SESSION['sessionID']);
        if ($lunched) {
           require 'application/views/tiro/comida.php';
        }else{
            echo '<div class="error-message">No se pudo enviar a este usuario a comer, por favor hablale a los de sistemas</div>';
        }
     

    }


    


    

    


        
    
   
   
}

<?php


class Ajuste extends Controller
{
    
    public function index(){

        session_start();
        $sessions_model = $this->loadModel('SessionsModel');
        $process_model = $this->loadModel('ProcessModel');
        $ete_model = $this->loadModel('EteModel');

        $seccion=$sessions_model->getSessionStatus();

if ($seccion['actividad_actual']=='ajuste') {
   if (isset($_SESSION['ajusteStarted'])) {
           
            if ($_SESSION['ajusteStarted']=='true') {
            require 'application/views/templates/head.php';
            require 'application/views/ajuste/index.php';
            }elseif($_SESSION['ajusteStarted']=='false'){
                if ($sessions_model->initAjuste()) {

        $_SESSION['ajusteStarted']='true';
        require 'application/views/templates/head.php';
        require 'application/views/ajuste/index.php';
        }else{
            echo '<div class="error-message">No se pudo iniciar el ajuste, por favor hablale a los de sistemas</div>';
        }
            }

        }else{

           if ($sessions_model->initAjuste()) {

        $_SESSION['ajusteStarted']='true';
        require 'application/views/templates/head.php';
        require 'application/views/ajuste/index.php';
        }else{
            echo '<div class="error-message">No se pudo iniciar el ajuste, por favor hablale a los de sistemas</div>';
        } 
        }
}elseif($seccion['actividad_actual']=='tiro'){
    header("Location:".URL.'tiro');
}elseif($seccion['actividad_actual']=='alerta'||$seccion['actividad_actual']=='comida'){
    if ($seccion['seccion_alert']=='ajuste') {
       if (isset($_SESSION['ajusteStarted'])) {
           
            if ($_SESSION['ajusteStarted']=='true') {
            require 'application/views/templates/head.php';
            require 'application/views/ajuste/index.php';
            }elseif($_SESSION['ajusteStarted']=='false'){
                if ($sessions_model->initAjuste()) {

        $_SESSION['ajusteStarted']='true';
        require 'application/views/templates/head.php';
        require 'application/views/ajuste/index.php';
        }else{
            echo '<div class="error-message">No se pudo iniciar el ajuste, por favor hablale a los de sistemas</div>';
        }
            }

        }else{

           if ($sessions_model->initAjuste()) {

        $_SESSION['ajusteStarted']='true';
        require 'application/views/templates/head.php';
        require 'application/views/ajuste/index.php';
        }else{
            echo '<div class="error-message">No se pudo iniciar el ajuste, por favor hablale a los de sistemas</div>';
        } 
        }

    }
}

        
        
        
        
       
    }
    public function startAlert(){

        session_start();
        $sessions_model = $this->loadModel('SessionsModel');
        $process_model = $this->loadModel('ProcessModel');
        $ete_model = $this->loadModel('EteModel');
       
        if ($_POST['actividad']=='alerta'){
            require 'application/views/templates/head.php';
        require 'application/views/ajuste/alerta.php';
        }else{
            if ($sessions_model->putTeamOnAlert($_POST['section'])) {
           require 'application/views/templates/head.php';
        require 'application/views/ajuste/alerta.php';
        }else{
             echo '<div class="error-message">No se pudo iniciar la alerta, por favor hablale a los de sistemas</div>';
        }
        }
        
        
       
    }
    public function startLunchTime(){

        session_start();
        $sessions_model = $this->loadModel('SessionsModel');
        $process_model = $this->loadModel('ProcessModel');
        $ete_model = $this->loadModel('EteModel');
         if ($_POST['actividad']=='comida'){
             require 'application/views/templates/head.php';
        require 'application/views/ajuste/comida.php';
         }else{
            if ($sessions_model->putTeamOnLunchTime()) {
           require 'application/views/templates/head.php';
        require 'application/views/ajuste/comida.php';
        }else{
            echo '<div class="error-message">No se pudo iniciar la alerta, por favor hablale a los de sistemas</div>';
        }
         }
        
        
       
    }
    public function finishAjuste(){

        session_start();
        print_r($_POST);
        $sessions_model = $this->loadModel('SessionsModel');
        $process_model = $this->loadModel('ProcessModel');
        $i=0;
        $team_lenght=count($_SESSION['teamSession']);
        $sessions_model->initTeamTiro();
        $horadeldia=$sessions_model->getSessionStatus();
        foreach ($_SESSION['teamSession'] as $key => $member) {
            if ($sessions_model->saveAjuste($key,$member['memberSessionID'],$member['memberProcessID'],$member['memberTiroActual'],$_POST['tiempo'],$horadeldia['inicio_ajuste'])) {
                $i++;
            }
        }

        if ($team_lenght==$i) {
           echo "todo bien";
        }else{
            echo "algo salio mal";
        }
        
        
       
    }

    public function addMembers(){

        session_start();
        $sessions_model = $this->loadModel('SessionsModel');
        $cambio_model = $this->loadModel('CambioModel');
        $process_model = $this->loadModel('ProcessModel');
        $login_model = $this->loadModel('LoginModel');

        require 'application/views/templates/head.php';
        require 'application/views/ajuste/operarios.php';
        
        
       
    }


    public function pushMembers(){
        session_start();
        $sessions_model = $this->loadModel('SessionsModel');
        $process_model=$this->loadModel('ProcessModel');
        $cambio_model = $this->loadModel('CambioModel');
        $roles=$_POST['leader'];

        
        
        
        foreach ($_POST['workers'] as $worker) {
            $role=$roles[$worker];
            $sessions_model->addMemeberToTeam($worker,$_POST['sesion'],$role);
            if (isset($_SESSION['preparingTasks'][$worker])) {
               $processID=$_SESSION['preparingTasks'][$worker][0];
               $userID=$worker;
            if ($sessions_model->checkSessionByUser($userID)) {

                $sessionId=$_SESSION['teamSession'][$userID]['memberSessionID'];
        $memberProcessID=$processID;

            $restart=$cambio_model->newMemberCambio($userID,$sessionId,$memberProcessID);
            
            if ($restart) {
                $_SESSION['teamSession'][$_POST['user']]['memberProcessID']=$processID;
                $sessions_model->putMemberOnTiro($sessionId,$_POST['user']);
               require 'application/views/tiro/userInterface.php';
            }else{
              echo "<p style='padding:30px;color:red;'>No se pudo guardar la informacion por favor hablale a los de sistemas</p>";  }
             
            
        }else{
          $initMember=$sessions_model->newMemberSession($userID,$processID,'ajuste');
          if ($initMember){
            $memberSessionID=$_SESSION['teamSession'][$userID]['memberSessionID'];
            $memberProcessID=$_SESSION['teamSession'][$userID]['memberProcessID'];
            $tiro_inserted=$cambio_model->newMemberCambio($userID,$memberSessionID,$memberProcessID);
            if ($tiro_inserted) {
              
            }else{
               echo "<p style='color:#fff;'>Ocurrio un error porfavor hablale a los de sistemas</p>";
            }
           } else{
            echo "<p style='color:#fff;'>Ocurrio un error porfavor hablale a los de sistemas</p>";
           }
        }   
            //$this->initProcessByUser($worker,$tasks[0]);
            }
            
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

        $alert_saved=$sessions_model->addAlertAjuste($opcion,$observaciones,$tiempo,$estacion,$usuario,$horaInicio,$tiro);
        if ($alert_saved) {
            $sessions_model->putTeamOnAjuste();
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
            $sessions_model->putTeamOnAjuste();
            echo "todo bien";
           
        }else{
            echo "<p style='padding:30px;color:red;'>No se pudo iniciar la alerta por favor hablale a los de sistemas</p>";
        }
        
    }


    
}

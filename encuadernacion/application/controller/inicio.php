<?php


class Inicio extends Controller
{
    
    public function index(){
        session_start();
        

        $sessions_model = $this->loadModel('SessionsModel');
        $cambio_model = $this->loadModel('CambioModel');
        $process_model = $this->loadModel('ProcessModel');
        $login_model = $this->loadModel('LoginModel');

if(isset($_SESSION['logged_in'])){
    
    $session_exist = $sessions_model->checkForSession($_SESSION['stationID'],$_SESSION['idUser'],TODAY);

        if (!$session_exist) {

          if ($sessions_model->newSession()) {
             $insertedCambio = $cambio_model->newCambio();
             if ($insertedCambio) {
              $_SESSION['environment']='encuadernacion';
                $cambio_model->setCurrentCambio();
                require 'application/views/templates/head.php';
                require 'application/views/inicio/operarios.php';
                
             }
             
          }else{
            echo "<script>alert('No estas asignado a una estacion');location.href = '".BASE_URL."logout.php';</script>";
          }

        }else{

        $_SESSION['environment']='encuadernacion';
        $_SESSION['tiroActual']=$cambio_model->getCurrentCambio();
        $seccion=$sessions_model->getSessionStatus();
        $_SESSION['teamSession']=$sessions_model->recoverTeam();
        if ($seccion['actividad_actual']=='ajuste'){
            header("Location:".URL.'ajuste');
            $_SESSION['odt']= $seccion['orden_actual'];
            $_SESSION['ajusteStarted']='true';

        }elseif($seccion['actividad_actual']=='tiro'){
            header("Location:".URL.'tiro');
            $_SESSION['odt']=$seccion['orden_actual'];
            $_SESSION['ajusteStarted']='false';
        }elseif($seccion['actividad_actual']=='inicio'){
          require 'application/views/templates/head.php';
          require 'application/views/inicio/operarios.php'; 
        }
                   
          
        } 
    }else{
         header("Location: ../");
    }



    }

    public function initProcessByUser($userID,$processID){
       
         $sessions_model = $this->loadModel('SessionsModel');
         $cambio_model = $this->loadModel('CambioModel');
         $process_model = $this->loadModel('ProcessModel');
         $login_model = $this->loadModel('LoginModel');
         $ete_model = $this->loadModel('EteModel');
        


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
              echo "usuario iniciado";
            }else{
               echo "<p style='color:#fff;'>Ocurrio un error porfavor hablale a los de sistemas</p>";
            }
           } else{
            echo "<p style='color:#fff;'>Ocurrio un error porfavor hablale a los de sistemas</p>";
           }
        }
    
        
        
        
    }

    
    public function initTeam(){
        session_start();
        
        $sessions_model = $this->loadModel('SessionsModel');
        $process_model=$this->loadModel('ProcessModel');
        $cambio_model = $this->loadModel('CambioModel');
        $roles=$_POST['leader'];

        $_SESSION['odt']=$_POST['odt'];
        $sessions_model->setOdtInSession($_POST['odt']);
        $sessions_model->putTeamOnAjuste();
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

        require 'application/views/inicio/odt.php';

    }
    public function assignTasks(){
        session_start();
        $sessions_model = $this->loadModel('SessionsModel');
        $login_model = $this->loadModel('LoginModel');
        $process_model=$this->loadModel('ProcessModel');
        $stations= $process_model->getProcesByUser($_POST['user']);
        require 'application/views/inicio/options.php';
    }

    
    public function prepareTasks(){
        session_start();
        //error_reporting(0);
        error_reporting(E_ALL ^ E_NOTICE);
        $sessions_model = $this->loadModel('SessionsModel');
        $login_model = $this->loadModel('LoginModel');
        $process_model=$this->loadModel('ProcessModel');

        
            
        $taken=$sessions_model->userIsTaken($_POST['user']);
        $result=array();
        $assigned=array();
        print_r($_POST);
        if (!$taken) {

            foreach ($_POST['tasks'] as $task) {
               
               $assigned[]=$task;
            }
           

            $_SESSION['preparingTasks'][$_POST['user']]=$assigned;
            if ($_POST['is-random']=='true') {
              $_SESSION['randomTasks'][$_POST['user']]=$_POST['custom-task'];
            }
            
            

            $result['response']='success';                

        }else{
                $result['response']='taken';
        }

        echo json_encode($result);
    }
    public function chooseODT(){
        session_start();
        $sessions_model = $this->loadModel('SessionsModel');
        $login_model = $this->loadModel('LoginModel');
        $process_model=$this->loadModel('ProcessModel');
        require 'application/views/inicio/ODT.php';    
        
    }
    
}

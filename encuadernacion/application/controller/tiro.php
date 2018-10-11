<?php


class Tiro extends Controller{
    
    public function index(){
        session_start();
        $sessions_model = $this->loadModel('SessionsModel');
        $process_model = $this->loadModel('ProcessModel');
        $ete_model = $this->loadModel('EteModel');
    if(isset($_SESSION['logged_in'])){


        $seccion=$sessions_model->getSessionStatus();
        if ($seccion['actividad_actual']=='tiro') {
            require 'application/views/templates/head.php';
            require 'application/views/tiro/index.php';
        }elseif ($seccion['actividad_actual']=='ajuste') {
            header("Location:".URL.'ajuste');
        }

       

    }else{
         header("Location: ../");
    }
        
    }
    public function userinterface(){
    session_start();
    $sessions_model = $this->loadModel('SessionsModel');
    $process_model = $this->loadModel('ProcessModel');
    $login_model = $this->loadModel('LoginModel');
    $ete_model = $this->loadModel('EteModel');
    if(isset($_SESSION['logged_in'])){

        $sessionStatus=$sessions_model->getSessionStatus();
        if ($sessions_model->checkSessionByUser($_POST['user'])) {
            $current_activity=$sessions_model->getMemberActivity($_POST['user']);
           if ($current_activity=='tiro') {
            require 'application/views/tiro/userInterface.php';
        }elseif($current_activity=='alerta'){

            require 'application/views/tiro/alerta.php';
        }elseif($current_activity=='comida'){
             require 'application/views/tiro/comida.php';
        }elseif($current_activity=='ajuste'){
                require 'application/views/tiro/options.php';
        }

        }else{

            require 'application/views/tiro/options.php';
           // $sessions_model->initMemberSession($_POST['user'])

        }

    }else{
         header("Location: ../");
    }
       
    }
    
    public function setProcess(){
        session_start();
         $sessions_model = $this->loadModel('SessionsModel');
         $cambio_model = $this->loadModel('CambioModel');
         $process_model = $this->loadModel('ProcessModel');
         $login_model = $this->loadModel('LoginModel');
         $ete_model = $this->loadModel('EteModel');
        


        if ($sessions_model->checkSessionByUser($_POST['user'])) {

                $sessionId=$_SESSION['teamSession'][$_POST['user']]['memberSessionID'];
        $memberProcessID=$_POST['option'];

            $restart=$cambio_model->newMemberCambio($_POST['user'],$sessionId,$memberProcessID);
            
            if ($restart) {
                $_SESSION['teamSession'][$_POST['user']]['memberProcessID']=$_POST['option'];
                $sessions_model->putMemberOnTiro($sessionId,$_POST['user']);
              // require 'application/views/tiro/userInterface.php';
                $sessions_model->updateMemberSession($sessionId,'proceso',$_POST['option']);

                print_r($_SESSION);
            }else{
              echo "<p style='padding:30px;color:red;'>No se pudo guardar la informacion por favor hablale a los de sistemas</p>";  }
             
            
        }else{
          $initMember=$sessions_model->newMemberSession($_POST['user'],$_POST['option'],'tiro');
          if ($initMember){
            $memberSessionID=$_SESSION['teamSession'][$_POST['user']]['memberSessionID'];
            $memberProcessID=$_SESSION['teamSession'][$_POST['user']]['memberProcessID'];
            $tiro_inserted=$cambio_model->newMemberCambio($_POST['user'],$memberSessionID,$memberProcessID);
            if ($tiro_inserted) {
              //require 'application/views/tiro/userInterface.php';
                

                print_r($_SESSION);
            }else{
               echo "<p style='color:#fff;'>Ocurrio un error por favor hablale a los de sistemas</p>";
            }
           } else{
            echo "<p style='color:#fff;'>Ocurrio un error porfavor hablale a los de sistemas</p>";
           }
        }
    
        
        
        
    }

    public function saveTeamTiros(){
        session_start();
        $cambio_model = $this->loadModel('CambioModel');
        $sessions_model = $this->loadModel('SessionsModel');
        $process_model=$this->loadModel('ProcessModel');
        echo "<pre>";
        print_r($_POST);
        echo "</pre>";

        $pedido=$_POST['adm-pedidos'];
            $buenos=$_POST['adm-buenos'];
            $ajuste=0;
            $recibidos=$_POST['adm-recibidos'];
            $defectos=$_POST['adm-defectos'];

        foreach ($_POST['members'] as $member) {
            

            $miniPost=array();
            $miniPost['tiempo-tiraje']='07:22:33';
            $miniPost['user']=$member;
            $miniPost['tiro-actual']=$_SESSION['teamSession'][$member]['memberTiroActual'];
            $miniPost['pedido']=$pedido[$member];
            $miniPost['buenos']=$buenos[$member];
            $miniPost['ajuste']=$ajuste;
            $miniPost['recibidos']=$recibidos[$member];
            $miniPost['merma']=$buenos[$member]-$pedido[$member];
            $miniPost['defectos']=$defectos[$member];
            $miniPost['proceso']=$_SESSION['teamSession'][$member]['memberProcessID'];

            
        $memberSessionID=$_SESSION['teamSession'][$member]['memberSessionID'];
        $memberProcessID=$_SESSION['teamSession'][$member]['memberProcessID'];

        $completed=$cambio_model->completingTiro($miniPost,$process_model);
        if ($completed){
            $sessions_model->putMemberOnAjuste($memberSessionID);
          
            echo "todo bien para el ".$member;
             
        }else{
            echo "<p style='padding:30px;color:red;'>No se pudo guardar la informacion por favor hablale a los de sistemas</p>";
        }
           

        }


    }

    public function startTeamAlert(){
        session_start();
        $sessions_model = $this->loadModel('SessionsModel');
        $process_model=$this->loadModel('ProcessModel');
        
        $alert_starded=$sessions_model->putTeamMembersOnAlert('tiro');
        $result=array();

        
        if ($alert_starded) {
           $result['response']='success';
        }else{
             $result['response']='error';
        }
        echo json_encode($result);
        
    }


    public function startAlert(){
        session_start();
        $sessions_model = $this->loadModel('SessionsModel');
        $process_model=$this->loadModel('ProcessModel');
        $alert_starded=$sessions_model->putMemberOnAlert($_POST['member_session']);
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
        $member_session=$_POST['member_session'];
        $estacion=$_SESSION['stationID'];
        $usuario=$_POST['user'];
        $horaInicio=$_POST['hora-inicio'];
        $tiro=$_POST['tiro'];

        $alert_saved=$sessions_model->addAlertTiempoMuerto($opcion,$observaciones,$tiempo,$estacion,$usuario,$horaInicio,$tiro);
        if ($alert_saved) {
            $sessions_model->endAlert($member_session);
            require 'application/views/tiro/userInterface.php';
           
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
        $member_session=$_POST['member_session'];
        $estacion=$_SESSION['stationID'];
        $usuario=$_POST['user'];
        $horaInicio=$_POST['hora-inicio'];
        $tiro=$_POST['tiro'];

        $alert_saved=$sessions_model->addLunchTime($opcion,$tiempo,$estacion,$usuario,$horaInicio,$tiro,$section);
        if ($alert_saved) {
            $sessions_model->endLunch($member_session);
            require 'application/views/tiro/userInterface.php';
           
        }else{
            echo "<p style='padding:30px;color:red;'>No se pudo iniciar la alerta por favor hablale a los de sistemas</p>";
        }
        
    }
    public function finishTiro(){
        session_start();
        $sessions_model = $this->loadModel('SessionsModel');
        $process_model=$this->loadModel('ProcessModel');
        $cambio_model=$this->loadModel('CambioModel');
        $login_model=$this->loadModel('LoginModel');
        $completed=$cambio_model->completingTiro($_POST,$process_model);
        $user=$_POST['user'];
        $memberSessionID=$_SESSION['teamSession'][$user]['memberSessionID'];
        $memberProcessID=$_SESSION['teamSession'][$user]['memberProcessID'];


        if ($completed) {
            $sessions_model->putMemberOnAjuste($memberSessionID);
            require 'application/views/tiro/options.php';
             
        }else{
            echo "<p style='padding:30px;color:red;'>No se pudo guardar la informacion por favor hablale a los de sistemas</p>";
        }
    }
    public function finishCambio(){
        session_start();
        $sessions_model = $this->loadModel('SessionsModel');
        $process_model=$this->loadModel('ProcessModel');
        $cambio_model=$this->loadModel('CambioModel');
        $login_model=$this->loadModel('LoginModel');
        print_r($_SESSION);
        $sessions_model->putTeamOnInicio();
        $cambio_model->newCambio();
        $cambio_model->setCurrentCambio();
        $_SESSION['preparingTasks']='';
        $_SESSION['odt']='';
        $sessions_model->cleanTeam();



    }
    
    public function teamExchange(){
        session_start();
        $sessions_model = $this->loadModel('SessionsModel');
        $process_model=$this->loadModel('ProcessModel');
        $cambio_model=$this->loadModel('CambioModel');
        $login_model=$this->loadModel('LoginModel');
       
        $user=$_POST['user'];
        $memberSessionID=$_SESSION['teamSession'][$user]['memberSessionID'];
        $memberProcessID=$_SESSION['teamSession'][$user]['memberProcessID'];

        $currentTeam=$_POST['current'];
        $targetTeam=$_POST['target'];
 
    }
    public function teamOptions(){
        session_start();
        $sessions_model = $this->loadModel('SessionsModel');
        $process_model=$this->loadModel('ProcessModel');
        $cambio_model=$this->loadModel('CambioModel');
        $login_model=$this->loadModel('LoginModel');
       
        $user=$_POST['user'];
        $memberSessionID=$_SESSION['teamSession'][$user]['memberSessionID'];
        $memberProcessID=$_SESSION['teamSession'][$user]['memberProcessID'];

        $teams=$sessions_model->getDisponibleTeams();
        require 'application/views/tiro/teams.php';
 

    }

     public function lunchTime(){
        session_start();
        $sessions_model = $this->loadModel('SessionsModel');
        $process_model=$this->loadModel('ProcessModel');
        $cambio_model=$this->loadModel('CambioModel');
        $login_model=$this->loadModel('LoginModel');
       
        $user=$_POST['user'];
        $memberSessionID=$_SESSION['teamSession'][$user]['memberSessionID'];
        $memberProcessID=$_SESSION['teamSession'][$user]['memberProcessID'];

        $lunched=$sessions_model->putMemberOnLunchTime($memberSessionID);
        if ($lunched) {
           require 'application/views/tiro/comida.php';
        }else{
            echo '<div class="error-message">No se pudo enviar a este usuario a comer, por favor hablale a los de sistemas</div>';
        }

        

        

    }

    public function activitySwitch(){
        session_start();
        $sessions_model = $this->loadModel('SessionsModel');
        $process_model=$this->loadModel('ProcessModel');
        $cambio_model=$this->loadModel('CambioModel');
        $login_model=$this->loadModel('LoginModel');
       
        $user=$_POST['user'];
        $memberSessionID=$_SESSION['teamSession'][$user]['memberSessionID'];
        $memberProcessID=$_SESSION['teamSession'][$user]['memberProcessID'];

        require 'application/views/tiro/options.php';
        

        

    }

     public function addMembers(){

        session_start();
        $sessions_model = $this->loadModel('SessionsModel');
        $cambio_model = $this->loadModel('CambioModel');
        $process_model = $this->loadModel('ProcessModel');
        $login_model = $this->loadModel('LoginModel');

        require 'application/views/templates/head.php';
        require 'application/views/tiro/operarios.php';
        
        
       
    }

    
    public function adminTeam(){

        session_start();
        $sessions_model = $this->loadModel('SessionsModel');
        $cambio_model = $this->loadModel('CambioModel');
        $process_model = $this->loadModel('ProcessModel');
        $login_model = $this->loadModel('LoginModel');

        require 'application/views/templates/head.php';
        require 'application/views/tiro/admin.php';
        
        
       
    }


    public function adminPanel(){
        

    }


    public function pushMembers(){
        session_start();
        $sessions_model = $this->loadModel('SessionsModel');
        $process_model=$this->loadModel('ProcessModel');
        $cambio_model = $this->loadModel('CambioModel');
        $login_model = $this->loadModel('LoginModel');
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
                $sessions_model->putMemberOnTiro($sessionId,$worker);
               require 'application/views/tiro/userInterface.php';
            }else{
              echo "<p style='padding:30px;color:red;'>No se pudo guardar la informacion por favor hablale a los de sistemas</p>";  }
             
            
        }else{
          $initMember=$sessions_model->newMemberSession($userID,$processID,'tiro');
          if ($initMember){
            $memberSessionID=$_SESSION['teamSession'][$userID]['memberSessionID'];
            $memberProcessID=$_SESSION['teamSession'][$userID]['memberProcessID'];
            $tiro_inserted=$cambio_model->newMemberCambio($userID,$memberSessionID,$memberProcessID);
            if ($tiro_inserted) {
                $newMember= $login_model->getUserInfo($userID);
                $currentActivity=$sessions_model->getMemberActivity($userID);
                $class=(!$sessions_model->checkSessionByUser($newMember['id']))? 'disabled':'';
                $photo=((!empty($newMember['foto']))? $newMember['foto'] :'images/default.jpg');
                $process_name=(isset($_SESSION['teamSession'][$newMember['id']]))? preg_replace('/\s+/', '', $process_model->getProcessName($_SESSION['teamSession'][$newMember['id']]['memberProcessID'])) :'Sin asignar';
              $script='';

              $crd= '<div class="member '.$class.' " id="member-'.$newMember['id'].' "><div class="member-content '.$currentActivity.'" data-id="'.$newMember['id'].'"><div class="member-header"><div class="member-photo"><img src="'.URL.'public/'.$photo.'"></div><div class="member-name-timer"><p>'.$newMember['logged_in'].' </p><div class="personal-timer"><span id="'.$newMember['id'].'-timer">00:00:00</span></div></div></div><div class="timer-band">'.$process_name.'</div><div class="member-body"><div id="'.$newMember['id'].'" style="top:5px;width: 98%;left: 1px; height: 120px; position:absolute;"></div></div></div></div>';
              $script='<script>';
              $script.='var userid=document.getElementById('.$newMember['id'].');';
              $script.='var timer_'.$newMember['id'].' = new Timer();';
              $script.='timer_'.$newMember['id'].'.addEventListener("secondsUpdated", function (e) {$("#"+'.$newMember['id'].' +"-timer").html(timer_'.$newMember['id'].'.getTimeValues().toString());});';
              $script.='function start'.$newMember['id'].' (elapsed){ if (elapsed=="undefined") { timer_'.$newMember['id'].'.start();}else{ timer_'.$newMember['id'].'.start({startValues: {seconds:elapsed}});}}';
              $script.='function stop'.$newMember['id'].'(){ timer_'.$newMember['id'].'.stop();}';
              $script.="timer_".$newMember['id'].'.start({startValues: {seconds:'.$process_model->getTiroElapsedTime($_SESSION['teamSession'][$newMember['id']]['memberSessionID']).'}});</script>';

               echo $crd;
               echo $script;
               


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

    


        
    
   
   
}

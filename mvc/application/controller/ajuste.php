<?php


class Ajuste extends Controller
{
    
    public function index(){

        session_start();
        $sessions_model = $this->loadModel('SessionsModel');
        $process_model = $this->loadModel('ProcessModel');
        $ete_model = $this->loadModel('EteModel');
            $login= $this->loadController('login');
        if($login->isLoged()){
        $seccion=$sessions_model->getSessionStatus();
        
        $workingInfo=$sessions_model->getWorkingInfo();
        $elements=$process_model->getProcessElements();
        
        $task_results=$process_model->getTasksInProcess($_SESSION['sessionID']);  

        $tasksInProcess='';
        foreach ($task_results as $part) {

            $tasksInProcess.= "<div class='part-option ".(($part['status']=='actual')? 'actual':'')."' data-numodt='".$part['num_odt']."' data-idorden='".$part['id_orden']."' data-idpro='".$part['id_proceso']."' data-idelem='".$part['producto']."' data-elem='".$part['elemento']."'><span>".$part['elemento']."</span></div>";

        }


        
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
}elseif ($seccion['actividad_actual']=='inicio') {
    header("Location:".URL.'procesos');
}
}else{
    header("Location:".URL.'login');
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
            if ($sessions_model->putUserOnAlert($_SESSION['sessionID'],$_POST['section'])) {

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
        
        $sessions_model = $this->loadModel('SessionsModel');
        $process_model = $this->loadModel('ProcessModel');
        $working=$sessions_model->getWorkingInfo();
        
        $sessions_model->initTiro();
        $sesion=$sessions_model->getSessionStatus();
        $estandar=$process_model->getAjusteStandard($_SESSION['processID']);
        $ontime=$sesion['en_tiempo'];

        if ($ontime=='false') {
           $sessions_model->addAusteDeadTime($_POST['tiempo']);
        }

        if ($working['is_virtual']=='true') {
            $sessions_model->saveAjuste($_SESSION['tiroActual'],$_POST['tiempo'],$sesion['inicio_ajuste'],NULL, $working['num_odt'],$working['id_elemento_virtual'],'true',$working['parte'],$estandar['ajuste_standard'],$ontime);
        }else{
            $sessions_model->saveAjuste($_SESSION['tiroActual'],$_POST['tiempo'],$sesion['inicio_ajuste'],$working['id_orden'],NULL,NULL,'false',NULL,$estandar['ajuste_standard'],$ontime);
        }
        
        $sessions_model->setOnTime($_SESSION['sessionID']);
        $sessions_model->initTiempoComida();   
      
    }

    
    public function timeOver(){

        session_start();
        
        $sessions_model = $this->loadModel('SessionsModel');
        $sessions_model->setNoOnTime($_SESSION['sessionID']);
        
            
      
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
        $response=array();
        $alert_saved=$sessions_model->addAlertAjuste($opcion,$observaciones,$tiempo,$estacion,$usuario,$horaInicio,$tiro);
        if ($alert_saved) {
            $elapsed=$process_model->getAjusteElapsedTime($_SESSION['sessionID']);
            $sessions_model->endAlertAjuste($tiempo);
            
            $response['elapsed']=$elapsed['time'];
            $response['tiempo']=$tiempo;

        }else{
            $elapsed=$process_model->getAjusteElapsedTime($_SESSION['sessionID']);
            $response['elapsed']=$elapsed['time'];
        }

        echo json_encode($response);
        
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
        $response=array();
        $alert_saved=$sessions_model->addLunchTime($opcion,$tiempo,$estacion,$usuario,$horaInicio,$tiro,$section);
        if ($alert_saved) {
            $elapsed=$process_model->getAjusteElapsedTime($_SESSION['sessionID']);
             $sessions_model->endAjusteLunch($tiempo);

            
            $response['elapsed']=$elapsed['time'];
            $response['tiempo']=$tiempo;
           
        }else{
           $elapsed=$process_model->getAjusteElapsedTime($_SESSION['sessionID']);
            $response['elapsed']=$elapsed['time'];
        }
        echo json_encode($response);
        
    }


   
    public function searchOrder(){

        session_start();
        
        $sessions_model = $this->loadModel('SessionsModel');
        $process_model = $this->loadModel('ProcessModel');
        $ete_model = $this->loadModel('EteModel');
        $odt_model=$this->loadModel('OdtModel');
        
        $order_results=$odt_model->getOrderByOdt($_POST['odt'],$_SESSION['process'][$_SESSION['processID']]['nombre_proceso']);
        if (!empty($_POST['odt'])) {
            if (!empty($order_results)) {
            foreach ($order_results as $order){
                $pendings=$odt_model->odtPendings($order['numodt'],$_SESSION['process'][$_SESSION['processID']]['nombre_proceso']);
                echo "<div class='order-option' data-numodt='".$order['numodt']."' ".(($pendings==0)? 'style="display:none;':'' )."><span>".$order['numodt']."</span></div>";
        }
        }else{
            echo "<p>No se encontraron resultados</p>";
        }
        }  
    }

    
    public function getOrderContent(){

        session_start();
        
        $sessions_model = $this->loadModel('SessionsModel');
        $process_model = $this->loadModel('ProcessModel');
        $ete_model = $this->loadModel('EteModel');
        $odt_model=$this->loadModel('OdtModel');
        $odt_results=$odt_model->getContentByOdt($_POST['odt'],$_SESSION['process'][$_SESSION['processID']]['nombre_proceso']);

        if (!empty($_POST['odt'])) {
            if (!empty($odt_results)) {
            foreach ($odt_results as $odt){

                echo "<div class='part-option' data-numodt='".$odt['numodt']."' data-idorden='".$odt['id_orden']."' data-idpro='".$odt['id_proceso']."' data-idelem='".$odt['producto']."' data-elem='".$odt['elemento']."'><span>".$odt['elemento']."</span></div>";

        }
        }else{
            echo "<p>No se encontraron resultados</p>";
        }
        }
        
        

       
    }
    public function setOrderPart(){

        session_start();
        
        $sessions_model = $this->loadModel('SessionsModel');
        $process_model = $this->loadModel('ProcessModel');
        $ete_model = $this->loadModel('EteModel');
        $odt_model=$this->loadModel('OdtModel');
        
        $process_model->cleanPersonalProcess($_SESSION['sessionID']);

        if ($_POST['is_virtual']=='true') {
        
          $process_model->setIsVirtual('true');
          
          $process_model->setRegistroProceso('NULL');
          $process_model->setCurrentOdt($_POST['virtualodt']);
          $inserted=$process_model->addTaskForThisUser('null',$_POST['virtualodt'],'null','actual',1,null,$_POST['virtualelem'] ,$_POST['elem-id']);

            echo "<div class='part-option actual' data-numodt='".$_POST['virtualodt']."' data-idorden='' data-idpro='' data-idelem='".$_POST['elem-id']."' data-elem='".$_POST['virtualelem']."'><span>".$_POST['virtualelem']."</span></div>";
            $_SESSION['registro_proceso']='';
            $_SESSION['is_virtual']='true';
        }else{

            $_SESSION['registro_proceso']=$_POST['id_proceso'];
            $_SESSION['is_virtual']='false';
            $process_model->setProduct($_POST['producto']);
        $odt_results=$odt_model->getContentByOdt($_POST['odt'],$_SESSION['process'][$_SESSION['processID']]['nombre_proceso']);  
        
        $process_model->setCurrentOdt($_POST['odt']);
        $sortedParts=$this->sortSelectedPart($_POST['id_proceso'],$odt_results);
        $process_model->setRegistroProceso($_POST['id_proceso']);
        $_SESSION['producto']=$_POST['producto'];

        $i=1;
        foreach ($sortedParts as $part) {

            $idOrden=$part['id_orden'];
            $numOdt=$part['numodt'];
            
            $id_process=$part['id_proceso'];
            $status=($i==1)? 'actual':'programado';
            
            $planillas=(isset($_POST['planillas']))? $_POST['planillas'] : null;

            $inserted=$process_model->addTaskForThisUser($idOrden,$numOdt,$id_process,$status,$i,$planillas,null,null);

            echo "<div class='part-option ".(($i==1)? 'actual':'')."' data-numodt='".$part['numodt']."' data-idorden='".$part['id_orden']."' data-idpro='".$part['id_proceso']."' data-idelem='".$part['producto']."' data-elem='".$part['elemento']."'><span>".$part['elemento']."</span></div>";

            $i++;
        }
        }
        
        
       
    }

   



    public function sortSelectedPart($id, $array) {
   foreach ($array as $key => $val) {
       if ($val['id_proceso'] === $id) {
           $target=$key;
       }
   }
   
    $temp = array($target => $array[$target]);
    unset($array[$target]);
    $new_array = $temp + $array;

    return $new_array;

    }

    public function taskSwitching() {

        session_start();
        
        $sessions_model = $this->loadModel('SessionsModel');
        $process_model = $this->loadModel('ProcessModel');
        $ete_model = $this->loadModel('EteModel');
        $odt_model=$this->loadModel('OdtModel');
        $cambio_model=$this->loadModel('CambioModel');

        $ontime=$_POST['on_time'];
        $time_elapsed=$_POST['elapsed'];
        

        $completed=$sessions_model->completeQueueTiro($time);
        

        $queued=$sessions_model->addAjusteToQueue($ontime,$time_elapsed);
        if ($queued) {

            
            if ($completed) {
            
            $process_model->setCurrentOdt('--');
            $cambio_model->newCambio();
            $cambio_model->setCurrentCambio();
            $cambio_model->removeActualTask();
        

            

            
        }


            $sessions_model->initAjuste();
            if ($sessions_model->changeToInicio()) {
                echo "si se pudo iniciar el inicio";
            }else{
                echo "no se pudo iniciar el inicio";
            }
        }else{
            echo "no se pudo encolar la cola";
        }




    
    }
    
}

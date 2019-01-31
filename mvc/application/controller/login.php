<?php


class Login extends Controller
{
    
    public function index()
    {
        session_start();
        $logged=$this->isLoged();
        if(!$logged){
          
          
        require 'application/views/templates/head.php';
        require 'application/views/login/index.php';
      
  
    }else{      
      header("Location:".URL.$_SESSION['area'].'/');  
    }
    }

    
 
    public function isLoged(){
       
       if (isset($_SESSION['logged_in'])) {
          return true;
        }else{
            return false;
        }
        
    }

     public function signIn(){
      session_start();
      $log=new Logs();
      
      $process_model=$this->loadModel('ProcessModel');
      $login_model = $this->loadModel('LoginModel');
      $sessions_model= $this->loadModel('SessionsModel');
      $cambio_model= $this->loadModel('CambioModel');
      $logged=$login_model->login($_POST);
      $hora_actual=date("H:i:s",time());
      
         
      if (!$logged) {
         $_SESSION['session_messages']='<p class="small-error-message">Usuario o contraseña incorrectos</p>';
         header("Location:".URL);
      }else{
        $_SESSION['errors']='';
        $_SESSION['user']=$logged;
        $_SESSION['logged_in']='true';
        $stations=$login_model->isMultiStation($logged['id']);
        if (!$stations) {
          unset($_SESSION['logged_in']);
          $_SESSION['session_messages']='<p class="small-error-message">No estas asignado a ninguna estación</p>';
         header("Location:".URL);
        }else{
        if ($stations['multi']=='true'){
          echo "este usuario tiene muchas estaciones";
           

        }else{
            $process=$login_model->getStationProcess($stations['station']['id_estacion']);
             $_SESSION['stationID']=$stations['station']['id_estacion'];

                        

            if (count($process)>1) {
            $_SESSION['multi_process']='true';
            $session_exist = $sessions_model->checkForSession($stations['station']['id_estacion'],$_SESSION['user']['id'],TODAY);
        if (!$session_exist) { 
            header("Location:".URL.'procesos');
        }else{
          $session=$sessions_model->getSessionStatus();
          $_SESSION['process'][$session['proceso']]=$process[$session['proceso']];
          $_SESSION['processID']=$session['proceso'];
          $_SESSION['processSelected']=$session['proceso'];
          $_SESSION['station']['id_estacion']=$session['estacion'];
          $_SESSION['registro_proceso']=$session['registro_proceso'];
          $_SESSION['is_virtual']=$session['is_virtual'];
          if ($session['is_virtual']=='false') {
           $_SESSION['producto']=$session['parte'];
          }
          
          $this->redirect($cambio_model,$sessions_model); 
        }
            

        }elseif(count($process)==1){
              
              $_SESSION['multi_process']='false';
              $_SESSION['process']=$process;
              $_SESSION['processID']=key($process);
              $_SESSION['processSelected']=key($process);
              $_SESSION['station']=$stations['station'];
              $_SESSION['registro_proceso']=$session['registro_proceso'];
              $_SESSION['is_virtual']=$session['is_virtual'];
              $session_exist = $sessions_model->checkForSession($_SESSION['station']['id_estacion'],$_SESSION['user']['id'],TODAY);
        if (!$session_exist) {

          if ($sessions_model->newSession()) {
             $insertedCambio = $cambio_model->newCambio();
             
             if ($insertedCambio){

                $cambio_model->setCurrentCambio();

                if ($this->isAsaichiDay()) {

                  if (strtotime($hora_actual)>=strtotime('08:15:00')) {
                 $diference=gmdate("H:i:s",strtotime($hora_actual)-strtotime('08:00:00'));
                  $sessions_model->addTiempoTarde($diference);
                  $sessions_model->putUserOnAjuste($_SESSION['sessionID']);
                 }else{
                  $sessions_model->initAsaichi();
                 }
                   
                 

                }else{
                   
                    if (strtotime($hora_actual)>=strtotime($_SESSION['user']['hora_entrada'])) {
                 $diference=gmdate("H:i:s",strtotime($hora_actual)-strtotime($_SESSION['user']['hora_entrada']));
                  $sessions_model->addTiempoTarde($diference);
                  $sessions_model->putUserOnAjuste($_SESSION['sessionID']);
                 }
                }
               $this->redirect($cambio_model,$sessions_model);
                
             }else{
              $log->lwrite($_SESSION['errors'],'ERROR_LOGIN_'.$SESSION['user']['logged_in']);
             }
             
          }

        }else{

        $this->redirect($cambio_model,$sessions_model);        
          
        }
          
            }elseif (count($process)==0) {
              unset($_SESSION['logged_in']);
          $_SESSION['session_messages']='<p class="small-error-message">No estas asignado a ningun proceso</p>';
            header("Location:".URL);
            }

        }
      }

       
      }
        
    }

    public function isAsaichiDay(){

      
    
      
       if(date("w")==1||date("w")==4){
                     
            return true;
            
        }else{

            return false;
        }
  
  
  }

  public function redirect($cambio_model,$sessions_model){
    
    
    $_SESSION['tiroActual']=$cambio_model->getCurrentCambio();
        $seccion=$sessions_model->getSessionStatus();
        $_SESSION['act_act']=$seccion['actividad_actual'];
        if ($seccion['actividad_actual']=='ajuste'){

            $_SESSION['odt']= $seccion['orden_actual'];
            $_SESSION['ajusteStarted']='true';
            header("Location:".URL.'ajuste');
            

        }elseif($seccion['actividad_actual']=='tiro'){

            $_SESSION['odt']=$seccion['orden_actual'];
            $_SESSION['ajusteStarted']='false';
            header("Location:".URL.'tiro');
            
        }elseif($seccion['actividad_actual']=='asaichi'){

          header("Location:".URL.'asaichi');

        }elseif($seccion['actividad_actual']=='alerta'){

          if ($seccion['seccion_alert']=='ajuste') {
            $_SESSION['ajusteStarted']='true';
           header("Location:".URL.'ajuste');
          }else{
            header("Location:".URL.'tiro');
          }

         

        }elseif($seccion['actividad_actual']=='comida'){

          if ($seccion['seccion_comida']=='ajuste') {
            
           header("Location:".URL.'ajuste');
          }else{
            header("Location:".URL.'tiro');
          }

         

        }elseif($seccion['actividad_actual']=='inicio'){

          
            header("Location:".URL.'procesos');
          

         

        }elseif($seccion['actividad_actual']=='encuesta'){

          
            header("Location:".URL.'encuesta');
          

         

        }
           
  }

  public function setProcess(){
    session_start();
     $process_model=$this->loadModel('ProcessModel');
      $login_model = $this->loadModel('LoginModel');
      $sessions_model= $this->loadModel('SessionsModel');
      $cambio_model= $this->loadModel('CambioModel');
      $log=new Logs();
      $_process=$_POST['process_id'];
      $station=$_POST['station'];
      $process=array();
      $hora_actual=date("H:i:s",time());
      $process[$_process]=$_process;
      $process_info=$login_model->getStationProcess($station);
      $_SESSION['process'][$_process]=$process_info[$_process];
              $_SESSION['processID']=$_process;
              $_SESSION['processSelected']=$_process;
              $_SESSION['station']['id_estacion']=$station;

          if ($sessions_model->newSession()) {
             $insertedCambio = $cambio_model->newCambio();
             if ($insertedCambio) {
                $sessions_model->initAjuste();
                $cambio_model->setCurrentCambio();

                
           if (strtotime($hora_actual)>=strtotime($_SESSION['user']['hora_entrada'])) {
                 $diference=gmdate("H:i:s",strtotime($hora_actual)-strtotime($_SESSION['user']['hora_entrada']));
                  $sessions_model->addTiempoTarde($diference);
                 }

                
               $this->redirect($cambio_model,$sessions_model);
                
             }else{
              $log->lwrite('No se inserto el cambio',TODAY.'_ERROR_LOGIN_'.$_SESSION['user']['logged_in'],__FILE__);
              $log->lwrite($_SESSION['errors'],TODAY.'_ERROR_LOGIN_'.$_SESSION['user']['logged_in'],__FILE__);

             }
             
          }

        
            
      
  
  
  }


    

}

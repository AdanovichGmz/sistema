<?php


class Procesos extends Controller
{
    
    public function index()
    {
        session_start();
        $sessions_model = $this->loadModel('SessionsModel');
        $process_model = $this->loadModel('ProcessModel');
        $login_model=$this->loadModel('LoginModel');
        $login= $this->loadController('login');
        $ete_model = $this->loadModel('EteModel');
        $cambio_model=$this->loadModel('CambioModel');
        $processes=$login_model->getStationProcess($_SESSION['stationID']);


        $session_exist = $sessions_model->checkForSession($_SESSION['stationID'],$_SESSION['user']['id'],TODAY);

        $hora_actual=date("H:i:s",time());
       
        

        if($login->isLoged()){
          if (!$session_exist) {

            if ($this->isAsaichiDay()) {
              $_SESSION['station']['id_estacion']=$_SESSION['stationID'];
                  if (strtotime($hora_actual)>=strtotime('08:15:00')) {
                   /* 
                 $diference=gmdate("H:i:s",strtotime($hora_actual)-strtotime('08:45:00'));
                  $sessions_model->addTiempoTarde($diference);
                  $sessions_model->putUserOnInicio(); */
                  
                  $url='login/setProcess/';
                  require 'application/views/templates/head.php';
                  require 'application/views/procesos/index.php';
                 }else{

                  
                  

              if ($sessions_model->newSession()) {
              $insertedCambio = $cambio_model->newCambio();
              if ($insertedCambio) {

                $cambio_model->setCurrentCambio();
                $sessions_model->initAsaichi(); 
                 header("Location:".URL.'asaichi');
              }
              }
                 
                 }
                   
                 

                }else{
                  
                  $url='login/setProcess/';
                    
                  require 'application/views/templates/head.php';
                  require 'application/views/procesos/index.php';
                }

          
         
        }else{
           $_SESSION['tiroActual']=$cambio_model->getCurrentCambio();
        $seccion=$sessions_model->getSessionStatus();
          if ($seccion['actividad_actual']=='inicio') {
            $url='procesos/changeProcess/';
            require 'application/views/templates/head.php';
            require 'application/views/procesos/index.php';

          }elseif($seccion['actividad_actual']=='ajuste'){

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
           header("Location:".URL.'ajuste');
          }else{
            header("Location:".URL.'tiro');
          }

         

        }elseif($seccion['actividad_actual']=='encuesta'){

          header("Location:".URL.'encuesta');

        }

          
        
      }
  
    }else{      
      header("Location:".URL);  
    }
    }

    public function changeProcess(){
    session_start();
     $process_model=$this->loadModel('ProcessModel');
      $login_model = $this->loadModel('LoginModel');
      $sessions_model= $this->loadModel('SessionsModel');
      $cambio_model= $this->loadModel('CambioModel');

      $_process=$_POST['process_id'];
      $station=$_POST['station'];
      $process=array();
      $process[$_process]=$_process;
      $process_info=$login_model->getStationProcess($station);
      $_SESSION['process'][$_process]=$process_info[$_process];
      $_SESSION['processID']=$_process;
      $_SESSION['processSelected']=$_process;
      $_SESSION['station']['id_estacion']=$station;
      $_SESSION['ajusteStarted']='true';
      $process_model->setNewProcess();
      
  
  }
  public function isAsaichiDay(){

      
    
      
       if(date("w")==1||date("w")==4){
                    
            return true;
            
        }else{

            return false;
        }
  
  
  }
   

    
 
}

<?php

class CambioModel extends Controller
{
    
    function __construct($db)
    {
        try {
            $this->db = $db;
        } catch (PDOException $e) {
            exit('No se pudo establecer conexion con la base de datos.');
        }
    }

    

    public function checkForSession($stationId,$userId,$date){

        $sql = "SELECT * FROM sesiones WHERE estacion=$stationId AND operador=$userId AND fecha='$date' ";
        $query = $this->db->prepare($sql);
        $query->execute();

        if ($query->rowCount()>0) {
            
            return true;
            
        }else{

            return false;
        }
  
  
  }
  public function newSession(){
        
        $operador=$_SESSION['idUser'];
        $estacion=$_SESSION['stationID'];
        $today=TODAY;
        $time=date("H:i:s", time());

        $sql = "INSERT INTO sesiones(operador,estacion,proceso,actividad_actual,active,en_tiempo,asaichi_cumplido,fecha,inicio_ajuste) VALUES($operador,$estacion,NULL,2,1,1,1,'$today','$time')";

        $query = $this->db->prepare($sql);
        $inserted=$query->execute();

        if ($inserted) {
          $_SESSION['sessionID']=$this->db->lastInsertId();
          return true;
        }else{
          return false;
            }
  
  }


public function newCambio(){
        
        $station_id=$_SESSION['stationID'];
        $iduser=$_SESSION['idUser'];
        $sessionID=$_SESSION['sessionID'];
        $processID=$_SESSION['processID'];
        $today=TODAY;
        $time=date("H:i:s", time());

        $sql="INSERT INTO tiraje(id_estacion,id_proceso,horadeldia_ajuste, fechadeldia_ajuste,id_user,id_sesion) VALUES ($station_id,$processID,'$time','$today', $iduser,$sessionID)";
        $query = $this->db->prepare($sql);
        $inserted=$query->execute();

        if ($inserted) {
          $_SESSION['tiroActual']=$this->db->lastInsertId();
          return true;
        }else{
          return false;
            }
  
  }

 public function setCurrentCambio(){
        
        $station_id=$_SESSION['stationID'];
        $iduser=$_SESSION['idUser'];
        $sessionID=$_SESSION['sessionID'];
        $tiroActual=$_SESSION['tiroActual'];
        $today=TODAY;
        $time=date("H:i:s", time());

        $sql="UPDATE sesiones SET tiro_actual=$tiroActual WHERE id_sesion=".$sessionID;
        $query = $this->db->prepare($sql);
        $updated=$query->execute();

        if ($updated) {
          return true;
        }else{
          return false;
            }
  
  } 

 public function getCurrentCambio(){
    
        $sessionID=$_SESSION['sessionID'];

        $sql = "SELECT tiro_actual FROM sesiones WHERE id_sesion=".$sessionID;
        $query = $this->db->prepare($sql);
        $query->execute();

        
        return $query->fetch()->tiro_actual;
    }

public function newMemberCambio($userID,$memberSessionID,$memberProcessID){
        
        $station_id=$_SESSION['stationID'];
        $iduser=$_SESSION['idUser'];
        $sessionID=$_SESSION['sessionID'];
        $processID=$_SESSION['processID'];
        $today=TODAY;
        $hora_ajuste=date("H:i:s", time());
        $fin_ajuste=date("H:i:s", time());
        $time=date("H:i:s", time());
        $odt=(isset($_SESSION['odt']))? "'".$_SESSION['odt']."'" : "NULL" ;

        $sql="INSERT INTO tiraje(id_estacion,id_proceso,horadeldia_ajuste, horafin_ajuste,fechadeldia_ajuste,horadeldia_tiraje, id_user, is_virtual,odt_virtual,id_sesion) VALUES ($station_id,$memberProcessID,'$hora_ajuste','$hora_ajuste', '$today','$hora_ajuste',$userID,'true',$odt,$sessionID)";
        $query = $this->db->prepare($sql);
        $inserted=$query->execute();

        if ($inserted) {
            $tiro=$this->db->lastInsertId();
        $_SESSION['teamSession'][$userID]['memberTiroActual']=$tiro;
        $sql2="UPDATE sesion_equipo SET inicio_tiro='$time',tiro_actual=$tiro, tiempo_alert=NULL WHERE id_sesion_equipo=".$_SESSION['teamSession'][$userID]['memberSessionID'];
        $query2 = $this->db->prepare($sql2);
        $query2->execute(); 
        
          return true;
        }else{
          return false;
            }
  
  }

  public function completingTiro($post,$model){
        
        $today=TODAY;
        $time=date("H:i:s", time());
        $producto=17;
        $proceso=$post['proceso'];
        $iduser=$post['user'];
        $pedido=$post['pedido'];
        $recibidos=$post['recibidos'];
        $buenos=$post['buenos'];
        $merma=$post['merma'];
        $defectos=$post['defectos'];
        $tiempoTiraje=$post['tiempo-tiraje'];
        $ajuste=$post['ajuste'];
      
        $tiraje=$post['tiro-actual'];
        $seconds = strtotime("1970-01-01 $tiempoTiraje UTC");
        $standard=$model->getElementStandard($proceso,$producto);
        
        
        //$log= $this->loadController('logs');

        $tiraje_estandar=($seconds*$standard['piezas_por_hora'])/3600;
        $prodEsperada=round($tiraje_estandar); 
        if ($tiraje_estandar>0) {
            $predesemp=($buenos*100)/$tiraje_estandar;
            $tiraje_desemp=($predesemp>100)? 100 : $predesemp;
            
            }else{
              $tiraje_desemp=0;
             
        } 

        $sql="UPDATE tiraje set producto='$producto', pedido='$pedido', cantidad=$recibidos, buenos=$buenos, defectos=$defectos, merma=$merma,piezas_ajuste=$ajuste, merma_entregada=$merma, entregados=$buenos, tiempoTiraje='$tiempoTiraje', fechadeldia_tiraje='$today', horafin_tiraje='$time',id_user=$iduser,produccion_esperada=$prodEsperada,desempenio=$tiraje_desemp WHERE idtiraje=$tiraje";

        

        $query = $this->db->prepare($sql);
        $completed=$query->execute();

        if ($completed) {
          return true;
        }else{
        $log->lwrite( $sql,$today.'_ERROR_'.$iduser);
        $log->lclose();
          return false;
            }
  
  }

  




 
}

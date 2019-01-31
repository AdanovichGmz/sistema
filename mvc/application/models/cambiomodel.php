<?php

class CambioModel
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
        
        $operador=$_SESSION['user']['id'];
        $estacion=$_SESSION['station']['id_estacion'];
        $today=TODAY;
        $time=date("H:i:s", time());
        

        $sql = "INSERT INTO sesiones(operador,estacion,proceso,actividad_actual,active,en_tiempo,asaichi_cumplido,fecha,inicio_ajuste) VALUES($operador,$estacion,NULL,2,1,1,1,'$today','$time')";

        $query = $this->db->prepare($sql);
        $inserted=$query->execute();

        if ($inserted) {
          $_SESSION['sessionID']=$this->db->lastInsertId();
          return true;
        }else{
            $_SESSION['errors']='Fallo la insercion----'.$sql;
          return false;
            }
  
  }


public function newCambio(){
        
        $station_id=(isset($_SESSION['station']['id_estacion']))? $_SESSION['station']['id_estacion'] : 'NULL';
        $iduser=$_SESSION['user']['id'];
        $sessionID=$_SESSION['sessionID'];
        $processID=(isset($_SESSION['processSelected']))? $_SESSION['processSelected'] : 'NULL';
        $today=TODAY;
        $time=date("H:i:s", time());

        $sql="INSERT INTO tiraje(id_estacion,id_proceso,horadeldia_ajuste, fechadeldia_ajuste,id_user,id_sesion) VALUES ($station_id,$processID,'$time','$today', $iduser,$sessionID)";
        $query = $this->db->prepare($sql);
        $inserted=$query->execute();

        if ($inserted) {
          $_SESSION['tiroActual']=$this->db->lastInsertId();
          return true;
        }else{
        $_SESSION['errors']='Fallo la insercion----'.$sql;
          return false;
            }
  
  }

  
  public function removeActualTask(){
        
 
        $sessionID=$_SESSION['sessionID'];
       
        
        $sql="DELETE FROM personal_process WHERE status='actual' AND sesion=".$sessionID;
        $query = $this->db->prepare($sql);
        $deleted=$query->execute();

        if ($deleted) {
          
          return true;
        }else{
            $_SESSION['errors']='Fallo la consulta----'.$sql;
          return false;
            }
  
  }

 public function setCurrentCambio($section='ajuste'){
        
        $station_id=$_SESSION['station']['id_estacion'];
        $iduser=$_SESSION['user']['id'];
        $sessionID=$_SESSION['sessionID'];
        $tiroActual=$_SESSION['tiroActual'];
        $today=TODAY;
        $time=date("H:i:s", time());

        $sql="UPDATE sesiones SET tiro_actual=$tiroActual, actividad_actual='$section' WHERE id_sesion=".$sessionID;
        $query = $this->db->prepare($sql);
        $updated=$query->execute();

        if ($updated) {
          return true;
        }else{
            $_SESSION['errors']='Fallo la consulta----'.$sql;
          return false;
            }
  
  }
  
  public function cancellCambio(){
        
        
        $tiroActual=$_SESSION['tiroActual'];
        $today=TODAY;
        $time=date("H:i:s", time());

        $sql="UPDATE tiraje SET pedido=0,buenos=0,piezas_ajuste=0,defectos=0,merma=0,merma_entregada=0,entregados=0,produccion_esperada=0,desempenio=0,tiempoTiraje='".$_POST['time']."', cancelado=1, horafin_tiraje='".date(" H:i:s", time())."',fechadeldia_tiraje='".date("d-m-Y")."' WHERE idtiraje=".$tiroActual;
        $query = $this->db->prepare($sql);
        $updated=$query->execute();

        if ($updated) {
          return true;
        }else{
        $_SESSION['errors']='Fallo la consulta----'.$sql;
          return false;
            }
  
  } 

 public function getCurrentCambio(){
    
        $sessionID=$_SESSION['sessionID'];

        $sql = "SELECT tiro_actual FROM sesiones WHERE id_sesion=".$sessionID;
        $query = $this->db->prepare($sql);
        

        if ($query->execute()) {
           return $query->fetch()->tiro_actual;
        }else{
            $_SESSION['errors']='Fallo la consulta----'.$sql;
            return false;
        }
        
    }

public function newMemberCambio($userID,$memberSessionID,$memberProcessID){
        
        $station_id=$_SESSION['station']['id_estacion'];
        $iduser=$_SESSION['user']['id'];
        $sessionID=$_SESSION['sessionID'];
        $processID=$_SESSION['processSelected'];
        $today=TODAY;
        $hora_ajuste=date("H:i:s", time());
        $fin_ajuste=date("H:i:s", time());
        $time=date("H:i:s", time());
        $odt=(isset($_SESSION['odt']))? "'".$_SESSION['odt']."'" : "NULL" ;

        $sql="INSERT INTO tiraje(id_estacion,id_proceso, fechadeldia_ajuste, id_user, is_virtual,odt_virtual,id_sesion) VALUES ($station_id,$memberProcessID, '$today',$userID,'true',$odt,$sessionID)";
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
        $_SESSION['errors'].=' Fallo la insercion----'.$sql;
        $_SESSION['errors'].=' Fallo la consulta----'.$sql2;
          return false;
            }
  
  }

 public function completingTiro($post,$model){
        
        $today=TODAY;
        $time=date("H:i:s", time());
        if ($_SESSION['is_virtual']=='false') {
           $producto=$_SESSION['producto'];
        }else{
           $producto='NULL'; 
        }
        
        $proceso=$post['proceso'];
        $iduser=$post['user'];
        $pedido=$post['pedido'];
        $recibidos=$post['recibidos'];
        $buenos=$post['buenos']-$post['merma'];
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

        //$log->lwrite('proceso: '.$proceso,$today.'_ESTANDARES_'.$iduser);
        //$log->lwrite('producto: '.$producto,$today.'_ESTANDARES_'.$iduser);

        
        if (is_array($standard)) {
           // $log->lwrite('standard: '.implode(' | ', $standard),$today.'_ESTANDARES_'.$iduser);
        }else{

            //$log->lwrite('standard: '.$standard,$today.'_ESTANDARES_'.$iduser);
        }
$random=(isset($_SESSION['randomTasks'][$iduser]))? "elemento_virtual='".$_SESSION['randomTasks'][$iduser]."', ":'';
        


        //$log->lclose();

        $sql="UPDATE tiraje set producto=$producto, pedido='$pedido', cantidad=$recibidos, buenos=$buenos, defectos=$defectos, merma=$merma,piezas_ajuste=$ajuste, merma_entregada=$merma,$random entregados=$buenos, tiempoTiraje='$tiempoTiraje', fechadeldia_tiraje='$today', horafin_tiraje='$time',id_user=$iduser,produccion_esperada=$prodEsperada,desempenio=$tiraje_desemp WHERE idtiraje=$tiraje";

        

        $query = $this->db->prepare($sql);
        $completed=$query->execute();

        if ($completed) {

            if ($_SESSION['is_virtual']=='false') {
               $sql2="UPDATE procesos set avance='completado' WHERE id_proceso=".$_SESSION['registro_proceso'];
                $query2 = $this->db->prepare($sql2);
                $completed2=$query2->execute();
                if ($completed2) {
                   return true;
                }else{
                     $_SESSION['errors']='No se completo el proceso------'.$sql2;
                    return false;
                }
            }else{
                return true;
            }

            
          
        }else{
        //$log->lwrite( $sql,$today.'_ERROR_'.$iduser);
        //$log->lclose();
            $_SESSION['errors']='Fallo la actualizacion------'.$sql;
          return false;
            }
  
  }
  
  public function completeQueueTiro($ajuste_time){
        
        $today=TODAY;
        $time=date("H:i:s", time());
        if ($_SESSION['is_virtual']=='false') {
           $producto=$_SESSION['producto'];
        }else{
           $producto='NULL'; 
        }
        
        $proceso=$_SESSION['processID'];
        $iduser=$_SESSION['user']['id'];
        
        $seconds = strtotime("1970-01-01 $tiempoTiraje UTC");
        
        
       

        
    $random=(isset($_SESSION['randomTasks'][$iduser]))? "elemento_virtual='".$_SESSION['randomTasks'][$iduser]."', ":'';
        
        

        $sql="UPDATE tiraje set tiempo_ajuste='$ajuste_time', id_proceso=$proceso, horafin_ajuste='$time',horadeldia_tiraje='$time', producto=$producto, pedido='0', cantidad=0, buenos=0, defectos=0, merma=0,piezas_ajuste=0, merma_entregada=0,$random entregados=0, tiempoTiraje='00:00:00', fechadeldia_tiraje='$today', horafin_tiraje='$time',id_user=$iduser,produccion_esperada=0,desempenio=0 WHERE idtiraje=$tiraje";

        

        $query = $this->db->prepare($sql);
        $completed=$query->execute();

        if ($completed) {

            
                return true;
            

            
          
        }else{
        
            $_SESSION['errors']='Fallo la actualizacion------'.$sql;
          return false;
            }
  
  }


  




 
}

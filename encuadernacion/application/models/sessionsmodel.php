<?php

class SessionsModel
{
    
    function __construct($db)
    {
        try {
            $this->db = $db;
        } catch (PDOException $e) {
            exit('No se pudo establecer conexion con la base de datos.');
        }
    }

    
    public function getAmountOfSongs()
    {
        $sql = "SELECT COUNT(id) AS amount_of_songs FROM song";
        $query = $this->db->prepare($sql);
        $query->execute();

       
        return $query->fetch()->amount_of_songs;
    }

    public function checkForSession($stationId,$userId,$date){

        if ($stationId!=null) {
          $sql = "SELECT * FROM sesiones WHERE estacion=$stationId AND operador=$userId AND fecha='$date' ";
        $query = $this->db->prepare($sql);
        $query->execute();

        if ($query->rowCount()>0) {
            $_SESSION['sessionID']=$query->fetch()->id_sesion;            
            return true;
            
        }else{

            return false;
        }
        }else{
            return false;
        }

        
  
  
  }
  public function newSession(){

        if (isset($_SESSION['stationID'])) {
           $operador=$_SESSION['idUser'];
        $estacion=$_SESSION['stationID'];
        $processID=$_SESSION['processID'];
        $today=TODAY;
        $time=date("H:i:s", time());

        $sql = "INSERT INTO sesiones(operador,estacion,proceso,actividad_actual,active,en_tiempo,asaichi_cumplido,fecha) VALUES($operador,$estacion,$processID,'inicio',1,1,1,'$today')";

        $query = $this->db->prepare($sql);
        $inserted=$query->execute();

        if ($inserted) {
          $_SESSION['sessionID']=$this->db->lastInsertId();
          return true;
        }else{
          return false;
            }
        }else{
            return false;
        }
        
        
  
  }
   public function getTeamMembers(){

        $sql = "SELECT * FROM usuarios WHERE team_member='true'";
        $query = $this->db->prepare($sql);
        $query->execute();

        return $query->fetchAll();
    }

    public function getTeamMembersBySession($sessionID){
        $result=array();
        $sql = "SELECT id_usuario FROM usuarios_sesion_equipo WHERE id_sesion='$sessionID'";
        $query = $this->db->prepare($sql);
        $query->execute();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $sql2="SELECT * FROM usuarios WHERE id=".$row['id_usuario'];
            $query2 = $this->db->prepare($sql2);
            $query2->execute();
        $result[]=$query2->fetch(PDO::FETCH_ASSOC);
        
    }

        return $result;
    }
    

    public function getSessionStatus(){

        $sql = "SELECT * FROM sesiones WHERE id_sesion=".$_SESSION['sessionID'];
        $query = $this->db->prepare($sql);
        $query->execute();
        $status=$query->fetch(PDO::FETCH_ASSOC);
        return $status;
    }
    public function checkSessionByUser($userId){

        $sql = "SELECT * FROM sesion_equipo WHERE miembro=$userId AND id_sesion=".$_SESSION['sessionID'];
        $query = $this->db->prepare($sql);
        $query->execute();
       
         if ($query->rowCount()>0) {
            return true;
         }else{
                    return false;
                }
          


    }
    public function newMemberSession($userId,$process,$section){
        
        $operador=$_SESSION['idUser'];
        $sesion=$_SESSION['sessionID'];
        
        $today=TODAY;
        $time=date("H:i:s", time());

        $sql = "INSERT INTO `sesion_equipo` (`id_sesion_equipo`, `miembro`, `proceso`, `actividad_actual`, `active`, `inicio_tiro`, `tiempo_alert`, `tiempo_comida`, `id_sesion`,`fecha`) VALUES (NULL, $userId, $process, '$section', 'true', '$time', NULL, NULL, $sesion,'$today')";

        $query = $this->db->prepare($sql);
        $inserted=$query->execute();

        if ($inserted) {
          $_SESSION['teamSession'][$userId]['memberSessionID']=$this->db->lastInsertId();
          $_SESSION['teamSession'][$userId]['memberProcessID']=$process;

          return true;
        }else{
          return false;
            }
  
  }
    public function getMemberActivity($userId){

        $sql = "SELECT actividad_actual FROM sesion_equipo WHERE miembro=$userId AND id_sesion=".$_SESSION['sessionID'];
        $query = $this->db->prepare($sql);
        $query->execute();

         if ($query->rowCount()>0) {
            return $query->fetch()->actividad_actual;
         }else{
                    return 'ajuste';
                }
        
    }
    public function putMemberOnAlert($sessionId){

        $time=date("H:i:s", time());
        $sql = "UPDATE sesion_equipo SET inicio_alert='$time', actividad_actual='alerta' WHERE id_sesion_equipo=".$sessionId;
        $query = $this->db->prepare($sql);
        
        $updated=$query->execute();
         if ($updated) {
            return true;
         }else{
                    return false;
                }
        
    }

    
    public function recoverTeam(){
        $members=array();
        $sql = "SELECT * FROM sesion_equipo WHERE id_sesion=".$_SESSION['sessionID'];
        $query = $this->db->prepare($sql);
        $query->execute();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            
            $members[$row['miembro']]['memberSessionID'] =$row['id_sesion_equipo'] ;
            $members[$row['miembro']]['memberProcessID'] =$row['proceso'] ;
            $members[$row['miembro']]['memberTiroActual'] =$row['tiro_actual'] ;
}
        
        return $members;
        
    }
    
    public function setOdtInSession($odt){

        $time=date("H:i:s", time());
        $sql = "UPDATE sesiones SET orden_actual='$odt' WHERE id_sesion=".$_SESSION['sessionID'];
        $query = $this->db->prepare($sql);
        
        $updated=$query->execute();
         if ($updated) {
            return true;
         }else{
                    return false;
                }
        
    }
    public function initAjuste(){

        $time=date("H:i:s", time());
        $sql = "UPDATE sesiones SET inicio_ajuste='$time', actividad_actual='ajuste' WHERE id_sesion=".$_SESSION['sessionID'];
        $query = $this->db->prepare($sql);
        
        $updated=$query->execute();
         if ($updated) {
            return true;
         }else{
                    return false;
                }
        
    }

    
    public function saveAjuste($member,$memberSessionID,$memberProcessID,$memberTiroActual,$timeAjuste,$horadeldia){

        $time=date("H:i:s", time());


        $sql = "UPDATE sesion_equipo SET inicio_tiro='$time', actividad_actual='tiro' WHERE id_sesion_equipo=".$memberSessionID;
        $query = $this->db->prepare($sql);
        $sql2 = "UPDATE tiraje SET horadeldia_ajuste='$horadeldia', horafin_ajuste='$time', horadeldia_tiraje='$time',fechadeldia_tiraje='".TODAY."', tiempo_ajuste='$timeAjuste' WHERE idtiraje=".$memberTiroActual;
        $query2 = $this->db->prepare($sql2);
        $i=0;
        echo  $sql2;
        $updated1=$query->execute();
        $updated2=$query2->execute();
         if ($updated1) {
            $i++;
         }
         if ($updated2) {
            $i++;
         }
         if ($i==2) {
            return true;
         }else{
            return false;
         }
        
    }

    public function putTeamOnAlert($section){

        
        $time=date("H:i:s", time());
        $sql = "UPDATE sesiones SET inicio_alert='$time', seccion_alert='$section', actividad_actual='alerta' WHERE id_sesion=".$_SESSION['sessionID'];
        $query = $this->db->prepare($sql);
        
        $updated=$query->execute();
         if ($updated) {
            return true;
         }else{
                    return false;
                }
        
    }
    public function putTeamMembersOnAlert($section){

        
        $time=date("H:i:s", time());
        $sql = "UPDATE sesion_equipo SET inicio_alert='$time', actividad_actual='alerta' WHERE id_sesion=".$_SESSION['sessionID'];
        $query = $this->db->prepare($sql);
        
        $updated=$query->execute();
         if ($updated) {
            return true;
         }else{
                    return false;
                }
        
    }
    public function putTeamOnAjuste(){

        $time=date("H:i:s", time());
        $sql = "UPDATE sesiones SET inicio_ajuste='$time', actividad_actual='ajuste' WHERE id_sesion=".$_SESSION['sessionID'];
        $query = $this->db->prepare($sql);
        
        $updated=$query->execute();
         if ($updated) {
            return true;
         }else{
                    return false;
                }
        
    }
    public function putTeamOnInicio(){

        $time=date("H:i:s", time());
        $sql = "UPDATE sesiones SET actividad_actual='inicio' WHERE id_sesion=".$_SESSION['sessionID'];
        $query = $this->db->prepare($sql);
        
        $updated=$query->execute();
         if ($updated) {
            return true;
         }else{
                    return false;
                }
        
    }

    public function putTeamOnTiro(){

        $time=date("H:i:s", time());
        $sql = "UPDATE sesiones SET actividad_actual='tiro' WHERE id_sesion=".$_SESSION['sessionID'];
        $query = $this->db->prepare($sql);
        
        $updated=$query->execute();
         if ($updated) {
            return true;
         }else{
                    return false;
                }
        
    }
    public function initTeamTiro(){

        $time=date("H:i:s", time());
        $sql = "UPDATE sesiones SET inicio_tiro='$time',actividad_actual='tiro' WHERE id_sesion=".$_SESSION['sessionID'];
        $query = $this->db->prepare($sql);
        
        $updated=$query->execute();
         if ($updated) {
            return true;
         }else{
                    return false;
                }
        
    }
    


    public function putTeamOnLunchTime(){

        $time=date("H:i:s", time());
        $sql = "UPDATE sesiones SET inicio_comida='$time', actividad_actual='comida' WHERE id_sesion=".$_SESSION['sessionID'];
        $query = $this->db->prepare($sql);
        
        $updated=$query->execute();
         if ($updated) {
            return true;
         }else{
                    return false;
                }
        
    }
    public function putMemberOnAjuste($sessionId){

        $time=date("H:i:s", time());
        $sql = "UPDATE sesion_equipo SET inicio_alert='$time', actividad_actual='ajuste' WHERE id_sesion_equipo=".$sessionId;
        $query = $this->db->prepare($sql);
        
        $updated=$query->execute();
         if ($updated) {
            return true;
         }else{
                    return false;
                }
        
    }
    public function putMemberOnLunchTime($sessionId){

        $time=date("H:i:s", time());
        $sql = "UPDATE sesion_equipo SET inicio_comida='$time', actividad_actual='comida' WHERE id_sesion_equipo=".$sessionId;
        $query = $this->db->prepare($sql);
        
        $updated=$query->execute();
         if ($updated) {
            return true;
         }else{
                    return false;
                }
        
    }
    
    public function putMemberOnTiro($sessionId,$member){

        $time=date("H:i:s", time());
        $sql = "UPDATE sesion_equipo SET inicio_alert='$time', actividad_actual='tiro' WHERE miembro=$member AND id_sesion_equipo=".$sessionId;
        $query = $this->db->prepare($sql);
        
        $updated=$query->execute();
         if ($updated) {
            return true;
         }else{
                    return false;
                }
        
    }
    public function endAlert($sessionId){

        $time=date("H:i:s", time());
        $sql = "UPDATE sesion_equipo SET inicio_alert=NULL, actividad_actual='tiro' WHERE id_sesion_equipo=".$sessionId;
        $query = $this->db->prepare($sql);
        
        $updated=$query->execute();
         if ($updated) {
            return true;
         }else{
                    return false;
                }
        
    }
    public function endLunch($sessionId){

        $time=date("H:i:s", time());
        $sql = "UPDATE sesion_equipo SET inicio_comida=NULL, actividad_actual='tiro' WHERE id_sesion_equipo=".$sessionId;
        $query = $this->db->prepare($sql);
        
        $updated=$query->execute();
         if ($updated) {
            return true;
         }else{
                    return false;
                }
        
    }
    
    public function addAlertTiempoMuerto($opcion,$observaciones,$tiempo,$estacion,$usuario,$horaInicio,$tiro){

        $fin=date("H:i:s", time());
        $date=TODAY;
        $sql = "INSERT INTO `alertamaquinaoperacion` (`idalertamaquina`, `radios`, `observaciones`, `tiempoalertamaquina`, `id_estacion`, `id_usuario`, `horadeldiaam`, `horafin_alerta`, `fechadeldiaam`, `id_tiraje`, `es_tiempo_muerto`) VALUES (NULL, '$opcion', '$observaciones', '$tiempo', '$estacion', '$usuario', '$horaInicio', '$fin', '$date', $tiro, 'true')";
        $query = $this->db->prepare($sql);
        
        $saved=$query->execute();
         if ($saved) {
            return true;
         }else{
                    return false;
                }
        
    }
    public function addAlertAjuste($opcion,$observaciones,$tiempo,$estacion,$usuario,$horaInicio,$tiro){

        $fin=date("H:i:s", time());
        $date=TODAY;
        $sql = "INSERT INTO `alertageneralajuste` (`idalertamaquina`, `radios`, `observaciones`, `tiempoalertamaquina`, `id_estacion`, `id_usuario`, `horadeldiaam`,`fechadeldiaam`,`id_tiraje`, `horafin_alerta`,   `es_tiempo_muerto`) VALUES (NULL, '$opcion', '$observaciones', '$tiempo', '$estacion', '$usuario', '$horaInicio','$date',$tiro, '$fin', 'true')";
        $query = $this->db->prepare($sql);
        
        $saved=$query->execute();
         if ($saved) {
            return true;
         }else{
                    return false;
                }
        
    }
    public function addLunchTime($opcion,$tiempo,$estacion,$usuario,$horaInicio,$tiro,$section){

        $fin=date("H:i:s", time());
        $date=TODAY;
        $stationId=$_SESSION['stationID'];
        $sql = "INSERT INTO `breaktime` (`idbreaktime`, `radios`, `otra_actividad`, `breaktime`, `id_estacion`, `id_usuario`, `id_tiraje`, `seccion`, `horadeldiaam`, `hora_fin_comida`, `fechadeldiaam`) VALUES (NULL, '$opcion', NULL, '$tiempo', $stationId, $usuario, $tiro, '$section', '$horaInicio', '$fin', '$date')";
        $query = $this->db->prepare($sql);
        
        $saved=$query->execute();
         if ($saved){
            return true;
         }else{
            return false;
                }
        
    }

    public function  addMemeberToTeam($worker,$sessionID,$role){

        $fecha=TODAY;
        $sql = "INSERT INTO `usuarios_sesion_equipo` (`id`, `id_usuario`, `id_sesion`, `fecha`, `team_leader`) VALUES (NULL, $worker, $sessionID, '$fecha','$role')";

        $query = $this->db->prepare($sql);
        
        $inserted=$query->execute();
         if ($inserted) {
            return true;
         }else{
            return false;
                }
        
    }

   public function  getDisponibleTeams(){

        $today=TODAY;
        $sql = "SELECT id_usuario, id_sesion FROM usuarios_sesion_equipo WHERE team_leader='true' AND fecha='$today' ";
        $result=array();
        $query = $this->db->prepare($sql);
        $query->execute();

        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
        $sql2 = "SELECT * FROM usuarios WHERE id=".$row['id_usuario'];

        $query2 = $this->db->prepare($sql2);
        $query2->execute();
        $team=$query2->fetch(PDO::FETCH_ASSOC);
        $result[$row['id_sesion']]=$team;

        }

        return $result;
         
        
    }
  
    public function  userIsTaken($userID){

        $today=TODAY;
        $sql = "SELECT * FROM usuarios_sesion_equipo WHERE fecha='$today' AND id_sesion NOT IN(".$_SESSION['sessionID'].") AND id_usuario=".$userID;
        
        $query = $this->db->prepare($sql);
        $query->execute();

        if ($query->rowCount()>0) {
            return true;
         }else{
                    return false;
                }

    
         
        
    }
    public function cleanTeam(){
         $sql = "DELETE FROM usuarios_sesion_equipo WHERE id_sesion=".$_SESSION['sessionID'];
        
        $query = $this->db->prepare($sql);
        $query->execute();
        $sql2 = "DELETE FROM sesion_equipo WHERE id_sesion=".$_SESSION['sessionID'];
        
        $query2 = $this->db->prepare($sql2);
        $query2->execute();

        if ($query->rowCount()>0) {
            return true;
         }else{
                    return false;
                }
    }
 
}

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

        $sql = "SELECT * FROM sesiones WHERE estacion=$stationId AND operador=$userId AND fecha='$date' ";
        $query = $this->db->prepare($sql);
        $query->execute();

        if ($query->rowCount()>0) {
            $_SESSION['sessionID']=$query->fetch()->id_sesion;            
            return true;
            
        }else{

            return false;
        }
  
  
  }

  

  public function newSession(){
        
        $operador=$_SESSION['user']['id'];
        $estacion=(isset($_SESSION['station']['id_estacion']))? $_SESSION['station']['id_estacion']:((isset($_SESSION['stationID']))?$_SESSION['stationID']:'NULL');
        $processID=(isset($_SESSION['processSelected']))? $_SESSION['processSelected'] : 'NULL';
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
  
  }
   public function getTeamMembers(){

        $sql = "SELECT * FROM usuarios WHERE team_member='true'";
        $query = $this->db->prepare($sql);
        $query->execute();

        return $query->fetchAll();
    }

    public function getSessionOperarios(){
        $result=array();
        $sql = "SELECT * FROM usuarios WHERE app_active='true'";
        $query = $this->db->prepare($sql);
        $query->execute();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            
        $result[]=$row;
        
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
    public function getSessionStatusById($session){

        $sql = "SELECT * FROM sesiones WHERE id_sesion=".$session;
        $query = $this->db->prepare($sql);
        $query->execute();
        $status=$query->fetch(PDO::FETCH_ASSOC);
        return $status;
    }
    public function getSessionIdByUser($user){

        $sql = "SELECT id_sesion FROM sesiones WHERE fecha='".TODAY."' AND operador=".$user;
        $query = $this->db->prepare($sql);
        $query->execute();
        $status=$query->fetch(PDO::FETCH_ASSOC);
        return $status['id_sesion'];
    }
    public function checkSessionByUser($userId){

        $sql = "SELECT * FROM sesiones WHERE operador=$userId AND fecha='".TODAY."'";
        $query = $this->db->prepare($sql);
        $query->execute();
       
         if ($query->rowCount()>0) {
            return true;
         }else{
                    return false;
                }
    }
    public function newMemberSession($userId,$process){
        
        $operador=$_SESSION['user']['id'];
        $sesion=$_SESSION['sessionID'];
        
        $today=TODAY;
        $time=date("H:i:s", time());

        $sql = "INSERT INTO `sesion_equipo` (`id_sesion_equipo`, `miembro`, `proceso`, `actividad_actual`, `active`, `inicio_tiro`, `tiempo_alert`, `tiempo_comida`, `id_sesion`) VALUES (NULL, $userId, $process, 'tiro', 'true', '$time', NULL, NULL, $sesion)";

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

  
  public function addAjusteToQueue($ontime,$time_elapsed){
        
        $operador=$_SESSION['user']['id'];
        $sesion=$_SESSION['sessionID'];
        $process=$_SESSION['processID'];
        $tiro=$_SESSION['tiroActual'];
        $today=TODAY;
        $time=date("H:i:s", time());

        $sql = "INSERT INTO `en_cola` (`id_cola`, `id_tiro`, `en_tiempo`, `seccion`, `hora`, `fecha`, `sesion`, `estatus`, `proceso`, `tiempo`, `team_sesion`, `team_member`) VALUES (NULL, $tiro, '$ontime', 'ajuste', '$time', '$today', $sesion, 'pendiente', $process, '$time_elapsed', NULL, NULL)";

        $query = $this->db->prepare($sql);
        $inserted=$query->execute();

        if ($inserted) {
          return true;
        }else{
          return false;
          echo  $sql;
            }
  
  }

  public function addEncuesta($post){
        
        $operador=$_SESSION['user']['id'];
        $sesion=$_SESSION['sessionID'];
        $station=$_SESSION['station']['id_estacion'];
        $today=TODAY;
        $lento=$post['lento'];
        $lento_exp=($post['lento']=='SI')? "'".$post['radios-lento']."'" :'NULL';
        $bien=$post['bien'];
        $bien_exp=($post['bien']=='NO')? "'".$post['radios-mal']."'" :'NULL';
        $time=date("H:i:s", time());
        $observaciones=(!empty($post['observaciones']))? "'".$post['observaciones']."'" :'NULL';

        $sql = "INSERT INTO `encuesta` (`idencuesta`, `id_usuario`, `id_estacion`, `horadeldia`, `fechadeldia`, `desempeno`, `problema`, `calidad`, `problema2`, `observaciones`) VALUES (NULL, $operador, $station, '$time', '$today', '$lento', $lento_exp, '$bien', $bien_exp, $observaciones)";

        $query = $this->db->prepare($sql);
        $inserted=$query->execute();

        if ($inserted) {
          
          return true;
        }else{
          return false;
            }

  
  }
   
    public function addTiempoTarde($diference){
        
        $operador=$_SESSION['user']['id'];
        $sesion=$_SESSION['sessionID'];
        
        $today=TODAY;
        $time=date("H:i:s", time());
        $station_id=$_SESSION['station']['id_estacion'];
        $iduser=$_SESSION['user']['id'];
        $tiraje=$_SESSION['tiroActual'];
        $sql = "INSERT INTO `tiempo_muerto` (`id_tiempo_muerto`, `tiempo_muerto`, `fecha`, `id_estacion`, `id_user`, `numodt`, `id_orden`, `seccion`, `hora_del_dia`, `id_tiraje`, `id_sesion`) VALUES (NULL, '$diference', '$today', $station_id, $iduser, NULL, NULL, 4, '$time', $tiraje, ".$_SESSION['sessionID'].")";

        $query = $this->db->prepare($sql);
        $inserted=$query->execute();

        if ($inserted) {
           return true;
        }else{
          return false;
            }
  
  }

    public function getOperActivity($userId){

        $sql = "SELECT actividad_actual FROM sesiones WHERE operador=$userId AND fecha='".TODAY."'";
        $query = $this->db->prepare($sql);
        $query->execute();

         if ($query->rowCount()>0) {
            return $query->fetch()->actividad_actual;
         }else{
                    return 'inicio';
                }
        
    }
    public function putUserOnAlert($sessionId,$seccion='ajuste'){

        $time=date("H:i:s", time());
        $sql = "UPDATE sesiones SET inicio_alert='$time', actividad_actual='alerta', seccion_alert='$seccion' WHERE id_sesion=".$sessionId;
        $query = $this->db->prepare($sql);
        
        $updated=$query->execute();
         if ($updated) {
            return true;
         }else{
                    return false;
                }
        
    }


    public function changeToAjuste(){

        $time=date("H:i:s", time());
        $sql = "UPDATE sesiones SET actividad_actual='ajuste'WHERE id_sesion=".$_SESSION['sessionID'];
        $query = $this->db->prepare($sql);
        
        $updated=$query->execute();
         if ($updated) {
            return true;
         }else{
                    return false;
                }
        
    }
    
    public function changeToInicio(){

        $time=date("H:i:s", time());
        $sql = "UPDATE sesiones SET actividad_actual='inicio'WHERE id_sesion=".$_SESSION['sessionID'];
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

    public function initAsaichi(){

        $time=date("H:i:s", time());
        $sql = "UPDATE sesiones SET inicio_asaichi='$time', actividad_actual='asaichi' WHERE id_sesion=".$_SESSION['sessionID'];
        $query = $this->db->prepare($sql);
        
        $updated=$query->execute();
         if ($updated) {
            return true;
         }else{
                    return false;
                }
        
    }

    
    public function initTiempoComida(){

        $time=date("H:i:s", time());
        $sql = "UPDATE sesiones SET tiempo_comida=NULL WHERE id_sesion=".$_SESSION['sessionID'];
        $query = $this->db->prepare($sql);
        
        $updated=$query->execute();
         if ($updated) {
            return true;
         }else{
                    return false;
                }
        
    }

    
    public function saveAjuste($tiroActual,$timeAjuste,$horadeldia,$idorden=null,$v_odt=null,$idv_elem,$is_virtual,$elem_v,$estandar,$ontime){

        $time=date("H:i:s", time());
        $id_orden=($idorden==null)? 'null':$idorden;
        $virtual_odt=($v_odt==null)? 'null': "'".$v_odt."'";
        $v_id_elem=($idv_elem==null)? 'null': "'".$idv_elem."'";
        $elem_v=($elem_v==null)? 'null': "'".$elem_v."'";

        $secondsAjuste = strtotime("1970-01-01 $timeAjuste UTC");
        if ($ontime=='true') {
            echo "estandar: ".$estandar.' segundos: '.$secondsAjuste;
            $tiempoTotal="'".gmdate("H:i:s",($estandar-$secondsAjuste))."'";
        }else{
            $tiempoTotal="'".gmdate("H:i:s",$estandar)."'";
        }
        
        


        $sql = "UPDATE tiraje SET horadeldia_ajuste='$horadeldia', horafin_ajuste='$time', horadeldia_tiraje='$time',fechadeldia_tiraje='".TODAY."', tiempo_ajuste=$tiempoTotal, id_orden=$id_orden, odt_virtual=$virtual_odt,is_virtual='$is_virtual',id_elem_virtual=$v_id_elem,elemento_virtual=$elem_v WHERE idtiraje=".$tiroActual;
        $query = $this->db->prepare($sql);
        
        
        $updated1=$query->execute();
        
         if ($updated1){
            return true;
         }else{
            return false;
         }
        
    }

    public function saveAsaichi($user,$time,$id_sesion,$inicio){

        $horafin=date("H:i:s", time());
        if ($_SESSION['multi_process']=='true') {
            $activ='inicio';
            $sqlm = "UPDATE sesiones SET inicio_ajuste='$horafin' WHERE id_sesion=".$id_sesion;
            $querym = $this->db->prepare($sqlm);
            $updatedm=$querym->execute();
        }else{
            $activ='ajuste';
        }

        $sql = "INSERT INTO `asaichi` (`idasaichi`, `tiempo`, `id_usuario`, `horadeldia`, `hora_fin`, `fechadeldia`) VALUES (NULL, '$time', $user, '$inicio', '$horafin','".TODAY."')";

        $query = $this->db->prepare($sql);
        $sql2 = "UPDATE sesiones SET actividad_actual='$activ', asaichi_cumplido='true' WHERE id_sesion=".$id_sesion;
        $query2 = $this->db->prepare($sql2);
        $i=0;
        
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
    
    public function setNoOnTime($sessionID){

        
        
        $sql = "UPDATE sesiones SET en_tiempo='false' WHERE id_sesion=".$sessionID;
        $query = $this->db->prepare($sql);
        
        $updated=$query->execute();
         if ($updated) {
            return true;
         }else{
                    return false;
                }
        
    }
    public function setOnTime($sessionID){

        
        
        $sql = "UPDATE sesiones SET en_tiempo='true' WHERE id_sesion=".$sessionID;
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

    public function putUserOnInicio(){

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
    public function initTiro(){

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
    public function putUserOnAjuste($sessionId){

        $time=date("H:i:s", time());
        $sql = "UPDATE sesiones SET inicio_alert=null, tiempo_alert_ajuste=NULL, tiempo_alert=NULL,tiempo_comida=NULL,inicio_ajuste='$time', actividad_actual='ajuste' WHERE id_sesion=".$sessionId;
        $query = $this->db->prepare($sql);
        
        $updated=$query->execute();
         if ($updated) {
            return true;
           
         }else{     
            
                    return false;
                }
        
    }
    public function putUserOnLunchTime($sessionId){

        $time=date("H:i:s", time());
        $sql = "UPDATE sesiones SET inicio_comida='$time', actividad_actual='comida', seccion_comida='tiro' WHERE id_sesion=".$sessionId;
        $query = $this->db->prepare($sql);
        
        $updated=$query->execute();
         if ($updated) {
            return true;
         }else{
                    return false;
                }
        
    }
    
    public function putMemberOnTiro($sessionId){

        $time=date("H:i:s", time());
        $sql = "UPDATE sesion_equipo SET inicio_alert='$time', actividad_actual='tiro' WHERE id_sesion_equipo=".$sessionId;
        $query = $this->db->prepare($sql);
        
        $updated=$query->execute();
         if ($updated) {
            return true;
         }else{
                    return false;
                }
        
    }
   

     public function endAlertTiro($tiempo){

        $time=date("H:i:s", time());

        $sql0 = "SELECT IFNULL(tiempo_alert,0)AS t_alert FROM sesiones WHERE id_sesion=".$_SESSION['sessionID'];
        $query0 = $this->db->prepare($sql0); 
       
        $query0->execute();
        $row = $query0->fetch(PDO::FETCH_ASSOC);
        $seconds=strtotime("1970-01-01 $tiempo UTC");
         
        $total=$seconds+$row['t_alert'];
        $sql = "UPDATE sesiones SET inicio_alert=NULL,tiempo_alert=$total,actividad_actual='tiro' WHERE id_sesion=".$_SESSION['sessionID'];
        $query = $this->db->prepare($sql); 
        
        $updated=$query->execute();
         if ($updated) {
            return true;
         }else{
                    return false;
                    
                }
        
    }
     public function endAlertAjuste($tiempo){

        $time=date("H:i:s", time());

        $sql0 = "SELECT IFNULL(tiempo_alert_ajuste,0)AS t_alert FROM sesiones WHERE id_sesion=".$_SESSION['sessionID'];
        $query0 = $this->db->prepare($sql0); 
       
        $query0->execute();
        $row = $query0->fetch(PDO::FETCH_ASSOC);
        $seconds=strtotime("1970-01-01 $tiempo UTC");
         
        $total=$seconds+$row['t_alert'];
        $sql = "UPDATE sesiones SET inicio_alert=NULL,tiempo_alert_ajuste=$total,actividad_actual='ajuste' WHERE id_sesion=".$_SESSION['sessionID'];
        $query = $this->db->prepare($sql); 
        
        $updated=$query->execute();
         if ($updated) {
            return true;
         }else{
                    return false;
                    
                }
        
    }
    public function endTiroLunch($tiempo){

        $seconds=strtotime("1970-01-01 $tiempo UTC");
        $time=date("H:i:s", time());
        $sql = "UPDATE sesiones SET inicio_comida=NULL, tiempo_comida=$seconds, actividad_actual='tiro' WHERE id_sesion=".$_SESSION['sessionID'];
        $query = $this->db->prepare($sql);
        
        $updated=$query->execute();
         if ($updated) {
            return true;
         }else{
                    return false;
                }
        
    }
    public function endAjusteLunch($tiempo){

        $seconds=strtotime("1970-01-01 $tiempo UTC");
        $time=date("H:i:s", time());
        $sql = "UPDATE sesiones SET inicio_comida=NULL, tiempo_comida=$seconds, actividad_actual='ajuste' WHERE id_sesion=".$_SESSION['sessionID'];
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
    public function addAusteDeadTime($time){

        $fin=date("H:i:s", time());
        $date=TODAY;
        $sql = "INSERT INTO `tiempo_muerto` (`id_tiempo_muerto`, `tiempo_muerto`, `fecha`, `id_estacion`, `id_user`, `numodt`, `id_orden`, `seccion`, `hora_del_dia`, `id_tiraje`, `id_sesion`) VALUES (NULL, '$time', '$date', ".$_SESSION['station']['id_estacion'].", ".$_SESSION['user']['id'].", NULL, NULL, 'ajuste', '$fin', ".$_SESSION['tiroActual'].", ".$_SESSION['sessionID'].")";
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
        $stationId=$_SESSION['station']['id_estacion'];
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
    public function  getPendingsByProcess($process){

        
        $sql = "SELECT * FROM en_cola WHERE sesion=".$_SESSION['sessionID']." AND proceso=".$process." ORDER BY id_cola ASC";
        $result=array();
        $query = $this->db->prepare($sql);
        $query->execute();

        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
        

        $result[]=$row;

        }

        return $result;
         
        
    }
    

   

    public function getWorkingInfo(){        

        $sql ="SELECT * FROM personal_process WHERE status='actual' AND sesion=".$_SESSION['sessionID'];
        $query = $this->db->prepare($sql);
        $query->execute();
        $result=array();
        $result=$query->fetch(PDO::FETCH_ASSOC);
        if ($query->rowCount()>0) {
        if (!empty($result['elemento_virtual'])) {
            $result['is_virtual']='true';
            $result['idproducto']=$result['id_elemento_virtual'];
            $result['parte']=$result['elemento_virtual'];
        }else{
            $result['is_virtual']='false';
            $sql2 ="SELECT o.producto,(SELECT nombre_elemento FROM elementos WHERE id_elemento=o.producto)AS elemento FROM ordenes o WHERE o.idorden=".$result['id_orden'];
            $query2 = $this->db->prepare($sql2);
            $query2->execute();
            $result2=$query2->fetch(PDO::FETCH_ASSOC);
            $result['idproducto']=$result2['producto'];
            $result['parte']=$result2['elemento'];
        }
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

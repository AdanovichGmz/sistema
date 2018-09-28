<?php

class ProcessModel
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
        
        $today=TODAY;
        $time=date("H:i:s", time());

        $sql="INSERT INTO tiraje(id_estacion,id_proceso,horadeldia_ajuste, fechadeldia_ajuste,id_user,id_sesion) VALUES ($station_id,NULL,'$time','$today', $iduser,$sessionID)";
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

 

public function getAjusteAlertOptions(){
        $sql = "SELECT * FROM opciones WHERE tipo_opcion='alerta_ajuste' AND id_proceso=".$_SESSION['processID'];
        $query = $this->db->prepare($sql);
        $query->execute();
        
        // $query->fetchAll(PDO::FETCH_ASSOC); or change libs/controller.php's PDO options to
        // $options = array(PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC ...
        return $query->fetchAll();
    }

public function getTiroAlertOptions($processID){
        $sql = "SELECT * FROM opciones WHERE tipo_opcion='alerta_tiro' AND id_proceso=".$processID;
        $query = $this->db->prepare($sql);
        $query->execute();
        
        // $query->fetchAll(PDO::FETCH_ASSOC); or change libs/controller.php's PDO options to
        // $options = array(PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC ...
        return $query->fetchAll();
    }
public function getWorkingOdts(){

        $processName=$_SESSION['processName'];
        $stationName=$_SESSION['stationName'];
        $sql = "SELECT pp.*,(SELECT producto FROM ordenes WHERE idorden=pp.id_orden) AS producto,p.nombre_proceso,p.reproceso FROM personal_process pp INNER JOIN procesos p ON pp.id_proceso=p.id_proceso WHERE proceso_actual='$stationName' AND nombre_proceso='$processName' AND avance NOT IN('completado') order by orden_display asc";
        $query = $this->db->prepare($sql);
        $query->execute();
        
        // $query->fetchAll(PDO::FETCH_ASSOC); or change libs/controller.php's PDO options to
        // $options = array(PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC ...
        return $query->fetchAll();
    }

public function getInitialOdts(){

        $processName=$_SESSION['processName'];

        $sql = "SELECT  o.idorden AS id_orden,o.numodt AS num_odt,o.fechafin,o.fechareg,o.producto,p.id_proceso,p.avance,p.reproceso,(SELECT orden_display FROM personal_process WHERE id_orden=o.idorden AND id_proceso=p.id_proceso) AS orden_display,(SELECT status FROM personal_process WHERE id_orden=o.idorden AND id_proceso=p.id_proceso) AS status FROM ordenes o LEFT JOIN procesos p ON p.id_orden=o.idorden WHERE nombre_proceso='$processName' AND o.numodt='$getodt' AND avance NOT IN('completado') order by fechafin asc LIMIT 12";
        $query = $this->db->prepare($sql);
        $query->execute();
        
        // $query->fetchAll(PDO::FETCH_ASSOC); or change libs/controller.php's PDO options to
        // $options = array(PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC ...
        return $query->fetchAll();
    }

public function odtExist(){

        $stationName=$_SESSION['stationName'];

        $sql = "SELECT COUNT(*) AS conteo FROM personal_process WHERE proceso_actual='$stationName'";
        $query = $this->db->prepare($sql);
        $query->execute();

        
        return $query->fetch()->conteo;
    }

     public function getProcesByUser($userId){
        $userStations=array();
        $stations=$this->getStationByUser($userId);
        foreach ($stations as $key => $station) {
            $sql = "SELECT ep.*,(SELECT nombre_proceso FROM procesos_catalogo WHERE id_proceso=ep.id_proceso)AS nombre_proceso, (SELECT nombre_estacion FROM estaciones WHERE id_estacion=ep.id_estacion)AS nombre_estacion FROM estaciones_procesos ep WHERE ep.id_estacion=".$station." ORDER BY nombre_proceso ASC";
            $query = $this->db->prepare($sql);
            $query->execute();

            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
        $userStations[$row['nombre_estacion']][]=$row;
        
        
    }

            
        }

       
       return $userStations;


    }

public function getEncuadernacionTasks(){
        $tasks=array();
       
            $sql = "SELECT * FROM actividades_encuadernacion ORDER BY nombre_actividad ASC";

            $query = $this->db->prepare($sql);
            $query->execute();

            while ($row = $query->fetch(PDO::FETCH_ASSOC)){

            $sql2 = "SELECT ap.*,(SELECT nombre_proceso FROM procesos_catalogo WHERE id_proceso=ap.id_proceso)AS n_proceso FROM actividades_procesos ap WHERE id_actividad=".$row['id_actividad']." ORDER BY n_proceso ASC";
            $query2 = $this->db->prepare($sql2);
            $query2->execute();

            $tasks[$row['id_actividad']]['name']=$row['nombre_actividad'];
            $childs=array();
            if ($query2->rowCount()==0) {
               $tasks[$row['id_actividad']]['has_child']='false';
               $tasks[$row['id_actividad']]['childs']='';
               $tasks[$row['id_actividad']]['id_proceso']=$row['id_proceso'];
            }else{
                $tasks[$row['id_actividad']]['has_child']='true';
                
                while ($row2 = $query2->fetch(PDO::FETCH_ASSOC)){
                   $childs[]=$row2; 
            }
            $tasks[$row['id_actividad']]['childs']=$childs;

            }
            

       
    }

    return $tasks;


    }

    public function getStationByUser($userId){        

        $sql = "SELECT id_estacion FROM usuarios_estaciones WHERE id_usuario='$userId'";
        $query = $this->db->prepare($sql);
        $query->execute();
        $result=array();
        
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
        $result[]=$row['id_estacion'];
        
    }
        
        return $result;
    }

    public function getPendingsByUser($userId){        
        $result=array();
        $sql = "SELECT * FROM en_cola WHERE sesion=".$_SESSION['sessionID']." AND team_member=".$userId;
        $query = $this->db->prepare($sql);
        $query->execute();
        $result=array();
        
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
        $result[$row['proceso']]=$row;
        
    }
        
        return $result;
    }
    public function setPending($userId){        
        $result=array();
        $sql = "SELECT * FROM en_cola WHERE sesion=".$_SESSION['sessionID']." AND team_member=".$userId;
        $query = $this->db->prepare($sql);
        $query->execute();
        $result=array();
        
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
        $result[$row['proceso']]=$row;
        
    }
        
        return $result;
    }

    public function getProcessName($processID){

        

        $sql = "SELECT nombre_proceso FROM procesos_catalogo WHERE id_proceso=".$processID;
        $query = $this->db->prepare($sql);
        $query->execute();
        $name=$query->fetch(PDO::FETCH_ASSOC);
        return $name['nombre_proceso'];
    }
    public function getTiroElapsedTime($memberSessionID){
        
        $sql = "SELECT * FROM sesion_equipo WHERE id_sesion_equipo=".$memberSessionID;
        $query = $this->db->prepare($sql);
        $query->execute();
        $sesion=$query->fetch(PDO::FETCH_ASSOC);
        $time=(strtotime(date("H:i:s",time()))-strtotime($sesion['inicio_tiro']))-(((empty($sesion['tiempo_alert']))? 0: $sesion['tiempo_alert'])+((empty($sesion['tiempo_comida']))? 0: $sesion['tiempo_comida']));
        return $time;
    }
    
    
    public function getAlertElapsedTime($memberSessionID){
        
        $sql = "SELECT * FROM sesion_equipo WHERE id_sesion_equipo=".$memberSessionID;
        $query = $this->db->prepare($sql);
        $query->execute();
        $sesion=$query->fetch(PDO::FETCH_ASSOC);
        $time=strtotime(date("H:i:s",time()))-strtotime($sesion['inicio_alert']);
        return $time;
    }
    public function getAjusteElapsedTime($sessionID){
        
        $sql = "SELECT * FROM sesiones WHERE id_sesion=".$sessionID;
        $query = $this->db->prepare($sql);
        $query->execute();
        $sesion=$query->fetch(PDO::FETCH_ASSOC);
        $time=(strtotime(date("H:i:s",time()))-strtotime($sesion['inicio_ajuste']))-(((empty($sesion['tiempo_alert']))? 0: $sesion['tiempo_alert'])+((empty($sesion['tiempo_comida']))? 0: $sesion['tiempo_comida']));
        return $time;
    }
    public function getLunchElapsedTime($memberSessionID){
        
        $sql = "SELECT * FROM sesion_equipo WHERE id_sesion_equipo=".$memberSessionID;
        $query = $this->db->prepare($sql);
        $query->execute();
        $sesion=$query->fetch(PDO::FETCH_ASSOC);
        $time=strtotime(date("H:i:s",time()))-strtotime($sesion['inicio_comida']);
        return $time;
    }
    public function getAjusteLunchElapsedTime(){
        
        $sql = "SELECT * FROM sesiones WHERE id_sesion=".$_SESSION['sessionID'];
        $query = $this->db->prepare($sql);
        $query->execute();
        $sesion=$query->fetch(PDO::FETCH_ASSOC);
        $time=strtotime(date("H:i:s",time()))-strtotime($sesion['inicio_comida']);
        return $time;
    }
    
    public function getAjusteStartingLunch(){
        
        $sql = "SELECT inicio_comida FROM sesiones WHERE id_sesion=".$_SESSION['sessionID'];
        $query = $this->db->prepare($sql);
        $query->execute();
        $sesion=$query->fetch(PDO::FETCH_ASSOC);
        
        return $sesion['inicio_comida'];
    }
    public function getStartingHourAlert($memberSessionID){
        
        $sql = "SELECT inicio_alert FROM sesion_equipo WHERE id_sesion_equipo=".$memberSessionID;
        $query = $this->db->prepare($sql);
        $query->execute();
        $sesion=$query->fetch(PDO::FETCH_ASSOC);
        
        return $sesion['inicio_alert'];
    }
    
    public function getStartingHourAjuste($sessionID){
        
        $sql = "SELECT inicio_ajuste FROM sesiones WHERE id_sesion=".$sessionID;
        $query = $this->db->prepare($sql);
        $query->execute();
        $sesion=$query->fetch(PDO::FETCH_ASSOC);
        
        return $sesion['inicio_ajuste'];
    }

    public function getStartingHourLunch($memberSessionID){
        
        $sql = "SELECT inicio_comida FROM sesion_equipo WHERE id_sesion_equipo=".$memberSessionID;
        $query = $this->db->prepare($sql);
        $query->execute();
        $sesion=$query->fetch(PDO::FETCH_ASSOC);
        
        return $sesion['inicio_comida'];
    }
    public function getElementStandard($processID,$element){
        
        $sql = "SELECT * FROM estandares WHERE id_proceso=$processID AND id_elemento= $element";
        $query = $this->db->prepare($sql);
        $query->execute();
        $standard=$query->fetch(PDO::FETCH_ASSOC);

        if ($query->rowCount()>0) {
            
            return $standard;
            
        }else{
            $sql2 = "SELECT * FROM estandares WHERE id_proceso=$processID AND id_elemento=144";
            $query2 = $this->db->prepare($sql2);
            $query2->execute();
            $standard_default=$query2->fetch(PDO::FETCH_ASSOC);
            return $standard_default;
        }
        
        
    }


    
 
}

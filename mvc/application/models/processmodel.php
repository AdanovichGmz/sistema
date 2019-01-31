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

  public function addTaskForThisUser($idOrden,$numOdt,$id_process,$status,$display,$planillas='null',$elem_virtual=null,$id_elem_v=null){
        
       
        $estacion=$_SESSION['stationID'];
        $sesion=$_SESSION['sessionID'];
        $today=TODAY;
        $time=date("H:i:s", time());
        $plans=($planillas==null)? 'null':$planillas;
        $e_virtual=($elem_virtual==null)? "null":"'".$elem_virtual."'";
        $id_e_virtual=($id_elem_v==null)? "null":"'".$id_elem_v."'";

        $sql = "INSERT INTO personal_process(id_pp,id_orden,num_odt,proceso_actual, id_proceso,status,orden_display,elemento_virtual,id_elemento_virtual,planillas_de,sesion) VALUES(null,$idOrden,'$numOdt',null,$id_process, '$status',$display,$e_virtual,$id_e_virtual,$plans,$sesion)";

        $query = $this->db->prepare($sql);
        $inserted=$query->execute();

        if ($inserted) {
         
          return true;
        }else{
            echo $sql;
          return false;
            }

            
  
  }


  
  public function getTasksInProcess($sessionID){
        $orders=array();
        $sql = "SELECT p.*,o.*,(SELECT nombre_elemento FROM elementos WHERE id_elemento=o.producto)AS elemento FROM personal_process p INNER JOIN ordenes o ON p.id_orden=o.idorden WHERE p.sesion=$sessionID ORDER BY p.orden_display ASC";
        $query = $this->db->prepare($sql);
        $query->execute();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
        $orders[]=$row;
        
    }

        return $orders;
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

  public function setEncuesta(){
        
        $station_id=$_SESSION['stationID'];
        
        $sessionID=$_SESSION['sessionID'];
        $tiroActual=$_SESSION['tiroActual'];
        $today=TODAY;
        $time=date("H:i:s", time());

        $sql="UPDATE sesiones SET actividad_actual='encuesta' WHERE id_sesion=".$sessionID;
        $query = $this->db->prepare($sql);
        $updated=$query->execute();

        if ($updated) {
          return true;
        }else{
          return false;
            }
  
  }

  
  public function setCurrentOdt($odt){
        
        

        $sql="UPDATE sesiones SET orden_actual='$odt' WHERE id_sesion=".$_SESSION['sessionID'];
        $query = $this->db->prepare($sql);
        $updated=$query->execute();

        if ($updated) {
          return true;
        }else{
          return false;
            }
  
  } 

 
  public function setRegistroProceso($id_proceso){
        
       
        $sessionID=$_SESSION['sessionID'];
    
        $sql="UPDATE sesiones SET registro_proceso=$id_proceso WHERE id_sesion=".$sessionID;

        $query = $this->db->prepare($sql);
        $updated=$query->execute();

        if ($updated) {
          return true;
        }else{
          return false;
            }
  
  }
  public function setIsVirtual($param){
        
       
        $sessionID=$_SESSION['sessionID'];
    
        $sql="UPDATE sesiones SET is_virtual='$param' WHERE id_sesion=".$sessionID;
        $query = $this->db->prepare($sql);
        $updated=$query->execute();

        if ($updated) {
          return true;
        }else{
          return false;
            }
  
  }
  public function setProduct($param){
        
       
        $sessionID=$_SESSION['sessionID'];
    
        $sql="UPDATE sesiones SET parte='$param' WHERE id_sesion=".$sessionID;
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
public function getEncuestaOptions($tipo){
        $sql = "SELECT * FROM opciones WHERE tipo_opcion='".$tipo."' AND id_proceso=".$_SESSION['processID'];
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

     public function getProcessElements(){
        $elements=array();
        $processID=$_SESSION['processID'];
            $sql = "SELECT * FROM estandares e INNER JOIN elementos el ON e.id_elemento=el.id_elemento WHERE e.id_proceso=$processID AND el.id_elemento NOT IN(144)  ORDER BY nombre_elemento ASC";
            $query = $this->db->prepare($sql);
            $query->execute();

            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
        $elements[]=$row;
        
        
    }

            
        

       
       return $elements;


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

    public function cleanPersonalProcess($sessionID){        

        $sql = "DELETE FROM personal_process WHERE sesion='$sessionID'";

        $query = $this->db->prepare($sql);
        $cleaned=$query->execute();
        
        
        if ($cleaned) {
            return true;
        }else{
            return false;
        }
        
        
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

    public function setInicioAjuste($sessionID){        
        $result=array();
        $hora=date("H:i:s",time());
        $sql = "UPDATE sesiones SET inicio_ajuste='$hora' WHERE id_sesion=".$sessionID;
        $query = $this->db->prepare($sql);
        $result=$query->execute();
        
        
        if ($result) {
            return true;
        }else{
            return false;
        }
    }

    
    public function setInicio(){        
        $result=array();
        $hora=date("H:i:s",time());
        $sql = "UPDATE sesiones SET actividad_actual='inicio' WHERE id_sesion=".$_SESSION['sessionID'];
        $query = $this->db->prepare($sql);
        $result=$query->execute();
        
        
        if ($result) {
            return true;
        }else{
            return false;
        }
    }

    
    public function setNewProcess(){        
        $result=array();
        $hora=date("H:i:s",time());
        $sql = "UPDATE sesiones SET actividad_actual='ajuste',proceso=".$_SESSION['processID']." WHERE id_sesion=".$_SESSION['sessionID'];
        $query = $this->db->prepare($sql);
        $result=$query->execute();
        
        
        if ($result) {
            $sql2 = "UPDATE tiraje SET id_proceso=".$_SESSION['processID']." WHERE idtiraje=".$_SESSION['tiroActual'];
        $query2 = $this->db->prepare($sql2);
        $result2=$query2->execute();
        if ($result2) {
            return true;
        }else{
            return false;
        }
            
        }else{
            return false;
        }
    }

    public function getProcessName($processID){

        

        $sql = "SELECT nombre_proceso FROM procesos_catalogo WHERE id_proceso=".$processID;
        $query = $this->db->prepare($sql);
        $query->execute();
        $name=$query->fetch(PDO::FETCH_ASSOC);
        return $name['nombre_proceso'];
    }
    
    public function getElementNameByOrder($orderID){

        

        $sql = "SELECT o.producto,(SELECT nombre_elemento FROM elementos WHERE id_elemento=o.producto)AS elemento FROM ordenes o WHERE idorden=".$orderID;
        $query = $this->db->prepare($sql);
        $query->execute();
        $name=$query->fetch(PDO::FETCH_ASSOC);
        return $name['elemento'];
    }
    public function getElementName($elementID){

        

        $sql = "SELECT nombre_elemento FROM elementos WHERE id_elemento=".$elementID;
        $query = $this->db->prepare($sql);
        $query->execute();
        $name=$query->fetch(PDO::FETCH_ASSOC);
        return $name['nombre_elemento'];
    }
    public function getAjusteStandard($processID){

        $sql ="SELECT * FROM estandares WHERE id_elemento=144 AND id_proceso=".$processID;
        $query = $this->db->prepare($sql);
        $query->execute();
        $name=$query->fetch(PDO::FETCH_ASSOC);
        
        return $name;
    }
    public function getTiroElapsedTime($sessionID){
        
        $sql = "SELECT * FROM sesiones WHERE id_sesion=".$sessionID;
        $query = $this->db->prepare($sql);
        $query->execute();
        $sesion=$query->fetch(PDO::FETCH_ASSOC);
        $time=(strtotime(date("H:i:s",time()))-strtotime($sesion['inicio_tiro']))-(((empty($sesion['tiempo_alert']))? 0: $sesion['tiempo_alert'])+((empty($sesion['tiempo_comida']))? 0: $sesion['tiempo_comida']));
        return $time;

    }


    
    public function getAlertElapsedTime($sessionID){
        
        $sql = "SELECT inicio_alert FROM sesiones WHERE id_sesion=".$sessionID;
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
        $tm=date("H:i:s",time());
        $hour=(strtotime("1970-01-01 $tm UTC")-strtotime("1970-01-01 ".$sesion['inicio_ajuste']." UTC"));
        $tiempo_cola=0;
        //$time=$hour-(((empty($sesion['tiempo_alert_ajuste']))? 0: $sesion['tiempo_alert_ajuste'])+((empty($sesion['tiempo_comida']))? 0: $sesion['tiempo_comida']));
        $transur=strtotime(date("H:i:s",time()))-strtotime($sesion['inicio_ajuste']);
        $alert_comida=((empty($sesion['tiempo_alert_ajuste']))? 0: $sesion['tiempo_alert_ajuste'])+((empty($sesion['tiempo_comida']))? 0: $sesion['tiempo_comida']);
        $time=($transur-$alert_comida)+$tiempo_cola;
        $result['time']=$time;
        $result['valores']='tanscur: '.$transur.' alert_comida: '.$alert_comida.' time: '.$time.' $hour: '.$hour;

        $result['inicios']='hora actual: '.date("H:i:s",time()).' inicio ajuste: '.$sesion['inicio_ajuste'];
        return $result;
    }

    public function getAsaichiElapsedTime($sessionID){
        
        $sql = "SELECT * FROM sesiones WHERE id_sesion=".$sessionID;
        $query = $this->db->prepare($sql);
        $query->execute();
        $sesion=$query->fetch(PDO::FETCH_ASSOC);
        $time=strtotime(date("H:i:s",time()))-strtotime($sesion['inicio_asaichi']);
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
     public function getTiroLunchElapsedTime(){
        
        $sql = "SELECT * FROM sesiones WHERE id_sesion=".$_SESSION['sessionID'];
        $query = $this->db->prepare($sql);
        $query->execute();
        $sesion=$query->fetch(PDO::FETCH_ASSOC);
        $time=strtotime(date("H:i:s",time()))-strtotime($sesion['inicio_comida']);
        return $time;
    }
    
    public function getTiroStartingLunch(){
        
        $sql = "SELECT inicio_comida FROM sesiones WHERE id_sesion=".$_SESSION['sessionID'];
        $query = $this->db->prepare($sql);
        $query->execute();
        $sesion=$query->fetch(PDO::FETCH_ASSOC);
        
        return $sesion['inicio_comida'];
    }

    public function getPiezasPorHora($elemento){
        
        $sql = "SELECT piezas_por_hora FROM estandares WHERE id_elemento=$elemento AND id_proceso=".$_SESSION['processID'];
        $query = $this->db->prepare($sql);
        $query->execute();
        $pph=$query->fetch(PDO::FETCH_ASSOC);

        if ($query->rowCount()>0) {
            return $pph['piezas_por_hora'];
        }else{
           $sql2 = "SELECT piezas_por_hora FROM estandares WHERE id_elemento=144 AND id_proceso=".$_SESSION['processID'];
        $query2 = $this->db->prepare($sql2);
        $query2->execute();
        $pph2=$query2->fetch(PDO::FETCH_ASSOC); 
        return $pph2['piezas_por_hora'];
        }
        
        
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

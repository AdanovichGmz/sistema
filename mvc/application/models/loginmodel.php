<?php

class LoginModel
{
    /**
     * Every model needs a database connection, passed to the model
     * @param object $db A PDO database connection
     */
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

    public function signIn($user,$password){

        $sql = "SELECT * FROM usuarios WHERE password='$password' AND usuario LIKE '$user' ";
        $query = $this->db->prepare($sql);
        $query->execute();

        if ($query->rowCount()>0) {
            session_start();
            $_SESSION['logged']='true';
            
            return true;
            
        }else{
            return false;
        }
  
  
  }
  public function getUserInfo($userID){
        $sql = "SELECT * FROM usuarios WHERE id=".$userID;
        $query = $this->db->prepare($sql);
        $query->execute();
        $info= $query->fetch(PDO::FETCH_ASSOC);
        
        return $info;
    }

  public function getAllowedMembers(){
        $sql = "SELECT * FROM usuarios WHERE team_member='true' AND id NOT IN(SELECT id_usuario FROM usuarios_sesion_equipo WHERE fecha='".TODAY."' AND id_sesion NOT IN(".$_SESSION['sessionID'].")) ORDER BY logged_in ASC";
        $query = $this->db->prepare($sql);
        $query->execute();
        $info= $query->fetch(PDO::FETCH_ASSOC);
        $users=array();
while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
        $users[]=$row;
        
    }
        return $users;
        
    }

    public function getFreeMembers(){
        $sql = "SELECT * FROM usuarios WHERE team_member='true' AND id NOT IN(SELECT id_usuario FROM usuarios_sesion_equipo WHERE fecha='".TODAY."') ORDER BY logged_in ASC";
        $query = $this->db->prepare($sql);
        $query->execute();
        $info= $query->fetch(PDO::FETCH_ASSOC);
        $users=array();
while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
        $users[]=$row;
        
    }
        return $users;
        
    }

    
    public function login($post){
        $user=$post['usuario'];
        $pass=$post['pass'];
        
        $sql = "SELECT * FROM usuarios WHERE usuario LIKE '$user' AND password='$pass'";
        $query = $this->db->prepare($sql);
        $query->execute();
        if ($query->rowCount()>0) {
            $info= $query->fetch(PDO::FETCH_ASSOC);
            return $info;
        }else{
            return false;
        }

        
    }
    public function getUserStations($userID){
        
        
        $sql = "SELECT * FROM usuarios_estaciones WHERE id_usuario=".$userID;
        $query = $this->db->prepare($sql);
        $query->execute();

        while ($row = $query->fetch(PDO::FETCH_ASSOC)){
        $this->getStationProcess($row['id_estacion']);
        
        }

        if ($query->rowCount()>0) {
            $info= $query->fetch(PDO::FETCH_ASSOC);
            return $info;
        }else{
            return false;
        }

        
    }

    public function isMultiStation($userID){
        
        
        $sql = "SELECT * FROM usuarios_estaciones WHERE id_usuario=".$userID;
        $query = $this->db->prepare($sql);
        $query->execute();
        $stats=array();
            
        if ($query->rowCount()>1) {
            $stats['multi']='true';
            while ($row=$query->fetch(PDO::FETCH_ASSOC)) {
                $stats['station'][$row['id_estacion']]=$row;
            }
            return $stats;
        }elseif($query->rowCount()==1){
            $stats['multi']='false';
            $idStation=$query->fetch(PDO::FETCH_ASSOC); 
           
            $stats['station']=$idStation;
            return $stats;
           
        }else{
            return false;
        }

        
    }

     public function getStationProcess($stationID){
        $process=array();        
        $sql = "SELECT * FROM estaciones_procesos WHERE id_estacion=".$stationID;
        $query = $this->db->prepare($sql);
        $query->execute();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)){
        $process[$row['id_proceso']]=$this->getProcessInfo($row['id_proceso']);
        
        }
        return $process;

        
    }

    public function getProcessInfo($processID){
        $process=array();        
        $sql = "SELECT * FROM procesos_catalogo WHERE id_proceso=".$processID;
        $query = $this->db->prepare($sql);
        $query->execute();
        $processInfo = $query->fetch(PDO::FETCH_ASSOC);
        return $processInfo;
}
        
    
    
 
}

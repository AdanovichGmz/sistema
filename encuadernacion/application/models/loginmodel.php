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

    /**
     * Get simple "stats". This is just a simple demo to show
     * how to use more than one model in a controller (see application/controller/songs.php for more)
     */
    public function getAmountOfSongs()
    {
        $sql = "SELECT COUNT(id) AS amount_of_songs FROM song";
        $query = $this->db->prepare($sql);
        $query->execute();

        // fetchAll() is the PDO method that gets all result rows
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

    

    
 
}

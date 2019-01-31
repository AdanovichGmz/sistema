<?php

class OdtModel
{
    
    function __construct($db)
    {
        try {
            $this->db = $db;
        } catch (PDOException $e) {
            exit('No se pudo establecer conexion con la base de datos.');
        }
    }

   

    

    public function getOrderByOdt($odt,$process){
        $orders=array();
        $sql = "SELECT o.numodt FROM ordenes o INNER JOIN procesos p ON p.id_orden=o.idorden WHERE p.nombre_proceso='$process' AND o.numodt LIKE '%" . $odt . "%' AND entregado NOT IN('true') GROUP BY o.numodt";
        $query = $this->db->prepare($sql);
        $query->execute();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
        $orders[]=$row;
        
    }

        return $orders;
    }

    
    public function getContentByOdt($odt,$process){
        $odts=array();
         $sql = "SELECT p.*,o.producto,(SELECT nombre_elemento FROM elementos WHERE id_elemento=o.producto)AS elemento FROM procesos p INNER JOIN ordenes o ON p.id_orden=o.idorden WHERE p.numodt='".$odt."' AND p.nombre_proceso='".$process."' AND p.avance NOT IN('completado')";

        $query = $this->db->prepare($sql);
        $query->execute();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
        $odts[]=$row;
        
    }

        return $odts;
    }

    public function odtPendings($odt,$process){
        $odts=array();
        $sql = "SELECT COUNT(*)AS total FROM procesos WHERE numodt='".$odt."' AND nombre_proceso='".$process."' AND avance NOT IN('completado')";
        $query = $this->db->prepare($sql);
        $query->execute();
        $info= $query->fetch(PDO::FETCH_ASSOC);
        return $info['total'];
    }

    

    
    


 
}

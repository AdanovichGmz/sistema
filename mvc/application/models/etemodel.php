<?php

class EteModel
{
    
    function __construct($db)
    {
        try {
            $this->db = $db;
        } catch (PDOException $e) {
            exit('No se pudo establecer conexion con la base de datos.');
        }
    }

   

    public function getEteByUser($userID,$date){

        $queryReal="SELECT TIME_FORMAT(SEC_TO_TIME((SUM(TIME_TO_SEC(tiempoTiraje))+SUM(TIME_TO_SEC(tiempo_ajuste))+IFNULL((SELECT SUM(TIME_TO_SEC(tiempoalertamaquina)) FROM alertageneralajuste WHERE fechadeldiaam = '$date' AND id_usuario =$userID AND es_tiempo_muerto='false'),0)+IFNULL((SELECT SUM(TIME_TO_SEC(tiempo)) FROM asaichi WHERE fechadeldia = '$date' AND id_usuario=$userID),0)-IFNULL((SELECT SUM(TIME_TO_SEC(breaktime)) FROM breaktime WHERE fechadeldiaam = '$date' AND id_usuario =$userID AND radios='Sanitario'),0))), '%H:%i') AS t_real,(SUM(TIME_TO_SEC(tiempoTiraje))+SUM(TIME_TO_SEC(tiempo_ajuste))+IFNULL((SELECT SUM(TIME_TO_SEC(tiempoalertamaquina)) FROM alertageneralajuste WHERE fechadeldiaam = '$date' AND id_usuario =$userID AND es_tiempo_muerto='false'),0)-IFNULL(
            (SELECT SUM(TIME_TO_SEC(breaktime)) FROM breaktime WHERE fechadeldiaam = '$date' AND id_usuario =$userID AND radios='Sanitario'),0))+IFNULL((SELECT SUM(TIME_TO_SEC(tiempo)) FROM asaichi WHERE fechadeldia= '$date' AND id_usuario=$userID),0) AS sec_t_real FROM tiraje WHERE fechadeldia_ajuste = '$date' AND id_user =$userID";

            $real = $this->db->prepare($queryReal);
            $real->execute();
            $sec_t_real= $real->fetch()->sec_t_real;


            $queryDisponible="SELECT TIME_FORMAT(SEC_TO_TIME(((SUM(TIME_TO_SEC(horafin_tiraje) - TIME_TO_SEC(horadeldia_tiraje))+SUM(TIME_TO_SEC(horafin_ajuste)-TIME_TO_SEC(horadeldia_ajuste)))-IFNULL(
            (SELECT SUM(TIME_TO_SEC(breaktime)) FROM breaktime WHERE fechadeldiaam = '$date' AND id_usuario =$userID),0))+IFNULL((SELECT SUM(TIME_TO_SEC(tiempo_muerto)) FROM tiempo_muerto WHERE fecha= '$date' AND id_user =$userID AND seccion='desfase'),0)), '%H:%i') AS disponible, ((SUM(TIME_TO_SEC(horafin_tiraje) - TIME_TO_SEC(horadeldia_tiraje))+SUM(TIME_TO_SEC(horafin_ajuste)-TIME_TO_SEC(horadeldia_ajuste)))-IFNULL(
            (SELECT SUM(TIME_TO_SEC(breaktime)) FROM breaktime WHERE fechadeldiaam = '$date' AND id_usuario =$userID),0))+IFNULL((SELECT SUM(TIME_TO_SEC(tiempo_muerto)) FROM tiempo_muerto WHERE fecha='$date' AND id_user=$userID AND seccion='desfase'),0) AS sec_disponible FROM tiraje WHERE fechadeldia_ajuste = '$date' AND id_user =$userID";

            $disponible = $this->db->prepare($queryDisponible);
            $disponible->execute();
            $sec_disponible= $disponible->fetch()->sec_disponible;
 

            $querySumatorias="SELECT IFNULL(SUM(buenos),0)AS sum_prod_real,IFNULL(SUM(merma_entregada),0)AS sum_merma,IFNULL(SUM(produccion_esperada),0)AS sum_prod_esperada, IFNULL(SUM(buenos)-SUM(defectos),0)AS sum_calidad_primera FROM tiraje WHERE fechadeldia_ajuste = '$date' AND id_user =$userID";

            $sumatorias = $this->db->prepare($querySumatorias);
            $sumatorias->execute();
            $sum= $sumatorias->fetch(PDO::FETCH_ASSOC);
            $sum_prod_esperada=$sum['sum_prod_esperada'];
            $sum_prod_real= $sum['sum_prod_real'];
            $sum_merma= $sum['sum_merma'];
            $sum_calidad_primera= $sum['sum_calidad_primera'];
             
            $dispon=(($sec_disponible<=0)? 0: ($sec_t_real/$sec_disponible)*100);
            $dispon_tope= ($dispon>100)?100:$dispon;
            $desemp=( ($sum_prod_esperada<=0)? 0: (($sum_prod_real+$sum_merma)/$sum_prod_esperada)*100);
            $desemp_tope=($desemp>100)?100:$desemp;
            $calidad=(($sum_prod_real<=0)? 0: ($sum_calidad_primera/$sum_prod_real)*100);
            $calidad_tope=($calidad>100)?100:$calidad;
            $final=(($calidad_tope/100)*($desemp_tope/100)*($dispon_tope/100))*100;
            $ete['disponibilidad']=$dispon_tope;
            $ete['desempenio']=$desemp_tope;
            $ete['calidad']=$calidad_tope;
            $ete['ete']=$final;
            

        return $ete;
    }

    public function getBuenos($id_user,$sessionID){
        $result=array();
        $sql = "SELECT IFNULL(SUM(buenos),0) AS produccion FROM tiraje WHERE id_sesion='$sessionID' AND id_user=".$id_user;
        $query = $this->db->prepare($sql);
        $query->execute();
        $result=$query->fetch(PDO::FETCH_ASSOC);

        return $result['produccion'];
    }

    public function getMerma($id_user,$sessionID){
        $result=array();
        $sql = "SELECT IFNULL(SUM(merma),0)AS merma FROM tiraje WHERE id_sesion='$sessionID' AND id_user=".$id_user;
        $query = $this->db->prepare($sql);
        $query->execute();
        $result=$query->fetch(PDO::FETCH_ASSOC);

        return $result['merma'];
    }
    


 
}

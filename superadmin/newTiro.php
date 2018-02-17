<?php 

require('../saves/conexion.php');

$in_ajuste=(strlen($_POST['in_ajuste'])==5)? $_POST['in_ajuste'].':00':$_POST['in_ajuste'];
$fin_ajuste=(strlen($_POST['fin_ajuste'])==5)? $_POST['fin_ajuste'].':00':$_POST['fin_ajuste'];

$in_tiro=(strlen($_POST['in_tiro'])==5)? $_POST['in_tiro'].':00':$_POST['in_tiro'];
$fin_tiro=(strlen($_POST['fin_tiro'])==5)? $_POST['fin_tiro'].':00':$_POST['fin_tiro'];

$oper=(isset($_POST['operario']))?$_POST['operario']:0;
$producto=(isset($_POST['producto']))? "'".$_POST['producto']."'":'null';
$odt=(isset($_POST['odt']))? "'".$_POST['odt']."'":'null';
$fecha=(isset($_POST['fecha']))? "'".$_POST['fecha']."'":'null';
$pedido=(isset($_POST['pedido']))? $_POST['pedido']:0;
$recibido=(isset($_POST['recibido']))? $_POST['recibido']:0;
$buenos=(isset($_POST['buenos']))? $_POST['buenos']:0;
$piezas=(isset($_POST['piezas']))? $_POST['piezas']:0;
$defectos=($piezas>2)? $piezas-2:0;
$merma=$buenos-$pedido;


$entorno=(isset($_POST['entorno']))?$_POST['entorno']:'maquina';

switch ($oper) {
	case '14':
		$maquina=10;
		break;
		case '16':
		$maquina=10;
		break;
		case '18':
		$maquina=10;
		break;
		case '11':
		$maquina=16;
		break;
		case '13':
		$maquina=9;
		break;
		case '2':
		$maquina=9;
		break;
	
	default:
		$maquina=0;
		break;
}

$tiempoT=strtotime($fin_tiro) - strtotime($in_tiro);
$tiempoA=strtotime($fin_ajuste) - strtotime($in_ajuste);

$standar_query = "SELECT * FROM estandares WHERE id_maquina=$maquina AND id_elemento=$producto";
            $getstandar     = mysqli_fetch_assoc($mysqli->query($standar_query));
            $estandar       = $getstandar['piezas_por_hora'];


            if ($maquina==10) {
            	if ($entorno=='mesa') {
                    $tiraje_estandar=($tiempoT*300)/3600;
                    
                  }else{
                  	if (!empty($estandar)) {
                    $tiraje_estandar=($tiempoT*$estandar)/3600;
                    
                  }
                  else{
                    $tiraje_estandar=($tiempoT*600)/3600;
              		}
                  }
            }else{

				if ($maquina==10) {
                    $tiraje_estandar=($tiempoT*480)/3600;
                  }else{
                    $tiraje_estandar=($tiempoT*600)/3600;
                  } 
            }


                

                  if ($tiraje_estandar>0) {
                    $predesemp=($buenos*100)/$tiraje_estandar;
                   $tiraje_desemp=($predesemp>100)? 100 : $predesemp;
                   
                  }else{
                    $tiraje_desemp=0;
                    
                  }

$n_prod=mysqli_fetch_assoc($mysqli->query("SELECT nombre_elemento FROM elementos WHERE id_elemento=$producto"));
$nombre=$n_prod['nombre_elemento'];



$ajuste= gmdate("H:i:s", $tiempoA);
$tiraje= gmdate("H:i:s", $tiempoT);

$query="INSERT INTO `tiraje` (`idtiraje`, `producto`, `id_maquina`, `pedido`, `cantidad`, `buenos`, `piezas_ajuste`, `defectos`, `merma`, `merma_entregada`, `entregados`, `produccion_esperada`, `desempenio`, `tiempoTiraje`, `tiempo_ajuste`, `horadeldia_ajuste`, `horafin_ajuste`, `fechadeldia_ajuste`, `horadeldia_tiraje`, `horafin_tiraje`, `fechadeldia_tiraje`, `id_orden`, `id_user`, `is_virtual`, `odt_virtual`, `elemento_virtual`, `id_elem_virtual`, `cancelado`) VALUES (NULL, $producto, $maquina, $pedido, $recibido, $buenos, $piezas, $defectos, $merma, $merma, $buenos, $tiraje_estandar, $tiraje_desemp, '$tiraje', '$ajuste', '$in_ajuste', '$fin_ajuste', $fecha, '$in_tiro', '$fin_tiro', $fecha, NULL, $oper, 'true', $odt, '$nombre', $producto, 'false')";
$insert=$mysqli->query($query);

if ($insert) {
	echo "Todo bien!!<br><br><br>";
echo $query;
}else{	print_r($_POST);
		printf($mysqli->error);
	}





?>
<?php 
include('../../../db_conn.php');
$idcliente=(isset($_GET['idcliente']))?$_GET['idcliente']:"";

$sql = "SELECT * FROM clientes WHERE idclientes = $idcliente";
$result = $con->query($sql);
$row = $result->fetch_object();

$sql_borrar="SELECT * FROM `grupos_clie` WHERE cliente1 = '$row->dni' OR cliente2 = '$row->dni' OR cliente3 = '$row->dni' OR cliente4 = '$row->dni' OR cliente5 = '$row->dni'";
$rs_borrar=$con->query($sql_borrar);
$cnt_borrar=$rs_borrar->num_rows;

if($cnt_borrar == 0){	
	/*if(is_numeric($idcliente)){*/
	//Tabla asignaciones
	$sql = "DELETE from asignaciones WHERE idcliente = '$idcliente' ";
	$rs = $con->query($sql);
	
	//Tabla asistencias
	$sql = "DELETE from asistencias WHERE idcliente = '$idcliente' ";
	$rs = $con->query($sql);
	
	//Tabla facturacion
	$sql = "DELETE from facturacion WHERE idcliente = '$idcliente' ";
	$rs = $con->query($sql);
	
	//Tabla ingresos_mem
	$sql = "DELETE from ingresos_mem WHERE idcliente = '$idcliente' ";
	$rs = $con->query($sql);
	
	//Tabla rutinas
	$sql = "DELETE from rutinas WHERE idcliente = '$idcliente' ";
	$rs = $con->query($sql);
	
	//Tabla clientes
	$sql = "DELETE from clientes WHERE idclientes = '$idcliente' ";
	$rs = $con->query($sql);

	header("location:../clientes_baja.php");
} else {
    echo "<html>";
	echo "<!-- meta character set -->";
	echo "<meta charset='UTF-8'>";
	echo "<!-- Jquery -->";
	echo "<script type='text/javascript' src='../../../js/jquery-3.4.1.min.js'></script>";
	echo "<!-- bootstrap -->";
	echo "<link rel='stylesheet' href='../../../css/bootstrap.min.css'>";
	echo "<script type='text/javascript' src='../../../js/bootstrap.min.js'></script>";
 	echo "<body>";
	echo "<div class='alert alert-danger' role='alert'>
				<h4 class='alert-heading'>¡No es posible realizar la acción!</h4>
				<p><b>Cliente</b>: ".ucwords($row->nombre)." ".ucwords($row->apellido)."<br><b>Pertenece a</b>: ";
    while($rs = $rs_borrar->fetch_object()){
         echo " | ";
         echo ucwords($rs->nom_grupos); 
         echo " | ";
    };
	echo "<br><br>¡Para poder realizar la acción, el cliente no debe pertenecer a ningún grupo!"; 
	echo "</p><hr>
				<p class='mb-0'>Sera redireccionado en segundos.</p>
				</div>
				<meta http-equiv='refresh' content='7;URL=../clientes_baja.php'/>";
	echo "</body>";
	echo "</html>";
}
?>
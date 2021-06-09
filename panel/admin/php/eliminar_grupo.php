<?php 
include('../../../db_conn.php');
$idgrupos=(isset($_GET['idgrupos']))?$_GET['idgrupos']:"";
if(is_numeric($idgrupos)){
	//Tabla idasignaciones
	$sql = "DELETE from idasignaciones WHERE idgrupo = $idgrupos ";
	$rs = $con->query($sql);
	
	//Tabla facturacion
	$sql = "DELETE from facturacion WHERE idgrupos = $idgrupos ";
	$rs = $con->query($sql);
	
	//Tabla grupos_clie
	$sql = "DELETE from grupos_clie WHERE idgrupos = $idgrupos ";
	$rs = $con->query($sql);	
	
	header("location:../clientes_grupos.php");
} else {
	print "<html><head><script>alert('Accion no permitida.');</script><meta http-equiv='refresh' content='0; url=../clientes_grupos.php'></head></html>";
}
?>
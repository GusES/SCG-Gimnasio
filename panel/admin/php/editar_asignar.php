<?php 
include('../../../db_conn.php');
$idcliente=isset($_POST['idcliente'])?$_POST['idcliente']:"";
$idmembresias=isset($_POST['membresias'])?$_POST['membresias']:"";
$zona = new DateTime('America/Argentina/Buenos_Aires');
$fecha = $zona->format('Y-m-d H:i:s');
$fecha = date( "Y-m-d H:i:s", strtotime( "$fecha +1 month" ) );

if(is_numeric($idcliente)){
	$sql="DELETE FROM asignaciones WHERE idcliente = '$idcliente';"; 
	$result = $con->query($sql); 
	for($i=0; $i < @count($idmembresias); $i++){
		@$sql="INSERT INTO asignaciones (idcliente, idmembresia,fecha_vencimiento) VALUES ('$idcliente', '$idmembresias[$i]','$fecha')"; 
		$result = $con->query($sql); 
	}
	
	//actualiza estado
	$sql_membresias="SELECT * FROM asignaciones INNER JOIN membresias WHERE asignaciones.idmembresia = membresias.idmembresias AND idcliente = '$idcliente'";
	$rs_membresias = $con->query($sql_membresias);
	while($row_membresias = $rs_membresias->fetch_object()){$disciplinas[]=$row_membresias->nom_modulo;}
	for($i=0; $i < @count($disciplinas); $i++){
		$sql_dueda="SELECT * FROM facturacion INNER JOIN ingresos_mem on facturacion.idcliente=ingresos_mem.idcliente WHERE ingresos_mem.idcliente = '$idcliente' and facturacion.disciplina='$disciplinas[$i]' ORDER BY num_factura DESC LIMIT 1";
		$rs_deuda = $con->query($sql_dueda);
		while($row_deuda = $rs_deuda->fetch_object()){
			$sql_estado="UPDATE clientes SET estado = 1 WHERE idclientes = '$idcliente'";
			$rs_estado = $con->query($sql_estado);
		}
	}
	//fin actualiza estado
	
  echo "<html><head><meta charset='UTF-8'><title>Tarea Exitosa</title><script type='text/javascript' src='../../../js/jquery-3.4.1.min.js'></script><script type='text/javascript' src='../../../js/bootstrap.min.js'></script><script type='text/javascript' src='../../../js/font-all.min.js'></script><link rel='stylesheet' href='../../../css/bootstrap.min.css'></head><body><div class='alert alert-success text-center'><p><i class='fal fa-check-circle fa-3x'></i></p><h4 class='alert-heading'>¡Tarea realizada con éxito!</h4><hr><p class='mb-0'>Sera redireccionado en segundos.</p></div></body></html>
  <meta http-equiv='refresh' content='4;URL=../detalle_cliente.php?id=$idcliente' /> ";	
} else {
	print "<html><head><script>alert('Accion no permitida.');</script><meta http-equiv='refresh' content='0; url=../ver_clientes.php'></head></html>";
} 
mysqli_close($con);
?>
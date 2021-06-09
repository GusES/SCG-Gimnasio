<?php 
include('../../../db_conn.php');
$idcliente=isset($_POST['idcliente'])?$_POST['idcliente']:"";
$num_factura=isset($_POST['num_factura'])?$_POST['num_factura']:"";
$fecha_fact=isset($_POST['fecha_fact'])?$_POST['fecha_fact']:"";
$nombre=isset($_POST['nombre'])?str_replace("'","''",$_POST['nombre']):"";
$nombre=mb_strtolower($nombre);
$modulo=isset($_POST['modulo'])?$_POST['modulo']:"";
$modulo=explode(",",$modulo);
$inser_modulo=isset($_POST['modulo'])?$_POST['modulo']:"";
$valores=isset($_POST['valores'])?$_POST['valores']:"";
$precio=isset($_POST['precio'])?$_POST['precio']:"";
$abonado=isset($_POST['abonado'])?$_POST['abonado']:"";
$acumulado=isset($_POST['notacredito'])?$_POST['notacredito']:"";
$restante=isset($_POST['restante'])?$_POST['restante']:0;
if($restante == 0 && $abonado != $precio){$abonado = $abonado + $acumulado;}
$zona = new DateTime('America/Argentina/Buenos_Aires');
$fecha = $zona->format('Y-m-d H:i:s');
$vencimieto = date( "Y-m-d H:i:s", strtotime( "$fecha +1 month" ) );
//Actualizar fecha_vencimiento de asignaciones
for($i=0;$i < count($modulo);$i++){
	$sql_idmembresia="SELECT * FROM membresias WHERE nom_modulo='$modulo[$i]'";
	$rs_idmembresia = $con->query($sql_idmembresia);
	$row_idmembresia = $rs_idmembresia->fetch_object();
	$sql_update_asignacion= "UPDATE asignaciones SET fecha_vencimiento = '$vencimieto' WHERE idcliente='$idcliente' and idmembresia = '$row_idmembresia->idmembresias' ";
	$rs_update_asignacion = $con->query($sql_update_asignacion);
//fin Actualizar fehca_vencimiento de asignaciones
}

if($fecha_fact != NULL){
	$fecha_factura = new DateTime($fecha_fact);
	$mes = $fecha_factura->format("m");
	$anio = $fecha_factura->format("Y");
}

function nombremes($mes){
	setlocale(LC_TIME, 'spanish');  
	$nombre=strftime("%B",mktime(0, 0, 0, $mes, 1, 2000)); 
	return $nombre;
}


//control de facturacion
$mes_nombre=nombremes($mes);
for($i=0;$i < count($modulo);$i++){
	$query = "SELECT COUNT(disciplina) AS cont_disciplina FROM facturacion WHERE idcliente = $idcliente AND disciplina LIKE '%$modulo[$i]%' AND MONTH(fecha_pago)= $mes AND YEAR(fecha_pago)= $anio";
	$result = mysqli_query($con, $query);
	$contador = mysqli_fetch_row($result);
}

for($i=0;$i < count($modulo);$i++){
	 $sql = "SELECT *, fecha_pago AS fecha FROM facturacion where idcliente = $idcliente AND disciplina = '$modulo[$i]' ORDER BY fecha_pago DESC LIMIT 1";
	$resultado = $con->query($sql);
	$row = $resultado->fetch_object();
  //control de ingreso al sistema
 if (is_null($row)){
 $fecha_pago[]="No registra pagos";
 } else {
 $fecha_pago[]=fechaCastellano($row->fecha_pago);
      
 };   
 

}

if($fecha != NULL){
	$fecha_factura = new DateTime($fecha);
	$fecha_alert = $fecha_factura->format("d-m-Y");
}

if($contador[0] >= 1){
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
				<p><b>Cliente</b>: ".ucwords($nombre)."<br><b>Abono</b>: ";
	for($i=0; $i < count($modulo); $i++){
		echo ucwords($modulo[$i]);
		if($i+1 != count($modulo)){
			echo " - ";
		}
    echo "<br><b>Pagado</b>: ".$fecha_pago[$i]; 
	}	
	echo ".</p><hr>
				<p class='mb-0'>Sera redireccionado en segundos.</p>
				</div>
				<meta http-equiv='refresh' content='5;URL=../ver_facturacion.php' />";
	echo "</body>";
	echo "</html>";
} else {
	if($idcliente != ""){
		$sql = "INSERT INTO facturacion(tipo, idcliente, nom_completo, disciplina, valores, precio, abonado, num_factura, fecha_pago, fecha_vence) VALUES('cliente', '$idcliente', '$nombre', '$inser_modulo', '$valores', '$precio', '$abonado', '$num_factura', '$fecha', '$vencimieto')";
		$result = $con->query($sql);
		$sql_asignadas="SELECT * FROM asignaciones INNER JOIN membresias WHERE asignaciones.idmembresia = membresias.idmembresias AND idcliente = '$idcliente'";
		$rs_asignadas=$con->query($sql_asignadas);
		while($row_asignadas=$rs_asignadas->fetch_object()){$disciplinas[]=$row_asignadas->nom_modulo;}
		$x=0;
		for($i=0; $i < count($disciplinas); $i++){
			$sql_dueda="SELECT * FROM facturacion INNER JOIN ingresos_mem on facturacion.idcliente=ingresos_mem.idcliente WHERE ingresos_mem.idcliente = '$idcliente' and facturacion.disciplina LIKE '%$disciplinas[$i]%' ORDER BY num_factura DESC LIMIT 1";
			$rs_deuda = $con->query($sql_dueda);
			$cnt_deuda = $rs_deuda->num_rows;
			while($row_deuda=$rs_deuda->fetch_object()){
				if($fecha >= $row_deuda->fecha_vence){
					$x++;
				}
			}
			if($cnt_deuda==0){
				$x++;
			}
		}
		if($x == 0){
			$sql = "UPDATE clientes SET estado = '1' WHERE idclientes = '$idcliente'";
			$result = $con->query($sql);				
		}
		$sql = "UPDATE clientes SET notacredito = '$restante', ult_fecha_pago = '$fecha' WHERE idclientes = '$idcliente'";
		$result = $con->query($sql);	
		echo "<html>";
		echo "<!-- meta character set -->";
		echo "<meta charset='UTF-8'>";
		echo "<!-- Jquery -->";
		echo "<script type='text/javascript' src='../../../js/jquery-3.4.1.min.js'></script>";
		echo "<!-- bootstrap -->";
		echo "<link rel='stylesheet' href='../../../css/bootstrap.min.css'>";
		echo "<script type='text/javascript' src='../../../js/bootstrap.min.js'></script>";
		echo "<body>";
		echo "<div class='alert alert-success' role='alert'>
					<h4 class='alert-heading'>¡Tarea realizada con éxito!</h4>
					<p><b>Cliente</b>: <span class='text-capitalize'>$nombre</span> <br><b>Abono</b>: "; 
		for($i=0; $i < count($modulo); $i++){
			echo ucwords($modulo[$i]);
			if($i+1 != count($modulo)){
				echo " - ";
			}
		}
		echo ".</p><hr>
					<p class='mb-0'>Sera redireccionado en segundos.</p>
					</div>
					<meta http-equiv='refresh' content='2;URL=../ver_facturacion.php' />";
		echo "</body>";
		echo "</html>";
	} else {
		print "<html><head><script>alert('Accion no permitida.');</script><meta http-equiv='refresh' content='0; url=../index.php'></head></html>";
	}
}
?>
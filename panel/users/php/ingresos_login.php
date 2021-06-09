<?php
include('../../../db_conn.php');
$dni=isset($_POST['dni'])?$_POST['dni']:null;
$clave=isset($_POST['clave'])?$_POST['clave']:null;
$aux = number_format($dni, 0, '.', '.');  
$completo = "DNI Ingresado: <span class='text-info alert-link'>$aux</span>";
//Cliente Existe
$sql = "SELECT * FROM clientes WHERE dni='$dni' and clave='$clave'";
$result = mysqli_query($con, $sql);
$count = mysqli_num_rows($result);
$row = $result->fetch_object();
if($count == 1 and ($row->estado == 1 or $row->estado == 0)){
	$zona = new DateTime('America/Argentina/Buenos_Aires');
	$fecha = $zona->format('Y-m-d H:i:s');
	$hora = $zona->format('H:i');
	//consultas asistencias
  $sql_asistencia = "INSERT INTO asistencias(idcliente, fecha_asistencia) VALUES ('$row->idclientes', '$fecha')";
  $rs_asistencia = $con->query($sql_asistencia);
	//IF para modificar ult_fecha_pago de un cliente inactivo 
  $ult_fechaingreso = $row->ult_ingreso;
	$tiempo_inactividad = strtotime ('+1 month', strtotime ($ult_fechaingreso));
  $tiempo_inactividad = date('Y-m-d H:i:s', $tiempo_inactividad);
  if($fecha >= $tiempo_inactividad){
		$sql="UPDATE clientes SET ult_ingreso = '$fecha', ult_fecha_pago = '$fecha', estado= '1' WHERE dni = '$dni' AND clave = '$clave'";
    $result = $con->query($sql);
	} else if ($fecha < $tiempo_inactividad){
		$sql = "UPDATE clientes SET ult_ingreso = '$fecha', estado= '1' WHERE dni = '$dni' AND clave = '$clave'";
    $result = $con->query($sql);
  } 
  if($row->estado == 0){
  $fecha_exp = date( "Y-m-d H:i:s", strtotime( "$fecha +1 month" ) );
  $sql="UPDATE asignaciones SET fecha_vencimiento = '$fecha_exp' WHERE idcliente = '$row->idclientes'";
  $result = $con->query($sql);
 } 
?>
<!DOCTYPE html>
<html lang="es">

<head>
	<title>Ingreso Correcto</title>
	<meta charset="UTF-8">
	<link rel="shortcut icon" href="../../../images/favicon.png">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="../../../css/bootstrap.min.css">
	<meta http-equiv='refresh' content='25;URL=../ingresos.php' />
	<script src="../../../js/jquery-3.4.1.min.js"></script>
	<script src="../../../js/bootstrap.min.js"></script>
</head>

<body>
	<center>
		<a href="../ingresos.php" class="d-block bg-dark text-white p-2"><b>Cerrar</b></a>
		<h3 class="p-3 mb-2 bg-success text-white">
			Bienvenid<?php if($row->genero=="femenino"){echo "a";}else{echo "o";}?>
			<br>
			<?=ucwords($row->nombre)?> <?=ucwords($row->apellido)?>
		</h3>
		<form method="POST" action="asignacion_membresia.php">
			<input type="hidden" name="dni" value="<?=$row->dni?>">
			<input type="hidden" name="clave" value="<?=$row->clave?>">
			<div class="alert alert-primary w-75 text-left">
				<h4 class="m-0">Advertencias:</h4>
				<b>Altas, Bajas y Problemas de Membresias Informe en Administracion.</b>
				<br>
				- No seleccionar actividades que no realizara o pueden aplicarse cargos equívocos.
				<br>
				- Si dejara de asistir a una membresia por favor informe.
			</div>
			<div>
				<p class="m-0">Seleccione la actividad que realizara:</p>
				<?php
				//BTN Membresia
				$sql="SELECT * FROM asignaciones INNER JOIN membresias WHERE asignaciones.idmembresia = membresias.idmembresias AND idcliente = '$row->idclientes'";
				$result = $con->query($sql);
				while($row = $result->fetch_object()){
				?>
				<button type="submit" name="membresia" value="<?=$row->idmembresias?>" class="btn btn-secondary d-flex-inline m-1"><?=ucwords($row->nom_modulo)?></button>
				<?php } 
				//BTN Membresia Grupo
				$sql_grupo="SELECT * FROM asignaciones INNER JOIN membresias INNER JOIN grupos_clie WHERE asignaciones.idmembresia = membresias.idmembresias AND asignaciones.idgrupo = grupos_clie.idgrupos AND (grupos_clie.cliente1 LIKE '$dni' or grupos_clie.cliente2 LIKE '$dni' or grupos_clie.cliente3 LIKE '$dni' or grupos_clie.cliente4 LIKE '$dni' or grupos_clie.cliente5 LIKE '$dni')";
				$rs_grupo = $con->query($sql_grupo);
				while($row_grupo = $rs_grupo->fetch_object()){
				?>
				<button type="submit" name="membresia" value="<?=$row_grupo->idmembresias?>" class="btn btn-secondary d-flex-inline m-1"><?=ucwords($row_grupo->nom_modulo)?></button>
				<?php } ?>
			</div>
		</form>
	</center>
</body>

</html>
<?php 
}else if($count==1 and $row->estado == 2){
	$zona = new DateTime('America/Argentina/Buenos_Aires');
	$fecha = $zona->format('Y-m-d H:i:s');
	$hora = $zona->format('H:i');
	//consultas asistencias
	$sql_asistencia = "INSERT INTO asistencias(idcliente, fecha_asistencia) VALUES ('$row->idclientes', '$fecha')";
  $rs_asistencia = $con->query($sql_asistencia);
	//IF para modificar ult_fecha_pago de un cliente inactivo 
  $ult_fechaingreso = $row->ult_ingreso;
	$tiempo_inactividad = strtotime ('+1 month', strtotime ($ult_fechaingreso));
  $tiempo_inactividad = date('Y-m-d H:i:s', $tiempo_inactividad);
  if($fecha >= $tiempo_inactividad){
		$sql="UPDATE clientes SET ult_ingreso = '$fecha', ult_fecha_pago = '$fecha', estado= '2' WHERE dni = '$dni' AND clave = '$clave'";
    $result = $con->query($sql);
	} else if ($fecha < $tiempo_inactividad){
		$sql = "UPDATE clientes SET ult_ingreso = '$fecha', estado= '2' WHERE dni = '$dni' AND clave = '$clave'";
    $result = $con->query($sql);
  } ?>
<!DOCTYPE html>
<html lang="es">

<head>
	<title>Ingreso Correcto</title>
	<meta charset="UTF-8">
	<link rel="shortcut icon" href="../../../images/favicon.png">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="../../../css/bootstrap.min.css">
	<meta http-equiv='refresh' content='25;URL=../ingresos.php' />
	<script src="../../../js/jquery-3.4.1.min.js"></script>
	<script src="../../../js/bootstrap.min.js"></script>
</head>

<body>
	<center>
		<a href="../ingresos.php" class="d-block bg-dark text-white p-2"><b>Cerrar</b></a>
		<h3 class="p-3 mb-2 bg-success text-white">
			Bienvenid<?php if($row->genero=="femenino"){echo "a";}else{echo "o";}?>
			<br>
			<?=ucwords($row->nombre)?> <?=ucwords($row->apellido)?>
		</h3>
		<form method="POST" action="asignacion_membresia.php">
			<input type="hidden" name="dni" value="<?=$row->dni?>">
			<input type="hidden" name="clave" value="<?=$row->clave?>">
			<div class="d-flex flex-column">
				<div class="order-0 alert alert-primary w-75 text-left mx-auto mb-2">
					<h4 class="m-0">Advertencias:</h4>
					<b>Altas, Bajas y Problemas de Membresias Informe en Administracion.</b>
					<br>
					- No seleccionar actividades que no realizara o pueden aplicarse cargos equívocos.
					<br>
					- Si dejara de asistir a una membresia por favor informe.
				</div>
				<div class="order-2 mx-auto">
					<p class="m-0">Seleccione la actividad que realizara:</p>
					<?php
					//BTN Membresia
					$sql="SELECT * FROM asignaciones INNER JOIN membresias WHERE asignaciones.idmembresia = membresias.idmembresias AND idcliente = '$row->idclientes'";
					$result = $con->query($sql);
					$disciplinas = Array();
					while($row_btn = $result->fetch_object()){
						$disciplinas[]=$row_btn->nom_modulo;
					?>
					<button type="submit" name="membresia" value="<?=$row_btn->idmembresias?>" class="btn btn-secondary d-flex-inline m-1"><?=ucwords($row_btn->nom_modulo)?></button>
					<?php } 
					//BTN Membresia Grupo
					$sql_grupo="SELECT * FROM asignaciones INNER JOIN membresias INNER JOIN grupos_clie INNER JOIN facturacion WHERE asignaciones.idmembresia = membresias.idmembresias AND asignaciones.idgrupo = grupos_clie.idgrupos AND (grupos_clie.cliente1 LIKE '$dni' or grupos_clie.cliente2 LIKE '$dni' or grupos_clie.cliente3 LIKE '$dni' or grupos_clie.cliente4 LIKE '$dni' or grupos_clie.cliente5 LIKE '$dni') AND facturacion.idgrupos=grupos_clie.idgrupos GROUP BY facturacion.num_factura";
					$rs_grupo = $con->query($sql_grupo);
					while($row_grupo = $rs_grupo->fetch_object()){
					?>
					<button type="submit" name="membresia" value="<?=$row_grupo->idmembresias?>" class="btn btn-secondary d-flex-inline m-1"><?=ucwords($row_grupo->nom_modulo)?></button>
					<?php } ?>
				</div>
				<div class="order-1 alert alert-danger w-75 text-left mx-auto mb-2">
					<h4 class="m-0">Regularice su Situación:</h4>
					- Tiene pagos pendientes, consulte en administracion.
					<br>
					<?php
					for($i=0; $i < count($disciplinas); $i++){
						$sql_dueda="SELECT * FROM facturacion INNER JOIN ingresos_mem on facturacion.idcliente=ingresos_mem.idcliente WHERE ingresos_mem.idcliente = '$row->idclientes' and facturacion.disciplina LIKE '%$disciplinas[$i]%' ORDER BY num_factura DESC LIMIT 1";
						$rs_deuda = $con->query($sql_dueda);
						while($row_deuda = $rs_deuda->fetch_object()){
							if($row_deuda->ult_ingreso > $row_deuda->fecha_vence or $fecha >= $row_deuda->fecha_vence){
								echo "<hr class='my-1'>";
								echo "<div class='col-4 d-inline-block align-top'><b>Membresia</b>: ".ucwords($disciplinas[$i]). "</div><div class='col-4 d-inline-block align-top'><b>Pagado: </b>".fechaCastellano($row_deuda->fecha_pago)."</div><div class='col-4 d-inline-block align-top'><b>Vencido: </b>".fechaCastellano($row_deuda->fecha_vence)."</div>";
							}
						}
					}
					$sql_dueda_grupo="SELECT * FROM asignaciones INNER JOIN membresias INNER JOIN grupos_clie INNER JOIN facturacion WHERE asignaciones.idmembresia = membresias.idmembresias AND asignaciones.idgrupo = grupos_clie.idgrupos AND (grupos_clie.cliente1 LIKE '$dni' or grupos_clie.cliente2 LIKE '$dni' or grupos_clie.cliente3 LIKE '$dni' or grupos_clie.cliente4 LIKE '$dni' or grupos_clie.cliente5 LIKE '$dni') AND facturacion.idgrupos=grupos_clie.idgrupos GROUP BY facturacion.num_factura ORDER BY facturacion.fecha_pago DESC";
					$rs_deuda_grupo = $con->query($sql_dueda_grupo);	
					while($row_deuda_grupo=$rs_deuda_grupo->fetch_object()){
						if($fecha >= $row_deuda_grupo->fecha_vence){
							echo "<hr class='my-1'>";
							echo "<div class='col-4 d-inline-block align-top'><b>Membresia</b>: ".ucwords($row_deuda_grupo->nom_modulo)."</div><div class='col-4 d-inline-block align-top'><b>Pagado: </b>".fechaCastellano($row_deuda_grupo->fecha_pago)."</div><div class='col-4 d-inline-block align-top'><b>Vencido: </b>".fechaCastellano($row_deuda_grupo->fecha_vence)."</div>";
						}
					}
				?>
				</div>
			</div>
		</form>
	</center>
</body>

</html>
<?php } else if($count==1 and $row->estado == 3){ ?>
<html lang="es">

<head>
	<title>Problema de Autenticación</title>
	<meta charset="UTF-8">
	<link rel="shortcut icon" href="../../../images/favicon.png">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="../../../css/bootstrap.min.css">
	<script src="../../../js/bootstrap.min.js"></script>
</head>

<body>
	<center>
		<h3 class="p-3 mb-2 bg-primary text-white">Bienvenido</h3>
		<h2 class="text-muted w-75">Su usuario debe ser activado manualmente, informe al administrador.</h2>
		<p class="text-muted small">
			Disculpe las molestias.
			<br>
			<a class="btn btn-secondary my-3" href="../ingresos.php">Aceptar</a>
		</p>
	</center>
</body>

</html>
<?php
} else { ?>
<!DOCTYPE html>
<html lang="es">

<head>
	<title>Datos Incorrectos</title>
	<meta charset="UTF-8">
	<link rel="shortcut icon" href="../../../images/favicon.png">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="../../../css/bootstrap.min.css">
	<script src="../../../js/bootstrap.min.js"></script>
</head>

<body>
	<center>
		<h3 class="p-3 mb-2 bg-danger text-white">Datos Incorrectos.</h3>
		<h1 class="text-muted"><?=$completo?></h1>
		<p class="text-muted small">
			Disculpe las molestias.
			<br>
			<a class="btn btn-secondary my-3" href="../ingresos.php">Reintentar</a>
		</p>
	</center>
</body>

</html>
<?php } ?>
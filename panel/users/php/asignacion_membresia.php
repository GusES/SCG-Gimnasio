<?php
include('../../../db_conn.php');
//cartel de bienvenido
$dni=isset($_POST['dni'])?$_POST['dni']:null;
$clave=isset($_POST['clave'])?$_POST['clave']:null;
$sql = "SELECT * FROM clientes WHERE dni='$dni' and clave='$clave'";
$result = mysqli_query($con, $sql);
$row = $result->fetch_object();
$idcliente = $row->idclientes;
//recibimos
$membresia=isset($_POST['membresia'])?$_POST['membresia']:null;
$query = "SELECT * FROM membresias WHERE idmembresias='$membresia'";
$result = mysqli_query($con, $query);
$row2 = $result->fetch_object();
//fecha
$zona = new DateTime('America/Argentina/Buenos_Aires');
$fecha = $zona->format('Y-m-d H:i:s');
$hora = $zona->format('H:i');
if ($membresia != NULL){
	//insertar o actualizar
	$query = "SELECT * FROM `ingresos_mem` WHERE idcliente = $idcliente AND idmembresia = $membresia";
	$resultado = mysqli_query($con, $query);
	$count = mysqli_num_rows($resultado);
	if ($count == 0){
		$sql = "INSERT INTO ingresos_mem(idcliente, idmembresia, ult_ingreso) VALUES ('$idcliente', '$membresia', '$fecha');";
		$resultado = $con->query($sql);
	} else {
		$sql = "UPDATE ingresos_mem SET ult_ingreso='$fecha' WHERE idcliente = '$idcliente' AND idmembresia = '$membresia'";
		$result = $con->query($sql);
	}
?>
<!DOCTYPE html>
<html lang="es">

<head>
	<meta charset="UTF-8">
	<script src="../../../js/bootstrap.min.js"></script>
	<link rel="stylesheet" type="text/css" href="../../../css/bootstrap.min.css">
	<title>Ingreso Correcto</title>
	<link rel="shortcut icon" href="../../../images/favicon.png">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv='refresh' content='2;URL=../ingresos.php' />
</head>

<body>
	<center>
		<h3 class="p-3 mb-2 bg-success text-white">
			Bienvenid<?php if($row->genero=="femenino"){echo "a";}else{echo "o";}?>
			<br>
			<?=ucwords($row->nombre)?> <?=ucwords($row->apellido)?>
		</h3>
		<h1 class="text-muted my-0">Puede continuar, disfrute su sesión de:<br><?=ucwords($row2->nom_modulo)?></h1>
		<small>Entrando el <?=fechacastellano($fecha)?> a las <?=$hora?>.</small>
	</center>
</body>

</html>
<?php 
} else { 
	print "<html><head><script>alert('Debe seleccionar una actividad.');</script><meta http-equiv='refresh' content='0;URL=../ingresos.php' /></head></html>";
} ?>
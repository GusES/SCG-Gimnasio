<?php
include '../db_conn.php';
$usuario=isset($_POST['usuario'])?$_POST['usuario']:null;
$contrasena=isset($_POST['contrasena'])?$_POST['contrasena']:null;
$_SESSION['autenticado'] = false;
$sql = "SELECT * FROM roles WHERE usuario='$usuario' and contrasena='$contrasena'";
$result = mysqli_query($con, $sql);
$count = mysqli_num_rows($result);
if ($count == 1) {
	session_start();
	$row = mysqli_fetch_assoc($result);
	$_SESSION['usuario'] = $row['usuario'];
	$_SESSION['contrasena'] = $row['contrasena'];
	$_SESSION['tipo'] = $row['tipo'];
	$_SESSION['rol'] = $row['rol'];
	$_SESSION['autenticado'] = true;
	//Clientes morosos
	$query = "UPDATE clientes INNER JOIN asignaciones ON asignaciones.idcliente = clientes.idclientes SET estado = 2 WHERE NOW() >= asignaciones.fecha_vencimiento";
	$result = $con->query($query);
	//Clientes inactivos
	$query = "UPDATE clientes SET estado = 0 WHERE estado BETWEEN 1 AND 2 AND NOW() >= (DATE_ADD(ult_ingreso, INTERVAL 35 DAY))";
	$result = $con->query($query);
	//Clientes que abandonaron
	$query="UPDATE clientes SET estado = 3 WHERE estado = 0 AND NOW() >= (DATE_ADD(ult_ingreso, INTERVAL 6 MONTH))";
	$result = $con->query($query);	
	//Nota credito
	$query="UPDATE clientes SET notacredito = 0 WHERE NOW() >= (DATE_ADD(ult_ingreso, INTERVAL 45 DAY))";
	$result=$con->query($query);
	$permisos = $_SESSION['tipo'];  
	if ($permisos == 1) {
		header("location:admin/index.php");
	} else if ($permisos == 2) {
		header("location:tutor/index.php");
	} else {
		header("location:../index.php");
	}
} else {
	print "<html><head><script>alert('Usuario o Contraseña Invalidos');</script><meta http-equiv='refresh' content='0; url=../index.php'></head></html>";
} 
mysqli_close($con);
?>
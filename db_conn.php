<?php
$HOST = "localhost";
$USER_DB = "root";
$PASS_DB = "";
$DB = "dbgym";
$con = mysqli_connect($HOST, $USER_DB, $PASS_DB, $DB);
if (!$con) {
  echo "Error: No se pudo conectar a MySQL." . PHP_EOL . "<br>";
  echo "Errno de depuración: " . mysqli_connect_errno() . PHP_EOL ."<br>";
  echo "Error de depuración: " . mysqli_connect_error() . PHP_EOL ."<br>";
  exit();
} 
function check_login() {
	if($_SESSION['autenticado']==false){
		header('location:../../index.php');
	} else {
		header('panel/admin/index.php');
	}
} 
function fechaCastellano ($fecha) {
  $fecha = substr($fecha, 0, 10);
  $numeroDia = date('d', strtotime($fecha));
  $dia = date('l', strtotime($fecha));
  $mes = date('F', strtotime($fecha));
  $anio = date('Y', strtotime($fecha));
  $dias_ES = array("Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado", "Domingo");
  $dias_EN = array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday");
  $nombredia = str_replace($dias_EN, $dias_ES, $dia);
  $meses_ES = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
  $meses_EN = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
  $nombreMes = str_replace($meses_EN, $meses_ES, $mes);
  return $numeroDia." de ".$nombreMes." de ".$anio; 
}
function dinero($x){
	return number_format($x, 0, ',', '.');
}
?>